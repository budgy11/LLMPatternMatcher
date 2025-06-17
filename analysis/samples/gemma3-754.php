
<!DOCTYPE html>
<html>
<head>
    <title>Login Form</title>
</head>
<body>

<h2>Login</h2>

<form action="" method="post">
    Username: <input type="text" name="username">
    Password: <input type="password" name="password">
    <input type="submit" value="Login">
</form>

</body>
</html>


<?php

// Database connection details (Replace with your actual credentials)
$db_host = 'localhost';
$db_user = 'your_username';
$db_password = 'your_password';
$db_name = 'your_database';

// Function to handle login
function loginUser($username, $password) {
  // Connect to the database
  $conn = new mysqli($db_host, $db_user, $db_password, $db_name);

  // Check connection
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  // Prepare the SQL query
  $sql = "SELECT * FROM users WHERE username = ? AND password = ?";

  // Prepare the statement
  $stmt = $conn->prepare($sql);

  if ($stmt) {
    // Bind parameters
    $stmt->bind_param("ss", $username, $password);  // "ss" means two strings

    // Execute the statement
    if ($stmt->execute()) {
      // Get the result
      $result = $stmt->get_result();

      if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();
        // Successful login
        $_SESSION['user_id'] = $row['id']; // Store user ID in session
        $_SESSION['username'] = $row['username']; // Store username

        // Redirect to a protected page or display a success message
        header("Location: /protected_page.php"); // Replace with your protected page URL
        exit(); // Important: Stop further execution after redirecting

      } else {
        // Login failed (no user found or wrong password)
        return false;
      }
    } else {
      // Query execution failed
      return false;
    }
  } else {
    // Error preparing the statement
    return false;
  }

  // Close the statement and connection
  $stmt->close();
  $conn->close();

  return true; // Indicate successful login
}


// Example usage (in your login form)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $username = $_POST["username"];
  $password = $_POST["password"];

  if (loginUser($username, $password)) {
    echo "Login successful!  Redirecting...";
  } else {
    echo "Invalid username or password.";
  }
}
?>
