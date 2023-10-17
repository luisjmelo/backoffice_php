<?php
  
  // ########################################################
  // ####### FUNCTION GET_ALL_PORTS
  // ########################################################
  // FUNCTION to get boats and store them in $_SESSION['all_boats']
  // REQUIRES: Nothing
  function FUNC_get_all_ports(){

    // Create the query string
    // Get all ports and the count of respective boats
    $sql_statement = "SELECT DISTINCT p.port_id, p.port_name, COUNT(pb.boat_id) AS port_boat_count 
                      FROM ports p
                      LEFT JOIN port_boats pb ON pb.port_id = p.port_id
                      GROUP BY p.port_id, p.port_name
                      ORDER BY p.port_name";

    // Create a database connection and execute the query
    include '../app_components/db_connect.php';
    $result = mysqli_query($conn, $sql_statement);

    /* If search returned records*/
    if (mysqli_num_rows($result) > 0){
      // Fetch the results and store them in an associative array      
      $_SESSION['all_ports'] = array();
      while ($row = $result->fetch_assoc()) {
          $_SESSION['all_ports'][] = $row;
      }
    }

    // close the database connection
    include '../app_components/db_disconnect.php';
  }

  // ########################################################
  // ####### FUNCTION GET_ALL_SPECIES
  // ########################################################
  // FUNCTION to get fish species and store them in $_SESSION['all_species']
  // REQUIRES: Nothing
  function FUNC_get_all_species(){
    // Create the query string
    // Get all species and the count of respective permissions
    $sql_statement = "SELECT fsp_id, fsp_name, fsp_scientific_name
                      FROM fish_species
                      ORDER BY fsp_name";

    // Create a database connection and execute the query
    include '../app_components/db_connect.php';
    $result = mysqli_query($conn, $sql_statement);

    /* If search returned records*/
    if (mysqli_num_rows($result) > 0){
      // Fetch the results and store them in an associative array      
      $_SESSION['all_species'] = array();
      while ($row = $result->fetch_assoc()) {
          $_SESSION['all_species'][] = $row;
      }
    }

    // close the database connection
    include '../app_components/db_disconnect.php';
  }

  // ########################################################
  // ####### FUNCTION GET_ALL_BUYERS
  // ########################################################
  // FUNCTION to get buyers and store them in $_SESSION['all_buyers']
  // REQUIRES: Nothing
  function FUNC_get_all_buyers(){
    // Create the query string
    // Get all species and the count of respective permissions
    // Get all buyers
    $sql_statement = "SELECT byr_id, byr_name 
                      FROM buyers
                      ORDER BY byr_name";

    // Create a database connection and execute the query
    include '../app_components/db_connect.php';
    $result = mysqli_query($conn, $sql_statement);

    /* If search returned records*/
    if (mysqli_num_rows($result) > 0){
      // Fetch the results and store them in an associative array      
      $_SESSION['all_buyers'] = array();
      while ($row = $result->fetch_assoc()) {
        $_SESSION['all_buyers'][] = $row;
      }
    }

    // close the database connection
    include '../app_components/db_disconnect.php';
  }

  // ########################################################
  // ####### FUNCTION GET_ALL_BOATS
  // ########################################################
  // FUNCTION to get boats and store them in $_SESSION['all_boats']
  // REQUIRES: Nothing
  function FUNC_get_all_boats(){
    // create a database connection
    include '../app_components/db_connect.php';

    // Get BOATS
    $sql_get_all_boats = "SELECT boat_id, boat_name 
                          FROM boats 
                          ORDER BY boat_name"; 
    $result_get_all_boats = mysqli_query($conn, $sql_get_all_boats);

    // if boats were returned
    if (mysqli_num_rows($result_get_all_boats) > 0) {
      // Fetch the results and store them in an associative array      
      $_SESSION['all_boats'] = array();
      while ($row = $result_get_all_boats->fetch_assoc()) {
          $_SESSION['all_boats'][] = $row;
      }
    }

    // close the database connection
    include '../app_components/db_disconnect.php';
  }

  // ########################################################
  // ####### FUNCTION GET_PORT_BOATS
  // ########################################################
  // FUNCTION to get port boats and store them in $_SESSION['port_boats']
  // Stores port and boat ID pairs.
  // REQUIRES: Nothing
  function FUNC_get_port_boats(){
    // create a database connection
    include '../app_components/db_connect.php';

    // Get PORT BOATS
    $sql_get_port_boats = "SELECT port_id, boat_id
                          FROM port_boats
                          ORDER BY port_id";
    $result_get_port_boats = mysqli_query($conn, $sql_get_port_boats);

    // if port boats were returned
    if (mysqli_num_rows($result_get_port_boats) > 0) {
      // Fetch the results and store them in an associative array      
      $_SESSION['port_boats'] = array();
      while ($row = $result_get_port_boats->fetch_assoc()) {
          $_SESSION['port_boats'][] = $row;
      }
    }

    // close the database connection
    include '../app_components/db_disconnect.php';
  }

  // ########################################################
  // ####### FUNCTION GET_BOATS_FOR_PORT
  // ########################################################
  // FUNCTION to get boats for a specific port and store them in $_SESSION['boats_for_port']
  // Stores port_id, boat_id and boat_name sets.
  // REQUIRES: port_id
  function FUNC_get_boats_for_port($port_id){
    // create a database connection
    include '../app_components/db_connect.php';

    // Get BOATS FOR PORT
    $sql_get_boats_for_port = "SELECT p.port_id, p.boat_id, b.boat_name
                          FROM port_boats p INNER JOIN boats b ON b.boat_id=p.boat_id
                          WHERE p.port_id=$port_id
                          ORDER BY b.boat_name";
    // Exec query
    $result_get_boats_for_port = mysqli_query($conn, $sql_get_boats_for_port);

    // if port boats were returned
    if (mysqli_num_rows($result_get_boats_for_port) > 0) {
      // Fetch the results and store them in an associative array      
      $_SESSION['boats_for_port'] = array();
      while ($row = $result_get_boats_for_port->fetch_assoc()) {
          $_SESSION['boats_for_port'][] = $row;
      }
    }

    // close the database connection
    include '../app_components/db_disconnect.php';
  }

  // ########################################################
  // ####### FUNCTION INSERT_UPDATE_PORT_BOATS
  // ########################################################
  // FUNCTION to insert and update port_boats
  // REQUIRES: port_id; curr_port_boats
  function FUNC_insert_update_port_boats($port_id, $curr_port_boats){
    // create a database connection
    include '../app_components/db_connect.php';

    // If some boats were selected
    if (!empty($curr_port_boats)){
      $port_boats = $curr_port_boats;
      // Create a comma delimited list of port_boats
      $port_boats_id_List = implode(',', $port_boats);
      // Delete all port_boats that are not in the $port_boats_id_List
      $sql_del_port_boats = "DELETE FROM port_boats WHERE port_id = $port_id AND boat_id NOT IN ($port_boats_id_List)"; 
      $del_result = mysqli_query($conn, $sql_del_port_boats);
      
      // Get list of existing port_boats
      $sql_get_port_boats = mysqli_query($conn, "SELECT boat_id FROM port_boats WHERE port_id = $port_id");
      $existing_port_boats_Ids = [];
      while ($row = mysqli_fetch_assoc($sql_get_port_boats)) {
          $existing_port_boats_Ids[] = $row['boat_id'];
      }

      // If user already has some boats
      if (!empty($existing_port_boats_Ids)) {
        // Create an array with boats that are not already in the database
        $new_boat_ids = array_diff($port_boats, $existing_port_boats_Ids);
      } else {
        // All boats are to be inserted
        $new_boat_ids = $port_boats;
      }

      // Insert the new boats into the database table
      if (!empty($new_boat_ids)) {
        foreach ($new_boat_ids as $curr_boat_id) {
          // Example: Insert the individual ID into the database
          $insert_port_boats_sql = "INSERT INTO port_boats (port_id, boat_id) VALUES ($port_id, $curr_boat_id)";
          mysqli_query($conn, $insert_port_boats_sql);
        }
      }
    // If no boats were selected
    } else {
      // Delete all port_boats
      $sql_del_all_port_boats = "DELETE FROM port_boats WHERE port_id = $port_id"; 
      $del_result = mysqli_query($conn, $sql_del_all_port_boats);
    }
    
    // close the database connection
    include '../app_components/db_disconnect.php';
  }

  // ########################################################
  // ####### FUNCTION INSERT_PORT_DAY_SPECIE_REPORT
  // ########################################################
  // FUNCTION to insert a port/day/species report
  // REQUIRES: drp_id: the ID of the port/day report
  // RETURNS: true or false depending on if the record was inserted
  function FUNC_insert_port_day_specie_report($drp_id){
    // create a database connection
    include '../app_components/db_connect.php';
          
    // Create local variables with the remaining form fields data
    $fsp_id = $_POST["fsp_id"];
    $drs_quantity = $_POST["drs_quantity"];
    // Create a comma delimited list of boats
    $boat_id_list = '';
    if (isset($_POST['boats'])){
      $boat_id_list = implode(',', $_POST['boats']);
    }
    $drs_cgl_bought = $_POST["drs_cgl_bought"]; // Required
    $drs_cgl_quantity = $_POST["drs_cgl_quantity"]; // if no value was passed, set to NULL
    if ($drs_cgl_quantity==''){
      $drs_cgl_quantity = 'NULL';
    }
    $drs_cgl_avg_price_kg = 'NULL'; // initialize as NULL
    if ($_POST["drs_cgl_avg_price_kg"] <> ''){
      $drs_cgl_avg_price_kg = str_replace(',', '.', $_POST["drs_cgl_avg_price_kg"]); // If a value was passed, replace decimal separator comma with period
    }
    $drs_otr_bought = $_POST["drs_otr_bought"]; // Required
    // Create a comma delimited list of buyers
    $byr_id_list = '';
    if (isset($_POST['drs_byr_ids'])){
      $byr_id_list = implode(',', $_POST['drs_byr_ids']);
    }
    $drs_byr_quantity = $_POST["drs_byr_quantity"];
    if ($drs_byr_quantity==''){ // if no value was passed, set to NULL
      $drs_byr_quantity = 'NULL';
    }
    $drs_byr_avg_price_kg = 'NULL'; // initialize as NULL
    if ($_POST["drs_byr_avg_price_kg"] <> ''){
      $drs_byr_avg_price_kg = str_replace(',', '.', $_POST["drs_byr_avg_price_kg"]); // If a value was passed, replace decimal separator comma with period
    }
    $drs_notes = $_POST["drs_notes"];

    // create insert query
    $sql_2 = "INSERT INTO dayrep_species (drp_id, fsp_id, drs_quantity, drs_boat_ids, drs_cgl_bought, drs_cgl_quantity, drs_cgl_avg_price_kg, drs_otr_bought, drs_byr_ids, drs_byr_quantity, drs_byr_avg_price_kg, drs_notes) 
              VALUES ($drp_id, $fsp_id, $drs_quantity, '$boat_id_list', '$drs_cgl_bought', $drs_cgl_quantity, $drs_cgl_avg_price_kg, '$drs_otr_bought', '$byr_id_list', $drs_byr_quantity, $drs_byr_avg_price_kg, '$drs_notes')";

    // Attempt to insert SPECIES report data into database
    if (mysqli_query($conn, $sql_2)) {
      // If it succeeds, return TRUE
      return true;
    } else {
      // If it fails, return FALSE
      return false;
    }
    
    // close the database connection
    include '../app_components/db_disconnect.php';
  }

  // ########################################################
  // ####### FUNCTION UPDATE_PORT_DAY_SPECIE_REPORT
  // ########################################################
  // FUNCTION to update a port/day/species report
  // REQUIRES: drp_id: the ID of the port/day report
  // RETURNS: true or false depending on if the record was inserted
  function FUNC_update_port_day_specie_report(){
    // create a database connection
    include '../app_components/db_connect.php';

    // Create local variables with the remaining form fields data
    $drs_id = $_POST["drs_id"];
    $drs_quantity = $_POST["drs_quantity"];
    // Create a comma delimited list of boats
    $boat_id_list = '';
    if (isset($_POST['boats'])){
      $boat_id_list = implode(',', $_POST['boats']);
    }

    $drs_cgl_quantity = 'NULL'; // initialize as NULL
    $drs_cgl_avg_price_kg = 'NULL'; // initialize as NULL
    $drs_cgl_bought = $_POST["drs_cgl_bought"]; // Required
    // If Congelagos bought, set purchase quantity and average price
    if ($drs_cgl_bought == 'Sim'){
      $drs_cgl_quantity = $_POST["drs_cgl_quantity"];
      $drs_cgl_avg_price_kg = str_replace(',', '.', $_POST["drs_cgl_avg_price_kg"]); // If a value was passed, replace decimal separator comma with period
    }
    
    $byr_id_list = '';
    $drs_byr_quantity = 'NULL';
    $drs_byr_avg_price_kg = 'NULL'; // initialize as NULL
    $drs_otr_bought = $_POST["drs_otr_bought"]; // Required
    // If Other bought, set list_of_buyers, purchase quantity and average price
    if ($drs_otr_bought == 'Sim'){
      if (isset($_POST['drs_byr_ids'])){
        $byr_id_list = implode(',', $_POST['drs_byr_ids']);
      }
      $drs_byr_quantity = $_POST["drs_byr_quantity"];
      $drs_byr_avg_price_kg = str_replace(',', '.', $_POST["drs_byr_avg_price_kg"]); // If a value was passed, replace decimal separator comma with period
    }

    $drs_notes = $_POST["drs_notes"];

    // create insert query
    $sql_2 = "UPDATE dayrep_species
              SET drs_quantity=$drs_quantity, drs_boat_ids='$boat_id_list', drs_cgl_bought='$drs_cgl_bought', 
              drs_cgl_quantity=$drs_cgl_quantity, drs_cgl_avg_price_kg=$drs_cgl_avg_price_kg, drs_otr_bought='$drs_otr_bought', 
              drs_byr_ids='$byr_id_list', drs_byr_quantity=$drs_byr_quantity, drs_byr_avg_price_kg=$drs_byr_avg_price_kg, drs_notes='$drs_notes'
              WHERE drs_id=$drs_id";
    
    // echo($sql_2);
    // exit;

    /* Attempt to insert SPECIES report data into database */
    if (mysqli_query($conn, $sql_2)) {
      return true; // If it succeeds, return TRUE
    } else {
      return false; // If it fails, return FALSE
    }
    
    // close the database connection
    // include '../app_components/db_disconnect.php';
  }

  // ########################################################
  // ####### FUNCTION UPDATE_PORT_DAY_REPORT
  // ########################################################
  // FUNCTION to insert a port/day report
  // REQUIRES: drp_id: the ID of the port/day report to be updated
  //           drp_capture: Te capture status for the port/day (Sim" ou "Não - por vários motivos")
  function FUNC_update_port_day_report($drp_id, $drp_capture){
    
    // create a database connection
    include "../app_components/db_connect.php";

    // Update the port/day record
    $sql_1 = "UPDATE dayrep_port 
              SET drp_capture='$drp_capture'
              WHERE drp_id=$drp_id";

    // Execute the query
    $result = mysqli_query($conn, $sql_1);
    
    // close the database connection
    include "../app_components/db_disconnect.php";
  }

?>