<?php
// include code that checks to see if the user is logged in
include '../app_components/COMP_session_check.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Relato diário de compras: adicionar</title>
  <link rel="stylesheet" href="../app_components/CSS_style.css">
  <script src="https://kit.fontawesome.com/e49c624ab4.js" crossorigin="anonymous"></script>
  <script src="../app_components/JS_form_validation.js"></script>
  <script src="../app_components/JS_common_functions.js"></script>
  <script>
    // Function to calculate the number of kgs purchased by 
    // other vendors and automatically populate that field
    function calc_otb_amount() {
      const totAmount_fld = document.getElementById('drs_quantity');
      var totAmount_val = parseFloat(totAmount_fld.value),
        cglAmount_val = 0,
        otbAmount_val = 0;

      // If congelagos quantity is diplayed
      if (document.getElementById('drs_cgl_quantity')) {
        const cglAmount_fld = document.getElementById('drs_cgl_quantity');
        cglAmount_val = parseFloat(cglAmount_fld.value);
        // If Congelagos bought nothing, set it to 0
        cglAmount_val = isNaN(cglAmount_val) ? '0' : cglAmount_val;
      }

      otbAmount_fld = document.getElementById('drs_byr_quantity');
      otbAmount_curr_val = parseFloat(otbAmount_fld.value);

      otbAmount_val = isNaN(otbAmount_curr_val) ? totAmount_val - cglAmount_val : otbAmount_curr_val;
      // Update the third input field with the calculated value
      otbAmount_fld.value = isNaN(otbAmount_val) ? '' : otbAmount_val;
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
        <button class="btn_nav" onclick="location.href='dpr_actions.php?action=manage'"><i class="fa-solid fa-binoculars"></i></button>
      </span>
    </div>
    <div class="breadcrumbs">
      <span>» Página principal » Relato diário de compras: adicionar</span>
    </div>
  </div>
  <div class="container">
    <?php
    // include file that displays messages and deletes the variables after displaying
    include '../app_components/COMP_msg_display.php';
    ?>
    <form id="drp_add_form" name="drp_add_form" method="post" action="dpr_actions.php" onsubmit="return checkpage(this.id);">
      <input type="hidden" id="action" name="action" />
      <div class="row">
        <div class="col_50_l">
          <label for="drp_date">* Data:</label>
          <input type="date" id="drp_date" name="drp_date" placeholder="dd-mm-yyyy" value="<?php echo date('Y-m-d'); ?>" fld_req="true" fld_error_message="O Campo 'Data' tem que conter uma data válida no formato dd-mm-yyyy." fld_regEx="rx_date" />
        </div>
        <div class="col_50_r">
          <label for="port_id">* Lota:</label>
          <select name="port_id" id="port_id" fld_req="true" fld_error_message="Selecione uma opção no campo 'Lota'." onchange="if (drp_capture.disabled == true){drp_capture.disabled = false;};">
            <option value='---' selected="true" disabled="true">Escolha uma</option>
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
            }
            ?>
          </select>
        </div>
      </div>
      <div class="row">
        <label for="drp_capture">* Houve captura?</label>
        <select name="drp_capture" id="drp_capture" disabled="true" fld_req="true" fld_error_message="Selecione uma opção no campo 'Houve captura?'." onchange="if (this.value=='Sim'){ show_hide_sections('CAPT_section', 'block', '_new'); } else { show_hide_sections('CAPT_section', 'none', '_new'); }">
          <option value='---' selected="true" default disabled="true" defaultSelected>Escolha uma</option>
          <option value='Sim'>Sim</option>
          <option value='Não - Mau tempo'>Não - Mau tempo</option>
          <option value='Não - Não sairam'>Não - Não sairam</option>
          <option value='Não - Feriado'>Não - Feriado</option>
          <option value='Não - Outros motivos'>Não - Outros motivos</option>
        </select>
      </div>
      <div id="CAPT_section_new" style="display: none;"> <!-- #################### Start of Captures Section ###################### -->
        <div class="row">
          <div class="col_50_l">
            <label for="fsp_id">* Espécie:</label>
            <select name="fsp_id" id="fsp_id" style="display: none;" fld_req="true" fld_error_message="Selecione uma opção no campo 'Espécie'.">
              <option value='---' selected="true" disabled="true" defaultSelected>Escolha uma</option>
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
            <label for="drs_quantity">* kgs:</label>
            <input type="number" id="drs_quantity" name="drs_quantity" value="" style="display: none;" pattern="\d*" fld_req="true" fld_error_message="O Campo 'kgs' tem que conter um número inteiro, maior que 0." fld_regEx="rx_nbrNotNegNotZero" />
          </div>
        </div>

        <!-- #####################################################
               BOATS SECTION
               ##################################################### -->
        <div class="row">
          <fieldset>
            <legend><i class="fa-solid fa-ship"></i>&nbsp;* Embarcações:</legend>
            <div id="boatsSection">
              <!-- Boats are AJAX added here -->
            </div>
          </fieldset>
        </div>

        <div class="row">
          <label for="drs_cgl_bought_new">* CGL comprou?</label>
          <select name="drs_cgl_bought" id="drs_cgl_bought_new" fld_req="true" fld_error_message="Selecione uma opção no campo 'CGL comprou?'." style="display: none;" onchange="if (this.value=='Sim'){ show_hide_sections('CGL_section', 'block', '_new'); } else { show_hide_sections('CGL_section', 'none', '_new'); }">
            <option value='---' selected="true" default disabled="true" defaultSelected>Escolha uma</option>
            <option value='Sim'>Sim</option>
            <option value='Não - Preço'>Não - Preço</option>
            <option value='Não - Qualidade'>Não - Qualidade</option>
            <option value='Não - Mistura'>Não - Mistura</option>
            <option value='Não - Outros'>Não - Outros</option>
          </select>
        </div>

        <!-- CONGELAGOS purchase section -->
        <div class="row" id="CGL_section_new" style="display:none;">
          <div class="col_50_l">
            <label for="drs_cgl_quantity">* kgs:</label>
            <input type="number" id="drs_cgl_quantity" name="drs_cgl_quantity" style="display: none;" fld_req="true" pattern='\d*' fld_error_message="O Campo 'kgs' da Congelagos tem que conter um número inteiro, maior que 0." fld_regEx="rx_nbrNotNegNotZero" />
          </div>
          <div class="col_50_r">
            <label for="drs_cgl_avg_price_kg">* Preço médio kg:</label>
            <input type="text" id="drs_cgl_avg_price_kg" name="drs_cgl_avg_price_kg" style="display: none;" fld_req="true" pattern='\d{1,3}([,.])\d{1,2}' fld_error_message="O Campo 'Preço' da Congelagos tem que conter um número maior que 0, inteiro ou decimal." fld_regEx="rx_nbrDecimalNotNeg" />
          </div>
        </div>

        <!-- ############################################################
              BUYERS purchase section
              ############################################################ -->

        <div class="row">
          <label for="drs_otr_bought">* Outros compradores compraram?</label>
          <select name="drs_otr_bought" id="drs_otr_bought_new" style="display: none;" fld_req="true" fld_error_message="Selecione uma opção no campo 'Outros compradores compraram?'." onchange="if (this.value=='Sim'){ show_hide_sections('OTB_section', 'block', '_new'); calc_otb_amount();} else { show_hide_sections('OTB_section', 'none', '_new'); }">
            <option value='---' selected="true" disabled="true" defaultSelected>Escolha uma</option>
            <option value='Sim'>Sim</option>
            <option value='Não'>Não</option>
          </select>
        </div>

        <div id="OTB_section_new" style="display:none;">
          <div class="row">
            <?php
            // If "all_buyers" array exists
            if (isset($_SESSION['all_buyers'])) {
              echo "<fieldset>
                      <legend><i class='fa-solid fa-person-circle-plus'></i>&nbsp;* Compradores:</legend>";
              // initialize the div class to display the buyers in two columns
              $coll_side = 'col_50_l';
              // loop through $_SESSION['all_buyers'] array. OUTPUT: $buyers['byr_name'] AND $buyer['byr_id']
              foreach ($_SESSION['all_buyers'] as $buyer) {
                echo "<div class='$coll_side'>
                        <label class='CHK_container' for='buyer_$buyer[byr_id]'> $buyer[byr_name]
                        <input type='checkbox' id='buyer_$buyer[byr_id]' name='drs_byr_ids[]' value='$buyer[byr_id]'
                        style='display: none;'
                        fld_req='true'
                        fld_error_message='Selecione pelo menos um comprador.'>
                        <span class='checkmark'></span>
                        </label>
                        </div>";

                if ($coll_side == 'col_50_l') {
                  $coll_side = 'col_50_r';
                } else {
                  $coll_side = 'col_50_l';
                }
              }
              unset($_SESSION['all_buyers']);
              echo "</fieldset>";
            }
            ?>
          </div>

          <div class="row">
            <div class="col_50_l">
              <label for="drs_byr_quantity">* kgs:</label>
              <input type="number" id="drs_byr_quantity" name="drs_byr_quantity" style="display: none;" fld_req="true" pattern='\d*' fld_error_message="O Campo 'kgs' dos Compradores tem que conter um número inteiro, maior que 0." fld_regEx="rx_nbrNotNegNotZero" onclick="calc_otb_amount()" />
            </div>
            <div class="col_50_r">
              <label for="drs_byr_avg_price_kg">* Preço médio kg:</label>
              <input type="text" id="drs_byr_avg_price_kg" name="drs_byr_avg_price_kg" style="display: none;" fld_req="true" pattern='\d{1,3}([,.])\d{1,2}' fld_error_message="O Campo 'Preço' dos Compradores tem que conter um número maior que 0, inteiro ou decimal." fld_regEx="rx_nbrDecimalNotNeg" />
            </div>
          </div>
        </div>
        <div class="row">
          <label for="drs_notes">Notas:</label>
          <input type="text" id="drs_notes" name="drs_notes" style="display: none;" fld_req="false" fld_error_message="Nada a apontar sobe o campo 'Notas'." fld_regEx="rx_allowAll" />
        </div>
      </div> <!-- END of Captures Section -->
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
<!--######################################################
      AJAX script to get boats for Port
    ###################################################### -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script>
  $(function() {
    // Event listener for port select change event
    $('#port_id').change(function() {
      var selectedPort = $(this).val();
      // Make an AJAX request to fetch boats for the selected port
      $.ajax({
        url: 'AJAX_get_boats.php',
        type: 'POST',
        data: {
          port_id: selectedPort
        },
        dataType: 'html',
        success: function(response) {
          // Update the boats section with the fetched boat data
          $('#boatsSection').html(response);
        },
        error: function(xhr, status, error) {
          console.error(xhr.responseText);
        }
      });
    });
  });
</script>

</html>