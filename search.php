<?php
    require 'ConnectionDB.php';
    require 'catalogo.php';
    $pdo = connectDB();

    if($_POST['search']){
        $search = $_POST['search'];

        $query = $pdo->prepare("SELECT * FROM Libri JOIN Autori WHERE Autore = ID_Autore AND Titolo = :search OR Nome = :search OR Cognome = :search");
        $query->bindParam(':search', $search, PDO::PARAM_INT);
        $query->execute();
        $result = $query->fetchAll(PDO::FETCH_ASSOC);

        $lista_libri = book_display($result);
    }
?>