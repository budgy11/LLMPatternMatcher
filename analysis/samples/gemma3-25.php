    Username: <input type="text" name="username" placeholder="Enter your username">
    Password: <input type="password" name="password" placeholder="Enter your password">
    <button type="submit">Login</button>
  </form>

  <p>Don't have an account? <a href="register.php">Register here</a></p>  <!-- Link to registration page -->

</body>
</html>


<?php

// Database credentials (replace with your actual values)
$host = "localhost";
$username = "your_username";
$password = "your_password";
$database = "your_database";

// Form data
$login_username = $_POST['username'];
$login_password = $_POST['password'];

// Check if the form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

  // Connect to the database
  $conn = new mysqli($host, $username, $password, $database);

  // Check connection
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  // SQL query to authenticate
  $sql = "SELECT id, username, password FROM users WHERE username = '$login_username'";
  $result = $conn->query($sql);

  if ($result->num_rows > 0) {
    // User found, verify the password
    $user_data = $result->fetch_assoc();
    if (password_verify($login_password, $user_data['password'])) {
      // Password is correct, user is authenticated
      // Set a session variable to indicate the user is logged in
      session_start();
      $_SESSION['user_id'] = $user_data['id'];
      $_SESSION['username'] = $user_data['username'];
      echo "Login successful! You are now logged in.";
      // Redirect to a protected page or home page
      header("Location: /protected_page.php"); // Replace with your protected page URL
      exit();
    } else {
      echo "Incorrect password.";
    }
  } else {
    echo "User not found.";
  }

  $conn->close();
} else {
  // If the form wasn't submitted via POST, redirect to the login page
  header("Location: login.php");
  exit();
}

?>
