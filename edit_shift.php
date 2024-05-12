<?php
require 'db_connect.php'; // Make sure this points to your actual DB connection file
require 'nav.php'; // Include your navigation bar

// Check if shift ID is present in the URL
if (isset($_GET['shift_id'])) {
    $shiftId = $_GET['shift_id'];

    // Fetch the existing shift details
    $stmt = $pdo->prepare("SELECT * FROM shifts WHERE shift_id = ?");
    $stmt->execute([$shiftId]);
    $shift = $stmt->fetch();

    if (!$shift) {
        echo "<script>alert('Shift not found!'); window.location.href='shifts.php';</script>";
    }
} else {
    echo "<script>alert('Shift ID not specified.'); window.location.href='shifts.php';</script>";
}

// Handle the form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get the form data
    $clockIn = $_POST['clock_in'];
    $clockOut = $_POST['clock_out'];
    $totalHours = $_POST['total_hours'];

    // Prepare the update statement
    $stmt = $pdo->prepare("UPDATE shifts SET clock_in = ?, clock_out = ?, total_hours = ? WHERE shift_id = ?");
    $stmt->execute([$clockIn, $clockOut, $totalHours, $shiftId]);

    // Redirect back to the shifts page
    echo "<script>alert('Shift updated successfully.'); window.location.href='shifts.php';</script>";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Shift</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        *{
            text-align: center;
        }
        .container {
            max-width: 600px;
            margin-top: 40px;
        }
        form {
            margin: 20px 0;
        }
        form input, form button {
            margin: 5px 0;
        }
    </style>
</head>
<body>

<div class="container">
    <h2 class="my-4">Edit Shift</h2>

    <form action="" method="post">
        <div class="form-group">
            <label for="clock_in">Clock In</label>
            <input type="datetime-local" id="clock_in" name="clock_in" class="form-control" value="<?= date('Y-m-d\TH:i', strtotime($shift['clock_in'])) ?>">
        </div>
        <div class="form-group">
            <label for="clock_out">Clock Out</label>
            <input type="datetime-local" id="clock_out" name="clock_out" class="form-control" value="<?= $shift['clock_out'] ? date('Y-m-d\TH:i', strtotime($shift['clock_out'])) : '' ?>">
        </div>
        <div class="form-group">
            <label for="total_hours">Total Hours</label>
            <input type="text" id="total_hours" name="total_hours" class="form-control" value="<?= $shift['total_hours'] ?>">
        </div>
        <button type="submit" class="btn btn-primary">Update Shift</button>
    </form>
</div>

<!-- Bootstrap Bundle with Popper.js -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
