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
                            <form action="modificaStudenti.php" method="POST">
                                <div id="rigaNomi" class="row m-auto mb-2">
                                    <div id="colonnaNome" class="col-6 m-auto">
                                        <input name="nome" type="text" class="form-control custom-bg" id="InputNome" aria-describedby="nameHelp" placeholder="Nome">
                                    </div>

                                    <div id="colonnaCognome" class="col-6 m-auto">
                                        <input name="cognome" type="text" class="form-control custom-bg" id="InputCognome" aria-describedby="surnameHelp" placeholder="Cognome">
                                    </div>
                                </div>

                                <div id="rigaCDL" class="row m-auto mb-2">
                                    <div id="colonnaCDL" class="col">
                                        <select name="CDL" class="form-select custom-bg" id="inputGroupSelect01">
                                            <option selected>Corso Di Laurea</option>
                                            <?php
                                                $conn = pg_connect("host=localhost user=postgres password=FiatPanda8 dbname=Esame_Basi_Di_Dati")
                                                        or die('Could not connect: ' . pg_last_error());

                                                $query = 'SELECT c.id, c.nome FROM universita.corso_di_laurea c';
                                                $result = pg_prepare($conn, "corsi", $query);
                                                $result = pg_execute($conn, "corsi", array());

                                                while ($row = pg_fetch_assoc($result)){
                                                    $id[] = $row['id'];
                                                    $nomi[] = $row['nome'];
                                                }

                                                for ($i = 0; $i < count($id); $i++){
                                                    echo '<option value="' . $id[$i] . '">' . $nomi[$i] . '</option>';
                                                }

                                                pg_close($conn);
                                            ?>
                                        </select>
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
                                
                                <div id="rec" class="row m-auto mb-2">
                                    <button name="submit" type="submit" class="btn btn-custom">Registra</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div id="colonna2" class="col-4 m-auto">
                    <div class="card h-100 bg-custom2" style="width: 20rem;">
                        <div class="card-body">
                            <h5 class="card-title">Rimuovi Studente</h5>
                            <form action="modificaStudenti.php" method="POST">
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
        $id_corso = $_POST['CDL'];

        // Query di inserimento
        $query = 'INSERT INTO Universita.Studente (nome, cognome, data_di_nascita, telefono, email, via, cap, civico, id_corso, password) VALUES ($1, $2, $6, $7, $8, $3, $5, $4, $9, $10);';
        
        $parametri = Array(
            $name,
            $surname,
            $via,
            $civico,
            $cap,
            $nascita,
            $telefono,
            $username,
            $id_corso,
            $password
        );

        $result = pg_prepare($conn, "inserimento_studente", $query);
        

        $result = pg_execute($conn, "inserimento_studente", $parametri);
        if ($result) {
            echo "Inserimento riuscito!";
        } else {
            echo "Errore nell'esecuzione della query: " . pg_last_error();
        }
        
        pg_close($conn);
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['elimina'])) {
        // Recupera i dati dal modulo
        $matricola = $_POST['mat'];
        

        // Query di inserimento
        $query = 'DELETE FROM universita.studente s WHERE s.matricola = $1';
        
        $parametri = Array(
            $matricola
        );

        $result = pg_prepare($conn, "rimozione_studente", $query);
        $result = pg_execute($conn, "rimozione_studente", $parametri);

        if ($result) {
            echo "Eliminazione riuscita!";
        } else {
            echo "Errore nell'esecuzione della query: " . pg_last_error();
        }
        
        pg_close($conn);
    }
?>
