
<!DOCTYPE html>
<html>
<head>
  <title>User Login</title>
</head>
<body>

  <h2>User Login</h2>

  <form method="post" action="">
    Username: <input type="text" name="username" placeholder="Your Username"><br><br>
    Password: <input type="password" name="password" placeholder="Your Password"><br><br>
    <input type="submit" value="Login">
  </form>

</body>
</html>


<?php

// Database connection details
$host = "localhost";
$username = "your_username";
$password = "your_password";
$database = "your_database";

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Get the username and password from the form
  $username = $_POST["username"];
  $password = $_POST["password"];

  // Validate the input (important for security!)
  if (empty($username) || empty($password)) {
    $error = "Username and Password must be filled.";
  } else {
    // Database query
    $conn = new mysqli($host, $username, $password, $database);

    if ($conn->connect_error) {
      die("Connection failed: " . $conn->connect_error);
    }

    // Prepare the SQL query (using prepared statements - very important!)
    $sql = "SELECT id, username, password FROM users WHERE username = ? AND password = ?";
    $stmt = $conn->prepare($sql);

    if ($stmt) {
      // Bind the parameters
      $stmt->bind_param("ss", $username, $password);

      // Execute the query
      $stmt->execute();

      // Get the result
      $result = $stmt->get_result();

      // Check if a user was found
      if ($result->num_rows == 1) {
        // User found, set session variables
        session_start();
        $user_data = $result->fetch_assoc();
        $_SESSION["user_id"] = $user_data["id"];
        $_SESSION["username"] = $user_data["username"];

        // Redirect the user
        header("Location: welcome.php"); // Assuming a welcome page
        exit();

      } else {
        $error = "Invalid username or password.";
      }
    } else {
      $error = "Error preparing statement.";
    }
  }
}
?>
