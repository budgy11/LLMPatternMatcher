    Username: <input type="text" name="username" required><br><br>
    Password: <input type="password" name="password" required><br><br>
    <input type="submit" value="Login">
  </form>

</body>
</html>


<?php

// Database connection details (replace with your actual credentials)
$db_host = "localhost";
$db_name = "your_database_name";
$db_user = "your_username";
$db_password = "your_password";

// Function to handle user login
function loginUser($username, $password) {
  // Connect to the database
  $conn = new mysqli($db_host, $db_user, $db_password, $db_name);

  // Check connection
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  // SQL query to check for user credentials
  $sql = "SELECT id, username, password FROM users WHERE username = '$username'";
  $result = $conn->query($sql);

  if ($result->num_rows > 0) {
    // User found, retrieve data
    $user = $result->fetch_assoc();
    $hashed_password = $user['password']; // Retrieve the password

    // Verify password (compare with hashed password)
    if (password_verify($password, $hashed_password)) {
      // Password is correct, set session variables
      session_start();
      $_SESSION['user_id'] = $user['id'];
      $_SESSION['username'] = $user['username'];
      return true; // Login successful
    } else {
      return false; // Incorrect password
    }
  } else {
    return false; // User not found
  }
}

// Example usage (this would be in your login form processing)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $username = $_POST["username"];
  $password = $_POST["password"];

  if (loginUser($username, $password)) {
    // Login successful - redirect or display a welcome message
    header("Location: welcome.php"); // Example: Redirect to a welcome page
    exit();
  } else {
    // Login failed - display an error message
    echo "<p style='color: red;'>Invalid username or password.</p>";
  }
}

?>
