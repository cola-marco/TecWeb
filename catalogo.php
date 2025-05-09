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
        $query = $pdo->prepare("SELECT * FROM Libri");
        $query->execute();
        $result = $query->fetchAll(PDO::FETCH_ASSOC);

        $li = '
            <li class="card">
                <div>
                    ##IMG###
                </div>
                <div class="description">
                    <a href="libro.php?###TITOLO###"><h3>###TITOLO###</h3></a>
                    <h4>###AUTORE###</h4>
                    <p><strong>Trama</strong>:###TRAMA###</p>
                </div>
            </li>
        ';

        $lista_libri = "";

        foreach($result as $book){
            $titolo = $book["Titolo"];
            $autore = $book["Autore"];
            $casa = $book["Casa_Editrice"];
            $genere = $book["Genere"];
            $pubblicazione = $book["Pubblicazione"];

            $li = str_replace('###TITOLO###', $titolo, $li);
            $li = str_replace('###AUTORE###', $autore, $li);
            $li = str_replace('###TRAMA###', $trama, $li);

            $lista_libri = $lista_libri . " " . $li;
        }
    }
    

    $DOM = str_replace('###LISTA###', $lista_libri, $DOM);

    echo $DOM;
?>