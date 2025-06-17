    Username: <input type="text" name="username">
    Password: <input type="password" name="password">
    Confirm Password: <input type="password" name="confirm_password">
    <button type="submit">Register</button>
  </form>

  <a href="login.php">Already have an account? Login here.</a>

</body>
</html>


<?php

// Database credentials (replace with your actual credentials)
$host = "localhost";
$username = "your_username";
$password = "your_password";
$database = "your_database_name";

// Form data
$username = $_POST['username'];
$password = $_POST['password'];

// Connect to the database
$conn = new mysqli($host, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

// Prepare the SQL statement
$sql = "SELECT * FROM users WHERE username = ? AND password = ?";

// Bind parameters
$stmt = $conn->prepare($sql);
$stmt->bind_param("ss", $username, $password);

// Execute the statement
if ($stmt->execute()) {
  $result = $stmt->get_result();

  if ($result->num_rows == 1) {
    // User found - Fetch the user data
    $user = $result->fetch_assoc();
    // Set session variables
    $_SESSION['user_id'] = $user['id'];
    $_SESSION['username'] = $user['username'];

    // Redirect to a protected page (e.g., dashboard)
    header("Location: dashboard.php");
    exit();  // Important to stop further script execution
  } else {
    // User not found
    echo "Invalid username or password.";
  }
} else {
  // Something went wrong with the query
  echo "Query error: " . $conn->error;
}

// Close the statement and connection
$stmt->close();
$conn->close();

?>
