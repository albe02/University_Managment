<?php
session_start();

// Verifica se l'utente è connesso
if (!isset($_SESSION['username']) || $_SESSION['tipo'] != 'docente') {
    header('Location: login.php'); // Se non è connesso, reindirizza alla pagina di login
    exit();
}
?>

<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Università</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="stileBase.css">
  </head>
  <body>
    <div id="containerEsterno" class="container-fluid">
      <div class="row">
      <?php include("navbar.php");?>
      </div>

      <div id="rigaContainer" class="container-fluid">
        <div id="containerCard" class="row m-auto">
            <div id="colonna1" class="col-6 m-auto">
                <div class="card h-100 bg-custom2 m-auto" style="width: 18rem;">
                    <div class="card-body">
                        <h5 class="card-title">Gestisci Calendario Esami</h5>
                        <a href="esame.php" class="card-link"><button class="btn btn-custom">CLICK HERE</button></a>
                    </div>
                </div>
            </div>
            <div id="colonna2" class="col-6 m-auto">
                <div class="card h-100 bg-custom2 m-auto" style="width: 18rem;">
                    <div class="card-body">
                        <h5 class="card-title">Registra Esiti Esami</h5>
                        <a href="voti.php" class="card-link"><button class="btn btn-custom">CLICK HERE</button></a>
                    </div>
                </div>
            </div>
        </div>
      </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous"></script>
  </body>
</html>