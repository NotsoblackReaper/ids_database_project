<?php

//include DatabaseHelper.php file
require_once('../DatabaseHelpers/TypesDatabaseHelper.php');
$database = new TypesDatabaseHelper();

//Grab variable id from POST request
$empid='';
if (isset($_POST['empid'])) {
    $empid = $_POST['empid'];
}
// Delete method
$error_code = $database->deleteEmployee($empid);

// Check result
if ($error_code == 1){
    echo "Employee with ID: '{$empid}' successfully deleted!'";
}
else{
    echo "Error can't delete Employee with ID: '{$empid}'. Errorcode: {$error_code}";
}
?>

<!-- link back to index page-->
<br>
<a href="emp_crud.php">
    go back
</a>