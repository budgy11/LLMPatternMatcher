
<!DOCTYPE html>
<html>
<head>
    <title>Login Form</title>
</head>
<body>

<h2>Login</h2>

<form method="post" action="">
    Username: <input type="text" name="username" required><br>
    Password: <input type="password" name="password" required><br>
    <input type="submit" value="Login">
</form>

</body>
</html>


<?php

// Database configuration
$db_host = "localhost";
$db_name = "your_database_name";
$db_user = "your_db_username";
$db_password = "your_db_password";

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Get username and password from the form
  $username = trim($_POST["username"]);
  $password = trim($_POST["password"]);

  // Validate input (important for security)
  if (empty($username) || empty($password)) {
    $error_message = "Username and password cannot be empty.";
  } elseif (!filter_var($username, FILTER_VALIDATE_EMAIL) && !filter_var($username, FILTER_VALIDATE_REGULAR)) {
    $error_message = "Invalid username.  Please enter a valid email or username.";
  } else {
    // Hash the password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Query the database
    $conn = new mysqli($db_host, $db_user, $db_password, $db_name);

    if ($conn->connect_error) {
      die("Connection failed: " . $conn->connect_error);
    }

    // SQL query to select the user
    $sql = "SELECT id, username, email FROM users WHERE username = '$username' OR email = '$username'";  //Using '$username' and '$username' to handle both email and username
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
      // User found, check the password
      $user_data = $result->fetch_assoc();
      if (password_verify($password, $user_data["password"])) {
        // Password matches, login successful
        // Session management (for security - important!)
        session_start();
        $_SESSION["user_id"] = $user_data["id"];
        $_SESSION["username"] = $user_data["username"];
        $_SESSION["email"] = $user_data["email"];

        // Redirect to a secure page
        header("Location: secure_page.php"); // Replace 'secure_page.php' with your desired secure page
        exit();
      } else {
        $error_message = "Incorrect password.";
      }
    } else {
      $error_message = "User not found.";
    }
  }
}
?>
