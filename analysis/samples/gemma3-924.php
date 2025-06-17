    Username: <input type="text" name="username" placeholder="Enter your username"><br><br>
    Password: <input type="password" name="password"><br><br>
    <input type="submit" value="Login">
  </form>

</body>
</html>


<?php

// Database connection details - REPLACE WITH YOUR ACTUAL VALUES
$host = "localhost";
$username = "your_username";
$password = "your_password";
$database = "your_database";

// Function to handle user login
function loginUser($username, $password) {
  // Connect to the database
  $conn = new mysqli($host, $username, $password, $database);

  // Check connection
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  // SQL query to check login
  $sql = "SELECT * FROM users WHERE username = '$username' AND password = '$password'";
  $result = $conn->query($sql);

  if ($result->num_rows > 0) {
    // User found, set session variables
    $user = $result->fetch_assoc();
    $_SESSION['user_id'] = $user['id']; // Assuming 'id' is a user ID column
    $_SESSION['username'] = $user['username'];
    return true; // Login successful
  } else {
    return false; // Login failed
  }

  $conn->close();
}


// Example Usage (Handling login form submission)

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Get username and password from form
  $username = $_POST["username"];
  $password = $_POST["password"];

  // Validate input (Important for security!)
  if (empty($username) || empty($password)) {
    $error = "Username and Password must be filled.";
  } else {
    // Call the loginUser function
    $loginResult = loginUser($username, $password);

    if ($loginResult) {
      // Redirect to a protected page or display a welcome message
      header("Location: protected_page.php"); // Redirect to a protected page
      exit();
    } else {
      $error = "Invalid username or password.";
    }
  }
}

// Display the login form
?>
