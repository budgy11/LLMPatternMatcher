    Username: <input type="text" name="username" placeholder="your_email@example.com" required>
    <br><br>
    Password: <input type="password" name="password" required>
    <br><br>
    <button type="submit">Login</button>
  </form>

  <br>
  <a href="register.php">Don't have an account? Register here.</a>
</body>
</html>


<?php

// Database credentials (replace with your actual values)
$db_host = 'localhost';
$db_user = 'your_username';
$db_password = 'your_password';
$db_name = 'your_database_name';

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

  // Get username and password from the form
  $username = trim($_POST["username"]);
  $password = trim($_POST["password"]);

  // Validate input (important for security - see explanation below)
  if (empty($username) || empty($password)) {
    $error_message = "Username and Password cannot be empty.";
  } else {
    // Hash the password (BEST PRACTICE - DO NOT STORE PLAINTEXT PASSWORDS!)
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Query the database
    $pdo = new PDO("mysql:host=$db_host;dbname=$db_name", $db_user, $db_password);

    try {
      $stmt = $pdo->prepare("SELECT id, username, password FROM users WHERE username = ?");
      $stmt->execute([$username]);
      $user = $stmt->fetch(PDO::FETCH_ASSOC);

      if ($user) {
        // Password verification
        if (password_verify($password, $user["password"])) {
          // Login successful
          session_start();
          $_SESSION["user_id"] = $user["id"];
          $_SESSION["username"] = $username;
          header("Location: welcome.php"); // Redirect to a welcome page
          exit();
        } else {
          $error_message = "Incorrect password.";
        }
      } else {
        $error_message = "Username not found.";
      }
    } catch (PDOException $e) {
      $error_message = "Database error: " . $e->getMessage();
    }
  }
} else {
  // If the form hasn't been submitted, clear any previous errors
  $error_message = "";
}

?>
