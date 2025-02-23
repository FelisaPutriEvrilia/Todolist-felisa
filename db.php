<?php
$host = 'localhost'; // Server MySQL (biasanya 'localhost')
$dbname = 'db_todolistfelis'; // Pastikan nama database benar
$username = 'root'; // Username default XAMPP
$password = ''; // Kosongkan jika tidak ada password

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}
?>
