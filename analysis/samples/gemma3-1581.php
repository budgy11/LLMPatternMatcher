

<?php

// Database connection details - Replace with your actual credentials
$host = "localhost";
$username = "your_username";
$password = "your_password";
$database = "your_database";

// Function to reset password
function forgot_password($email, $new_password, $database_host, $database_username, $database_password, $database_name) {
  // 1. Validate Email
  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    return "Invalid email address.";
  }

  // 2. Connect to the database
  $conn = new mysqli($database_host, $database_username, $database_password, $database_name);

  // Check connection
  if ($conn->connect_error) {
    return "Connection failed: " . $conn->connect_error;
  }

  // 3.  Check if user exists
  $sql = "SELECT id, email FROM users WHERE email = '$email'";
  $result = $conn->query($sql);

  if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $user_id = $row['id'];
    $user_email = $row['email'];

    // 4.  Hash the new password
    $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

    // 5. Update the password in the database
    $sql = "UPDATE users SET password = '$hashed_password' WHERE id = '$user_id'";
    if ($conn->query($sql) === TRUE) {
      return "Password reset successfully.  Please check your email for login instructions.";
    } else {
      return "Error updating password: " . $conn->error;
    }
  } else {
    return "User not found.";
  }

  // Close the connection
  $conn->close();
}


// Example Usage (This is just for demonstration - you would typically process a form submission)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $email = $_POST["email"];
  $new_password = $_POST["new_password"];

  $reset_message = forgot_password($email, $new_password, $host, $username, $password, $database);
  echo $reset_message;
}

?>
