<?php
  // include code that checks to see if the user is logged in
  include '../app_components/COMP_session_check.php';
?>

<?php
  // If "modules" Session array exists
  if (isset($_SESSION['module'])) {
    // Polulate local variables with user data
    foreach ($_SESSION['module'] as $module) {
      $mdl_id = $module["mdl_id"];
      $mdl_number = $module["mdl_number"];
      $mdl_name = $module["mdl_name"];
    }
    // Delete the "modules" Session array
    unset($_SESSION['module']);
  } else {
    // Send user to the target page with the message
    $_SESSION['message'] = "Não há um registo para esse módulo. Pode tentar novamente!";
    $_SESSION['message_type'] = "msg_error";
    $_SESSION['message_icon'] = "<i class='fa-solid fa-circle-exclamation'></i>";
    header('Location: mdl_srch.php');
    exit();
  }
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Module Detalhes</title>
    <link rel="stylesheet" href="../app_components/CSS_style.css">
    <script src="https://kit.fontawesome.com/e49c624ab4.js" crossorigin="anonymous"></script>
    <script src="../app_components/JS_form_validation.js"></script>
    <script src="../app_components/JS_common_functions.js"></script>
  </head>
  <!-- <body onload="load_permissions()"> -->
  <body>
    <div class="top_container">
      <div class="header_sys">
        <img class="logo" src="images/cgl_logo.png" alt="Company Logo">
        <span class="nav_buttons_housing">
         <button class="btn_nav" onclick="location.href='usr_actions.php?action=logout'"><i class="fa fa-right-from-bracket" alt="Sair"></i></button>
         <button class="btn_nav" onclick="location.href='dashboard.php'"><i class="fa-solid fa-house" alt="Página principal"></i></button>
         <button class="btn_nav" onclick="location.href='mdl_actions.php?action=manage'"><i class="fa-solid fa-arrow-left" alt="Cancel"></i></button>
        </span>
      </div>
      <div class="breadcrumbs">
        <span>» Página principal » Módulos: detalhe</span>
      </div>
    </div>
    <div class="container">

      <?php
        // include file that displays messages and deletes the variables after displaying
        include '../app_components/COMP_msg_display.php';
      ?>

      <form id="mdl_detail_form" method="post" action="mdl_actions.php" onsubmit="return checkpage('mdl_detail_form');">
        <input type="hidden" id="action" name="action" />
        <input type="hidden" id="mdl_id" name="mdl_id" value="<?php echo $mdl_id; ?>" />
        <div class="row">
          <div class="col_50_l">
            <label for="mdl_number">* Número do módulo:</label>
            <input type="text" id="mdl_number" name="mdl_number" value="<?php echo $mdl_number; ?>"
              fld_req="true"
              fld_error_message="O 'Número do módulo' é requerido. Não pode conter letras."
			        fld_regEx="rx_nbr" />
          </div>
          <div class="col_50_r">
            <label for="mdl_name">* Nome do módulo:</label>
            <input type="text" id="mdl_name" name="mdl_name" value="<?php echo $mdl_name; ?>"
              fld_req="true"
              fld_error_message="O 'Nome do módulo' é requerido."
              fld_regEx="rx_allowAll" />
          </div>
        </div>
        <div class="form_buttons_housing">
          <button class="btn btn_cancel" onclick="mdl_detail_form.action.value='manage';"><i class="fa-solid fa-arrow-left"></i> Cancela</button>
          <button class="btn btn_delete" onclick="if (confirm_delete()){ this.form.action.value='delete'; mdl_detail_form.submit();}"><i class="fa-solid fa-trash"></i> Apaga</button>
          <button class="btn btn_primary" onclick="mdl_detail_form.action.value='update';"><i class="fa-solid fa-floppy-disk"></i> Guarda</button>
        </div>
      </form>
      <p>
      <div class='header_level_1'>
        <i class="fa-solid fa-key"></i><span> Permissões do módulo</span>
      </div>
      <table width='100%'>
      <?php
        $prm_to_display = 0;
        // If "module permissions" array exists
        if (isset($_SESSION['module_permissions'])) {
          $prm_to_display = 1;
          // Create the table to contain the module permissions
          echo "<tr>
                <th width='20%'>Número</th>
                <th width='50%'>Nome</th>
                <th width='30%' style='text-align: right; padding-right: 10px;'>Ação</th>
                </tr>";

          // Polulate local variables with user data
          foreach ($_SESSION['module_permissions'] as $section) {
            $prm_id = $section["prm_id"];
            $prm_name = $section["prm_name"];

            // Now display the existing module permissions
            echo "<tr>
                  <form id='prm_edit_form_$prm_id' method='post' action='prm_actions.php' onsubmit='return checkpage(\"prm_edit_form_$prm_id\");'>
                  <input type='hidden' id='mdl_id' name='mdl_id' value='$mdl_id' />
                  <input type='hidden' id='prm_id' name='prm_id' value='$prm_id' />
                  <input type='hidden' id='action' name='action' />
                  <td style='padding-right: 10px;'>
                  <div name='prm_dsp_$prm_id'><li>$prm_id</div>
                  <div name='prm_act_$prm_id' style='display:none;'><li>$prm_id</div>
                  </td>
                  <td>
                  <div name='prm_dsp_$prm_id'>$prm_name</div>
                  <div name='prm_act_$prm_id' style='display:none;'>
                  <input type='text' id='prm_name' name='prm_name' value='$prm_name'
                  fld_req='true'
                  fld_error_message='O Nome da permissão é requerido.'
                  fld_regEx='rx_allowAll' />
                  </div>
                  </td>
                  <td style='text-align: right;'>
                  <div name='prm_dsp_$prm_id' style='padding-right: 10px;'>
                  <i class='fa-solid fa-pen-to-square act_icon act_icon_secondary' onclick='toggle_show_hide(\"prm_act_$prm_id\", \"prm_dsp_$prm_id\");'></i>&nbsp;&nbsp;
                  <i class='fa-solid fa-trash act_icon act_icon_delete' onclick='if (confirm_delete()){ window.location.href=\"prm_actions.php?action=delete&prm_id=$prm_id&mdl_id=$mdl_id\";}'></i>
                  </div>
                  <div name='prm_act_$prm_id' style='display:none; padding-right: 10px;'>
                  <i class='fa-regular fa-circle-xmark act_icon act_icon_delete' onclick='prm_edit_form_$prm_id.reset(); toggle_show_hide(".'"prm_act_'.$prm_id.'", "prm_dsp_'.$prm_id.'"'.");'></i>&nbsp;&nbsp;
                  <i class='fa-solid fa-floppy-disk act_icon act_icon_primary' onclick='prm_edit_form_$prm_id.action.value=\"update\"; if (checkpage(\"prm_edit_form_$prm_id\")){prm_edit_form_$prm_id.submit();};'></i>
                  </div>
                  </td>
                  </form>
                  </tr>";
          }
          // Delete the "module_permissions" array
          unset($_SESSION['module_permissions']);
        } 
      ?>
      <!-- Add new Module Permission -->
      <tr>
        <form id="prm_add_form" method="post" action="prm_actions.php" onsubmit="return checkpage('prm_add_form');">
        <input type="hidden" id="mdl_id" name="mdl_id" value="<?php echo $mdl_id; ?>" />
        <input type="hidden" id="action" name="action" />
        <td style="padding-right: 10px;">
          <?php
            // if there are already permissions for this module
            if($prm_to_display != 0){ 
              echo "<div name='prm_new_dsp'></div><div name='prm_new_act' style='display:none;'></div>";
            }else{
              echo "<div name='prm_new_act'></div>";
            }
          ?>
        </td>
        <td>
          <?php
            // if there are already permissions for this module
            if($prm_to_display != 0){ 
              echo "<div name='prm_new_dsp'></div><div name='prm_new_act' style='display:none;'>";
            }else{
              echo "<div name='prm_new_act'>";
            }
          ?>
            <label for="prm_name">* Nome da permissão:</label>
            <input type="text" id="prm_name" name="prm_name"
              fld_req="true"
              fld_error_message="O 'Nome da permissão' é requerido."
              fld_regEx="rx_allowAll" />
          </div>
        </td>
        <td style="text-align: right;">
          <?php
            // if there are already permissions for this module
            if($prm_to_display != 0){ 
              echo "<div name='prm_new_dsp' style='padding-right: 10px;'>
                    <i class='fa-solid fa-circle-plus act_icon act_icon_secondary' onclick='toggle_show_hide(\"prm_new_act\", \"prm_new_dsp\"); prm_add_form.prm_name.focus();'> Novo</i>
                    </div>
                    <div name='prm_new_act' style='display:none; padding-right: 10px;'>
                    <i class='fa-regular fa-circle-xmark act_icon act_icon_delete' onclick='prm_add_form.reset(); toggle_show_hide(\"prm_new_act\", \"prm_new_dsp\");'></i><br />";
            }else{
              echo "<div name='prm_new_act' style='padding-right: 10px;'>";
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
      // include footer file
      include '../app_components/COMP_footer.php';
    ?>
  </body>
</html>