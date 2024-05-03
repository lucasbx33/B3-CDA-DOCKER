<?php
    if (isset($_POST['US_login']) && isset($_POST['US_password'])) {
        session_start();
        include 'connect.php';

        $US_login = $_POST['US_login'];
        $US_password = $_POST['US_password'];

        $hashed_password = hash('sha256', $US_password);

        $sql = "SELECT * FROM utilisateurs WHERE us_login = :login AND us_password = :hashed_password";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':login', $US_login);
        $stmt->bindParam(':hashed_password', $hashed_password);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $utilisateur = $stmt->fetch(PDO::FETCH_ASSOC);
            $_SESSION['login'] = $utilisateur['us_login'];
            header("Location: home.php");
        } else {
            header("Location: index.php");
        }
    }
?>
