
<!DOCTYPE html>
<html>
<head>
    <title>Forgot Password</title>
</head>
<body>

    <h1>Forgot Password</h1>

    <form method="post" action="">
        <label for="email">Email Address:</label>
        <input type="email" id="email" name="email" required>
        <button type="submit">Reset Password</button>
    </form>

</body>
</html>


<?php

// Configuration (Replace with your actual values)
$db_host = 'localhost';
$db_name = 'your_database_name';
$db_user = 'your_database_username';
$db_password = 'your_database_password';

// Function to reset password
function forgotPassword($email, $new_password) {
  // 1. Connect to the database
  $conn = new mysqli($db_host, $db_user, $db_password, $db_name);

  // Check connection
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  // 2. Prepare and execute the SQL query
  $sql = "UPDATE users SET password = ? WHERE email = ?";
  $stmt = $conn->prepare($sql);

  if ($stmt) {
    $stmt->bind_param("ss", $new_password, $email);  // Use 'ss' for string

    if ($stmt->execute()) {
      // Password reset successful
      $stmt->close();
      return true;
    } else {
      // Password reset failed
      $stmt->close();
      return false;
    }
  } else {
    // Prepare statement failed
    $stmt = null; // Ensure $stmt is null to avoid issues
    return false;
  }
}


// Example Usage (For testing - **DO NOT USE THIS IN A PRODUCTION ENVIRONMENT**)
// **Important Security Considerations - See Below**

// Test email and new password (replace with actual values for testing)
$test_email = 'test@example.com';  // Replace with a valid email address for testing
$new_password = 'NewPassword123'; // Replace with a strong password


// Call the function to reset the password
if (forgotPassword($test_email, $new_password)) {
  echo "Password reset successfully for $test_email";
} else {
  echo "Password reset failed for $test_email.  Check the database and email configuration.";
}


// **IMPORTANT SECURITY CONSIDERATIONS AND BEST PRACTICES**

// 1.  Salt and Hashing:
//     *  **NEVER** store passwords in plain text in your database.
//     *  Always hash passwords using a strong hashing algorithm (like bcrypt or Argon2) *before* storing them in the database.
//     *  Use a unique salt for each user.
//     *  Use a library for password hashing (e.g., password_hash() and password_verify() in PHP).
//     *  This example is for demonstration purposes only and does *not* include proper password hashing.

// 2.  Email Verification (Crucial!)
//     *   **ALWAYS** send an email to the user containing a temporary, unique link.  This link should:
//         *  Be a unique token that can be verified.
//         *  Expire after a short period (e.g., 30 minutes).
//         *  Lead to a secure page where the user can set a new, permanent password.
//     *  This prevents attackers from guessing the password and resetting it without user interaction.

// 3.  Rate Limiting:
//     *  Implement rate limiting to prevent brute-force password reset attempts.

// 4.  Input Validation:
//     *   Thoroughly validate the email address and password input.  Check for valid email formats, sanitize input to prevent SQL injection, and limit password length.

// 5.  Security Audits:
//     *  Regularly review your code for security vulnerabilities.
//     *  Consider using a security scanner.

// 6.  Error Handling:
//     *  Implement robust error handling to catch and log any errors that occur during the password reset process.  *Never* display detailed error messages to the user in a production environment.  Log them securely.

// 7.  Temporary Token:
//     * Use a unique, randomly generated token to represent the reset request. This token should be stored in the database along with the email.  This token is used to verify that the user is actually requesting a password reset.

// 8.  HTTPS:
//     *  Always use HTTPS to encrypt communication between the user's browser and your server.


?>
