<?php
include "templates/header.php";
require 'utils.php';
$pdo = connectDB();
session_start();
check_session_timeout();

if(isLogged() == 1){
    $form_recensione ='
    <div class="recensione">
        <h4>Recensione</h4>
        <form action="recensione.php" class="form-recensione" method="post">
            <select name="" id="">
                <option value="">Seleziona un\'opzione</option>
                <option value="1">1</option>
                <option value="2">2</option>
                <option value="3">3</option>
                <option value="4">4</option>
                <option value="5">5</option>
            </select>
            <textarea name="" id=""></textarea>
            <button onclick="enter" type="submit">Invia</button>
        </form>
    </div>';

    $DOM = str_replace("###FORM-RECENSIONE###", $form_recensione, $DOM);
}
?>
