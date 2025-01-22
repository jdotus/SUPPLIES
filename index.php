<?php
include('dbcon.php');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Supplies</title>
    
    <link rel="stylesheet" href="style.css">

    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js" integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy" crossorigin="anonymous"></script>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

</head>

<body>

    <div class="search_container">
        <form class="search">
                <label for="supplies">Select Supply</label><br>
                <select name="supplies" id="supplies" require>
                    <option value="">SELECT</option>
                    <option value="toner">Toner</option>
                    <option value="drum">Drum</option>
                    <option value="fillament">Fillament</option>
                </select>
                <input type="text" id="barcode" require onkeypress="return (event.charCode != 13);" autocomplete="off"> 
        </form>
    </div>

    <div class="add_supplies">
        <!-- Button trigger modal -->
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#staticBackdrop">
        Add Supplies
        </button>
    </div>

        <!-- Modal -->
        <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="staticBackdropLabel">Add Supplies</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form class="form_modal">
                    <div class="form-group">
                        <label> Supply</label>
                        <select name="addsupply" id="addsupply" require>
                            <option value="addtoner">Toner</option>
                            <option value="adddrum">Drum</option>
                            <option value="addfilament">Filament</option>
                        </select>
                    </div>
                    <div class="form-group ">
                        <label> Model</label>
                        <input type="text" name="Model" class="form-control custom-input" placeholder="Model">
                    </div>
                    <div class="form-group">
                        <label> Description</label>
                        <input type="text" name="Description" class="form-control custom-input" placeholder="Description">
                    </div>
                    <div class="form-group">
                        <label> Owner</label>
                        <input type="text" name="Owner" class="form-control custom-input" placeholder="Owner">
                    </div>
                    <div class="form-group">
                        <label> Code</label>
                        <input type="text" name="Code" class="form-control custom-input" placeholder="Code">
                    </div>
                    <div class="form-group">
                        <label> Quantity</label>
                        <input type="text" name="Code" class="form-control custom-input" placeholder="Quantity">
                    </div>
                </form>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary">Add Supplies</button>
                <button type="button" class="btn btn-primary">IN Transactiont History</button>
                <button type="button" class="btn btn-primary">OUT Transactiont History</button>
            </div>
            </div>
        </div>
        </div>

    <div id="scanbarcode" class="result_container"></div>

    <script>
        $(document).ready(function() {

            $('#barcode').prop('disabled', true);

            $('#supplies').on('change', function() {
                $('#barcode').attr('disabled', false);
            });

            $('#barcode').keyup(function() {
                var val = $('#supplies').val();
                var barcode = $('#barcode').val();

                if (val != "" || barcode != "") {
                    $.ajax({
                        type: "POST",
                        url: "barcode_scanner.php",
                        data: {
                            input: val,
                            barcode: barcode
                        },
                        // dataType: "dataType",
                        success: function(value) {
                            $('#scanbarcode').html(value);
                        }
                    });

                    // alert(barcode);
                } else {
                    $('#scanbarcode').css("display", "none");
                }
            });
        });



   
    </script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js" integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy" crossorigin="anonymous"></script>
</body>


</html>