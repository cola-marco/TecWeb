<?php
    $metaDescription = 'Pannello amministrazione: gestisci catalogo e impostazioni della biblioteca.';
    $metaKeywords    = 'admin, gestione biblioteca, backend';
    
    include "templates/header.php"; 
    require 'utils.php';
    //session_start();
    $pdo = connectDB();
    $DOM = file_get_contents('html/admin.html');

    if(!$pdo){
        $error = "
            <h2>OOOPS</h2>
            <p>Se vedi questo messaggio c'è un errore server</p>
        ";
        $tabella_libri = '';
        $DOM = str_replace('###BODY_TABELLA###', $tabella_libri, $DOM);
        $DOM = str_replace('###ERRORE_DB###', $error, $DOM);
    }
    else if (!isset($_SESSION['ID_Cliente']) || $_SESSION['ruolo'] !== 'Admin') {
        header('Location: index.php');
        exit();
    }
    else { //collegamento al db andato a buon fine e fatto login come admin
        $stmt = $pdo->prepare("SELECT * FROM Libri");
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $tr = '
        <tr>
            <td>{{Titolo}}</td>
            <td>{{Autore}}</td>
            <td>{{Anno}}</td>
            <td><a href="admin-form.php?id={{ID_Libro}}">Modifica</a></td>
            <td><a href="deleteBook.php?id={{ID_Libro}}" onclick="return confirm("Confermi eliminazione?")">Elimina</a></td>
        </tr>
        ';   
        $error = '';
        $tabella_libri = tab_book_display($result, $tr);    
        $DOM = str_replace('###BODY_TABELLA###', $tabella_libri, $DOM);
        $DOM = str_replace('###ERRORE_DB###', $error, $DOM);
    }

    echo $DOM;
    include "templates/footer.php";
?>
