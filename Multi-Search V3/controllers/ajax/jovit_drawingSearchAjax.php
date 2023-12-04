<?php 

include $_SERVER['DOCUMENT_ROOT']."/version.php";
$path = $_SERVER['DOCUMENT_ROOT']."/".v."/Common Data/";
set_include_path($path);
include('PHP Modules/mysqliConnectionDemo.php');
include('PHP Modules/gerald_functions.php');
include('PHP Modules/anthony_retrieveText.php');
include("PHP Modules/anthony_wholeNumber.php");
include("PHP Modules/rose_prodfunctions.php");
ini_set("display_errors", "on");

function hasDrawing($db,$lotNumber)
{
    $drawingConfigArray = [
        [
            'type' => 'MAIN',
            'folder' => 'Master Folder',
            'caption' => displayText('L24', 'utf8', 0, 0, 1)
        ],
        [
            'type' => 'ARK',
            'folder' => 'Arktech Folder',
            'caption' => displayText('L876', 'utf8', 0, 0, 1)
        ],
        [
            'type' => '3D',
            'folder' => '3D Drawing',
            'caption' => displayText('L4157', 'utf8', 0, 0, 1)
        ],
        [
            'type' => '3DF',
            'folder' => '3D Files',
            'caption' => displayText('L4338', 'utf8', 0, 0, 1)
        ],
      
    ];

    $sql = "SELECT partId FROM ppic_lotlist WHERE lotNumber = '" . $lotNumber . "'";
    $queryLotlist = $db->query($sql);
    if ($queryLotlist and $queryLotlist->num_rows > 0) {
        $resultLotlist = $queryLotlist->fetch_assoc();
        $partId = $resultLotlist['partId'];
    }

    $rootFolder = '../../Document Management System';
    $count = 0;
    foreach ($drawingConfigArray as $data) {
        $type = $data['type'];
        $folder = $data['folder'];
        $caption = $data['caption'];

        $fileName = ($type == '3DF') ? $type . "_" . $partId . ".PDF" : $type . "_" . $partId . ".pdf";
        $path = $rootFolder . "/" . $folder . "/" . $fileName;

        if (file_exists($path)) {
            $count++;
        }
    }

    return $count > 0? TRUE: FALSE;

}


function processDrawing($db, $partId)
{


    $sql = "SELECT processCode FROM cadcam_partprocess WHERE partId = ?";
    $stmt = $db->prepare($sql);
    $stmt->bind_param("s", $partId);
    $stmt->execute();
    $queryLotlist = $stmt->get_result();

 
    $flag = false;

    if ($queryLotlist && $queryLotlist->num_rows > 0) {
        while ($resultLotlist = $queryLotlist->fetch_assoc()) {
            $processCode = $resultLotlist['processCode'];
            $path = $_SERVER['DOCUMENT_ROOT'] . "/Document Management System/Work Instruction Folder/".$processCode."_" . $partId . ".pdf";
            if (file_exists($path)) {
                $flag = true;     
                if($flag == true) break;
            }
        }
    }

    return $flag;
}



$requestData= $_REQUEST;
$sqlData = isset($requestData['sqlData']) ? $requestData['sqlData'] : "";
$totalRecords = (isset($requestData['totalRecords'])) ? $requestData['totalRecords'] : 0;
$totalFiltered = $totalRecords;
$totalData = $totalFiltered;

$data = array();
$i = 1;

$sql = $sqlData; // first query
$sql .= " LIMIT ".$requestData['start']." ,".$requestData['length']."  ";
$counter = $requestData['start'];
$queryResult = $db->query($sql) or die ($db->error);

$searchValue = isset($_POST['searchValue']) ? $_POST['searchValue'] : ''; //new
$highlighted = ''; //here

if ($queryResult AND $queryResult -> num_rows > 0)
{
    
    
    while ($requestData = $queryResult -> fetch_assoc())
    {
        $customerId = $requestData['customerId'];
        $partNumber = $requestData['partNumber'];
        $partName = $requestData['partName'];
        $revisionId = $requestData['revisionId'];
        $partId = $requestData['partId'];            

        $customerSql = "SELECT customerAlias FROM `sales_customer` WHERE customerId = ".$customerId ."";

        $result = $db->query($customerSql) or die ($db->error);

        if ($result && $result -> num_rows > 0)
        {
            while ($requestData = $result -> fetch_assoc())
            {
                
                $cusDrawing = $_SERVER['DOCUMENT_ROOT']."/Document Management System/Master Folder/MAIN_".$partId.".pdf";
                $arkDrawing = $_SERVER['DOCUMENT_ROOT']."/Document Management System/Arktech Folder/ARK_".$partId.".pdf";
                $drawing3DF = $_SERVER['DOCUMENT_ROOT']."/Document Management System/3D Files/3DF_".$partId.".PDF";
                $isometricDrawing = $_SERVER['DOCUMENT_ROOT'] . "/Document Management System/3D Drawing/3D_" . $partId . ".pdf";
                $workFolder = $_SERVER['DOCUMENT_ROOT'] . "/Document Management System/Work Instruction Folder/WI_" . $partId . ".pdf";
                
                $workInstruction = "<i onclick=\"showWI('".$partId."');\" style='cursor:pointer;' class='fa fa-photo w3-medium w3-text-purple' title='WI Drawing'></i>";
                // $workInstructions = "<i onclick=\"drawingViewer('".$partId."',this,'WI');\" style='cursor:pointer;' class='fa fa-photo w3-medium w3-text-purple' title='WI Drawing'></i>";

                // $workInstructions = "<i onclick=\"showAllDraw('".$partId."');\" style='cursor:pointer;' class='fa fa-photo w3-medium w3-text-purple' title='WI Drawing'></i>";

        
                $flag = 0;
            
                $viewUrl = "drawingViewer('".$partId."',this, 'WI');";
                $workIns = "<i style='cursor:pointer;' class='fa fa-photo w3-medium w3-text-purple' onclick=\"".$viewUrl."\" title='Customer'></i>" ;

                $cusDwg = (file_exists($cusDrawing)) ?
                "<i style='cursor:pointer;' class='fa fa-photo w3-medium w3-text-purple' onclick=\"drawingViewer('".$partId."',this, 'MAIN');\" title='Customer'></i>" :
                "<font color='black'></font>";
            

               

                if(file_exists($arkDrawing))
                {
                    
                    $ext = pathinfo($arkDrawing, PATHINFO_EXTENSION);
                    $url = "window.open('/Document Management System/Arktech Folder/ARK_".$partId.".".$ext."','cc','left=50,screenX=700,screenY=20,resizable,scrollbars,status,width=1000,height=700')";
                    $viewUrl = "drawingViewer('".$partId."',this, 'ARK');";
                    $arkDwg = "<i style='cursor:pointer;' class='fa fa-photo w3-medium w3-text-purple' onclick=\"".$viewUrl."\" title='Arktech Drawing'></i>";
                }
                

                $dwg3DF = "";
                if(file_exists($drawing3DF))
                {
                    $ext = pathinfo($drawing3DF, PATHINFO_EXTENSION);
                    $dwg3DF = "<a href='/Document Management System/3D Files/3DF_".$partId.".".$ext."' download><i style='cursor:pointer;' class='fa fa-photo w3-medium w3-text-purple' title='3D PDF'></i></a>";
                }

               
                
                if(file_exists($isometricDrawing))
                {
                    
                    $ext = pathinfo($isometricDrawing, PATHINFO_EXTENSION);
                    $url = "window.open('/Document Management System/3D Drawing/3D_".$partId.".".$ext."','cc','left=50,screenX=700,screenY=20,resizable,scrollbars,status,width=1000,height=700')";
                    $viewUrl = "drawingViewer('".$partId."',this, '3D');";
                    $isometricDwg = "<i style='cursor:pointer;' class='fa fa-photo w3-medium w3-text-purple' onclick=\"".$viewUrl."\" title='Isometric'></i>";
                }

                
                $customerAlias = $requestData['customerAlias'];

                $nestedData = Array();
                $hiddenVal = ($flag == 1)?  "<input type ='hidden' class='listId' value = '$partId' >" : "";
                $nestedData[] = $hiddenVal.++$counter;
                $nestedData[] = $customerAlias;
                // $nestedData[] = $partNumber;

                // $nestedData[] = $partNumber; //edit this

                if (!empty($searchValue)) {
                    // $searchWords = explode(' ', $searchValue);
                    $searchWords = preg_split('/[\p{Zs}\s]+/u', $searchValue);
                    $highlightedPartNumber = [];
                
                    $fileWords = preg_split('/\s+/', $partNumber);
                
                    foreach ($fileWords as $fileWord) {
                        $highlightedWord = $fileWord;
                
                        foreach ($searchWords as $searchWord) {
                            $regex = '/' . preg_quote($searchWord, '/') . '/i';
                            $highlightedWord = preg_replace($regex, '<span style="background-color: yellow;">$0</span>', $highlightedWord);
                        }
                
                        $highlightedPartNumber[] = $highlightedWord;
                    }
                
                    $highlightPartNumber = implode(' ', $highlightedPartNumber);
                    $nestedData[] =  $highlightPartNumber; //with match
                } else {
                    $highlightPartNumber = $partNumber;
                    $nestedData[] =  $highlightPartNumber; //without match
                }

                // $nestedData[] = $partName; //edit this

                if (!empty($searchValue)) {
                    // $searchWords = explode(' ', $searchValue);
                    $searchWords = preg_split('/[\p{Zs}\s]+/u', $searchValue);
                    $highlightedPartName = [];
                
                    $fileWords = preg_split('/\s+/', $partName);
                
                    foreach ($fileWords as $fileWord) {
                        $highlightedWord = $fileWord;
                
                        foreach ($searchWords as $searchWord) {
                            $regex = '/' . preg_quote($searchWord, '/') . '/i';
                            $highlightedWord = preg_replace($regex, '<span style="background-color: yellow;">$0</span>', $highlightedWord);
                        }
                
                        $highlightedPartName[] = $highlightedWord;
                    }
                
                    $highlightPartName = implode(' ', $highlightedPartName);
                    $nestedData[] =  $highlightPartName; //with match
                } else {
                    $highlightPartName = $partName;
                    $nestedData[] =  $highlightPartName; //without match
                }
    
                $nestedData[] = $revisionId;
                $nestedData[] = $cusDwg;
                $nestedData[] = $arkDwg;
                $nestedData[] = $isometricDwg;
                $nestedData[] = $dwg3DF;
                $nestedData[] = processDrawing($db , $partId) ? $workIns : '' ;
                
                $data[] = $nestedData;

                
            }
        }
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