<?php
// Start the session
// session_start();

// Database connection
include 'includes/connect.php';

// Initialize variables
$error = '';
$success = '';

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);
    $new_password = trim($_POST['new_password']);
    $confirm_password = trim($_POST['confirm_password']);

    // Basic validation
    if (empty($email) || empty($new_password) || empty($confirm_password)) {
        $error = "All fields are required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Invalid email format.";
    } elseif ($new_password !== $confirm_password) {
        $error = "Passwords do not match.";
    } else {
        // Check if user exists
        $stmt = $con->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            // Update password
            $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
            $update = $con->prepare("UPDATE users SET password = ? WHERE email = ?");
            $update->bind_param("ss", $hashed_password, $email);

            if ($update->execute()) {
                // Redirect to login.php after 2 seconds
                $success = "Password has been reset successfully. Redirecting to login...";
                header("refresh:2; url=login.php");
            } else {
                $error = "Failed to reset password. Please try again.";
            }
            $update->close();
        } else {
            $error = "No account found with that email.";
        }
        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Reset Password</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f2f2f2; }
        .container { width: 400px; margin: 100px auto; padding: 20px; background: #fff; border-radius: 8px; box-shadow: 0px 0px 10px rgba(0,0,0,0.1); }
        input[type="email"], input[type="password"] { width: 95%; padding: 10px; margin: 10px 0; }
        input[type="submit"] { width: 100%; padding: 10px; background: skyblue; color: white; border: none; cursor: pointer; }
        input[type="submit"]:hover { background: #45a049; }
        .error { color: red; }
        .success { color: green; }
    </style>
</head>
<body>

<div class="container">
    <h2>Reset Password</h2>

    <?php if (!empty($error)) { echo '<p class="error">' . $error . '</p>'; } ?>
    <?php if (!empty($success)) { echo '<p class="success">' . $success . '</p>'; } ?>

    <form action="" method="POST">
        <label>Email:</label>
        <input type="email" name="email" required>

        <label>New Password:</label>
        <input type="password" name="new_password" required>

        <label>Confirm Password:</label>
        <input type="password" name="confirm_password" required>

        <input type="submit" value="Reset Password">
    </form>
</div>

</body>
</html>
