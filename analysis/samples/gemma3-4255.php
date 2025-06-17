

<?php

// Database credentials (replace with your actual values)
$db_host = "localhost";
$db_name = "your_database_name";
$db_user = "your_db_user";
$db_pass = "your_db_password";

// Check if the registration form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

  // Validate the form data (very important to prevent security vulnerabilities)
  $username = trim($_POST["username"]);
  $email = trim($_POST["email"]);
  $password = trim($_POST["password"]);
  $confirm_password = trim($_POST["confirm_password"]);

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
  } elseif (strlen($password) < 8) {
    $password_error = "Password must be at least 8 characters long.";
  }

  // Check if passwords match
  if ($password != $confirm_password) {
    $password_error = "Passwords must match.";
  }

  // If no errors, proceed with registration
  if (empty($username_error) && empty($email_error) && empty($password_error)) {

    // Escape user input to prevent SQL injection
    $username = $db_lib->real_escape_string($username);  // Assuming you have a database library with real_escape_string

    // Hash the password (VERY IMPORTANT for security)
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // SQL query to insert the new user
    $sql = "INSERT INTO users (username, email, password) VALUES ('$username', '$email', '$hashed_password')";

    // Execute the query
    $result = mysqli_query($conn, $sql); // Assuming you're using mysqli

    if ($result) {
      // Registration successful
      echo "<h2>Registration successful!</h2>";
      echo "<p>You have successfully created a new account.</p>";
      // Redirect to a success page or homepage
      header("Location: /success.php"); // Replace with your success page URL
      exit();
    } else {
      // Registration failed
      echo "<h2>Registration failed!</h2>";
      echo "<p>Error: " . mysqli_error($conn) . "</p>";
    }
  }
}
?>
