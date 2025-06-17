    Username: <input type="text" name="username" <?php if (isset($error_message)) echo 'style="border-color: red;"'; ?> />
    <br />
    Password: <input type="password" name="password" />
    <br />
    <button type="submit">Login</button>
  </form>

  <br />
  <a href="register.php">Don't have an account? Register here.</a>

</body>
</html>


<?php
session_start();

/**
 * User Login Function
 *
 * This function handles user login using a simple username/password validation.
 *
 * @param string $username The username provided by the user.
 * @param string $password The password provided by the user.
 * @return bool True if login is successful, false otherwise.
 */
function loginUser($username, $password) {
  // **Important Security Note:**  This is a simplified example.
  // NEVER store passwords in plain text in a real-world application.
  // Always hash and salt passwords before storing them in the database.

  // Simulate a user database (replace with your actual database connection)
  $users = [
    ['username' => 'john.doe', 'password' => 'securePassword123'],
    ['username' => 'jane.smith', 'password' => 'anotherPassword'],
  ];

  // Find the user in the database
  foreach ($users as $user) {
    if ($user['username'] == $username && password_verify($password, $user['password'])) {  // Use password_verify for secure comparison
      // Login successful
      $_SESSION['username'] = $username;
      return true;
    }
  }

  // Login failed
  return false;
}

/**
 * Logout Function (clears session variables)
 */
function logoutUser() {
  unset($_SESSION['username']);
  session_unset();  // Remove all session variables
  session_destroy(); // Destroy the session
}

// **Example Usage (This is just for demonstration.  You'll integrate this into your application) **

// Check if the form has been submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $username = $_POST['username'];
  $password = $_POST['password'];

  if (loginUser($username, $password)) {
    // Login successful - redirect to a secure page or display a welcome message
    echo '<p>Login successful! Welcome, ' . $username . '</p>';
    // Redirect (replace with your desired redirect)
    // header("Location: /welcome.php");
    // exit();

  } else {
    // Login failed - display an error message
    echo '<p style="color: red;">Invalid username or password.</p>';
  }
}
?>
