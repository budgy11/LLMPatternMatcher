    <label for="username">Username:</label>
    <input type="text" id="username" name="username" placeholder="Enter username" required>

    <br><br>

    <label for="email">Email:</label>
    <input type="email" id="email" name="email" placeholder="Enter email" required>

    <br><br>

    <label for="password">Password:</label>
    <input type="password" id="password" name="password" placeholder="Enter password" required>

    <br><br>

    <label for="confirm_password">Confirm Password:</label>
    <input type="password" id="confirm_password" name="confirm_password" placeholder="Confirm password" required>

    <br><br>

    <button type="submit">Register</button>
  </form>

</body>
</html>


<?php

// Database configuration (Replace with your actual database details)
$db_host = "localhost";
$db_name = "your_database_name";
$db_user = "your_database_user";
$db_password = "your_database_password";

// Function to register a new user
function registerUser($username, $email, $password) {

  // 1. Validate Inputs (Important for security)
  $username = trim($username);  // Remove leading/trailing whitespace
  $email = trim($email);
  $password = trim($password);

  if (empty($username) || empty($email) || empty($password)) {
    return "Error: All fields are required.";
  }

  // Validate email format (basic check)
  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    return "Error: Invalid email format.";
  }

  //  You could add more robust password validation here,
  //  e.g., minimum length, required characters, etc.

  // 2. Connect to the Database
  $conn = new mysqli($db_host, $db_user, $db_password, $db_name);

  // Check connection
  if ($conn->connect_error) {
    return "Error: Unable to connect to database: " . $conn->connect_error;
  }

  // 3. Prepare and Execute the SQL Query
  // Use prepared statements to prevent SQL injection
  $sql = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";
  $stmt = $conn->prepare($sql);

  if ($stmt) {
    $password_hash = password_hash($password, PASSWORD_DEFAULT); // Hash the password
    $stmt->bind_param("sss", $username, $email, $password_hash); // "s" means string

    if ($stmt->execute()) {
      $stmt->close();
      return "Registration successful! You have been logged in.";
    } else {
      $error = $stmt->error;
      $stmt->close();
      return "Error: " . $error;
    }
  } else {
    return "Error: Unable to prepare statement.";
  }
}


// --- Example Usage (Handle Form Submission) ---

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

  // Get form data
  $username = $_POST["username"];
  $email = $_POST["email"];
  $password = $_POST["password"];

  // Call the registration function
  $registration_result = registerUser($username, $email, $password);

  // Display the result
  echo "<p>" . $registration_result . "</p>";

}

?>
