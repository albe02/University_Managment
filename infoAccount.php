<?php
session_start();

// Verifica se l'utente è connesso
if (!isset($_SESSION['username'])) {
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
            <div id="containerCard" class="row">
                <div id="colonna1" class="col-4">
                    <div class="card h-100 bg-custom2" style="width: 45rem;">
                        <div class="card-body">
                            <?php                                
                                $conn = pg_connect("host=localhost user=postgres password=FiatPanda8 dbname=Esame_Basi_Di_Dati")
                                                    or die('Could not connect: ' . pg_last_error());
                                $tipo = $_SESSION['tipo'];
                                $user = $_SESSION['username'];
                                
                                switch ($tipo){
                                    case "segreteria":
                                        $query="SELECT s.id, s.nome, s.cognome, s.email, s.data_di_nascita, s.telefono, s.via, s.civico, i.citta, s.cap FROM universita.segreteria s INNER JOIN universita.indirizzo i ON s.cap=i.cap WHERE s.email = $1";
                                        break;
                                    case "studente":
                                        $query="SELECT s.matricola as id, s.nome, s.cognome, s.email, s.data_di_nascita, s.telefono, s.via, s.civico, i.citta, s.cap FROM universita.studente s INNER JOIN universita.indirizzo i ON s.cap=i.cap WHERE s.email = $1";
                                        break;
                                    case "docente":
                                        $query="SELECT d.id, d.nome, d.cognome, d.email, d.data_di_nascita, d.telefono, d.via, d.civico, i.citta, d.cap FROM universita.docente d INNER JOIN universita.indirizzo i ON d.cap=i.cap WHERE d.email = $1";
                                        break;
                                }

                                $parametri = array(
                                    $user
                                );

                                $result = pg_prepare($conn, "corsi", $query);
                                $result = pg_execute($conn, "corsi", $parametri);

                                $row = pg_fetch_assoc($result);
                                $id = $row['id'];
                                $nome = $row['nome'];
                                $cognome = $row['cognome'];
                                $email = $row['email'];
                                $data = $row['data_di_nascita'];
                                $telefono = $row['telefono'];
                                $via = $row['via'];
                                $civico = $row['civico'];
                                $citta = $row['citta'];
                                $cap = $row['cap'];

                                echo '<h1>'. $id . '</h1>';
                                echo '<h2>' . $nome . ' '. $cognome .' Data di Nascita: '. $data .'</h2>';
                                echo '<h3> Email: ' . $email . ' Telefono: ' . $telefono . '</h3>';
                                echo '<h4> Indirizzo: ' . $via . ' '. $civico . ' ' . $citta . ' ' . $cap . '</h4>';   
                                

                                pg_close($conn);
                            ?>
                        </div>
                    </div>
                </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous"></script>
  </body>
</html>
