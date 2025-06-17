
  <form action="" method="post">
    Username: <input type="text" name="username">
    <br><br>
    Password: <input type="password" name="password">
    <br><br>
    <input type="submit" value="Login">
  </form>

</body>
</html>


<?php

// Database configuration
$db_host = 'localhost';
$db_user = 'your_db_user';
$db_password = 'your_db_password';
$db_name = 'your_db_name';

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

  // Get username and password from the form
  $username = $_POST["username"];
  $password = $_POST["password"];

  // Validate input (Important for security!)
  if (empty($username) || empty($password)) {
    $error = "Username and password cannot be empty.";
  } elseif (!filter_var($username, FILTER_VALIDATE_EMAIL)) { // Example: Email validation
    $error = "Invalid username (Please enter a valid email address).";
  } else {
    // Database connection
    $conn = new mysqli($db_host, $db_user, $db_password, $db_name);

    // Check connection
    if ($conn->connect_error) {
      die("Connection failed: " . $conn->connect_error);
    }

    // Prepare and execute the query
    $sql = "SELECT * FROM users WHERE email = '$username'"; // Use email for security
    $result = $conn->query($sql);

    // Check if the query executed successfully
    if ($result->num_rows > 0) {
      // User found
      $row = $result->fetch_assoc();

      // Verify password
      if (password_verify($password, $row['password'])) {
        // Login successful
        // Set session variables to track the user
        session_start();
        $_SESSION['user_id'] = $row['id'];
        $_SESSION['username'] = $row['email']; // Or username if you use it
        
        // Redirect to a welcome page or another area
        header("Location: welcome.php");
        exit();
      } else {
        $error = "Incorrect password.";
      }
    } else {
      $error = "User not found.";
    }
  }
}
?>
