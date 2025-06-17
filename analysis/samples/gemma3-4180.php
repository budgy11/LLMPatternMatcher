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

// Database connection details (replace with your actual credentials)
$dbHost = 'localhost';
$dbUsername = 'your_username';
$dbPassword = 'your_password';
$dbName = 'your_database_name';

// Function to register a new user
function registerUser($username, $password, $email) {
  // 1. Database Connection
  $conn = new mysqli($dbHost, $dbUsername, $dbPassword, $dbName);

  // Check for connection errors
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  // 2. Data Sanitization and Validation (Crucial for security)
  //  - Escape input to prevent SQL injection
  $username = $conn->real_escape_string($username);
  $email = $conn->real_escape_string($email);

  //  - Validation (add more checks as needed)
  if (empty($username) || empty($password) || empty($email)) {
    return false; // Indicate failure - required fields missing
  }

  //  - Check for existing username or email (optional, but recommended)
  $sql = "SELECT id, username, email FROM users WHERE username = '$username' OR email = '$email'";
  $result = $conn->query($sql);

  if ($result->num_rows > 0) {
    return false; // Username or email already exists
  }

  // 3.  Prepare and Execute the SQL Query
  $passwordHash = password_hash($password, PASSWORD_DEFAULT); // Hash the password for security

  $sql = "INSERT INTO users (username, password, email) VALUES ('$username', '$passwordHash', '$email')";

  if ($conn->query($sql) === TRUE) {
    return true; // Registration successful
  } else {
    // Handle database errors (important for debugging)
    error_log("Registration error: " . $conn->error);
    return false;
  }
}


// Example Usage (Handle form submission)

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $username = $_POST["username"];
  $password = $_POST["password"];
  $email = $_POST["email"];

  // Call the registration function
  if (registerUser($username, $password, $email)) {
    echo "Registration successful!  Please check your email to verify.";
  } else {
    echo "Registration failed. Please try again.";
  }
}


// HTML Form for Registration
?>
