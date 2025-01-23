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

    <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js" integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy" crossorigin="anonymous"></script>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

</head>
<body>
    <?php
    if(isset($_POST['input']) && isset($_POST['barcode'])) {
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
                        </tr>

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
                                    <div class="col-md-5">
                                    <div class="form-group">
                                        <label> Model</label>
                                        <input type="text" name="Model" id="model" class="form-control custom-input" disabled>
                                    </div>
                                    </div>
                                    <div class="col-md-5">
                                    <div class="form-group">
                                        <label> Delivery Date</label><br>
                                        <input type="date" class="date_of_delivery fsorm-control" value="<?php echo date('Y-m-d'); ?>" max="<?php echo date('Y-m-d'); ?>" required>
                                    </div>
                                    </div>
                                    <div class="col-md-5">
                                    <div class="form-group">
                                        <label> Description</label>
                                        <input type="text" name="Description" id="description" class="form-control custom-input" disabled>
                                    </div>
                                    </div>
                                    <div class="col-md-5">
                                    <div class="form-group">
                                        <label> Quantity</label>
                                        <input type="number" name="Quantity" id="quantity" class="quantity form-control custom-input" required>
                                    </div>
                                    </div>
                                    
                                </div>
                                <div class="row"> 
                                    <div class="col-md-5">
                                    <div class="form-group">
                                        <label> Code</label>
                                        <input type="text" name="Code" id="code" class="form-control custom-input" disabled>
                                    </div>
                                    </div>
                                    <div class="col-md-5">
                                    <div class="form-group">
                                        <label> Invoice</label>
                                        <input type="text" name="Code" id="invoice" class="form-control custom-input" required>
                                    </div>
                                    </div>
                                    
                                </div>
                                <div class="row"> 
                                    <div class="col-md-5">
                                    <div class="form-group">
                                        <label> Owner</label>
                                        <input type="text" name="Owner" id="owner" class="form-control custom-input" disabled>
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
                                    <div class="col-md-5">
                                        <div class="form-group">
                                            <label> Model</label>
                                            <input type="text" name="Model" id="model_out" class="form-control custom-input" disabled>
                                        </div>
                                    </div>
                                    <div class="col-md-5">
                                        <div class="form-group">
                                            <label> Delivery Date</label><br>
                                            <input type="date" class="date_of_delivery_out form-control" value="<?php echo date('Y-m-d'); ?>" max="<?php echo date('Y-m-d'); ?>" required>
                                        </div>
                                    </div>
                                    <div class="col-md-5">
                                        <div class="form-group">
                                            <label> Code</label>
                                            <input type="text" name="Code" id="code_out" class="form-control custom-input" disabled>
                                        </div>
                                    </div>
                                    <div class="col-md-5"> 
                                        <div class="form-group">
                                            <label> Client</label>
                                            <input type="text" name="client" id="client_out" class="form-control custom-input" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="row"> 
                                    <div class="col-md-5">
                                        <div class="form-group">
                                            <label> Description</label>
                                            <input type="text" name="Description" id="description_out" class="form-control custom-input" disabled>
                                        </div>
                                    </div>
                                    <div class="col-md-5">
                                        <div class="form-group">
                                            <label> Quantity</label>
                                            <input type="number" name="Quantity" id="quantity_out" class="quantity form-control custom-input" required>
                                        </div>
                                    </div>
                                    <div class="col-md-5">
                                        <div class="form-group">
                                            <label> Owner</label>
                                            <input type="text" name="Owner" id="owner_out" class="form-control custom-input" disabled>
                                        </div>
                                    </div>
                                    <div class="col-md-5">
                                        <div class="form-group">
                                            <label> Stock Transfer No.</label>
                                            <input type="text" name="Stock_Transfer" id="stock_transfer" class="form-control custom-input" require>
                                        </div>
                                    </div>
                                </div>
                                
                                </form>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                <button type="button" class="btn btn-danger btn_out">Remove Supplies</button>
                            </div>
                            </div>
                        </div>
                        </div>
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
            const inputQuantity = document.getElementById("quantity");
            const inputInvoice = document.getElementById("invoice");

            const numberOnlyRegex = /[^0-9.-]/g; 

            const handleInput = (event) => {
            event.target.value = event.target.value.replace(numberOnlyRegex, '');
            };

            inputQuantity.addEventListener("input", handleInput);
            inputInvoice.addEventListener("input", handleInput);

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
        
         
        // OUT REMOVE SUPPLIES
        $('.btn_out').click(function() {
            var id = $('#staticBackdrop_out').data('id'); // Get the item ID stored in the modal's data-id
            var quantity = $('#quantity_out').val(); // Get the entered quantity
            var deliveryDate = $('.date_of_delivery_out').val(); // Get the delivery date
            
            // Get other item details from the modal (for example, the model, description, etc.)
            var model = $('#model_out').val();
            var description = $('#description_out').val();
            var code = $('#code_out').val();
            var owner = $('#owner_out').val();
            var selectedSupply = '<?php echo $selectedSupply; ?>'; 
            var $row = $('tr[data-id="' + id + '"]');
            var total_quantity = $row.find('.total_quantity').text(); // Get the total quantity from the table


            // Validation to check if quantity is a valid number greater than 0
            if (quantity > 0) {
                var isTrue = confirm("Are you sure you want to remove this quantity?");
                if (isTrue) {
                    // AJAX request to update the database
                    $.ajax({
                        type: "POST",
                        url: "out.php",  // PHP file to handle the request
                        data: {
                            id: id,  // Pass the item ID
                            quantity: quantity,
                            selectedSupply: selectedSupply,
                            total_quantity: total_quantity,
                            code: code,
                            owner: owner,
                            model: model,
                            description: description,
                            date_of_delivery: deliveryDate
                        },
                        success: function(response) {
                            // Display the server's response in the 'out-result' div
                            $('#out-result').html(response);
    
                            // Check if the total quantity is greater than or equal to the quantity to be removed
                            if (total_quantity >= quantity) {
                                // Calculate the new total quantity after removal
                                var newTotalQuantity = parseInt($row.find('.total_quantity').text()) - parseInt(quantity);
                                $row.find('.total_quantity').text(newTotalQuantity); // Update the quantity column in the table

                                // Clear the input field in the modal after successful removal
                                $('#quantity_out').val('');
                                $('#client_out').val('');
                            } 
                        },
                        error: function() {
                            alert("There was an error updating the quantity.");
                        }
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
