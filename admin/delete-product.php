<?php
    session_start();
    require "../config/db.php";

    if (!isset($_SESSION["id"])) {
        header("Location: " . $BASE_URL . "auth.php");
        exit;
    }

    if ($_SESSION["role"] === "kasir") {
        header("Location: " . $BASE_URL . "kasir/dashboard.php");
        exit;
    }

    if (isset($_GET['id'])) {
        $id = $_GET['id'];
        $outlet_code = $_SESSION['outlet_code'];

        $query = "DELETE FROM product WHERE id = '$id' AND outlet_code = '$outlet_code'";
        
        if (mysqli_query($conn, $query)) {
            header("Location:" . $BASE_URL . "admin/dashboard.php?status=deleted");
        } else {
            header("Location: " . $BASE_URL . "admin/dashboard.php?status=error");
        }
    } else {
        header("Location: " . $BASE_URL . "admin/dashboard.php");
    }
    exit;