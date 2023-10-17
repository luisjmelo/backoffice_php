<?php
  session_start();
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Página de Login</title>
    <link rel="stylesheet" href="app_components/CSS_style.css">
    <script src="https://kit.fontawesome.com/e49c624ab4.js" crossorigin="anonymous"></script>
    <script src="app_components/JS_form_validation.js"></script>
  </head>
  <body>
    <div class="top_container">
      <div class="header_sys">
        <img class="logo" src="intranet/images/cgl_logo.png" alt="Company Logo">
      </div>
      <div class="breadcrumbs">
        <span>» Login</span>
      </div>
    </div>
    <div class="container">
      <?php
        // include file that displays messages and deletes the variables after displaying
        include 'app_components/COMP_msg_display.php';
      ?>
      <form id="loginForm" name="loginForm" method="post" action="intranet/usr_actions.php" onsubmit="return checkpage(loginForm);">
        <input type="hidden" id="action" name="action" />
        <div class="row">
          <div class="col_50_l">
            <label for="usr_username">* Nome de utilizador:</label>
            <input type="text" id="usr_username" name="usr_username"
              fld_req="true"
              fld_error_message="O nome de utilizador é requerido."
              fld_regEx="rx_allowAll" />
          </div>
          <div class="col_50_r">
            <label for="usr_password">* Senha:</label>
            <input type="password" id="usr_password" name="usr_password"
              fld_req="true"
              fld_error_message="A 'Senha' é requerida e tem que conter: Letras maiusculas e minusculas; Numeros e carateres especiais; No mínimo 8 carateres."
              fld_regEx="rx_password" />
          </div>
        </div>
        <div class="form_buttons_housing">
          <button class="btn btn_primary" onclick="loginForm.action.value='login';"><i class="fa-solid fa-right-to-bracket"></i> Entrar</button>
        </div>
      </form>
    </div>
    <?php
      // include footer file
      include 'app_components/COMP_footer.php';
    ?>
  </body>
</html>
