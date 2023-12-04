<?php
include $_SERVER['DOCUMENT_ROOT'] . "/version.php";
$path = $_SERVER['DOCUMENT_ROOT'] . "/" . v . "/Common Data/";
set_include_path($path);
include('PHP Modules/mysqliConnection.php');
include('PHP Modules/gerald_functions.php');
include('PHP Modules/anthony_retrieveText.php');
include("PHP Modules/anthony_wholeNumber.php");
include("PHP Modules/rose_prodfunctions.php");

ini_set("display_errors", "on");
if ($_SERVER['REQUEST_METHOD'] == 'GET' or $_SERVER['REQUEST_METHOD'] == 'POST') {
    $type = isset($_GET['type']) ? $_GET['type'] : '';
}

if ($type == 1) {
    ob_start(); // Start output buffering
    echo "- " . displayText('L4707', 'utf8', 0, 0, 1);
    $version = ob_get_clean(); // Store the echoed value in $version and end output buffering
} elseif ($type == 3) {
    ob_start();
    echo  "- " . displayText('L4708', 'utf8', 0, 0, 1);
    $version = ob_get_clean();
} elseif ($type == 4) {
    ob_start();
    echo  "- " . displayText('L4709', 'utf8', 0, 0, 1);
    $version = ob_get_clean();
} elseif ($type == 5) {
    ob_start();
    echo  "- " . displayText('L4710', 'utf8', 0, 0, 1);
    $version = ob_get_clean();
}

PMSResponsive::includeHeader("Multi-Search");
$ctrl = new PMSDatabase;
$tpl = new PMSTemplates;
$pms = new PMSDBController;
$rdr = new Render\PMSTemplates;

$tpl->setDisplayId("103") # OPTIONAL
    ->setVersion($version)
    ->setPrevLink("eric_multiSearch.php")
    ->createHeader();

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/searchpanes/2.1.2/css/searchPanes.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/select/1.6.2/css/select.dataTables.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.2.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <script src="https://cdn.datatables.net/scroller/2.0.5/js/dataTables.scroller.min.js"></script>
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/scroller/2.0.5/css/scroller.dataTables.min.css">
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.5.1.js"></script>

    <link rel="stylesheet" href="assets/css/style.css">

    <style>
        body {
            font-size: 6px;
        }

        .ellipsis {
            text-align: left;
            width: 100px;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .ellipsis:hover {
            white-space: initial;
            /* transition: 2s ease; */
            /* word-wrap: break-word */
        }

        /* Custom even-row class */
        #dataTable .even-row {
            background-color: #caf0f8;
        }

        /* Custom odd-row class */
        #dataTable .odd-row {
            background-color: #ade8f4;
        }

        #dataTable .highlighted-row {
            background-color: orange;
        }


        #dataTable {
            border-collapse: collapse;
            border: .1px solid whitesmoke;
            overflow-x: hidden;
            overflow-y: auto;
            /* Change the color and width as needed */

        }

        th,
        td {
            border: .1px solid whitesmoke;
            /* Change the color and width as needed */
            padding-top: 8px;


        }

        #dataTable tbody tr,
        #dataTable td {
            border: 1px solid whitesmoke !important;
            padding: 8px !important;

        }


        .chat-popup,
        .chat-popup2 {
            width: 300px;
            height: 130px;
            background-color: #dedede;
            display: none;
            position: absolute;
            max-height: 200px;
            /* Adjust the value as needed */
            /* overflow-y: auto; */
            right: 150px;
            border: 3px solid #f1f1f1;
            z-index: 9;
        }

        .chat-popupSType {
            width: 300px;
            height: 130px;
            background-color: #dedede;
            display: none;
            position: absolute;
            max-height: 200px;
            /* Adjust the value as needed */
            /* overflow-y: auto; */
            right: 80rem;
            border: 3px solid #f1f1f1;
            z-index: 9;
        }

        .sType {
            padding: 1rem;
            font-size: 2rem;
        }

        .dropdown-menu {
            font-size: initial !important;
        }

        /* Custom even-row class */
        #dataTable .even-row {
            background-color: #caf0f8;
        }

        /* Custom odd-row class */
        #dataTable .odd-row {
            background-color: #ade8f4;
        }

        #dataTable .highlighted-row {
            background-color: orange;
        }

        /* hide homeBtn */
        .hide-homeBtnIcon {
            display: none !important;
        }

        .search-box {
            display: flex;
            align-items: center;
            background: #fff;
            border-radius: 25px;
            margin: 10px;
            padding: 4px 8px 4px 4px !important;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.4);
            max-width: 500px;

        }

        .search-input {
            line-height: 40px;
            font-size: 16px;
            border: 1px solid white !important;
            border-radius: 25px;
            outline: none;
        }

        #search {
            width: 20rem;
            margin-left: 6px;
            text-transform: uppercase;
        }

        #searchBtn {
            background-color: #00abd8;
            align-items: center !important;
            justify-content: center;
            padding-right: 1rem;
            border-radius: 24px;
            color: white;
            width: 5rem !important;
            height: 40px;

            border-style: solid;
            border-width: 2px 2px 2px 2px;
            border-color: rgba(255, 255, 255, 0.333);

            transform: translate(0px, 0px) rotate(0deg);
            transition: 0.2s;
            box-shadow: -7px 6px 4px 0px rgba(255, 255, 255, 0.25) inset, -4px 1px 4px 0px rgba(58, 42, 42, 0.25) inset, 3px -4px 4px 0px rgba(154, 154, 154, 0.37) inset;

        }

        #searchBtn:active {
            background: #427A9C;
            box-shadow: -8px 5px 4px 0px rgba(40, 40, 40, 0.25) inset, -4px -1px 4px 0px rgba(41, 41, 41, 0.25) inset;
            padding-right: 4%;
        }

        .search-icon {
            color: #fff;
            margin-right: 5px;
            padding-left: 4px;
            font-size: 16px;
        }

        #reset {
            padding: 3px 15px 3px 15px;
            background-color: #3452b4;
            color: white;
            border-radius: 10px;
            border: none;
            margin-bottom: 0;

        }

        .reset i {
            padding-top: 2px;
            font-size: 1rem;
        }

        .reset .btnLabel {
            font-size: 1rem;
        }


        /* Responsive Styles */
        @media (max-width: 1024px) {

            th,
            td {
                font-size: 8px !important;
            }


        }

        /* adjust in tablet */
        @media screen and (max-height: 768px) {

            /* hidden length column */
            /* .dataTable th:nth-child(7) {
                display: none;
            }
            .dataTable td:nth-child(7) {
                display: none;
            } */
        }

        @media screen and (max-width: 768px) {
            .search-box {
                max-width: 100%;
            }

            .reset {
                max-width: 100%;
            }
        }

        /* phone portrait */
        @media (min-width:200px) and (max-width: 585px) {
            #top {
                margin-top: 3rem;
            }
        }

        /* phone landscape */
        @media (min-width: 660px) and (max-width: 850px) and (min-height: 280px) and (max-height: 380px) {}


        /* ipad portrait */
        @media (min-width: 750px) and (max-width: 768px) and (min-height: 800px) and (max-height: 1020px) {
            .resetBtn {
                margin: 1.5rem 0 0 1.5rem !important;
            }
        }

        /* ipad landscape */
        @media (min-width:1000px) and (max-width: 1024px) {
            .resetBtn {
                /* margin: -.5rem 0 0 12rem !important; */
            }

            #search {
                width: 20rem;
            }

            .search-box {

                max-width: 300px !important;

            }
        }
    </style>


</head>
<?php
$searchValue = isset($_POST['searchValue']) ? $_POST['searchValue'] :  $_GET['searchValue'];
if ($_SERVER['REQUEST_METHOD'] == 'GET' or $_SERVER['REQUEST_METHOD'] == 'POST') {
    $type = isset($_GET['type']) ? $_GET['type'] : '';
    $sType = isset($_POST['sType']) ? $_POST['sType'] : '';
    $locations = isset($_POST['locations']) ? $_POST['locations'] : '';
    $size = 15;

    // echo $locations;
    $searchKeys =  preg_split('/[\p{Zs}\s]+/u', trim($searchValue));


    $sql = "SELECT * FROM warehouse_inventory WHERE type = '$type' AND ";

    foreach ($searchKeys as $key) {
        $sql .= "(supplierAlias LIKE '%$key%' OR dataOne LIKE '%$key%' OR dataTwo LIKE '%$key%' OR dataThree LIKE '%$key%' OR dataFour LIKE '%$key%') AND ";
    }
    if (!empty($locations)) {
        $sql .= " inventoryLocation = '$locations' ";
        $size = 1;
        // $locationCheck = explode(",", str_replace("'", "", $locations));
    }
    if ($sType != "") {
        $sql .= $sType == '0' ?  "dataSix IN (0,3)" : "dataSix = $sType";
    }

    $sql = rtrim($sql, 'AND ');
    $locations = [];
    $query = $db->query($sql);
    $totalRecords = $query->num_rows;

    // echo $sql;
}

?>

<body>
    <form action="" method="post" id='formFilter'></form>
    <input type="hidden" name='locations' id='hidden' form='formFilter'>
    <!-- <div class="row">
        <div class="col">
            <button class="btn btn-primary btn-lg fw-bold float-end"> <i class="fa fa-plus" aria-hidden="true"></i> batch update</button>
        </div>

    </div> -->
    <div class="row mt-4">
        <div class="col-md-12 col-lg-2 d-flex align-items-center">
            <div class="search-box mt-4">
                <button form='formFilter' class='' type="submit" id="searchBtn"> <i class="fas fa-search search-icon"></i> </button>
                <input form="formFilter" type="text" class="search-input" id="search" name="searchValue" value="<?php echo $searchValue ?>" placeholder="Search...">
            </div>
            <div class="resetBtn mt-4">
                <button class="w3-teal reset" id="reset">
                    <i class="fa fa-rotate-left icon w3-xlarge"></i><br>
                    <span class="btnLabel"><?php echo displayText('L1337', 'utf8', 0, 0, 1); // reset  S 
                                            ?></span>
                </button>
            </div>

            <div class="pt-4" style="margin-left: 12px !important ">
            <button class="w3-right reset" id="reset" onclick="updateBatch()">
                <i class="fa fa-pen icon w3-xlarge"></i><br>
                <span class="btnLabel">BATCH</span>
            </button>
        </div>
        </div>
        <!-- <div class="col pt-4">
            <button class="w3-right reset" id="reset" onclick="updateBatch()">
                <i class="fa fa-pen icon w3-xlarge"></i><br>
                <span class="btnLabel">BATCH</span>
            </button>
        </div> -->
        
    </div>

    <!-- stype filter -->
    <div class="chat-popupSType container-fluid p-1" id="SForm">
        <div class="row p-4">
            <div class="col-md-6">
                <h2 class="form-label text-center fw-bold float-start mt-1"><?php echo displayText("L10012", "utf8", 0, 0, 1); ?></h2>
            </div>
            <div class="col">
                <button id='close' class="btn btn-danger btn-lg fw-bold float-end">X</button>
                <!-- <button id='filter' class="btn btn-primary btn-lg fw-bold float-end"><?php echo displayText("L437", "utf8", 0, 0, 1); ?></button> -->
            </div>

            <select name="sType" id="stype" class="sType" form="formFilter" size="3">
                <option value="" disabled hidden selected><?php echo displayText("B5", "utf8", 0, 0, 1); ?></option>
                <option value="0"><?php echo displayText("L1368", "utf8", 0, 0, 1); ?></option>
                <option value="1"><?php echo displayText("L181", "utf8", 0, 0, 1); ?></option>
                <option value="2"><?php echo displayText("L10013", "utf8", 0, 0, 1); ?></option>
            </select>
        </div>
    </div>

    <!-- location filter -->
    <div class="chat-popup container-fluid p-4" id="myForm">
        <div class="row p-3">
            <div class="col-md-6">
                <h2 class="form-label text-center fw-bold float-start mt-1">LOCATION</h2>
            </div>
            <div class="col">
                <button id='close' class="btn btn-danger btn-lg fw-bold float-end">close</button>
                <!-- <button type='submit' form='formFilter' class="btn btn-primary btn-lg fw-bold float-end">FILTER</button> -->
            </div>
        </div>

        <!-- location select -->
        <?php
        list($left, $right) = explode(" * ", $sql);
        $newSql = $left . " inventoryLocation, COUNT(inventoryLocation) as totalCounts " . $right . " GROUP BY inventoryLocation ORDER BY inventoryLocation ASC";
        $query = $db->query($newSql);
        echo '<select class="form-select fs-3" name = "locations" id="locations" form="formFilter" style="z-index:99999" size='.$size.'>';
        if ($query and $query->num_rows > 0) {
            while ($row = $query->fetch_object()) {
                $location = $row->inventoryLocation != NULL ? $row->inventoryLocation : 'No location';
                // echo '<hr>';
                echo "<option value='$row->inventoryLocation'>" . $row->totalCounts . " - " . $location . "</option>";
                // echo '<hr>';
            }
        }
        echo '</select>';
        ?>
    </div>

    <div class="flexContainer">
        <div class="tableDatas">

            <?php
            if ($type == 1) {
                include 'views/tables/jera_includeMaterial.php';
            } elseif ($type == 3) {
                include 'views/tables/jera_includeSupply.php';
            } elseif ($type == 4) {
                include 'views/tables/jera_includeAccessory.php';
            } elseif ($type == 5) {
                include 'views/tables/jera_includeGoods.php';
            } elseif ($type == 'drawing') {
                include 'views/tables/jera_includeGoods.php';
            }
            ?>

        </div>
    </div>

    <div id="modal-izi"><span class="izimodal-content"></span></div>
    <div id='modal-izi-viewer'><span class='izimodal-content-viewer'></span></div>
    <div id='modal-izi-update'><span class='izimodal-content-update'></span></div>
</body>

</html>

<?php PMSResponsive::includeFooter(); ?>

<script>
    var searchValue = '<?php echo $searchValue ?>';

    function multiSearch() {

        $("#modal-izi").iziModal({

            width: 1200,
            height: 340,
            fullscreen: false,
            transitionIn: 'comingIn',
            transitionOut: 'comingOut',
            restoreDefaultContent: true,
            closeOnEscape: true,
            closeButton: true,
            overlayClose: true,
            onOpening: function(modal) {
                modal.startLoading();
                $.ajax({
                    url: 'eric_multiSearch.php',
                    type: 'POST',
                    data: {
                        searchValue: searchValue
                    },
                    success: function(data) {
                        $(".izimodal-content").html(data);
                        modal.stopLoading();

                        // Hide the element when the modal is opened
                        $(".btn-real-dent.btn-real-dent1x.homeBtnIcon").addClass("hide-homeBtnIcon");
                    }
                });
            },
            onClosed: function(modal) {
                // Remove the hide class when the modal is closed
                $(".btn-real-dent.btn-real-dent1x.homeBtnIcon").removeClass("hide-homeBtnIcon");
                $("#modal-izi").iziModal("destroy");
            }
        });

        $("#modal-izi").iziModal("open");
    }

    $("#reset").click(function() {
        window.location.href = 'jera_searchResults.php?type=' + '<?php echo $type ?>';
    })

    $('#details').multiselect({
        maxHeight: 300,
        includeSelectAllOption: true,
        buttonClass: 'w3-input w3-border w3-white hello',
        buttonWidth: '100%',
        nonSelectedText: 'Select',
        numberDisplayed: 500,
        onChange: function(option, checked, select) {
            var selectID = $(option).parent().attr('id');
            $("." + selectID).remove();
        },
        onSelectAll: function(event) {
            // event.preventDefault();
        },
        onDeselectAll: function(event) {
            // event.preventDefault();
        }
    });

    $("#details").change(function() {
        const selectedOptions = $(this).val();
        const hiddenInput = $("#hidden");
        const values = "'" + selectedOptions.join("','") + "'";
        $(hiddenInput).val(values)

    });

    $(document).on("click", ".dropdown", function() {

        let updateId = $(this).attr("data-id");
        let button2 = $(this);
        let partId = $(this).attr("data-partNumber");
        let oldMemo = $(this).attr("data-memo");
        $("#locations").click();
        $("#myForm").toggle('fast', function() {
            if ($(this).is(':visible')) {
                $(".btn-group").addClass("open");

            }
        });
        $("#memoArea2").on("keyup", function() {
            $("#inputChange").val($(this).val());
        });



    })


    const updateBatch = () => {

        const checkedIds = [...$("input.checkbox:checked")].map((checkbox) => $(checkbox).data("id")).join(",");
        // console.log(checkedIds);

        if (checkedIds) {
            $("#modal-izi-update").iziModal({
                title: '<i class="fa fa-plus"></i>&nbsp;INSPECT',
                headerColor: '#1F4788',
                width: '25%',
                height: '50%',
                fullscreen: false,
                transitionIn: 'comingIn',
                transitionOut: 'comingOut',
                padding: 20,
                radius: 0,
                top: 100,
                restoreDefaultContent: true,
                closeOnEscape: true,
                closeButton: true,
                overlayClose: false,
                onOpening: function(modal) {
                    modal.startLoading();
                    $.ajax({
                        url: 'eric_msUpdate.php',
                        type: 'POST',
                        data: {
                            inventoryIds: checkedIds
                        },
                        success: function(data) {
                            $(".izimodal-content-update").html(data);
                            modal.stopLoading();
                        }
                    });
                },
                onClosed: function(modal) {
                    $("#modal-izi-update").iziModal("destroy");
                }
            });

            $("#modal-izi-update").iziModal("open");
        } else {
            swal({
                title: 'Warning!',
                text: 'No Checkboxes Checked',
                type: 'warning',
                timer: 2000,
                showConfirmButton: false
            });
        }


    }


    const imageViewer = (obj, param) => {

        $('#mainTableId tbody tr').css('background-color', '');
        $(obj).closest('#mainTableId tbody tr').css('background-color', 'orange');
        $("#modal-izi-viewer").iziModal({
            title: '<i class="fa fa-eye"></i> IMAGE VIEWER',
            headerColor: '#1F4788',
            subtitle: '<b><?php echo strtoupper(date('F d, Y')); ?></b>',
            width: 1200,
            fullscreen: false,
            transitionIn: 'comingIn',
            transitionOut: 'comingOut',
            padding: 20,
            radius: 0,
            top: 10,
            restoreDefaultContent: true,
            closeOnEscape: true,
            closeButton: true,
            overlayClose: false,
            onOpening: function(modal) {
                modal.startLoading();
                // alert(assignedTo);
                $.ajax({
                    url: 'eric_imageViewer2.php',
                    type: 'POST',
                    data: {
                        inventoryId: param,
                    },
                    success: function(data) {
                        $(".izimodal-content-viewer").html(data);
                        modal.stopLoading();
                    }
                });
            },
            onClosed: function(modal) {
                $("#modal-izi-viewer").iziModal("destroy");
            }
        });

        $("#modal-izi-viewer").iziModal("open");

    }

    $(document).on("click", ".Smodal", function() {
        $("#SForm").toggle();
        // $("#stype").attr('size', '4');
    })

    $(document).on("change", "#stype,#locations", function() {
        $('#formFilter').submit();
    })

    $(document).on("click", "#close", function() {
        $("#SForm").hide()
        $("#myForm").hide()
    })
</script>