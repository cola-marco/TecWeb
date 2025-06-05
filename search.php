<?php
    require 'utils.php';
    
    $pdo = connectDB();
    $search = "";
    $lista_libri = "";
    $DOM = file_get_contents('html/catalogo.html');

    if(isset($_POST['search'])){
        $search = '%' . $_POST['search'] . '%';

        $query = $pdo->prepare("SELECT * FROM Libri WHERE LOWER(CONCAT(Titolo,' ',Autore)) LIKE LOWER(:search)");
        $query->bindParam(':search', $search, PDO::PARAM_STR);
        $query->execute();
        $result = $query->fetchAll(PDO::FETCH_ASSOC);

        if(count($result) == 0){ 
            $lista_libri = "<p id='no-libri'>Nessun libro trovato, torna al <a href='catalogo.php'>catalogo</a> oppure prova a fare una ricerca diversa.<p>";
            $DOM = str_replace('<ul class="slider">', '<ul>', $DOM);
        }
        else{
            $li = '<li class="card">
            <div>
                <img src="###IMG-PATH###" alt="">
            </div>
            <div class="description">
                <a href="libro.php?id_libro=###ID_LIBRO###"><h3>###TITOLO###</h3></a>
                <h4>###AUTORE###</h4>
                <p><strong>Trama</strong>:###TRAMA###</p>
            </div>
            </li>';
            $lista_libri = book_display($result, $li);
        } 

        $DOM = str_replace('###LISTA###', $lista_libri, $DOM);
        $DOM = str_replace('<h2>Catalogo</h2>', '<h2>Catalogo - Risultati Ricerca</h2>', $DOM);
        echo $DOM;
    }
?>