

<?php

// Database configuration (replace with your actual details)
$db_host = "localhost";
$db_name = "your_database_name";
$db_user = "your_database_user";
$db_password = "your_database_password";

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

  // Validate the form data
  $username = trim($_POST["username"]);
  $email = trim($_POST["email"]);
  $password = trim($_POST["password"]);

  // Sanitize and validate the input (Important for security!)
  $username = filter_var($username, FILTER_SANITIZE_STRING);
  $email = filter_var($email, FILTER_SANITIZE_EMAIL);
  $password = filter_var($password, FILTER_SANITIZE_STRING);

  // Check for empty fields
  if (empty($username) || empty($email) || empty($password)) {
    $errors = "All fields are required.";
  } else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors = "Invalid email format.";
  } else if (!preg_match("/^[a-zA-Z0-9._-]+$/", $username)) {
    $errors = "Username must contain only letters, numbers, periods, underscores, and hyphens.";
  } else if (strlen($password) < 8) {
    $errors = "Password must be at least 8 characters long.";
  }

  // If no errors, proceed with registration
  if (empty($errors)) {
    // Hash the password (VERY IMPORTANT - never store passwords in plain text!)
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Connect to the database
    $conn = new mysqli($db_host, $db_user, $db_password, $db_name);

    // Check the connection
    if ($conn->connect_error) {
      die("Connection failed: " . $conn->connect_error);
    }

    // Prepare the SQL query
    $sql = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";

    // Prepare the statement
    $stmt = $conn->prepare($sql);

    // Bind the parameters
    $stmt->bind_param("ss", $username, $hashed_password);

    // Execute the query
    if ($stmt->execute()) {
      // Registration successful
      echo "Registration successful!  You have been redirected.";
      // Optionally, redirect to a success page
      header("Location: success.php");
      exit();
    } else {
      // Registration failed
      echo "Registration failed: " . $stmt->error;
    }

    // Close the statement and connection
    $stmt->close();
    $conn->close();
  }
}
?>
