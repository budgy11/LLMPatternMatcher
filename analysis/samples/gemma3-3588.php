    <label for="username">Username:</label>
    <input type="text" id="username" name="username" required><br><br>

    <label for="password">Password:</label>
    <input type="password" id="password" name="password" required><br><br>

    <label for="email">Email:</label>
    <input type="email" id="email" name="email" required><br><br>

    <button type="submit">Register</button>
</form>

</body>
</html>


<?php

// Assuming you have a database connection established (e.g., $db)
// and you're using a suitable database system (MySQL, PostgreSQL, etc.)

/**
 * Registers a new user.
 *
 * @param string $username  The desired username.
 * @param string $password  The user's password.
 * @param string $email   The user's email address.
 * @param string $db        The database connection object.
 *
 * @return bool True on successful registration, false otherwise.
 */
function registerUser($username, $password, $email, $db) {
  // **Sanitization and Validation - CRUCIAL for Security**
  $username = filter_var($username, FILTER_SANITIZE_STRING); // Remove potentially harmful characters
  $email = filter_var($email, FILTER_SANITIZE_EMAIL);       // Validate and sanitize email

  // Validate username -  Minimum length and allowed characters
  if (strlen($username) < 3 || !preg_match('/^[a-zA-Z0-9._-]+$/', $username)) {
    return false; // Invalid username
  }

  // Validate email - Check format and maybe against an existing list (more advanced)
  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    return false; // Invalid email format
  }


  // Hash the password -  Never store passwords in plain text!
  $hashed_password = password_hash($password, PASSWORD_DEFAULT);


  // Prepare the SQL query
  $sql = "INSERT INTO users (username, password, email) VALUES (?, ?, ?)";

  // Prepare the statement
  $stmt = $db->prepare($sql);

  // Bind the parameters
  $stmt->bind_param("ss", $username, $hashed_password, $email);

  // Execute the query
  if ($stmt->execute()) {
    // Success!  You can return a success message or redirect.
    return true;
  } else {
    // Handle errors -  Important for debugging
    error_log("Error registering user: " . $stmt->error); // Log the error
    return false;
  }

  // Always close the statement
  $stmt->close();
}


// Example Usage (Demonstration)
// In a real application, this would be in a form submission handler

// Simulate a database connection (replace with your actual connection)
// $db = new mysqli("localhost", "username", "password", "database_name");

//if ($db->connect_error) {
//    die("Connection failed: " . $db->connect_error);
// }



//  Example form submission (simulated)
//$username = $_POST['username'];
//$password = $_POST['password'];
//$email = $_POST['email'];

//if (registerUser($username, $password, $email, $db)) {
//  echo "User registered successfully!";
//} else {
//  echo "Registration failed. Please try again.";
//}

?>
