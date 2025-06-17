

<?php

// Database credentials (replace with your actual details)
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

  // Prepare the SQL query
  $sql = "SELECT * FROM users WHERE username = ? AND password = ?";

  // Prepare the statement (important for security - prevents SQL injection)
  $stmt = $conn->prepare($sql);

  if ($stmt) {
    // Bind the parameters
    $stmt->bind_param("ss", $username, $password);

    // Execute the query
    if ($stmt->execute()) {
      // Fetch the result
      $result = $stmt->fetch_assoc();

      // Check if the query returned any results
      if ($result) {
        // User found, set session variables
        session_start();
        $_SESSION['user_id'] = $result['id']; // Assuming you have an 'id' column in your users table
        $_SESSION['username'] = $username;
        return true; // Login successful
      } else {
        return false; // User not found
      }
    } else {
      // Error executing query
      return false;
    }
  } else {
    // Error preparing the statement
    return false;
  }

  // Close the statement and connection
  $stmt->close();
  $conn->close();
}

// Example usage (in your login form)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $username = $_POST["username"];
  $password = $_POST["password"];

  if (loginUser($username, $password)) {
    // Login successful, redirect to a secure page
    header("Location: /secure_page.php"); // Replace with your desired secure page
    exit();
  } else {
    // Login failed, display an error message
    echo "Invalid username or password.";
  }
}

?>
