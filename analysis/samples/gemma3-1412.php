
<!-- HTML Form (Example) -->
<form method="post" action="">
  <label for="email">Email:</label>
  <input type="email" id="email" name="email" required>
  <button type="submit">Reset Password</button>
</form>


<?php

// Database connection details (Replace with your actual values)
$host = 'localhost';
$username = 'your_username';
$password = 'your_password';
$database = 'your_database_name';

// Function to handle password reset requests
function forgot_password($email) {
    // 1. Validate email format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return "Invalid email address.";
    }

    // 2. Fetch user data from the database
    $pdo = new PDO("mysql:host=$host;dbname=$database", $username, $password);
    $stmt = $pdo->prepare("SELECT id, password, email FROM users WHERE email = :email");
    $stmt->bindParam(':email', $email);
    $stmt->execute();

    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    // 3. Check if the user exists
    if ($user) {
        // 4. Generate a unique token
        $token = bin2hex(random_bytes(32)); // Generates a cryptographically secure random token
        // 5.  Store the token and user ID in the database
        try {
            $pdo->prepare("UPDATE users SET password_reset_token = :token, password_reset_token_expiry = :expiry WHERE email = :email")
                  ->bindParam(':token', $token)
                  ->bindParam(':expiry', date('Y-m-d H:i:s', time() + 3600)) // Expires after 1 hour
                  ->bindParam(':email', $email)
                  ->execute();
            return $token; // Return the token to the user
        } catch (PDOException $e) {
            // Handle database errors (e.g., duplicate token)
            error_log("Error resetting password: " . $e->getMessage()); // Log the error for debugging
            return "An error occurred while generating the reset token. Please try again.";
        }

    } else {
        return "User not found.";
    }
}


// Example Usage (for demonstration purposes - DON'T use this in a real web application directly)
// $email = 'test@example.com';
// $resetToken = forgot_password($email);

// if ($resetToken == "Invalid email address.") {
//     echo $resetToken; // Display the error message
// } elseif ($resetToken == "User not found.") {
//     echo $resetToken;
// } else {
//     echo "Password reset link sent to: " . $resetToken;
// }
?>
