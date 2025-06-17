
  <form method="post" action="">
    Username: <input type="text" name="username">
    Password: <input type="password" name="password">
    <input type="submit" value="Login">
  </form>

</body>
</html>


<?php

// Database configuration (replace with your actual values)
$db_host = "localhost";
$db_name = "your_database_name";
$db_user = "your_db_user";
$db_password = "your_db_password";

// Function to authenticate the user
function loginUser($username, $password) {
  // 1. Connect to the database
  $conn = new mysqli($db_host, $db_user, $db_password, $db_name);

  // Check connection
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  // 2. Prepare the query
  $sql = "SELECT * FROM users WHERE username = ? AND password = ?";
  $stmt = $conn->prepare($sql);

  if ($stmt === false) {
    die("Error preparing statement: " . $conn->error);
  }

  // 3. Bind parameters
  $stmt->bind_param("ss", $username, $password);

  // 4. Execute the query
  if (!$stmt->execute()) {
    die("Error executing query: " . $stmt->error);
  }

  // 5. Get the result
  $result = $stmt->get_result();

  // 6. Check if a user was found
  if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    // 7.  Create a session
    session_start();  // Start the session
    $_SESSION['user_id'] = $row['id']; // Store user ID in session
    $_SESSION['username'] = $username; // Store username in session (optional)

    //  You might also want to store other user data in the session.

    // 8.  Success!  Return true
    return true;
  } else {
    // 9. User not found
    return false;
  }

  // 10. Close the statement
  $stmt->close();

  // 11. Close the connection (not always necessary, but good practice)
  $conn->close();
}


// Example usage (handling the login form)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $username = $_POST["username"];
  $password = $_POST["password"];

  if (loginUser($username, $password)) {
    // User successfully logged in
    header("Location: welcome.php"); // Redirect to a welcome page
    exit();
  } else {
    // User login failed
    $error = "Invalid username or password.";
  }
}
?>
