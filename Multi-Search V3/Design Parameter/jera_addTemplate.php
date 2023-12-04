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
$tpl = new PMSTemplates;
$objDB = new PMSDatabase;
$rdr = new Render\PMSTemplates;

$type = isset($_POST['type']) ? $_POST['type'] : "";
$option = isset($_POST['option']) ? $_POST['option'] : "A";

if($type == 3)
{
    $displayId = isset($_POST['displayId']) ? $_POST['displayId'] : "";
    ?>
    $tpl = new PMSTemplates; // Declare Once
    $varName = $tpl->setDataValue("<?php echo $displayId;?>")
                   ->setAttribute([
                        "name"  => "",
                        "id"    => "",
                        "type"  => ""
                   ])
                   ->addClass("") // Optional
                   ->createButton();
    <?php
    exit();
}

if($type == 4)
{
    $inputType = isset($_POST['inputType']) ? $_POST['inputType'] : "";

    if($inputType == "text")
    {
        ?>
        $input = new CreateInputClass('text');
        $input->attr(array(
            'name'=>"",
            'id'=>"",
            'style'=>""
        ));
        $inputText = $input->createInput();
        <?php
    }
    else if($inputType == "number")
    {
        ?>
        $input = new CreateInputClass('number');
        $inputNumber = $input->createInput();
        <?php
    }
    else if($inputType == "dropdown")
    {
        ?>
        $input = new CreateInputClass('dropdown');
        $input->data($dataArray);
        $input->attr(array(
            'value'=>'28'
        ));
        $inputNumber = $input->createInput();
        <?php
    }
    else if($inputType == "textarea")
    {
        ?>
        $input = new CreateInputClass('textarea');
        $inputTextArea = $input->createInput();
        <?php
    }
    else if($inputType == "datalist")
    {
        ?>
        $input = new CreateInputClass('datalist');
            $input->data($dataArray);
            $inputDatalist = $input->createInput();	
        <?php
    }

    exit();
}

if($type == 5)
{
echo "<?php\n";
?>
include $_SERVER['DOCUMENT_ROOT']."/version.php";
$path = $_SERVER['DOCUMENT_ROOT']."/".v."/Common Data/";
set_include_path($path);    
include('PHP Modules/mysqliConnection.php');
include('PHP Modules/anthony_retrieveText.php');
include('PHP Modules/gerald_functions.php');
include("PHP Modules/rose_prodfunctions.php");
ini_set("display_errors", "on");
$ctrl = new PMSDatabase;
$tpl = new PMSTemplates;
$pms = new PMSDBController;
$rdr = new Render\PMSTemplates;

$title = "";
PMSTemplates::includeHeader($title);

$tpl->setDisplayId("") # OPTIONAL
    ->setVersion("") # OPTIONAL
    ->setPrevLink("") # OPTIONAL
    ->setHomeIcon() # OPTIONAL 0 - Default; 1 - w/o home icon
    ->createHeader();
<?php
echo "?>\n";
?>
<div class='container-fluid'>
    <div class='row w3-padding-top'> <!-- row 1 -->
        <div class='col-md-12'>
            <!-- Code Here.. -->
        </div>
    </div>
    <div class='row w3-padding-top'>  <!-- row 2 -->
        <div class='col-md-12'>
            <!-- TABLE TEMPLATE -->
            <label><?php echo '<?php echo displayText("L41", "utf8", 0, 0, 1)." : ". $totalRecords; ?>'; ?></label>
            <?php
            if($option == "A")
            {
            ?>
			<table id='mainTableId' class="table table-bordered table-striped table-condensed">
				<thead class='w3-indigo' style='text-transform:uppercase;'>
                    <th class='w3-center' style='vertical-align:middle;'>HEADER 1</th>
                    <th class='w3-center' style='vertical-align:middle;'>HEADER 2</th>
                    <th class='w3-center' style='vertical-align:middle;'>HEADER 3</th>
                    <th class='w3-center' style='vertical-align:middle;'>HEADER 4</th>
                    <th class='w3-center' style='vertical-align:middle;'>HEADER 5</th>
				</thead>
				<tbody class='w3-center'>
					
				</tbody>
				<tfoot class='w3-indigo' >
                    <tr>
                        <th class='w3-center' style='vertical-align:middle;'></th>
                        <th class='w3-center' style='vertical-align:middle;'></th>
                        <th class='w3-center' style='vertical-align:middle;'></th>
                        <th class='w3-center' style='vertical-align:middle;'></th>
                        <th class='w3-center' style='vertical-align:middle;'></th>
                    </tr>
				</tfoot>
			</table>
            <?php
            }
            else if($option == "B")
            {
                echo "<?php\n";
                ?>
                $rdr->setColumn([
                        "Header 0" => [
                            "visible"       => true, // Required
                            "footer"        => ""
                        ],
                        "Header 1" => [
                            "visible"       => true, // Required
                            "footer"        => ""
                        ],
                        "Header 2" => [
                            "visible"       => true, // Required
                            "footer"        => ""
                        ],
                        "Header 3" => [
                            "visible"       => true, // Required
                            "footer"        => ""
                        ],
                        "Header 4" => [
                            "visible"       => true, // Required
                            "footer"        => ""
                        ],
                        "Header 5" => [
                            "visible"       => true, // Required
                            "footer"        => ""
                        ],
                    ])
                    ->RenderTable();
                <?php
                echo "?>\n";
            }
            ?>
        </div>
    </div>
</div>
<?php
echo "<?php\n";
?>
PMSTemplates::includeFooter();
<?php
echo "?>\n";
?>
<script>
// script here
$(document).ready(function(){
    var sqlData = "<?php echo '<?php echo $sqlData; ?>';?>";
    var totalRecords = "<?php echo '<?php echo $totalRecords; ?>';?>";
    var dataTable = $('#mainTableId').DataTable( {
		"searching"     : false,
		"processing"    : true,
		"ordering"      : false,
		"serverSide"    : true,
		"bInfo"         : false,
		"ajax"          : {
                url     : "ajax url here...", // json datasource
                type    : "POST",  // method  , by default get
                data    : {
                            "sqlData"           : sqlData, // SQL Query POST
                            "totalRecords"      : totalRecords
                },
                error   : function(){  // error handling
                            $(".mainTableId-error").html("");
                            $("#mainTableId").append('<tbody class="mainTableId-error"><tr><th colspan="3">No data found in the server</th></tr></tbody>');
                            $("#mainTableId_processing").css("display","none");
                }
		},
        "createdRow"    : function( row, data, index ) {
                            $(row).addClass("w3-hover-dark-grey rowClass");
                            $(row).click(function(){
                                $(".rowClass").removeClass("w3-deep-orange");
                                $(this).addClass("w3-deep-orange");
                            });
        },
		"columnDefs"    : [
		
        ],
		fixedColumns    : true,
		deferRender     : true,
		scrollY         : 530,
		scrollX         : true,
		scroller        : {
			loadingIndicator    : true
		},
		stateSave       : false
	});
});
</script>
<?php
    exit();
}

if(isset($_POST['saveBtn']))
{
    $inputName = isset($_POST['inputName']) ? $_POST['inputName'] : "";
    $inputColor = isset($_POST['inputColor']) ? $_POST['inputColor'] : "";
    $inputIcon = isset($_POST['inputIcon']) ? $_POST['inputIcon'] : "";
    $inputWidth = isset($_POST['inputWidth']) ? $_POST['inputWidth'] : "";
     $inputFontsize = isset($_POST['inputFontsize']) ? $_POST['inputFontsize'] : "";
    $sql = "SELECT displayId FROM system_software WHERE displayTextOne LIKE '".$inputName."' AND (INSTR(displayId, 'L') > 0 OR INSTR(displayId, 'B') > 0) ORDER BY displayId DESC LIMIT 1";
    $objDB->setSQLQuery($sql);
    $resultData = $objDB->getRecords();
    if($resultData != NULL)
    {
        $displayId = $resultData[0]['displayId'];

        $fieldsArray = Array();
        $fieldsArray[] = "displayId";
        $fieldsArray[] = "templateName";
        $fieldsArray[] = "templateColor";
        $fieldsArray[] = "templateWidth";
        $fieldsArray[] = "templateIcon";
        $fieldsArray[] = "templateType";
        //eric
        $fieldsArray[] = "templateFontsize";

        $valuesArray = Array();
        $valuesArray[] = $db->real_escape_string(trim($displayId));
        $valuesArray[] = $db->real_escape_string(trim($inputName));
        $valuesArray[] = $db->real_escape_string(trim($inputColor));
        $valuesArray[] = $db->real_escape_string(trim($inputWidth));
        $valuesArray[] = $db->real_escape_string(trim($inputIcon));
        //eric
         $valuesArray[] = $db->real_escape_string(trim($inputFontsize));
        $valuesArray[] = 0;

        $objDB->insertRecords("system_templates", $fieldsArray, $valuesArray);

        header("location:raymond_buttonTemplatesv2.php");
    }
    else
    {
        echo "PLEASE INPUT ON LANGUAGE FIRST";
    }

    exit();
}

if(isset($_POST['updateBtn']))
{
    $templateId = isset($_POST['templateId']) ? $_POST['templateId'] : "";
    $inputName = isset($_POST['inputName']) ? $_POST['inputName'] : "";
    $inputColor = isset($_POST['inputColor']) ? $_POST['inputColor'] : "";
    $inputIcon = isset($_POST['inputIcon']) ? $_POST['inputIcon'] : "";
    $inputWidth = isset($_POST['inputWidth']) ? $_POST['inputWidth'] : "";
    $inputFontsize = isset($_POST['inputFontsize']) ? $_POST['inputFontsize'] : "";
    // eric
    

    $sql = "SELECT displayId FROM system_software WHERE displayTextOne LIKE '".$inputName."' AND (INSTR(displayId, 'L') > 0 OR INSTR(displayId, 'B') > 0) ORDER BY displayId DESC LIMIT 1";
    $objDB->setSQLQuery($sql);
    $resultData = $objDB->getRecords();
    if($resultData != NULL)
    {
        $displayId = $resultData[0]['displayId'];
        
        $valuesArray = Array();
        $valuesArray[] = "displayId = '".$db->real_escape_string(trim($displayId))."'";
        $valuesArray[] = "templateName = '".$db->real_escape_string(trim($inputName))."'";
        $valuesArray[] = "templateColor = '".$db->real_escape_string(trim($inputColor))."'";
        $valuesArray[] = "templateWidth = '".$db->real_escape_string(trim($inputWidth))."'";
        $valuesArray[] = "templateIcon = '".$db->real_escape_string(trim($inputIcon))."'";
        // eric
        $valuesArray[] = "templateFontsize = '".$db->real_escape_string(trim($inputFontsize))."'";

        $whereQuery = "templateId = ".$templateId;
        $objDB->updateRecords("system_templates", $valuesArray, $whereQuery);

        header("location:raymond_buttonTemplatesv2.php");
    }
    else
    {
        echo "PLEASE INPUT ON LANGUAGE FIRST";
    }

    exit();
}

$templateIdInput = "";
if($type == 2)
{
    $templateId = isset($_POST['templateId']) ? $_POST['templateId'] : "";

    $sql = "SELECT * FROM system_templates WHERE templateId = ".$templateId;
    $objDB->setSQLQuery($sql);
    $templateData = $objDB->getRecords();
    if($templateData != NULL)
    {
        $templateName = $templateData[0]['templateName'];
        $templateColor = $templateData[0]['templateColor'];
        $templateWidth = $templateData[0]['templateWidth'];
        $templateIcon = $templateData[0]['templateIcon'];
        $templateType = $templateData[0]['templateType'];
        // eric
        $templateFontsize = $templateData[0]['templateFontsize'];
    }

    $tpl->createElement("input");
    $tpl->setAttribute("type","hidden");
    $tpl->setAttribute("class","w3-input w3-border");
    $tpl->setAttribute("name","templateId");
    $tpl->setAttribute("value", $templateId);
    $tpl->setAttribute("form","formSave");
    $templateIdInput = $tpl->createInput();
}

$tpl->createElement("form");
$tpl->setAttribute("id","formSave");
$tpl->setAttribute("method","POST");
$tpl->setAttribute("action", $_SERVER['PHP_SELF']);
$formName = $tpl->createInput();

$tpl->createElement("input");
$tpl->setAttribute("type","text");
$tpl->setAttribute("class","w3-input w3-border");
$tpl->setAttribute("name","inputName");
$tpl->setAttribute("value", $templateName);
$tpl->setAttribute("form","formSave");
$inputName = $tpl->createInput();

$tpl->setAttribute("type","text");
$tpl->setAttribute("class","w3-input w3-border");
$tpl->setAttribute("name","inputColor");
$tpl->setAttribute("value", $templateColor);
$tpl->setAttribute("form","formSave");
$inputColor = $tpl->createInput();

$tpl->setAttribute("type","text");
$tpl->setAttribute("class","w3-input w3-border");
$tpl->setAttribute("name","inputIcon");
$tpl->setAttribute("value", $templateIcon);
$tpl->setAttribute("form","formSave");
$inputIcon = $tpl->createInput();

$tpl->setAttribute("type","text");
$tpl->setAttribute("class","w3-input w3-border");
$tpl->setAttribute("name","inputWidth");
$tpl->setAttribute("value", $templateWidth);
$tpl->setAttribute("form","formSave");
$inputWidth = $tpl->createInput();

//eric
$tpl->setAttribute("type","text");
$tpl->setAttribute("class","w3-input w3-border");
$tpl->setAttribute("name","inputFontsize");
$tpl->setAttribute("placeholder","DEFAULT(12px)");
$tpl->setAttribute("value", $templateFontsize);
$tpl->setAttribute("form","formSave");
$inputFontsize = $tpl->createInput();

if($type == 2)
{
    $tpl->setDataValue("L1054");
    $tpl->setAttribute("type","submit");
    $tpl->setAttribute("name","updateBtn");
    $tpl->setAttribute("form","formSave");
    $saveBtn = $tpl->createButton();
}
else
{
    $tpl->setDataValue("L1052");
    $tpl->setAttribute("type","submit");
    $tpl->setAttribute("name","saveBtn");
    $tpl->setAttribute("form","formSave");
    $saveBtn = $tpl->createButton();
}

echo $formName;
echo $templateIdInput;
?>
<div class='row'>
    <div class='col-md-12'>
        <label>ELEMENT NAME</label>
        <?php echo $inputName; ?>
    </div>
</div>
<div class='row w3-padding-top'>
    <div class='col-md-12'>
        <label>SELECTOR</label>
        <?php echo $inputIcon; ?>
    </div>
</div>
<div class='row w3-padding-top'>
    <div class='col-md-12'>
        <label>CSS PROPERTY</label>
        <?php echo $inputColor; ?>
    </div>
</div>

<?php

?>
<div class='row w3-padding-top'>
    <div class='col-md-12 w3-center'>
        <?php echo $saveBtn; ?>
    </div>
</div>