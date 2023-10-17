<?php
    /* DB credentials for Melo's W.O.R.K computer */
    $db_server = "localhost";
    $db_name = "congelagosdb";
    $db_username = "CGL_usr";
    $db_password = "CGL_P455W0rd&";

    /* DB credentials for Melo's H.O.M.E computer
    $db_server = "localhost";
    $db_name = "ljmelo_apps";
    $db_username = "ljmelo_user";
    $db_password = "R415P4rt4&"; */

    /* DB credentials for the SERVER
    $db_server = "localhost";
    $db_name = "wwwcongelagos_apps";
    $db_username = "wwwcongelagos_apps";
    $db_password = "C0ng3l4g05&"; */

    $conn = mysqli_connect($db_server, $db_username, $db_password, $db_name);

    // check if the connection was successful
    if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
    }
?>