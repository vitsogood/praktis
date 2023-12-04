<?php
include $_SERVER['DOCUMENT_ROOT'] . "/version.php";
$path = $_SERVER['DOCUMENT_ROOT'] . "/" . v . "/Common Data/";
set_include_path($path);
include('PHP Modules/mysqliConnection.php');
include('PHP Modules/gerald_functions.php');
// for testing
// include('Templates/mysqliConnection.php');
// include('PHP Modules/gerald_functionDemo.php');
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
    <link rel="stylesheet" href="assets/css/style1.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css"
        integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="path/to/font-awesome/css/font-awesome.min.css">



    <?php

        $ipAddress = $_SERVER['REMOTE_ADDR'];
        // 192.168.254.35 WAN PH
        // 192.168.254.75 LAN PH
        if (strpos($ipAddress, "192.168.254.") === 0) 
        {
            $ipAddress =  "192.168.254.163";
        }
        else
        {
            $ipAddress = "203.177.14.250";
        }

        $searchValue = isset($_POST['searchValue']) ? $_POST['searchValue'] : $_GET['searchValue'];

        $materialTotal = 0;

        if (isset($_GET['country'])) {
            $country = $_GET['country'];

            $styleClass = ($country == 1) ? 'font-12px' : '';
        } else {
            echo "Country parameter is not set.";
        }


        //material
        $sql = "SELECT COUNT(*) AS count FROM warehouse_inventory WHERE type = '1'";
        $result = mysqli_query($db, $sql);

        if ($result) {
            $row = mysqli_fetch_assoc($result);
            $materialTotal = $row['count'];
        } else {
            echo 'Error: ' . mysqli_error($db);
        }

        //supply
        $sql = "SELECT COUNT(*) AS count FROM warehouse_inventory WHERE type = '3'";
        $result = mysqli_query($db, $sql);

        if ($result) {
            $row = mysqli_fetch_assoc($result);
            $supplyTotal = $row['count'];
        } else {
            echo 'Error: ' . mysqli_error($db);
        }

        //accessory
        $sql = "SELECT COUNT(*) AS count FROM warehouse_inventory WHERE type = '4'";
        $result = mysqli_query($db, $sql);

        if ($result) {
            $row = mysqli_fetch_assoc($result);
            $accTotal = $row['count'];
        } else {
            echo 'Error: ' . mysqli_error($db);
        }

        //fg
        $sql = "SELECT COUNT(*) AS count FROM warehouse_inventory WHERE type = '5'";
        $result = mysqli_query($db, $sql);

        if ($result) {
            $row = mysqli_fetch_assoc($result);
            $fgTotal = $row['count'];
        } else {
            echo 'Error: ' . mysqli_error($db);
        }

        //drawing
        $sql = "SELECT COUNT(*) AS count FROM cadcam_parts";
        $result = mysqli_query($db, $sql);

        if ($result) {
            $row = mysqli_fetch_assoc($result);
            $drawTotal = $row['count'];
        } else {
            echo 'Error: ' . mysqli_error($db);
        }        

    ?>



</head>
<?php
        $sql = "SELECT * FROM system_msParameter";
        $result = mysqli_query($db, $sql);     

        while ($data = mysqli_fetch_assoc($result)) {
            $element = $data['element'];
            $selector = $data['selector'];    

            echo "<style>";
            echo "$selector {";
            
            $fontProperty = $data['cssProperty'] == 0 ? 'font-size' : 'height';
            echo "    $fontProperty: " . $data['selector'] . " !important;";
            

            echo "}";
        
        	// pc view 
        	echo "@media (min-width: 102px) {";
			echo "    $selector {";
			echo "        $fontProperty: " . $data['pc1'] . " !important;";
			echo "    }";
			echo "}";

        	// ipad portrait
            echo "@media (min-width: 600px) and (max-width: 768px) and (min-height: 800px) and (max-height: 1020px) {";
            echo "    $selector {";
            echo "        $fontProperty: " . $data['ipadPortrait'] . " !important;";
            echo "    }";
            echo "}";

			// phone landscape         	
            echo "@media (min-width: 660px) and (max-width: 850px) and (min-height: 280px) and (max-height: 380px)  {";
            echo "    $selector {";
            echo "        $fontProperty: " . $data['phoneLandscape'] . " !important;";
            echo "    }";
            echo "}";

            // phone portrait
            echo "@media (min-width:200px) and (max-width: 585px) {";
            echo "    $selector {";
            echo "        $fontProperty: " . $data['phonePortrait'] . " !important;";
            echo "    }";
            echo "}";

            // ipad landscape
            echo "@media (min-width:1000px) and (max-width: 1024px) {";
            echo "    $selector {";
            echo "        $fontProperty: " . $data['ipadLandscape'] . " !important;";
            echo "    }";
            echo "}";
        }

        echo "</style>";

        
        if (mysqli_num_rows($result) === 0) {
            echo "No data found.";
        }

    ?>

<style>
body {
    justify-content: center;
    align-items: center;
}

#mobileNav {
    display: none;
}

#searchBtn {
    background-color: #00abd8;
    align-items: center !important;
    justify-content: center;
    padding-right: 3.5%;
    border-radius: 16px;
    color: white;
    width: 10% !important;

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

#searchForm {
    padding: 4px 4px 4px 4px;
    /* padding: 0; */
}

#nav {
    margin-top: 2rem !important;
}

/* ---------------------------- */
/* Loading background color */
.loading-bg {
    background-color: red;
}

.default-bg {
    background-color: #ffffff;
}

#lcl,
#cld {
    color: #33691e;
    background-color: white;
    border-radius: .5rem;
    padding: 0 10px 0 10px;
}

.grayed-out {
    opacity: 0.7;
    pointer-events: none;
}

.grayed-out-button {
    background-color: #99A3A4 !important;
    /* Grayed-out background color */
    color: #ccc !important;
    /* Grayed-out text color */
    pointer-events: none !important;
    /* Disable pointer events on grayed-out buttons */
}

#headerText {
    font-size: 4rem;
}

.homeBtnIcon {
    margin-top: .7rem !important;
}

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

#keypadButton,
#reset,
#switch {
    padding: 24px 8px 10px 8px;
    background-color: #3452b4;
    color: white;
    border-radius: 16px;
    border: none;
    margin-bottom: 0;
}

.btnLabel {
    /* font-size: 16px; */
}

.reset {
    background-color: #4caf50 !important;
}

#container {
    margin: 100px auto;
    width: 550px;
    height: 355px;
    border-radius: 4px;
    background-color: #a2abba !important;
    padding: 16px;
}

#keyboard {
    margin: 0;
    list-style: none;
    width: 280px;
    height: 0;
    padding: 0 !important;
    background-color: transparent;
}

#keyboard ul {
    border: 3px solid #fff;
}

#keyboard li {
    float: left;
    margin: 0 0px 0px 0;
    width: 80px;
    height: 80px;
    font-size: 16px;
    line-height: 80px;
    text-align: center;
    background: #fff;
    border: 1px solid black;
}

#tip,
.gap {
    height: 40px !important;
    width: 220px !important;
    background-color: transparent !important;
    border: none !important;
}

#enter {
    width: 160px !important;
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
    color: black;
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
    /* height: 10px; */
    /* Adjust the height as needed */
    /* width: 300px !important; */
    /* background-color: #a2abba !important; */
    /* border: none !important; */
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
    color: #fff;
    border: none;
    padding: 8px 16px;
    border-radius: 16px;
    display: none;
}

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

/* loader */
.lds-ripple,
#loading-spinner-cloud {
    display: flex;
    justify-content: center;
    align-items: center;
    position: absolute;
    position: relative;
    width: 80px;
    height: 80px;
    margin-left: 45% !important;
    top: 35px !important;

}

#loading-spinner-cloud div {
    border: 4px solid #F5B041;
}

.lds-ripple div {
    position: absolute;
    border: 4px solid #3452b4;
    opacity: 1;
    border-radius: 50%;
    animation: lds-ripple 1s cubic-bezier(0, 0.2, 0.8, 1) infinite;
}

.lds-ripple div:nth-child(2) {
    animation-delay: -0.5s;
}

@keyframes lds-ripple {
    0% {
        top: 36px;
        left: 36px;
        width: 0;
        height: 0;
        opacity: 0;
    }

    4.9% {
        top: 36px;
        left: 36px;
        width: 0;
        height: 0;
        opacity: 0;
    }

    5% {
        top: 36px;
        left: 36px;
        width: 0;
        height: 0;
        opacity: 1;
    }

    100% {
        top: 0px;
        left: 0px;
        width: 72px;
        height: 72px;
        opacity: 0;
    }
}

/* mobile phone portrait view */
@media (min-width:200px) and (max-width: 585px) {
    body {
        overflow: hidden !important;
    }

    .container {
        /* background-color: pink; */
        width: 350px !important;
        padding: 8px;
    }

    .btnLabel {
        font-size: 12px !important;
        display: block;
    }

    #headerText {
        font-size: 2rem !important;
    }

    #mobileNav {
        display: block;
        margin-left: 1.7rem;
        margin-top: 0rem !important;
    }

    #container-category {
        /* margin-top: 100px !important; */
    }

    #searchForm {
        width: 99% !important;
        margin-bottom: 8px;
        margin-left: 0px;
        margin-top: -150px;
        height: 64px;
    }

    #keypadButton,
    #reset,
    #switch {
        height: 100% !important;
        margin-top: 12px !important;
        padding-top: 0px;
    }

    #mobileNav #pad,
    #mobileNav #reset,
    #mobileNav #switch {
        margin-left: 6px;
        margin-top: 0px;
    }

    #mobileNav #pad,
    #mobileNav #resetDiv,
    #mobileNav #switchDiv {
        margin-top: 70px;
        align-items: center;
    }

    #keypadButton i,
    #reset i,
    #switch i {
        font-size: 24px;
    }

    #keypadButton span,
    #reset span,
    #switch span {
        margin-top: 0 !important;
    }

    .column {
        width: 108px;
        height: 70px;
        border: 1px solid #ccc;
        /* border: none; */
        margin: 0 -2px 20px 5px;
        align-items: center;
        border-radius: 8px;
        padding-top: -1rem;
    }

    .column .mblKey {
        background-color: #3452b4;
    }

    .navigation {
        display: flex;
    }

    #searchInput {
        flex-grow: 12;
    }

    .local1 {
        display: flex;
        flex-wrap: wrap;
        gap: 2px;
        align-items: flex-start;
    }

    .col-sm-4 {
        width: 50%;
    }

    /* keypad */
    #container {
        margin: 100px auto;
        width: 230px;
        height: 570px;
        border-radius: 4px;
        background-color: #a2abba;
        padding: 16px;
        overflow: hidden;

    }

    #container .row .first {
        margin-top: 20px;
    }

    #container .row .second {
        margin-top: 30px;
    }

    #keyboard li {
        float: left;
        margin: 0;
        /* padding: 10px 10px 10px 10px; */
        width: 65px;
        height: 65px;
        font-size: 16px;
        /* line-height: 80px; */
        text-align: center;
        background: #fff;
        border: 1px solid black;
    }

    #enter {
        width: 130px !important;
    }

    #closeKeypad {
        top: -3.2rem !important;
        padding: 3px;
        width: 10px;
        height: 10px;
        right: -20px;
    }

    /* end of keypad */
    #searchBtn i {
        font-size: 8px;
        margin-right: 1px !important;
    }

    #searchBtn:active i {
        margin-right: 4px !important;

    }

    #searchBtn {
        height: 100% !important;
        background-color: #00abd8;
        align-items: center !important;
        text-align: center !important;
        bottom: 60% !important;

        border-radius: 16px 0 0 16px;
        color: white;
        width: 10% !important;
        padding-right: 4.2%;
        width: 15% !important;
    }

    #searchBtn:active {
        padding-right: 5.2%;
    }

    /* loader */
    .lds-ripple,
    #loading-spinner,
    #loading-spinner-cloud {
        margin-left: 41% !important;
    }

    .categoryBtn {
        padding-top: -2rem !important;
        height: 48px !important;

    }

    span {
        margin-top: -4rem !important;
        font-size: 12px !important;
    }

    p {
        margin-top: 0 !important;
    }

    #switchDiv {
        background-color: #a8c545 !important;
    }
}

/* phone landscape */
@media (min-width: 660px) and (max-width: 850px) and (min-height: 280px) and (max-height: 380px) {
    body {
        /* background-color: pink !important; */
    }

    .container {
        width: 100vw !important;
    }

    #nav {
        /* for PH only, due to bigger header (not working)*/
        margin-top: 3rem !important;
    }

    #pad,
    #resetDiv,
    #switchDiv {
        height: 0px !important;
        background-color: transparent !important;
        align-items: center;

    }

    .miniView {
        /* for removing the gap at the top */
        /* margin-bottom: -3.5rem !important;  */
    }

    #searchBtn i {
        font-size: 12px;
        margin-right: 1px !important;
    }

    #searchBtn:active i {
        margin-right: 4px !important;
    }

    .btnLabel {
        font-size: 12px !important;
        display: block;
        margin-bottom: 0px;
        width: 90%;
        margin-left: 4px;
        height: 14px;
    }

    #keypadButton i,
    #reset i,
    #switch i {
        font-size: 12px;
        margin-top: -3;
    }

    #keypadButton span,
    #reset span,
    #switch span {
        margin-top: 0 !important;
        font-size: 12px;
    }

    #keypadButton,
    #reset,
    #switch {
        border-radius: 8px;
        padding-top: 4px !important;
        height: 40px;
        padding-top: -1;
    }

    /* keypad */
    #container {
        margin: 100px auto;
        width: 380px;
        height: 270px;
        border-radius: 4px;
        background-color: #a2abba;
        padding: 16px;

    }

    #keyboard li {
        float: left;
        margin: 0 0px 0px 0;
        width: 55px;
        height: 55px;
        font-size: 16px;
        line-height: 80px;
        text-align: center;
        background: #fff;
        border: 1px solid black;
    }

    #enter {
        width: 110px !important;
    }

    .categoryBtn {
        padding-top: -2rem !important;
        height: 48px !important;

    }

    .categoryBtn p {}

    span {
        margin-top: -4rem !important;
        font-size: 12px !important;
    }

    p {
        margin-top: 0 !important;
    }

    #pad,
    #resetDiv,
    #switchDiv {
        height: 0px !important;
        background-color: transparent !important;

    }

}

/* teclast tablet portrait */
@media (min-width: 712px) and (max-width: 720px) and (min-height: 850px) and (max-height: 980px) {
    .navigation {
        margin-left: -100px !important;
    }

    #searchForm {
        width: 33rem !important;
    }

    .btnLabel {
        font-size: 12px;
    }

    /* keypad */
    #container {
        margin: 100px auto !important;
        width: 230px !important;
        height: 570px !important;
        border-radius: 4px !important;
        background-color: #a2abba;
        padding: 16px;
        overflow: hidden;

    }

    #container .row .first {
        margin-top: 20px;
    }

    #container .row .second {
        margin-top: 30px;
    }

    #keyboard li {
        float: left;
        margin: 0;
        /* padding: 10px 10px 10px 10px; */
        width: 65px;
        height: 65px;
        font-size: 16px;
        /* line-height: 80px; */
        text-align: center;
        background: #fff;
        border: 1px solid black;
    }

    #enter {
        width: 130px !important;
    }

    #closeKeypad {
        top: -3.2rem !important;
        padding: 3px;
        width: 10px;
        height: 10px;
        right: -20px;
    }

    /* end of keypad */
}

/* TAB 8 Tablet portrait */
@media (min-width: 500px) and (max-width: 700px) and (min-height: 795px) and (max-height: 800px) {
    .navigation {
        margin-left: -120px !important;
    }

    #searchForm {
        width: 33rem !important;
    }

    .btnLabel {
        font-size: 12px;
    }

    /* keypad */
    #container {
        margin: 100px auto !important;
        width: 230px !important;
        height: 570px !important;
        border-radius: 4px !important;
        background-color: #a2abba;
        padding: 16px;
        overflow: hidden;

    }

    #container .row .first {
        margin-top: 20px;
    }

    #container .row .second {
        margin-top: 30px;
    }

    #keyboard li {
        float: left;
        margin: 0;
        /* padding: 10px 10px 10px 10px; */
        width: 65px;
        height: 65px;
        font-size: 16px;
        /* line-height: 80px; */
        text-align: center;
        background: #fff;
        border: 1px solid black;
    }

    #enter {
        width: 130px !important;
    }

    #closeKeypad {
        top: -3.2rem !important;
        padding: 3px;
        width: 10px;
        height: 10px;
        right: -20px;
    }

    /* end of keypad */
}



/* ipad portrait */
@media (min-width: 500px) and (max-width: 768px) and (min-height: 795px) and (max-height: 1020px) {
    body {
        overflow-y: hidden !important;
        position: fixed;
        width: 100%;
        height: 100%;
        /* background-color: pink !important; */
    }

    #container-category {
        width: 100vw !important;
    }

    #nav.row {
        width: 100% !important;
    }

    #nav {
        /* for PH only, due to bigger header (not working)*/
        margin-top: 6rem !important;
    }

    .categoryBtn {
        height: 8rem !important;
        line-height: 4rem;
        font-size: 2rem !important;
        padding-top: 1rem !important;
    }

    .categoryBtn p {
        margin-top: -2rem !important;
    }

    #resetDiv,
    #switchDiv {
        margin-left: -1rem !important;
    }

    #searchForm {
        margin-left: -1rem !important;
        width: 36rem;
    }

    #searchBtn {
        padding: 7% 2% 0 0 !important;
        width: 5rem !important;
    }

    #keypadButton,
    #switch,
    #reset {
        padding: 8px 8px 0px 8px;
        background-color: #3452b4;
        color: white !important;
        border-radius: 16px !important;
        border: none !important;
        margin-bottom: 0 !important;
        height: 80px !important;
        padding-bottom: 0 !important;
        align-items: center !important;
    }

    .btnLabel {
        font-size: 16px;
        display: block;
        margin-top: 0 !important;
        width: 100% !important;
    }

    #lcl,
    #cld {
        margin: 5px !important;
    }

    #searchBtn i {
        margin-right: 2px !important;
        margin-bottom: 3em !important;
    }

    #searchBtn:active i {
        margin-right: 5px !important;
        margin-bottom: 3em !important;
    }

    #pad,
    #resetDiv,
    #switchDiv {
        height: 0px !important;
        background-color: transparent !important;

    }
}

/*ipad landscape*/
@media (min-width:1000px) and (max-width: 1139px) {
    body {
        overflow-y: hidden !important;
        position: fixed;
        width: 100%;
        height: 100%;
    }

    #nav {
        /* for PH only, due to bigger header (not working)*/
        margin-top: 4rem !important;
    }

    #keypadButton,
    #switch,
    #reset {
        max-width: 80%;
    }

    #switch {
        margin-left: -3rem;
    }

    #reset {
        margin-left: -6rem;
    }

    #searchForm {
        margin-left: -9rem;
    }

    /* test */
    #search {
        margin-top: .3rem !important;
        /* height: 10rem !important; */

    }

    .categoryBtn {
        height: 7rem !important;
        line-height: 4rem;
        font-size: 2rem !important;
        padding-top: 1rem !important;
    }

    .categoryBtn p {
        margin-top: -2rem !important;
    }

    .lds-ripple,
    #loading-spinner {
        height: 175px !important;
    }

    #searchBtn {
        padding-right: 4.2%;
        width: 15% !important;
    }

    #searchBtn:active {
        padding-right: 4.7%;
    }
}

@media screen and (min-width: 1025px) {
    .col-sm-4 .categoryBtn {
        height: 10rem !important;
    }

    #pad,
    #resetDiv,
    #switchDiv {
        background-color: transparent !important;
    }
}

/*sir ace, bagong laptops*/
@media screen and (min-width: 1600px) {
    p {
        font-size: 1rem !important;
        margin-bottom: 2rem !important;
    }

    .categoryBtn {
        height: 8rem !important;
        line-height: 4rem;
        padding-bottom: 5rem;
        font-size: 2.5rem;
    }

    #keypadButton,
    #switch,
    #reset {
        max-width: 60%;
    }

    #switch {
        margin-left: -8rem;
    }

    #reset {
        margin-left: -16rem;
    }

    #searchForm {
        margin-left: -23rem;
    }

    .btn3d {
        padding-bottom: 4rem !important;
    }
}

/*sir ace, bagong laptops*/
@media screen and (min-width: 1366px) {
    p {
        font-size: 3rem !important;
    }

    .categoryBtn {
        height: 7rem;
        line-height: 4rem;
        font-size: 3rem;
    }

    #keypadButton,
    #switch,
    #reset {
        max-width: 60%;
    }

    #switch {
        margin-left: -8rem;
    }

    #reset {
        margin-left: -16rem;
    }

    #searchForm {
        margin-left: -23rem;
    }
}
</style>

<body>

    <div class="container" id="nav" style="">
        <div class="row navigation">
            <div class="col-sm-2" id="pad">
                <button class="<?php echo $styleClass; ?>" id="keypadButton" style="width: 100% !important; ">
                    <i class="fa fa-calculator icon w3-xlarge"></i><br>
                    <span class="btnLabel"><?php echo displayText('L4800', 'utf8', 0, 0, 1); // Keypad ?></span>
                </button>
            </div>
            <div class="col-sm-2" id="switchDiv">
                <button class="" id="switch" style="width: 100% !important; background-color: #a8c545;">
                    <i style="margin-bottom: 4px" class="fa fa-repeat icon w3-xlarge"></i><br>
                    <span class="btnLabel" id="lcl">LCL/CLD</span>
                    <span class="btnLabel" id="cld" style="display:none;">LCL</span>
                </button>
            </div>
            <div class="col-sm-2" id="resetDiv">
                <button class="w3-teal" id="reset" style="width: 100% !important;">
                    <i class="fa fa-rotate-left icon w3-xlarge"></i><br>
                    <span class="btnLabel"><?php echo displayText('L1337', 'utf8', 0, 0, 1); // reset ?></span>
                </button>
            </div>
            <div class="col-sm-6" id="searchInput" style="">
                <div id="searchForm">
                    <button class='' type="submit" id="searchBtn" style=""> <i class="fas fa-search"></i> </button>
                    <input style="text-transform: uppercase; outline: none;" type="search" name="search" id="search"
                        class="search" oninput=""
                        placeholder="<?php echo displayText('B5', 'utf8', 0, 0, 1); // search ?>"
                        value="<?php echo isset($_GET['search']) ? strtoupper($_GET['search']) : $searchValue; ?>">
                </div>
            </div>
        </div>
    </div>

    <div class="container category" id="container-category">

        <!-- local -->
        <!-- <p style="font-size: 12px !important;" id="sizeInfo"></p> -->
        <!-- <p style="font-size: 12px !important;" id="screenInfo"></p> -->

        <div class="local" id="local">
            <!-- material, supply, accessory -->
            <div class="row" id="local1">
                <div class="col-sm-4">
                    <button class="categoryBtn btn btn-primary btn-lg btn3d" id='material' value="material">
                        <?php echo ($country == 1) ? displayText('L4707', 'utf8', 0, 0, 1) : displayText('L174', 'utf8', 0, 0, 1); ?>
                        <p style=""><span
                                value="material"><?php echo ("(" . $materialCount . ")" ? "(" . $materialTotal . ")" : ''); ?></span>
                        </p>
                    </button>
                </div>
                <div class="col-sm-4">
                    <button class="categoryBtn btn btn-primary btn-lg btn3d" id="supply"
                        style="color: #07594f !important;border: 1px solid #7fccc3 !important;" role="button"
                        value="supply">
                        <?php echo displayText('L1356', 'utf8', 0, 0, 1); // supplies ?>
                        <p style=""><span
                                value="supply"><?php echo ("(" . $supplyCount . ")" ? "(" . $supplyTotal . ")" : ''); ?></span>
                        </p>
                    </button>
                </div>
                <div class="col-sm-4">
                    <button class="categoryBtn btn btn-primary btn-lg btn3d" id="accessory" role="button"
                        value="accessory">
                        <?php echo displayText('L471', 'utf8', 0, 0, 1); // acc ?>
                        <p style=""><span value="accessory">
                                <?php echo ("(" . $accessoryCount . ")" ? "(" . $accTotal . ")" : ''); ?></span></p>
                    </button>
                </div>
                <!-- local2 moved here 10/18/23 jera-->
                <div class="col-sm-4">
                    <button class="categoryBtn btn btn-primary btn-lg btn3d" id="fg" role="button" value="fg">
                        <?php echo ($country == 1) ? displayText('L4710', 'utf8', 0, 0, 1) : displayText('L4676', 'utf8', 0, 0, 1); ?>
                        <p style=""><span
                                value="fg"><?php echo ("(" . $finishGoodsCount . ")" ? "(" . $fgTotal . ")" : ''); ?></span>
                        </p>
                    </button>
                </div>
                <div class="col-sm-4">
                    <button class="categoryBtn btn btn-primary btn-lg btn3d" id="draw" role="button" value='drawing'>
                        <?php echo displayText('L77', 'utf8', 0, 0, 1); // drawing ?>
                        <p style=""><span
                                value="drawing"><?php echo ("(" . $drawingCount . ")" ? "(" . $drawTotal . ")" : ''); ?></span>
                        </p>
                    </button>
                </div>
                <div class="col-sm-4">
                    <button class="categoryBtn btn btn-primary btn-lg btn3d" id="barcode" role="button" value='barcode'
                        disabled>
                        <?php echo displayText('L4750', 'utf8', 0, 0, 1); // barcode ?>
                        <p style=""><span
                                value="barcode"><?php echo ("(" . $drawingCount . ")" ? $drawingCount : ''); ?></span>
                        </p>
                    </button>
                </div>
            </div>

            <div class="lds-ripple" id="loading-spinner" style="display: none; z-index: 5; height: 200px">
                <div></div>
                <div></div>
            </div>


        </div>

        <!-- cloud -->
        <div class="cloud">
            <!-- video, audio, pdf  -->
            <div class="row" id="cloud1">
                <div class="col-sm-4">
                    <button class="categoryBtn cloudBtn btn btn-primary btn-lg btn3d" id="video" role="button"
                        value='video'>
                        <?php echo displayText('L4445', 'utf8', 0, 0, 1); // video ?>
                        <p style=""><span
                                value='video'><?php echo ("(" . $drawingCount . ")" ? $drawingCount : ''); ?></span></p>
                    </button>
                </div>
                <div class="col-sm-4">
                    <button class="categoryBtn cloudBtn btn btn-primary btn-lg btn3d" id="audio" role="button"
                        value='audio'>
                        <?php echo displayText('L4444', 'utf8', 0, 0, 1); // audio ?>
                        <p style=""><span
                                value='audio'><?php echo ("(" . $drawingCount . ")" ? $drawingCount : ''); ?></span></p>
                    </button>
                </div>
                <div class="col-sm-4">
                    <button class="categoryBtn cloudBtn btn btn-primary btn-lg btn3d" id="pdf" role="button"
                        value='pdf'>
                        <?php echo displayText('L85', 'utf8', 0, 0, 1); // pdf ?>
                        <p style=""><span
                                value='pdf'><?php echo ("(" . $drawingCount . ")" ? $drawingCount : ''); ?></span></p>
                    </button>
                </div>
                <!-- cloud2 moved here 10/18/23 jera -->
                <div class="col-sm-4">
                    <button class="categoryBtn cloudBtn btn btn-primary btn-lg btn3d" id="image" role="button"
                        value="image">
                        <?php echo displayText('L4801', 'utf8', 0, 0, 1); // image ?>
                        <p style=""><span
                                value='image'><?php echo ("(" . $drawingCount . ")" ? $drawingCount : ''); ?></span></p>
                    </button>
                </div>
                <div class="col-sm-4">
                    <button class="categoryBtn cloudBtn btn btn-primary btn-lg btn3d" id="card" role="button"
                        value="cards">
                        <?php echo displayText('L4443', 'utf8', 0, 0, 1); // card ?>
                        <p style=""><span
                                value='cards'><?php echo ("(" . $drawingCount . ")" ? $drawingCount : ''); ?></span></p>
                    </button>
                </div>
            </div>

            <!-- loader -->
            <div class="lds-ripple" id="loading-spinner-cloud" style="display: none; z-index: 5; height: 200px">
                <div></div>
                <div></div>
            </div>


        </div>
    </div>


    <!-- 10/12/2023 -->
    <style>
    #closeKeypad {
        position: absolute;
        left: 0;
        bottom: -10px;
        padding: 10px;
        border: 1px solid #44464a;
        background-color: #d9d9d9;
        border-radius: 50px;
        color: white !important;
    }
    </style>

    <!-- keypad -->
    <div id="container" style="display:none" class="modal keyPad">
        <div class="row">
            <div class="col-md-6 first">
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
                    <li class="gap clearl" id="tip"></li>
                </ul>
                <button type="button" id="closeKeypad" class="btn-close" aria-label="Close"></button>
            </div>
            <div class="col-md-6 second">
                <ul id="keyboard">
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
        </div>
    </div>
    <!-- 10/12/2023 -->

</body>

</html>
<?php PMSResponsive::includeFooter(); ?>


<script>
$(document).ready(function() {

    var searchValue = '<?php echo $searchValue ?>';
    var ipAddress = "<?php echo $ipAddress ?>";
    const url = "eric_cloudV2.php";
    const originalButtonColors = {};
    const cloudUrl = `https://arktechph.com/V4/20%20Document%20Management%20System/eric_getCounts.php`;
    var material = <?php echo json_encode($materialTotal); ?>;
    var supply = <?php echo json_encode($supplyTotal); ?>;
    var acc = <?php echo json_encode($accTotal); ?>;
    var fg = <?php echo json_encode($fgTotal); ?>;
    var draw = <?php echo json_encode($drawTotal); ?>;

    //for keypad
    const keypadButton = document.getElementById("keypadButton");
    const keypadModal = document.getElementById("container");
    const input = document.getElementById("search");
    const tip = document.getElementById("tip");
    let tagCount = 0;

    // for modal
    var switchBtn = document.getElementById("switch");
    var lclText = document.getElementById("lcl");
    var cldText = document.getElementById("cld");
    var modal = document.getElementById("myModal");
    var closeBtn = document.querySelector(".close");
    var localSearchButton = document.getElementById("localSearch");
    var localCloudButton = document.getElementById("localCloud");
    var cloudDiv = document.querySelector(".cloud");
    var cloudBtns = document.querySelectorAll(".cloudBtn");
    let isSwitchOn = false;

    // buttons
    const switchDiv = document.getElementById('switchDiv');

    // close keypad
    if ((window.innerWidth >= 200 && window.innerWidth <= 585) || (window.innerWidth >= 660 && window
            .innerWidth <= 850 && window.innerHeight >= 284 && window.innerHeight <= 380)) {
        $("#closeKeypad").on("touchstart", function(e) {
            keypadModal.style.display = "none";

        });
    } else {
        $("#closeKeypad").click(function(e) {
            keypadModal.style.display = "none";

        });
    }

    // add, remove Id for phone view
    function updateContainerId() {
        const container = document.querySelector('.container');
        const windowWidth = window.innerWidth;
        const navigation = document.querySelector('.navigation');
        const searchForm = document.getElementById('searchForm');
        const pad = document.getElementById('pad');
        const resetDiv = document.getElementById('resetDiv');

        searchForm.addEventListener('click', function(event) {
            event.preventDefault();
        });

        if (window.innerWidth <= 585) {
            container.id = 'mobileNav';

            pad.classList.remove('col-sm-2');
            switchDiv.classList.remove('col-sm-2');
            resetDiv.classList.remove('col-sm-2');

            pad.classList.add('column');
            switchDiv.classList.add('column');
            resetDiv.classList.add('column');

            pad.style.backgroundColor = "#3452b4";
            switchDiv.style.backgroundColor = "#a8c545";
            resetDiv.style.backgroundColor = "#009688";

        } else {
            container.id = 'nav';

            pad.classList.add('col-sm-2');
            switchDiv.classList.add('col-sm-2');
            resetDiv.classList.add('col-sm-2');

            pad.classList.remove('column');
            switchDiv.classList.remove('column');
            resetDiv.classList.remove('column');
        }
    }

    // Initial check and attach the event listener
    updateContainerId();
    window.addEventListener('resize', updateContainerId);

    switchBtn.addEventListener("click", function() {

        isSwitchOn = !isSwitchOn;

        // Check the state to determine whether to apply or remove styles
        if (isSwitchOn) {
            lclText.style.display = "none";
            cldText.style.display = "block";
            cldText.style.backgroundColor = "#266e0a";
            cldText.style.color = "white";

            cloudDiv.classList.add("grayed-out");

            cloudBtns.forEach(function(button) {
                button.classList.add("grayed-out-button");
            });

        } else {

            lclText.style.display = "block";
            lclText.style.backgroundColor = "white";

            cldText.style.display = "none";

            cloudDiv.classList.remove("grayed-out");

            cloudBtns.forEach(function(button) {
                button.classList.remove("grayed-out-button");
            });
        }


    });

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
                };
            })
            .catch(error => {
                return {
                    success: false,
                    error: error.message
                }; // Return an error flag and the error message
            });
    }

    function fetchDataFromCloudOnLoad() {
        const searchValue = '<?php echo $searchValue ?>';

        fetchFromCloud(cloudUrl, searchValue).then((cloudResponse) => {
            if (cloudResponse.success) {
                let cloudData = cloudResponse.data;
                updateButtonLabels(cloudData);
            } else {
                console.error("Error fetching data from the cloud:", cloudResponse.error);
            }
        }).catch((error) => {
            console.error("Error fetching data from the cloud:", error);
        });
    }

    //reset button
    $("#reset").click(function() {
        // Clear the input field's value
        $("#search").val('');

        // Remove the grayed-out class from .cloud
        cloudDiv.classList.remove("grayed-out");
        cloudBtns.forEach(function(button) {
            button.classList.remove("grayed-out-button"); // Apply the class to each button
        });

        fetchDataFromServer(url, searchValue).then((data) => {
            updateButtonLabels(data);
            // console.log(data);
        }).catch((error) => {
            console.error("Error fetching data:", error);
        });
        // reloadContent()
        fetchDataFromCloudOnLoad();

    });

    // Call the function to fetch data from the cloud source on page load
    fetchDataFromCloudOnLoad();

    const updateButtonLabels = (data) => {
        for (let key in data) {
            if (data.hasOwnProperty(key)) {
                const label = data[key];
                const $button = $(`button[value="${key}"]`);

                if (!cloudDiv.classList.contains("grayed-out") || (cloudDiv.classList.contains(
                        "grayed-out") && key !== 'video' && key !== 'audio' && key !== 'pdf' && key !==
                        'image' && key !== 'cards')) {
                    // Only update the button if .cloud is not grayed out
                    $button.find('span').text(`(${label})`);

                    if (label === '0' || label === 0) {
                        $button.css('background-color', '#99A3A4');
                        $button.attr('disabled', true);
                    } else {
                        $button.css('background-color', '');
                        $button.attr('disabled', false);
                    }
                }
            }
        }
    }

    $(".categoryBtn").each(function() {
        const value = $(this).val();
        const originalColor = $(this).css('background-color');
        originalButtonColors[value] = originalColor;
    });

    const fetchDataFromServer = (url, searchValue) => {
        const postData = {
            "searchValue": searchValue,
        };
        return $.ajax({
            url: url,
            type: "POST", // Use POST request instead of GET
            dataType: "json",
            data: postData,
            searchValue: searchValue,
        });
    };


    function reloadContent() {
        fetch('eric_multiSearch.php') // Replace with the URL of the content you want to load
            .then(response => response.text())
            .then(data => {
                // Replace the content of the 'content' div with the fetched data
                document.getElementById('content').innerHTML = data;
            })
            .catch(error => {
                console.error('Error:', error);
            });
    }

    function handleSearch() {
        const searchValue = $("#search").val();
        if (searchValue !== '') {
            $("#loading-spinner").css("display", "block");
            $("#local1").hide();
            // $("#local2").css("display", "none");

            if (cloudDiv.classList.contains("grayed-out")) {
                $("#loading-spinner-cloud").css("display", "none");
            } else {
                $("#loading-spinner-cloud").css("display", "block");
                $("#cloud1").hide();
            }



            fetchDataFromServer(url, searchValue).then((serverData) => {
                if (serverData.barcode > 0) {
                    redirectToBarcodeSearch()
                }
                updateButtonLabels(serverData);
                $("#loading-spinner").css("display", "none");
                $("#local1").show();


                fetchFromCloud(cloudUrl, searchValue).then((cloudResponse) => {
                    if (cloudResponse.success) {
                        let cloudData = cloudResponse.data
                        updateButtonLabels(cloudData);
                        // console.log(cloudData)
                        $("#loading-spinner-cloud").css("display", "none");
                        $("#cloud1").show();
                    } else {
                        console.error("Error fetching data from the cloud:", cloudResponse
                            .error);
                    }
                }).catch((error) => {
                    console.error("Error fetching data from the cloud:", error);
                });



            }).catch((error) => {
                console.error("Error fetching data:", error);
            });
        }
    }


    //when ENTER from button and main search icon is clicked
    // $("#enter").on("touchstart", function(e) {
    //     handleSearch();
    // });

    if ((window.innerWidth >= 200 && window.innerWidth <= 585) || (window.innerWidth >= 660 && window
            .innerWidth <= 850 && window.innerHeight >= 284 && window.innerHeight <= 380)) {
        $("#enter").on("touchstart", function(e) {
            handleSearch();
        });
    } else {
        $("#enter").click(function(e) {
            handleSearch();
        });
    }


    //when main search icon is clicked
    $("#searchBtn").click(function() {
        handleSearch();
    });

    $('#search').on('keyup', function(event) {
        if (event.keyCode === 13) { // Check if the Enter key (key code 13) was pressed ENTER BUTTON
            const searchValue = $("#search").val();
            if (searchValue !== '') {

                $("#loading-spinner").css("display", "block");
                $("#local1").hide();

                if (cloudDiv.classList.contains("grayed-out")) {
                    $("#loading-spinner-cloud").css("display", "none");
                } else {
                    $("#loading-spinner-cloud").css("display", "block");
                    $("#cloud1").hide();
                }


                fetchDataFromServer(url, searchValue).then((serverData) => {
                    //redirect to barcode search
                    if (serverData.barcode > 0) {
                        redirectToBarcodeSearch()
                    }
                    console.log(serverData)
                    updateButtonLabels(serverData);
                    updateContainerId();
                    // console.log(serverData)
                    $("#loading-spinner").css("display", "none");
                    $("#local1").show();

                    if (cloudDiv.classList.contains("grayed-out")) {
                        // .cloud is grayed out, don't fetch cloudResponse and don't update button labels
                        $("#loading-spinner-cloud").css("display", "none");

                    } else {
                        // .cloud is not grayed out, fetch cloudResponse and update button labels if successful
                        fetchFromCloud(cloudUrl, searchValue)
                            .then((cloudResponse) => {
                                if (cloudResponse.success) {
                                    let cloudData = cloudResponse.data;
                                    // Update button labels
                                    updateButtonLabels(cloudData);
                                    updateContainerId();
                                    $("#loading-spinner-cloud").css("display", "none");
                                    $("#cloud1").show();
                                    // $("#cloud2").css("display", "block");
                                } else {
                                    console.error("Error fetching data from the cloud:",
                                        cloudResponse.error);
                                }
                            })
                            .catch((error) => {
                                console.error("Error fetching data from the cloud:", error);
                            });
                    }

                }).catch((error) => {
                    console.error("Error fetching data:", error);
                });
            }
        }
    });

    if (searchValue !== '') {
        fetchDataFromServer(url, searchValue).then((data) => {
            updateButtonLabels(data);
            console.log(data);
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
        } else if (type === 'video' || type === 'audio' || type === 'pdf' || type === 'image' ||
            type === 'cards') {
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

        const fileUrl =
            `https://arktechph.com/V4/20%20Document%20Management%20System/tamang_dms.php?file=${fileNumber}&ipAddress=${ipAddress}`;

        const url = `${fileUrl}&searchValue=${encodeURIComponent(searchValue)}&return=server`;
        window.location.href = url;
    }

    function redirectToBarcodeSearch() {
        const searchValue = $("#search").val();
        const baseUrl = '../../../../V4/76%20Nandemo%20Barcode%20Software/v4.3/ck_searchEngine3.php';

        const barcodeSearchUrl =
            `${baseUrl}?se=1&barcode=${encodeURIComponent(searchValue)}&getSearchKey=${encodeURIComponent(searchValue)}&historySearch=&return=1`;

        window.location.href = barcodeSearchUrl;
    }

    function clearErrorText() {
        tip.textContent = "";
        tip.style.color = "";
        tagCount = 0;
    }

    // handles the typing to search input from keypad  for mobile (portrait and landscape)
    // Check the viewport width
    if ((window.innerWidth >= 200 && window.innerWidth <= 585) || (window.innerWidth >= 660 && window
            .innerWidth <= 850 && window.innerHeight >= 284 && window.innerHeight <= 380)) {
        $("#keyboard .letter").on("touchstart", function(e) {
            e.stopPropagation(); // Stop the event propagation here

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
    } else {
        $("#keyboard .letter").click(function(e) {
            e.stopPropagation(); // Stop the event propagation here

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
    }


    keypadButton.addEventListener("click", () => {
        if (keypadModal.style.display === "block") {
            keypadModal.style.display = "none";
            clearErrorText();
            tagCount = 0;
        } else {
            keypadModal.style.display = "block";
            keypadModal.style.background = "none";
            // phone portrait
            if (window.innerWidth >= 200 && window.innerWidth <= 585) {
                keypadModal.style.marginTop = "15rem";
            }
            // phone landscape
            else if (window.innerWidth >= 660 && window.innerWidth <= 850 && window.innerHeight >=
                284 && window.innerHeight <= 380) {
                keypadModal.style.marginTop = "7rem";
                keypadModal.style.marginLeft = "2rem";
            } else {
                keypadModal.style.marginTop = "25rem";
            }
        }
    });

    //show the actual size of the page
    function updatePageSize() {
        const width = window.innerWidth;
        const height = window.innerHeight;
        const sizeInfo = document.getElementById('sizeInfo');
        sizeInfo.textContent = `actual size: ${width}x${height}`;
    }

    // Initial page size update
    updatePageSize();

    // Attach a listener to update the size when the window is resized
    window.addEventListener('resize', updatePageSize);

    function updateScreenSize() {
        const width = window.screen.width;
        const height = window.screen.height;
        const sizeInfo = document.getElementById('screenInfo');
        sizeInfo.textContent = `screen size: ${width}x${height}`;
    }

    // Initial screen size update
    updateScreenSize();

});
</script>


<script src="../Libraries/ui-touch-punch/jquery.ui.touch-punch.min.js"></script>

<script>
$('#container').draggable({
    scroll: false,
    containment: "#bg-container",

    start: function(event, ui) {

        console.log("start top is :" + ui.position.top)
        console.log("start left is :" + ui.position.left)
    },
    drag: function(event, ui) {
        console.log('draging.....');
    },
    stop: function(event, ui) {
        console.log("stop top is :" + ui.position.top)
        console.log("stop left is :" + ui.position.left)

        // alert('left:'+ui.position.left + ' top:'+ui.position.top)
    }
});
</script>