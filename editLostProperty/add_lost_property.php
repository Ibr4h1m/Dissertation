<?php
session_start();
require '../db_connect.php'; // Ensure this path correctly points to your database connection script

// Check if user is not logged in, redirect to index.php
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: ../index.php");
    exit;
}

// Check for form submission
if (isset($_POST['add_record'])) {
    // Extract and sanitize input
    $jobId = filter_input(INPUT_POST, 'job_id', FILTER_SANITIZE_NUMBER_INT);
    $driverCallsign = filter_input(INPUT_POST, 'driver_callsign', FILTER_SANITIZE_STRING);
    $pickupDate = filter_input(INPUT_POST, 'pickup_date', FILTER_SANITIZE_STRING);
    $pickupTime = filter_input(INPUT_POST, 'pickup_time', FILTER_SANITIZE_STRING);
    $dropOffTime = filter_input(INPUT_POST, 'drop_off_time', FILTER_SANITIZE_STRING);
    $propertyDescription = filter_input(INPUT_POST, 'property_description', FILTER_SANITIZE_STRING);
    $itemColour = filter_input(INPUT_POST, 'item_colour', FILTER_SANITIZE_STRING);
    $status = filter_input(INPUT_POST, 'status', FILTER_SANITIZE_STRING);

    // Prepare SQL statement to insert the new lost property record
    $sql = "INSERT INTO lost_property (operator_name, job_id, driver_callsign, pickup_date, pickup_time, drop_off_time, property_description, item_colour, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $pdo->prepare($sql);

    try {
        // Assuming 'operator_name' needs to be captured or is static for this example. Adjust as necessary.
        $operatorName = $_SESSION['username']; // Placeholder or retrieve from session or form
        
        $stmt->execute([$operatorName, $jobId, $driverCallsign, $pickupDate, $pickupTime, $dropOffTime, $propertyDescription, $itemColour, $status]);

        // Get the last inserted ID to use in the log
        $recordId = $pdo->lastInsertId();

        // Prepare to insert log entry
        $logDate = date('Y-m-d');
        $logTime = date('H:i:s');
        $username = $_SESSION['username']; // Retrieve username from session
        $actionTaken = 'added';

        // Insert log entry
        $logSql = "INSERT INTO lost_property_logs (log_date, log_time, username, action_taken, record_id) VALUES (?, ?, ?, ?, ?)";
        $logStmt = $pdo->prepare($logSql);
        $logStmt->execute([$logDate, $logTime, $username, $actionTaken, $recordId]);
        
        header("Location: lost_property.php");
        exit;
        
    } catch (PDOException $e) {
        // Handle error
        echo "Error: " . $e->getMessage();
    }
} else {
    // Form not submitted
    echo "Please submit the form.";
}
?>
