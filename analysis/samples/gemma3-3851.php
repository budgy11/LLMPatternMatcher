
</body>
</html>


<?php

// Database Configuration (Replace with your actual database credentials)
$dbHost = "localhost";
$dbUsername = "your_username";
$dbPassword = "your_password";
$dbName = "your_database_name";

// --- Registration Function ---
function registerUser($username, $password, $email) {
  // 1. Connect to the Database
  $conn = new mysqli($dbHost, $dbUsername, $dbPassword, $dbName);

  // Check connection
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  // 2. Sanitize and Validate Inputs
  $username = $conn->real_escape_string($username); // Escape for SQL
  $password = $conn->real_escape_string($password); // Escape for SQL

  // Basic Validation (Add more robust validation here)
  if (empty($username) || empty($password) || empty($email)) {
    return "All fields are required.";
  }
  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    return "Invalid email format.";
  }

  // 3. Hash the Password (VERY IMPORTANT - Never store passwords in plain text!)
  $hashedPassword = password_hash($password, PASSWORD_DEFAULT);  // Use a strong hashing algorithm

  // 4. Prepare and Execute the SQL Query
  $sql = "INSERT INTO users (username, password, email) VALUES ('$username', '$hashedPassword', '$email')";

  if ($conn->query($sql) === TRUE) {
    return "Registration successful!  You have been sent a verification email.";  // Send email here
  } else {
    return "Registration failed: " . $conn->error;
  }

  // 5. Close the Connection
  $conn->close();
}


// --- Example Usage (Handle Form Submission - This is a simplified example) ---

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $username = $_POST["username"];
  $password = $_POST["password"];
  $email = $_POST["email"];

  $registrationResult = registerUser($username, $password, $email);
  echo "<p>" . $registrationResult . "</p>";
}

?>
