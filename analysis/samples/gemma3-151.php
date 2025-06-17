    Username: <input type="text" name="username" required>
    Password: <input type="password" name="password" required>
    <button type="submit">Login</button>
  </form>

  <p>Don't have an account? <a href="register.php">Register here</a></p>
</body>
</html>


<?php

/**
 * User Login Function
 *
 * This function handles user login based on credentials.
 *
 * @param string $username The username entered by the user.
 * @param string $password The password entered by the user.
 * @return int|false $user_id if login is successful, or false if failed.
 */
function loginUser(string $username, string $password) {
  // **Important Security Note:**  Never store passwords directly in your code or database.
  // This example is for demonstration purposes *only*.
  // Always use a strong hashing algorithm like bcrypt or Argon2id.

  // Simulated User Database (Replace this with a real database connection)
  $users = [
    'john.doe' => 'password123',
    'jane.smith' => 'securePassword',
  ];

  // Check if the username exists in the database
  if (isset($users[$username])) {
    // Verify the password
    if (password_verify($password, $users[$username])) {  // Use password_verify
      // Login successful, return the user ID
      return $username; // Returning the username as the ID
      //  Alternatively, return a database user ID here.
    } else {
      // Incorrect password
      return false;
    }
  } else {
    // User not found
    return false;
  }
}

// **Example Usage**
// Check if the login form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $username = $_POST["username"];
  $password = $_POST["password"];

  // Validate input (VERY IMPORTANT - in a real application)
  if (empty($username) || empty($password)) {
    echo "Username and Password cannot be empty.";
  } else {
    $user_id = loginUser($username, $password);

    if ($user_id) {
      echo "Login successful! Welcome, " . $user_id . "!";
      // You would likely redirect the user here:
      // header("Location: /welcome.php?user=" . $user_id);
      // exit();
    } else {
      echo "Invalid username or password.";
    }
  }
}
?>
