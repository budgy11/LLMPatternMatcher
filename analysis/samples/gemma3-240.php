

<?php

// Database credentials (Replace with your actual credentials)
$db_host = "localhost";
$db_name = "your_database_name";
$db_user = "your_username";
$db_password = "your_password";

// Session handling
session_start();

// Function to check login credentials
function loginUser($username, $password) {
  // Connect to the database
  $conn = new mysqli($db_host, $db_user, $db_password, $db_name);

  // Check connection
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  // Prepare the SQL query
  $sql = "SELECT id, username, password FROM users WHERE username = ?";
  $stmt = $conn->prepare($sql);

  if ($stmt) {
    // Bind the username parameter
    $stmt->bind_param("s", $username);

    // Execute the query
    if ($stmt->execute()) {
      $result = $stmt->get_result();

      if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        // Verify password
        if (password_verify($password, $user['password'])) {
          // Login successful
          $_SESSION['user_id'] = $user['id'];
          $_SESSION['username'] = $user['username'];
          return true;
        } else {
          // Incorrect password
          return false;
        }
      } else {
        // User not found
        return false;
      }

      $stmt->close();
    } else {
      // Query execution failed
      return false;
    }
  } else {
    // Failed to prepare statement
    return false;
  }
}

// Example usage:

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $username = $_POST["username"];
  $password = $_POST["password"];

  if (loginUser($username, $password)) {
    // Login successful, redirect to a protected page
    header("Location: /protected_page.php"); // Replace with your protected page URL
    exit();
  } else {
    // Login failed
    $error = "Invalid username or password";
  }
}
?>
