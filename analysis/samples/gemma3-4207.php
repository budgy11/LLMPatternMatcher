
<!-- HTML Form (Example) -->
<form method="post" action="">
  <label for="username">Username:</label>
  <input type="text" id="username" name="username">

  <label for="password">Password:</label>
  <input type="password" id="password" name="password">

  <label for="email">Email:</label>
  <input type="email" id="email" name="email">

  <button type="submit">Register</button>
</form>


<?php

// Database connection details (Replace with your actual credentials)
$db_host = "localhost";
$db_user = "your_username";
$db_password = "your_password";
$db_name = "your_database_name";

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Validate input (Crucial for security)
  $username = trim($_POST["username"]);
  $email = trim($_POST["email"]);
  $password = trim($_POST["password"]);
  $confirm_password = trim($_POST["confirm_password"]);

  // Validate email format
  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $email_error = "Invalid email address.";
  }

  // Check if passwords match
  if ($password != $confirm_password) {
    $password_error = "Passwords do not match.";
  }

  // Check if username is empty
  if (empty($username)) {
    $username_error = "Username cannot be empty.";
  }

  // Hash the password before storing it in the database
  $hashed_password = password_hash($password, PASSWORD_DEFAULT);

  // Database query (Use prepared statements for security)
  $sql = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";
  $stmt = $conn->prepare($sql); // Assuming $conn is your database connection object
  $stmt->bind_param("ss", $username, $hashed_password);

  if ($stmt->execute()) {
    // Registration successful
    echo "Registration successful! Please check your email to activate your account.";
  } else {
    // Registration failed
    echo "Registration failed. Error: " . $stmt->error;
  }

  $stmt->close();
}
?>
