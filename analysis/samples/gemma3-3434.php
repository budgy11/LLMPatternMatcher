    <label for="firstname">First Name:</label>
    <input type="text" id="firstname" name="firstname" placeholder="Your First Name" required><br><br>

    <label for="lastname">Last Name:</label>
    <input type="text" id="lastname" name="lastname" placeholder="Your Last Name" required><br><br>

    <label for="email">Email:</label>
    <input type="email" id="email" name="email" placeholder="Your Email" required><br><br>

    <label for="password">Password:</label>
    <input type="password" id="password" name="password" minlength="6" required><br><br>

    <input type="submit" value="Register">
  </form>

</body>
</html>


<?php

// Database Configuration (Replace with your actual database details)
$db_host = "localhost";
$db_name = "your_database_name";
$db_user = "your_db_user";
$db_password = "your_db_password";

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Validate the form input
  $username = trim($_POST["username"]);
  $email = trim($_POST["email"]);
  $password = trim($_POST["password"]);

  // Basic validation
  if (empty($username) || empty($email) || empty($password)) {
    $errors = "All fields are required.";
  } elseif (
    !preg_match("/^[a-zA-Z0-9._-]+$/", $username) || // Username validation
    !filter_var($email, FILTER_VALIDATE_EMAIL) || // Email validation
    strlen($password) < 8 // Password minimum length
  ) {
    $errors = "Invalid input. Please check your username, email, and password.";
  } else {
    // Hash the password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Prepare the SQL query
    $sql = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";

    // Prepare the statement (for security - prevents SQL injection)
    $stmt = $conn->prepare($sql);

    // Bind the parameters
    $stmt->bind_param("ss", $username, $hashed_password);

    // Execute the query
    if ($stmt->execute()) {
      // Registration successful
      $success = true;
      $message = "Registration successful! Please log in.";
    } else {
      // Registration failed
      $success = false;
      $message = "Registration failed. Please try again.";
    }

    // Close the statement
    $stmt->close();
  }
}

// Display error messages or success message
?>
