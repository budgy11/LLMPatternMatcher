

<?php

// Database connection details - REPLACE WITH YOUR ACTUAL CREDENTIALS
$host = "localhost";
$username = "your_username";
$password = "your_password";
$database = "your_database_name";

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

  // Validate the form input (IMPORTANT - Add more robust validation here)
  $username = trim($_POST["username"]);
  $email = trim($_POST["email"]);
  $password = trim($_POST["password"]);

  // Validation checks (VERY BASIC - Expand for Security)
  if (empty($username) || empty($email) || empty($password)) {
    $errors = "All fields are required.";
  } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors = "Invalid email format.";
  } elseif (strlen($password) < 8) {
    $errors = "Password must be at least 8 characters long.";
  } else {
    // Password hashing (VERY IMPORTANT - Use a strong algorithm!)
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // SQL query to insert the new user
    $sql = "INSERT INTO users (username, email, password) VALUES ('$username', '$email', '$hashed_password')";

    if (mysqli_query($GLOBALS["conn"], $sql)) {
      $success = "Registration successful! Please login.";
    } else {
      $errors = "Error registering user. " . mysqli_error($GLOBALS["conn"]);
    }
  }
} else {
  // If the form hasn't been submitted, clear any previous errors
  $errors = "";
  $success = "";
}

// Connect to the database
$GLOBALS["conn"] = mysqli_connect($host, $username, $password, $database);

// Check the connection
if ($GLOBALS["conn"]->connect_error) {
  die("Connection failed: " . $GLOBALS["conn"]->connect_error);
}

?>
