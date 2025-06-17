    Username or Email: <input type="text" name="username" required>
    <br><br>
    Password: <input type="password" name="password" required>
    <br><br>
    <button type="submit">Login</button>
  </form>

  <br><br>
  <a href="register.php">Don't have an account? Register here.</a>

</body>
</html>


<?php

// Database configuration (Replace with your actual credentials)
$db_host = 'localhost';
$db_name = 'your_database_name';
$db_user = 'your_db_user';
$db_password = 'your_db_password';

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Get user input
  $username = trim($_POST["username"]);
  $password = trim($_POST["password"]);

  // Validate input (IMPORTANT - This is a basic example, enhance security!)
  if (empty($username) || empty($password)) {
    $error_message = "Username and Password cannot be empty.";
  } elseif (mysqli_num_rows(mysqli_query(mysqli_connect($db_host, $db_user, $db_password), "SELECT * FROM users WHERE username = '$username'") ) > 0) {
    $error_message = "Invalid username or password.";
  }

  // Hash password (VERY IMPORTANT for security - never store plain text passwords)
  $hashed_password = password_hash($password, PASSWORD_DEFAULT);

  // Attempt to authenticate
  // This example shows how to compare with a hashed password stored in the database.
  //  In a production environment, your code should compare the hashed password
  //  against the one stored in the database.

  // Check if the user exists and password matches
  if (mysqli_num_rows(mysqli_query(mysqli_connect($db_host, $db_user, $db_password), "SELECT * FROM users WHERE username = '$username' AND password = '$hashed_password'") ) > 0) {
    // User authentication successful!
    session_start();
    $_SESSION["username"] = $username; // Store username in session
    // Redirect to a secure page after successful login
    header("Location: secure_page.php"); // Replace with your secure page URL
    exit(); // Stop further execution
  } else {
    $error_message = "Invalid username or password.";
  }
}
?>
