<?php
  // include code that checks to see if the user is logged in
  include '../app_components/COMP_session_check.php';
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Utilizadores: localizar</title>
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
         <button class="btn_nav" onclick="location.href='../intranet/usr_actions.php?action=logout'"><i class="fa fa-right-from-bracket" alt="Log out"></i></button>
         <button class="btn_nav" onclick="location.href='../intranet/dashboard.php'"><i class="fa-solid fa-house" alt="Dashboard"></i></button>
         <button class="btn_nav" onclick="location.href='dpr_actions.php?action=add'"><i class="fa-solid fa-plus" alt="Add"></i></button>
        </span>
      </div>
      <div class="breadcrumbs">
        <span>» Página principal » Relato diário de compras: localizar</span>
      </div>
    </div>
    <div class="container">
      <?php
        // include file that displays messages and deletes the variables after displaying
        include '../app_components/COMP_msg_display.php';

        // Initialize displayed_section_count that is later used to  
        // determine if some section headers should be displayed
        $displayed_section_count = 0;

        // ###################################################################################
        // Section that displays the latest dayly purchase records
        // ###################################################################################
        if (isset($_SESSION['latest_day_purchase_reps']) && !isset($_SESSION['day_purchase_rep_results'])) {
            echo "<div class='header_level_1'>
             <i class='fa-regular fa-clock'>&nbsp;</i><span>Últimos relatos</span>
            </div>";
            echo "<table width='100%'>
                      <tr>
                          <th width='25%' style='text-align: left; padding-left: 10px;'>Data</th>
                          <th width='25%' style='text-align: left; padding-left: 10px;'>Lota</th>
                          <th width='30%' style='text-align: left; padding-left: 10px;'>Capturas</th>
                          <th width='10%' style='text-align: left;'>Registos</th>
                          <th width='10%'></th>
                      </tr>";
            
            foreach ($_SESSION['latest_day_purchase_reps'] as $rep) {
              $drp_id = $rep["drp_id"];
              $drp_date = DateTime::createFromFormat("Y-m-d", $rep["drp_date"])->format("d-m-Y");
              $port_name = $rep["port_name"];
              $drp_capture = $rep["drp_capture"];
              if ($drp_capture == 'Sim' && $rep["quantity"] <> ''){
                $drp_capture .=  " - " . $rep["quantity"] . " kg";
              }
              $drp_records = $rep["records"];
            
              echo "<tr>
                      <td>$drp_date</td>
                      <td>$port_name</td>
                      <td>$drp_capture</td>
                      <td style='text-align: left; padding-left: 30px;'>$drp_records</td>
                      <td style='text-align: right; padding-right: 10px;'><i class='fa-solid fa-magnifying-glass act_icon' onclick='window.location.href=\"dpr_actions.php?action=detail&drp_id=$drp_id\"'></i>&nbsp;&nbsp;</td>
                  </tr>";
            }
            // Delete the search results session variable
            unset($_SESSION['latest_day_purchase_reps']);
            $displayed_section_count++;
        }

        // ###################################################################################
        // Section that displays search results, if a search was made and maches were returned
        // ###################################################################################
        if (isset($_SESSION['reps_srch_results'])) {
          echo "<div class='header_level_1'>
           <i class='fa-solid fa-list'>&nbsp;</i><span>Resultados da pesquisa</span>
          </div>";
          echo "<table width='100%'>
                    <tr>
                        <th width='25%' style='text-align: left; padding-left: 10px;'>Data</th>
                        <th width='25%' style='text-align: left; padding-left: 10px;'>Lota</th>
                        <th width='30%' style='text-align: left; padding-left: 10px;'>Capturas</th>
                        <th width='10%' style='text-align: left;'>Registos</th>
                        <th width='10%'></th>
                    </tr>";
          
          foreach ($_SESSION['reps_srch_results'] as $rep) {
            $drp_id = $rep["drp_id"];
            $drp_date = DateTime::createFromFormat("Y-m-d", $rep["drp_date"])->format("d-m-Y");
            $port_name = $rep["port_name"];
            $drp_capture = $rep["drp_capture"];
            if ($drp_capture == 'Sim' && $rep["quantity"] <> ''){
                $drp_capture .=  " - " . $rep["quantity"] . " kg";
            }
            $drp_records = $rep["records"];
          
            echo "<tr>
                    <td>$drp_date</td>
                    <td>$port_name</td>
                    <td>$drp_capture</td>
                    <td style='text-align: left; padding-left: 30px;'>$drp_records</td>
                    <td style='text-align: right; padding-right: 10px;'><i class='fa-solid fa-magnifying-glass act_icon' onclick='window.location.href=\"dpr_actions.php?action=detail&drp_id=$drp_id\"'></i>&nbsp;&nbsp;</td>
                </tr>";
          }
          // Delete the search results session variable
          unset($_SESSION['reps_srch_results']);
          $displayed_section_count++;
        }
        
        if ($displayed_section_count > 0) {
          echo "</table><br />
                <div class='header_level_1'>
                    <i class='fa-solid fa-magnifying-glass'>&nbsp;</i><span>Pesquisa</span>
                </div>";
        }
      ?>
      <form id="usr_add_form" method="post" action="dpr_actions.php">
        <input type="hidden" id="action" name="action" />
        <div class="row">
          <div class="col_50_l">
            <label for="date_crit">Critério de data:</label>
            <select name="date_crit" id="date_crit"
            onchange="if (drp_date.disabled == true){drp_date.disabled = false;}drp_date.focus();">
                <option value='---' selected="true" disabled="true">Possíveis escolhas</option>
                <option value='='>No dia</option>    
                <option value='<'>Antes de</option>
                <option value='>'>Depois de</option>
            </select>
          </div>
          <div class="col_50_r">
            <label for="drp_date">Data:</label>
            <input type="date" id="drp_date" name="drp_date"
                fld_req="false"
                disabled="true"
                fld_error_message="O Campo 'Data' só pode conter uma data válida no formato dd-mm-yyyy."
                fld_regEx="rx_date" />
          </div>
        </div>
        <div class="row">
          <div class="col_50_l">
            <label for="port_id">Lota?</label>
            <select name="port_id" id="port_id"
              fld_req="false"
              fld_error_message="Selecione uma opção no campo 'Lota'.">
              <option value='---' selected="true" disabled="true">Possíveis lotas</option>
              <?php
                $ports_to_display = 0;
                // If "ports" array exists
                if (isset($_SESSION['all_ports'])) {
                  $ports_to_display = 1;
                  // Polulate local variables with Port data
                  foreach ($_SESSION['all_ports'] as $port) {
                    $port_id = $port["port_id"];
                    $port_name = $port["port_name"];
                    echo "<option value='$port_id'>$port_name</option>";
                  }
                  unset($_SESSION['all_ports']);
                }
              ?>
            </select>
          </div>
          <div class="col_50_r">
            <label for="drp_capture">Houve captura?</label>
            <select name="drp_capture" id="drp_capture"
                fld_req="false"
                fld_error_message="Selecione uma opção no campo 'Houve captura?'."
                onchange="if (this.value=='Sim'){ show_hide_section('CAPT_section', 'block'); } else { show_hide_section('CAPT_section', 'none'); }">
                <option value='---' selected="true" default disabled="true">Possíveis escolhas</option>
                <option value='Sim'>Sim</option>
                <option value='Não - Mau tempo'>Não - Mau tempo</option>
                <option value='Não - Não sairam'>Não - Não sairam</option>
                <option value='Não - Feriado'>Não - Feriado</option>
                <option value='Não - Outros motivos'>Não - Outros motivos</option>
            </select>
          </div>
        </div>
        <div class="row">
          <div class="col_50_l">
            <label for="fsp_id">Espécie:</label>
            <select name="fsp_id" id="fsp_id"
                fld_req="false"
                fld_error_message="Selecione uma opção no campo 'Espécie'.">
                <option value='---' selected="true" disabled="true">Possíveis escolhas</option>
                <?php
                  $species_to_display = 0;
                  // If "species" array exists
                  if (isset($_SESSION['all_species'])) {
                    $species_to_display = 1;
                    // Populate local variables with Port data
                    foreach ($_SESSION['all_species'] as $specie) {
                      $fsp_id = $specie["fsp_id"];
                      $fsp_name = $specie["fsp_name"];
                      echo "<option value='$fsp_id'>$fsp_name</option>";
                    }
                    unset($_SESSION['all_species']);
                  }
                ?>
            </select>
          </div>
          <div class="col_50_r">
            <label for="drs_cgl_bought">CGL comprou?</label>
            <select name="drs_cgl_bought" id="drs_cgl_bought"
              fld_req="false"
              fld_error_message="Selecione uma opção no campo 'CGL comprou?'.">
              <option value='---' selected="true" default disabled="true">Possíveis escolhas</option>
              <option value='Sim'>Sim</option>
              <option value='Não - Preço'>Não - Preço</option>
              <option value='Não - Qualidade'>Não - Qualidade</option>
              <option value='Não - Mistura'>Não - Mistura</option>
              <option value='Não - Outros'>Não - Outros</option>
            </select>
          </div>
        </div>
        <div class="form_buttons_housing">
          <button class="btn btn_secondary" onclick="this.form.action.value='search';"><i class="fa-solid fa-magnifying-glass"></i> Procura</button>
        </div>
      </form>
    </div>
    <?php
      // include footer file
      include '../app_components/COMP_footer.php';
    ?>
  </body>
</html>