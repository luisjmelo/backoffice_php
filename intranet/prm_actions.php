<?php
  session_start();
  // Get the action variable passed through the URL or a form field
  if (isset($_POST['action'])){
    $action = $_POST['action'];  
  } elseif (isset($_GET['action'])){
    $action = $_GET['action'];  
  }
  
  // Create a random number between 1 and 10000 that when
  // appended to URLs, it prevents browser caching issues
  $rdm_uuid=rand(1, 10000);

  // Check to see if this was an AJAX or HTTP call
  $ajax_call = 0;
  if (isset($_GET['ajax_call'])){
    $ajax_call = 1;
  }

  // ### execute the code based on the value of $action ###
  switch ($action){
  
    // ########################################################
    // ####### If ADDING
    // ########################################################
    case 'save':
      // create a database connection
      include '../app_components/db_connect.php';
      
      // Handle section values if they were passed by POST or GET method
      if (isset($_GET['mdl_id'])){
        $mdl_id = $_GET["mdl_id"];
        $prm_name = $_GET["prm_name"];
      }elseif(isset($_POST['mdl_id'])){
        $mdl_id = $_POST["mdl_id"];
        $prm_name = $_POST["prm_name"];
      }
      
      $sql = "INSERT INTO module_permissions (mdl_id, prm_name) VALUES ($mdl_id, '$prm_name')";
      $result = mysqli_query($conn, $sql);

      // if this was an HTTP (NOT AJAX) call, set the message
      if ($ajax_call == 0) {
        if ($result) {
          // Create success message and set target location  
          $message = "A permissão foi adicionada ao módulo.";
          $message_type = "msg_success"; // there are 3 types of messages: msg_success, msg_warning and msg_error
          $message_icon = "<i class='fa-regular fa-face-smile-beam'></i>";
          $target_location = "mdl_actions.php?action=detail&mdl_id=$mdl_id&UUID=$rdm_uuid";
        } else {
          // Create error message and set target location  
          $message = "Ocorreu um erro. Pode tentar novamenteee.";
          $message_type = "msg_error"; // there are 3 types of messages: msg_success, msg_warning and msg_error
          $message_icon = "<i class='fa-solid fa-circle-exclamation'></i>";
          $target_location = "mdl_actions.php?action=detail&mdl_id=$mdl_id&UUID=$rdm_uuid";
        }
      }

      // close the database connection
      include '../app_components/db_disconnect.php';
      break;

    // ########################################################
    // ####### if UPDATING
    // ########################################################
    case 'update':

      // create a database connection
      include '../app_components/db_connect.php';

      $mdl_id = $_POST["mdl_id"];
      $prm_id = $_POST["prm_id"];
      $prm_name = $_POST["prm_name"];

      // Update the module record
      $sql = "UPDATE module_permissions SET prm_name='$prm_name' WHERE prm_id=$prm_id";
      $result = mysqli_query($conn, $sql);

      // if this was not an AJAX call, set the message
      if ($ajax_call == 0) {
        // if update was successfull
        if ($result) {
          // Create success message and set target location to the mdl_detail page
          $message = "A permissão do módulo foi atualizada.";
          $message_type = "msg_success"; // there are 3 types of messages: msg_success, msg_warning and msg_error
          $message_icon = "<i class='fa-regular fa-face-smile-beam'></i>";
          $target_location = "mdl_actions.php?action=detail&mdl_id=$mdl_id&UUID=$rdm_uuid";
        } else {
          // Create error message and set target location to the mdl_detail page 
          $message = "Ocorreu um erro. Pode tentar novamente.";
          $message_type = "msg_error"; // there are 3 types of messages: msg_success, msg_warning and msg_error
          $message_icon = "<i class='fa-solid fa-circle-exclamation'></i>";
          $target_location = "mdl_actions.php?action=detail&mdl_id=$mdl_id&UUID=$rdm_uuid";
        }
      }

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
      if (isset($_POST['prm_id'])){
        $prm_id = $_POST['prm_id'];
        $mdl_id = $_POST["mdl_id"];
      } elseif (isset($_GET['prm_id'])){
        $prm_id = $_GET['prm_id'];
        $mdl_id = $_GET["mdl_id"];
      }

      // Delete the module record
      $sql = "DELETE FROM module_permissions WHERE prm_id=$prm_id";
      $result = mysqli_query($conn, $sql);

      // if this was not an AJAX call, set the message
      if ($ajax_call == 0) {
        // Create success message and set target location to dashboard page
        $message = "A permissão do módulo foi apagada.";
        $message_type = "msg_success"; // there are 3 types of messages: msg_success, msg_warning and msg_error
        $message_icon = "<i class='fa-regular fa-face-smile-beam'></i>";
        $target_location = "mdl_actions.php?action=detail&mdl_id=$mdl_id&UUID=$rdm_uuid";
      }

      // close the database connection
      include '../app_components/db_disconnect.php';
      break;

    // ########################################################
    // ####### If AJAX getting ALL permissions FOR A MODULE
    // ########################################################
    case 'get_mdl_permissions':
      
      $mdl_id = $_GET["mdl_id"];
          
      // Create the query string
      $sql_statement = "SELECT * FROM module_permissions WHERE mdl_id=".$mdl_id." ORDER BY prm_id";
            
      // create a database connection
      include '../app_components/db_connect.php';
      // $sql = $sql_statement;
      $result = mysqli_query($conn, $sql_statement);

        // Prepare the user data as a JSON object
        $mdl_permissions = array();
        if (mysqli_num_rows($result) > 0) {
          while ($row = $result->fetch_assoc()) {
            $section = array(
                'prm_id' => $row['prm_id'],
                'mdl_id' => $row['mdl_id'],
                'prm_name' => $row['prm_name']
            );
            $mdl_permissions[] = $section;
          }
        }

        // Return the JSON object
        header('Content-Type: application/json');
        echo json_encode($mdl_permissions);

        // if this was an HTTP (NOT AJAX) call, set the message
        if ($ajax_call == 0) {
          // Create success message and set target location to dashboard page
          $message = "Lista de permissões para o módulo";
          $message_type = "msg_success"; // there are 3 types of messages: msg_success, msg_warning and msg_error
          $message_icon = "<i class='fa-regular fa-face-smile-beam'></i>";
          $target_location = "mdl_actions.php?action=detail&mdl_id=$mdl_id&UUID=$rdm_uuid";
        }

        // close the database connection
        include '../app_components/db_disconnect.php';

      break;
  }
  
  if (isset($message)) {
    // Create the Session variables and send user to the target page
    $_SESSION['message'] = $message;
    $_SESSION['message_type'] = $message_type;
    $_SESSION['message_icon'] = $message_icon;
    header("Location: $target_location");
  }

  exit();
?>