

<?php

// Database credentials (replace with your actual values)
$db_host = 'localhost';
$db_user = 'your_db_user';
$db_pass = 'your_db_password';
$db_name = 'your_db_name';

// Function to handle user login
function loginUser($username, $password) {
  // Connect to the database
  $conn = new mysqli($db_host, $db_user, $db_pass, $db_name);

  // Check connection
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  // Prepare the SQL query
  $query = "SELECT id, username, password FROM users WHERE username = ?";

  // Use prepared statement to prevent SQL injection
  $stmt = $conn->prepare($query);

  if ($stmt) {
    $stmt->bind_param("s", $username);  // 's' indicates a string parameter
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
      $user = $result->fetch_assoc();
      // Verify password
      if (password_verify($password, $user['password'])) {
        // Successful login
        session_start();
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        return true;
      } else {
        $stmt->close();
        return false; // Incorrect password
      }
    } else {
      $stmt->close();
      return false; // User not found
    }
  } else {
    $stmt = null; // Make sure the statement is closed if it fails to prepare
    return false;
  }

  $stmt->close();
}


// Example usage:
// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Get username and password from the form
  $username = $_POST["username"];
  $password = $_POST["password"];

  // Perform login
  if (loginUser($username, $password)) {
    // Login successful - redirect to a different page, or display a welcome message
    header("Location: welcome.php"); // Redirect to a welcome page
    exit();

  } else {
    // Login failed - display an error message
    $error = "Invalid username or password.";
  }
}
?>
