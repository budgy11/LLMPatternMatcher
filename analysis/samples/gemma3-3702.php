

<?php

// Database Configuration (Replace with your actual values)
$db_host = 'localhost';
$db_name = 'your_database_name';
$db_user = 'your_database_user';
$db_password = 'your_database_password';

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

  // Get form data
  $username = $_POST["username"];
  $password = $_POST["password"];
  $email = $_POST["email"];

  // Validate input (Important for security!)
  if (empty($username) || empty($password) || empty($email)) {
    $error = "All fields are required.";
  } elseif (strlen($username) < 3) {
    $error = "Username must be at least 3 characters long.";
  } elseif (preg_match('/@/', $email)) { // Check for invalid email format
    $error = "Invalid email format.";
  } else {
    // Hash the password (VERY IMPORTANT!)
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Prepare the SQL query
    $sql = "INSERT INTO users (username, password, email) VALUES (?, ?, ?)";

    // Prepare the statement
    $stmt = $conn->prepare($sql);

    // Bind the parameters
    $stmt->bind_param("ss", $username, $hashed_password, $email);

    // Execute the query
    if ($stmt->execute()) {
      $success = "Registration successful! You have been logged in.";
    } else {
      $error = "Registration failed. " . $stmt->error;
    }
  }
}

// Start the session
session_start();

// Display error or success message
if (isset($error)) {
  echo "<div class='error'>" . $error . "</div>";
}

if (isset($success)) {
  echo "<div class='success'>" . $success . "</div>";
  // Redirect the user to the login page (or home page)
  header("Location: login.php");
  exit; // Important: Exit after redirection
}

?>
