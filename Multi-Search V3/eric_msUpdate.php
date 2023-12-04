<?php
$inventoryIds = isset($_REQUEST['inventoryIds']) ? $_REQUEST['inventoryIds'] : '';
// echo $inventoryIds;
?>

<h3 class='text-center fw-bold'>UPDATE LOCATION</h3>
<input type="text" name="" class=' form-control text-center bg-warning' id="location" required>

<div class="d-flex justify-content-center align-items-center">
    <button type="button" class='float-center btn btn-primary btn-lg mt-5 mb-2' id='confirmUpdate'>CONFIRM</button>
</div>

<script>
    $(document).ready(() => {
        $("#confirmUpdate").on("click", function() {
            const location = $("#location").val(); // Get the location value
            if (!location) {
                swal({
                    title: "Something went wrong.....",
                    text: "Blank input is not allowed!",
                    type: "error",
                    timer: 2000, // Specify the time duration for the alert (e.g., 3000ms or 3 seconds)
                    showConfirmButton: false // Hide the "OK" button
                })


                return;
            }
            $.ajax({
                type: "POST",
                url: "eric_msAJAX.php", // Use the current page's URL
                data: {
                    inventoryIds: "<?php echo $inventoryIds; ?>",
                    loc: location,
                    // Include the inventoryIds
                    // location: location // Include the updated location
                },
                success: (response) => {
                    if (response == 'success') {
                        swal({
                            title: "Redirecting...",
                            text: "Data has been successfully updated! ",
                            type: "success",
                            timer: 2000, // Specify the time duration for the alert (e.g., 3000ms or 3 seconds)
                            showConfirmButton: false // Hide the "OK" button
                        }).then(function() {
                            // Use JavaScript to navigate to the desired page
                        });
                    } else {
                        swal({
                            title: "Something went wrong.....",
                            text: "Failed to update",
                            type: "info",
                            timer: 2000, // Specify the time duration for the alert (e.g., 3000ms or 3 seconds)
                            showConfirmButton: false // Hide the "OK" button
                        })
                    }
                    setTimeout(function() {
                        $("#reset").trigger("click");
                    }, 2000); // Trigger click after 2 seconds

                },
                error: function(xhr, status, error) {
                    // Handle errors here, if any
                    console.error(error);
                }
            });
        });
    })
</script>