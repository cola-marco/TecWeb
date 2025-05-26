<?php
    require 'utils.php';
    
    $pdo = connectDB();
    $search = "";
    $lista_libri = "";
    $DOM = file_get_contents('html/catalogo.html');

    if(isset($_POST['search'])){
        $search = '%' . $_POST['search'] . '%';

        $query = $pdo->prepare("SELECT * FROM Libri JOIN Autori ON Libri.Autore = Autori.ID_Autore WHERE LOWER(CONCAT(Titolo,' ',Nome,' ',Cognome)) LIKE LOWER(:search)");
        $query->bindParam(':search', $search, PDO::PARAM_STR);
        $query->execute();
        $result = $query->fetchAll(PDO::FETCH_ASSOC);

        if(count($result) == 0){ 
            $lista_libri = "<p id='no-libri'>Nessun libro trovato, torna al <a href='catalogo.php'>catalogo</a> oppure prova a fare una ricerca diversa.<p>";
            $DOM = str_replace('<ul class="slider">', '<ul>', $DOM);
        }
        else $lista_libri = book_display($result);

        $DOM = str_replace('###LISTA###', $lista_libri, $DOM);
        $DOM = str_replace('<h2>Catalogo</h2>', '<h2>Catalogo - Risultati Ricerca</h2>', $DOM);
        echo $DOM;
    }
?>