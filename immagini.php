<html>
<head>

<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
<script src="https://unpkg.com/masonry-layout@4/dist/masonry.pkgd.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
<script async src="https://static.addtoany.com/menu/page.js"></script>




<style>.grid-item { width: 300px; margin-bottom:15px; }</style>

<!-- Bootstrap CSS -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
<link rel="stylesheet" href="css/style.css">

<!-- Share Script-->
</head>

<body style="background-image: url(img/body-background.png);" class="body-background-home">


<!-- Header --> 
<header style="background-image: url(img/header-watercolor.png)" class="background-header">
            <div class="container-fluid">
                <div class="row justify-content-center">
                    <!-- Visible on MD --> 
                    <div class="col-3 d-none d-sm-block"> <a href="https://www.scout.org/rovers100"><img src="./img/rovers100-watercolor-spot.png" class="img-fluid rounded header-image" alt="Responsive image" ></a></div>
                    <div class="col-6 align-self-center align-content-center d-none d-sm-block"> <a href="home.html"><img src="./img/centenario-roversimo-title-white.png" class="img-fluid title-image" alt="Responsive image"></a></div>
                    <div class="col-3 d-none d-sm-block"> <a href="http://www.scouteguide.it/"><img src="./img/fis-watercolor-spot.png" class="img-fluid rounded header-image float-right" alt="Responsive image"></a></div>
                    <!-- Visible on XS -->
                    <div class="col-12 align-self-center align-content-center d-block d-sm-none"> <a href="https://www.scout.org/rovers100"> <img src="./img/centenario-roversimo-title-white.png" class="img-fluid title-image-XS" alt="Responsive image"></a></div>
                    <div class="col-6 align-self-center align-content-center d-block d-sm-none"> <a href="home.html"><img src="./img/rovers100-watercolor-spot.png" class="img-fluid rounded header-image" alt="Responsive image"></a></div>
                    <div class="col-6 align-self-center align-content-center d-block d-sm-none"> <a href="http://www.scouteguide.it/"><img src="./img/fis-watercolor-spot.png" class="img-fluid rounded header-image float-right" alt="Responsive image"></a></div>
                </div>
                
            </div>
        </header>

<!-- Corpo --> 
<div class="container-fluid">

<div class="row row-immagini-centenario"></div>

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
    $chiave = $row['chiave'];
    $idmodal = substr($nomefile, 0, -4);

    echo "<div class='card'>";
    echo "<img src='immagini/$nomefile'  class='card-img-top' type='button' data-toggle='modal' data-target='#$idmodal'></img>";
    echo "<div class='card-body'>";
    echo "<h5 class='card-title'>$gruppo</h5>";
    echo "<p class='card-text'>$descrizione</p>";
    echo "</div>";
    echo "</div>";


    echo "<div class='modal fade' id='$idmodal' tabindex='-1' role='dialog' aria-labelledby='ModalImmagineTitle' aria-hidden='true'>";
      echo "<div class='modal-dialog modal-dialog-centered modal-lg' role='document'>";
        echo "<div class='modal-content'>";
          echo "<div class='modal-header'>";
            echo "<h5 class='modal-title' id='ModalImmagineTitle'>$gruppo</h5>";
              echo "<button type='button' class='close' data-dismiss='modal' aria-label='Close'>";
              echo "<span aria-hidden='true'>&times;</span>";
              echo "</button>";
          echo "</div>";
          echo "<div class='modal-body'>";
            echo "<img src='immagini/$nomefile' class='col-12'";
          echo "</div>";
          echo "<div class='modal-footer'>";
            echo "<p>$descrizione</p>";
          echo "</div>";
          echo "<div class='container-fluid'><div class='row'><div class='col'>";
          echo "<div class='a2a_kit a2a_kit_size_50 a2a_default_style' data-a2a-url='localhost/rover100/immagini.php?img=$nomefile' data-a2a-title='Centenario dello Scoutismo' data-a2a-img='immagini/$nomefile'>
          <a class='a2a_button_facebook'></a>
          <a class='a2a_button_twitter'></a>
          <a class='a2a_button_google_plus'></a>
          </div>";
          echo "</div></div></div>";
        echo "</div>";
       echo "</div>";
      echo "</div>";
    echo "</div>";
  
  
  // Verifica la presenza di url
  
  if ($_GET['img'] != "" ){
    $show = 'show';
    } else {
      $show = '';
    }  

  }

  // Lancia il Modal
  $idauto = substr($_GET['img'], 0, -4);  
  echo "<script type='text/javascript'>$(window).on('load',function(){
  $('#$idauto').modal('$show');
  });</script>";

  
    ?>

    

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

<!-- Funzioni -->



<script>
$('.grid').masonry({
  // options
  itemSelector: '.grid-item',
  columnWidth: 300
});
</script>


</body>
</html>