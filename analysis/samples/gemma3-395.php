    Username: <input type="text" name="username" required><br><br>
    Password: <input type="password" name="password" required><br><br>
    <input type="submit" value="Login">
  </form>

  <p>Don't have an account? <a href="register.php">Register</a></p>

</body>
</html>


<?php

// ... (Previous code) ...

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Get username and password from the form
  $username = $_POST["username"];
  $password = $_POST["password"];

  // Validate input
  if (empty($username) || empty($password)) {
    $error_message = "Username and password cannot be empty.";
  } elseif (strlen($username) < 3) {
    $error_message = "Username must be at least 3 characters long.";
  }

  // Hash the password
  $hashed_password = password_hash($password, PASSWORD_DEFAULT);

  // Simulate a login check (replace with actual database query)
  if ($username == "admin" && $hashed_password == password_verify("password", $password)) {
    // Login successful
    session_start();
    $_SESSION["username"] = $username;
    header("Location: /welcome.php");
    exit();
  } else {
    $error_message = "Invalid username or password.";
  }
}
?>
