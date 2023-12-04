<?php
include $_SERVER['DOCUMENT_ROOT'] . "/version.php";
$path = $_SERVER['DOCUMENT_ROOT'] . "/" . v . "/Common Data/";
set_include_path($path);
include('PHP Modules/mysqliConnection.php');
include('PHP Modules/gerald_functions.php');
include('PHP Modules/anthony_retrieveText.php');
include("PHP Modules/anthony_wholeNumber.php");
include("PHP Modules/rose_prodfunctions.php");

// function updateData($inventoryId){

// }

function getNextVersion($currentVersion)
{
    if($currentVersion === '0'){
        return '1.0';
    }
    
    list($major, $minor) = explode('.', $currentVersion);
    return (int)$minor === 9 ? ($major + 1) . ".0" : $major . "." . ($minor + 1);
}

$inventoryIds = isset($_REQUEST['inventoryIds']) ? $_REQUEST['inventoryIds'] : '';
$location = isset($_REQUEST['loc']) ? $_REQUEST['loc'] : '';

$inventoryId = explode(',',$inventoryIds);
$try = '';

foreach($inventoryId as $id) {
    $sql = "SELECT version FROM warehouse_inventory WHERE inventoryId = '$id' LIMIT 1";
    $query = $db->query($sql);

    if($query AND $query->num_rows == 1){
        $data = $query->fetch_assoc();
        $version = getNextVersion($data['version']);
       
        $sql = "UPDATE warehouse_inventory SET inventoryLocation = '$location', version ='$version' WHERE inventoryId = '$id' LIMIT 1";
        $query = $db->query($sql);
    }

}

echo $query ? 'success' : 'failed'

?>