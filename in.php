<?php
    include('dbcon.php');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Supplies</title>

    <link rel="stylesheet" href="style.css">    
</head>
    <body>
        <?php
            if(isset($_POST["quantity"]) && isset($_POST["code"]) && isset($_POST["owner"])){

                $quantity = htmlspecialchars($_POST['quantity']);
                $code = htmlspecialchars($_POST['code']);
                $owner = htmlspecialchars($_POST['owner']);


                $sql = "SELECT BEGINNING_INVENTORY FROM toner WHERE CODE = ?"; 

                $stmnt2 = $con->prepare($sql);
                $stmnt2->bind_param("s", $code);
                $stmnt2->execute();
                $stmnt2->bind_result($currentQuantity);

                // Check if a row was found
                if ($stmnt2->fetch()) { 
                    $totalResult = $currentQuantity + $quantity; 
                } else {
                    // Handle the case where no row is found 
                    // (e.g., set $totalResult to a default value)
                    $totalResult = 0 + $quantity; 
                }

                $stmnt2->close();

                $sql1 = "UPDATE toner SET BEGINNING_INVENTORY = ? WHERE CODE = ?";
                $stmnt = $con->prepare($sql1);
                $stmnt->bind_param("is", $totalResult, $code); // Assuming $totalResult is an integer
                $stmnt->execute();

                if ($stmnt->affected_rows > 0) {
                    echo "NALAGAY NA";
                } else {
                    echo "NO RECORDasdasdas";
                }

                $stmnt->close();
                
            }
        ?>
    </body>
</html>