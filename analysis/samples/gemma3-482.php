
  <form action="login.php" method="post">
    Username: <input type="text" name="username">
    Password: <input type="password" name="password">
    <button type="submit">Login</button>
  </form>

  <p>Don't have an account? <a href="register.php">Register</a></p>

</body>
</html>


<?php

// Database connection details (replace with your actual credentials)
$db_host = "localhost";
$db_user = "your_username";
$db_password = "your_password";
$db_name = "your_database";

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Get the username and password from the form
  $username = $_POST["username"];
  $password = $_POST["password"];

  // Validate the input (Important for security!)
  if (empty($username) || empty($password)) {
    $error_message = "Username and Password cannot be empty.";
  } else {
    // Hash the password before comparing (VERY IMPORTANT!)
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Query the database to check the credentials
    $sql = "SELECT id, username, password FROM users WHERE username = '$username'";
    $result = mysqli_query($db_connection, $sql);

    if (mysqli_num_rows($result) > 0) {
      $row = mysqli_fetch_assoc($result);
      if (password_verify($password, $row["password"])) {
        // Password matches, login successful
        session_start(); // Start a session to store user information
        $_SESSION["user_id"] = $row["id"];
        $_SESSION["username"] = $row["username"];
        
        // Redirect the user to a protected page
        header("Location: protected_page.php"); // Replace with your protected page URL
        exit(); // Stop further script execution
      } else {
        $error_message = "Incorrect password.";
      }
    } else {
      $error_message = "Incorrect username.";
    }
  }
}

// Display the login form
?>
