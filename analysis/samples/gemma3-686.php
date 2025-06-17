
<!DOCTYPE html>
<html>
<head>
  <title>Login Form</title>
</head>
<body>

  <h2>Login</h2>

  <form method="post" action="">
    Username: <input type="text" name="username" placeholder="Your Username" required>
    Password: <input type="password" name="password" required>
    <button type="submit">Login</button>
  </form>

</body>
</html>


<?php

// Database connection details (replace with your actual credentials)
$host = "localhost";
$username = "your_username";
$password = "your_password";
$database = "your_database";

// Form data
$login_username = $_POST['username'] ?? ''; // Use null coalescing operator for safety
$login_password = $_POST['password'] ?? '';

// Check if the form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

  // Sanitize input (Important for security!)
  $login_username = filter_var($login_username, FILTER_SANITIZE_STRING);
  $login_password = filter_var($login_password, FILTER_SANITIZE_STRING);

  // Validate inputs (basic example - customize as needed)
  if (empty($login_username) || empty($login_password)) {
    $error_message = "Username and Password cannot be empty.";
  } else {
    // SQL Query (use prepared statements for security - demonstrated below)
    $sql = "SELECT id, username, password FROM users WHERE username = ? AND password = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $login_username, $login_password);
    $stmt->execute();

    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
      // User found, set session variables
      $user = $result->fetch_assoc();
      $_SESSION['user_id'] = $user['id'];
      $_SESSION['username'] = $user['username'];

      // Redirect to a protected page (e.g., user dashboard)
      header("Location: user_dashboard.php");
      exit(); // Important to stop further execution
    } else {
      $error_message = "Invalid username or password.";
    }
  }
}
?>
