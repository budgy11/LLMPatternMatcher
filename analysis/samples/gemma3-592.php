

<?php

// Database credentials (replace with your actual credentials)
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
  $sql = "SELECT id, username, password FROM users WHERE username = ?";
  $stmt = $conn->prepare($sql);

  if ($stmt) {
    // Bind the parameters
    $stmt->bind_param("s", $username);

    // Execute the query
    if ($stmt->execute()) {
      // Get the result
      $result = $stmt->get_result();

      if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        // Verify the password
        if (password_verify($password, $row["password"])) {
          // Login successful
          session_start();  // Start the session to store user data
          $_SESSION["user_id"] = $row["id"];
          $_SESSION["username"] = $username;
          return true;
        } else {
          // Password mismatch
          return false;
        }
      } else {
        // No user found
        return false;
      }

      $stmt->close();
    } else {
      // Query execution failed
      return false;
    }
  } else {
    // Failed to prepare statement
    return false;
  }
}

// Example usage (for testing)
// Uncomment the following lines to test the function:
// $username = "testuser";
// $password = "password123";
//
// if (loginUser($username, $password)) {
//   echo "Login successful!";
// } else {
//   echo "Login failed.";
// }


?>
