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
      
      $port_name = $_POST["port_name"];
      
      $sql = "INSERT INTO ports (port_name) VALUES ('$port_name')";

      if (mysqli_query($conn, $sql)) {

        // Get the ID of the inserted record
        $new_port_id = mysqli_insert_id($conn);
        $curr_port_boats = $_POST["port_boats"];
        // Execute the function that deletes, inserts and updates the port boats
        FUNC_insert_update_port_boats($new_port_id, $curr_port_boats); // function is in the "APP_COMERCIAL/COMM_APP_FUNCTIONS.PHP" file

        // Create success message and set target location to dashboard page  
        $message = "A Lota foi adicionada.";
        $message_type = "msg_success"; // there are 3 types of messages: msg_success, msg_warning and msg_error
        $message_icon = "<i class='fa-regular fa-face-smile-beam'></i>";
      } else {
        // Create error message and set target location to module mange page  
        $message = "Ocorreu um erro. A Lota não foi adicionada. Pode tentar novamente.";
        $message_type = "msg_error"; // there are 3 types of messages: msg_success, msg_warning and msg_error
        $message_icon = "<i class='fa-solid fa-circle-exclamation'></i>";
      }
      
      $target_location = "commercial_auxiliary_ports_actions.php?action=manage&UUID=$rdm_uuid";

      // close the database connection
      include '../app_components/db_disconnect.php';
      break;

    // ########################################################
    // ####### if UPDATING
    // ########################################################
    case 'update':

      // create a database connection
      include '../app_components/db_connect.php';

      $port_id = $_POST["port_id"];
      $port_name = $_POST["port_name"];
      $curr_port_boats = $_POST["port_boats_$port_id"];

      // Update the module record
      $sql = "UPDATE ports SET port_name='$port_name' WHERE port_id=$port_id";
      $result = mysqli_query($conn, $sql);

      // Execute the function that deletes, inserts and updates the port boats
      FUNC_insert_update_port_boats($port_id, $curr_port_boats); // function is in the "APP_COMERCIAL/COMM_APP_FUNCTIONS.PHP" file

      // if query executed without errors
      if ($result) {
        // Create success message and set target location to the port_detail page
        $message = "A Lota foi atualizada.";
        $message_type = "msg_success"; // there are 3 types of messages: msg_success, msg_warning and msg_error
        $message_icon = "<i class='fa-regular fa-face-smile-beam'></i>";
      } else {
        // Create error message and set target location to the port_detail page 
        $message = "Ocorreu um erro. Pode tentar novamente.";
        $message_type = "msg_error"; // there are 3 types of messages: msg_success, msg_warning and msg_error
        $message_icon = "<i class='fa-solid fa-circle-exclamation'></i>";
      }

      $target_location = "commercial_auxiliary_ports_actions.php?action=manage&UUID=$rdm_uuid";

      // close the database connection
      include '../app_components/db_disconnect.php';
      break;

    // ########################################################
    // ####### if DELETING
    // ########################################################
    case 'delete':

      // create a database connection
      include '../app_components/db_connect.php';

      // Get the action variable passed through the URL or a form field
      if (isset($_POST['port_id'])){
        $port_id = $_POST['port_id'];  
      } elseif (isset($_GET['port_id'])){
        $port_id = $_GET['port_id'];  
      }

      // Delete the module record
      $sql = "DELETE FROM ports 
              WHERE port_id=$port_id";
      $result = mysqli_query($conn, $sql);

      // Create success message and set target location to dashboard page
      $message = "A Lota foi apagada.";
      $message_type = "msg_success"; // there are 3 types of messages: msg_success, msg_warning and msg_error
      $message_icon = "<i class='fa-regular fa-face-smile-beam'></i>";
      $target_location = "commercial_auxiliary_ports_actions.php?action=manage&UUID=$rdm_uuid";

      // close the database connection
      include '../app_components/db_disconnect.php';
      break;

    // ########################################################
    // ####### If MANAGING
    // ########################################################
    case 'manage':
      
      // Get all Ports and store them in the $_SESSION.all_ports array
      FUNC_get_all_ports(); // function is in the "APP_COMERCIAL/COMM_APP_FUNCTIONS.PHP" file
      // Get all boats and store them in the $_SESSION.all_boats array
      FUNC_get_all_boats(); // function is in the "APP_COMERCIAL/COMM_APP_FUNCTIONS.PHP" file
      // Get Port and Boat ID pairs and store them in the $_SESSION.port_boats array
      FUNC_get_port_boats(); // function is in the "APP_COMERCIAL/COMM_APP_FUNCTIONS.PHP" file

      $target_location = "commercial_auxiliary_ports_manage.php?UUID=$rdm_uuid";

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