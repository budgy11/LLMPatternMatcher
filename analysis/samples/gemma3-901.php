    Username: <input type="text" name="username" required><br><br>
    Password: <input type="password" name="password" required><br><br>
    <input type="submit" value="Login">
  </form>

  <br>
  <a href="register.php">Don't have an account? Register here.</a>

</body>
</html>


// Example of hashing the password before storing it in the database
$password = $_POST["password"];
$hashed_password = password_hash($password, PASSWORD_DEFAULT);

// Now, store $hashed_password in your database instead of $password


<?php

// Database connection details (Replace with your actual credentials)
$db_host = "localhost";
$db_name = "your_database_name";
$db_user = "your_username";
$db_pass = "your_password";

// Form data handling
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $username = $_POST["username"];
  $password = $_POST["password"];

  // Validate input (Important for security)
  if (empty($username) || empty($password)) {
    $error_message = "Username and password cannot be empty.";
  } elseif (mysqli_query($conn, "SELECT * FROM users WHERE username = '$username'") === false) {
    $error_message = "Invalid username or password.";
  } else {
    // Check if password matches
    if (mysqli_query($conn, "SELECT * FROM users WHERE username = '$username' AND password = '$password'") === false) {
      $error_message = "Invalid username or password.";
    } else {
      // Successful login
      session_start();
      $_SESSION["username"] = $username;
      header("Location: welcome.php"); // Redirect to a welcome page
      exit();
    }
  }
}
?>
