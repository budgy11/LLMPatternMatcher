
<!DOCTYPE html>
<html>
<head>
  <title>Forgot Password</title>
</head>
<body>

  <h1>Forgot Password</h1>

  <form method="post" action="">
    <label for="email">Email:</label>
    <input type="email" id="email" name="email" required>
    <button type="submit">Reset Password</button>
  </form>

</body>
</html>


<?php

// Database connection details (replace with your actual credentials)
$host = 'localhost';
$username = 'your_username';
$password = 'your_password';
$dbname = 'your_database_name';

// Function to handle the forgot password process
function forgotPassword($email) {
  // 1. Check if the email exists in the database
  $conn = new mysqli($host, $username, $password, $dbname);

  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  $stmt = $conn->prepare("SELECT id, email FROM users WHERE email = ?");
  $stmt->bind_param("s", $email); // 's' indicates a string parameter

  if ($stmt->execute() === false) {
    echo "Error executing query: " . $stmt->error;
    $stmt->close();
    $conn->close();
    return false;
  }

  $result = $stmt->get_result();

  if ($result->num_rows == 0) {
    $stmt->close();
    $conn->close();
    return false; // Email not found
  }

  $user = $result->fetch_assoc();
  $userId = $user['id'];
  $userEmail = $user['email'];
  $stmt->close();

  // 2. Generate a unique token and store it in the database
  $token = bin2hex(random_bytes(32)); // Generate a secure random token
  $token_stored = false;

  $stmt = $conn->prepare("INSERT INTO password_resets (user_id, token, expires_at) VALUES (?, ?, NOW())");
  $stmt->bind_param("is", $userId, $token);

  if ($stmt->execute() === false) {
    echo "Error inserting token: " . $stmt->error;
    $stmt->close();
    $conn->close();
    return false;
  }

  // 3. Send an email with the reset link
  $to = $email;
  $subject = 'Password Reset';
  $message = "Please click on the following link to reset your password: <a href='" . $_SERVER['PHP_SELF'] . "?reset=" . $token . "'>Reset Password</a>";
  $headers = "From: your_email@example.com" . "\r
"; // Replace with your email address

  mail($to, $subject, $message, $headers);

  // 4. Close the connection
  $conn->close();

  return true; // Password reset link sent successfully
}


// Example usage (this is just a demonstration - typically you'd have a form submission)
if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['email'])) {
  $email = $_GET['email'];
  if (forgotPassword($email)) {
    echo "Password reset link sent to " . $email;
  } else {
    echo "Error: Could not send reset link.";
  }
}

?>
