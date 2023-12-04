<style>
.flex {
    display: flex;
    flex-wrap: wrap;
    width: 100%;
}

iframe {
    width: 775px;
    height: 335px;
}
</style>


<?php
include $_SERVER['DOCUMENT_ROOT']."/version.php";
$path = $_SERVER['DOCUMENT_ROOT']."/".v."/Common Data/";
set_include_path($path);    
include('PHP Modules/mysqliConnection.php');
include('PHP Modules/anthony_wholeNumber.php');
include('PHP Modules/anthony_retrieveText.php');
include('PHP Modules/gerald_functions.php');
ini_set("display_errors", "on");
$ctrl = new PMSDatabase;
$tpl = new PMSTemplates;

$partId = isset($_POST['partId']) ? $_POST['partId'] : "hello";

$arkDrawing = $_SERVER['DOCUMENT_ROOT'] . "/Document Management System/Arktech Folder/ARK_".$partId.".pdf";
$cusDrawing = $_SERVER['DOCUMENT_ROOT'] ."/Document Management System/Master Folder/MAIN_".$partId.".pdf";
$drawing3DF = $_SERVER['DOCUMENT_ROOT'] . "/Document Management System/3D Files/3DF_".$partId.".PDF";

$arktech = "/Document Management System/Arktech Folder/ARK_".$partId.".pdf";
$customer =  "/Document Management System/Master Folder/MAIN_".$partId.".pdf";
$pdf3d = "/Document Management System/3D Files/3DF_".$partId.".PDF";
// echo "<div class='flex'>";
if(file_exists($arkDrawing))
{
    ?><iframe src="<?php echo $arktech ?>" frameborder="0"></iframe><?php
}
else
{
    echo "<iframe src='image.png' class = 'nodata'></iframe>";
}

if(file_exists($cusDrawing))
{
    ?><iframe src="<?php echo $customer ?>" frameborder="0"></iframe><?php
}
else
{
    echo "<iframe src='image.png' class = 'nodata'></iframe>";
}

if(file_exists($drawing3DF))
{
    ?><iframe src="<?php echo $pdf3d ?>" frameborder="0"></iframe><?php
}
else
{
    echo "<iframe src='image.png' class = 'nodata'></iframe>";
}

echo "</div>";

?>