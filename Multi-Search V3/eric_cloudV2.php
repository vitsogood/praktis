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
$searchValue = isset($_REQUEST['searchValue']) ? $_REQUEST['searchValue'] : '';

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

function getCountQuery($type, $searchValue)
{
    $types = [
        'material' => 1,
        'supply' => 3,
        'accessory' => 4,
        'fg' => 5,
    ];
    $table = ($type == 'drawing') ? "cadcam_parts" : "warehouse_inventory";
    $columns = ($type == 'drawing') ? ['partNumber', 'partName'] : ['supplierAlias', 'dataOne', 'dataTwo', 'dataThree', 'dataFour'];
    $searchWords = $searchWords = preg_split('/[\p{Zs}\s]+/u', trim($searchValue));
    $conditions = [];

    foreach ($searchWords as $word) {
        $subconditions = [];
        foreach ($columns as $column) {
            $subconditions[] = "$column LIKE '%$word%'";
        }
        $conditions[] = '(' . implode(' OR ', $subconditions) . ')';
    }

    $type = ($type != 'drawing') ? ' type = ' . $types[$type] . ' AND ' : '';

    $query = "SELECT COUNT(*) AS count FROM $table WHERE $type (" . implode(' AND ', $conditions) . ")";
    return $query;
}

$searchValue = mysqli_real_escape_string($db, $searchValue);

$counts = [
    'barcode' => '0',
    'material' => '0',
    'supply'  => '0', 
    'accessory'  => '0', 
    'fg'  => '0', 
    'drawing'  => '0',
];

if (preg_match('/^(?:[PpMmHhRr]\d+|\d+(?:-\d+)*\d?)$/', $searchValue)) 
{
    // Handle barcode-like search separately
    if (stripos($searchValue, 'M') === '0' || stripos($searchValue, 'm') === '0') {
        $tables = ['warehouse_inventory'];
    } else {
        $tables = ['ppic_lotlist'];
    }

    $counts = [
        'barcode' => 0,
        'barcode' => '0',
        'material' => '0',
        'supply'  => '0',
        'accessory'  => '0',
        'fg'  => '0',
        'drawing'  => '0',
    ];

    foreach ($tables as $table) {
        $subconditions = [];
        $columns = ($table == 'ppic_lotlist') ? ['lotNumber', 'productionTag', 'groupTag', 'hTag'] : ['inventoryId'];

        foreach ($columns as $column) {
            // $tablePrefix = ($table == 'ppic_lotlist') ? 'ppic_lotlist' : 'warehouse_inventory';
            $subconditions[] = "$column LIKE '$searchValue'";
        }

        // Construct the SQL query based on the table and conditions
        $query = "SELECT COUNT(*) AS count FROM $table WHERE " . implode(' OR ', $subconditions);

        $result = mysqli_query($db, $query);

        if ($result) {
            $row = mysqli_fetch_assoc($result);
            $counts['barcode'] = $row['count'];
        } else {
            echo "Error in query for table $table: " . mysqli_error($db);
        }
    }
} 
else 
{
    $types = ['material', 'supply', 'accessory', 'fg', 'drawing'];

    foreach ($types as $type) {
        $query = getCountQuery($type, $searchValue);
        $result = mysqli_query($db, $query);

        if ($result) {
            $row = mysqli_fetch_assoc($result);
            $counts[$type] = $row['count'];
        } else {
            echo "Error in query for type $type: " . mysqli_error($db);
        }
    }
    
    $searchValueForRequest = $searchValue;
    $countsFromHostedScript = makeFileGetContentsRequest($searchValueForRequest);

    if ($countsFromHostedScript !== null) {
        // Merge the counts from the second script with the existing counts
        $counts = array_merge($counts, $countsFromHostedScript);
    } else {
        echo "nande";
    }
  
}
$jsonResponse = json_encode($counts);
echo $jsonResponse;
?>  