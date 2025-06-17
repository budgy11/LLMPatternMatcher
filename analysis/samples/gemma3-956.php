    Username: <input type="text" name="username" required>
    Password: <input type="password" name="password" required>
    <button type="submit">Login</button>
</form>


<?php

// Database configuration (Replace with your actual values)
$db_host = "localhost";
$db_name = "your_database_name";
$db_user = "your_username";
$db_pass = "your_password";

// Function to handle user login
function loginUser($username, $password) {
  // Connect to the database
  $conn = new mysqli($db_host, $db_user, $db_pass, $db_name);

  // Check connection
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  // Prepare and execute the query
  $query = "SELECT * FROM users WHERE username = ? AND password = ?";
  $stmt = $conn->prepare($query);
  $stmt->bind_param("ss", $username, $password); // "ss" means two strings
  $stmt->execute();

  // Get the result
  $result = $stmt->get_result();

  // Check if any rows were returned
  if ($result->num_rows > 0) {
    // User found, get user data
    $user = $result->fetch_assoc();
    // Authentication successful
    // You can store session variables or redirect to a secure area
    $_SESSION['username'] = $user['username']; //  Store username in session
    $_SESSION['user_id'] = $user['id']; // Assuming you have an 'id' column in users table
    return true;
  } else {
    // User not found
    return false;
  }

  // Close the statement and connection
  $stmt->close();
  $conn->close();
}

// Example usage:
// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $username = $_POST["username"];
  $password = $_POST["password"];

  if (loginUser($username, $password)) {
    // Login successful
    echo "Login successful! You are now logged in.";
    // Redirect to a protected page or dashboard
    header("Location: dashboard.php"); // Replace dashboard.php with your desired page
    exit();
  } else {
    // Login failed
    echo "Invalid username or password.";
  }
}

?>
