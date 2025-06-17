
<!DOCTYPE html>
<html>
<head>
    <title>Login Form</title>
</head>
<body>

    <h1>Login</h1>

    <form method="post" action="">
        <label for="username">Username:</label>
        <input type="text" id="username" name="username"><br><br>

        <label for="password">Password:</label>
        <input type="password" id="password" name="password"><br><br>

        <input type="submit" value="Login">
    </form>

</body>
</html>


<?php

// Database connection details (replace with your actual details)
$db_host = "localhost";
$db_name = "your_database_name";
$db_user = "your_username";
$db_password = "your_password";

// Function to handle user login
function loginUser($username, $password) {
  // Connect to the database
  $conn = new mysqli($db_host, $db_user, $db_password, $db_name);

  // Check connection
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  // SQL query to fetch user details
  $sql = "SELECT * FROM users WHERE username = '$username' AND password = '$password'";
  $result = $conn->query($sql);

  if ($result->num_rows > 0) {
    // User found
    $user = $result->fetch_assoc();
    // Set session variables to store user information
    $_SESSION['user_id'] = $user['id'];
    $_SESSION['username'] = $user['username'];
    // You might want to store other user details here, e.g., $user['email']
    return true; // Login successful
  } else {
    // User not found
    return false; // Login failed
  }

  $conn->close();
}

// Example usage:

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Get username and password from the form
  $username = trim($_POST["username"]);
  $password = trim($_POST["password"]);

  // Validate input (important for security!)
  if (empty($username) || empty($password)) {
    echo "<p>Username and password cannot be empty.</p>";
  } else {
    // Call the login function
    if (loginUser($username, $password)) {
      // Login successful - redirect to a protected page or display a welcome message
      header("Location: protected_page.php"); // Replace with your protected page
      exit();
    } else {
      // Login failed - display an error message
      echo "<p>Invalid username or password.</p>";
    }
  }
}

?>
