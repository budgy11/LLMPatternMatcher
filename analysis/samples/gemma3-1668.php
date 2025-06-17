    <button type="submit">Reset Password</button>
</form>

</body>
</html>


<?php

// This is a simplified example for demonstration purposes.
// In a real-world application, you MUST use a secure method
// for generating and sending passwords.  This example is vulnerable to
// attacks if used without proper security measures.

// Database connection details (Replace with your actual values)
$db_host = 'localhost';
$db_name = 'your_database_name';
$db_user = 'your_database_user';
$db_pass = 'your_database_password';

// Function to reset password
function forgot_password($email, $new_password) {
  // 1. Connect to the database
  $conn = new mysqli($db_host, $db_user, $db_pass, $db_name);

  // Check connection
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  // 2.  Hash the new password (VERY IMPORTANT - Do NOT store passwords in plain text)
  $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);  // Use password_hash() for secure hashing

  // 3. Update the password in the database
  $query = "UPDATE users SET password = '$hashed_password' WHERE email = '$email'";
  if ($conn->query($query) === TRUE) {
    return true; // Password reset successful
  } else {
    error_log("Query failed: " . $conn->error);  // Log the error for debugging
    return false; // Password reset failed
  }

  // 4. Close the connection
  $conn->close();
}


// Example Usage (This should be handled in a form submission)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $email = $_POST["email"];
  $new_password = $_POST["new_password"];

  if (empty($email) || empty($new_password)) {
    echo "Error: Email and new password are required.";
  } else {
    if (forgot_password($email, $new_password)) {
      echo "Password reset email sent successfully. Please check your inbox.";
    } else {
      echo "Error: Password reset failed.  Please try again.";
    }
  }
}
?>
