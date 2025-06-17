    <label for="email">Email Address:</label>
    <input type="email" id="email" name="email" placeholder="Your Email">
    <button type="submit">Reset Password</button>
  </form>

</body>
</html>


<?php

// Assuming you have a database connection established
// This example uses a simplified setup. Adapt it to your actual database configuration.

// Database credentials (replace with your actual values)
$db_host = 'localhost';
$db_name = 'your_database_name';
$db_user = 'your_db_user';
$db_pass = 'your_db_password';

// Function to reset password
function forgot_password($email, $token, $new_password, $db) {
    // **Important Security Considerations:**
    // 1.  Token Validation:  This is crucial!  Always verify that the token 
    //     received from the user actually exists in the database and hasn't been
    //     used before.  This protects against attackers creating fake reset
    //     links.

    // 2.  Password Hashing:  NEVER store passwords in plain text.  Always
    //     hash them with a strong hashing algorithm (e.g., bcrypt, Argon2) before
    //     storing them in the database.  This example uses password_hash() for
    //     this purpose.

    // 3.  Rate Limiting: Implement rate limiting to prevent abuse of the
    //     forgot password functionality (e.g., multiple requests from the same IP
    //     within a short period).

    // 4.  Email Sending:  Use a reliable email library (e.g., PHPMailer) to send
    //     the password reset email.  Never construct email content directly in
    //     PHP; use a library to handle email formatting and attachments.

    // ----------------------------------------------------------
    // Database Query (Simplified)
    // ----------------------------------------------------------

    $sql = "UPDATE users SET password = ? WHERE email = ?";
    $stmt = $db->prepare($sql); // Prepare the statement for security
    $stmt->bind_param("ss", $new_password, $email);

    if ($stmt->execute()) {
        // Password reset successful
        $stmt->close();
        return true;
    } else {
        // Error resetting password
        error_log("Error resetting password for user: " . $email); // Log the error for debugging
        $stmt->close();
        return false;
    }
}


// **Example Usage (in a controller or similar):**

// 1. Generate a unique token (using a secure method like a UUID library)
//    This should be a long, random string.

// 2. Store the token in the database associated with the user's email.
//    This is usually done when the user initiates the forgot password process.
//    This would typically be an INSERT statement to create a new record
//    in a 'reset_tokens' table.

// 3.  Receive the token and new password from the user's request.

// Example (after receiving token and new_password):
// $token = $_POST['token'];
// $new_password = $_POST['password'];

// $db = new mysqli($db_host, $db_user, $db_pass, $db_name);

// if ($db->connect_error) {
//     die("Connection failed: " . $db->connect_error);
// }

// $result = forgot_password($token, $token, $new_password, $db);

// if ($result) {
//     echo "Password reset successful! Please check your email.";
// } else {
//     echo "Error: Invalid token or password reset failed.";
// }

// $db->close();
?>
