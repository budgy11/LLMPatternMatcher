

<?php

// Database Configuration (Replace with your actual values)
$db_host = "localhost";
$db_user = "your_db_user";
$db_pass = "your_db_password";
$db_name = "your_db_name";

// ---  Function to handle Login ---
function loginUser($username, $password) {
  // Connect to the database
  $conn = new mysqli($db_host, $db_user, $db_pass, $db_name);

  // Check connection
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  // SQL query to fetch user information
  $sql = "SELECT id, username, password FROM users WHERE username = '$username'";
  $result = $conn->query($sql);

  if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
    // Verify password (use a secure hashing method - see below)
    if (password_verify($password, $user['password'])) {
      // Successful login
      $_SESSION['user_id'] = $user['id'];
      $_SESSION['username'] = $user['username'];
      return true; // Indicate successful login
    } else {
      return false; // Incorrect password
    }
  } else {
    return false; // User not found
  }

  $conn->close();
}

// ---  Example Usage (For demonstration only - should be within a form submission) ---

// Check if the login form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Get username and password from the form
  $username = $_POST["username"];
  $password = $_POST["password"];

  // Validate input (important for security - prevent SQL injection)
  if (empty($username) || empty($password)) {
    $error = "Username and password cannot be empty.";
  } else {
      // Call the loginUser function
      $loginResult = loginUser($username, $password);

      if ($loginResult) {
        // Successful login - Redirect to a secure page or display a welcome message
        header("Location: welcome.php"); // Replace with your desired page
        exit(); // Stop further execution
      } else {
        $error = "Invalid username or password.";
      }
    }
}
?>
