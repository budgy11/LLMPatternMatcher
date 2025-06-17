

<?php

// Database credentials (replace with your actual values)
$db_host = "localhost";
$db_name = "your_database_name";
$db_user = "your_db_user";
$db_password = "your_db_password";

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

  // Get form data
  $username = $_POST["username"];
  $email = $_POST["email"];
  $password = $_POST["password"];
  $confirm_password = $_POST["confirm_password"];

  // Validate the data
  $errors = [];
  if (empty($username)) {
    $errors[] = "Username cannot be empty.";
  }
  if (empty($email)) {
    $errors[] = "Email cannot be empty.";
  }
  if (empty($password)) {
    $errors[] = "Password cannot be empty.";
  }
  if (empty($confirm_password)) {
    $errors[] = "Confirm Password cannot be empty.";
  }
  if ($password != $confirm_password) {
    $errors[] = "Passwords do not match.";
  }

  // Check if email is valid
  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors[] = "Invalid email format.";
  }

  // If no errors, proceed with registration
  if (empty($errors)) {
    // Hash the password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // SQL query to insert the new user
    $sql = "INSERT INTO users (username, email, password) VALUES ('$username', '$email', '$hashed_password')";

    // Execute the query
    if (mysqli_query($GLOBALS["conn"], $sql)) {
      // Registration successful
      echo "<h2>Registration successful!</h2>";
      echo "<p>You have been successfully registered. Please <a href='login.php'>log in</a>.</p>";
    } else {
      // Registration failed
      echo "<h2>Registration failed!</h2>";
      echo "<p>Error: " . mysqli_error($GLOBALS["conn"]) . "</p>";
    }
  } else {
    // Display errors
    echo "<h2>Registration Failed</h2>";
    echo "<ul>";
    foreach ($errors as $error) {
      echo "<li>" . $error . "</li>";
    }
    echo "</ul>";
  }
}
?>
