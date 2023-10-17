<?php
  // include code that checks to see if the user is logged in
  include '../app_components/COMP_session_check.php';
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Lotas: Gerir</title>
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
        <span>» Página principal » Lotas: gerir</span>
      </div>
    </div>
    <div class="container">
      <?php
        // include file that displays messages and deletes the variables after displaying
        include '../app_components/COMP_msg_display.php';
        echo "<table width='100%'>";
        $ports_to_display = 0;
        // If "module permissions" array exists
        if (isset($_SESSION['all_ports'])) {
          $ports_to_display = 1;
          // Create the table to contain the module permissions
          echo "<tr>
                <th width='80%' style='text-align: left; padding-left: 10px;'>* Nome da lota</th>
                <th width='20%' style='text-align: right; padding-right: 10px;'>Ação</th>
                </tr>";

          // Polulate local variables with Port data
          foreach ($_SESSION['all_ports'] as $section) {
            $port_id = $section["port_id"];
            $port_name = $section["port_name"];
            $port_boat_count = $section["port_boat_count"];

            // Display the Port information
            echo "<tr>
                  <form id='port_edit_form_$port_id' method='post' action='commercial_auxiliary_ports_actions.php' onsubmit='return checkpage(\"port_edit_form_$port_id\");'>
                  <input type='hidden' id='port_id' name='port_id' value='$port_id' />
                  <input type='hidden' id='action' name='action' />
                  <td>
                    <div name='port_dsp_$port_id' style='padding-left: 10px;'>$port_name - $port_boat_count <i class='fa-solid fa-ship'></i></div>
                    <div name='port_act_$port_id' style='display:none;'>
                      <input type='text' id='port_name' name='port_name' value='$port_name'
                        fld_req='true'
                        fld_error_message=\"O 'Nome da lota' é requerido.\"
                        fld_regEx='rx_allowAll' />";

            // If "all_boats" array exists
            if (isset($_SESSION['all_boats'])) {
              $boat_coll_side = 'col_50_l'; // initialize the boats column (there are 2) where boats are displayed
              echo "<fieldset>
                      <legend><i class='fa-solid fa-ship'></i>&nbsp;Embarcações associadas: ($port_boat_count)</legend>
                      <div class='row' id='boats_$port_id' name='boats_$port_id'>";
              // loop through $_SESSION['all_boats'] array. OUTPUT: $port_boat['boat_name'] AND $port_boat['boat_id']
              foreach ($_SESSION['all_boats'] as $port_boat) {
                // Initialize $is_checked variable as empty. Value will only change if there is a record in the DB with this value-pair 
                $is_checked = '';
                if (isset($_SESSION['port_boats'])){
                  foreach ($_SESSION['port_boats'] as $key => $pair) {
                    if ($pair['boat_id'] == $port_boat['boat_id'] && $pair['port_id'] == $port_id) {
                      $is_checked = 'checked';
                      unset($_SESSION['port_boats'][$key]);
                      break;
                    }
                  }
                }
                echo "<div class='$boat_coll_side'>
                        <label class='CHK_container' for='port_boat_" . $port_boat['boat_id'] . "'> " . $port_boat['boat_name'] . "
                          <input type='checkbox' id='port_boat_".$port_boat['boat_id']."' name='port_boats_".$port_id."[]' value='".$port_boat['boat_id']."' $is_checked>
                          <span class='checkmark'></span>
                        </label>
                      </div>";
                // if $boat_coll_side === 'col_50_l', set $boat_coll_side to 'col_50_r', otherwise 'col_50_l'
                $boat_coll_side = $boat_coll_side === 'col_50_l' ? 'col_50_r' : 'col_50_l';
              }
              echo "</fieldset>";
            }
            //display the Port Boat-count number
            echo "</div>
                  </td>";
            // Display the action section
              echo "<td style='text-align: right; vertical-align:top;'>
                      <div name='port_dsp_$port_id' style='padding-right: 10px;'>
                        <i class='fa-solid fa-pen-to-square act_icon act_icon_secondary' onclick='toggle_show_hide(\"port_act_$port_id\", \"port_dsp_$port_id\");'></i>
                      </div>
                      <div name='port_act_$port_id' style='display:none; padding-right: 10px;'>
                        <i class='fa-solid fa-trash act_icon act_icon_delete' onclick='if (confirm_delete()){ window.location.href=\"commercial_auxiliary_ports_actions.php?action=delete&port_id=$port_id\";}'></i>&nbsp;&nbsp;
                        <i class='fa-solid fa-floppy-disk act_icon act_icon_primary' onclick='port_edit_form_$port_id.action.value=\"update\"; if (checkpage(\"port_edit_form_$port_id\")){port_edit_form_$port_id.submit();};'></i>&nbsp;&nbsp;
                        <i class='fa-regular fa-circle-xmark act_icon act_icon_delete' onclick='port_edit_form_$port_id.reset(); toggle_show_hide(".'"port_act_'.$port_id.'", "port_dsp_'.$port_id.'"'.");'></i>
                      </div>
                    </td>
                    </form>
                  </tr>";
          }
          // Delete the "ports" array
          unset($_SESSION['ports']);
        } 
      ?>
      <!-- Add new port -->
      <tr>
        <form id="port_add_form" method="post" action="commercial_auxiliary_ports_actions.php" onsubmit="return checkpage('port_add_form');">
        <input type="hidden" id="action" name="action" />
        <td>
          <?php
            // if there already are ports
            if($ports_to_display != 0){ 
              echo "<div name='port_new_dsp'></div>
                    <div name='port_new_act' style='display:none;'>";
            }else{
              echo "<div name='port_new_act'>";
            }
          ?>
            <label for="port_name">* Nome da lota:</label>
            <input type="text" id="port_name" name="port_name"
              fld_req="true"
              fld_error_message="O 'Nome da lota' é requerido."
              fld_regEx="rx_allowAll" />
          
          <?php
            // If "all_boats" array exists
            if (isset($_SESSION['all_boats'])) {
              $boat_coll_side = 'col_50_l'; // initialize the boats column (there are 2) where boats are displayed
              echo "<fieldset>
                      <legend><i class='fa-solid fa-ship'></i>&nbsp;Embarcações:</legend>
                      <div class='row' id='boats_newPort' name='boats_newPort'>";
              foreach ($_SESSION['all_boats'] as $port_boat) {
                echo "<div class='$boat_coll_side'>
                        <label class='CHK_container' for='new_port_boat_" . $port_boat['boat_id'] . "'> " . $port_boat['boat_name'] . "        
                          <input type='checkbox' id='new_port_boat_".$port_boat['boat_id']."' name='port_boats[]' value='".$port_boat['boat_id']. "'>
                          <span class='checkmark'></span>
                        </label>
                      </div>";
                // if $boat_coll_side === 'col_50_l', set $boat_coll_side to 'col_50_r', otherwise 'col_50_l'
                $boat_coll_side = $boat_coll_side === 'col_50_l' ? 'col_50_r' : 'col_50_l';
              }
              unset($_SESSION['all_boats']);
              echo "</div>
                  </fieldset>";
            }
          ?>
          </div>
        </td>
        <td style="text-align: right; vertical-align:top;">
          <?php
            // if there are already ports
            if($ports_to_display != 0){ 
              echo "<div name='port_new_dsp' style='padding-right: 10px;'>
                    <i class='fa-solid fa-circle-plus act_icon act_icon_secondary' onclick='toggle_show_hide(\"port_new_act\", \"port_new_dsp\"); port_add_form.port_name.focus();'> Nova</i>
                    </div>
                    <div name='port_new_act' style='display:none; padding-right: 10px;'>
                    <i class='fa-regular fa-circle-xmark act_icon act_icon_delete' onclick='port_add_form.reset(); toggle_show_hide(\"port_new_act\", \"port_new_dsp\");'></i><br />";
            }else{
              echo "<div name='port_new_act' style='padding-right: 10px;'>";
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
      if (!isset($_SESSION['all_ports'])) {
        // Delete the ports array variable
        unset($_SESSION['all_ports']);
      }
      // include footer file
      include '../app_components/COMP_footer.php';
    ?>
  </body>
</html>