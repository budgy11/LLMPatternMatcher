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

// Database configuration (replace with your actual credentials)
$db_host = "localhost";
$db_name = "your_database_name";
$db_user = "your_database_user";
$db_password = "your_database_password";

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

  // Validate the form data
  $username = trim($_POST["username"]);
  $email = trim($_POST["email"]);
  $password = trim($_POST["password"]);

  // Perform input validation - IMPORTANT!  This prevents common vulnerabilities.
  if (empty($username) || empty($email) || empty($password)) {
    $error_message = "All fields are required.";
  } elseif (strlen($username) < 3) {
    $error_message = "Username must be at least 3 characters long.";
  } elseif (filter_var($email, FILTER_VALIDATE_EMAIL) === false) {
    $error_message = "Invalid email address.";
  } elseif (strlen($password) < 8) {
    $error_message = "Password must be at least 8 characters long.";
  } else {
    // Hash the password (VERY IMPORTANT for security!)
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Prepare the SQL query
    $sql = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";

    // Prepare the statement
    $stmt = $conn->prepare($sql);

    if ($stmt) {
      // Bind the parameters
      $stmt->bind_param("ss", $username, $hashed_password);

      // Execute the query
      if ($stmt->execute()) {
        // Registration successful
        $success_message = "Registration successful! Please log in.";
      } else {
        // Query execution error
        $error_message = "Error registering user.  Check your database connection.";
      }
    } else {
      // Prepare failed
      $error_message = "Error preparing statement.";
    }
  }
}
?>
