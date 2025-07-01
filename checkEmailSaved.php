<?php 
require 'utils.php';
session_start();
header("Content-type: application/json");
$pdo = connectDB();

$risultato = false;

if(isset($_POST['checkEmail'])){
    $EmailRicevuta = pulisciInput($_POST['checkEmail']);
    if($pdo){
        if(isset($_SESSION['is_logged_in']) && $_SESSION['is_logged_in'] === true){
            $idCliente = $_SESSION['ID_Cliente'];
            $sqlCheckEmail = "SELECT * FROM Clienti WHERE Email = :email AND ID_Cliente != :id_cliente";
            $stmt = $pdo->prepare($sqlCheckEmail);
            $stmt->bindParam(':email', $EmailRicevuta, PDO::PARAM_STR);
            $stmt->bindParam(':id_cliente', $idCliente, PDO::PARAM_STR);
        }
        else {
            $sqlCheckEmail = "SELECT * FROM Clienti WHERE Email = :email";
            $stmt = $pdo->prepare($sqlCheckEmail);
            $stmt->bindParam(':email', $EmailRicevuta, PDO::PARAM_STR);
        }
        
        $success = $stmt->execute();
        if(!$success) {
            echo json_encode(["error" => "Errore durante la ricerca delle email"]);
            exit();
        }
        if($stmt->rowCount() > 0){ //email già registrata
            $risultato = true;
        }
        echo json_encode(["result" => $risultato]);
        exit();
    }
    else{
        //problemi di connessione a database
        echo json_encode(["error" => "Connessione al database fallita"]);
        exit();
    }
}

?>