<?php
// Retrieve the selected port ID from the AJAX request
$selectedPort = $_POST['port_id'];

// create a database connection
include '../app_components/db_connect.php';

// Get BOATS FOR PORT
$sql_get_boats_for_port = "SELECT p.port_id, p.boat_id, b.boat_name
                      FROM port_boats p INNER JOIN boats b ON b.boat_id=p.boat_id
                      WHERE p.port_id=$selectedPort
                      ORDER BY b.boat_name";
$result_get_boats_for_port = mysqli_query($conn, $sql_get_boats_for_port);

// close the database connection
include '../app_components/db_disconnect.php';

// Build the HTML content for the boats section
$boatsHtml = '';
$coll_side='col_50_l';
foreach ($result_get_boats_for_port as $boat) {
    $port_id = $boat['port_id'];
    $boatId = $boat['boat_id'];
    $boatName = $boat['boat_name'];
    $boatsHtml .= "<div class='$coll_side'>
                    <label class='CHK_container' for='boat_$boatId'> $boatName
                    <input type='checkbox' id='boat_$boatId' name='boats[]' value='$boatId'
                    style='display: none;'
                    fld_req='true'
                    fld_error_message='Selecione pelo menos uma embarcação.'>
                    <span class='checkmark'></span>
                    </label>
                  </div>";
    if ($coll_side=='col_50_l'){
    $coll_side='col_50_r';
    } else {
    $coll_side='col_50_l';
    }
}

// Return the boats HTML content
echo $boatsHtml;
?>
