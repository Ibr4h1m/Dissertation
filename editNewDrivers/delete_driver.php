<?php
session_start();
require '../db_connect.php'; // Adjust the path as necessary

// Check user permissions
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true || $_SESSION['rank'] != 2) {
    header("Location: ../index.php");
    exit;
}

if (isset($_POST['delete_driver'])) {
    $driverId = $_POST['driver_id'];
    
    // Check if the new_drivers_archive table exists, create it if not
    $pdo->exec("CREATE TABLE IF NOT EXISTS new_drivers_archive LIKE drivers");

    // First, archive the record
    $archiveStmt = $pdo->prepare("INSERT INTO new_drivers_archive SELECT * FROM drivers WHERE driver_id = ?");
    $archiveSuccess = $archiveStmt->execute([$driverId]);

    if ($archiveSuccess) {
        // Proceed with deleting the driver from the original table
        $deleteStmt = $pdo->prepare("DELETE FROM drivers WHERE driver_id = ?");
        if ($deleteStmt->execute([$driverId])) {
            // Redirect back with a success message
            $_SESSION['success_message'] = 'Driver deleted successfully.';
            // Logging code as before
            $logDate = date('Y-m-d');
            $logTime = date('H:i:s');
            $actionTaken = 'Deleted';
            $username = $_SESSION['username']; // Retrieve username from session

            // Prepare SQL statement for logging
            $logSql = "INSERT INTO drivers_logs (log_date, log_time, username, action_taken, driver_id) VALUES (?, ?, ?, ?, ?)";
            $logStmt = $pdo->prepare($logSql);
            $logStmt->execute([$logDate, $logTime, $username, $actionTaken, $driverId]);
            
        } else {
            // Redirect back with an error message
            $_SESSION['error_message'] = 'Error deleting driver.';
        }
    } else {
        // If archiving fails, set an error message
        $_SESSION['error_message'] = 'Error archiving driver record.';
    }
}

header("Location: new_drivers.php"); // Redirect back to the drivers page
exit;
?>
