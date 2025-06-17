

<?php

// Database configuration (replace with your actual credentials)
$host = "localhost";
$username = "your_username";
$password = "your_password";
$database = "your_database";

// Form handling (registration form)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Get form data
  $username = trim($_POST["username"]);
  $email = trim($_POST["email"]);
  $password = trim($_POST["password"]);
  $confirm_password = trim($_POST["confirm_password"]);

  // Validate the form data
  $errors = [];

  if (empty($username)) {
    $errors[] = "Username cannot be empty.";
  }
  if (empty($email)) {
    $errors[] = "Email cannot be empty.";
  }
  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors[] = "Invalid email format.";
  }
  if (empty($password)) {
    $errors[] = "Password cannot be empty.";
  }
  if ($password != $confirm_password) {
    $errors[] = "Passwords do not match.";
  }

  // Check if there are any errors
  if (empty($errors)) {
    // Prepare the SQL query
    $sql = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";

    // Use prepared statement to prevent SQL injection
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $username, $password);

    // Execute the query
    if ($stmt->execute()) {
      // Registration successful
      echo "Registration successful! You have been redirected.";
      // Optionally, redirect the user to a login page or homepage
      header("Location: login.php");
      exit();
    } else {
      // Registration failed
      echo "Registration failed! " . $conn->error;
    }
  }
}
?>
