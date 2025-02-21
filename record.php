<?php include('dbcon.php'); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Records</title>

    <!-- Include CSS -->
    <link rel="stylesheet" href="style.css">
    
    <!-- jQuery & Bootstrap -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- DataTables -->
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
                <select name="supplies" id="supplies">
                    <option value="default">SELECT</option>
                    <option value="delivery_in">Delivery IN</option>
                    <option value="delivery_out">Delivery OUT</option>
                </select>
                <input id="filter" type="text" disabled>
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
                        selectedSupply: selectedValue,
                        filter: filter
                    },
                    success: function(response) {
                        if ($.trim(response) === '') {
                            $('#no-record').show();
                            $('#view-table').html('');
                        } else {
                            $('#no-record').hide();
                            $('#view-table').html(response);
                            
                            setTimeout(function() {
                                var table = $('#example').DataTable({
                                    searching: false,
                                    // ordering: false
                                    // paging: true,
    
                                    dom: '<"d-flex justify-content-between"lfB>rtip', // Flexbox for alignment
                                    buttons: [
                                        {
                                            extend: 'excel',
                                            title: 'Record: ' + selectedValue + '',
                                            filename: 'Record',
                                        }, 
                                        'csv', 'print'
                                    ],
                                    lengthMenu: [ 10, 25, 50, 100 ],
                                });
                                table
                                    .column('.status')
                                    .order('desc')
                                    .draw();
                            }, 100)
                        }
                    },
                    error: function() {
                        $('#no-record').show();
                    }
                });
            }

            // Load default data on page load
            viewDefaultTable("default", ""); 

            $('#supplies').on('change', function() {
                var selectedValue = $(this).val();
                var filter = $('#filter').val().trim();

                if (selectedValue !== 'default') {
                    $('#filter').prop('disabled', false);
                    viewDefaultTable(selectedValue, filter);
                } else {
                    $('#filter').prop('disabled', true);
                    viewDefaultTable("default", "");
                }
            });

            $('#filter').on('keyup', function() {
                var selectedValue = $('#supplies').val();
                var filter = $(this).val().trim();

                if (selectedValue !== 'default') {
                    viewDefaultTable(selectedValue, filter);
                }
            });
        });
    </script>
</body>
</html>
