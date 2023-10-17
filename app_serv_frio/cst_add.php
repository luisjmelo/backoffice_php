<?php
// include code that checks to see if the user is logged in
include '../app_components/COMP_session_check.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Clientes: adicionar</title>
  <link rel="stylesheet" href="../app_components/CSS_style.css">
  <script src="https://kit.fontawesome.com/e49c624ab4.js" crossorigin="anonymous"></script>
  <script src="../app_components/JS_form_validation.js"></script>
  <script src="../app_components/JS_common_functions.js"></script>
  <script>
    function check_cst_page(form) {
      // setup needed variables for password 'typo' verificaion
      var result = true,
        form_obj = document.forms[form],
        field1_val = form_obj.elements['cst_password'].value,
        field2_val = form_obj.elements['cst_password_confirmar'].value;
      // first check all form fields
      if (!checkpage(form)) {
        result = false;
        // form fields are ok. Now check if the password and pwd_confirm fields are the same
      } else if (field1_val !== field2_val) {
        alert("A Senha e a Confirmação necessitam de ser exatamente iguais!");
        form_obj.elements['cst_password_confirmar'].select();
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
      <img class="logo" src="../intranet/images/cgl_logo.png" alt="Company Logo">
      <span class="nav_buttons_housing">
        <button class="btn_nav" onclick="location.href='../intranet/usr_actions.php?action=logout'"><i class="fa fa-right-from-bracket"></i></button>
        <button class="btn_nav" onclick="location.href='../intranet/dashboard.php'"><i class="fa-solid fa-house"></i></button>
        <!-- <button class="btn_nav" onclick="location.href='cst_srch.php'"><i class="fa-solid fa-binoculars"></i></button> -->
      </span>
    </div>
    <div class="breadcrumbs">
      <span>» Página principal » Clientes: adicionar</span>
    </div>
  </div>
  <div class="container">
    <?php
    // include file that displays messages and deletes the variables after displaying
    include '../app_components/COMP_msg_display.php';
    ?>
    <form id="cst_add_form" name="cst_add_form" method="post" action="cst_actions.php" onsubmit="return check_cst_page(this.id);">
      <input type="hidden" id="action" name="action" />
      <div class="row">
        <label for="cst_creation_date">* Data de criação:</label>
        <input type="date" id="cst_creation_date" name="cst_creation_date" placeholder="dd-mm-yyyy" value="<?php echo date('Y-m-d'); ?>" fld_req="true" fld_error_message="O Campo 'Data' tem que conter uma data válida no formato dd-mm-yyyy." fld_regEx="rx_date" />
      </div>
      <div class="row">
        <label for="cst_first_name">* Designação social:</label>
        <input type="text" id="cst_name" name="cst_name" fld_req="true" fld_error_message="A 'Designação social' é requerida." fld_regEx="rx_allowAll" />
      </div>
      <div class="row">
        <div class="col_50_l">
          <label for="cst_nif">* NIF:</label>
          <input type="text" id="cst_nif" name="cst_nif" fld_req="true" fld_error_message="O campo 'NIF' é requerido." fld_regEx="rx_nbr" />
        </div>
        <div class="col_50_r">
          <label for="cst_cae">* CAE:</label>
          <input type="text" id="cst_cae" name="cst_cae" fld_req="true" fld_error_message="O 'CAE' é requerido." fld_regEx="rx_nbr" />
        </div>
      </div>
      <div class="row">
        <label for="cst_comercial_cert_code">* Código de certidão comercial:</label>
        <input type="text" id="cst_comercial_cert_code" name="cst_comercial_cert_code" fld_req="true" fld_error_message="O 'Código de certidão comercial' é requerido." fld_regEx="rx_nbr" />
      </div>
      <div class="row" style="margin-top: 7px; margin-bottom: 7px;">
        <fieldset>
          <legend><i class="fa-solid fa-user-tie"></i> * Tipo de cliente:</legend>
          <div class="row">
            <div class="col_50_l">
              <label class='CHK_container' for='cst_cat_0'>Frio
                <input type='checkbox' id='cst_cat_0' name='cst_cat[]' value='0' fld_req='true' fld_error_message='Selecione pelo menos um Tipo de cliente.'>
                <span class='checkmark'></span>
              </label>
            </div>
            <div class=" col_50_r">
              <label class='CHK_container' for='cst_cat_1'>FSD
                <input type='checkbox' id='cst_cat_1' name='cst_cat[]' value='1' fld_req='true' fld_error_message='Selecione pelo menos um Tipo de cliente.'>
                <span class='checkmark'></span>
              </label>
            </div>
            <div class="col_50_l">
              <label class='CHK_container' for='cst_cat_2'>Commodity
                <input type='checkbox' id='cst_cat_2' name='cst_cat[]' value='2' fld_req='true' fld_error_message='Selecione pelo menos um Tipo de cliente.'>
                <span class='checkmark'></span>
              </label>
            </div>
            <div class=" col_50_r">
              <label class='CHK_container' for='cst_cat_3'>Novos Produtos
                <input type='checkbox' id='cst_cat_3' name='cst_cat[]' value='3' fld_req='true' fld_error_message='Selecione pelo menos um Tipo de cliente.'>
                <span class='checkmark'></span>
              </label>
            </div>
          </div>
        </fieldset>
      </div>
      <div class="row" style="margin-top: 7px; margin-bottom: 7px;">
        <fieldset>
          <legend><i class="fa-solid fa-location-dot"></i> Informação de contacto:</legend>
          <div class="row">
            <label for="cst_street">Rua:</label>
            <input type="text" id="cst_street" name="cst_street" fld_req="false" fld_error_message="Null" fld_regEx="rx_allowAll" />
          </div>
          <div class="row">
            <div class="col_50_l">
              <label for="cst_city">Localidade:</label>
              <input type="text" id="cst_city" name="cst_city" fld_req="false" fld_error_message="Null" fld_regEx="rx_allowAll" />
            </div>
            <div class="col_50_r">
              <label for="cst_postal_code">Código postal:</label>
              <input type="text" id="cst_postal_code" name="cst_postal_code" fld_req="false" fld_error_message="Null" fld_regEx="rx_allowAll" />
            </div>
          </div>
          <div class="row">
            <label for="cst_country">País:</label>
            <input type="text" id="cst_country" name="cst_country" fld_req="false" fld_error_message="Null" fld_regEx="rx_allowAll" />
          </div>
          <div class="row">
            <div class="col_50_l">
              <label for="cst_cnct_gen_email">Email geral:</label>
              <input type="text" id="cst_cnct_gen_email" name="cst_cnct_gen_email" fld_req="false" fld_error_message="O campo 'Email' só pode conter um endereço de email válido." fld_regEx="rx_email" />
            </div>
            <div class="col_50_r">
              <label for="cst_cnct_gen_phone">Telefone geral:</label>
              <input type="text" id="cst_cnct_gen_phone" name="cst_cnct_gen_phone" fld_req="false" fld_error_message="O campo 'Telefone' só pode conter um número de telefone Português." fld_regEx="rx_ptPhone" placeholder="999999999" />
            </div>
          </div>
        </fieldset>
      </div>
      <div class="row" style="margin-bottom: 7px;">
        <fieldset>
          <legend><i class="fa-solid fa-truck-fast"></i> Departamento de logística:</legend>
          <div class="row">
            <label for="cst_cnct_logi_name">Pessoa responsável:</label>
            <input type="text" id="cst_cnct_logi_name" name="cst_cnct_logi_name" fld_req="false" fld_error_message="Null" fld_regEx="rx_allowAll" />
          </div>
          <div class="row">
            <div class="col_50_l">
              <label for="cst_cnct_logi_email">Email:</label>
              <input type="text" id="cst_cnct_logi_email" name="cst_cnct_logi_email" fld_req="false" fld_error_message="O campo 'Email' do departamento de Logística só pode conter um endereço de email válido." fld_regEx="rx_email" />
            </div>
            <div class="col_50_r">
              <label for="cst_cnct_logi_phone">Telefone:</label>
              <input type="text" id="cst_cnct_logi_phone" name="cst_cnct_logi_phone" fld_req="false" fld_error_message="O campo 'Telefone' do departamento de Logística só pode conter um número de telefone Português." fld_regEx="rx_ptPhone" placeholder="999999999" />
            </div>
          </div>
        </fieldset>
      </div>
      <div class="row" style="margin-bottom: 7px;">
        <fieldset>
          <legend><i class="fa-solid fa-euro-sign"></i> Departamento financeiro:</legend>
          <div class="row">
            <label for="cst_cnct_fin_name">Pessoa responsável:</label>
            <input type="text" id="cst_cnct_fin_name" name="cst_cnct_fin_name" fld_req="false" fld_error_message="Null" fld_regEx="rx_allowAll" />
          </div>
          <div class="row">
            <div class="col_50_l">
              <label for="cst_cnct_fin_email">Email:</label>
              <input type="text" id="cst_cnct_fin_email" name="cst_cnct_fin_email" fld_req="false" fld_error_message="O campo 'Email' do departamento financeiro só pode conter um endereço de email válido." fld_regEx="rx_email" />
            </div>
            <div class="col_50_r">
              <label for="cst_cnct_fin_phone">Telefone:</label>
              <input type="text" id="cst_cnct_fin_phone" name="cst_cnct_fin_phone" fld_req="false" fld_error_message="O campo 'Telefone' do departamento financeiro só pode conter um número de telefone Português." fld_regEx="rx_ptPhone" placeholder="999999999" />
            </div>
          </div>
        </fieldset>
      </div>
      <fieldset>
        <legend><i class="fa-solid fa-user-lock"></i> Área reservada:</legend>
        <div class="row">
          <div class="col_50_l">
            <label for="cst_status">* Acesso</label>
            <select name="cst_status" id="cst_status" fld_req="true" fld_error_message="Selecione o 'Acesso' do cliente.">
              <option value='---' selected="true" default disabled="true" defaultSelected>Escolha um</option>
              <option value='1'>Ativo</option>
              <option value='0'>Inativo</option>
              <option value='2'>Pendente</option>
            </select>
          </div>
          <div class="col_50_r">
            <label for="cst_username">Nome de utilizador:</label>
            <input type="text" id="cst_username" name="cst_username" fld_req="false" fld_error_message="" fld_regEx="rx_allowAll" />
          </div>
        </div>
        <div class="row">
          <div class="col_50_l">
            <label for="cst_password">Senha:</label>
            <input type="password" id="cst_password" name="cst_password" fld_req="false" fld_error_message="A 'Senha', para ser válida, tem que conter: Letras maiusculas e minusculas; Numeros e carateres especiais; No mínimo 8 carateres." fld_regEx="rx_password" />
          </div>
          <div class="col_50_r">
            <label for="cst_password_confirmar">Senha (confirmar):</label>
            <input type="password" id="cst_password_confirmar" name="cst_password_confirmar" fld_req="false" fld_error_message="A 'Senha (confirmar)' tem que ser exatamente igual à 'Senha'." fld_regEx="rx_password" />
          </div>
        </div>
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