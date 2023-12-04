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

$partId = isset($_POST['partId']) ? $_POST['partId'] : "";
$type = isset($_POST['type']) ? $_POST['type'] : "";


if($type == 1)
{
    $patternIdArray = Array ();
    $sql = "SELECT DISTINCT patternId FROM cadcam_partprocess WHERE partId = ".$partId." ORDER BY patternId";
    $queryPartProcess = $db->query($sql);
    if($queryPartProcess AND $queryPartProcess->num_rows > 0)
    {
        while($resultPartProcess = $queryPartProcess->fetch_assoc())
        {
            $patternIdArray[] = $resultPartProcess['patternId'];
        }
    }

    $pdfUrls = array();
    foreach ($patternIdArray as $key) 
    {
        $sql = "SELECT * FROM cadcam_partprocess WHERE partId = ".$partId." AND patternId = ".$key." ORDER BY processOrder";
        $queryPartProcess = $db->query($sql);
        if($queryPartProcess AND $queryPartProcess->num_rows > 0)
        {
            while($resultPartProcess = $queryPartProcess->fetch_assoc())
            {
                $processCode = $resultPartProcess['processCode'];
                $processDetail = $resultPartProcess['processDetail'];
                $pdFile = $_SERVER['DOCUMENT_ROOT']."/Document Management System/Work Instruction Folder/".$processCode."_".$partId.".pdf";
                if(file_exists($pdFile) > 0)  
                {
                    $pdfUrls[] = 'http://192.168.254.163/Document Management System/Work Instruction Folder/'. $processCode .'_'. $partId .'.pdf';
                    // echo 'http://192.168.254.163/Document Management System/Work Instruction Folder/'. $processCode .'_'. $partId .'.pdf';
                }            
            }
        }
    }
}
echo json_encode($pdfUrls); 
?>