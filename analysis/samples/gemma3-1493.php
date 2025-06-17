

<?php

// Database connection details - Replace with your actual credentials
$host = "localhost";
$username = "your_username";
$password = "your_password";
$database = "your_database_name";

// Function to reset password
function forgotPassword($email, $newPassword) {
  // 1. Connect to the database
  $conn = new mysqli($host, $username, $password, $database);

  if ($conn->connect_error) {
    return false;
  }

  // 2. Sanitize input (important for security)
  $newPassword = password_hash($newPassword, PASSWORD_DEFAULT); // Hash the password

  // 3. Update the password in the database
  $sql = "UPDATE users SET password = '$newPassword' WHERE email = '$email'";
  if ($conn->query($sql) === TRUE) {
    return true;
  } else {
    return false;
  }

  // 4. Close the connection
  $conn->close();
}


// Example usage (this is for demonstration - you would typically
// handle this via a form submission)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST["email"];
    $newPassword = $_POST["new_password"];

    if (!empty($email) && !empty($newPassword)) {
        if (forgotPassword($email, $newPassword)) {
            echo "Password reset successfully! Please check your email for instructions.";
        } else {
            echo "Error resetting password. Please try again.";
        }
    } else {
        echo "Please fill in all fields.";
    }
}
?>
