        Username: <input type="text" name="username" required><br>
        Password: <input type="password" name="password" required><br><br>
        <input type="submit" value="Login">
    </form>

</body>
</html>


<?php

/**
 * User Login Function
 *
 * This function handles user login using a simple database (you'll need
 * to adapt this to your database setup).
 *
 * @param string $username The username provided by the user.
 * @param string $password The password provided by the user.
 * @return int|false  Returns the user ID if login is successful, 
 *                   false if login fails.
 */
function loginUser(string $username, string $password) {
  // Replace this with your actual database connection details.
  $db_host = 'localhost';
  $db_name = 'your_database_name';
  $db_user = 'your_database_user';
  $db_password = 'your_database_password';

  // Construct the database connection string.
  $dsn = "mysql:host=$db_host;dbname=$db_name;charset=utf8mb4";
  $options = []; // For PDO options, e.g., exception on error

  try {
    $pdo = new PDO($dsn, $db_user, $db_password, $options);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // Enable error handling
  } catch (PDOException $e) {
    // Handle database connection errors
    error_log("Database connection failed: " . $e->getMessage());
    return false;
  }


  // SQL query to check username and password
  $sql = "SELECT id FROM users WHERE username = ? AND password = ?";

  // Prepare the statement
  $stmt = $pdo->prepare($sql);

  // Bind parameters
  $stmt->bindParam(1, $username, PDO::PARAM_STR);
  $stmt->bindParam(2, $password, PDO::PARAM_STR);

  // Execute the query
  $stmt->execute();

  // Fetch the result
  $user = $stmt->fetch(PDO::FETCH_ASSOC);

  // Close the statement and connection
  $stmt = null;
  $pdo = null; // Close the connection

  // Check if a user was found and if the password matches.
  if ($user) {
    return $user['id']; // Return the user ID
  } else {
    return false; // Login failed
  }
}


// **Example Usage (For testing)**
// $username = "testuser";
// $password = "password123";

// $userId = loginUser($username, $password);

// if ($userId) {
//   echo "User logged in successfully. User ID: " . $userId . "
";
//   // Redirect to a secure page or perform other actions.
// } else {
//   echo "Invalid username or password.
";
// }


// **Important Security Considerations & Best Practices:**

// 1.  Never store passwords in plain text. Always hash passwords.
//     *  Use a strong hashing algorithm like bcrypt or Argon2.
//     *  PHP's `password_hash()` function is designed for this.
//     *  Don't use PHP's built-in `password_verify()` function directly in this function;  use the dedicated function for verification.

// 2.  Input Validation and Sanitization: Always validate and sanitize user input *before* using it in your database queries. This prevents SQL injection attacks.
//     *   Use prepared statements (as shown) - this is already implemented in the example.
//     *   Escape special characters if prepared statements are not used (not recommended for this example, but crucial in other scenarios).

// 3.  Error Handling:  The code includes basic error handling (catching PDO exceptions). Enhance this to provide more informative error messages and logging.

// 4.  Session Management:  After successful login, use sessions or cookies to track the user's logged-in state.

// 5.  Rate Limiting:  Implement rate limiting to prevent brute-force attacks.

// 6.  HTTPS:  Always use HTTPS to encrypt communication between the browser and your server.

// 7.  Regular Security Audits: Regularly review your code and database security configurations.

?>
