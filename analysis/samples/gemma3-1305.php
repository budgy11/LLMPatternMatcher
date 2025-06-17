

<?php

// Assuming you have a database connection established and a 'users' table
// with a 'email' field.  This is a simplified example and doesn't include
// robust error handling or security best practices.

// Function to handle the forgotten password request
function forgot_password($email) {
    // 1.  Validate Input (Crucial for Security)
    if (empty($email)) {
        return "Error: Email address is required.";
    }

    // You'll want to sanitize and validate the email address.
    //  For example:
    //  $email = filter_var($email, FILTER_SANITIZE_EMAIL);
    //  if (filter_var($email, FILTER_VALIDATE_EMAIL) === false) {
    //      return "Error: Invalid email address.";
    //  }

    // 2.  Check if the user exists
    $user = getUserByEmail($email);

    if ($user === false) {
        return "Error: User not found.";
    }

    // 3. Generate a unique token
    $token = generateUniqueToken();

    // 4. Store the token in the database, associated with the user's email.
    //  This is the critical part:  This function *must* securely store the token.
    storeToken($user['id'], $token);

    // 5.  Send an email to the user with a link to reset their password.
    $reset_url = 'http://' . $_SERVER['HTTP_HOST'] . '/reset_password.php?token=' . urlencode($token);
    sendResetPasswordEmail($user['email'], $reset_url);

    return "Password reset email sent to $email.";
}

// --- Helper Functions (Implement these based on your database and email setup) ---

// Function to get user by email (replace with your database query)
function getUserByEmail($email) {
    // **IMPORTANT:**  Replace this with your actual database query.
    // This is just a placeholder.
    // Example using PDO:
    // $stmt = $pdo->prepare("SELECT * FROM users WHERE email = :email");
    // $stmt->bindParam(':email', $email);
    // $stmt->execute();
    // $user = $stmt->fetch(PDO::FETCH_ASSOC);
    // return $user;
    // return false; // User not found
    return false;
}

// Function to generate a unique token (using a cryptographically secure method)
function generateUniqueToken() {
    return bin2hex(random_bytes(32)); // Generate a 32-byte random string
}

// Function to store the token in the database
function storeToken($user_id, $token) {
    // **IMPORTANT:** Replace this with your actual database insertion query.
    // Example using PDO:
    // $stmt = $pdo->prepare("INSERT INTO password_tokens (user_id, token, created_at) VALUES (:user_id, :token, NOW()) ON DUPLICATE KEY UPDATE token = :token, updated_at = NOW()");
    // $stmt->bindParam(':user_id', $user_id);
    // $stmt->bindParam(':token', $token);
    // $stmt->execute();
    // return true;
    return true; // Return true on success
}


// Function to send the password reset email (replace with your email sending code)
function sendResetPasswordEmail($to, $reset_url) {
    // This is a placeholder. Replace with your email sending code.
    $subject = 'Password Reset';
    $message = "Click the link below to reset your password: " . $reset_url;
    $headers = ['Content-Type: text/html']; // Important for HTML emails

    //  Use a library like PHPMailer for robust email handling:
    //  https://www.phpmailer.com/

    //  Example (Conceptual):
    //  mail($to, $subject, $message, $headers);
    echo "Email sent (placeholder)";
}

// --- Example Usage (for testing) ---
// This should be in a separate file or a testing section of your application.

// If running this directly in a web environment, you'll need to handle input from
// a form submission.  For example:

// if ($_SERVER["REQUEST_METHOD"] == "POST") {
//     $email = $_POST["email"];
//     $result = forgot_password($email);
//     echo $result;
// }
?>
