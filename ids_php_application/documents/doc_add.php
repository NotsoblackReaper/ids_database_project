<?php

//include DatabaseHelper.php file
require_once('../DatabaseHelpers/DocumentsDatabaseHelper.php');

//instantiate DatabaseHelper class
$database = new DocumentsDatabaseHelper();

//Grab variables from POST request

$az6='';
if (isset($_POST['az6'])) {
    $az6 = $_POST['az6'];
}

$document_url='';
if (isset($_POST['document_url'])) {
    $document_url = $_POST['document_url'];
}

// Insert method
$success = $database->insertIntoDocuments($az6, $document_url);

// Check result
if ($success){
    echo "Person '{$az6} {$document_url}' successfully added!'";
}
else{
    echo "Error can't insert Person '{$az6} {$document_url}'!";
}
?>

<!-- link back to index page-->
<br>
<a href="doc_crud.php">
    go back
</a>