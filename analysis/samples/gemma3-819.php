    Username: <input type="text" name="username" value="<?php echo isset($username) ? htmlspecialchars($username) : ''; ?>">
    Password: <input type="password" name="password">
    <button type="submit">Login</button>
  </form>

  <p>Don't have an account? <a href="register.php">Register</a></p>

</body>
</html>


<?php

// Database credentials (replace with your actual values)
$host = "localhost";
$username = "your_username";
$password = "your_password";
$database = "your_database";

// Function to handle login
function loginUser($username, $password) {
  // Connect to the database
  $conn = new mysqli($host, $username, $password, $database);

  // Check connection
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  // SQL query to retrieve user data
  $sql = "SELECT id, username, password FROM users WHERE username = '$username'";
  $result = $conn->query($sql);

  if ($result->num_rows > 0) {
    $user_data = $result->fetch_assoc();
    $hashed_password = $user_data['password']; // Get the stored password

    // Verify the password
    if (password_verify($password, $hashed_password)) {
      // Authentication successful
      $_SESSION['user_id'] = $user_data['id'];
      $_SESSION['username'] = $user_data['username'];
      return true;
    } else {
      // Incorrect password
      return false;
    }
  } else {
    // User not found
    return false;
  }

  // If we reach here, something went wrong (e.g., connection issue)
  $conn->close();
  return false;
}

// Example Usage (in your login form)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $username = $_POST["username"];
  $password = $_POST["password"];

  if (loginUser($username, $password)) {
    // Successful login - redirect to a protected page, etc.
    header("Location: /protected_page.php"); // Replace with your desired redirect URL
    exit();
  } else {
    // Login failed - display an error message
    echo "<p style='color:red;'>Invalid username or password.</p>";
  }
}
?>
