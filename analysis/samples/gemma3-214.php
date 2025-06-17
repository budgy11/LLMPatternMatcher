    Username: <input type="text" name="username" required><br><br>
    Password: <input type="password" name="password" required><br><br>
    <input type="submit" value="Login">
  </form>

</body>
</html>


<?php

// Database configuration (replace with your actual credentials)
$db_host = "localhost";
$db_name = "your_database_name";
$db_user = "your_database_user";
$db_password = "your_database_password";

// Session management
session_start();

// Function to check login credentials
function login($username, $password) {
  // Connect to the database
  $conn = new mysqli($db_host, $db_user, $db_password, $db_name);

  // Check connection
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  // SQL query to check the credentials
  $sql = "SELECT * FROM users WHERE username = ? AND password = ? "; // Use prepared statement to prevent SQL injection
  $stmt = $conn->prepare($sql);
  $stmt->bind_param("ss", $username, $password);  // "ss" indicates two string parameters
  $stmt->execute();

  $result = $stmt->get_result();

  if ($result->num_rows == 1) {
    $row = $result->fetch_assoc();

    // Set session variables
    $_SESSION['user_id'] = $row['id'];
    $_SESSION['username'] = $row['username'];

    return true; // Login successful
  } else {
    return false; // Login failed
  }

  $stmt->close();
  $conn->close();
}

// Example usage:

// 1.  Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $username = $_POST["username"];
  $password = $_POST["password"];

  // Validate input (important for security - add more validation as needed)
  if (empty($username) || empty($password)) {
    $error = "Username and password cannot be empty.";
  } else {
    $login_result = login($username, $password);

    if ($login_result) {
      // Redirect to a protected page
      header("Location: /protected_page.php"); // Replace with your protected page URL
      exit();
    } else {
      $error = "Invalid username or password.";
    }
  }
}

?>
