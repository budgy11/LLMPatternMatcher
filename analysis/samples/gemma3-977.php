
<!DOCTYPE html>
<html>
<head>
  <title>Login</title>
</head>
<body>

  <h2>Login</h2>

  <form action="" method="post">
    <label for="username">Username:</label>
    <input type="text" id="username" name="username" required><br><br>

    <label for="password">Password:</label>
    <input type="password" id="password" name="password" required><br><br>

    <button type="submit">Login</button>
  </form>

</body>
</html>


   $password = password_hash($_POST["password"], PASSWORD_DEFAULT);
   $sql = "SELECT * FROM users WHERE username = '$username' AND password = '$password'";
   

* **Session Management:**
   - Consider using a more robust session management system (e.g., `session_start()` and `session_destroy()`) to handle session creation, destruction, and management securely.
   - **Set the `session.cookie_secure` option to 1 in your PHP configuration** to ensure that session cookies are only transmitted over HTTPS.
   - **Set an expiration time for your session.**

* **Input Validation:** Implement thorough input validation to prevent users from submitting malicious data.  Use regular expressions to enforce expected formats.

* **Error Handling:**  Add more robust error handling to gracefully handle database errors and other potential issues.  Don't display sensitive error messages to the user.

* **HTTPS:**  Always serve your website over HTTPS to encrypt communication between the user's browser and your server.  This protects sensitive information, including login credentials.

**Example with Prepared Statements (Recommended):**



<?php

// Database credentials (replace with your actual values)
$db_host = "localhost";
$db_name = "your_database_name";
$db_user = "your_db_user";
$db_pass = "your_db_password";

// Function to handle user login
function loginUser($username, $password) {
  // Connect to the database
  $conn = new mysqli($db_host, $db_user, $db_pass, $db_name);

  // Check connection
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  // SQL query to retrieve the password hash
  $sql = "SELECT password_hash, user_id FROM users WHERE username = ?";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param("s", $username); // "s" indicates a string parameter
  $stmt->execute();
  $result = $stmt->get_result();

  if ($result->num_rows == 0) {
    // User not found
    $stmt->close();
    return false;
  } else {
    $row = $result->fetch_assoc();
    $password_hash = $row['password_hash'];
    $user_id = $row['user_id'];

    // Verify password
    if (password_verify($password, $password_hash)) {
      // Password is correct, login successful
      // Set session variables (example)
      session_start();
      $_SESSION['user_id'] = $user_id;
      $_SESSION['username'] = $username;
      $stmt->close();
      return true;
    } else {
      // Password is incorrect
      $stmt->close();
      return false;
    }
  }
}


// Example usage (This should be in your login form's processing)

// Get username and password from the form
$username = $_POST['username'];
$password = $_POST['password'];

// Call the login function
if (loginUser($username, $password)) {
  // Login successful, redirect to a protected page or display a welcome message
  echo "Login successful!  Redirecting...";
  header("Location: /protected_page.php"); // Replace with your protected page URL
  exit();
} else {
  // Login failed, display an error message
  echo "Invalid username or password.";
}

?>
