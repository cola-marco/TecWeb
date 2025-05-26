<?php
    require 'utils.php';
    connectDB();

    $query = $pdo->prepare("INSERT INTO Wishlist (Cliente, Libro) VALUES (:user, :id_libro)");
    $query->bindParam(':user', $user, PDO::PARAM_STR);
    $query->bindParam(':id_libro', $id_libro, PDO::PARAM_STR);
    $query->execute();
    $result = $query->fetchAll(PDO::FETCH_ASSOC);
?>