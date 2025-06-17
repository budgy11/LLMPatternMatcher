
<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
</head>
<body>

    <h2>Login</h2>

    <form action="" method="post">
        Username: <input type="text" name="username" required><br><br>
        Password: <input type="password" name="password" required><br><br>
        <input type="submit" value="Login">
    </form>

</body>
</html>


<?php

// Database credentials (replace with your actual values)
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

  // SQL query to retrieve the user's password hash
  $query = "SELECT password_hash, user_id FROM users WHERE username = '$username'";
  $result = $conn->query($query);

  if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $hashedPassword = $row['password_hash'];
    $userId = $row['user_id'];

    // Verify the password
    if (password_verify($password, $hashedPassword)) {
      // Authentication successful
      session_start(); // Start a session to store user information

      // Store user information in the session
      $_SESSION['user_id'] = $userId;
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

// Example Usage (in your login form HTML)
//  <form method="post" action="login.php">
//   Username: <input type="text" name="username">
//   Password: <input type="password" name="password">
//   <button type="submit">Login</button>
// </form>

// PHP code to handle the form submission
//  <?php
//  if ($_SERVER["REQUEST_METHOD"] == "POST") {
//    $username = $_POST["username"];
//    $password = $_POST["password"];

//    if (loginUser($username, $password)) {
//      // Login successful, redirect to your homepage or desired page
//      header("Location: homepage.php"); // Replace with your homepage URL
//      exit();
//    } else {
//      // Login failed
//      $error = "Invalid username or password.";
//    }
//  }
//?>
