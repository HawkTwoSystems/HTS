<?php
// Start a session
session_start();

// Load environment variables
require 'vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

// Database configuration
$servername = "localhost";
$username = "root";
$password = $_ENV['DB_PASSWORD']; // Fetch from .env
$dbname = "databass";

// Create a connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Initialize variables
$error_message = "";

// Step 1: Check if form data is submitted
if ($_SERVER["REQUEST_METHOD"] === "POST" && !isset($_POST['2fa_code'])) {
    $input_username = $_POST['username'];
    $input_password = $_POST['password'];

    // Query the database
    $stmt = $conn->prepare("SELECT id, email, password FROM users WHERE username = ?");
    $stmt->bind_param("s", $input_username);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($user_id, $user_email, $hashed_password);
        $stmt->fetch();

        // Verify password
        if (password_verify($input_password, $hashed_password)) {
            // Generate a 6-digit 2FA code
            $two_fa_code = random_int(100000, 999999);

            // Store the code and user details in the session
            $_SESSION['user_id'] = $user_id;
            $_SESSION['2fa_code'] = $two_fa_code;
            $_SESSION['user_email'] = $user_email;

            // Send the 2FA code via email using SendGrid
            $email = new Mail();
            $email->setFrom("hawktwosystems@gmail.com", "Hawk Two Systems");
            $email->setSubject("Your 2FA Code");
            $email->addTo($user_email, "Hawk Two User");
            $email->addContent("text/plain", "Your 2FA code is: $two_fa_code");

            $sendgrid = new \SendGrid($sendgrid_api_key);
            try {
                $response = $sendgrid->send($email);
                if ($response->statusCode() != 202) {
                    $error_message = "SendGrid API response: " . $response->body();
                } else {
                    header("Location: 2fa.php");
                    exit();
                }
            } catch (Exception $e) {
                $error_message = "Failed to send the 2FA code. Error: " . $e->getMessage();
            }
        } else {
            $error_message = "Invalid password!";
        }
    } else {
        $error_message = "No user found with that username!";
    }

    $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Page</title>
    <link rel="stylesheet" href="index.css">
    <link rel="stylesheet" href="login.css">
</head>
<body>
    <div class="login-container">
        <div class="login-box">
            <h1>Login</h1>
            
            <!-- Display error message -->
            <?php if (!empty($error_message)): ?>
                <p style="color: red;"><?php echo htmlspecialchars($error_message); ?></p>
            <?php endif; ?>
            
            <!-- Login form -->
            <form method="POST" action="">
                <input type="text" name="username" placeholder="Username" required>
                <input type="password" name="password" placeholder="Password" required>
                <button type="submit">Sign In</button>
            </form>
            
            <div class="links">
                <a href="index.html">Back</a>
                <p>Don't have an account? <a href="register.php">Sign Up</a></p>
            </div>
        </div>
    </div>
</body>
</html>
