

<?php

// Database configuration (Replace with your actual credentials)
$db_host = "localhost";
$db_name = "your_database_name";
$db_user = "your_database_user";
$db_pass = "your_database_password";

// Function to handle user registration
function registerUser($username, $email, $password) {
  // 1. Data Validation - VERY IMPORTANT!
  // Validate inputs.  This is crucial for security.
  if (empty($username) || empty($email) || empty($password)) {
    return false; // Return false if any field is empty
  }

  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    return false; // Invalid email format
  }

  // Password complexity (adjust requirements as needed)
  if (strlen($password) < 6) {
    return false; // Password must be at least 6 characters
  }

  //  Hashing the password for security!  NEVER store passwords in plain text.
  $hashed_password = password_hash($password, PASSWORD_BCRYPT);

  // 2. Database Interaction - Using Prepared Statements for Security!
  try {
    $pdo = new PDO("mysql:host=$db_host;dbname=$db_name", $db_user, $db_pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);  // Enable error reporting for easier debugging

    // Prepare the SQL query to prevent SQL injection
    $stmt = $pdo->prepare("INSERT INTO users (username, email, password) VALUES (:username, :email, :password)");

    // Bind the parameters
    $stmt->bindParam(':username', $username);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':password', $hashed_password);

    // Execute the query
    $stmt->execute();

    return true; // Return true if registration was successful
  } catch (PDOException $e) {
    // Handle database errors - log them for debugging.  Don't expose the full error to the user.
    error_log("Registration error: " . $e->getMessage());
    return false; // Return false if there was a database error
  }
}


// Example Usage (Demonstration - this code is not for the user interface)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $username = $_POST["username"];
  $email = $_POST["email"];
  $password = $_POST["password"];

  if (registerUser($username, $email, $password)) {
    echo "Registration successful!  Please check your email for a verification link.";
  } else {
    echo "Registration failed. Please try again.";
  }
}
?>
