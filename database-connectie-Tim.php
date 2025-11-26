<?php
if($_SERVER['HTTP_HOST'] === 'localhost') {
    $servername = "localhost";
    $database = "te_laat_meldingen";
    $username = "root";
    $password = "";    
}
else
{
    $servername = "localhost";
    $database = "2207975-database1";
    $username = "crud.3";
    $password = "Junkert023";
}
    $conn = new PDO("mysql:host=$servername;dbname=$database",$username, $password);
    ?>