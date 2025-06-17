    <label for="username">Username:</label>
    <input type="text" id="username" name="username" required>
    <br><br>

    <label for="email">Email:</label>
    <input type="email" id="email" name="email" required>
    <br><br>

    <label for="password">Password:</label>
    <input type="password" id="password" name="password" required>
    <br><br>

    <button type="submit">Register</button>
  </form>

</body>
</html>


<?php

// Database configuration (replace with your actual database details)
$db_host = "localhost";
$db_name = "your_database_name";
$db_user = "your_db_user";
$db_password = "your_db_password";


// Function to register a user
function registerUser($username, $password, $email) {
  // 1. Connect to the database
  $conn = new mysqli($db_host, $db_user, $db_password, $db_name);

  // Check connection
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  // 2. Sanitize and Validate Input (VERY IMPORTANT)
  $username = $conn->real_escape_string($username);  // Escape for SQL
  $password = password_hash($password, PASSWORD_DEFAULT); // Hash password for security
  $email = $conn->real_escape_string($email);

  // 3. Insert the new user into the database
  $sql = "INSERT INTO users (username, password, email) VALUES ('$username', '$password', '$email')";

  if ($conn->query($sql) === TRUE) {
    return true; // Registration successful
  } else {
    return false; // Registration failed
  }

  // Close the connection
  $conn->close();
}


// Example Usage (Handle form submission - Replace with your actual form)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Retrieve form data
  $username = $_POST["username"];
  $password = $_POST["password"];
  $email = $_POST["email"];

  // Validate Input (Add more robust validation here)
  if (empty($username) || empty($password) || empty($email)) {
    $error_message = "All fields are required.";
  } elseif (strlen($username) < 3) {
      $error_message = "Username must be at least 3 characters long.";
  } else {
    // Register the user
    if (registerUser($username, $password, $email)) {
      $success_message = "Registration successful! Please check your email to verify your account.";
    } else {
      $error_message = "Registration failed.  Please try again.";
    }
  }
}
?>
