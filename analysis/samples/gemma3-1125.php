
<!DOCTYPE html>
<html>
<head>
  <title>Login Form</title>
</head>
<body>

  <h2>Login</h2>

  <form method="post" action="">
    <label for="username">Username:</label>
    <input type="text" id="username" name="username" required><br><br>

    <label for="password">Password:</label>
    <input type="password" id="password" name="password" required><br><br>

    <button type="submit">Login</button>
  </form>

</body>
</html>


<?php

// Database credentials (Replace with your actual values)
$db_host = "localhost";
$db_name = "your_database_name";
$db_user = "your_username";
$db_password = "your_password";

/**
 * Logs in a user based on their username and password.
 *
 * @param string $username The username.
 * @param string $password The password.
 * @return int|false User ID if successful, false if not.
 */
function loginUser(string $username, string $password) {
  // Connect to the database
  $conn = new mysqli($db_host, $db_user, $db_password, $db_name);

  // Check connection
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  // Prepare the SQL query
  $sql = "SELECT id, username FROM users WHERE username = ? AND password = ?;";

  // Use prepared statement to prevent SQL injection
  $stmt = $conn->prepare($sql);
  $stmt->bind_param("ss", $username, $password); // 'ss' indicates two string parameters

  // Execute the query
  if ($stmt->execute() === TRUE) {
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
      $user = $result->fetch_assoc();
      $userId = $user['id'];
      return $userId;
    } else {
      return false; // User not found
    }
  } else {
    // Handle query errors
    echo "Query failed: " . $conn->error;
    return false;
  }

  // Close the statement and connection
  $stmt->close();
  $conn->close();
}

// --- Example Usage ---
// Get username and password from the form (or wherever you get them)
$username = $_POST['username'];
$password = $_POST['password'];

// Check if the login form was submitted
if (isset($_POST['login'])) {
  // Call the login function
  $userId = loginUser($username, $password);

  // Check if the login was successful
  if ($userId) {
    // Redirect the user to a logged-in page, or set a session variable
    session_start();
    $_SESSION['userId'] = $userId;
    $_SESSION['username'] = $username;
    echo "Login successful! You are logged in as " . $username;
    // Redirect to a logged-in page
    header("Location: /loggedin.php"); // Replace with your logged-in page URL
    exit();
  } else {
    echo "Invalid username or password.";
  }
}
?>
