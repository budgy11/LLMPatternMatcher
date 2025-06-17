    Username: <input type="text" name="username" required><br><br>
    Password: <input type="password" name="password" required><br><br>
    <input type="submit" value="Login">
  </form>

</body>
</html>


<?php

// Database configuration (Replace with your actual credentials)
$db_host = "localhost";
$db_name = "your_database_name";
$db_user = "your_username";
$db_pass = "your_password";

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

  // Get username and password from the form
  $username = $_POST["username"];
  $password = $_POST["password"];

  // Validate the input (Important for security!)
  if (empty($username) || empty($password)) {
    $error_message = "Username and password cannot be empty.";
  } else {
    // Database connection
    $conn = new mysqli($db_host, $db_user, $db_pass, $db_name);

    // Check connection
    if ($conn->connect_error) {
      $error_message = "Connection failed: " . $conn->connect_error;
    } else {
      // SQL query to check username and password
      $sql = "SELECT * FROM users WHERE username = '$username' AND password = '$password'";
      $result = $conn->query($sql);

      if ($result->num_rows > 0) {
        // User found, set session variables
        session_start();  // Important: Start the session
        $_SESSION["username"] = $username;
        $error_message = "Login successful!";

        // Redirect to a protected page
        header("Location: protected_page.php");  // Replace with your desired page
        exit();
      } else {
        $error_message = "Invalid username or password.";
      }
    }
  }
}
?>
