    Username: <input type="text" name="username">
    Password: <input type="password" name="password">
    <input type="submit" value="Login">
</form>

</body>
</html>


<?php

// Database credentials - **IMPORTANT: NEVER HARDCODE THIS IN A REAL APPLICATION!**
$db_host = 'localhost';
$db_name = 'your_database_name';
$db_user = 'your_username';
$db_password = 'your_password';

// --- Authentication Logic ---

// Function to check if the login is successful
function isLoggedIn($username, $password) {
  // Connect to the database
  $conn = new mysqli($db_host, $db_user, $db_password, $db_name);

  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  // Prepare and execute the query
  $query = "SELECT * FROM users WHERE username = ? AND password = ?";
  $stmt = $conn->prepare($query);
  $stmt->bind_param("ss", $username, $password);  // "ss" indicates two string parameters
  $stmt->execute();

  $result = $stmt->get_result();

  // Check if a user was found
  if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
    // Set session variables
    $_SESSION['user_id'] = $user['id']; // Assuming 'id' is a primary key in the users table
    $_SESSION['username'] = $user['username'];
    return true; // Login successful
  } else {
    return false; // Login failed
  }

  // Close the statement and connection
  $stmt->close();
  $conn->close();
}


// --- Login Form Handling ---

// Check if the login form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $username = $_POST["username"];
  $password = $_POST["password"];

  // Validate input (Basic example - improve this for production)
  if (empty($username) || empty($password)) {
    $error = "Username and password are required.";
  } else {
    // Attempt to login
    if (isLoggedIn($username, $password)) {
      // Redirect to a secure page
      header("Location: /secure_page.php"); // Replace with your secure page URL
      exit();
    } else {
      $error = "Invalid username or password.";
    }
  }
}

?>
