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


    if(mysqli_num_rows($result)) {?>
        
        <div class="table-responsive bg-white">
                  <table class="table mb-0">
                    <thead>
                      <tr>
                        <th class="h3 fw-bold" scope="col">MODEL</th>
                        <th class="h3 fw-bold" scope="col">DESCRIPTION</th>
                        <th class="h3 fw-bold" scope="col">CODE</th>
                        <th class="h3 fw-bold" scope="col">QUANTITY</th>
                        <th class="h3 fw-bold" scope="col">ACTIONS</th>
                      </tr>
                    </thead>
                    <tbody>
                    
                    <?php
                      while($row = mysqli_fetch_assoc($result)) {
              
                        $model = $row['MODEL'];
                        $description =$row['DESCRIPTION'];
                        $code = $row['CODE'];
                      
                        ?>

                        <tr>
                          
                          <td><?php echo $model ?></td>
                          <td><?php echo $description ?></td>
                          <td><?php echo $code ?></td>
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
    </body>
</html>

