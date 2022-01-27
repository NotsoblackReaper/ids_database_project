<?php

//include DatabaseHelper.php file
require_once('../DatabaseHelpers/ComponentsDatabaseHelper.php');

//instantiate DatabaseHelper class
$database = new ComponentsDatabaseHelper();

//Grab variable id from POST request

$snr='';
if (isset($_POST['snr'])) {
    $snr = $_POST['snr'];
}
// Delete method
$error_code = $database->deleteComponent( $snr);

// Check result
if ($error_code == 1){
    echo "Component with ID: '{$snr}' successfully deleted!'";
}
else{
    echo "Error can't delete Component with ID: '{$snr}'. Errorcode: {$error_code}";
}
?>

<!-- link back to index page-->
<br>
<a href="comp_crud.php">
    go back
</a>