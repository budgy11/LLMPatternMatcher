    <label for="email">Email:</label>
    <input type="text" id="email" name="email" placeholder="Your Email">
    <button type="submit">Reset Password</button>
  </form>

</body>
</html>


<?php

// Database connection details (Replace with your actual credentials)
$db_host = 'localhost';
$db_name = 'your_database_name';
$db_user = 'your_db_user';
$db_pass = 'your_db_password';

// Function to handle the forgot password process
function forgotPassword($email) {
  // 1. Check if the email exists in the database
  $conn = new mysqli($db_host, $db_user, $db_pass, $db_name);

  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  $sql = "SELECT id, password, email FROM users WHERE email = '$email'";
  $result = $conn->query($sql);

  if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
    $userId = $user['id'];
    $hashedPassword = $user['password']; // The hashed password

    // 2. Generate a unique token (for security - important!)
    $token = bin2hex(random_bytes(32));

    // 3.  Store the token associated with the user's email
    $insertTokenSql = "INSERT INTO password_reset_tokens (user_id, token, expiry_timestamp)
                        VALUES ($userId, '$token', NOW() + INTERVAL 24 HOUR)"; // Token valid for 24 hours

    if (!$conn->query($insertTokenSql)) {
      $error = $conn->error;
      // Handle the error (e.g., log it, show an error message)
      error_log("Error inserting token: " . $error);
      return "An error occurred while generating the reset link. Please try again.";
    }

    // 4.  Send the reset link (Email is a separate process - handled here for simplicity)
    $resetLink = "http://yourwebsite.com/reset_password?token=$token&user_id=$userId";

    //  You'd typically send this email using a mail function or a library.
    //  This is a placeholder.  Implement your email sending here.
    //  Example (simulated):
    $resetMessage = "Please click the link below to reset your password:
" . $resetLink;
    //echo $resetMessage; // This will only display in the browser.
    // In a real application, you'd send this as an email.
    
    return "Password reset link sent to your email.  Please check your inbox.";

  } else {
    return "Email not found.";
  }

  $conn->close();
}

// Example Usage (for testing - don't expose this in a live application)
// Assuming you have a form to submit the email address.  This is just an example.
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST["email"];
    $resetResult = forgotPassword($email);
    echo "<p>" . $resetResult . "</p>";
}
?>
