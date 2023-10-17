<?php
  // include code that checks to see if the user is logged in
  include '../app_components/COMP_session_check.php';

  // Function to check if an option in a select box should be selected
  function isOptionSelected($optionValue, $selectedValue) {
      return $optionValue === $selectedValue ? 'SELECTED' : '';
  }

  // Function to check if a CheckBox should be checked
  function isChkBoxChecked($optionValue, $selectedValue) {
    return $optionValue === $selectedValue ? 'CHECKED' : '';
  }
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
      <?php
        if (isset($_SESSION['curr_sp_rep'])) {
          echo "function onload_show_hide_sections() {
            ";
          foreach ($_SESSION['curr_sp_rep'] as $sp_rep) {
            echo "show_hide_section('CAPT_section_".$sp_rep["drs_id"]."', 'none');
            ";
          }
          echo "}";
        }
      ?>
    </script>
  </head>
  <body>
    <div class="top_container">
      <div class="header_sys">
        <img class="logo" src="../intranet/images/cgl_logo.png" alt="Company Logo">
        <span class="nav_buttons_housing">
         <button class="btn_nav" onclick="location.href='../intranet/usr_actions.php?action=logout'"><i class="fa fa-right-from-bracket"></i></button>
         <button class="btn_nav" onclick="location.href='../intranet/dashboard.php'"><i class="fa-solid fa-house"></i></button>
         <button class="btn_nav" onclick="location.href='dpr_actions.php?action=add'"><i class="fa-solid fa-plus"></i></button>
         <button class="btn_nav" onclick="location.href='dpr_actions.php?action=manage'"><i class="fa-solid fa-binoculars"></i></button>
        </span>
      </div>
      <div class="breadcrumbs">
        <span>» Página principal » Relato diário de compras: detalhe</span>
      </div>
    </div>
    <div class="container">
      <?php
        // include file that displays messages and deletes the variables after displaying
        include '../app_components/COMP_msg_display.php';
      ?>
      <?php
        // Get the values of the dayrep_port record
        foreach ($_SESSION['curr_rep'] as $rep) {
          $drp_id = $rep["drp_id"];
          $drp_date = DateTime::createFromFormat("Y-m-d", $rep["drp_date"])->format("d-m-Y");
          $port_name = $rep["port_name"];
          $drp_capture = $rep["drp_capture"];
        }
      ?>
      <form id="drp_add_form" name="drp_add_form" method="post" action="dpr_actions.php" onsubmit="return checkpage(this.id);">
        <input type="hidden" id="action" name="action" />
        <div class="row">
          <div class="col_50_l">
            <label for="drp_date">* Data:</label>
            <input type="text" id="drp_date" name="drp_date" 
              disabled="true"
              value="<?php echo $drp_date;?>"
              fld_req="false"
              fld_error_message="O Campo 'Data' tem que conter uma data válida no formato dd-mm-yyyy."
              fld_regEx="rx_date" />
          </div>
          <div class="col_50_r">
            <label for="port_id">* Lota:</label>
            <input type="text" name="port_id" id="port_id"
              fld_req="false"
              disabled="true"
              value="<?php echo $port_name;?>"
              fld_error_message="Selecione uma opção no campo 'Lota'.">           
            </select>
          </div>
        </div>
        <div class="row">
          <label for="drp_capture">* Houve captura?</label>
          <input type="text" name="drp_capture" id="drp_capture"
            disabled="true"
            fld_req="false"
            disabled="true"
            value="<?php echo $drp_capture;?>"
            fld_error_message="Selecione uma opção no campo 'Houve captura?'.">
        </div>
      </form>
      <!-- #################### Start of Captures Section ######################
           ########### Display one section for each port/day specie ############ -->
      <?php
        if (isset($_SESSION['curr_sp_rep'])) {
          echo "<div class='header_level_1'>
                  <i class='fa-solid fa-cart-shopping'>&nbsp;</i><span>Relatos de compra (".sizeof($_SESSION['curr_sp_rep']).")</span>
                </div>";
          
          foreach ($_SESSION['curr_sp_rep'] as $sp_rep) {
            // set local variables
            $drs_id = $sp_rep["drs_id"];
            $fsp_name = $sp_rep["fsp_name"];
            $drs_quantity = $sp_rep["drs_quantity"];
            $drs_boat_ids = $sp_rep["drs_boat_ids"]; // this contains the list of boat IDs that were checked
            $drs_cgl_bought = $sp_rep["drs_cgl_bought"];
            $drs_cgl_quantity = $sp_rep["drs_cgl_quantity"];
            $drs_cgl_avg_price_kg = str_replace('.', ',', $sp_rep["drs_cgl_avg_price_kg"]); // Replace decimal separator comma with period
            $drs_otr_bought = $sp_rep["drs_otr_bought"];
            $drs_byr_quantity = $sp_rep["drs_byr_quantity"];
            $drs_byr_avg_price_kg = str_replace('.', ',', $sp_rep["drs_byr_avg_price_kg"]); // Replace decimal separator comma with period
            $drs_byr_ids = $sp_rep["drs_byr_ids"]; // this may contain a list of buyer IDs (if they were checked)
            $drs_notes = $sp_rep["drs_notes"];
            $boat_coll_side = 'col_50_l'; // initialize the column (there are 2) where boats are displayed
            $buyer_coll_side = 'col_50_l'; // initialize the column (there are 2) where buyers are displayed
            
            echo "<fieldset>
                  <legend>&nbsp;
                  <i id='edit_icn_$drs_id'
                     name='edit_icn_$drs_id' 
                     style='display: inline-block;' 
                     class='fa-solid fa-pen-to-square act_icon act_icon_secondary' 
                     onclick='cncl_icn_$drs_id.style.display=\"inline-block\"; edit_icn_$drs_id.style.display=\"none\"; show_hide_sections(\"CAPT_section\", \"block\", \"_$drs_id\");'></i>
                  <i id='cncl_icn_$drs_id' 
                     name='cncl_icn_$drs_id' 
                     style='display: none;' 
                     class='fa-regular fa-circle-xmark act_icon act_icon_delete' 
                     onclick='cncl_icn_$drs_id.style.display=\"none\"; edit_icn_$drs_id.style.display=\"inline-block\"; show_hide_sections(\"CAPT_section\", \"none\", \"_$drs_id\");'></i>
                    &nbsp;$fsp_name, $drs_quantity kgs</legend>
                    <div name='CAPT_section_$drs_id' id='CAPT_section_$drs_id' style='display:none;'>
                      <form id='drs_detail_form_$drs_id' name='drs_detail_form_$drs_id' method='post' action='dpr_actions.php' onsubmit='return checkpage(this.id);'>
                      <input type='hidden' id='action' name='action' value='update' />
                      <input type='hidden' id='drs_id' name='drs_id' value='$drs_id' />
                      <input type='hidden' id='drp_id' name='drp_id' value='" . $rep["drp_id"] ."' />
                      <div class='row'>
                        <div class='col_50_l'>
                          <label for='fsp_name'>* Espécie:</label>
                          <input type='text' id='fsp_name' name='fsp_name'
                          fld_req='false'
                          fld_error_message='O Campo \"Espécie\" é requerido.'
                          fld_regEx='rx_allowAll'
                          value='$fsp_name'
                          disabled='true' />
                        </div>
                        <div class='col_50_r'>
                          <label for='drs_quantity'>* kgs:</label>
                          <input type='number' id='drs_quantity' name='drs_quantity'
                          fld_req='true'
                          pattern='\d*'
                          fld_error_message='O Campo \"kgs\" tem que conter um número inteiro, maior que 0.'
                          fld_regEx='rx_nbrNotNegNotZero'
                          value='$drs_quantity' />
                        </div>
                      </div>";
            
            // #################### BOATS SECTION : START ####################
            echo "<div id='boats_container_$drs_id' name='boats_container_$drs_id'>
                    <fieldset>
                      <legend><i class='fa-solid fa-ship'></i>&nbsp;* Embarcações</legend>
                      <div class='row' id='boats_$drs_id' name='boats_$drs_id'>";
            
            $boat_coll_side = 'col_50_l';                 // initialize the boats column (there are 2) where boats are displayed
            $port_boat_arr = explode(',', $drs_boat_ids); // Convert the list of boats into an array
            
            // loop through $_SESSION['all_port_boats'] array
            foreach ($_SESSION['boats_for_port'] as $port_boat) {
              // set local variables
              $boat_id = $port_boat["boat_id"];
              $boat_name = $port_boat["boat_name"];
              $boat_and_drs_id = $drs_id.'_'.$boat_id;
              // If curr boat_id is in the array of selected boat_ids, set $is_checked to 'checked', otherwise ''
              $is_checked = in_array($boat_id, $port_boat_arr) ? 'checked' : ''; 

              echo "<div class='$boat_coll_side'>
                      <label class='CHK_container' for='boat_$boat_and_drs_id'> $boat_name
                      <input type='checkbox' id='boat_$boat_and_drs_id' name='boats[]' value='$boat_id'
                        fld_req='true'
                        fld_error_message='Selecione pelo menos uma embarcação.'
                        $is_checked>
                      <span class='checkmark'></span>
                      </label>
                    </div>";
              // if $boat_coll_side === 'col_50_l', set $boat_coll_side to 'col_50_r', otherwise 'col_50_l'
              $boat_coll_side = $boat_coll_side === 'col_50_l' ? 'col_50_r' : 'col_50_l';
            }

            echo "</div>
                  </fieldset>
                  </div>";
            // #################### BOATS SECTION : END ####################

            // ############ CONGELAGOS PURCHASE SECTION : START ############
            echo "<div class='row'>
                    <label for='drs_cgl_bought_$drs_id'>* CGL comprou?</label>
                    <select name='drs_cgl_bought' id='drs_cgl_bought_$drs_id'
                      fld_req='true'
                      fld_error_message='Selecione uma opção no campo \"CGL comprou?\".'
                      style='display: block;' 
                      onchange='if (this.value==\"Sim\"){ show_hide_sections(\"CGL_section\", \"block\", \"_$drs_id\"); } else { show_hide_sections(\"CGL_section\", \"none\", \"_$drs_id\"); }'>
                      <option value='Sim' " . isOptionSelected('Sim', $drs_cgl_bought) . ">Sim</option>
                      <option value='Não - Preço' " . isOptionSelected('Não - Preço', $drs_cgl_bought) . ">Não - Preço</option>
                      <option value='Não - Qualidade' " . isOptionSelected('Não - Qualidade', $drs_cgl_bought) . ">Não - Qualidade</option>
                      <option value='Não - Mistura' " . isOptionSelected('Não - Mistura', $drs_cgl_bought) . ">Não - Mistura</option>
                      <option value='Não - Outros' " . isOptionSelected('Não - Outros', $drs_cgl_bought) . ">Não - Outros</option>
                    </select>
                  </div>";

            echo "<div class='row' id='CGL_section_$drs_id' style='display:block;'>
                    <div class='col_50_l'>
                      <label for='drs_cgl_quantity'>* kgs:</label>
                      <input type='number' id='drs_cgl_quantity' name='drs_cgl_quantity' style='display: block;'
                      fld_req='true'
                      fld_error_message='O Campo \"kgs\" da Congelagos tem que conter um número inteiro, maior que 0.'
                      fld_regEx='rx_nbrNotNegNotZero'
                      value='$drs_cgl_quantity' />
                    </div>
                    <div class='col_50_r'>
                      <label for='drs_cgl_avg_price_kg'>* Preço médio kg:</label>
                      <input type='text' id='drs_cgl_avg_price_kg' name='drs_cgl_avg_price_kg' style='display: block;'
                      fld_req='true'
                      fld_error_message='O Campo \"Preço\" da Congelagos tem que conter um número maior que 0, inteiro ou decimal.'
                      fld_regEx='rx_nbrDecimalNotNeg'
                      value='$drs_cgl_avg_price_kg' />
                    </div>
                  </div>";
            
            // ############# CONGELAGOS PURCHASE SECTION : END #############

            // ########### OTHER BUYER PURCHASE SECTION : START ############
            echo "<div class='row'>
                    <label for='drs_otr_bought_$drs_id'>* Outros compradores compraram?</label>
                    <select name='drs_otr_bought' id='drs_otr_bought_$drs_id'
                      style='display: block;'
                      fld_req='true'
                      fld_error_message='Selecione uma opção no campo \"Outros compradores compraram?\".'
                      onchange='if (this.value==\"Sim\"){ show_hide_sections(\"OTB_section\", \"block\", \"_$drs_id\"); } else { show_hide_sections(\"OTB_section\", \"none\", \"_$drs_id\"); }'>
                      <option value='Sim'" . isOptionSelected('Sim', $drs_otr_bought) . ">Sim</option>
                      <option value='Não'" . isOptionSelected('Não', $drs_otr_bought) . ">Não</option>
                    </select>
                  </div>";

            echo "<div id='OTB_section_$drs_id' style='display:block;'>
                    <div class='row'>";

            // If "all_buyers" array exists
            if (isset($_SESSION['all_buyers'])) {
              echo "<fieldset>
                    <legend><i class='fa-solid fa-person-circle-plus'></i>&nbsp;* Compradores:</legend>";
              // initialize the div class to display the buyers in two columns
              $byr_coll_side='col_50_l';
              $drs_byr_arr = explode(',', $drs_byr_ids); // Convert the list of boats into an array
              // loop through $_SESSION['all_buyers'] array. OUTPUT: $buyers['byr_name'] AND $buyer['byr_id']
              foreach ($_SESSION['all_buyers'] as $buyer) {
                // If curr byr_id is in the array of byr_ids, set $is_checked to 'checked, otherwise ''
                $is_checked = in_array($buyer['byr_id'], $drs_byr_arr) ? 'checked' : '';
                $drs_and_byr_id = $drs_id.'_'.$buyer['byr_id'];
                echo "<div class='$byr_coll_side'>
                        <label class='CHK_container' for='buyer_$drs_and_byr_id'> $buyer[byr_name]
                        <input type='checkbox' id='buyer_$drs_and_byr_id' name='drs_byr_ids[]' value='$buyer[byr_id]'
                          style='display: none;'
                          fld_req='true'
                          fld_error_message='Selecione pelo menos um comprador.'
                          $is_checked>
                        <span class='checkmark'></span>
                        </label>
                      </div>";
                
                // if $byr_coll_side === 'col_50_l', set $byr_coll_side to 'col_50_r', otherwise 'col_50_l'
                $byr_coll_side = $byr_coll_side === 'col_50_l' ? 'col_50_r' : 'col_50_l';
              }
              echo "</fieldset>";
            }
            echo "</div>";
            echo "<div class='row'>
                    <div class='col_50_l'>
                      <label for='drs_byr_quantity'>* kgs:</label>
                      <input type='number' id='drs_byr_quantity' name='drs_byr_quantity' style='display: block;'
                      fld_req='true'
                      fld_error_message='O Campo \"kgs\" dos Compradores tem que conter um número inteiro, maior que 0.'
                      fld_regEx='rx_nbrNotNegNotZero' 
                      value='$drs_byr_quantity' />
                    </div>
                    <div class='col_50_r'>
                      <label for='drs_byr_avg_price_kg'>* Preço médio kg:</label>
                      <input type='text' id='drs_byr_avg_price_kg' name='drs_byr_avg_price_kg' style='display: block;'
                      fld_req='true'
                      fld_error_message='O Campo \"Preço\" dos Compradores tem que conter um número maior que 0, inteiro ou decimal.'
                      fld_regEx='rx_nbrDecimalNotNeg' 
                      value='$drs_byr_avg_price_kg' />
                    </div>
                  </div>
                </div>";

            // ############ OTHER BUYER PURCHASE SECTION : END #############

            // ################## NOTES SECTION : START ####################
            echo "<div class='row'>
                    <label for='drs_notes'>Notas:</label>
                      <input type='text' id='drs_notes' name='drs_notes' style='display: block;'
                      fld_req='false'
                      fld_error_message='Nada a apontar sobe o campo 'Notas'.'
                      fld_regEx='rx_allowAll' 
                      value='$drs_notes' />
                  </div>";
            // ################### NOTES SECTION : END #####################

            // ############## ACTION BUTTONS SECTION : START ###############
            echo "<div class='form_buttons_housing'>
                    <button type='button' class='btn btn_secondary' onclick='cncl_icn_$drs_id.style.display=\"none\"; edit_icn_$drs_id.style.display=\"inline-block\"; CAPT_section_$drs_id.style.display=\"none\";'><i class='fa-solid fa-ban'></i> Cancela</button>
                    <button type='button' class='btn btn_delete' onclick='if (confirm_delete()){ this.form.action.value=\"delete_drs\"; this.form.submit(); }'><i class='fa-solid fa-trash'></i> Apaga</button>
                    <button class='btn btn_primary' onclick='this.form.action.value=\"update_drs\";'><i class='fa-solid fa-floppy-disk'></i> Guarda</button>
                  </div>";
            // ############### ACTION BUTTONS SECTION : END ################

            echo "</form>
                  </div>
                  </fieldset>";
          }
          unset($_SESSION['curr_sp_rep']);
          unset($_SESSION['all_buyers']);
        }
      ?>
    </div>
    <?php
      // include footer file
      include '../app_components/COMP_footer.php';
    ?>
    <script>
      onload_show_hide_sections();
    </script>
  </body>
</html>
