<?php
session_start();
if (!isset($_SESSION['loggedin']) || !$_SESSION['loggedin']) {
     header('Location: login.php');
     exit;
}
?>
<html>
<head>

<script   src="https://code.jquery.com/jquery-3.3.1.min.js"   integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8="   crossorigin="anonymous"></script>
<link rel="stylesheet" type="text/css" href="//cdn.datatables.net/1.10.16/css/jquery.dataTables.min.css">
<script   src="//cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>


</head>
<body>
<h1>Tabella di amministrazione</h1>
<table id="tabella">
    <thead>
        <tr>
        <th>Data</th>
        <th>Gruppo</th>
        <th>Immagine</th>
        <th>Stato</th>
        <th>Azioni</th>
        </tr>
    </thead>
    <tbody>
<?php
$db = new SQLite3('./../RS.db');
if(!$db)
{
    die("Errore Sqlite: ");
}

$results = $db->query('SELECT * FROM immagini');
while ($row = $results->fetchArray()) {
 $timestamp = $row['timestamp'];
 $gruppo = $row['gruppo'];
 $descrizione = $row['descrizione'];
 $nomefile = $row['nomefile'];
 $chiave = $row['chiave'];
 $statoN = $row['stato'];
 

 if ($statoN == 0){
     $stato = "In attesa";
 } else if ($statoN == 1){
     $stato = "Approvato";
 } elseif ($statoN == 2){ 
     $stato = "Declinato";
 } else {
     $stato = $statoN;
 }


 echo "<tr>";
 echo "<td>".$timestamp."</td>";
 echo "<td>".$gruppo."</td>";
 echo "<td><a href='viewimage.php?nomefile=$nomefile'>guarda immagine</a></td>";
 echo "<td>".$stato."</td>";
 echo "<td><a href='../changestatus.php?nomefile=$nomefile&chiave=$chiave&stato=1'>Approva<a> | <a href='../changestatus.php?nomefile=$nomefile&chiave=$chiave&stato=2'>Declina<a> | <a href='../changestatus.php?nomefile=$nomefile&chiave=$chiave&stato=0'>Metti in attesa<a></td>";
 echo "</tr>";
}






?>
    </tbody>
    <tfoot>
        <tr>
        <th>Data</th>
        <th>Gruppo</th>
        <th>Immagine</th>
        <th>Stato</th>
        <th>Azioni</th>
        </tr>
    <tfoot>
</table>


<script>
$(document).ready( function () {
    $('#tabella').DataTable();
} );
</script>


</body>
</html>