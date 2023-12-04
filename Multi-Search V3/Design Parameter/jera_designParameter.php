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
$obj = new PMSDatabase;

$tpl->setDataValue("L10014");
$tpl->setAttribute("type","button");
$tpl->setAttribute("id","addBtn");
$addBtn = $tpl->createButton();


$sqlData = "SELECT * FROM system_msParameter ORDER BY id";
$obj->setSQLQuery($sqlData);
$totalRecords = count($obj->getRecords());

PMSTemplates::includeHeader("Multi-Search Design Parameter");
createHeader("L10014");
?>
<div class="container-fluid"> 
    <div class="row w3-padding-top">
        <div class="col-md-12">
            <div class='w3-right'>
                <!-- <?php echo $addBtn; ?> -->
            </div>
        </div>
    </div>
    <div class="row w3-padding-top">
        <div class="col-md-12">
            <label>RECORDS : <?php echo $totalRecords;?></label>
            <table id='mainTableId' style='' class="table table-bordered table-striped table-condensed">
                <thead class='w3-indigo' style='text-transform: uppercase;'>
                    <th class='w3-center'>#</th>
                    <th class='w3-center'>ELEMENTS </th>
                    <th class='w3-center'>PC</th>
                    <th class='w3-center'>Phone Portrait</th>
                    <th class='w3-center'>Phone Landscape</th>
                    <th class='w3-center'>iPad Portrait</th>
                    <th class='w3-center'>iPad Landscape </th>
                </thead>
                <tbody class='w3-center'>
                    
                </tbody>
                <tfoot class='w3-indigo'>
                    <tr>
                        <th class='w3-center'></th>
                        <th class='w3-center'></th>
                        <th class='w3-center'></th>
                        <th class='w3-center'></th>
                        <th class='w3-center'></th>
                        <th class='w3-center'></th>
                        <th class='w3-center'></th>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>
<div id='modal-izi'><span class='izimodal-content'></span></div>
<div id='modal-izi-how'><span class='izimodal-content-how'></span></div>
<textarea rows="1" cols="2" id='copyCode' style='position:fixed; top:0; opacity:; z-index:-99999;'></textarea>
<?php
PMSTemplates::includeFooter();
?>
<script>
$(document).ready(function(){
    var sqlData = "<?php echo $sqlData; ?>";
    var totalRecords = "<?php echo $totalRecords; ?>";

    var dataTable = $('#mainTableId').DataTable( {
        "processing"    : true,
        "ordering"      : false,
        "serverSide"    : true,
        "bInfo" 		: false,
        "ajax":{
            url     :"jera_parameterAjax.php", // json datasource
            type    : "post",  // method  , by default get
            data    : {
                        "totalRecords"   	: totalRecords,
                        "sqlData"     	    : sqlData
                        },
            error: function(data){  // error handling
                
                $(".mainTableId-error").html("");
                $("#mainTableId").append('<tbody class="mainTableId-error"><tr><th colspan="3">No data found in the server</th></tr></tbody>');
                $("#mainTableId_processing").css("display","none");
                
            }
        },
        "createdRow": function( row, data, index ) {
                $(row).addClass("w3-hover-dark-grey rowClass");
                $(row).click(function(){
                    $(".rowClass").removeClass("w3-deep-orange");
                    $(this).addClass("w3-deep-orange");
                });
        },
        "columnDefs": [
                        {
                            "targets" 		: [0],
                            "width"		    : "2%"
                        },
        ],
        language	: {
                    processing	: "<span class='loader'></span>"
        },
        fixedColumns:   {
                leftColumns: 0
        },
        // responsive		: true,
        scrollY     	: 505,
        scrollX     	: false,
        scrollCollapse	: false,
        scroller    	: {
            loadingIndicator    : true
        },
        stateSave   	: false
    });

    $("#addBtn").on('click', function (event) {
        $("#modal-izi").iziModal({
            title                   : '<i class="fa fa-plus"></i> ADD TEMPLATE',
            headerColor             : '#1F4788',
            subtitle                : '<b><?php echo strtoupper(date('F d, Y'));?></b>',
            width                   : 470,
            fullscreen              : false,
            transitionIn            : 'comingIn',
            transitionOut           : 'comingOut',
            padding                 : 20,
            radius                  : 0,
            top                     : 10,
            restoreDefaultContent   : true,
            closeOnEscape           : true,
            closeButton             : true,
            overlayClose            : false,
            onOpening               : function(modal){
                                        modal.startLoading();
                                        $.ajax({
                                            url         : 'jera_addTemplate.php',
                                            type        : 'POST',
                                            data        : {
                                                            type            : 1
                                            },
                                            success     : function(data){
                                                            $( ".izimodal-content" ).html(data);
                                                            modal.stopLoading();
                                            }
                                        });
                                    },
                onClosed            : function(modal){
                                        $("#modal-izi").iziModal("destroy");
                        }
        });

        $("#modal-izi").iziModal("open");
    });

    function updateDatabase(templateId, columnName, newValue, selector, cssProperty) {
        $.ajax({
            url: 'jera_updateValue.php', // Create a PHP file to handle the database update
            method: 'POST',
            data: {
                templateId: templateId,
                columnName: columnName,
                newValue: newValue,
                selector: selector,
                cssProperty: cssProperty
            },
            success: function(response) {
                // Handle the response from the server (e.g., show a success message)
                console.log('Database updated successfully');
                console.log('Data from server:', templateId,columnName,newValue,selector,cssProperty);
                
                // Show a Sweet Alert on success
                swal({
                    title: "Success",
                    text: "Style updated successfully",
                    icon: "success",
                });
               
            },
            error: function(xhr, status, error) {
                // Handle errors (e.g., display an error message)
                console.error('Error updating database:', error);
            }
        });
    }

    //enter event
    $('#mainTableId').on('keyup', '.editable-cell', function(e) {
        if (e.keyCode === 13) { 
            var newValue = $(this).val();
            var templateId = $(this).data('template-id');
            var columnName = $(this).data('column-name');
            var selector = $(this).data('css');
            var cssProperty = $(this).data('css-property');
            updateDatabase(templateId, columnName, newValue, selector, cssProperty);
            console.log('new value:',newValue);
            console.log('css:',selector);
            console.log('css property:',cssProperty);


        }
    });
    
});
</script>
