
<!DOCTYPE html>
<html>
<head>
  <title>Login Form</title>
</head>
<body>

  <h1>Login</h1>

  <form method="post" action="">
    Username: <input type="text" name="username" required><br><br>
    Password: <input type="password" name="password" required><br><br>
    <input type="submit" value="Login">
  </form>

</body>
</html>


<?php

// Database connection details (replace with your actual credentials)
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

  // SQL query to check username and password
  $sql = "SELECT * FROM users WHERE username = '$username' AND password = '$password'";
  $result = $conn->query($sql);

  if ($result->num_rows == 0) {
    // User not found
    return false;
  } else {
    // User found, set session variables for login
    session_start();
    $_SESSION['username'] = $username;
    return true;
  }

  // Close the connection
  $conn->close();
}


// Example Usage (Login Form)
// Assuming you have a form with fields 'username' and 'password'
// and a button that calls this function.

// Example: User input from a form

// if (isset($_POST['login_submit'])) {
//   $username = $_POST['username'];
//   $password = $_POST['password'];

//   if (loginUser($username, $password)) {
//     // Login successful
//     header("Location: welcome.php"); // Redirect to a welcome page
//     exit(); // Stop further execution
//   } else {
//     // Login failed
//     echo "<p style='color:red;'>Invalid username or password.</p>";
//   }
// }
// else {
//     // Form submission wasn't for the login form - handle accordingly
// }



// Example: Simple Test (For demonstration only - don't use in production)
// You can uncomment this to test the function.
// $username_test = "testuser";
// $password_test = "password123";
// if (loginUser($username_test, $password_test)) {
//   echo "<p>Login successful!  User: " . $_SESSION['username'] . "</p>";
// } else {
//   echo "<p>Login failed.</p>";
// }


?>
