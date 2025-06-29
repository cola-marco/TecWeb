<?php 
require 'utils.php';
session_start();
header("Content-type: application/json");
$pdo = connectDB();

$risultato = false;

if(isset($_POST['checkUser'])){
    $UserRicevuto = pulisciInput($_POST['checkUser']);
    if($pdo){
        if(isset($_SESSION['is_logged_in']) && $_SESSION['is_logged_in'] === true){
            $idCliente = $_SESSION['ID_Cliente'];
            $sqlCheckUser = "SELECT * FROM Clienti WHERE Username = :username AND ID_Cliente != :id_cliente";
            $stmt = $pdo->prepare($sqlCheckUser);
            $stmt->bindParam(':username', $UserRicevuto, PDO::PARAM_STR);
            $stmt->bindParam(':id_cliente', $idCliente, PDO::PARAM_STR);
        }
        else {
            $sqlCheckUser = "SELECT * FROM Clienti WHERE Username = :username";
            $stmt = $pdo->prepare($sqlCheckUser);
            $stmt->bindParam(':username', $UserRicevuto, PDO::PARAM_STR);
        }

        $success = $stmt->execute();
        if(!$success) {
            echo json_encode(["error" => "Errore durante la ricerca degli username"]);
            exit();
        }
        if($stmt->rowCount() > 0){ //username già registrato
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