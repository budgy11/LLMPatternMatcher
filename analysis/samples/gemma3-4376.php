    <label for="username">Username:</label>
    <input type="text" id="username" name="username" required>

    <label for="email">Email:</label>
    <input type="email" id="email" name="email" required>

    <label for="password">Password:</label>
    <input type="password" id="password" name="password" required>

    <label for="confirm_password">Confirm Password:</label>
    <input type="password" id="confirm_password" name="confirm_password" required>

    <button type="submit">Register</button>
  </form>

</body>
</html>


<?php

// Database configuration (replace with your actual values)
$db_host = 'localhost';
$db_name = 'your_database_name';
$db_user = 'your_database_user';
$db_password = 'your_database_password';

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Validate the form data (basic example - enhance with more robust validation)
  $username = trim($_POST["username"]);
  $email = trim($_POST["email"]);
  $password = trim($_POST["password"]);

  // Sanitize the inputs
  $username = filter_var($username, FILTER_SANITIZE_STRING);
  $email = filter_var($email, FILTER_SANITIZE_EMAIL);
  $password = filter_var($password, FILTER_SANITIZE_STRING);

  // Validate required fields
  if (empty($username) || empty($email) || empty($password)) {
    $error_message = "All fields are required.";
  } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $error_message = "Invalid email address.";
  } elseif (strlen($password) < 8) {
    $error_message = "Password must be at least 8 characters long.";
  } else {
    // Hash the password (important for security!)
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // SQL query to insert the user into the database
    $sql = "INSERT INTO users (username, email, password) VALUES ('$username', '$email', '$hashed_password')";

    // Execute the query
    if (mysqli_query($GLOBALS['conn'], $sql)) {
      $success_message = "Registration successful! Please check your email to verify your account.";
    } else {
      $error_message = "Error inserting user. " . mysqli_error($GLOBALS['conn']);
    }
  }
}

// Database connection
$GLOBALS['conn'] = mysqli_connect($db_host, $db_user, $db_password, $db_name);

// Check for connection errors
if (!$GLOBALS['conn']->connect_error) {
  // Display error or success messages
  echo "<h1>User Registration</h1>";

  if (isset($error_message)) {
    echo "<p style='color: red;'>$error_message</p>";
  }

  if (isset($success_message)) {
    echo "<p style='color: green;'>$success_message</p>";
  }

  // Registration form
  echo "<form method='post' action='" . htmlspecialchars($_SERVER["PHP_SELF"]) . "'>";
  echo "<label for='username'>Username:</label><br>";
  echo "<input type='text' id='username' name='username' value='" . htmlspecialchars($username) . "'><br><br>";

  echo "<label for='email'>Email:</label><br>";
  echo "<input type='email' id='email' name='email' value='" . htmlspecialchars($email) . "'><br><br>";

  echo "<label for='password'>Password:</label><br>";
  echo "<input type='password' id='password' name='password' required><br><br>";

  echo "<input type='submit' value='Register'>";
  echo "</form>";
} else {
  echo "<p style='color: red;'>Error connecting to database: " . mysqli_error($GLOBALS['conn']) . "</p>";
}

// Close the database connection
mysqli_close($GLOBALS['conn']);

?>
