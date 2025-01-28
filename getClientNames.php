<?php
include('dbcon.php');

if (isset($_GET['searchTerm'])) {
    $searchTerm = htmlspecialchars($_GET['searchTerm']);
    error_log("Search term received: " . $searchTerm);

    $sql = "SELECT client_name FROM client_names WHERE client_name LIKE ?";
    $stmt = $con->prepare($sql);

    $searchTermWithWildcard = $searchTerm . '%';
    $stmt->bind_param("s", $searchTermWithWildcard);

    $stmt->execute();
    $result = $stmt->get_result();

    $names = [];
    while ($row = $result->fetch_assoc()) {
        $names[] = $row["client_name"];
    }

    error_log("Fetched Names: " . json_encode($names));
    echo json_encode($names);
}
?>
