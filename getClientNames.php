<?php
    include('dbcon.php');
    
    if(isset($_POST['searchTerm'])){

        $searchTerm = htmlspecialchars($_POST['searchTerm']);

        // Prepare the query
        $stmntClientName = $con->prepare("SELECT client_name FROM client_names WHERE client_name LIKE ?");
        
        // Bind the parameter with a wildcard for partial matches
        $searchTermWithWildcard = $searchTerm . '%';
        $stmntClientName->bind_param("s", $searchTermWithWildcard);
    
        // Execute the query
        $stmntClientName->execute();
    
        // Fetch results
        $result = $stmntClientName->get_result();
        $names = array();
    
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $names[] = $row["client_name"];
            }
        }
    
        // Return the names as JSON
        echo json_encode($names);
    }

?>
