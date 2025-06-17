    Username: <input type="text" name="username" />
    Password: <input type="password" name="password" />
    <button type="submit">Login</button>
  </form>

  <p>Don't have an account? <a href="register.php">Register</a></p>

</body>
</html>


<?php

// Database Credentials (Replace with your actual credentials)
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

  // SQL Query to retrieve user information
  $sql = "SELECT id, username, password FROM users WHERE username = '$username'";
  $result = $conn->query($sql);

  if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    // Verify the password
    if (password_verify($password, $row['password'])) {
      // Password is correct, set session variables
      $_SESSION['user_id'] = $row['id'];
      $_SESSION['username'] = $username;
      return true; // Login successful
    } else {
      // Password mismatch
      return false;
    }
  } else {
    // User not found
    return false;
  }

  $conn->close(); // Close the connection
}

// Example usage (Handle form submission)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $username = $_POST["username"];
  $password = $_POST["password"];

  if (loginUser($username, $password)) {
    // Login successful, redirect to the welcome page or desired location
    header("Location: welcome.php"); // Replace welcome.php with your welcome page
    exit();
  } else {
    // Login failed, display an error message
    echo "<p style='color: red;'>Invalid username or password.</p>";
  }
}

?>
