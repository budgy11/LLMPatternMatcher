

<?php

// Database credentials (Replace with your actual credentials)
$host = "localhost";
$username = "your_username";
$password = "your_password";
$database = "your_database";

// Function to handle login
function loginUser($username, $password) {
  // Connect to the database
  $conn = new mysqli($host, $username, $password, $database);

  // Check connection
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  // SQL query to retrieve user data
  $sql = "SELECT id, username, password FROM users WHERE username = '$username'";
  $result = $conn->query($sql);

  if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    // Verify password
    if (password_verify($password, $row['password'])) {
      // Login successful
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

  // Close the connection (important!)
  $conn->close();
}

// Example Usage (for testing - replace with your form input)
// This is just an example; you'll likely use a form to submit the credentials.
// $username = $_POST['username'];
// $password = $_POST['password'];

// If you uncomment the above lines and use a form, you'll need to
// also include HTML form elements to capture the username and password.
//  For example:

//  <form method="post" action="">
//   Username: <input type="text" name="username">
//   Password: <input type="password" name="password">
//   <button type="submit">Login</button>
// </form>


// Example: Testing the function directly (for demonstration)
// $username = "testuser";
// $password = "password123";
// if (loginUser($username, $password)) {
//   echo "Login successful!";
// } else {
//   echo "Login failed.";
// }
?>
