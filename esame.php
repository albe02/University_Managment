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

        <div id="rigaContainer" class="row">
            <div id="containerCard" class="container-fluid">
                <div id="rigaIscrizione" class="row m-auto">
                    <div class="card h-100 bg-custom2 m-auto" style="width: 40rem;">
                        <div class="card-body">
                            <h5 class="card-title">Crea un esame</h5>
                            <form action="esame.php" method="POST">
                                <select name="insegnamento" class="form-select custom-bg m-auto mb-2" id="inputGroupSelect01">
                                    <option selected>Seleziona insegnamento</option>
                                    <?php
                                        $conn = pg_connect("host=localhost user=postgres password=FiatPanda8 dbname=Esame_Basi_Di_Dati")
                                                or die('Could not connect: ' . pg_last_error());

                                        $query = 'SELECT i.id, i.nome FROM universita.insegnamento i INNER JOIN universita.docente d ON i.id_docente_responsabile = d.id WHERE d.email = $1;';

                                        $email = $_SESSION['username'];

                                        $parametri = array(
                                            $email
                                        );
                                        $result = pg_prepare($conn, "corsi", $query);
                                        $result = pg_execute($conn, "corsi", $parametri);

                                        while ($row = pg_fetch_assoc($result)){
                                            $id[] = $row['id'];
                                            $nomi[] = $row['nome'];
                                        }

                                        for ($i = 0; $i < count($id); $i++){
                                            echo '<option value="' . $id[$i] .  '">' . $nomi[$i] . '</option>';
                                        }

                                        pg_close($conn);
                                    ?>
                                </select>

                                <input name="data" type="date" class="form-control custom-bg mb-2" id="InputData" aria-describedby="dateHelp" placeholder="Data">
                                <input name="ora" type="time" class="form-control custom-bg mb-2" id="InputOra" aria-describedby="oraHelp" placeholder="Ora">

                                <select name="tipologia" class="form-select custom-bg mb-2" id="inputGroupSelect01">
                                    <option selected> Inserire il tipo </option>
                                    <option value = "Scritto"> Scritto </option>
                                    <option value = "Orale"> Orale </option>
                                    <option value = "Laboratorio"> Laboratorio </option>
                                </select>

                                <input name="nome" type="text" class="form-control custom-bg mb-2" id="InputNome" aria-describedby="nameHelp" placeholder="Nome Esame">
                                

                                <button name="submit" type="submit" class="btn btn-custom">Aggiungi Esame</button>
                            </form>
                        </div>
                    </div>

                    <div id="rigaEsamiEffettuati" class="row m-auto mt-2">
                    <div class="card h-100 bg-custom2 m-auto" style="width: 40rem;">
                        <div class="card-body">
                            <h5 class="card-title">Esami creati</h5>
                            <?php
                                $conn = pg_connect("host=localhost user=postgres password=FiatPanda8 dbname=Esame_Basi_Di_Dati")
                                        or die('Could not connect: ' . pg_last_error());

                                $query = 'SELECT e.nome_esame, e.tipologia, e.data, e.ora FROM universita.esame e INNER JOIN universita.docente d ON e.id_docente = d.id WHERE d.email = $1;';

                                $email = $_SESSION['username'];

                                $parametri = array(
                                    $email
                                );
                                $result = pg_prepare($conn, "corsi", $query);
                                $result = pg_execute($conn, "corsi", $parametri);

                                while ($row = pg_fetch_assoc($result)){
                                    $nomeE[] = $row['nome_esame'];
                                    $tipo2[] = $row['tipologia'];
                                    $data[] = $row['data'];
                                    $ora[]= $row['ora'];
                                }

                                for ($i = 0; $i < count($data); $i++){
                                    echo '<h4>' . $nomeE[$i]. ' ' . $tipo2[$i]. ' ' .$data[$i]. ' ' .$ora[$i] . '</h4>';
                                }

                                pg_close($conn);
                            ?>
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
<?php

    $conn = pg_connect("host=localhost user=postgres password=FiatPanda8 dbname=Esame_Basi_Di_Dati ")
    or die('Could not connect: ' . pg_last_error());

    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit'])) {
        // Recupera i dati dal modulo
        
        $user = $_SESSION['username'];
        $insegnamento = $_POST['insegnamento'];
        $data = $_POST['data'];
        $ora = $_POST['ora'];
        $tipologia = $_POST['tipologia'];
        $nome = $_POST['nome'];

        // Query di inserimento
        $query = 'SELECT docente.id FROM universita.docente WHERE docente.email = $1';
        
        $parametri = Array(
            $user
        );

        $result = pg_prepare($conn, "id_docente", $query);
        $result = pg_execute($conn, "id_docente", $parametri);

        $row = pg_fetch_assoc($result);
        
        $id = $row['id'];

        $query2 = 'INSERT INTO universita.esame ( id_insegnamento, data, ora, tipologia, id_docente, nome_esame) VALUES ( $1, $2, $3, $4, $5, $6);';

        $parametri2 = Array(
            $insegnamento,
            $data,
            $ora,
            $tipologia,
            $id,
            $nome
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