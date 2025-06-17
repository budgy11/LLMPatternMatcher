

<?php

// This is a simplified example and should be used with caution.
// In a real-world application, you should:
// 1.  Use a strong password hashing algorithm (e.g., bcrypt, Argon2).
// 2.  Implement proper security measures to prevent vulnerabilities like brute-force attacks.
// 3.  Store reset tokens securely and with appropriate expiration times.
// 4.  Sanitize and validate all user inputs.
// 5.  Consider using a session management library for better security.
// 6.  Don't store passwords in plain text.

// Database connection details (replace with your actual values)
$host = 'localhost';
$username = 'your_db_user';
$password = 'your_db_password';
$database = 'your_db_name';

//  Helper function to generate a random token
function generateToken() {
  return bin2hex(random_bytes(32));
}

// Function to handle the forgot password process
function forgotPassword($email) {
  // 1. Check if the email exists in the users table
  $conn = new mysqli($host, $username, $password, $database);

  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  $stmt = $conn->prepare("SELECT id, email FROM users WHERE email = ?");
  $stmt->bind_param("s", $email); // "s" indicates a string
  $stmt->execute();
  $result = $stmt->get_result();

  if ($result->num_rows == 0) {
    $stmt->close();
    $conn->close();
    return "Email not found.";
  }

  $user = $result->fetch_assoc();
  $userId = $user['id'];
  $stmt->close();


  // 2. Generate a reset token
  $resetToken = generateToken();

  // 3. Store the reset token and user ID in the database
  $conn = new mysqli($host, $username, $password, $database);
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  $stmt = $conn->prepare("INSERT INTO password_resets (user_id, token, expiry) VALUES (?, ?, ?)");
  $stmt->bind_param("siss", $userId, $resetToken, time() + (24 * 60 * 60)); // Expire in 24 hours
  if ($stmt->execute() === false) {
      // Handle the error - important!
      echo "Error inserting reset token: " . $stmt->error;
      $stmt->close();
      $conn->close();
      return "Error creating reset token.";
  }

  $stmt->close();
  $conn->close();


  // 4. Send the reset link to the user's email
  $resetLink = 'http://' . $_SERVER['HTTP_HOST'] . '/reset_password.php?token=' . $resetToken; //  Customize the URL
  // In a real application, you would use an email sending library
  // or service to send the email.

  return $resetLink;
}

// Example Usage (simulated -  you would process this through a form)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $email = $_POST["email"];
  $resetLink = forgotPassword($email);

  if (strpos($resetLink, "Error") !== false) { // Check for error message
    echo $resetLink;
  } else {
    echo "Please check your email for the reset link.";
  }
}

?>
