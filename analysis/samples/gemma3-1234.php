    Username: <input type="text" name="username" required><br><br>
    Password: <input type="password" name="password" required><br><br>
    <input type="submit" value="Login">
  </form>

  <p>If you don't have an account, <a href="register.php">register</a>.</p>

</body>
</html>


<?php

// Database configuration (replace with your actual credentials)
$db_host = "localhost";
$db_name = "your_database_name";
$db_user = "your_username";
$db_pass = "your_password";

// Function to handle user login
function loginUser($username, $password) {
  // Connect to the database
  $conn = new mysqli($db_host, $db_user, $db_pass, $db_name);

  // Check connection
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  // SQL query to check credentials
  $sql = "SELECT * FROM users WHERE username = '$username' AND password = '$password'";
  $result = $conn->query($sql);

  if ($result->num_rows > 0) {
    // User found - store the user's data in a session
    $user_data = $result->fetch_assoc();
    $_SESSION['user_id'] = $user_data['id']; // Assuming 'id' is the primary key
    $_SESSION['username'] = $user_data['username'];
    return true; // Login successful
  } else {
    return false; // Login failed
  }

  $conn->close();
}


// Example usage:  This is for demonstration - don't directly use this in your web app.
//  This shows how to call the function and handle the result.

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $username = $_POST["username"];
  $password = $_POST["password"];

  if (loginUser($username, $password)) {
    echo "Login successful!  You are now logged in.";
    // Redirect to a secure page or display a welcome message.
    header("Location: /welcome.php"); // Replace with your secure page URL.  Important to redirect.
    exit();
  } else {
    echo "Invalid username or password.";
  }
}
?>
