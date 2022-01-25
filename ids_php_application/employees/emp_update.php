<?php

// Include DatabaseHelper.php file
require_once('../DatabaseHelpers/EmployeesDatabaseHelper.php');

// Instantiate DatabaseHelper class
$database = new EmployeesDatabaseHelper();

$empid='';
if (isset($_POST['empid'])) {
    $empid = $_POST['empid'];
}

var_dump($empid);
//Fetch data from database
$employee = $database->selectOneEmploye($empid);


$firstname=$employee[0]['FIRSTNAME'];
$surname=$employee[0]['SURNAME'];
$email=$employee[0]['EMAIL'];

?>

<html>
<head>
    <title>Employees</title>
    <link rel="stylesheet" href="../static/styles/menu-bar.css"/>
    <link rel="stylesheet" href="../static/styles/forms.css"/>
</head>

<body style="margin-top: 0px;padding-left: 10px;padding-right: 10px;">
<ul id="menu-bar">
    <li><a href="../index.php">Home</a></li>
    <li><a href="../documents/doc_crud.php">Documents</a></li>
    <li><a href="../employees/emp_crud.php">Employees</a></li>
    <li><a href="../types/typ_crud.php">Types</a></li>
    <li><a href="../incidents/inc_crud.php">Incidents</a></li>
</ul>
<h1>Update Employee</h1>

<!-- Add Person -->
<h2>Edit Employee: </h2>
<h3>Id: <?php echo $empid; ?></h3>
<form method="post" action="emp_submit_update.php">
    <input name="empid" type="hidden" value='<?php echo $empid; ?>'>
    <div>
        <label for="new_fname" class="label">Firstname:</label>
        <input id="new_fname" name="firstname" type="text" maxlength="20" value='<?php echo $firstname; ?>'>
    </div>
    <div>
        <label for="new_sname" class="label">Surname:</label>
        <input id="new_sname" name="surname" type="text" maxlength="20" value='<?php echo $surname; ?>'>
    </div>
    <div>
        <label for="new_email" class="label">Email:</label>
        <input id="new_email" name="email" type="text" maxlength="40" value='<?php echo $email; ?>'>
    </div>
    <div>
        <button type="submit" class="button-submit">
            Save
        </button>
    </div>
</form>
</body>
</html>