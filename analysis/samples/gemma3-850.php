
  <form method="post" action="login.php">
    Username: <input type="text" name="username" required>
    Password: <input type="password" name="password" required>
    <button type="submit">Login</button>
  </form>

</body>
</html>


<?php

// Database credentials (replace with your actual credentials)
$host = "localhost";
$username = "your_username";
$password = "your_password";
$database = "your_database";

// Form handling -  This is crucial to prevent vulnerabilities!
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Sanitize input -  VERY IMPORTANT to prevent SQL injection
  $username = filter_input(INPUT_POST, "username", FILTER_SANITIZE_STRING);
  $password = filter_input(INPUT_POST, "password", FILTER_SANITIZE_STRING);

  // Validate input -  Important for security and good user experience
  if (empty($username) || empty($password)) {
    $error = "Username and password are required.";
  } elseif (!filter_var($username, FILTER_VALIDATE_EMAIL)) { //  Check if the username is a valid email format (you can adjust this)
    $error = "Invalid username (must be a valid email address).";
  } else {
    // Database connection
    $conn = new mysqli($host, $username, $password, $database);

    // Check connection
    if ($conn->connect_error) {
      die("Connection failed: " . $conn->connect_error);
    }

    // Query -  Use prepared statements to prevent SQL injection
    $stmt = $conn->prepare("SELECT id, username, password FROM users WHERE username = ?");
    $stmt->bind_param("s", $username); // "s" indicates a string, assuming username is a string
    $stmt->execute();

    $result = $stmt->get_result();

    if ($result->num_rows == 0) {
      $error = "Invalid username or password.";
    } else {
      $user = $result->fetch_assoc();
      $id = $user['id'];
      $hashed_password = $user['password']; // Store the hashed password securely

      // Password verification -  HASHED comparison
      if (password_verify($password, $hashed_password)) {
        // Login successful -  Set session variables
        session_start();
        $_SESSION["user_id"] = $id;
        $_SESSION["username"] = $username;
        header("Location: /welcome.php"); // Redirect to a welcome page
        exit();
      } else {
        $error = "Invalid username or password.";
      }
    }
  }
}
?>
