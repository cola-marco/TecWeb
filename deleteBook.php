<?php
    require 'utils.php';
    session_start();
    $pdo = connectDB();
    if (!isset($_SESSION['ID_Cliente']) || $_SESSION['ruolo'] !== 'Admin') { //admin non loggato
        header('Location: index.php');
        exit();
    }
    if(!$pdo){
        //problema con connessione a db 
        header("location: 505.php"); 
        exit();
    }
    else {
        $idDeletedBook = $_GET['id'];
        $stmt = $pdo->prepare('DELETE FROM Libri WHERE ID_Libro = :id_libro');
        $stmt->bindParam(':id_libro', $idDeletedBook, PDO::PARAM_INT);
        $stmt->execute();
        if($stmt->rowCount() > 0){ //libro cancellato con successo
            header('Location: admin.php');
            exit();
        }
        else {
            //problema con l'esecuzione della query
            header("location: 505.php"); 
            exit();
        }
    }
?>