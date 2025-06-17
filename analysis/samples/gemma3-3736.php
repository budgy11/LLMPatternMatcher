    <label for="username">Username:</label>
    <input type="text" id="username" name="username" placeholder="Enter username">

    <br><br>

    <label for="email">Email:</label>
    <input type="email" id="email" name="email" placeholder="Enter email">

    <br><br>

    <label for="password">Password:</label>
    <input type="password" id="password" name="password" placeholder="Enter password">

    <br><br>

    <button type="submit">Register</button>
  </form>

</body>
</html>


<?php

// Database Configuration (Replace with your actual database details)
$db_host = "localhost";
$db_name = "your_database_name";
$db_user = "your_database_user";
$db_pass = "your_database_password";

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Validate the form data (Important for security!)
  $username = trim($_POST["username"]);
  $email = trim($_POST["email"]);
  $password = trim($_POST["password"]);

  // Validation - Add more robust validation here!
  if (empty($username) || empty($email) || empty($password)) {
    $error_message = "All fields are required.";
  } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $error_message = "Invalid email address.";
  } elseif (strlen($password) < 8) {
    $error_message = "Password must be at least 8 characters long.";
  } else {
    // Prepare the SQL query to prevent SQL injection
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // SQL query to insert the new user
    $sql = "INSERT INTO users (username, email, password) VALUES ('$username', '$email', '$hashed_password')";

    if (mysqli_query($GLOBALS['conn'], $sql)) {
      $success_message = "Registration successful! Please verify your email.";
    } else {
      $error_message = "Error registering user. " . mysqli_error($GLOBALS['conn']);
    }
  }
}

// Connect to the database
$GLOBALS['conn'] = new mysqli($db_host, $db_user, $db_pass, $db_name);

// Check the connection
if ($GLOBALS['conn']->connect_error) {
  die("Connection failed: " . $GLOBALS['conn']->connect_error);
}

?>
