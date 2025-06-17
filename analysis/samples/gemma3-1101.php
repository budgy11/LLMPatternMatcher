
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

// Database credentials (replace with your actual values)
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

  // SQL query to check for the username and password
  $sql = "SELECT id, username, password FROM users WHERE username = ? AND password = ?";

  // Prepare the statement
  $stmt = $conn->prepare($sql);

  if ($stmt) {
    // Bind parameters
    $stmt->bind_param("ss", $username, $password);

    // Execute the statement
    if ($stmt->execute()) {
      // Get the result
      $result = $stmt->get_result();

      if ($result->num_rows == 1) {
        // User found, retrieve user data
        $user = $result->fetch_assoc();
        // Set session variables to store the user information
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        return true; // Login successful
      } else {
        // User not found
        return false;
      }
    } else {
      // Error executing statement
      error_log("Query error: " . $conn->error); // Log the error for debugging
      return false;
    }
  } else {
    // Error preparing statement
    error_log("Statement preparation error");
    return false;
  }

  // Close the statement and connection
  $stmt->close();
  $conn->close();
}

// Example Usage (Form Handling - Not a complete form, just demonstrating the login function)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $username = $_POST["username"];
  $password = $_POST["password"];

  if (loginUser($username, $password)) {
    // Redirect to a secure page or display a welcome message
    header("Location: /welcome.php"); // Replace with your welcome page URL
    exit();
  } else {
    // Handle login failure (display error message)
    echo "<p style='color:red;'>Invalid username or password.</p>";
  }
}

?>
