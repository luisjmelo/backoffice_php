<?php
  // include code that checks to see if the user is logged in
  include '../app_components/COMP_session_check.php';
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Espécies: Gerir</title>
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
        <span>» Página principal » Espécies: gerir</span>
      </div>
    </div>
    <div class="container">
      <?php
        // include file that displays messages and deletes the variables after displaying
        include '../app_components/COMP_msg_display.php';
        echo "<table width='100%'>";
        $species_to_display = 0;
        // If "Species" array exists
        if (isset($_SESSION['all_species'])) {
          $species_to_display = 1;
          // Create the table to contain the species
          echo "<tr>
                <th width='40%' style='text-align: left; padding-left: 5px;'>* Espécie</th>
                <th width='40%' style='text-align: left; padding-left: 5px;'>* Nome científico</th>
                <th width='20%' style='text-align: right; padding-right: 5px;'>Ação</th>
                </tr>";

          // Polulate local variables with species data
          foreach ($_SESSION['all_species'] as $section) {
            $fsp_id = $section["fsp_id"];
            $fsp_name = $section["fsp_name"];
            $fsp_scientific_name = $section["fsp_scientific_name"];

            // Now display the existing species
            echo "<tr>
                  <form id='specie_edit_form_$fsp_id' method='post' action='commercial_auxiliary_fish_species_actions.php' onsubmit='return checkpage(\"specie_edit_form_$fsp_id\");'>
                  <input type='hidden' id='fsp_id' name='fsp_id' value='$fsp_id' />
                  <input type='hidden' id='action' name='action' />
                  <td>
                    <div name='specie_dsp_$fsp_id' style='padding-left: 10px;'>$fsp_name</div>
                    <div name='specie_act_$fsp_id' style='display:none; padding-right: 10px;'>
                      <input type='text' id='fsp_name' name='fsp_name' value='$fsp_name'
                        fld_req='true'
                        fld_error_message='O Nome da espécie é requerido.'
                        fld_regEx='rx_allowAll' />
                    </div>
                  </td>
                  <td>
                    <div name='specie_dsp_$fsp_id'>$fsp_scientific_name</div>
                    <div name='specie_act_$fsp_id' style='display:none;'>
                      <input type='text' id='fsp_scientific_name' name='fsp_scientific_name' value='$fsp_scientific_name'
                        fld_req='true'
                        fld_error_message='O Nome científico da espécie é requerido.'
                        fld_regEx='rx_allowAll' />
                    </div>
                  </td>
                  <td style='text-align: right; vertical-align:top;'>
                    <div name='specie_dsp_$fsp_id' style='padding-right: 5px;'>
                      <i class='fa-solid fa-pen-to-square act_icon act_icon_secondary' onclick='toggle_show_hide(\"specie_act_$fsp_id\", \"specie_dsp_$fsp_id\");'></i>
                    </div>
                    <div name='specie_act_$fsp_id' style='display:none; padding-right: 5px;'>
                      <i class='fa-solid fa-trash act_icon act_icon_delete' onclick='if (confirm_delete()){ window.location.href=\"commercial_auxiliary_fish_species_actions.php?action=delete&fsp_id=$fsp_id\";}'></i>&nbsp;&nbsp;
                      <i class='fa-solid fa-floppy-disk act_icon act_icon_primary' onclick='specie_edit_form_$fsp_id.action.value=\"update\"; if (checkpage(\"specie_edit_form_$fsp_id\")){specie_edit_form_$fsp_id.submit();};'></i>&nbsp;&nbsp;
                      <i class='fa-regular fa-circle-xmark act_icon act_icon_delete' onclick='specie_edit_form_$fsp_id.reset(); toggle_show_hide(".'"specie_act_'.$fsp_id.'", "specie_dsp_'.$fsp_id.'"'.");'></i>
                    </div>
                  </td>
                  </form>
                  </tr>";
          }
          // Delete the "species" array
          unset($_SESSION['all_species']);
        } 
      ?>
      <!-- Add new specie -->
      <tr>
        <form id="specie_add_form" method="post" action="commercial_auxiliary_fish_species_actions.php" onsubmit="return checkpage('specie_add_form');">
        <input type="hidden" id="action" name="action" />
        <td>
          <?php
            // if there are already species
            if($species_to_display != 0){ 
              echo "<div name='specie_new_dsp'></div><div name='specie_new_act' style='display:none; padding-right: 10px;'>";
            }else{
              echo "<div name='specie_new_act' style='padding-right: 10px;'>";
            }
          ?>
            <label for="fsp_name">* Espécie:</label>
            <input type="text" id="fsp_name" name="fsp_name"
              fld_req="true"
              fld_error_message="O 'Nome da espécie' é requerido."
              fld_regEx="rx_allowAll" />
          </div>
        </td>
        <td>
          <?php
            // if there are already species
            if($species_to_display != 0){ 
              echo "<div name='specie_new_dsp'></div><div name='specie_new_act' style='display:none;'>";
            }else{
              echo "<div name='specie_new_act' style='padding-right: 10px;'>";
            }
          ?>
            <label for="fsp_scientific_name">* Nome científico:</label>
            <input type="text" id="fsp_scientific_name" name="fsp_scientific_name"
              fld_req="true"
              fld_error_message="O 'Nome científico' é requerido."
              fld_regEx="rx_allowAll" />
          </div>
        </td>
        <td style="text-align: right;">
          <?php
            // if there are already species
            if($species_to_display != 0){ 
              echo "<div name='specie_new_dsp' style='padding-right: 5px;'>
                    <i class='fa-solid fa-circle-plus act_icon act_icon_secondary' onclick='toggle_show_hide(\"specie_new_act\", \"specie_new_dsp\"); specie_add_form.fsp_name.focus();'> Nova</i>
                    </div>
                    <div name='specie_new_act' style='display:none; padding-right: 5px;'>
                    <i class='fa-regular fa-circle-xmark act_icon act_icon_delete' onclick='specie_add_form.reset(); toggle_show_hide(\"specie_new_act\", \"specie_new_dsp\");'></i><br />";
            }else{
              echo "<div name='specie_new_act' style='padding-right: 5px;'>";
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
      if (!isset($_SESSION['all_species'])) {
        // Delete the species array variable
        unset($_SESSION['all_species']);
      }
      // include footer file
      include '../app_components/COMP_footer.php';
    ?>
  </body>
</html>