    Username: <input type="text" name="username">
    Password: <input type="password" name="password">
    <button type="submit">Login</button>
</form>


<?php

// Database connection details (Replace with your actual credentials)
$servername = "localhost";
$username = "your_username";
$password = "your_password";
$dbname = "your_database";

// Function to handle user login
function loginUser($username, $password) {
  // Create connection
  $conn = new mysqli($servername, $username, $password, $dbname);

  // Check connection
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  // SQL query to retrieve the user's password
  $sql = "SELECT password FROM users WHERE username = '$username'";  // Use quotes around $username for security

  $result = $conn->query($sql);

  if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $stored_password = $row["password"];

    // Compare the entered password with the stored password (using password_verify)
    if (password_verify($password, $stored_password)) {
      // If passwords match, set a session variable to indicate login
      session_start();
      $_SESSION['username'] = $username;  // Store the username in the session
      return true; // Login successful
    } else {
      return false; // Passwords do not match
    }
  } else {
    return false; // User not found
  }

  $conn->close();
}

// Example usage:
// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $username = $_POST["username"];
  $password = $_POST["password"];

  if (loginUser($username, $password)) {
    // If login is successful, redirect to a secure page
    header("Location: secure_page.php");
    exit();
  } else {
    // If login fails, display an error message
    echo "<p style='color:red;'>Invalid username or password.</p>";
  }
}
?>
