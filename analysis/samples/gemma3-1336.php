

<?php

// Assuming you have a database connection established (e.g., $db)

/**
 * Resets a user's password.
 *
 * @param string $email The email address of the user.
 * @return bool True if a reset link was generated and emailed, false otherwise.
 */
function forgotPassword(string $email)
{
    // 1. Validate Input
    if (empty($email)) {
        error_log("Forgot Password: Empty email provided.");  // Log for debugging
        return false;
    }

    // 2. Retrieve User
    $user = getUserByEmail($email);

    if (!$user) {
        error_log("Forgot Password: User with email '$email' not found."); // Log for debugging
        return false;
    }

    // 3. Generate a Unique Token and Timestamp
    $token = generateUniqueToken();
    $timestamp = time();

    // 4. Store Token and Timestamp in Database
    $reset_token = [
        'token' => $token,
        'user_id' => $user['id'],
        'timestamp' => $timestamp,
        'expiry' => time() + (24 * 60 * 60) // Token expires after 24 hours
    ];

    // Assuming you have a database table named 'password_resets'
    //  and a 'password_resets' table with columns: id, token, user_id, timestamp, expiry
    //  Use your database connection here to insert the $reset_token array into the password_resets table.
    // Example:
    // $db->insert('password_resets', $reset_token);

    // Simulate database insertion (replace with your actual database code)
    // Note: This is for demonstration purposes only and is NOT a safe replacement for real database interactions.
    $db->insert('password_resets', $reset_token);


    // 5. Generate Reset Link
    $reset_link = appUrl() . '/reset-password?token=' . $token;  // Adjust appUrl() to your application's URL

    // 6. Email Reset Link
    $subject = "Password Reset Link";
    $message = "Click the following link to reset your password: " . $reset_link;
    $headers = "From: " . appName() . " <" . appEmail() . ">\r
"; // Adjust appName() and appEmail()
    $sent = sendEmail($email, $subject, $message, $headers);

    if ($sent) {
        return true;
    } else {
        error_log("Forgot Password: Email not sent for user '$email'."); //Log for debugging
        return false;
    }
}

/**
 * Placeholder function for getting user by email.  Implement this to connect to your database.
 *
 * @param string $email The email address to search for.
 * @return array|null  An array containing user data if found, or null if not.
 */
function getUserByEmail(string $email)
{
    // **Replace this with your database query**
    // Example:
    // $result = $db->query("SELECT * FROM users WHERE email = '$email'");
    // if ($result->num_rows > 0) {
    //   $user = $result->fetch_assoc();
    //   return $user;
    // } else {
    //   return null;
    // }

    // Simulate a user
    $users = [
        ['id' => 1, 'email' => 'test@example.com', 'password' => 'hashed_password']
    ];
    foreach ($users as $user) {
        if ($user['email'] == $email) {
            return $user;
        }
    }
    return null;
}


/**
 * Placeholder function for generating a unique token.
 *
 * @return string  A unique token.
 */
function generateUniqueToken()
{
    return bin2hex(random_bytes(32)); // Use a strong random number generator.
}

/**
 * Placeholder for appUrl function.  Implement this to return your application's URL.
 *
 * @return string The base URL of your application.
 */
function appUrl()
{
    return 'http://localhost/my-app/'; // Replace with your application's URL.
}

/**
 * Placeholder for appName function.  Implement this to return your application's name.
 *
 * @return string The name of your application.
 */
function appName()
{
    return 'My Application';
}

/**
 * Placeholder for appEmail function.  Implement this to return your application's email address.
 *
 * @return string The email address for your application.
 */
function appEmail()
{
    return 'admin@example.com';
}


/**
 * Placeholder for sendEmail function.  Implement this to send emails.
 *
 * @param string $to       The email address to send to.
 * @param string $subject  The email subject.
 * @param string $message  The email body.
 * @param string $headers  Email headers.
 *
 * @return bool True on success, false on failure.
 */
function sendEmail(string $to, string $subject, string $message, string $headers)
{
    // **Replace this with your email sending code (e.g., using PHPMailer)**

    //Example using PHPMailer (assuming you have it installed and configured):
    // $mail = new PHPMailer(true);
    // $mail->SetFrom('admin@example.com', 'My Application');
    // $mail->AddAddress($to);
    // $mail->SetSubject($subject);
    // $mail->MsgBody($message, 'text/html'); // or 'text/plain'
    // $mail->AddAttachment('attachment.jpg', 'image.jpg');
    // $mail->AltBody = "Alternative text";
    // if ($mail->send()) {
    //     return true;
    // } else {
    //     return false;
    // }


    // Simulate email sending.
    error_log("Email sent to " . $to);
    return true;
}


<?php

/**
 * Forgets a user's password.
 *
 * This function generates a unique, time-based token and sends an email
 * containing a link to reset the password.
 *
 * @param string $email The email address of the user requesting a password reset.
 * @param string $baseUrl The base URL of your website or application. This is used to construct the reset link.
 * @return bool True on success, false on failure.
 */
function forgotPassword(string $email, string $baseUrl): bool
{
    // 1. Validate Email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        error_log("Invalid email address provided.");
        return false;
    }

    // 2. Generate a Unique Token
    $token = bin2hex(random_bytes(32)); // Use a strong, random token
    // This creates a 64-character hexadecimal string.

    // 3.  Store the Token (Database or Session - We'll use a database for persistence)
    //  - Create a database table if one doesn't exist (example)
    //  - Add a new row to the table with the user's email and token.

    // This example assumes you have a 'users' table with 'email' and 'password_reset_token' columns
    $db = new PDO('mysql:host=localhost;dbname=your_database_name', 'your_username', 'your_password');

    try {
        // Check if the email already has a reset token
        $stmt = $db->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user_id = $stmt->fetchColumn();

        if ($user_id) {
            // Token already exists, update it
            $stmt = $db->prepare("UPDATE users SET password_reset_token = ? WHERE email = ?");
            $stmt->execute([$token, $email]);

            // Optional:  Set an expiration time for the token (e.g., 1 hour)
            // You'll likely want to add a column 'expiration_time' to your 'users' table
            // and update it here.  For simplicity, we're not doing that in this example.


        } else {
            // Insert a new row if the email doesn't exist
            $stmt = $db->prepare("INSERT INTO users (email, password_reset_token) VALUES (?, ?)");
            $stmt->execute([$email, $token]);
        }

    } catch (PDOException $e) {
        error_log("Database error: " . $e->getMessage());
        return false;
    }



    // 4. Generate the Reset Link
    $resetLink = $baseUrl . "/reset_password?token=" . $token;

    // 5. Send the Email (Replace with your email sending logic)
    //   This is a placeholder.  You'll need to implement your email sending function.
    $subject = "Password Reset Request";
    $message = "Click the link below to reset your password:
" . $resetLink;
    $headers = "From: Your Website <your.email@example.com>\r
";

    // Example email sending (replace with your actual implementation)
    // $success = sendEmail($email, $subject, $message, $headers);

    // Placeholder - return true to indicate success.  You'll need to implement the email sending.
    return true;
}

/**
 * Placeholder for email sending.  Replace with your actual implementation.
 *
 * @param string $to The email address.
 * @param string $subject The email subject.
 * @param string $body The email body.
 * @param array $headers Headers for the email.
 * @return bool True on success, false on failure.
 */
function sendEmail(string $to, string $subject, string $body, array $headers): bool
{
    // This is just a placeholder. Implement your email sending logic here.
    // You'll likely use a library like PHPMailer or SwiftMailer.

    // Example: (Replace this with actual sending code)
    // error_log("Simulating email sending: " . $to);
    return true; //  Always return true for this example.
}


// Example Usage:
$baseUrl = "http://example.com"; // Replace with your website's base URL

$email = "test@example.com";  // Replace with the user's email address

if (forgotPassword($email, $baseUrl)) {
    echo "Password reset email has been sent to " . $email . ".";
} else {
    echo "Failed to generate password reset link.";
}

?>
