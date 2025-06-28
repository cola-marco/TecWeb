<?php
    $metaDescription = 'Sfoglia il catalogo online e visualizza i libri.';
    $metaKeywords    = 'catalogo, libri, ricerca';
    
    include "templates/header.php";

    require 'utils.php';
    $pdo = connectDB();
    //session_start();
    check_session_timeout();

    $DOM = file_get_contents('html/catalogo.html');
    
    if(!$pdo){
        /*$error = "
            <h2>OOOPS</h2>
            <p>Se vedi questo messaggio c'è un errore server</p>
        ";
        $lista_libri = '';
        $DOM = str_replace('###LISTA###', $lista_libri, $DOM);
        $DOM = str_replace('###ERRORE_DB###', $error, $DOM);*/

        header("Location: 505.php");
    }
    else{
        $query = $pdo->prepare("SELECT * FROM Libri");
        $query->execute();
        $result = $query->fetchAll(PDO::FETCH_ASSOC);

        $li = '
            <li class="card">
                <div>
                    <img src="###IMG-PATH###" alt="">
                </div>
                <div class="description">
                    <div>
                        <a href="libro.php?id_libro=###ID_LIBRO###"><h3>###TITOLO###</h3></a>
                        <h4>###AUTORE###</h4>
                        <p><strong>Trama</strong>:###TRAMA###</p>
                    </div>
                </div>    
            </li>
            ';

        $lista_libri = book_display($result, $li);        

        $error = '';
        $lista_libri = book_display($result, $li);    
        $DOM = str_replace('###LISTA###', $lista_libri, $DOM);
        $DOM = str_replace('###ERRORE_DB###', $error, $DOM);
    }

    echo $DOM;
    include "templates/footer.php";
?>