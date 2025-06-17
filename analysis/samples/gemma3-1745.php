

<?php

// Database connection details (replace with your actual credentials)
$host = "localhost";
$username = "your_username";
$password = "your_password";
$database = "your_database_name";

// Function to send an email
function sendPasswordResetEmail($email, $token) {
  // Replace with your email sending logic (e.g., using PHPMailer)
  // This is just a placeholder
  $subject = "Password Reset Request";
  $message = "Click this link to reset your password: " . "<a href='" . $_SERVER['PHP_SELF'] . "?reset=$token'>" . $_SERVER['PHP_SELF'] . "?reset=$token</a>";
  $headers = "From: your_email@example.com" . "\r
";

  mail($email, $message, $headers);
}

// Function to generate a unique token
function generateToken() {
  return bin2hex(random_bytes(32)); // Generates a 32-byte random string, hex encoded
}

// Function to check if an email exists
function emailExists($email) {
  //  This is a simplified example.  In a real application,
  //  you should use a proper database query with WHERE clause.
  return true; //  Replace with your database query to check for existing email
}

// Forgot Password Function
function forgotPassword($email) {
  // 1. Validate email
  if (!emailExists($email)) {
    return "Invalid email address.";
  }

  // 2. Generate a unique token
  $token = generateToken();

  // 3.  Store the token in the database (e.g., in a 'reset_tokens' table)
  //     You'll need to create a table to store these tokens.
  //     Example table structure:
  //     CREATE TABLE reset_tokens (
  //       id INT AUTO_INCREMENT PRIMARY KEY,
  //       email VARCHAR(255) NOT NULL,
  //       token VARCHAR(255) NOT NULL,
  //       created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
  //     );

  // Assuming you have a database connection established (e.g., $conn)

  // Example database insertion (replace with your actual query)
  //$query = "INSERT INTO reset_tokens (email, token) VALUES ('$email', '$token')";
  //if (!mysqli_query($conn, $query)) {
  //  return "Error inserting token into database.";
  //}

  // 4. Send the password reset email
  sendPasswordResetEmail($email, $token);

  return "Password reset link has been sent to your email address.";
}


//  Example Usage (for testing - you would handle this through a form submission)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $email = $_POST["email"];
  $resetResult = forgotPassword($email);
  echo $resetResult;
}

?>
