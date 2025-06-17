

<?php

// Database credentials - Replace with your actual values
$db_host = "localhost";
$db_name = "your_database_name";
$db_user = "your_db_user";
$db_pass = "your_db_password";

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Validate the form data
  $username = filter_input(INPUT_POST, "username", FILTER_SANITIZE_STRING);
  $email = filter_input(INPUT_POST, "email", FILTER_SANITIZE_EMAIL);
  $password = filter_input(INPUT_POST, "password", FILTER_SANITIZE_STRING);

  // Sanitize and validate email format
  if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
    // Hash the password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // SQL query to insert the new user
    $sql = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);  // Use prepared statement for security
    $stmt->bind_param("ss", $username, $email, $hashed_password);

    if ($stmt->execute()) {
      // Registration successful
      echo "Registration successful! Please verify your email.";
      // Optionally, you can redirect the user
      // header("Location: verify_email.php");
      // exit;
    } else {
      // Registration failed
      echo "Registration failed: " . $stmt->error;
    }
  } else {
    // Email validation failed
    echo "Invalid email format.";
  }
}
?>
