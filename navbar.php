<nav class="navbar navbar-expand-lg navbar-light bg-light mb-5">
        <a class="navbar-brand" >Progetto Basi di Dati</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
        <div class="navbar-nav">
            <?php 
                
                if(isset($_SESSION['username'])){
                    $logout_link= "login.php";

                    $conn = pg_connect("host=localhost user=postgres password=FiatPanda8 dbname=Esame_Basi_Di_Dati")
                    or die('Could not connect: ' . pg_last_error());

                    $tipo = $_SESSION['tipo'];
                    $user = $_SESSION['username'];
                    $link="";
                    
                    switch ($tipo){
                        case "segreteria":
                            $query="SELECT s.nome, s.cognome FROM universita.segreteria s INNER JOIN universita.indirizzo i ON s.cap=i.cap WHERE s.email = $1";
                            $link = "segreteria.php";
                            break;
                        case "studente":
                            $query="SELECT s.nome, s.cognome FROM universita.studente s INNER JOIN universita.indirizzo i ON s.cap=i.cap WHERE s.email = $1";
                            $link = "studente.php";
                            break;
                        case "docente":
                            $query="SELECT  d.nome, d.cognome FROM universita.docente d INNER JOIN universita.indirizzo i ON d.cap=i.cap WHERE d.email = $1";
                            $link = "docente.php";
                            break;
                    }

                    $parametri = array(
                        $user
                    );

                    $result = pg_prepare($conn, "info", $query);
                    $result = pg_execute($conn, "info", $parametri);

                    $row = pg_fetch_assoc($result);
                    $nome = $row['nome'];
                    $cognome = $row['cognome'];
                    $loggato = $nome . " " . $cognome;
            ?>
                <a class="nav-item nav-link" href="<?php echo $link ?>" ><?php echo("Benvenuto/a $loggato - ");?></a>
                <a class="nav-item nav-link active" href="infoAccount.php">Info Utente</a>
                <a class="nav-item nav-link active" href="modificaPassword.php">Cambia Password</a>
                <a class="nav-item nav-link active" href="logOutFunction.php">Logout</a>
            <?php
                }
            ?>
        </div>
        </div>
    </nav>