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
    
    if ($type == 'drawing') {
        ob_start();
        echo  "- ".displayText('L77', 'utf8', 0, 0, 1);
        $version = ob_get_clean();
    }



    PMSResponsive::includeHeader("Multi-Search");
    $ctrl = new PMSDatabase;
    $tpl = new PMSTemplates;
    $pms = new PMSDBController;
    $rdr = new Render\PMSTemplates;
    $tpl->setDisplayId("103") # OPTIONAL
        ->setVersion($version) # OPTIONAL
        ->setPrevLink("eric_multiSearch.php")
        ->createHeader();

        
    $searchValue = isset($_POST['searchValue']) ? $_POST['searchValue'] :  $_GET['searchValue'];




    ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/searchpanes/2.1.2/css/searchPanes.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/select/1.6.2/css/select.dataTables.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.2.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"
        integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <script src="https://cdn.datatables.net/scroller/2.0.5/js/dataTables.scroller.min.js"></script>
    <link rel="stylesheet" type="text/css"
        href="https://cdn.datatables.net/scroller/2.0.5/css/scroller.dataTables.min.css">
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.5.1.js"></script>

    <link rel="stylesheet" href="assets/css/style.css">


    <style>
    .hide-homeBtnIcon {
        display: none !important;
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

    .dropdown-menu {
        font-size: initial !important;
    }

    .search-box {
        display: flex;
        align-items: center;
        background: #fff;
        border-radius: 25px;
        margin: 10px;
        padding: 4px 8px 4px 4px !important;
        box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.4);

    }

    .search-input {
        line-height: 40px;
        font-size: 16px;
        border: 1px solid white !important;
        border-radius: 25px;
        outline: none;
    }

    #search {
        /* width: 20rem; */
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
        border-width: 2px;
        border-color: rgba(255, 255, 255, 0.333);

        transform: translate(0px, 0px) rotate(0deg);
        transition: 0.2s;
        box-shadow: -7px 6px 4px 0px rgba(255, 255, 255, 0.25) inset, -4px 1px 4px 0px rgba(58, 42, 42, 0.25) inset, 3px -4px 4px 0px rgba(154, 154, 154, 0.37) inset;

    }

    #searchBtn:active {
        background: #427A9C;
        box-shadow: -8px 5px 4px 0px rgba(40, 40, 40, 0.25) inset, -4px -1px 4px 0px rgba(41, 41, 41, 0.25) inset;
        padding-right: 1%;
    }

    .search-icon {
        color: #fff;
        margin-right: 5px;
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

    @media screen and (max-height: 768px) {

        .dataTable td:nth-child(7),
        .dataTable th:nth-child(7) {
            /* display: none; */
        }
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
            margin: 1.5rem 0 0 .5rem !important;
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

    <style>
    /* Style for the toggle switch */
    .toggle {
        position: relative;
        width: 60px;
        height: 34px;
        margin-left: 1450px;
        /* Push the toggle to the right */
    }

    /* Hide default checkbox */
    .toggle input {
        display: none;
    }

    /* Style for the slider */
    .slider {
        position: absolute;
        cursor: pointer;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-color: #ccc;
        border-radius: 34px;
        transition: .4s;
    }

    /* Slider's circle */
    .slider:before {
        position: absolute;
        content: "";
        height: 26px;
        width: 26px;
        left: 4px;
        bottom: 4px;
        background-color: white;
        border-radius: 50%;
        transition: .4s;
    }

    /* Change slider color when checked */
    .toggle input:checked+.slider {
        background-color: #2196F3;
    }

    /* Move the slider circle when checked */
    .toggle input:checked+.slider:before {
        transform: translateX(26px);
    }
    </style>

</head>

<body>
    <form action="" method="post" id='formFilter'></form>
    <div class="row" id="top">

        <div class="col-md-6 col-lg-2 d-flex align-items-start">
            <div class="search-box  mt-4">

                <button form='formFilter' class='' type="submit" id="searchBtn" style=""> <i
                        class="fas fa-search search-icon"></i> </button>
                <input form="formFilter" type="text" class="search-input" id="search" name="searchValue"
                    value="<?php echo $searchValue ?>" placeholder="Search...">
            </div>

            <div class="resetBtn  mt-4">
                <button class="w3-teal reset" id="reset">
                    <i class="fa fa-rotate-left icon w3-xlarge"></i><br>
                    <span class="btnLabel"><?php echo displayText('L1337', 'utf8', 0, 0, 1); // reset  
                                                ?></span>
                </button>

            </div>


        </div>
        <div class="container">
            <label class="toggle">
                <input type="checkbox">
                <span class="slider"></span>
            </label>
        </div>


    </div>


    <div class="flexContainer">
        <div class="tableDatas">

            <?php

                if ($_SERVER['REQUEST_METHOD'] == 'GET' or $_SERVER['REQUEST_METHOD'] == 'POST') {
                    
                    $searchKeys = preg_split('/[\p{Zs}\s]+/u', $searchValue);


                    // $sql = "SELECT * FROM cadcam_parts WHERE status = 0 AND ";
                    $sql = "SELECT * FROM cadcam_parts WHERE ";


                    foreach ($searchKeys as $key) {
                        $sql .= "(partNumber LIKE '%$key%' OR partName LIKE '%$key%') AND ";
                    }
                      $sType = isset($_POST['sType']) ? $_POST['sType'] : '';
                    $sql = rtrim($sql, 'AND ');

                    $query = $db->query($sql);
                    $totalRecords = $query->num_rows;

                    // include 'views/tables/jera_includeDrawing.php'; 
                    if ($query->num_rows > 0) {
                        echo '<label>' . displayText("L509", "utf8", 0, 0, 1)  . ' :' . $totalRecords . '</label>';
                        echo ' <table id="mainTableId" class="cell-border display compact" style="width:100%">
                        <thead>
                            <tr>
                            <th class="w3-indigo" colspan="1"  rowspan="2" style="text-align: center; vertical-align: middle; width: 10px;">#</th>';

                    
                        //jovit
                        echo "<th class='w3-indigo' colspan='1' rowspan='2' style='text-align: center; vertical-align: middle;'>" . displayText("L269", "utf8", 0, 0, 1) . "</th>"; // Customer Name
                        echo "<th class='w3-amber' colspan='1' rowspan='2' style='text-align: center; vertical-align: middle; '>" . displayText("L28", "utf8", 0, 0, 1) . "</th>"; // Part Number       
                        echo "<th class='w3-amber' colspan='1' rowspan='2' style='text-align: center; vertical-align: middle; ;'>" . displayText("L4739", "utf8", 0, 0, 1) . "</th>"; // Part Name
                        echo "<th class='w3-indigo' colspan='1' rowspan='2' style='text-align: center; vertical-align: middle; width: 75px;'>" . displayText("L4740", "utf8", 0, 0, 1) . "</th>"; // REVISION ID
                        echo "<th class='w3-indigo' colspan='1' style='text-align: center; vertical-align: middle; width: 75px;'>" . displayText("L24", "utf8", 0, 0, 1) . "</th>"; // Customer
                        echo "<th class='w3-indigo' colspan='1' style='text-align: center; vertical-align: middle; width: 75px;'>" . displayText("L4663", "utf8", 0, 0, 1) . "</th>"; // Arktech
                        echo "<th class='w3-indigo' colspan='1' style='text-align: center; vertical-align: middle; width: 75px;'>" . displayText("L4157", "utf8", 0, 0, 1) . "</th>"; // Isometric
                        echo "<th class='w3-indigo' colspan='1' style='text-align: center; vertical-align: middle; width: 75px;'>" . "3D PDF" . "</th>"; // 3D PDF
                        echo "<th class='w3-indigo' colspan='1' style='text-align: center; vertical-align: middle; width: 75px;'>" . displayText("L10007", "utf8", 0, 0, 1) . "</th>"; // Process Drawing

                        
                        
                   
                        echo ' </tr>

                            <tr>
                                <th class="w3-indigo" style="text-align:  center; display: none; vertical-align: middle;">ARK</th>
                                <th class="w3-indigo" style="text-align: center; display: none; vertical-align: middle;">CUS</th>
                                <th class="w3-indigo" style="text-align: center; display: none; vertical-align: middle;">CUS</th>
                                <th class="w3-indigo" style="text-align: center; display: none; vertical-align: middle;">CUS</th>
                                <th class="w3-indigo" style="text-align: center; display: none; vertical-align: middle;">CUS</th>
                            </tr>

                            
                        </thead>
                        <tbody>

                        </tbody>
                    </table>';
                    } else {
                        echo '
                
                    <div class="bodyTable">
                        <img src="nodata.jpg" alt="" class = "logo">
                    </div>

                ';
                    }
                } else {
                }
                ?>

        </div>
    </div>

    <div id="modal-izis"><span class="izimodal-content"></span></div>
    <div id="modal-izi"><span class="izimodal-contents"></span></div>
    <div id="modal-izi-drawing"><span class="izi-content-drawing"></span></div>

    <!-- <div id="modal-izi"><span class="izimodal-search"></span></div> -->

    <!-- 3d,wi,all columns -->
    <!-- <th class="w3-indigo" style="text-align: center; vertical-align: middle;">3D</th>
        <th class="w3-indigo" style="text-align: center; vertical-align: middle;">WI</th>
        <th class="w3-indigo" style="text-align: center; vertical-align: middle;">ALL</th> -->

</body>

</html>

<?php PMSResponsive::includeFooter(); ?>
<style>
/* Custom even-row class */
#mainTableId .even-row {
    background-color: #caf0f8;
}

/* Custom odd-row class */
#mainTableId .odd-row {
    background-color: #ade8f4;
}

#mainTableId .highlighted-row {
    background-color: orange;
}


#mainTableId {
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
    padding: 0px;
}

#mainTableId tbody tr,
#mainTableId td {
    border: 1px solid whitesmoke !important;
    padding: 4px !important;

}

/* Custom even-row class */
#mainTableId .even-row {
    background-color: #caf0f8;
}

/* Custom odd-row class */
#mainTableId .odd-row {
    background-color: #ade8f4;
}

#mainTableId .highlighted-row {
    background-color: orange;
}

#centerDwgBtn {
    align-self: center !important;
}
</style>



<script>
// var searchValue = '<?php echo isset($_POST['search']) ? $_POST['search'] : ''; ?>';
var searchValue = '<?php echo $searchValue ?>';

const drawingViewer = (param, obj, btn) => {
    $('#mainTableId tbody tr').css('background-color', '');
    $(obj).closest('#mainTableId tbody tr').css('background-color', 'orange');
    const viewerURL = `/<?php echo v; ?>/20 Document Management System/eric_drawingViewer _jovit.php`;
    console.log(viewerURL, obj, btn)

    $("#modal-izi-drawing").iziModal({
        title: '<i class="fa fa-eye"></i> DRAWING VIEWER',
        headerColor: '#1F4788',
        subtitle: `<b></b>`,
        width: 1200,
        fullscreen: false,
        transitionIn: 'comingIn',
        transitionOut: 'comingOut',
        radius: 0,
        top: 10,
        restoreDefaultContent: true,
        closeOnEscape: true,
        closeButton: true,
        overlayClose: false,
        footer: false,
        onOpening: function(modal) {
            modal.startLoading();
            $.ajax({
                url: viewerURL,
                type: 'POST',
                data: {
                    // "lotNumber": param,
                    "partId": param,
                    "sqlData": "<?php echo $sql; ?>",
                    "dwgType": 2,
                    "type": 1,
                    "target": btn // jovit
                },
                success: function(data) {
                    // console.log(data); // Log the data received from the server
                    $(".izi-content-drawing").html(data);

                    modal.stopLoading();


                },
                error: function(xhr, status, error) {
                    console.error("AJAX Error:", status, error);
                }
            });
        },
        onClosed: function(modal) {
            $("#modal-izi-drawing").iziModal("destroy");
        }
    });
    $("#modal-izi-drawing").iziModal("open");
}

$(document).ready(function() {
    var search = $('#search').val();

    var sqlData = "<?php echo $sql; ?>";
    console.log(sqlData);
    var totalRecords = "<?php echo $totalRecords; ?>";
    var dataTable = $('#mainTableId').DataTable({
        "searching": false,
        "processing": true,
        "stripeClasses": ["even-row", "odd-row"],
        "createdRow": function(row, data, dataIndex) {
            $(row).addClass("w3-hover-orange");
            // Add CSS class to alternate rows
            // const bgColor = dataIndex % 2 === 0 ? "w3-green" : "w3-red";
            // $(row).addClass(bgColor);

            const hiddenInput = $(row).find('input[type="hidden"]');
            const hiddenValue = hiddenInput.val();
            $(row).attr('data-id', hiddenValue);

            // console.log(hiddenValue)
        },
        "ordering": false,
        "serverSide": true,
        "bInfo": false,
        "ajax": {
            url: "controllers/ajax/jovit_drawingSearchAjax.php", // json datasource
            type: "POST", // method  , by default get
            data: {
                "totalRecords": totalRecords,
                "sqlData": sqlData,
                "query": "<?php echo $sql; ?>",
                search: search,
                searchValue: searchValue,
            },
            error: function(data) { // error handling
                console.log(data);
            }
        },

        language: {
            processing: "<span class='loader'></span>"
        },
        fixedColumns: {
            leftColumns: 0
        },
        "columnDefs": [{
            // "targets": [0, 1, 2, 4, 5, 6, 8, 9],
            "targets": [0, 1, 2, 4, 5],
            "render": function(data, type, row) {
                return '<div style="white-space:normal !important; font-size: 12px; align-items: center !important">' +
                    data + '</div>';
            }
        }],
        // responsive		: true,
        scrollY: 600,
        scrollX: true,
        scrollCollapse: false,
        scroller: {
            loadingIndicator: true
        },
        stateSave: false
    });

    var screenWidth = window.innerWidth;
    var screenHeight = window.innerHeight;


    if ((screenWidth <= 1024 && screenHeight <= 653) || (screenWidth <= 1024 && screenHeight <= 748) || (
            screenWidth <= 1280 && screenHeight <= 800) || (screenWidth <= 1024 && screenHeight <= 768)) {
        // dataTable.column(9).visible(false);
        // dataTable.column(7).visible(false); //jera 09/04/2023 hides the 3d drawing
        // dataTable.column(6).visible(false);

    }

    // dataTable.column(7).visible(false);


    $("#reset").click(function() {
        window.location.href = 'jera_searchDrawing.php';
    })

    // $('#mainTableId tbody').on('click', 'tr', function() {
    //     const [isHidden, ...data] = dataTable.row(this).data();
    //     const look = isHidden.match(/value\s*=\s*['"]([^'"]+)['"]/);
    //     look ? drawingViewer(look[1], this) : "";

    // });
});

function multiSearch() {
    // var modalId = 'multiSearchModal_' + new Date().getTime();

    $("#modal-izi").iziModal({
        width: 1200,
        fullscreen: false,
        transitionIn: 'comingIn',
        transitionOut: 'comingOut',
        // padding: 20,
        openFullscreen: false,
        radius: 0,
        top: 10,
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
                    // type: 1,
                    // partId: partId
                    searchValue: searchValue
                },
                success: function(data) {
                    $(".izimodal-contents").html(data);
                    modal.stopLoading();

                    // Hide the element when the modal is opened
                    $(".btn-real-dent.btn-real-dent1x.homeBtnIcon").addClass(
                        "hide-homeBtnIcon");
                }
            });
        },
        onClosed: function(modal) {
            $(".btn-real-dent.btn-real-dent1x.homeBtnIcon").removeClass("hide-homeBtnIcon");
            $("#modal-izi").iziModal("destroy");
        }
    });

    $("#modal-izi").iziModal("open");
}



function showWI(partId) {

    $("#modal-izis").iziModal({
        title: '<i class="fa fa-list"></i> WORK INSTRUCTION',
        headerColor: '#1F4788',
        subtitle: '<b><?php echo strtoupper(date('F d, Y')); ?></b>',
        width: 700,
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
            $.ajax({
                url: 'controllers/ajax/mj_dmsModal.php',
                type: 'POST',
                data: {
                    type: 1,
                    partId: partId
                },
                success: function(data) {
                    console.log(data);
                    console.log(partId);
                    $(".izimodal-content").html(data);
                    modal.stopLoading();
                },
                error: function(xhr, status, error) {
                    console.error("AJAX request error:");
                    console.error(error);
                }
            });
        },
        onClosed: function(modal) {
            $("#modal-izis").iziModal("destroy");
        }
    });

    $("#modal-izis").iziModal("open");
}


function showAllDraw(partId) {
    var pdfFiles = [
        "/Document Management System/Arktech Folder/ARK_" + partId + ".pdf",
        "/Document Management System/Master Folder/MAIN_" + partId + ".pdf"
    ];

    var download = "/Document Management System/3D Files/3DF_" + partId + ".PDF";

    var windowWidth = 800; // Width of each window
    var windowHeight = 400; // Height of each window
    var windowLeft = 10; // Left position of the first window
    var windowGap = 10; // Gap between windows
    var existingFilesCount = 0;

    // Check if the download file exists using an AJAX request
    var downloadXhr = new XMLHttpRequest();
    downloadXhr.open("HEAD", download, false);
    downloadXhr.send();

    if (downloadXhr.status === 200) {
        // File exists, initiate download
        var downloadLink = document.createElement("a");
        downloadLink.href = download;
        downloadLink.download = "DownloadedFile.pdf";
        downloadLink.target = "_blank";
        downloadLink.click();
    }

    pdfFiles.forEach(function(pdfFile) {
        if (pdfFile !== "" && pdfFile !== null && pdfFile !== undefined) {
            // Check if the file exists using an AJAX request
            var xhr = new XMLHttpRequest();
            xhr.open("HEAD", pdfFile, false);
            xhr.send();

            if (xhr.status === 200) {
                // File exists, open the window
                var windowName = "window" + ++existingFilesCount;
                var windowFeatures = "width=" + windowWidth + ",height=" + windowHeight + ",left=" + windowLeft;
                var newWindow = window.open(pdfFile, "_blank", windowFeatures);

                // Adjust the width, height, and left position according to your requirements

                if (newWindow) {
                    // Window opened successfully
                    // Add additional logic here if needed
                } else {
                    // Window blocked by pop-up blocker or failed to open
                    // Add error handling logic here
                    console.log("Window blocked by pop-up blocker or failed to open");
                }

                windowLeft += windowWidth + windowGap;
            } else {
                // File does not exist, skip opening the window
            }
        }
    });
    $.ajax({
        url: "controllers/ajax/mj_workDrawing.php",
        type: "POST",
        data: {
            partId: partId,
            type: 1,
        },
        success: function(response) {
            if (response && response.trim() !== '') {
                // AJAX request succeeded and a valid file URL is received
                var pdfUrls = JSON.parse(response);
                var totalFiles = pdfUrls.length;

                if (totalFiles > 0) {
                    var currentIndex = 0; // Current index of the displayed PDF

                    var windowFeatures = "width=" + 800 + ",height=" + 400 + ",left=" + 800 + ",top=" + 500;
                    var pdfWindow = window.open("", "_blank", windowFeatures);

                    // Function to display the current PDF file
                    function displayCurrentPdf() {
                        var pdfIframe = pdfWindow.document.getElementById("pdf-iframe");
                        if (pdfIframe) {
                            pdfIframe.src = pdfUrls[currentIndex];
                        } else {
                            var iframe = pdfWindow.document.createElement("iframe");
                            iframe.id = "pdf-iframe";
                            iframe.src = pdfUrls[currentIndex];
                            iframe.style.width = "100%";
                            iframe.style.height = "100%";

                            pdfWindow.document.body.appendChild(iframe);
                        }

                        attachEventListeners();
                    }

                    // Function to attach event listeners to next and previous buttons
                    function attachEventListeners() {
                        var nextButton = pdfWindow.document.getElementById("next-button");
                        var previousButton = pdfWindow.document.getElementById("previous-button");

                        if (nextButton) {
                            nextButton.removeEventListener("click", showNextPdf);
                        }
                        if (previousButton) {
                            previousButton.removeEventListener("click", showPreviousPdf);
                        }

                        nextButton = pdfWindow.document.createElement("button");
                        nextButton.id = "next-button";
                        nextButton.innerText = "Next";
                        nextButton.style.backgroundColor = "blue";
                        nextButton.style.padding = "0.7rem";
                        nextButton.style.color = "white";
                        nextButton.style.cursor = "pointer";
                        nextButton.style.fontWeight = "600";

                        previousButton = pdfWindow.document.createElement("button");
                        previousButton.id = "previous-button";
                        previousButton.innerText = "Previous";
                        previousButton.style.backgroundColor = "blue";
                        previousButton.style.padding = "0.7rem"
                        previousButton.style.color = "white";
                        previousButton.style.cursor = "pointer";
                        previousButton.style.fontWeight = "600";

                        var buttonContainer = pdfWindow.document.createElement("div");
                        buttonContainer.appendChild(previousButton);
                        buttonContainer.appendChild(nextButton);

                        buttonContainer.style.position = "fixed";
                        buttonContainer.style.top = "10px";
                        buttonContainer.style.left = "1rem";
                        buttonContainer.style.zIndex = "9999";
                        buttonContainer.style.fontSize = "2rem";
                        buttonContainer.style.fontWeight = "600";
                        buttonContainer.style.margin = "0 1rem";

                        previousButton.style.marginRight = "1.5rem";

                        // Disable next button if no more files in the forward direction
                        if (currentIndex === totalFiles - 1) {
                            nextButton.disabled = true;
                        }

                        // Disable previous button if no more files in the backward direction
                        if (currentIndex === 0) {
                            previousButton.disabled = true;
                        }

                        nextButton.addEventListener("click", showNextPdf);
                        previousButton.addEventListener("click", showPreviousPdf);

                        pdfWindow.document.body.appendChild(buttonContainer);
                    }

                    // Function to display the next PDF file
                    function showNextPdf() {
                        currentIndex++;
                        if (currentIndex >= totalFiles) {
                            currentIndex = totalFiles - 1; // Stay at the last file
                        }
                        displayCurrentPdf();
                    }

                    // Function to display the previous PDF file
                    function showPreviousPdf() {
                        currentIndex--;
                        if (currentIndex < 0) {
                            currentIndex = 0; // Stay at the first file
                        }
                        displayCurrentPdf();
                    }

                    displayCurrentPdf();
                } else {
                    console.log("No files found");
                }
            } else {
                // No file URLs received, handle the case here (e.g., show an error message)
                console.log("No files found");
            }
        },
        error: function(xhr, status, error) {
            // AJAX request encountered an error, handle the error here
            console.log("Error");
        }
    });




}
</script>