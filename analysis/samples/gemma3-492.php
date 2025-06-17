    Username: <input type="text" name="username" required>
    Password: <input type="password" name="password" required>
    <button type="submit">Login</button>
  </form>

</body>
</html>


<?php

// Database connection details (Replace with your actual credentials)
$db_host = "localhost";
$db_name = "your_database_name";
$db_user = "your_username";
$db_password = "your_password";

// Function to handle the login process
function loginUser($username, $password) {
  // Connect to the database
  $conn = new mysqli($db_host, $db_user, $db_password, $db_name);

  // Check connection
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  // SQL query to authenticate the user
  $sql = "SELECT id, username, password FROM users WHERE username = '$username'";
  $result = $conn->query($sql);

  if ($result->num_rows > 0) {
    // User found, verify the password
    $row = $result->fetch_assoc();
    if (password_verify($password, $row['password'])) { // Use password_verify for security
      // Authentication successful
      session_start();  // Start the session to store user information

      // Store user information in the session
      $_SESSION['user_id'] = $row['id'];
      $_SESSION['username'] = $row['username'];

      return true; // Indicate successful login
    } else {
      return false; // Password mismatch
    }
  } else {
    return false; // User not found
  }

  $conn->close();
}

// Example usage (This is a simplified example; handle form input and error handling properly)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $username = $_POST["username"];
  $password = $_POST["password"];

  if (loginUser($username, $password)) {
    // Login successful
    echo "Login successful!  You are now logged in.";
    // Redirect to a protected page or display a welcome message
    header("Location: /protected_page.php"); // Replace with your protected page URL
    exit();
  } else {
    echo "Invalid username or password.";
  }
}

?>
