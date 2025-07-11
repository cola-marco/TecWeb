<?php
    $metaDescription = 'Scheda libro della Biblioteca: trama, autore, valutazioni e recensioni.';
    $metaKeywords    = 'libro, dettagli, recensioni, autore, biblioteca';

    include "templates/header.php";
    require 'utils.php';
    $pdo = connectDB();
    //session_start();
    check_session_timeout();
    if(!$pdo){
        header("Location: 505.php");
        exit();
    }

    $DOM = file_get_contents('html/libro.html');
    $id_libro = $_GET["id_libro"];
    $user = $_SESSION["ID_Cliente"] ?? '';
    if(isset($_SESSION['ruolo'])) $role = $_SESSION['ruolo'];
    else $role = "";

    
    if (!isset($_GET["id_libro"])) {
        header("location: catalogo.php");
    }
    else{
        $DOM = displayBookInfo($DOM, $pdo, $id_libro);

        if(isLogged() == 1){
            $user = $_SESSION["ID_Cliente"];
            
            //ADMIN
            if($_SESSION['ruolo'] == 'Admin'){
                //$role = $_SESSION['ruolo'];
                $star = $form_recensione = '';
                //$lista_recensioni = get_reviews($pdo, $role, $id_libro);
            }

            //CLIENTE
            else{
                if(isSaved($pdo, $id_libro, $user)){
                    $star = '<p class="alert-inside-wishlist">Questo libro è già all\'interno della tua <a href="login.php" lang="en">wishlist</a>.</p>';
                    $DOM = str_replace('###STAR###', $star, $DOM);
                }
                else{
                    $star = '<form action="#" class="wish-form" method="post"><label for="wish">Clicca qui per salvare il libro nella tua wishlist</label><button type="submit" name="wish" id="wish" value="true"><svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#434343"><path d="m354-287 126-76 126 77-33-144 111-96-146-13-58-136-58 135-146 13 111 97-33 143ZM233-120l65-281L80-590l288-25 112-265 112 265 288 25-218 189 65 281-247-149-247 149Zm247-350Z"/></svg></button></form>';
                    $DOM = str_replace('###STAR###', $star, $DOM);
                }

                if(isset($_POST["wish"]) && $_POST["wish"] == true){
                    $insert = addToWishlist($pdo, $user, $id_libro);
                    if($insert == true){
                        $old_form = '<form action="#" class="wish-form" method="post"><label for="wish">Clicca qui per salvare il libro nella tua wishlist</label><button type="submit" name="wish" id="wish" value="true"><svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#434343"><path d="m354-287 126-76 126 77-33-144 111-96-146-13-58-136-58 135-146 13 111 97-33 143ZM233-120l65-281L80-590l288-25 112-265 112 265 288 25-218 189 65 281-247-149-247 149Zm247-350Z"/></svg></button></form>';
                        
                        $DOM = str_replace($old_form, '<p class="alert-inside-wishlist">Libro salvato nella tua <a href="login.php" lang="en">wishlist</a>.</p>', $DOM);
                    }
                    else echo $insert;
                }

                //DISPLAY FORM RECENSIONE
                $query = $pdo->prepare("SELECT R.Valutazione, R.Recensione, C.Username, R.Libro
                            FROM Recensioni R
                            JOIN Clienti C ON R.Cliente = C.ID_Cliente
                            WHERE R.Libro = :id_libro
                            AND C.ID_Cliente = :user");

                $query->bindParam(':id_libro', $id_libro, PDO::PARAM_STR);
                $query->bindParam(':user', $user, PDO::PARAM_STR);
                if(!$query->execute()){
                    header('Location: 505.php');
                    exit();
                }
                $result = $query->fetchAll(PDO::FETCH_ASSOC);
                if(count($result)>0) $form_recensione = ""; //$form_recensione = "<p>Hai già effettuato una recensione per questo libro.</p>";
                else{
                    $form_recensione ='
                    <div class="recensione">
                        <h4>Recensione</h4>
                        <form id="add-recensione-section" action="recensione.php?id_libro=###ID-LIBRO###" class="form-recensione" method="post">
                            <fieldset class="valutazione" aria-describedby="err-valutazione-client">
                                <legend>Inserisci la valutazione del libro</legend>
                                <p>Valutazione: </p>
                                <div class="star-rating">
                                    <input type="radio" id="star5" name="valutazione" value="5" aria-label="5 stelle"/>
                                    <label for="star5">&#9733;</label>
                                    <input type="radio" id="star4" name="valutazione" value="4" aria-label="4 stelle"/>
                                    <label for="star4">&#9733;</label>
                                    <input type="radio" id="star3" name="valutazione" value="3" aria-label="3 stelle"/>
                                    <label for="star3">&#9733;</label>
                                    <input type="radio" id="star2" name="valutazione" value="2" aria-label="2 stelle"/>
                                    <label for="star2">&#9733;</label>
                                    <input type="radio" id="star1" name="valutazione" value="1" aria-label="1 stella"/>
                                    <label for="star1">&#9733;</label>
                                </div>
                            </fieldset>
                            <p id="err-valutazione-client" class="error-msg valutazione-error" aria-live="polite"></p>
                            <label for="textarea">Scrivi qui la tua recensione</label>
                            <textarea name="mex" id="textarea" placeholder="Scrivi qui la tua recensione."></textarea>
                            <button type="submit" name="submit-review" value="###ID-LIBRO###">Invia</button>
                        </form>
                    </div>';

                    $form_recensione = str_replace("###ID-LIBRO###", $id_libro, $form_recensione);
                }

                //$role = $_SESSION['ruolo'];
            }

        }
        else if(isLogged() == 0 || isLogged() == -1){
            $star = '<p class="login-request">Per salvare un libro nella tua <span lang="en">wishlist</span> e aggiungere una recensione ti preghiamo di <a href="login.php">accedere</a>.</p>';
            $form_recensione = '';
        }

        $lista_recensioni = get_reviews($pdo, $role, $id_libro);

        if(isset($_POST["delete-review-button"]) && $_POST["delete-review-button"] > 0){
            $delete = deleteFromRecensioni($pdo, $_POST["ID_Cliente"], $_POST["delete-review-button"]);
            if($delete) {
                header("Location: libro.php?id_libro=".$id_libro);
                exit();
            }
            else header("Location: 505.php");
            }

        $DOM = str_replace('###RECENSIONI###', $lista_recensioni, $DOM);
        $DOM = str_replace('###STAR###', $star, $DOM);
        $DOM = str_replace("###FORM-RECENSIONE###", $form_recensione, $DOM);
        echo $DOM;
    }
    include "templates/footer.php";
?>