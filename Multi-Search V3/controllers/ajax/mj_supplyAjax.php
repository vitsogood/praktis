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
session_start();

function hasImage($inventoryId){
    $extensions = ['jpg', 'jpeg', 'pdf', 'png'];
    for($i = 0; $i < 10; $i ++){
		foreach ($extensions as $extension) {
			$filePath = $_SERVER['DOCUMENT_ROOT'] . '/Document Management System/Inventory Folder/' . $inventoryId . '_'.$i.'.'.$extension;
			if (file_exists($filePath) OR file_exists($_SERVER['DOCUMENT_ROOT'] . '/Document Management System/Inventory Folder/' . $inventoryId.$extension)) {
                return true;
				break;	
			}	
		}			
    }
    return false;
}

$_SESSION['idNumber'];
$permissionid = ['1176','1159','1163','0280'];

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
$count = $requestData['start'];
$queryResult = $db->query($sql) or die ($db->error);

// $search = isset($_POST['search']) ? $_POST['search'] : ''; //here
$searchValue = isset($_POST['searchValue']) ? $_POST['searchValue'] : ''; //new

$highlighted = ''; //here

if ($queryResult AND $queryResult -> num_rows > 0)
{
    while ($requestData = $queryResult -> fetch_assoc())
    {
        $inventoryId = $requestData['inventoryId'];
        $batchNumber = $requestData['batchNumber'];
        $supplier = $requestData['supplierAlias'];
        $stockDate = $requestData['stockDate'];
        $itemName = $requestData['dataOne'];
        $itemDesc = $requestData['dataTwo'];
        $inventoryQuantity = $requestData['inventoryQuantity'];
        $currentLocation = $requestData['inventoryLocation']; //currentlocation
        $expirationDate = $requestData['dataThree'] != "" ? $requestData['dataThree'] : "NA"; //expiration
        $inventoryRemarks = $requestData['inventoryRemarks'];
        $version = $requestData['version'];

        $nestedData = Array();
        $count = ++$count; // add by mj
        $nestedData[] = ++$counter.'<input type="checkbox" class="checkbox" data-id="'. $inventoryId.'">';
        $nestedData[] = $inventoryId;
        // $nestedData[] = $supplier; //edit this

        if (!empty($searchValue)) {
            // $searchWords = explode(' ', $searchValue);
            $searchWords = preg_split('/[\p{Zs}\s]+/u', $searchValue);
            $highlightedSupplier = [];
        
            $fileWords = preg_split('/\s+/', $supplier);
        
            foreach ($fileWords as $fileWord) {
                $highlightedWord = $fileWord;
        
                foreach ($searchWords as $searchWord) {
                    $regex = '/' . preg_quote($searchWord, '/') . '/i';
                    $highlightedWord = preg_replace($regex, '<span style="background-color: yellow;">$0</span>', $highlightedWord);
                }
        
                $highlightedSupplier[] = $highlightedWord;
            }
        
            $highlightSupplier = implode(' ', $highlightedSupplier);
            $nestedData[] =  $highlightSupplier; //with match
        } else {
            $highlightSupplier = $supplier;
            $nestedData[] =  $highlightSupplier; //without match
        }

        $nestedData[] = $stockDate;
        // $nestedData[] = $itemName; //edit this

        if (!empty($searchValue)) {
            // $searchWords = explode(' ', $searchValue);
            $searchWords = preg_split('/[\p{Zs}\s]+/u', $searchValue);
            $highlightedSupply = [];
        
            $fileWords = preg_split('/\s+/', $itemName);
        
            foreach ($fileWords as $fileWord) {
                $highlightedWord = $fileWord;
        
                foreach ($searchWords as $searchWord) {
                    $regex = '/' . preg_quote($searchWord, '/') . '/i';
                    $highlightedWord = preg_replace($regex, '<span style="background-color: yellow;">$0</span>', $highlightedWord);
                }
        
                $highlightedSupply[] = $highlightedWord;
            }
        
            $highlightSupply = implode(' ', $highlightedSupply);
            $nestedData[] =  $highlightSupply; //with match
        } else {
            $highlightSupply = $itemName;
            $nestedData[] =  $highlightSupply; //without match
        }

        // $nestedData[] = $itemDesc; //edit this

        if (!empty($searchValue)) {
            // $searchWords = explode(' ', $searchValue);
            $searchWords = preg_split('/[\p{Zs}\s]+/u', $searchValue);
            $highlightedDesc = [];
        
            $fileWords = preg_split('/\s+/', $itemDesc);
        
            foreach ($fileWords as $fileWord) {
                $highlightedWord = $fileWord;
        
                foreach ($searchWords as $searchWord) {
                    $regex = '/' . preg_quote($searchWord, '/') . '/i';
                    $highlightedWord = preg_replace($regex, '<span style="background-color: yellow;">$0</span>', $highlightedWord);
                }
        
                $highlightedDesc[] = $highlightedWord;
            }
        
            $highlightDesc = implode(' ', $highlightedDesc);
            $nestedData[] =  $highlightDesc; //with match
        } else {
            $highlightDesc = $itemDesc;
            $nestedData[] =  $highlightDesc; //without match
        }

        $nestedData[] = $inventoryQuantity;
        // $nestedData[] = $currentLocation; //edit this

       

        $nestedData[] = $expirationDate; //expiration date
        $nestedData[] = $inventoryRemarks;
        if (!empty($searchValue)) {
            // $searchWords = explode(' ', $searchValue);
            $searchWords = preg_split('/[\p{Zs}\s]+/u', $searchValue);
            $highlightedLoc = [];

            $fileWords = preg_split('/\s+/', $currentLocation);

            foreach ($fileWords as $fileWord) {
                $highlightedWord = $fileWord;

                foreach ($searchWords as $searchWord) {
                    $regex = '/' . preg_quote($searchWord, '/') . '/i';
                    $highlightedWord = preg_replace($regex, '<span style="background-color: yellow;">$0</span>', $highlightedWord);
                }

                $highlightedLoc[] = $highlightedWord;
            }

            $highlightLoc = implode(' ', $highlightedLoc);
            $nestedData[] =  $highlightLoc; //with match
        } else {
            $highlightLoc = $currentLocation;
            $nestedData[] =  $highlightLoc; //without match
        }
        $nestedData[]='V'.$version;
        // $nestedData[] = "<button class='bg-success text-white p-2 btn fw-bold' id = 'save' onclick='edit(this, \"$inventoryId\",$count)' data-target='$inventoryId'><i style='font-size:1.6em !important;' class='fa fa-pen' aria-hidden='true'></i></button>";
        $nestedData[] = hasImage($inventoryId)? "<button class='bg-primary text-white p-2 btn fw-bold' id = 'view' onclick='imageViewer(this,\"$inventoryId\")' data-target='$inventoryId'><i style='font-size:1.7em !important;' class='fa fa-search' aria-hidden='true'></i></button>":"" ;
        
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