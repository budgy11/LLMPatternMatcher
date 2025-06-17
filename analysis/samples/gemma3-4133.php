
<!DOCTYPE html>
<html>
<head>
  <title>User Registration</title>
</head>
<body>

  <h1>User Registration</h1>

  <form action="register.php" method="POST">
    <label for="username">Username:</label>
    <input type="text" id="username" name="username" required><br><br>

    <label for="email">Email:</label>
    <input type="email" id="email" name="email" required><br><br>

    <label for="password">Password:</label>
    <input type="password" id="password" name="password" required><br><br>

    <label for="confirm_password">Confirm Password:</label>
    <input type="password" id="confirm_password" name="confirm_password" required><br><br>

    <button type="submit">Register</button>
  </form>

</body>
</html>


<?php

// Database configuration (Replace with your actual database details)
$db_host = "localhost";
$db_name = "your_database_name";
$db_user = "your_db_user";
$db_pass = "your_db_password";

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

  // Validate the form data
  $username = trim($_POST["username"]);
  $email = trim($_POST["email"]);
  $password = trim($_POST["password"]);

  // Sanitize the inputs to prevent common vulnerabilities
  $username = filter_var($username, FILTER_SANITIZE_STRING);
  $email = filter_var($email, FILTER_SANITIZE_EMAIL);
  $password = filter_var($password, FILTER_SANITIZE_STRING);

  // Validate the email format
  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $error_message = "Invalid email format.";
  }
  // Validate the password (minimum length)
  if (strlen($password) < 8) {
    $error_message = "Password must be at least 8 characters long.";
  }

  // Check if username already exists
  $sql = "SELECT * FROM users WHERE username = '$username'";
  $result = mysqli_query($GLOBALS['db_host'], $sql);
  $user = mysqli_fetch_assoc($result);

  if ($user) {
    $error_message = "Username already exists.";
  }


  // If there are no errors, insert the user into the database
  if (!isset($error_message)) {
    $password_hash = password_hash($password, PASSWORD_DEFAULT); // Hash the password
    $sql = "INSERT INTO users (username, email, password) VALUES ('$username', '$email', '$password_hash')";

    if (mysqli_query($GLOBALS['db_host'], $sql)) {
      $success_message = "Registration successful! Please check your email to verify your account.";
    } else {
      $error_message = "Error inserting user. " . mysqli_error($GLOBALS['db_host']);
    }
  }
}
?>
