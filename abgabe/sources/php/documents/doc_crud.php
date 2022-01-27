<?php

// Include DatabaseHelper.php file
require_once('../DatabaseHelpers/DocumentsDatabaseHelper.php');

// Instantiate DatabaseHelper class
$database = new DocumentsDatabaseHelper();

$guid='';
if (isset($_GET['guid'])) {
    $guid = $_GET['guid'];
}

$az6='';
if (isset($_GET['az6'])) {
    $az6 = $_GET['az6'];
}

$document_url='';
if (isset($_GET['document_url'])) {
    $document_url = $_GET['document_url'];
}

//Fetch data from database
$document_array = $database->selectAllDocuments($guid, $az6, $document_url,50);
$document_count = $database->countAllDocuments($guid, $az6, $document_url);
?>

<html>
<head>
    <title>Documents</title>
    <link rel="stylesheet" href="../static/styles/menu-bar.css"/>
    <link rel="stylesheet" href="../static/styles/forms.css"/>
</head>

<body style="margin-top: 0px;padding-left: 10px;padding-right: 10px;">
<ul id="menu-bar">
    <li><a href="../index.php">Home</a></li>
    <li><a class="active" href="../documents/doc_crud.php">Documents</a></li>
    <li><a href="../employees/emp_crud.php">Employees</a></li>
    <li><a href="../types/typ_crud.php">Types</a></li>
    <li><a href="../components/comp_crud.php">Components</a></li>
    <li><a href="../incidents/inc_crud.php">Incidents</a></li>
</ul>
<h1>Documents</h1>

<!-- Add Person -->
<h2>Add Document: </h2>
<form method="post" action="doc_add.php">
    <div>
        <label for="new_az6" class="label">AZ6:</label>
        <input id="new_az6" name="az6" type="text" maxlength="20">
    </div>
    <div>
        <label for="new_surname" class="label">Document URL:</label>
        <input id="new_surname" name="document_url" type="text" maxlength="20">
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
<h2>Delete Document: </h2>
<form method="post" action="doc_delete.php">
    <!-- ID textbox -->
    <div>
        <label for="doc_guid" class="label">ID:</label>
        <input id="doc_guid" name="id" type="number" min="0">
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
<h2>Documents Search:</h2>
<form method="get">
    <!-- GUID textbox:-->
    <div>
        <label for="guid_inp" class="label">GUID:</label>
        <input id="guid_inp" name="guid" type="number" value='<?php echo $guid; ?>' min="0">
    </div>
    <div>
        <label for="az6_inp"  class="label">AZ6:</label>
        <input id="_inp" name="az6" type="text" class="form-control input-md" value='<?php echo $az6; ?>'
               maxlength="12">
    </div>
    <div>
        <label for="document_url_inp" class="label">Document URL:</label>
        <input id="document_url_inp" name="document_url" type="text"
               value='<?php echo $document_url; ?>' maxlength="40">
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
<h2>Documents Search Result:</h2>
<h3>(Found <?php echo $document_count[0]; ?>, showing <?php echo min($document_count[0], 50)?>) </h3>
<table>
    <tr>
        <th>GUID</th>
        <th>AZ6</th>
        <th>Document URL</th>
        <th>Delete</th>
    </tr>
    <?php foreach ($document_array as $document) : ?>
        <tr>
            <td><?php echo $document['GUID']; ?>  </td>
            <td><?php echo $document['AZ6']; ?>  </td>
            <td><?php echo $document['DOCUMENT_URL']; ?>  </td>
            <td><form method="post" action="doc_delete.php">
                    <input name="id" type="hidden" value="<?php echo $document['GUID']; ?>">
                    <button id="button-list" type="submit" <?php if ($document['TYPEID'] != ''){ ?> disabled <?php } ?>>
                        <img src="../static/imgs/trashcan.png" alt="Del" width="20">
                    </button>
                </form>
            </td>
        </tr>
    <?php endforeach; ?>
</table>

</body>
</html>