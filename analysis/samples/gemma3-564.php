

<?php

// Database connection details (replace with your actual credentials)
$db_host = "localhost";
$db_name = "your_database_name";
$db_user = "your_username";
$db_pass = "your_password";

/**
 * User Login Function
 *
 * @param string $username The username provided by the user.
 * @param string $password The password provided by the user.
 * @return int|false  User ID if login is successful, false otherwise.
 */
function loginUser($username, $password) {
  // Connect to the database
  $conn = new mysqli($db_host, $db_user, $db_pass, $db_name);

  // Check connection
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  // Prepare the SQL query
  $sql = "SELECT id, username FROM users WHERE username = ? AND password = ?";

  // Bind the parameters to prevent SQL injection
  $stmt = $conn->prepare($sql);
  $stmt->bind_param("ss", $username, $password);  // "ss" indicates two strings

  // Execute the query
  if ($stmt->execute() === TRUE) {
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
      $row = $result->fetch_assoc();
      $userId = $row['id'];
      return $userId; // Return the user ID if successful
    } else {
      return false; // User not found
    }
  } else {
    return false; // Query execution error
  }

  // Close the statement and connection
  $stmt->close();
  $conn->close();
}


// --- Example Usage (Illustrative - NOT for direct implementation) ---
//  This section demonstrates how the `loginUser` function would be called.
//  In a real application, this would be handled by a form submission.

/*
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $username = $_POST["username"];
  $password = $_POST["password"];

  $userId = loginUser($username, $password);

  if ($userId) {
    echo "Login successful! User ID: " . $userId;
    // Redirect to a secure page or display a welcome message
  } else {
    echo "Invalid username or password.";
  }
}
*/

?>
