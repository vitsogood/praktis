<?php
include $_SERVER['DOCUMENT_ROOT']."/version.php";
$path = $_SERVER['DOCUMENT_ROOT']."/".v."/Common Data/";
set_include_path($path);    
include('PHP Modules/mysqliConnection.php');
include('PHP Modules/anthony_wholeNumber.php');
include('PHP Modules/anthony_retrieveText.php');
include('PHP Modules/gerald_functions.php');
include("PHP Modules/rose_prodfunctions.php");
ini_set("display_errors", "on");
$obj = new PMSDatabase;
$tpl = new PMSTemplates;

$requestData= $_REQUEST;
$sqlData = isset($requestData['sqlData']) ? $requestData['sqlData'] : "";
$exportExcelData = isset($requestData['exportExcelData']) ? $requestData['exportExcelData'] : "";
$totalRecords = (isset($requestData['totalRecords'])) ? $requestData['totalRecords'] : 0;
$totalFiltered = $totalRecords;
$totalData = $totalFiltered;

$data = array();
$sql = $sqlData;
$sql.=" LIMIT ".$requestData['start']." ,".$requestData['length']."   ";
$counter = $requestData['start'];
$obj->setSQLQuery($sql);
$templatesData = $obj->getRecords();
if($templatesData != NULL)
{	
    foreach ($templatesData as $resultTemplates)
    {
        $templateId = $resultTemplates['id'];
        $element = $resultTemplates['element'];
        $pc1 = $resultTemplates['pc1'];
        $ipadPortrait = $resultTemplates['ipadPortrait'];
        $ipadLandscape = $resultTemplates['ipadLandscape'];

        $phonePortrait = $resultTemplates['phonePortrait'];
        $phoneLandscape = $resultTemplates['phoneLandscape'];

        $selector = $resultTemplates['selector'];

        $cssProperty = $resultTemplates['cssProperty'];
       
        
        $nestedData = Array ();
        $nestedData[] = "<b>".++$counter."</b>";
        $nestedData[] = $element;
        $nestedData[] = "<input type='text' style='outline:none;background-color:transparent;border:none;text-align:center;'class='editable-cell' value='$pc1' data-template-id='$templateId' data-column-name='pc1' data-css='$selector' data-css-property='$cssProperty'>" ;
    	$nestedData[] = "<input type='text' style='outline:none;background-color:transparent;border:none;text-align:center;' class='editable-cell' value='$phonePortrait' data-template-id='$templateId' data-column-name='phonePortrait' data-css='$selector' data-css-property='$cssProperty'>";
        $nestedData[] = "<input type='text' style='outline:none;background-color:transparent;border:none;text-align:center;' class='editable-cell' value='$phoneLandscape' data-template-id='$templateId' data-column-name='phoneLandscape' data-css='$selector' data-css-property='$cssProperty'>";
        $nestedData[] = "<input type='text' style='outline:none;background-color:transparent;border:none;text-align:center;' class='editable-cell' value='$ipadPortrait' data-template-id='$templateId' data-column-name='ipadPortrait' data-css='$selector' data-css-property='$cssProperty'>";
        $nestedData[] = "<input type='text' style='outline:none;background-color:transparent;border:none;text-align:center;' class='editable-cell' value='$ipadLandscape' data-template-id='$templateId' data-column-name='ipadLandscape' data-css='$selector' data-css-property='$cssProperty'>";
        
        
        $data[] = $nestedData;
    }
}

$json_data = array(
            "draw"            => intval( $requestData['draw'] ),  // for every request/draw by clientside , they send a number as a parameter, when they recieve a response/data they first check the draw number, so we are sending same number in draw. 
            "recordsTotal"    => intval( $totalData ),  // total number of records
            "recordsFiltered" => intval( $totalFiltered ), // total number of records after searching, if there is no searching then totalFiltered = totalData
            "data"            => $data   // total data array
    );

echo json_encode($json_data);  // send data as json format
?>