<?php 
include ('dbcon.php');

if(isset($_POST['input']) && isset($_POST['barcode'])){
    // $selectedSupply = $_POST['input'];
    // $scanCode = $_POST['barcode'];

    // $query = "SELECT * FROM '{$selectedSupply}' WHERE CODE LIKE '{$scanCode}%'";

    // $result = mysqli_query($con, $query);

    // 2. Get user input (Sanitize input to prevent XSS)
    $selectedSupply = htmlspecialchars($_POST['input']); 
    $scanCode = htmlspecialchars($_POST['barcode']); 
    
    // 3. Prepare the SQL statement (using prepared statements to prevent SQL injection)
    $stmt = $con->prepare("SELECT * FROM `{$selectedSupply}` WHERE CODE LIKE ?");
    $stmt->bind_param("s", $scanCode); 

    // 4. Execute the statement
    $stmt->execute();
    $result = $stmt->get_result();


    if(mysqli_num_rows($result)) {
        
        while($row = $result->fetch_assoc()) {

            $description =$row['DESCRIPTION'];
            $model = $row['MODEL'];
            echo('MODEL : ' . $model . 'DESCRIPTION : ' . $description);

        }
        
    }else {
        echo('NO RECORD');
    }
}   
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js" integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy" crossorigin="anonymous"></script>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

</head>
    <body>
    <div class="table-responsive bg-white">
              <table class="table mb-0">
                <thead>
                  <tr>
                    <th scope="col">EMPLOYEES</th>
                    <th scope="col">POSITION</th>
                    <th scope="col">CONTACTS</th>
                    <th scope="col">AGE</th>
                    <th scope="col">ADDRESS</th>
                    <th scope="col">SALARY</th>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <th scope="row" style="color: #666666;">Tiger Nixon</th>
                    <td>System Architect</td>
                    <td>tnixon12@example.com</td>
                    <td>61</td>
                    <td>Edinburgh</td>
                    <td>$320,800</td>
                  </tr>
                  <tr>
                    <th scope="row" style="color: #666666;">Sonya Frost</th>
                    <td>Software Engineer</td>
                    <td>sfrost34@example.com</td>
                    <td>23</td>
                    <td>Edinburgh</td>
                    <td>$103,600</td>
                  </tr>
                  <tr>
                    <th scope="row" style="color: #666666;">Jena Gaines</th>
                    <td>Office Manager</td>
                    <td>jgaines75@example.com</td>
                    <td>30</td>
                    <td>London</td>
                    <td>$90,560</td>
                  </tr>
                  <tr>
                    <th scope="row" style="color: #666666;">Quinn Flynn</th>
                    <td>Support Lead</td>
                    <td>qflyn09@example.com</td>
                    <td>22</td>
                    <td>Edinburgh</td>
                    <td>$342,000</td>
                  </tr>
                  <tr>
                    <th scope="row" style="color: #666666;">Charde Marshall</th>
                    <td>Regional Director</td>
                    <td>cmarshall28@example.com</td>
                    <td>36</td>
                    <td>San Francisco</td>
                    <td>$470,600</td>
                  </tr>
                  <tr>
                    <th scope="row" style="color: #666666;">Haley Kennedy</th>
                    <td>Senior Marketing Designer</td>
                    <td>hkennedy63@example.com</td>
                    <td>43</td>
                    <td>London</td>
                    <td>$313,500</td>
                  </tr>
                  <tr>
                    <th scope="row" style="color: #666666;">Tatyana Fitzpatrick</th>
                    <td>Regional Director</td>
                    <td>tfitzpatrick00@example.com</td>
                    <td>19</td>
                    <td>Warsaw</td>
                    <td>$385,750</td>
                  </tr>
                </tbody>
              </table>
            </div>
    </body>
</html>

