<?php
    // There are 3 possible actions: 
    //      'drop' (drops DB objects), 
    //      'create' (creates DB objects), 
    //      'insert' (inserts specified records into tables)
    // URL passed ($_GET) action variable can contain a hyphen separeted list of commands (eg: action=drop-create-insert)
    // Only the sections specified in the action will be executed in the code below

    echo "<h1>DB setup for \"Serviço de Frio\"</h1>
          <ul>";

    if (!$_GET['action']){
        echo "<li>No action defined. Nothing was done!";
    } else {
        $action_arr = explode('-', $_GET['action']); // Convert the list into an array
    
        // create a database connection
        include '../app_components/db_connect.php';

        // **********************************************************************
        // ********************* DROP DB OBJECTS SECTION ************************
        // **********************************************************************
        // If action so dictates, drop DB objects
        if(in_array('drop', $action_arr)){

            // Drop SYS_USERS table
            $sql_drop_table = "DROP TABLE customers";
            if($result1 = mysqli_query($conn, $sql_drop_table)){
                echo "<li>customers table was dropped;";
            }else{
                echo "<li>ERROR :: customers table was NOT dropped;";
            }
        }

        // **********************************************************************
        // ******************** CREATE DB OBJECTS SECTION ***********************
        // **********************************************************************
        if (in_array('create', $action_arr)){ // Create DB objects

            // Create customers table *******************************************
            $sql_create_table = "CREATE TABLE customers (
                cst_id int NOT NULL AUTO_INCREMENT,
                cst_creation_date date NOT NULL,
                cst_name varchar(50) NOT NULL,
                cst_cat varchar(50) NOT NULL,
                cst_nif varchar(50) NOT NULL,
                cst_cae varchar(50) NOT NULL,
                cst_comercial_cert_code varchar(50) NOT NULL,
                cst_street varchar(256) NULL,
                cst_city varchar(50) NULL,
                cst_postal_code varchar(50) NULL,
                cst_country varchar(50) NULL,
                cst_cnct_gen_email varchar(256) NULL,
                cst_cnct_gen_phone varchar(50) NULL,
                cst_cnct_logi_name varchar(50) NULL,
                cst_cnct_logi_phone varchar(50) NULL,
                cst_cnct_logi_email varchar(256) NULL,
                cst_cnct_fin_name varchar(50) NULL,
                cst_cnct_fin_phone varchar(50) NULL,
                cst_cnct_fin_email varchar(256) NULL,
                cst_status tinyint(1) NOT NULL,
                cst_username varchar(50) NULL,
                cst_password varchar(50) NULL,
                PRIMARY KEY (cst_id)
            )";
            if($result2 = mysqli_query($conn, $sql_create_table)){
                echo "<li>customers table was created;";
            }else{
                echo "<li>ERROR :: customers table was NOT created;";
            }
        }

        // **********************************************************************
        // ********************** INSERT RECORDS SECTION ************************
        // **********************************************************************
        if (in_array('insert', $action_arr)){ // Insert records into tables
            // Insert the first user into the DB
            $sql_insert_first_user = "INSERT INTO customers (usr_first_name, usr_last_name, usr_email, usr_phone, usr_birthdate, usr_username, usr_password, usr_status) VALUES
            ('Luis', 'Melo', NULL, NULL, NULL, 'ljmelo', 'R415P4rt4&', 1)";

            if($result3 = mysqli_query($conn, $sql_insert_first_user)){
                echo "<li>First user was inserted;";
            }else{
                echo "<li>ERROR :: First user was NOT inserted;";
            }
        }
        // close the database connection
        include '../app_components/db_disconnect.php';
    }

    echo "</ul>";
?>