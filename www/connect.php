<?php
    $host = "postgres_exo_docker";
    $username = "postgres";
    $password = "root";
    $db = "gestion_produits";
    $port = 5432;

    try {
        $dsn = "pgsql:host=$host;port=$port;dbname=$db;";
        $conn = new PDO($dsn, $username, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $e) {
        die("Erreur de connexion à la base de données: " . $e->getMessage());
    }
?>
