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
            <div id="colonna1" class="col-6 m-auto">
                <form action="carrieraCompleta.php" method="POST">
                    <div id="rigaStudente" class="row m-auto mb-2">
                        <select name="mat" class="form-select custom-bg" id="inputGroupSelect01">
                            <option selected>Matricola Studente</option>
                            <?php
                                $conn = pg_connect("host=localhost user=postgres password=FiatPanda8 dbname=Esame_Basi_Di_Dati")
                                        or die('Could not connect: ' . pg_last_error());
            
                                $query = 'SELECT s.matricola, s.nome, s.cognome FROM universita.studente s';
                                $result = pg_prepare($conn, "corsi", $query);
                                $result = pg_execute($conn, "corsi", array());
            
                                while ($row = pg_fetch_assoc($result)){
                                    $matricolaA[] = $row['matricola'];
                                    $nomiStud[] = $row['nome'];
                                    $cognomiStud[] = $row['cognome'];
                                }
            
                                for ($i = 0; $i < count($matricolaA); $i++){
                                    echo '<option value="' . $matricolaA[$i] . '">' . $matricolaA[$i] . " " . $nomiStud[$i] . " " . $cognomiStud[$i] . '</option>';
                                }
                                unset($matricolaA);
                                unset($nomiStud);
                                unset($cognomiStud);
                                pg_close($conn);
                            ?>
                        </select>
                    </div>

                    <div id="bottoneSubmit" class="row m-auto mb-5">
                        <button name="richiedi" type="submit" class="btn bg-custom2">Richiedi</button>
                    </div>
        
                    <div id="risultato" class="row m-auto">
                        <?php
                            if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['richiedi'])) {
                                $conn = pg_connect("host=localhost user=postgres password=FiatPanda8 dbname=Esame_Basi_Di_Dati")
                                   or die('Could not connect: ' . pg_last_error());
                                // Recupera i dati dal modulo
                                $matricola = $_POST['mat'];
        
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
                                    
                                    foreach ($dati as $row) {
                                    echo  $row['nome_esame'] ;
                                    echo " |  Data: " . $row['data'] ;
                                    echo " | Voto: " . $row['voto'];
                                    if ($row['esito'] == 't') {
                                        echo " | Esito: PROMOSSO " . "<br>";
                                    } else {
                                        echo " | Esito: BOCCIATO " . "<br>";
                                    }
                                    }
        
                                } else {
                                    echo "Errore nell'esecuzione della query: " . pg_last_error();
                                }
                                unset($parametri);
                                unset($dati);
                                pg_close($conn);
                            }
                        ?>
                    </div>
                </form>
            </div>

            <div id="colonna2" class="col-6 m-auto">
                <form action="carrieraCompleta.php" method="POST">
                    <div id="rigaStudente" class="row m-auto mb-2">
                        <select name="mat" class="form-select custom-bg" id="inputGroupSelect01">
                            <option selected>Matricola Studente</option>
                            <?php
                                $conn = pg_connect("host=localhost user=postgres password=FiatPanda8 dbname=Esame_Basi_Di_Dati")
                                        or die('Could not connect: ' . pg_last_error());
            
                                $query = 'SELECT s.matricola, s.nome, s.cognome FROM universita.storico_studente s';
                                $result = pg_prepare($conn, "corsi", $query);
                                $result = pg_execute($conn, "corsi", array());
            
                                while ($row = pg_fetch_assoc($result)){
                                    $matricolaA[] = $row['matricola'];
                                    $nomiStud[] = $row['nome'];
                                    $cognomiStud[] = $row['cognome'];
                                }
            
                                for ($i = 0; $i < count($matricolaA); $i++){
                                    echo '<option value="' . $matricolaA[$i] . '">' . $matricolaA[$i] . " " . $nomiStud[$i] . " " . $cognomiStud[$i] . '</option>';
                                }
                                unset($matricolaA);
                                unset($nomiStud);
                                unset($cognomiStud);
                                pg_close($conn);
                            ?>
                        </select>
                    </div>

                    <div id="bottoneSubmit" class="row m-auto mb-5">
                        <button name="richiedi" type="submit" class="btn bg-custom2">Richiedi</button>
                    </div>
        
                    <div id="risultato" class="row m-auto">
                        <?php
                            if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['richiedi'])) {
                                $conn = pg_connect("host=localhost user=postgres password=FiatPanda8 dbname=Esame_Basi_Di_Dati")
                                   or die('Could not connect: ' . pg_last_error());
                                // Recupera i dati dal modulo
                                $matricola = $_POST['mat'];
        
                                // Query di inserimento
                               $query = "SELECT c.nome_esame, c.esito, c.voto, c.data
                                FROM universita.curriculum c
                                INNER JOIN universita.storico_studente s ON s.id_storico_carriera = c.id_carriera
                                WHERE s.matricola = $1;";
                                
                                $parametri = Array(
                                    $matricola
                                );
    
                                /*$result = pg_query($query); */
                                $result = pg_prepare($conn, "carriera_storico_studente", $query);
                                $result = pg_execute($conn, "carriera_storico_studente", $parametri);
                                
                                if ($result) {
                                    $dati = array();
                                    while ($row = pg_fetch_assoc($result)) {
                                        $dati[] = $row;
                                    }
                                    // Visualizzazione dei dati
                                    
                                    foreach ($dati as $row) {
                                    echo  $row['nome_esame'] ;
                                    echo " |  Data: " . $row['data'] ;
                                    echo " | Voto: " . $row['voto'];
                                    if ($row['esito'] == 't') {
                                        echo " | Esito: PROMOSSO " . "<br>";
                                    } else {
                                        echo " | Esito: BOCCIATO " . "<br>";
                                    }
                                    }
        
                                } else {
                                    echo "Errore nell'esecuzione della query: " . pg_last_error();
                                }
                                unset($dati);
                                unset($parametri);
                                pg_close($conn);
                            }
                        ?>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous"></script>
  </body>
</html>
