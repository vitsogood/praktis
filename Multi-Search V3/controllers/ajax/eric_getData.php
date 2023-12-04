7<?php 

include $_SERVER['DOCUMENT_ROOT']."/version.php";
$path = $_SERVER['DOCUMENT_ROOT']."/".v."/Common Data/";
set_include_path($path);
include('PHP Modules/mysqliConnectionDemo.php');
include('PHP Modules/gerald_functions.php');
// include('PHP Modules/anthony_retrieveText.php');
// include("PHP Modules/anthony_wholeNumber.php");
// include("PHP Modules/rose_prodfunctions.php");
ini_set("display_errors", "on");

$requestData= $_REQUEST;
$sqlData = isset($requestData['sql']) ? $requestData['sql'] : "";
$type = isset($requestData['drawingType']) ? $requestData['drawingType'] : "";
if($type == "ark")
{
    $folder = 'Arktech';
    $fileNameStart = 'ARK';
}
if($type == "cus")
{
    $folder = 'Master';
    $fileNameStart = 'MAIN';
}
$data = array();
$config = explode("*", $sqlData);
$fullSql = implode("partId, partNumber, partName", $config);
$sql = $fullSql; // first query
$queryDMS = $db->query($sql);
$x = 0;
if ($queryDMS && $queryDMS->num_rows > 0) {
    while ($resultDMS = $queryDMS->fetch_assoc()) {
        $partId = $resultDMS['partId'];
        // $partId = $resultDMS['partName'];
        //  $partId = $resultDMS['partName'];
        $filePath =  $_SERVER['DOCUMENT_ROOT'] ."/Document Management System/".$folder." Folder/".$fileNameStart."_".$partId.".pdf";
        if(file_exists($filePath))
        {
            $data[] =  $resultDMS;
        }
        else
        {
            // $data[$filePath] = $partId;
            continue;
        }
        // $data[] = $resultDMS['partId'];
    }
}
header("Content-Type: application/json");
echo json_encode($data);

?>