    <label for="username">Username:</label>
    <input type="text" id="username" name="username" required><br><br>

    <label for="password">Password:</label>
    <input type="password" id="password" name="password" required><br><br>

    <button type="submit">Login</button>
  </form>

</body>
</html>


<?php

// Database credentials (replace with your actual values)
$db_host = "localhost";
$db_name = "your_database_name";
$db_user = "your_username";
$db_password = "your_password";

// Session handling (for storing user login status)
session_start();

/**
 * Logs in a user based on username and password.
 *
 * @param string $username The user's username.
 * @param string $password The user's password.
 * @return bool True if login is successful, false otherwise.
 */
function loginUser(string $username, string $password) {
  // Connect to the database
  $conn = new mysqli($db_host, $db_user, $db_password, $db_name);

  // Check connection
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  // Prepare and execute the query
  $stmt = $conn->prepare("SELECT id, username, password FROM users WHERE username = ?");
  $stmt->bind_param("s", $username); // "s" indicates a string parameter
  $stmt->execute();

  // Get the result
  $result = $stmt->get_result();

  if ($result->num_rows == 0) {
    // User not found
    $stmt->close();
    return false;
  } else {
    // User found, verify password
    $user = $result->fetch_assoc();
    if (password_verify($password, $user['password'])) {
      // Password is correct, log in the user
      $_SESSION['user_id'] = $user['id'];
      $_SESSION['username'] = $username;

      // Optionally, redirect the user
      header("Location: user_dashboard.php"); // Replace with your dashboard page
      exit();

      $stmt->close();
      return true;
    } else {
      // Incorrect password
      $stmt->close();
      return false;
    }
  }
}


/**
 * Registers a new user. (Simplified - In a real application, you'd sanitize, validate, and hash the password)
 */
function registerUser(string $username, string $password) {
    // In a real application, you'd hash the password
    // For demonstration purposes, we'll use password_hash directly

    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Connect to the database
    $conn = new mysqli($db_host, $db_user, $db_password, $db_name);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Prepare and execute the query
    $stmt = $conn->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
    $stmt->bind_param("ss", $username, $hashed_password);
    $stmt->execute();

    if ($stmt->affected_rows === 0) {
        // Insertion failed (e.g., username already exists)
        $stmt->close();
        return false;
    }

    $stmt->close();
    return true;
}



// Example usage (handling login form submission)

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $password = $_POST["password"];

    if (loginUser($username, $password)) {
        // Login successful
        echo "Login successful!  Redirecting...";
    } else {
        // Login failed
        echo "Invalid username or password.";
    }
}

?>
