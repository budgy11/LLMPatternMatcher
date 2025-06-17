

<?php

// Database connection details (Replace with your actual details)
$servername = "localhost";
$username = "your_username";
$password = "your_password";
$dbname = "your_database_name";

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Collect form data
  $username = $_POST["username"];
  $email = $_POST["email"];
  $password = $_POST["password"];

  // Validate the data (Crucial for security!)
  if (empty($username) || empty($email) || empty($password)) {
    $error = "All fields are required.";
  } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $error = "Invalid email format.";
  } elseif (strlen($password) < 8) {
    $error = "Password must be at least 8 characters long.";
  }
  // Hash the password (VERY IMPORTANT - never store passwords in plain text!)
  $hashed_password = password_hash($password, PASSWORD_DEFAULT);

  // Connect to the database
  $conn = new mysqli($servername, $username, $password, $dbname);

  // Check connection
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  // Prepare the SQL query
  $sql = "INSERT INTO users (username, email, password) VALUES ('$username', '$email', '$hashed_password')";

  // Execute the query
  if ($conn->query($sql) === TRUE) {
    // Registration successful
    $success = true;
  } else {
    // Registration failed
    $error = "Registration failed: " . $conn->error;
  }
}
?>
