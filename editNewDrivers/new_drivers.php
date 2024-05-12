<?php
require '../db_connect.php'; // Include your database connection file
require '../nav.php'; // Include your database connection file


// Check if the user is not logged in or does not have the correct rank
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true || $_SESSION['rank'] != 2) {
    header("Location: ../index.php"); // Redirect to home page
    exit;
}
// Fetch drivers data from database
try {
    $stmt = $pdo->query("SELECT * FROM drivers ORDER BY driver_id DESC");
    $drivers = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Could not connect to the database :" . $e->getMessage());
}

// Handle mark as paid action
if (isset($_POST['mark_paid'])) {
    $driverId = $_POST['driver_id'];
    $updateStmt = $pdo->prepare("UPDATE drivers SET status = 1 WHERE driver_id = ?");
    $updateStmt->execute([$driverId]);
    // Prepare to insert log entry
        $logDate = date('Y-m-d');
        $logTime = date('H:i:s');
        $actionTaken = 'Paid';
        $username = $_SESSION['username']; // Retrieve username from session

        // Prepare SQL statement for logging
        $logSql = "INSERT INTO drivers_logs (log_date, log_time, username, action_taken, driver_id) VALUES (?, ?, ?, ?, ?)";
        $logStmt = $pdo->prepare($logSql);
        
        $logStmt->execute([$logDate, $logTime, $username, $actionTaken, $driverId]);
    
    header("Location: new_drivers.php");
    exit;
}

// Fetch new drivers data from database
$newDriversStmt = $pdo->query("SELECT * FROM drivers WHERE status = 0 ORDER BY date_started DESC");
$newDrivers = $newDriversStmt->fetchAll(PDO::FETCH_ASSOC);

// Fetch paid drivers data from database
$paidDriversStmt = $pdo->query("SELECT * FROM drivers WHERE status = 1 ORDER BY date_started DESC");
$paidDrivers = $paidDriversStmt->fetchAll(PDO::FETCH_ASSOC);


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New Drivers</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .centered-form .form-group, .centered-form .btn, .action-buttons form {
            margin-right: 5px; /* Adjust spacing between action buttons */
        }
        .centered-form, .action-buttons {
            text-align: center; /* Center content */
        }
        .action-buttons form {
            display: inline; /* Display buttons next to each other */
        }
        *{
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="container text-center mt-5">
        <div class="bg-light border rounded-3 p-4 mb-5">
            <h1 class="mb-4 bg-dark text-white p-2 rounded">Add Driver</h1>
            <div class="centered-form">
                <form action="add_driver.php" method="post">
                    <div class="form-group">
                        <label for="driver_id">Driver ID:</label>
                        <input type="number" class="form-control" id="driver_id" name="driver_id" required>
                    </div>
                    <div class="form-group">
                        <label for="date_started">Date Started:</label>
                        <input type="date" class="form-control" id="date_started" name="date_started" required>
                    </div>
                    <div class="form-group">
                        <label for="referral_id">Referral ID (optional):</label>
                        <input type="text" class="form-control" id="referral_id" name="referral_id">
                    </div>
                    <br>
                    <button type="submit" name="add_driver" class="btn btn-primary">Add Driver</button>
                </form>
                <br><br>
            </div>
            <h2 class="mb-4 bg-dark text-white p-2 rounded">New Drivers</h2>
            <div class="table-responsive">
                <table class="table table-hover table-bordered">
                    <thead class="table-dark">
                        <tr>
                            <th>Driver ID</th>
                            <th>Date Started</th>
                            <th>Weeks Completed</th>
                            <th>Referral ID</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($newDrivers as $driver): 
                            $dateStarted = new DateTime($driver['date_started']);
                            $currentDate = new DateTime(); // Today's date
                            $weeksCompleted = $dateStarted->diff($currentDate)->days / 7; // Calculate weeks completed
                        ?>
                        <tr>
                            <td><?= htmlspecialchars($driver['driver_id']) ?></td>
                            <td><?= htmlspecialchars((new DateTime($driver['date_started']))->format('d-m-Y')) ?></td>
                            <td><?= floor((new DateTime())->diff(new DateTime($driver['date_started']))->days / 7) ?></td>
                            <td><?= htmlspecialchars($driver['referral_id']) ?></td>
                            <td><?= $weeksCompleted >= 4 ? 'Credit Pending' : 'Not Eligible'; ?></td>
                            <td class="action-buttons">
                                <form action="new_drivers.php" method="post">
                                    <input type="hidden" name="driver_id" value="<?= $driver['driver_id'] ?>">
                                    <button type="submit" name="mark_paid" class="btn btn-success btn-sm">Pay</button>
                                </form>
                                <form action="delete_driver.php" method="post" onsubmit="return confirm('Are you sure?');">
                                    <input type="hidden" name="driver_id" value="<?= $driver['driver_id'] ?>">
                                    <button type="submit" name="delete_driver" class="btn btn-danger btn-sm">Delete</button>
                                </form>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            
        </div>
        <div class="bg-light border rounded-3 p-4">
            <h2 class="mb-4 bg-dark text-white p-2 rounded">Paid Drivers</h2>
            <div class="table-responsive">
                <table class="table table-hover table-bordered">
                    <thead class="table-dark">
                        <tr>
                            <th>Driver ID</th>
                            <th>Date Started</th>
                            <th>Weeks Completed</th>
                            <th>Referral ID</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($paidDrivers as $driver): ?>
                        <tr>
                            <td><?= htmlspecialchars($driver['driver_id']) ?></td>
                            <td><?= htmlspecialchars((new DateTime($driver['date_started']))->format('d-m-Y')) ?></td>
                            <td><?= floor((new DateTime())->diff(new DateTime($driver['date_started']))->days / 7) ?></td>
                            <td><?= htmlspecialchars($driver['referral_id']) ?></td>
                            <td><?= $driver['status'] == 1 ? 'Credited' : 'Pending'; ?></td>
                            <td class="action-buttons">
                                <form action="delete_driver.php" method="post" onsubmit="return confirm('Are you sure?');">
                                    <input type="hidden" name="driver_id" value="<?= $driver['driver_id'] ?>">
                                    <button type="submit" name="delete_driver" class="btn btn-danger btn-sm">Delete</button>
                                </form>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <!-- Bootstrap Bundle with Popper.js -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
