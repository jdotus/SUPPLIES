<?php 
include ('dbcon.php');

if(isset($_POST['input'])){
    $selectedSupply = $_POST['input'];

    $query = "SELECT * FROM '{$selectedSupply}' WHERE CODE LIKE '{scanCode}%'";
}   

?>
<body>
   
</body>
