<?php
include $_SERVER['DOCUMENT_ROOT'] . "/version.php";
$path = $_SERVER['DOCUMENT_ROOT'] . "/" . v . "/Common Data/";
set_include_path($path);
include('PHP Modules/mysqliConnection.php');
include('PHP Modules/gerald_functions.php');
include('PHP Modules/anthony_retrieveText.php');
include("PHP Modules/anthony_wholeNumber.php");
include("PHP Modules/rose_prodfunctions.php");
ini_set("display_errors", "on");
$searchValue = isset($_REQUEST['searchValue'])? $_REQUEST['searchValue'] : '';
function getCountQuery($type, $searchValue) {

    $table = ($type == 'drawing') ? "cadcam_parts" : "warehouse_inventory";
    $columns = ($type == 'drawing') ? ['partNumber', 'partName'] : ['supplierAlias', 'dataOne', 'dataTwo', 'dataThree', 'dataFour'];
    $searchWords = explode(' ', $searchValue);
    $conditions = [];

    foreach ($searchWords as $word) {
        $subconditions = [];
        foreach ($columns as $column) {
            $subconditions[] = "$column LIKE '%$word%'";
        }
        $conditions[] = '(' . implode(' OR ', $subconditions) . ')';
    }

    $type = ($type != 'drawing')?' type = '.$type.' AND ': '';
    $query = "SELECT COUNT(*) AS count FROM $table WHERE $type (" . implode(' AND ', $conditions) . ")";
    return $query;
    
}
// $searchValue = "SECC";
$searchValue = mysqli_real_escape_string($db, $searchValue);

$types = [1, 3, 4, 5, 'drawing'];
$counts = [];

foreach ($types as $type) {
    $query = getCountQuery($type, $searchValue);
    $result = mysqli_query($db, $query);

    // echo $query.'<br>'; 
    if ($result) {
        $row = mysqli_fetch_assoc($result);
        $counts[$type] = $row['count'];
    } else {
        echo "Error in query for type $type: " . mysqli_error($db);
    }
}

$counts['query'] = $query;
// Now $counts contains the counts for each type
echo json_encode($counts);
?>
