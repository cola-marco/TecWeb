<?php
$metaDescription = 'Errore 505 - pagina momentaneamente non disponibile.';
$metaKeywords    = 'errore 505, pagina non disponibile';

include "templates/header.php"; 

$DOM = file_get_contents("html/505.html");

echo $DOM;
include "templates/footer.php";
?>