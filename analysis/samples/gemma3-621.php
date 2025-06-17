    Username: <input type="text" name="username" required><br><br>
    Password: <input type="password" name="password" required><br><br>
    <input type="submit" value="Login">
</form>

</body>
</html>


<?php

// Database credentials (Replace with your actual credentials)
$db_host = 'localhost';
$db_username = 'your_username';
$db_password = 'your_password';
$db_name = 'your_database_name';

// Function to handle user login
function loginUser($username, $password) {
  // Connect to the database
  $conn = new mysqli($db_host, $db_username, $db_password, $db_name);

  // Check connection
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  // Prepare the SQL query
  $sql = "SELECT * FROM users WHERE username = ? AND password = ?";

  // Use prepared statement to prevent SQL injection
  $stmt = $conn->prepare($sql);

  if ($stmt) {
    // Bind parameters
    $stmt->bind_param("ss", $username, $password);

    // Execute the query
    if ($stmt->execute()) {
      // Get the result
      $result = $stmt->get_result();

      if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        // User found, set session variables
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        return true; // Login successful
      } else {
        return false; // User not found
      }
    } else {
      return false; // Query execution error
    }
  } else {
    return false; // Prepare statement error
  }

  // Close the statement and connection
  $stmt->close();
  $conn->close();
}

// Example Usage (Demonstration - Replace with actual form handling)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $username = $_POST["username"];
  $password = $_POST["password"];

  if (loginUser($username, $password)) {
    // Login successful, redirect to a protected page or display a welcome message
    header("Location: /protected_page.php"); // Replace with your protected page URL
    exit();
  } else {
    // Login failed, display an error message
    echo "<p style='color:red;'>Invalid username or password.</p>";
  }
}

?>
