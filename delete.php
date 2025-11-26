<?php
// Verbindt met de database
require 'database-connectie-Tim.php';

// Controleer of er een geldig id is meegegeven in de URL
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("Geen geldig ID opgegeven.");
}
$id = (int)$_GET['id'];

// Als het formulier is verstuurd (POST)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Als de gebruiker bevestigt, verwijder de melding
    if (isset($_POST['bevestigen']) && $_POST['bevestigen'] === 'ja') {
        $stmt = $conn->prepare("DELETE FROM meldingen WHERE id = :id");
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        // Na verwijderen, terug naar het overzicht
        header("Location: main.php");
        exit;
    } else {
        // Als gebruiker niet bevestigt, ook terug naar het overzicht
        header("Location: main.php");
        exit;
    }
}

// Haal de melding op uit de database om te tonen wat je gaat verwijderen
$stmt = $conn->prepare("SELECT * FROM meldingen WHERE id = :id");
$stmt->bindParam(':id', $id);
$stmt->execute();
$melding = $stmt->fetch(PDO::FETCH_ASSOC);

// Als de melding niet bestaat, stop dan
if (!$melding) {
    die("Melding niet gevonden.");
}
?>

<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <title>Verwijderen bevestigen</title>
    <style>
        /* Opmaak van de pagina */
        body { background: #f8f8f8; font-family: Arial, sans-serif; }
        .container {
            background: #fff;
            max-width: 400px;
            margin: 60px auto 0 auto;
            padding: 28px 24px 24px 24px;
            border-radius: 8px;
            box-shadow: 0 2px 12px rgba(0,0,0,0.12);
            text-align: center;
        }
        .btn {
            padding: 10px 18px;
            border: none;
            border-radius: 4px;
            font-size: 15px;
            font-weight: bold;
            cursor: pointer;
            margin: 8px;
        }
        .btn-yes { background: #e74c3c; color: #fff; }
        .btn-no { background: #bbb; color: #222; }
    </style>
</head>
<body>
    <div class="container">
        <h2>Weet je zeker dat je deze melding wilt verwijderen?</h2>
        <!-- Toon de gegevens van de melding zodat je weet wat je verwijdert -->
        <p>
            <strong><?= ($melding['naam']) ?></strong> uit <strong><?= ($melding['klas']) ?></strong><br>
            Minuten te laat: <?= ($melding['minuten_te_laat']) ?><br>
            Reden: <?= ($melding['reden']) ?>
        </p>
        <!-- Formulier met twee knoppen: Ja (verwijderen) of Nee (terug) -->
        <form method="post">
            <button type="submit" name="bevestigen" value="ja" class="btn btn-yes">Ja, verwijderen</button>
            <button type="submit" name="bevestigen" value="nee" class="btn btn-no">Nee, terug</button>
        </form>
    </div>
</body>
</html>