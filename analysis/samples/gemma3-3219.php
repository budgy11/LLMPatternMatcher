
  <form action="register.php" method="post">
    <label for="username">Username:</label>
    <input type="text" id="username" name="username" required><br><br>

    <label for="email">Email:</label>
    <input type="email" id="email" name="email" required><br><br>

    <label for="password">Password:</label>
    <input type="password" id="password" name="password" required><br><br>

    <button type="submit">Register</button>
  </form>

</body>
</html>


<?php

// Database configuration (Replace with your actual credentials)
$db_host = 'localhost';
$db_name = 'your_database_name';
$db_user = 'your_username';
$db_pass = 'your_password';

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Validate the form data
  $username = trim($_POST["username"]);
  $email = trim($_POST["email"]);
  $password = trim($_POST["password"]);

  // Validate the data
  $username_regex = '/^[a-zA-Z0-9_]+$/'; // Allows alphanumeric and underscore only
  if (!preg_match($username_regex, $username)) {
    $username_error = "Username must contain only alphanumeric characters and underscores.";
  }

  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $email_error = "Invalid email format.";
  }

  if (empty($password)) {
    $password_error = "Password cannot be empty.";
  }

  // Check if username and email are not empty
  if (empty($username) || empty($email)) {
    $username_error = "Username and email are required.";
    $email_error = "Email is required.";
  }
  // Hash the password (Important for security!)
  $hashed_password = password_hash($password, PASSWORD_DEFAULT);


  // If all validations pass, insert the user into the database
  if (empty($username_error) && empty($email_error) && empty($password_error)) {
    // Prepare the SQL query
    $sql = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";

    // Prepare the statement
    $stmt = $conn->prepare($sql);

    // Bind the parameters
    $stmt->bind_param("ss", $username, $email, $hashed_password);

    // Execute the query
    if ($stmt->execute()) {
      // User registered successfully
      echo "Registration successful! Please check your email to verify your account.";
      // Redirect to a success page (optional)
      header("Location: success.php");
      exit();
    } else {
      // Error executing the query
      echo "Error registering user. Please try again.";
      // Log the error for debugging (optional)
      error_log("Error: " . $stmt->error);
    }
  }
}
?>
