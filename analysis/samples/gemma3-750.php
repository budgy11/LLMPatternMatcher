
<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
</head>
<body>

    <h2>Login</h2>

    <form method="post" action="">
        <label for="username">Username:</label>
        <input type="text" id="username" name="username" required><br><br>

        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required><br><br>

        <button type="submit">Login</button>
    </form>

</body>
</html>


<?php

// Database connection details (Replace with your actual values)
$host = "localhost";
$username = "your_username";
$password = "your_password";
$database = "your_database";

// Function to handle login
function loginUser($username, $password) {
  // 1. Connect to the database
  $conn = new mysqli($host, $username, $password, $database);

  // Check connection
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  // 2. SQL Query to verify credentials
  $sql = "SELECT * FROM users WHERE username = '$username' AND password = '$password'";
  $result = $conn->query($sql);

  // 3. Check if any rows were returned
  if ($result->num_rows > 0) {
    // User found - Get the data
    $user = $result->fetch_assoc();
    // 4. Store user session information
    $_SESSION['user_id'] = $user['id']; // Assuming 'id' is the user's ID
    $_SESSION['username'] = $user['username'];

    return true; // Login successful
  } else {
    return false; // Login failed
  }

  // Close connection
  $conn->close();
}


// Example Usage (Demonstration - Not for Production)
if (isset($_POST['login_username']) && isset($_POST['login_password'])) {
  $username = $_POST['login_username'];
  $password = $_POST['login_password'];

  if (loginUser($username, $password)) {
    // Redirect to a protected page or display a success message
    header("Location: protected_page.php"); // Replace with your protected page
    exit(); // Important: Stop further script execution
  } else {
    // Display error message
    echo "<p style='color:red;'>Invalid username or password.</p>";
  }
}
?>
