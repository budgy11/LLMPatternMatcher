

<?php

// Configuration (replace with your actual settings)
$db_host = 'localhost';
$db_name = 'your_database_name';
$db_user = 'your_db_user';
$db_password = 'your_db_password';

// Function to reset password
function forgot_password($email, $new_password) {
  // 1. Database Connection
  $conn = new mysqli($db_host, $db_user, $db_password, $db_name);

  // Check connection
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  // 2. Hash the new password (IMPORTANT for security)
  $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

  // 3. Update the password in the database
  $sql = "UPDATE users SET password = '$hashed_password' WHERE email = '$email'";

  if ($conn->query($sql) === TRUE) {
    return true; // Password reset successful
  } else {
    return false; // Password reset failed
  }

  // Close the connection
  $conn->close();
}


// --- Example Usage (This is just an example, replace with your form submission logic) ---

// Example:  Simulated form submission
if (isset($_POST['email']) && isset($_POST['new_password'])) {
    $email = $_POST['email'];
    $new_password = $_POST['new_password'];

    // Validation - VERY IMPORTANT!  Add more robust validation here.
    if (empty($email) || empty($new_password)) {
        echo "Error: Email and new password are required.";
    } else {
        // Call the forgot_password function
        if (forgot_password($email, $new_password)) {
            echo "Password reset successful! Please check your email for instructions.";
        } else {
            echo "Error: Password reset failed. Please try again.";
        }
    }
}


// ----------------------------------------------------------------------

?>
