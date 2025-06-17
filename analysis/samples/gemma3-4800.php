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

// Database configuration (replace with your actual credentials)
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
  $confirm_password = trim($_POST["confirm_password"]);

  // Validate the input (IMPORTANT - Sanitize and Validate)
  if (empty($username) || empty($email) || empty($password) || empty($confirm_password)) {
    $errors = "All fields are required.";
  } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors = "Invalid email format.";
  } elseif ($password != $confirm_password) {
    $errors = "Passwords do not match.";
  } else {
    // Hash the password (VERY IMPORTANT for security)
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // SQL query to insert the new user
    $sql = "INSERT INTO users (username, email, password) VALUES ('$username', '$email', '$hashed_password')";

    // Execute the query
    if (mysqli_query($GLOBALS["conn"], $sql)) {
      $success = "Registration successful! You have been redirected.";
    } else {
      $errors = "Error registering user. " . mysqli_error($GLOBALS["conn"]);
    }
  }
}

// Connect to the database
$GLOBALS["conn"] = new mysqli($db_host, $db_user, $db_password, $db_name);

// Check connection
if ($GLOBALS["conn"]->connect_error) {
  die("Connection failed: " . $GLOBALS["conn"]->connect_error);
}


?>
