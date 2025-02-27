<?php include('dbcon.php'); ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="style.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
    
    <!-- Datatables button -->
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/3.2.2/css/buttons.dataTables.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/2.2.2/css/dataTables.dataTables.css">
</head>
<body>

<?php
if (isset($_GET['selectedSupply']) && isset($_GET['filter'])) {
    $selectedSupply = htmlspecialchars($_GET['selectedSupply']);
    $filter = htmlspecialchars($_GET['filter']);

    if ($selectedSupply == "default") {
         $sql = "SELECT * FROM record WHERE model LIKE '%$filter%' OR code LIKE '%$filter%' OR owner LIKE '%$filter%' OR description LIKE '%$filter%' OR date_of_delivery LIKE '%$filter%' ORDER BY date DESC";

    } else {
        // Query for a specific supply type
        $table = ($selectedSupply == "delivery_in") ? "delivery_in" : "delivery_out";
        $sql = "SELECT * FROM $table WHERE model LIKE '%$filter%' OR code LIKE '%$filter%' OR owner LIKE '%$filter%' OR description LIKE '%$filter%' OR date_of_delivery LIKE '%$filter%' 
                OR " . ($selectedSupply == "delivery_in" ? "invoice" : "stock_transfer") . " LIKE '%$filter%'";
    }

    $result = $con->query($sql);

    if ($result && $result->num_rows > 0) { ?>
        <div class="  bg-white">
            <table class="table table-responsive table-hover table-responsive-md mb-0" id="example">
                <thead>
                    <tr>
                        <th class="h6 fw-bold">TYPE</th>
                        <th class="h6 fw-bold">MODEL</th>
                        <th class="h6 fw-bold">DESCRIPTION</th>
                        <th class="h6 fw-bold">CODE</th>
                        <th class="h6 fw-bold">OWNER</th>
                        <th class="h6 fw-bold">DATE OF DELIVERY</th>
                        <th class="h6 fw-bold">QUANTITY</th>
                        <th class="h6 fw-bold">INVOICE or <BR>STOCK TRANSFER</th>
                        <th class="h6 fw-bold">BARCODE</th>
                        <th class="h6 fw-bold">CLIENT</th>
                        <th class="h6 fw-bold">TECH NAME</th>
                        <th class="h6 fw-bold">MACHINE MODEL</th>
                        <th class="h6 fw-bold">MACHINE SERIAL</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result->fetch_assoc()) { ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['type'] ?? ''); ?></td>
                            <td><?php echo htmlspecialchars($row['model']); ?></td>
                            <td><?php echo htmlspecialchars($row['description']); ?></td>
                            <td><?php echo htmlspecialchars($row['code']); ?></td>
                            <td><?php echo htmlspecialchars($row['owner']); ?></td>
                            <td><?php echo htmlspecialchars($row['date_of_delivery']); ?></td>
                            <td><?php echo htmlspecialchars($row['quantity']); ?></td>
                            <td><?php echo htmlspecialchars($row['invoice'] ?? $row['stock_transfer'] ?? ''); ?></td>
                            <td><?php echo htmlspecialchars($row['barcode'] ?? ''); ?></td>
                            <td><?php echo htmlspecialchars($row['client'] ?? ''); ?></td>
                            <td><?php echo htmlspecialchars($row['tech_name'] ?? ''); ?></td>
                            <td><?php echo htmlspecialchars($row['machine_model'] ?? ''); ?></td>
                            <td><?php echo htmlspecialchars($row['machine_serial'] ?? ''); ?></td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    <?php } else {
        echo "<p>No records found.</p>";
    }
}
?>

<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>

<!-- Data Tables BuiltIn Buttons-->
<script src="https://code.jquery.com/jquery-3.7.1.js"></script>
<script src="https://cdn.datatables.net/2.2.2/js/dataTables.js"></script>
<script src="https://cdn.datatables.net/buttons/3.2.2/js/dataTables.buttons.js"></script>
<script src="https://cdn.datatables.net/buttons/3.2.2/js/buttons.dataTables.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/3.2.2/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/3.2.2/js/buttons.print.min.js"></script>

</body>
</html>
