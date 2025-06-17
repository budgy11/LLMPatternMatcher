

<?php

// Database connection details (Replace with your actual credentials)
$servername = "localhost";
$username = "your_db_username";
$password = "your_db_password";
$dbname = "your_db_name";

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Validate the form data
  $username = filter_input(INPUT_POST, "username", FILTER_SANITIZE_STRING);
  $email = filter_input(INPUT_POST, "email", FILTER_SANITIZE_EMAIL);
  $password = filter_input(INPUT_POST, "password", FILTER_SANITIZE_STRING);

  // Validate email format
  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $email_error = "Invalid email format.";
  }

  // Hash the password (IMPORTANT for security!)
  $hashed_password = password_hash($password, PASSWORD_DEFAULT);


  // Check if the username or email already exists
  $sql = "SELECT * FROM users WHERE username = ? OR email = ?";
  $stmt = $conn->prepare($sql);  // Use prepared statements for security
  $stmt->bind_param("ss", $username, $email);
  $stmt->execute();
  $result = $stmt->get_result();

  if ($result->num_rows > 0) {
    $error = "Username or email already exists.";
  }

  // If all validation passes, insert the new user into the database
  if (!empty($error)) {
    $registration_error = $error;
  } else {
    $sql = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sss", $username, $email, $hashed_password);

    if ($stmt->execute()) {
      $registration_success = true;
      $registration_message = "Registration successful! Please check your email for verification instructions.";
    } else {
      $registration_error = "An error occurred during registration. Please try again.";
    }
  }
}
?>
