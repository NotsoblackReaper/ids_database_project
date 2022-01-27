<?php

// Include DatabaseHelper.php file
require_once('../DatabaseHelpers/IncidentDatabaseHelper.php');

// Instantiate DatabaseHelper class
$database = new IncidentDatabaseHelper();

$guid='';
if (isset($_POST['guid'])) {
    $guid = $_POST['guid'];
}


$type='';
if (isset($_POST['type'])) {
    $type = $_POST['type'];
}

$inc_cost='';
if (isset($_POST['inc_cost'])) {
    $inc_cost = $_POST['inc_cost'];
}

//Fetch data from database
$component_array = $database->selectAllAffectedComponents($guid,50);
$component_count = $database->countAllAffectedComponents($guid);
$not_affected_array= $database->selectAllNotAffected($guid);
?>

<html>
<head>
    <title>Details</title>
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
<h1>Incident Details</h1>

<div>
    <label for="id" >ID:</label> <span id="id"><?php echo $guid; ?></span><br>
    <label for="type" >Type:</label> <span id="type"><?php echo $type; ?></span><br>
    <label for="cost" >Total Cost:</label> <span id="cost"><?php echo $database->getCosts($guid); ?></span>
</div>
</form>
<h2>Affected Components:</h2>
<h3>(Found <?php echo $component_count[0]; ?>, showing <?php echo min($component_count[0], 50)?>) </h3>
<table>
    <tr>
        <th>Serial Nr</th>
        <th>Parent Nr</th>
        <th>KV2</th>
        <th>Shipping</th>
        <th>Installation</th>
        <th>Cost</th>
    </tr>
    <?php foreach ($component_array as $component) : ?>
        <tr>
            <td><?php echo $component['SERIAL_NR']; ?>  </td>
            <td><?php echo $component['PARENT_NR']; ?>  </td>
            <td><?php echo $component['KV2']; ?>  </td>
            <td><?php echo $component['SHIPPING_DATE']; ?>  </td>
            <td><?php echo $component['INSTALLATION_DATE']; ?>  </td>
            <td><?php echo $component['TYPE_COST']; ?>  </td>
        </tr>
    <?php endforeach; ?>
</table>
</body>
</html>