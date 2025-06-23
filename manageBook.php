<?php
    include "templates/header.php";
    require 'utils.php';
    //session_start();

    $DOM = file_get_contents("html/admin-form.html");
    $formValido = true;
    //inizializzazione variabili form
    $id_libro = $titolo = $autore = $imagePath = $casa_editrice = $genere = $anno = $trama = $n_copie = '';
    $imageLoaded = false;
    $titoloErr = $autoreErr = $imageErr = $casaErr = $genereErr = $annoErr = $tramaErr = $ncopieErr = '';

    if($_SERVER['REQUEST_METHOD']=="POST"){ //bottone submit premuto
        //controllo input

        $titolo = $_POST['titolo'];
        if(strlen($titolo) == 0){
            $titoloErr .= '<p>Titolo non inserito</p>';
            $formValido = false;
        }
        else if(strlen(trim($titolo)) == 0){ //composto da soli spazi
            $titoloErr .= '<p>Titolo non può contenere soli spazi</p>';
            $formValido = false;
        }
        $titolo = pulisciInput($titolo);

        $autore = $_POST['autore'];
        if(strlen($autore) == 0){
            $autoreErr .= '<p>Autore non inserito</p>';
            $formValido = false;
        }
        else if(strlen(trim($autore)) == 0){ //composto da soli spazi
            $autoreErr .= '<p>Autore non può contenere soli spazi</p>';
            $formValido = false;
        }
        else if(!preg_match("/^[\p{L}\s'\-\.]+$/u", $autore)){ //regex non permette numeri e caratteri strani
            $autoreErr .= '<p>Autore non può contenere numeri o caratteri speciali</p>';
            $formValido = false;
        }
        $autore = pulisciInput($autore);

        // preparazione immagine

        $uploadDir = __DIR__ . '/Immagini/';

        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true); // crea la cartella se non esiste
        }

        $imagePath = 'Immagini/default_book_cover.png'; // default

        if (isset($_FILES['image_path']) && $_FILES['image_path']['error'] !== UPLOAD_ERR_NO_FILE) {
            $imageLoaded = true;
            $allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/webp'];
            $maxSize = 1 * 1024 * 1024; // 1MB

            $fileTmpPath = $_FILES['image_path']['tmp_name'];
            $fileName = basename($_FILES['image_path']['name']);
            $fileSize = $_FILES['image_path']['size'];
            $fileType = mime_content_type($fileTmpPath);

            if (!in_array($fileType, $allowedTypes)) {
                $imageErr .= "<p>Formato immagine non consentito. Usa <abbr title='Joint Photographic Experts Group'>JPG</abbr>, <abbr title='Portable Network Graphics'>PNG</abbr> o <abbr title='Web Picture format'>WEBP</abbr>.</p>";
                $formValido = false;
            } elseif ($fileSize > $maxSize) {
                $imageErr .= "<p>Dimensione immagine troppo grande. Max 1MB.</p>";
                $formValido = false;
            } else {
                // Estrazione estensione
                $fileExtension = pathinfo($fileName, PATHINFO_EXTENSION);
                // Nome file unico per evitare sovrascritture
                $newFileName = uniqid('cover_', true) . '.' . $fileExtension;
                $destPath = $uploadDir . $newFileName;

                if (move_uploaded_file($fileTmpPath, $destPath)) {
                    // Salva il path relativo, così poi puoi mostrarla nel catalogo
                    $imagePath = 'Immagini/' . $newFileName;
                } else {
                    $imageErr = "<p>Errore nel caricamento dell'immagine.</p>";
                    $formValido = false;
                }
            }
        }

        $casa_editrice = $_POST['casa_editrice'];
        if(strlen($casa_editrice) == 0){
            $casaErr .= '<p>Casa editrice non inserita</p>';
            $formValido = false;
        }
        else if(strlen(trim($casa_editrice)) == 0){ //composto da soli spazi
            $casaErr .= '<p>Casa editrice non può contenere soli spazi</p>';
            $formValido = false;
        }
        $casa_editrice = pulisciInput($casa_editrice);

        $genere = $_POST['genere'];
        if(strlen($genere) == 0){
            $genereErr .= '<p>Genere non inserito</p>';
            $formValido = false;
        }
        else if(strlen(trim($genere)) == 0){ //composto da soli spazi
            $genereErr .= '<p>Genere non può contenere soli spazi</p>';
            $formValido = false;
        }
        else if(!preg_match("/^[\p{L}\s'\-]+$/u", $genere)){ //regex non permette numeri e caratteri strani
            $genereErr .= '<p>Genere non può contenere numeri o caratteri speciali</p>';
            $formValido = false;
        }
        $genere = pulisciInput($genere);

        $anno = $_POST['pubblicazione'];
        if(strlen($anno) == 0){
            $annoErr .= '<p>Anno di pubblicazione non inserito</p>';
            $formValido = false;
        }
        else if(strlen(trim($anno)) == 0){ //composto da soli spazi
            $annoErr .= '<p>Anno di pubblicazione non può contenere soli spazi</p>';
            $formValido = false;
        }
        else if(!filter_var($anno, FILTER_VALIDATE_INT)){ //non contiene solo numeri
            $annoErr .= '<p>Anno di pubblicazione può contenere solo numeri naturali</p>';
            $formValido = false;
        }
        $anno = pulisciInput($anno);

        $trama = $_POST['trama'];
        if(strlen($trama) == 0){
            $tramaErr .= '<p>Trama non inserita</p>';
            $formValido = false;
        }
        else if(strlen(trim($anno)) == 0){ //composto da soli spazi
            $tramaErr .= '<p>Trama non può contenere soli spazi</p>';
            $formValido = false;
        }
        $trama = pulisciInput($trama);

        $n_copie = $_POST['numero_copie'];
        if(strlen($n_copie) == 0){
            $ncopieErr .= '<p>Numero di copie non inserito</p>';
            $formValido = false;
        }
        else if(strlen(trim($n_copie)) == 0){ //composto da soli spazi
            $ncopieErr .= '<p>Numero di copie non può contenere soli spazi</p>';
            $formValido = false;
        }
        else if(!filter_var($n_copie, FILTER_VALIDATE_INT)){ //non contiene solo numeri
            $ncopieErr .= '<p>Numero di copie può contenere solo numeri naturali</p>';
            $formValido = false;
        }
        $n_copie = pulisciInput($n_copie);

        //terminata validazione input
        if($formValido){
            $pdo = connectDB();
            if($pdo){
                if(isset($_GET['id'])){ //modifica di un libro
                    $id_libro = $_GET['id'];
                    if($imageLoaded){ //immagine caricata nel form
                        $sqlUpdate = "UPDATE Libri
                                        SET 
                                            Titolo = :titolo,
                                            Autore = :autore,
                                            Image_path = :image_path,
                                            Casa_Editrice = :casa_editrice,
                                            Genere = :genere,
                                            Pubblicazione = :pubblicazione,
                                            Trama = :trama,
                                            Numero_copie = :numero_copie
                                        WHERE 
                                            ID_Libro = :id_libro;
                                    ";
                        $stmt = $pdo->prepare($sqlUpdate);
                        $stmt->bindParam(':titolo', $titolo, PDO::PARAM_STR);
                        $stmt->bindParam(':autore', $autore, PDO::PARAM_STR);
                        $stmt->bindParam(':image_path', $imagePath, PDO::PARAM_STR);
                        $stmt->bindParam(':casa_editrice', $casa_editrice, PDO::PARAM_STR);
                        $stmt->bindParam(':genere', $genere, PDO::PARAM_STR);
                        $stmt->bindParam(':pubblicazione', $anno, PDO::PARAM_INT);
                        $stmt->bindParam(':trama', $trama, PDO::PARAM_STR);
                        $stmt->bindParam(':numero_copie', $n_copie, PDO::PARAM_INT);
                        $stmt->bindParam(':id_libro', $id_libro, PDO::PARAM_INT);
                        if($stmt->execute()){
                            $_SESSION['success_message'] = "Libro modificato con successo!";
                            header("Location: admin.php");
                            exit();
                        }
                        else { 
                            $_SESSION['error_message'] = "Abbiamo avuto un problema con la modifica del libro";
                            header("Location: admin-form.php");
                            exit();
                        }
                    }
                    else { //immagine non caricata
                        $sqlUpdate = "UPDATE Libri
                                        SET 
                                            Titolo = :titolo,
                                            Autore = :autore,
                                            Casa_Editrice = :casa_editrice,
                                            Genere = :genere,
                                            Pubblicazione = :pubblicazione,
                                            Trama = :trama,
                                            Numero_copie = :numero_copie
                                        WHERE 
                                            ID_Libro = :id_libro;
                                    ";
                        $stmt = $pdo->prepare($sqlUpdate);
                        $stmt->bindParam(':titolo', $titolo, PDO::PARAM_STR);
                        $stmt->bindParam(':autore', $autore, PDO::PARAM_STR);
                        $stmt->bindParam(':casa_editrice', $casa_editrice, PDO::PARAM_STR);
                        $stmt->bindParam(':genere', $genere, PDO::PARAM_STR);
                        $stmt->bindParam(':pubblicazione', $anno, PDO::PARAM_INT);
                        $stmt->bindParam(':trama', $trama, PDO::PARAM_STR);
                        $stmt->bindParam(':numero_copie', $n_copie, PDO::PARAM_INT);
                        $stmt->bindParam(':id_libro', $id_libro, PDO::PARAM_INT);
                        if($stmt->execute()){
                            $_SESSION['success_message'] = "Libro modificato con successo!";
                            header("Location: admin.php");
                            exit();
                        }
                        else { 
                            $_SESSION['error_message'] = "Abbiamo avuto un problema con la modifica del libro";
                            header("Location: admin-form.php");
                            exit();
                        }
                    }
                }
                else { //aggiunta di un libro
                    $sqlInsert = "INSERT INTO Libri (Titolo, Autore, Image_path, Casa_Editrice, Genere, Pubblicazione, Trama, Numero_copie) 
                                    VALUES (:titolo, :autore, :image_path, :casa_editrice, :genere, :pubblicazione, :trama, :numero_copie);";
                    $stmt = $pdo->prepare($sqlInsert);
                    $stmt->bindParam(':titolo', $titolo, PDO::PARAM_STR);
                    $stmt->bindParam(':autore', $autore, PDO::PARAM_STR);
                    $stmt->bindParam(':image_path', $imagePath, PDO::PARAM_STR);
                    $stmt->bindParam(':casa_editrice', $casa_editrice, PDO::PARAM_STR);
                    $stmt->bindParam(':genere', $genere, PDO::PARAM_STR);
                    $stmt->bindParam(':pubblicazione', $anno, PDO::PARAM_INT);
                    $stmt->bindParam(':trama', $trama, PDO::PARAM_STR);
                    $stmt->bindParam(':numero_copie', $n_copie, PDO::PARAM_INT);
                    if($stmt->execute()){
                        $_SESSION['success_message'] = "Libro aggiunto con successo!";
                        header("Location: admin.php");
                        exit();
                    }
                    else { 
                        $_SESSION['error_message'] = "Abbiamo avuto un problema con l'aggiunta del libro";
                        header("Location: admin-form.php");
                        exit();
                    }
                }
            }
            else {
                //connessione al db fallita
                exit();
            }
        }
        else if(!$formValido && isset($_GET['id'])) { //form non valido nel caso di modifica
            
            $DOM = str_replace('{{Azione}}', 'Modifica', $DOM);
            $DOM = str_replace('{{buttonAction}}', 'Modifica', $DOM);
            $id_libro = $_GET['id'];
            $form_action = 'manageBook.php?id=' . urlencode($id_libro);
            $DOM = str_replace('{{FormAction}}', $form_action, $DOM);
            $DOM = str_replace('{{Titolo}}', $titolo, $DOM);
            $DOM = str_replace('{{Autore}}', $autore, $DOM);
            $DOM = str_replace('{{Casa_Editrice}}', $casa_editrice, $DOM);
            $DOM = str_replace('{{Genere}}', $genere, $DOM);
            $DOM = str_replace('{{Pubblicazione}}', $anno, $DOM);
            $DOM = str_replace('{{Trama}}', $trama, $DOM);
            $DOM = str_replace('{{Numero_copie}}', $n_copie, $DOM);

            $DOM = str_replace('###titoloError###', $titoloErr, $DOM);
            $DOM = str_replace('###autoreError###', $autoreErr, $DOM);
            $DOM = str_replace('###imageError###', $imageErr, $DOM);
            $DOM = str_replace('###casaedError###', $casaErr, $DOM);
            $DOM = str_replace('###genereError###', $genereErr, $DOM);
            $DOM = str_replace('###annopubbError###', $annoErr, $DOM);
            $DOM = str_replace('###tramaError###', $tramaErr, $DOM);
            $DOM = str_replace('###ncopieError###', $ncopieErr, $DOM);
            echo($DOM);
        }
        else if (!$formValido && !isset($_GET['id'])) { //form non valido nel caso di aggiunta
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

            $DOM = str_replace('###titoloError###', $titoloErr, $DOM);
            $DOM = str_replace('###autoreError###', $autoreErr, $DOM);
            $DOM = str_replace('###imageError###', $imageErr, $DOM);
            $DOM = str_replace('###casaedError###', $casaErr, $DOM);
            $DOM = str_replace('###genereError###', $genereErr, $DOM);
            $DOM = str_replace('###annopubbError###', $annoErr, $DOM);
            $DOM = str_replace('###tramaError###', $tramaErr, $DOM);
            $DOM = str_replace('###ncopieError###', $ncopieErr, $DOM);
            echo($DOM);
        }

    }
    include "templates/footer.php";
?>