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

$partId = isset($_POST['partId']) ? $_POST['partId'] : "";
$type = isset($_POST['type']) ? $_POST['type'] : "";

echo "Part ID: " . $partId . "<br>";
echo "Type: " . $type . "<br>";
?>
<style>
#casPoItemTable thead {
    position: -webkit-sticky;
    position: sticky;
    top: 0;
}

#casPoItemTable tfoot {
    position: -webkit-sticky;
    position: sticky;
    bottom: 0;
}
</style>
<?php
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
    
    echo "<div class='row'>";
        foreach ($patternIdArray as $key) 
        {
            echo "<div class='col-md-12 w3-padding-top'>";
                echo "<label>PATTERN : ".$key."</label>";
                echo "<div style='width:100%;max-height:80vh;overflow-y:scroll;'>";
                echo "<table class='table table-bordered table-condensed table-striped' id='casPoItemTable'>";
                    echo "<thead class='w3-indigo'>";
                        echo "<th class='w3-center'>#</th>";
                        echo "<th class='w3-center'>PROCESS</th>";
                        echo "<th class='w3-center'>PROCESS DETAIL</th>";
                        echo "<th class='w3-center'>DMS WI</th>";
                        echo "<th class='w3-center'>DMS 3D</th>";//TAMANG
                    echo "</thead>";
                    echo "<tbody>";
                    $sql = "SELECT * FROM cadcam_partprocess WHERE partId = ".$partId." AND patternId = ".$key." ORDER BY processOrder";
                    $queryPartProcess = $db->query($sql);
                    if($queryPartProcess AND $queryPartProcess->num_rows > 0)
                    {
                        while($resultPartProcess = $queryPartProcess->fetch_assoc())
                        {
                            $processCode = $resultPartProcess['processCode'];
                            $processDetail = $resultPartProcess['processDetail'];

                            $checkData = "<i class='fa fa-remove'></i>";
                            $pdFile = $_SERVER['DOCUMENT_ROOT']."/Document Management System/Work Instruction Folder/".$processCode."_".$partId.".pdf";
                            if(file_exists($pdFile) > 0)  
                            {
                                $checkData = "<i style='cursor:pointer;' onclick=\"window.open('/Document Management System/Work Instruction Folder/".$processCode."_".$partId.".pdf#zoom=70', 'details', 'width=1200,height=500');\" class='fa fa-circle-o'></i>";
                            }
                            //TAMANG CODE START HERE
                            $data3D = "<i class='fa fa-remove'></i>";
                            $pdFile = $_SERVER['DOCUMENT_ROOT']."/Document Management System/3D Work Instruction/".$processCode."_".$partId.".pdf";
                            if(file_exists($pdFile) > 0)  
                            {
                                //$data3D = "<i style='cursor:pointer;' onclick=\"window.open('/Document Management System/3D Work Instruction/".$processCode."_".$partId.".pdf#zoom=70', 'details', 'width=1200,height=500');\" class='fa fa-circle-o'></i>";
                                $data3D = "<a href='/Document Management System/3D Work Instruction/".$processCode."_".$partId.".pdf' download><i style='cursor:pointer;' class='fa fa-circle-o' title='3D PDF'></i></a>";
                            }
                            //TAMANG CODE END HERE
                            $sql = "SELECT processName FROM cadcam_process WHERE processCode = ".$processCode;
                            $queryProcessName = $db->query($sql);
                            if($queryProcessName AND $queryProcessName->num_rows > 0)
                            {
                                $resultProcessName = $queryProcessName->fetch_assoc();
                                $processName = $resultProcessName['processName'];
                            }

                            echo "<tr>";
                                echo "<td class='w3-center'><b>".++$x."</b></td>";
                                echo "<td class='w3-center'>".$processName."</td>";
                                echo "<td class='w3-center'>".$processDetail."</td>";
                                echo "<td class='w3-center'>".$checkData."</td>";
                                echo "<td class='w3-center'>".$data3D."</td>";//TAMANG
                            echo "</tr>";
                        }
                    }
                    echo "</tbody>";
                echo "</table>";
                echo "</div>";
            echo "</div>";
        }
    echo "</div>";
}
?>