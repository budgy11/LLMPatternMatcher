

<?php

// IMPORTANT: This is a simplified example for demonstration purposes only.
// In a production environment, you should NEVER store passwords in plain text.
// Use strong hashing algorithms (e.g., bcrypt, Argon2) and salting.

// 1. Database connection (replace with your actual credentials)
$dbHost = "localhost";
$dbName = "your_database_name";
$dbUser = "your_database_username";
$dbPassword = "your_database_password";

try {
    $pdo = new PDO("mysql:host=$dbHost;dbname=$dbName;charset=utf8mb4", $dbUser, $dbPassword);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}

// 2. Function to handle the forgot password process
function forgotPassword($email) {
    // Validate email (basic check - improve in a real application)
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return "Invalid email address.";
    }

    // Check if the user exists
    $stmt = $pdo->prepare("SELECT id, email FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user) {
        return "User not found.";
    }

    // Generate a unique token (use a more robust method in production)
    $token = bin2hex(random_bytes(32)); // Generate a cryptographically secure random string

    // Store the token and user ID in the database
    $stmt = $pdo->prepare("UPDATE users SET password_reset_token = ?, password_reset_expires = ? WHERE email = ?");
    $stmt->execute([$token, date('Y-m-d H:i:s', time() + 3600), $email]); // Token expires after 1 hour

    return $token; // Return the token to the user
}

// 3. Example usage (simulated form handling)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST["email"];

    if (isset($email)) {
        $resetToken = forgotPassword($email);
        echo "<p>Please check your email inbox. A password reset link has been sent to you.</p>";
        echo "<p>Reset Token: " . htmlspecialchars($resetToken) . "</p>"; //Display the token (careful with security)
        echo "<p>You can copy and paste this token into the reset password form.</p>";
    } else {
        echo "<p>Error: Email address is required.</p>";
    }
}

?>
