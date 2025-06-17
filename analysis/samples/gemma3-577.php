

<?php

// Database credentials (Replace with your actual credentials)
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

  // SQL query to retrieve the user's password hash
  $sql = "SELECT password_hash, user_id FROM users WHERE username = '$username'";
  $result = $conn->query($sql);

  if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $hashed_password = $row['password_hash'];
    $userId = $row['user_id'];

    // Verify the password
    if (password_verify($password, $hashed_password)) {
      // Authentication successful, set session variables
      session_start(); // Start the session (important!)

      $_SESSION['user_id'] = $userId;  // Store the user ID
      $_SESSION['username'] = $username; // Optionally store the username

      return true; // Login successful
    } else {
      return false; // Incorrect password
    }
  } else {
    return false; // User not found
  }

  $conn->close(); // Close the database connection
}


// Example Usage (for testing - this would be in your form handling code)
//  This is just an example. In a real application, you'd get the username and password
//  from your form submission.  This demonstrates the login function being called.
// $username = $_POST['username']; // Get username from form
// $password = $_POST['password'];   // Get password from form

// if (loginUser($username, $password)) {
//   echo "Login successful! You are now logged in.";
//   // Redirect to a protected page or display a welcome message
// } else {
//   echo "Invalid username or password.";
// }



?>
