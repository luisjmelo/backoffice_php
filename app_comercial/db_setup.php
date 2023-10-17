<?php
    // There are 3 possible actions: 
    //      'drop' (drops DB objects), 
    //      'create' (creates DB objects), 
    //      'insert' (inserts specified records into tables)
    // URL passed ($_GET) action variable can contain a hyphen separeted list of commands (eg: action=drop-create-insert)
    // Only the sections specified in the action will be executed in the code below

    echo "<h1>DB setup for \"App comercial\"</h1>
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

            // Drop BOATS table
            $sql_drop_table = "DROP TABLE boats";
            if($result1 = mysqli_query($conn, $sql_drop_table)){
                echo "<li>boats table was dropped;";
            }else{
                echo "<li>ERROR :: boats table was NOT dropped;";
            }

            // Drop PORTS table
            $sql_drop_table = "DROP TABLE ports";
            if($result1 = mysqli_query($conn, $sql_drop_table)){
                echo "<li>ports table was dropped;";
            }else{
                echo "<li>ERROR :: ports table was NOT dropped;";
            }

            $sql_drop_table = "DROP TABLE port_boats";
            if($result1 = mysqli_query($conn, $sql_drop_table)){
                echo "<li>ports_boats table was dropped;";
            }else{
                echo "<li>ERROR :: port_boats table was NOT dropped;";
            }

            // Drop FISH_SPECIES table
            $sql_drop_table = "DROP TABLE fish_species";
            if($result1 = mysqli_query($conn, $sql_drop_table)){
                echo "<li>fish_species table was dropped;";
            }else{
                echo "<li>ERROR :: fish_species table was NOT dropped;";
            }

            // Drop BUYERS table
            $sql_drop_table = "DROP TABLE buyers";
            if($result1 = mysqli_query($conn, $sql_drop_table)){
                echo "<li>buyers table was dropped;";
            }else{
                echo "<li>ERROR :: buyers table was NOT dropped;";
            }

            // Drop FISH_SPECIES table
            $sql_drop_table = "DROP TABLE dayrep_port";
            if($result1 = mysqli_query($conn, $sql_drop_table)){
                echo "<li>dayrep_port table was dropped;";
            }else{
                echo "<li>ERROR :: dayrep_port table was NOT dropped;";
            }

            // Drop BUYERS table
            $sql_drop_table = "DROP TABLE dayrep_species";
            if($result1 = mysqli_query($conn, $sql_drop_table)){
                echo "<li>dayrep_species table was dropped;";
            }else{
                echo "<li>ERROR :: dayrep_species table was NOT dropped;";
            }
        }

        // **********************************************************************
        // ******************** CREATE DB OBJECTS SECTION ***********************
        // **********************************************************************
        if (in_array('create', $action_arr)){ // Create DB objects

            // Create boats table *******************************************
            $sql_create_table = "CREATE TABLE boats (
                boat_id int NOT NULL AUTO_INCREMENT,
                boat_name varchar(100) NOT NULL,
                PRIMARY KEY (boat_id)
            )";
            if($result2 = mysqli_query($conn, $sql_create_table)){
                echo "<li>boats table was created;";
            }else{
                echo "<li>ERROR :: boats table was NOT created;";
            }

            // Create ports table *******************************************
            $sql_create_table = "CREATE TABLE ports (
                port_id int NOT NULL AUTO_INCREMENT,
                port_name varchar(100) NOT NULL,
                PRIMARY KEY (port_id)
            )";
            if($result2 = mysqli_query($conn, $sql_create_table)){
                echo "<li>ports table was created;";
            }else{
                echo "<li>ERROR :: ports table was NOT created;";
            }
            
            // Create port_boats table *******************************************
            $sql_create_table = "CREATE TABLE port_boats (
                pbt_id int NOT NULL AUTO_INCREMENT,
                port_id int NOT NULL,
                boat_id int NOT NULL,
                PRIMARY KEY (pbt_id),
                CONSTRAINT fk_port_boats_2_ports
                    FOREIGN KEY (port_id) REFERENCES ports(port_id),
                CONSTRAINT fk_port_boats_2_boats
                    FOREIGN KEY (boat_id) REFERENCES boats(boat_id)
            )";
            if($result2 = mysqli_query($conn, $sql_create_table)){
                echo "<li>port_boats table was created;";
            }else{
                echo "<li>ERROR :: port_boats table was NOT created;";
            }

            // Create fish_species table *******************************************
            $sql_create_table = "CREATE TABLE fish_species (
                fsp_id int NOT NULL AUTO_INCREMENT,
                fsp_name varchar(100) NOT NULL,
                fsp_scientific_name varchar(100) NOT NULL,
                PRIMARY KEY (fsp_id)
            )";
            if($result2 = mysqli_query($conn, $sql_create_table)){
                echo "<li>fish_species table was created;";
            }else{
                echo "<li>ERROR :: fish_species table was NOT created;";
            }

            // Create buyers table *******************************************
            $sql_create_table = "CREATE TABLE buyers (
                byr_id int NOT NULL AUTO_INCREMENT,
                byr_name varchar(100) NOT NULL,
                PRIMARY KEY (byr_id)
            )";
            if($result2 = mysqli_query($conn, $sql_create_table)){
                echo "<li>buyers table was created;";
            }else{
                echo "<li>ERROR :: buyers table was NOT created;";
            }

            // Create Dayly Port Report table *******************************************
            $sql_create_table = "CREATE TABLE dayrep_port (
                drp_id int NOT NULL AUTO_INCREMENT,
                drp_date date NOT NULL,
                port_id int NULL,
                drp_capture varchar(100) NOT NULL,
                PRIMARY KEY (drp_id),
                CONSTRAINT fk_dayrep_port_2_ports
                    FOREIGN KEY (port_id) REFERENCES ports(port_id)
            )";
            if($result2 = mysqli_query($conn, $sql_create_table)){
                echo "<li>dayrep_port table was created;";
            }else{
                echo "<li>ERROR :: dayrep_port table was NOT created;";
            }

            // Create Dayly Species Report table *******************************************
            $sql_create_table = "CREATE TABLE dayrep_species (
                drs_id int NOT NULL AUTO_INCREMENT,
                drp_id int NOT NULL,
                fsp_id int NOT NULL,
                drs_quantity int NULL,
                drs_boat_ids varchar(100) NULL,
                drs_cgl_bought varchar(100) NULL,
                drs_cgl_quantity int NULL,
                drs_cgl_avg_price_kg decimal(5,2) NULL, 
                drs_otr_bought varchar(100) NULL,
                drs_byr_ids varchar(100) NULL,
                drs_byr_quantity int NULL,
                drs_byr_avg_price_kg decimal(5,2) NULL, 
                drs_notes varchar(256) NULL,
                PRIMARY KEY (drs_id),
                CONSTRAINT fk_dayrep_species_2_dayrep_ports
                    FOREIGN KEY (drp_id) REFERENCES dayrep_port(drp_id),
                CONSTRAINT fk_dayrep_species_2_fish_species
                    FOREIGN KEY (fsp_id) REFERENCES fish_species(fsp_id)
            )";
            if($result2 = mysqli_query($conn, $sql_create_table)){
                echo "<li>dayrep_species table was created;";
            }else{
                echo "<li>ERROR :: dayrep_species table was NOT created;";
            }
        }

        // **********************************************************************
        // ********************** INSERT RECORDS SECTION ************************
        // **********************************************************************
        if (in_array('insert', $action_arr)){ // Insert records into tables
            /* Insert the first user into the DB
            $sql_insert_first_user = "INSERT INTO sys_users (usr_first_name, usr_last_name, usr_email, usr_phone, usr_birthdate, usr_username, usr_password, usr_status) VALUES
            ('Luis', 'Melo', NULL, NULL, NULL, 'ljmelo', 'R415P4rt4&', 1)";

            if($result3 = mysqli_query($conn, $sql_insert_first_user)){
                echo "<li>First user was inserted;";
            }else{
                echo "<li>ERROR :: First user was NOT inserted;";
            } */
        }
        // close the database connection
        include '../app_components/db_disconnect.php';
    }

    echo "</ul>";
?>