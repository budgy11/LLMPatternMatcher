    Username: <input type="text" name="username" />
    Password: <input type="password" name="password" />
    <button type="submit">Login</button>
  </form>

  <p>Don't have an account? <a href="register.php">Register</a></p>

</body>
</html>


<?php

// Database credentials (replace with your actual values)
$db_host = "localhost";
$db_name = "your_database_name";
$db_user = "your_db_user";
$db_pass = "your_db_password";

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Get username and password from the form
  $username = $_POST["username"];
  $password = $_POST["password"];

  // Validate the input (important for security)
  if (empty($username) || empty($password)) {
    $error = "Username and password cannot be empty.";
  } else {
    // Hash the password (store securely)
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Query the database to check the credentials
    $sql = "SELECT id, username, password FROM users WHERE username = '$username'";
    $result = mysqli_query($GLOBALS["db_host"], $sql);

    if (mysqli_num_rows($result) > 0) {
      $row = mysqli_fetch_assoc($result);

      // Verify the password
      if (password_verify($password, $row["password"])) {
        // Login successful!
        // Set a session variable to store the user's ID
        session_start();
        $_SESSION["user_id"] = $row["id"];
        $_SESSION["username"] = $row["username"]; // Optional: Store username for convenience
        // Redirect the user to a secure page (e.g., dashboard)
        header("Location: dashboard.php"); // Replace dashboard.php with your desired URL
        exit(); // Stop further execution of the script
      } else {
        $error = "Incorrect password.";
      }
    } else {
      $error = "Invalid username.";
    }
  }
}
?>
