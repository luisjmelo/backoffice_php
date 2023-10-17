<?php
  // include code that checks to see if the user is logged in
  include '../app_components/COMP_session_check.php';
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>compradors: Gerir</title>
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
        <span>» Página principal » Compradores: gerir</span>
      </div>
    </div>
    <div class="container">
      <?php
        // include file that displays messages and deletes the variables after displaying
        include '../app_components/COMP_msg_display.php';
        echo "<table width='100%'>";
        $buyers_to_display = 0;
        // If "buyers" array exists
        if (isset($_SESSION['all_buyers'])) {
          $buyers_to_display = 1;
          // Create the table to contain the buyers
          echo "<tr>
                <th width='50%' style='text-align: left; padding-left: 10px;'>* Nome</th>
                <th width='30%' style='text-align: right; padding-right: 10px;'>Ação</th>
                </tr>";

          // Polulate local variables with buyer data
          foreach ($_SESSION['all_buyers'] as $section) {
            $byr_id = $section["byr_id"];
            $byr_name = $section["byr_name"];

            // Now display the existing buyers
            echo "<tr>
                  <form id='buyer_edit_form_$byr_id' method='post' action='commercial_auxiliary_buyers_actions.php' onsubmit='return checkpage(\"buyer_edit_form_$byr_id\");'>
                  <input type='hidden' id='byr_id' name='byr_id' value='$byr_id' />
                  <input type='hidden' id='action' name='action' />
                  <td>
                    <div name='buyer_dsp_$byr_id' style='padding-left: 10px;'>$byr_name</div>
                    <div name='buyer_act_$byr_id' style='display:none;'>
                      <input type='text' id='byr_name' name='byr_name' value='$byr_name'
                        fld_req='true'
                        fld_error_message='O Nome do comprador é requerido.'
                        fld_regEx='rx_allowAll' />
                    </div>
                  </td>
                  <td style='text-align: right; vertical-align:top;'>
                    <div name='buyer_dsp_$byr_id' style='padding-right: 10px;'>
                      <i class='fa-solid fa-pen-to-square act_icon act_icon_secondary' onclick='toggle_show_hide(\"buyer_act_$byr_id\", \"buyer_dsp_$byr_id\");'></i>
                    </div>
                    <div name='buyer_act_$byr_id' style='display:none; padding-right: 10px;'>
                      <i class='fa-solid fa-trash act_icon act_icon_delete' onclick='if (confirm_delete()){ window.location.href=\"commercial_auxiliary_buyers_actions.php?action=delete&byr_id=$byr_id\";}'></i>&nbsp;&nbsp;
                      <i class='fa-solid fa-floppy-disk act_icon act_icon_primary' onclick='buyer_edit_form_$byr_id.action.value=\"update\"; if (checkpage(\"buyer_edit_form_$byr_id\")){buyer_edit_form_$byr_id.submit();};'></i>&nbsp;&nbsp;
                      <i class='fa-regular fa-circle-xmark act_icon act_icon_delete' onclick='buyer_edit_form_$byr_id.reset(); toggle_show_hide(".'"buyer_act_'.$byr_id.'", "buyer_dsp_'.$byr_id.'"'.");'></i>
                    </div>
                  </td>
                  </form>
                  </tr>";
          }
          // Delete the "buyers" array
          unset($_SESSION['all_buyers']);
        } 
      ?>
      <!-- Add new buyer -->
      <tr>
        <form id="buyer_add_form" method="post" action="commercial_auxiliary_buyers_actions.php" onsubmit="return checkpage('buyer_add_form');">
        <input type="hidden" id="action" name="action" />
        <td>
          <?php
            // if there are already buyers
            if($buyers_to_display != 0){ 
              echo "<div name='buyer_new_dsp'></div><div name='buyer_new_act' style='display:none;'>";
            }else{
              echo "<div name='buyer_new_act'>";
            }
          ?>
            <label for="byr_name">* Nome do comprador:</label>
            <input type="text" id="byr_name" name="byr_name"
              fld_req="true"
              fld_error_message="O 'Nome do comprador' é requerido."
              fld_regEx="rx_allowAll" />
          </div>
        </td>
        <td style="text-align: right;">
          <?php
            // if there are already buyers
            if($buyers_to_display != 0){ 
              echo "<div name='buyer_new_dsp' style='padding-right: 10px;'>
                    <i class='fa-solid fa-circle-plus act_icon act_icon_secondary' onclick='toggle_show_hide(\"buyer_new_act\", \"buyer_new_dsp\"); buyer_add_form.byr_name.focus();'> Novo</i>
                    </div>
                    <div name='buyer_new_act' style='display:none; padding-right: 10px;'>
                    <i class='fa-regular fa-circle-xmark act_icon act_icon_delete' onclick='buyer_add_form.reset(); toggle_show_hide(\"buyer_new_act\", \"buyer_new_dsp\");'></i><br />";
            }else{
              echo "<div name='buyer_new_act' style='padding-right: 10px;'>";
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
      if (!isset($_SESSION['all_buyers'])) {
        // Delete the buyers array
        unset($_SESSION['all_buyers']);
      }
      // include footer file
      include '../app_components/COMP_footer.php';
    ?>
  </body>
</html>