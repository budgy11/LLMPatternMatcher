

<?php

// Database credentials (Replace with your actual credentials)
$db_host = "localhost";
$db_user = "your_username";
$db_password = "your_password";
$db_name = "your_database";

// Function to handle user login
function loginUser($username, $password) {
  // Connect to the database
  $conn = new mysqli($db_host, $db_user, $db_password, $db_name);

  // Check connection
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  // Prepare the SQL query
  $sql = "SELECT id, username, password FROM users WHERE username = ? AND password = ?";

  // Use prepared statement to prevent SQL injection
  $stmt = $conn->prepare($sql);
  $stmt->bind_param("ss", $username, $password);  // "ss" means two strings

  // Execute the query
  if ($stmt->execute()) {
    // Get the result
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
      $row = $result->fetch_assoc();
      // User found, set session variables
      $_SESSION["user_id"] = $row["id"];
      $_SESSION["username"] = $row["username"];
      return true; // Login successful
    } else {
      return false; // User not found
    }
  } else {
    return false; // Query execution failed
  }

  // Close the statement and connection
  $stmt->close();
  $conn->close();
}


// Example usage:
// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Get form data
  $username = $_POST["username"];
  $password = $_POST["password"];

  // Validate username and password (Important for security!)
  if (empty($username) || empty($password)) {
    $error = "Username and password cannot be empty.";
  } else {
    // Attempt to log in
    $login_result = loginUser($username, $password);

    if ($login_result) {
      // Login successful, redirect to a secure page or display a welcome message
      echo "Welcome, " . $_SESSION["username"] . "! You have successfully logged in.";
      // Optionally redirect:
      header("Location: welcome.php"); // Redirect to a welcome page.  Make sure 'welcome.php' exists
      exit();
    } else {
      $error = "Invalid username or password.";
    }
  }
}
?>
