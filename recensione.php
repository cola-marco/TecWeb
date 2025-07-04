<?php
$metaDescription = 'Leggi o scrivi una recensione per i libri della nostra biblioteca.';
$metaKeywords    = 'recensioni libri, opinioni, utenti';

include "templates/header.php";
require 'utils.php';
$pdo = connectDB();
check_session_timeout();
if(!$pdo){
    header('Location: 505.php');
    exit();
}
if(isset($_POST["submit-review"]) && $_POST["submit-review"] > 0){
    $user = $_SESSION["ID_Cliente"];
    $id_libro = $_POST["submit-review"];
    if(isset($_POST["valutazione"])) $valutazione = pulisciInput($_POST["valutazione"]);
    else $valutazione = 0;
    $recensione = pulisciInput($_POST["mex"]);

    $query = $pdo->prepare("INSERT INTO Recensioni(Cliente, Libro, Valutazione, Recensione, Data) VALUES (:user, :id_libro, :valutazione, :recensione, NOW())");
    $query->bindParam(':user', $user, PDO::PARAM_STR);
    $query->bindParam(':id_libro', $id_libro, PDO::PARAM_STR);
    $query->bindParam(':valutazione', $valutazione, PDO::PARAM_STR);
    $query->bindParam(':recensione', $recensione, PDO::PARAM_STR);

    $result = $query->execute();
    if(!$result){
        header("location: 505.php"); 
        exit;
    } 
    else{
        $loc = "location: libro.php?id_libro=" . $id_libro;
        header($loc); 
        exit;
    }
}
?>
