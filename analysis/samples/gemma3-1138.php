    Username: <input type="text" name="username" required>
    Password: <input type="password" name="password" required>
    <button type="submit">Login</button>
  </form>

</body>
</html>


<?php

// Database configuration (Replace with your actual database details)
$db_host = "localhost";
$db_name = "your_database_name";
$db_user = "your_database_username";
$db_password = "your_database_password";

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Get the username and password from the form
  $username = $_POST["username"];
  $password = $_POST["password"];

  // Validate input (Important for security!)
  if (empty($username) || empty($password)) {
    $error_message = "Username and password cannot be empty.";
  } else {
    // Hash the password for security (using password_hash)
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Query the database to check credentials
    $sql = "SELECT id, username, password FROM users WHERE username = '$username'";
    $result = mysqli_query($db_host, $sql);

    if (mysqli_num_rows($result) > 0) {
      $row = mysqli_fetch_assoc($result);
      // Verify the password
      if (password_verify($password, $row["password"])) {
        // If passwords match, set session variables
        session_start();
        $_SESSION["user_id"] = $row["id"];
        $_SESSION["username"] = $username;
        echo "Login successful! <a href='welcome.php'>Welcome, " . $username . "</a>"; // Redirect to a welcome page
      } else {
        $error_message = "Incorrect password.";
      }
    } else {
      $error_message = "Username not found.";
    }
  }
}
?>
