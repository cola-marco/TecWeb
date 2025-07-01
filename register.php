<?php
    $metaDescription = 'Crea un nuovo account per prenotare libri e scrivere recensioni.';
    $metaKeywords    = 'registrazione, account, biblioteca';
    
    include "templates/header.php";

    $DOM = file_get_contents('html/register.html');

    $usernameErr = '';
    $emailErr = '';
    $passErr = '';
    $confpassErr = '';
    $DOM = str_replace('###userError###', $usernameErr, $DOM);
    $DOM = str_replace('###emailError###', $emailErr, $DOM);
    $DOM = str_replace('###passError###', $passErr, $DOM);
    $DOM = str_replace('###confpassError###', $confpassErr, $DOM);

    echo $DOM;
    include "templates/footer.php";
?>