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
        <div id="rigaContainer" class="row m-auto">
                <div id="colonna1" class="col-4 m-auto">
                    <div class="card h-100 bg-custom2" style="width: 20rem;">
                        <div class="card-body">
                            <h5 class="card-title">Aggiungi Studente</h5>
                            <form action="modificaSegreteria.php" method="POST">
                                <div id="rigaNomi" class="row m-auto mb-2">
                                    <div id="colonnaNome" class="col-6">
                                        <input name="nome" type="text" class="form-control custom-bg" id="InputNome" aria-describedby="nameHelp" placeholder="Nome">
                                    </div>

                                    <div id="colonnaCognome" class="col-6">
                                        <input name="cognome" type="text" class="form-control custom-bg" id="InputCognome" aria-describedby="surnameHelp" placeholder="Cognome">
                                    </div>
                                </div>

                                <div id="rigaIndirizzo" class="row m-auto mb-2">
                                    <div id="colonnaVia" class="col-5">
                                        <input name="via" type="text" class="form-control custom-bg" id="InputVia" aria-describedby="viaHelp" placeholder="Via (Senza numero civico)">
                                    </div>

                                    <div id="colonnaCivico" class="col-3">
                                        <input name="civico" type="text" class="form-control custom-bg" id="InputCivico" aria-describedby="civicoHelp" placeholder="N°">
                                    </div>

                                    <div id="colonnaCAP" class="col-4">
                                        <input name="CAP" type="text" class="form-control custom-bg" id="InputCAP" aria-describedby="CAPHelp" placeholder="CAP">
                                    </div>
                                </div>

                                <div id="nascita" class="row m-auto mb-2">
                                    <input name="nascita" type="date" class="form-control custom-bg" id="InputNascita" aria-describedby="nascHelp" placeholder="Data di nascita">
                                </div>

                                <div id="telefono" class="row m-auto mb-2">
                                    <input name="telefono" type="tel" class="form-control custom-bg" id="InputTelefono" aria-describedby="telHelp" placeholder="Numero di Telefono o Cellulare">
                                </div>
                                
                                <div id="email" class="row m-auto mb-2">
                                    <input name="email" type="email" class="form-control custom-bg" id="InputEmail" aria-describedby="emailHelp" placeholder="E-Mail">
                                </div>

                                <div id="psw" class="row m-auto mb-2">
                                    <input name="password" type="password" class="form-control custom-bg" id="InputPsw" aria-describedby="pswHelp" placeholder="Password">
                                </div>
                                
                                <div id="rec" class="row m-auto">
                                    <button name="submit" type="submit" class="btn btn-custom">Registra</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div id="colonna2" class="col-4 m-auto">
                    <div class="card h-100 bg-custom2" style="width: 20rem;">
                        <div class="card-body">
                            <h5 class="card-title">Rimuovi Segreteria</h5>
                            <form action="modificaSegreteria.php" method="POST">
                               <div id="idSegreteria" class="row m-auto mb-2">
                                    <select name="id" class="form-select custom-bg" id="inputGroupSelect01">
                                        <option selected>Id Segreteria</option>
                                        <?php
                                            $conn = pg_connect("host=localhost user=postgres password=FiatPanda8 dbname=Esame_Basi_Di_Dati")
                                                    or die('Could not connect: ' . pg_last_error());

                                            $query = 'SELECT s.id, s.nome, s.cognome FROM universita.segreteria s';
                                            $result = pg_prepare($conn, "corsi", $query);
                                            $result = pg_execute($conn, "corsi", array());

                                            while ($row = pg_fetch_assoc($result)){
                                                $id[] = $row['id'];
                                                $nomiS[] = $row['nome'];
                                                $cognomiS[] = $row['cognome'];
                                            }

                                            for ($i = 0; $i < count($id); $i++){
                                                echo '<option value="' . $id[$i] . '">' . $id[$i] . " " . $nomiS[$i] . " " . $cognomiS[$i] . '</option>';
                                            }

                                            pg_close($conn);
                                        ?>
                                    </select>
                                </div> 

                                <div id="del" class="row m-auto">
                                    <button name="elimina" type="submit" class="btn btn-custom">Elimina</button>
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
        // Recupera i dati dal modulo
        $name = $_POST['nome'];
        $surname = $_POST['cognome'];
        $via = $_POST['via'];
        $civico = $_POST['civico'];
        $cap = $_POST['CAP'];
        $nascita = $_POST['nascita'];
        $telefono = $_POST['telefono'];
        $username = $_POST['email'];
        $password = md5($_POST['password']);

        // Query di inserimento
        $query = 'INSERT INTO Universita.Segreteria (nome, cognome, data_di_nascita, telefono, email, via, cap, civico, password) VALUES ($1, $2, $6, $7, $8, $3, $5, $4, $9);';
        
        $parametri = Array(
            $name,
            $surname,
            $via,
            $civico,
            $cap,
            $nascita,
            $telefono,
            $username,
            $password
        );

        $result = pg_prepare($conn, "inserimento_s", $query);
        

        $result = pg_execute($conn, "inserimento_s", $parametri);
        if ($result) {
            echo "Inserimento riuscito!";
        } else {
            echo "Errore nell'esecuzione della query: " . pg_last_error();
        }
        
        pg_close($conn);
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['elimina'])) {
        // Recupera i dati dal modulo
        $idSeg = $_POST['id'];
        

        // Query di inserimento
        $query = 'DELETE FROM universita.segreteria s WHERE s.id = $1';
        
        $parametri = Array(
            $idSeg
        );

        $result = pg_prepare($conn, "rimozione_segreteria", $query);
        $result = pg_execute($conn, "rimozione_segreteria", $parametri);

        if ($result) {
            echo "Eliminazione riuscita!";
        } else {
            echo "Errore nell'esecuzione della query: " . pg_last_error();
        }
        
        pg_close($conn);
    }
?>
