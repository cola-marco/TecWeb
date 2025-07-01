<?php
    //session_start();

    $metaDescription = 'Chi siamo - Biblioteca Luzzatti di Padova';
    $metaKeywords    = 'biblioteca, padova, chi siamo, team, missione';

    require 'utils.php';
    check_session_timeout();

    include 'templates/header.php';

    echo file_get_contents('html/chi-siamo.html');

    include 'templates/footer.php';
?>
