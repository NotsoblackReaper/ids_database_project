<?php

//include DatabaseHelper.php file
require_once('../DatabaseHelpers/EmployeesDatabaseHelper.php');

//instantiate DatabaseHelper class
$database = new EmployeesDatabaseHelper();

//Grab variables from POST request

$empid='';
if (isset($_POST['empid'])) {
    $empid = $_POST['empid'];
}

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

if($empid!=''&&$firstname!=''&&$surname!=''&&$email!='')
    $success = $database->updateEmployee($empid,$firstname,$surname, $email);
else
    $success=false;
// Check result
if ($success){
    echo "Employee '{$empid} {$firstname} {$surname} {$email}' successfully updated!'";
}
else{
    echo "Error can't update Employee '{$empid} {$surname} {$surname} {$email}'!";
}
?>

<!-- link back to index page-->
<br>
<a href="emp_crud.php">
    go back
</a>