<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
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
<?php
include 'db_connect.php';
include 'nav.php';

// Check if user is already logged in
if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) {
    header("Location: index.php"); // Redirect to home page
    exit;
}

function cleanInput($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

$error = ""; 
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = cleanInput($_POST["username"]);
    $password = cleanInput($_POST["password"]);
    $confirm_password = cleanInput($_POST["confirm_password"]);
    $activation_code = cleanInput($_POST["activation_code"]);

    // Check if fields are empty
    if (empty($username) || empty($password) || empty($confirm_password)) {
        $error = "Username and Password are required.";
    } else if ($password != $confirm_password) {
        $error = "Passwords do not match.";
    } else if ($activation_code !== 'ActivationCode') {
        $error = "Invalid activation code.";
    } else {
        $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->execute([$username]);
        if ($stmt->rowCount() > 0) {
            $error = "Username already taken.";
        } else {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
            if ($stmt->execute([$username, $hashed_password])) {
                echo "<script>alert('Registration successful. You will now be redirected to the login page.'); window.location.href='login.php';</script>";

            } else {
                $error = "Error in registration.";
            }
        }
    }
}
?>

<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <h1 class="mb-3">Register</h1>
                <?php if ($error): ?>
                    <div class="alert alert-danger" role="alert">
                        <?php echo $error; ?>
                    </div>
                <?php endif; ?>
                <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
                    <div class="mb-3">
                        <label for="username" class="form-label">Username:</label>
                        <input type="text" class="form-control" name="username" id="username" required>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Password:</label>
                        <input type="password" class="form-control" name="password" id="password" required>
                    </div>
                    <div class="mb-3">
                        <label for="confirm_password" class="form-label">Confirm Password:</label>
                        <input type="password" class="form-control" name="confirm_password" id="confirm_password" required>
                    </div>
                    <div class="mb-3">
                        <label for="activation_code" class="form-label">Activation Code:</label>
                        <input type="password" class="form-control" name="activation_code" id="activation_code" required>
                    </div>
                    <button type="submit" class="btn btn-primary" name="register">Register</button>
                </form>
            </div>
        </div>
    </div>
    <!-- Bootstrap Bundle with Popper.js -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
