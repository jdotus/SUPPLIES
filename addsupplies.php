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

    // Initialize SQL query for insertion and validation
    $table = "";
    switch ($supply) {
        case "toner":
            $table = "toner";
            break;
        case "drum":
            $table = "drum";
            break;
        case "waste":
            $table = "waste";
            break;
        case "maintenance":
            $table = "maintenance";
            break;
        default:
            echo "Invalid supply type.";
            exit;
    }

    // Check if data already exists
    $checkSql = "SELECT * FROM $table WHERE model = '$model' AND code = '$code'";
    $result = $con->query($checkSql);

    if ($result->num_rows > 0) {
        // Data already exists
        echo "Error: Supply with the same model and code already exists.";
    } else {
        // Insert new data
        $insertSql = "INSERT INTO $table (model, description, owner, code, total_quantity) 
                      VALUES ('$model', '$description', '$owner', '$code', '$quantity')";
        if ($con->query($insertSql) === TRUE) {
            echo "New supply added successfully!";
        } else {
            echo "Error: " . $con->error;
        }
    }

    // Close connection
    $con->close();

} else {
    echo "Please fill out all required fields.";
}

?>
