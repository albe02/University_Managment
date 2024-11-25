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

      <div id="containerCard" class="container-fluid">
        <div id="rigaContainer" class="row">
                <div id="colonna1" class="col-6 m-auto">
                    <div class="card h-100 bg-custom2 m-auto" style="width: 40rem;">
                        <div class="card-body">
                            <h5 class="card-title">Modifica credenziali Studente</h5>
                            <form action="modificaCredStud.php" method="POST">
                               <div id="matricola" class="row m-auto mb-2">
                                    <select name="mat" class="form-select custom-bg" id="inputGroupSelect01">
                                        <option selected>Matricola Studente</option>
                                        <?php
                                            $conn = pg_connect("host=localhost user=postgres password=FiatPanda8 dbname=Esame_Basi_Di_Dati")
                                                    or die('Could not connect: ' . pg_last_error());

                                            $query = 'SELECT s.matricola, s.nome, s.cognome FROM universita.studente s';
                                            $result = pg_prepare($conn, "corsi", $query);
                                            $result = pg_execute($conn, "corsi", array());

                                            while ($row = pg_fetch_assoc($result)){
                                                $matricola[] = $row['matricola'];
                                                $nomiStud[] = $row['nome'];
                                                $cognomiStud[] = $row['cognome'];
                                            }

                                            for ($i = 0; $i < count($matricola); $i++){
                                                echo '<option value="' . $matricola[$i] . '">' . $matricola[$i] . " " . $nomiStud[$i] . " " . $cognomiStud[$i] . '</option>';
                                            }

                                            pg_close($conn);
                                        ?>
                                    </select>
                                </div> 
                                <div id="nuovaMail" class="row m-auto mb-2">
                                    <input name="email" type="email" class="form-control custom-bg" id="InputEmail" aria-describedby="emailHelp" placeholder="E-Mail">  
                                </div>
                                <div id="nuovaPsw" class="row m-auto mb-2">
                                    <input name="password" type="password" class="form-control custom-bg" id="InputPsw" aria-describedby="pswHelp" placeholder="Password">
                                </div>

                                <div id="agg" class="row m-auto mb-2">
                                    <button name="aggiorna" type="submit" class="btn btn-custom">Aggiorna</button>
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

    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['aggiorna'])) {
        // Recupera i dati dal modulo
        $username = $_POST['email'];
        $password = md5($_POST['password']);
        $matricola = $_POST['mat'];

        // Query di inserimento
        $query = 'UPDATE universita.studente SET email = $1, password = $2 WHERE studente.matricola = $3 ';
        
        $parametri = Array(
            $username,
            $password,
            $matricola
        );

        $result = pg_prepare($conn, "aggiornamento_studente", $query);
        

        $result = pg_execute($conn, "aggiornamento_studente", $parametri);
        if ($result) {
            echo "Aggiornamento riuscito!";
        } else {
            echo "Errore nell'esecuzione della query: " . pg_last_error();
        }
        
        pg_close($conn);
    }

?>
