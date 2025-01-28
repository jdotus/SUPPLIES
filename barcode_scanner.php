<?php include('dbcon.php')?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=3.0">
    <title>Supplies</title>

    <!-- Include jQuery -->
    <link rel="stylesheet" href="style.css">

    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
    </head>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js" integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy" crossorigin="anonymous"></script>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

</head>
<body>
    <?php
    if(isset($_POST['input']) && isset($_POST['barcode']) ) {
        $selectedSupply = htmlspecialchars($_POST['input']); 
        $scanCode = htmlspecialchars($_POST['barcode']); 

        // Prepare the SQL statement
        $stmt = $con->prepare("SELECT * FROM `{$selectedSupply}` WHERE CODE LIKE '{$scanCode}%' OR MODEL LIKE '{$scanCode}%' ");
        $stmt->execute();
        $result = $stmt->get_result();

        if(mysqli_num_rows($result) && $scanCode != "") { ?>
            <div class="table-responsive bg-white">
                <table class="table table-hover table-responsive-md mb-0">
                    <thead>
                        <tr>
                            <th class="h6 fw-bold" scope="col">MODEL</th>
                            <th class="h6 fw-bold" scope="col">DESCRIPTION</th>
                            <th class="h6 fw-bold" scope="col">CODE</th>
                            <th class="h6 fw-bold" scope="col">OWNER</th>
                            <th class="h6 fw-bold" scope="col">TOTAL QUANTITY</th>
                            <th class="h6 fw-bold" scope="col">ACTIONS</th>
                        </tr>
                    </thead>
                    <tbody id="live-updated">
                    <?php while($row = mysqli_fetch_assoc($result)) { 
                        $id = $row['ID'];
                        $model = $row['MODEL'];
                        $description = $row['DESCRIPTION'];
                        $code = $row['CODE'];
                        $owner = $row['OWNER'];
                        $total_quantity = $row['TOTAL_QUANTITY'];
                    ?>
                        <tr data-id="<?php echo $id; ?>" class="item-row">
                            <td class="model"><?php echo $model; ?></td>
                            <td class="description"><?php echo $description; ?></td>
                            <td class="code"><?php echo $code; ?></td>
                            <td class="owner"><?php echo $owner; ?></td>
                            <td class="total_quantity"><?php echo $total_quantity; ?></td>

                            <td>
                                <button class="in" data-bs-toggle="modal" data-bs-target="#staticBackdrop_in" data-id="<?php echo $id; ?>">IN</button>
                                <button class="out" data-bs-toggle="modal" data-bs-target="#staticBackdrop_out" data-id="<?php echo $id; ?>">OUT</button>
                            </td>
                            <!-- Modal IN -->
                            <div class="modal fade" id="staticBackdrop_in" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel_in" aria-hidden="true">
                            <div class="modal-dialog modal-xl">
                                <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="staticBackdropLabel_in">Add Supplies</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <div id="in-result"></div>
                                    <form class="form_modal">
                                    <div class="row"> 
                                        <div class="col-md-3">
                                        <div class="form-group">
                                            <label> Model</label>
                                            <input type="text" name="Model" id="model" class="form-control custom-input" disabled>
                                        </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label> Description</label>
                                                <input type="text" name="Description" id="description" class="form-control custom-input" disabled>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label> Owner</label>
                                                <input type="text" name="Owner" id="owner" class="form-control custom-input" disabled>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label> Code</label>
                                                <input type="text" name="Code" id="code" class="form-control custom-input" disabled>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row"> 
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label> Delivery Date</label><br>
                                                <input type="date" class="date_of_delivery fsorm-control" value="<?php echo date('Y-m-d'); ?>" max="<?php echo date('Y-m-d'); ?>" required>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label> Invoice or DR </label>
                                                <input type="text" name="Code" id="invoice" class="form-control custom-input" required>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label> Quantity</label>
                                                <input type="number" name="Quantity" id="quantity" class="quantity form-control custom-input" required>
                                            </div>
                                        </div>
                                    </div>
                                    </form>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                    <button type="button" class="btn btn-primary btn_in">Add Supplies</button>
                                </div>
                                </div>
                            </div>
                            </div>
    
                            <!-- Modal OUT -->
                            <div class="modal fade" id="staticBackdrop_out" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel_out" aria-hidden="true">
                            <div class="modal-dialog modal-xl">
                                <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="staticBackdropLabel_out">Remove Supplies</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <div id="out-result"></div>
                                    <form class="form_modal">
                                    <div class="row"> 
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label> Model</label>
                                                <input type="text" name="Model" id="model_out" class="form-control custom-input" disabled>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label> Description</label>
                                                <input type="text" name="Description" id="description_out" class="form-control custom-input" disabled>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label> Code</label>
                                                <input type="text" name="Code" id="code_out" class="form-control custom-input" disabled>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label> Owner</label>
                                                <input type="text" name="Owner" id="owner_out" class="form-control custom-input" disabled>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label> Current Quantity</label>
                                                <input type="text" name="Current Quantity" id="current_quantity_out" class="form-control custom-input" disabled>
                                            </div>
                                        </div>
                                    </div> 
                                    <div class="space">
                                        
                                    </div>
                                    <div class="row"> 
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label> Delivery Date</label><br>
                                                <input type="date" class="date_of_delivery_out form-control" value="<?php echo date('Y-m-d'); ?>" max="<?php echo date('Y-m-d'); ?>" required>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label> Quantity</label>
                                                <input type="number" name="Quantity" id="quantity_out" class="quantity form-control custom-input" required>
                                            </div>
                                        </div>
                                        <div class="col-md-3"> 
                                            <div class="form-group">
                                                <label> Client</label>
                                                <input type="text" name="Client_Name" id="client_out" class="client_out form-control custom-input" />
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label> Stock Transfer No.</label>
                                                <input type="text" name="Stock_Transfer" id="stock_transfer_out" class="form-control custom-input" required>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label> Machine Model</label>
                                                <input type="text" name="Stock_Transfer" id="machine_model_out" class="form-control custom-input" require>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label> Machine Serial</label>
                                                <input type="text" name="Stock_Transfer" id="machine_serial_out" class="form-control custom-input" require>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label> Tech Name</label>
                                                <input type="text" name="Stock_Transfer" id="tech_name_out" class="form-control custom-input" require>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="textarea">Barcode</label>
                                                <!-- <textarea require class="form-control barcode" cols="40" rows="3" id="barcode_out" name="textarea"></textarea> -->
                                                <input type="text" name="Barcode" id="barcode_out" class="form-control custom-input" require>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    </form>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                    <button type="button" class="btn btn-danger btn_out">OUT</button>
                                </div>
                                </div>
                            </div>
                            </div>
                        </tr>
                    <?php } ?>  
                    </tbody>
                </table>
            </div>
        <?php
        } else {
            echo('<p class="no-record">NO RECORD</p>');
        }
    }
    ?>

    <script>  
        
        $(document).ready(function() {
            const barcodeInput = document.getElementById('barcode_out');

            barcodeInput.addEventListener('input', () => {
            let inputValue = barcodeInput.value;
            inputValue = inputValue.replace(/[^0-9]/g, ''); // Remove non-numeric characters

            // Add commas and spaces every 8 characters
            const formattedValue = inputValue.match(/.{1,8}/g).join(', '); 
            barcodeInput.value = formattedValue;
            });

            const inputQuantity = document.getElementById("quantity");
            const inputInvoice = document.getElementById("invoice");
            // const stockTransferOut = document.getElementById("stock_transfer_out");
            const numberOnlyRegex = /[^0-9.-]/g; 

            const handleInput = (event) => {
                event.target.value = event.target.value.replace(numberOnlyRegex, '');
            };

            inputQuantity.addEventListener("input", handleInput);
            inputInvoice.addEventListener("input", handleInput);
            // stockTransferOut.addEventListener("input", handleInput);

            $(".client_out").on("keyup", function () {
                var searchTerm = $(this).val().toLowerCase();

                // Debug search term
                console.log("Search Term: " + searchTerm);

                $.ajax({
                    url: "getClientNames.php",
                    type: "GET",
                    data: { searchTerm: searchTerm },
                    success: function (result) {
                        // Clear any previous suggestions
                        $("#suggestions").remove();

                        try {
                            var names = JSON.parse(result);
                            console.log("Result:", result);
                            console.log("Parsed Names:", names);

                            if (names.length > 0) {
                                var suggestionBox = $("<ul id='suggestions'></ul>").css({
                                    border: "1px solid #ccc",
                                    position: "absolute",
                                    zIndex: "500",
                                    maxHeight: "150px",
                                    overflowY: "auto",
                                    backgroundColor: "#fff",
                                    width: $(".client_out").outerWidth(),
                                    listStyleType: "none",
                                    margin: "0",
                                    padding: "0",
                                });

                                names.forEach(function (name) {
                                    var suggestionItem = $("<li></li>")
                                        .text(name)
                                        .css({ padding: "8px", cursor: "pointer" })
                                        .on("click", function () {
                                            $(".client_out").val(name);
                                            $("#suggestions").remove();
                                        });
                                    suggestionBox.append(suggestionItem);
                                });

                                $(".client_out").after(suggestionBox);
                            }
                        } catch (error) {
                            console.error("Error parsing JSON:", error);
                        }
                    },
                    error: function (xhr, status, error) {
                        console.error("AJAX Error:", status, error);
                        alert("Error fetching client names.");
                    },
                });
            });
                                  
        // When the "IN" button is clicked
        $('button.in').click(function() {
            var id = $(this).data('id'); // Get the data-id attribute
            var row = $(this).closest('tr'); // Get the row containing the clicked button
            
            // Populate the modal with the current values of the row
            $('#model').val(row.find('.model').text());
            $('#description').val(row.find('.description').text());
            $('#owner').val(row.find('.owner').text());
            $('#code').val(row.find('.code').text());
            
            // Save the ID in a hidden variable for later use
            $('#staticBackdrop_in').data('id', id);
        });

        // When the "OUT" button is clicked
        $('button.out').click(function() {
            var id = $(this).data('id'); // Get the data-id attribute
            var row = $(this).closest('tr'); // Get the row containing the clicked button
            
            // Populate the modal with the current values of the row
            $('#model_out').val(row.find('.model').text());
            $('#description_out').val(row.find('.description').text());
            $('#owner_out').val(row.find('.owner').text());
            $('#code_out').val(row.find('.code').text());
            $('#current_quantity_out').val(row.find('.total_quantity').text());

            // Save the ID in a hidden variable for later use
            $('#staticBackdrop_out').data('id', id);
        });
        
        // IN ADD SUPPLIES
        $('.btn_in').click(function() {
        
        // Get the item ID stored in the modal's data-id
        var id = $('#staticBackdrop_in').data('id');
        var quantity = $('#quantity').val();  // Get the entered quantity
        var deliveryDate = $('.date_of_delivery').val();  // Get the delivery date

        // Get other item details from the modal (for example, the model, description, etc.)
        var model = $('#model').val();
        var description = $('#description').val();
        var code = $('#code').val();
        var owner = $('#owner').val();
        var invoice = $('#invoice').val();
        var selectedSupply = '<?php echo $selectedSupply; ?>'; 
       
        // Validation to check if quantity is a valid number greater than 0
        if (quantity > 0 && invoice > 0) {
            var isTrue = confirm("Are you sure about that?");
            if (isTrue) {
                // AJAX request to update the database
                $.ajax({
                    type: "POST",
                    url: "in.php",  // PHP file to handle the request
                    data: {
                        id: id,  // Pass the item ID
                        quantity: quantity,
                        selectedSupply: selectedSupply,
                        invoice: invoice,
                        code: code,
                        owner: owner,
                        model: model,
                        description: description,
                        date_of_delivery: deliveryDate
                    },
                    success: function(response) {

                        // If you want to display the server's response in an element with id "in-result"
                        $('#in-result').html(response); // Display the server's response
                        // Find the row corresponding to the clicked item by its ID
                        var $row = $('tr[data-id="' + id + '"]');

                        // Update the quantity in the row
                        var currentQuantity = parseInt($row.find('.total_quantity').text());
                        var newTotalQuantity = currentQuantity + parseInt(quantity);
                        $row.find('.total_quantity').text(newTotalQuantity);  // Update the quantity column

                        // Optionally, you can reset or clear input fields here if needed
                        $('#quantity').val('');  // Clear the quantity input in the modal
                        $('#invoice').val('');
                    },
                    error: function() {
                        alert("There was an error updating the quantity.");
                    }
                });
            }
        } else {
            alert("Please enter a valid data.");
        }
        });
        
         

        $('.btn_out').click(function () {
            var id = $('#staticBackdrop_out').data('id'); // Get the item ID stored in the modal's data-id
            var quantity = $('#quantity_out').val(); // Get the entered quantity
            var deliveryDate = $('.date_of_delivery_out').val(); // Get the delivery date

            // Get other item details from the modal
            var model = $('#model_out').val();
            var description = $('#description_out').val();
            var owner = $('#owner_out').val();
            var code = $('#code_out').val();

            var client = $('#client_out').val();
            var stockTransfer = $('#stock_transfer_out').val();
            var machineModel = $('#machine_model_out').val();
            var machineSerial = $('#machine_serial_out').val();
            var techName = $('#tech_name_out').val();
            var barcode = $('#barcode_out').val();
            var selectedSupply = '<?php echo $selectedSupply; ?>'; 

            var $row = $('tr[data-id="' + id + '"]'); // Select the specific row
            var total_quantity = parseInt($row.find('.total_quantity').text()); // Get the total quantity from the table

            // Validation to check if quantity is a valid number greater than 0
            if (quantity > 0 && quantity <= total_quantity) {
                var isTrue = confirm("Are you sure you want to remove this quantity?");
                if (isTrue) {
                    // AJAX request to update the database
                    $.ajax({
                        type: "POST",
                        url: "out.php", // PHP file to handle the request
                        data: {
                            id: id,
                            quantity: quantity,
                            barcode: barcode,
                            code: code,
                            client: client,
                            total_quantity: total_quantity,
                            selectedSupply: selectedSupply,
                            stockTransfer: stockTransfer,
                            machineModel: machineModel,
                            machineSerial: machineSerial,
                            techName: techName,
                            owner: owner,
                            model: model,
                            description: description,
                            date_of_delivery: deliveryDate,
                        },
                        success: function (response) {
                            // Assuming the server returns the updated total_quantity as plain text
                            var newTotalQuantity = parseInt(response.trim());
                            $('#out-result').html(response);
                            if (!isNaN(newTotalQuantity)) {
                                // Update the total quantity in the table
                                $row.find('.total_quantity').text(newTotalQuantity);

                                // Clear and reset modal fields
                                $('#quantity_out').val('');
                                $('#client_out').val('');
                                $('#stock_transfer_out').val('');
                                $('#machine_model_out').val('');
                                $('#machine_serial_out').val('');
                                $('#barcode_out').val('');
                                $('#tech_name_out').val('');

                                // Close the modal
                                $('#staticBackdrop_out').modal('hide');
                            } else {
                                alert("Invalid server response. Please try again.");
                            }
                        },
                        error: function () {
                            alert("An error occurred during the AJAX request.");
                        },
                    });
                }
            } else {
                alert("Please enter a valid quantity.");
            }
        });


    });
    </script>
</body>
</html>
