    Username: <input type="text" name="username" required><br><br>
    Password: <input type="password" name="password" required><br><br>
    <input type="submit" value="Login">
  </form>

  <p>Don't have an account? <a href="register.php">Register</a></p> <!-- Replace with your registration page -->

</body>
</html>


<?php

// Database connection details (Replace with your actual credentials)
$db_host = "localhost";
$db_name = "your_database_name";
$db_user = "your_username";
$db_password = "your_password";

/**
 * User Login Function
 *
 * This function checks if a user's login credentials are valid.
 *
 * @param string $username The username provided by the user.
 * @param string $password The password provided by the user.
 * @return bool True if login is successful, false otherwise.
 */
function loginUser($username, $password) {
  // Connect to the database
  $conn = new mysqli($db_host, $db_user, $db_password, $db_name);

  // Check connection
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  // Prepare the SQL query
  $sql = "SELECT * FROM users WHERE username = ? AND password = ? ";

  // Prepare the statement (Important for security - prevents SQL injection)
  $stmt = $conn->prepare($sql);

  if ($stmt === false) {
      die("Error preparing statement: " . $conn->error);
  }

  // Bind parameters
  $stmt->bind_param("ss", $username, $password);  // "ss" means two string parameters

  // Execute the statement
  if ($stmt->execute() === false) {
    die("Error executing query: " . $stmt->error);
  }

  // Get the result
  $result = $stmt->get_result();

  // Check if any rows were returned
  if ($result->num_rows > 0) {
    // User found - verify credentials and set session
    $row = $result->fetch_assoc();
    // Verify password (you can use a more secure hashing method here)
    if (password_verify($password, $row['password'])) {
      // Login successful - set session variables
      session_start();
      $_SESSION['user_id'] = $row['id'];
      $_SESSION['username'] = $username;
      $stmt->close();
      $conn->close();
      return true;
    } else {
      $stmt->close();
      $conn->close();
      return false; // Password mismatch
    }
  } else {
    $stmt->close();
    $conn->close();
    return false; // User not found
  }
}

// --- Example Usage (This would typically be in a separate form handling script) ---
/*
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $username = $_POST["username"];
  $password = $_POST["password"];

  if (loginUser($username, $password)) {
    header("Location: welcome.php"); // Redirect to a welcome page
    exit();
  } else {
    echo "Invalid username or password.";
  }
}
*/

?>
