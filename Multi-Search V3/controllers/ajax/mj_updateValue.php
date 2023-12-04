<?php
include $_SERVER['DOCUMENT_ROOT']."/version.php";
$path = $_SERVER['DOCUMENT_ROOT']."/".v."/Common Data/";
set_include_path($path);
include('PHP Modules/mysqliConnection.php');
include('PHP Modules/gerald_functions.php');
include('PHP Modules/anthony_retrieveText.php');
include("PHP Modules/anthony_wholeNumber.php");
include("PHP Modules/rose_prodfunctions.php");
ini_set("display_errors", "on");


$thickness = isset($_POST['thickness']) ? $_POST['thickness'] : "";
$expirationDate = isset($_POST['expirationDate']) ? $_POST['expirationDate'] : "";
$inventoryRemakrs = isset($_POST['inventoryRemarks']) ? $_POST['inventoryRemarks'] : "";
$inventoryId = isset($_POST['inventoryId']) ? $_POST['inventoryId'] : "";

echo $sql = "UPDATE warehouse_inventory SET dataTwo = '". $thickness ."', inventoryRemarks = '". $inventoryRemakrs ."',dataThree = '". $expirationDate . "' WHERE inventoryId = '". $inventoryId ."'";
$query = $db -> query($sql);
if ($query && $query -> num_rows > 0)
{
   // echo "success";
}
else
{
    // echo "failed";
}
?>