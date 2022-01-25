<?php

// Include DatabaseHelper.php file
require_once('../DatabaseHelpers/EmployeesDatabaseHelper.php');

// Instantiate DatabaseHelper class
$database = new EmployeesDatabaseHelper();

$employee_id='';
if (isset($_GET['employee_id'])) {
    $employee_id = $_GET['employee_id'];
}

$firstname='';
if (isset($_GET['firstname'])) {
    $firstname = $_GET['firstname'];
}

$surname='';
if (isset($_GET['surname'])) {
    $surname = $_GET['surname'];
}

$email='';
if (isset($_GET['email'])) {
    $email = $_GET['email'];
}

//Fetch data from database
$employee_array = $database->selectAllEmployees($employee_id, $firstname,$surname, $email,50);
$employee_count = $database->countAllEmployees($employee_id, $firstname,$surname, $email);
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
    <li><a class="active" href="../employees/emp_crud.php">Employees</a></li>
    <li><a href="../types/typ_crud.php">Types</a></li>
    <li><a href="../components/comp_crud.php">Components</a></li>
    <li><a href="../incidents/inc_crud.php">Incidents</a></li>
</ul>
<h1>Employees</h1>

<!-- Add Person -->
<h2>Add Employee: </h2>
<form method="post" action="emp_add.php">
    <div>
        <label for="new_fname" class="label">Firstname:</label>
        <input id="new_fname" name="firstname" type="text" maxlength="20">
    </div>
    <div>
        <label for="new_sname" class="label">Surname:</label>
        <input id="new_sname" name="surname" type="text" maxlength="20">
    </div>
    <div>
        <label for="new_email" class="label">Email:</label>
        <input id="new_email" name="email" type="text" maxlength="40">
    </div>
    <div>
        <button type="submit" class="button-submit">
            Add
        </button>
    </div>
</form>
<br>
<hr>

<!-- Delete Person -->
<h2>Delete Employee: </h2>
<form method="post" action="emp_delete.php">
    <div>
        <label for="emp_guid" class="label">ID:</label>
        <input id="emp_guid" name="empid" type="number" min="0">
    </div>
    <div>
        <button type="submit" class="button-submit">
            Delete
        </button>
    </div>
</form>
<br>
<hr>

<!-- Search form -->
<h2>Employees Search:</h2>
<form method="get">
    <div>
        <label for="guid_inp" class="label">ID:</label>
        <input id="guid_inp" name="guid" type="number" value='<?php echo $employee_id; ?>' min="0">
    </div>
    <div>
        <label for="fname_inp" class="label">Firstname:</label>
        <input id="fname_inp" name="firstname" type="text" class="form-control input-md" value='<?php echo $firstname; ?>'
               maxlength="20">
    </div>
    <div>
        <label for="sname_inp" class="label">Surname:</label>
        <input id="sname_inp" name="surname" type="text" class="form-control input-md" value='<?php echo $surname; ?>'
               maxlength="20">
    </div>
    <div>
        <label for="document_url_inp" class="label">Email:</label>
        <input id="document_url_inp" name="email" type="text"
               value='<?php echo $email; ?>' maxlength="40">
    </div>
    <div>
        <button id='submit' type='submit' class="button-submit">
            Search
        </button>
    </div>
</form>
<br>
<hr>

<!-- Search result -->
<h2>Employees Search Result:</h2>
<h3>(Found <?php echo $employee_count[0]; ?>, showing <?php echo min($employee_count[0], 50)?>) </h3>
<table>
    <tr>
        <th>ID</th>
        <th style="text-align: right;padding-right: 5px;">Firstname</th>
        <th style="text-align: left;">Surname</th>
        <th style="text-align: left;">Email</th>
        <th>Actions</th>
    </tr>
    <?php foreach ($employee_array as $employee) : ?>
        <tr>
            <td><?php echo $employee['EMPLOYEEID']; ?>  </td>
            <td style="text-align: right;padding-right: 5px;"><?php echo $employee['FIRSTNAME']; ?>  </td>
            <td><?php echo $employee['SURNAME']; ?>  </td>
            <td><?php echo $employee['EMAIL']; ?>  </td>
            <td>
                <form method="post" action="emp_update.php">
                    <input name="empid" type="hidden" value="<?php echo $employee['EMPLOYEEID']?>">
                    <button type="submit" id="button-list">
                        <img src="../static/imgs/feather.png" alt="Edit" width="20">
                    </button>
                </form>
            </td>
            <td>
                <form method="post" action="emp_delete.php">
                        <input name="empid" type="hidden" value="<?php echo $employee['EMPLOYEEID']?>">
                        <button type="submit" id="button-list">
                            <img src="../static/imgs/trashcan.png" alt="Del" width="20">
                        </button>
                </form>
            </td>
        </tr>
    <?php endforeach; ?>
</table>

</body>
</html>