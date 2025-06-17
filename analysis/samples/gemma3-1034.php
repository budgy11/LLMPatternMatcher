    Username: <input type="text" name="username" required>
    Password: <input type="password" name="password" required>
    <button type="submit">Login</button>
  </form>

</body>
</html>


<?php

// Database credentials (replace with your actual values)
$db_host = "localhost";
$db_name = "your_database_name";
$db_user = "your_username";
$db_password = "your_password";

// Check if the login form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

  // Get username and password from the form
  $username = $_POST["username"];
  $password = $_POST["password"];

  // Validate input (important for security - see explanation below)
  if (empty($username) || empty($password)) {
    $error_message = "Username and Password cannot be empty.";
  } else {
    // Database connection
    $conn = new mysqli($db_host, $db_user, $db_password, $db_name);

    // Check connection
    if ($conn->connect_error) {
      die("Connection failed: " . $conn->connect_error);
    }

    // Prepare and execute the query
    $sql = "SELECT id, username, password FROM users WHERE username = '$username'";  // Use prepared statements (see below)
    $result = $conn->query($sql);

    if ($result->num_rows == 0) {
      $error_message = "Invalid username or password.";
    } else {
      $user_data = $result->fetch_assoc();

      // Verify the password (very important - don't store passwords in plain text!)
      if (password_verify($password, $user_data["password"])) {
        // Login successful - store session data (example)
        session_start();
        $_SESSION["user_id"] = $user_data["id"];
        $_SESSION["username"] = $user_data["username"];
        echo "Login successful! <a href='welcome.php'>Welcome, " . $user_data["username"] . "</a>";
      } else {
        $error_message = "Invalid username or password.";
      }
    }
  }
}
?>
