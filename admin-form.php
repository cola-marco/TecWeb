<?php
    $metaDescription = 'Pannello di aggiunta libro: aggiungi un libro con il suo Titolo, Autore ecc';
    $metaKeywords    = 'admin, gestione libri, backend';

    include "templates/header.php"; 
    require 'utils.php';
    //session_start();

    $DOM = file_get_contents('html/admin-form.html');
    $id_libro = $titolo = $autore = $casa_editrice = $genere = $anno = $trama = $n_copie = '';
    $titoloErr = $autoreErr = $imageErr = $casaErr = $genereErr = $annoErr = $tramaErr = $ncopieErr = '';
    if (!isset($_SESSION['ID_Cliente']) || $_SESSION['ruolo'] !== 'Admin') { //admin non loggato
        header('Location: index.php');
        exit();
    }
    if(isset($_GET['id'])){ //id settato quindi form per modifica
        $DOM = str_replace('{{Azione}}', 'Modifica', $DOM);
        $DOM = str_replace('{{buttonAction}}', 'Modifica', $DOM);
        $id_libro = $_GET['id'];
        $form_action = 'manageBook.php?id=' . urlencode($id_libro);
        $DOM = str_replace('{{FormAction}}', $form_action, $DOM);
        $pdo = connectDB();
        if($pdo){
            $stmt = $pdo->prepare("SELECT * FROM Libri WHERE ID_Libro = :id_libro");
            $stmt->bindParam(':id_libro', $id_libro, PDO::PARAM_INT);
            if($stmt->execute()){ //query eseguita correttamente
                $result = $stmt->fetch(PDO::FETCH_ASSOC);
                $titolo = $result["Titolo"];
                $autore = $result["Autore"];
                $casa_editrice = $result["Casa_Editrice"];
                $genere = $result["Genere"];
                $anno = $result["Pubblicazione"];
                $trama = $result["Trama"];
                $n_copie = $result["Numero_copie"];
                
                $DOM = str_replace('{{Titolo}}', $titolo, $DOM);
                $DOM = str_replace('{{Autore}}', $autore, $DOM);
                $DOM = str_replace('{{Casa_Editrice}}', $casa_editrice, $DOM);
                $DOM = str_replace('{{Genere}}', $genere, $DOM);
                $DOM = str_replace('{{Pubblicazione}}', $anno, $DOM);
                $DOM = str_replace('{{Trama}}', $trama, $DOM);
                $DOM = str_replace('{{Numero_copie}}', $n_copie, $DOM);

            }
            else {
                //problema con l'esecuzione della query
                header("location: 505.php"); 
                exit();
            }
        }
        else {
            //problema con la connessione a db
            header("location: 505.php"); 
            exit();
        }
    }
    else { //form per l'aggiunta di un libro
        $DOM = str_replace('{{Azione}}', 'Aggiungi', $DOM);
        $DOM = str_replace('{{buttonAction}}', 'Aggiungi', $DOM);
        $form_action = 'manageBook.php';
        $DOM = str_replace('{{FormAction}}', $form_action, $DOM);
        $DOM = str_replace('{{Titolo}}', $titolo, $DOM);
        $DOM = str_replace('{{Autore}}', $autore, $DOM);
        $DOM = str_replace('{{Casa_Editrice}}', $casa_editrice, $DOM);
        $DOM = str_replace('{{Genere}}', $genere, $DOM);
        $DOM = str_replace('{{Pubblicazione}}', $anno, $DOM);
        $DOM = str_replace('{{Trama}}', $trama, $DOM);
        $DOM = str_replace('{{Numero_copie}}', $n_copie, $DOM);
    }

    $DOM = str_replace('###titoloError###', $titoloErr, $DOM);
    $DOM = str_replace('###autoreError###', $autoreErr, $DOM);
    $DOM = str_replace('###imageError###', $imageErr, $DOM);
    $DOM = str_replace('###casaedError###', $casaErr, $DOM);
    $DOM = str_replace('###genereError###', $genereErr, $DOM);
    $DOM = str_replace('###annopubbError###', $annoErr, $DOM);
    $DOM = str_replace('###tramaError###', $tramaErr, $DOM);
    $DOM = str_replace('###ncopieError###', $ncopieErr, $DOM);

    echo $DOM;
    include "templates/footer.php";
?>