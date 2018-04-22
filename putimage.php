
<?php
//Funzioni utili:
error_reporting(E_ALL);
ini_set('display_errors', 1);
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'lib/PHPMailer/src/Exception.php';
require 'lib/PHPMailer/src/PHPMailer.php';
require 'lib/PHPMailer/src/SMTP.php';

function generateRandomString($length = 32) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}




//variabili

$originalname =  $_FILES['immagine']['name'];
$ext = pathinfo($originalname, PATHINFO_EXTENSION);
$time = time();
$filename = sha1_file($_FILES['immagine']['tmp_name'])."-".$time.".".$ext;
//upload dei file

try {
    
    // Undefined | Multiple Files | $_FILES Corruption Attack
    // If this request falls under any of them, treat it invalid.
    if (
        !isset($_FILES['immagine']['error']) ||
        is_array($_FILES['immagine']['error'])
    ) {
        throw new RuntimeException('Invalid parameters.');
    }

    // Check $_FILES['immagine']['error'] value.
    switch ($_FILES['immagine']['error']) {
        case UPLOAD_ERR_OK:
            break;
        case UPLOAD_ERR_NO_FILE:
            throw new RuntimeException('No file sent.');
        case UPLOAD_ERR_INI_SIZE:
        case UPLOAD_ERR_FORM_SIZE:
            throw new RuntimeException('Exceeded filesize limit.');
        default:
            throw new RuntimeException('Unknown errors.');
    }

    // You should also check filesize here. 
    if ($_FILES['immagine']['size'] > 10000000000000) {
        throw new RuntimeException('Exceeded filesize limit.');
    }

    // DO NOT TRUST $_FILES['upfile']['mime'] VALUE !!
    // Check MIME Type by yourself.
    $finfo = new finfo(FILEINFO_MIME_TYPE);
    if (false === $ext = array_search(
        $finfo->file($_FILES['immagine']['tmp_name']),
        array(
            'jpg' => 'image/jpeg',
            'png' => 'image/png',
            'gif' => 'image/gif',
        ),
        true
    )) {
        throw new RuntimeException('Invalid file format.');
    }

    // You should name it uniquely.
    // DO NOT USE $_FILES['upfile']['name'] WITHOUT ANY VALIDATION !!
    // On this example, obtain safe unique name from its binary data.
    move_uploaded_file(
        $_FILES['immagine']['tmp_name'],
        sprintf('./immaginioriginali/%s',
            $filename
        )
    );


} catch (RuntimeException $e) {
    header('Content-Type: text/plain; charset=utf-8');

    echo $e->getMessage();

    exit;

}

define('DESIRED_IMAGE_WIDTH', 1200);
define('DESIRED_IMAGE_HEIGHT', 1200);

$source_path = "immaginioriginali/".$filename;
$destination_path = "immagini/".$filename;
$stamp_gdim = imagecreatefrompng('./img/cornice.png');
imageAlphaBlending($stamp_gdim, true);
imageSaveAlpha($stamp_gdim, true);

/*
 * Add file validation code here
 */

list($source_width, $source_height, $source_type) = getimagesize($source_path);

switch ($source_type) {
    case IMAGETYPE_GIF:
        $source_gdim = imagecreatefromgif($source_path);
        break;
    case IMAGETYPE_JPEG:
        $source_gdim = imagecreatefromjpeg($source_path);
        break;
    case IMAGETYPE_PNG:
        $source_gdim = imagecreatefrompng($source_path);
        break;
}

$source_aspect_ratio = $source_width / $source_height;
$desired_aspect_ratio = DESIRED_IMAGE_WIDTH / DESIRED_IMAGE_HEIGHT;

if ($source_aspect_ratio > $desired_aspect_ratio) {
    /*
     * Triggered when source image is wider
     */
    $temp_height = DESIRED_IMAGE_HEIGHT;
    $temp_width = ( int ) (DESIRED_IMAGE_HEIGHT * $source_aspect_ratio);
} else {
    /*
     * Triggered otherwise (i.e. source image is similar or taller)
     */
    $temp_width = DESIRED_IMAGE_WIDTH;
    $temp_height = ( int ) (DESIRED_IMAGE_WIDTH / $source_aspect_ratio);
}

/*
 * Resize the image into a temporary GD image
 */

$temp_gdim = imagecreatetruecolor($temp_width, $temp_height);
imagecopyresampled(
    $temp_gdim,
    $source_gdim,
    0, 0,
    0, 0,
    $temp_width, $temp_height,
    $source_width, $source_height
);

/*
 * Copy cropped region from temporary image into the desired GD image
 */

$x0 = ($temp_width - DESIRED_IMAGE_WIDTH) / 2;
$y0 = ($temp_height - DESIRED_IMAGE_HEIGHT) / 2;
$desired_gdim = imagecreatetruecolor(DESIRED_IMAGE_WIDTH, DESIRED_IMAGE_HEIGHT);
imagecopy(
    $desired_gdim,
    $temp_gdim,
    0, 0,
    $x0, $y0,
    DESIRED_IMAGE_WIDTH, DESIRED_IMAGE_HEIGHT
);


imagecopy(
    $desired_gdim,
    $stamp_gdim,
    0, 0,
    0, 0,
    DESIRED_IMAGE_WIDTH, DESIRED_IMAGE_HEIGHT
);


imagejpeg($desired_gdim, $destination_path);


/*
 * Render the image
 * Alternatively, you can save the image in file-system or database
 */

//header('Content-type: image/jpeg');
//imagejpeg($desired_gdim);

/*
 * inizio lavoro su db
 */

$db = new SQLite3('RS.db');
if(!$db)
{
    die("Errore Sqlite: ");
}

if ($db->query('CREATE TABLE IF NOT EXISTS immagini (id INTEGER PRIMARY KEY, gruppo TEXT, descrizione TEXT, nomefile TEXT, chiave TEXT, stato INTEGER, Timestamp DATETIME DEFAULT CURRENT_TIMESTAMP)')) {
    include("confirm.php"); // Works
} else {
    include("error.php"); // Will also work
}

/*
crea tabella con nome - commenti (filtrati sql) - sha1 foto - stato

imposta stato a "pendente" e manda mail con link con cui approvare la foto o declinarla
*/
$gruppo = $_POST['gruppo'];
$descrizione = $_POST['descrizione'];
$chiave=generateRandomString()."-".time();

if ($db->query("INSERT INTO immagini (gruppo, descrizione, nomefile, chiave, stato) VALUES ('$gruppo','$descrizione','$filename','$chiave',0)")) {

echo "<html>";
echo "<head>";
echo "<meta charset='utf-8'>";
echo "<meta name='viewport' content='width=device-width, initial-scale=1, shrink-to-fit=no'>";
echo "<link rel='stylesheet' href='https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css' integrity='sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm' crossorigin='anonymous'>"; 
echo "<link rel='stylesheet' href='css/style.css' >"; 
echo "</head>";
echo "<body>"; 
echo "<div class='container-fluid'>";
echo "<div class='row justify-content-center'>";
echo "<div class='col-md-6'>";
echo "<br>";
echo "<img src='http://localhost/rover100/immagini/$filename' class='img-fluid'>"; // Works
echo "</div>";
echo "</div>";
echo "</div>";
echo "</div>";
echo "</body";
echo "</html>";



} else {
    include("error.php"); // Will also work
}

//INVIO EMAIL <IMAP:></IMAP:>

$htmlmessage = "

<img src='cid:immagine'>

";

$mail = new PHPMailer(true);                              // Passing `true` enables exceptions
try {
    //Server settings
    $mail->SMTPDebug = 2;                                 // Enable verbose debug output
    $mail->isSMTP();                                      // Set mailer to use SMTP
    $mail->Host = 'smtp.mailgun.org';  // Specify main and backup SMTP servers
    $mail->SMTPAuth = true;                               // Enable SMTP authentication
    $mail->Username = 'postmaster@mail.canzoniereonline.it';                 // SMTP username
    $mail->Password = '575850eb3db71c519afa5ba04756ec54';                           // SMTP password
    $mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
    $mail->Port = 587;                                    // TCP port to connect to

    //Recipients
    $mail->setFrom('from@example.com', 'Mailer');
    $mail->addAddress('concimatteo@gmail.com');               // Name is optional

    //Attachments
    $mail->addAttachment("immagini/$filename",'immagine');         // Add attachments

    //Content
    $mail->isHTML(true);                                  // Set email format to HTML
    $mail->Subject = 'Here is the subject';
    $mail->Body    = $htmlmessage;

    $mail->send();
    echo 'Message has been sent';
} catch (Exception $e) {
    echo 'Message could not be sent. Mailer Error: ', $mail->ErrorInfo;
}

?>
