<?php

//include DatabaseHelper.php file
require_once('TypesDatabaseHelper.php');
$database = new TypesDatabaseHelper();

//Grab variables from POST request
$name='';
if (isset($_POST['name'])) {
    $name = $_POST['name'];
}

$email='';
if (isset($_POST['email'])) {
    $email = $_POST['email'];
}

// Insert method
$success = $database->insertIntoEmployees($name, $email);

// Check result
if ($success){
    echo "Employee '{$name} {$email}' successfully added!'";
}
else{
    echo "Error can't insert Employee '{$name} {$email}'!";
}
?>

<!-- link back to index page-->
<br>
<a href="emp_crud.php">
    go back
</a>