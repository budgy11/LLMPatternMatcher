
<!DOCTYPE html>
<html>
<head>
  <title>Login</title>
</head>
<body>

  <h2>Login</h2>

  <form method="post" action="">
    Username: <input type="text" name="username" placeholder="Enter your username" required>
    Password: <input type="password" name="password" placeholder="Enter your password" required>
    <input type="submit" value="Login">
  </form>

  <p>Don't have an account? <a href="register.php">Register</a></p> <!-- Example register link -->

</body>
</html>


<?php

// Database configuration (replace with your actual details)
$db_host = "localhost";
$db_name = "your_database_name";
$db_user = "your_database_user";
$db_password = "your_database_password";

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Get username and password from the form
  $username = $_POST["username"];
  $password = $_POST["password"];

  // Validate input (important for security!)
  if (empty($username) || empty($password)) {
    $error = "Username and password cannot be empty.";
  } elseif (!filter_var($username, FILTER_SANITIZE_STRING)) {
    $error = "Invalid username.  Only letters and numbers are allowed.";
  } elseif (!filter_var($password, FILTER_SANITIZE_STRING)) {
    $error = "Invalid password.  Only letters and numbers are allowed.";
  } else {
    // Hash the password for security
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Connect to the database
    $conn = new mysqli($db_host, $db_user, $db_password, $db_name);

    // Check the connection
    if ($conn->connect_error) {
      $error = "Connection failed: " . $conn->connect_error;
    } else {
      // Prepare and execute the query
      $sql = "SELECT * FROM users WHERE username = ? AND password = ? ";
      $stmt = $conn->prepare($sql);
      $stmt->bind_param("ss", $username, $hashed_password);  // Use "ss" for string comparison
      $stmt->execute();
      $result = $stmt->get_result();

      if ($result->num_rows == 1) {
        // User found, set session variables
        session_start();
        $_SESSION["username"] = $username;
        $stmt->close();
        $conn->close();

        // Redirect to a secure page
        header("Location: secure_page.php"); // Replace with your secure page
        exit();

      } else {
        $error = "Invalid username or password.";
        $stmt->close();
        $conn->close();
      }
    }
  }
}
?>
