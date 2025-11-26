<?php
// Zet foutmeldingen aan voor het debuggen
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Verbindt met de database
require 'database-connectie-Tim.php';

// Zorgt dat fouten van PDO netjes worden weergegeven
try {
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die("Verbinding mislukt: " . $e->getMessage());
}

// Hierin komen foutmeldingen te staan
$errors = [];

// Mogelijke keuzes voor klas en minuten
$klassen = ['3A', '3B', '3C'];
$minuten_opties = [5, 10, 15, 20, 25, 30, 35, 40, 45, 50, 55, 60, 65, 70, 75, 80, 85, 90, 95, 100, 105, 110, 115, 120];

// Maximale lengte voor naam en reden
$max_naam = 40;
$max_reden = 100;

// Als het formulier is verstuurd (POST)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Trim verwijderd alle spaties aan het begin en einde van de tekst
    $naam = trim($_POST['naam'] ?? '');
    $klas = trim($_POST['klas'] ?? '');
    $minuten = trim($_POST['minuten'] ?? '');
    $reden = trim($_POST['reden'] ?? '');

    // Controleer of alles goed is ingevuld
    if ($naam === '') {
        $errors[] = "Naam is verplicht.";
        // mb_strlen telt het aantal tekens in een string
    } elseif (mb_strlen($naam) > $max_naam) {
        $errors[] = "Naam mag maximaal $max_naam tekens zijn.";
    }
    // !in_array controleert of de waarde niet voorkomt in een array
    if ($klas === '' || !in_array($klas, $klassen)) {
        $errors[] = "Klas is verplicht.";
    }
    // !is_numeric controleert of de waarde niet een getal is
    if (!is_numeric($minuten) || (int)$minuten < 0 || (int)$minuten > 120) {
        $errors[] = "Minuten te laat moet een getal tussen 0 en 120 zijn.";
    }
    if ($reden === '') {
        $errors[] = "Reden is verplicht.";
    } elseif (mb_strlen($reden) > $max_reden) {
        $errors[] = "Reden mag maximaal $max_reden tekens zijn.";
    }

    // empty controleert of $errors leeg is als ie leeg is geeft hij true terug
    if (empty($errors)) {
        $stmt = $conn->prepare("INSERT INTO meldingen (naam, klas, minuten_te_laat, reden, datum) VALUES (?, ?, ?, ?, NOW())");
        $stmt->execute([$naam, $klas, $minuten, $reden]);
        // Ga terug naar het overzicht
        header("Location: main.php");
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Te laat registratie</title>
    <style>
        body {
            background: #f8f8f8;
            font-family: Arial, sans-serif;
            padding: 0;
            margin: 0;
        }
        .container {
            background: #fff;
            max-width: 420px;
            margin: 50px auto 0 auto;
            padding: 32px 36px 28px 36px;
            border-radius: 10px;
            box-shadow: 0 4px 24px rgba(0,0,0,0.10);
        }
        h1 {
            text-align: center;
            color: #0074D9;
            margin-bottom: 28px;
        }
        form label {
            display: block;
            margin-bottom: 12px;
            color: #333;
            font-weight: bold;
        }
        input[type="text"], select, input[type="number"] {
            width: 100%;
            padding: 9px 10px;
            margin-top: 4px;
            margin-bottom: 18px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 15px;
            background: #f9f9f9;
            box-sizing: border-box;
        }
        button[type="submit"] {
            background: #0074D9;
            color: #fff;
            border: none;
            padding: 12px 0;
            width: 100%;
            border-radius: 5px;
            font-size: 17px;
            font-weight: bold;
            cursor: pointer;
            transition: background 0.2s;
        }
        button[type="submit"]:hover {
            background: #005fa3;
        }
        ul {
            color: #e74c3c;
            margin-bottom: 18px;
            padding-left: 20px;
        }
        .back-link {
            display: block;
            text-align: center;
            margin-top: 18px;
            color: #0074D9;
            text-decoration: none;
            font-size: 15px;
        }
        .back-link:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Te laat registratie</h1>
        <!-- Laat foutmeldingen zien als die er zijn -->
        <?php if (!empty($errors)): ?>
            <ul>
                <?php foreach ($errors as $error): ?>
                    <li><?= ($error) ?></li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>
        <!-- Het formulier om een melding toe te voegen -->
        <form method="post">
            <label>Naam:
                <input type="text" name="naam" maxlength="<?= $max_naam ?>" value="<?= ($_POST['naam'] ?? '') ?>">
            </label>
            <label>Klas:
                <select name="klas">
                    <option value="">-- Kies klas --</option>
                    <?php foreach ($klassen as $optie): ?>
                        <option value="<?= $optie ?>" <?= (isset($_POST['klas']) && $_POST['klas'] === $optie) ? 'selected' : '' ?>><?= $optie ?></option>
                    <?php endforeach; ?>
                </select>
            </label>
            <label>Minuten te laat:
                <select name="minuten">
                    <?php foreach ($minuten_opties as $optie): ?>
                        <option value="<?= $optie ?>" <?= (isset($_POST['minuten']) && $_POST['minuten'] == $optie) ? 'selected' : '' ?>><?= $optie ?></option>
                    <?php endforeach; ?>
                </select>
            </label>
            <label>Reden:
                <input type="text" name="reden" maxlength="<?= $max_reden ?>" value="<?= ($_POST['reden'] ?? '') ?>">
            </label>
            <button type="submit">Opslaan</button>
        </form>
        <!-- Link terug naar het overzicht -->
        <a href="main.php" class="back-link">&larr; Terug naar overzicht</a>
    </div>
</body>
</html>