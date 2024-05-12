<?php
require 'db_connect.php'; // Ensure this path is correct

$errorMessage = '';
$successMessage = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['deleteVehicle'])) {
    // Extract vehicle ID from the POST request
    $vehicleId = $_POST['vehicleId'];

    // Basic validation (ensure there's an ID to work with)
    if (!empty($vehicleId)) {
        try {
            $sql = "DELETE FROM vehicles WHERE id = ?";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$vehicleId]);
            
            // Check if the delete was successful
            if ($stmt->rowCount() > 0) {
                $successMessage = "Vehicle deleted successfully.";
            } else {
                // If no rows were affected, the vehicle was not found
                $errorMessage = "Vehicle not found or already deleted.";
            }
        } catch (PDOException $e) {
            $errorMessage = "Error deleting vehicle: " . $e->getMessage();
        }
    } else {
        $errorMessage = "Invalid request.";
    }
}

// Redirect back to the vehicles listing page with a success or error message
header("Location: vehicles.php?successMessage={$successMessage}&errorMessage={$errorMessage}");
exit;
?>
