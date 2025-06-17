    <label for="email">Email:</label>
    <input type="email" id="email" name="email" required><br><br>

    <label for="newPassword">New Password:</label>
    <input type="password" id="newPassword" name="newPassword" required><br><br>

    <input type="submit" value="Reset Password">
  </form>

</body>
</html>


<?php

// Assuming you have a database connection established and a 'users' table
// with a 'email' column.  This is a simplified example and 
// should be adapted to your specific database structure and security needs.

// **IMPORTANT SECURITY NOTES:**
// * **NEVER store passwords directly in the database.**  Always use a secure hashing algorithm like bcrypt or Argon2.
// * **Rate Limiting:**  Implement rate limiting on password reset requests to prevent brute-force attacks.
// * **Email Verification:**  Always verify the reset link via email to ensure the user is actually requesting a reset and not a malicious actor.
// * **Secure Session Handling:** Properly handle sessions to prevent session hijacking.
// * **Sanitize and Validate Input:**  Validate all user input rigorously.
// * **Error Handling:** Implement robust error handling to catch and log any issues.


// Function to reset password
function reset_password($email, $token) {
    // 1. Database Query - Retrieve user information based on email and token
    $conn = // your database connection here
    $sql = "SELECT * FROM users WHERE email = ? AND reset_token = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $email, $token);
    $stmt->execute();

    $result = $stmt->get_result();

    if ($result->num_rows == 0) {
        // User not found or token invalid
        return false;
    }

    $user = $result->fetch_assoc();


    // 2. Generate a new, unique token (Important for security)
    $new_token = bin2hex(random_bytes(32)); // Generate a cryptographically secure random token

    // 3. Update the user's record with the new token
    $update_sql = "UPDATE users SET reset_token = ? WHERE email = ?";
    $update_stmt = $conn->prepare($update_sql);
    $update_stmt->bind_param("ss", $new_token, $email);
    $update_stmt->execute();


    // 4.  Create the reset link/email
    $reset_link = "https://yourdomain.com/reset-password?token=" . $new_token; // Replace yourdomain.com

    // 5. Send the reset email (using a proper email sending library)
    // Example using PHPMailer (you need to install it: composer require phpmailer/phpmailer)
    //  (This part is simplified for demonstration purposes)
    // Requires Composer to be installed
    // composer require phpmailer/phpmailer
    //
    // $mail = new PHPMailer(true);
    // $mail->isSMTP();
    // $mail->Host = 'smtp.example.com';
    // $mail->Port = 587;
    // $mail->SMTPAuth = true;
    // $mail->Username = 'your_email@example.com';
    // $mail->Password = 'your_password';
    // $mail->SetFrom('your_email@example.com', 'Your Website Name');
    // $mail->Subject = 'Password Reset';
    // $mail->Body = "Click the following link to reset your password: " . $reset_link;
    // $mail->send();


    return true; // Password reset link generated successfully

}



// Example Usage (in a web form or API endpoint)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST["email"];
    $token = $_POST["token"];

    if (reset_password($email, $token)) {
        echo "Password reset link has been generated.  Check your email.";
    } else {
        echo "Invalid email or token.";
    }
}
?>
