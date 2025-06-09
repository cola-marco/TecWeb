<?php
    include "templates/header.php";
    require 'utils.php';
    $pdo = connectDB();
    check_session_timeout();

    // Se l'utente è già loggato, reindirizza alla sua area appropriata
    if(isset($_SESSION['is_logged_in']) && $_SESSION['is_logged_in'] == true){
        if($_SESSION['ruolo'] == 'Admin') {
            $_SESSION['user_role'] = 'Admin';
            header("Location: admin.php");
            exit();
        } else {
            $_SESSION['user_role'] = 'cliente';
            header("Location: cliente.php");
            exit();
        }
    }

    // Se non è loggato, mostra il form di login
    $DOM = file_get_contents('html/login.html');

    // Gestione errori (vuoti se non ci sono errori)
    $DOM = str_replace('###passError###', '', $DOM);
    $DOM = str_replace('###userError###', '', $DOM);

    echo $DOM;
    include "templates/footer.php";
?>