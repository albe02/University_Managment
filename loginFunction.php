<?php

$conn = pg_connect("host=localhost user=postgres password=FiatPanda8 dbname=Esame_Basi_Di_Dati ")
or die('Could not connect: ' . pg_last_error());

$username = $_POST['email'];
$password = md5($_POST['password']);
$tipo = $_POST['ruolo'];

$query = '';

switch ($tipo){
    case "segreteria":
        $query="SELECT * FROM \"universita\".\"segreteria\" WHERE email = $1 AND password = $2";
        break;
    case "studente":
        $query="SELECT * FROM \"universita\".\"studente\" WHERE email = $1 AND password = $2";
        break;
    case "docente":
        $query="SELECT * FROM \"universita\".\"docente\" WHERE email = $1 AND password = $2";
        break;
}

$parametri = Array(
    $username,
    $password
);

$result = pg_prepare($conn, "controllo utente", $query);
$result = pg_execute($conn, "controllo utente", $parametri);

if (!$result) {
    die("Errore nell'esecuzione della query:" . pg_last_error());
}

$row = pg_fetch_assoc($result);

if (!$row) {
    echo "Credenziali non valide";
} else {
    session_start(); // Inizia la sessione
    $_SESSION['username'] = $row['email']; // Imposta una variabile di sessione per gestire l'accesso
    switch ($tipo){
        case "segreteria":
            $_SESSION['tipo'] = 'segreteria';
            header('Location: Segreteria.php');
            break;
        case "studente":
            $_SESSION['tipo'] = 'studente';
            header('Location: Studente.php');
            break;
        case "docente":
            $_SESSION['tipo'] = 'docente';
            header('Location: Docente.php');
            break;
    }
}

pg_close($conn);
?>
