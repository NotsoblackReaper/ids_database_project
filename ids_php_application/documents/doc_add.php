<?php

//include DatabaseHelper.php file
require_once('DocumentsDatabaseHelper.php');

//instantiate DatabaseHelper class
$database = new TypesDatabaseHelper();

//Grab variables from POST request

$a6z='';
if (isset($_POST['a6z'])) {
    $a6z = $_POST['a6z'];
}

$document_url='';
if (isset($_POST['document_url'])) {
    $document_url = $_POST['document_url'];
}

// Insert method
$success = $database->insertIntoDocuments($a6z, $document_url);

// Check result
if ($success){
    echo "Person '{$a6z} {$document_url}' successfully added!'";
}
else{
    echo "Error can't insert Person '{$a6z} {$document_url}'!";
}
?>

<!-- link back to index page-->
<br>
<a href="doc_crud.php">
    go back
</a>