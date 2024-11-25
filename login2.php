<?php
$conn = pg_connect("host=localhost port=443 dbname=Esame_Basi_Di_Dati user=postgres password=FiatPanda8");
if (!$conn) {
    die("Errore di connessione al database: " . pg_last_error());
}

$username = $_POST['username'];
$password = $_POST['password'];
$tipo = $_POST['ruolo'];

$query = "SELECT * FROM '$tipo' WHERE email = '$username' AND password = '$password'";
$result = pg_query($conn, $query);

if (!$result) {
    die("Errore nell'esecuzione della query: " . pg_last_error());
}

$row = pg_fetch_assoc($result);

if (!$row) {
    echo "Credenziali non valide";
} else {
    session_start(); // Inizia la sessione
    $_SESSION['username'] = $row['username']; // Imposta una variabile di sessione per gestire l'accesso
    header('Location: area_riservata.php'); // Reindirizza l'utente a un'area riservata
}

pg_close($conn);
?>
