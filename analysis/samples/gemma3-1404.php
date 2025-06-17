
<!DOCTYPE html>
<html>
<head>
  <title>Forgot Password</title>
</head>
<body>

  <h1>Forgot Password</h1>

  <form method="post" action="">
    <label for="email">Email:</label>
    <input type="email" id="email" name="email" required><br><br>

    <label for="new_password">New Password:</label>
    <input type="password" id="new_password" name="new_password" required><br><br>

    <button type="submit">Reset Password</button>
  </form>

</body>
</html>


<?php

// This is a simplified example and should be used with caution.
// In a real-world application, you would:
// 1. Store passwords securely (hashed).
// 2. Implement proper user authentication and security measures.
// 3.  Use a more robust email sending library.

class User {
    private $hashedPassword;
    private $email;

    public function __construct($email, $hashedPassword) {
        $this->email = $email;
        $this->hashedPassword = $hashedPassword;
    }

    public function resetPassword($newPassword) {
        // In a real application, you would hash the new password.
        // This is just for demonstration.
        $newHashedPassword = hash('sha256', $newPassword); 

        // Update the password in the database or other storage
        // This part is crucial for security!
        // Replace this with your actual database update code.
        $this->hashedPassword = $newHashedPassword;
    }
}

/**
 *  Function to handle the forgot password process.
 *
 * @param string $email The email address associated with the user.
 * @param string $baseUrl The base URL for the password reset link.  
 *                        e.g., 'http://example.com/reset-password'
 * @return string The password reset link, or an error message.
 */
function forgotPassword($email, $baseUrl) {
    // 1. Validate Email (Important!)
    if (empty($email)) {
        return "Error: Email address is required.";
    }
    
    // Check if the user exists (replace with your database query)
    // This is a placeholder -  you'll need to connect to your database
    // and verify the user.
    $userExists = checkIfUserExists($email);  // Implement this function

    if (!$userExists) {
        return "Error: User not found.";
    }


    // 2. Generate a Unique Token (Critical for security)
    $resetToken = generateUniqueToken(); // Implement this function

    // 3. Store the Token and User ID in the database
    // (Replace this with your database interaction code)
    storeTokenForUser($email, $resetToken); // Implement this function

    // 4.  Create the Password Reset Link
    $resetLink = $baseUrl . "?token=" . urlencode($resetToken);

    // 5.  Send the Email (Replace with a proper email library)
    $subject = "Password Reset Request";
    $message = "Click the link below to reset your password: " . $resetLink;
    //  In a real application, you would use a library like PHPMailer
    //  to send the email.  Don't hardcode email addresses or passwords.
    //  Example:
    //  sendEmail($email, $subject, $message); 

    return $resetLink;
}


/**
 * Placeholder function to check if a user exists in the database.
 * Replace this with your actual database query.
 *
 * @param string $email The email address to search for.
 * @return bool True if the user exists, false otherwise.
 */
function checkIfUserExists($email) {
    // Replace this with your actual database query
    // Example:
    // $conn = new mysqli("localhost", "username", "password", "database");
    // $result = $conn->query("SELECT * FROM users WHERE email = '$email'");
    // if ($result->num_rows > 0) {
    //   return true;
    // } else {
    //   return false;
    // }

    // For this example, we'll just return true for demonstration.
    return true;
}

/**
 * Placeholder function to generate a unique token.
 *
 * @return string A unique token.
 */
function generateUniqueToken() {
    return bin2hex(random_bytes(32));  // Use a cryptographically secure random number generator
}

/**
 * Placeholder function to store the token in the database.
 * Replace this with your actual database update code.
 *
 * @param string $email The email address.
 * @param string $token The token.
 */
function storeTokenForUser($email, $token) {
    // Replace this with your database update code.
    // Example:
    // $conn = new mysqli("localhost", "username", "password", "database");
    // $sql = "INSERT INTO reset_tokens (user_email, reset_token, expiry_date)
    //         VALUES ('$email', '$token', NOW())";
    // if ($conn->query($sql) === TRUE) {
    //   //print "New record created successfully";
    // } else {
    //   echo "Error: " . $sql . "<br>" . $conn->error;
    // }
}

// Example Usage:
$email = "test@example.com";
$baseUrl = "http://localhost/reset-password";
$resetLink = forgotPassword($email, $baseUrl);

if (strpos($resetLink, "Error:") === 0) {
    echo $resetLink . "<br>";
} else {
    echo "Password reset link: " . $resetLink . "<br>";
}


?>
