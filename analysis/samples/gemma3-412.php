
  <form method="post" action="">
    Username: <input type="text" name="username" />
    Password: <input type="password" name="password" />
    <input type="submit" value="Login" />
  </form>

</body>
</html>


<?php

// Database credentials (Replace with your actual credentials)
$db_host = "localhost";
$db_name = "your_database_name";
$db_user = "your_database_user";
$db_password = "your_database_password";

/**
 * User login function
 *
 * @param string $username
 * @param string $password
 * @return int|false  Returns user ID on success, false on failure
 */
function user_login(string $username, string $password) {
  // Connect to the database
  $conn = new mysqli($db_host, $db_user, $db_password, $db_name);

  // Check connection
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  // Prepare the SQL query
  $sql = "SELECT id, username FROM users WHERE username = ? AND password = ? LIMIT 1";
  $stmt = $conn->prepare($sql);

  if ($stmt === false) {
    die("Error preparing statement: " . $conn->error);
    return false;
  }

  // Bind parameters
  $stmt->bind_param("ss", $username, $password);

  // Execute the query
  if (!$stmt->execute()) {
    die("Error executing query: " . $stmt->error);
    return false;
  }

  // Get the result
  $result = $stmt->get_result();

  // Check if a user was found
  if ($result->num_rows == 0) {
    $stmt->close();
    return false;
  }

  // Fetch the user data
  $user = $result->fetch_assoc();

  // Close the statement and result
  $stmt->close();
  $result->close();

  // Return the user ID
  return $user['id'];
}


// Example usage:
$username = $_POST['username']; // Get username from form
$password = $_POST['password'];  // Get password from form

if (isset($username) && isset($password)) {
  $user_id = user_login($username, $password);

  if ($user_id) {
    // User is logged in successfully
    echo "Welcome, User ID: " . $user_id . "<br>";
    // You can now redirect the user to a protected page or perform other actions.
  } else {
    // Login failed
    echo "Invalid username or password.<br>";
  }
}
?>
