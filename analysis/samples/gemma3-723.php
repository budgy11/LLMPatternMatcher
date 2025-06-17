
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
    <input type="submit" value="Login">
  </form>

</body>
</html>


function loginUser($username, $password) {
  // Connect to the database
  $conn = new mysqli($db_host, $db_user, $db_pass, $db_name);

  // Check connection
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  // SQL query with placeholders
  $sql = "SELECT * FROM users WHERE username = ? AND password = ?";

  // Prepare the statement
  $stmt = $conn->prepare($sql);

  // Bind the parameters
  $stmt->bind_param("ss", $username, $password); // "ss" indicates two string parameters

  // Execute the query
  $result = $stmt->execute();

  if ($result) {
    $row = $stmt->fetch_assoc();
    if ($row) {
      // User found - Set session variables to indicate login
      session_start();
      $_SESSION['username'] = $username;
      return true;
    }
  }

  // Close the connection
  $conn->close();
  return false;
}


<?php
session_start();

/**
 * Handles user login.
 *
 * @param string $username The user's username.
 * @param string $password The user's password.
 * @return bool True if login was successful, false otherwise.
 */
function loginUser($username, $password) {
  // In a real application, you'd likely store passwords hashed, 
  // not plain text.  This example is for demonstration only.

  // Check username and password against stored values.
  // Replace these with your actual database query.
  $storedUsers = [
    ['username' => 'john.doe', 'password' => 'securePassword123'],
    ['username' => 'jane.smith', 'password' => 'anotherSecurePass'],
  ];

  foreach ($storedUsers as $user) {
    if ($user['username'] === $username && password_verify($password, $user['password'])) {
      // Authentication successful.  Set session variables.
      $_SESSION['username'] = $username;
      return true;
    }
  }

  return false;
}

/**
 * Handles user logout.
 */
function logoutUser() {
  unset($_SESSION['username']);
  // Optionally, destroy the session completely:
  // session_destroy();
}


// --- Example Usage (Illustrative) ---
// You'll typically get the username and password from a form.

//  Example form handling (Simplified)
//  if ($_SERVER["REQUEST_METHOD"] == "POST") {
//    $username = $_POST["username"];
//    $password = $_POST["password"];
//    if (loginUser($username, $password)) {
//      echo "Login successful!";
//      // Redirect to a protected page or display a welcome message.
//    } else {
//      echo "Invalid username or password.";
//    }
//  }
?>
