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

      <div id="container" class="container-fluid">
        <div id="rigaContainer" class="row m-auto">
                <form action="voti.php" method="POST">
                    <div id="rigaCorsi" class="row">
                        <select name="idIns" class="form-select custom-bg" id="inputCorso2">
                            <option <?php if (!($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['Ins']))) { echo 'selected';} ?>>Id Corsi</option>
                            <?php
                                $conn = pg_connect("host=localhost user=postgres password=FiatPanda8 dbname=Esame_Basi_Di_Dati")
                                        or die('Could not connect: ' . pg_last_error());

                                $query = 'SELECT e.id_insegnamento, e.data, e.nome_esame, e.tipologia FROM universita.esame e INNER JOIN universita.insegnamento i ON e.id_insegnamento = i.id INNER JOIN universita.docente d ON i.id_docente_responsabile = d.id WHERE d.email = $1';
                                
                                $user= $_SESSION['username'];

                                $parametri = array(
                                    $user
                                );

                                $result = pg_prepare($conn, "insegnamenti", $query);
                                $result = pg_execute($conn, "insegnamenti", $parametri);

                                while ($row = pg_fetch_assoc($result)){
                                    $id[] = $row['id_insegnamento'];
                                    $data[] = $row['data'];
                                    $nome[]=$row['nome_esame'];
                                    $tipologia[]=$row['tipologia'];
                                };

                                for ($i = 0; $i < count($id); $i++){

                                    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['Ins'])) { 
                                        $corso = $_POST['idIns'];
                                        list($id_insegnamento, $dat) = explode(", " , $corso);
                                        
                                        if ($id[$i] == $id_insegnamento && $data[$i] == $dat){
                                            
                                            echo '<option selected value="' . $id[$i] .", ". $data[$i] . '">' . $nome[$i]. ", " . $tipologia[$i] . '</option>';
                                        } else {
                                            echo '<option value="' . $id[$i] .", ". $data[$i] . '">' . $nome[$i]. ", " . $tipologia[$i] . '</option>';
                                        }
                                    }else {
                                        echo '<option value="' . $id[$i] .", ". $data[$i] . '">' . $nome[$i]. ", " . $tipologia[$i] . '</option>';
                                    }
                                }
                                unset($id);
                                unset($data);
                                unset($nome);
                                unset($tipologia);
                                pg_close($conn);
                            ?>
                        </select>
                    </div>

                    <div id="rigaBottoneConferma" class="row">
                        <button name="Ins" type="submit" class="btn btn-primary">Conferma Insegnamento</button>
                    </div>

                    <?php
                        $conn = pg_connect("host=localhost user=postgres password=FiatPanda8 dbname=Esame_Basi_Di_Dati ")
                        or die('Could not connect: ' . pg_last_error());
                        
                        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['Ins'])) {
                            echo '<div id="rigaIscritti" class="row m-auto">
                                    <select name="Isc" class="form-select custom-bg" id="inputGroupSelect05">
                                        <option selected>Matricola </option>';
                            
                            $esame = $_POST['idIns'];

                            list($id_insegnamento, $data) = explode(",", $esame);

                            $query = 'SELECT s.id_carriera, p.matricola_studente, s.nome, s.cognome FROM universita.prenotante p INNER JOIN universita.studente s ON p.matricola_studente = s.matricola WHERE p.id_insegnamento = $1 AND p.data = $2';
                                

                            $parametri = Array(
                                $id_insegnamento,
                                $data
                            );
                            $result = pg_prepare($conn, "corsi", $query);
                            $result = pg_execute($conn, "corsi", $parametri);

                            while ($row = pg_fetch_assoc($result)){
                                $id_carriera[] = $row['id_carriera'];
                                $matricola[] = $row['matricola_studente'];
                                $nomi[] = $row['nome'];
                                $cognome[] = $row['cognome'];
                            }

                            for ($i = 0; $i < count($id_carriera); $i++){
                                echo '<option value="' . $id_carriera[$i] . '">' . $matricola[$i] . " " . $nomi[$i]. " " . $cognome[$i] . '</option>';
                            }
  
                            pg_close($conn);

                            echo '</select>
                                </div>';

                            echo '<div id="rigaVoto" class="row"> 
                                      <input name="voto" type="number" class="form-control custom-bg" id="InputVoto" aria-describedby="votoHelp" placeholder="Voto" min="0" max="30">
                                </div>';
                        }
                    ?>

                    <div id="bottoneConferma" class="row m-auto">
                        <button name="conferma" type="submit" class="btn btn-custom">Conferma Voto</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  </body>
</html>
<?php

    $conn = pg_connect("host=localhost user=postgres password=FiatPanda8 dbname=Esame_Basi_Di_Dati ")
    or die('Could not connect: ' . pg_last_error());

    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['conferma'])) {
        // Recupera i dati dal modulo
        $voto = $_POST['voto'];
        $esame = $_POST['idIns'];
        
        $carriera = $_POST['Isc'];
        
        list($id_insegnamento2, $data2) = explode("," , $esame);

        
        // Query di inserimento
        $query = 'INSERT INTO universita.trascrizione (id_insegnamento, data, id_carriera, voto) VALUES ($1, $2, $3, $4);';
        
        $parametri2 = Array(
            $id_insegnamento2,
            $data2,
            $carriera,
            $voto
        );

        $result = pg_prepare($conn, "inserisci_voto", $query);
        $result = pg_execute($conn, "inserisci_voto", $parametri2);
        
        if ($result) {
            echo "Inserimento riuscito!";
        } else {
            echo "Errore nell'esecuzione della query: " . pg_last_error();
        }
        
        pg_close($conn);
    }

    ?>