

<?php

// Database connection details (replace with your actual values)
$host = "localhost";
$username = "your_username";
$password = "your_password";
$database = "your_database_name";

// Check if the registration form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

  // Validate input
  $username = trim($_POST["username"]);
  $email = trim($_POST["email"]);
  $password = trim($_POST["password"]);

  // Validate username (basic example - you should implement more robust validation)
  if (empty($username)) {
    $username_error = "Username cannot be empty.";
  } elseif (strlen($username) < 3) {
    $username_error = "Username must be at least 3 characters long.";
  }

  // Validate email
  if (empty($email)) {
    $email_error = "Email cannot be empty.";
  } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $email_error = "Invalid email format.";
  }

  // Validate password (basic example - you should use stronger hashing)
  if (empty($password)) {
    $password_error = "Password cannot be empty.";
  } elseif (strlen($password) < 8) {
    $password_error = "Password must be at least 8 characters long.";
  }

  // Check if there are any validation errors
  if ($username_error || $email_error || $password_error) {
    $error = $error . "<br>" . $username_error . "<br>" . $email_error . "<br>" . $password_error;
  } else {
    // Hash the password (VERY IMPORTANT - never store passwords in plain text!)
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Prepare the SQL query
    $sql = "INSERT INTO users (username, email, password) VALUES ('$username', '$email', '$hashed_password')";

    // Execute the query
    if (mysqli_query($conn, $sql)) {
      $success = "Registration successful! You have been redirected.";
    } else {
      $error = "Error registering user. " . mysqli_error($conn);
    }
  }
}
?>
