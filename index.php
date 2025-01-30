<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Supplies</title>
    <link rel="stylesheet" href="style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>

<body>

    <div class="search_container">
        <form class="search">
            <label for="supplies">SELECT SUPPLY</label><br>
            <select name="supplies" id="supplies" required>
                <option value="default">SELECT</option>
                <option value="toner">Toner</option>
                <option value="drum">Drum</option>
            </select>
            <input type="text" id="barcode" required onkeypress="return (event.charCode != 13);" autocomplete="off"> 
        </form>
    </div>

    <div class="add_supplies">
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#staticBackdrop">
            Add Supplies
        </button>
        <a href="record.php" target="_blank">
            <button type="button" class="btn btn-primary">View History</button>
        </a>
    </div>

    <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="staticBackdropLabel">Add Supplies</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
    <form class="form_modal" id="addSupplyForm"> 
        <div class="form-group">
            <label> Supply</label>
            <select name="addsupply" id="addsupply" required>
                <option value="toner">Toner</option>
                <option value="drum">Drum</option>
            </select>
        </div>
        <div class="form-group ">
            <label> Model</label> 
            <input type="text" name="addModel" class="form-control custom-input" placeholder="Model"> 
        </div>
        <div class="form-group">
            <label> Description</label>
            <input type="text" name="addDescription" class="form-control custom-input" placeholder="Description">
        </div>
        <div class="form-group">
            <label> Owner</label>
            <input type="text" name="addOwner" class="form-control custom-input" placeholder="Owner">
        </div>
        <div class="form-group">
            <label> Code</label>
            <input type="text" name="addCode" class="form-control custom-input" placeholder="Code">
        </div>
        <div class="form-group">
            <label> Quantity</label>
            <input type="text" name="addQuantity" class="form-control custom-input" placeholder="Quantity">
        </div>
    </form>
</div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" id="importexcel">Import Excel File</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="addSupplyBtn">Add Supplies</button>
                </div>
            </div>
        </div>
    </div>

    <div id="scanbarcode" class="result_container"></div>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js" integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy" crossorigin="anonymous"></script>

    <script>
        $(document).ready(function() {

            $('#barcode').prop('disabled', true);

            $('#supplies').on('change', function() {
                var selectedSupply = $(this).val();

                if (selectedSupply == 'default') {
                    $('#barcode').prop('disabled', true);
                } else {
                    $('#barcode').prop('disabled', false);
                }
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
                        success: function(value) {
                            $('#scanbarcode').html(value);
                        }
                    });
                } else {
                    $('#scanbarcode').css("display", "none");
                }
            });


            $('#addSupplyBtn').on('click', function (event) {
    event.preventDefault(); // Prevent default form submission behavior

    // Disable the button to prevent multiple submissions
    const $button = $(this);
    $button.prop('disabled', true);

    $.ajax({
        type: "POST",
        url: "addsupplies.php",
        data: $('#addSupplyForm').serialize(),
        success: function (response) {
            alert(response); // Display success/error message from the server

            // Only reset the form and hide the modal if the response indicates success
            if (response.includes("successfully")) {
                $('#addSupplyForm')[0].reset(); // Reset the form
                $('#staticBackdrop').modal('hide'); // Hide modal on success
            }
            // For errors, the modal remains open
        },
        error: function () {
            alert('An error occurred while adding the supply.');
        },
        complete: function () {
            // Re-enable the button after the request completes
            $button.prop('disabled', false);
        }
    });
});

// Focus management when modal closes
$('#staticBackdrop').on('hidden.bs.modal', function () {
    $('#addSupplyBtn').focus(); // Return focus to the triggering button
});

        });

        document.getElementById("importexcel").addEventListener("click", function() {
            window.open("/dashboard/supplies/excel/excel.php", "_blank"); 
        });

    </script>
</body>
</html>