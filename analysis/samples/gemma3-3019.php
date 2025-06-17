    <label for="username">Username:</label>
    <input type="text" id="username" name="username" required><br><br>

    <label for="email">Email:</label>
    <input type="email" id="email" name="email" required><br><br>

    <label for="password">Password:</label>
    <input type="password" id="password" name="password" required><br><br>

    <button type="submit">Register</button>
  </form>

</body>
</html>


<?php

// Assuming you have a database connection established (e.g., using mysqli)
// For demonstration purposes, let's assume a database connection is available
// and we have variables $db_host, $db_user, $db_pass, $db_name

// Example Database Connection (replace with your actual details)
$db_host = "localhost";
$db_user = "your_username";
$db_pass = "your_password";
$db_name = "your_database";

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

  // Sanitize and validate the input
  $username = filter_input(INPUT_POST, "username", FILTER_SANITIZE_STRING);
  $email = filter_input(INPUT_POST, "email", FILTER_SANITIZE_EMAIL);
  $password = filter_input(INPUT_POST, "password", FILTER_SANITIZE_STRING);

  // Validate email format
  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $email_error = "Invalid email address.";
  }

  // Hash the password (Important for security)
  $hashed_password = password_hash($password, PASSWORD_DEFAULT);

  // Check if username is empty
  if (empty($username)) {
    $username_error = "Username cannot be empty.";
  }

  // Check if password is empty
  if (empty($password)) {
    $password_error = "Password cannot be empty.";
  }


  // Check if username already exists (optional - depends on your requirements)
  $sql = "SELECT * FROM users WHERE username = ?";
  $stmt = $db->prepare($sql); // Assuming you have a $db object
  $stmt->bind_param("s", $username);
  $stmt->execute();
  $result = $stmt->get_result();

  if ($result->num_rows > 0) {
    $username_error = "Username already exists.";
  }


  // If validation passes, insert the user into the database
  if (empty($username_error) && empty($email_error) && empty($password_error)) {
    // Prepare the SQL query
    $sql = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";
    $stmt = $db->prepare($sql);

    // Bind the values
    $stmt->bind_param("ss", $username, $email, $hashed_password);

    // Execute the query
    if ($stmt->execute()) {
      // User registration successful
      echo "Registration successful! Please log in.";
    } else {
      // Something went wrong
      echo "Registration failed. Error: " . $stmt->error;
    }

    // Close the statement and connection
    $stmt->close();
    // $db->close(); // Close connection only if you're sure you're done with it.
  }
}
?>
