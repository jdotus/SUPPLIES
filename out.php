<?php
// Include database connection
include("dbcon.php");

// Function to respond with a JSON message
function respond($status, $message = "")
{
    echo json_encode(["status" => $status, "message" => $message]);
    exit;
}

// Ensure request method is POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    respond("error", "Invalid request method.");
}

// Retrieve and sanitize POST data
$id = $_POST['id'] ?? null;
$quantity = isset($_POST['quantity']) ? (int)$_POST['quantity'] : 0;
$barcode = $_POST['barcode'] ?? null;
$code = $_POST['code'] ?? null;
$client = $_POST['client'] ?? null;
$selectedSupply = $_POST['selectedSupply'] ?? null;
$stockTransfer = $_POST['stockTransfer'] ?? null;
$machineModel = $_POST['machineModel'] ?? null;
$machineSerial = $_POST['machineSerial'] ?? null;
$techName = $_POST['techName'] ?? null;
$owner = $_POST['owner'] ?? null;
$model = $_POST['model'] ?? null;
$description = $_POST['description'] ?? null;
$date_of_delivery = $_POST['date_of_delivery'] ?? null;

// Validate required fields
if (!$quantity || !$code || !$client || !$selectedSupply) {
    respond("error", "Missing required fields.");
}

if ($quantity <= 0) {
    respond("error", "Invalid quantity. Must be greater than 0.");
}

// Check stock availability
$sqlCheckStock = "SELECT TOTAL_QUANTITY FROM `$selectedSupply` WHERE CODE = ?";
$stmtCheckStock = $con->prepare($sqlCheckStock);

if (!$stmtCheckStock) {
    respond("error", "Database error: " . $con->error);
}

$stmtCheckStock->bind_param("s", $code);
$stmtCheckStock->execute();
$result = $stmtCheckStock->get_result();
$row = $result->fetch_assoc();

if (!$row) {
    $stmtCheckStock->close();
    respond("error", "Item not found.");
}

$currentQuantity = (int)$row['TOTAL_QUANTITY'];
$stmtCheckStock->close();

$newQuantity = $currentQuantity - $quantity;
if ($newQuantity < 0) {
    respond("error", "Insufficient stock. Current stock: $currentQuantity.");
}

// Update stock
$sqlUpdateStock = "UPDATE `$selectedSupply` SET TOTAL_QUANTITY = ? WHERE CODE = ?";
$stmtUpdateStock = $con->prepare($sqlUpdateStock);
$stmtUpdateStock->bind_param("is", $newQuantity, $code);
$stmtUpdateStock->execute();

if ($stmtUpdateStock->affected_rows <= 0) {
    $stmtUpdateStock->close();
    respond("error", "Failed to update stock.");
}

$stmtUpdateStock->close();

// Insert into `delivery_out` table
$sqlInsertDelivery = "INSERT INTO delivery_out 
    (date, model, description, code, owner, date_of_delivery, barcode, quantity, client, machine_model, machine_serial, tech_name, stock_transfer) 
    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

$stmtInsertDelivery = $con->prepare($sqlInsertDelivery);
$currentDate = date("Y-m-d");

$stmtInsertDelivery->bind_param(
    "sssssssssssss",
    $currentDate,
    $model,
    $description,
    $code,
    $owner,
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
