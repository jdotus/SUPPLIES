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

            function showAlert($type, $message) {
                echo "<div class='alert alert-$type alert-dismissible fade show' role='alert'>
                        <strong>$type:</strong> $message
                        <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                        </div>";
            }
            if(isset($_POST["quantity"]) && isset($_POST["code"]) && isset($_POST["owner"])){

                $quantity = htmlspecialchars($_POST['quantity']);
                $code = htmlspecialchars($_POST['code']);
                $owner = htmlspecialchars($_POST['owner']);
                $model = htmlspecialchars($_POST['model']);
                $description = htmlspecialchars($_POST['description']);
                $date_of_delivery = date("m-d-Y", strtotime($_POST['date_of_delivery']));

                if($quantity != "" && $quantity != null && $quantity > 0) {

                    $sql = "SELECT TOTAL_QUANTITY FROM toner WHERE CODE = ?"; 
    
                    $stmnt2 = $con->prepare($sql);
                    $stmnt2->bind_param("s", $code);
                    $stmnt2->execute();
                    $stmnt2->bind_result($currentQuantity);
    
                    // Check if a row was found
                    if ($stmnt2->fetch()) { 
                        $totalResult = $currentQuantity - $quantity; 
                        if ($totalResult < 0) {
                            showAlert("danger", "Quantity exceeds available stock. Current stock: " . htmlspecialchars($currentQuantity));
                            $stmnt2->close();
                            exit;
                        }
                    } else {
                        // Handle the case where no row is found 
                        // (e.g., set $totalResult to a default value)
                        $totalResult = 0 + $quantity; 
                    }
    
                    $stmnt2->close();
    
                    $sql1 = "UPDATE toner SET TOTAL_QUANTITY = ? WHERE CODE = ?";
                    $stmnt = $con->prepare($sql1);
                    $stmnt->bind_param("is", $totalResult, $code); // Assuming $totalResult is an integer
                    $stmnt->execute();
    
                    if ($stmnt->affected_rows > 0) {?>
                    
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <strong>Success!</strong> Data Updated!
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>  
                    <?php
                        $currentDate = date("m-d-Y");
                        $sql2 = "INSERT INTO delivery_out (date, model, description, code, date_of_delivery, quantity) VALUES (?, ?, ?, ?, ?, ?)";
                        $stmnt3 = $con->prepare($sql2);
                        $stmnt3->bind_param("ssssss",  $currentDate, $model, $description, $code, $date_of_delivery, $quantity);
                        $stmnt3->execute();
                        $stmnt3->close();

                    } else { ?>
                    <div class="alert alert-warning alert-dismissible fade show" role="alert">
                        <strong>Ohhh no!</strong> NO RECORD FOUND
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>  
                    <?php
                    }
    
                    $stmnt->close();
                } else { ?>
                    <div class="alert alert-warning alert-dismissible fade show" role="alert">
                        <strong>Ohhh no!</strong> Invalid Data, Try again.
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>  
                    <?php
                }                
            }

        ?>
    </body>
</html>