
  <form action="login.php" method="post">
    Username: <input type="text" name="username">
    Password: <input type="password" name="password">
    <button type="submit">Login</button>
  </form>

  <p>Don't have an account? <a href="register.php">Register here</a></p> <!-- Replace register.php with your registration page -->

</body>
</html>


<?php

// Database connection details - REPLACE WITH YOUR ACTUAL CREDENTIALS
$db_host = "localhost";
$db_name = "your_database_name";
$db_user = "your_database_user";
$db_password = "your_database_password";

// Function to handle user login
function loginUser($username, $password) {
  // 1. Database connection
  $conn = new mysqli($db_host, $db_user, $db_password, $db_name);

  // Check connection
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  // 2. SQL Query
  $sql = "SELECT * FROM users WHERE username = ? AND password = ?"; 
  // Use prepared statements to prevent SQL injection
  $stmt = $conn->prepare($sql); 
  $stmt->bind_param("ss", $username, $password); // "ss" means two strings

  // 3. Execute the query
  if ($stmt->execute() === TRUE) {
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
      $row = $result->fetch_assoc();
      // 4.  Authentication successful - Now you can use the user's data
      $_SESSION['user_id'] = $row['id']; // Store user ID in session
      $_SESSION['username'] = $username;

      return true; // Login successful
    } else {
      return false; // No user found or incorrect password
    }
  } else {
    // Query error
    return false;
  }

  // 5. Close connection
  $stmt->close();
  $conn->close();
}

// Example Usage (in your HTML form)

// 1. Form Input Handling (Example - in your HTML form)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $username = $_POST["username"];
  $password = $_POST["password"];

  // 2. Validate Input (Important - always validate user input!)
  if (empty($username) || empty($password)) {
    $error = "Username and password are required!";
  } else {
    // 3. Call the loginUser function
    $login_result = loginUser($username, $password);

    if ($login_result) {
      // 4. Successful Login - Redirect to a protected page
      header("Location: protected_page.php"); // Replace with your protected page
      exit();
    } else {
      $error = "Invalid username or password.";
    }
  }
}
?>
