    Username: <input type="text" name="username" required>
    <br><br>
    Password: <input type="password" name="password" required>
    <br><br>
    <button type="submit">Login</button>
</form>

</body>
</html>


<?php

// Database configuration (replace with your actual details)
$db_host = "localhost";
$db_name = "your_database_name";
$db_user = "your_username";
$db_password = "your_password";

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Get the login credentials
  $username = $_POST["username"];
  $password = $_POST["password"];

  // Validate the input (basic example, improve for production)
  if (empty($username) || empty($password)) {
    $error = "Username and password cannot be empty.";
  } else {
    // Query the database to check for the credentials
    $sql = "SELECT * FROM users WHERE username = '$username' AND password = '$password'";
    $result = mysqli_query($db_host, $sql);

    if (mysqli_num_rows($result) > 0) {
      // User found, set session variables for authentication
      session_start();
      $_SESSION["username"] = $username;  // Store the username
      // You might want to store other user information here, like user ID, etc.
      // $_SESSION["user_id"] = $user_id;

      // Redirect the user to a secure page (e.g., dashboard)
      header("Location: dashboard.php"); // Replace with your secure page
      exit(); // Important: Stop further script execution
    } else {
      $error = "Invalid username or password.";
    }
  }
}

// If the form hasn't been submitted, display the login form
?>
