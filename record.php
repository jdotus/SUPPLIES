<?php include('dbcon.php')?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Records</title>

    <!-- Include jQuery -->
    <link rel="stylesheet" href="style.css">

    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js" integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy" crossorigin="anonymous"></script>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

    <!-- Data Tables -->
    <link rel="stylesheet" type="text/css" href="//cdn.datatables.net/2.2.1/css/dataTables.dataTables.min.css">
    <script src="//cdn.datatables.net/2.2.1/js/dataTables.min.js"></script>

</head>
    <body>
        <div class="records">
            <div class="search_container">
                <div class="align_center">
                    <label for="record">RECORDS</label>
                </div>
                <div class="align_center">
                    <select name="supplies" id="supplies" require>
                        <option value="default">SELECT</option>
                        <option value="delivery_in">Delivery IN</option>
                        <option value="delivery_out ">Delivery OUT</option>
                        <!-- <option value="fillament">Fillament</option> -->
                    </select>
                    <input id="filter" type="text">
                </div>
            </div>
            <div class="no-record" id="no-record">NO RECORD</div>
            <div class="view-table" id="view-table"></div>
        </div>
    <script>
        $(document).ready(function() {
            function viewDefaultTable(selectedValue, filter) {
                $.ajax({
                    type: "GET",
                    url: "record_table.php",
                    data: {
                        selectedSupply: selectedValue, // No need to use $(selectedValue).val() again
                        filter: filter
                    },  
                    success: function (response) {
                        if ($.trim(response) === '') {
                            $('#no-record').show(); // Show "No Record" if response is empty
                        } else {
                            $('#no-record').hide(); // Hide if data exists
                        }
                        $('#view-table').html(response);
                    },
                    error: function() {
                        $('#scanbarcode').hide();
                    }
                });
            }

            // Initially disable filter and hide "No Record" message
            $('#filter').prop('disabled', true);
            $('#no-record').hide();

            $('#supplies').on('change', function() {
                var selectedValue = $(this).val();
                var filter = $('#filter').val() || ""; // Ensure filter has a value

                if (selectedValue !== 'default') {
                    $('#filter').prop('disabled', false); // Enable filter
                    viewDefaultTable(selectedValue, filter); // Fetch data
                } else {
                    $('#filter').prop('disabled', true); // Disable filter
                    // $('#view-table').html(''); // Clear table
                    viewDefaultTable("default", filter);
                    $('#no-record').hide(); // Hide "No Record"
                }
            });

            // Add keyup event to #filter input
            $('#filter').on('keyup', function() {
                var selectedValue = $('#supplies').val();
                var filter = $(this).val().trim(); // Get and trim filter input

                if (selectedValue !== 'default') {
                    viewDefaultTable(selectedValue, filter); // Fetch filtered data
                }
            });
        });
    </script>
    </body>
</html>