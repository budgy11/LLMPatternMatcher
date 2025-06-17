    Username: <input type="text" name="username" placeholder="Your Email Address">
    Password: <input type="password" name="password" placeholder="Your Password">
    <button type="submit">Login</button>
  </form>

  <p>Don't have an account? <a href="register.php">Register</a></p>  <!-- Link to registration page -->
</body>
</html>


<?php

// Database credentials (replace with your actual credentials)
$db_host = 'localhost';
$db_name = 'your_database_name';
$db_user = 'your_username';
$db_password = 'your_password';

// Function to handle user login
function loginUser($username, $password) {
  // Connect to the database
  $conn = new mysqli($db_host, $db_user, $db_password, $db_name);

  // Check connection
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  // SQL query to check for the user
  $sql = "SELECT * FROM users WHERE username = '$username' AND password = '$password'";
  $result = $conn->query($sql);

  if ($result->num_rows > 0) {
    // User found - Fetch data from the result set
    $user = $result->fetch_assoc();

    // Create a session
    session_start();

    // Store user data in the session
    $_SESSION['user_id'] = $user['id'];  // Assuming 'id' is the user's ID column
    $_SESSION['username'] = $user['username']; // Store username as well

    // Redirect to a secure page (e.g., the homepage)
    header("Location: welcome.php");
    exit(); // Important: Stop further script execution
  } else {
    // User not found
    return false;
  }

  $conn->close();
}

// Example Usage (handling login form submission)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Get username and password from the form
  $username = $_POST["username"];
  $password = $_POST["password"];

  // Validate input (very important - see explanation below)
  if (empty($username) || empty($password)) {
    $error = "Username and Password must be filled.";
  } else {
    // Call the loginUser function
    $login_result = loginUser($username, $password);

    if ($login_result === false) {
      $error = "Invalid username or password.";
    }
  }
}
?>
