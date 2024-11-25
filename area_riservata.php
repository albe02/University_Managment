<?php
session_start();

// Verifica se l'utente è connesso
if (!isset($_SESSION['username'])) {
    header('Location: login.html'); // Se non è connesso, reindirizza alla pagina di login
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Area Riservata</title>
</head>
<body>
    <h2>Benvenuto nell'area riservata, <?php echo $_SESSION['username']; ?>!</h2>
    
    <?php
    // Mostra il contenuto basato sul ruolo dell'utente
    if ($_SESSION['ruolo'] === 'amministratore') {
        echo "<p>Sei un amministratore e hai accesso a tutte le funzionalità.</p>";
    } elseif ($_SESSION['ruolo'] === 'utente_normale') {
        echo "<p>Sei un utente normale e hai accesso alle funzionalità di base.</p>";
    }
    ?>

    <a href="logout.php">Esci</a> <!-- Aggiungi un link per effettuare il logout -->
</body>
</html>
