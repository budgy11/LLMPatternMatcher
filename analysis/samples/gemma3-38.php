
<!--  Your login form HTML (example): -->
<form action="" method="post">
    Username: <input type="text" name="username">
    Password: <input type="password" name="password">
    <button type="submit">Login</button>
</form>


<?php

// Database credentials (Replace with your actual values)
$host = "localhost";
$username = "your_username";
$password = "your_password";
$database = "your_database";

// Function to handle login
function login($username, $password) {
  // Connect to the database
  $conn = new mysqli($host, $username, $password, $database);

  // Check connection
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  // SQL query to check for valid credentials
  $stmt = $conn->prepare("SELECT id, username, password FROM users WHERE username = ?");
  $stmt->bind_param("s", $username); // "s" indicates a string parameter
  $stmt->execute();

  // Get the result
  $result = $stmt->get_result();

  if ($result->num_rows == 0) {
    return false; // Username not found
  } else {
    $user = $result->fetch_assoc(); // Fetch the result as an associative array
    // Verify password
    if (password_verify($password, $user['password'])) {
      // Password is correct, set session variables
      $_SESSION['user_id'] = $user['id'];
      $_SESSION['username'] = $user['username'];
      return true; // Login successful
    } else {
      return false; // Incorrect password
    }
  }

  // Close the statement and connection
  $stmt->close();
  $conn->close();
}


// Example Usage (for demonstration - this needs to be on a form)
// To use this function, you'd typically call it from a form submission.

//  Assume you have a form with fields "username" and "password"
//  and a button to submit.  The form data would be captured in $_POST

//  Example (for demonstration, not a complete form handling)
//  if (isset($_POST['username']) && isset($_POST['password'])) {
//      $username = $_POST['username'];
//      $password = $_POST['password'];

//      if (login($username, $password)) {
//          echo "Login successful!";
//          // Redirect to a protected page or perform other actions
//      } else {
//          echo "Invalid username or password.";
//      }
//  }
?>
