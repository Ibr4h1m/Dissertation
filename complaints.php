<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Log Customer Complaint</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="manifest" href="manifest.json">
    <meta name="theme-color" content="#343a40">
    <style>
        * {
            text-align: center;
        }
        .container {
            max-width: 90%;
        }
        table {
            width: 100%;
        }
        .hide {
            display: none;
        }
    </style>
</head>

<?php
// Start the session at the beginning
require 'nav.php';
require 'db_connect.php';

// Check if the user is logged in, if not then redirect to login page
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("Location: login.php");
    exit;
}

// Define variables and initialize with empty values
$booking_id = $customer_name = $phone_number = $driver_name = $description = $action = "";

// Processing form data when form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $booking_id = trim($_POST["booking_id"]);
    $customer_name = trim($_POST["customer_name"]);
    $phone_number = trim($_POST["phone_number"]);
    $driver_name = trim($_POST["driver_name"]);
    $description = trim($_POST["description"]);
    $action = trim($_POST["action"]);
    $username = $_SESSION["username"]; // Assume username is stored in session

    // Prepare an insert statement
    $sql = "INSERT INTO customer_complaints (username, booking_id, customer_name, phone_number, driver_name, description, action) VALUES (:username, :booking_id, :customer_name, :phone_number, :driver_name, :description, :action)";

    if ($stmt = $pdo->prepare($sql)) {
        // Bind variables to the prepared statement as parameters
        $stmt->bindParam(":username", $username, PDO::PARAM_STR);
        $stmt->bindParam(":booking_id", $booking_id, PDO::PARAM_INT);
        $stmt->bindParam(":customer_name", $customer_name, PDO::PARAM_STR);
        $stmt->bindParam(":phone_number", $phone_number, PDO::PARAM_STR);
        $stmt->bindParam(":driver_name", $driver_name, PDO::PARAM_STR);
        $stmt->bindParam(":description", $description, PDO::PARAM_STR);
        $stmt->bindParam(":action", $action, PDO::PARAM_STR);

        // Attempt to execute the prepared statement
        if ($stmt->execute()) {
            // Records created successfully. Redirect or display a success message
            echo "<script>alert('Complaint logged successfully.'); window.location.href='/index.php';</script>";
            // header("Location: /index.php"); // Uncomment if you prefer to redirect
            exit();
        } else {
            echo "Oops! Something went wrong. Please try again later.";
        }
    }

    // Close statement
    unset($stmt);

    // Optionally, close connection. In this script, it might be reused if pdo is used elsewhere after this operation.
    // unset($pdo);
}

// Fetch all complaints from the database
try {
    $sql = "SELECT * FROM customer_complaints ORDER BY created_at DESC";
    $stmt = $pdo->query($sql);
    $complaints = $stmt->fetchAll();
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
?>

<body>
    <div class="container text-center mt-5">
        <h1 class="mb-4 bg-dark text-white p-2 rounded" style="cursor:pointer;" onclick="toggleForm()">Log a Customer Complaint</h1>
        <div id="formContainer" class="hide">
            <form action="" method="post">
                <div class="mb-3">
                    <label for="booking_id" class="form-label">Booking ID:</label>
                    <input type="text" class="form-control" name="booking_id" id="booking_id" required>
                </div>
                <div class="mb-3">
                    <label for="customer_name" class="form-label">Customer Name:</label>
                    <input type="text" class="form-control" name="customer_name" id="customer_name" required>
                </div>
                <div class="mb-3">
                    <label for="phone_number" class="form-label">Phone Number:</label>
                    <input type="text" class="form-control" name="phone_number" id="phone_number" required>
                </div>
                <div class="mb-3">
                    <label for="driver_name" class="form-label">Driver's Name:</label>
                    <input type="text" class="form-control" name="driver_name" id="driver_name" required>
                </div>
                <div class="mb-3">
                    <label for="description" class="form-label">Description:</label>
                    <textarea class="form-control" name="description" id="description" rows="3" required></textarea>
                </div>
                <div class="mb-3">
                    <label for="action" class="form-label">Action:</label>
                    <textarea class="form-control" name="action" id="action" rows="3" required></textarea>
                </div>
                <div class="d-grid">
                    <button type="submit" class="btn btn-primary">Log Complaint</button>
                </div>
            </form>
        </div>
    </div>
    
    <div class="container text-center mt-5">
        <div class="container text-center mt-5">
            <table class="table table-hover table-bordered">
                <thead class="table-dark">
                <tr>
                    <th>Booking ID</th>
                    <th>Customer Name</th>
                    <th>Phone Number</th>
                    <th>Driver Name</th>
                    <th>Description</th>
                    <th>Action Taken</th>
                    <th>Operations</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($complaints as $complaint): ?>
                <tr>
                    <td><?= htmlspecialchars($complaint['booking_id']) ?></td>
                    <td><?= htmlspecialchars($complaint['customer_name']) ?></td>
                    <td><?= htmlspecialchars($complaint['phone_number']) ?></td>
                    <td><?= htmlspecialchars($complaint['driver_name']) ?></td>
                    <td><?= htmlspecialchars($complaint['description']) ?></td>
                    <td><?= htmlspecialchars($complaint['action']) ?></td>
                    <td>
                        <a href="edit_complaint.php?id=<?= $complaint['complaint_id'] ?>" class="btn btn-primary btn-sm">Edit</a>
                        <a href="delete_complaint.php?id=<?= $complaint['complaint_id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this complaint?')">Delete</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    
    
    
    <script>
        function toggleForm() {
            var formContainer = document.getElementById('formContainer');
            if (formContainer.style.display === "none") {
                formContainer.style.display = "block";
            } else {
                formContainer.style.display = "none";
            }
        }
    </script>

    <!-- Bootstrap Bundle with Popper.js -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
