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

// Database connection details (Replace with your actual credentials)
$db_host = "localhost";
$db_name = "your_database_name";
$db_user = "your_username";
$db_pass = "your_password";

// Check if the registration form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Validate and sanitize input data
  $username = trim($_POST["username"]);
  $email = trim($_POST["email"]);
  $password = trim($_POST["password"]);

  // Input validation (Important!  Robust validation is critical for security)
  if (empty($username) || empty($email) || empty($password)) {
    $errors = "All fields are required.";
  } elseif (strlen($username) < 3) {
    $errors = "Username must be at least 3 characters long.";
  } elseif (preg_match("/@/", $email)) {
    $errors = "Invalid email format.";
  } elseif (strlen($password) < 8) {
    $errors = "Password must be at least 8 characters long.";
  } else {
    // Hash the password (Important for security!)
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Prepare the SQL query
    $sql = "INSERT INTO users (username, email, password)
            VALUES ('$username', '$email', '$hashed_password')";

    // Execute the query
    if (mysqli_query($GLOBALS["conn"], $sql)) {
      $success = "Registration successful! You have been redirected.";
    } else {
      $errors = "Error registering: " . mysqli_error($GLOBALS["conn"]);
    }
  }
}

// Establish database connection
$GLOBALS["conn"] = mysqli_connect($db_host, $db_user, $db_pass, $db_name);

if (!$GLOBALS["conn"]) {
  die("Connection failed: " . mysqli_connect_error());
}

?>
