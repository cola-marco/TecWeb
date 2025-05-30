<?php
    include "templates/header.php";
    require 'utils.php';
    $pdo = connectDB();
    session_start();

    $DOM = file_get_contents('html/libro.html');
    $id_libro = $_GET["id_libro"];
    $user = $_SESSION["ID_Cliente"];
    
    if (!isset($_GET["id_libro"])) {
        header("location: catalogo.php");
    }
    else{
        $DOM = displayBookInfo($DOM, $pdo, $id_libro);

        if(isLogged() == 1){
            if(isSaved($pdo, $id_libro, $user)){
                $DOM = str_replace('###STAR###', "<p>Questo libro è già all'interno della tua <a href='login.php'>wishlist</a>.</p>", $DOM);
            }
            else{
                $DOM = str_replace('###STAR###', '<form action="#" method="post"><button type="submit" name="wish" value="true"><svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#434343"><path d="m354-287 126-76 126 77-33-144 111-96-146-13-58-136-58 135-146 13 111 97-33 143ZM233-120l65-281L80-590l288-25 112-265 112 265 288 25-218 189 65 281-247-149-247 149Zm247-350Z"/></svg></button></form>', $DOM);
            }

            if(isset($_POST["wish"]) && $_POST["wish"] == true){
                $insert = addToWishlist($pdo, $user, $id_libro);
                if($insert == true) $DOM = str_replace('<form action="#" method="post"><button type="submit" name="wish" value="true"><svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#434343"><path d="m354-287 126-76 126 77-33-144 111-96-146-13-58-136-58 135-146 13 111 97-33 143ZM233-120l65-281L80-590l288-25 112-265 112 265 288 25-218 189 65 281-247-149-247 149Zm247-350Z"/></svg></button></form>', '<p>Libro salvato nella tua <a href="login.php">wishlist</a>.</p>', $DOM);
                else echo $insert;
            }
        } 
        else if(isLogged() == 0 || isLogged() == -1) $DOM = str_replace('<button type="submit">###STAR###</button>', '<p class="login-request">Per salvare un libro nella tua wishlist ti preghiamo di <a href="login.php">accedere</a>.</p>', $DOM);    
        
        echo $DOM;
    }
    include "templates/footer.php";
?>