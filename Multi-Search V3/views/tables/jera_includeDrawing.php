<?php
    if ($query->num_rows > 0) 
    {
        echo '<label>' . displayText("L509", "utf8", 0, 0, 1)  . ' :' . $totalRecords . '</label>';
        echo ' <table id="dataTable" class="cell-border display compact" style="width:100%">
        <thead>
        <tr>
        <th colspan="1" class="w3-indigo" rowspan="2" style="text-align: center; vertical-align: middle;">#</th>';
            echo "<th class='w3-indigo' colspan='1'  rowspan='2' style='text-align: center; vertical-align: middle;'>" . displayText("L269", "utf8", 0, 0, 1) . "</th>"; // Inventory ID   
            echo "<th class='w3-indigo' colspan='1'  rowspan='2' class='w3-amber' style='text-align: center; vertical-align: middle;'>" . displayText("L28", "utf8", 0, 0, 1) . "</th>"; // Part Number       
            echo "<th class='w3-indigo' colspan='1'  rowspan='2' class='w3-amber' style='text-align: center; vertical-align: middle;'>" . displayText("L4739", "utf8", 0, 0, 1) . "</th>"; // Part Name
            echo "<th class='w3-indigo' colspan='1'  rowspan='2' style='text-align: center; vertical-align: middle;'>" . displayText("L4740", "utf8", 0, 0, 1) . "</th>"; // REVISION ID
            echo "<th class='w3-indigo' colspan='5' style='text-align: center; vertical-align: middle;'>" . displayText("L77", "utf8", 0, 0, 1) . "</th>"; // Drawing
            echo ' </tr>

        <tr>
            <th class="w3-indigo" style="text-align: center; vertical-align: middle;">ARK</th>
            <th class="w3-indigo" style="text-align: center; vertical-align: middle;">CUS</th>
            <th class="w3-indigo" style="text-align: center; vertical-align: middle;">3D</th>
            <th class="w3-indigo" style="text-align: center; vertical-align: middle;">WI</th>
            <th class="w3-indigo" style="text-align: center; vertical-align: middle;">ALL</th>
        </tr>
        </thead>
        <tbody>
        </tbody>
        </table>';
    }
    else
    {
        echo '
            <div class="bodyTable">
            <img src="nodata.jpg" alt="" class = "logo">
            </div>
        ';
    }
?>

<script>
    var search = $('#search').val();

    $(document).ready(function() {
                var sqlData = "<?php echo $sql; ?>";
                console.log(sqlData);
                var totalRecords = "<?php echo $totalRecords; ?>";
                var dataTable = $('#tableData').DataTable({
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
                        url: "././controllers/ajax/mj_drawingSearchAjax.php", // json datasource
                        type: "POST", // method  , by default get
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
                    fixedColumns: {
                        leftColumns: 0
                    },
                    "columnDefs": [{
                        "targets": [0,1,2,4,5,6,8,9], // 
                        "render": function ( data, type, row ) {
                            return '<div style="white-space:normal !important; font-size: 12px;">'+data+'</div>';
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
            });
        

</script>
