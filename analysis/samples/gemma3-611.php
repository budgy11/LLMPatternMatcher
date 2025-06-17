    Username: <input type="text" name="username" required>
    Password: <input type="password" name="password" required>
    <button type="submit">Login</button>
  </form>

  <p>Don't have an account? <a href="register.php">Register here</a></p>  <!-- Link to registration page -->

</body>
</html>


<?php

// Database connection details (Replace with your actual credentials)
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

  // SQL query to retrieve the user based on username
  $sql = "SELECT * FROM users WHERE username = '$username'";
  $result = $conn->query($sql);

  if ($result->num_rows > 0) {
    // Fetch the user data
    $user = $result->fetch_assoc();

    // Verify the password
    if (password_verify($password, $user['password'])) {
      // Login successful!
      // Create a session to store the user's information
      session_start();
      $_SESSION['user_id'] = $user['id'];
      $_SESSION['username'] = $user['username'];
      // You can store more user data in the session if needed

      return true; // Indicate successful login
    } else {
      return false; // Indicate incorrect password
    }
  } else {
    return false; // Indicate user not found
  }

  $conn->close();
}

// Example usage (Demonstration - Replace with form input from your HTML)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $username = $_POST["username"];
  $password = $_POST["password"];

  if (loginUser($username, $password)) {
    // Redirect to a secure page or display a success message
    header("Location: secure_page.php"); // Replace with your secure page
    exit();
  } else {
    // Handle login failure (e.g., display an error message)
    echo "<p>Invalid username or password.</p>";
  }
}
?>
