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
        </div>
    <script>
        $(document).ready(function() {
            $('#filter').prop('disabled', true);

            $('#supplies').on('change', function() {
                var selectedvalue = $(this).val();
                
                if (selectedvalue == 'default') {
                    $('#filter').prop('disabled', true); // Disable the filter button if "default" is selected
                } else {
                    $('#filter').prop('disabled', false); // Enable the filter button for other options
                }
            });
        });
    </script>
    </body>
</html>