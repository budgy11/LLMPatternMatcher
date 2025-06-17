
<!DOCTYPE html>
<html>
<head>
  <title>User Login</title>
</head>
<body>

  <h1>User Login</h1>

  <form method="post" action="">
    <label for="username">Username:</label>
    <input type="text" id="username" name="username" required>

    <label for="password">Password:</label>
    <input type="password" id="password" name="password" required>

    <button type="submit">Login</button>
  </form>

</body>
</html>


<?php

// Database credentials (replace with your actual details)
$host = "localhost";
$username = "your_username";
$password = "your_password";
$database = "your_database";

// Login form handling
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $username = $_POST["username"];
  $password = $_POST["password"];

  // Validate input (important for security!)
  if (empty($username) || empty($password)) {
    $error = "Username and password cannot be empty.";
  } else {
    // Query the database to check credentials
    $conn = new mysqli($host, $username, $password, $database);

    if ($conn->connect_error) {
      die("Connection failed: " . $conn->connect_error);
    }

    // Prepare the SQL query (using prepared statements - BEST PRACTICE)
    $sql = "SELECT * FROM users WHERE username = ? AND password = ?";
    $stmt = $conn->prepare($sql);

    if ($stmt) {
      // Bind the parameters
      $stmt->bind_param("ss", $username, $password);

      // Execute the query
      if ($stmt->execute()) {
        // Get the result
        $result = $stmt->get_result();

        if ($result->num_rows == 1) {
          // User found - set session variables
          session_start(); // Start session if it doesn't exist
          $_SESSION["username"] = $username;
          // You can also store other user data here (e.g., user ID, role)
          echo "Login successful! You are now logged in as " . $username;
          // Redirect to a protected page or the homepage
          header("Location: /protected_page.php"); // Replace with your desired redirect URL
          exit();
        } else {
          $error = "Incorrect username or password.";
        }
      } else {
        $error = "Query error";
      }
    } else {
      $error = "Query preparation error";
    }
  }
}
?>
