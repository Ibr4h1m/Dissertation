<?php
session_start();

// Check if the user is logged in and their rank
$userLoggedIn = isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true;
$userRank = isset($_SESSION['rank']) ? $_SESSION['rank'] : null;
$username = $_SESSION['username']; // Get the username from the session
// Determine the root URL dynamically
$protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http";
$rootURL = $protocol . "://" . $_SERVER['HTTP_HOST'] . "/autocab/";

?>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container-fluid">
        <a class="navbar-brand" href="<?php echo $rootURL; ?>index.php">MyPWA</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <?php if (!$userLoggedIn): ?>
                    <li><a class="nav-item nav-link" href="<?php echo $rootURL; ?>login.php">Login</a></li>
                    <li><a class="nav-item nav-link" href="<?php echo $rootURL; ?>register.php">Register</a></li>
                <?php else: ?>
                    <?php if ($userRank == 1): ?>
                        <li><a class="nav-item nav-link" href="<?php echo $rootURL; ?>complaints.php">Complaints</a></li>
                        <li><a class="nav-item nav-link" href="<?php echo $rootURL; ?>lost_property.php">Lost Property</a></li>
                        <li><a class="nav-item nav-link" href="<?php echo $rootURL; ?>timesheet.php">Timesheet</a></li>
                    <?php elseif ($userRank == 2): ?>
                        <li><a class="nav-item nav-link" href="<?php echo $rootURL; ?>new_drivers.php">New Drivers</a></li>
                        <li><a class="nav-item nav-link" href="<?php echo $rootURL; ?>complaints.php">Complaints</a></li>
                        <li><a class="nav-item nav-link" href="<?php echo $rootURL; ?>vehicles.php">Vehicles</a></li>
                        <li><a class="nav-item nav-link" href="<?php echo $rootURL; ?>lost_property.php">Lost Property</a></li>
                        <li><a class="nav-item nav-link" href="<?php echo $rootURL; ?>shifts.php">Shifts</a></li>
                    <?php endif; ?>
                    <li><a class="nav-item nav-link" href="<?php echo $rootURL; ?>logout.php">Logout</a></li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>
