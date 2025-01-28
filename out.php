<?php
    include("dbcon.php");
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
    <?php
        include("dbcon.php");

        function showAlert($type, $message) {
            echo "<div class='alert alert-$type alert-dismissible fade show' role='alert'>
                    <strong>$type:</strong> $message
                    <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                </div>";
        }

        // Initialize variables for form inputs
        $selectedSupply = htmlspecialchars($_POST['selectedSupply']);
        $total_quantity = htmlspecialchars($_POST['total_quantity']);
        $quantity = htmlspecialchars($_POST['quantity']);
        $code = htmlspecialchars($_POST['code']);
        $owner = htmlspecialchars($_POST['owner']);
        $model = htmlspecialchars($_POST['model']);
        $description = htmlspecialchars($_POST['description']);
        $date_of_delivery = htmlspecialchars($_POST['date_of_delivery']);
        $barCode = htmlspecialchars($_POST['barcode']);
        $stockTransfer = htmlspecialchars($_POST['stockTransfer']);
        $machineModel = htmlspecialchars($_POST['machineModel']);
        $machineSerial = htmlspecialchars($_POST['machineSerial']);
        $techName = htmlspecialchars($_POST['techName']);
        $client = htmlspecialchars($_POST['client']);
        
        echo($total_quantity);
        echo($quantity);
        echo($date_of_delivery);
        echo($client);
        echo($machineSerial);


        // Handle form submission
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Validate required fields
            $requiredFields = ["quantity", "code", "owner", "selectedSupply", "total_quantity"];
            foreach ($requiredFields as $field) {
                if (!isset($_POST[$field]) || empty(trim($_POST[$field]))) {
                    showAlert("danger", "Missing required field: $field");
                    exit;
                }
            }

            // Sanitize inputs
            $selectedSupply = htmlspecialchars($selectedSupply);
            $total_quantity = (int)$total_quantity;
            $quantity = (int)$quantity;
            $code = htmlspecialchars($code);
            $owner = htmlspecialchars($owner);
            $model = htmlspecialchars($model);
            $description = htmlspecialchars($description);
            $date_of_delivery = date("Y-m-d", strtotime($date_of_delivery));
            $barCode = htmlspecialchars($barCode);
            $stockTransfer = htmlspecialchars($stockTransfer);
            $machineModel = htmlspecialchars($machineModel);
            $machineSerial = htmlspecialchars($machineSerial);
            $techName = htmlspecialchars($techName);
            $client = htmlspecialchars($client);

            // Validate quantity
            if ($quantity <= 0 || $total_quantity < $quantity) {
                showAlert("warning", "Invalid quantity. Please check your input.");
                exit;
            }

            // Step 1: Check current stock of the item
            $sql = "SELECT TOTAL_QUANTITY FROM `$selectedSupply` WHERE CODE = ?";
            $stmt = $con->prepare($sql);
            if (!$stmt) {
                showAlert("danger", "Database error: " . $con->error);
                exit;
            }

            $stmt->bind_param("s", $code);
            $stmt->execute();
            $stmt->bind_result($currentQuantity);

            if ($stmt->fetch()) {
                $newQuantity = $currentQuantity - $quantity;

                if ($newQuantity < 0) {
                    showAlert("danger", "Insufficient stock. Current stock: $currentQuantity.");
                    $stmt->close();
                    exit;
                }
            } else {
                showAlert("danger", "Item not found in the database.");
                $stmt->close();
                exit;
            }

            $stmt->close();

            // Step 2: Check if the client name exists
            $sqlCheckClient = "SELECT client_name FROM client_names WHERE client_name = ?";
            $stmtClient = $con->prepare($sqlCheckClient);
            if (!$stmtClient) {
                showAlert("danger", "Database error: " . $con->error);
                exit;
            }

            $stmtClient->bind_param("s", $client);
            $stmtClient->execute();
            $resultClient = $stmtClient->get_result();

            if ($resultClient->num_rows === 0) {
                showAlert("danger", "Client name not found. Please check the input.");
                $stmtClient->close();
                exit;
            }

            $stmtClient->close();

            // Step 3: Update the stock in the selected supply table
            $sqlUpdateStock = "UPDATE `$selectedSupply` SET TOTAL_QUANTITY = ? WHERE CODE = ?";
            $stmtUpdateStock = $con->prepare($sqlUpdateStock);
            if (!$stmtUpdateStock) {
                showAlert("danger", "Error preparing UPDATE statement: " . $con->error);
                exit;
            }

            $stmtUpdateStock->bind_param("is", $newQuantity, $code);
            $stmtUpdateStock->execute();

            if ($stmtUpdateStock->affected_rows > 0) {
                showAlert("success", "Stock updated successfully.");
            } else {
                showAlert("warning", "No rows were updated. Please verify your input.");
                $stmtUpdateStock->close();
                exit;
            }

            $stmtUpdateStock->close();

            // Step 4: Insert the delivery data into the delivery_out table
            $currentDate = date("Y-m-d");
            $sqlInsertDelivery = "INSERT INTO delivery_out 
                (date, model, description, code, date_of_delivery, barcode, quantity, client, machine_model, machine_serial, tech_name, stock_transfer) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

            $stmtInsertDelivery = $con->prepare($sqlInsertDelivery);
            if (!$stmtInsertDelivery) {
                showAlert("danger", "Error preparing INSERT statement: " . $con->error);
                exit;
            }

            $stmtInsertDelivery->bind_param(
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

            $stmtInsertDelivery->execute();

            if ($stmtInsertDelivery->affected_rows > 0) {
                showAlert("success", "Delivery data recorded successfully.");
            } else {
                showAlert("warning", "Failed to insert delivery data. Please try again.");
            }

            $stmtInsertDelivery->close();
        }
?>

    </body>
</html>