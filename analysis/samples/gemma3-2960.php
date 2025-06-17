
  <form action="register.php" method="post">
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

// Database connection details (replace with your actual values)
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

  // Validate form data - IMPORTANT SECURITY STEP
  if (empty($username) || empty($email) || empty($password)) {
    $error_message = "All fields are required.";
  } elseif (filter_var($email, FILTER_VALIDATE_EMAIL) === false) {
    $error_message = "Invalid email format.";
  } elseif (strlen($password) < 6) {
    $error_message = "Password must be at least 6 characters long.";
  } else {
    // Hash the password - IMPORTANT SECURITY STEP
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // SQL query to insert the new user
    $sql = "INSERT INTO users (username, email, password) VALUES ('$username', '$email', '$hashed_password')";

    if (mysqli_query($GLOBALS['conn'], $sql)) {
      $success_message = "Registration successful!  Please check your email to verify your account.";
    } else {
      $error_message = "Error inserting user. " . mysqli_error($GLOBALS['conn']);
    }
  }
}

// Database connection
$GLOBALS['conn'] = new mysqli($db_host, $db_user, $db_password, $db_name);

// Check connection
if ($GLOBALS['conn']->connect_error) {
  die("Connection failed: " . $GLOBALS['conn']->connect_error);
}

?>
