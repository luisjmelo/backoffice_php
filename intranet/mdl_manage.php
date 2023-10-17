<?php
  // include code that checks to see if the user is logged in
  include '../app_components/COMP_session_check.php';
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Modulos: localizar</title>
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
          <button class="btn_nav" onclick="location.href='usr_actions.php?action=logout'"><i class="fa fa-right-from-bracket" alt="sair"></i></button>
          <button class="btn_nav" onclick="location.href='dashboard.php'"><i class="fa-solid fa-house" alt="Página principal"></i></button>
        </span>
      </div>
      <div class="breadcrumbs">
        <span>» Página principal » Modulos: gerir</span>
      </div>
    </div>
    <div class="container">
      <?php
        // include file that displays messages and deletes the variables after displaying
        include '../app_components/COMP_msg_display.php';

        //  Section that displays search results, if a search was made and maches were returned
        if (isset($_SESSION['modules'])) {
          echo "<div class='header_level_1'>
                <i class='fa-solid fa-list'>&nbsp;</i><span>Modulos existentes</span>
                </div>";
          echo "<table width='100%'><tr><th width='25%' style='text-align: left;'>&nbsp;Número</th><th width='40%' style='text-align: left;'>Nome</th><th width='15%'>Permissões</th><th width='20%'></th></tr>";
          
          foreach ($_SESSION['modules'] as $module) {
            $mdl_id = $module["mdl_id"];
            $mdl_name = $module["mdl_name"];
            $mdl_number = $module["mdl_number"];
            $permission_count = $module["permission_count"];
          
            echo "<tr>";
            echo "<td><li />" . $mdl_number . "</td>";
            echo "<td>" . $mdl_name . "</td>";
            echo "<td style='text-align: center;'>" . $permission_count . "</td>";
            echo "</td><td style='text-align: right;' style='padding-right: 10px;'>";
            echo "<i class='fa-solid fa-pen-to-square act_icon act_icon_secondary' onclick='window.location.href=\"mdl_actions.php?action=detail&mdl_id=$mdl_id\"'></i>";
            echo "</td>";
            echo "</tr>";
          }
        }
      ?>

      <!-- Add new Module section -->
      <?php
        if (!isset($_SESSION['modules'])) {
          echo "<table width='100%'>";
        }
      ?>
        <tr>
          <form id="mdl_add_form" method="post" action="mdl_actions.php" onsubmit="return checkpage('mdl_add_form');">
          <input type="hidden" id="action" name="action" />
          <td  width='25%' style="padding-right: 10px;">
            <?php
              // if there are modules
              if (isset($_SESSION['modules'])) {
                echo "<div name='mdl_new_dsp'></div><div name='mdl_new_act' style='display:none; padding-right: 10px;'>";
              }else{
                echo "<div name='mdl_new_act' style='padding-right: 10px;'>";
              }
            ?>
              <label for="mdl_number">* Número:</label>
              <input type="text" id="mdl_number" name="mdl_number"
                fld_req="true"
                fld_error_message="O 'Número do módulo' é requerido. Não pode conter letras."
                fld_regEx="rx_nbr" />
            </div>
          </td>
          <td width='55%' colspan="2">
            <?php
              // if there are modules
              if (isset($_SESSION['modules'])) {
                echo "<div name='mdl_new_dsp'></div><div name='mdl_new_act' style='display:none;'>";
              } else {
                echo "<div name='mdl_new_act'>";
              }
            ?>
              <label for="mdl_name">* Nome:</label>
              <input type="text" id="mdl_name" name="mdl_name"
                fld_req="true"
                fld_error_message="O 'Nome do módulo' é requerido."
                fld_regEx="rx_allowAll" />
            </div>
          </td>
          <td width='20%' style="text-align: right;">
            <?php
              // if there are modules
              if (isset($_SESSION['modules'])) {
                echo "<div name='mdl_new_dsp'>";
                echo "<i class='fa-solid fa-circle-plus act_icon' onclick='toggle_show_hide(\"mdl_new_act\", \"mdl_new_dsp\");; mdl_add_form.mdl_number.focus();'> Novo</i>";
                echo "</div>";
                echo "<div name='mdl_new_act' style='display:none;'>";
                echo "<i class='fa-regular fa-circle-xmark act_icon act_icon_delete' onclick='mdl_add_form.reset(); toggle_show_hide(\"mdl_new_act\", \"mdl_new_dsp\");'></i><br />";
                echo "<button class='btn btn_primary' onclick=\"this.form.action.value='save';\"><i class='fa-solid fa-floppy-disk'></i> Guarda</button>";
                echo "</div>";
              }else{
                echo "<div name='mdl_new_act'>";
                echo "<button class='btn btn_primary' onclick=\"this.form.action.value='save';\"><i class='fa-solid fa-floppy-disk'></i> Guarda</button>";
                echo "</div>";
              }
            ?>
          </td>
          </form>
        </tr>
      </table>
    </div>
    <?php
      if (!isset($_SESSION['modules'])) {
        // Delete the search results sessiion variable
        unset($_SESSION['modules']);
      }
      // include footer file
      include '../app_components/COMP_footer.php';
    ?>
  </body>
</html>