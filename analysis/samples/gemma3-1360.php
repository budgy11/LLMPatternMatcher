
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

// Assuming you have a database connection established (e.g., $db)
// and a table called 'users' with a 'email' column.

// Function to handle the forgot password flow
function forgotPassword($email) {
  // 1. Validate email format (basic check - you'll want more robust validation)
  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    return 'Invalid email format.';
  }

  // 2. Check if the user exists
  $user = getUserByEmail($email);  // Implement this function (see below)

  if ($user === null) {
    return 'User not found.';
  }

  // 3. Generate a unique token
  $token = generateUniqueToken(); // Implement this function (see below)

  // 4. Store the token and user ID in a temporary table (for security)
  // This prevents leaking the user's password to an attacker.
  storeToken($user['id'], $token); 

  // 5.  Send the reset link email
  $resetLink = generateResetLink($user['email'], $token);
  sendResetPasswordEmail($user['email'], $resetLink);

  return 'Password reset link sent to your email.';
}


// --------------------- Helper Functions (Implement these!) ---------------------

// 1. getUserByEmail($email) - Retrieves user information based on email.
function getUserByEmail($email) {
  // Replace this with your database query
  // Example (using MySQLi)
  // $stmt = $db->prepare("SELECT id, email FROM users WHERE email = ?");
  // $stmt->bind_param("s", $email);
  // $stmt->execute();
  // $result = $stmt->get_result();

  // if ($result->num_rows > 0) {
  //   return $result->fetch_assoc();
  // } else {
  //   return null;
  // }
  // Return null if user doesn't exist.
  return null; 
}



// 2. generateUniqueToken() - Generates a unique, secure token.
function generateUniqueToken() {
  return bin2hex(random_bytes(32));  // Uses a strong random source.  This is good practice.
}



// 3. storeToken($userId, $token) - Stores the token in a temporary table.
function storeToken($userId, $token) {
  // Replace this with your database insertion query.
  // Example (using MySQLi)
  // $stmt = $db->prepare("INSERT INTO password_tokens (user_id, token, expiry_date) VALUES (?, ?, ?)");
  // $stmt->bind_param("iii", $userId, $token, time() + 3600); // Token expires after 1 hour.
  // $stmt->execute();

  // Dummy implementation for demonstration
  //  echo "Stored token in database (dummy implementation)";
}



// 4. generateResetLink($email, $token) - Constructs the reset link.
function generateResetLink($email, $token) {
  return 'http://yourwebsite.com/reset-password?token=' . urlencode($token); // Replace yourwebsite.com
}



// 5. sendResetPasswordEmail($email, $resetLink) - Sends the email.
function sendResetPasswordEmail($email, $resetLink) {
  // Replace this with your email sending logic.
  // Example (using PHPMailer)
  // require_once 'PHPMailer/PHPMailerAutoload.php';
  // $mail = new PHPMailer();
  // $mail->addAddress($email, 'User');
  // $mail->Subject = 'Password Reset';
  // $mail->Body    = $resetLink;
  // $mail->isSMTP();
  // $mail->Host       = 'smtp.example.com';
  // $mail->Port = 587;
  // $mail->setUsername('your_username');
  // $mail->setPassword('your_password');
  // $mail->setFrom('noreply@example.com', 'Your Website');
  // $mail->CharSet = 'UTF-8';
  // if (!$mail->send()) {
  //   echo "Email failed to send.";
  // }

  // Dummy implementation - just prints the link.
  echo "Email sent with link: " . $resetLink;
}


// --------------------- Example Usage ---------------------

// Get the email from the user (e.g., from a form submission)
$email = $_POST['email'];  // Assuming the email is submitted via a form

// Call the forgotPassword function
$result = forgotPassword($email);

// Display the result
echo $result;

?>
