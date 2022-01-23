<?php

//include DatabaseHelper.php file
require_once('../DatabaseHelpers/DocumentsDatabaseHelper.php');

//instantiate DatabaseHelper class
$database = new DocumentsDatabaseHelper();

//Grab variable id from POST request
$guid='';
if(isset($_POST['id'])) {
    $guid = $_POST['id'];
}
// Delete method
$error_code = $database->deleteDocument( $guid);

// Check result
if ($error_code == 1){
    echo "Document with ID: '{$guid}' successfully deleted!'";
}
else{
    echo "Error can't delete Document with ID: '{$guid}'. Errorcode: {$error_code}";
}
?>

<!-- link back to index page-->
<br>
<a href="doc_crud.php">
    go back
</a>