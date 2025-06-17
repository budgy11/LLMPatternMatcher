
<!DOCTYPE html>
<html>
<head>
  <title>Login</title>
</head>
<body>

  <h2>Login</h2>

  <form method="post" action="">
    Username: <input type="text" name="username" required>
    Password: <input type="password" name="password" required>
    <button type="submit">Login</button>
  </form>

</body>
</html>


<?php

// Database credentials - **IMPORTANT:  Never hardcode these in a production environment!**
$db_host = "localhost";
$db_name = "your_database_name";
$db_user = "your_database_user";
$db_pass = "your_database_password";

/**
 * Logs in a user based on their username and password.
 *
 * @param string $username The username to log in.
 * @param string $password The password for the username.
 * @return int|false The user ID if login is successful, false otherwise.
 */
function loginUser(string $username, string $password) {
  // Connect to the database
  $conn = new mysqli($db_host, $db_user, $db_pass, $db_name);

  // Check connection
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  // Prepare and execute the SQL query
  $stmt = $conn->prepare("SELECT id FROM users WHERE username = ? AND password = ?");
  $stmt->bind_param("ss", $username, $password); // "ss" indicates two strings

  if ($stmt->execute() === false) {
    // Handle execution errors
    echo "Query execution failed: " . $stmt->error;
    $stmt->close();
    $conn->close();
    return false;
  }

  // Get the result
  $result = $stmt->get_result();

  if ($result->num_rows == 0) {
    // User not found
    $stmt->close();
    $conn->close();
    return false;
  }

  $user = $result->fetch_assoc();
  $userId = $user['id'];

  $stmt->close();
  $conn->close();

  return $userId;
}


// Example usage:

// Get username and password from form input (in a real application, validate this input!)
$username = $_POST['username'];
$password = $_POST['password'];

// Perform the login
$userId = loginUser($username, $password);

if ($userId) {
  // Login successful
  echo "Login successful! User ID: " . $userId;

  // You would typically redirect the user to a protected area of the website here.
} else {
  // Login failed
  echo "Login failed.  Please check your username and password.";
}

?>
