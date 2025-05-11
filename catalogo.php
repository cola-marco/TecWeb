<?php
    require 'ConnectionDB.php';
    $pdo = connectDB();

    $DOM = file_get_contents('html/catalogo.html');
    
    if(!$pdo){
        $error = "
        <div>
            <h2>OOOPS</h2>
            <p>Se vedi questo messaggio c'è un errore server</p>
        </div>
        ";

        $DOM = str_replace('###LISTA###', $error, $DOM);
    }
    else{
        $query = $pdo->prepare("SELECT * FROM Libri JOIN Autori WHERE Autore = ID_Autore");
        $query->execute();
        $result = $query->fetchAll(PDO::FETCH_ASSOC);

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
    }
    

    $DOM = str_replace('###LISTA###', $lista_libri, $DOM);

    echo $DOM;
?>