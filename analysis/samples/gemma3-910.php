
<!DOCTYPE html>
<html>
<head>
  <title>Login Form</title>
</head>
<body>

  <h1>Login</h1>

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

// Database Credentials - Replace with your actual credentials!
$db_host = 'localhost';
$db_user = 'your_username';
$db_password = 'your_password';
$db_name = 'your_database_name';

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Get username and password from the form
  $username = trim($_POST["username"]);
  $password = trim($_POST["password"]);

  // Validate input - Basic validation (Stronger validation is recommended in production)
  if (empty($username) || empty($password)) {
    $error_message = "Username and password cannot be empty.";
  } elseif (strlen($username) < 3) {
    $error_message = "Username must be at least 3 characters long.";
  } 
  // Add more validation rules here, like checking for valid characters, password strength, etc.


  // Database connection
  $conn = new mysqli($db_host, $db_user, $db_password, $db_name);

  // Check connection
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  // Prepare the SQL query
  $sql = "SELECT * FROM users WHERE username = ? AND password = ?"; // Use prepared statements for security

  // Prepare the statement
  $stmt = $conn->prepare($sql);

  // Bind parameters
  $stmt->bind_param("ss", $username, $password);

  // Execute the statement
  if ($stmt->execute() === TRUE) {
    // Get result
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
      // User found, set session variables
      session_start();
      $_SESSION["username"] = $username;  // Store the username
      $user_data = $result->fetch_assoc(); // Get user data
      $_SESSION['user_id'] = $user_data['id']; // Store the user ID (optional but recommended)

      // Redirect to a protected page or display a success message
      header("Location: /protected_page.php"); // Replace with your protected page URL
      exit; 
    } else {
      $error_message = "Invalid username or password.";
    }
  } else {
    $error_message = "Error executing query. " . $conn->error;
  }
} else {
  // If the form hasn't been submitted, display the login form
  // This is usually handled by your HTML template.  The code below is just to
  // show what's happening on the server side when the page is first loaded.
  $error_message = ""; 
}

// Close the connection
$conn->close();

?>
