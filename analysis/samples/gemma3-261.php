    <button type="submit">Login</button>
  </form>

  <p>New user? <a href="register.php">Register</a></p>  <!-- Add a registration link -->

</body>
</html>


<?php
// Database Credentials (Replace with your actual credentials)
$db_host = "localhost";
$db_name = "your_database_name";
$db_user = "your_username";
$db_password = "your_password";

// Function to handle user login
function loginUser($username, $password) {
  // Connect to the database
  $conn = new mysqli($db_host, $db_user, $db_password, $db_name);

  // Check connection
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  // Prepare and execute the query
  $query = "SELECT id, username, password FROM users WHERE username = ? AND password = ?;";
  $stmt = $conn->prepare($query);
  $stmt->bind_param("ss", $username, $password); // "ss" indicates two strings
  $stmt->execute();

  // Get the result
  $result = $stmt->get_result();

  // Check if the query returned any rows
  if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
    // Session setup
    session_start();
    $_SESSION['user_id'] = $user['id'];
    $_SESSION['username'] = $user['username'];

    // Successful login
    return true;
  } else {
    // Login failed
    return false;
  }

  // Close the statement and connection
  $stmt->close();
  $conn->close();
}

// Example usage (for demonstration - in a real application, this would be in a form submission)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $username = $_POST["username"];
  $password = $_POST["password"];

  if (loginUser($username, $password)) {
    // Login successful, redirect to a welcome page or perform other actions
    header("Location: welcome.php"); // Replace welcome.php with your welcome page
    exit();
  } else {
    // Login failed
    $error = "Invalid username or password.";
  }
}
?>
