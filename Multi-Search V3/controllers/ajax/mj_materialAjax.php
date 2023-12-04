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

$requestData = $_REQUEST;
$sqlData = isset($requestData['sqlData']) ? $requestData['sqlData'] : "";
$totalRecords = (isset($requestData['totalRecords'])) ? $requestData['totalRecords'] : 0;
$totalFiltered = $totalRecords;
$totalData = $totalFiltered;
session_start();
$_SESSION['idNumber'];
$permissionid = ['1176','1159','1163','0280'];

    // function getBookingQuantity($inventoryId){
    //     include('PHP Modules/mysqliConnection.php');
    //     $data = 0;
    //     $sql = "SELECT SUM(bookingQuantity) as total  FROM `engineering_booking` WHERE `inventoryId` LIKE '$inventoryId' AND `bookingStatus` = 0 AND `nestingType` IN(1,2)";
    //     $query = $db->query($sql);
    //     if ($query and $query->num_rows > 0) {
    //         $requestData = $query->fetch_assoc();
    //         return $data = ($requestData['total'] != NULL)? $requestData['total']: 0;
            
    //     }
    //     return $data;
        
    // }

$data = array();
$i = 1;
$sql = $sqlData;
$sql .= " LIMIT ".$requestData['start']." ,".$requestData['length']."  ";
$counter = $requestData['start'];
$count = $requestData['start'];
$queryResult = $db->query($sql) or die ($db->error);

// $search = isset($_POST['search']) ? $_POST['search'] : ''; //here
$searchValue = isset($_POST['searchValue']) ? $_POST['searchValue'] : ''; //new

// Now you can use $searchValue in mj_materialAjax.php

$highlighted = ''; //here

if ($queryResult AND $queryResult -> num_rows > 0)
{
    while ($requestData = $queryResult -> fetch_assoc())
    {
        $inventoryIdEdit = $requestData['inventoryId'];
        $inventoryId = $requestData['inventoryId'];
        $id = $requestData['inventoryId'];
        $batchNumber = $requestData['batchNumber'];
        $version = $requestData['version'];

        $sql = "SELECT cocNumber FROM cocDocuments WHERE cocLotNumber LIKE '".trim($batchNumber)."' ORDER BY cocId DESC LIMIT 1 ";
        $queryCOCLotNumber = $db->query($sql);
        if($queryCOCLotNumber AND $queryCOCLotNumber->num_rows == 0)
        {
            $errorMessage = "No Mill Certificate!!";
        }
        else
        {
            $resultCOCLotNumber = $queryCOCLotNumber->fetch_assoc();
            $MILLCERTstring1=$resultCOCLotNumber['cocNumber'];

            $inventoryId = "<span style='cursor:pointer;color:blue;text-decoration:underline;' onclick=\" window.open('../../cocprint/coc_documents/".$MILLCERTstring1."','myWindow3','left=50,screenX=40,screenY=60,resizable,scrollbars,status,width=1100,height=500'); return false; \">".$inventoryId."</span>";
        }

        $count = ++$count;
        $dataType = $requestData['type'];
        $supplier = $requestData['supplierAlias'];
        $stockDate = $requestData['stockDate'];
        $returnDate = $requestData['returnDate'];
        if ($returnDate != "0000-00-00")
        {
            $stockDate = $returnDate;
        }
        $type = $requestData['dataOne'];
        $thickness = $requestData['dataTwo'];

        $length = $requestData['dataThree'];
        $width = $requestData['dataFour'];
        $treatment = $requestData['dataFive'];
        // $treatment = ($treatment =='Raw') ? 'Standard' : $treatment;
        $sType = $requestData['dataSix'];
        // $treatment = ($sType == 'RAW') ? 'Standard' : $sType;

       if ($sType == 0 OR $sType == 3 )
       {
        $sType = displayText("L1368", "utf8", 0, 0, 1);
       }
       else if ($sType == 1)
       {
        $sType = displayText("L468", "utf8", 0, 0, 1);
       }
       else if ($sType == 2 )
       {
        $sType = displayText("L10013", "utf8", 0, 0, 1);
       }


        $inventoryQuantity = $requestData['inventoryQuantity'];

        $stock = $inventoryQuantity;
        $inventory = new Inventory($requestData);
        $stock = $inventory->stock();
        $totalBookingQty = $inventory->quantityBooked(0);
        $totalTempBookingQty = $inventory->quantityBooked(1);
        $quantityWithdrawn = $inventory->quantityWithdrawn();


        $availableStock = $requestData['availableStock'];

        $availableStock = $stock - $totalBookingQty - $totalTempBookingQty;
        $weight = $requestData['unitWeight'];

        $sql = "SELECT baseWeight, coatingWeight FROM engineering_materialtype WHERE materialType LIKE '" . $type . "' LIMIT 1";
        $queryMaterialType = $db->query($sql);
        if ($queryMaterialType and $queryMaterialType->num_rows > 0) {
            $resultMaterialType = $queryMaterialType->fetch_assoc();
            $baseWeight = $resultMaterialType['baseWeight'];
            $coatingWeight = $resultMaterialType['coatingWeight'];

            $var1 = (($baseWeight * $thickness) + $coatingWeight);
            $var2 = ($length / 1000);
            $var3 = ($width / 1000);
            $weight = ($var1 * $var2 * $var3);
        }

        // $stock = $inventoryQuantity;
        // $inventory = new Inventory($result);
        // $stock = $inventory->stock();
        // $totalBookingQty = $inventory->quantityBooked(0);
        // $totalTempBookingQty = $inventory->quantityBooked(1);
        // $quantityWithdrawn = $inventory->quantityWithdrawn();

        // $availableStock = $stock - $totalBookingQty - $totalTempBookingQty;




        // $bends = $requestData['bendStatus'] == 0 ? "NO" : "YES";
        $scratch = $requestData['scratchStatus'] == 0 ? "NO" : "YES";
        $pvc = $requestData['pvcStatus'] == 0 ? "NO" : "YES";
        $currentLocation = $requestData['inventoryLocation'];


        $nestedData = Array();
        $nestedData[] = ++$counter.'<input type="checkbox" class="checkbox" data-id="'.$id.'">'; //uncomment after testing
        // $nestedData[] = $searchValue; //tester
        $nestedData[] = $inventoryId;
        

        // $nestedData[] = $supplier; //edit this

        if (!empty($searchValue)) {
            // $searchWords = explode(' ', $searchValue);
            $searchWords = preg_split('/[\p{Zs}\s]+/u', $searchValue);   
            $highlightedSupplier = [];
        
            // Remove multiple consecutive spaces and trim the supplier text
            $supplier = preg_replace('/\s+/', ' ', trim($supplier));

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
             // Remove multiple consecutive spaces and trim the supplier text
             $supplier = preg_replace('/\s+/', ' ', trim($supplier));

            $highlightSupplier = $supplier;
            $nestedData[] =  $highlightSupplier; //without match
        }


        $nestedData[] = $stockDate;
        // $nestedData[] = $type; //edit this

        if (!empty($searchValue)) {
            // $searchWords = explode(' ', $searchValue);
            $searchWords = preg_split('/[\p{Zs}\s]+/u', $searchValue);   
            $highlightedType = [];

            $type = preg_replace('/\s+/', ' ', trim($type));
        
            $fileWords = preg_split('/\s+/', $type);
        
            foreach ($fileWords as $fileWord) {
                $highlightedWord = $fileWord;
        
                foreach ($searchWords as $searchWord) {
                    $regex = '/' . preg_quote($searchWord, '/') . '/i';

                    $highlightedWord = preg_replace($regex, '<span style="background-color: yellow;">$0</span>', $highlightedWord);
                }
        
                $highlightedType[] = $highlightedWord;
            }
        
            $highlightedFileName = implode(' ', $highlightedType);
            $nestedData[] =  $highlightedFileName; //with match
        } else {
            $type = preg_replace('/\s+/', ' ', trim($type));
            
            $highlightedFileName = $type;
            $nestedData[] =  $highlightedFileName; //without match
        }




        // $nestedData[] = $thickness; //edit this

        if (!empty($searchValue)) {
            // $searchWords = explode(' ', $searchValue);
            $searchWords = preg_split('/[\p{Zs}\s]+/u', $searchValue);   
            $highlightedThickness = [];
        
            $fileWords = preg_split('/\s+/', $thickness);
        
            foreach ($fileWords as $fileWord) {
                $highlightedWord = $fileWord;
        
                foreach ($searchWords as $searchWord) {
                    $regex = '/' . preg_quote($searchWord, '/') . '/i';
                    $highlightedWord = preg_replace($regex, '<span style="background-color: yellow;">$0</span>', $highlightedWord);
                }
        
                $highlightedThickness[] = $highlightedWord;
            }
        
            $highlightedFileName = implode(' ', $highlightedThickness);
            $nestedData[] =  $highlightedFileName; //with match
        } else {
            $highlightedFileName = $thickness;
            $nestedData[] =  $highlightedFileName; //without match
        }

        // $nestedData[] = $length; //edit this

        if (!empty($searchValue)) {
            // $searchWords = explode(' ', $searchValue);
            $searchWords = preg_split('/[\p{Zs}\s]+/u', $searchValue);   
            $highlightedLength = [];
        
            $fileWords = preg_split('/\s+/', $length);
        
            foreach ($fileWords as $fileWord) {
                $highlightedWord = $fileWord;
        
                foreach ($searchWords as $searchWord) {
                    $regex = '/' . preg_quote($searchWord, '/') . '/i';
                    $highlightedWord = preg_replace($regex, '<span style="background-color: yellow;">$0</span>', $highlightedWord);
                }
        
                $highlightedLength[] = $highlightedWord;
            }
        
            $highlightedFileName = implode(' ', $highlightedLength);
            $nestedData[] =  $highlightedFileName; //with match
        } else {
            $highlightedFileName = $length;
            $nestedData[] =  $highlightedFileName; //without match
        }


        // $nestedData[] = $width; //edit this

        if (!empty($searchValue)) {
            $searchWords = preg_split('/[\p{Zs}\s]+/u', $searchValue);   
            $highlightedWidth = [];
        
            $fileWords = preg_split('/\s+/', $width);
        
            foreach ($fileWords as $fileWord) {
                $highlightedWord = $fileWord;
        
                foreach ($searchWords as $searchWord) {
                    $regex = '/' . preg_quote($searchWord, '/') . '/i';
                    $highlightedWord = preg_replace($regex, '<span style="background-color: yellow;">$0</span>', $highlightedWord);
                }
        
                $highlightedWidth[] = $highlightedWord;
            }
        
            $highlightedFileName = implode(' ', $highlightedWidth);
            $nestedData[] =  $highlightedFileName; //with match
        } else {
            $highlightedFileName = $width;
            $nestedData[] =  $highlightedFileName; //without match
        }



        // $nestedData[] = $treatment; //edit this

        if (!empty($searchValue)) {
            // $searchWords = explode(' ', $searchValue);
            $searchWords = preg_split('/[\p{Zs}\s]+/u', $searchValue);              
            $highlightedTreatment = [];
        
            $fileWords = preg_split('/\s+/', $treatment);
        
            foreach ($fileWords as $fileWord) {
                $highlightedWord = $fileWord;   
        
                foreach ($searchWords as $searchWord) {
                    $regex = '/' . preg_quote($searchWord, '/') . '/i';
                    $highlightedWord = preg_replace($regex, '<span style="background-color: yellow;">$0</span>', $highlightedWord);
                }
        
                $highlightedTreatment[] = $highlightedWord;
            }
        
            $highlightedFileName = implode(' ', $highlightedTreatment);
            $nestedData[] =  $highlightedFileName; //with match
        } else {
            $highlightedFileName = $treatment;
            $nestedData[] =  $highlightedFileName; //without match
        }


        $nestedData[] = $sType;
        $nestedData[] = $stock;
        $nestedData[] = $totalBookingQty+ $totalTempBookingQty;
        // $nestedData[] = $totalTempBookingQty;
        $nestedData[] = $availableStock;
        $nestedData[] = number_format($weight,2);
        // $nestedData[] = $bends;
        $nestedData[] = $scratch;
        $nestedData[] = $pvc;
        $nestedData[] = $currentLocation;
        $nestedData[] = 'V'.$version; 
            // $nestedData[] = "<button class='bg-success text-white p-2 btn fw-bold' id='save' onclick='edit(this, \"$inventoryIdEdit\", $count)' data-target='$inventoryIdEdit'>
            // <i style='font-size:1.6em !important;' class='fa fa-pen' aria-hidden='true'></i></button>";
       
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