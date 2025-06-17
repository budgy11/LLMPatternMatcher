
<!DOCTYPE html>
<html>
<head>
  <title>Login</title>
</head>
<body>

  <h2>Login</h2>

  <form method="post" action="login.php">
    Username: <input type="text" name="username" required>
    Password: <input type="password" name="password" required>
    <button type="submit">Login</button>
  </form>

</body>
</html>


<?php

// Database Credentials (Replace with your actual credentials)
$db_host = "localhost";
$db_name = "your_database_name";
$db_user = "your_username";
$db_password = "your_password";

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Get the username and password from the form
  $username = $_POST["username"];
  $password = $_POST["password"];

  // Validate input (Important for security!)
  if (empty($username) || empty($password)) {
    $error_message = "Username and password must be filled.";
  } else {
    // Hash the password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Query the database to check the credentials
    $stmt = $conn->prepare("SELECT id, username, password FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
      $user = $result->fetch_assoc();
      if (password_verify($password, $user["password"])) {
        // Password matches!  Set session variables.
        $_SESSION["user_id"] = $user["id"];
        $_SESSION["username"] = $user["username"];
        echo "Login successful!  Redirecting...";
        header("Location: welcome.php"); // Redirect to a welcome page
        exit; // Stop further execution
      } else {
        $error_message = "Incorrect password.";
      }
    } else {
      $error_message = "Incorrect username.";
    }
  }
}
?>
