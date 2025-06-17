    <label for="username">Username:</label>
    <input type="text" id="username" name="username" required>

    <br><br>

    <label for="email">Email:</label>
    <input type="email" id="email" name="email" required>

    <br><br>

    <label for="password">Password:</label>
    <input type="password" id="password" name="password" required>

    <br><br>

    <button type="submit">Register</button>
  </form>

  <p>Already have an account? <a href="login.php">Login</a></p>
</body>
</html>


<?php

// Database Configuration (Replace with your actual database details)
$db_host = "localhost";
$db_name = "your_database_name";
$db_user = "your_db_user";
$db_pass = "your_db_password";

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Get form data
  $username = trim($_POST["username"]);
  $email = trim($_POST["email"]);
  $password = trim($_POST["password"]);

  // Validate Input (Important for security!)
  $errors = [];

  // Username validation
  if (empty($username)) {
    $errors[] = "Username cannot be empty.";
  } elseif (strlen($username) < 3) {
    $errors[] = "Username must be at least 3 characters long.";
  }

  // Email validation
  if (empty($email)) {
    $errors[] = "Email cannot be empty.";
  } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors[] = "Invalid email format.";
  }

  // Password validation
  if (empty($password)) {
    $errors[] = "Password cannot be empty.";
  } elseif (strlen($password) < 8) {
    $errors[] = "Password must be at least 8 characters long.";
  }

  // Check if there are any errors
  if (count($errors) > 0) {
    // Display error messages
    echo "<div class='error'><ul>";
    foreach ($errors as $error) {
      echo "<li>" . $error . "</li>";
    }
    echo "</ul></div>";
  } else {
    // Prepare the SQL query
    $sql = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";
    $stmt = $db_conn->prepare($sql); // Use prepared statements for security!

    // Hash the password (Important for security!)
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Bind the values to the prepared statement
    $stmt->bind_param("sss", $username, $email, $hashed_password);

    // Execute the query
    if ($stmt->execute()) {
      // Registration successful
      echo "<div class='success'>Registration successful!</div>";
      // Optionally, redirect the user to a login page or homepage
      // header("Location: login.php");
    } else {
      // Registration failed
      echo "<div class='error'>Registration failed.  " . $db_conn->error . "</div>";
    }
    $stmt->close(); // Close the statement
  }
}

// Check if the registration form is being displayed
?>
