<?php
    $user = 'root';
    $pass = '';
    $host = 'localhost';
    $dbName = 'db_finanzas';

    $connection = mysqli_connect("localhost", "root", "", "db_finanzas");
    
    // Verificar conexión
    if (!$connection) {
        die("No se pudo hacer la conexión: " . mysqli_connect_error());
    } else {
        mysqli_select_db($connection, $dbName);
    }
?>
