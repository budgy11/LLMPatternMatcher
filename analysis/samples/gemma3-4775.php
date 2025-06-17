

<?php

// Database connection details (Replace with your actual values)
$host = "localhost";
$username = "your_username";
$password = "your_password";
$database = "your_database";

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

  // Validate the form data (Basic validation - improve this for production)
  $username = trim($_POST["username"]);
  $email = trim($_POST["email"]);
  $password = trim($_POST["password"]);

  // Check if required fields are filled
  if (empty($username) || empty($email) || empty($password)) {
    $errors = "All fields are required.";
  }
  // Validate email format (basic check)
  elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors = "Invalid email format.";
  }
  // You'd add more complex password validation here (e.g., minimum length, special characters)
  // For simplicity, we'll just check if the password is not empty
  else {
    // Hash the password (VERY IMPORTANT for security!)
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // SQL query to insert the new user into the database
    $sql = "INSERT INTO users (username, email, password) VALUES ('$username', '$email', '$hashed_password')";

    // Execute the query
    if (mysqli_query($GLOBALS["conn"], $sql)) {
      $success = "Registration successful. Please log in.";
    } else {
      $errors = "Error registering user. " . mysqli_error($GLOBALS["conn"]);
    }
  }
}

// Connect to the database
$GLOBALS["conn"] = mysqli_connect($host, $username, $password, $database);

// Check the connection
if ($GLOBALS["conn"]->connect_error) {
  die("Connection failed: " . $GLOBALS["conn"]->connect_error);
}

?>
