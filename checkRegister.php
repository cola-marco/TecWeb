<?php
    include "templates/header.php"; 
    require 'utils.php';
    session_start();

    $DOM = file_get_contents("html/register.html");
    $formValido = true;
    //inizializzazione variabili form
    $username = '';
    $email = '';
    $password = '';
    $conferma_password = '';

    //inizializzazione variabili per messaggi di errore
    $usernameErr = '';
    $emailErr = '';
    $passErr = '';
    $confpassErr = '';

    if($_SERVER['REQUEST_METHOD']=="POST"){ //bottone submit premuto
        //controllo input 

        $username = $_POST['username'];
        if(strlen($username) == 0){
            $usernameErr .= '<li>Username non inserito</li>';
            $formValido = false;
        }
        else if(strlen(trim($username)) == 0){ //composto da soli spazi
            $usernameErr .= '<li>Username composto da soli spazi</li>';
            $formValido = false;
        }
        $username = pulisciInput($username); //chiamata dopo perchè se no la funzione fa trim(), toglie gli spazi e non si vede se sono inseriti solo spazi nel form
        if(strlen($username) < 3){
            $usernameErr .= '<li>Username deve essere composto da almeno 3 caratteri</li>';
            $formValido = false;
        }

        $email = $_POST['email'];
        if(strlen($email) == 0){
            $emailErr .= '<li>Email non inserita</li>';
            $formValido = false;
        }
        if(strlen(trim($email)) == 0){
            $emailErr .= '<li>Email composta da soli spazi</li>';
            $formValido = false;
        }
        $email = pulisciInput($email);
        $email_filtred = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
        if(!$email_filtred){ //controlla che l'email sia scritta nel formato corretto
            $emailErr .= '<li>Email non valida</li>';
            $formValido = false;
        }

        $password = $_POST['password'];
        if(strlen($password) == 0){
            $passErr .= '<li>Password non inserita</li>';
            $formValido = false;
        }
        if(strlen(trim($password)) == 0){
            $passErr .= '<li>Password composta da soli spazi</li>';
            $formValido = false;
        }
        $password = trim($password); //non usa pulisciInput perchè potrebbe togliere caratteri importanti
        if(strlen($password) < 8){
            $passErr .= '<li>La password deve avere almeno 8 caratteri</li>';
            $formValido = false;
        }

        $conferma_password = $_POST['confirm-password'];
        if(strlen($conferma_password) == 0){
            $confpassErr .= '<li>Password di conferma non inserita</li>';
            $formValido = false;
        }
        if(strlen(trim($conferma_password)) == 0){
            $confpassErr .= '<li>Password di conferma composta da soli spazi</li>';
            $formValido = false;
        }
        $conferma_password = trim($conferma_password);
        if($password !== $conferma_password){
            $confpassErr .= '<li>Password di conferma diversa dalla password inserita</li>';
            $formValido = false;
        }

        $password = password_hash($password, PASSWORD_DEFAULT); // Hash della password
        //se il form è valido provo a connettermi al database
        if($formValido){
            $pdo = connectDB();
            if($pdo){
                //connessione al db riuscita
                //controllo che l'email inserita non sia già stata utilizzata
                $sqlCheckEmail = "SELECT * FROM Clienti WHERE Email = :email";
                $stmt = $pdo->prepare($sqlCheckEmail);
                $stmt->bindParam(':email', $email, PDO::PARAM_STR);
                $stmt->execute();
                //controllo che l'username inserito non sia già stato utilizzato 
                $sqlCheckUsername = "SELECT * FROM Clienti WHERE Username = :username";
                $stmt2 = $pdo->prepare($sqlCheckUsername);
                $stmt2->bindParam(':username', $username, PDO::PARAM_STR);
                $stmt2->execute();
                
                if ($stmt->rowCount() > 0) { //email già stata utilizzata
                    $emailErr .= '<li>Email già utilizzata</li>';
                }
                if ($stmt2->rowCount() > 0) { //username già stato utilizzato
                    $usernameErr .= '<li>Username già utilizzato</li>';
                }
                if($emailErr || $usernameErr){ //email o user già utilizzate, devo rimandare al form
                    $DOM = str_replace('<userError/>', $usernameErr, $DOM);
                    $DOM = str_replace('<emailError/>', $emailErr, $DOM);
                    echo($DOM);
                    exit();
                }
                //email, username non ancora registrati
                //registro dati su db e reindirizzo alla pagina principale
                $sqlInsert = "INSERT INTO Clienti (Email,Username,Pass) 
                                VALUES (:email, :username, :password)";             
                $stmt = $pdo->prepare($sqlInsert);
                $stmt->bindParam(':email', $email, PDO::PARAM_STR);
                $stmt->bindParam(':username', $username, PDO::PARAM_STR);
                $stmt->bindParam(':password', $password, PDO::PARAM_STR);
                if($stmt->execute()){
                    $_SESSION['success_message'] = "Registrazione avvenuta con successo!";
                    header("Location: login.php"); //reindirizzo alla pagina di login
                    exit();
                }
                else { 
                    $_SESSION['error_message'] = "Abbiamo avuto un problema con la registrazione";
                    header("Location: register.php"); //reindirizzo alla pagina di registrazione (problema con l'esecuzione della query)
                    exit();
                }
            } else {
                //problema con la connessione al DB, immagino si farà reindirizzamento ad una pagina di errore
                exit();
            }
        } else { //form non valido
            //faccio visualizzare i messaggi di errore del form
            $DOM = str_replace('###userError###', $usernameErr, $DOM);
            $DOM = str_replace('###emailError###', $emailErr, $DOM);
            $DOM = str_replace('###passError###', $passErr, $DOM);
            $DOM = str_replace('###confpassError###', $confpassErr, $DOM);
            echo($DOM);
            exit();
        }
    }

    echo($DOM);
    include "templates/footer.php";
?>