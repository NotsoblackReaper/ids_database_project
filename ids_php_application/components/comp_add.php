<?php

//include DatabaseHelper.php file
require_once('../DatabaseHelpers/ComponentsDatabaseHelper.php');

//instantiate DatabaseHelper class
$database = new ComponentsDatabaseHelper();

//Grab variables from POST request
$pnr='';
if (isset($_POST['parent_nr'])) {
    $pnr = $_POST['parent_nr'];
}

$tid='';
if (isset($_POST['tid'])) {
    $tid = $_POST['tid'];
}

$shipping='null';
if (isset($_POST['shipping'])&&$_POST['shipping']!=''){
    $shipping = date("d-M-y", strtotime($_POST['shipping']));
}

$installation='null';
if (isset($_POST['installation'])&&$_POST['installation']!='') {
    $installation = date("d-M-y", strtotime($_POST['installation']));
}

// Insert method
$success = $database->insertIntoComponents($pnr,$tid,$shipping,$installation);

// Check result
if ($success){
    echo "Component successfully added!'";
}
else{
    echo "Error can't insert Component!";
}
?>

<!-- link back to index page-->
<br>
<a href="comp_crud.php">
    go back
</a>