
</body>
</html>


<?php

// Database credentials (Replace with your actual credentials)
$db_host = 'localhost';
$db_name = 'your_database_name';
$db_user = 'your_username';
$db_password = 'your_password';

// Function to handle user login
function loginUser($username, $password) {
  // Connect to the database
  $conn = new mysqli($db_host, $db_user, $db_password, $db_name);

  // Check connection
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  // SQL query to check username and password
  $sql = "SELECT id, username, password FROM users WHERE username = '$username'";
  $result = $conn->query($sql);

  if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    // Verify the password
    if (password_verify($password, $row['password'])) {
      // Login successful - Set session variables
      $_SESSION['user_id'] = $row['id'];
      $_SESSION['username'] = $username;
      return true; // Indicate successful login
    } else {
      return false; // Indicate incorrect password
    }
  } else {
    return false; // Indicate username not found
  }

  $conn->close();
}

// Example usage:
// Assume the user has entered their username and password in form fields
if (isset($_POST['username']) && isset($_POST['password'])) {
  $username = $_POST['username'];
  $password = $_POST['password'];

  if (loginUser($username, $password)) {
    // Login successful - Redirect to a protected page or display a welcome message
    header("Location: /protected_page.php"); // Replace with your protected page
    exit();
  } else {
    // Login failed - Display an error message
    echo "<p style='color:red;'>Invalid username or password.</p>";
  }
}

?>
