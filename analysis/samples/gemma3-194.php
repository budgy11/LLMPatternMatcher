
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
$servername = "localhost";
$username = "your_username";
$password = "your_password";
$dbname = "your_database_name";

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Get the username and password from the form
  $username = $_POST["username"];
  $password = $_POST["password"];

  // Connect to the database
  $conn = new mysqli($servername, $username, $password, $dbname);

  // Check connection
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  // Prepare and execute the query
  $sql = "SELECT * FROM users WHERE username = ? AND password = ?";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param("ss", $username, $password); // "ss" means two strings
  $stmt->execute();

  // Get the result
  $result = $stmt->get_result();

  // Check if there's a row in the result
  if ($result->num_rows > 0) {
    // User found, set session variables
    session_start();
    $_SESSION["username"] = $username;
    $_SESSION["user_id"] = $result->fetch_assoc()["user_id"]; // Assuming you have a user_id column
    // Redirect to a secure page or display a success message
    header("Location: /protected_page.php"); // Replace with your secure page URL
    exit();
  } else {
    // User not found
    echo "Invalid username or password.";
  }

  // Close the statement and connection
  $stmt->close();
  $conn->close();
}
?>
