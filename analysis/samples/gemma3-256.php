
<!DOCTYPE html>
<html>
<head>
  <title>Login Form</title>
</head>
<body>

  <h2>Login</h2>

  <form method="post" action="">
    <label for="username">Username:</label>
    <input type="text" id="username" name="username">
    <br><br>

    <label for="password">Password:</label>
    <input type="password" id="password" name="password">
    <br><br>

    <button type="submit">Login</button>
  </form>

</body>
</html>


<?php

// Database connection details - Replace with your actual details
$db_host = "localhost";
$db_name = "your_database_name";
$db_user = "your_database_user";
$db_password = "your_database_password";

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

  // Get the username and password from the form
  $username = $_POST["username"];
  $password = $_POST["password"];

  // Sanitize the inputs (Important for security)
  $username = filter_var($username, FILTER_SANITIZE_STRING);
  $password = filter_var($password, FILTER_SANITIZE_STRING);

  // Validate the inputs
  if (empty($username) || empty($password)) {
    $error = "Username and Password cannot be empty.";
  } else {
    // Database query
    $conn = new mysqli($db_host, $db_user, $db_password, $db_name);

    // Check connection
    if ($conn->connect_error) {
      die("Connection failed: " . $conn->connect_error);
    }

    // Prepare the SQL query
    $sql = "SELECT id, username, password FROM users WHERE username = ? AND password = ? ";

    // Bind the parameters (Important for security - prevents SQL injection)
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $username, $password); // "ss" indicates two strings

    // Execute the query
    if ($stmt->execute() === TRUE) {
      // Get the result
      $result = $stmt->get_result();

      if ($result->num_rows == 1) {
        // User found
        $row = $result->fetch_assoc();
        // Set session variables
        $_SESSION["user_id"] = $row["id"];
        $_SESSION["username"] = $row["username"];

        // Redirect to a welcome page or the main page
        header("Location: welcome.php"); // Replace with your welcome page
        exit();
      } else {
        $error = "Invalid username or password.";
      }
    } else {
      $error = "Error executing query.";
    }
  }
}
?>
