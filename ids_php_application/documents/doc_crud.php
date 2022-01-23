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
$document_array = $database->selectAllDocuments($guid, $az6, $document_url);
?>

<html>
<head>
    <title>Documents</title>
    <link rel="stylesheet" href="../static/styles/menu-bar.css"/>
</head>

<body style="margin-top: 0px">
<ul id="menu-bar">
    <li><a href="../index.php">Home</a></li>
    <li><a class="active" href="../documents/doc_crud.php">Documents</a></li>
    <li><a href="../employees/emp_crud.php">Employees</a></li>
    <li><a href="../types/typ_crud.php">Types</a></li>
</ul>
<h1>Documents</h1>

<!-- Add Person -->
<h2>Add Document: </h2>
<form method="post" action="doc_add.php">
    <div>
        <label for="new_az6">AZ6:</label>
        <input id="new_az6" name="az6" type="text" maxlength="20">
    </div>
    <br>

    <!-- Surname textbox -->
    <div>
        <label for="new_surname">Document URL:</label>
        <input id="new_surname" name="document_url" type="text" maxlength="20">
    </div>
    <br>

    <!-- Submit button -->
    <div>
        <button type="submit">
            Add Document
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
        <label for="doc_guid">ID:</label>
        <input id="doc_guid" name="id" type="number" min="0">
    </div>
    <br>

    <!-- Submit button -->
    <div>
        <button type="submit">
            Delete Document
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
        <label for="guid_inp">GUID:</label>
        <input id="guid_inp" name="guid" type="number" value='<?php echo $guid; ?>' min="0">
    </div>
    <br>

    <!-- az6 textbox:-->
    <div>
        <label for="az6_inp">AZ6:</label>
        <input id="_inp" name="az6" type="text" class="form-control input-md" value='<?php echo $az6; ?>'
               maxlength="12">
    </div>
    <br>

    <!-- Surname textbox:-->
    <div>
        <label for="document_url_inp">Document URL:</label>
        <input id="document_url_inp" name="document_url" type="text"
               value='<?php echo $document_url; ?>' maxlength="40">
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
<h2>Documents Search Result:</h2>
<table>
    <tr>
        <th>GUID</th>
        <th>AZ6</th>
        <th>Document URL</th>
    </tr>
    <?php foreach ($document_array as $document) : ?>
        <tr>
            <td><?php echo $document['GUID']; ?>  </td>
            <td><?php echo $document['AZ6']; ?>  </td>
            <td><?php echo $document['DOCUMENT_URL']; ?>  </td>
        </tr>
    <?php endforeach; ?>
</table>

</body>
</html>