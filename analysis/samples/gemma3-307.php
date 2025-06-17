

<?php

// Database credentials - REPLACE WITH YOUR ACTUAL CREDENTIALS
$host = "localhost";
$username = "your_username";
$password = "your_password";
$database = "your_database";

// Function to handle user login
function loginUser($username, $password) {
  // Connect to the database
  $conn = new mysqli($host, $username, $password, $database);

  // Check connection
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  // SQL query to check for the user
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
      return false; // Indicate password mismatch
    }
  } else {
    return false; // User not found
  }

  $conn->close();
}

// Example Usage (Illustrative - Implement this in your form)
if (isset($_POST['username']) && isset($_POST['password'])) {
  $username = $_POST['username'];
  $password = $_POST['password'];

  if (loginUser($username, $password)) {
    // Redirect to a secure page or display a success message
    header("Location: secure_page.php"); // Replace with your secure page
    exit();
  } else {
    // Handle login failure - Display error message
    echo "<p style='color:red;'>Invalid username or password.</p>";
  }
}

?>
