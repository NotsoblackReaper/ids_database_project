<?php

//include DatabaseHelper.php file
require_once('../DatabaseHelpers/TypesDatabaseHelper.php');
$database = new TypesDatabaseHelper();

//Grab variables from POST request
$spec_id='';
if (isset($_POST['spec_id'])) {
    $spec_id = $_POST['spec_id'];
}
$kv2='';
if (isset($_POST['kv2'])) {
    $kv2 = $_POST['kv2'];
}
$supplier='';
if (isset($_POST['supplier'])) {
    $supplier = $_POST['supplier'];
}
$cost='';
if (isset($_POST['cost'])) {
    $cost = $_POST['cost'];
}
// Insert method
$success = $database->insertIntoTypes($spec_id, $kv2, $supplier, $cost);

// Check result
if ($success){
    echo "Type '{$spec_id} {$kv2} {$supplier} {$cost}' successfully added!'";
}
else{
    echo "Error can't insert Employee '{$spec_id} {$kv2} {$supplier} {$cost}'!";
}
?>

<!-- link back to index page-->
<br>
<a href="typ_crud.php">
    go back
</a>