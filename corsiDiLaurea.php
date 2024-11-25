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

        <div id="rigaContainer" class="row m-auto">
            <div id="colonna1" class="col-6 m-auto bg-custom2 p-3">
                <form action="corsiDiLaurea.php" method="POST">
                    <div id="rigaCDL" class="row m-auto">
                        <select name="idC" class="form-select custom-bg" id="inputGroupSelect01">
                            <option selected>Corsi di Laurea</option>
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
                                unset($id);
                                unset($nomi);
                                
                                pg_close($conn);
                            ?>
                        </select>
                    </div>
                    
                    <div id="bottoneSubmit" class="row">
                        <button name="richiedi" type="submit" class="btn btn-custom w-50 m-auto mt-2">Richiedi</button>
                    </div>

                    <div id="risultato" class="row">
                        <?php
                            if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['richiedi'])) {
                                $conn = pg_connect("host=localhost user=postgres password=FiatPanda8 dbname=Esame_Basi_Di_Dati")
                                   or die('Could not connect: ' . pg_last_error());
                                // Recupera i dati dal modulo
                                $id = $_POST['idC'];

                                $query2 = 'SELECT d.nome, d.cognome FROM universita.corso_di_laurea c INNER JOIN universita.docente d ON c.id_docente = d.id WHERE c.id = $1';
                                
                                $parametri = Array(
                                    $id
                                );

                                $result = pg_prepare($conn, "nomeResp", $query2);
                                $result = pg_execute($conn, "nomeResp", $parametri);
                                
                                $row = pg_fetch_assoc($result);

                                $docenteResp = $row['nome']. " " . $row['cognome'];

                                echo '<h3> DOCENTE RESPONSABILE CDL: ' . $docenteResp . "</h3>";


                                // Query di inserimento
                               $query = "SELECT i.nome as ins, d.nome, d.cognome, i.anno
                               FROM universita.insegnamento i INNER JOIN universita.docente d
                               ON i.id_docente_responsabile = d.id
                               INNER JOIN universita.programma p ON p.id_insegnamento = i.id
                               INNER JOIN universita.corso_di_laurea c ON p.id_corso_di_laurea = c.id
                               WHERE c.id = $1;";
                                
    
                                /*$result = pg_query($query); */
                                $result = pg_prepare($conn, "datiCorso", $query);
                                $result = pg_execute($conn, "datiCorso", $parametri);
                                
                                if ($result) {
                                    $dati = array();
                                    while ($row = pg_fetch_assoc($result)) {
                                        $dati[] = $row;
                                    }
                                    // Visualizzazione dei dati
                                    
                                    foreach ($dati as $row) {
                                        echo  $row['ins'] ;
                                        echo " |  Docente: " . $row['nome'] . " " . $row['cognome'] ;
                                        echo " | Anno: " . $row['anno'] . "<br>"; 
                                          
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
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous"></script>
  </body>
</html>
