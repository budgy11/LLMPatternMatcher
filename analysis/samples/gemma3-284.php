

<?php

// Database connection details (Replace with your actual credentials)
$host = "localhost";
$username = "your_db_username";
$password = "your_db_password";
$database = "your_db_name";

// Login Form Processing
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Get form data
  $username = $_POST["username"];
  $password = $_POST["password"];

  // Validate username and password (IMPORTANT: NEVER store passwords in plain text)
  if (empty($username) || empty($password)) {
    $error = "Username and password cannot be empty.";
  } else {
    // Hash the password before comparing (Recommended security practice)
    // You'll need to implement a hashing function like password_hash()
    // This example assumes you've already hashed the password in your database
    $hashedPassword = "your_hashed_password"; //Replace with the hashed password from the database

    if ($hashedPassword == $password) {
      // Login successful
      session_start();
      $_SESSION["username"] = $username; // Store username in session
      header("Location: welcome.php"); // Redirect to welcome page
      exit;
    } else {
      $error = "Incorrect username or password.";
    }
  }
}
?>
