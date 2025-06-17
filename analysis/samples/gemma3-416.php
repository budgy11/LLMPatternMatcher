    Username: <input type="text" name="username" required>
    Password: <input type="password" name="password" required>
    <button type="submit">Login</button>
  </form>

</body>
</html>


<?php

// Database connection details (replace with your actual credentials)
$db_host = "localhost";
$db_name = "your_database_name";
$db_user = "your_database_user";
$db_pass = "your_database_password";

// Function to handle user login
function loginUser($username, $password) {
  // Establish database connection
  $conn = new mysqli($db_host, $db_user, $db_pass, $db_name);

  // Check connection
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  // SQL query to retrieve user credentials
  $sql = "SELECT id, username, password FROM users WHERE username = '$username'";

  $result = $conn->query($sql);

  if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();

    // Verify password
    if (password_verify($password, $row['password'])) {
      // Login successful
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


// Example usage (simulated form submission)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $username = $_POST["username"];
  $password = $_POST["password"];

  if (loginUser($username, $password)) {
    // Login successful
    header("Location: welcome.php"); // Redirect to a welcome page
    exit();
  } else {
    // Login failed
    echo "<p style='color:red;'>Invalid username or password.</p>";
  }
}

?>
