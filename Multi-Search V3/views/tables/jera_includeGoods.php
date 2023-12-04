<?php
 if ($query -> num_rows > 0)
 {
     echo '<label>'. displayText("L509", "utf8", 0, 0, 1)  . ' :' . $totalRecords . '</label>';
     echo " <table id='dataTable' class='cell-border display compact' style='width:100%'>
             <thead>
                 <tr>
                     <th class='w3-indigo'>#</th>";
                     echo "<th class='w3-indigo'>" . displayText("L4714", "utf8", 0, 0, 1) . "</th>"; // Inventory ID     
                     echo "<th class='w3-amber'>" . displayText("L24", "utf8", 0, 0, 1) . "</th>"; // customer
                     echo "<th class='w3-indigo'>" . displayText("L4717", "utf8", 0, 0, 1) . "</th>"; // Stock Date
                     echo "<th class='w3-amber'>" . displayText("L4737", "utf8", 0, 0, 1) . "</th>"; // Part Number
                     echo "<th class='w3-amber'>" . displayText("L4738", "utf8", 0, 0, 1) . "</th>"; // Revision Level
                     echo "<th class='w3-amber'>" . displayText("L4739", "utf8", 0, 0, 1) . "</th>"; // Part Name
                     echo "<th class='w3-indigo'>" . displayText("L4724", "utf8", 0, 0, 1) . "</th>"; // Inventory Quantity
                     echo "<th class='w3-indigo'>" . displayText("L4725", "utf8", 0, 0, 1) . "</th>"; // Book Quantity
                     echo "<th class='w3-indigo'>" . displayText("L4726", "utf8", 0, 0, 1) . "</th>"; // Available Stock
                    //  echo "<th class='w3-indigo'>" . displayText("L4731", "utf8", 0, 0, 1) . "</th>"; // Current Location
                     echo "<th class='w3-indigo'>" . displayText("L4732", "utf8", 0, 0, 1) . "</th>"; // Inventory Remarks
                     echo "<th class='w3-green w3-center dropdown' style='cursor:pointer;'>" . displayText("L4456", "utf8", 0, 0, 1) . "  <i class='fa fa-filter' aria-hidden='true'></i><br>";
                    echo "<th class='w3-indigo w3-center' id='version' >" . displayText("L4155", "utf8", 0, 0, 1) . "</th>"; // Verion
                    echo "<th class='w3-indigo w3-center'>" . displayText("L243", "utf8", 0, 0, 1) . "</th>"; // Action
                     echo "
                     
                     </tr>
                 </thead>

                 <tbody>
                 </tbody>
                 
                 ";
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
                console.log(sqlData,'shesh');
                var totalRecords = "<?php echo $totalRecords; ?>";
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
                        url: "././controllers/ajax/mj_finishGoodsAjax.php", // json datasource
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
                        "targets": [0,1,2,4,5,6,7,8,9],
                        "render": function(data, type, row) {
                            return '<div class="ellipsis">' + data + '</div>';
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

                // $("#btnFilter").click(function() {
                //     //alert('asd');
                // });

            });

</script>
