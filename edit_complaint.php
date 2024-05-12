<?php
require 'nav.php';
require 'db_connect.php';

// Check if user is logged in
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("Location: login.php");
    exit;
}

$complaint_id = $booking_id = $customer_name = $phone_number = $driver_name = $description = $action = "";

// Processing form data when form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if complaint ID is set
    if(isset($_POST["id"]) && !empty($_POST["id"])){
        $complaint_id = $_POST["id"];
        $booking_id = trim($_POST["booking_id"]);
        $customer_name = trim($_POST["customer_name"]);
        $phone_number = trim($_POST["phone_number"]);
        $driver_name = trim($_POST["driver_name"]);
        $description = trim($_POST["description"]);
        $action = trim($_POST["action"]);
        
        // Prepare an update statement
        $sql = "UPDATE customer_complaints SET booking_id = :booking_id, customer_name = :customer_name, phone_number = :phone_number, driver_name = :driver_name, description = :description, action = :action WHERE complaint_id = :complaint_id";
        
        if ($stmt = $pdo->prepare($sql)) {
            // Bind variables to the prepared statement as parameters
            $stmt->bindParam(":booking_id", $booking_id, PDO::PARAM_INT);
            $stmt->bindParam(":customer_name", $customer_name, PDO::PARAM_STR);
            $stmt->bindParam(":phone_number", $phone_number, PDO::PARAM_STR);
            $stmt->bindParam(":driver_name", $driver_name, PDO::PARAM_STR);
            $stmt->bindParam(":description", $description, PDO::PARAM_STR);
            $stmt->bindParam(":action", $action, PDO::PARAM_STR);
            $stmt->bindParam(":complaint_id", $complaint_id, PDO::PARAM_INT);
            
            // Attempt to execute the prepared statement
            if ($stmt->execute()) {
                // Records updated successfully. Redirect to landing page
                header("Location: index.php");
                exit();
            } else {
                echo "Oops! Something went wrong. Please try again later.";
            }
        }
        
        // Close statement
        unset($stmt);
    }
    
    // Close connection
    unset($pdo);
} else {
    // Check existence of id parameter before processing further
    if(isset($_GET["id"]) && !empty(trim($_GET["id"]))){
        // Get URL parameter
        $complaint_id =  trim($_GET["id"]);
        
        // Prepare a select statement
        $sql = "SELECT * FROM customer_complaints WHERE complaint_id = :complaint_id";
        if($stmt = $pdo->prepare($sql)){
            // Bind variables to the prepared statement as parameters
            $stmt->bindParam(":complaint_id", $complaint_id, PDO::PARAM_INT);
            
            // Attempt to execute the prepared statement
            if($stmt->execute()){
                if($stmt->rowCount() == 1){
                    /* Fetch result row as an associative array. Since the result set
                    contains only one row, no need to use while loop */
                    $row = $stmt->fetch(PDO::FETCH_ASSOC);
                
                    // Retrieve individual field value
                    $booking_id = $row["booking_id"];
                    $customer_name = $row["customer_name"];
                    $phone_number = $row["phone_number"];
                    $driver_name = $row["driver_name"];
                    $description = $row["description"];
                    $action = $row["action"];
                } else{
                    // URL doesn't contain valid id. Redirect to error page
                    header("Location: error.php");
                    exit();
                }
                
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }
        }
        
        // Close statement
        unset($stmt);
        
        // Close connection
        unset($pdo);
    }  else {
        // URL doesn't contain id parameter. Redirect to error page
        header("Location: error.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Complaint</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        *{
            text-align: center;
        }
        body {
            font-family: Arial, sans-serif;
        }
        .wrapper {
            width: 100%;
            max-width: 600px;
            padding: 20px;
            margin: auto;
        }
        .form-group {
            margin-bottom: 1rem;
        }
        .form-group label {
            margin-bottom: 0.5rem;
        }
        .form-control {
            display: block;
            width: 100%;
            padding: 0.375rem 0.75rem;
            font-size: 1rem;
            font-weight: 400;
            line-height: 1.5;
            color: #212529;
            background-color: #fff;
            background-clip: padding-box;
            border: 1px solid #ced4da;
            border-radius: 0.25rem;
            transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
        }
        .btn-primary {
            color: #fff;
            background-color: #007bff;
            border-color: #007bff;
        }
        .btn-default {
            color: #333;
            background-color: #fff;
            border-color: #ccc;
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <h2>Edit Complaint</h2>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group">
                <label>Booking ID</label>
                <input type="text" name="booking_id" class="form-control" value="<?php echo $booking_id; ?>">
            </div>
            <div class="form-group">
                <label>Customer Name</label>
                <input type="text" name="customer_name" class="form-control" value="<?php echo $customer_name; ?>">
            </div>
            <div class="form-group">
                <label>Phone Number</label>
                <input type="text" name="phone_number" class="form-control" value="<?php echo $phone_number; ?>">
            </div>
            <div class="form-group">
                <label>Driver Name</label>
                <input type="text" name="driver_name" class="form-control" value="<?php echo $driver_name; ?>">
            </div>
            <div class="form-group">
                <label>Description</label>
                <textarea name="description" class="form-control"><?php echo $description; ?></textarea>
            </div>
            <div class="form-group">
                <label>Action</label>
                <textarea name="action" class="form-control"><?php echo $action; ?></textarea>
            </div>
            <input type="hidden" name="id" value="<?php echo $complaint_id; ?>"/>
            <input type="submit" class="btn btn-primary" value="Submit">
            <a href="index.php" class="btn btn-default">Cancel</a>
        </form>
    </div>    
</body>
</html>
