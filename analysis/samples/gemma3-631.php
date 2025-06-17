

<?php

// Database Credentials (Replace with your actual credentials)
$db_host = "localhost";
$db_username = "your_db_username";
$db_password = "your_db_password";
$db_name = "your_db_name";

// Function to handle user login
function loginUser($username, $password) {
  // Connect to the database
  $conn = new mysqli($db_host, $db_username, $db_password, $db_name);

  // Check connection
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  // SQL query to authenticate the user
  $sql = "SELECT id, username, password FROM users WHERE username = '$username'";
  $result = $conn->query($sql);

  if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    // Verify the password
    if (password_verify($password, $row['password'])) {
      // Authentication successful
      $_SESSION['user_id'] = $row['id'];
      $_SESSION['username'] = $username;
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

// Example Usage (Demonstration - Replace with your form handling)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $username = $_POST["username"];
  $password = $_POST["password"];

  if (loginUser($username, $password)) {
    // Login successful - Redirect to the user's page or desired location
    header("Location: user_page.php"); // Replace with your user page
    exit();
  } else {
    // Login failed - Display an error message
    echo "<p style='color:red;'>Invalid username or password.</p>";
  }
}

?>
