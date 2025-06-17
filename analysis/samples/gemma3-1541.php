    <label for="email">Email Address:</label>
    <input type="email" id="email" name="email" placeholder="Your email" required>
    <button type="submit">Reset Password</button>
  </form>

</body>
</html>


   $stmt = mysqli_prepare($db, "UPDATE users SET password_reset_token = ?, password_reset_token_expiry = NOW() WHERE email = ?");
   mysqli_stmt_bind_param($stmt, "ss", $token, $email);
   mysqli_stmt_execute($stmt);
   mysqli_stmt_close($stmt);
   
   * Use `mysqli_stmt_bind_param()` to securely bind the variables to the query.

2. **Email Sending:**
   * **Use a reliable email sending library or service:**  Don't directly use `mail()` with your own email account.  Email sending can be unreliable and is often flagged as spam.  Consider using:
      * **PHPMailer:**  A popular, easy-to-use PHP library for sending emails.
      * **SwiftMailer:** Another robust email sending library.
      * **SMTP service:**  Services like SendGrid, Mailgun, or Amazon SES are generally the best approach for production.

3. **Password Reset Page (password_reset_page.php):**
   * This file (which you'll create) needs to:
      * Get the token and email from the URL.
      * Verify that the token is valid and hasn't expired.
      * Allow the user to set a new password.
      * Update the user's password in the database.

4. **Rate Limiting:**  Implement rate limiting to prevent brute-force attacks.  Don't allow a user to request a password reset multiple times in a short period.

5. **Logging:**  Log all password reset attempts (successful and unsuccessful) for auditing and security purposes.

6. **Two-Factor Authentication (2FA):**  Consider adding 2FA to the password reset process for an extra layer of security.

7. **Test Thoroughly:**  Test the entire password reset flow thoroughly, including edge cases, to ensure it's secure and reliable.

**Example `password_reset_page.php` (Illustrative - Requires Adaptation):**



<?php

// Assuming you have a database connection established and a 'users' table
// with an 'email' column.  This is a basic example and should be adapted
// to your specific database setup and security needs.

// 1.  Generate a unique token
function generateUniqueToken($length = 32) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $randomString = '';
    $characterLength = strlen($characters);
    for ($i = 0; $i < $length; $i++) {
        $index = rand(0, $characterLength - 1);
        $randomString .= $characters[$index];
    }
    return $randomString;
}


// 2.  Reset Password Function
function resetPassword($email, $token, $newPasswordHash) {
    // Replace this with your actual database query
    // This is a simplified example - always sanitize and validate user input
    $db = new PDO('mysql:host=localhost;dbname=your_database_name', 'your_username', 'your_password');

    try {
        $stmt = $db->prepare("UPDATE users SET password = :password, reset_token = :reset_token WHERE email = :email AND reset_token = :token");
        $stmt->bindParam(':password', $newPasswordHash);
        $stmt->bindParam(':reset_token', $token);
        $stmt->bindParam(':email', $email);
        $stmt->execute();

        return true; // Success
    } catch (PDOException $e) {
        // Handle errors (logging, email notifications, etc.)
        error_log("Error resetting password: " . $e->getMessage());
        return false; // Failure
    }
}



// Example Usage:

// 1. Generate a token
$resetToken = generateUniqueToken();

// 2.  Example Password Reset Form (Simplified for demonstration)
//   Assume this form is submitted with email and a new password.

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST["email"];
    $password = $_POST["password"]; // This will be hashed
    $resetToken = $_POST["reset_token"];

    // Validate input (Important for security)
    if (empty($email) || empty($password) || empty($resetToken)) {
        echo "Error: Please fill in all fields.";
        exit;
    }

    // Hash the password (Use a strong hashing algorithm like password_hash())
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Reset the password
    $resetResult = resetPassword($email, $resetToken, $hashedPassword);

    if ($resetResult) {
        echo "Password reset successful!  Check your email for instructions.";
    } else {
        echo "Error: Password reset failed.  Please try again.";
    }
}
?>
