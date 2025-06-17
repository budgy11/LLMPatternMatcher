
<!DOCTYPE html>
<html>
<head>
    <title>User Login</title>
</head>
<body>

    <h1>User Login</h1>

    <form method="post" action="">
        <label for="username">Username:</label>
        <input type="text" id="username" name="username">

        <label for="password">Password:</label>
        <input type="password" id="password" name="password">

        <button type="submit">Login</button>
    </form>

</body>
</html>


<?php

// Database credentials (replace with your actual credentials)
$host = "localhost";
$username = "your_username";
$password = "your_password";
$database = "your_database";

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Get username and password from the form
  $username = $_POST["username"];
  $password = $_POST["password"];

  // Validate input (IMPORTANT - Add more robust validation)
  if (empty($username) || empty($password)) {
    $error = "Username and password cannot be empty.";
  } elseif (!filter_var($username, FILTER_VALIDATE_EMAIL)) {
    $error = "Invalid username (must be a valid email address).";
  } else {
    // Connect to the database
    $conn = new mysqli($host, $username, $password, $database);

    // Check connection
    if ($conn->connect_error) {
      die("Connection failed: " . $conn->connect_error);
    }

    // Prepare and execute the query
    $sql = "SELECT * FROM users WHERE email = '$username'"; // Use email for more secure authentication
    $result = $conn->query($sql);

    // Check if a user exists
    if ($result->num_rows > 0) {
      // Fetch the user data
      $user = $result->fetch_assoc();

      // Verify password
      if (password_verify($password, $user["password"])) {
        // Login successful - Set session variables
        session_start();
        $_SESSION["user_id"] = $user["id"];
        $_SESSION["username"] = $user["email"]; // Use email for session
        header("Location: welcome.php"); // Redirect to a welcome page
        exit();
      } else {
        $error = "Incorrect password.";
      }
    } else {
      $error = "User not found.";
    }
  }
}
?>
