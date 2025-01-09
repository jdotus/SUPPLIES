<?php 
include ('dbcon.php');

if(isset($_POST['input']) && isset($_POST['barcode'])){
    // $selectedSupply = $_POST['input'];
    // $scanCode = $_POST['barcode'];

    // $query = "SELECT * FROM '{$selectedSupply}' WHERE CODE LIKE '{$scanCode}%'";

    // $result = mysqli_query($con, $query);

    // 2. Get user input (Sanitize input to prevent XSS)
    $selectedSupply = htmlspecialchars($_POST['input']); 
    $scanCode = htmlspecialchars($_POST['barcode']); 
    
    // 3. Prepare the SQL statement (using prepared statements to prevent SQL injection)
    $stmt = $con->prepare("SELECT * FROM `{$selectedSupply}` WHERE CODE LIKE ?");
    $stmt->bind_param("s", $scanCode); 

    // 4. Execute the statement
    $stmt->execute();
    $result = $stmt->get_result();


    if(mysqli_num_rows($result)) {
        
        while($row = $result->fetch_assoc()) {

            $description =$row['DESCRIPTION'];
            $model = $row['MODEL'];
            echo('MODEL : ' . $model . 'DESCRIPTION : ' . $description);

        }
        
    }
}   
?>
