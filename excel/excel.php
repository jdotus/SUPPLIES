<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Import Excel File to Supplies</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
    #supplies {
        width: 10%; /* Full width for better appearance */
        padding: 8px; /* Add padding for better usability */
        margin-top: 10px;
        margin-left: 10px;
        margin-bottom: 10px; /* Space between label and dropdown */
        font-size: 20px; /* Adjust font size for readability */
        border: 1px solid #ced4da; /* Match the Bootstrap input border style */
        border-radius: 4px; /* Rounded corners */
        background-color: lightblue; /* Background color */
        appearance: none; /* Remove default browser styling */
    }

    #supplies:focus {
        border-color: #80bdff; /* Highlight color when focused */
        outline: none; /* Remove default focus outline */
        box-shadow: 0 0 5px rgba(128, 189, 255, 0.5); /* Add subtle focus shadow */
    }
</style>


</head>
<body>
    
    <div class="container">
        <div class="row">
            <div class="col-md-12 mt-4">

                <?php
                if(isset($_SESSION['message']))
                {
                    echo "<h4>".$_SESSION['message']."</h4>";
                    unset($_SESSION['message']);
                }
                ?>

                <div class="card">
                    <div class="card-header">
                        <h4>Import Excel File to Supplies</h4>
                    </div>
                    <div class="card-body">

                    <form action="code.php" method="POST" enctype="multipart/form-data">

                        <label for="supplies"><h4>Select Supply</h4></label>
                        <select name="supplies" id="supplies" required>
                            <option value="toner">Toner</option>
                            <option value="drum">Drum</option>
                        </select>

                        <input type="file" name="import_file" class="form-control" />
                        <button type="submit" name="save_excel_data" class="btn btn-primary mt-3">Import</button>

                    </form>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>