

<?php

// Database credentials (Replace with your actual credentials)
$host = "localhost";
$username = "your_username";
$password = "your_password";
$database = "your_database";

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

  // Validate the form data
  $username = trim($_POST["username"]);
  $password = trim($_POST["password"]);
  $email = trim($_POST["email"]);

  // Perform validation
  if (empty($username) || empty($password) || empty($email)) {
    $error_message = "All fields are required.";
  } elseif (strlen($username) < 3) {
    $error_message = "Username must be at least 3 characters.";
  } elseif (preg_match("/@/", $email)) {
    $error_message = "Invalid email format.";
  } else {
    // Prepare the SQL query
    $sql = "INSERT INTO users (username, password, email) VALUES ('$username', '$password', '$email')";

    // Execute the query
    if (mysqli_query($GLOBALS["conn"], $sql)) {
      $success_message = "Registration successful! Please log in.";
    } else {
      $error_message = "Error: " . mysqli_error($GLOBALS["conn"]);
    }
  }
}

// Connect to the database
$GLOBALS["conn"] = mysqli_connect($host, $username, $password, $database);

// Check connection
if (!$GLOBALS["conn"]) {
  die("Connection failed: " . mysqli_connect_error());
}

?>
