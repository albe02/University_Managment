<?php
session_start();

// Verifica se l'utente è connesso
if (!isset($_SESSION['username']) || $_SESSION['tipo'] != 'segreteria') {
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

      <div id="rigaContainer" class="row">
          <div id="containerCard" class="container">
            <div id="riga1" class="row m-auto mb-3">
                <div id="modificaStud" class="col-4">
                    <div class="card h-100 bg-custom2" style="width: 18rem;">
                        <div class="card-body">
                            <h5 class="card-title">Modifica Studenti</h5>
                            <a href="modificaStudenti.php" class="card-link"><button class="btn btn-custom">CLICK HERE</button></a>
                        </div>
                    </div>
                </div>
                <div id="credenzialiStud" class="col-4">
                    <div class="card h-100 bg-custom2" style="width: 18rem;">
                        <div class="card-body">
                            <h5 class="card-title">Modifica Credenziali Studenti</h5>
                            <a href="modificaCredStud.php" class="card-link"><button class="btn btn-custom">CLICK HERE</button></a>
                        </div>
                    </div>
                </div>
                <div id="modificaIns" class="col-4">
                    <div class="card h-100 bg-custom2" style="width: 18rem;">
                        <div class="card-body">
                            <h5 class="card-title">Modifica Insegnamenti</h5>
                            <a href="modificaInsegnamento.php" class="card-link"><button class="btn btn-custom">CLICK HERE</button></a>
                        </div>
                    </div>
                </div>
            </div>
            <div id="riga2" class="row m-auto mb-3">
                <div id="modificaDoc" class="col-4">
                    <div class="card h-100 bg-custom2" style="width: 18rem;">
                        <div class="card-body">
                            <h5 class="card-title">Modifica Docenti</h5>
                            <a href="modificaDocenti.php" class="card-link"><button class="btn btn-custom">CLICK HERE</button></a>
                        </div>
                    </div>
                </div>
                <div id="credenzialiDoc" class="col-4">
                    <div class="card h-100 bg-custom2" style="width: 18rem;">
                        <div class="card-body">
                            <h5 class="card-title">Modifica credenziali Docenti</h5>
                            <a href="modificaCredDoc.php" class="card-link"><button class="btn btn-custom">CLICK HERE</button></a>
                        </div>
                    </div>
                </div>
                <div id="modificaCDL" class="col-4">
                    <div class="card h-100 bg-custom2" style="width: 18rem;">
                        <div class="card-body">
                            <h5 class="card-title">Modifica Corsi di Laurea</h5>
                            <a href="modificaCorso.php" class="card-link"><button class="btn btn-custom">CLICK HERE</button></a>
                        </div>
                    </div>
                </div>
            </div>
            <div id="riga3" class="row m-auto mb-3">
                <div id="carrieraCompl" class="col-4">
                    <div class="card h-100 bg-custom2" style="width: 18rem;">
                        <div class="card-body">
                            <h5 class="card-title">Visualizza Carriera Completa Studente</h5>
                            <a href="carrieraCompleta.php" class="card-link"><button class="btn btn-custom">CLICK HERE</button></a>
                        </div>
                    </div>
                </div>
                <div id="modificaSeg" class="col-4">
                    <div class="card h-100 bg-custom2" style="width: 18rem;">
                        <div class="card-body">
                            <h5 class="card-title">Modifica Segreteria</h5>
                            <a href="modificaSegreteria.php" class="card-link"><button class="btn btn-custom">CLICK HERE</button></a>
                        </div>
                    </div>
                </div>
                <div id="carrieraValid" class="col-4">
                    <div class="card h-100 bg-custom2" style="width: 18rem;">
                        <div class="card-body">
                            <h5 class="card-title">Visualizza Carriera Valida Studente</h5>
                            <a href="carrieraValida.php" class="card-link"><button class="btn btn-custom">CLICK HERE</button></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
      </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous"></script>
  </body>
</html>