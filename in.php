<?php
    include('dbcon.php');
?>

        <?php
            if (
                isset($_POST["id"], $_POST["quantity"], $_POST["code"], $_POST["owner"], 
                $_POST["selectedSupply"], $_POST["invoice"], $_POST["date_of_delivery"])
            ) {
                $id = htmlspecialchars($_POST['id']);
                $quantity = intval($_POST['quantity']);
                $code = htmlspecialchars($_POST['code']);
                $owner = htmlspecialchars($_POST['owner']);
                $selectedSupply = htmlspecialchars($_POST['selectedSupply']);
                $invoice = intval($_POST['invoice']);
                $date_of_delivery = date("m-d-Y", strtotime($_POST['date_of_delivery']));
                $model = htmlspecialchars($_POST['model']);
                $description = htmlspecialchars($_POST['description']);
            
                // Validate input
                if ($quantity > 0 && $invoice > 0) {
                    $sql = "SELECT TOTAL_QUANTITY FROM `{$selectedSupply}` WHERE CODE = ?";
                    $stmnt2 = $con->prepare($sql);
                    $stmnt2->bind_param("s", $code);
                    $stmnt2->execute();
                    $stmnt2->bind_result($currentQuantity);
            
                    if ($stmnt2->fetch()) {
                        $totalResult = $currentQuantity + $quantity;
                    } else {
                        $totalResult = $quantity;
                    }
                    $stmnt2->close();
            
                    // Update total quantity
                    $sql1 = "UPDATE `{$selectedSupply}` SET TOTAL_QUANTITY = ? WHERE CODE = ?";
                    $stmnt1 = $con->prepare($sql1);
                    $stmnt1->bind_param("is", $totalResult, $code);
                    $stmnt1->execute();
            
                    if ($stmnt1->affected_rows > 0) {
                        // Record the delivery
                        $currentDate = date("m-d-Y");
                        $sql2 = "INSERT INTO delivery_in (date, model, description, code, invoice, date_of_delivery, quantity) 
                                 VALUES (?, ?, ?, ?, ?, ?, ?)";
                        $stmnt3 = $con->prepare($sql2);
                        $stmnt3->bind_param("sssssss", $currentDate, $model, $description, $code, $invoice, $date_of_delivery, $quantity);
                        $stmnt3->execute();
                        $stmnt3->close();
            
                        echo "success";
                    } else {
                        echo "Failed to update quantity.";
                    }
                    $stmnt1->close();
                } else {
                    echo "Invalid data. Quantity and invoice must be greater than 0.";
                }
            } else {
                echo "Required fields are missing.";
            }
        ?>
