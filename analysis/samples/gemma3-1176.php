    Username: <input type="text" name="username" placeholder="Your Username"><br><br>
    Password: <input type="password" name="password" placeholder="Your Password"><br><br>
    <button type="submit">Login</button>
</form>

</body>
</html>


<?php

// Database Credentials (Replace with your actual credentials!)
$db_host = "localhost";
$db_name = "your_database_name";
$db_user = "your_db_user";
$db_pass = "your_db_password";

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Get username and password from the form
  $username = $_POST["username"];
  $password = $_POST["password"];

  // Validate input (Important for security!)
  if (empty($username) || empty($password)) {
    $error_message = "Username and password cannot be empty.";
  } elseif (mysqli_stmt_prepare($conn, "SELECT username, password FROM users WHERE username = ?")) {
    if (mysqli_stmt_bind_param($conn, "s", $username)) {
      mysqli_stmt_execute($conn);
      $result = mysqli_stmt_get_result($conn);
      if ($row = mysqli_fetch_assoc($result)) {
        if (password_verify($password, $row["password"])) {
          // Password matches!  Set session variables.
          session_start(); // Start the session if it's not already
          $_SESSION["username"] = $username;
          $error_message = "";  // Clear any previous errors
          header("Location: welcome.php"); // Redirect to a welcome page
          exit(); // Stop further execution
        } else {
          $error_message = "Incorrect password.";
        }
        mysqli_stmt_close($conn); // Close the statement
      } else {
        $error_message = "Incorrect username.";
      }
      mysqli_stmt_close($conn);
    } else {
        $error_message = "Error preparing statement.";
    }
  } else {
    $error_message = "Error preparing statement.";
  }
}
?>
