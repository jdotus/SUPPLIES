<?php include('dbcon.php'); ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="style.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
</head>
    <body>
        <?php
    if (isset($_GET['selectedSupply']) && isset($_GET['filter'])) {
        $selectedSupply = htmlspecialchars($_GET['selectedSupply']);
        $filter = htmlspecialchars($_GET['filter']);

        echo($selectedSupply);
        if($selectedSupply == "delivery_in") {
            if (empty($filter) && $selectedSupply == "delivery_in") {
                $stmnt = $con->prepare("SELECT * FROM delivery_in");
            } else if (!empty($filter) && $selectedSupply == "delivery_in") {
                $stmnt = $con->prepare("SELECT * FROM delivery_in WHERE invoice LIKE ? OR code LIKE ? OR model LIKE ? OR owner LIKE ?");
                $filter = $filter . '%'; // Add wildcard for LIKE search
                $stmnt->bind_param("ssss", $filter, $filter, $filter, $filter);
            } else {
                die("Invalid selection.");
            }
            
            $stmnt->execute();
            $result = $stmnt->get_result();
            
            if ($result->num_rows > 0) { ?>
                <div class="table-responsive bg-white">
                    <table class="table table-hover table-responsive-md mb-0" id="dataTable">
                        <thead>
                            <tr>
                                <th class="h6 fw-bold" scope="col">MODEL</th>
                                <th class="h6 fw-bold" scope="col">DESCRIPTION</th>
                                <th class="h6 fw-bold" scope="col">CODE</th>
                                <th class="h6 fw-bold" scope="col">OWNER</th>
                                <th class="h6 fw-bold" scope="col">DATE OF DELIVERY</th>
                                <th class="h6 fw-bold" scope="col">QUANTITY</th>
                                <th class="h6 fw-bold" scope="col">INVOICE No.</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($row = $result->fetch_assoc()) { ?>
                                <tr data-id="<?php echo $row['id']; ?>">
                                    <td><?php echo htmlspecialchars($row['model']); ?></td>
                                    <td><?php echo htmlspecialchars($row['description']); ?></td>
                                    <td><?php echo htmlspecialchars($row['code']); ?></td>
                                    <td><?php echo htmlspecialchars($row['owner']); ?></td>
                                    <td><?php echo htmlspecialchars($row['date_of_delivery']); ?></td>
                                    <td><?php echo htmlspecialchars($row['quantity']); ?></td>
                                    <td><?php echo htmlspecialchars($row['invoice']); ?></td>
                                </tr>
                                <?php } ?>
                        </tbody>
                    </table>
                </div>
                    <?php } else {
                        echo "<p>No records found.</p>";
                }
            }else if($selectedSupply == "delivery_out"){

                if (empty($filter) && $selectedSupply == "delivery_out") {
                    $stmnt = $con->prepare("SELECT * FROM delivery_out");
                } else if (!empty($filter) && $selectedSupply == "delivery_out") {
                    $stmnt = $con->prepare("SELECT * FROM delivery_out WHERE stock_transfer LIKE ? OR code LIKE ? OR model LIKE ? OR owner LIKE ?");
                    $filter = $filter . '%'; // Add wildcard for LIKE search
                    $stmnt->bind_param("ssss", $filter, $filter, $filter, $filter);
                } else {
                    die("Invalid selection.");
                }
                
                $stmnt->execute();
                $result = $stmnt->get_result();

                if ($result->num_rows > 0) { ?>
                <div class="table-responsive bg-white">
                    <table class="table table-hover table-responsive-md mb-0" id="dataTable">
                        <thead>
                            <tr>
                                <th class="h6 fw-bold" scope="col">MODEL</th>
                                <th class="h6 fw-bold" scope="col">DESCRIPTION</th>
                                <th class="h6 fw-bold" scope="col">CODE</th>
                                <th class="h6 fw-bold" scope="col">OWNER</th>
                                <th class="h6 fw-bold" scope="col">DATE OF DELIVERY</th>
                                <th class="h6 fw-bold" scope="col">QUANTITY</th>
                                <th class="h6 fw-bold" scope="col">STOCK<br>TRANSFER No.</th>
                                <th class="h6 fw-bold" scope="col">BARCODE</th>
                                <th class="h6 fw-bold" scope="col">CLIENT</th>
                                <th class="h6 fw-bold" scope="col">TECH NAME</th>
                                <th class="h6 fw-bold" scope="col">MACHINE MODEL</th>
                                <th class="h6 fw-bold" scope="col">MACHINE SERIAL</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($row = $result->fetch_assoc()) { ?>
                                <tr data-id="<?php echo $row['id']; ?>">
                                    <td><?php echo htmlspecialchars($row['model']); ?></td>
                                    <td><?php echo htmlspecialchars($row['description']); ?></td>
                                    <td><?php echo htmlspecialchars($row['code']); ?></td>
                                    <td><?php echo htmlspecialchars($row['owner']); ?></td>
                                    <td><?php echo htmlspecialchars($row['date_of_delivery']); ?></td>
                                    <td><?php echo htmlspecialchars($row['quantity']); ?></td>
                                    <td><?php echo htmlspecialchars($row['stock_transfer']); ?></td>
                                    <td><?php echo htmlspecialchars($row['barcode']); ?></td>
                                    <td><?php echo htmlspecialchars($row['client']); ?></td>
                                    <td><?php echo htmlspecialchars($row['tech_name']); ?></td>
                                    <td><?php echo htmlspecialchars($row['machine_model']); ?></td>
                                    <td><?php echo htmlspecialchars($row['machine_serial']); ?></td>
                                </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                <?php } else {
                    echo "<p>No records found.</p>";
                }
            }
        }
    ?>

<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script>
    $(document).ready(function() {
        $('#dataTable').DataTable({ 
                searching: false
            });

        $('.dataTables_length label').contents().filter(function(){
            return this.nodeType === 3; //Node.TEXT_NODE
        }).remove();

    });
</script>
</body>
</html>
