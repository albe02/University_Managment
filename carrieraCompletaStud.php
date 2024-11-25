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
            <div id="colonna1" class="col-6">
                <div id="risultato" class="row">
                    <h3>La tua carriera</h3>
                    <?php
                        $conn = pg_connect("host=localhost user=postgres password=FiatPanda8 dbname=Esame_Basi_Di_Dati")
                        or die('Could not connect: ' . pg_last_error());

                        $user=$_SESSION['username'];

                        // Query di inserimento
                        $query2 = 'SELECT studente.matricola FROM universita.studente WHERE studente.email = $1';
                        
                        $parametri2 = Array(
                            $user
                        );

                        $result = pg_prepare($conn, "matricola_studente", $query2);
                        $result = pg_execute($conn, "matricola_studente", $parametri2);

                        $row = pg_fetch_assoc($result);
                        $matricola = $row['matricola'];

                        // Query di inserimento
                        $query = "SELECT e.nome_esame, t.esito, t.voto, t.data
                        FROM universita.esame e INNER JOIN universita.trascrizione t
                        ON e.id_insegnamento = t.id_insegnamento AND e.data = t.data
                        INNER JOIN universita.studente s ON s.id_carriera = t.id_carriera
                        WHERE s.matricola = $1;";
                        
                        $parametri = Array(
                            $matricola
                        );

                        /*$result = pg_query($query); */
                        $result = pg_prepare($conn, "carriera_studente", $query);
                        $result = pg_execute($conn, "carriera_studente", $parametri);
                        
                        if ($result) {
                            $dati = array();
                            while ($row = pg_fetch_assoc($result)) {
                                $dati[] = $row;
                            }
                            // Visualizzazione dei dati
                            echo "<p>";
                            foreach ($dati as $row) {
                                echo  $row['nome_esame'] ;
                                echo " |  Data: " . $row['data'] ;
                                echo " | Voto: " . $row['voto'] . "<br>";
                            }
                            echo "</p>";
                        } else {
                            echo "Errore nell'esecuzione della query: " . pg_last_error();
                        }

                        pg_close($conn);
                    
                    ?>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous"></script>
  </body>
</html>
