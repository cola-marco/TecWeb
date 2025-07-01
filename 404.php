<?php
http_response_code(404);

$metaDescription = 'Errore 404 - pagina momentaneamente non disponibile.';
$metaKeywords    = 'errore 404, pagina non disponibile';

include "templates/header.php"; 

$DOM = file_get_contents("html/404.html");

echo $DOM;
include "templates/footer.php";
?>