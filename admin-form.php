<?php
    require 'utils.php';
    session_start();

    $DOM = file_get_contents('html/admin-form.html');
    $titolo = $autore = $casa_editrice = $genere = $anno = $trama = $n_copie = '';
    if(isset($_GET['id'])){ //id settato quindi form per modifica
        $DOM = str_replace('{{Azione}}', 'Modifica', $DOM);
        $id_libro = $_GET['id'];
        $pdo = connectDB();
        if($pdo){
            $stmt = $pdo->prepare("SELECT * FROM Libri JOIN Autori WHERE ID_Libro = :id_libro AND Autore = ID_Autore");
            $stmt->bindParam(':id_libro', $id_libro, PDO::PARAM_INT);
            if($stmt->execute()){ //query eseguita correttamente
                $result = $stmt->fetch(PDO::FETCH_ASSOC);
                $titolo = $result["Titolo"];
                $autore = $result["Nome"] . " " . $result["Cognome"];
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
            }
        }
        else {
            //problema con la connessione a db
        }
    }
    else { //form per l'aggiunta di un libro
        $DOM = str_replace('{{Azione}}', 'Aggiungi', $DOM);
        $DOM = str_replace('{{Titolo}}', $titolo, $DOM);
        $DOM = str_replace('{{Autore}}', $autore, $DOM);
        $DOM = str_replace('{{Casa_Editrice}}', $casa_editrice, $DOM);
        $DOM = str_replace('{{Genere}}', $genere, $DOM);
        $DOM = str_replace('{{Pubblicazione}}', $anno, $DOM);
        $DOM = str_replace('{{Trama}}', $trama, $DOM);
        $DOM = str_replace('{{Numero_copie}}', $n_copie, $DOM);
    }

    echo $DOM;
?>