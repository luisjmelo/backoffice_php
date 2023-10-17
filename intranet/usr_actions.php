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

  // FUNCTION to get module permissions and store them in $_SESSION['mdl_prms']
  function FUNC_get_all_mod_permissions(){
    // create a database connection
    include '../app_components/db_connect.php';

    // Get MODULES WITH PERMISSIONS
    $sql_get_mdl_prm = "SELECT m.mdl_number, m.mdl_name, p.prm_id, p.prm_name 
                        FROM modules m 
                        INNER JOIN module_permissions p on p.mdl_id = m.mdl_id 
                        ORDER BY m.mdl_number, p.prm_id"; 
    $result_get_mdl_prm = mysqli_query($conn, $sql_get_mdl_prm);

    // if modules and permissions were returned
    if (mysqli_num_rows($result_get_mdl_prm) > 0) {
      // Fetch the results and store them in an associative array      
      $_SESSION['mdl_prms'] = array();
      while ($row = $result_get_mdl_prm->fetch_assoc()) {
          $_SESSION['mdl_prms'][] = $row;
      }
    }

    // close the database connection
    include '../app_components/db_disconnect.php';
  }

  // FUNCTION to get user permissions and store them in $_SESSION['usr_prms']
  // Requires usr_id to be supplied
  function FUNC_get_all_usr_permissions($usr_id){
    // create a database connection
    include '../app_components/db_connect.php';

    // Get USER PERMISSIONS
    $sql_get_usr_prm = "SELECT prm_id FROM sys_users_permissions WHERE usr_id = $usr_id"; 
    $result_get_usr_prm = mysqli_query($conn, $sql_get_usr_prm);

    // if user permissions were returned
    if (mysqli_num_rows($result_get_usr_prm) > 0) {
      // Fetch the results and store them in an associative array      
      $_SESSION['usr_prms'] = array();
      while ($row = $result_get_usr_prm->fetch_assoc()) {
          array_push($_SESSION['usr_prms'], $row['prm_id']);
      }
    }

    // close the database connection
    include '../app_components/db_disconnect.php';
  }

  // FUNCTION to insert and update user permissions
  // Requires usr_id to be supplied
  function FUNC_insert_update_usr_permissions($usr_id){
    // create a database connection
    include '../app_components/db_connect.php';

    // If some permissions were selected
    if (isset($_POST["usr_pers"])){
      $usr_pers = $_POST["usr_pers"];
      // Create a comma delimited list of user permissions
      $usr_per_id_List = implode(',', $usr_pers);
      // Delete all user permissions that are not in the $usr_per_id_List
      $sql_del_usr_prm = "DELETE FROM sys_users_permissions WHERE usr_id = $usr_id AND prm_id NOT IN ($usr_per_id_List)"; 
      $del_result = mysqli_query($conn, $sql_del_usr_prm);
      
      // Get list of existing user permissions
      $sql_get_usr_prm = mysqli_query($conn, "SELECT prm_id FROM sys_users_permissions WHERE usr_id = $usr_id");
      $existing_usr_prm_Ids = [];
      while ($row = mysqli_fetch_assoc($sql_get_usr_prm)) {
          $existing_usr_prm_Ids[] = $row['prm_id'];
      }

      // If user already has some permissions
      if (!empty($existing_usr_prm_Ids)) {
        // Create an array with IDs that are not already in the database
        $new_prm_Ids = array_diff($usr_pers, $existing_usr_prm_Ids);
      } else {
        // All permission IDs are to be inserted
        $new_prm_Ids = $usr_pers;
      }

      // Insert the new IDs into the database table
      if (!empty($new_prm_Ids)) {
        foreach ($new_prm_Ids as $curr_prm_id) {
          // Example: Insert the individual ID into the database
          $insert_users_permissions_sql = "INSERT INTO sys_users_permissions (usr_id, prm_id) VALUES ($usr_id, $curr_prm_id)";
          mysqli_query($conn, $insert_users_permissions_sql);
        }
      }
    // If no permissions were selected
    } else {
      // Delete all user permissions
      $sql_del_all_usr_prm = "DELETE FROM sys_users_permissions WHERE usr_id = $usr_id"; 
      $del_result = mysqli_query($conn, $sql_del_all_usr_prm);
    }
    
    // close the database connection
    include '../app_components/db_disconnect.php';
  }

  // ### execute the code based on the value of $action ###
  switch($action){
    
    // ########################################################
    // ####### If LOGGING IN
    // ########################################################
    case 'login':
      $usr_username = $_POST['usr_username'];
      $usr_password = $_POST['usr_password'];

      // create a database connection
      include '../app_components/db_connect.php';

      // retrieve the user's information from the database
      $sql = "SELECT * FROM sys_users WHERE usr_username = '$usr_username' AND usr_password = '$usr_password' AND usr_status=1";
      $result = mysqli_query($conn, $sql);

      // check if a record exists for the user
      if (mysqli_num_rows($result) > 0) {
        // retrieve the user's first and last name from the database
        $row = mysqli_fetch_assoc($result);
        $usr_first_name = $row['usr_first_name'];
        $usr_last_name = $row['usr_last_name'];
        $_SESSION['usr_id'] = $row['usr_id'];
        
        // Create user permission Session variable
        $_SESSION['usr_permissions']='';
        // Get user permissions
        $sql = "SELECT prm_id FROM sys_users_permissions WHERE usr_id=".$_SESSION["usr_id"];

        $result = mysqli_query($conn, $sql);
        
        // Check if any permissions were found
        if (mysqli_num_rows($result) > 0) {
          $user_permissions = array();
          // Loop through the result and store permissions in the $user_permissions array
          while ($row = mysqli_fetch_assoc($result)) {
              $user_permissions[] = $row['prm_id'];
          }
          // Store the permissions array in the session
          $_SESSION['usr_permissions'] = $user_permissions;
        }

        // Create login success message and set target location to dashboard page  
        $message = "Bem vindo $usr_first_name $usr_last_name!";
        $message_type = "msg_success"; // there are 3 types of messages: msg_success, msg_warning and msg_error
        $message_icon = "<i class='fa-regular fa-face-smile-beam'></i>";
        $target_location = "dashboard.php?UUID=$rdm_uuid";
      } else {
        // Create login error message and set target location to login page
        $message = 'O Nome de utilizador ou a senha estão incorretos. Corrija e tente novamente.';
        $message_type = "msg_error"; // there are 3 types of messages: msg_success, msg_warning and msg_error
        $message_icon = "<i class='fa-solid fa-triangle-exclamation'></i>";
        $target_location = "../index.php?UUID=$rdm_uuid";
      }

      // close the database connection
      include '../app_components/db_disconnect.php';
      break;
    
    // ########################################################
    // ####### Prepare the ADD page
    // ########################################################
    case 'add':
      FUNC_get_all_mod_permissions();
      $target_location = "usr_add.php";
      break;
    
    // ########################################################
    // ####### If SAVING
    // ########################################################
    case 'save':
      // create a database connection
      include '../app_components/db_connect.php';
      
      $usr_first_name = $_POST["usr_first_name"];
      $usr_last_name = $_POST["usr_last_name"];
      $usr_email = $_POST["usr_email"];
      $usr_phone = $_POST["usr_phone"];
      // Convert date to MySQL-compatible format
      $usr_birthdate = "";
      if (trim($_POST["usr_birthdate"]) <> "") {
        $usr_birthdate = $_POST["usr_birthdate"];
      }
      $usr_username = $_POST["usr_username"];
      $usr_password = $_POST["usr_password"];
      $usr_status = $_POST["usr_status"];

      $sql = "INSERT INTO sys_users (usr_first_name, usr_last_name, usr_email, usr_phone, usr_birthdate, usr_username, usr_password, usr_status) 
              VALUES ('$usr_first_name', '$usr_last_name', '$usr_email', '$usr_phone', '$usr_birthdate', '$usr_username', '$usr_password', $usr_status)";
      if (mysqli_query($conn, $sql)) {
        // Get the ID of the inserted record
        $new_usr_id = mysqli_insert_id($conn);

        // Execute the function that deletes, inserts and updates the user permissions
        FUNC_insert_update_usr_permissions($new_usr_id);

        // Create success message and set target location to dashboard page  
        $message = "A informação foi guardada.";
        $message_type = "msg_success"; // there are 3 types of messages: msg_success, msg_warning and msg_error
        $message_icon = "<i class='fa-regular fa-face-smile-beam'></i>";
        $target_location = "dashboard.php?UUID=$rdm_uuid";
      } else {
        // Create error message and set target location to dashboard page  
        $message = "Ocorreu um erro. Pode tentar novamente.";
        $message_type = "msg_error"; // there are 3 types of messages: msg_success, msg_warning and msg_error
        $message_icon = "<i class='fa-solid fa-circle-exclamation'></i>";
        $target_location = "dashboard.php?UUID=$rdm_uuid";
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

      $usr_id = $_POST["usr_id"];
      $usr_first_name = $_POST["usr_first_name"];
      $usr_last_name = $_POST["usr_last_name"];
      $usr_email = $_POST["usr_email"];
      $usr_phone = $_POST["usr_phone"];
      $usr_birthdate = "";
      // Convert date to MySQL-compatible format
      if (trim($_POST["usr_birthdate"]) <> "") {
        $usr_birthdate = $_POST["usr_birthdate"];
      }
      $usr_username = $_POST["usr_username"];
      $usr_password = $_POST["usr_password"];
      $usr_status = $_POST["usr_status"];
      $usr_pers = '';

      // Update the user record
      $sql = "UPDATE sys_users SET usr_first_name='$usr_first_name', usr_last_name='$usr_last_name', usr_email='$usr_email', usr_phone='$usr_phone', usr_birthdate='$usr_birthdate', usr_username='$usr_username', usr_password='$usr_password', usr_status=$usr_status WHERE usr_id=$usr_id";
      $result = mysqli_query($conn, $sql);

      // Execute the function that deletes, inserts and updates the user permissions
      FUNC_insert_update_usr_permissions($usr_id);

      // Create success message and set target location to the usr_detail page
      $message = "A informação foi atualizada.";
      $message_type = "msg_success"; // there are 3 types of messages: msg_success, msg_warning and msg_error
      $message_icon = "<i class='fa-regular fa-face-smile-beam'></i>";
      $target_location = "usr_actions.php?action=detail&usr_id=$usr_id&UUID=$rdm_uuid";

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
      if (isset($_POST['usr_id'])){
        $usr_id = $_POST['usr_id'];  
      } elseif (isset($_GET['usr_id'])){
        $usr_id = $_GET['usr_id'];  
      }

      // Execute the function that deletes, inserts and updates the user permissions
      FUNC_insert_update_usr_permissions($usr_id);
      
      // Delete the user
      $sql = "DELETE FROM sys_users WHERE usr_id = $usr_id";

      if (mysqli_query($conn, $sql)) {
        // Create success message and set target location to dashboard page  
        $message = "O utilizador foi apagado.";
        $message_type = "msg_success"; // there are 3 types of messages: msg_success, msg_warning and msg_error
        $message_icon = "<i class='fa-regular fa-face-smile-beam'></i>";
        $target_location = "dashboard.php?UUID=$rdm_uuid";
      } else {
        // Create error message and set target location to dashboard page  
        $message = ". Ocorreu um erro. O utilizador não foi apagado. Pode tentar novamente.";
        $message_type = "msg_error"; // there are 3 types of messages: msg_success, msg_warning and msg_error
        $message_icon = "<i class='fa-solid fa-circle-exclamation'></i>";
        $target_location = "usr_detail.php?usr_id=$usr_id&UUID=$rdm_uuid";
      }

      // close the database connection
      include '../app_components/db_disconnect.php';
      break;
    
    // ########################################################
    // ####### If getting DETAIL
    // ########################################################
    case 'detail':
      // Get the user ID passed through the URL or a form field
      if (isset($_POST['usr_id'])){
        $usr_id = $_POST['usr_id'];  
      } elseif (isset($_GET['usr_id'])){
        $usr_id = $_GET['usr_id'];  
      }
      
      // create a database connection
      include '../app_components/db_connect.php';

      $sql_get_usr = "SELECT * FROM sys_users WHERE usr_id = $usr_id";
      $result_get_usr = mysqli_query($conn, $sql_get_usr);

      // if query returned one user
      if (mysqli_num_rows($result_get_usr) == 1) {
        // Fetch the results and store them in an associative array      
        $_SESSION['users'] = array();
        while ($row = $result_get_usr->fetch_assoc()) {
            $_SESSION['users'][] = $row;
        }

        // Get MODULES WITH PERMISSIONS (Function at the begining of the page)
        FUNC_get_all_mod_permissions();
        // if modules and permissions were returned
        if (isset($_SESSION['mdl_prms'])){
          // Get ALL USER Permissions (Function at the begining of the page)
          FUNC_get_all_usr_permissions($usr_id);
        }

        // Create message and set target location
        $target_location = "usr_detail.php";
      } else {
        // Create message and set target location
        $message = "Nenhum utilizador encontrado. Pode tentar novamente.";
        $message_type = "msg_warning"; // there are 3 types of messages: msg_success, msg_warning and msg_error
        $message_icon = "<i class='fa-regular fa-bell'></i>";
        $target_location = "dashboard.php?UUID=$rdm_uuid";
      }

      // close the database connection
      include '../app_components/db_disconnect.php';
      break;

    // ########################################################
    // ####### If SEARCHING
    // ########################################################
    case 'search':
      $usr_first_name = trim($_POST["usr_first_name"]);
      $usr_last_name = trim($_POST["usr_last_name"]);
      if (isset($_POST["usr_status"])) {
        $usr_status = $_POST["usr_status"];
      }
      
      // Create the query string
      $sql_statement = "SELECT usr_id, usr_first_name, usr_last_name, usr_status  FROM sys_users WHERE 0=0";
      if ($usr_first_name <> "") {
        $sql_statement = $sql_statement . " AND usr_first_name LIKE '%" . trim($usr_first_name) . "%'";
      }
      if ($usr_last_name <> "") {
        $sql_statement = $sql_statement . " AND usr_last_name LIKE '%" . trim($usr_last_name) . "%'";
      }
      if (isset($usr_status)) {
        $sql_statement = $sql_statement . " AND usr_status =" . trim($usr_status);
      }
      $sql_statement = $sql_statement . " ORDER BY usr_first_name, usr_last_name, usr_id";

      // create a database connection
      include '../app_components/db_connect.php';
      $sql = $sql_statement;
      $result = mysqli_query($conn, $sql);
      
      // If search returned records
      if (mysqli_num_rows($result) > 0){

        // Fetch the results and store them in an associative array      
        $_SESSION['users'] = array();
        while ($row = $result->fetch_assoc()) {
            $_SESSION['users'][] = $row;
        }

        // If only one result was returned, create a message and set the destination page (action page)
        if (mysqli_num_rows($result) == 1) {
          $row = $_SESSION['users'][0]; // Get the first row
          $message = "Um registo encontrado.";
          $message_type = "msg_success"; // there are 3 types of messages: msg_success, msg_warning and msg_error
          $message_icon = "<i class='fa-regular fa-face-smile-beam'></i>";
          $target_location = "usr_actions.php?action=detail&usr_id=" . $row['usr_id'] . "&UUID=" . $rdm_uuid;

        
        // If more than one result was returned, create a message and set the destination page (search page)
        } elseif (mysqli_num_rows($result) > 1) {
          // Create message
          $message = mysqli_num_rows($result) . " registos encontrados.";
          $message_type = "msg_success"; // there are 3 types of messages: msg_success, msg_warning and msg_error
          $message_icon = "<i class='fa-regular fa-face-smile-beam'></i>";
          $target_location = "usr_srch.php?UUID=$rdm_uuid";
        }

        // close the database connection
        include '../app_components/db_disconnect.php';
      
      //If no results were returned, create a message and set the destination page (search page)
      } else {
        // Create message
        $message = "Nenhum registo encontrado. Pode tentar novamente.";
        $message_type = "msg_warning"; // there are 3 types of messages: msg_success, msg_warning and msg_error
        $message_icon = "<i class='fa-regular fa-bell'></i>";
        $target_location = "usr_srch.php?UUID=$rdm_uuid";
      }
      break;

    // ########################################################
    // ####### If no user is logged in
    // ########################################################
    case 'expell':
      $message = "A sua sessão expirou. Por favor faça o login novamente!";
      $message_type = "msg_error"; // there are 3 types of messages: msg_success, msg_warning and msg_error
      $message_icon = "<i class='fa-solid fa-circle-exclamation'></i>";
      $target_location = "../index.php?UUID=$rdm_uuid";
      break;
    
    // ########################################################
    // ####### If LOG OUT
    // ########################################################
    case 'logout':
      unset($_SESSION['usr_id']);
      $message = "A sua sessão foi encerrada.";
      $message_type = "msg_success"; // there are 3 types of messages: msg_success, msg_warning and msg_error
      $message_icon = "<i class='fa-regular fa-face-smile-beam'></i>";
      $target_location = "../index.php?UUID=$rdm_uuid";
      break;

  }

  // Create the Session variables and send user to the target page
  // Only set session message if those were set during the action
  if(isset($message)){
    $_SESSION['message'] = $message;
    $_SESSION['message_type'] = $message_type;
    $_SESSION['message_icon'] = $message_icon;
  }
  header("Location: $target_location");
  exit();
?>