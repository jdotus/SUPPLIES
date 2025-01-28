<?php 
include ('dbcon.php');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=3.0">
    <title>Supplies</title>

    <link rel="stylesheet" href="style.css">
    
    <script src="https://code.jquery.com/jquery-3.2.3.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>

    <script src="https://code.jquery.com/jquery-3.7.3.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.33.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js" integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy" crossorigin="anonymous"></script>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

</head>
<body>
    <?php
if(isset($_POST['input']) && isset($_POST['barcode'])){

    // 2. Get user input (Sanitize input to prevent XSS)
    $selectedSupply = htmlspecialchars($_POST['input']); 
    $scanCode = htmlspecialchars($_POST['barcode']); 
    
    // 3. Prepare the SQL statement (using prepared statements to prevent SQL injection)
    $stmt = $con->prepare("SELECT * FROM `{$selectedSupply}` WHERE CODE LIKE ?");
    $scanCodeWithWildcard = $scanCode . "%"; // Add "%" to the end of $scanCode
    $stmt->bind_param("s", $scanCodeWithWildcard); 

    // 4. Execute the statement
    $stmt->execute();
    $result = $stmt->get_result();

    if(mysqli_num_rows($result) && $scanCode != "") {?>
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
                    <tbody>
                    
                    <?php
                      while($row = mysqli_fetch_assoc($result)) {
                        $model = $row['MODEL'];
                        $description =$row['DESCRIPTION'];
                        $code = $row['CODE'];
                        $owner = $row['OWNER'];
                        $total_quantity = $row['TOTAL_QUANTITY'];
                        ?>

                        <tr>
                            <td id="model" class="model"><?php echo $model; ?></td>
                            <td id="description" class="description"><?php echo $description; ?></td>
                            <td id="code" class="code"><?php echo $code; ?></td>
                            <td id="owner" class="owner"><?php echo $owner;?></td>
                            <td><input type="date" id="date_of_delivery" name="date_of_delivery" 
                                        value="<?php echo date('Y-m-d'); ?>" 
                                        max="<?php echo date('Y-m-d'); ?>" 
                                        required>
                            </td>
                            <td id="total_quantity" class="total_quantity"><?php echo $total_quantity;?></td>
                            <td><input type="number" name="qunatity" id="quantity" class="quantity-input"></td>
                            <td><button class="in" id="in">IN</button><button class="out" id="out">OUT</button></td>
                        </tr>
                        </tbody>
                        <?php
                      }
                ?>  
                </table>
                </div>

                <?php
        
    }else {
        echo('<p class="no-record">NO RECORD</p>');
    }
}   
?>

  <script>
    $(document).ready(function() {
      // IN
      $('#in').on('click',function() {
        var quantity = $('#quantity').val();
        var code = $('#code').text();
        var owner = $('#owner').text();
        var model = $('#model').text();
        var description = $('#description').text();
        var date_of_delivery = $('#date_of_delivery').val();
        var isTrue = confirm("Are you sure about that?");

        
        if(isTrue) {
          
          $.ajax({
            type: "POST",
            url: "in.php",
            data: {
              quantity: quantity,
              code: code,
              owner: owner,
              model: model,
              description: description,
              date_of_delivery: date_of_delivery
            },
            // dataType: "dataType",
            success: function (value) {
              $('#in-result').html(value);
            }
          });
        }
      });  

      // OUT
      $('#out').on('click', function() {
        var quantity = $('#quantity').val();
        var code = $('#code').text();
        var owner = $('#owner').text();
        var model = $('#model').text();
        var description = $('#description').text();
        var date_of_delivery = $('#date_of_delivery').val();
        var isTrue = confirm("Are you sure about that?");

        if(isTrue) {
          $.ajax({
            type: "POST",
            url: "out.php",
            data: {
              quantity: quantity,
              code: code,
              owner: owner,
              model: model,
              description: description,
              date_of_delivery: date_of_delivery
            },
            // dataType: "dataType",
            success: function (value) {
              $('#out-result').html(value);
            }
          });
        }
        
      });
    });

  </script>
    </body>
</html>

<?php
// Check if POST data is set
if (isset($_POST['inputText'])) {
    $enteredValue = $_POST['inputText'];

    // Query the database to verify the value exists
    $sql = "SELECT COUNT(*) as count FROM dropdown_table WHERE value = ?";
    $stmt = $con->prepare($sql);
    $stmt->bind_param("s", $enteredValue);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result) {
        $row = $result->fetch_assoc();
        if ($row['count'] > 0) {
            echo "Valid input: $enteredValue"; // Proceed with processing
        } else {
            echo "Invalid input. Please choose a valid option.";
        }
    } else {
        echo "Error verifying input: " . $con->error;
?>

<form id="myForm" method="POST" action="your_php_file.php">
<label for="textInput">Enter an option:</label>
<input type="text" id="textInput" name="inputText" list="validOptions" required>
<datalist id="validOptions">
    <option value="Option1">
    <option value="Option2">
    <option value="Option3">
</datalist>
<button type="submit">Submit</button>
</form>

<script>
document.getElementById('myForm').addEventListener('submit', function (event) {
    const textInput = document.getElementById('textInput');
    const validOptions = ["Option1", "Option2", "Option3"]; // Predefined valid options

    if (!validOptions.includes(textInput.value)) {
        event.preventDefault(); // Prevent form submission
        alert('Please select a valid option from the list.');
    }


// $sql1 = "UPDATE `{$selectedSupply}` SET TOTAL_QUANTITY = ? WHERE CODE = ?";
                    // $stmnt = $con->prepare($sql1);
                    // $stmnt->bind_param("is", $totalResult, $code); // Assuming $totalResult is an integer
                    // $stmnt->execute();

                    // $sql = "SELECT * FROM client_names WHERE client_name = ?";
                    // $stmt = $con->prepare($sql);
                    // $stmt->bind_param("s", $client);
                    // $stmt->execute();
                    // $result = $stmt->get_result();
                  
    
                    // if ($stmnt->affected_rows > 0 && $result) {?>
                    
                    // <div class="alert alert-success alert-dismissible fade show" role="alert">
                    //     <strong>Success!</strong> Data Updated!
                    //     <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    // </div>  
                    // <?php
                    //     $currentDate = date("Y-m-d"); // Use the SQL date format (YYYY-MM-DD)
                    //     $sql2 = "INSERT INTO delivery_out 
                    //         (date, model, description, code, date_of_delivery, barcode, quantity, client, machine_model, machine_serial, tech_name, stock_transfer) 
                    //         VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
                    //     $stmnt3 = $con->prepare($sql2);
                        
                    //     // Corrected data types and variables
                    //     $stmnt3->bind_param(
                    //         "ssssssssssss",  // s = string, i = integer
                    //         $currentDate, 
                    //         $model, 
                    //         $description, 
                    //         $code, 
                    //         $date_of_delivery, 
                    //         $barCode,  // Assuming barcode is a string; adjust if necessary
                    //         $quantity,  // Assuming quantity is an integer
                    //         $client, 
                    //         $machineModel, 
                    //         $machineSerial, 
                    //         $techName, 
                    //         $stockTransfer
                    //     );
                    //     $stmnt3->execute();
                    //     $stmnt3->close();

                    // } else { ?>
                    // <div class="alert alert-warning alert-dismissible fade show" role="alert">
                    //     <strong>Ohhh no!</strong> NO RECORD FOUND
                    //     <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    // </div>  
                    // <?php
                    // }
    




                    
                    <?php

                    // Show alert function
                    function showAlert($type, $message) {
                        echo "<div class='alert alert-$type alert-dismissible fade show' role='alert'>
                                <strong>$type:</strong> $message
                                <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                            </div>";
                    }
            
                    // Check if required POST variables exist
                    if (isset($_POST["quantity"]) && isset($_POST["code"]) && isset($_POST["owner"]) && isset($_POST["selectedSupply"]) && isset($_POST["total_quantity"])) {
                        // Sanitize POST data
                        $selectedSupply = htmlspecialchars($_POST['selectedSupply']);
                        $total_quantity = (int)$_POST['total_quantity'];
                        $quantity = (int)$_POST['quantity'];
                        $code = htmlspecialchars($_POST['code']);
                        $owner = htmlspecialchars($_POST['owner']);
                        $model = htmlspecialchars($_POST['model']);
                        $description = htmlspecialchars($_POST['description']);
                        $date_of_delivery = date("Y-m-d", strtotime($_POST['date_of_delivery'])); // Use SQL date format
                        $barCode = htmlspecialchars($_POST['barcode']);
                        $stockTransfer = htmlspecialchars($_POST['stockTransfer']);
                        $machineModel = htmlspecialchars($_POST['machineModel']);
                        $machineSerial = htmlspecialchars($_POST['machineSerial']);
                        $techName = htmlspecialchars($_POST['techName']);
                        $client = htmlspecialchars($_POST['client']);
            
                        // Validate the quantity and stock
                        if ($quantity > 0 && $total_quantity >= $quantity) {
                            // Check current stock of the item
                            $sql = "SELECT TOTAL_QUANTITY FROM `{$selectedSupply}` WHERE CODE = ?";
                            $stmt2 = $con->prepare($sql);
                            $stmt2->bind_param("s", $code);
                            $stmt2->execute();
                            $stmt2->bind_result($currentQuantity);
            
                            // Check if the current stock is sufficient
                            if ($stmt2->fetch()) {
                                $totalResult = $currentQuantity - $quantity;
            
                                // Check if stock is sufficient
                                if ($totalResult < 0) {
                                    showAlert("danger", "Quantity exceeds available stock. Current stock: " . $currentQuantity);
                                    $stmt2->close();
                                    exit;
                                }
                            } else {
                                // If no stock is found, set the result to the quantity
                                $totalResult = $quantity;
                            }
            
                            $stmt2->close();
                            echo($client);
                            // Step 1: Check if the client name exists in the database
                            $sqlCheckClient = $con->prepare("SELECT client_name FROM client_names WHERE client_name = ?");
                            $sqlCheckClient->bind_param("s", $client);
                            $sqlCheckClient->execute();
                            $resultCheckClient = $sqlCheckClient->get_result();
            
                            // If the client exists, proceed with updating stock
                            if ($resultCheckClient->num_rows > 0) {
                                // Step 2: Update TOTAL_QUANTITY in the selected supply table
                                $sql1 = "UPDATE `{$selectedSupply}` SET TOTAL_QUANTITY = ? WHERE CODE = ?";
                                $stmt1 = $con->prepare($sql1);
            
                                if ($stmt1) {
                                    $stmt1->bind_param("is", $totalResult, $code);
                                    $stmt1->execute();
                                    $updateAffectedRows = $stmt1->affected_rows;
            
                                    // If rows were updated successfully
                                    if ($updateAffectedRows > 0) {
                                        showAlert("success", "Total Quantity Updated!");
            
                                        // Step 3: Insert into the delivery_out table
                                        $currentDate = date("Y-m-d"); // SQL date format
                                        $sql2 = "INSERT INTO delivery_out 
                                                (date, model, description, code, date_of_delivery, barcode, quantity, client, machine_model, machine_serial, tech_name, stock_transfer) 
                                                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            
                                        $stmt2 = $con->prepare($sql2);
                                        if ($stmt2) {
                                            $stmt2->bind_param(
                                                "ssssssssssss", 
                                                $currentDate, 
                                                $model, 
                                                $description, 
                                                $code, 
                                                $date_of_delivery, 
                                                $barCode, 
                                                $quantity, 
                                                $client, 
                                                $machineModel, 
                                                $machineSerial, 
                                                $techName, 
                                                $stockTransfer
                                            );
                                            $stmt2->execute();
                                            $stmt2->close();
                                        } else {
                                            showAlert("danger", "Error preparing INSERT statement: " . $con->error);
                                            // $response = array(
                                            //     'status' => 'error',
                                            //     'message' => 'Error preparing INSERT statement: ' . $con->error
                                            //   );
                                            //   echo json_encode($response);
                                            //   exit; // Stop further output to avoid conflicts
                                        }
                                    } else {
                                        showAlert("warning", "No rows were updated for TOTAL_QUANTITY.");
                                        // $response = array(
                                        //     'status' => 'error',
                                        //     'message' => 'No rows were updated for TOTAL_QUANTITY.' . $con->error
                                        //   );
                                        //   echo json_encode($response);
                                        //   exit; // Stop further output to avoid conflicts
                                    }
            
                                    $stmt1->close();
                                } else {
                                    showAlert("danger", "Error preparing UPDATE statement: " . $con->error);
                                    // $response = array(
                                    //     'status' => 'error',
                                    //     'message' => 'Error preparing UPDATE statement: ' . $con->error
                                    //   );
                                    //   echo json_encode($response);
                                    //   exit; // Stop further output to avoid conflicts
                                }
                            } else {
                                // Client name not found in the database
                                // showAlert("danger", "Client name not found. Please check the input.");
                                // $response = array(
                                //     'status' => 'error',
                                //     'message' => 'Client name not found. Please check the input.' . $con->error
                                //   );
                                //   echo json_encode($response);
                                //   exit; // Stop further output to avoid conflicts
                            }
            
                            // Close the client check statement
                            $sqlCheckClient->close();
                        } else {
                            // Invalid data (quantity issues)
                            showAlert("warning", "Invalid Data. Quantity is either empty, less than 1, or exceeds available stock.");
                            // $response = array(
                            //     'status' => 'error',
                            //     'message' => 'Invalid Data. Quantity is either empty, less than 1, or exceeds available stock.' . $con->error
                            //   );
                            //   echo json_encode($response);
                            //   exit; // Stop further output to avoid conflicts
                        }
                    }
                    ?>
            