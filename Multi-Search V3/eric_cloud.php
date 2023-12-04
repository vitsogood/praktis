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
function makeFileGetContentsRequest($searchValue)
{
    $scriptUrl = 'https://arktechph.com/V4/20%20Document%20Management%20System/eric_getCounts.php'; // Replace with your actual URL

    $contextOptions = [
        'http' => [
            'method' => 'GET',
            'header' => 'Content-type: application/x-www-form-urlencoded',
            'ignore_errors' => true,
        ],
    ];

    $context = stream_context_create($contextOptions);
    $fullUrl = $scriptUrl . '?searchValue=' . urlencode($searchValue);
    $response = file_get_contents($fullUrl);

    return json_decode($response, true); // Decode JSON response into an a  sociative array
}

function getCountQuery($type, $searchValue) {

    $types = [
        'material' => 1,
        'supply' => 3,
        'accessory' => 4,
        'fg' => 5,
    ];
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

    $type = ($type != 'drawing')?' type = '.$types[$type].' AND ': '';
    

    $query = "SELECT COUNT(*) AS count FROM $table WHERE $type (" . implode(' AND ', $conditions) . ")";
    return $query;
}
// $searchValue = "SECC";
$searchValue = mysqli_real_escape_string($db, $searchValue);

$types = ['material', 'supply', 'accessory', 'fg', 'drawing'];
$counts = [];
// $counts[] = null;    

// if (preg_match('/^[A-Z][PMH]\d+$/', $searchValue)) {
//     $types[] = 'barcode'; 
// }

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

// Make HTTP request to the second script
$searchValueForRequest = $searchValue;
$countsFromHostedScript = makeFileGetContentsRequest($searchValueForRequest);

if ($countsFromHostedScript !== null) {
    // Merge the counts from the second script with the existing counts
    $counts = array_merge($counts, $countsFromHostedScript);
}
else{
    echo "nande";
}

// Encode the merged counts array as JSON
$jsonResponse = json_encode($counts);

// Return the JSON response
echo $jsonResponse;

// Define a separate function for making HTTP GET requests using file_get_contents
