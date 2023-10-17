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
    // ####### If ADDING
    // ########################################################
    case 'save':
      // create a database connection
      include '../app_components/db_connect.php';
      
      $fsp_name = $_POST["fsp_name"];
      $fsp_scientific_name = $_POST["fsp_scientific_name"];
      
      $sql = "INSERT INTO fish_species (fsp_name, fsp_scientific_name) VALUES ('$fsp_name', '$fsp_scientific_name')";

      if (mysqli_query($conn, $sql)) {
        // Create success message and set target location to dashboard page  
        $message = "A espécie foi adicionada.";
        $message_type = "msg_success"; // there are 3 types of messages: msg_success, msg_warning and msg_error
        $message_icon = "<i class='fa-regular fa-face-smile-beam'></i>";
      } else {
        // Create error message and set target location to module mange page  
        $message = "Ocorreu um erro. A espécie não foi adicionada. Pode tentar novamente.";
        $message_type = "msg_error"; // there are 3 types of messages: msg_success, msg_warning and msg_error
        $message_icon = "<i class='fa-solid fa-circle-exclamation'></i>";
      }
      
      $target_location = "commercial_auxiliary_fish_species_actions.php?action=manage&UUID=$rdm_uuid";

      // close the database connection
      include '../app_components/db_disconnect.php';
      break;

    // ########################################################
    // ####### if UPDATING
    // ########################################################
    case 'update':

      // create a database connection
      include '../app_components/db_connect.php';

      $fsp_id = $_POST["fsp_id"];
      $fsp_name = $_POST["fsp_name"];
      $fsp_scientific_name = $_POST["fsp_scientific_name"];

      // Update the module record
      $sql = "UPDATE fish_species SET fsp_name='$fsp_name', fsp_scientific_name='$fsp_scientific_name' WHERE fsp_id=$fsp_id";
      $result = mysqli_query($conn, $sql);

      // if query executed without errors
      if ($result) {
        // Create success message and set target location to the specie_detail page
        $message = "A espécie foi atualizado.";
        $message_type = "msg_success"; // there are 3 types of messages: msg_success, msg_warning and msg_error
        $message_icon = "<i class='fa-regular fa-face-smile-beam'></i>";
      } else {
        // Create error message and set target location to the specie_detail page 
        $message = "Ocorreu um erro. Pode tentar novamente.";
        $message_type = "msg_error"; // there are 3 types of messages: msg_success, msg_warning and msg_error
        $message_icon = "<i class='fa-solid fa-circle-exclamation'></i>";
      }

      $target_location = "commercial_auxiliary_fish_species_actions.php?action=manage&UUID=$rdm_uuid";

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
      if (isset($_POST['fsp_id'])){
        $fsp_id = $_POST['fsp_id'];  
      } elseif (isset($_GET['fsp_id'])){
        $fsp_id = $_GET['fsp_id'];  
      }

      // Delete the module record
      $sql = "DELETE FROM fish_species 
              WHERE fsp_id=$fsp_id";
      $result = mysqli_query($conn, $sql);

      // Create success message and set target location to dashboard page
      $message = "A espécie foi apagada.";
      $message_type = "msg_success"; // there are 3 types of messages: msg_success, msg_warning and msg_error
      $message_icon = "<i class='fa-regular fa-face-smile-beam'></i>";
      $target_location = "commercial_auxiliary_fish_species_actions.php?action=manage&UUID=$rdm_uuid";

      // close the database connection
      include '../app_components/db_disconnect.php';
      break;

    // ########################################################
    // ####### If MANAGING
    // ########################################################
    case 'manage':
      
      // Get all Species and store them in the $_SESSION.all_species array
      FUNC_get_all_species();

      $target_location = "commercial_auxiliary_fish_species_manage.php?UUID=$rdm_uuid";

      // Close the database connection
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