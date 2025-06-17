
<!DOCTYPE html>
<html>
<head>
  <title>User Registration</title>
</head>
<body>

  <h2>User Registration</h2>

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

// Database configuration (replace with your actual database credentials)
$db_host = 'localhost';
$db_name = 'your_database_name';
$db_user = 'your_db_user';
$db_pass = 'your_db_password';

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

  // Get form data
  $username = $_POST["username"];
  $email = $_POST["email"];
  $password = $_POST["password"];

  // Validate the data (important for security!)
  if (empty($username) || empty($email) || empty($password)) {
    $error = "All fields are required.";
  } elseif (filter_var($email, FILTER_VALIDATE_EMAIL) === false) {
    $error = "Invalid email format.";
  } elseif (strlen($password) < 8) {
    $error = "Password must be at least 8 characters long.";
  }

  // Hash the password (VERY IMPORTANT!)
  $hashed_password = password_hash($password, PASSWORD_DEFAULT);

  //  Simple validation - you'd want more robust email domain validation
  if (empty($error)) {
    // Database connection
    $conn = new mysqli($db_host, $db_user, $db_pass, $db_name);

    // Check connection
    if ($conn->connect_error) {
      die("Connection failed: " . $conn->connect_error);
    }

    // Prepare the SQL query
    $sql = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";

    // Prepare the statement
    $stmt = $conn->prepare($sql);

    // Bind the parameters
    $stmt->bind_param("ss", $username, $email, $hashed_password);

    // Execute the query
    if ($stmt->execute()) {
      $success = "Registration successful!  Please check your email to verify your account.";
      $stmt->close(); // Close the statement
    } else {
      $error = "Registration failed.  Error: " . $stmt->error;
      $stmt->close(); // Close the statement
    }

    // Close the connection
    $conn->close();
  }
}
?>
