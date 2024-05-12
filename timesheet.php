<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employee Timesheet</title>
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

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: index.php"); // Redirect to the home page
    exit;
}

// Function to check if the user is currently clocked in
function isClockedIn($pdo, $username) {
    $stmt = $pdo->prepare("SELECT * FROM shifts WHERE username = ? AND clock_out IS NULL ORDER BY shift_id DESC LIMIT 1");
    $stmt->execute([$username]);
    $row = $stmt->fetch();
    return $row !== false ? $row : null;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['clockIn']) && !isClockedIn($pdo, $username)) {
        $clockIn = date('Y-m-d H:i:s');
        $stmt = $pdo->prepare("INSERT INTO shifts (username, clock_in) VALUES (?, ?)");
        $stmt->execute([$username, $clockIn]);
    } elseif (isset($_POST['clockOut'])) {
        $clockedInData = isClockedIn($pdo, $username);
        if ($clockedInData) {
            $clockOut = date('Y-m-d H:i:s');
            $clockInTime = new DateTime($clockedInData['clock_in']);
            $clockOutTime = new DateTime($clockOut);
            $interval = $clockInTime->diff($clockOutTime);
            $hours = $interval->h + ($interval->i / 60) + ($interval->s / 3600);
            $stmt = $pdo->prepare("UPDATE shifts SET clock_out = ?, total_hours = ? WHERE username = ? AND clock_out IS NULL");
            $stmt->execute([$clockOut, $hours, $username]);
        }
    }

    // Redirecting to stop from resubmitting form.
    header('Location: ' . $_SERVER['PHP_SELF']);
    exit;
}
?>
    
<body>
    <div class="container text-center mt-5">
        <div class="bg-light border rounded-3 p-4">
            <h1 class="mb-4 bg-dark text-white p-2 rounded">Your Timesheet</h1>
            <form method="post" class="mb-4">
                <?php if (!isClockedIn($pdo, $username)): ?>
                    <button type="submit" name="clockIn" class="btn btn-success">Clock In</button>
                <?php else: ?>
                    <button type="submit" name="clockOut" class="btn btn-danger">Clock Out</button>
                <?php endif; ?>
            </form>
            <div class="table-responsive">
                <table class="table table-hover table-bordered">
                    <thead class="table-dark">
                        <tr>
                            <th>Date</th>
                            <th>Clock In</th>
                            <th>Clock Out</th>
                            <th>Total Hours</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $stmt = $pdo->prepare("SELECT DATE(clock_in) as date, clock_in, clock_out, total_hours FROM shifts WHERE username = ? ORDER BY date ASC");
                        $stmt->execute([$username]);
                        while ($row = $stmt->fetch()) {
                            echo "<tr>";
                            echo "<td>" . date('d/m/Y', strtotime($row['date'])) . "</td>"; // Formats and displays the date in d/m/Y format
                            echo "<td>" . date('H:i', strtotime($row['clock_in'])) . "</td>"; // Formats and displays only the time for clock_in
                            echo "<td>" . (isset($row['clock_out']) ? date('H:i', strtotime($row['clock_out'])) : 'N/A') . "</td>"; // Formats and displays only the time for clock_out, or 'N/A' if null
                            echo "<td>" . number_format($row['total_hours'], 2) . "</td>"; // Formats the hours to 2 decimal places
                            echo "</tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <!-- Bootstrap Bundle with Popper.js -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
