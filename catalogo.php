<?php
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
   
        $lista_libri = book_display($result);        
    }

    $DOM = str_replace('###LISTA###', $lista_libri, $DOM);
    echo $DOM;
?>