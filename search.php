<?php
    require 'utils.php';
    
    $pdo = connectDB();
    $search = "";
    $lista_libri = "";

    if(isset($_POST['search'])){
        $search = $_POST['search'];
        $query = $pdo->prepare("
            SELECT * 
            FROM Libri JOIN Autori ON Libri.Autore = Autori.ID_Autore
            WHERE Titolo = :search OR 
            Autori.Nome = :search OR 
            Autori.Cognome = :search
            AND 1=0");
        $query->bindParam(':search', $search, PDO::PARAM_INT);
        $query->execute();
        $result = $query->fetchAll(PDO::FETCH_ASSOC);

        echo count($result);

        if(count($result) == 0){
            echo "entra"; 
            //header('Location: catalogo.php');
            $lista_libri = "<p>Nessun libro trovato, torna al <a href='catalogo.php'>catalogo</a><p>";
        }
        else $lista_libri = book_display($result);

        $DOM = file_get_contents('html/catalogo.html');
        $DOM = str_replace('###LISTA###', $lista_libri, $DOM);
        echo $DOM;
    }
?>