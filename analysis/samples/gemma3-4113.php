    <label for="username">Username:</label>
    <input type="text" id="username" name="username" required><br><br>

    <label for="email">Email:</label>
    <input type="email" id="email" name="email" required><br><br>

    <label for="password">Password:</label>
    <input type="password" id="password" name="password" required><br><br>

    <label for="confirm_password">Confirm Password:</label>
    <input type="password" id="confirm_password" name="confirm_password" required><br><br>

    <input type="submit" value="Register">
</form>

</body>
</html>


<?php

// Database connection details (replace with your actual credentials)
$host = "localhost";
$username = "your_username";
$password = "your_password";
$database = "your_database";

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Validate the form data (IMPORTANT - Add more validation as needed)
  $username = trim($_POST["username"]);
  $email = trim($_POST["email"]);
  $password = trim($_POST["password"]);

  // Check if required fields are empty
  if (empty($username) || empty($email) || empty($password)) {
    $error_message = "All fields are required.";
  } else {
    // Sanitize and validate email format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
      $error_message = "Invalid email format.";
    }
    // Sanitize the password (you might want to hash it for security)
    $password = password_hash($password, PASSWORD_DEFAULT); // Securely hash the password

    // SQL Query (Prepared statement is preferred for security)
    $sql = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql); // $conn is your database connection

    if ($stmt) {
      $stmt->bind_param("ss", $username, $email);
      $stmt->execute();
      // Check if the query was successful
      if ($stmt->errno) {
        $error_message = "Error inserting data into the database: " . $stmt->error;
      } else {
        $success_message = "Registration successful!  Please log in.";
      }
    } else {
      $error_message = "Error preparing the SQL statement.";
    }
  }
}
?>
