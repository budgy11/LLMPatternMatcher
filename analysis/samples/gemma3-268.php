

<?php

// Database credentials (Replace with your actual credentials)
$db_host = "localhost";
$db_name = "your_database_name";
$db_user = "your_database_user";
$db_password = "your_database_password";

/**
 * Logs in a user based on username and password.
 *
 * @param string $username The username to authenticate.
 * @param string $password The password.
 * @return int|false User ID if successful, false otherwise.
 */
function login(string $username, string $password) {
  // Connect to the database
  $conn = new mysqli($db_host, $db_user, $db_password, $db_name);

  // Check connection
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  // Prepare the SQL query
  $sql = "SELECT id FROM users WHERE username = ? AND password = ?;";

  // Use prepared statement to prevent SQL injection
  $stmt = $conn->prepare($sql);
  $stmt->bind_param("ss", $username, $password); // "ss" indicates two string parameters

  // Execute the query
  if ($stmt->execute() === TRUE) {
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
      $row = $result->fetch_assoc();
      $user_id = $row["id"];
      return $user_id;
    } else {
      return false; // User not found
    }
  } else {
    // Handle query error
    echo "Query failed: " . $conn->error;
    return false;
  }

  // Close the statement and connection
  $stmt->close();
  $conn->close();
}


// Example Usage (for testing - don't use directly in your application)
// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Get username and password from the form
  $username = $_POST["username"];
  $password = $_POST["password"];

  // Validate input (important for security)
  if (empty($username) || empty($password)) {
    echo "Username and password cannot be empty.";
  } else {
    // Call the login function
    $user_id = login($username, $password);

    if ($user_id) {
      // User logged in successfully
      echo "User ID: " . $user_id . "<br>";
      // You can redirect the user to a secure area, etc.
    } else {
      // User login failed
      echo "Invalid username or password.";
    }
  }
}
?>
