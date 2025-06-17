
<form method="post" action="login.php">
    Username: <input type="text" name="username" required><br><br>
    Password: <input type="password" name="password" required><br><br>
    <input type="submit" value="Login">
</form>

</body>
</html>


<?php

// Database credentials (replace with your actual credentials)
$db_host = "localhost";
$db_name = "your_database_name";
$db_user = "your_database_user";
$db_password = "your_database_password";

// Function to validate the login attempt
function login($username, $password) {
  // 1. Connect to the database
  $conn = new mysqli($db_host, $db_user, $db_password, $db_name);

  // Check connection
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  // 2. Prepare the SQL query
  $sql = "SELECT id, username, password FROM users WHERE username = ?";
  $stmt = $conn->prepare($sql);

  if ($stmt === false) {
    die("Error preparing statement: " . $conn->error);
  }

  // 3. Bind the parameters
  $stmt->bind_param("s", $username); // "s" indicates a string parameter

  // 4. Execute the query
  if (!$stmt->execute()) {
    die("Error executing query: " . $stmt->error);
  }

  // 5. Get the result
  $result = $stmt->get_result();

  // 6. Check if any rows were returned
  if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $hashed_password = $row['password'];  // Get the hashed password

    // 7. Verify the password
    if (password_verify($password, $hashed_password)) {
      // Password is correct - Set session variables
      session_start();
      $_SESSION['user_id'] = $row['id'];
      $_SESSION['username'] = $row['username'];

      // Redirect the user
      return true;
    } else {
      // Password doesn't match
      $stmt->close();
      return false;
    }
  } else {
    // No user found
    $stmt->close();
    return false;
  }
}

// Example usage:
if (isset($_POST['username']) && isset($_POST['password'])) {
  $username = $_POST['username'];
  $password = $_POST['password'];

  if (login($username, $password)) {
    echo "Login successful! You are now logged in.";
    // Redirect to a protected page or do something else here.
    header("Location: /protected_page.php"); // Example redirection
    exit();
  } else {
    echo "Invalid username or password.";
  }
}

?>
