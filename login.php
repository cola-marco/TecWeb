<?php
    require 'utils.php';
    $pdo = connectDB();
    session_start();

    if(!isset($_SESSION['is_logged_in']) || isset($_SESSION['is_logged_in']) && $_SESSION['is_logged_in'] == false){
        $DOM = file_get_contents('html/login.html');
    }
    else{
        $DOM = file_get_contents("html/area-riservata.html");
        $cliente = $_SESSION["ID_Cliente"];
        $wishlist_query = $pdo->prepare("SELECT * FROM Wishlist, Libri, Clienti, Autori WHERE Libro = ID_libro AND Autore = ID_Autore AND Cliente = :cliente");
        $wishlist_query->bindParam(':cliente', $cliente, PDO::PARAM_STR);
        $wishlist_query->execute();
        $result = $wishlist_query->fetchAll(PDO::FETCH_ASSOC);

        if(count($result) > 0){
            $li = '
                <li class="card">
                    <div>
                        <img src="###IMG-PATH###" alt="">
                    </div>
                    <div class="description">
                        <a href="libro.php?id_libro=###ID_LIBRO###"><h3>###TITOLO###</h3></a>
                        <h4>###AUTORE###</h4>
                        <p><strong>Trama</strong>:###TRAMA###</p>

                        <form action="#" method="post" class="delete-form">
                            <button type="submit" name="delete-from-wishlist" value="###ID_LIBRO###" class="delete-button"><svg xmlns="http://www.w3.org/2000/svg" height="30px" viewBox="0 -960 960 960" width="30px" fill="#000000"><path d="M280-120q-33 0-56.5-23.5T200-200v-520h-40v-80h200v-40h240v40h200v80h-40v520q0 33-23.5 56.5T680-120H280Zm400-600H280v520h400v-520ZM360-280h80v-360h-80v360Zm160 0h80v-360h-80v360ZM280-720v520-520Z"/></svg></button>
                        </form>
                    </div>
                </li>';

            $wishlist = book_display($result, $li);
        }
        else{
            $wishlist = "<p>Nessun libro salvato, se ne vuoi salvarne alcuni vai al <a href=\"catalogo.php\">catalogo</a>.</p>";
        }

        $personal_query = $pdo->prepare("SELECT * FROM Clienti WHERE ID_Cliente = :cliente");
        $personal_query->bindParam(':cliente', $cliente, PDO::PARAM_STR);
        $personal_query->execute();
        $personal_result = $personal_query->fetch(PDO::FETCH_ASSOC);


        if($personal_result && count($personal_result) > 0){
            $personal_data = "<li> Username: " .  $personal_result["Username"] . "</li>";
            $personal_data = $personal_data . "<li> Nome:". $personal_result["Nome"] ."</li>";
            $personal_data = $personal_data . "<li> Cognome: ". $personal_result["Cognome"] ."</li>";
            $personal_data = $personal_data . "<li> Email: ". $personal_result["Email"] ."</li>";
            $personal_data = $personal_data . "<li> Telefono:". $personal_result["Telefono"] ."</li>";
        }
        else{
            $personal_data = "Nessun cliente";
        }

        if(isset($_POST["delete-from-wishlist"]) && $_POST["delete-from-wishlist"] > 0){
            $delete = deleteFromWishlist($pdo, $cliente, $_POST["delete-from-wishlist"]);
            if($delete) header("Location: login.php");
            exit();
        }
        
        $DOM = str_replace('###LISTA-WISHLIST###', $wishlist, $DOM);
        $DOM = str_replace('###DATI-PERSONALI###', $personal_data, $DOM);
    }
    //echo $_SESSION["created"];
    echo $DOM;
?>