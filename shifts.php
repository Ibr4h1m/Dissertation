<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shifts</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="manifest" href="manifest.json">
    <meta name="theme-color" content="#343a40">
    <style>
        *{
            text-align: center;
        }
    </style>
</head>

<?php
require 'db_connect.php';
require 'nav.php';
date_default_timezone_set('Europe/London');

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true || $_SESSION['rank'] != 2) {
    header("Location: index.php"); // Redirect to home page
    exit;
}

// Check if user is logged in and their rank
$userLoggedIn = isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true;
$username = $_SESSION['username']; 
$userRank = isset($_SESSION['rank']) ? $_SESSION['rank'] : null;

// Get all uses' summary
$summaryQuery = "SELECT username, COUNT(shift_id) AS number_of_shifts, SUM(total_hours) AS total_hours FROM shifts GROUP BY username";
$summaryStmt = $pdo->query($summaryQuery);
$usersSummary = $summaryStmt->fetchAll(PDO::FETCH_ASSOC);

// get details for each user
$shiftsQuery = "SELECT shift_id, DATE_FORMAT(clock_in, '%d/%m/%Y') AS date, TIME_FORMAT(clock_in, '%H:%i') AS clock_in_time, TIME_FORMAT(clock_out, '%H:%i') AS clock_out_time, total_hours FROM shifts WHERE username = ? ORDER BY clock_in ASC";
$shiftsStmt = $pdo->prepare($shiftsQuery);

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['deleteClosedShifts'])) {
    $deleteQuery = "DELETE FROM shifts WHERE clock_out IS NOT NULL";
    $result = $pdo->exec($deleteQuery);

    if ($result) {
        echo "<script>alert('All closed shifts have been deleted successfully.');</script>";
    } else {
        echo "<script>alert('An error occurred while deleting the shifts.');</script>";
    }

    // Reload page to show new data
    echo "<script>window.location = 'shifts.php';</script>";
}

if (isset($_GET['delete_shift'])) {
    $shiftId = $_GET['delete_shift'];
    $deleteShiftQuery = "DELETE FROM shifts WHERE shift_id = ?";
    $stmt = $pdo->prepare($deleteShiftQuery);
    $stmt->execute([$shiftId]);

    // Redirect after deleting 
    echo "<script>alert('Shift deleted successfully.'); window.location.href='shifts.php';</script>";
}


?>

<body>
    <div class="container text-center mt-5">
        <div class="bg-light border rounded-3 p-4 my-4">
            <h1 class="mb-4 bg-dark text-white p-2 rounded">Timesheet</h1>
            <div class="table-responsive">
                <table class="table table-hover table-bordered">
                    <thead class="table-dark">
                        <tr>
                            <th>User Name</th>
                            <th>Number of Shifts</th>
                            <th>Total Hours</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($usersSummary as $user): ?>
                        <tr>
                            <td><?= htmlspecialchars(ucfirst($user['username'])) ?></td>
                            <td><?= htmlspecialchars($user['number_of_shifts']) ?></td>
                            <td><?= number_format($user['total_hours'], 2) ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <button class="btn btn-primary mb-4" onclick="printTables()">Print Tables</button>
            <script>
                function printTables() {
                    window.print();
                }
            </script>
            <form action="shifts.php" method="post" onsubmit="return confirm('Are you sure you want to delete all closed shifts?');">
                <button type="submit" name="deleteClosedShifts" class="btn btn-danger">Delete Closed Shifts</button>
            </form>
        </div>
            <?php foreach ($usersSummary as $user): ?>
            <div class="bg-light border rounded-3 p-4 my-4">
                <h2 class="bg-dark text-white p-2 rounded">Shifts for <?= htmlspecialchars(ucfirst($user['username'])) ?></h2>
                <div class="table-responsive">
                    <table class="table table-hover table-bordered">
                        <thead class="table-dark">
                            <tr>
                                <th>Shift ID</th>
                                <th>Date</th>
                                <th>Clock In</th>
                                <th>Clock Out</th>
                                <th>Hours</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $shiftsStmt->execute([$user['username']]);
                            $shifts = $shiftsStmt->fetchAll(PDO::FETCH_ASSOC);
                            foreach ($shifts as $shift):
                            ?>
                            <tr>
                                <td><?= htmlspecialchars($shift['shift_id']) ?></td>
                                <td><?= htmlspecialchars($shift['date']) ?></td>
                                <td><?= htmlspecialchars($shift['clock_in_time']) ?></td>
                                <td><?= htmlspecialchars($shift['clock_out_time']) ?></td>
                                <td><?= number_format($shift['total_hours'], 2) ?></td>
                                <td>
                                    <a href="edit_shift.php?shift_id=<?= $shift['shift_id'] ?>" class="btn btn-primary">Edit</a>
                                    <a href="?delete_shift=<?= $shift['shift_id'] ?>" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this shift?');">Delete</a>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>

                </div>
            </div>
            <?php endforeach; ?>
        </div>
    <!-- Bootstrap Bundle with Popper.js -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
