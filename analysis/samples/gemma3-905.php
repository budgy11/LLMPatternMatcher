    Username: <input type="text" name="username" placeholder="Username" required>
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
// ... (Database connection details)

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $username = $_POST["username"];
  $password = $_POST["password"];

  // Validate input
  if (empty($username) || empty($password)) {
    $error_message = "Username and password cannot be empty.";
  } else {
    // Hash the password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // SQL query
    $result = mysqli_query($conn, "SELECT * FROM users WHERE username = '$username' AND password = '$hashed_password'");

    if (mysqli_num_rows($result) > 0) {
      // Successful login
      session_start();
      $_SESSION["username"] = $username;
      header("Location: welcome.php");
      exit();
    } else {
      $error_message = "Invalid username or password.";
    }
  }
}
?>
