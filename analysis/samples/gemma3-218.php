    Username: <input type="text" name="username" required><br><br>
    Password: <input type="password" name="password" required><br><br>
    <input type="submit" value="Login">
  </form>

  <br>
  <a href="register.php">Don't have an account? Register here.</a>

</body>
</html>


<?php

// Database configuration (replace with your actual credentials)
$db_host = "localhost";
$db_name = "your_database_name";
$db_user = "your_database_user";
$db_pass = "your_database_password";

// Function to check user credentials
function loginUser($username, $password) {
  // Connect to the database
  $conn = new mysqli($db_host, $db_user, $db_pass, $db_name);

  // Check connection
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  // Prepare the SQL query
  $sql = "SELECT * FROM users WHERE username = ? AND password = ?";

  // Prepare statement
  $stmt = $conn->prepare($sql);

  // Bind parameters (important for security)
  $stmt->bind_param("ss", $username, $password); // "ss" indicates two string parameters

  // Execute the statement
  if ($stmt->execute()) {
    // Get the result set
    $result = $stmt->get_result();

    // Check if any rows were returned
    if ($result->num_rows > 0) {
      $row = $result->fetch_assoc();
      // Session management (simplified for demonstration)
      session_start();
      $_SESSION['user_id'] = $row['id']; // Assuming 'id' is a column in your users table
      $_SESSION['username'] = $username;

      return true; // Login successful
    } else {
      return false; // No user found
    }
  } else {
    return false; // Error executing query
  }

  // Close the statement and connection
  $stmt->close();
  $conn->close();
}

// Example usage (handling login form submission)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $username = $_POST["username"];
  $password = $_POST["password"];

  if (loginUser($username, $password)) {
    header("Location: welcome.php"); // Redirect to a welcome page
    exit();
  } else {
    $error = "Invalid username or password.";
  }
}
?>
