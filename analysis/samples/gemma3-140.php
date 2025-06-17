    Username: <input type="text" name="username" placeholder="your_email@example.com" required>
    <br><br>
    Password: <input type="password" name="password" required>
    <br><br>
    <button type="submit">Login</button>
  </form>

</body>
</html>


<?php

// Database connection details (replace with your actual credentials)
$host = "localhost";
$username = "your_db_username";
$password = "your_db_password";
$database = "your_db_name";

// Function to handle user login
function loginUser($username, $password) {
  // Connect to the database
  $conn = new mysqli($host, $username, $password, $database);

  // Check connection
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  // SQL query to retrieve user information
  $sql = "SELECT id, username, password FROM users WHERE username = '$username'";
  $result = $conn->query($sql);

  if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();

    // Verify password
    if (password_verify($password, $row['password'])) {
      // Login successful
      $_SESSION['loggedin'] = true;
      $_SESSION['id'] = $row['id'];
      $_SESSION['username'] = $row['username'];

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
// Check if the user is logged in
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $username = $_POST["username"];
  $password = $_POST["password"];

  if (loginUser($username, $password)) {
    // Redirect to a secure page or display a welcome message
    header("Location: secure_page.php"); // Replace with your secure page
    exit();
  } else {
    $error = "Invalid username or password.";
  }
}
?>
