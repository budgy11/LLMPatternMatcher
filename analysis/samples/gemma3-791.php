

<?php

// Database credentials (Replace with your actual credentials)
$db_host = "localhost";
$db_name = "your_database_name";
$db_user = "your_username";
$db_pass = "your_password";

// Function to handle user login
function loginUser($username, $password) {
  // Connect to the database
  $conn = new mysqli($db_host, $db_user, $db_pass, $db_name);

  // Check connection
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  // SQL query to retrieve the user's password hash
  $sql = "SELECT password_hash, user_id FROM users WHERE username = '$username'";
  $result = $conn->query($sql);

  if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $hashed_password = $row['password_hash'];
    $user_id = $row['user_id'];

    // Verify the password
    if (password_verify($password, $hashed_password)) {
      // Authentication successful
      $_SESSION['user_id'] = $user_id; // Store user ID in session
      return true; // Indicate successful login
    } else {
      // Authentication failed
      return false;
    }
  } else {
    // User not found
    return false;
  }

  // Close the connection
  $conn->close();
}


// Example usage (Demonstration - do not use in a production environment like this)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $username = $_POST["username"];
  $password = $_POST["password"];

  if (loginUser($username, $password)) {
    // Login successful
    echo "Login successful!  Redirecting...";
    header("Location: welcome.php"); // Redirect to a welcome page
    exit();
  } else {
    // Login failed
    echo "Invalid username or password.";
  }
}
?>
