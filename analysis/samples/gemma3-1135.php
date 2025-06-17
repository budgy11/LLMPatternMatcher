    Username: <input type="text" name="username" />
    Password: <input type="password" name="password" />
    <button type="submit">Login</button>
  </form>

  <p>Don't have an account? <a href="register.php">Register</a></p>

</body>
</html>


<?php
session_start();

/**
 * Logs in a user based on username and password.
 *
 * @param string $username The user's username.
 * @param string $password The user's password.
 * @return bool True if login successful, false otherwise.
 */
function loginUser(string $username, string $password) {
  // Simulate a database or user store.  In a real application,
  // this would connect to a database to retrieve user information.
  $users = [
    'john.doe' => 'password123',
    'jane.smith' => 'securePass',
  ];

  // Check if the username exists
  if (isset($users[$username])) {
    // Verify the password
    if (password_verify($password, $users[$username])) {
      // Login successful
      $_SESSION['username'] = $username;  // Store username in session
      return true;
    } else {
      // Password does not match
      return false;
    }
  } else {
    // Username does not exist
    return false;
  }
}

/**
 *  Demonstration/Testing Function
 *  This function will show the login form and process the login.
 */
function showLogin() {
    // Start the session
    session_start();

    //Check if user is already logged in
    if (isset($_SESSION['username'])) {
        header("Location: /success.php"); // Redirect to a success page
        exit();
    }

    echo "<!DOCTYPE html>";
    echo "<html lang='en'>";
    echo "<head>";
    echo "<meta charset='UTF-8'>";
    echo "<title>Login</title>";
    echo "</head>";
    echo "<body>";
    echo "<h1>Login</h1>";

    // Check if there's an error message
    if (isset($_SESSION['error'])) {
        echo "<p style='color: red;'>" . htmlspecialchars($_SESSION['error']) . "</p>";
        unset($_SESSION['error']); // Clear error message after displaying
    }

    echo "<form method='post' action='" . htmlspecialchars($_SERVER['PHP_SELF']) . "'>";
    echo "<label for='username'>Username:</label><br>";
    echo "<input type='text' id='username' name='username' required><br><br>";
    echo "<label for='password'>Password:</label><br>";
    echo "<input type='password' id='password' name='password' required><br><br>";
    echo "<button type='submit'>Login</button>";
    echo "</form>";
}


//Example Usage (Demonstration)
if (isset($_POST['username']) && isset($_POST['password'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    if (loginUser($username, $password)) {
        echo "<p>Login successful!</p>";
        // Redirect to a protected page or homepage
        header("Location: /home.php");
        exit();
    } else {
        // Handle login failure
        $_SESSION['error'] = "Invalid username or password.";
        showLogin();
    }
} else {
    // Show the login form initially
    showLogin();
}
?>
