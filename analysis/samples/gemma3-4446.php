
<!DOCTYPE html>
<html>
<head>
  <title>User Registration</title>
</head>
<body>

  <h1>User Registration</h1>

  <form method="post" action="">
    <label for="username">Username:</label>
    <input type="text" id="username" name="username" required><br><br>

    <label for="password">Password:</label>
    <input type="password" id="password" name="password" required><br><br>

    <label for="email">Email:</label>
    <input type="email" id="email" name="email" required><br><br>

    <button type="submit">Register</button>
  </form>

</body>
</html>


<?php

// Database connection details (replace with your actual credentials)
$db_host = "localhost";
$db_name = "your_database_name";
$db_user = "your_database_user";
$db_password = "your_database_password";

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

  // Sanitize the input data
  $username = filter_input(INPUT_POST, "username", FILTER_SANITIZE_STRING);
  $email = filter_input(INPUT_POST, "email", FILTER_SANITIZE_EMAIL);
  $password = filter_input(INPUT_POST, "password", FILTER_SANITIZE_STRING);

  // Validate the input data
  $username = trim($username);
  $email = trim($email);
  $password = trim($password);

  // Check if username and email are empty
  if (empty($username) || empty($email) || empty($password)) {
    $error_message = "All fields are required.";
  } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $error_message = "Invalid email format.";
  } elseif (strlen($password) < 8) {
    $error_message = "Password must be at least 8 characters long.";
  }
  // Check if username already exists in the database
  $sql = "SELECT * FROM users WHERE username = '$username'";
  $result = mysqli_query($GLOBALS['db_host'], $sql);
  if (mysqli_num_rows($result) > 0) {
    $error_message = "Username already exists.";
  }

  // If no errors, proceed with user registration
  if (empty($error_message)) {
    // Hash the password (important for security!)
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // SQL query to insert the new user
    $sql = "INSERT INTO users (username, email, password) VALUES ('$username', '$email', '$hashed_password')";

    // Execute the query
    mysqli_query($GLOBALS['db_host'], $sql);

    // Redirect to a success page or display a success message
    echo "<script>alert('Registration successful! Please check your email to activate your account.');</script>";
    // Redirect (optional) - replace with your actual success page URL
    // header("Location: success.php");
    // exit();
  }
}
?>
