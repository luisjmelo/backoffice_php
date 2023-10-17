<?php
// include code that checks to see if the user is logged in
include '../app_components/COMP_session_check.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Utilizadores: localizar</title>
  <link rel="stylesheet" href="../app_components/CSS_style.css">
  <script src="https://kit.fontawesome.com/e49c624ab4.js" crossorigin="anonymous"></script>
  <script src="../app_components/JS_form_validation.js"></script>
  <script src="../app_components/JS_common_functions.js"></script>
</head>

<body>
  <div class="top_container">
    <div class="header_sys">
      <img class="logo" src="images/cgl_logo.png" alt="Company Logo">
      <span class="nav_buttons_housing">
        <button class="btn_nav" onclick="location.href='usr_actions.php?action=logout'"><i class="fa fa-right-from-bracket" alt="Log out"></i></button>
        <button class="btn_nav" onclick="location.href='dashboard.php'"><i class="fa-solid fa-house" alt="Dashboard"></i></button>
        <button class="btn_nav" onclick="location.href='usr_actions.php?action=add'"><i class="fa-solid fa-plus" alt="Add"></i></button>
      </span>
    </div>
    <div class="breadcrumbs">
      <span>» Página principal » Utilizadores: localizar</span>
    </div>
  </div>
  <div class="container">
    <?php
    // include file that displays messages and deletes the variables after displaying
    include '../app_components/COMP_msg_display.php';

    //  Section that displays search results, if a search was made and maches were returned
    if (isset($_SESSION['users'])) {
      echo "<div class='header_level_1'>
           <i class='fa-solid fa-list'>&nbsp;</i><span>Resultados da pesquisa</span>
          </div>";
      echo "<table width='100%'><tr><th width='40%'>Nome</th><th width='30%'>Estado</th><th width='30%'></th></tr>";

      foreach ($_SESSION['users'] as $user) {
        $usr_id = $user["usr_id"];
        $usr_first_name = $user["usr_first_name"];
        $usr_last_name = $user["usr_last_name"];
        $usr_status = $user["usr_status"];
        // Translate bit to text
        $usr_status_text = "Ativo";
        if ($user["usr_status"] == 0) {
          $usr_status_text = "Inativo";
        }

        echo "<tr>";
        // display first and last name
        echo "<td><li />" . $usr_first_name . " " . $usr_last_name . "</td>";
        // display user status
        echo "<td align='center'>" . $usr_status_text . "</td>";
        // Display actions cell
        echo "</td><td style='text-align: right;'>";
        echo "<i class='fa-solid fa-pen-to-square act_icon' onclick='window.location.href=" . '"usr_actions.php?action=detail&usr_id=' . $usr_id . '"' . "'></i>&nbsp;&nbsp;";
        if ($usr_id == $_SESSION['usr_id']) {
          echo "<i class='fa-solid fa-trash act_icon art_icon_disabled'></i>&nbsp;&nbsp;";
        } else {
          echo "<i class='fa-solid fa-trash act_icon act_icon_delete' onclick='if (confirm_delete()){ window.location.href=" . '"usr_actions.php?action=delete&usr_id=' . $usr_id . '"' . ";}'></i>&nbsp;&nbsp;";
        }
        echo "</td>";
        echo "</tr>";
      }
      // Delete the search results sessiion variable
      unset($_SESSION['users']);

      echo "</table><br />";
      echo "<div class='header_level_1'>
            <i class='fa-solid fa-magnifying-glass'>&nbsp;</i><span>Nova Pesquisa</span>
          </div>";
    }
    ?>
    <form id="usr_add_form" method="post" action="usr_actions.php">
      <input type="hidden" id="action" name="action" />
      <div class="row">
        <div class="col_50_l">
          <label for="usr_first_name">Nome próprio:</label>
          <input type="text" id="usr_first_name" name="usr_first_name" />
        </div>
        <div class="col_50_r">
          <label for="usr_last_name">Apelido:</label>
          <input type="text" id="usr_last_name" name="usr_last_name" />
        </div>
      </div>
      <div class="row">
        <div class="col_50_l">
          <label for="usr_status">* Estado:</label>
          <div class="row">
            <div class="col_50_l" style="width: 45%;">
              <label class="RDB_container">Ativo
                <input type="radio" name="usr_status" value="1" fld_req="false">
                <span class="RDB_checkmark"></span>
              </label>
            </div>
            <div class="col_50_r" style="width: 45%;">
              <label class="RDB_container">Inativo
                <input type="radio" name="usr_status" value="0">
                <span class="RDB_checkmark"></span>
              </label>
            </div>
          </div>
        </div>
      </div>
      <div class="form_buttons_housing">
        <button class="btn btn_secondary" onclick="this.form.action.value='search';"><i class="fa-solid fa-magnifying-glass"></i> Procura</button>
      </div>
    </form>
  </div>
  <?php
  // include footer file
  include '../app_components/COMP_footer.php';
  ?>
</body>

</html>