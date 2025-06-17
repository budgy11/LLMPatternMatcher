    Username: <input type="text" name="username" required><br><br>
    Password: <input type="password" name="password" required><br><br>
    <input type="submit" value="Login">
  </form>

  <p>Don't have an account? <a href="register.php">Register</a></p> <!-- Link to registration page (you'll need to create this) -->

</body>
</html>


<?php

// Database configuration (replace with your actual credentials)
$db_host = "localhost";
$db_name = "your_database_name";
$db_user = "your_db_user";
$db_password = "your_db_password";

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

  // Get username and password from the form
  $username = $_POST["username"];
  $password = $_POST["password"];

  // Validate username and password (important for security!)
  if (empty($username) || empty($password)) {
    $error_message = "Username and Password cannot be empty.";
  } else {
    // Database connection
    $conn = new mysqli($db_host, $db_user, $db_password, $db_name);

    // Check connection
    if ($conn->connect_error) {
      die("Connection failed: " . $conn->connect_error);
    }

    // SQL query to authenticate the user
    $sql = "SELECT * FROM users WHERE username = '$username' AND password = '$password'";
    $result = $conn->query($sql);

    if ($result->num_rows == 0) {
      $error_message = "Invalid username or password.";
    } else {
      // Authentication successful!
      // Set a session variable to store the user's ID
      session_start();
      $_SESSION["user_id"] = $result->fetch_assoc()["id"]; // Assuming 'id' is the user ID column
      $error_message = "Login successful.";

      // Redirect to a protected page
      header("Location: protected_page.php"); // Replace with your protected page URL
      exit();
    }
  }
}
?>
