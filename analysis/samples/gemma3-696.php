

<!DOCTYPE html>
<html>
<head>
    <title>Login Form</title>
</head>
<body>

<h2>Login</h2>

<form method="post" action="">
    Username: <input type="text" name="username">
    Password: <input type="password" name="password">
    <button type="submit">Login</button>
</form>

</body>
</html>


<?php

// Database credentials (Replace with your actual credentials)
$servername = "localhost";
$username = "your_username";
$password = "your_password";
$dbname = "your_database_name";

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Get form data
  $username = $_POST["username"];
  $password = $_POST["password"];

  // Connect to the database
  $conn = new mysqli($servername, $username, $password, $dbname);

  // Check connection
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  // SQL query to check the username and password
  $sql = "SELECT id, username, password FROM users WHERE username = '$username'";
  $result = $conn->query($sql);

  if ($result->num_rows > 0) {
    // User found, verify the password
    $row = $result->fetch_assoc();
    if (password_verify($password, $row["password"])) {
      // Password matches, login successful
      // Set session variables to identify the user
      session_start();
      $_SESSION["user_id"] = $row["id"];
      $_SESSION["username"] = $username;

      // Redirect the user to a secure page (e.g., their dashboard)
      header("Location: dashboard.php"); // Replace dashboard.php with your login success page
      exit(); // Important to stop further execution
    } else {
      // Password does not match
      echo "<p>Incorrect password.</p>";
    }
  } else {
    // User not found
    echo "<p>User not found.</p>";
  }

  $conn->close();
}
?>
