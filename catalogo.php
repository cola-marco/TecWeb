<?php
    include "templates/header.php";
    require 'utils.php';
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
        $query = $pdo->prepare("
            SELECT * 
            FROM Libri JOIN Autori 
            WHERE Autore = ID_Autore");
        $query->execute();
        $result = $query->fetchAll(PDO::FETCH_ASSOC);
   
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

        $lista_libri = book_display($result, $li);        
    }

    $DOM = str_replace('###LISTA###', $lista_libri, $DOM);
    echo $DOM;
    include "templates/footer.php";
?>