

<?php

// Database configuration (Replace with your actual database details)
$host = "localhost";
$username = "your_username";
$password = "your_password";
$database = "your_database_name";

// Function to register a new user
function registerUser($username, $email, $password) {
  // 1. Validate Inputs (Crucial for Security!)
  $username = trim($username); // Remove leading/trailing whitespace
  $email = trim($email);
  $password = trim($password);

  if (empty($username) || empty($email) || empty($password)) {
    return "Error: All fields are required.";
  }

  // Simple email validation (Improve this with a proper regex)
  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    return "Error: Invalid email format.";
  }

  //  Password validation (Add more sophisticated checks here - length, character types etc.)
  if (strlen($password) < 8) {
    return "Error: Password must be at least 8 characters long.";
  }

  // 2. Database Connection
  $conn = new mysqli($host, $username, $password, $database);

  if ($conn->connect_error) {
    return "Error: Unable to connect to database: " . $conn->connect_error;
  }

  // 3. Escape Data to Prevent SQL Injection
  $username = $conn->real_escape_string($username);
  $email = $conn->real_escape_string($email);
  $password = $conn->real_escape_string($password);

  // 4. Prepare and Execute the SQL Query
  $sql = "INSERT INTO users (username, email, password) VALUES ('$username', '$email', '$password')";

  if ($conn->query($sql) === TRUE) {
    return "Success: User registered successfully!";
  } else {
    return "Error: " . $conn->error;
  }

  // 5. Close Connection
  $conn->close();
}


// Example Usage (Form Handling - This is just an illustration)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $username = $_POST["username"];
  $email = $_POST["email"];
  $password = $_POST["password"];

  $registrationResult = registerUser($username, $email, $password);
  echo "<p>" . $registrationResult . "</p>";
}
?>
