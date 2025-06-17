    Username: <input type="text" name="username" required><br><br>
    Password: <input type="password" name="password" required><br><br>
    Email: <input type="email" name="email" required><br><br>
    <input type="submit" value="Register">
</form>

</body>
</html>


<?php

// Database connection details (replace with your actual values)
$db_host = "localhost";
$db_name = "your_database_name";
$db_user = "your_username";
$db_password = "your_password";

// Check if the registration form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Validate the form data (essential for security)
  $username = trim($_POST["username"]);
  $email = trim($_POST["email"]);
  $password = trim($_POST["password"]);

  // Basic validation - add more robust validation as needed
  if (empty($username) || empty($email) || empty($password)) {
    $error_message = "All fields are required.";
  } elseif (
    !preg_match("/^[a-zA-Z0-9_]+$/", $username) // Username validation - letters, numbers, underscore
  ) {
    $error_message = "Username can only contain letters, numbers, and underscores.";
  } elseif (
    !filter_var($email, FILTER_VALIDATE_EMAIL)
  ) {
    $error_message = "Invalid email address.";
  } elseif (strlen($password) < 8) {
    $error_message = "Password must be at least 8 characters long.";
  } else {
    // Hash the password before storing it in the database
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Prepare the SQL query
    $sql = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";

    // Prepare the statement
    $stmt = $conn->prepare($sql);

    // Bind the parameters
    $stmt->bind_param("ss", $username, $hashed_password);

    // Execute the query
    if ($stmt->execute()) {
      $success_message = "Registration successful! Please check your email to verify your account.";
    } else {
      $error_message = "Registration failed. " . $stmt->error; //Get specific error
    }
  }
}

// Display the registration form
?>
