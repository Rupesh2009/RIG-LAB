<?php
// Directly assign database credentials
$host = 'localhost';
$dbname = 'rupeshjha_riglab';
$username = 'rupeshjha_admin';  // Replace with your actual MySQL username
$password = 'Rupesh@2009';  // Replace with your actual MySQL password

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Make $pdo globally accessible (optional)
    global $pdo;

    

} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}
?>
