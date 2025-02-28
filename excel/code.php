<?php
session_start();
include('dbconfig.php');

require 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

if (isset($_POST['save_excel_data'])) {
    // Retrieve the selected supply
    $selectedSupply = $_POST['supplies']; // This will hold "toner" or "drum"

    $fileName = $_FILES['import_file']['name'];
    $file_ext = pathinfo($fileName, PATHINFO_EXTENSION);

    $allowed_ext = ['xls', 'csv', 'xlsx'];

    if (in_array($file_ext, $allowed_ext)) {
        $inputFileNamePath = $_FILES['import_file']['tmp_name'];
        $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($inputFileNamePath);
        $data = $spreadsheet->getActiveSheet()->toArray();

        $count = 0;
        $insertedCount = 0;
        $duplicateCount = 0;

        foreach ($data as $row) {
            if ($count > 0) {
                // Check if the entire row is blank or contains only spaces
                if (array_filter($row, fn($value) => trim($value) !== '') === []) {
                    continue; // Skip this row
                }

                $MODEL = trim($row[0]);
                $DESCRIPTION = trim($row[1]);
                $CODE = trim($row[2]);
                $TOTAL_QUANTITY = trim($row[3]);
                $OWNER = trim($row[4]);

                // Determine the table based on the selected supply
                // $table = ($selectedSupply === 'toner') ? 'toner' : 'drum';
                $table = ($selectedSupply === 'toner') ? 'toner' : 
                (($selectedSupply === 'drum') ? 'drum' : 
                (($selectedSupply === 'waste') ? 'waste' : 
                (($selectedSupply === 'maintenance') ? 'maintenance' : 'default_table')));


                // Check if CODE already exists in the selected table
                $checkQuery = "SELECT COUNT(*) AS count FROM $table WHERE CODE = '$CODE'";
                $checkResult = mysqli_query($conn, $checkQuery);
                $checkRow = mysqli_fetch_assoc($checkResult);

                if ($checkRow['count'] == 0) {
                    // If CODE doesn't exist, insert the new record
                    $insertQuery = "INSERT INTO $table (MODEL, DESCRIPTION, CODE, TOTAL_QUANTITY, OWNER) VALUES ('$MODEL', '$DESCRIPTION', '$CODE', '$TOTAL_QUANTITY', '$OWNER')";
                    if (mysqli_query($conn, $insertQuery)) {
                        $insertedCount++;
                    }
                } else {
                    // CODE already exists, count as duplicate
                    $duplicateCount++;
                }
            } else {
                $count = 1; // Skip the first row (assuming it's a header)
            }
        }

        // Prepare the alert message
        $alertMessage = "Successfully Imported: $insertedCount records into $selectedSupply. Duplicates Skipped: $duplicateCount records.";
        echo "<script>
            alert('$alertMessage');
            window.location.href = 'excel.php';
        </script>";
        exit(0);
    } else {
        echo "<script>
            alert('Invalid File');
            window.location.href = 'excel.php';
        </script>";
        exit(0);
    }
}
?>
