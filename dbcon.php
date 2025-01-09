<?php
     $servername = "localhost";
     $username = "root";
     $password = "";
     $dbname = "barcode";

     $con = mysqli_connect($servername,$username,$password,$dbname);

     if($con->connect_error) {
        die("Connection Error: " . $con->connect_error);
     }
?>