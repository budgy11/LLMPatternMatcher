    <label for="username">Username:</label>
    <input type="text" id="username" name="username" placeholder="Enter your username">
    <br><br>
    <label for="password">Password:</label>
    <input type="password" id="password" name="password" placeholder="Enter your password">
    <br><br>
    <button type="submit">Login</button>
  </form>

</body>
</html>


<?php

// Database credentials (replace with your actual credentials)
$db_host = "localhost";
$db_name = "your_database_name";
$db_user = "your_database_user";
$db_password = "your_database_password";

// Function to handle user login
function loginUser($username, $password) {
  // Connect to the database
  $conn = new mysqli($db_host, $db_user, $db_password, $db_name);

  // Check connection
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  // SQL query to retrieve user credentials
  $sql = "SELECT id, username, password FROM users WHERE username = '$username'";
  $result = $conn->query($sql);

  if ($result->num_rows > 0) {
    // Fetch the user data
    $user = $result->fetch_assoc();

    // Verify the password
    if (password_verify($password, $user['password'])) {
      // Login successful
      $_SESSION['user_id'] = $user['id'];
      $_SESSION['username'] = $user['username'];

      return true; // Indicate success
    } else {
      // Incorrect password
      return false;
    }
  } else {
    // User not found
    return false;
  }

  $conn->close();
}

// Example usage:  (This is for demonstration - don't include this in your actual login form)
// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Get username and password from the form
  $username = $_POST["username"];
  $password = $_POST["password"];

  // Validate inputs (important for security!)
  if (empty($username) || empty($password)) {
      echo "Username and password cannot be empty.";
  }
  else{
    // Attempt to login
    if (loginUser($username, $password)) {
      // Login successful - redirect to a secure page or display a welcome message
      header("Location: welcome.php"); // Replace 'welcome.php' with your secure page
      exit();
    } else {
      // Login failed - display an error message
      echo "Incorrect username or password.";
    }
  }
}
?>
