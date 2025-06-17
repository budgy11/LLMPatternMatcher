
<!DOCTYPE html>
<html>
<head>
  <title>Login Form</title>
</head>
<body>

  <h2>Login</h2>

  <form method="post" action="">
    Username: <input type="text" name="username" required>
    Password: <input type="password" name="password" required>
    <button type="submit">Login</button>
  </form>

</body>
</html>


<?php

// Database credentials - Replace with your actual credentials
$host = "localhost";
$username = "your_username";
$password = "your_password";
$database = "your_database";

// Function to handle the login process
function loginUser($username, $password) {
  // Connect to the database
  $conn = new mysqli($host, $username, $password, $database);

  // Check connection
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  // Prepare the SQL statement for the login query
  $sql = "SELECT * FROM users WHERE username = ? AND password = ?";

  // Use prepared statement to prevent SQL injection
  $stmt = $conn->prepare($sql);

  if ($stmt) {
    // Bind the parameters
    $stmt->bind_param("ss", $username, $password);

    // Execute the statement
    $stmt->execute();

    // Get the result
    $result = $stmt->get_result();

    // Check if any rows were returned
    if ($result->num_rows > 0) {
      $row = $result->fetch_assoc();
      // User found, now you can do something like:
      //  - Set session variables
      //  - Redirect the user
      //  - Display a welcome message
      // Example:
      session_start();
      $_SESSION['user_id'] = $row['id'];
      $_SESSION['username'] = $username;
      $stmt->close();
      $conn->close();

      // Redirect the user after login
      header("Location: welcome.php"); // Replace welcome.php with your desired page
      exit(); // Stop further execution

    } else {
      $stmt->close();
      $conn->close();
      return false; // User not found
    }
  } else {
    $stmt = null;
    $conn->close();
    return false; // Preparation failed
  }
}


// Example usage (from a login form submission)
//  Let's assume the username and password are coming from a form called 'loginForm'
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $username = $_POST["username"];
  $password = $_POST["password"];

  // Validate the input (VERY IMPORTANT - see notes below)
  if (empty($username) || empty($password)) {
    $error = "Username and password cannot be empty.";
  } else {
    $loginResult = loginUser($username, $password);

    if ($loginResult === false) {
      $error = "Invalid username or password.";
    }
  }
}
?>
