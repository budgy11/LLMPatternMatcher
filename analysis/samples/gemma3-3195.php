    <label for="username">Username:</label>
    <input type="text" id="username" name="username" required><br><br>

    <label for="email">Email:</label>
    <input type="email" id="email" name="email" required><br><br>

    <label for="password">Password:</label>
    <input type="password" id="password" name="password" required><br><br>

    <input type="submit" value="Register">
  </form>

</body>
</html>


<?php

// Assuming you have a database connection established (e.g., using mysqli or PDO)
// Let's represent this with a placeholder:
// $db = new mysqli("localhost", "username", "password", "database_name");

// Check the connection
// if ($db->connect_error) {
//   die("Connection failed: " . $db->connect_error);
// }

// Function to register a new user
function registerUser($username, $password, $email) {
  // Sanitize and validate inputs (IMPORTANT for security)
  $username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_STRING);
  $password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING);
  $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);

  // Validate email format
  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    return "Invalid email format.";
  }

  // Hash the password (VERY IMPORTANT - never store passwords in plain text)
  $hashed_password = password_hash($password, PASSWORD_DEFAULT);

  // Database query (replace with your actual database connection and query)
  // Example using mysqli:
  $sql = "INSERT INTO users (username, password, email) VALUES (?, ?, ?)";
  $stmt = $db->prepare($sql);
  $stmt->bind_param("sss", $username, $hashed_password, $email);
  $stmt->execute();

  // Check if the query was successful
  if ($stmt) {
    // Success
    return "Registration successful! Please check your email to verify your account.";
  } else {
    // Error
    return "Registration failed. Please try again.";
  }

  // Close the statement
  $stmt->close();
}


// Example usage (This is just for demonstration, you'll likely use this in a form submission)
// Assuming you've received the data from a form
// $username = $_POST['username'];
// $password = $_POST['password'];
// $email = $_POST['email'];


// Call the registration function
// $registration_result = registerUser($username, $password, $email);
// echo $registration_result;

?>
