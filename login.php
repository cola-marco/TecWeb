<?php
    require 'utils.php';
    $pdo = connectDB();

    /*if(!isset($_SESSION['is_logged'])){
        $DOM = file_get_contents('html/login.html');
        echo $DOM;
    }*/
    //else{
        $DOM = file_get_contents("html/area-riservata.html");
        $cliente = $_SESSION["ID_Cliente"];
        $wishlist_query = $pdo->prepare("SELECT * FROM Whishlist, Libri, Clienti WHERE Libro = ID_libro AND Cliente = :cliente");
        $wishlist_query->bindParam(':cliente', $cliente, PDO::PARAM_STR);
        $wishlist_query->execute();
        $result = $wishlist_query->fetchAll(PDO::FETCH_ASSOC);

        if(count($result) > 0){
            $wishlist = book_display($result);
        }

        $result = "";
        $personal_query = $pdo->prepare("SELECT * FROM Clienti WHERE ID_Cliente = :cliente");
        $personal_query->bindParam(':cliente', $cliente, PDO::PARAM_STR);
        $personal_query->execute();
        $result = $personal_query->fetchAll(PDO::FETCH_ASSOC);

        $DOM = str_replace('###LISTA-WISHLIST###', $wishlist, $DOM);
        $personal_data = "<li>".$result["Username"]."</li>";
        $personal_data = "<li>".$result["Nome"]."</li>";
        $personal_data = "<li>".$result["Cognome"]."</li>";
        $personal_data = "<li>".$result["Email"]."</li>";
        $personal_data = "<li>".$result["Telefono"]."</li>";
    //}
?>