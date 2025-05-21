<?php 
function connectDB() {
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
        echo "Connessione fallita: " . $e->getMessage();
        exit(); //se connessione al database è fallita esce dal flusso 
    }
}

function book_display($result){
    $lista_libri = "";

    foreach($result as $book){
        $li = '
        <li class="card">
            <div>
                <img src="###IMG-PATH###" alt="">
            </div>
            <div class="description">
                <a href="libro.php?id_libro=###ID_LIBRO###"><h3>###TITOLO###</h3></a>
                <h4>###AUTORE###</h4>
                <p><strong>Trama</strong>:###TRAMA###</p>
            </div>
        </li>
        ';

        $titolo = $autore = $casa = $genere = $pubblicazione = $trama = "";

        $titolo = $book["Titolo"];
        $autore = $book["Nome"] . " " . $book["Cognome"];
        $casa = $book["Casa_Editrice"];
        $genere = $book["Genere"];
        $pubblicazione = $book["Pubblicazione"];
        $id_libro = $book["ID_Libro"];
        //$trama = $book["Trama"];
        $trama = "Lorem, ipsum dolor sit amet consectetur adipisicing elit. Eaque eos quae veniam! Quas nulla reprehenderit ratione fugit aspernatur, quae possimus maiores deleniti veritatis nemo atque exercitationem sunt, accusantium doloremque itaque?";

        $li = str_replace('###IMG-PATH###', 'Immagini/esempio libro.jpg', $li);
        $li = str_replace('###ID_LIBRO###', $id_libro, $li);
        $li = str_replace('###TITOLO###', $titolo, $li);
        $li = str_replace('###AUTORE###', $autore, $li);
        $li = str_replace('###TRAMA###', $trama, $li);

        $lista_libri = $lista_libri . " " . $li;
    }
    return $lista_libri;
}

function isLogged(){
    if(isset($_SESSION['is_logged']) && $_SESSION['is_logged'] == true) return 1;
    else if(isset($_SESSION['is_logged']) && $_SESSION['is_logged'] == false) return 0;
    else return -1;
}

function displayBookInfo($DOM, $pdo, $id_libro){
    $query = $pdo->prepare("SELECT * FROM Libri JOIN Autori WHERE Autore = ID_Autore AND ID_libro = :id_libro");
    $query->bindParam(':id_libro', $id_libro, PDO::PARAM_INT);
    $query->execute();

    $book = $query->fetch(PDO::FETCH_ASSOC);
    $titolo = $book["Titolo"];
    $autore = $book["Nome"] . " " . $book["Cognome"];
    $casa = $book["Casa_Editrice"];
    $genere = $book["Genere"];
    $pubblicazione = $book["Pubblicazione"];
    //$trama = $book["Trama"];
    $trama = "Lorem, ipsum dolor sit amet consectetur adipisicing elit. Eaque eos quae veniam! Quas nulla reprehenderit ratione fugit aspernatur, quae possimus maiores deleniti veritatis nemo atque exercitationem sunt, accusantium doloremque itaque?";

    $DOM = str_replace("###TITOLO###", $titolo, $DOM);
    $DOM = str_replace('###IMG-PATH###', 'Immagini/esempio libro.jpg', $DOM);
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
?>