    <label for="username">Username:</label>
    <input type="text" id="username" name="username" required><br><br>

    <label for="email">Email:</label>
    <input type="email" id="email" name="email" required><br><br>

    <label for="password">Password:</label>
    <input type="password" id="password" name="password" required><br><br>

    <label for="confirm_password">Confirm Password:</label>
    <input type="password" id="confirm_password" name="confirm_password" required><br><br>

    <input type="submit" value="Register">
  </form>

</body>
</html>


<?php

// Database configuration (replace with your actual details)
$db_host = "localhost";
$db_name = "your_database_name";
$db_user = "your_database_user";
$db_password = "your_database_password";

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

  // Get form data
  $username = trim($_POST["username"]);
  $email = trim($_POST["email"]);
  $password = trim($_POST["password"]);

  // Validate input (IMPORTANT - ALWAYS VALIDATE!)
  $errors = [];

  // Username validation
  if (empty($username)) {
    $errors[] = "Username cannot be empty.";
  }
  if (strlen($username) < 3) {
    $errors[] = "Username must be at least 3 characters long.";
  }
  if (preg_match('/^\s*$/', $username)) { // Check for empty username
    $errors[] = "Username cannot be empty.";
  }

  // Email validation
  if (empty($email)) {
    $errors[] = "Email cannot be empty.";
  }
  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors[] = "Invalid email format.";
  }

  // Password validation
  if (empty($password)) {
    $errors[] = "Password cannot be empty.";
  }
  if (strlen($password) < 8) {
    $errors[] = "Password must be at least 8 characters long.";
  }

  // Check if there are any errors
  if (count($errors) > 0) {
    // Display the form with the errors
    echo "<h2>Registration Error:</h2>";
    echo "<p>".implode("</p><p>", $errors)."</p>";
    echo "<p><a href=\"register.php\">Try again</a></p>";
    exit;
  }

  // Hash the password (VERY IMPORTANT for security!)
  $hashed_password = password_hash($password, PASSWORD_DEFAULT);

  // Prepare the SQL query
  $sql = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";

  // Prepare the statement
  $stmt = $db_conn->prepare($sql);  // Use $db_conn instead of $conn

  // Bind the parameters
  $stmt->bind_param("ss", $username, $hashed_password);

  // Execute the query
  if ($stmt->execute()) {
    // Registration successful
    echo "<h2>Registration Successful!</h2>";
    echo "<p>You have successfully registered.  Please <a href=\"login.php\">log in</a>.</p>";
  } else {
    // Registration failed
    echo "<h2>Registration Failed!</h2>";
    echo "<p>An error occurred during registration.  Please try again later.</p>";
  }

  // Close the statement
  $stmt->close();

}
?>
