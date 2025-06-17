    Username: <input type="text" name="username" required>
    Password: <input type="password" name="password" required>
    <button type="submit">Login</button>
  </form>

  <p>Don't have an account? <a href="register.php">Register</a></p>  <!-- Link to registration page -->

</body>
</html>


<?php

// Database connection details (Replace with your actual credentials)
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

  // SQL query to select the user
  $sql = "SELECT * FROM users WHERE username = '$username' AND password = '$password'";
  $result = $conn->query($sql);

  if ($result->num_rows > 0) {
    // User found, create a session
    session_start();

    // Get the user data
    $user = $result->fetch_assoc();

    // Store user data in session for later access
    $_SESSION['user_id'] = $user['id'];
    $_SESSION['username'] = $user['username'];
    $_SESSION['role'] = $user['role']; // Assuming you have a role column in your table

    // Redirect to a secure page or display a success message
    header("Location: secure_page.php"); // Replace with your secured page
    exit(); // Stop further execution of the script
  } else {
    // User not found
    return false;
  }

  $conn->close();
}

// Example usage (handling login form submission)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Get form data
  $username = $_POST["username"];
  $password = $_POST["password"];

  // Validate input (important for security)
  if (empty($username) || empty($password)) {
    echo "Username and password cannot be empty.";
  } else {
    // Call the login function
    loginUser($username, $password);
  }
}

?>
