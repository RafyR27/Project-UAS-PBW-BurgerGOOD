<?php
    $host = "localhost";
    $user = "root";
    $pass = "";
    $db = "burgergood";

    $conn = mysqli_connect($host, $user, $pass, $db);

    $BASE_URL = "http://localhost/burgergood/";

    if(!$conn){
        die("Koneksi gagal: " . mysqli_connect_error());
    }
?>