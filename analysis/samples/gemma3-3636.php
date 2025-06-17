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

// Database connection details (replace with your actual values)
$db_host = "localhost";
$db_name = "your_database_name";
$db_user = "your_db_user";
$db_pass = "your_db_password";

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Validate the form data
  $username = filter_input(INPUT_POST, "username", FILTER_SANITIZE_STRING);
  $email = filter_input(INPUT_POST, "email", FILTER_SANITIZE_EMAIL);
  $password = filter_input(INPUT_POST, "password", FILTER_SANITIZE_STRING);


  // Password validation (basic example - you should use a stronger hashing algorithm)
  if (empty($username) || empty($email) || empty($password)) {
    $error = "All fields are required.";
  } elseif (strlen($username) < 3) {
    $error = "Username must be at least 3 characters long.";
  } elseif (filter_var($email, FILTER_VALIDATE_EMAIL) === false) {
    $error = "Invalid email address.";
  } elseif (strlen($password) < 8) {
    $error = "Password must be at least 8 characters long.";
  } else {
    // Hash the password (IMPORTANT - use a strong hashing algorithm like password_hash())
    $hashed_password = password_hash($password, PASSWORD_DEFAULT); 

    // SQL query to insert the new user
    $sql = "INSERT INTO users (username, email, password) VALUES ('$username', '$email', '$hashed_password')";

    // Execute the query
    if (mysqli_query($GLOBALS["conn"], $sql)) {
      $success = "Registration successful! You have been redirected to the login page.";
    } else {
      $error = "Registration failed. " . mysqli_error($GLOBALS["conn"]);
    }
  }
}

// Connect to the database
$GLOBALS["conn"] = mysqli_connect($db_host, $db_user, $db_pass, $db_name);

// Check connection
if ($GLOBALS["conn"]->connect_error) {
  die("Connection failed: " . $GLOBALS["conn"]->connect_error);
}

?>
