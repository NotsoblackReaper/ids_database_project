<?php

require_once('DatabaseHelpers/DocumentsDatabaseHelper.php');
$database_doc = new DocumentsDatabaseHelper();

require_once('DatabaseHelpers/TypesDatabaseHelper.php');
$database_typ = new TypesDatabaseHelper();

require_once('DatabaseHelpers/EmployeesDatabaseHelper.php');
$database_emp = new EmployeesDatabaseHelper();

require_once('DatabaseHelpers/ComponentsDatabaseHelper.php');
$database_comp = new ComponentsDatabaseHelper();

$document_count = $database_doc->countAllDocuments('', '', '');
$type_count = $database_typ->countAllTypes('', '', '','','','');
$employee_count = $database_emp->countAllEmployees('', '', '','');
$component_count = $database_comp->countAllComponents('', '', '','','','');
?>

<html>
<head>
    <title>IDS</title>
    <link rel="stylesheet" href="static/styles/menu-bar.css"/>
</head>

<body style="margin-top: 0px">
<ul id="menu-bar">
    <li><a class="active"href="index.php">Home</a></li>
    <li><a href="documents/doc_crud.php">Documents</a></li>
    <li><a href="employees/emp_crud.php">Employees</a></li>
    <li><a href="types/typ_crud.php">Types</a></li>
    <li><a href="components/comp_crud.php">Components</a></li>
    <li><a href="incidents/inc_crud.php">Incidents</a></li>
</ul>
<h1>Homepage</h1>
<h2>Total Documents:  <?php echo $document_count[0]?></h2>
<h2>Total Types:      <?php echo $type_count[0]?></h2>
<h2>Total Employees:  <?php echo $employee_count[0]?></h2>
<h2>Total Components: <?php echo $component_count[0]?></h2>