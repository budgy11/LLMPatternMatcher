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

// Database connection details (replace with your actual values)
$host     = "localhost";
$username = "your_username";
$password = "your_password";
$database = "your_database";

// Form handling - checking if it's a POST request
if ($_SERVER["REQUEST_METHOD"] == "POST") {

  // Sanitize input data - VERY IMPORTANT for security!
  $username = trim($_POST["username"]);
  $email = trim($_POST["email"]);
  $password = trim($_POST["password"]);

  // Validate input (basic examples - expand as needed)
  if (empty($username) || empty($email) || empty($password)) {
    $error_message = "All fields are required.";
  } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $error_message = "Invalid email format.";
  } elseif (strlen($password) < 6) {
    $error_message = "Password must be at least 6 characters long.";
  }
  else {
    // Hash the password - IMPORTANT for security!
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // SQL query -  Use prepared statements for security!
    $sql = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";

    // Prepare the statement
    $stmt = $conn->prepare($sql);

    // Bind the parameters
    $stmt->bind_param("sss", $username, $email, $hashed_password); // s = string

    // Execute the statement
    if ($stmt->execute()) {
      $success_message = "Registration successful!  Please check your email to verify your account.";
    } else {
      $error_message = "Registration failed: " . $stmt->error;
    }

    // Close the statement
    $stmt->close();
  }
}
?>
