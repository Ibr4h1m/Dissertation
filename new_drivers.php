<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New Drivers</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="manifest" href="manifest.json">
    <meta name="theme-color" content="#343a40">
    <style>
        *{
            text-align: center;
        }
        .content-background {
            background-color: #f8f9fa; /* Light background color for content */
            border-radius: 5px;
            padding: 20px;
            margin-bottom: 20px;
        }
        .header-background {
            background-color: #343a40; /* Dark background color for header */
            color: #ffffff; /* White text color */
        }
    </style>
</head>
    

<?php
require 'db_connect.php';
require 'nav.php'; 


// Check if the user is not logged in or does not have the correct rank
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true || $_SESSION['rank'] != 2) {
    header("Location: index.php"); // Redirect to home page
    exit;
}
// Fetch new drivers data from database
$newDriversStmt = $pdo->query("SELECT * FROM drivers WHERE status = 0 ORDER BY date_started DESC");
$newDrivers = $newDriversStmt->fetchAll(PDO::FETCH_ASSOC);

// Fetch drivers data from database
try {
    $stmt = $pdo->query("SELECT * FROM drivers ORDER BY driver_id DESC");
    $drivers = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Could not connect to the database :" . $e->getMessage());
}


?>
    
<body>
    <div class="container text-center mt-5">
        <div class="bg-light border rounded-3 p-4"> <!-- Adjusted for subheading background -->
            <h1 class="mb-4 bg-dark text-white p-2 rounded">New Drivers</h1> <!-- Adjusted for dark background -->
            <div class="table-responsive">
                <table class="table table-hover table-bordered">
                    <thead class="table-dark"> <!-- Adjusted for light background -->
                        <tr>
                            <th>Driver ID</th>
                            <th>Date Started</th>
                            <th>Weeks Completed</th>
                            <th>Referral ID</th>
                            <th>Status</th>
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
                            <td><?= $dateStarted->format('d-m-Y') ?></td>
                            <td><?= floor($weeksCompleted) ?></td>
                            <td><?= htmlspecialchars($driver['referral_id']) ?></td>
                            <td><?= $weeksCompleted >= 4 ? 'Credit Pending' : 'Not Eligible'; ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <a href="editNewDrivers/new_drivers.php" class="btn btn-primary my-3">Edit</a>
        <?php $numberOfDrivers = count($newDrivers); ?>
        <p>Total Number of Drivers: <?= $numberOfDrivers ?></p>
        </div>

    </div>
    <!-- Bootstrap Bundle with Popper.js -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
