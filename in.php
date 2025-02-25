<?php
include('dbcon.php');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo "Invalid request method.";
    exit;
}

// Retrieve and sanitize POST data
$id = $_POST['id'] ?? null;
$quantity = isset($_POST['quantity']) ? (int)$_POST['quantity'] : 0;
$code = $_POST['code'] ?? null;
$owner = $_POST['owner'] ?? null;
$selectedSupply = $_POST['selectedSupply'] ?? null;
$invoice = $_POST['invoice'] ?? null;
$date_of_delivery = $_POST['date_of_delivery'] ?? null;
$model = $_POST['model'] ?? null;
$description = $_POST['description'] ?? null;

// Validate input
if ($quantity <= 0) {
    echo "Invalid data. Quantity must be greater than 0.";
    exit;
}

// Check if item exists in the database
$sqlCheckStock = "SELECT TOTAL_QUANTITY FROM `$selectedSupply` WHERE CODE = ?";
$stmtCheckStock = $con->prepare($sqlCheckStock);
$stmtCheckStock->bind_param("s", $code);
$stmtCheckStock->execute();
$result = $stmtCheckStock->get_result();
$row = $result->fetch_assoc();
$stmtCheckStock->close();

$totalResult = $row ? $row['TOTAL_QUANTITY'] + $quantity : $quantity;

// Update or Insert stock
$sqlUpdateStock = "UPDATE `$selectedSupply` SET TOTAL_QUANTITY = ? WHERE CODE = ?";
$stmtUpdateStock = $con->prepare($sqlUpdateStock);
$stmtUpdateStock->bind_param("is", $totalResult, $code);
$stmtUpdateStock->execute();

if ($stmtUpdateStock->affected_rows <= 0) {
    echo "Failed to update stock.";
    exit;
}

$stmtUpdateStock->close();

// Insert into `delivery_in` table
$sqlInsertDelivery = "INSERT INTO delivery_in 
    (date, model, description, code, owner, invoice, date_of_delivery, quantity,type) 
    VALUES (?, ?, ?, ?, ?, ?, ?, ?,'IN')";

$stmtInsertDelivery = $con->prepare($sqlInsertDelivery);
date_default_timezone_set('Asia/Manila');
$currentDate = date("Y-m-d H:i:s");
// $currentDate = new DateTime("now", new DateTimeZone("Asia/Manila"));

$stmtInsertDelivery->bind_param(
    "ssssssss",
    $currentDate,
    $model,
    $description,
    $code,
    $owner,
    $invoice,
    $date_of_delivery,
    $quantity
);

$stmtInsertDelivery->execute();

if ($stmtInsertDelivery->affected_rows > 0) {
    $stmtInsertDelivery->close();
    echo "success";
} else {
    $stmtInsertDelivery->close();
    echo "Failed to record delivery data.";
}
?>
