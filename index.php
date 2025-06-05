<?php
    require 'utils.php';
    session_start();
    check_session_timeout();
    

    $DOM = file_get_contents('html/index.html');
    echo $DOM;
?>