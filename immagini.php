<html>
<head>

<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
<script src="https://unpkg.com/masonry-layout@4/dist/masonry.pkgd.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
<style>   
.grid-item { width: 300px; margin-bottom:15px; }
</style>

<!-- Bootstrap CSS -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
<link rel="stylesheet" href="css/style.css">

</head>

<body style="background-image: url(img/body-background.png);" class="body-background">


<!-- Header --> 
<header style="background-image: url(img/header-watercolor.png)" class="background-header">
            <div class="container-fluid">
                <div class="row justify-content-center">
                    <!-- Visible on MD --> 
                    <div class="col-3 d-none d-sm-block"> <img src="./img/rovers100-watercolor-spot.png" class="img-fluid rounded header-image" alt="Responsive image"></div>
                    <div class="col-6 align-self-center align-content-center d-none d-sm-block"> <img src="./img/centenario-roversimo-title-white.png" class="img-fluid title-image" alt="Responsive image"></div>
                    <div class="col-3 d-none d-sm-block"> <img src="./img/fis-watercolor-spot.png" class="img-fluid rounded header-image float-right" alt="Responsive image"></div>
                    <!-- Visible on XS -->
                    <div class="col-12 align-self-center align-content-center d-block d-sm-none"> <img src="./img/centenario-roversimo-title-white.png" class="img-fluid title-image-XS" alt="Responsive image"></div>
                    <div class="col-6 align-self-center align-content-center d-block d-sm-none"> <img src="./img/rovers100-watercolor-spot.png" class="img-fluid rounded header-image" alt="Responsive image"></div>
                    <div class="col-6 align-self-center align-content-center d-block d-sm-none"> <img src="./img/fis-watercolor-spot.png" class="img-fluid rounded header-image float-right" alt="Responsive image"></div>
                </div>
                
            </div>
 </header>

<!-- Corpo --> 
<div class="container-fluid">
<div class="row justify-content-center">
<div class="col-md-10 col-xs-12">

<article>
<div class="card-columns">

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

   
    echo "<div class='card'>";
    echo "<img src='immagini/$nomefile'  class='card-img-top' type='button' data-toggle='modal' data-target='#ModalImmagine'></img>";
    echo "<div class='card-body'>";
    echo "<h5 class='card-title'>$gruppo</h5>";
    echo "<p class='card-text'>$descrizione</p>";
    echo "</div>";
    echo "</div>";

    }
    ?>

</div>

<div class="modal fade" id="ModalImmagine" tabindex="-1" role="dialog" aria-labelledby="ModalImmagineTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="ModalImmagineTitle"><?php echo $gruppo;?></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
          <?php echo "<img src='immagini/$nomefile' class='col-12'"; ?>
      </div>
      <div class="modal-footer">
      <?php echo $descrizione;?>
      </div>
    </div>
  </div>
</div>

</article>


</div>
</div>
</div>

<!-- Footer -->
<footer style="background-image: url(img/footer-watercolor.png)" class="background-footer">
    <div class="container-fluid">
        <div class="row justify-content-center">
          <div class="col-md-6 col-xs-12"><p class="text-white text-center footer-alignement"></p></div>
        </div>
    </div>
</footer>


<script>
$('.grid').masonry({
  // options
  itemSelector: '.grid-item',
  columnWidth: 300
});
</script>


</body>
</html>