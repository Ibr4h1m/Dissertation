<?php
session_start();
require '../db_connect.php'; // Adjust path as needed

// Check if user is logged in and has the correct permissions
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true || $_SESSION['rank'] != 2) {
    header("Location: ../index.php"); // Redirect if not permitted
    exit;
}

$message = ''; // Initialize message variable

if (isset($_POST['add_driver'])) {
    $driverId = $_POST['driver_id'];
    $dateStarted = $_POST['date_started'];
    $referralId = $_POST['referral_id'] ?? ''; // Assuming this field exists and using null coalescing operator for PHP 7+

    // First, check if the driver already exists
    $checkSql = "SELECT * FROM drivers WHERE driver_id = ?";
    $checkStmt = $pdo->prepare($checkSql);
    $checkStmt->execute([$driverId]);
    
    if ($checkStmt->rowCount() > 0) {
        // Driver already exists
        $message = 'alert("Error: Driver already exists.");';
    } else {
        // Prepare SQL statement to insert the new driver
        $sql = "INSERT INTO drivers (driver_id, date_started, referral_id) VALUES (?, ?, ?)";
        $stmt = $pdo->prepare($sql);
        
        try {
            $stmt->execute([$driverId, $dateStarted, $referralId]);

            // Prepare to insert log entry
            $logDate = date('Y-m-d');
            $logTime = date('H:i:s');
            $actionTaken = 'Added';
            $username = $_SESSION['username']; // Retrieve username from session

            // Prepare SQL statement for logging
            $logSql = "INSERT INTO drivers_logs (log_date, log_time, username, action_taken, driver_id) VALUES (?, ?, ?, ?, ?)";
            $logStmt = $pdo->prepare($logSql);
            $logStmt->execute([$logDate, $logTime, $username, $actionTaken, $driverId]);
            
            // Driver added successfully
            $message = 'alert("Driver added successfully.");';
        } catch(PDOException $e) {
            // Handle error
            $message = 'alert("Error: ' . addslashes($e->getMessage()) . '");';
        }
    }

    // Redirect with JavaScript alert
    echo "<script>$message window.location.href = 'new_drivers.php';</script>";
    exit;
}
?>
