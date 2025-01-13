<?Php
include('dbcon.php');

    if(isset($_POST["quantity"]) && isset($_POST["code"]) && isset($_POST["owner"])){

        $quantity = htmlspecialchars($_POST['quantity']);
        $code = htmlspecialchars($_POST['code']);
        $owner = htmlspecialchars($_POST['owner']);

        $stmnt = $con->prepare("UPDATE")
    }
?>