    Username: <input type="text" name="username" required><br><br>
    Password: <input type="password" name="password" required><br><br>
    <input type="submit" value="Login">
  </form>

  <br>
  <a href="register.php">Don't have an account? Register here.</a>

</body>
</html>


<?php

// Database Configuration (Replace with your actual credentials)
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

  // SQL query to check the username and password
  $sql = "SELECT * FROM users WHERE username = '$username' AND password = '$password'";  //IMPORTANT: Escape user input properly.  Consider prepared statements for production.

  $result = $conn->query($sql);

  if ($result->num_rows > 0) {
    // User found, fetch the user data
    $user = $result->fetch_assoc();
    // Set session variables to store user information
    $_SESSION['user_id'] = $user['id']; // Assuming you have an 'id' column in your users table
    $_SESSION['username'] = $user['username'];
    // You can also fetch other user details here, like email, etc.

    return true; // Login successful
  } else {
    return false; // Login failed
  }

  $conn->close();
}


// Example usage (Illustrative - Not a complete form handler)
if (isset($_POST['username']) && isset($_POST['password'])) {
  $username = mysqli_real_escape_string($conn, $_POST['username']); // IMPORTANT: Escape user input
  $password = mysqli_real_escape_string($conn, $_POST['password']);  // IMPORTANT: Escape user input

  if (loginUser($username, $password)) {
    // Redirect to a protected page, or display a welcome message
    header("Location: protected_page.php"); // Example: Redirect to a protected page
    exit();
  } else {
    echo "Invalid username or password.";
  }
}
?>
