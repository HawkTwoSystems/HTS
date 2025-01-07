<?php
// Start a session
session_start();

// Initialize variables
$error_message = "";
$success_message = "";

// Step 2: Handle 2FA code submission
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['2fa_code'])) {
    $entered_code = $_POST['2fa_code'];

    if ($entered_code == $_SESSION['2fa_code']) {
        // 2FA success
        unset($_SESSION['2fa_code']); // Clear 2FA session data
        unset($_SESSION['show_2fa_form']); // Clear 2FA flag
        
        // Get the username from session (you can also use user_email or user_id)
        $username = $_SESSION['user_email']; // Assuming user_email is stored in session
        
        // Display login success message with the username
        $success_message = "Login successful, $username!";
    } else {
        $error_message = "Invalid 2FA code!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>2FA Verification</title>
    <link rel="stylesheet" href="index.css">
    <link rel="stylesheet" href="login.css">
</head>
<body>
    <div class="login-container">
        <div class="login-box">
            <h1>2FA Verification</h1>
            
            <!-- Display error message -->
            <?php if (!empty($error_message)): ?>
                <p style="color: red;"><?php echo htmlspecialchars($error_message); ?></p>
            <?php endif; ?>

            <!-- Display success message -->
            <?php if (!empty($success_message)): ?>
                <p style="color: green;"><?php echo htmlspecialchars($success_message); ?></p>
            <?php endif; ?>
            
            <!-- 2FA form -->
            <form method="POST" action="">
                <input type="text" name="2fa_code" placeholder="Enter 2FA Code" required>
                <button type="submit">Verify</button>
            </form>
            
            <div class="links">
                <p><a href="login.php">Back to Login</a></p>
            </div>
        </div>
    </div>
</body>
</html>
