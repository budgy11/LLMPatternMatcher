    <label for="username">Username:</label>
    <input type="text" id="username" name="username" required><br><br>

    <label for="password">Password:</label>
    <input type="password" id="password" name="password" required><br><br>

    <label for="email">Email:</label>
    <input type="email" id="email" name="email" required><br><br>

    <button type="submit">Register</button>
  </form>

</body>
</html>


<?php

// Database configuration (Replace with your actual database credentials)
$db_host = "localhost";
$db_name = "your_database_name";
$db_user = "your_db_user";
$db_pass = "your_db_password";

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Get form data
  $username = $_POST["username"];
  $email = $_POST["email"];
  $password = $_POST["password"];
  $confirm_password = $_POST["confirm_password"];

  // Validate the form data (Basic validation - improve this in a production environment)
  $errors = [];

  // Username validation
  if (empty($username)) {
    $errors[] = "Username cannot be empty.";
  }
  if (strlen($username) < 3) {
    $errors[] = "Username must be at least 3 characters long.";
  }
  if (preg_match("/^\s*$/", $username)) { // Check for empty string or whitespace only
    $errors[] = "Username cannot be empty or contain only whitespace.";
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
  // Confirm password validation
  if (empty($confirm_password)) {
    $errors[] = "Confirm password cannot be empty.";
  }
  if ($password != $confirm_password) {
    $errors[] = "Passwords do not match.";
  }

  // If there are errors, display them
  if (!empty($errors)) {
    echo "<h2>Error:</h2><ul>";
    foreach ($errors as $error) {
      echo "<li>" . $error . "</li>";
    }
    echo "</ul>";
  } else {
    // Hash the password (VERY IMPORTANT - never store plain text passwords)
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Prepare the SQL query
    $sql = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";

    // Prepare the statement (best practice for security)
    $stmt = $conn->prepare($sql);

    // Bind the parameters
    $stmt->bind_param("ss", $username, $hashed_password);

    // Execute the query
    if ($stmt->execute()) {
      echo "<h2>Registration Successful!</h2>";
      echo "<p>You have successfully created a new account.</p>";
      // Optionally, redirect the user to a login page or welcome page
      // header("Location: login.php"); // Example
    } else {
      echo "<h2>Registration Failed!</h2>";
      echo "<p>Error: " . $conn->error . "</p>";
    }

    // Close the statement
    $stmt->close();
  }
}
?>
