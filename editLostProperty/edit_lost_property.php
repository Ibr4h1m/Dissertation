<?php
require '../db_connect.php'; // Ensure you have your database connection file included
require '../nav.php'; // Ensure you have your database connection file included

// Check if user is not logged in, redirect to index.php
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: ../index.php"); // Redirect to the home page
    exit;
}

$username = $_SESSION['username']; // Get the username from the session

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update'])) {
    $id = $_POST['id'];
    $driverCallsign = $_POST['driver_callsign'];
    $pickupDate = $_POST['pickup_date'];
    $pickupTime = $_POST['pickup_time'];
    $dropOffTime = $_POST['drop_off_time'];
    $propertyDescription = $_POST['property_description'];
    $itemColour = $_POST['item_colour'];
    $status = $_POST['status'];

    // Update the database
    $sql = "UPDATE lost_property SET driver_callsign = :driver_callsign, pickup_date = :pickup_date, pickup_time = :pickup_time, drop_off_time = :drop_off_time, property_description = :property_description, item_colour = :item_colour, status = :status WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['driver_callsign' => $driverCallsign, 'pickup_date' => $pickupDate, 'pickup_time' => $pickupTime, 'drop_off_time' => $dropOffTime, 'property_description' => $propertyDescription, 'item_colour' => $itemColour, 'status' => $status, 'id' => $id]);

    // Log the edit action
    $logDate = date('Y-m-d');
    $logTime = date('H:i:s');
    $actionTaken = 'edited';

    // Insert log entry
    $logSql = "INSERT INTO lost_property_logs (log_date, log_time, username, action_taken, record_id) VALUES (?, ?, ?, ?, ?)";
    $logStmt = $pdo->prepare($logSql);
    $logStmt->execute([$logDate, $logTime, $username, $actionTaken, $id]);

    // Redirect after update or display a success message
    header("Location: ../lost_property.php");
    exit;
} elseif (isset($_POST['id'])) {
    // Prepare to display the current data for editing
    $id = $_POST['id'];
    $sql = "SELECT * FROM lost_property WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['id' => $id]);
    $property = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$property) {
        echo "No record found.";
        exit;
    }
} else {
    echo "Invalid request.";
    exit;
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Lost Property</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <div class="row">
            <div class="col-lg-8 mx-auto">
                <div class="card shadow">
                    <div class="card-body">
                        <h2 class="card-title mb-4">Edit Lost Property</h2>
                        <form action="edit_lost_property.php" method="post">
                            <input type="hidden" name="id" value="<?= htmlspecialchars($property['id']) ?>">
                            <div class="mb-3">
                                <label for="driver_callsign" class="form-label">Driver Callsign:</label>
                                <input type="text" class="form-control" name="driver_callsign" id="driver_callsign" value="<?= htmlspecialchars($property['driver_callsign']) ?>" required>
                            </div>
                            <div class="mb-3">
                                <label for="pickup_date" class="form-label">Pickup Date:</label>
                                <input type="date" class="form-control" name="pickup_date" id="pickup_date" value="<?= date('Y-m-d', strtotime($property['pickup_date'])) ?>" required>
                            </div>
                            <div class="mb-3">
                                <label for="pickup_time" class="form-label">Pickup Time:</label>
                                <input type="time" class="form-control" name="pickup_time" id="pickup_time" value="<?= htmlspecialchars($property['pickup_time']) ?>" required>
                            </div>
                            <div class="mb-3">
                                <label for="drop_off_time" class="form-label">Dropoff Time:</label>
                                <input type="time" class="form-control" name="drop_off_time" id="drop_off_time" value="<?= htmlspecialchars($property['drop_off_time']) ?>" required>
                            </div>
                            <div class="mb-3">
                                <label for="property_description" class="form-label">Property Description:</label>
                                <textarea class="form-control" name="property_description" id="property_description" required><?= htmlspecialchars($property['property_description']) ?></textarea>
                            </div>
                            <div class="mb-3">
                                <label for="item_colour" class="form-label">Item Colour:</label>
                                <input type="text" class="form-control" name="item_colour" id="item_colour" value="<?= htmlspecialchars($property['item_colour']) ?>" required>
                            </div>
                            <div class="mb-3">
                                <label for="status" class="form-label">Status:</label>
                                <select class="form-select" name="status" id="status" required>
                                    <option value="pending" <?= $property['status'] == 'pending' ? 'selected' : '' ?>>Pending</option>
                                    <option value="returned" <?= $property['status'] == 'returned' ? 'selected' : '' ?>>Returned</option>
                                </select>
                            </div>
                            <button type="submit" name="update" class="btn btn-primary">Update</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Bootstrap Bundle with Popper.js -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
