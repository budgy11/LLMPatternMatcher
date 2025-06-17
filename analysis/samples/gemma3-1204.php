

<?php

// Database connection details (replace with your actual credentials)
$servername = "localhost";
$username = "your_username";
$password = "your_password";
$database = "your_database";

// Function to handle user login
function loginUser($username, $password) {
  // Create connection
  $conn = new mysqli($servername, $username, $password, $database);

  // Check connection
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  // Prepare the SQL statement
  $sql = "SELECT id, username, password FROM users WHERE username = ? AND password = ?";
  $stmt = $conn->prepare($sql);

  if ($stmt === false) {
    die("Error preparing statement: " . $conn->error);
  }

  // Bind parameters
  $stmt->bind_param("ss", $username, $password);

  // Execute the statement
  if (!$stmt->execute()) {
    echo "Error: " . $stmt->error;
    return false;
  }

  // Get the result
  $result = $stmt->get_result();

  if ($result->num_rows == 0) {
    // No user found with those credentials
    return false;
  }

  // Fetch the user data
  $user = $result->fetch_assoc();

  // Close the statement and connection
  $stmt->close();
  $conn->close();

  // Successful login - return user data
  return $user;
}

// --- Example Usage ---

// Get username and password from form (example)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $username = $_POST["username"];
  $password = $_POST["password"];

  // Validate input (add more robust validation here - e.g., password strength, input length)
  if (empty($username) || empty($password)) {
    echo "<p>Username and password fields cannot be empty.</p>";
  } else {
    // Call the login function
    $loginResult = loginUser($username, $password);

    if ($loginResult) {
      // User is logged in - redirect or display user info
      echo "<h2>Welcome, " . $loginResult["username"] . "</h2>";
      // You can store the user ID in a session, cookie, or local storage for later use.
      // Example:
      // $_SESSION['user_id'] = $loginResult['id'];  // Requires session_start()
    } else {
      // Login failed
      echo "<p>Invalid username or password.</p>";
    }
  }
}
?>
