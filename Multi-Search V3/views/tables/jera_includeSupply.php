<?php

session_start();
$_SESSION['idNumber'];
$permissionid = ['1176','1159','1163','0280'];
// $searchValue = isset($_GET['searchValue']) ? $_GET['searchValue'] : '';

 if ($query -> num_rows > 0)
 {
     echo '<label>'. displayText("L509", "utf8", 0, 0, 1)  . ' :' . $totalRecords . '</label>';
     echo " <table id='dataTable' class='cell-border display compact' style='width:100%;'>
     <thead>
         <tr>
         <th class='w3-indigo'>#</th>";
         echo "<th class='w3-indigo'>" . displayText("L4714", "utf8", 0, 0, 1) . "</th>"; // Inventory ID     
         // echo "<th class='w3-indigo'>" . displayText("L4715", "utf8", 0, 0, 1) . "</th>"; // batch Number
         echo "<th class='w3-amber'>" . displayText("L4716", "utf8", 0, 0, 1) . "</th>"; // Supplier
         echo "<th class='w3-indigo'>" . displayText("L4717", "utf8", 0, 0, 1) . "</th>"; // Stock Date
         echo "<th class='w3-amber'>" . displayText("L4708", "utf8", 0, 0, 1) . "</th>"; // Supply
         echo "<th class='w3-amber'>" . displayText("L4720", "utf8", 0, 0, 1) . "</th>"; // Thickness
         echo "<th class='w3-indigo'>" . displayText("L4724", "utf8", 0, 0, 1) . "</th>"; // Inventory Quantity
        //  echo "<th class='w3-indigo'>" . displayText("L4731", "utf8", 0, 0, 1) . "</th>"; // Current Locations
         echo "<th class='w3-indigo'>" . displayText("L2168", "utf8", 0, 0, 1) . "</th>"; // expiration date
         echo "<th class='w3-indigo'>" . displayText("L4732", "utf8", 0, 0, 1) . "</th>"; // Inventory Remarks
         echo "<th class='w3-green w3-center dropdown' style='cursor:pointer;'>" . displayText("L4456", "utf8", 0, 0, 1) . "  <i class='fa fa-filter' aria-hidden='true'></i><br>";

        echo "<th class='w3-indigo w3-center' id='version' >" . displayText("L4155", "utf8", 0, 0, 1) . "</th>"; // Verion
        echo "<th class='w3-indigo w3-center'>" . displayText("L188", "utf8", 0, 0, 1) . "</th>"; // Action

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
    function edit(button, inventoryId,count) {
    var update = $("." + inventoryId);
    var updateArray = update;
    var inventoryId = inventoryId;
    var isEditable = update.attr("contenteditable") === "true";
    update.attr("contenteditable", !isEditable);
    update.removeAttr("disabled");
    update.css('background-color', '#FDFD96');
    update.css('padding', '1rem');
    update.css('color', 'black');

    update.attr("type","date");

    if (isEditable)
    {
        const thickness = updateArray[0].textContent;
        const expirationDate = $(updateArray[1]).val();
        const inventoryRemarks = updateArray[2].textContent;

       $.ajax({
            url: "././controllers/ajax/mj_updateValue.php",
            type: "POST",
            data: {
                thickness : thickness,
                expirationDate : expirationDate,
                inventoryRemarks : inventoryRemarks,
                inventoryId : inventoryId

            },  
            success: function(response)
            {
                console.log(response);
                location.reload();
            },
            error: function(data)
            {
                console.log(data)
            }
        })
    }

    }



    $(document).ready(function() {
        var sqlData = "<?php echo $sql; ?>";
        console.log(sqlData);
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
                url: "././controllers/ajax/mj_supplyAjax.php", // json datasource
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
                "render": function(data, type, row,meta) {
                    if (meta.col == 8 || meta.col == 5) {
                        return '<div class="' + row[1] + ' ellipsis">' + data + '</div>';
                    } else if (meta.col == 7)
                    {
                        return '<input type = "text" class="' + row[1] + '" style="white-space:normal !important; background-color: transparent; border:none; outline:none;" disabled value = "'+ data +'">';
                    }
                    else {
                        // const [need, trash] = row[0].split("<");
                        return '<div class="ellipsis">' + data + '</div>';
                    }
                },
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