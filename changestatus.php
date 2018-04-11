<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">

    <title>Rover100</title>
  </head>
  <body>
    <div class="container-fluid">
    <h1>Cambio stato dell'immagine</h1>

    

<?php

/*
Prende da GET ID della foto, chiave di controllo e stato desiderato

fa una query su id foto e chiave

se corretta, modifica lo stato

*/

$stato = $_GET['stato'];
$nomefile = $_GET['nomefile'];
$chiave = $_GET['chiave'];


$db = new SQLite3('RS.db');
if(!$db)
{
    die("Errore Sqlite: ");
}

$query = "UPDATE immagini SET stato = $stato WHERE nomefile == '$nomefile' AND chiave == '$chiave'";

if ($db->query($query)) {
    echo "Stato immagine aggiornato"; // Works
    echo "<br><a href='amministrazione/'>Torna all'amministrazione</a>";
} else {
    echo "Qualcosa è andato storto..."; // Will also work
}

?>

</div>

</body>
</html>