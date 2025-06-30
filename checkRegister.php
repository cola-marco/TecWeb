<?php
    include "templates/header.php"; 
    require 'utils.php';
    //session_start();

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
            $usernameErr .= '<p><span lang="en">Username</span> non inserito</p>';
            $formValido = false;
        }
        else if(strlen(trim($username)) == 0){ //composto da soli spazi
            $usernameErr .= '<p><span lang="en">Username</span> non può contenere soli spazi</p>';
            $formValido = false;
        }
        $username = pulisciInput($username); //chiamata dopo perchè se no la funzione fa trim(), toglie gli spazi e non si vede se sono inseriti solo spazi nel form
        if(strlen($username) < 3){
            $usernameErr .= '<p><span lang="en">Username</span> deve essere composto da almeno 3 caratteri</p>';
            $formValido = false;
        }

        $email = $_POST['email'];
        if(strlen($email) == 0){
            $emailErr .= '<p><span lang="en">Email</span> non inserita</p>';
            $formValido = false;
        }
        if(strlen(trim($email)) == 0){
            $emailErr .= '<p><span lang="en">Email</span> non può contenere soli spazi</p>';
            $formValido = false;
        }
        $email = pulisciInput($email);
        $email_filtred = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
        if(!$email_filtred){ //controlla che l'email sia scritta nel formato corretto
            $emailErr .= '<p><span lang="en">Email</span> non valida</p>';
            $formValido = false;
        }

        $password = $_POST['password'];
        if(strlen($password) == 0){
            $passErr .= '<p><span lang="en">Password</span> non inserita</p>';
            $formValido = false;
        }
        if(strlen(trim($password)) == 0){
            $passErr .= '<p><span lang="en">Password</span> non può contenere soli spazi</p>';
            $formValido = false;
        }
        $password = trim($password); //non usa pulisciInput perchè potrebbe togliere caratteri importanti
        if(strlen($password) < 4){
            $passErr .= '<p>La <span lang="en">password</span> deve avere almeno 4 caratteri</p>';
            $formValido = false;
        }

        $conferma_password = $_POST['confirm-password'];
        if(strlen($conferma_password) == 0){
            $confpassErr .= '<p><span lang="en">Password</span> di conferma non inserita</p>';
            $formValido = false;
        }
        if(strlen(trim($conferma_password)) == 0){
            $confpassErr .= '<p><span lang="en">Password</span> di conferma non può contenere soli spazi</p>';
            $formValido = false;
        }
        $conferma_password = trim($conferma_password);
        if($password !== $conferma_password){
            $confpassErr .= '<p><span lang="en">Password</span> di conferma diversa dalla <span lang="en">password<span/> inserita</p>';
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
                    $emailErr .= '<p><span lang="en">Email</span> già utilizzata</p>';
                }
                if ($stmt2->rowCount() > 0) { //username già stato utilizzato
                    $usernameErr .= '<p><span lang="en">Username</span> già utilizzato</p>';
                }
                if($emailErr || $usernameErr){ //email o user già utilizzate, devo rimandare al form
                    /*if($usernameErr) $usernameErr = '<ul class="error-msg" aria-live="polite">' . $usernameErr . '</ul>';
                    if($emailErr) $emailErr = '<ul class="error-msg" aria-live="polite">' . $emailErr . '</ul>';
                    if($passErr) $passErr = '<ul class="error-msg" aria-live="polite">' . $passErr . '</ul>';
                    if($confpassErr) $confpassErr = '<ul class="error-msg" aria-live="polite">' . $confpassErr . '</ul>';*/
                    $DOM = str_replace('###userError###', $usernameErr, $DOM);
                    $DOM = str_replace('###emailError###', $emailErr, $DOM);
                    $DOM = str_replace('###passError###', $passErr, $DOM);
                    $DOM = str_replace('###confpassError###', $confpassErr, $DOM);
                    echo($DOM);
                    include "templates/footer.php";
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
                    header("Location: 505.php"); //reindirizzo alla pagina di registrazione (problema con l'esecuzione della query)
                    exit();
                }
            } else {
                //problema con la connessione al DB
                header("Location: 505.php");
                exit();
            }
        } else { //form non valido
            //faccio visualizzare i messaggi di errore del form
            /*if($usernameErr) $usernameErr = '<ul class="error-msg" aria-live="polite">' . $usernameErr . '</ul>';
            if($emailErr) $emailErr = '<ul class="error-msg" aria-live="polite">' . $emailErr . '</ul>';
            if($passErr) $passErr = '<ul class="error-msg" aria-live="polite">' . $passErr . '</ul>';
            if($confpassErr) $confpassErr = '<ul class="error-msg" aria-live="polite">' . $confpassErr . '</ul>';*/
            $DOM = str_replace('###userError###', $usernameErr, $DOM);
            $DOM = str_replace('###emailError###', $emailErr, $DOM);
            $DOM = str_replace('###passError###', $passErr, $DOM);
            $DOM = str_replace('###confpassError###', $confpassErr, $DOM);
            echo($DOM);
            include "templates/footer.php";
            exit();

        }
    }

    echo($DOM);
    include "templates/footer.php";
?>