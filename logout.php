<?php
    session_start();

    if (isset($_SESSION['is_logged_in']) && $_SESSION['is_logged_in'] === true) {
        session_unset();
        session_destroy();
        $_SESSION['logout_success'] = "Sei stato disconnesso con successo!";
    } else {
        $_SESSION['logout_error'] = "Non sei loggato!";
    }
    header("Location: login.php");
    exit();
?>