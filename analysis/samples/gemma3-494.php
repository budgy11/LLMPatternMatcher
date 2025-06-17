
<!-- HTML Form (Example) -->
<form action="" method="post">
  Username: <input type="text" name="username">
  Password: <input type="password" name="password">
  <button type="submit">Login</button>
</form>


<?php

// Database configuration (replace with your actual values)
$db_host = "localhost";
$db_name = "your_database_name";
$db_user = "your_database_username";
$db_password = "your_database_password";

// Session handling (for storing user information)
session_start();

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

  // Prepare the statement
  $stmt = $conn->prepare($sql);

  // Bind the parameters
  $stmt->bind_param("ss", $username, $password);

  // Execute the statement
  if ($stmt->execute() === TRUE) {
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
      $user_data = $result->fetch_assoc();
      $user_id = $user_data['id'];
      $username = $user_data['username'];

      // Set session variables
      $_SESSION['user_id'] = $user_id;
      $_SESSION['username'] = $username;

      // Optionally, redirect the user
      // header("Location: user_dashboard.php");
      // exit();

      return true;  // Login successful
    } else {
      return false; // No user found
    }

  } else {
    // Handle query errors
    return false;
  }

  // Close the statement and connection
  $stmt->close();
  $conn->close();
}

// Example usage (handling login form submission)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $username = $_POST["username"];
  $password = $_POST["password"];

  if (loginUser($username, $password)) {
    echo "Login successful!  Redirecting...";
    // Redirect to a user dashboard or other protected page
    header("Location: user_dashboard.php");
    exit();
  } else {
    echo "Invalid username or password.";
  }
}

?>
