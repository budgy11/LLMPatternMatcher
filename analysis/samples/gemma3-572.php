
<!DOCTYPE html>
<html>
<head>
    <title>Login Form</title>
</head>
<body>

    <h2>Login</h2>

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

// Database credentials (Replace with your actual values)
$db_host = "localhost";
$db_name = "your_database_name";
$db_user = "your_database_user";
$db_pass = "your_database_password";

// Function to handle user login
function loginUser($username, $password) {
  // Connect to the database
  $conn = new mysqli($db_host, $db_user, $db_pass, $db_name);

  // Check connection
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  // SQL query to check the username and password
  $sql = "SELECT id, username, password FROM users WHERE username = '$username'";
  $result = $conn->query($sql);

  if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    // Verify password
    if (password_verify($password, $row['password'])) {
      // Authentication successful
      $_SESSION['user_id'] = $row['id'];
      $_SESSION['username'] = $row['username'];
      return true;
    } else {
      // Incorrect password
      return false;
    }
  } else {
    // User not found
    return false;
  }

  // Close the connection
  $conn->close();
}

// Example usage (Illustrative - use with proper form handling)
// This is a simplified example.  You would get the username and password
// from a form submission.

// Check if the login form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $username = $_POST["username"];
  $password = $_POST["password"];

  if (loginUser($username, $password)) {
    // Login successful, redirect to a different page or display a success message
    header("Location: welcome.php"); // Redirect to a welcome page.  Replace 'welcome.php'
                                    // with your desired page.
    exit();
  } else {
    // Login failed, display an error message
    $error = "Invalid username or password.";
  }
}
?>
