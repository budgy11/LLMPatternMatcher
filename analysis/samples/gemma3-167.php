    Username: <input type="text" name="username" placeholder="Enter your username"><br><br>
    Password: <input type="password" name="password" placeholder="Enter your password"><br><br>
    <input type="submit" value="Login">
  </form>

  <p>Don't have an account? <a href="register.php">Register</a></p>

</body>
</html>


<?php

/**
 * User Login Function
 *
 * This function handles user login, checks credentials against a database.
 *
 * @param string $username The user's username.
 * @param string $password The user's password.
 * @param PDO $pdo  A PDO object for database connection.
 * @return int|false  User ID if login is successful, false otherwise.
 */
function loginUser(string $username, string $password, PDO $pdo) {
  // 1. Sanitize and Validate Inputs
  $username = trim($username); // Remove leading/trailing whitespace
  $password = trim($password);

  if (empty($username) || empty($password)) {
    return false; // Username and password cannot be empty
  }

  // 2. Fetch the password hash from the database
  $stmt = $pdo->prepare("SELECT password_hash, user_id FROM users WHERE username = :username");
  $stmt->bindParam(':username', $username, PDO::PARAM_STR);
  $stmt->execute();

  $user = $stmt->fetch(PDO::FETCH_ASSOC);

  // 3. Check if User Exists and Password Matches
  if ($user && $user['password_hash'] === $password) {
    // Password is correct, return user ID
    return $user['user_id'];
  }

  // 4. Authentication Failed
  return false;
}


/**
 * Example Usage (Illustrative -  Requires database setup and table)
 */

// 1. Database Connection (Replace with your actual credentials)
try {
    $host = 'localhost';
    $dbname = 'your_database_name';
    $user = 'your_username';
    $password = 'your_password';

    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // For better error handling
    // Example PDO connection string (adjust for your setup):
    // $dsn = "mysql:host=$host;dbname=$dbname;charset=utf8";
    // $options = [
    //     PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    //     PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    //     PDO::ATTR_EMULATE_PREPARES => false, // Important for security
    // ];
    // $pdo = new PDO($dsn, 'your_username', 'your_password', $options);


} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}


// 2.  Example Usage
$username = 'testuser';
$password = 'password123';

$userId = loginUser($username, $password, $pdo);

if ($userId) {
  echo "Login successful! User ID: " . $userId . "<br>";
  // Do something with the user ID, like redirecting to their profile page
} else {
  echo "Login failed.  Invalid username or password.<br>";
}
?>
