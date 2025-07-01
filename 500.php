<?php
http_response_code(500);

$metaDescription = 'Errore 500 - pagina momentaneamente non disponibile.';
$metaKeywords    = 'errore 500, pagina non disponibile';

include "templates/header.php"; 

$DOM = file_get_contents("html/500.html");

echo $DOM;
include "templates/footer.php";
?>