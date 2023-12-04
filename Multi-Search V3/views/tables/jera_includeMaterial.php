<?php
session_start();
$_SESSION['idNumber'];
$permissionid = ['1176', '1159', '1163', '0280'];
// $searchValue = isset($_GET['searchValue']) ? $_GET['searchValue'] : '';
if ($query->num_rows > 0) {
    echo '<label class="totalRecordsPC">' . displayText("L509", "utf8", 0, 0, 1)  . ' :' . $totalRecords . '</label>';
    echo "<table id='dataTable' class='cell-border display compact' style='width: 100%'>";
    echo "<thead>";
    echo "<tr>";
    echo "<th class='w3-indigo'>#</th>";

    echo "<th class='w3-indigo'>" . displayText("L4750", "utf8", 0, 0, 1) . " ID</th>"; // Inventory ID     
    echo "<th  class='w3-amber' style='width: 20px !important;'>" . displayText("L4716", "utf8", 0, 0, 1) . "</th>"; // Supplier
    echo "<th class='w3-indigo'>" . displayText("L4717", "utf8", 0, 0, 1) . "</th>"; // Stock Date
    echo "<th class='w3-amber'>" . displayText("L935", "utf8", 0, 0, 1) . "</th>"; // Material Type
    echo "<th class='w3-amber'>" . displayText("L4720", "utf8", 0, 0, 1) . "</th>"; // Thickness
    echo "<th class='w3-amber'>" . displayText("L4721", "utf8", 0, 0, 1) . "</th>"; // Length
    echo "<th class='w3-amber'>" . displayText("L4722", "utf8", 0, 0, 1) . "</th>"; // Width
    echo "<th class='w3-amber'>" . displayText("L4723", "utf8", 0, 0, 1) . "</th>"; // Treatment
    echo "<th class='w3-green  w3-center Smodal 'style='cursor:pointer;'>" . displayText("L10012", "utf8", 0, 0, 1) . " <i class='fa fa-filter' aria-hidden='true'></i><br>";
    echo "</th>"; // S-TYPE
    echo "<th class='w3-indigo'>" . displayText("L4724", "utf8", 0, 0, 1) . "</th>"; // Inventory Quantity
    echo "<th class='w3-indigo'>" . displayText("L4725", "utf8", 0, 0, 1) . "</th>"; // Book Quantity
    // echo "<th class='w3-indigo'>" . displayText("L2034", "utf8", 0, 0, 1) . "</th>"; // tempBooking
    echo "<th class='w3-indigo'>" . displayText("L4726", "utf8", 0, 0, 1) . "</th>"; // Available Stock
    echo "<th class='w3-indigo'>" . displayText("L4727", "utf8", 0, 0, 1) . "</th>"; // Weight
    // echo "<th class='w3-indigo'>" . displayText("L4728", "utf8", 0, 0, 1) . "</th>"; // Bends
    echo "<th class='w3-indigo'>" . displayText("L4729", "utf8", 0, 0, 1) . "</th>"; // Scratch
    echo "<th class='w3-indigo'>" . displayText("L4730", "utf8", 0, 0, 1) . "</th>"; // PVC
    echo "<th class='w3-green w3-center dropdown' style='cursor:pointer;'>" . displayText("L4456", "utf8", 0, 0, 1) . "  <i class='fa fa-filter' aria-hidden='true'></i><br>";
    echo "</th> "; // Current Location
    echo "<th class='w3-indigo w3-center' id='version' >" . displayText("L4155", "utf8", 0, 0, 1) . "</th>"; // Verion
    // echo "<th class='w3-indigo w3-center'>" . displayText("L243", "utf8", 0, 0, 1) . "</th>"; // Action
    echo "</tr>";
    echo "</thead>";
    echo "<tbody>";
    echo "</tbody>";
    echo "</table>";
} else {
    echo '
    
    <div class="bodyTable">
    <img src="nodata.jpg" alt="" class = "logo">
    </div>

    ';
}

?>

<style>
</style>
<!-- <div id="modal-izi"><span class="izimodal-content"></span></div> -->

<script>
    var search = $('#search').val();

    function edit(button, inventoryId, count) {

        console.log(button)
        var update = $("." + count);
        var updateArray = update;
        var count = count;
        var isEditable = update.attr("contenteditable") === "true";
        update.attr("contenteditable", !isEditable);
        update.removeAttr("disabled");
        update.css('background-color', '#FDFD96');
        update.css('padding', '1rem');
        update.css('color', 'black');

        if (isEditable) {
            const locations = updateArray[0].textContent;
            $.ajax({
                url: "././controllers/ajax/mj_materialEditValue.php",
                type: "POST",
                data: {
                    locations: locations,
                    inventoryId: inventoryId

                },
                success: function(response) {
                    console.log(response);
                    location.reload();
                },
                error: function(data) {
                    console.log(data)
                }
            })

        }
    }

    $(document).ready(function() {


        var sqlData = "<?php echo $sql; ?>";
        console.log(sqlData);
        var totalRecords = "<?php echo $totalRecords; ?>";


        $("th").click(function() {
            var targetId = $(this).attr("data-target");
            $("#" + targetId).toggle();
        });

        //datatable
        var dataTable = $('#dataTable').DataTable({
            "searching": false,
            "processing": true,
            "createdRow": function(row, data, dataIndex) {
                // Add CSS class to alternate rows
                $(row).addClass("w3-hover-orange");
                if (dataIndex % 2 === 0) {
                    $(row).css("background-color", "#caf0f8");
                } else {
                    $(row).css("background-color", "#ade8f4");
                }
            },
            "ordering": false,
            "serverSide": true,
            "bInfo": false,
            "ajax": {
                url: "././controllers/ajax/mj_materialAjax.php", // json datasource
                type: "POST", // method, by default get
                data: {
                    "totalRecords": totalRecords,
                    "sqlData": sqlData,
                    "query": "<?php echo $sql; ?>",
                    // search : search,
                    searchValue: searchValue
                },
                error: function(data) { // error handling
                    console.log(data);
                }
            },
            language: {
                processing: "<span class='loader'></span>"
            },
            
            "columnDefs": 
            [
                {
                    render: function(data, type, row, meta) {
                        const [need, trash] = row[0].split("<");
                        // return '<div class="' + need + '" style="white-space:normal !important;">' + data + '</div>';
                        return '<div class="' + need + ' ellipsis">' + data + '</div>';
                    },
                    targets: [2], 
                }
            ],
            
            fixedColumns: {
                leftColumns: 0
            },
            scrollY: 600,
            scrollX: true,
            scrollCollapse: false,
            scroller: {
                loadingIndicator: true
            },
            stateSave: false,
        });

        // $('#tableData_wrapper .dataTables_scrollBody').css("width", "1000");


    });
</script>