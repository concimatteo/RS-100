
<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);


session_start();
if (!isset($_SESSION['loggedin']) || !$_SESSION['loggedin']) {
     header('Location: login.php');
     exit;
}
?>


<html lang="it">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">

    <title>Rover100:Vedi Immagine</title>
  </head>
  <body>
   
   <div class="container-fluid">

<?php
$db = new SQLite3('./../RS.db');
if(!$db)
{
    die("Errore Sqlite: ");
}
$nomefile = $_GET['nomefile'];

$results = $db->query("SELECT * FROM immagini WHERE nomefile = '$nomefile'");

while ($row = $results->fetchArray()) {

 $timestamp = $row['timestamp'];
 $gruppo = $row['gruppo'];
 $descrizione = $row['descrizione'];
 $nomefile = $row['nomefile'];
 $chiave = $row['chiave'];
 
 echo "<h1>Dati immagine</h1>";
 echo "<p><b>Gruppo: </b>$gruppo</p>";
 echo "<p><b>Descrizione: </b>$descrizione</p>";
 echo "<h4 style='margin-top:15px;'>Modifica Stato</h4>";
 echo "<a href='../changestatus.php?nomefile=$nomefile&chiave=$chiave&stato=1'>Approva<a> | <a href='../changestatus.php?nomefile=$nomefile&chiave=$chiave&stato=2'>Declina<a> | <a href='../changestatus.php?nomefile=$nomefile&chiave=$chiave&stato=0'>Metti in attesa<a>";
 echo "<img src='../immagini/$nomefile' style='margin-top:15px;' class='img-fluid col-10'></img>";
 
}






?>

</div>
</body>
</html>