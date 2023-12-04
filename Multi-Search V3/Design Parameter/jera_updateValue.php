<?php
include $_SERVER['DOCUMENT_ROOT']."/version.php";
$path = $_SERVER['DOCUMENT_ROOT']."/".v."/Common Data/";
set_include_path($path);    
include('PHP Modules/mysqliConnection.php');
include('PHP Modules/anthony_wholeNumber.php');
include('PHP Modules/anthony_retrieveText.php');
include('PHP Modules/gerald_functions.php');
include("PHP Modules/rose_prodfunctions.php");
ini_set("display_errors", "on");
$tpl = new PMSTemplates;
$obj = new PMSDatabase;


if (isset($_POST['templateId']) && isset($_POST['columnName']) && isset($_POST['newValue']) && isset($_POST['cssProperty'])) {
    $templateId = $_POST['templateId'];
    $columnName = $_POST['columnName'];
    $newValue = $_POST['newValue'];
    $cssProperty = $_POST['cssProperty'];

    // Define the SQL statement with a placeholder for the attribute
    $sql = "UPDATE system_msParameter SET $columnName = ? WHERE id = ? AND cssProperty = ?";
    
    // Prepare the SQL statement
    $stmt = $db->prepare($sql);
    
    // Bind the parameters to the placeholders
    $stmt->bind_param("sis", $newValue, $templateId, $cssProperty);

    if ($stmt->execute()) {
        echo "Database updated successfully";
    } else {
        echo "Database update failed";
    }

    $stmt->close();
} else {
    echo "Invalid input data";
}


?>