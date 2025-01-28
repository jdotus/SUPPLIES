<?php
// Include the database connection
include("dbcon.php");

// Function to respond with a message
function respond($status, $message = "")
{
    echo json_encode(["status" => $status, "message" => $message]);
    exit;
}

// Check if the request method is POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    respond("error", "Invalid request method.");
}

// Retrieve and sanitize POST data
$id = isset($_POST['id']) ? htmlspecialchars($_POST['id']) : null;
$quantity = isset($_POST['quantity']) ? (int)$_POST['quantity'] : 0;
$barcode = isset($_POST['barcode']) ? htmlspecialchars($_POST['barcode']) : null;
$code = isset($_POST['code']) ? htmlspecialchars($_POST['code']) : null;
$client = isset($_POST['client']) ? htmlspecialchars($_POST['client']) : null;
$total_quantity = isset($_POST['total_quantity']) ? (int)$_POST['total_quantity'] : 0;
$selectedSupply = isset($_POST['selectedSupply']) ? htmlspecialchars($_POST['selectedSupply']) : null;
$stockTransfer = isset($_POST['stockTransfer']) ? htmlspecialchars($_POST['stockTransfer']) : null;
$machineModel = isset($_POST['machineModel']) ? htmlspecialchars($_POST['machineModel']) : null;
$machineSerial = isset($_POST['machineSerial']) ? htmlspecialchars($_POST['machineSerial']) : null;
$techName = isset($_POST['techName']) ? htmlspecialchars($_POST['techName']) : null;
$owner = isset($_POST['owner']) ? htmlspecialchars($_POST['owner']) : null;
$model = isset($_POST['model']) ? htmlspecialchars($_POST['model']) : null;
$description = isset($_POST['description']) ? htmlspecialchars($_POST['description']) : null;
$date_of_delivery = isset($_POST['date_of_delivery']) ? htmlspecialchars($_POST['date_of_delivery']) : null;

// Validate required fields
if (empty($quantity) || empty($code) || empty($client) || empty($selectedSupply)) {
    respond("error", "Missing required fields.");
}

if ($quantity <= 0) {
    respond("error", "Invalid quantity. Must be greater than 0.");
}

// Validate the stock and update
$sqlCheckStock = "SELECT TOTAL_QUANTITY FROM `$selectedSupply` WHERE CODE = ?";
$stmtCheckStock = $con->prepare($sqlCheckStock);

if (!$stmtCheckStock) {
    respond("error", "Database error: " . $con->error);
}

$stmtCheckStock->bind_param("s", $code);
$stmtCheckStock->execute();
$stmtCheckStock->bind_result($currentQuantity);

if ($stmtCheckStock->fetch()) {
    $newQuantity = $currentQuantity - $quantity;

    if ($newQuantity < 0) {
        $stmtCheckStock->close();
        respond("error", "Insufficient stock. Current stock: $currentQuantity.");
    }
} else {
    $stmtCheckStock->close();
    respond("error", "Item not found.");
}

$stmtCheckStock->close();

// Update the stock
$sqlUpdateStock = "UPDATE `$selectedSupply` SET TOTAL_QUANTITY = ? WHERE CODE = ?";
$stmtUpdateStock = $con->prepare($sqlUpdateStock);

if (!$stmtUpdateStock) {
    respond("error", "Database error: " . $con->error);
}

$stmtUpdateStock->bind_param("is", $newQuantity, $code);
$stmtUpdateStock->execute();

if ($stmtUpdateStock->affected_rows <= 0) {
    $stmtUpdateStock->close();
    respond("error", "Failed to update stock.");
}

$stmtUpdateStock->close();

// Insert into delivery_out table
$sqlInsertDelivery = "INSERT INTO delivery_out 
    (date, model, description, code, date_of_delivery, barcode, quantity, client, machine_model, machine_serial, tech_name, stock_transfer) 
    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

$stmtInsertDelivery = $con->prepare($sqlInsertDelivery);

if (!$stmtInsertDelivery) {
    respond("error", "Database error: " . $con->error);
}

$currentDate = date("Y-m-d");

$stmtInsertDelivery->bind_param(
    "ssssssssssss",
    $currentDate,
    $model,
    $description,
    $code,
    $date_of_delivery,
    $barcode,
    $quantity,
    $client,
    $machineModel,
    $machineSerial,
    $techName,
    $stockTransfer
);

$stmtInsertDelivery->execute();

if ($stmtInsertDelivery->affected_rows > 0) {
    $stmtInsertDelivery->close();
    respond("success", "Stock updated and delivery data recorded.");
} else {
    $stmtInsertDelivery->close();
    respond("error", "Failed to record delivery data.");
}
?>
