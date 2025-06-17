        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required>
        <button type="submit">Forgot Password</button>
    </form>

</body>
</html>


<?php

// Ensure this file is in a secure location and not directly accessible via web browser.
// This is just an example for demonstration purposes.
// In a production environment, you should use a proper database and secure authentication practices.

// Database credentials (replace with your actual credentials)
$db_host = 'localhost';
$db_name = 'your_database_name';
$db_user = 'your_db_user';
$db_password = 'your_db_password';


function forgot_password($email) {
  // 1. Check if the email exists in the user table
  $conn = new mysqli($db_host, $db_user, $db_password, $db_name);

  if ($conn->connect_error) {
    // Handle connection error appropriately (e.g., log it, display an error message)
    die("Connection failed: " . $conn->connect_error);
  }

  $sql = "SELECT id, email FROM users WHERE email = ?";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param("s", $email); // "s" indicates a string parameter
  $stmt->execute();

  $result = $stmt->get_result();

  if ($result->num_rows == 0) {
    $stmt->close();
    $conn->close();
    return false; // Email not found
  }

  $user_id = $result->fetch_assoc()['id']; //get the id
  $stmt->close();
  $conn->close();


  // 2. Generate a unique token for the password reset
  $token = bin2hex(random_bytes(32)); // Generate a random string (32 bytes = 256 bits)

  // 3. Store the token and user ID in the database
  $conn = new mysqli($db_host, $db_user, $db_password, $db_name);
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  $sql = "INSERT INTO password_resets (user_id, token, expires_at) VALUES (?, ?, NOW())";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param("ss", $user_id, $token);
  $stmt->execute();

  $stmt->close();
  $conn->close();


  // 4. Send an email with the token and a link to reset the password
  $email_body = "Hello,

" .
                "Please use the following link to reset your password:
" .
                "http://yourwebsite.com/reset_password?token=" . $token . "

" .
                "This link will expire in 24 hours.";  // Adjust expiration time as needed

  //  Replace with your email sending logic (e.g., using PHPMailer, SMTP, etc.)
  //  Example (placeholder):
  //  send_email($email, "Password Reset", $email_body);

  return true; // Password reset request initiated successfully
}


// Example Usage (for demonstration - replace with your form submission handling)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST["email"];
    if (isset($email)) {
      if (forgot_password($email)) {
        echo "Password reset email sent to " . $email;
      } else {
        echo "Email not found or an error occurred during the password reset process.";
      }
    } else {
      echo "Email not provided.";
    }
}
?>
