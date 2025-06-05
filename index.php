<?php
    require 'utils.php';
    session_start();
    check_session_timeout();
    include "templates/header.php";  
    $DOM = file_get_contents('html/index.html');
    echo $DOM;
    include "templates/footer.php";
?>