    <label for="username">Username:</label>
    <input type="text" id="username" name="username" placeholder="Enter username" required>

    <br><br>

    <label for="password">Password:</label>
    <input type="password" id="password" name="password" placeholder="Enter password" required>

    <br><br>

    <label for="email">Email:</label>
    <input type="email" id="email" name="email" placeholder="Enter email" required>

    <br><br>

    <button type="submit">Register</button>
  </form>

</body>
</html>


<?php

// Database Configuration (Replace with your actual credentials)
$db_host = "localhost";
$db_name = "your_database_name";
$db_user = "your_database_user";
$db_password = "your_database_password";

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Get form data
  $username = $_POST["username"];
  $password = $_POST["password"];
  $email = $_POST["email"];

  // Validate the input (Important!)
  if (empty($username) || empty($password) || empty($email)) {
    $error_message = "All fields are required.";
  } elseif (strlen($username) < 3) {
    $error_message = "Username must be at least 3 characters long.";
  } elseif (preg_match('/@/', $email)) { // Basic email validation
    $error_message = "Invalid email format.";
  } else {

    // Hash the password (Important for security!)
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // SQL Query (Use prepared statements to prevent SQL injection)
    $sql = "INSERT INTO users (username, password, email) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sss", $username, $hashed_password, $email);
    $stmt->execute();

    if ($stmt->error) {
      $error_message = "Error registering user: " . $stmt->error;
    } else {
      $error_message = "Registration successful!";
    }

    $stmt->close();
  }
}

// Display the registration form
?>
