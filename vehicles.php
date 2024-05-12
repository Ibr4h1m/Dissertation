<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vehicles</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="manifest" href="manifest.json">
    <meta name="theme-color" content="#343a40">
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
        .alert {
            margin-bottom: 20px; /* Ensure spacing between alert and form */
        }
    </style>
    <script>
        function toggleForm() {
            var x = document.getElementById("addVehicleForm");
            if (x.style.display === "none") {
                x.style.display = "block";
            } else {
                x.style.display = "none";
            }
        }
    </script>
</head>
    
<?php
require 'db_connect.php'; // Ensure this path is correct
require 'nav.php';

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true || $_SESSION['rank'] != 2) {
    header("Location: index.php"); // Redirect to home page
    exit;
}

$errorMessage = '';
$successMessage = '';

// Adding a new vehicle
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['addVehicle'])) {
    // Extract and sanitize input
    $registration = filter_input(INPUT_POST, 'registration', FILTER_SANITIZE_STRING);
    $motExpiry = $_POST['mot_expiry'];
    $councilName = $_POST['council_name'];
    $plateExpiry = $_POST['plate_expiry'];
    $interimExpiry = !empty($_POST['interim_expiry']) ? $_POST['interim_expiry'] : null; // Set to null if empty

    // Basic validation (You should expand upon this)
    if (!empty($registration) && !empty($motExpiry) && !empty($councilName) && !empty($plateExpiry)) {
        try {
            $sql = "INSERT INTO vehicles (registration, mot_expiry, council_name, plate_expiry, interim_expiry) VALUES (?, ?, ?, ?, ?)";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$registration, $motExpiry, $councilName, $plateExpiry, $interimExpiry]);
            $successMessage = "Vehicle added successfully.";
        } catch (PDOException $e) {
            $errorMessage = "Error adding vehicle: " . $e->getMessage();
        }
    } else {
        $errorMessage = "Please fill in all required fields.";
    }
}

// Fetching all vehicles and filtering for expiring soon
$vehicles = [];
$expiringSoonVehicles = []; // Initialize an array to hold vehicles expiring soon
try {
    $vehiclesQuery = "SELECT * FROM vehicles";
    $vehiclesStmt = $pdo->query($vehiclesQuery);
    $tenWeeksFromNow = new DateTime('+10 weeks');
    while ($vehicle = $vehiclesStmt->fetch(PDO::FETCH_ASSOC)) {
        $motExpiry = new DateTime($vehicle['mot_expiry']);
        $plateExpiry = new DateTime($vehicle['plate_expiry']);
        $interimExpiry = $vehicle['interim_expiry'] ? new DateTime($vehicle['interim_expiry']) : null;

        if ($motExpiry <= $tenWeeksFromNow || $plateExpiry <= $tenWeeksFromNow || ($interimExpiry && $interimExpiry <= $tenWeeksFromNow)) {
            $expiringSoonVehicles[] = $vehicle; // Add to expiring soon list
        } else {
            $vehicles[] = $vehicle; // Otherwise, add to regular list
        }
    }
} catch (PDOException $e) {
    $errorMessage = "Error fetching vehicles: " . $e->getMessage();
}

?>
    
<body>
<div class="container mt-5">
    <div class="bg-light border rounded-3 p-4">
        <h1 class="mb-4 bg-dark text-white p-2 rounded" style="cursor:pointer;" onclick="toggleForm()">Vehicles Management</h1>
        <?php if ($errorMessage): ?>
            <div class="alert alert-danger"><?= $errorMessage ?></div>
        <?php endif; ?>
        <?php if ($successMessage): ?>
            <div class="alert alert-success"><?= $successMessage ?></div>
        <?php endif; ?>

        <div id="addVehicleForm" style="display: none;">
            <form action="vehicles.php" method="post">
                <div class="form-group">
                    <label for="registration" class="form-label">Registration</label>
                    <input type="text" class="form-control" id="registration" name="registration" required>
                </div>
                <div class="form-group">
                    <label for="council_name" class="form-label">Council Name</label>
                    <select class="form-select" id="council_name" name="council_name" required>
                        <option value="">Select a Council</option>
                        <option value="SOT">SOT</option>
                        <option value="Wolverhampton">Wolverhampton</option>
                        <option value="Ashfield">Ashfield</option>
                        <option value="Newcastle">Newcastle</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="mot_expiry" class="form-label">MOT Expiry</label>
                    <input type="date" class="form-control" id="mot_expiry" name="mot_expiry" required>
                </div>
                <div class="form-group">
                    <label for="plate_expiry" class="form-label">Plate Expiry</label>
                    <input type="date" class="form-control" id="plate_expiry" name="plate_expiry" required>
                </div>
                <div class="form-group">
                    <label for="interim_expiry" class="form-label">Interim Expiry (Optional)</label>
                    <input type="date" class="form-control" id="interim_expiry" name="interim_expiry">
                </div>
                <button type="submit" name="addVehicle" class="btn btn-primary mt-2">Add Vehicle</button>
            </form>
        </div>

        <div id="vehicleList">
            <h2 class="mt-5 mb-4 bg-dark text-white p-2 rounded">Vehicle List</h2>
            <div class="table-responsive">
                <table class="table table-hover table-bordered">
                    <thead class="table-dark">
                        <tr>
                            <th>ID</th>
                            <th>Registration</th>
                            <th>Council Name</th>
                            <th>MOT Expiry</th>
                            <th>Plate Expiry</th>
                            <th>Interim Expiry</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($vehicles as $vehicle): ?>
                            <tr>
                                <td><?= htmlspecialchars($vehicle['id']) ?></td>
                                <td><?= htmlspecialchars($vehicle['registration']) ?></td>
                                <td><?= htmlspecialchars($vehicle['council_name']) ?></td>
                                <td><?= date('d/m/Y', strtotime($vehicle['mot_expiry'])) ?></td>
                                <td><?= date('d/m/Y', strtotime($vehicle['plate_expiry'])) ?></td>
                                <td><?= $vehicle['interim_expiry'] ? date('d/m/Y', strtotime($vehicle['interim_expiry'])) : '' ?></td>
                                <td class="action-buttons">
                                    <a href="edit_vehicle.php?vehicleId=<?= $vehicle['id'] ?>" class="btn btn-warning btn-sm">Edit</a>
                                    <form method="post" action="delete_vehicle.php" style="display: inline-block;" onsubmit="return confirm('Are you sure?');">
                                        <input type="hidden" name="vehicleId" value="<?= $vehicle['id'] ?>">
                                        <button type="submit" name="deleteVehicle" class="btn btn-danger btn-sm">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <?php if (!empty($expiringSoonVehicles)): ?>
            <div id="expiringSoon">
                <h2 class="mt-5 mb-4 bg-dark text-white p-2 rounded">Expiring Soon</h2>
                <div class="table-responsive">
                    <table class="table table-hover table-bordered">
                        <thead class="table-dark">
                            <tr>
                                <th>ID</th>
                                <th>Registration</th>
                                <th>Council Name</th>
                                <th>MOT Expiry</th>
                                <th>Plate Expiry</th>
                                <th>Interim Expiry</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($expiringSoonVehicles as $vehicle): ?>
                                <tr>
                                    <td><?= htmlspecialchars($vehicle['id']) ?></td>
                                    <td><?= htmlspecialchars($vehicle['registration']) ?></td>
                                    <td><?= htmlspecialchars($vehicle['council_name']) ?></td>
                                    <td><?= date('d/m/Y', strtotime($vehicle['mot_expiry'])) ?></td>
                                    <td><?= date('d/m/Y', strtotime($vehicle['plate_expiry'])) ?></td>
                                    <td><?= $vehicle['interim_expiry'] ? date('d/m/Y', strtotime($vehicle['interim_expiry'])) : '' ?></td>
                                    <td class="action-buttons">
                                        <a href="edit_vehicle.php?vehicleId=<?= $vehicle['id'] ?>" class="btn btn-warning btn-sm">Edit</a>
                                        <form method="post" action="delete_vehicle.php" style="display: inline-block;" onsubmit="return confirm('Are you sure?');">
                                            <input type="hidden" name="vehicleId" value="<?= $vehicle['id'] ?>">
                                            <button type="submit" name="deleteVehicle" class="btn btn-danger btn-sm">Delete</button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
