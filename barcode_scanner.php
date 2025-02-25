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
    
    <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js" integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy" crossorigin="anonymous"></script>
    
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    
    <!-- Data Tables -->
    <script src="https://cdn.datatables.net/2.2.1/js/dataTables.min.js"></script>
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/2.2.1/css/dataTables.dataTables.min.css">
    
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
                                                <input type="date" class="date_of_delivery fsorm-control" value="<?php echo date('Y-m-d'); ?>"required>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label> Invoice or DR </label>
                                                <input type="text" name="Code" id="invoice" class="form-control custom-input">
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
                                    <button type="button" class="btn btn-primary btn_in" >IN</button>
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
                                                <input type="date" class="date_of_delivery_out form-control" value="<?php echo date('Y-m-d'); ?>" required>
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

            $(".client_out").on("keyup", function () {
                var searchTerm = $(this).val().toLowerCase();

                console.log("Search Term: " + searchTerm);

                $.ajax({
                    url: "getClientNames.php",
                    type: "GET",
                    data: { searchTerm: searchTerm },
                    success: function (result) {
                        $("#suggestions").remove();

                        console.log("Server Response:", result);

                        var names = JSON.parse(result);

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
                                    .text(name.trim())
                                    .css({ padding: "8px", cursor: "pointer" })
                                    .on("click", function (e) {
                                        e.stopPropagation();
                                        $(".client_out").val(name.trim());
                                        $("#suggestions").remove();
                                    });
                                suggestionBox.append(suggestionItem);
                            });

                            $(".client_out").after(suggestionBox);

                            $(document).on("click", function () {
                                $("#suggestions").remove();
                            });
                        }
                    },
                    error: function (xhr, status, error) {
                        console.error("AJAX Error:", status, error);
                        alert("Error fetching client names.");
                    },
                });
            });

            $('.table').DataTable( {
                searching: false
            });
            $('.dataTables_length label').contents().filter(function(){
                return this.nodeType === 3; //Node.TEXT_NODE
            }).remove();

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
        $('.btn_in').click(function () {
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

            // Perform client-side validation
            var errors = [];

            if (!quantity || isNaN(quantity) || parseInt(quantity) <= 0) {
                errors.push("Please enter a valid quantity greater than 0.");
            }
            // if (!invoice || isNaN(invoice) || parseInt(invoice) <= 0) {
            //     errors.push("Please enter a valid invoice number greater than 0.");
            // }
            if (!deliveryDate) {
                errors.push("Please select a delivery date.");
            }
            if (!model) {
                errors.push("Model field cannot be empty.");
            }
            if (!description) {
                errors.push("Description field cannot be empty.");
            }

            if (errors.length > 0) {
                
                $('#in-result').html(
                '<div class="alert alert-warning alert-dismissible fade show" role="alert">' +
                '<strong>Ohhh no!</strong> ' + errors.join("<br>") +
                '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>' +
                '</div>' 
                );
                return;
            }

            var isTrue = confirm("Are you sure about that?");
            if (isTrue) {
                // AJAX request
                $.ajax({
                    type: "POST",
                    url: "in.php",
                    data: {
                        id: id,
                        quantity: quantity,
                        selectedSupply: selectedSupply,
                        invoice: invoice,
                        code: code,
                        owner: owner,
                        model: model,
                        description: description,
                        date_of_delivery: deliveryDate,
                    },
                    success: function (response) {
                        if (response.trim() === "success") {

                            // Update quantity in the table
                            var $row = $('tr[data-id="' + id + '"]');
                            var currentQuantity = parseInt($row.find('.total_quantity').text());
                            var newTotalQuantity = currentQuantity + parseInt(quantity);
                            $row.find('.total_quantity').text(newTotalQuantity);

                            // Clear input fields
                            $('#quantity').val('');
                            $('#invoice').val('');

                            $('#in-result').html(
                            '<div class="alert alert-success alert-dismissible fade show" role="alert">' +
                            '<strong>Success!</strong> Data Inserted to <strong>Model: ' + model + '</strong>, <strong>Quantity: ' + quantity  +
                            '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>' +
                            '</div>' 
                            );
                        } else {
                            alert("Error: " + response.trim());
                        }
                    },
                    error: function () {
                        alert("There was an error processing the request.");
                    },
                });
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

            // Perform client-side validation 
            var errors = [];
            alert(client);
            alert(total_quantity);
            if (!quantity || isNaN(quantity) || parseInt(quantity) <= 0) {
                errors.push("Please enter a valid quantity greater than 0.");
            }
            if (!barcode || isNaN(barcode) || parseInt(barcode) <= 0) {
                errors.push("Please enter a valid barcode number greater than 0.");
            }
            if (!techName ||techName == "") {
                errors.push("Please enter a valid Technician Name ");
            }
            if (!machineSerial || machineSerial == "") {
                errors.push("Please enter a valid Machine Serial number greater than 0.");
            }
            if (!machineModel || machineModel == "") {
                errors.push("Please enter a valid Machine Model.");
            }
            if (client == "") {
                errors.push("Please enter a valid Client Name.");
            }
            if (!stockTransfer || stockTransfer == "") {
                errors.push("Please enter a valid Stock Transfer.");
            }
            if (!deliveryDate) {
                errors.push("Please select a delivery date.");
            }
            if (!model) {
                errors.push("Model field cannot be empty.");
            }
            if (!description) {
                errors.push("Description field cannot be empty.");
            }

            if (errors.length > 0) {
                
                $('#out-result').html(
                '<div class="alert alert-warning alert-dismissible fade show" role="alert">' +
                '<strong>Ohhh no!</strong> ' + errors.join("<br>") +
                '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>' +
                '</div>' 
                );
                return;
            }

            // Validation to check if quantity is a valid number greater than 0
            
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

                            var result = JSON.parse(response);
                            if (result.status === "success") {
                                $('#out-result').html(
                                    '<div class="alert alert-success alert-dismissible fade show" role="alert">' +
                                    '<strong>Success!</strong> ' + result.message +
                                    '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>' +
                                    '</div>'
                                );

                                // Save the ID in a hidden variable for later use
                                $('#staticBackdrop_out').data('id', id);
                                
                                var $row = $('tr[data-id="' + id + '"]');
                                var currentQuantity = parseInt($row.find('.total_quantity').text());
                                var newTotalQuantity = currentQuantity - parseInt(quantity);
                                $row.find('.total_quantity').text(newTotalQuantity);
                                
                                // Populate the modal with the current values of the row
                                $('#current_quantity_out').val(newTotalQuantity).text();
                                
                                 // Clear and reset modal fields
                                $('#quantity_out').val('');
                                $('#client_out').val('');
                                $('#stock_transfer_out').val('');
                                $('#machine_model_out').val('');
                                $('#machine_serial_out').val('');
                                $('#barcode_out').val('');
                                $('#tech_name_out').val('');
                                
                            } else {
                                $('#out-result').html(
                                    '<div class="alert alert-danger alert-dismissible fade show" role="alert">' +
                                    '<strong>Error:</strong> ' + result.message +
                                    '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>' +
                                    '</div>'
                                );
                            }
                        },
                        error: function () {
                            alert("An error occurred during the AJAX request.");
                        },
                    });
                }
        });
    });
    </script>
</body>
</html>
