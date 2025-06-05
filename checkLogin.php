<?php
    require "utils.php";
    session_start();

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $pdo = connectDB();
        if($pdo){
            $username = $_POST['username'] ?? '';
            $password = $_POST['password'] ?? '';
            $stmt = $pdo->prepare("SELECT * FROM Clienti WHERE Username = :username");
            $stmt->bindParam(':username', $username, PDO::PARAM_STR);
            $stmt->execute();
            if ($stmt->rowCount() > 0) {
                $user = $stmt->fetch(PDO::FETCH_ASSOC);
                if (password_verify($password, $user['Pass'])){


                $_SESSION['username'] = $username; //salva username e id_cliente in sessione
                $_SESSION['ID_Cliente'] = $user['ID_Cliente'];
                $_SESSION['is_logged_in'] = true; //per capire se è loggato o no
                $_SESSION['ruolo'] = $user['Ruolo'];
                if($user['Ruolo'] == 'Cliente') {
                    header("Location: index.php"); //viene mandato alla pagina principale 
                    exit();
                }
                else { //login admin
                    header("Location: admin.php");
                }
            } else{
              /*  header("Location: permission_denied.php"); password sbagliata */
            }
        }
    }
    
}
?>
