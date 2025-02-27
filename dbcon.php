<?php
     $servername = "localhost";
     $username = "root";
     $password = "";
     $dbname = "office";

     $con = mysqli_connect($servername,$username,$password,$dbname);

     if($con->connect_error) {
        die("Connection Error: " . $con->connect_error);
     }
?>