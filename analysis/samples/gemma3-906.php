

<?php
session_start(); // Start the session

/**
 * Logs in a user.
 *
 * @param string $username The username.
 * @param string $password The password.
 * @return bool True if login successful, false otherwise.
 */
function login(string $username, string $password) {
  // Database credentials -  **IMPORTANT: NEVER HARDCODE REAL CREDENTIALS IN PRODUCTION!**
  $db_host = 'localhost';
  $db_name = 'your_database_name'; // Replace with your database name
  $db_user = 'your_db_user';     // Replace with your database username
  $db_password = 'your_db_password'; // Replace with your database password

  // Simulate a database query (replace with your actual database code)
  $conn = new mysqli($db_host, $db_user, $db_password, $db_name);

  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  // Sanitize and validate input (VERY IMPORTANT!)
  $username = trim($username); // Remove leading/trailing whitespace
  $password = trim($password);

  // Basic password validation (replace with a more robust hashing scheme)
  $hashed_password = "hashed_password_from_db"; // Simulate a hashed password.  In a real app, hash the password!
  if ($username === 'testuser' && $password === 'password') {
    // Login successful
    $_SESSION['username'] = $username;
    return true;
  } else {
    // Login failed
    return false;
  }

  $conn->close();
}

// Example usage (form handling - this is just a placeholder)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $username = $_POST["username"];
  $password = $_POST["password"];

  if (login($username, $password)) {
    // Redirect to a secure page
    header("Location: /secure_page.php"); // Replace with your secure page
    exit();
  } else {
    // Display an error message
    echo "<p style='color: red;'>Invalid username or password.</p>";
  }
}
?>
