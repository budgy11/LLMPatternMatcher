    Username: <input type="text" name="username" required>
    Password: <input type="password" name="password" required>
    <button type="submit">Login</button>
  </form>

  <a href="register.php">Don't have an account? Register here.</a>

</body>
</html>


<?php

// Database credentials (replace with your actual credentials)
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

  // SQL query to retrieve the user's password
  $query = "SELECT password FROM users WHERE username = '$username'";
  $result = $conn->query($query);

  if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $stored_password = $row['password'];

    // Verify the password
    if (password_verify($password, $stored_password)) {
      // Authentication successful
      $_SESSION['username'] = $username;
      return true;
    } else {
      // Authentication failed
      return false;
    }
  } else {
    // User not found
    return false;
  }

  // Close the connection
  $conn->close();
}


// Example usage (from a form submission)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $username = $_POST["username"];
  $password = $_POST["password"];

  if (loginUser($username, $password)) {
    // Login successful - redirect to a protected page or display a success message
    header("Location: /protected_page.php"); // Replace with your protected page URL
    exit();

  } else {
    // Login failed - display an error message
    echo "<p style='color:red;'>Invalid username or password.</p>";
  }
}

?>
