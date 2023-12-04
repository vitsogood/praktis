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


function hasImage($inventoryId)
{
    $extensions = ['jpg', 'jpeg', 'pdf', 'png'];
    for ($i = 0; $i < 10; $i++) {
        foreach ($extensions as $extension) {
            $filePath = $_SERVER['DOCUMENT_ROOT'] . '/Document Management System/Inventory Folder/' . $inventoryId . '_' . $i . '.' . $extension;
            if (file_exists($filePath) or file_exists($_SERVER['DOCUMENT_ROOT'] . '/Document Management System/Inventory Folder/' . $inventoryId . $extension)) {
                return true;
                break;
            }
        }
    }
    return false;
}

$requestData = $_REQUEST;
$sqlData = isset($requestData['sqlData']) ? $requestData['sqlData'] : "";
$totalRecords = (isset($requestData['totalRecords'])) ? $requestData['totalRecords'] : 0;
$totalFiltered = $totalRecords;
$totalData = $totalFiltered;


$data = array();
$i = 1;
$sql = $sqlData;
$sql .= " LIMIT ".$requestData['start']." ,".$requestData['length']."  ";
$counter = $requestData['start'];
$queryResult = $db->query($sql) or die ($db->error);

// $search = isset($_POST['search']) ? $_POST['search'] : ''; //here
$searchValue = isset($_POST['searchValue']) ? $_POST['searchValue'] : ''; //new
$highlighted = ''; //here

if ($queryResult AND $queryResult -> num_rows > 0)
{
    while ($requestData = $queryResult -> fetch_assoc())
    {
        $inventoryId = $requestData['inventoryId'];
        // $batchNumber = $requestData['batchNumber'];
        $supplier = $requestData['supplierAlias'];
        $stockDate = $requestData['stockDate'];
        $accessoryNumber = $requestData['dataOne'];
        $accessoryName = $requestData['dataTwo'];
        $APIcommonNumber = $requestData['APIcommonNumber'];
        $APIcommonName = $requestData['APIcommonName'];
        $inventoryQuantity = $requestData['inventoryQuantity'];
        $bookQuantity = $requestData['noBookFlag'];
        $availableStock = $requestData['availableStock'];
        $currentLocation = $requestData['inventoryLocation'];
        $inventoryRemarks = $requestData['inventoryRemarks'];
        $version = $requestData['version'];

        $nestedData = Array();

        $nestedData[] = ++$counter.'<input type="checkbox" class="checkbox" data-id="'. $inventoryId.'">'; 
        $nestedData[] = $inventoryId;
        // $nestedData[] = $supplier; //edit this

        if (!empty($searchValue)) {
            $searchWords = preg_split('/[\p{Zs}\s]+/u', $searchValue);
            $highlightedWords = [];
        
            $fileWords = preg_split('/\s+/', $supplier);
        
            foreach ($fileWords as $fileWord) {
                $highlightedWord = $fileWord;
        
                foreach ($searchWords as $searchWord) {
                    $regex = '/' . preg_quote($searchWord, '/') . '/i';
                    $highlightedWord = preg_replace($regex, '<span style="background-color: yellow;">$0</span>', $highlightedWord);
                }
        
                $highlightedWords[] = $highlightedWord;
            }
        
            $highlightedFileName = implode(' ', $highlightedWords);
            $nestedData[] =  $highlightedFileName; //with match
        } else {
            $highlightedFileName = $supplier;
            $nestedData[] =  $highlightedFileName; //without match
        }

        $nestedData[] = $stockDate;
        // $nestedData[] = $accessoryNumber; //edit this

        if (!empty($searchValue)) {
            $searchWords = preg_split('/[\p{Zs}\s]+/u', $searchValue);
            $highlightedAccNumber = [];
        
            $fileWords = preg_split('/\s+/', $accessoryNumber);
        
            foreach ($fileWords as $fileWord) {
                $highlightedWord = $fileWord;
        
                foreach ($searchWords as $searchWord) {
                    $regex = '/' . preg_quote($searchWord, '/') . '/i';
                    $highlightedWord = preg_replace($regex, '<span style="background-color: yellow;">$0</span>', $highlightedWord);
                }
        
                $highlightedAccNumber[] = $highlightedWord;
            }
        
            $highlightAccNumber = implode(' ', $highlightedAccNumber);
            $nestedData[] =  $highlightAccNumber; //with match
        } else {
            $highlightAccNumber = $accessoryNumber;
            $nestedData[] =  $highlightAccNumber; //without match
        }
        
        // $nestedData[] = $accessoryName; //edit this

        if (!empty($searchValue)) {
            $searchWords = preg_split('/[\p{Zs}\s]+/u', $searchValue);
            $highlightedAccName = [];
        
            $fileWords = preg_split('/\s+/', $accessoryName);
        
            foreach ($fileWords as $fileWord) {
                $highlightedWord = $fileWord;
        
                foreach ($searchWords as $searchWord) {
                    $regex = '/' . preg_quote($searchWord, '/') . '/i';
                    $highlightedWord = preg_replace($regex, '<span style="background-color: yellow;">$0</span>', $highlightedWord);
                }
        
                $highlightedAccName[] = $highlightedWord;
            }
        
            $highlightAccName = implode(' ', $highlightedAccName);
            $nestedData[] =  $highlightAccName; //with match
        } else {
            $highlightAccName = $accessoryName;
            $nestedData[] =  $highlightAccName; //without match
        }

        // $nestedData[] = $APIcommonNumber;
        // $nestedData[] = $APIcommonName;
        // $nestedData[] = $inventoryQuantity;
        $nestedData[] = $bookQuantity;
        $nestedData[] = $availableStock;
        $nestedData[] = $inventoryRemarks;
        $nestedData[] = $currentLocation;
        $nestedData[] = 'V'.$version;
        $nestedData[] = hasImage($inventoryId) ? "<button class='bg-primary text-white p-2 btn fw-bold' id = 'view' onclick='imageViewer(this,\"$inventoryId\")' data-target='$inventoryId'><i style='font-size:1.7em !important;' class='fa fa-search' aria-hidden='true'></i></button>" : "";
        
        $data[] = $nestedData;
    }
}
else
{
    echo "Error";
}

$json_data = array(
    "draw"            => intval( $requestData['draw'] ),   // for every request/draw by clientside , they send a number as a parameter, when they recieve a response/data they first check the draw number, so we are sending same number in draw. 
    "recordsTotal"    => intval( $totalData ),  // total number of records
    "recordsFiltered" => intval( $totalFiltered ), // total number of records after searching, if there is no searching then totalFiltered = totalData
    "data"            => $data   // total data array
);

echo json_encode($json_data);  // send data as json format