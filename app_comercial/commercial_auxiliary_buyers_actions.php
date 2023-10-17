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
      
      $byr_name = $_POST["byr_name"];
      
      $sql = "INSERT INTO buyers (byr_name) VALUES ('$byr_name')";

      if (mysqli_query($conn, $sql)) {
        // Create success message and set target location to dashboard page  
        $message = "O comprador foi adicionado.";
        $message_type = "msg_success"; // there are 3 types of messages: msg_success, msg_warning and msg_error
        $message_icon = "<i class='fa-regular fa-face-smile-beam'></i>";
      } else {
        // Create error message and set target location to module mange page  
        $message = "Ocorreu um erro. O comprador não foi adicionado. Pode tentar novamente.";
        $message_type = "msg_error"; // there are 3 types of messages: msg_success, msg_warning and msg_error
        $message_icon = "<i class='fa-solid fa-circle-exclamation'></i>";
      }
      
      $target_location = "commercial_auxiliary_buyers_actions.php?action=manage&UUID=$rdm_uuid";

      // close the database connection
      include '../app_components/db_disconnect.php';
      break;

    // ########################################################
    // ####### if UPDATING
    // ########################################################
    case 'update':

      // create a database connection
      include '../app_components/db_connect.php';

      $byr_id = $_POST["byr_id"];
      $byr_name = $_POST["byr_name"];

      // Update the module record
      $sql = "UPDATE buyers SET byr_name='$byr_name' WHERE byr_id=$byr_id";
      $result = mysqli_query($conn, $sql);

      // if query executed without errors
      if ($result) {
        // Create success message and set target location to the buyer_detail page
        $message = "O comprador foi atualizado.";
        $message_type = "msg_success"; // there are 3 types of messages: msg_success, msg_warning and msg_error
        $message_icon = "<i class='fa-regular fa-face-smile-beam'></i>";
      } else {
        // Create error message and set target location to the buyer_detail page 
        $message = "Ocorreu um erro. Pode tentar novamente.";
        $message_type = "msg_error"; // there are 3 types of messages: msg_success, msg_warning and msg_error
        $message_icon = "<i class='fa-solid fa-circle-exclamation'></i>";
      }

      $target_location = "commercial_auxiliary_buyers_actions.php?action=manage&UUID=$rdm_uuid";

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
      if (isset($_POST['byr_id'])){
        $byr_id = $_POST['byr_id'];  
      } elseif (isset($_GET['byr_id'])){
        $byr_id = $_GET['byr_id'];  
      }

      // Delete the module record
      $sql = "DELETE FROM buyers 
              WHERE byr_id=$byr_id";
      $result = mysqli_query($conn, $sql);

      // Create success message and set target location to dashboard page
      $message = "O comprador foi apagado.";
      $message_type = "msg_success"; // there are 3 types of messages: msg_success, msg_warning and msg_error
      $message_icon = "<i class='fa-regular fa-face-smile-beam'></i>";
      $target_location = "commercial_auxiliary_buyers_actions.php?action=manage&UUID=$rdm_uuid";

      // close the database connection
      include '../app_components/db_disconnect.php';
      break;

    // ########################################################
    // ####### If MANAGING
    // ########################################################
    case 'manage':
      
      // Get all Buyers and store them in the $_SESSION.all_buyers array
      FUNC_get_all_buyers();
      
      $target_location = "commercial_auxiliary_buyers_manage.php";

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