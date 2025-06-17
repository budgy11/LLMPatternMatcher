    Username: <input type="text" name="username" required>
    Password: <input type="password" name="password" required>
    <button type="submit">Login</button>
  </form>

  <p>Don't have an account? <a href="register.php">Register</a></p>  <!-- Replace with your registration page -->

</body>
</html>


<?php

// Database Credentials (Replace with your actual values!)
$db_host = "localhost";
$db_name = "your_database_name";
$db_user = "your_username";
$db_password = "your_password";

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Get username and password from the form
  $username = $_POST["username"];
  $password = $_POST["password"];

  // Validate input (basic example, enhance for production)
  if (empty($username) || empty($password)) {
    $error_message = "Username and password cannot be empty.";
  } else {
    // Query the database (using prepared statements for security)
    $pdo = new PDO("mysql:host=$db_host;dbname=$db_name", $db_user, $db_password);
    $stmt = $pdo->prepare("SELECT id, username, password FROM users WHERE username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    // Check if the user exists and password matches
    if ($user) {
      // Verify the password (using password_hash for secure hashing in a real application!)
      if (password_verify($password, $user["password"])) {
        // Successful login
        session_start();
        $_SESSION["user_id"] = $user["id"];
        $_SESSION["username"] = $username;
        header("Location: welcome.php"); // Redirect to a welcome page
        exit();
      } else {
        $error_message = "Incorrect password.";
      }
    } else {
      $error_message = "Invalid username.";
    }
  }
}

// Display the login form
?>
