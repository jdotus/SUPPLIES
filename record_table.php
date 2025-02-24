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
        // Query for both delivery_in and delivery_out when "SELECT" is chosen
        $sql = "
            SELECT 'IN' AS type, model, description, code, date, owner, date_of_delivery, quantity, 
                   invoice, NULL AS stock_transfer, NULL AS barcode, NULL AS client, 
                   NULL AS tech_name, NULL AS machine_model, NULL AS machine_serial
            FROM delivery_in
            WHERE model LIKE '%$filter%' OR code LIKE '%$filter%' OR owner LIKE '%$filter%' OR invoice LIKE '%$filter%' OR description LIKE '%$filter%'
            
            UNION
            
            SELECT 'OUT' AS type, model, description, code, date, owner, date_of_delivery, quantity, 
                   NULL AS invoice, stock_transfer, barcode, client, tech_name, machine_model, machine_serial
            FROM delivery_out
            WHERE model LIKE '%$filter%' OR code LIKE '%$filter%' OR owner LIKE '%$filter%' OR stock_transfer LIKE '%$filter%' OR description LIKE '%$filter%'
            
            ORDER BY date DESC
            LIMIT 20;
        ";
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
                        <th class="h6 fw-bold">INVOICE/STOCK TRANSFER</th>
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
