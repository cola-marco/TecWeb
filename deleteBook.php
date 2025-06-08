<?php
    require 'utils.php';
    //session_start();
    $pdo = connectDB();

    if(!$pdo){
        //problema con connessione a db 
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
        }
    }
?>