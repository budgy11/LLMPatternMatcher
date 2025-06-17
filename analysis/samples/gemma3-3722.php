
<!DOCTYPE html>
<html>
<head>
    <title>User Registration</title>
</head>
<body>

<h1>User Registration</h1>

<form action="register.php" method="post">
    <label for="username">Username:</label>
    <input type="text" id="username" name="username" required>
    <br><br>

    <label for="email">Email:</label>
    <input type="email" id="email" name="email" required>
    <br><br>

    <label for="password">Password:</label>
    <input type="password" id="password" name="password" required>
    <br><br>

    <input type="submit" value="Register">
</form>

</body>
</html>


<?php

// Database Configuration (Replace with your actual credentials)
$db_host = "localhost";
$db_name = "your_database_name";
$db_user = "your_username";
$db_password = "your_password";

// Check if the registration form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

  // Validate input (Important for security - prevent SQL injection and other issues)
  $username = trim($_POST["username"]);
  $email = trim($_POST["email"]);
  $password = trim($_POST["password"]);

  // Basic validation (customize as needed)
  if (empty($username) || empty($email) || empty($password)) {
    $error_message = "All fields are required.";
  } elseif (
    !preg_match("/^[a-zA-Z0-9_]+$/", $username)  // Allow only alphanumeric and underscores for username
  ) {
    $error_message = "Username must contain only letters, numbers, and underscores.";
  } elseif (
    !filter_var($email, FILTER_VALIDATE_EMAIL)
  ) {
    $error_message = "Invalid email format.";
  } elseif (
    strlen($password) < 8  //  Minimum password length
  ) {
    $error_message = "Password must be at least 8 characters long.";
  } else {
    // Hash the password before storing it in the database (VERY IMPORTANT FOR SECURITY)
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Prepare the SQL query (Using prepared statements to prevent SQL injection)
    $sql = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sss", $username, $email, $hashed_password);
    $stmt->execute();

    // Success message
    $success_message = "Registration successful! Please log in.";

    // Close the statement
    $stmt->close();
  }
}
?>
