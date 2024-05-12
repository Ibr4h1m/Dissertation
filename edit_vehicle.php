<?php
require 'db_connect.php';

$errorMessage = '';
$successMessage = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['updateVehicle'])) {
    // Collect and sanitize input
    $vehicleId = $_POST['vehicleId'];
    $registration = filter_input(INPUT_POST, 'registration', FILTER_SANITIZE_STRING);
    $motExpiry = $_POST['mot_expiry'];
    $councilName = $_POST['council_name'];
    $plateExpiry = $_POST['plate_expiry'];
    // Check if interim_expiry is provided; otherwise, set to NULL
    $interimExpiry = !empty($_POST['interim_expiry']) ? $_POST['interim_expiry'] : null;

    // Validation
    if (!empty($registration) && !empty($motExpiry) && !empty($councilName) && !empty($plateExpiry)) {
        try {
            $sql = "UPDATE vehicles SET registration = ?, mot_expiry = ?, council_name = ?, plate_expiry = ?, interim_expiry = ? WHERE id = ?";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$registration, $motExpiry, $councilName, $plateExpiry, $interimExpiry, $vehicleId]);
            $successMessage = "Vehicle updated successfully.";
            header("Location: vehicles.php"); // Redirect after update
        } catch (PDOException $e) {
            $errorMessage = "Error updating vehicle: " . $e->getMessage();
        }
    } else {
        $errorMessage = "Please fill in all required fields.";
    }
}

// Fetch vehicle details for editing
if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['vehicleId'])) {
    $vehicleId = $_GET['vehicleId'];

    try {
        $sql = "SELECT * FROM vehicles WHERE id = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$vehicleId]);
        $vehicle = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$vehicle) {
            $errorMessage = "Vehicle not found.";
        }
    } catch (PDOException $e) {
        $errorMessage = "Error fetching vehicle: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Vehicle</title>
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
<div class="container mt-5">
    <div class="bg-light border rounded-3 p-4">
        <h1 class="mb-4 bg-dark text-white p-2 rounded">Edit Vehicle</h1>
        <?php if ($errorMessage): ?>
            <div class="alert alert-danger"><?= $errorMessage ?></div>
        <?php endif; ?>
        <?php if ($successMessage): ?>
            <div class="alert alert-success"><?= $successMessage ?></div>
        <?php endif; ?>

        <!-- Only display the form if $vehicle is set (i.e., vehicle was found) -->
        <?php if (isset($vehicle)): ?>
            <div class="centered-form">
                <form action="edit_vehicle.php" method="post">
                    <input type="hidden" name="vehicleId" value="<?= htmlspecialchars($vehicle['id']) ?>">

                    <div class="form-group">
                        <label for="registration" class="form-label">Registration</label>
                        <input type="text" class="form-control" id="registration" name="registration" value="<?= htmlspecialchars($vehicle['registration']) ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="council_name" class="form-label">Council Name</label>
                        <select class="form-select" id="council_name" name="council_name" required>
                            <option value="">Select a Council</option>
                            <!-- Ensure the correct option is selected based on the vehicle's current council_name -->
                            <option value="SOT" <?= $vehicle['council_name'] == 'SOT' ? 'selected' : '' ?>>SOT</option>
                            <option value="Wolverhampton" <?= $vehicle['council_name'] == 'Wolverhampton' ? 'selected' : '' ?>>Wolverhampton</option>
                            <option value="Ashfield" <?= $vehicle['council_name'] == 'Ashfield' ? 'selected' : '' ?>>Ashfield</option>
                            <option value="Newcastle" <?= $vehicle['council_name'] == 'Newcastle' ? 'selected' : '' ?>>Newcastle</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="mot_expiry" class="form-label">MOT Expiry</label>
                        <input type="date" class="form-control" id="mot_expiry" name="mot_expiry" value="<?= htmlspecialchars($vehicle['mot_expiry']) ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="plate_expiry" class="form-label">Plate Expiry</label>
                        <input type="date" class="form-control" id="plate_expiry" name="plate_expiry" value="<?= htmlspecialchars($vehicle['plate_expiry']) ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="interim_expiry" class="form-label">Interim Expiry (Optional)</label>
                        <input type="date" class="form-control" id="interim_expiry" name="interim_expiry" value="<?= $vehicle['interim_expiry'] ? htmlspecialchars($vehicle['interim_expiry']) : '' ?>">
                    </div>
                    <button type="submit" name="updateVehicle" class="btn btn-primary">Update Vehicle</button>
                </form>
            </div>
        <?php endif; ?>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
