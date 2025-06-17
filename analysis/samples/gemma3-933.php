    Username: <input type="text" name="username" placeholder="Username"><br><br>
    Password: <input type="password" name="password" placeholder="Password"><br><br>
    <input type="submit" value="Login">
  </form>

  <a href="register.php">Don't have an account? Register here.</a>

</body>
</html>


<?php

// Database configuration (replace with your actual credentials)
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

  // SQL query to retrieve the password hash
  $sql = "SELECT password_hash, salt FROM users WHERE username = '$username'";
  $result = $conn->query($sql);

  if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $password_hash = $row['password_hash'];
    $salt = $row['salt'];

    // Verify password
    if (password_verify($password, $password_hash . " " . $salt)) {
      // Login successful
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

  // Close the connection
  $conn->close();
}

// Example usage:
// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Get username and password from the form
  $username = $_POST["username"];
  $password = $_POST["password"];

  // Call the loginUser function
  if (loginUser($username, $password)) {
    // Redirect to a protected page
    header("Location: /protected_page.php"); // Replace with your protected page URL
    exit();
  } else {
    // Login failed
    $error = "Invalid username or password.";
  }
}
?>
