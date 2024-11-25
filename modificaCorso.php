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

      <div id="container" class="container-fluid">
        <div id="rigaContainer" class="row m-auto p-3 bg-custom2">
                <form action="modificaCorso.php" method="POST">
                    <div id="corso" class="row m-auto mb-2">
                        <select name="id2" class="form-select custom-bg" id="inputGroupSelect03">
                            <option selected>Id Corso di Laurea</option>
                            <?php
                                $conn = pg_connect("host=localhost user=postgres password=FiatPanda8 dbname=Esame_Basi_Di_Dati")
                                        or die('Could not connect: ' . pg_last_error());

                                $query = 'SELECT c.id, c.nome FROM universita.corso_di_laurea c';
                                $result = pg_prepare($conn, "corsi", $query);
                                $result = pg_execute($conn, "corsi", array());

                                while ($row = pg_fetch_assoc($result)){
                                    $id[] = $row['id'];
                                    $nomiCs[] = $row['nome'];
                                }

                                for ($i = 0; $i < count($id); $i++){
                                    echo '<option value="' . $id[$i] . '">' . $nomiCs[$i] . '</option>';
                                }

                                unset($id);
                                unset($nomiIns);

                                pg_close($conn);
                            ?>
                        </select>
                    </div>
                    <div id="rigaResponsabile" class="row m-auto mb-2">
                        <select name="idDocente" class="form-select custom-bg" id="inputGroupSelect04">
                            <option selected>Id Docente</option>
                            <?php
                                $conn = pg_connect("host=localhost user=postgres password=FiatPanda8 dbname=Esame_Basi_Di_Dati")
                                        or die('Could not connect: ' . pg_last_error());

                                $query = 'SELECT d.id, d.nome, d.cognome FROM universita.docente d';
                                $result = pg_prepare($conn, "corsi", $query);
                                $result = pg_execute($conn, "corsi", array());

                                while ($row = pg_fetch_assoc($result)){
                                    $idDoc[] = $row['id'];
                                    $nomiDoc[] = $row['nome'];
                                    $cognomiDoc[] = $row['cognome'];
                                }

                                for ($i = 0; $i < count($idDoc); $i++){
                                    echo '<option value="' . $idDoc[$i] . '">' . $idDoc[$i] . " " . $nomiDoc[$i] . " " . $cognomiDoc[$i] . '</option>';
                                }

                                pg_close($conn);
                            ?>
                        </select>
                    </div>
                    <div id="bottone" class="row m-auto mb-5">
                        <button name="submitDocente" type="submit" class="btn btn-custom">Modifica Docente</button>
                    </div>
                </form>
                <form action="modificaCorso.php" method="POST">
                    <div id="corso" class="row m-auto mb-2">
                        <select name="id3" class="form-select custom-bg" id="inputGroupSelect05">
                            <option selected>Id Corso di Laurea</option>
                            <?php
                                $conn = pg_connect("host=localhost user=postgres password=FiatPanda8 dbname=Esame_Basi_Di_Dati")
                                        or die('Could not connect: ' . pg_last_error());

                                $query = 'SELECT c.id, c.nome FROM universita.corso_di_laurea c';
                                $result = pg_prepare($conn, "corsi", $query);
                                $result = pg_execute($conn, "corsi", array());

                                while ($row = pg_fetch_assoc($result)){
                                    $id[] = $row['id'];
                                    $nomiIns[] = $row['nome'];
                                }

                                for ($i = 0; $i < count($id); $i++){
                                    echo '<option value="' . $id[$i] . '">' . $nomiIns[$i] . '</option>';
                                }

                                unset($id);
                                unset($nomiIns);
                                
                                pg_close($conn);
                            ?>
                        </select>
                    </div>
                    <div id="rigaNome" class="row m-auto mb-2">
                        <input name="nome" type="text" class="form-control custom-bg" id="InputNome" aria-describedby="nomeHelp" placeholder="Nuovo Nome">  
                    </div>
                    <div id="bottone" class="row m-auto mb-5">
                        <button name="submitNome" type="submit" class="btn btn-custom">Modifica Nome</button>
                    </div>
                </form>

                <form action="modificaCorso.php" method="POST">
                    <div id="rigaCorsi" class="row m-auto mb-2">
                        <select name="idCor" class="form-select custom-bg" id="inputGroupSelect05">
                            <option selected>Id Corso di Laurea</option>
                            <?php
                                $conn = pg_connect("host=localhost user=postgres password=FiatPanda8 dbname=Esame_Basi_Di_Dati")
                                        or die('Could not connect: ' . pg_last_error());

                                $query = 'SELECT c.id, c.nome FROM universita.corso_di_laurea c';
                                $result = pg_prepare($conn, "corsi", $query);
                                $result = pg_execute($conn, "corsi", array());

                                while ($row = pg_fetch_assoc($result)){
                                    $id[] = $row['id'];
                                    $nomiIns[] = $row['nome'];
                                }

                                for ($i = 0; $i < count($id); $i++){
                                    echo '<option value="' . $id[$i] . '">' . $nomiIns[$i] . '</option>';
                                }

                                unset($id);
                                unset($nomiIns);
                                
                                pg_close($conn);
                            ?>
                        </select>
                    </div>

                    <div id="rigaInsegnamenti" class="row m-auto mb-2">
                        <select name="idIns" class="form-select custom-bg" id="inputGroupSelect05">
                            <option selected>Id Insegnamento</option>
                            <?php
                                $conn = pg_connect("host=localhost user=postgres password=FiatPanda8 dbname=Esame_Basi_Di_Dati")
                                        or die('Could not connect: ' . pg_last_error());

                                $query = 'SELECT i.id, i.nome FROM universita.insegnamento i';
                                $result = pg_prepare($conn, "corsi", $query);
                                $result = pg_execute($conn, "corsi", array());

                                while ($row = pg_fetch_assoc($result)){
                                    $id[] = $row['id'];
                                    $nomiIns[] = $row['nome'];
                                }

                                for ($i = 0; $i < count($id); $i++){
                                    echo '<option value="' . $id[$i] . '">' . $nomiIns[$i] . '</option>';
                                }

                                unset($id);
                                unset($nomiIns);
                                
                                pg_close($conn);
                            ?>
                        </select>
                    </div>

                    <div id="bottoneAggiunta" class="row m-auto mb-5">
                        <button name="aggiungiInsegnamento" type="submit" class="btn btn-custom">Aggiungi Insegnamento</button>
                    </div>
                </form>

                <form id="formRimozione" action="modificaCorso.php" method="POST">
                    <div id="rigaCorsi" class="row m-auto mb-2">
                        <select name="idCor2" class="form-select custom-bg" id="inputCorso2">
                            <option <?php if (!($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['CDL']))) { echo 'selected';} ?>>Id Corsi</option>
                            <?php
                                $conn = pg_connect("host=localhost user=postgres password=FiatPanda8 dbname=Esame_Basi_Di_Dati")
                                        or die('Could not connect: ' . pg_last_error());

                                $query = 'SELECT c.id, c.nome FROM universita.corso_di_laurea c';
                                $result = pg_prepare($conn, "corsi", $query);
                                $result = pg_execute($conn, "corsi", array());

                                while ($row = pg_fetch_assoc($result)){
                                    $id[] = $row['id'];
                                    $nomiIns[] = $row['nome'];
                                };

                                for ($i = 0; $i < count($id); $i++){
                                    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['CDL'])) { 
                                        $corso = $_POST['idCor2'];
                                        if ($id[$i] == $corso){
                                            echo '<option selected value="' . $id[$i] . '">' . $nomiIns[$i] . '</option>';
                                        } else {
                                            echo '<option value="' . $id[$i] . '">' . $nomiIns[$i] . '</option>';        
                                        }
                                    }else {
                                        echo '<option value="' . $id[$i] . '">' . $nomiIns[$i] . '</option>';
                                    }
                                }

                                unset($id);
                                unset($nomiIns);
                                
                                pg_close($conn);
                            ?>
                        </select>
                    </div>

                    <div id="rigaBottoneConferma" class="row m-auto mb-5">
                        <button name="CDL" type="submit" class="btn btn-custom">Conferma Corso</button>
                    </div>

                    <?php
                        $conn = pg_connect("host=localhost user=postgres password=FiatPanda8 dbname=Esame_Basi_Di_Dati ")
                        or die('Could not connect: ' . pg_last_error());
                        
                        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['CDL'])) {
                            echo '<div id="rigaInsegnamenti" class="row m-auto mb-2">
                                    <select name="idIns2" class="form-select custom-bg" id="inputGroupSelect05">
                                        <option selected>Id Insegnamento</option>';
                            
                            $query = 'SELECT i.id, i.nome FROM universita.insegnamento i INNER JOIN universita.programma p ON i.id = p.id_insegnamento WHERE p.id_corso_di_laurea = $1';
                                $idCorso= $_POST['idCor2'];
                                $parametri3 = Array(
                                    $idCorso
                                );
                                $result = pg_prepare($conn, "corsi", $query);
                                $result = pg_execute($conn, "corsi", $parametri3);

                                while ($row = pg_fetch_assoc($result)){
                                    $idInsegnamenti[] = $row['id'];
                                    $nomiInsegnamenti[] = $row['nome'];
                                }

                                for ($i = 0; $i < count($idInsegnamenti); $i++){
                                    echo '<option value="' . $idInsegnamenti[$i] . '">' . $nomiInsegnamenti[$i] . '</option>';
                                }
  
                            pg_close($conn);

                            echo '</select>
                                </div>';
                        }
                    ?>

                    <div id="bottoneRimozione" class="row m-auto">
                        <button name="rimuoviInsegnamento" type="submit" class="btn btn-custom">Rimuovi Insegnamento</button>
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

    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submitNome'])) {
        // Recupera i dati dal modulo
        $id3 = $_POST['id3'];
        $nome = $_POST['nome'];
        
        // Query di inserimento
        $query = 'UPDATE universita.corso_di_laurea SET nome = $2 WHERE corso_di_laurea.id = $1 ';
        
        $parametri = Array(
            $id3,
            $nome
        );

        $result = pg_prepare($conn, "modifica_nome", $query);
        $result = pg_execute($conn, "modifica_nome", $parametri);
        
        if ($result) {
            echo "Aggiornamento riuscito!";
        } else {
            echo "Errore nell'esecuzione della query: " . pg_last_error();
        }
        
        pg_close($conn);
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submitDocente'])) {
        // Recupera i dati dal modulo
        $id2 = $_POST['id2'];
        $docente = $_POST['idDocente'];
        
        // Query di inserimento
        $query = 'UPDATE universita.corso_di_laurea SET id_docente = $2 WHERE corso_di_laurea.id = $1 ';
        
        $parametri = Array(
            $id2,
            $docente
        );

        $result = pg_prepare($conn, "modifica_docente", $query);
        $result = pg_execute($conn, "modifica_docente", $parametri);
        
        if ($result) {
            echo "Aggiornamento riuscito!";
        } else {
            echo "Errore nell'esecuzione della query: " . pg_last_error();
        }
        
        pg_close($conn);
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['aggiungiInsegnamento'])) {
         // Recupera i dati dal modulo
        $idCors = $_POST['idCor'];
        $idIns = $_POST['idIns'];
        
        // Query di inserimento
        $query = 'INSERT INTO universita.programma(id_insegnamento, id_corso_di_laurea) VALUES ($2, $1); ';
        
        $parametri = Array(
            $idCors,
            $idIns
        );

        $result = pg_prepare($conn, "modifica_docente", $query);
        $result = pg_execute($conn, "modifica_docente", $parametri);
        
        if ($result) {
            echo "Inserimento riuscito!";
        } else {
            echo "Errore nell'esecuzione della query: " . pg_last_error();
        }
        
        pg_close($conn);
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['rimuoviInsegnamento'])) {
         // Recupera i dati dal modulo
        $idCors2 = $_POST['idCor2'];
        $idIns2 = $_POST['idIns2'];
        
        // Query di inserimento
        $query = 'DELETE FROM universita.programma WHERE programma.id_insegnamento = $1 AND programma.id_corso_di_laurea = $2 ';
        
        $parametri = Array(
            $idIns2,
            $idCors2
        );

        $result = pg_prepare($conn, "modifica_docente", $query);
        $result = pg_execute($conn, "modifica_docente", $parametri);
        
        if ($result) {
            echo "Rimozione riuscita!";
        } else {
            echo "Errore nell'esecuzione della query: " . pg_last_error();
        }
        
        pg_close($conn);
    }
?>
