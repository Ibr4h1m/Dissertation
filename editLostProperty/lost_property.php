<?php
include '../db_connect.php'; // Include your database connection file
include '../nav.php'; // Include your database connection file

// Check if user is not logged in, redirect to index.php
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: ../index.php"); // Redirect to the home page
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

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lost Property</title>
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
            <h1 class="mb-4 bg-dark text-white p-2 rounded">Add Log</h1>
            <form action="add_lost_property.php" method="post" class="mb-4 row g-3 justify-content-center">
                <div class="col-md-6">
                    <label for="job_id" class="form-label">Booking ID:</label>
                    <input type="number" class="form-control" id="job_id" name="job_id" required>
                </div>
                <div class="col-md-6">
                    <label for="driver_callsign" class="form-label">Driver Callsign:</label>
                    <input type="text" class="form-control" id="driver_callsign" name="driver_callsign" required>
                </div>
                <div class="col-md-6">
                    <label for="pickup_date" class="form-label">Pickup Date:</label>
                    <input type="date" class="form-control" id="pickup_date" name="pickup_date" required>
                </div>
                <div class="col-md-6">
                    <label for="pickup_time" class="form-label">Pickup Time:</label>
                    <input type="time" class="form-control" id="pickup_time" name="pickup_time" required>
                </div>
                <div class="col-md-6">
                    <label for="drop_off_time" class="form-label">Dropoff Time:</label>
                    <input type="time" class="form-control" id="drop_off_time" name="drop_off_time" required>
                </div>
                <div class="col-md-6">
                    <label for="property_description" class="form-label">Property Description:</label>
                    <textarea class="form-control" id="property_description" name="property_description" required></textarea>
                </div>
                <div class="col-md-6">
                    <label for="item_colour" class="form-label">Item Colour:</label>
                    <input type="text" class="form-control" id="item_colour" name="item_colour" required>
                </div>
                <div class="col-md-6">
                    <label for="status" class="form-label">Status:</label>
                    <select class="form-select" id="status" name="status" required>
                        <option value="pending">Pending</option>
                        <option value="returned">Returned</option>
                    </select>
                </div>
                <div class="col-12">
                    <button type="submit" class="btn btn-primary" name="add_record">Add Record</button>
                </div>
            </form>
        </div>

        <div class="bg-light border rounded-3 p-4">
            <h2 class="mb-4 bg-dark text-white p-2 rounded">Lost Property</h2>
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
                            <th>Action</th>
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
                            <td>
                                <form action="edit_lost_property.php" method="post" class="d-inline">
                                    <input type="hidden" name="id" value="<?= $property['id'] ?>">
                                    <button type="submit" name="edit" class="btn btn-secondary btn-sm">Edit</button>
                                </form>
                                <form action="delete_lost_property.php" method="post" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this item?');">
                                    <input type="hidden" name="id" value="<?= $property['id'] ?>">
                                    <button type="submit" name="delete" class="btn btn-danger btn-sm">Delete</button>
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

    <!-- Edit Vehicle Modal -->
    <div class="modal fade" id="editVehicleModal" tabindex="-1" aria-labelledby="editVehicleModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="editVehicleModalLabel">Edit Vehicle</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <form id="editVehicleForm">
              <!-- Dynamically filled form fields go here -->
            </form>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            <button type="button" class="btn btn-primary" id="saveEdit">Save Changes</button>
          </div>
        </div>
      </div>
    </div>
    
</body>
</html>
