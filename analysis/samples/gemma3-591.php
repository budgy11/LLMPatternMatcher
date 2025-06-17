        Username: <input type="text" name="username">
        Password: <input type="password" name="password">
        <button type="submit">Login</button>
    </form>

    <p>Don't have an account? <a href="register.php">Register</a></p>

</body>
</html>


<?php

// Database credentials (replace with your actual credentials)
$db_host = "localhost";
$db_name = "your_database_name";
$db_user = "your_username";
$db_password = "your_password";

// Form handling (for simplicity, we'll assume POST method)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $username = $_POST["username"];
  $password = $_POST["password"];

  // Validate input (VERY IMPORTANT - prevent SQL injection and other issues)
  if (empty($username) || empty($password)) {
    $error_message = "Username and password cannot be empty.";
  } else {
    // SQL query (using prepared statements for security - highly recommended)
    $hashed_password = password_hash($password, PASSWORD_DEFAULT); // Hash the password
    $sql = "SELECT id, username, password FROM users WHERE username = ? AND password = ? ";
    $stmt = $conn->prepare($sql); // Prepare the statement
    $stmt->bind_param("ss", $username, $hashed_password); // Bind parameters
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
      // User found - set session variables
      $user = $result->fetch_assoc();
      $_SESSION["user_id"] = $user["id"];
      $_SESSION["username"] = $user["username"];
      $success_message = "Login successful!";

      // Redirect to a secure page (e.g., dashboard)
      header("Location: dashboard.php"); // Replace with your secure page
      exit(); // Important to stop further script execution
    } else {
      $error_message = "Invalid username or password.";
    }
  }
}

// Check if the user is logged in
if (!isset($_SESSION["user_id"])) {
  // User is not logged in - display the login form
  //  This is generally handled in a separate view or template file.
  //  Here's a basic example:
  echo "<!DOCTYPE html>
  <html>
  <head>
    <title>Login</title>
  </head>
  <body>
    <h1>Login</h1>
    <form method='post'>
      <label for='username'>Username:</label>
      <input type='text' id='username' name='username' required><br><br>
      <label for='password'>Password:</label>
      <input type='password' id='password' name='password' required><br><br>
      <input type='submit' value='Login'>
    </form>
    ";

    if(isset($error_message)){
      echo "<p style='color:red;'>".$error_message."</p>";
    }
    if(isset($success_message)){
      echo "<p style='color:green;'>".$success_message."</p>";
    }

}
?>
