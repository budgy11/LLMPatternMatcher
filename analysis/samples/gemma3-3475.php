
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

    <label for="password">Password:</label>
    <input type="password" id="password" name="password" required><br><br>

    <label for="email">Email:</label>
    <input type="email" id="email" name="email" required><br><br>

    <input type="submit" value="Register">
  </form>

</body>
</html>


<?php

// Database configuration (Replace with your actual database details)
$db_host = "localhost";
$db_name = "your_database_name";
$db_user = "your_database_user";
$db_password = "your_database_password";

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Get form data
  $username = $_POST["username"];
  $email = $_POST["email"];
  $password = $_POST["password"];

  // Validate the data (Important!  Add more robust validation as needed)
  if (empty($username) || empty($email) || empty($password)) {
    $error_message = "All fields are required.";
  } elseif (
    !preg_match("/^[a-zA-Z0-9._-]+$/", $username)  // Simple username validation
  ) {
    $error_message = "Username must contain only alphanumeric characters, periods, underscores, and hyphens.";
  } elseif (
    !filter_var($email, FILTER_VALIDATE_EMAIL)
  ) {
    $error_message = "Invalid email format.";
  } elseif (
    strlen($password) < 8
  ) {
    $error_message = "Password must be at least 8 characters long.";
  }
  else {
    // Hash the password (VERY IMPORTANT for security!)
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // SQL query to insert the new user
    $sql = "INSERT INTO users (username, email, password) VALUES ('$username', '$email', '$hashed_password')";

    // Execute the query
    if (mysqli_query($GLOBALS["conn"], $sql)) {
      $success_message = "Registration successful! You can now log in.";
    } else {
      $error_message = "Error registering user: " . mysqli_error($GLOBALS["conn"]);
    }
  }
}

// Connect to the database
$GLOBALS["conn"] = new mysqli($db_host, $db_user, $db_password, $db_name);

// Check connection
if ($GLOBALS["conn"]->connect_error) {
  die("Connection failed: " . $GLOBALS["conn"]->connect_error);
}


?>
