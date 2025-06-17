

<?php
// ... (previous code) ...

/**
 * User Login Function (using Prepared Statements)
 *
 * @param string $username The username provided by the user.
 * @param string $password The password provided by the user.
 * @return bool True if login is successful, false otherwise.
 */
function loginUser($username, $password) {
  // Database connection details (Replace with your actual credentials)
  $dbHost = "localhost";
  $dbUsername = "your_db_username";
  $dbPassword = "your_db_password";
  $dbName = "your_db_name";

  // Connect to the database
  $conn = new mysqli($dbHost, $dbUsername, $dbPassword, $dbName);

  // Check connection
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  // Prepare the SQL statement
  $sql = "SELECT id, username, password FROM users WHERE username = ?";
  $stmt = $conn->prepare($sql);

  // Bind the parameters
  $stmt->bind_param("s", $username);  // "s" indicates a string parameter

  // Execute the statement
  if ($stmt->execute()) {
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
      $row = $result->fetch_assoc();

      // Verify the password
      if (password_verify($password, $row['password'])) {
        // Login successful
        $_SESSION['username'] = $username; // Store username in session
        $_SESSION['id'] = $row['id']; // Store user ID in session
        return true;
      } else {
        // Password mismatch
        return false;
      }
    } else {
      return false; // No rows returned (user not found)
    }

  } else {
    // Error executing the statement
    die("Error executing query: " . $conn->error);
  }

  // Close the connection
  $conn->close();
  $stmt->close(); // Important: Close the statement
}

// ... (rest of the code remains the same) ...
?>
