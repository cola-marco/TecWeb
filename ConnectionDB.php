<?php 
function connectDB() {
    $host = 'localhost';                         
    $dbname = 'progettotecweb';          
    $userdbname = 'root';          
    $passwordDB = '';
    try {
        $dsn = "mysql:host=$host;dbname=$dbname;charset=utf8mb4";
        $pdo = new PDO($dsn, $userdbname, $passwordDB);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $pdo;
    } catch (PDOException $e) {
        echo "Connessione fallita: " . $e->getMessage();
        exit(); //se connessione al database è fallita esce dal flusso 
    }
}
?>