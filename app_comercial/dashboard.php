<div class='header_level_1'>
  <i class="fa-solid fa-cart-shopping">&nbsp;</i><span>Compras</span>
</div>
<table width='100%'>
  <?php
    if (in_array('3', $_SESSION['usr_permissions'])) {
      // include the Administrator section
      echo "<!--  Auxiliary sistems --->
            <tr><td style='vertical-align:top;'>
              <li> Sistemas auxiliares:</li>
            </td><td style='text-align: right;'>
              <button class='btn btn_secondary' 
                onclick='window.location.href=\"../app_comercial/commercial_auxiliary_boats_actions.php?action=manage\"'
                style='padding-top: 2px; padding-bottom: 2px; width: 150px; margin-bottom: 2px;'>
                <i class='fa-solid fa-ship'></i> Embarcações</button><br>
              <button class='btn btn_secondary' 
                onclick='window.location.href=\"../app_comercial/commercial_auxiliary_ports_actions.php?action=manage\"'
                style='padding-top: 2px; padding-bottom: 2px; width: 150px; margin-bottom: 2px;'>
                <i class='fa-solid fa-anchor'></i> Lotas</button><br>
              <button class='btn btn_secondary' 
                onclick='window.location.href=\"../app_comercial/commercial_auxiliary_fish_species_actions.php?action=manage\"'
                style='padding-top: 2px; padding-bottom: 2px; width: 150px; margin-bottom: 2px;'>
                <i class='fa-solid fa-fish'></i> Espécies</button><br>
              <button class='btn btn_secondary' 
                onclick='window.location.href=\"../app_comercial/commercial_auxiliary_buyers_actions.php?action=manage\"'
                style='padding-top: 2px; padding-bottom: 2px; width: 150px;'>
                <i class='fa-solid fa-person-circle-plus'></i> Compradores</button>
            </td></tr>";
    }
    // include the Daylly Purchase Report section
    echo "<tr><td style='vertical-align:top;'>
            <li> Relato diário de compras:</li>
          </td><td style='text-align: right;'>
            <button class='btn btn_secondary' 
              onclick='window.location.href=\"../app_comercial/dpr_actions.php?action=add\"'
              style='padding-top: 2px; padding-bottom: 2px; width: 73px;'>
              <i class='fa-solid fa-plus'></i> Novo
            </button>
            <button class='btn btn_secondary' 
              onclick='window.location.href=\"../app_comercial/dpr_actions.php?action=manage\"'
              style='padding-top: 2px; padding-bottom: 2px; width: 73px;'>
              <i class='fa-solid fa-screwdriver-wrench'></i> Gerir
            </button>
          </td></tr>";
    ?>
</table>