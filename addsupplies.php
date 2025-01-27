<?php

include('dbcon.php');

// Check if all required POST fields are set
if (isset($_POST["addsupply"]) && isset($_POST["addModel"]) && isset($_POST["addDescription"]) && isset($_POST["addOwner"]) && isset($_POST["addCode"]) && isset($_POST["addQuantity"])) {

    // Retrieve POST data
    $supply = $_POST["addsupply"];
    $model = $_POST["addModel"]; 
    $description = $_POST["addDescription"];
    $owner = $_POST["addOwner"];
    $code = $_POST["addCode"];
    $quantity = $_POST["addQuantity"];

    // Initialize SQL query
    $sql = "";

    // Determine table based on supply type
    switch ($supply) {
        case "toner":
            $sql = "INSERT INTO toner (model, description, owner, code, total_quantity) 
                    VALUES ('$model', '$description', '$owner', '$code', '$quantity')";
            break;
        case "drum":
            $sql = "INSERT INTO drum (model, description, owner, code, total_quantity) 
                    VALUES ('$model', '$description', '$owner', '$code', '$quantity')";
            break;
        default:
            echo "Invalid supply type.";
            exit;
    }

    // Execute query
    if ($con->query($sql) === TRUE) {
        echo "New supply added successfully!";
    } else {
        echo "Error: " . $con->error;
    }

    // Close connection
    $con->close();

} else {
    echo "Please fill out all required fields.";
}

?>
