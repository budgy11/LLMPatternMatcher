    Username: <input type="text" name="username">
    Password: <input type="password" name="password">
    <button type="submit">Login</button>
  </form>

</body>
</html>


<?php

// Database configuration (replace with your actual credentials)
$db_host = "localhost";
$db_user = "your_username";
$db_password = "your_password";
$db_name = "your_database_name";

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Get username and password from the form
  $username = $_POST["username"];
  $password = $_POST["password"];

  // Validate input (Important for security!)
  if (empty($username) || empty($password)) {
    $error_message = "Username and password cannot be empty.";
  } else {
    // Database connection
    $conn = new mysqli($db_host, $db_user, $db_password, $db_name);

    // Check connection
    if ($conn->connect_error) {
      $error_message = "Connection failed: " . $conn->connect_error;
    } else {
      // SQL query to authenticate the user
      $sql = "SELECT * FROM users WHERE username = '$username' AND password = '$password'";
      $result = $conn->query($sql);

      if ($result->num_rows == 1) {
        // User found - set session variables to keep user logged in
        $row = $result->fetch_assoc();
        $_SESSION["user_id"] = $row["id"]; // Assuming 'id' is the user ID column
        $_SESSION["username"] = $username;

        // Redirect to a protected page
        header("Location: protected_page.php"); // Replace with your protected page
        exit(); // Important to prevent further execution
      } else {
        $error_message = "Invalid username or password.";
      }
    }
  }
}
?>
