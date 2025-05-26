<?php
    require 'utils.php';
    $pdo = connectDB();

    /*if(!isset($_SESSION['is_logged'])){
        $DOM = file_get_contents('html/login.html');
    }*/
    //else{
        $_SESSION["ID_Cliente"] = 1;

        $DOM = file_get_contents("html/area-riservata.html");
        $cliente = $_SESSION["ID_Cliente"];
        $wishlist_query = $pdo->prepare("SELECT * FROM Wishlist, Libri, Clienti WHERE Libro = ID_libro AND Cliente = :cliente");
        $wishlist_query->bindParam(':cliente', $cliente, PDO::PARAM_STR);
        $wishlist_query->execute();
        $result = $wishlist_query->fetchAll(PDO::FETCH_ASSOC);

        if(count($result) > 0){
            $wishlist = book_display($result);
        }
        else{
            $wishlist = "<p>Nessun libro salvato, se ne vuoi salvare alcuni vai al <a href=\"catalogo.php\">catalogo</a>.</p>";
        }

        $personal_query = $pdo->prepare("SELECT * FROM Clienti WHERE ID_Cliente = :cliente");
        $personal_query->bindParam(':cliente', $cliente, PDO::PARAM_STR);
        $personal_query->execute();
        $personal_result = $personal_query->fetch(PDO::FETCH_ASSOC);


        if(count($personal_result) > 0){
            $personal_data = "<li> Username: " .  $personal_result["Username"] . "</li>";
            $personal_data = $personal_data . "<li> Nome:". $personal_result["Nome"] ."</li>";
            $personal_data = $personal_data . "<li> Cognome: ". $personal_result["Cognome"] ."</li>";
            $personal_data = $personal_data . "<li> Email: ". $personal_result["Email"] ."</li>";
            $personal_data = $personal_data . "<li> Telefono:". $personal_result["Telefono"] ."</li>";
        }
        else{
            $personal_data = "Nessun cliente";
        }
        
        $DOM = str_replace('###LISTA-WISHLIST###', $wishlist, $DOM);
        $DOM = str_replace('###DATI-PERSONALI###', $personal_data, $DOM);
    //}
    echo $DOM;
?>