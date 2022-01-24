<?php

//include DatabaseHelper.php file
require_once('../DatabaseHelpers/TypesDatabaseHelper.php');
$database = new TypesDatabaseHelper();

//Grab variable id from POST request
$typeid='';
if (isset($_POST['typeid'])) {
    $typeid = $_POST['typeid'];
}
// Delete method
$error_code = $database->deleteType($typeid);

// Check result
if ($error_code == 1){
    echo "Type with ID: '{$typeid}' successfully deleted!'";
}
else{
    echo "Error can't delete Type with ID: '{$typeid}'. Errorcode: {$error_code}";
}
?>

<!-- link back to index page-->
<br>
<a href="typ_crud.php">
    go back
</a>