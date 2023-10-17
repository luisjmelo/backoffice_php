<?php
// include code that checks to see if the user is logged in
include '../app_components/COMP_session_check.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Utilizadores: adicionar</title>
  <link rel="stylesheet" href="../app_components/CSS_style.css">
  <script src="https://kit.fontawesome.com/e49c624ab4.js" crossorigin="anonymous"></script>
  <script src="../app_components/JS_form_validation.js"></script>
  <script src="../app_components/JS_common_functions.js"></script>
  <script>
    function check_sys_usr_page(form) {
      // setup needed variables for password 'typo' verificaion
      var result = true,
        form_obj = document.forms[form],
        field1_val = form_obj.elements['usr_password'].value,
        field2_val = form_obj.elements['usr_password_confirm'].value;
      // first check all for fields
      if (!checkpage(form)) {
        result = false;
        // form fields are ok. Now check if the password and pwd_confirm fields are the same
      } else if (field1_val !== field2_val) {
        alert("A Senha e a Confirmação necessitam de ser exatamente iguais!");
        form_obj.elements['usr_password_confirm'].select();
        result = false;
      }
      // if there were no errors, returs true, otherwise false
      return result;
    }
  </script>
</head>

<body>
  <div class="top_container">
    <div class="header_sys">
      <img class="logo" src="images/cgl_logo.png" alt="Company Logo">
      <span class="nav_buttons_housing">
        <button class="btn_nav" onclick="location.href='usr_actions.php?action=logout'"><i class="fa fa-right-from-bracket"></i></button>
        <button class="btn_nav" onclick="location.href='dashboard.php'"><i class="fa-solid fa-house"></i></button>
        <button class="btn_nav" onclick="location.href='usr_srch.php'"><i class="fa-solid fa-binoculars"></i></button>
      </span>
    </div>
    <div class="breadcrumbs">
      <span>» Página principal » Utilizadores: adicionar</span>
    </div>
  </div>
  <div class="container">
    <?php
    // include file that displays messages and deletes the variables after displaying
    include '../app_components/COMP_msg_display.php';
    ?>
    <form id="usr_add_form" name="usr_add_form" method="post" action="usr_actions.php" onsubmit="return check_sys_usr_page(this.id);">
      <input type="hidden" id="action" name="action" />
      <div class="row">
        <div class="col_50_l">
          <label for="usr_first_name">* Nome próprio:</label>
          <input type="text" id="usr_first_name" name="usr_first_name" fld_req="true" fld_error_message="O 'Nome próprio' é requerido." fld_regEx="rx_allowAll" />
        </div>
        <div class="col_50_r">
          <label for="usr_last_name">* Apelido:</label>
          <input type="text" id="usr_last_name" name="usr_last_name" fld_req="true" fld_error_message="O 'Apelido' é requerido." fld_regEx="rx_allowAll" />
        </div>
      </div>
      <label for="usr_email">* Email:</label>
      <input type="text" id="usr_email" name="usr_email" fld_req="true" fld_error_message="O campo 'Email' é requerido." fld_regEx="rx_email" />
      <div class="row">
        <div class="col_50_l">
          <label for="usr_phone">Telefone:</label>
          <input type="text" id="usr_phone" name="usr_phone" fld_req="false" fld_error_message="O campo 'Telefone' só pode conter um número de telefone Português." fld_regEx="rx_ptPhone" placeholder="999999999" />
        </div>
        <div class="col_50_r">
          <label for="usr_birthdate">Data de nascimento:</label>
          <input type="date" id="usr_birthdate" name="usr_birthdate" fld_req="false" fld_error_message="O Campo 'Data de nascimento' só pode conter uma data válida no formato dd-mm-yyyy." fld_regEx="rx_date" />
        </div>
      </div>
      <fieldset>
        <legend><i class="fa-solid fa-shield-halved"></i>Acesso ao sistema:</legend>
        <div class="row">
          <div class="col_50_l">
            <label for="usr_username">* Nome de utilizador:</label>
            <input type="text" id="usr_username" name="usr_username" fld_req="true" fld_error_message="O 'Nome de utilizador' é requerido." fld_regEx="rx_allowAll" />
          </div>
          <div class="col_50_r">
            <label for="usr_status">* Estado:</label>
            <div class="row">
              <div class="col_50_l" style="width: 45%;">
                <label class="RDB_container">Ativo
                  <input type="radio" name="usr_status" value="1" fld_req="true" fld_error_message="O 'Estado' do utilizador é requerido." checked="checked">
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
        <div class="row">
          <div class="col_50_l">
            <label for="usr_password">* Senha:</label>
            <input type="password" id="usr_password" name="usr_password" fld_req="true" fld_error_message="A 'Senha' é requerida e tem que conter: Letras maiusculas e minusculas; Numeros e carateres especiais; No mínimo 8 carateres." fld_regEx="rx_password" />
          </div>
          <div class="col_50_r">
            <label for="usr_password_confirm">* Senha (confirmar):</label>
            <input type="password" id="usr_password_confirm" name="usr_password_confirm" fld_req="true" fld_error_message="A 'Senha (confirmar)' é requerida e tem que ser exatamente igual à senha." fld_regEx="rx_password" />
          </div>
        </div>
      </fieldset>
      <br />
      <fieldset>
        <legend><i class="fa-solid fa-key"></i>&nbsp;Permissões:</legend>
        <?php
        $curr_mdl_number = '';
        // If "module permissions" array exists
        if (isset($_SESSION['mdl_prms'])) {
          $coll_side = 'col_50_l'; // initialize the column (there are 2) where permissions are displayed
          foreach ($_SESSION['mdl_prms'] as $mdl_prm) {
            // m.mdl_number, m.mdl_name, p.prm_id, p.prm_name
            if ($curr_mdl_number != $mdl_prm["mdl_number"]) {
              if ($curr_mdl_number != "") {
                echo "</div>";
              }
              echo "<div class='header_level_2' style='margin-bottom: 4px;'>
                      <i class='fa-solid fa-cubes'></i><span>&nbsp;&nbsp;" . $mdl_prm['mdl_name'] . " (" . $mdl_prm['mdl_number'] . ")</span>
                    </div>
                  <div class='row'>";
              $curr_mdl_number = $mdl_prm['mdl_number'];
              $coll_side = 'col_50_l'; // initialize the column (there are 2) where permissions are displayed
            }
            echo "<div class='$coll_side'>
                    <label class='CHK_container' for='usr_prm_" . $mdl_prm['prm_id'] . "'> " . $mdl_prm['prm_name'] . "
                      <input type='checkbox' id='usr_prm_" . $mdl_prm['prm_id'] . "' name='usr_pers[]' value='" . $mdl_prm['prm_id'] . "'>
                      <span class='checkmark'></span>
                    </label>
                  </div>";
            // if $coll_side === 'col_50_l', set $coll_side to 'col_50_r', otherwise 'col_50_l'
            $coll_side = $coll_side === 'col_50_l' ? 'col_50_r' : 'col_50_l';
          }
          unset($_SESSION['mdl_prms']);
        }
        ?>
      </fieldset>
      <div class="form_buttons_housing">
        <button class="btn btn_primary" onclick="this.form.action.value='save';"><i class="fa-solid fa-floppy-disk"></i> Guarda</button>
      </div>
    </form>
  </div>
  <?php
  // include footer file
  include '../app_components/COMP_footer.php';
  ?>
</body>

</html>