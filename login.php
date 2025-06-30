<?php
    $metaDescription = 'Accedi alla tua area personale per gestire wishlist e recensioni.';
    $metaKeywords    = 'login, area personale, recensioni, wishlist';
    
    include "templates/header.php";
    require 'utils.php';
    $pdo = connectDB();
    //session_start();
    check_session_timeout();
    if(!$pdo){
        header("location: 505.php"); 
        exit();
    }
    if(!isset($_SESSION['is_logged_in']) || isset($_SESSION['is_logged_in']) && $_SESSION['is_logged_in'] == false){
        $DOM = file_get_contents('html/login.html');
        $DOM = str_replace('###passError###', '', $DOM);
        $DOM = str_replace('###userError###', '', $DOM);
    }
    else if($_SESSION['ruolo'] == 'Admin') {
        $_SESSION['user_role'] = 'Admin';

        header("Location: admin.php");
        exit();
    }
    else if(isset($_SESSION['is_logged_in']) && !isset($_SESSION["ID_Cliente"])){
        header("Location: logout.php");
        exit();
    }
    else{
        $_SESSION['user_role'] = 'cliente';

        $DOM = file_get_contents("html/area-riservata.html");

        //DISPLAY WISHLIST
        $cliente = $_SESSION["ID_Cliente"];
        $wishlist_query = $pdo->prepare("SELECT * FROM Wishlist
                                        JOIN Libri ON Wishlist.Libro = Libri.ID_libro
                                        JOIN Clienti ON Wishlist.Cliente = Clienti.ID_Cliente
                                        WHERE Wishlist.Cliente = :cliente");
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
                        <div class="info-libro">
                            <a href="libro.php?id_libro=###ID_LIBRO###"><h3>###TITOLO###</h3></a>
                            <h4>###AUTORE###</h4>
                            <p><strong>Trama</strong>:###TRAMA###</p>
                        </div>    

                        <form action="#" method="post" class="delete-form">
                            <!-- <label for="delete-button-###ID_LIBRO###" class="delete-label">Elimina ###TITOLO###</label> -->
                            <button type="submit" name="delete-button" value="###ID_LIBRO###" id="delete-button-###ID_LIBRO###" class="delete-button" aria-label="Elimina ###TITOLO###"><svg xmlns="http://www.w3.org/2000/svg" height="30px" viewBox="0 -960 960 960" width="30px" fill="#000000"><path d="M280-120q-33 0-56.5-23.5T200-200v-520h-40v-80h200v-40h240v40h200v80h-40v520q0 33-23.5 56.5T680-120H280Zm400-600H280v520h400v-520ZM360-280h80v-360h-80v360Zm160 0h80v-360h-80v360ZM280-720v520-520Z"/></svg></button>
                        </form>
                    </div>
                </li>';

            $wishlist = book_display($result, $li);
        }
        else{
            $wishlist = "<p>Nessun libro salvato, se ne vuoi salvarne alcuni vai al <a href=\"catalogo.php\">catalogo</a>.</p>";
        }

        //DISPLAY PERSONAL DATA
        $personal_query = $pdo->prepare("SELECT * FROM Clienti WHERE ID_Cliente = :cliente");
        $personal_query->bindParam(':cliente', $cliente, PDO::PARAM_STR);
        $personal_query->execute();
        $personal_result = $personal_query->fetch(PDO::FETCH_ASSOC);


        if($personal_result && count($personal_result) > 0){
            $personal_data = "<li lang='en'> Username: " .  $personal_result["Username"] . "</li>";
            $personal_data = $personal_data . "<li lang='en'> Email: ". $personal_result["Email"] ."</li>";
        }
        else{
            header("Location: 505.php");
        }

        if(isset($_POST["delete-button"]) && $_POST["delete-button"] > 0){
            $delete = deleteFromWishlist($pdo, $cliente, $_POST["delete-button"]);
            if($delete){
                header("Location: login.php");
                exit();
            }
            else header("Location: 505.php");
        }

        //DISPLAY RECENSIONI
        $user = $_SESSION["ID_Cliente"];
        $lista_recensioni = '';
        
        $query = $pdo->prepare("SELECT R.Valutazione, R.Recensione, R.Libro, L.Titolo, R.Data, C.ID_Cliente
                        FROM Recensioni R
                        JOIN Clienti C ON R.Cliente = C.ID_Cliente
                        JOIN Libri L ON R.Libro = L.ID_Libro
                        WHERE C.ID_Cliente = :user");

        $query->bindParam(':user', $user, PDO::PARAM_STR);
        $query->execute();
        $result = $query->fetchAll(PDO::FETCH_ASSOC);

        if(count($result)>0){
            foreach($result as $instance){
                $singola_recensione = '
                    <div class="card-recensione">
                        <div class="review-data">
                            <p><strong>Titolo del libro</strong>: ###TITOLO###</p>
                            <p aria-label="Valutazione: ###N-STELLE### stelle."><strong>Valutazione</strong>: ###VALUTAZIONE###</p>
                            <p><strong>Data e ora pubblicazione</strong>: <time datetime="###DATA_ORA###">###DATA_ORA###</time></p>
                        </div>
                        
                        <div class="mex">
                            <p>###RECENSIONE###</p>
                            
                            <form action="#" method="post" class="delete-form">
                                <!-- <label for="delete-review-button-###ID_LIBRO###" class="delete-review-label">Elimina la recensione a ###TITOLO###</label> -->
                                <button type="submit" name="delete-review-button" value="###ID_LIBRO###" id="delete-review-button-###ID_LIBRO###" class="delete-review-button" aria-label="Elimina la recensione a ###TITOLO###"><svg xmlns="http://www.w3.org/2000/svg" height="30px" viewBox="0 -960 960 960" width="30px" fill="#000000"><path d="M280-120q-33 0-56.5-23.5T200-200v-520h-40v-80h200v-40h240v40h200v80h-40v520q0 33-23.5 56.5T680-120H280Zm400-600H280v520h400v-520ZM360-280h80v-360h-80v360Zm160 0h80v-360h-80v360ZM280-720v520-520Z"/></svg></button>
                            </form>
                        </div>
                    </div>';
                   
                $singola_recensione = str_replace('###CLIENTE###', $instance["ID_Cliente"], $singola_recensione);
                $singola_recensione = str_replace('###N-STELLE###', $instance["Valutazione"], $singola_recensione);
                $singola_recensione = str_replace('###VALUTAZIONE###',  str_repeat("&#9733;", $instance["Valutazione"]), $singola_recensione);
                $singola_recensione = str_replace('###RECENSIONE###', $instance["Recensione"], $singola_recensione);
                $singola_recensione = str_replace('###ID_LIBRO###', $instance["Libro"], $singola_recensione);
                $singola_recensione = str_replace('###DATA_ORA###', $instance["Data"], $singola_recensione);
                $singola_recensione = str_replace('###TITOLO###', $instance["Titolo"], $singola_recensione);
            
                $lista_recensioni = $lista_recensioni . $singola_recensione;
            }   
        }
        else $lista_recensioni = '<p>Non hai ancora aggiunto alcuna recensione.</p>';

        if(isset($_POST["delete-review-button"]) && $_POST["delete-review-button"] > 0){
            $delete = deleteFromRecensioni($pdo, $cliente, $_POST["delete-review-button"]);
            if($delete) {
                header("Location: login.php");
                exit();
            }
            else echo("errore");
        }

        $DOM = str_replace('###RECENSIONI###', $lista_recensioni, $DOM);
        $DOM = str_replace('###LISTA-WISHLIST###', $wishlist, $DOM);
        $DOM = str_replace('###DATI-PERSONALI###', $personal_data, $DOM);
    }
    //echo $_SESSION["created"];
    echo $DOM;
    include "templates/footer.php";
?>