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

PMSResponsive::includeHeader("Multi-Search");
$ctrl = new PMSDatabase;
$tpl = new PMSTemplates;
$pms = new PMSDBController;
$rdr = new Render\PMSTemplates;
$tpl->setDisplayId("103") # OPTIONAL
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
        /* adjust in tablet */
        @media screen and (max-width: 1024px) {
            .search-box {
                margin-top: 3.4rem !important;
            }

            .resetBtn {
                margin-left: 49rem !important;
            }
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
    </style>

    <style>
        /* adjust in tablet */
        @media screen and (max-width: 1024px) {
            .search-box {
                margin-top: 1rem !important;
            }

        }

        @media screen and (max-height: 768px) {

            .dataTable td:nth-child(7),
            .dataTable th:nth-child(7) {
                display: none;
            }

        }

        .search-box {
            display: flex;
            align-items: center;
            background: #fff;
            border-radius: 25px;
            margin: 10px;
            padding: 5px 10px;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.4);
            max-width: 300px;
        }

        .search-input {
            border: 2px solid #fff;
            outline: none;
            padding: 5px;
            font-size: 16px;
        }

        .search-icon {
            color: #555;
            margin-right: 5px;
        }

        /* Responsive Styles */
        @media screen and (max-width: 768px) {
            .search-box {
                max-width: 100%;
            }

            .reset {
                max-width: 100%;
            }
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

        /* ipad portrait */
        @media screen and (max-width: 768px) and (min-height: 909px) {
            .resetBtn {
                margin: -.5rem 0 0 -2rem !important;
            }
        }

        /* ipad landscape */
        @media screen and (min-width: 1024px) and (max-height: 768px) {
            .resetBtn {
                margin: -.5rem 0 0 6rem !important;
            }
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

        .sType{
            padding: 1rem;
            font-size: 2rem;
        }

        .dropdown-menu {
            font-size: initial !important;
        }
        #searchBtn {}
    </style>


</head>
<?php
$searchValue = isset($_POST['searchValue']) ? $_POST['searchValue'] :  $_GET['searchValue'];
if ($_SERVER['REQUEST_METHOD'] == 'GET' or $_SERVER['REQUEST_METHOD'] == 'POST') {
    $type = isset($_GET['type']) ? $_GET['type'] : '';
    $locations = isset($_POST['locations']) ? $_POST['locations'] : '';
    $sType = isset($_POST['sType']) ? $_POST['sType'] : '';
    $searchKeys =  preg_split('/[\p{Zs}\s]+/u', trim($searchValue));


    $sql = "SELECT * FROM warehouse_inventory WHERE type = '$type' AND ";

    foreach ($searchKeys as $key) {
        $sql .= "(supplierAlias LIKE '%$key%' OR dataOne LIKE '%$key%' OR dataTwo LIKE '%$key%' OR dataThree LIKE '%$key%' OR dataFour LIKE '%$key%') AND ";
    }

    if ($sType != "")
    {
        $sql .= "dataSix = $sType";
    }

    if (!empty($locations)) {
        $sql .= " inventoryLocation IN($locations) ";
        $locationCheck = explode(",", str_replace("'", "", $locations));
    }
    $sql = rtrim($sql, 'AND ');
    $locations = [];
    $query = $db->query($sql);
    $totalRecords = $query->num_rows;
    if ($query and $query->num_rows > 0) {
        while ($row = $query->fetch_assoc()) {
            $locations[] = $row['inventoryLocation'];
        }
    }
}

?>

<body>
    <form action="" method="post" id='formFilter'></form>
    <input type="hidden" name='locations' id='hidden' form='formFilter'>
    <div class="row mt-4">
        <div class="col-md-4 col-lg-2 d-flex align-items-start">
            <div class="search-box">
                <button class='' type="submit" id="searchBtn" style=""> <i class="fas fa-search"></i> </button>
                <!-- <i class="fas fa-search search-icon"></i> -->
                <input form="formFilter" type="text" class="search-input" id="search" name="searchValue" value="<?php echo $searchValue ?>" placeholder="Search...">
            </div>
        </div>
        <div class="col-md-1 col-lg-1 mt-4 d-flex flex-fill align-items-start" style="margin: -1rem 0 0 4rem">
            <div class="resetBtn">
                <button class="w3-teal reset" id="reset">
                    <i class="fa fa-rotate-left icon w3-xlarge"></i><br>
                    <span class="btnLabel"><?php echo displayText('L1337', 'utf8', 0, 0, 1); // reset  S
                                            ?></span>
                </button>
            </div>
        </div>
    </div>

    <div class="chat-popupSType container-fluid p-1" id="SForm">
        <div class="row p-4">
            <div class="col-md-6">
                <h2 class="form-label text-center fw-bold float-start mt-1">S-Type</h2>
            </div>
            <div class="col">
                <button type='submit' form='formFilter' class="btn btn-primary btn-lg fw-bold float-end">FILTER</button>
            </div>

            <select name="sType" id="" class = "sType" form="formFilter">
                <option value="" disabled hidden selected>Select</option>
                <option value="0">Raw</option>
                <option value="1">Material Return</option>
            </select>
        </div>
    </div>

    <div class="chat-popup container-fluid p-4" id="myForm">
        <div class="row p-3">
            <div class="col-md-6">
                <h2 class="form-label text-center fw-bold float-start mt-1">LOCATION</h2>
            </div>
            <div class="col">
                <button type='submit' form='formFilter' class="btn btn-primary btn-lg fw-bold float-end">FILTER</button>
            </div>
        </div>

        <?php
        echo '<select class="w3-input w3-border" name = "location[]" id="details" multiple="multiple" form="formFilter" style="z-index:99999">';
        $detailsArray = array_unique(array_filter(array_map('trim', $locations)));

        sort($detailsArray);
        foreach ($detailsArray as $key) {
            $selectedDetails = "";
            if ($locationCheck != NULL) {
                if (in_array($key, $locationCheck)) $selectedDetails =  "selected";
            }
            echo "<option syle='font-size:40px;'" . $selectedDetails . " value = '" . $key . "'>" . $key . "</option>";
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
        buttonClass: 'w3-input w3-border w3-white',
        buttonWidth: '100%',
        nonSelectedText: 'Select',
        numberDisplayed: 100,
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
        $("#details").trigger("click");
        
        let updateId = $(this).attr("data-id");
        let button2 = $(this);
        let partId = $(this).attr("data-partNumber");
        let oldMemo = $(this).attr("data-memo");
        $("#myForm").toggle();
        $("#memoArea2").on("keyup", function() {
            $("#inputChange").val($(this).val());
        });
    })
    $(document).on("click", ".Smodal", function() {
        $("#SForm").toggle();
    })
</script>