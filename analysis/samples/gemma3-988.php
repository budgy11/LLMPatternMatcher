    Username: <input type="text" name="username" required>
    Password: <input type="password" name="password" required>
    <button type="submit">Login</button>
  </form>

  <p>Don't have an account? <a href="register.php">Register</a></p>

</body>
</html>


<?php

// Database configuration (replace with your actual values)
$db_host = "localhost";
$db_name = "your_database_name";
$db_user = "your_database_username";
$db_password = "your_database_password";

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Get username and password from the form
  $username = trim($_POST["username"]);
  $password = trim($_POST["password"]);

  // Validate input (Important for security!)
  if (empty($username) || empty($password)) {
    $error_message = "Username and password cannot be empty.";
  } elseif (mysqli_num_rows(mysqli_query(mysqli_connect($db_host, $db_user, $db_password), "SELECT * FROM users WHERE username = '$username'")) > 0) {
    // User exists, check the password
    $stmt = mysqli_prepare(mysqli_connect($db_host, $db_user, $db_password), "SELECT password FROM users WHERE username = ?");
    mysqli_stmt_bind_param($stmt, "s", $username);  // 's' indicates a string
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $row = mysqli_fetch_assoc($result);

    if (password_verify($password, $row["password"])) {
      // Password matches, login successful
      session_start(); // Start a session to store user data
      $_SESSION["username"] = $username;
      $error_message = ""; // Clear any previous errors
      header("Location: welcome.php"); // Redirect to a welcome page
      exit();  // Important to stop further script execution
    } else {
      // Password does not match
      $error_message = "Incorrect password.";
    }
  } else {
    // User does not exist
    $error_message = "Incorrect username.";
  }
}
?>
