<?php
    // There are 3 possible actions: 
    //      'drop' (drops DB objects), 
    //      'create' (creates DB objects), 
    //      'insert' (inserts specified records into tables)
    // URL passed ($_GET) action variable can contain a hyphen separeted list of commands (eg: action=drop-create-insert)
    // Only the sections specified in the action will be executed in the code below

    echo "<h1>DB setup for \"Intranet\"</h1>
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
            $sql_drop_table = "DROP TABLE sys_users";
            if($result1 = mysqli_query($conn, $sql_drop_table)){
                echo "<li>sys_users table was dropped;";
            }else{
                echo "<li>ERROR :: sys_users table was NOT dropped;";
            }
            
            // Drop MODULES table
            $sql_drop_table = "DROP TABLE modules";
            if($result1 = mysqli_query($conn, $sql_drop_table)){
                echo "<li>MODULES table was dropped;";
            }else{
                echo "<li>ERROR :: MODULES table was NOT dropped;";
            }

            // Drop MODULE_PERMISSIONS table
            $sql_drop_table = "DROP TABLE module_permissions";
            if($result1 = mysqli_query($conn, $sql_drop_table)){
                echo "<li>MODULE_PERMISSIONS table was dropped;";
            }else{
                echo "<li>ERROR :: MODULE_PERMISSIONS table was NOT dropped;";
            }

            // sys_users_permissions WHERE usr_id = $usr_id AND prm_id
            // Drop MODULE_PERMISSIONS table
            $sql_drop_table = "DROP TABLE sys_users_permissions";
            if($result1 = mysqli_query($conn, $sql_drop_table)){
                echo "<li>SYS_USER_PERMISSIONS table was dropped;";
            }else{
                echo "<li>ERROR :: SYS_USER_PERMISSIONS table was NOT dropped;";
            }
        }

        // **********************************************************************
        // ******************** CREATE DB OBJECTS SECTION ***********************
        // **********************************************************************
        if (in_array('create', $action_arr)){ // Create DB objects

            // Create sys_users table *******************************************
            $sql_create_table = "CREATE TABLE sys_users (
                usr_id int NOT NULL AUTO_INCREMENT,
                usr_first_name varchar(50) NOT NULL,
                usr_last_name varchar(50) NOT NULL,
                usr_email varchar(256) DEFAULT NULL,
                usr_phone varchar(50) DEFAULT NULL,
                usr_birthdate date DEFAULT NULL,
                usr_username varchar(50) NOT NULL,
                usr_password varchar(50) NOT NULL,
                usr_status tinyint(1) NOT NULL,
                PRIMARY KEY (usr_id)
            )";
            if($result2 = mysqli_query($conn, $sql_create_table)){
                echo "<li>sys_users table was created;";
            }else{
                echo "<li>ERROR :: sys_users table was NOT created;";
            }

            // Create MODULES table ****************************************
            $sql_create_table = "CREATE TABLE modules (
                mdl_id int NOT NULL AUTO_INCREMENT,
                mdl_number varchar(20) NOT NULL,
                mdl_name varchar(100) NOT NULL,
                PRIMARY KEY (mdl_id)
            )";
            if($result2 = mysqli_query($conn, $sql_create_table)){
                echo "<li>MODULES table was created;";
            }else{
                echo "<li>ERROR :: MODULES table was NOT created;";
            }

            // Create MODULE_PERMISSIONS table *********************************
            $sql_create_table = "CREATE TABLE module_permissions (
                prm_id int NOT NULL AUTO_INCREMENT,
                mdl_id int NOT NULL,
                prm_name varchar(100) NOT NULL,
                PRIMARY KEY (prm_id),
                CONSTRAINT fk_permissions_2_modules_id
                FOREIGN KEY (mdl_id) REFERENCES modules(mdl_id)
            )";
            if($result2 = mysqli_query($conn, $sql_create_table)){
                echo "<li>MODULE_PERMISSIONS table was created;";
            }else{
                echo "<li>ERROR :: MODULE_PERMISSIONS table was NOT created;";
            }

            // Create SYS_USERS_PERMISSIONS table *********************************
            $sql_create_table = "CREATE TABLE sys_users_permissions (
                sup_id int NOT NULL AUTO_INCREMENT,
                usr_id int NOT NULL,
                prm_id int NOT NULL,
                PRIMARY KEY (sup_id),
                CONSTRAINT fk_user_permissions_2_user_id
                FOREIGN KEY (usr_id) REFERENCES sys_users(usr_id),
                CONSTRAINT fk_user_permissions_2_permission_id
                FOREIGN KEY (prm_id) REFERENCES module_permissions(prm_id)
            )";
            if($result2 = mysqli_query($conn, $sql_create_table)){
                echo "<li>SYS_USERS_PERMISSIONS table was created;";
            }else{
                echo "<li>ERROR :: SYS_USERS_PERMISSIONS table was NOT created;";
            }
        }

        // **********************************************************************
        // ********************** INSERT RECORDS SECTION ************************
        // **********************************************************************
        if (in_array('insert', $action_arr)){ // Insert records into tables
            // Insert the first user into the DB
            $sql_insert_first_user = "INSERT INTO sys_users (usr_first_name, usr_last_name, usr_email, usr_phone, usr_birthdate, usr_username, usr_password, usr_status) VALUES
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