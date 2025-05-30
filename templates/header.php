<?php
    // Ottieni il nome del file corrente
    $currentPage = basename($_SERVER['PHP_SELF']);
    $DOM = file_get_contents('./html/templates/header.html');
    echo $DOM;
?>