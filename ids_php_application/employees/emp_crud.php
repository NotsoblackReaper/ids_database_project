<?php

// Include DatabaseHelper.php file
require_once('EmployeesDatabaseHelper.php');

// Instantiate DatabaseHelper class
$database = new TypesDatabaseHelper();

$employee_id='';
if (isset($_GET['employee_id'])) {
    $employee_id = $_GET['employee_id'];
}

$name='';
if (isset($_GET['name'])) {
    $name = $_GET['name'];
}

$email='';
if (isset($_GET['email'])) {
    $email = $_GET['email'];
}

//Fetch data from database
$employee_array = $database->selectAllEmployees($employee_id, $name, $email);
?>

<html>
<head>
    <title>Employees</title>
    <link rel="stylesheet" href="../static/styles/menu-bar.css"/>
</head>

<body style="margin-top: 0px">
<ul id="menu-bar">
    <li><a href="../index.php">Home</a></li>
    <li><a href="../documents/doc_crud.php">Documents</a></li>
    <li><a class="active" href="../employees/emp_crud.php">Employees</a></li>
    <li><a href="../types/typ_crud.php">Types</a></li>
</ul>
<h1>Employees</h1>

<!-- Add Person -->
<h2>Add Employee: </h2>
<form method="post" action="emp_add.php">
    <!-- ID is not needed, because its autogenerated by the database -->

    <!-- Name textbox -->
    <div>
        <label for="new_name">Name:</label>
        <input id="new_name" name="name" type="text" maxlength="20">
    </div>
    <br>

    <!-- Surname textbox -->
    <div>
        <label for="new_email">Email:</label>
        <input id="new_email" name="email" type="text" maxlength="40">
    </div>
    <br>

    <!-- Submit button -->
    <div>
        <button type="submit">
            Add Employee
        </button>
    </div>
</form>
<br>
<hr>

<!-- Delete Person -->
<h2>Delete Employee: </h2>
<form method="post" action="emp_delete.php">
    <!-- ID textbox -->
    <div>
        <label for="emp_guid">ID:</label>
        <input id="emp_guid" name="empid" type="number" min="0">
    </div>
    <br>

    <!-- Submit button -->
    <div>
        <button type="submit">
            Delete Employee
        </button>
    </div>
</form>
<br>
<hr>

<!-- Search form -->
<h2>Employees Search:</h2>
<form method="get">
    <!-- GUID textbox:-->
    <div>
        <label for="guid_inp">ID:</label>
        <input id="guid_inp" name="guid" type="number" value='<?php echo $employee_id; ?>' min="0">
    </div>
    <br>

    <!-- a6z textbox:-->
    <div>
        <label for="a6z_inp">Name:</label>
        <input id="_inp" name="name" type="text" class="form-control input-md" value='<?php echo $name; ?>'
               maxlength="12">
    </div>
    <br>

    <!-- Surname textbox:-->
    <div>
        <label for="document_url_inp">Email:</label>
        <input id="document_url_inp" name="email" type="text"
               value='<?php echo $email; ?>' maxlength="40">
    </div>
    <br>

    <!-- Submit button -->
    <div>
        <button id='submit' type='submit'>
            Search
        </button>
    </div>
</form>
<br>
<hr>

<!-- Search result -->
<h2>Employees Search Result:</h2>
<table>
    <tr>
        <th>ID</th>
        <th>Name</th>
        <th>Email</th>
    </tr>
    <?php foreach ($employee_array as $employee) : ?>
        <tr>
            <td><?php echo $employee['EMPLOYEEID']; ?>  </td>
            <td><?php echo $employee['NAME']; ?>  </td>
            <td><?php echo $employee['EMAIL']; ?>  </td>
        </tr>
    <?php endforeach; ?>
</table>

</body>
</html>