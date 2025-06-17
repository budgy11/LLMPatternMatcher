
<!DOCTYPE html>
<html>
<head>
  <title>Login Form</title>
</head>
<body>

  <h2>Login</h2>

  <form method="post" action="">
    Username: <input type="text" name="username">
    Password: <input type="password" name="password">
    <button type="submit">Login</button>
  </form>

</body>
</html>


<?php

// Database connection details (replace with your actual values)
$db_host = 'localhost';
$db_name = 'your_database_name';
$db_user = 'your_database_user';
$db_password = 'your_database_password';

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
    $userId = $row['user_id'];  // Store the user ID for later use

    // Verify the password
    if (password_verify($password, $hashed_password)) {
      // Password is correct!

      // You can store session information here (e.g., user ID)
      // In a real application, you'd typically use a session management system
      // to store user data securely.  This example uses a simple session variable
      session_start();
      $_SESSION['userId'] = $userId; // Store the user ID in the session

      return true; // Login successful
    } else {
      return false; // Incorrect password
    }
  } else {
    return false; // User not found
  }

  $conn->close();
}


// Example Usage (This part would typically be in a form submission handler)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $username = $_POST["username"];
  $password = $_POST["password"];

  if (loginUser($username, $password)) {
    // Login successful, redirect to a different page or display a welcome message
    echo "Login successful!  You are now logged in.";
    // Redirect to a secure page
    header("Location: /your_secure_page.php"); // Replace with your secure page
    exit();
  } else {
    echo "Invalid username or password.";
  }
}


?>
