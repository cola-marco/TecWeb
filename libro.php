<?php
    include "templates/header.php";
    require 'utils.php';
    $pdo = connectDB();
    //session_start();
    check_session_timeout();

    $DOM = file_get_contents('html/libro.html');
    $id_libro = $_GET["id_libro"];
    $user = $_SESSION["ID_Cliente"] ?? '';

    
    if (!isset($_GET["id_libro"])) {
        header("location: catalogo.php");
    }
    else{
        $DOM = displayBookInfo($DOM, $pdo, $id_libro);

        if(isLogged() == 1){
            $user = $_SESSION["ID_Cliente"];
            if($_SESSION['ruolo'] == 'Admin') $star = $form_recensione = '';

            if(isSaved($pdo, $id_libro, $user)) $star = '<p>Questo libro è già all\'interno della tua <a href="login.php">wishlist</a>.</p';
            else $star = '<form action="#" method="post"><button type="submit" name="wish" value="true"><svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#434343"><path d="m354-287 126-76 126 77-33-144 111-96-146-13-58-136-58 135-146 13 111 97-33 143ZM233-120l65-281L80-590l288-25 112-265 112 265 288 25-218 189 65 281-247-149-247 149Zm247-350Z"/></svg></button></form>';

            if(isset($_POST["wish"]) && $_POST["wish"] == true){
                $insert = addToWishlist($pdo, $user, $id_libro);
                if($insert == true) $DOM = str_replace($star, '<p>Libro salvato nella tua <a href="login.php">wishlist</a>.</p>', $DOM);
                else echo $insert;
            }

            $form_recensione ='
            <div class="recensione">
                <h4>Recensione</h4>
                <form action="recensione.php" class="form-recensione" method="post">
                    <select name="" id="">
                        <option value="">Seleziona un\'opzione</option>
                        <option value="1">1</option>
                        <option value="2">2</option>
                        <option value="3">3</option>
                        <option value="4">4</option>
                        <option value="5">5</option>
                    </select>
                    <textarea name="" id=""></textarea>
                    <button onclick="enter" type="submit">Invia</button>
                </form>
            </div>';
        }
        else if(isLogged() == 0 || isLogged() == -1){
            $star = '<p class="login-request">Per salvare un libro nella tua wishlist e aggiungere una recensione ti preghiamo di <a href="login.php">accedere</a>.</p>';
            $form_recensione = '';
        }

        $DOM = str_replace('###STAR###', $star, $DOM);
        $DOM = str_replace("###FORM-RECENSIONE###", $form_recensione, $DOM);
        echo $DOM;
    }
    include "templates/footer.php";
?>