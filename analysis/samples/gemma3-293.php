    Username: <input type="text" name="username" required>
    <br><br>
    Password: <input type="password" name="password" required>
    <br><br>
    <button type="submit">Login</button>
  </form>

  <br><br>
  <a href="register.php">Don't have an account? Register here.</a>

</body>
</html>


<?php

// Database configuration (Replace with your actual details)
$db_host = "localhost";
$db_name = "your_database_name";
$db_user = "your_database_user";
$db_password = "your_database_password";

// Function to handle user login
function loginUser($username, $password) {
  // 1. Connect to the database
  $conn = new mysqli($db_host, $db_user, $db_password, $db_name);

  // Check connection
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  // 2. Prepare the SQL query
  $sql = "SELECT * FROM users WHERE username = ? AND password = ?";
  $stmt = $conn->prepare($sql);

  // 3. Bind parameters (Important for security - prevents SQL injection)
  $stmt->bind_param("ss", $username, $password);

  // 4. Execute the query
  if ($stmt->execute() === TRUE) {
    // 5. Get the result
    $result = $stmt->get_result();

    // 6. Check if any rows were returned
    if ($result->num_rows > 0) {
      $row = $result->fetch_assoc();
      // 7.  Return user data if login is successful.  You can adapt this.
      return $row;
    } else {
      return false; // Login failed (username or password incorrect)
    }
  } else {
    // Query error
    return false;
  }

  // 8. Close the statement and connection
  $stmt->close();
  $conn->close();
}


// Example usage (This will only work if you have a 'users' table with 'username' and 'password' columns)
//  This section is for demonstration -  don't directly use this in a production environment.

// Simulate a login attempt
$username = "testuser";
$password = "password123";

$user = loginUser($username, $password);

if ($user) {
  echo "Login successful!";
  echo "<br>Username: " . $user['username'];
  echo "<br>Email: " . $user['email']; // Assuming you have an email column
} else {
  echo "Login failed.  Incorrect username or password.";
}

?>
