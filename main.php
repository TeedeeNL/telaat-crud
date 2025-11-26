<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CRUD Challenge - Te Laat Meldingen</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f8f8f8;
            padding: 40px;
        }
        table {
            border-collapse: collapse;
            width: 60%;
            margin: 0 auto;
            background: #fff;
            box-shadow: 0 2px 8px rgba(0,0,0,0.06);
        }
        th, td {
            border: 1px solid #ddd;
            padding: 12px 16px;
            text-align: left;
        }
        th {
            background: #0074D9;
            color: #fff;
        }
        tr:nth-child(even) {
            background: #f2f2f2;
        }
        tr:hover {
            background: #e6f7ff;
        }
        h2 {
            text-align: center;
            color: #333;
        }
        .verwijder-btn {
            background: #e74c3c;
            color: #fff;
            padding: 6px 14px;
            border: none;
            border-radius: 4px;
            text-decoration: none;
            font-weight: bold;
            transition: background 0.2s;
        }
        .verwijder-btn:hover {
            background: #c0392b;
        }
        .update-btn {
            background: #27ae60;
            color: #fff;
            padding: 6px 14px;
            border: none;
            border-radius: 4px;
            text-decoration: none;
            font-weight: bold;
            transition: background 0.2s;
        }
        .update-btn:hover {
            background: #1e8449;
        }
        .toevoegen-btn {
            background: #0074D9;
            color: #fff;
            padding: 10px 18px;
            border: none;
            border-radius: 4px;
            font-size: 16px;
            font-weight: bold;
            text-decoration: none;
            margin-bottom: 20px;
            display: inline-block;
            transition: background 0.2s;
            cursor: pointer;
        }
        .toevoegen-btn:hover {
            background: #005fa3;
        }
        .top-bar {
            width: 60%;
            margin: 0 auto 20px auto;
            text-align: right;
        }
        .stats-table {
            margin-top: 30px;
            width: 60%;
            margin-left: auto;
            margin-right: auto;
            background: #fff;
            box-shadow: 0 2px 8px rgba(0,0,0,0.06);
            border-collapse: collapse;
        }
        .stats-table th, .stats-table td {
            border: 1px solid #ddd;
            padding: 12px 16px;
            text-align: left;
        }
        .stats-table th {
            background: #0074D9;
            color: #fff;
            text-align: center;
        }
    </style>
</head>
<body>
    <!-- Bovenaan een knop om een nieuwe melding toe te voegen -->
    <div class="top-bar">
        <a href="create.php" class="toevoegen-btn">Weer eentje te laat!</a>
    </div>
    <?php
    // Verbindt met de database
    require 'database-connectie-Tim.php';

    // Haal alle meldingen op uit de database
    $sql = "SELECT * FROM meldingen";
    $result = $conn->query($sql);

    // Maak een tabelkop met kolomnamen
    echo "<table border='0'>";
    echo "<tr><th>Naam</th><th>Klas</th><th>Minuten te laat</th><th>Reden</th><th>Datum</th><th>Acties</th></tr>";

    // Loop door alle rijen (meldingen) en toon ze in de tabel
    foreach ($result as $row) {
        echo "<tr>";
        echo "<td>" . ($row['naam']) . "</td>"; // Naam van de leerling
        echo "<td>" . ($row['klas']) . "</td>"; // Klas
        echo "<td>" . ($row['minuten_te_laat']) . "</td>"; // Minuten te laat
        echo "<td>" . ($row['reden']) . "</td>"; // Reden
        echo "<td>" . ($row['datum']) . "</td>"; // Datum
        // Links om te verwijderen of updaten
        echo "<td>
            <a class='verwijder-btn' href='delete.php?id=" . ($row['id']) . "'>Verwijderen</a>
            <a class='update-btn' href='update.php?id=" . ($row['id']) . "'>Updaten</a>
        </td>";
        echo "</tr>";
    }
    echo "</table>";

    // Haal statistieken op: hoogste, gemiddelde en totaal aantal minuten te laat
    $statStmt = $conn->query("SELECT 
        MAX(minuten_te_laat) AS max_minuten, 
        AVG(minuten_te_laat) AS avg_minuten, 
        SUM(minuten_te_laat) AS totaal_minuten 
        FROM meldingen");
    $stats = $statStmt->fetch(PDO::FETCH_ASSOC);

    // Toon de statistieken in een aparte tabel
    echo "<table class='stats-table'>";
    echo "<tr><th colspan='2'>Statistieken</th></tr>";
    echo "<tr><td>Hoogste aantal minuten te laat</td><td>" . (int)$stats['max_minuten'] . "</td></tr>";
    // Als er geen gemiddelde is (is_null($stats['avg_minuten'])), wordt 0 getoond.
    // Anders wordt het gemiddelde afgerond op 1 decimaal met round($stats['avg_minuten'], 1).
    echo "<tr><td>Gemiddeld aantal minuten te laat</td><td>" . (is_null($stats['avg_minuten']) ? 0 : round($stats['avg_minuten'], 1)) . "</td></tr>";
    echo "<tr><td>Totaal aantal minuten te laat</td><td>" . (int)$stats['totaal_minuten'] . "</td></tr>";
    echo "</table>";
    ?>
</body>
</html>