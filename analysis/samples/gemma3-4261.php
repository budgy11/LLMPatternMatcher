
</body>
</html>


<?php

// Include the database connection file (assuming you have one)
require_once 'db_connection.php'; // Replace with your actual filename

/**
 * Registers a new user.
 *
 * @param string $username The desired username.
 * @param string $password The desired password.
 * @param string $email The user's email address.
 * @return bool True if registration was successful, false otherwise.
 */
function registerUser(string $username, string $password, string $email) {

  // Input validation - IMPORTANT!
  $username = trim($username);
  $password = trim($password);
  $email = trim($email);

  // Check if inputs are empty
  if (empty($username) || empty($password) || empty($email)) {
    return false;
  }

  // Sanitize inputs (very important to prevent SQL injection)
  $username = mysqli_real_escape_string($conn, $username);
  $password = mysqli_real_escape_string($conn, $password);
  $email = mysqli_real_escape_string($conn, $email);

  // Check if username already exists
  $check_query = "SELECT * FROM users WHERE username = '$username'";
  $result = mysqli_query($conn, $check_query);

  if (mysqli_num_rows($result) > 0) {
    return false; // Username already exists
  }

  // Hash the password - VERY IMPORTANT for security!
  $hashed_password = password_hash($password, PASSWORD_DEFAULT);

  // Insert the new user into the database
  $insert_query = "INSERT INTO users (username, password, email) VALUES ('$username', '$hashed_password', '$email')";

  if (mysqli_query($conn, $insert_query)) {
    return true; // Registration successful
  } else {
    // Handle errors - log them or display a general error message
    error_log("Error registering user: " . mysqli_error($conn)); // Log for debugging
    return false; // Registration failed
  }
}

// --- Example Usage (for testing - usually this would be handled by a form) ---
// If you're testing directly from the command line, you'll need to have a
// "users" table already created in your database.

// Example 1: Successful registration
if (registerUser("testuser", "password123", "test@example.com")) {
  echo "User registration successful!";
} else {
  echo "User registration failed.";
}

// Example 2: Registration with an existing username
if (registerUser("testuser", "anotherpassword", "new@email.com")) {
  echo "User registration successful!";
} else {
  echo "User registration failed (username already exists).";
}

?>
