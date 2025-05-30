<?php
    include "templates/header.php";
    $DOM = file_get_contents('html/register.html');
    /* $usernameErr = '';
    $emailErr = '';
    $passErr = '';
    $confpassErr = '';
    $DOM = str_replace('###USER_ERROR###', $usernameErr, $DOM);
    $DOM = str_replace('###EMAIL_ERROR###', $emailErr, $DOM);
    $DOM = str_replace('###PASS_ERROR###', $passErr, $DOM);
    $DOM = str_replace('###CONFPASS_ERROR###', $confpassErr, $DOM);  prima idea per non far visualizzare i segnaposti*/
    echo $DOM;
    include "templates/footer.php";
?>