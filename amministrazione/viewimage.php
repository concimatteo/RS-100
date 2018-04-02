<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);


session_start();
if (!isset($_SESSION['loggedin']) || !$_SESSION['loggedin']) {
     header('Location: login.php');
     exit;
}
?>
<html>
<head>

</head>

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
 echo "<p><b>Groppo: </b>$gruppo</p>";
 echo "<p><b>Descrizione: </b>$descrizione</p>";
 echo "<IMG src='../immagini/$nomefile'></IMG>";
 echo "<h4>Modifica Stato</h4>";
 echo "<a href='../changestatus.php?nomefile=$nomefile&chiave=$chiave&stato=1'>Approva<a> | <a href='../changestatus.php?nomefile=$nomefile&chiave=$chiave&stato=2'>Declina<a> | <a href='../changestatus.php?nomefile=$nomefile&chiave=$chiave&stato=0'>Metti in attesa<a>";

}






?>
</body>
</html>