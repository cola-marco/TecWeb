<?php
    include "templates/header.php";
    require "utils.php";
    //session_start();
    $DOM = file_get_contents('html/login.html');

    $userError = $passError = '';
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $pdo = connectDB();
        if($pdo){
            $username = $_POST['username'] ?? '';
            $password = $_POST['password'] ?? '';
            $stmt = $pdo->prepare("SELECT * FROM Clienti WHERE Username = :username");
            $stmt->bindParam(':username', $username, PDO::PARAM_STR);
            $stmt->execute();
            if ($stmt->rowCount() > 0) {
                $user = $stmt->fetch(PDO::FETCH_ASSOC);
                if (password_verify($password, $user['Pass'])){
                    $_SESSION['username'] = $username; //salva username e id_cliente in sessione
                    $_SESSION['ID_Cliente'] = $user['ID_Cliente'];
                    $_SESSION['is_logged_in'] = true; //per capire se è loggato o no
                    $_SESSION['ruolo'] = $user['Ruolo'];
                    $_SESSION['user_role'] = $user['Ruolo'];
                    
                    if($user['Ruolo'] == 'Cliente') {
                        header("Location: login.php"); //viene mandato alla pagina di login 
                        exit();
                    }
                    else { //login admin
                        header("Location: admin.php");
                        exit();
                    }
                } else{ //password errata 
                    $passError .= '<p><span lang="en">Password</span> errata</p>';
                }
            } else { //user non trovato
                $userError .= '<p><span lang="en">Username</span> non esistente</p>';
            }
        }
        else {
            //errore di connessione al db
            header("Location: 505.php");
            exit();

        }
        $DOM = str_replace('###passError###', $passError, $DOM);
        $DOM = str_replace('###userError###', $userError, $DOM);
        echo $DOM;
        include "templates/footer.php";
    }
    
?>
