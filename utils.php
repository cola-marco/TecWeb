<?php 
function connectDB() {
    /*$host = 'localhost';                         
    $dbname = 'damartin';         
    $userdbname = 'damartin';          
    $passwordDB = 'Doo3ieD4yoS7ienu';*/

    $host = 'localhost';                         
    $dbname = 'progettotecweb';         
    $userdbname = 'root';          
    $passwordDB = '';

    try {
        $dsn = "mysql:host=$host;dbname=$dbname;charset=utf8mb4";
        $pdo = new PDO($dsn, $userdbname, $passwordDB);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $pdo;
    } catch (PDOException $e) {
        //echo "Connessione fallita: " . $e->getMessage();
        return null; //se connessione al database è fallita esce dal flusso 
    }
}

function book_display($result, $li_template){
    $lista_libri = "";

    foreach($result as $book){
        $li = $li_template;

        $titolo = $autore = $copertina = $casa = $genere = $pubblicazione = $trama = "";

        $titolo = $book["Titolo"];
        $autore = $book["Autore"];
        $casa = $book["Casa_Editrice"];
        $genere = $book["Genere"];
        $pubblicazione = $book["Pubblicazione"];
        $id_libro = $book["ID_Libro"];
        
        if(!empty($book["Trama"])) $trama = $book["Trama"];
        else $trama = "Lorem, ipsum dolor sit amet consectetur adipisicing elit. Eaque eos quae veniam! Quas nulla reprehenderit ratione fugit aspernatur, quae possimus maiores deleniti veritatis nemo atque exercitationem sunt, accusantium doloremque itaque?";

        if(!empty($book["Image_path"])) $copertina = $book["Image_path"];
        else $copertina = "./Immagini/default_book_cover.png";

        $li = str_replace('###IMG-PATH###', $copertina, $li);
        $li = str_replace('###ID_LIBRO###', $id_libro, $li);
        $li = str_replace('###TITOLO###', $titolo, $li);
        $li = str_replace('###AUTORE###', $autore, $li);
        $li = str_replace('###TRAMA###', $trama, $li);

        $lista_libri = $lista_libri . " " . $li;
    }
    return $lista_libri;
}

function isLogged(){
    if(isset($_SESSION['is_logged_in']) && $_SESSION['is_logged_in'] == true && isset($_SESSION['ID_Cliente'])) return 1;
    else if(isset($_SESSION['is_logged_in']) && $_SESSION['is_logged_in'] == false) return 0;
    else return -1;
}

function displayBookInfo($DOM, $pdo, $id_libro){
    $query = $pdo->prepare("SELECT * FROM Libri WHERE ID_libro = :id_libro");
    $query->bindParam(':id_libro', $id_libro, PDO::PARAM_INT);
    $query->execute();

    $book = $query->fetch(PDO::FETCH_ASSOC);
    $titolo = $book["Titolo"];
    $autore = $book["Autore"];
    $casa = $book["Casa_Editrice"];
    $genere = $book["Genere"];
    $pubblicazione = $book["Pubblicazione"];

    if(!empty($book["Trama"])) $trama = $book["Trama"];
    else $trama = "Lorem, ipsum dolor sit amet consectetur adipisicing elit. Eaque eos quae veniam! Quas nulla reprehenderit ratione fugit aspernatur, quae possimus maiores deleniti veritatis nemo atque exercitationem sunt, accusantium doloremque itaque?";

    if(!empty($book["Image_path"])) $copertina = $book["Image_path"];
    else $copertina = "./Immagini/default_book_cover.png";
    

    $DOM = str_replace("###TITOLO###", $titolo, $DOM);
    $DOM = str_replace('###IMG-PATH###', $copertina, $DOM);
    $DOM = str_replace('###ID_LIBRO###', $id_libro, $DOM);
    $DOM = str_replace('###TITOLO###', $titolo, $DOM);
    $DOM = str_replace('###AUTORE###', $autore, $DOM);
    $DOM = str_replace('###GENERE###', $genere, $DOM);
    $DOM = str_replace('###CASA###', $casa, $DOM);
    $DOM = str_replace('###PUBBLICAZIONE###', $pubblicazione, $DOM);
    $DOM = str_replace('###TRAMA###', $trama, $DOM);

    return $DOM;
}

function pulisciInput($value){
    $value = trim($value); //toglie spazi
    $value = strip_tags($value); //rimossi tag html e php
    $value = htmlentities($value); //converte caratteri speciali in entità html
    return $value;
}

function isSaved($pdo, $id, $user){
    $query = $pdo->prepare("SELECT * FROM Wishlist WHERE Cliente = :user");
    $query->bindParam(':user', $user, PDO::PARAM_STR);
    $query->execute();
    $result = $query->fetchAll(PDO::FETCH_ASSOC);

    foreach($result as $book) if($book["Libro"] == $id) return true;

    return false;
}

function addToWishlist($pdo, $user, $id_libro){
    if(!isSaved($pdo, $id_libro, $user)){
        $query = $pdo->prepare("INSERT INTO Wishlist (Cliente, Libro) VALUES (:user, :id_libro)");
        $query->bindParam(':user', $user, PDO::PARAM_STR);
        $query->bindParam(':id_libro', $id_libro, PDO::PARAM_STR);
        $result = $query->execute();

        return $result;
    }
    else return false;
}

function deleteFromWishlist($pdo, $user, $id_libro){
    if(isSaved($pdo, $id_libro, $user)){
        $query = $pdo->prepare("DELETE FROM Wishlist WHERE Cliente = :user AND Libro = :id_libro");
        $query->bindParam(':user', $user, PDO::PARAM_STR);
        $query->bindParam(':id_libro', $id_libro, PDO::PARAM_STR);
        $result = $query->execute();

        return $result;
    }
}

function deleteFromRecensioni($pdo, $user, $id_libro){
    $query = $pdo->prepare("DELETE FROM Recensioni WHERE Cliente = :user AND Libro = :id_libro");
    $query->bindParam(':user', $user, PDO::PARAM_STR);
    $query->bindParam(':id_libro', $id_libro, PDO::PARAM_STR);
    $result = $query->execute();

    return $result;
}

function tab_book_display($result, $tr_template){
    $tab_libri = "";

    foreach($result as $book){
        $tr = $tr_template;

        $id_libro = $titolo = $autore = $anno = "";

        $id_libro = $book["ID_Libro"];
        $titolo = $book["Titolo"];
        $autore = $book["Autore"];
        $anno = $book["Pubblicazione"];

        $tr = str_replace('{{ID_Libro}}', $id_libro, $tr);
        $tr = str_replace('{{Titolo}}', $titolo, $tr);
        $tr = str_replace('{{Autore}}', $autore, $tr);
        $tr = str_replace('{{Anno}}', $anno, $tr);

        $tab_libri = $tab_libri . " " . $tr;
    }
    return $tab_libri;
}

function check_session_timeout(){
    if(isset($_SESSION['is_logged_in']) && $_SESSION['is_logged_in'] == true){ //controllo se l'utente è loggato 
        if(isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY'] > 1800)) {
            // ultima richeista 30 minuti fa
            session_unset();     // array di sessione svuotato
            session_destroy();  // distruzione dati di sessione
            header("Location: login.php");
            exit();   //se un utente era loggato ma è passato troppo tempo, lo rimando alla pagina di login
        }
    $_SESSION['LAST_ACTIVITY'] = time();  //aggiorno il dato temporale dell'ultima attività
    }
}

function get_reviews($pdo, $role, $id_libro){
    $query = $pdo->prepare("SELECT R.Valutazione, R.Recensione, C.Username, R.Libro, R.Data
                            FROM Recensioni R
                            JOIN Clienti C ON R.Cliente = C.ID_Cliente
                            WHERE R.Libro = :id_libro");

    $query->bindParam(':id_libro', $id_libro, PDO::PARAM_STR);
    $query->execute();
    $result = $query->fetchAll(PDO::FETCH_ASSOC);
    $lista_recensioni = '';

    if(count($result) > 0){
        foreach($result as $instance){
            
            if($role == "Admin"){
                $singola_recensione = '
                    <div class="card-recensione">
                        <div class="review-data">
                            <p><strong><span lang="en">Username</span></strong>: ###USERNAME###<p>
                            <p><strong>Valutazione</strong>: <span aria-label="###N-STELLE### stelle">###VALUTAZIONE###</span></p>
                            <p><time datetime="###DATA_ORA###">###DATA_ORA###</time></p>
                        </div>
                        
                        <div class="mex">
                            <p>###RECENSIONE###</p>

                            <form action="#" method="post" class="delete-form">
                                <button type="submit" name="delete-review-button" value="###ID_LIBRO###" id="delete-review-button-###ID_LIBRO###" class="delete-review-button" aria-label="Elimina la recensione a ###TITOLO###"><svg xmlns="http://www.w3.org/2000/svg" height="30px" viewBox="0 -960 960 960" width="30px" fill="#000000"><path d="M280-120q-33 0-56.5-23.5T200-200v-520h-40v-80h200v-40h240v40h200v80h-40v520q0 33-23.5 56.5T680-120H280Zm400-600H280v520h400v-520ZM360-280h80v-360h-80v360Zm160 0h80v-360h-80v360ZM280-720v520-520Z"/></svg></button>
                            </form>
                        </div>
                    </div>';
                }
            else{
                $singola_recensione = '
                    <div class="card-recensione">
                        <div class="review-data">
                            <p><strong><span lang="en">Username</span></strong>: ###USERNAME###<p>
                            <p><strong>Valutazione</strong>: <span aria-label="###N-STELLE### stelle">###VALUTAZIONE###</span></p>
                            <p><time datetime="###DATA_ORA###">###DATA_ORA###</time></p>
                        </div>
                        
                        <div class="mex">
                            <p>###RECENSIONE###</p>
                        </div>
                    </div>';   
            }

            if($instance["Libro"] == $id_libro){
                $singola_recensione = str_replace('###USERNAME###', $instance["Username"], $singola_recensione);
                $singola_recensione = str_replace('###N-STELLE###', $instance["Valutazione"], $singola_recensione);
                $singola_recensione = str_replace('###ID_LIBRO###', $instance["Libro"], $singola_recensione);
                $singola_recensione = str_replace('###VALUTAZIONE###',  str_repeat("&#9733;", $instance["Valutazione"]), $singola_recensione);
                $singola_recensione = str_replace('###RECENSIONE###', $instance["Recensione"], $singola_recensione);
                $singola_recensione = str_replace('###DATA_ORA###', $instance["Data"], $singola_recensione);
                
                $lista_recensioni = $lista_recensioni . $singola_recensione;
            } 
        }
    }
    else $lista_recensioni = 'Ancora nessuna recensione per questo libro, sii il primo a lasciarne una!';

    return $lista_recensioni;
}
?>