
<!-- HTML Form for Login -->
<form method="post" action="">
  Username: <input type="text" name="login_username" required>
  Password: <input type="password" name="login_password" required>
  <button type="submit" name="login_button">Login</button>
</form>


<?php

// Database credentials (Replace with your actual credentials)
$db_host = "localhost";
$db_user = "your_db_user";
$db_password = "your_db_password";
$db_name = "your_db_name";

// Function to handle user login
function loginUser($username, $password) {
  // Connect to the database
  $conn = new mysqli($db_host, $db_user, $db_password, $db_name);

  // Check connection
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  // Prepare the SQL query
  $sql = "SELECT * FROM users WHERE username = '$username' AND password = '$password'";

  // Execute the query
  $result = $conn->query($sql);

  // Check if a user was found
  if ($result->num_rows > 0) {
    // User found, you can now process their session or redirect them to a protected area
    // Example:  Store user data in a session
    session_start();
    $user_data = $result->fetch_assoc(); // Fetch the user data as an associative array
    $_SESSION['user_id'] = $user_data['id']; // Assuming 'id' is the user ID column
    $_SESSION['username'] = $user_data['username'];

    // Redirect to a protected page (e.g., the dashboard)
    header("Location: dashboard.php"); // Replace dashboard.php with your protected page
    exit(); // Important to stop further execution

  } else {
    // User not found
    echo "<p>Invalid username or password.</p>";
  }

  $conn->close();
}

// Example usage (Handle the login form submission)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Get username and password from the form
  $username = $_POST["username"];
  $password = $_POST["password"];

  // Validate the input (Important for security!)
  if (empty($username) || empty($password)) {
    echo "<p>Username and password fields cannot be empty.</p>";
  } else {
    // Call the loginUser function
    loginUser($username, $password);
  }
}

?>
