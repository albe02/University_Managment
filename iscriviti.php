<?php
session_start();

// Verifica se l'utente è connesso
if (!isset($_SESSION['username']) || $_SESSION['tipo'] != 'studente') {
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
            <div id="containerCard" class="container-fluid">
                <div id="rigaIscrizione" class="row m-auto mb-2">
                    <div class="card h-100 bg-custom2 m-auto" style="width: 40 rem;">
                        <div class="card-body">
                            <h5 class="card-title">Iscriviti ad un esame</h5>
                            <form action="iscriviti.php" method="POST">
                                <select name="esame" class="form-select custom-bg pb-10" id="inputGroupSelect01">
                                    <option selected>Seleziona Esame</option>
                                    <?php
                                        $conn = pg_connect("host=localhost user=postgres password=FiatPanda8 dbname=Esame_Basi_Di_Dati")
                                                or die('Could not connect: ' . pg_last_error());

                                        $query = 'SELECT e.id_insegnamento, e.nome_esame, e.tipologia, e.data, e.ora FROM universita.esame e';
                                        $result = pg_prepare($conn, "corsi", $query);
                                        $result = pg_execute($conn, "corsi", array());

                                        while ($row = pg_fetch_assoc($result)){
                                            $id[] = $row['id_insegnamento'];
                                            $nomi[] = $row['nome_esame'];
                                            $tipologia[] = $row['tipologia'];
                                            $data[] = $row['data'];
                                            $ora[] = $row['ora'];

                                        }

                                        for ($i = 0; $i < count($id); $i++){
                                            echo '<h4>' . $nomi[$i]. ' ' . $tipologia[$i]. ' ' . $data[$i]. ' ' . $ora[$i] . '</h4>';
                                        }
                                        unset($id);
                                        unset($nomi);
                                        unset($tipologia);
                                        unset($data);
                                        unset($ora);


                                        pg_close($conn);
                                    ?>
                                </select>

                                <button name="submit" type="submit" class="btn btn-custom mt-3">Iscriviti</button>
                            </form>
                        </div>
                    </div>
                </div>
                <div id="rigaIscritto" class="row m-auto">
                <div class="card h-100 bg-custom2 m-auto" style="width: 40 rem;">
                        <div class="card-body">
                            <h5 class="card-title">Iscrizioni effettuate</h5>
                            <?php
                                $conn = pg_connect("host=localhost user=postgres password=FiatPanda8 dbname=Esame_Basi_Di_Dati")
                                        or die('Could not connect: ' . pg_last_error());

                                $query = 'SELECT e.id_insegnamento, e.nome_esame, e.tipologia, e.data, e.ora FROM universita.esame e INNER JOIN universita.prenotante p ON e.id_insegnamento = p.id_insegnamento AND e.data = p.data INNER JOIN universita.studente s ON s.matricola = p.matricola_studente WHERE s.email = $1';
                                $result = pg_prepare($conn, "corsi", $query);
                                $result = pg_execute($conn, "corsi", array($_SESSION['username']));

                                while ($row = pg_fetch_assoc($result)){
                                    $id[] = $row['id_insegnamento'];
                                    $nomi[] = $row['nome_esame'];
                                    $tipologia[] = $row['tipologia'];
                                    $data[] = $row['data'];
                                    $ora[] = $row['ora'];

                                }

                                for ($i = 0; $i < count($id); $i++){
                                    echo '<option value="' . $id[$i]. ', ' . $data[$i] . '">' . $nomi[$i]. ' ' . $tipologia[$i]. ' ' . $data[$i]. ' ' . $ora[$i] . '</option>';
                                }

                                pg_close($conn);
                            ?>
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
        $esame = $_POST['esame'];
        
        list($id_insegnamento, $data) = explode(",", $esame);

        $user=$_SESSION['username'];

        // Query di inserimento
        $query = 'SELECT studente.matricola FROM universita.studente WHERE studente.email = $1';
        
        $parametri = Array(
            $user
        );

        $result = pg_prepare($conn, "matricola_studente", $query);
        $result = pg_execute($conn, "matricola_studente", $parametri);

        $row = pg_fetch_assoc($result);
        
        $matricola = $row['matricola'];

        $query2 = 'INSERT INTO universita.prenotante (matricola_studente, id_insegnamento, data) VALUES ( $1, $2, $3);';

        $parametri2 = Array(
            $matricola,
            $id_insegnamento,
            $data
        );

        $result = pg_prepare($conn, "prenotazione", $query2);
        $result = pg_execute($conn, "prenotazione", $parametri2);

        if ($result) {
            echo "Inserimento riuscito!";
        } else {
            echo "Errore nell'esecuzione della query: " . pg_last_error();
        }

        
        pg_close($conn);
    }