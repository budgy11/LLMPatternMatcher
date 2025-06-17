
<!DOCTYPE html>
<html>
<head>
  <title>Login</title>
</head>
<body>

  <h1>Login</h1>

  <form method="post" action="">
    <label for="username">Username:</label>
    <input type="text" id="username" name="username" required><br><br>

    <label for="password">Password:</label>
    <input type="password" id="password" name="password" required><br><br>

    <button type="submit">Login</button>
  </form>

</body>
</html>


<?php

// Database connection details (Replace with your actual credentials)
$host = "localhost";
$username = "your_username";
$password = "your_password";
$database = "your_database";

// Function to handle user login
function loginUser($username, $password) {
  // Connect to the database
  $conn = new mysqli($host, $username, $password, $database);

  // Check connection
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  // SQL query to check login
  $stmt = $conn->prepare("SELECT id, username, password FROM users WHERE username = ?");

  // Bind parameters securely
  $stmt->bind_param("s", $username);

  // Execute the query
  $stmt->execute();

  // Get the result
  $result = $stmt->get_result();

  // Check if the user exists
  if ($result->num_rows == 0) {
    return false; // User doesn't exist
  }

  // Fetch the user data
  $user = $result->fetch_assoc();

  // Verify the password (Important: Use password_hash for secure password storage)
  if (password_verify($password, $user['password'])) {
    // Password is correct, set session variables
    $_SESSION['user_id'] = $user['id'];
    $_SESSION['username'] = $user['username'];
    return true; // Login successful
  } else {
    return false; // Incorrect password
  }

  // Close the statement and connection
  $stmt->close();
  $conn->close();
}

// Example Usage (in your login form's submission handler)

// Assuming the username and password are submitted from the form
// Get username and password from the form
// $username = $_POST['username'];
// $password = $_POST['password'];

// Check if the login is successful
// if (loginUser($username, $password)) {
//   // Redirect to a protected page or display a success message
//   header("Location: /protected_page.php");
//   exit();
// } else {
//   // Display an error message
//   echo "<p>Invalid username or password.</p>";
// }

?>
