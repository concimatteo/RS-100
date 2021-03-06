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
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>

<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">

</head>
<body>
<div class="container-fluid">

<h1>Tabella di amministrazione</h1>

<?php 
$db = new SQLite3('./../RS.db');
if(!$db)
{
    die("Errore Sqlite: ");
}
$approvate=0;
$inattesa=0;
$declinate=0;

$results = $db->query('SELECT * FROM immagini');
while ($row = $results->fetchArray()) {

 $statoN = $row['stato'];
 

 if ($statoN == 0){
     $inattesa +=1;
 } else if ($statoN == 1){
     $approvate +=1;
 } elseif ($statoN == 2){ 
     $declinate +=1;
 } else {
 }
}

echo "In attesa: <b> $inattesa </b> | ";
echo "Approvate: <b> $approvate </b> | ";
echo "Declinate: <b> $declinate </b> ";

?>

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
 echo "<td><a href='viewimage.php?nomefile=$nomefile'>Vedi immagine</a></td>";
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

</div>
<script>
$(document).ready( function () {
    $('#tabella').DataTable({
        "order": [[ 3, "desc" ],[ 0, "desc" ]],
        "pageLength": 50,
        "lengthMenu": [[25, 50, 100, -1], [25, 50, 100, "All"]]
    });
} );
</script>


</body>
</html>