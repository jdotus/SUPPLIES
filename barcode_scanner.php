<?php include('dbcon.php')?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=3.0">
    <title>Supplies</title>

    <link rel="stylesheet" href="style.css">
    
    <!-- Include jQuery -->
    <script src="https://code.jquery.com/jquery-3.7.3.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>
<body>
    <?php
    if(isset($_POST['input']) && isset($_POST['barcode'])) {
        $selectedSupply = htmlspecialchars($_POST['input']); 
        $scanCode = htmlspecialchars($_POST['barcode']); 
        
        // Prepare the SQL statement
        $stmt = $con->prepare("SELECT * FROM `{$selectedSupply}` WHERE CODE LIKE ?");
        $scanCodeWithWildcard = $scanCode . "%";
        $stmt->bind_param("s", $scanCodeWithWildcard); 
        $stmt->execute();
        $result = $stmt->get_result();

        if(mysqli_num_rows($result) && $scanCode != "") { ?>
            <div id="in-result"></div>
            <div id="out-result"></div>
            <div class="table-responsive bg-white">
                <table class="table table-hover table-responsive-md mb-0">
                    <thead>
                        <tr>
                            <th class="h6 fw-bold" scope="col">MODEL</th>
                            <th class="h6 fw-bold" scope="col">DESCRIPTION</th>
                            <th class="h6 fw-bold" scope="col">CODE</th>
                            <th class="h6 fw-bold" scope="col">OWNER</th>
                            <th class="h6 fw-bold" scope="col">DATE OF DELIVER</th>
                            <th class="h6 fw-bold" scope="col">TOTAL QUANTITY</th>
                            <th class="h6 fw-bold" scope="col">QUANTITY</th>
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
                        <tr data-id="<?php echo $id; ?>">
                            <td class="model"><?php echo $model; ?></td>
                            <td class="description"><?php echo $description; ?></td>
                            <td class="code"><?php echo $code; ?></td>
                            <td class="owner"><?php echo $owner; ?></td>
                            <td><input type="date" class="date_of_delivery" value="<?php echo date('Y-m-d'); ?>" max="<?php echo date('Y-m-d'); ?>" require></td>
                            <td class="total_quantity"><?php echo $total_quantity; ?></td>
                            <td><input type="number" class="quantity-input" min="1" require></td>
                            <td>
                                <button class="in" >IN</button>
                                <button class="out" >OUT</button>
                            </td>
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

        // IN Button Click Handler
        $('.in').on('click', function() {
            var $row = $(this).closest('tr'); // Get the closest row
            var quantity = $row.find('.quantity-input').val(); 
            var code = $row.find('.code').text(); 
            var owner = $row.find('.owner').text(); 
            var model = $row.find('.model').text(); 
            var description = $row.find('.description').text();
            var date_of_delivery = $row.find('.date_of_delivery').val(); 
            
            // Validation
            if (quantity > 0) {
              var isTrue = confirm("Are you sure about that?");
                if (isTrue) {
                    $.ajax({
                        type: "POST",
                        url: "in.php", // Handle the request with this file
                        data: {
                            quantity: quantity,
                            code: code,
                            owner: owner,
                            model: model,
                            description: description,
                            date_of_delivery: date_of_delivery
                        },
                        success: function(response) {
                            alert("Quantity updated successfully!");
                            $('#in-result').html(response);

                            // Update the quantity column for this row
                            var newTotalQuantity = parseInt($row.find('.total_quantity').text()) + parseInt(quantity);
                            $row.find('.total_quantity').text(newTotalQuantity); // Update the quantity column
                            $row.find('.quantity-input').val('');

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

        // OUT Button Click Handler
        $('.out').on('click', function() {
            var $row = $(this).closest('tr'); // Get the closest row
            var quantity = $row.find('.quantity-input').val(); 
            var total_quantity = $row.find('.total_quantity').text();
            var code = $row.find('.code').text(); 
            var owner = $row.find('.owner').text(); 
            var model = $row.find('.model').text(); 
            var description = $row.find('.description').text();
            var date_of_delivery = $row.find('.date_of_delivery').val(); 
            
            // Validation
            if (quantity > 0 && quantity ) {
                var isTrue = confirm("Are you sure about that?");
                if (isTrue) {
                    $.ajax({
                        type: "POST",
                        url: "out.php", // Handle the request with this file
                        data: {
                            quantity: quantity,
                            code: code,
                            owner: owner,
                            model: model,
                            description: description,
                            date_of_delivery: date_of_delivery
                        },
                        success: function(response) {
                            alert("Quantity updated successfully!");
                            $('#out-result').html(response);
                            

                            if(total_quantity >= quantity) {
                                // Update the quantity column for this row
                                var newTotalQuantity = parseInt($row.find('.total_quantity').text()) - parseInt(quantity);
                                $row.find('.total_quantity').text(newTotalQuantity); // Update the quantity column
                                $row.find('.quantity-input').val('');
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
