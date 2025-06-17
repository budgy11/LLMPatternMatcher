

<?php

// Database connection details - Replace with your actual values
$db_host = "localhost";
$db_name = "your_database_name";
$db_user = "your_database_username";
$db_password = "your_database_password";

// Function to handle user login
function loginUser($username, $password) {
  // 1. Connect to the database
  $conn = new mysqli($db_host, $db_user, $db_password, $db_name);

  // Check connection
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  // 2. Prepare and execute the query
  $query = "SELECT * FROM users WHERE username = '" . $conn->real_escape_string($username) . "' AND password = '" . $conn->real_escape_string($password) . "'";
  $result = $conn->query($query);

  // 3. Check if the query returned any results
  if ($result->num_rows > 0) {
    // User found, so return their data (or user ID, etc.)
    $user_data = $result->fetch_assoc();
    // You can also store a session variable here for authentication:
    // $_SESSION['user_id'] = $user_data['id']; // Assuming 'id' is the user ID column
    return $user_data; // Return user data for further processing
  } else {
    // User not found
    return false;
  }

  // 4. Close the connection
  $conn->close();
}

// Example Usage:  (This would typically be in your login form's processing code)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $username = $_POST["username"];
  $password = $_POST["password"];

  // Validate input (Important for security - discussed below)
  if (empty($username) || empty($password)) {
    echo "Username and Password cannot be empty!";
  } else {
    $login_result = loginUser($username, $password);

    if ($login_result) {
      // User logged in successfully
      // Redirect to a protected page or display a welcome message
      // echo "Welcome, " . $login_result['username']; // Display username
      // $_SESSION['user_id'] = $login_result['id']; // Store user ID in session
      header("Location: welcome.php"); // Redirect to welcome page
      exit();
    } else {
      // User login failed
      echo "Invalid username or password.";
    }
  }
}

?>
