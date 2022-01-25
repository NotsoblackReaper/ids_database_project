<?php

// Include DatabaseHelper.php file
require_once('../DatabaseHelpers/IncidentDatabaseHelper.php');

// Instantiate DatabaseHelper class
$database = new IncidentDatabaseHelper();

$guid='';
if (isset($_GET['guid'])) {
    $guid = $_GET['guid'];
}

$type='';
if (isset($_GET['type'])) {
    $type = $_GET['type'];
}

$inc_cost='';
if (isset($_GET['inc_cost'])) {
    $inc_cost = $_GET['inc_cost'];
}

//Fetch data from database
$incident_array = $database->selectAllIncidents($guid, $type, $inc_cost,50);
$incident_count = $database->countAllIncidents($guid, $type, $inc_cost);
?>

<html>
<head>
    <title>Incidents</title>
    <link rel="stylesheet" href="../static/styles/menu-bar.css"/>
    <link rel="stylesheet" href="../static/styles/forms.css"/>
</head>

<body style="margin-top: 0px;padding-left: 10px;padding-right: 10px;">
<ul id="menu-bar">
    <li><a href="../index.php">Home</a></li>
    <li><a href="../documents/doc_crud.php">Documents</a></li>
    <li><a href="../employees/emp_crud.php">Employees</a></li>
    <li><a href="../types/typ_crud.php">Types</a></li>
    <li><a href="../components/comp_crud.php">Components</a></li>
    <li><a class="active" href="../incidents/inc_crud.php">Incidents</a></li>
</ul>
<h1>Incidents</h1>
<!-- Search form -->
<h2>Incident Search:</h2>
<form method="get">
    <!-- GUID textbox:-->
    <div>
        <label for="guid_inp" class="label">GUID:</label>
        <input id="guid_inp" name="guid" type="number" value='<?php echo $guid; ?>' min="0">
    </div>
    <div>
        <label for="type_inp"  class="label">Type:</label>
        <input id="type_inp" name="type" type="text" class="form-control input-md" value='<?php echo $type; ?>'
               maxlength="50">
    </div>
    <div>
        <label for="cost_inp" class="label">Cost:</label>
        <input id="cost_inp" name="inc_cost" type="number"
               value='<?php echo $inc_cost; ?>' min=0>
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
<h2>Incident Search Result:</h2>
<h3>(Found <?php echo $incident_count[0]; ?>, showing <?php echo min($incident_count[0], 50)?>) </h3>
<table>
    <tr>
        <th>GUID</th>
        <th>Type</th>
        <th>Incident cost</th>
        <th>Details</th>
    </tr>
    <?php foreach ($incident_array as $incident) : ?>
        <tr>
            <td><?php echo $incident['GUID']; ?>  </td>
            <td><?php echo $incident['TYPE']; ?>  </td>
            <td><?php echo $incident['INCIDENT_COST']; ?>  </td>
            <td>
                <form method="post" action="inc_details.php">
                    <input name="guid" type="hidden" value="<?php echo $incident['GUID']; ?>">
                    <input name="type" type="hidden" value="<?php echo $incident['TYPE']; ?>">
                    <input name="inc_cost" type="hidden" value="<?php echo $incident['INCIDENT_COST']; ?>">
                    <button type="submit" id="button-list">
                        <img src="../static/imgs/magnifying_glass.png" alt="Details" width="20">
                    </button>
                </form>
            </td>
        </tr>
    <?php endforeach; ?>
</table>

</body>
</html>