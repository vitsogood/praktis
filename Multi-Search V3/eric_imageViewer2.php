<?php
// session_start();
include $_SERVER['DOCUMENT_ROOT'] . "/version.php";
$path = $_SERVER['DOCUMENT_ROOT'] . "/" . v . "/Common Data/";
set_include_path($path);
include('PHP Modules/mysqliConnection.php');
include('PHP Modules/anthony_retrieveText.php');
include('PHP Modules/gerald_functions.php');

$inventoryId = isset($_POST['inventoryId']) ? $_POST['inventoryId'] : "CPAR-INT-23-09-071";
$getCPARResultArray = [];

$extensions = ['jpg', 'jpeg', 'pdf', 'png'];
$pattern = "/^{$inventoryId}(_\\d+)?\.(pdf|jpg|png|jpeg)$/i";
foreach ($extensions as $extension) {
    $files = glob($_SERVER['DOCUMENT_ROOT'] . "/Document Management System/Inventory Folder/*.$extension");
    foreach ($files as $file) {
        if (preg_match($pattern, basename($file))) {
            list(, $filePath) = explode("html", $file);
            $getCPARResultArray[] = $filePath;
        }
    }
}

$data = json_encode($getCPARResultArray);
$filesDirectory = $getCPARResultArray[0];
$ext = explode(".", $rootPath);

# For cache
function generateRandomString($length)
{
    $charset = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
    $randomString = '';

    for ($i = 0; $i < $length; $i++) {
        $randomString .= $charset[rand(0, strlen($charset) - 1)];
    }

    return $randomString;
}

$rand = generateRandomString(10);
// echo $rand;
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<style>
    .papalitan {
        /* position: relative; */
        top: 0;
        left: 0;
        display: flex;
        justify-content: center;
        align-items: center;
        /* position: relative; */
        overflow: hidden;
        width: 100%;
        /* Adjust the width and height as needed */
        height: 500px;

    }

    .imageToChange {
        width: 100%;
        height: 100%;
        cursor: move;
        transition: transform 0.3s ease;
    }

    #edit a {
        text-decoration: none;
        color: white;
    }
</style>

<body>
    <div class="container-fluid">
        <?php
        echo "<div class='row w3-padding-top'>";
        echo "<div class='col-md-12'>";
        echo "<div class='w3-left'>";
        // echo $downloadBtn;
        echo "</div>";
        echo "<div class='w3-left '>";
        echo "<label >FILE NAME: &nbsp;&nbsp;</label><label id='currentFile'>" . $filesDirectory . "</label>";
        // echo "<img src='".$filesDirectory."'>";
        echo "</div>";
        echo "<div class='w3-right'>";
        echo "<a id='editBtn' href='miniPaint-master/miniPaint-master/examples/open-edit-save.php?path=$filesDirectory&st=$rand'><button id='edit' class='w3-btn w3-round w3-red'  data-details='0'><b> ". displayText('L243', 'utf8', 0, 0, 1) ."</b></button></a>&nbsp;";
        echo "<button id='zoomIn' class='w3-btn w3-round w3-green'  data-details='0'><b> ". displayText('L10009', 'utf8', 0, 0, 1) ."</b></button>&nbsp;";
        echo "<button id='zoomOut'  class='w3-btn w3-round w3-amber'  data-details='0'><b> ". displayText('L10010', 'utf8', 0, 0, 1) ."</b></button>&nbsp&nbsp | &nbsp&nbsp";
        echo "<button id='prv' class='w3-btn w3-round w3-indigo'  data-details='0'><b><i class='fa fa-chevron-left' aria-hidden='true'></i>  ". displayText('L3491', 'utf8', 0, 0, 1) ."</b></button>&nbsp;";
        echo "<button id='nxt'  class='w3-btn w3-round w3-indigo'  data-details='0'><b><i class='fa fa-chevron-right' aria-hidden='true'></i>  ". displayText('L4762', 'utf8', 0, 0, 1) ."</b></button>";
        echo "</div>";
        echo "</div>";
        echo "</div>";
        echo "<hr>";
        ?>
        <div class="row">
            <div class="col-12 ">
                <?php
                if (in_array($ext[1], array('jpeg', 'jpg', 'png'))) {
                    echo "<div class='papalitan'>";
                    echo "<div class='col-12 justify-content-center align-items-center text-center'><img src='" . $filesDirectory . "' alt='Zoomable Image' class='imageToChange'></div>";
                    echo "</div>";
                } else {
                    echo "<div class='papalitan'>";
                    echo "<div class='col-12 justify-content-center align-items-center text-center papalitan'><iframe style = 'width: 100%; height: 730px; border:0;' src='" . $filesDirectory . "' allowfullscreen></iframe></div>";
                    echo "</div>";
                }

                ?>
            </div>
        </div>

    </div>
</body>

</html>

<script>
    (function() {
        let paths = <?php echo $data; ?>;
        let currentIndex = 0;
        let currentScale = 1; // Current Scale
        const minScale = 0.5; // Minimum zoom scale

        // console.log(paths);

        var path = "<?php echo $filesDirectory ?>;"
        let rand = "<?php echo $rand ?>;"

        const updateView = (data, index) => {
            currentScale = 1;
            const extension = data[index].split(".");
            $(".papalitan").empty();
            if (extension[1] == 'pdf') {
                $('#currentFile').text(data[index]);
                $(".papalitan").append(`<iframe style="width: 100%; height: 730px; border:0;" src="${data[index]}" allowfullscreen></iframe>`);
            } else {
                const $newImage = $(`<div class='col-12 justify-content-center align-items-center text-center'><img src='${data[index]}' alt='Zoomable Image' class='imageToChange'></div>`);
                $newImage.draggable();
                $newImage.css("transform-origin", "center center"); // Set the transform origin to the center
                $newImage.css("transform", `scale(${currentScale})`);
                $(".papalitan").append($newImage);
                $('#currentFile').text(data[index]);
                $("#editBtn").attr("href", `miniPaint-master/miniPaint-master/examples/open-edit-save.php?path=${data[index]}&st=${rand}`);
            }
            console.log(data[index]);
            console.log(data[index], rand);
        }

        $('#nxt').click(() => {
            // Increment currentIndex
            currentIndex += 1;
            if (currentIndex >= paths.length) {
                // Wrap around to the first element
                currentIndex = 0;
            }
            updateView(paths, currentIndex);
        });

        $('#prv').click(() => {
            // Decrement currentIndex
            currentIndex -= 1;
            if (currentIndex < 0) {
                // Wrap around to the last element
                currentIndex = paths.length - 1;
            }
            updateView(paths, currentIndex);
        });

        $('#zoomIn').click(() => {
            currentScale += 0.4;
            $(".imageToChange").css("transform", `scale(${currentScale})`);
        });

        $('#zoomOut').click(() => {
            currentScale -= 0.1;
            if (currentScale < minScale) {
                currentScale = minScale; // Limit zoom out
            }
            $(".imageToChange").css("transform", `scale(${currentScale})`);
        });
  
        $(".papalitan").on("wheel", (event) => {
            event.preventDefault();

            if (event.originalEvent.deltaY < 0) {
                // Zoom in
                currentScale += 0.1;
            } else {
                // Zoom out
                currentScale -= 0.1;
                if (currentScale < minScale) {
                    currentScale = minScale;
                }
            }

            $(".imageToChange").css("transform", `scale(${currentScale})`);
        });

        updateView(paths, currentIndex);
    })();
</script>