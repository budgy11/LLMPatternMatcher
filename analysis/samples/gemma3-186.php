

function loginUser(string $username, string $password) {
  // ... (previous code) ...

  if (isset($users[$username])) {
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT); // Hash the password
    if ($hashedPassword === $users[$username]) {
      return $username;
    } else {
      return "Incorrect password for " . $username;
    }
  } else {
    return "User " . $username . " not found.";
  }
}


<?php

// Database credentials (replace with your actual credentials)
$host = "localhost";
$username = "your_username";
$password = "your_password";
$database = "your_database";

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

  // Get username and password from the form
  $username = $_POST["username"];
  $password = $_POST["password"];

  // Validate input (important for security - sanitize and validate)
  $username = filter_var($username, FILTER_SANITIZE_STRING); // Sanitize and remove leading/trailing whitespace
  $password = filter_var($password, FILTER_SANITIZE_STRING);

  // Check if username and password are empty
  if (empty($username) || empty($password)) {
    $error = "Username and password must be filled.";
  } elseif (!filter_var($username, FILTER_VALIDATE_EMAIL)) {
    $error = "Invalid email format";
  } else {
    // Database connection
    $conn = new mysqli($host, $username, $password, $database);

    // Check connection
    if ($conn->connect_error) {
      die("Connection failed: " . $conn->connect_error);
    }

    // SQL query
    $sql = "SELECT * FROM users WHERE username = ? AND password = ?"; // Use parameterized query to prevent SQL injection

    // Prepare statement
    $stmt = $conn->prepare($sql);

    // Bind parameters
    $stmt->bind_param("ss", $username, $password);

    // Execute statement
    $stmt->execute();

    // Get result
    $result = $stmt->get_result();

    // Check if any rows were returned
    if ($result->num_rows > 0) {
      // User found
      session_start(); // Start the session if not already started
      $_SESSION["username"] = $username;  // Store username in session
      echo "Login successful!";
      header("Location: welcome.php"); // Redirect to welcome page
      exit(); // Terminate the script
    } else {
      $error = "Invalid username or password.";
    }
  }
}
?>
