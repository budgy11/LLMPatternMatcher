        Username: <input type="text" name="username" required>
        Password: <input type="password" name="password" required>
        <button type="submit">Login</button>
    </form>

    <p>Don't have an account? <a href="register.php">Register</a></p>

</body>
</html>


<?php

// Database Configuration (Replace with your actual credentials)
$db_host = "localhost";
$db_name = "your_database_name";
$db_user = "your_db_user";
$db_pass = "your_db_password";

// Function to authenticate the user
function loginUser($username, $password) {
  // 1. Connect to the Database
  $conn = new mysqli($db_host, $db_user, $db_pass, $db_name);

  // Check connection
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  // 2. SQL Query to Retrieve User
  $query = "SELECT id, username, password FROM users WHERE username = ?";
  $stmt = $conn->prepare($query);
  $stmt->bind_param("s", $username); // "s" indicates a string parameter

  if ($stmt->execute() === false) {
    // Handle execution error
    echo "Query execution failed: " . $conn->error;
    $stmt->close();
    $conn->close();
    return false;
  }

  // 3. Get Result
  $result = $stmt->get_result();

  // 4. Check if User Found
  if ($result->num_rows == 0) {
    // User not found
    $stmt->close();
    $conn->close();
    return false;
  }

  // 5. Get User Data
  $user = $result->fetch_assoc(); // Fetch as an associative array
  $stmt->close();
  $conn->close();

  // 6. Verify Password (Using Password Hashing - VERY IMPORTANT!)
  if (password_verify($password, $user['password'])) {
    // Password correct!
    return $user['id']; // Return the user ID if authentication is successful
  } else {
    // Incorrect password
    return false;
  }
}

// Example Usage (Handling Login Form Submission)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $username = $_POST["username"];
  $password = $_POST["password"];

  if (empty($username) || empty($password)) {
    echo "Username and password fields are required.";
  } else {
    $userId = loginUser($username, $password);

    if ($userId) {
      // Login Successful
      session_start(); // Start the session
      $_SESSION['userId'] = $userId;  // Store the user ID in the session
      echo "Login successful! You are now logged in.";
      // Redirect to a protected page or the homepage
      header("Location: /protected_page.php"); // Replace with your desired URL
      exit();
    } else {
      // Login Failed
      echo "Invalid username or password.";
    }
  }
}
?>
