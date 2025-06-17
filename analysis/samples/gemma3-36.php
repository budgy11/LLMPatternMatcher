    Username: <input type="text" name="username" placeholder="Enter your username" required>
    <br><br>
    Password: <input type="password" name="password" required>
    <br><br>
    <button type="submit">Login</button>
  </form>

</body>
</html>


<?php

/**
 * User Login Function
 *
 * This function handles user login by verifying credentials against a database.
 *
 * @param string $username The user's username.
 * @param string $password The user's password.
 * @param PDO $pdo The PDO database connection object.
 * @return int|false User ID if login is successful, false otherwise.
 */
function loginUser(string $username, string $password, PDO $pdo) {
  // Sanitize and prepare the query
  $stmt = $pdo->prepare("SELECT id, password FROM users WHERE username = :username");
  $stmt->bindParam(':username', $username, PDO::PARAM_STR);

  // Execute the query
  $stmt->execute();

  // Fetch the result
  $user = $stmt->fetch(PDO::FETCH_ASSOC);

  // Check if a user was found
  if ($user) {
    // Verify the password
    if (password_verify($password, $user['password'])) {
      // User exists and password matches - return user ID
      return $user['id'];
    } else {
      // Password doesn't match
      return false;
    }
  } else {
    // User not found
    return false;
  }
}

// Example Usage:
// Assuming you have a database connection set up as $pdo

// In a real application, you would obtain the password from a form submission.
// For demonstration, let's use a hardcoded username and password.  **DO NOT DO THIS IN PRODUCTION!**
// $username = 'testuser';
// $password = 'password123';

// Create a dummy PDO connection for demonstration purposes.
// In a real application, use your actual database connection.
// Example connection details (replace with your actual details)
$host = 'localhost';
$dbname = 'your_database_name';
$username = 'your_db_username';
$password = 'your_db_password';

try {
  $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
  $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
  echo "Database connection failed: " . $e->getMessage();
  exit; // Stop execution if the connection fails
}


// Example login attempt
$username = 'testuser';
$password = 'password123';

if (loginUser($username, $password, $pdo) !== false) {
  echo "Login successful!  User ID: " . loginUser($username, $password, $pdo) . "
";
} else {
  echo "Login failed.  Incorrect username or password.
";
}


// Example of creating a user (for testing purposes - **DO NOT DO THIS IN PRODUCTION**)
function createUser(string $username, string $password, PDO $pdo) {
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    $stmt = $pdo->prepare("INSERT INTO users (username, password) VALUES (:username, :password)");
    $stmt->bindParam(':username', $username, PDO::PARAM_STR);
    $stmt->bindParam(':password', $hashedPassword, PDO::PARAM_STR);
    $stmt->execute();
    return true;
}

// Example Usage (creating the user)
// $success = createUser('testuser', 'password123', $pdo);
// if ($success) {
//   echo "User created successfully.
";
// } else {
//   echo "Failed to create user.
";
// }
?>
