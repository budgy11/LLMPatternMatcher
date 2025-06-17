    Username: <input type="text" name="username" required><br><br>
    Password: <input type="password" name="password" required><br><br>
    <input type="submit" value="Login">
  </form>

  <br>
  <a href="register.php">Don't have an account? Register here</a>

</body>
</html>


<?php

// Database credentials (replace with your actual values)
$db_host = "localhost";
$db_name = "your_database_name";
$db_user = "your_db_user";
$db_pass = "your_db_password";

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

  // Bind the values (using prepared statements to prevent SQL injection)
  $stmt = $conn->prepare($sql);
  $stmt->bind_param("ss", $username, $password); // "ss" means two strings

  // Execute the query
  if ($stmt->execute() === TRUE) {
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();

    // Check if a user was found
    if ($row) {
      // User found!  You can do things like set a session
      $_SESSION['username'] = $row['username']; // Store username in session
      $_SESSION['user_id'] = $row['id']; // Store user ID
      return true;
    } else {
      return false;
    }
  } else {
    // Error executing query
    return false;
  }

  // Close the statement and connection
  $stmt->close();
  $conn->close();
}

// Example Usage:

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $username = $_POST["username"];
  $password = $_POST["password"];

  // Validate input (important!) - Add more validation as needed
  if (empty($username) || empty($password)) {
    echo "Username and password cannot be empty!";
  } else {
    // Call the loginUser function
    if (loginUser($username, $password)) {
      // Login successful - Redirect to a protected page or display a welcome message
      echo "Login successful!  You are now logged in.";
      // Redirect to your main page or protected content
      header("Location: /protected_page.php"); // Replace with your protected page
      exit;
    } else {
      echo "Invalid username or password.";
    }
  }
}
?>
