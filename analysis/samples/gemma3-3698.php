    <label for="username">Username:</label>
    <input type="text" id="username" name="username" placeholder="Enter username"><br><br>

    <label for="password">Password:</label>
    <input type="password" id="password" name="password" placeholder="Enter password"><br><br>

    <label for="email">Email:</label>
    <input type="email" id="email" name="email" placeholder="Enter email"><br><br>

    <button type="submit">Register</button>
</form>

</body>
</html>


<?php

// Assuming you have a database connection established (e.g., mysqli)
// Replace with your actual database connection details
$servername = "localhost";
$username = "your_username";
$password = "your_password";
$dbname = "your_database_name";

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

  // Validate the form input
  $username = trim($_POST["username"]);
  $email = trim($_POST["email"]);
  $password = trim($_POST["password"]);

  // Validate username
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

  // Validate password
  if (empty($password)) {
    $password_error = "Password cannot be empty.";
  } elseif (strlen($password) < 6) {
    $password_error = "Password must be at least 6 characters long.";
  }


  // If validation passes, proceed with registration
  if (!empty($username_error) || !empty($email_error) || !empty($password_error)) {
    $errors = [];
    if ($username_error) $errors[] = $username_error;
    if ($email_error) $errors[] = $email_error;
    if ($password_error) $errors[] = $password_error;
    $_SESSION["registration_errors"] = $errors; // Store errors in session
    // Redirect to the registration page to display the errors
    header("Location: registration.php");
    exit(); // Important to stop further execution
  } else {
    // Prepare the SQL query
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);  // Hash the password securely
    $sql = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql); // Use prepared statement for security

    if ($stmt) {
      $stmt->bind_param("ss", $username, $hashed_password);
      $stmt->execute();
      // Success!  Redirect to a login page or welcome page
      header("Location: login.php");
      exit();
    } else {
      // Handle the error preparing the statement
      echo "Error preparing statement: " . $conn->error;
    }
  }
} else {
  // If the form was not submitted, display the registration form
  $errors = []; // Initialize errors array
  if (isset($_SESSION["registration_errors"])) {
    $errors = $_SESSION["registration_errors"];
    unset($_SESSION["registration_errors"]); // Clear session data
  }
  include 'registration_form.php';
}


// Example registration_form.php (you'll need to create this file)
/*
<!DOCTYPE html>
<html>
<head>
  <title>Registration Form</title>
</head>
<body>

  <h1>Registration</h1>

  <?php if (isset($errors) && !empty($errors)) { ?>
