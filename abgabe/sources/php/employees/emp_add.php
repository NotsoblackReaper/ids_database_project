<?php

//include DatabaseHelper.php file
require_once('../DatabaseHelpers/EmployeesDatabaseHelper.php');

//instantiate DatabaseHelper class
$database = new EmployeesDatabaseHelper();

//Grab variables from POST request

$firstname='';
if (isset($_POST['firstname'])) {
    $firstname = $_POST['firstname'];
}

$surname='';
if (isset($_POST['surname'])) {
    $surname = $_POST['surname'];
}

$email='';
if (isset($_POST['email'])) {
    $email = $_POST['email'];
}

// Insert method
if($firstname!=''&&$surname!=''&&$email!='')
$success = $database->insertIntoEmployees($firstname,$surname, $email);
else
    $success=false;
// Check result
if ($success){
    echo "Employee '{$firstname} {$surname} {$email}' successfully added!'";
}
else{
    echo "Error can't insert Employee '{$surname} {$surname} {$email}'!";
}
?>

<!-- link back to index page-->
<br>
<a href="emp_crud.php">
    go back
</a>