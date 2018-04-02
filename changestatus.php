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