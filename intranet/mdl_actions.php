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

  // ### execute the code based on the value of $action ###
  switch ($action){
  
    // ########################################################
    // ####### If ADDING
    // ########################################################
    case 'save':
      // create a database connection
      include '../app_components/db_connect.php';
      
      $mdl_name = $_POST["mdl_name"];
      $mdl_number = $_POST["mdl_number"];
      
      $sql = "INSERT INTO modules (mdl_name, mdl_number) VALUES ('$mdl_name', '$mdl_number')";

      if (mysqli_query($conn, $sql)) {
        // Get the ID of the inserted record
        $last_inserted_id = mysqli_insert_id($conn);
        // Create success message and set target location to dashboard page  
        $message = "O Módulo foi adicionado. Agora pode adicionar Permissões.";
        $message_type = "msg_success"; // there are 3 types of messages: msg_success, msg_warning and msg_error
        $message_icon = "<i class='fa-regular fa-face-smile-beam'></i>";
        $target_location = "mdl_actions.php?action=detail&mdl_id=$last_inserted_id&UUID=$rdm_uuid";
      } else {
        // Create error message and set target location to module mange page  
        $message = "Ocorreu um erro. O módulo não foi adicionado. Pode tentar novamente.";
        $message_type = "msg_error"; // there are 3 types of messages: msg_success, msg_warning and msg_error
        $message_icon = "<i class='fa-solid fa-circle-exclamation'></i>";
        $target_location = "mdl_actions.php?action=manage&UUID=$rdm_uuid";
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
      $mdl_name = $_POST["mdl_name"];
      $mdl_number = $_POST["mdl_number"];

      // Update the module record
      $sql = "UPDATE modules SET mdl_name='$mdl_name', mdl_number='$mdl_number' WHERE mdl_id=$mdl_id";
      $result = mysqli_query($conn, $sql);

      // if query executed without errors
      if ($result) {
        // Create success message and set target location to the mdl_detail page
        $message = "O módulo foi atualizado.";
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
      if (isset($_POST['mdl_id'])){
        $mdl_id = $_POST['mdl_id'];  
      } elseif (isset($_GET['mdl_id'])){
        $mdl_id = $_GET['mdl_id'];  
      }

      // Delete the module record
      $sql = "DELETE FROM modules WHERE mdl_id=$mdl_id";
      $result = mysqli_query($conn, $sql);

      // Create success message and set target location to dashboard page
      $message = "O módulo foi apagado.";
      $message_type = "msg_success"; // there are 3 types of messages: msg_success, msg_warning and msg_error
      $message_icon = "<i class='fa-regular fa-face-smile-beam'></i>";
      $target_location = "dashboard.php?UUID=$rdm_uuid";

      // close the database connection
      include '../app_components/db_disconnect.php';
      break;

    // ########################################################
    // ####### If getting DETAIL
    // ########################################################
    case 'detail':
      
      // Get the user ID passed through the URL or a form field
      if (isset($_POST['mdl_id'])){
        $mdl_id = $_POST['mdl_id'];  
      } elseif (isset($_GET['mdl_id'])){
        $mdl_id = $_GET['mdl_id'];  
      }
      
      echo $mdl_id;
      // Create a database connection
      include '../app_components/db_connect.php';

      $sql_module = "SELECT * FROM modules WHERE mdl_id = $mdl_id";
      $result_module = mysqli_query($conn, $sql_module);

      // If query returned one row
      if (mysqli_num_rows($result_module) == 1) {
        // Fetch the results and store them in an associative array      
        $_SESSION['module'] = array();
        while ($row = $result_module->fetch_assoc()) {
            $_SESSION['module'][] = $row;
        }

        // Get all module permissions
        $sql_permissions = "SELECT * FROM module_permissions WHERE mdl_id = $mdl_id ORDER BY prm_id";
        $result_permissions = mysqli_query($conn, $sql_permissions);
        // If query returned rows
        if (mysqli_num_rows($result_permissions) > 0) {
          // Fetch the results and store them in an associative array      
          $_SESSION['module_permissions'] = array();
          while ($row = $result_permissions->fetch_assoc()) {
              $_SESSION['module_permissions'][] = $row;
          }
        }

        // Create message and set target location
        $target_location = "mdl_detail.php";
      } else {
        // Create message and set target location
        $message = "Nenhum modulo encontrado. Pode tentar novamente.";
        $message_type = "msg_warning"; // there are 3 types of messages: msg_success, msg_warning and msg_error
        $message_icon = "<i class='fa-regular fa-bell'></i>";
        $target_location = "dashboard.php?UUID=$rdm_uuid";
      }

      // Since this system has subsystems, there is a possibility that this action was called from a subsystem page. 
      // If that was the case, the message session variables already exist and should be used.
      if (isset($_SESSION['message'])){
        $message = $_SESSION['message'];
        $message_type = $_SESSION['message_type']; // there are 3 types of messages: msg_success, msg_warning and msg_error
        $message_icon =  $_SESSION['message_icon'];
      }

      // Close the database connection
      include '../app_components/db_disconnect.php';
      break;

    // ########################################################
    // ####### If MANAGING
    // ########################################################
    case 'manage':
      
      // Create the query string
      // Get all modules and the count of respective permissions
      $sql_statement = "SELECT m.mdl_id, m.mdl_number, m.mdl_name, COUNT(p.prm_id) AS permission_count 
                        FROM modules m 
                        LEFT JOIN module_permissions p ON m.mdl_id = p.mdl_id
                        GROUP BY m.mdl_id, m.mdl_number, m.mdl_name
                        ORDER BY m.mdl_number";

      // Create a database connection and execute the query
      include '../app_components/db_connect.php';
      $result = mysqli_query($conn, $sql_statement);

      /* If search returned records*/
      if (mysqli_num_rows($result) > 0){
        // Fetch the results and store them in an associative array      
        $_SESSION['modules'] = array();
        while ($row = $result->fetch_assoc()) {
            $_SESSION['modules'][] = $row;
        }
      }
      $target_location = "mdl_manage.php?UUID=$rdm_uuid";

      // Close the database connection
      include '../app_components/db_disconnect.php';
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