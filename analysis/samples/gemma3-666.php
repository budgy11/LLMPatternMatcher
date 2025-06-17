    Username: <input type="text" name="username" required><br><br>
    Password: <input type="password" name="password" required><br><br>
    <input type="submit" value="Login">
  </form>

  <p>Don't have an account? <a href="register.php">Register</a></p>  <!-- Link to a registration page -->

</body>
</html>


<?php

// Database credentials (replace with your actual credentials)
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

  // SQL query to authenticate the user
  $sql = "SELECT * FROM users WHERE username = '$username' AND password = '$password'";
  $result = $conn->query($sql);

  if ($result->num_rows > 0) {
    // User found, create a session variable to store the user's ID
    $row = $result->fetch_assoc();
    $_SESSION['user_id'] = $row['id']; // Assuming 'id' column represents the user ID
    $_SESSION['username'] = $row['username']; 
    return true; // Login successful
  } else {
    return false; // Login failed
  }

  $conn->close();
}

// Example Usage:
if (isset($_POST['login_username']) && isset($_POST['login_password'])) {
  $username = $_POST['login_username'];
  $password = $_POST['login_password'];

  if (loginUser($username, $password)) {
    echo "Login successful!  Redirecting...";
    // Redirect to a secure page or homepage
    header("Location: /secure_page.php"); // Replace with your secure page URL
    exit();
  } else {
    echo "Invalid username or password.";
  }
}
?>
