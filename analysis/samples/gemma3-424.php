

<?php

// Database credentials (replace with your actual values)
$host = "localhost";
$username = "your_username";
$password = "your_password";
$database = "your_database";

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Get the username and password from the form
  $username = $_POST["username"];
  $password = $_POST["password"];

  // Validate the input (important for security!)
  if (empty($username) || empty($password)) {
    $error = "Username and password must be filled.";
  } else {
    // Database connection
    $conn = new mysqli($host, $username, $password, $database);

    // Check for connection errors
    if ($conn->connect_error) {
      $error = "Connection failed: " . $conn->connect_error;
    } else {
      // SQL query for login
      $sql = "SELECT id, username, password FROM users WHERE username = '$username'";
      $result = $conn->query($sql);

      if ($result->num_rows == 0) {
        $error = "Invalid username or password.";
      } else {
        // If the query returns a result, it means the user exists
        $row = $result->fetch_assoc();
        // Password verification (VERY IMPORTANT - use password_hash and password_verify)
        if (password_verify($password, $row["password"])) {
          // Login successful! Set a session variable to store the user's ID
          $_SESSION["user_id"] = $row["id"];
          $error = "Login successful!";  // Or redirect to a success page
        } else {
          $error = "Invalid username or password.";
        }
      }
    }
  }
}

// Check if the user is logged in
if (isset($_SESSION["user_id"])) {
  // Redirect to a secure area or display a welcome message
  echo "<h1>Welcome, User!</h1>";
} else {
  // Display the login form
  echo "<h1>Login</h1>";
  echo "<form method='post' action='" . htmlspecialchars($_SERVER["PHP_SELF"]) . "'>";
  echo "<label for='username'>Username:</label><br>";
  echo "<input type='text' id='username' name='username' required><br><br>";
  echo "<label for='password'>Password:</label><br>";
  echo "<input type='password' id='password' name='password' required><br><br>";
  echo "<button type='submit'>Login</button>";
  echo "</form>";
  if (isset($error)) {
    echo "<p style='color: red;'>Error: " . $error . "</p>";
  }
}

?>
