<html>
<head>

<script src="https://code.jquery.com/jquery-3.3.1.min.js"   integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8="   crossorigin="anonymous"></script>
<script src="https://unpkg.com/masonry-layout@4/dist/masonry.pkgd.js"></script>
<style>
.grid-item { width: 200px; }

</style>
<head>
<body>
<header>
<h1>Un po' di header</h1>
</header>

<article>
<div class="grid">

<?php

$db = new SQLite3('RS.db');
if(!$db)
{
    die("Errore Sqlite: ");
}

$results = $db->query('SELECT * FROM immagini WHERE stato=1');

while ($row = $results->fetchArray()) {
    $gruppo = $row['gruppo'];
    $descrizione = $row['descrizione'];
    $nomefile = $row['nomefile'];
   
   
    echo "<div class='grid-item'>";
    echo "<img src='immagini/$nomefile' width='190' aligh='center'></img>";
    echo "<p>$gruppo</p>";
    echo "<p>$descrizione</p>";
    echo "</div>";
}

?>

</div>


</article>

<footer>
<h4>piè di pagina...</h4>
</footer>


<script>

$('.grid').masonry({
  // options
  itemSelector: '.grid-item',
  columnWidth: 200
});

</script>
</body>
</html>