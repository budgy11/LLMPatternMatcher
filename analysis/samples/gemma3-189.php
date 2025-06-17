    Username: <input type="text" name="username" required>
    Password: <input type="password" name="password" required>
    <button type="submit">Login</button>
  </form>

  <p>Don't have an account? <a href="register.php">Register</a></p>

</body>
</html>


<?php

// Database credentials (replace with your actual values)
$db_host = "localhost";
$db_name = "your_database_name";
$db_user = "your_database_username";
$db_password = "your_database_password";

// Function to handle user login
function loginUser($username, $password) {
  // Connect to the database
  $conn = new mysqli($db_host, $db_user, $db_password, $db_name);

  // Check connection
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  // SQL query to check login credentials
  $sql = "SELECT * FROM users WHERE username = '$username' AND password = '$password'";
  $result = $conn->query($sql);

  if ($result->num_rows > 0) {
    // User exists, return their ID (or other relevant data)
    $user_data = $result->fetch_assoc();
    return $user_data['id']; // Assuming 'id' is the primary key in your users table
  } else {
    // User does not exist
    return false;
  }

  $conn->close();
}

// Example Usage (Demonstration -  DO NOT HARDCODE CREDENTIALS IN PRODUCTION)
//  In a real application, this would come from a form submission.

// Simulate form submission (for testing)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $password = $_POST["password"];

    // Perform the login
    $user_id = loginUser($username, $password);

    if ($user_id) {
      echo "Login successful! User ID: " . $user_id;
      //  You would typically set a session variable here:
      //  $_SESSION['user_id'] = $user_id;
    } else {
      echo "Invalid username or password.";
    }
  }
?>
