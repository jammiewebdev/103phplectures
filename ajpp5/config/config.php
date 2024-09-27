<?php
$host = 'localhost';
$dbname = 'u606518727_rs_webapp_db';
$username = 'u606518727_ajpp5trial';
$password = 'AJpp5trial-1';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}
?>

