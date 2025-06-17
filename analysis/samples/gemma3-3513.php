

<?php

// Configuration (Replace with your actual database details)
$db_host = 'localhost';
$db_username = 'your_username';
$db_password = 'your_password';
$db_name = 'your_database';

// Function to register a new user
function registerUser($username, $password, $email) {
  // 1. Connect to the database
  $conn = new mysqli($db_host, $db_username, $db_password, $db_name);

  // Check connection
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  // 2. Prepare and Execute the SQL Query
  $hashed_password = password_hash($password, PASSWORD_DEFAULT);  // Hash the password for security

  $sql = "INSERT INTO users (username, password, email) VALUES ('$username', '$hashed_password', '$email')";

  if ($conn->query($sql) === TRUE) {
    return true; // Registration successful
  } else {
    return false; // Registration failed
  }

  // Close the connection
  $conn->close();
}


// Example Usage (This part handles the form submission)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $username = $_POST["username"];
  $password = $_POST["password"];
  $email = $_POST["email"];

  // Validation (Add more validation as needed)
  if (empty($username) || empty($password) || empty($email)) {
    echo "<p style='color:red;'>All fields are required.</p>";
  } elseif (strlen($username) < 3) {
    echo "<p style='color:red;'>Username must be at least 3 characters long.</p>";
  } else {
    // Call the registration function
    if (registerUser($username, $password, $email)) {
      echo "<p style='color:green;'>User registered successfully!</p>";
      // Redirect to a login page or welcome page
      header("Location: login.php");
      exit();
    } else {
      echo "<p style='color:red;'>Registration failed. Please try again.</p>";
    }
  }
}
?>
