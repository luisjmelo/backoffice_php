<?php
    // create a database connection
    $db_server = "localhost";
    $db_name = "congelagosdb";
    $db_username = "CGL_usr";
    $db_password = "CGL_P455W0rd&";

    $conn = mysqli_connect($db_server, $db_username, $db_password, $db_name);

    // check if the connection was successful
    if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
    }
?>