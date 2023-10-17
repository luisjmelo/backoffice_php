<?php
  // include code that checks to see if the user is logged in
  include '../app_components/COMP_session_check.php';
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Dashboard</title>
    <link rel="stylesheet" href="../app_components/CSS_style.css">
    <script src="https://kit.fontawesome.com/e49c624ab4.js" crossorigin="anonymous"></script>
  </head>
  <body>
    <div class="top_container">
      <div class="header_sys">    
        <img class="logo" src="images/cgl_logo.png" alt="Company Logo">
        <button class="btn_nav" onclick="location.href='usr_actions.php?action=logout'"><i class="fa fa-right-from-bracket"></i></button>
      </div>
      <div class="breadcrumbs">
        <span>» Página principal</span>
      </div>
    </div>
    <div class="container">
      <?php
        // include file that displays messages and deletes the variables after displaying
        include '../app_components/COMP_msg_display.php';
      ?>
      <?php
        if (in_array('1', $_SESSION['usr_permissions'])) {
          echo "<div class='header_level_1'>
                  <i class='fa-solid fa-gears'>&nbsp;</i><span>Sistema</span>
                </div>
                <table width='100%'>
                  <!-- Utilizadores -->  
                  <tr><td>
                    <li> Utilizadores:</li>
                  </td><td style='text-align: right;'>
                    <button class='btn btn_secondary' 
                      onclick='window.location.href=\"usr_actions.php?action=add\"'
                      style='padding-top: 2px; padding-bottom: 2px; width: 73px;'>
                      <i class='fa-solid fa-plus'></i> Novo
                    </button>
                    <button class='btn btn_secondary' 
                      onclick='window.location.href=\"usr_srch.php\"'
                      style='padding-top: 2px; padding-bottom: 2px; width: 73px;''>
                      <i class='fa-solid fa-screwdriver-wrench'></i> Gerir
                    </button>
                  </td></tr>
                  <!--  Modulos --->
                  <tr><td>
                    <li> Modulos:</li>
                  </td><td style='text-align: right;'>
                    <button class='btn btn_secondary' 
                      onclick='window.location.href=\"mdl_actions.php?action=manage\"'
                      style='padding-top: 2px; padding-bottom: 2px; width: 73px;''>
                      <i class='fa-solid fa-screwdriver-wrench'></i> Gerir</button>
                  </td></tr>
                </table>";
        }
        if (in_array('3', $_SESSION['usr_permissions']) or in_array('4', $_SESSION['usr_permissions'])) {
          // include the App Comercial dashboard 
          echo "<BR>";
          include '../app_comercial/dashboard.php';
        }
        if (in_array('5', $_SESSION['usr_permissions'])) {
          // include the App Serviço de Frio dashboard 
          echo "<BR>";
          include '../app_serv_frio/dashboard.php';
        }
      ?>
    </div>
    <?php
      // include footer file
      include '../app_components/COMP_footer.php';
    ?>
  </body>
</html>
