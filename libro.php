<?php
    require 'ConnectionDB.php';
    $pdo = connectDB();

    $DOM = file_get_contents('html/libro.html');

    if (!isset($_GET["id_libro"])) {
        header("location: catalogo.php");
        exit();
    }

    $id_libro = $_GET["id_libro"];

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

    $DOM = str_replace('###STAR###', '<svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#434343"><path d="m354-287 126-76 126 77-33-144 111-96-146-13-58-136-58 135-146 13 111 97-33 143ZM233-120l65-281L80-590l288-25 112-265 112 265 288 25-218 189 65 281-247-149-247 149Zm247-350Z"/></svg>', $DOM);

    echo $DOM;
?>