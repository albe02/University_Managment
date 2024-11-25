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
            <div id="containerCard" class="row m-auto">
                <div id="colonna1" class="col-6 m-auto">
                    <div class="card h-100 bg-custom2" style="width: 40rem;">
                        <div class="card-body">
                            <h5 class="card-title">Modifica Password</h5>
                            <form action="modificaPassword.php" method="POST">

                                <div id="vecchiaPsw" class="row m-auto">
                                    <input name="old" type="password" class="form-control custom-bg" id="InputOld" aria-describedby="oldHelp" placeholder="Vecchia Password">
                                </div>
                                
                                <div id="nuovaPsw" class="row m-auto mt-2">
                                    <input name="new" type="password" class="form-control custom-bg" id="Inputnew" aria-describedby="newHelp" placeholder="Nuova Password">
                                </div>

                                <div id="ripeti" class="row m-auto mt-2">
                                    <input name="reNew" type="password" class="form-control custom-bg" id="InputPsw" aria-describedby="pswHelp" placeholder="Ripeti la nuova Password">
                                </div>
                                
                                <div id="rec" class="row m-auto mt-2">
                                    <button name="submit" type="submit" class="btn btn-custom">Salva</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous"></script>
  </body>
</html>
<?php

    $conn = pg_connect("host=localhost user=postgres password=FiatPanda8 dbname=Esame_Basi_Di_Dati ")
    or die('Could not connect: ' . pg_last_error());

    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit'])) {
        $oldP = $_POST['old'];
        $newP = $_POST['new'];
        $reNewP = $_POST['reNew'];
        $tipo= $_SESSION['tipo'];
        $user= $_SESSION['username'];

        if($newP == $reNewP){
            switch ($tipo){
                case "segreteria":
                    $query="SELECT segreteria.password FROM \"universita\".\"segreteria\" WHERE email = $1";
                    break;
                case "studente":
                    $query="SELECT studente.password FROM \"universita\".\"studente\" WHERE email = $1";
                    break;
                case "docente":
                    $query="SELECT docente.password FROM \"universita\".\"docente\" WHERE email = $1";
                    break;
            }
            
            $parametri = Array(
                $user
            );

            $result = pg_prepare($conn, "selezione_utente", $query);
            $result = pg_execute($conn, "selezione_utente", $parametri);

            $row = pg_fetch_assoc($result);
            $password = $row['password'];

            $oldP=md5($oldP);

            if($password == $oldP){
                switch ($tipo){
                    case "segreteria":
                        $query2="UPDATE universita.segreteria SET password = $1 WHERE email = $2";
                        break;
                    case "studente":
                        $query2="UPDATE universita.studente SET password = $1 WHERE email = $2";
                        break;
                    case "docente":
                        $query2="UPDATE universita.docente SET password = $1 WHERE email = $2";
                        break;
                }

                $newP = md5($newP);
                
                $parametri2 = Array(
                    $newP,
                    $user
                );

                $result = pg_prepare($conn, "cambio_Psw", $query2);
                $result = pg_execute($conn, "cambio_Psw", $parametri2);

                if ($result) {
                    echo "Cambio riuscito!";
                } else {
                    echo "Errore nell'esecuzione della query: " . pg_last_error();
                }
    
            }
            
        }

        pg_close($conn);
    }
?>
