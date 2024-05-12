<?php
session_start();
require '../db_connect.php'; // Include your database connection

if (isset($_POST['delete']) && isset($_POST['id'])) {
    $id = $_POST['id'];

    // Begin transaction to ensure archiving, logging, and deletion actions are all successful
    $pdo->beginTransaction();

    try {
        // Archive the record: First, fetch the record to be deleted
        $selectSql = "SELECT * FROM lost_property WHERE id = :id";
        $selectStmt = $pdo->prepare($selectSql);
        $selectStmt->bindParam(':id', $id, PDO::PARAM_INT);
        $selectStmt->execute();
        $record = $selectStmt->fetch(PDO::FETCH_ASSOC);

        if (!$record) {
            throw new Exception('Record not found.');
        }

        // Insert the fetched record into the lost_property_archive table
        $archiveSql = "INSERT INTO lost_property_archive (id, operator_name, job_id, driver_callsign, pickup_date, pickup_time, drop_off_time, property_description, item_colour, status) VALUES (:id, :operator_name, :job_id, :driver_callsign, :pickup_date, :pickup_time, :drop_off_time, :property_description, :item_colour, :status)";
        $archiveStmt = $pdo->prepare($archiveSql);
        $archiveStmt->execute($record);

        // Prepare to insert log entry for deletion
        $logDate = date('Y-m-d');
        $logTime = date('H:i:s');
        $username = $_SESSION['username']; // Assuming username is stored in session
        $actionTaken = 'deleted';

        // Insert log entry into lost_property_logs
        $logSql = "INSERT INTO lost_property_logs (log_date, log_time, username, action_taken, record_id) VALUES (?, ?, ?, ?, ?)";
        $logStmt = $pdo->prepare($logSql);
        $logStmt->execute([$logDate, $logTime, $username, $actionTaken, $id]);

        // Finally, delete the original record from lost_property
        $deleteSql = "DELETE FROM lost_property WHERE id = :id";
        $deleteStmt = $pdo->prepare($deleteSql);
        $deleteStmt->bindParam(':id', $id, PDO::PARAM_INT);
        $deleteStmt->execute();

        // Commit transaction if all actions are successful
        $pdo->commit();
        $_SESSION['message'] = 'Record archived, deleted successfully, and action logged.';

    } catch (Exception $e) {
        // Rollback transaction in case of any error
        $pdo->rollback();
        $_SESSION['error'] = 'Operation failed: ' . $e->getMessage();
    }

    header("Location: lost_property.php");
    exit();
} else {
    $_SESSION['error'] = 'Invalid request.';
    header("Location: lost_property.php");
    exit();
}
?>
