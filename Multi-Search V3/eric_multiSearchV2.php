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

$tpl->setDisplayId("103")
    ->setPrevLink("")
    ->createHeader();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">

    <style>
        #headerText {
            font-size: 4rem !important;
        }

        /* Define your button color classes */
        .green-button {
            background-color: green;
            color: white;
        }

        .blue-button {
            background-color: blue;
            color: white;
        }

        .red-button {
            background-color: red;
            color: white;
        }

        #container {
            margin: 100px auto;
            width: 760px;
            width: 35rem;
            height: 42rem;
            margin-left: 100 !important;
        }

        #keypadButton,
        .reset {
            padding: 24px 8px 10px 8px;
            background-color: #3452b4;
            color: white;
            border-radius: 16px;
            border: none;
            margin-bottom: 0;
        }

        .btnLabel {
            font-size: 16px;
        }

        .reset {
            background-color: #4caf50 !important;
        }

        /* #keypadButton {
        margin-left: 4rem !important;
        } */

        #keyboard {
            margin: 0;
            padding: 16px;
            list-style: none;

            background-color: #a2abba;
            width: 336px;
            height: 350px;
            border-radius: 4px;
            box-shadow: rgba(0, 0, 0, 0.09) 0px 2px 1px, rgba(0, 0, 0, 0.09) 0px 4px 2px, rgba(0, 0, 0, 0.09) 0px 8px 4px, rgba(0, 0, 0, 0.09) 0px 16px 8px, rgba(0, 0, 0, 0.09) 0px 32px 16px;
        }

        #keyboard ul {
            border: 3px solid #fff;
            /* background-color: pink; */
        }

        #keyboard li {
            float: left;
            margin: 0 0px 0px 0;
            width: 100px;
            height: 40px;
            font-size: 16px;
            line-height: 40px;
            text-align: center;
            background: #fff;
            border: 1px solid black;

        }

        .capslock,
        .tab,
        .left-shift,
        .clearl,
        .switch {
            clear: left;
        }

        #keyboard .tab,
        #keyboard .enter {
            width: 200px;
        }

        #keyboard .capslock {
            width: 80px;
        }

        #keyboard .return {
            width: 90px;
        }

        #keyboard .left-shift {
            width: 70px;
        }

        #keyboard .switch {
            width: 90px;
        }

        #keyboard .rightright-shift {
            width: 109px;
        }

        .lastitem {
            margin-right: 0;
        }

        .enter {
            background-color: #b8e6e2;
        }

        .letter {
            width: 100px;
        }

        .del {
            background-color: #fbecdd !important;
        }

        .bs {
            background-color: #f0c243 !important;
        }

        .blue {
            background-color: #cce7eb !important;
        }

        .grey {
            background-color: #d8e2e3 !important;
        }

        .space {
            /* width:  !important; */
        }

        .gap {
            height: 10px;
            /* Adjust the height as needed */
            width: 300px !important;
            background-color: #a2abba !important;
            border: none !important;
        }

        .gap:hover {
            top: 0 !important;
            left: 0 !important;
        }

        .uppercase {
            text-transform: uppercase;
        }

        #keyboard .space {
            float: left;
            width: 556px;
        }

        #keyboard .switch,
        #keyboard .space,
        #keyboard .return {
            font-size: 16px;
        }

        .on {
            display: none;
        }

        #keyboard li:hover {
            position: relative;
            top: 1px;
            left: 1px;
            border-color: black;
            cursor: pointer;
            background-color: #e8e8e8;
        }

        .zoom {
            background-color: #f00;
            /* Adjust the background color as needed */
            color: #fff;
            /* Adjust the text color as needed */
            border: none;
            padding: 8px 16px;
            border-radius: 16px;
            display: none;
        }

        /* local vs cloud */
        .local,
        .cloud {
            border: 3px solid #76D7C4;
            border-radius: 12px;
            padding: 1rem;
            background-color: #E8F6F3;
        }

        .cloud {
            border: 3px solid #F7DC6F;
            margin-top: 1rem;
            background-color: #FCF3CF;

        }
    </style>
</head>

<body>
    <?php
    $searchValue = isset($_POST['searchValue']) ? $_POST['searchValue'] : '';
    ?>

    <div class="container" style="margin-top: 2rem !important;">
        <div class="row">
            <div class="col-sm-1">
                <button class="" id="keypadButton">
                    <!-- keypad -->
                    <i class="fa fa-calculator icon w3-xlarge"></i><br>
                    <span class="btnLabel">KEYPAD</span>
                    <div id="container" style="display:none" class="modal">
                        <ul id="keyboard">
                            <li class="letter del" id="del">DEL</li>
                            <li class="letter" id="space" data-value=" "></li>
                            <li class="letter bs" id="bs">BS</li>
                            <li class="letter blue clearl" data-value="B">B</li>
                            <li class="letter blue" data-value="H">H</li>
                            <li class="letter blue" data-value="M">M</li>
                            <li class="letter blue clearl" data-value="P">P</li>
                            <li class="letter blue" data-value="R">R</li>
                            <li class="letter blue" data-value="S">S</li>

                            <li class="gap clearl drag" id="tip"></li>

                            <li class="letter grey clearl" data-value="7">7</li>
                            <li class="letter grey" data-value="8">8</li>
                            <li class="letter grey" data-value="9">9</li>
                            <li class="letter grey clearl" data-value="4">4</li>
                            <li class="letter grey" data-value="5">5</li>
                            <li class="letter grey" data-value="6">6</li>
                            <li class="letter grey clearl" data-value="1">1</li>
                            <li class="letter grey" data-value="2">2</li>
                            <li class="letter grey" data-value="3">3</li>
                            <li class="letter grey clearl" data-value="0">0</li>
                            <li class="enter" id="enter" style="background-color: #c0fae6;">ENTER</li>
                        </ul>
                    </div>
                    <!-- keypad -->
                </button>
            </div>
            <div class="col-sm-10">
                <div id="searchForm">
                    <input type="search" name="search" id="search" oninput="this.value = this.value.toUpperCase();" placeholder="SEARCH" value="<?php echo isset($_GET['search']) ? strtoupper($_GET['search']) : $searchValue; ?>">
                    <button type="submit" id="searchBtn"> <i class="fas fa-search"></i> </button>
                </div>
            </div>
            <div class="col-sm-1">
                <button class="" id="keypadButton" style="80% !important;">
                    <i class="fa fa-rotate-left icon w3-xlarge"></i><br>
                    <span class="btnLabel">RESET</span>
                </button>
            </div>
        </div>
        <!-- dfhsjs -->
        <!-- <div class="row">
            <div id="searchForm">
                <input type="search" name="search" id="search" oninput="this.value = this.value.toUpperCase();" placeholder="SEARCH" value="<?php echo isset($_GET['search']) ? strtoupper($_GET['search']) : $searchValue; ?>">
                <button type="submit" id="searchBtn"> <i class="fas fa-search"></i> </button>
            </div>
        </div> -->
    </div>

    <div class="container category" id="container-category">

        <div class="local">
            <!-- material, supply, accessory -->
            <div class="row">
                <div class="col-sm-4">
                    <button class="categoryBtn" id="material" role="button" value="material" onclick="" disabled>
                        Material
                        <span value="material"><?php echo ("(" . $materialCount . ")" ? $materialCount : ''); ?></span>
                    </button>
                </div>
                <div class="col-sm-4">
                    <button class="categoryBtn" id="supply" role="button" value="supply" onclick="" disabled>
                        Supplies
                        <span value="supply"><?php echo ("(" . $supplyCount . ")" ? $supplyCount : ''); ?></span>
                    </button>
                </div>
                <div class="col-sm-4">
                    <button class="categoryBtn" id="accessory" style="background-color: #FCF3CF;" role="button" value="accessory" onclick="" disabled>
                        Accessory
                        <span value="accessory"> <?php echo ("(" . $accessoryCount . ")" ? $accessoryCount : ''); ?></span>
                    </button>
                </div>
            </div>
            <!-- finishGoods, drawing, barcode -->
            <div class="row">
                <div class="col-sm-4">
                    <button class="categoryBtn" id="" style="background-color: #EDBB99;" role="button" value="fg" onclick="" disabled>
                        Finish Goods
                        <span value="fg"><?php echo ("(" . $finishGoodsCount . ")" ? $finishGoodsCount : ''); ?></span>
                    </button>
                </div>
                <div class="col-sm-4">
                    <button class="categoryBtn" id="draw" role="button" value='drawing' onclick="" disabled>
                        Drawing
                        <span value="drawing"><?php echo ("(" . $drawingCount . ")" ? $drawingCount : ''); ?></span>
                    </button>
                </div>
                <div class="col-sm-4">
                    <button class="categoryBtn" id="" style="background-color: #A9CCE3;" role="button" value='barcode' onclick="" disabled>
                        Barcode
                        <span value="barcode"><?php echo ("(" . $drawingCount . ")" ? $drawingCount : ''); ?></span>
                    </button>
                </div>
            </div>
        </div>

        <!-- cloud -->
        <div class="cloud">
            <!-- video, audio, pdf  -->
            <div class="row">
                <div class="col-sm-4">
                    <button class="categoryBtn" id="" style="background-color: #B9F5D8;" role="button" value='video' onclick="" disabled>
                        Video
                        <span value='video'><?php echo ("(" . $drawingCount . ")" ? $drawingCount : ''); ?></span>
                    </button>
                </div>
                <div class="col-sm-4">
                    <button class="categoryBtn" id="audio" role="button" value='audio' disabled>
                        Audio
                        <span value='audio'><?php echo ("(" . $drawingCount . ")" ? $drawingCount : ''); ?></span>
                    </button>
                </div>
                <div class="col-sm-4">
                    <button class="categoryBtn" id="pdf" role="button" value='pdf' disabled>
                        PDF
                        <span value='pdf'><?php echo ("(" . $drawingCount . ")" ? $drawingCount : ''); ?></span>
                    </button>
                </div>
            </div>
            <!-- photos, calling card -->
            <div class="row">

                <div class="col-sm-4">
                    <button class="categoryBtn" id="draw" style="background-color: #B3AFC0" role="button" value="image" disabled>
                        Photos
                        <span value='image'><?php echo ("(" . $drawingCount . ")" ? $drawingCount : ''); ?></span>
                    </button>
                </div>
                <div class="col-sm-4">
                    <button class="categoryBtn" id="material" style="background-color: #FFCD70" role="button" value="cards" disabled>
                        Calling Card
                        <span value='cards'><?php echo ("(" . $drawingCount . ")" ? $drawingCount : ''); ?></span>
                    </button>
                </div>
            </div>
        </div>
</body>

</html>
<?php PMSResponsive::includeFooter(); ?>
<script>
    $(document).ready(function() {
        var searchValue = '<?php echo $searchValue ?>';
        const url = "eric_cloudV2.php";
        const cloudUrl = `https://arktechph.com/V4/20%20Document%20Management%20System/eric_getCounts.php`;

        const fetchDataFromServer = (url, searchValue) => {
            const postData = {
                "searchValue": searchValue,
                "type": 1,
            };
            return $.ajax({
                url: url,
                type: "POST", // Use POST request instead of GET
                dataType: "json",
                data: postData,
            });
        };

        const updateButtonLabels = (data) => {
            for (let key in data) {
                if (data.hasOwnProperty(key)) {
                    const label = data[key];
                    const $button = $(`button[value="${key}"]`);
                    $button.find('span').text(`(${label})`);

                    if (label === '0') {
                        $button.css('background-color', '#99A3A4');
                    } else {
                        $button.css('background-color', '');
                        $button.attr('disabled', false);
                    }
                }
            }
        }
                
        const fetchFromCloud = (cloudUrl, value) => {
            return fetch(cloudUrl + "?searchValue=" + value, {
                    method: 'GET',
                    mode: 'cors',
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(data => {
                    return {
                        success: true,
                        data
                    }; // Return a success flag and the data
                })
                .catch(error => {
                    return {
                        success: false,
                        error: error.message
                    }; // Return an error flag and the error message
                });
        }

        $('#search').on('keyup', function(event) {
            if (event.keyCode === 13) { // Check if the Enter key (key code 13) was pressed ENTER BUTTON
                const searchValue = $("#search").val();
                if (searchValue !== '') {
                    fetchDataFromServer(url, searchValue).then((serverData) => {
                        updateButtonLabels(serverData);
                        fetchFromCloud(cloudUrl, searchValue).then((cloudResponse) => {
                            if (cloudResponse.success) {
                                let cloudData = cloudResponse.data
                                updateButtonLabels(cloudData);
                            } else {
                                console.error("Error fetching data from the cloud:", cloudResponse.error);
                            }

                        }).catch((error) => {
                            console.error("Error fetching data from the cloud:", error);
                        });

                        // console.log(combinedData);
                    }).catch((error) => {
                        console.error("Error fetching data:", error);
                    });
                }
            }
        });

        if (searchValue !== '') {
            fetchDataFromServer(url, searchValue).then((serverData) => {
                updateButtonLabels(serverData);
                fetchFromCloud(cloudUrl, searchValue).then((cloudResponse) => {
                    if (cloudResponse.success) {
                        let cloudData = cloudResponse.data
                        updateButtonLabels(cloudData);
                    } else {
                        console.error("Error fetching data from the cloud:", cloudResponse.error);
                    }

                }).catch((error) => {
                    console.error("Error fetching data from the cloud:", error);
                });

                // console.log(combinedData);
            }).catch((error) => {
                console.error("Error fetching data:", error);
            });
        }

        $('.categoryBtn').click(function() {
            var type = $(this).val();

            const types = {
                material: '1',
                supply: '3',
                accessory: '4',
                fg: '5',
            };

            console.log(type);

            if (types[type]) {
                redirectToSearchResults(types[type]);
            } else if (type === 'video' || type === 'audio' || type === 'pdf' || type === 'image' || type === 'cards') {
                event.preventDefault();
                redirectToVideo(type);
            } else if (type === 'barcode') {
                event.preventDefault();
                if ($(this).prop('disabled')) {
                    return;
                }
                redirectToBarcodeSearch();
            } else {
                redirectToDrawing(type);
            }
        });

        function redirectToSearchResults(type) {
            const searchValue = $("#search").val();
            const url = `jera_searchResults.php?type=${type}&searchValue=${encodeURIComponent(searchValue)}`;
            window.location.href = url;
        }

        function redirectToDrawing(type) {
            const searchValue = $("#search").val();
            const url = `jera_searchDrawing.php?type=${type}&searchValue=${encodeURIComponent(searchValue)}`;
            window.location.href = url;
        }

        function redirectToVideo(type) {
            const searchValue = $("#search").val();

            const fileTypeMapping = {
                video: '4',
                audio: '3',
                pdf: '1',
                cards: '5',
                image: '2'
            };

            const fileNumber = fileTypeMapping[type];

            const fileUrl = `https://arktechph.com/V4/20%20Document%20Management%20System/jera_cloudSearch.php?file=${fileNumber}`;
            const url = `${fileUrl}&searchValue=${encodeURIComponent(searchValue)}`;
            window.location.href = url;
        }

        function redirectToBarcodeSearch() {
            const searchValue = $("#search").val();
            const baseUrl = 'http://192.168.254.163/V4/76%20Nandemo%20Barcode%20Software/v4.3/ck_searchEngine3.php';

            const barcodeSearchUrl = `${baseUrl}?se=1&barcode=${searchValue}&getSearchKey=${searchValue}&historySearch=&return=1`;

            window.location.href = barcodeSearchUrl;
        }

    });
</script>

<!-- for keypad -->
<script>
    $(document).ready(function() {

        const keypadButton = document.getElementById("keypadButton");
        const keypadModal = document.getElementById("container");
        const input = document.getElementById("searchKey");
        const tip = document.getElementById("tip");

        let tagCount = 0;

        $("#enter").click(function() {
            // Trigger the form submission
            $("#search").submit();
        });

        function clearErrorText() {
            tip.textContent = "";
            tip.style.color = "";
            tagCount = 0; // reset tagCount
        }

        $("#keyboard .letter").click(function() {
            const value = $(this).data("value");
            const keyId = $(this).attr("id");

            if (keyId === "bs") {
                input.value = input.value.slice(0, -1);
            } else if (keyId === "del") {
                const currentValue = input.value;
                input.value = currentValue.substring(0, currentValue.lastIndexOf(" "));
            } else if (value !== undefined) {
                if ($(this).hasClass("blue")) {
                    tagCount++;
                    if (tagCount > 1) {
                        tip.textContent = "Error: Check your barcode.";
                        tip.style.color = "red";
                        setTimeout(clearErrorText, 2000);
                        input.value = "";
                        return;
                    }
                }
                input.value += value;
            }
        });

        keypadButton.addEventListener("click", () => {
            if (keypadModal.style.display === "block") {
                keypadModal.style.display = "none";
                l
                clearErrorText();
                tagCount = 0;
            } else {
                keypadModal.style.display = "block";
                keypadModal.style.background = "none";
                keypadModal.style.marginTop = "25rem";
            }
        });
    });
</script>
<!-- end of jera script for handling keys 09/01/2023 -->


<!-- jera draggable keypad 09/01/2023-->
<script>
    $(document).ready(function() {
        const keypadModal = document.getElementById("container");
        const tip = document.getElementById("tip");
        let isDragging = false;
        let startPosX = 0;
        let startPosY = 0;

        tip.addEventListener("mousedown", (e) => {
            isDragging = true;
            startPosX = e.clientX - keypadModal.getBoundingClientRect().left;
            startPosY = e.clientY - keypadModal.getBoundingClientRect().top;
        });

        document.addEventListener("mousemove", (e) => {
            if (isDragging) {
                const left = e.clientX - startPosX;
                const top = e.clientY - startPosY;

                keypadModal.style.left = `${left}px`;
                keypadModal.style.top = `${top}px`;
            }
        });

        document.addEventListener("mouseup", () => {
            isDragging = false;
        });

    });
</script>
<!-- end of jera draggable keypad 09/01/2023-->