<?php
//Funzioni utili:
error_reporting(E_ALL);
ini_set('display_errors', 1);


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

define('DESIRED_IMAGE_WIDTH', 1000);
define('DESIRED_IMAGE_HEIGHT', 666);

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

if ($db->query('CREATE TABLE IF NOT EXISTS immagini (id INTEGER PRIMARY KEY, gruppo TEXT, descrizione TEXT, nomefile TEXT, chiave TEXT, stato INTEGER)')) {
    echo "Query successful."; // Works
} else {
    echo "Query failed."; // Will also work
}

/*
crea tabella con nome - commenti (filtrati sql) - sha1 foto - stato

imposta stato a "pendente" e manda mail con link con cui approvare la foto o declinarla
*/
$gruppo = $_POST['gruppo'];
$descrizione = $_POST['descrizione'];
$chiave=generateRandomString()."-".time();
echo $chiave;

if ($db->query("INSERT INTO immagini (gruppo, descrizione, nomefile, chiave, stato) VALUES ('$gruppo','$descrizione','$filename','$chiave',0)")) {
echo "Query successful."; // Works
} else {
    echo "Query failed."; // Will also work
}

//INVIO EMAIL!

$bound_text = "----*%$!$%*";
$bound = "--".$bound_text."\r\n";
$bound_last = "--".$bound_text."--\r\n";

$headers = "From: youremail@host.com\r\n";
$headers .= "MIME-Version: 1.0\r\n" .
"Content-Type: multipart/mixed; boundary=\"$bound_text\""."\r\n" ;

$message = " you may wish to enable your email program to accept HTML \r\n".
$bound;

$message .=
'Content-Type: text/html; charset=UTF-8'."\r\n".
'Content-Transfer-Encoding: 7bit'."\r\n\r\n".
"

<html>

<head>
<style> p {color:green} </style>
</head>
<body>

A line above
<br>
<img src='cid:http://localhost/rs/immagini/$filename'>
<br>
a line below
</body>

</html>"."\n\n".
$bound;

$file = file_get_contents("http://localhost/rs/immagini/$filename");

$message .= "Content-Type: image/jpeg; name=\"http://localhost/rs/immagini/$filename\"\r\n"
."Content-Transfer-Encoding: base64\r\n"
."Content-ID: <http://localhost/rs/immagini/$filename>\r\n"
."\r\n"
.chunk_split(base64_encode($file))
.$bound_last;

echo mail("concimatteo@gmail.com", "Modera!", $message, $headers) ;








?>