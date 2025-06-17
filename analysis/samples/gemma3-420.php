    Username: <input type="text" name="username" required><br><br>
    Password: <input type="password" name="password" required><br><br>
    <input type="submit" value="Login">
  </form>

</body>
</html>


<?php

// Database connection details (replace with your actual values)
$servername = "localhost";
$username = "your_username";
$password = "your_password";
$dbname = "your_database";

// Function to handle user login
function loginUser($username, $password) {
  // Create connection
  $conn = new mysqli($servername, $username, $password, $dbname);

  // Check connection
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  // SQL query to retrieve user information
  $sql = "SELECT id, username, password FROM users WHERE username = '$username'";
  $result = $conn->query($sql);

  if ($result->num_rows > 0) {
    // User found, verify password
    $row = $result->fetch_assoc();
    if (password_verify($password, $row['password'])) { // Use password_verify for secure password comparison
      // Login successful
      session_start(); // Start the session
      $_SESSION['user_id'] = $row['id'];
      $_SESSION['username'] = $username;
      return true;
    } else {
      // Incorrect password
      return false;
    }
  } else {
    // User not found
    return false;
  }

  // Close connection
  $conn->close();
}

// Example usage:
if (isset($_POST['username']) && isset($_POST['password'])) {
  $username = $_POST['username'];
  $password = $_POST['password'];

  if (loginUser($username, $password)) {
    // Login successful - redirect to a protected page or display a welcome message
    header("Location: protected_page.php"); // Redirect to a protected page
    exit(); // Stop further execution
  } else {
    // Login failed - display an error message
    echo "<p style='color:red;'>Invalid username or password.</p>";
  }
}

?>
