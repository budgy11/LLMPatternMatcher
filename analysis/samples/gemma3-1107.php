    Username: <input type="text" name="username" required><br><br>
    Password: <input type="password" name="password" required><br><br>
    <button type="submit">Login</button>
  </form>

  <br><br>
  <a href="register.php">Don't have an account? Register here</a>

</body>
</html>


<?php

// Database configuration (Replace with your actual credentials)
$db_host = "localhost";
$db_name = "your_database_name";
$db_user = "your_db_user";
$db_password = "your_db_password";

// Function to authenticate user credentials
function loginUser($username, $password) {
  // Connect to the database
  $conn = new mysqli($db_host, $db_user, $db_password, $db_name);

  // Check connection
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  // Prepare the SQL query
  $query = "SELECT id, username, password FROM users WHERE username = ?";
  $stmt = $conn->prepare($query);

  if ($stmt === false) {
    die("Error preparing statement: " . $conn->error);
  }

  // Bind the parameters
  $stmt->bind_param("s", $username);  // "s" indicates a string parameter

  // Execute the query
  if (!$stmt->execute()) {
    die("Error executing query: " . $stmt->error);
  }

  // Get the result
  $result = $stmt->get_result();

  if ($result->num_rows == 0) {
    $stmt->close();
    return false; // User not found
  }

  $user = $result->fetch_assoc();
  $stmt->close();

  // Verify password
  if (password_verify($password, $user['password'])) {
    // Successful login
    session_start();
    $_SESSION['user_id'] = $user['id'];
    $_SESSION['username'] = $user['username'];
    return true;
  } else {
    // Incorrect password
    return false;
  }
}

// Example usage (in a form submission, for example)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $username = $_POST["username"];
  $password = $_POST["password"];

  if (loginUser($username, $password)) {
    // Login successful - Redirect to a protected page or display a welcome message
    header("Location: /protected_page.php"); // Replace with the URL of your protected page
    exit();
  } else {
    // Login failed - Display an error message
    echo "<p style='color: red;'>Invalid username or password.</p>";
  }
}

?>
