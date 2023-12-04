<style>
    
	.miniView {
     	margin-bottom: 1px !important;
	}
	.desktopView {
     	margin-bottom: 1px !important;
	}
    .rotateDevice {
        display: none;
    }
    /* portrait */
    @media screen and (max-width: 768px) {
        body {
            margin: 0; 
            background-color: white;
            /* display: flex; */ /*Comment by Tamang 2023-10-17 */
            justify-content: center; 
            /* align-items: center;  */
            align-items: flex-start; /*Added by Tamang*/
            height: 100vh;
             
        }
        /*Added by Tamang*/
        #fixedHeader {
            width:100%;
        }

        .rotateDevice {
            display: block; 
            text-align: center; 
            background-color: white; 
            padding: 20px;

            position: fixed; 
            z-index: 999;
            top: 20rem !important; 
            left: 0; 
            right: 0; 
            bottom: 0; 

        }
       
    }


    /* sir jhon android tablet */
    @media screen and (max-width: 1280px) and (max-height: 800px){
        .modal {
            margin-top: 16rem !important;
        }
    }
    @media screen and (max-width: 1024px) and (max-height: 768px){
        .landscape {
            /* height: 100vh !important; */ /*Comment Tamang 2023-10-17 */
            height: 120vh !important; /*Added by Tamang*/
        }

        .embed-responsive-item {
            height: 100vh !important;
        }
    }

     /* adjust in tablet */
     @media screen and (max-width: 1024px) {
            #searchContainer {
                // margin-top: -3rem;
            }
    }
    @media screen and (min-width: 1600px) and (min-height: 900px) {
            .container-fluid .position-fixed #searchContainer {
                margin-top: -12rem !important;
            }
    }
    @media screen and (min-width: 1366px) {
            #searchContainer {
                // margin-top: -100rem;
            }
    }
 /* mobile phone portrait view */
    @media (min-width:200px) and (max-width: 585px) {
        .btnLabel {
            font-size: .8em !important;
        }
        #keypadButton i,
        #refreshButton i,
        #zoomButton i {
            font-size:  1em !important;
        }
        #keypadButton,
        #refreshButton,
        #zoomButton {
            width: 5em;
            margin-top: -1em !important;
            margin-bottom: 1em !important;

        }
        #keypadButton {
            margin-left: .7em !important;
            width: 5em !important;
        }
        #refreshButton {
            margin-left: 2em !important;
        }
        #zoomButton {
            margin-left: 1em !important;
        }
       #searchDiv {
            width: 260% !important;
            margin-top: -1em !important;
            margin-bottom: 1em !important;
            margin-left: -1em !important;
        }
        #searchDiv .form-floating input,
        #searchDiv .form-floating label,
        #searchDiv .form-floating i {
            font-size: .8em !important;

        }
       
        #container {
          margin: 100px auto;
          width: 18.5em !important;
          height: 40em !important;
          border-radius: 4px;
          background-color: #a2abba;
          padding: 16px;
          overflow: hidden;
          padding-top: 3em;

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
         #keyboard li .gap .clearl #tip {
            font-size: .8em !important;
            margin-top: -2em !important;

        }
        #enter {
            width: 110px !important;
        }
        #closeKeypad {
           
            top: -3.2rem !important; 
            padding: 3px;
            width: 10px;
            height: 10px;
            left: 20px;
        }   
    
    }
	/*phone landscape*/
	@media (min-width: 590px) and (max-width: 835px) {
		.btnLabel {
            font-size: .8em !important;
        }
        #keypadButton i,
        #refreshButton i,
        #zoomButton i {
            font-size:  1em !important;
        }
        #keypadButton,
        #refreshButton,
        #zoomButton {
            width: 5em;
            margin-top: .5em !important;
            margin-bottom: 1em !important;

        }
        #keypadButton {
            margin-left: .7em !important;
            width: 5em !important;
        }
        #refreshButton {
            margin-left: 2em !important;
        }
        #zoomButton {
            margin-left: 1em !important;
        }
       #searchDiv {
            width: 260% !important;
            margin-top: .5em !important;
            margin-bottom: 1em !important;
            margin-left: -1em !important;
        }
        #searchDiv .form-floating input,
        #searchDiv .form-floating label,
        #searchDiv .form-floating i {
            font-size: .8em !important;
        }
		.iframe-container {
			margin-top: -3em !important;
        	
		}
		
		#container {
            margin: 100px auto;
            width: 390px !important;
            height: 270px !important;
            border-radius: 4px;
            background-color: #a2abba;
            padding: 16px;
            z-index: 999;
            overflow: hidden !important;
        }
        #keyboard li {  
            float: left;
            margin: 0 0px 0px 0;
            width: 55px !important;
            height: 55px !important;
            font-size: 16px;
            line-height: 80px;
            text-align: center;
            background: #fff;
            border: 1px solid black;
        }  
        #enter {
            width: 110px !important;
        }
        #closeKeypad {
            left: 10px !important; 
           
        }
	
    }
</style>

<?php
// echo "Received searchValue: " . $_GET['searchValue']; // Debugging output
$searchValue = isset($_GET['searchValue']) ? $_GET['searchValue'] : '';
?>



<!--  -->
<!-- <div class='container-fluid position-fixed'  style='z-index: 99;margin-top: -.3rem !important' id='searchContainer' > --> <!--TAMANG 2023-10-17-->
<div class='container-fluid'  style='width:100%; z-index: 99;' id='searchContainer' >
    <div id='searchShow'>
        <div class='row'>
            <input type="hidden" id="categorySearch" value="<?php echo $_GET['search']; ?>">
            <input type="hidden" id="querySearch" value="<?php echo $_GET['query']; ?>">
            <div class='w3-container w3-white w3-bottombar w3-border-indigo'>
            <div class="col-md-12">
                    <div class='row form'>

                        <form method="POST" id="formResultSearch2"></form>
                        <div class="col-md-1" id="dropdownAppend">

                        </div>

                         <!-- jera keypad 09/01/2023 -->

                        <style>
							@media screen and (min-width: 1024px) {
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
							}
                            #container {  
                                margin: 100px auto;
                                width: 590px;
                                height: 390px;
                                /* margin-left: 100 !important; */
                                border-radius: 4px;
                                background-color: #a2abba !important;
                                padding: 32px;
                                }
                            #keypadButton, .refresh {
                                padding: 8px 8px 0px 8px; 
                                background-color:#3452b4;
                                color:white;
                                border-radius:16px;
                                border:none;
                                margin-bottom:0;
                                width: 80px;
                            }
                            .btnLabel{
                                font-size: 16px;
                            }
                            .refresh{
                                background-color:#4caf50 !important;
                            }
                            /* #keypadButton {
                                margin-left: 4rem !important;
                            } */

                            #keyboard {
                                margin: 0;
                                list-style: none;
                                width: 280px;
                                height: 0;
                                padding: 0 !important;
                                background-color: transparent;
                            
                            }
                            .column2 {
                                margin-left: 100px !important;
                            }

                            #keyboard ul {
                                border: 3px solid #fff;
                                /* background-color: pink; */
                            }
                               
                                #tip, .gap {
                                    height: 40px !important;
                                    width: 220px !important;
                                    background-color: transparent !important;
                                    border: none !important;
                                }
                                #enter {
                                    width: 160px;
                                }
                                    .capslock, .tab, .left-shift, .clearl, .switch {  
                                    clear: left;  
                                    }  
                                        #keyboard .tab, #keyboard .enter {  
                                        width: 200px;  
                                        }  
                                        #keyboard .capslock {  
                                        width: 80px;  
                                        }  
                                        #keyboard .return {  
                                        width: 90px;  
                                        }  
                                        #keyboard .left-shift{  
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
                                    .enter{
                                    background-color: #b8e6e2;
                                    }
                                    .letter{
                                    width: 100px;
                                    }
                                    .del{
                                    background-color: #fbecdd !important;
                                    }
                                    .bs{
                                    background-color: #f0c243 !important;
                                    }
                                    .blue{
                                        background-color: #cce7eb !important;
                                    }
                                    .grey{
                                        background-color: #d8e2e3 !important;
                                    }
                                    .space{
                                        width:  !important;
                                    }
                                    .gap {
                                        height: 10px; /* Adjust the height as needed */
                                        width: 300px !important;
                                        background-color: #a2abba !important;
                                        border:none !important;
                                    }
                                    .gap:hover{
                                        top:0 !important;
                                        left:0 !important;
                                    }

                                    .uppercase {  
                                    text-transform: uppercase;  
                                    }  
                                    #keyboard .space {  
                                    float: left;
                                    width: 556px;  
                                    }  
                                    #keyboard .switch, #keyboard .space, #keyboard .return{
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
                                        background-color: #f00; /* Adjust the background color as needed */
                                        color: #fff; /* Adjust the text color as needed */
                                        border: none;
                                        padding: 8px 16px;
                                        border-radius: 16px;
                                        display: none;
                                    }
                                
                                /* portraint ipad */
                                @media screen and (max-width: 768px) {
                                    .inputDiv {
                                        flex: 1 ;
                                        margin: 0 !important;
                                        padding: 0 !important;
                                        padding-top: 3rem !important;

                                    }
                                    .inputDiv:first-child {
                                        margin-left: 0 !important;
                                    }
                                    #searchKey {
                                        width: 100% !important; 
                                        margin-right: 0 !important; 
                                    }
                                    .refreshDiv {
                                        margin-left: 10px !important; 
                                    }
                                    #searchKey {
                                        width: 100%;
                                        margin: 0; /* Remove any margin */
                                        padding-right: 10px; /* Add padding to the right to separate the input and button */
                                    }
                                    #keypadButton {
                                        margin: 0; /* Remove any margin */
                                    }
                                    .zoom {
                                        display: block;
                                    }
                                    #zoomButton
                                    {
                                        padding: 6px 12px 1 12px;
                                    }
                                }

                                @media screen and (min-width: 769px) and (max-width: 1024px) {
                                    .inputDiv {
                                        padding-top: 3rem;
                                    }
                                }
                                /* ipad landscape */
                                @media screen and (max-width: 1794px){
                                    .zoom {
                                        display: block;
                                    }
                                    .inputDiv {
                                        flex: 1 ;
                                        margin: 0 !important;
                                        padding: 0 !important;
                                        /* padding-top: 3rem !important; */

                                    }
                                    .inputDiv:first-child {
                                        margin-left: 0 !important;
                                    }
                                    #searchKey {
                                        width: 100% !important; 
                                        margin-right: 0 !important; 
                                    }
                                    .refreshDiv {
                                        margin-left: 10px !important; 
                                    }
                                    #searchKey {
                                        width: 100%;
                                        margin: 0; Remove any margin
                                        padding-right: 10px; /* Add padding to the right to separate the input and button */
                                    }
                                    #keypadButton {
                                        margin: 0; /* Remove any margin */
                                    }
                                    .zoom {
                                        display: block;
                                    }
                                    #zoomButton
                                    {
                                        padding: 6px 12px 1 12px;
                                    }
                                    
                                }
                                    
                            

                                /* modal css */
                                #zoomModal.iziModal {
                         
                                    width: auto;
                                    height: auto;
                                    margin: 0 auto;
                                }
                                #zoomModal.iziModal .iziModal-header {
                                    display: none;
                                }

                                iframe #iframeValue{
                                    min-width: 100%;
                                    height: 120vh;
                                }


                        </style>
                        
                        <div style="" class='col-md-1 inputDiv'>
                            <button id="keypadButton">
                                <i class="fa fa-calculator icon w3-xlarge"></i><br>
                                <span class="btnLabel">KEYPAD</span>
                            </button>
                            
                        </div>

                        <!-- end of jera keypad 09/01/2023 -->

                        
                        <div class='col-md-3 inputDiv input' id="searchDiv">
                            <div class="inner-addon right-addon form-floating">
                                <i class="fa fa-search icon w3-large" style='top:-2px; right: 0px;'></i>
                                <input autofocus type='text' id='searchKey' class='form-control' form="formResultSearch2" value="<?php echo isset($_GET['query']) ? $_GET['query'] : $_GET['barcode']?>">
                                <label for="searchKey"><?php echo displayText("L4435", "utf8", 0, 0, 1); ?></label>
                                 <!-- <span><input style="margin-left: 20px;" type="checkbox" name="historySearch" value='checked' id="historyCheckBox" form="formResultSearch2" <?php echo $_GET['history'] == "checked" ? "checked" : "" ?>> <b><?php echo displayText("L4575"); ?></b></span> -->
                            </div>
                        </div>
                        <div class='col-md-3 col-sm-3 inputDiv refreshDiv' id="refreshDiv">
                            <!-- <?php echo $refreshBtn; ?> -->
                            <button class="refresh" id="refreshButton" onclick="location.reload();">
                                <i class="fa fa-refresh icon w3-xlarge"></i><br>
                                <span class="btnLabel">REFRESH</span>
                            </button>
                            <!-- <?php echo $scanBtn; ?> -->
                            <!-- <a href='gerald_batchSearch.php'><button class='btn btn-md btn-primary'><b>BATCH SEARCH</b></button></a> -->
                            
                            

                        </div>

                        <div class='col-md-1 inputDiv'>
                            <button class="zoom" id="zoomButton" onclick="viewDrawing()" >
                                <i class="fa fa-magnifying-glass icon w3-xlarge"></i><br>
                                <span class="btnLabel">ZOOM</span>
                            </button>
                            
                        </div>

                        <!-- modal -->
                        <div id="zoomModal" style="display: none;">
                            <div class="landScape">
                                <button data-izimodal-close="" style="float:right;padding: 4px 8px 4px 8px; border-radius: 4px; background-color: red; color: white;"class="custom-close-button">Close</button>
                                <iframe class="embed-responsive-item" id="iframeValue" src="" style="min-width: 100%; min-height: 100% !important;"></iframe>
                            </div>       
                        </div>
                        
                    </div>
                </div>
            </div>
        </div>
    </div>

                            <!-- keypad -->
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
<div id="container" style="display:none" class="modal keyPad">
        <div class="row">
            <div class="col-md-6">
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
            <div class="col-md-6">
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

<?php $_GET['barcode'] == "" && $_GET['employeeId'] == "" ? include('views/index/table/table.php') : ""; ?>

<?php isset($_GET['close']) and $_GET['search'] == "" and $_GET['query'] == "" ? include('views/index/employee/employee.php') : ""; ?>

<?php isset($_GET['inventoryId']) and $_GET['inv'] == '1' ? include('views/index/inventory/inventory.php') : ""; ?>

<?php isset($_GET['barcode']) ? include('views/index/nandemo.php') : "" ?>

<!-- jera script for handling keys 09/01/2023 -->
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.3.2/jquery.min.js"></script>  
<script type="text/javascript" src="js/keyboard.js"></script>


<!-- zoom drawing -->

 <script>
    $(document).ready(function() {
        
        const keypadButton = document.getElementById("keypadButton");
        const keypadModal = document.getElementById("container");
        const input = document.getElementById("searchKey");
        const tip = document.getElementById("tip");

        let tagCount = 0;

        // close keypad
        if (window.innerWidth >= 200 && window.innerWidth <= 585) {
            $("#closeKeypad").on("touchstart", function(e) {
                keypadModal.style.display = "none";

            });
        } else {
            $("#closeKeypad").click(function(e) {
                keypadModal.style.display = "none";

            });
        }


        //when ENTER from button and main search icon is clicked
        if ((window.innerWidth >= 200 && window.innerWidth <= 585) || (window.innerWidth >= 780 && window.innerWidth <= 790 && window.innerHeight >= 284 && window.innerHeight <= 380)) {
            $("#enter").on("touchstart", function(e) {
                $("#formResultSearch2").submit();
            });
        } else {
            $("#enter").click(function(e) {
                $("#formResultSearch2").submit();
            });
        }

        function clearErrorText() {
            tip.textContent = "";
            tip.style.color = "";
            tagCount = 0; // reset tagCount
        }
        
        

        // handles the typing to search input from keypad  for mobile
        // Check the viewport width
        if ((window.innerWidth >= 200 && window.innerWidth <= 585) || (window.innerWidth >= 780 && window.innerWidth <= 790 && window.innerHeight >= 284 && window.innerHeight <= 380)) {
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

        // show/hide virtual keypad
        keypadButton.addEventListener("click", () => {
            if (keypadModal.style.display === "block") {
                keypadModal.style.display = "none";
                clearErrorText();
                tagCount = 0;
            } 
            else {
                keypadModal.style.display = "block";
                keypadModal.style.background = "none";
                if (window.innerWidth >= 200 && window.innerWidth <= 585) {
                    keypadModal.style.marginTop = "15rem";
                }
                else if (window.innerWidth >= 780 && window.innerWidth <= 790 && window.innerHeight >= 284 && window.innerHeight <= 380) {
                    keypadModal.style.marginTop = "2rem";
                    keypadModal.style.marginLeft = "2rem";
                }
                else {
                    keypadModal.style.marginTop = "25rem";
                }
            }
        });
    });
</script>
<!-- end of jera script for handling keys 09/01/2023 -->


<!-- jera draggable keypad 10/11/2023-->
<!-- <script src="./Libraries/ui-touch-punch/jquery.ui.touch-punch.min.js"></script> -->
<script src="http://code.jquery.com/jquery-1.7.2.min.js"></script>
<script src="http://code.jquery.com/ui/1.8.21/jquery-ui.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui-touch-punch/0.2.3/jquery.ui.touch-punch.min.js"></script>


<script>
    $('#container').draggable({
        scroll: false,
        containment: "#bg-container",
        
        start: function( event, ui ) {
            console.log("start top is :" + ui.position.top)
            console.log("start left is :" + ui.position.left)
        },
        drag: function(event, ui) {
            console.log('draging.....');    
        },
        stop: function( event, ui ) {
            console.log("stop top is :" + ui.position.top)
            console.log("stop left is :" + ui.position.left)

         
        }    
    });
</script>
<!-- end of jera draggable keypad 10/11/2023-->


<!-- zoom drawing -->
<script>
    function openZoomModal() {
        $('#zoomModal').iziModal({
            title: 'Zoomed Image',
            headerColor: '#3452b4', 
            // fullscreen: true, 
            closeButton: true,
            openFullscreen: true, 
        });

        $('#zoomModal').iziModal('open');
    }

    $('#iframeValue').attr('src', iframeSrc);

    $('#zoomButton').click(function () {
        openZoomModal();
    });

    
</script>
