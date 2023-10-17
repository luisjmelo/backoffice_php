<?php
  // include code that checks to see if the user is logged in
  include '../app_components/COMP_session_check.php';
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Embarcação: Gerir</title>
    <link rel="stylesheet" href="../app_components/CSS_style.css">
    <script src="https://kit.fontawesome.com/e49c624ab4.js" crossorigin="anonymous"></script>
    <script src="../app_components/JS_form_validation.js"></script>
    <script src="../app_components/JS_common_functions.js"></script>
  </head>
  <body>
    <div class="top_container">
      <div class="header_sys">
        <img class="logo" src="../intranet/images/cgl_logo.png" alt="Company Logo">
        <span class="nav_buttons_housing">
          <button class="btn_nav" onclick="location.href='../intranet/usr_actions.php?action=logout'"><i class="fa fa-right-from-bracket" alt="sair"></i></button>
          <button class="btn_nav" onclick="location.href='../intranet/dashboard.php'"><i class="fa-solid fa-house" alt="Página principal"></i></button>
        </span>
      </div>
      <div class="breadcrumbs">
        <span>» Página principal » Embarcações: gerir</span>
      </div>
    </div>
    <div class="container">
      <?php
        // include file that displays messages and deletes the variables after displaying
        include '../app_components/COMP_msg_display.php';
        echo "<table width='100%'>";
        $boats_to_display = 0;
        // If "module permissions" array exists
        if (isset($_SESSION['all_boats'])) {
          $boats_to_display = 1;
          // Create the table to contain the module permissions
          echo "<tr>
                  <th width='50%' style='text-align: left; padding-left: 10px;'>* Nome da embarcação</th>
                  <th width='30%' style='text-align: right; padding-right: 10px;'>Ação</th>
                </tr>";

          // Polulate local variables with user data
          foreach ($_SESSION['all_boats'] as $section) {
            $boat_id = $section["boat_id"];
            $boat_name = $section["boat_name"];

            // Now display the existing module permissions
            // <form id='boat_edit_form_$boat_id' method='post' action='commercial_auxiliary_boats_actions.php' onsubmit='alert(\"gotHere\"); return checkpage(\"boat_edit_form_$boat_id\");'>
            echo "<tr>
                    <form id='boat_edit_form_$boat_id' method='post' action='commercial_auxiliary_boats_actions.php'>
                    <input type='hidden' id='boat_id' name='boat_id' value='$boat_id' />
                    <input type='hidden' id='action' name='action' />
                    <td>
                      <div name='boat_dsp_$boat_id' style='padding-left: 10px;'>$boat_name</div>
                      <div name='boat_act_$boat_id' style='display:none;'>
                        <input type='text' id='boat_name' name='boat_name' value='$boat_name'
                          fld_req='true'
                          fld_error_message='O Nome da embarcação é requerido.'
                          fld_regEx='rx_allowAll' />
                      </div>
                    </td>
                    <td style='text-align: right; vertical-align:top;'>
                      <div name='boat_dsp_$boat_id' style='padding-right: 10px;'>
                        <i class='fa-solid fa-pen-to-square act_icon act_icon_secondary' onclick='toggle_show_hide(\"boat_act_$boat_id\", \"boat_dsp_$boat_id\");'></i>
                      </div>
                      <div name='boat_act_$boat_id' style='display:none; padding-right: 10px;'>
                        <i class='fa-solid fa-trash act_icon act_icon_delete' onclick='if (confirm_delete()){ window.location.href=\"commercial_auxiliary_boats_actions.php?action=delete&boat_id=$boat_id\";}'></i>&nbsp;&nbsp;
                        <i class='fa-solid fa-floppy-disk act_icon act_icon_primary' onclick='boat_edit_form_$boat_id.action.value=\"update\"; if (checkpage(\"boat_edit_form_$boat_id\")){boat_edit_form_$boat_id.submit();};'></i>&nbsp;&nbsp;
                        <i class='fa-regular fa-circle-xmark act_icon act_icon_delete' onclick='boat_edit_form_$boat_id.reset(); toggle_show_hide(".'"boat_act_'.$boat_id.'", "boat_dsp_'.$boat_id.'"'.");'></i>
                      </div>
                    </td>
                    </form>
                  </tr>";
          }
          // Delete the "boats" array
          unset($_SESSION['all_boats']);
        } 
      ?>
      <!-- Add new boat -->
      <tr>
        <form id="boat_add_form" method="post" action="commercial_auxiliary_boats_actions.php" onsubmit="return checkpage('boat_add_form');">
        <input type="hidden" id="action" name="action" />
        <td>
          <?php
            // if there are already boats
            if($boats_to_display != 0){ 
              echo "<div name='boat_new_dsp'></div><div name='boat_new_act' style='display:none;'>";
            }else{
              echo "<div name='boat_new_act'>";
            }
          ?>
            <label for="boat_name">* Nome da embarcação:</label>
            <input type="text" id="boat_name" name="boat_name"
              fld_req="true"
              fld_error_message="O 'Nome da embarcação' é requerido."
              fld_regEx="rx_allowAll" />
          </div>
        </td>
        <td style="text-align: right;">
          <?php
            // if there are already boats
            if($boats_to_display != 0){ 
              echo "<div name='boat_new_dsp' style='padding-right: 10px;'>
                      <i class='fa-solid fa-circle-plus act_icon act_icon_secondary' onclick='toggle_show_hide(\"boat_new_act\", \"boat_new_dsp\"); boat_add_form.boat_name.focus();'> Novo</i>
                    </div>
                    <div name='boat_new_act' style='display:none; padding-right: 10px;'>
                      <i class='fa-regular fa-circle-xmark act_icon act_icon_delete' onclick='boat_add_form.reset(); toggle_show_hide(\"boat_new_act\", \"boat_new_dsp\");'></i><br />";
            }else{
              echo "<div name='boat_new_act' style='padding-right: 10px;'>";
            }
          ?>
            <button class="btn btn_primary" onclick="this.form.action.value='save';"><i class="fa-solid fa-floppy-disk"></i> Guarda</button>
          </div>
        </td>
        </form>
      </tr>
      </table>
    </div>
    <?php
      if (!isset($_SESSION['all_boats'])) {
        // Delete the boats array variable
        unset($_SESSION['all_boats']);
      }
      // include footer file
      include '../app_components/COMP_footer.php';
    ?>
  </body>
</html>