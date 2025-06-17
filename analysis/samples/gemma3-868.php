
<!DOCTYPE html>
<html>
<head>
  <title>Login Form</title>
</head>
<body>

  <h1>Login</h1>

  <form method="post" action="">
    Username: <input type="text" name="username" required><br><br>
    Password: <input type="password" name="password" required><br><br>
    <input type="submit" value="Login">
  </form>

</body>
</html>


<?php

// Database credentials (Replace with your actual credentials)
$db_host = "localhost";
$db_name = "your_database_name";
$db_user = "your_username";
$db_password = "your_password";

// Check if the login form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Get username and password from the form
  $username = $_POST["username"];
  $password = $_POST["password"];

  // Validate input (Important for security!)
  if (empty($username) || empty($password)) {
    $error_message = "Username and password must be filled.";
  } else {
    // Hash the password (Strongly recommended!)
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Query the database
    $sql = "SELECT * FROM users WHERE username = '$username'";
    $result = mysqli_query($db_host, $sql);

    if (mysqli_num_rows($result) > 0) {
      $row = mysqli_fetch_assoc($result);

      // Verify the password
      if (password_verify($password, $row["password"])) {
        // Login successful!
        // Set session variables to maintain the user's login status
        session_start();
        $_SESSION["username"] = $username;
        $_SESSION["user_id"] = $row["id"]; // Or any other unique identifier
        
        // Redirect the user to a welcome page or the main page
        header("Location: welcome.php");
        exit();
      } else {
        $error_message = "Incorrect password.";
      }
    } else {
      $error_message = "Username not found.";
    }
  }
}
?>
