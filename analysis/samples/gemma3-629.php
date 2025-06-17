
</body>
</html>


<?php

// ... (Database connection details as before)

// Function to handle user login using prepared statements
function loginUser($username, $password) {
  $conn = new mysqli($db_host, $db_user, $db_password, $db_name);

  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  // SQL query with placeholders
  $sql = "SELECT * FROM users WHERE username = ? AND password = ?";

  // Prepare the statement
  $stmt = $conn->prepare($sql);

  // Bind the parameters
  $stmt->bind_param("ss", $username, $password); // "ss" means two strings

  // Execute the statement
  $result = $stmt->execute();

  if ($result) {
    $user = $stmt->fetch_assoc();
    return $user;
  } else {
    return false;
  }

  $stmt->close();
  $conn->close();
}


<?php

// ... (Database connection details as before)

// Function to register a new user (includes password hashing)
function registerUser($username, $password, $db) {

  $hashedPassword = password_hash($password, PASSWORD_BCRYPT);  // Using bcrypt
  // or use: password_hash($password, PASSWORD_ARGON2)

  $sql = "INSERT INTO users (username, password) VALUES ('$username', '$hashedPassword')";
  if (!$db->query($sql)) {
    return false;
  }
  return true;
}

// Example usage:
// $success = registerUser("testuser", "MySecretPassword", $conn); // Use $conn instead of $db
?>
