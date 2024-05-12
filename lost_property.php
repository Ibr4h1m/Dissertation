<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lost Property</title>
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
include 'db_connect.php';
include 'nav.php';

// Check if user is not logged in, redirect to index.php
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: index.php"); // Redirect to the home page
    exit;
}



// Attempt to query database table and retrieve data
try {
    $stmt = $pdo->query("SELECT * FROM lost_property ORDER BY pickup_date DESC");
    $lostProperties = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Could not connect to the database $dbname :" . $e->getMessage());
}

?>
    
<body>
    <div class="container text-center mt-5">
        <div class="bg-light border rounded-3 p-4">
            <h1 class="mb-4 bg-dark text-white p-2 rounded">Lost Property</h1>
            <div class="table-responsive">
                <table class="table table-hover table-bordered">
                    <thead class="table-dark">
                        <tr>
                            <th>ID</th>
                            <th>Operator Name</th>
                            <th>Job ID</th>
                            <th>Driver Callsign</th>
                            <th>Pickup Date</th>
                            <th>Pickup Time</th>
                            <th>Drop Off Time</th>
                            <th>Property Description</th>
                            <th>Item Colour</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($lostProperties as $property): ?>
                        <tr>
                            <td><?= htmlspecialchars($property['id']) ?></td>
                            <td><?= htmlspecialchars($property['operator_name']) ?></td>
                            <td><?= htmlspecialchars($property['job_id']) ?></td>
                            <td><?= htmlspecialchars($property['driver_callsign']) ?></td>
                            <td><?= (new DateTime($property['pickup_date']))->format('d-m-Y') ?></td>
                            <td><?= htmlspecialchars($property['pickup_time']) ?></td>
                            <td><?= htmlspecialchars($property['drop_off_time']) ?></td>
                            <td><?= htmlspecialchars($property['property_description']) ?></td>
                            <td><?= htmlspecialchars($property['item_colour']) ?></td>
                            <td><?= htmlspecialchars($property['status']) ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <a href="editLostProperty/lost_property.php" class="btn btn-primary my-3">Edit</a>
        </div>
    </div>
    <!-- Bootstrap Bundle with Popper.js -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>


