        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required>
        <button type="submit">Reset Password</button>
    </form>

</body>
</html>


<?php

// Database connection details (Replace with your actual details)
$host = "localhost";
$username = "your_username";
$password = "your_password";
$database = "your_database_name";

// Function to reset password
function forgot_password($email, $password_reset_token, $expiration_time_seconds = 60*60*24) { // Default 24 hours
  // 1. Database connection
  $conn = new mysqli($host, $username, $password, $database);

  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  // 2. Prepare the SQL query
  $sql = "UPDATE users SET password = ? , password_reset_token = ? WHERE email = ?";

  // 3. Prepare the statement
  $stmt = $conn->prepare($sql);

  if ($stmt === false) {
    die("Error preparing statement: " . $conn->error);
  }

  // 4. Bind the parameters
  $new_password = "NewSecurePassword123!"; // A placeholder -  Never directly insert a user-provided password.
  $reset_token = $password_reset_token;
  $email = $email;

  $stmt->bind_param("ssi", $new_password, $reset_token, $email);

  // 5. Execute the query
  if (!$stmt->execute()) {
    die("Error executing query: " . $stmt->error);
  }

  // 6. Close the statement and connection
  $stmt->close();
  $conn->close();

  // 7.  Consider sending an email with the link to reset the password
  //     (This part is not included in this basic function but is *essential*
  //     for a real-world implementation)
  return true; // Indicate success
}


// Example Usage (for demonstration - NOT recommended for production)
// IMPORTANT:  This is just an example.  You should *never* directly
//  use user-provided data in a real application without proper sanitization and validation.
//  In a real application, you would generate a unique and secure token.
//  Also, you would send an email to the user with a link containing the token.

// $user_email = "test@example.com"; // Replace with a valid email address
// $reset_token = "unique_token_123";  // Generate a unique token -  Crucial for security
//
// if (forgot_password($user_email, $reset_token)) {
//   echo "Password reset link sent to " . $user_email;
// } else {
//   echo "Failed to reset password.";
// }


?>
