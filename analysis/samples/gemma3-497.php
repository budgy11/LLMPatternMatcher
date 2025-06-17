
<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
</head>
<body>

<h2>Login</h2>

<form method="post" action="">
    Username: <input type="text" name="username">
    Password: <input type="password" name="password">
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
  // Get form data
  $username = $_POST["username"];
  $password = $_POST["password"];

  // Validate input (Important for security!)
  if (empty($username) || empty($password)) {
    $error = "Username and password cannot be empty.";
  } else {
    // Hash the password (Store only the hash, not the plain password!)
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Query the database
    $conn = new mysqli($db_host, $db_user, $db_pass, $db_name);

    if ($conn->connect_error) {
      die("Connection failed: " . $conn->connect_error);
    }

    // SQL query
    $sql = "SELECT id, username, hashed_password FROM users WHERE username = '$username' AND hashed_password = '$hashed_password'";
    $result = $conn->query($sql);

    if ($result->num_rows == 1) {
      // User found, set session variables
      $row = $result->fetch_assoc();
      $_SESSION["user_id"] = $row["id"];
      $_SESSION["username"] = $row["username"];
      echo "Login successful!";
      // Redirect to a protected page or display a welcome message
      header("Location: protected_page.php"); // Example: Redirect to a protected page
      exit();
    } else {
      $error = "Invalid username or password.";
    }
  }
}
?>
