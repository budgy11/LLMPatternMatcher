
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

// Database credentials (replace with your actual values)
$db_host = "localhost";
$db_name = "your_database_name";
$db_user = "your_db_user";
$db_password = "your_db_password";

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Get username and password from the form
  $username = $_POST["username"];
  $password = $_POST["password"];

  // Validate the input (important for security)
  if (empty($username) || empty($password)) {
    $error = "Username and Password cannot be empty.";
  } elseif (!filter_var($username, FILTER_VALIDATE_EMAIL)) {
    $error = "Invalid email address.";
  } else {
    // Database connection
    $conn = new mysqli($db_host, $db_user, $db_password, $db_name);

    // Check connection
    if ($conn->connect_error) {
      die("Connection failed: " . $conn->connect_error);
    }

    // Prepare the SQL query
    $sql = "SELECT id, username, password FROM users WHERE username = '$username'";
    $result = $conn->query($sql);

    // Check if the query returned any results
    if ($result->num_rows > 0) {
      // Get the data from the result
      $user_data = $result->fetch_assoc();

      // Verify the password
      if (password_verify($password, $user_data["password"])) {
        // Successful login - set session variables
        session_start();
        $_SESSION["user_id"] = $user_data["id"];
        $_SESSION["username"] = $username;
        echo "Login successful! <a href='welcome.php'>Welcome, " . $username . "</a>"; // Redirect to a welcome page
      } else {
        $error = "Incorrect password.";
      }
    } else {
      $error = "User not found.";
    }
  }
}
?>
