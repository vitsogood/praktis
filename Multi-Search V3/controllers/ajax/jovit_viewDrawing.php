<?php
include $_SERVER['DOCUMENT_ROOT'] . "/version.php";
$path = $_SERVER['DOCUMENT_ROOT'] . "/" . v . "/Common Data/";
set_include_path($path);
include('PHP Modules/mysqliConnection.php');
include('PHP Modules/anthony_wholeNumber.php');
include('PHP Modules/anthony_retrieveText.php');
include('PHP Modules/gerald_functions.php');
ini_set("display_errors", "on");
$ctrl = new PMSDatabase;
$tpl = new PMSTemplates;

$sql = isset($_POST['sql']) ? $_POST['sql'] : '';
$partId = isset($_POST['partId']) ? $_POST['partId'] : '';
$dwgType = isset($_POST['dwgType']) ? $_POST['dwgType'] : '';

$sql1 = "SELECT * FROM cadcam_parts WHERE partId = $partId";
$query1 = $db->query($sql1);
if ($query1->num_rows > 0) {
    while ($row1 = $query1->fetch_assoc()) {
        $partNumber = $row1['partNumber'];
        $partName = $row1['partName'];
    }
}


if ($dwgType == "ark") {
    $src = "/Document Management System/Arktech Folder/ARK_" . $partId . ".pdf";
}

if ($dwgType == "cus") {
    $src = "/Document Management System/Master Folder/MAIN_" . $partId . ".pdf";
}

// echo $sql;
?>

<div class="container-fluid">
    <div class="row">
        <div class="w3-container w3-indigo w3-padding w3-card-2">
            <div class="col-md-12">
                <label class='w3-large' id="header" style='text-transform:;'>
                    <b><?php echo strtoupper("Drawing Viewer"); ?></b>
                    <?php echo "- " . $partName . " (" . $partNumber . ")"; ?>
                </label>

            </div>
        </div>
    </div>
    <div class="row w3-padding-top">
        <div class="col-md-12" style="display: flex; justify-content: space-between;">
            <div class='w3-left'>
                <button id='prv' <?php echo $disabledPrev; ?> class='w3-btn w3-round w3-indigo'><b>PREV</b></button>
                <button id='nxt' <?php echo $disabledNext; ?> class='w3-btn w3-round w3-indigo'><b>NEXT</b></button>
            </div>
            <div class='w3-center' style="">
                <button id='arkBtn' class='w3-btn w3-round <?php echo ($dwgType == 'ark') ? 'w3-orange' : 'w3-green'; ?>'><b>ARK</b></button>
                <button id='cusBtn' class='w3-btn w3-round <?php echo ($dwgType == 'cus') ? 'w3-orange' : 'w3-green'; ?>'><b>CUS</b></button>
            </div>
            <div class='w3-right'>
                <button data-izimodal-close="" data-izimodal-transitionout="bounceOutDown" class='w3-btn w3-round w3-red'><b>CLOSE</b></button>
            </div>

        </div>
    </div>
    <div class="row w3-padding-top">
        <div class="col-md-12">
            <div class="img-responsive changeContent">
                <iframe id='iframeToChange' style='width: 100%; height: 90vh; border:0;' src="<?php echo $src; ?>" allowfullscreen></iframe>
            </div>
        </div>
    </div>
</div>
<?php
PMSTemplates::includeFooter();
?>

<script>
    $(document).ready(function() {
        let modalData = [];
        let partId = '<?php echo $partId;  ?>'
        let path = '<?php echo $_SERVER['DOCUMENT_ROOT']; ?>'
        let drawingType = '<?php echo $dwgType; ?>'

        let partNumber = '<?php echo $partNumber; ?>'
        let partName = '<?php echo $partName; ?>'


        const postData = {
            "sql": "<?php echo $sql; ?>",
            "drawingType": drawingType,
        };

        $("#nxt, #prv").hide();

        const highlightRow = (position) => {
            $("#dataTable tbody tr").css('background-color', '');
            $(`#dataTable tbody tr[data-id="${position.partId}"]`).css('background-color', 'orange');
            const element = $(`#dataTable tbody tr[data-id="${position.partId}"]`)[0];


            console.log(element, position.partId, "highlight")
            if (element && element.scrollIntoViewIfNeeded) {
                element.scrollIntoViewIfNeeded({
                    behavior: "instant",
                    block: "start",
                    inline: "nearest"
                });
            } else if (element) {
                element.scrollIntoView({
                    behavior: "instant",
                    block: "end",
                    inline: "nearest"
                });
            }
        }

        const fetchDataFromServer = (url, type) => {
            return $.ajax({
                url: url,
                type: "POST",
                dataType: "json",
                data: {
                    ...postData,
                    "drawingType": type
                },
            });
        };

        const displayDataInModal = (data, flag) => {
            let folder, type;
            if (drawingType == 'ark') {
                folder = "Arktech";
                type = "ARK";
                $("#arkBtn").addClass("w3-orange").removeClass("w3-green");
                $("#cusBtn").removeClass("w3-orange").addClass("w3-green");
            } else if (drawingType == 'cus') {
                folder = "Master";
                type = "MAIN";
                $("#cusBtn").addClass("w3-orange").removeClass("w3-green");
                $("#arkBtn").removeClass("w3-orange").addClass("w3-green");
            }

            if (flag != '') {
                type = flag == '0' ? 'ARK' : 'MAIN'
                folder = flag == '0' ? 'Arktech' : 'Master'

            }
            // console.log(flag,data)
            let source = `/Document Management System/${folder} Folder/${type}_${data.partId}.pdf`
            $("#iframeToChange").attr("src", source);
            // console.log(source, drawingType)
            // // console.log(partNumber,partName)
            // console.log(data.partName, data.partNumber);
            // console.log(flag, data, source)

            $("#header").text("Drawing Viewer - " + data.partName + " (" + data.partNumber + ")");

        }

        const hasDrawing = (data) => {
            let sources = `/Document Management System/Arktech Folder/ARK_${data.partId}.pdf`
            fetch(sources)
                .then(response => {
                    if (response.ok) {
                        $("#arkBtn").prop('disabled', false);
                    } else {
                        $("#arkBtn").prop('disabled', true);
                    }
                })
                .catch(error => {
                    console.error('Error:', error); // Network error or other issues
                });
        }

        const findDataIndexById = (data, id) => {
            return data.findIndex(item => item.partId === id);
        };

        const changeDrawingType = (type) => {
            drawingType = type;
            fetchDataFromServer("controllers/ajax/eric_getData.php", drawingType).then((data) => {
                $("#nxt, #prv").show();
                modalData = data;
                // console.log(modalData)
                let currentDataIndex = findDataIndexById(data, partId)

                hasDrawing(modalData[currentDataIndex])

                const showNextData = () => {
                    if (currentDataIndex < modalData.length - 1) {
                        currentDataIndex = currentDataIndex + 1;
                        partId = modalData[currentDataIndex]; // Update partId
                        displayDataInModal(modalData[currentDataIndex], '');
                        // console.log(modalData[currentDataIndex]);

                    } else {
                        displayDataInModal(modalData[currentDataIndex], '');
                    }
                    highlightRow(modalData[currentDataIndex]);
                    hasDrawing(modalData[currentDataIndex])
                };

                const showPrevData = () => {
                    if (currentDataIndex > 0) {
                        currentDataIndex = currentDataIndex - 1;
                        partId = modalData[currentDataIndex]; // update partId
                        displayDataInModal(modalData[currentDataIndex], '');

                    } else {
                        displayDataInModal(modalData[currentDataIndex], '');
                    }
                    highlightRow(modalData[currentDataIndex]);
                    hasDrawing(modalData[currentDataIndex])
                };

                if (type == "cus") {
                    data = modalData[currentDataIndex]
                    console.log(data);
                    let sources = `/Document Management System/Arktech Folder/ARK_${data.partId}.pdf`
                    fetch(sources)
                        .then(response => {
                            if (response.ok) {
                                $("#arkBtn").prop('disabled', false);
                            } else {
                                $("#arkBtn").prop('disabled', true);
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error); // Network error or other issues
                        });
                }

                const changeTypeArk = () => {
                    displayDataInModal(modalData[currentDataIndex], '0');
                    // console.log(modalData[currentDataIndex])
                    $("#arkBtn").addClass("w3-orange").removeClass("w3-green");
                    $("#cusBtn").removeClass("w3-orange").addClass("w3-green");
                }

                const changeTypeCus = () => {
                    displayDataInModal(modalData[currentDataIndex], '1');
                    $("#cusBtn").addClass("w3-orange").removeClass("w3-green");
                    $("#arkBtn").removeClass("w3-orange").addClass("w3-green");

                    // console.log(modalData[currentDataIndex])
                }

                $(document).off('click', '#nxt', showNextData);
                $(document).off('click', '#prv', showPrevData);

                $(document).on('click', '#nxt', showNextData);
                $(document).on('click', '#prv', showPrevData);

                $(document).on('click', '#arkBtn', changeTypeArk);
                $(document).on('click', '#cusBtn', changeTypeCus);

            }).catch((error) => {
                console.error("Error fetching data:", Error);
            });
        }

        changeDrawingType(drawingType);

    });
</script>