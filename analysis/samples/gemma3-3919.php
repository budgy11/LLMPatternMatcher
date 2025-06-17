    Username: <input type="text" name="username"><br><br>
    Password: <input type="password" name="password"><br><br>
    Email: <input type="email" name="email"><br><br>
    <input type="submit" value="Register">
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
  // Validate the form data
  $username = trim($_POST["username"]);
  $email = trim($_POST["email"]);
  $password = trim($_POST["password"]);

  // Validate username
  if (empty($username)) {
    $username_error = "Username cannot be empty.";
  } elseif (strlen($username) < 3) {
    $username_error = "Username must be at least 3 characters long.";
  }

  // Validate email
  if (empty($email)) {
    $email_error = "Email cannot be empty.";
  } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $email_error = "Invalid email format.";
  }

  // Validate password
  if (empty($password)) {
    $password_error = "Password cannot be empty.";
  } elseif (strlen($password) < 8) {
    $password_error = "Password must be at least 8 characters long.";
  }

  // If validation passes, proceed with registration
  if (empty($username_error) && empty($email_error) && empty($password_error)) {
    // Hash the password (VERY IMPORTANT for security)
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // SQL query to insert the new user
    $sql = "INSERT INTO users (username, email, password) VALUES ('$username', '$email', '$hashed_password')";

    // Execute the query
    if (mysqli_query($GLOBALS['conn'], $sql)) {
      // Registration successful
      $registration_message = "Registration successful! Please check your email to verify your account.";
    } else {
      // Registration failed
      $registration_message = "Error registering user.  Check your database connection and try again.";
    }
  }
}

// Connect to the database
$GLOBALS['conn'] = new mysqli($db_host, $db_user, $db_pass, $db_name);

// Check connection
if ($GLOBALS['conn']->connect_error) {
  die("Connection failed: " . $GLOBALS['conn']->connect_error);
}

?>
