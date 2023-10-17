<div class='header_level_1'>
    <i class="fa-regular fa-snowflake">&nbsp;</i><span>Serviço de frio</span>
</div>
<table width='100%'>
    <?php
    if (in_array('3', $_SESSION['usr_permissions'])) {
        // include the Administrator section
        echo "<!--  Auxiliary sistems --->
                <tr><td style='vertical-align:top;'>
                    <li> Clientes:</li>
                </td><td style='text-align: right;'>
                    <button class='btn btn_secondary' 
                        onclick='window.location.href=\"../app_serv_frio/cst_actions.php?action=add\"'
                        style='padding-top: 2px; padding-bottom: 2px; width: 73px;'>
                        <i class='fa-solid fa-plus'></i> Novo
                    </button>
                    <button class='btn btn_secondary' 
                        onclick='window.location.href=\"../app_serv_frio/sct_actions.php?action=manage\"'
                        style='padding-top: 2px; padding-bottom: 2px; width: 73px;'>
                        <i class='fa-solid fa-screwdriver-wrench'></i> Gerir
                    </button>
                </td></tr>";
    }
    /* include the Daylly Purchase Report section
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
        */
    ?>
</table>