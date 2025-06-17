

<?php

// Database credentials (Replace with your actual credentials)
$db_host = "localhost";
$db_name = "your_database_name";
$db_user = "your_username";
$db_password = "your_password";

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

  // Validate and sanitize input
  $username = filter_input(INPUT_POST, "username", FILTER_SANITIZE_STRING);
  $email = filter_input(INPUT_POST, "email", FILTER_SANITIZE_EMAIL);
  $password = filter_input(INPUT_POST, "password", FILTER_SANITIZE_STRING);

  // Validate email format
  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $email_error = "Invalid email address.";
  }

  // Hash the password (Important for security!)
  $hashed_password = password_hash($password, PASSWORD_DEFAULT);

  // Check if username is empty
  if (empty($username)) {
    $username_error = "Username cannot be empty.";
  }

  //  Check if username already exists
  $sql = "SELECT * FROM users WHERE username = '$username'";
  $result = mysqli_query($GLOBALS['db_host'], $sql); // Use $GLOBALS for database connection

  if (mysqli_num_rows($result) > 0) {
    $username_error = "Username already exists. Please choose another.";
  }



  // If no errors, insert the user into the database
  if (empty($username_error) && empty($email_error)) {
    $sql = "INSERT INTO users (username, email, password) VALUES ('$username', '$email', '$hashed_password')";

    if (mysqli_query($GLOBALS['db_host'], $sql)) {
      $registration_message = "Registration successful! Please log in.";
    } else {
      $registration_error = "Error inserting user. " . mysqli_error($GLOBALS['db_host']);
    }
  }
}
?>
