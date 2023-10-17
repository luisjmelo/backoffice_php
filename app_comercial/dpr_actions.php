<?php
  session_start();
  // Get the action variable passed through the URL or a form field
  if (isset($_POST['action'])){
    $action = $_POST['action'];  
  } elseif (isset($_GET['action'])){
    $action = $_GET['action'];  
  }

  // Create a random number between 1 and 10000 that,
  // appended to URLs, prevents browser caching issues
  $rdm_uuid=rand(1, 10000);

  // include file that contains Commercial App functions
  include 'comm_app_functions.php';

  // ### execute the code based on the value of $action ###
  switch ($action){
  
    // ########################################################
    // ####### If SAVING
    // ########################################################
    case 'save':
      // create a database connection
      include '../app_components/db_connect.php';

      // Create local variables from submitted form fields
      $drp_date = $_POST["drp_date"];
      $port_id = $_POST["port_id"];
      $drp_capture = $_POST["drp_capture"];
      
      // Check if there is already another record for the same port/date 
      $sql_0 = "SELECT drp_id, drp_capture
                FROM dayrep_port
                WHERE drp_date='$drp_date' AND port_id=$port_id";
      
      // Execute the query
      $result = mysqli_query($conn, $sql_0);

      /* If there is already a record for that port/day */
      if (mysqli_num_rows($result) == 1) {
        // Fetch the results and store them in a local variable
        foreach ($result as $row) {   
          $existing_drp_id = $row["drp_id"];
          $existing_drp_capture = $row["drp_capture"];
        }
 
        // If the existing record drp_capture is different than the passed drp_capute
        if ($existing_drp_capture <> $drp_capture){
          // 1. Update the port/day record
          FUNC_update_port_day_report($existing_drp_id, $drp_capture); // function is in the "APP_COMERCIAL/COMM_APP_FUNCTIONS.PHP" file
          // Set the message and set target location to dashboard page
          $message = "A informação da captura para um porto/dia foi guardada.";
          $message_type = "msg_success"; // there are 3 types of messages: msg_success, msg_warning and msg_error
          $message_icon = "<i class='fa-regular fa-face-smile-beam'></i>";
          $target_location = "../intranet/dashboard.php?UUID=$rdm_uuid";
        }

        // if the existing record is "Sim" and the new record in one of the "Nãos" 
        if ($existing_drp_capture == "Sim" && $drp_capture <> "Sim") {
          
          // Delete all the SPECIES capture records for that port/day
          // ATTENTION: User needs to be notified by AJAX that this will happen!!!!!!
          $sql_2 = "DELETE FROM dayrep_species WHERE drp_id=$existing_drp_id";
            // Attempt to delete report port data from database
            mysqli_query($conn, $sql_2);

        // If the new record is "Sim"
        } else if ($drp_capture == "Sim") {
          // Insert the new species capture record for that port/day
          if (FUNC_insert_port_day_specie_report($existing_drp_id) == true){ // function is in the "APP_COMERCIAL/COMM_APP_FUNCTIONS.PHP" file
            // Set the message and set target location to dashboard page
            $message = "A informação da captura de uma espécie para um porto e dia foi guardada.";
            $message_type = "msg_success"; // there are 3 types of messages: msg_success, msg_warning and msg_error
            $message_icon = "<i class='fa-regular fa-face-smile-beam'></i>";
            $target_location = "../intranet/dashboard.php";
          } else {
            // Create error message and set target location to dashboard page  
            $message = "Ocorreu um erro. Registo não foi guardado. Pode tentar novamente. (Delete existing";
            $message_type = "msg_error"; // there are 3 types of messages: msg_success, msg_warning and msg_error
            $message_icon = "<i class='fa-solid fa-circle-exclamation'></i>";
            $target_location = "../intranet/dashboard.php";
          }
        }
              
      /* If there are no records for that port/day */
      } else {

        // create insert query for the port/day
        $sql_1 = "INSERT INTO dayrep_port (drp_date, port_id, drp_capture) 
                  VALUES ('$drp_date', $port_id, '$drp_capture')";
        
        //Attempt to insert report PORT data into database
        if (mysqli_query($conn, $sql_1)) {
          
          $drp_id = mysqli_insert_id($conn); // Get the ID of the inserted record

          // If there was a capture for that port/day
          if($drp_capture == 'Sim'){
            // Insert the species and purchase data into the DB
            FUNC_insert_port_day_specie_report($drp_id); // function is in the "COMM_APP_FUNCTIONS.PHP" file
          }
          
          // Set the message and set target location to dashboard page
          $message = "A informação da captura de uma espécie para um porto para um dia foi guardada.";
          $message_type = "msg_success"; // there are 3 types of messages: msg_success, msg_warning and msg_error
          $message_icon = "<i class='fa-regular fa-face-smile-beam'></i>";
          $target_location = "../intranet/dashboard.php?UUID=$rdm_uuid";
        // If there was an error inserting the record
        } else {
          // Create error message and set target location to dashboard page  
          $message = "Ocorreu um erro. Pode tentar novamente.";
          $message_type = "msg_error"; // there are 3 types of messages: msg_success, msg_warning and msg_error
          $message_icon = "<i class='fa-solid fa-circle-exclamation'></i>";
          $target_location = "../intranet/dashboard.php?UUID=$rdm_uuid";
        }
      }

      // close the database connection
      include '../app_components/db_disconnect.php';
      break;

    // ########################################################
    // ####### if UPDATING
    // ########################################################
    case 'update_drs':

      // Local variables needsd for processing
      $drp_id = $_POST["drp_id"];
      $drs_id = $_POST["drs_id"];

      // create a database connection
      include '../app_components/db_connect.php';

      // Create local variables with the remaining form fields data
      if (FUNC_update_port_day_specie_report()){ // function is in the "COMM_APP_FUNCTIONS.PHP" file
        // Set the message and set target location to dashboard page
        $message = "A informação foi atualizada.";
        $message_type = "msg_success"; // there are 3 types of messages: msg_success, msg_warning and msg_error
        $message_icon = "<i class='fa-regular fa-face-smile-beam'></i>";
        $target_location = "dpr_actions.php?action=detail&drp_id=$drp_id";
      // If there was an error inserting the record
      } else {
        // Create error message and set target location to dashboard page  
        $message = "Ocorreu um erro. Pode tentar novamente.";
        $message_type = "msg_error"; // there are 3 types of messages: msg_success, msg_warning and msg_error
        $message_icon = "<i class='fa-solid fa-circle-exclamation'></i>";
        $target_location = "dpr_actions.php?action=detail&drp_id=$drp_id";
      }

      // close the database connection
      include '../app_components/db_disconnect.php';
      break;

    // ########################################################
    // ####### if DELETING A SPECIES RECORD
    // ########################################################
    case 'delete_drs':

      // create a database connection
      include '../app_components/db_connect.php';

      // Get the data from the dayrep_port table
      $sql_del_drs = "DELETE FROM dayrep_species
                      WHERE drs_id=" . $_POST["drs_id"];
      mysqli_query($conn, $sql_del_drs);

      // attempt to delete the dayrep_species record
      if (mysqli_query($conn, $sql_del_drs)){ 
        // Get all species reports for the specified port/date
        $sql_drp = "SELECT drs.drs_id
                    FROM dayrep_species drs
                    WHERE drs.drp_id=".$_POST["drp_id"];
        $result = mysqli_query($conn, $sql_drp);

        /* If there are no more records for that port/day */
        if (mysqli_num_rows($result) == 0) {
          // Update the port/date purchase record to "Não - Outros motivos"
          $sql_2 = "UPDATE dayrep_port
                    SET drp_capture='Não - Outros motivos'
                    WHERE drp_id=".$_POST["drp_id"];
        }
        $curr_sp_rep = mysqli_query($conn, $sql_2);
        // Set the message and set target location to dashboard page
        $message = "A informação foi apagada.";
        $message_type = "msg_success"; // there are 3 types of messages: msg_success, msg_warning and msg_error
        $message_icon = "<i class='fa-regular fa-face-smile-beam'></i>";
        $target_location = "dpr_actions.php?action=detail&drp_id=".$_POST["drp_id"];
      // If there was an error deleting the record
      } else {
        // Create error message and set target location to dashboard page  
        $message = "Ocorreu um erro. Pode tentar novamente.";
        $message_type = "msg_error"; // there are 3 types of messages: msg_success, msg_warning and msg_error
        $message_icon = "<i class='fa-solid fa-circle-exclamation'></i>";
        $target_location = "dpr_actions.php?action=detail&drp_id=".$_POST["drp_id"];
      }

      // close the database connection
      include '../app_components/db_disconnect.php';
      break;
    
    // ########################################################
    // ####### if DETAIL
    // ########################################################
    case 'detail':

      // create a database connection
      include '../app_components/db_connect.php';

      // Get the data from the dayrep_port table
      $sql_drp = "SELECT drp.*, p.port_name
                  FROM dayrep_port drp
                  INNER JOIN ports p ON p.port_id=drp.port_id
                  WHERE drp.drp_id=" . $_GET["drp_id"];
      $curr_rep = mysqli_query($conn, $sql_drp);
      
      // Populate the curr_rep SESSION array with the dayrep_port data
      while ($row = mysqli_fetch_assoc($curr_rep)) {
        $_SESSION['curr_rep'][] = $row;
      }

      // Get the values of the dayrep_port record
      foreach ($_SESSION['curr_rep'] as $rep) {
        $drp_date = $rep["drp_date"];
        $port_id = $rep["port_id"];
        $drp_capture = $rep["drp_capture"];
      }

      // If fish was captured for that port/date
      if($drp_capture == 'Sim'){
        // Get all species reports for the specified port/date
        $sql_drs = "SELECT drs.*, s.fsp_name
                    FROM dayrep_species drs
                    INNER JOIN fish_species s ON s.fsp_id=drs.fsp_id
                    WHERE drs.drp_id=".$_GET["drp_id"]." ORDER BY s.fsp_name";
        $curr_sp_rep = mysqli_query($conn, $sql_drs);

        // Populate the curr_sp_rep SESSION array with the dayrep_species data
        while ($row = mysqli_fetch_assoc($curr_sp_rep)) {
          $_SESSION['curr_sp_rep'][] = $row;
        }

        // Get all Boats for the port on this record and store them in the $_SESSION.boats_for_port array
        FUNC_get_boats_for_port($port_id);
        // Get all Species and store them in the $_SESSION.all_species array
        FUNC_get_all_species();
        // Get all Buyers and store them in the $_SESSION.all_buyers array
        FUNC_get_all_buyers();
      }

      $target_location = "dpr_detail.php?UUID=$rdm_uuid";

      // close the database connection
      include '../app_components/db_disconnect.php';
      break;

    // ########################################################
    // ####### If MANAGING
    // ########################################################
    case 'manage':
      
      // Create a database connection and execute the query
      include '../app_components/db_connect.php';
      $sql_statement = "SELECT drp.drp_id, drp.drp_date, drp_capture, p.port_name, 
                          SUM(drs.drs_quantity) as quantity, 
                          COUNT(drs.drs_id) as records
                        FROM dayrep_port drp
                        INNER JOIN ports p ON p.port_id = drp.port_id
                        LEFT OUTER JOIN dayrep_species drs ON drs.drp_id=drp.drp_id
                        GROUP BY drp.drp_id, drp.drp_date, drp_capture, p.port_name
                        ORDER BY drp_id DESC LIMIT 10";
      $latest_reports = mysqli_query($conn, $sql_statement);

      // if reports were returned
      if (mysqli_num_rows($latest_reports) > 0) {
        // Fetch the results and store them in an associative array      
        $_SESSION['latest_day_purchase_reps'] = array();
        while ($row = $latest_reports->fetch_assoc()) {
            $_SESSION['latest_day_purchase_reps'][] = $row;
        }
      }

      // Get all Ports and store them in the $_SESSION.all_ports array
      FUNC_get_all_ports();
      // Get all Species and store them in the $_SESSION.all_species array
      FUNC_get_all_species();

      $target_location = "dpr_srch.php?UUID=$rdm_uuid";

      // Close the database connection
      include '../app_components/db_disconnect.php';
      break;
    
    // ########################################################
    // ####### If ADDING
    // ########################################################
    case 'add':
      
      // Get all Ports and store them in the $_SESSION.all_ports array
      FUNC_get_all_ports();
      // Get all Species and store them in the $_SESSION.all_species array
      FUNC_get_all_species();
      // Get all BOATS and store them in the $_SESSION.all_boats array
      FUNC_get_all_boats();
      // Get all Buyers and store them in the $_SESSION.all_buyers array
      FUNC_get_all_buyers();

      $target_location = "dpr_add.php?UUID=$rdm_uuid";

      break;

    // ########################################################
    // ####### If SEARCHING
    // ########################################################
    case 'search':
      
      // Create a database connection and execute the query
      include '../app_components/db_connect.php';

      $where_clause = "";
      // searching by date
      if(isset($_POST["date_crit"]) && $_POST["date_crit"] <> "---" && $_POST["drp_date"] <> ""){
        $drp_date = $_POST["drp_date"];
        $where_clause .= " AND drp.drp_date " . $_POST['date_crit'] . " '" . $drp_date . "'";
      }
      // searching by port
      if(isset($_POST["port_id"]) && $_POST["port_id"] <> "---"){
        $where_clause .= " AND p.port_id = " . $_POST['port_id'];
      }
      // searching by capture
      if(isset($_POST["drp_capture"]) && $_POST["drp_capture"] <> "---"){
        $where_clause .= " AND drp_capture = '" . $_POST['drp_capture'] . "'";
      }
      // searching by fish species
      if(isset($_POST["fsp_id"]) && $_POST["fsp_id"] <> "---"){
        $where_clause .= " AND drs.fsp_id = " . $_POST['fsp_id'];
      }
      // searching by Congelagos bought
      if(isset($_POST["drs_cgl_bought"]) && $_POST["drs_cgl_bought"] <> "---"){
        $where_clause .= " AND drs.drs_cgl_bought = '" . $_POST['drs_cgl_bought'] . "'";
      }

      $sql_statement = "SELECT drp.drp_id, drp.drp_date, drp_capture, p.port_name, 
                          SUM(drs.drs_quantity) as quantity, 
                          COUNT(drs.drs_id) as records
                        FROM dayrep_port drp
                        INNER JOIN ports p ON p.port_id = drp.port_id
                        LEFT OUTER JOIN dayrep_species drs ON drs.drp_id=drp.drp_id
                        WHERE 0=0 $where_clause
                        GROUP BY drp.drp_id, drp.drp_date, drp_capture, p.port_name
                        ORDER BY drp_id DESC";
      // echo $sql_statement;
      // exit;
      $reps_srch_query = mysqli_query($conn, $sql_statement);

      // If search returned reports
      if (mysqli_num_rows($reps_srch_query) > 0) {
        // Fetch the results and store them in an associative array      
        $_SESSION['reps_srch_results'] = array();
        while ($row = $reps_srch_query->fetch_assoc()) {
            $_SESSION['reps_srch_results'][] = $row;
        }
      // If no records were returned
      } else {
        // Create error message and set target location  
        $message = "Nenhum registo encontrado. Pode alterar os critérios de pesquisa.";
        $message_type = "msg_warning"; // there are 3 types of messages: msg_success, msg_warning and msg_error
        $message_icon = "<i class='fa-regular fa-bell'></i>";
        $target_location = "dpr_srch.php";
      }
      
      // Get all Ports and store them in the $_SESSION.all_ports array
      FUNC_get_all_ports();
      // Get all Species and store them in the $_SESSION.all_species array
      FUNC_get_all_species();

      $target_location = "dpr_srch.php?UUID=$rdm_uuid";

      break;

  }
  
  // Create the Session variables and send user to the target page
  if(isset($message)){
    $_SESSION['message'] = $message;
    $_SESSION['message_type'] = $message_type;
    $_SESSION['message_icon'] = $message_icon;
  }
  header("Location: $target_location");
  exit();
?>