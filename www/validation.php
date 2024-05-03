<?php

include 'connect.php';

$action = (isset($_POST['action'])) ? $_POST['action'] : $_GET['action'];

switch ($action) {
    
    case 'ajout_produit':

        $pro_lib = ($_POST['pro_lib'] != '') ? $_POST['pro_lib'] : null;
        $pro_description = ($_POST['pro_description'] != '') ? $_POST['pro_description'] : null;
        $pro_prix = ($_POST['pro_prix'] != '') ? str_replace(',', '.', $_POST['pro_prix']) : null;

        $sql = "INSERT INTO produits (pro_lib, pro_description, pro_prix) VALUES (:pro_lib, :pro_description, :pro_prix)";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':pro_lib', $pro_lib);
        $stmt->bindParam(':pro_description', $pro_description);
        $stmt->bindParam(':pro_prix', $pro_prix);
        if ($stmt->execute()) {
            $pro_id = $conn->lastInsertId();

            foreach ($_FILES["pro_ressources"]["error"] as $key => $error) {
                if ($error == UPLOAD_ERR_OK) {
                    $tmp_name = $_FILES["pro_ressources"]["tmp_name"][$key];
                    $extension = pathinfo($_FILES["pro_ressources"]["name"][$key], PATHINFO_EXTENSION);
                    $md5 = md5_file($tmp_name);
                    $name = $pro_id . "-" . $md5 . "." . $extension;
                    $url = "uploads/$name";
                    move_uploaded_file($tmp_name, $url);

                    $sql = "INSERT INTO ressources (re_type, re_url, pro_id) VALUES ('img', :url, :pro_id)";
                    $stmt = $conn->prepare($sql);
                    $stmt->bindParam(':url', $url);
                    $stmt->bindParam(':pro_id', $pro_id);
                    $stmt->execute();
                }
            }

            header('Location: home.php');

        } else {
            die("Erreur SQL");
        }
        break;


    case 'modification_produit':

        $pro_id = ($_POST['pro_id'] != '') ? $_POST['pro_id'] : null;
        $pro_lib = ($_POST['pro_lib'] != '') ? $_POST['pro_lib'] : null;
        $pro_description = ($_POST['pro_description'] != '') ? $_POST['pro_description'] : null;
        $pro_prix = ($_POST['pro_prix'] != '') ? str_replace(',', '.', $_POST['pro_prix']) : null;

        $sql = "UPDATE produits SET pro_lib = :pro_lib, pro_description = :pro_description, pro_prix = :pro_prix WHERE pro_id = :pro_id";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':pro_id', $pro_id);
        $stmt->bindParam(':pro_lib', $pro_lib);
        $stmt->bindParam(':pro_description', $pro_description);
        $stmt->bindParam(':pro_prix', $pro_prix);
        if ($stmt->execute()) {
            foreach ($_FILES["pro_ressources"]["error"] as $key => $error) {
                if ($error == UPLOAD_ERR_OK) {
                    $tmp_name = $_FILES["pro_ressources"]["tmp_name"][$key];
                    $extension = pathinfo($_FILES["pro_ressources"]["name"][$key], PATHINFO_EXTENSION);
                    $md5 = md5_file($tmp_name);
                    $name = $pro_id . "-" . $md5 . "." . $extension;
                    $url = "uploads/$name";
                    move_uploaded_file($tmp_name, $url);

                    $sql = "INSERT INTO ressources (re_type, re_url, pro_id) VALUES ('img', :url, :pro_id)";
                    $stmt = $conn->prepare($sql);
                    $stmt->bindParam(':url', $url);
                    $stmt->bindParam(':pro_id', $pro_id);
                    $stmt->execute();
                }
            }

            header('Location: produit.php?id=' . $pro_id);

        } else {
            die("Erreur SQL");
        }
        break;

    
        case 'supprimer_ressource':
            if(isset($_POST['re_id'])) {
                $re_id = $_POST['re_id'];
        
                $sql = "SELECT * FROM ressources WHERE re_id = :re_id";
                $stmt = $conn->prepare($sql);
                $stmt->bindParam(':re_id', $re_id);
                $stmt->execute();
                $ressource = $stmt->fetch(PDO::FETCH_ASSOC);
                
                if($ressource) {
                    $sql = "DELETE FROM ressources WHERE re_id = :re_id"; // Utilisation de $re_id ici
                    $stmt = $conn->prepare($sql);
                    $stmt->bindParam(':re_id', $re_id);
                    if ($stmt->execute()) {
                        if (file_exists($ressource['re_url'])) {
                            unlink($ressource['re_url']);
                        }
                        echo 'OK';
                    } else {
                        echo 'NOK';
                    }
                } else {
                    echo 'NOK';
                }
            }
            break;
        
        case 'supprimer_produit':
            if(isset($_POST['pro_id'])) {
                $pro_id = $_POST['pro_id'];
        
                $sql = "SELECT * FROM produits WHERE pro_id = :pro_id";
                $stmt = $conn->prepare($sql);
                $stmt->bindParam(':pro_id', $pro_id);
                $stmt->execute();
                $produit = $stmt->fetch(PDO::FETCH_ASSOC);
                
                if($produit) {
                    $sql = "SELECT * FROM ressources WHERE pro_id = :pro_id";
                    $stmt = $conn->prepare($sql);
                    $stmt->bindParam(':pro_id', $pro_id);
                    $stmt->execute();
                    $ressources = $stmt->fetchAll(PDO::FETCH_ASSOC);
                    if ($ressources) {
                        foreach ($ressources as $ressource) {
                            $re_id = $ressource['re_id'];
                            $sql = "DELETE FROM ressources WHERE re_id = :re_id"; // Utilisation de $re_id ici
                            $stmt = $conn->prepare($sql);
                            $stmt->bindParam(':re_id', $re_id);
                            if ($stmt->execute()) {
                                if (file_exists($ressource['re_url'])) {
                                    unlink($ressource['re_url']);
                                }
                            }
                        }
                    }
        
                    $sql = "DELETE FROM produits WHERE pro_id = :pro_id";
                    $stmt = $conn->prepare($sql);
                    $stmt->bindParam(':pro_id', $pro_id);
                    if ($stmt->execute()) {
                        echo 'OK';
                    } else {
                        echo 'NOK';
                    }
        
                } else {
                    echo 'NOK';
                }
            }
        break;
        
    default:
        # code...
        break;
}

?>
