

<?php

// Assuming you have a database connection established as $db

/**
 * Resets a user's password.
 *
 * @param string $email The user's email address.
 * @return bool True if password reset email sent successfully, false otherwise.
 */
function forgot_password(string $email): bool
{
    // 1. Validate Email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        error_log("Invalid email format provided: " . $email); // Log for debugging
        return false;
    }

    // 2. Check if User Exists
    $user = getUserByEmail($email); // Function to retrieve user by email (see implementation below)

    if ($user === null) {
        error_log("User not found with email: " . $email); // Log for debugging
        return false;
    }

    // 3. Generate a Unique Token
    $token = generate_unique_token();

    // 4. Store Token in Database (with expiration time)
    $token_id = store_token($token, $user->id); // Function to store token (see implementation below)

    if ($token_id === false) {
        error_log("Failed to store token for email: " . $email);
        return false;
    }


    // 5. Send Password Reset Email
    $reset_link = generate_reset_link($token_id); // Function to construct reset link (see implementation below)

    if (!send_password_reset_email($user->email, $reset_link)) {
        error_log("Failed to send password reset email to: " . $user->email);
        //Optionally, you might want to delete the token if the email fails to send.
        //delete_token($token_id);
        return false;
    }


    return true;
}


/**
 * Retrieves a user from the database based on their email.
 *
 * @param string $email The email address to search for.
 * @return User|null The User object if found, null otherwise.
 */
function getUserByEmail(string $email): ?User
{
    // Replace this with your actual database query
    // This is just a placeholder for demonstration.
    // Use prepared statements to prevent SQL injection!
    $db = get_database_connection(); // Assumes you have this function
    $stmt = $db->prepare("SELECT id, email FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($result) {
        return new User($result['id'], $result['email']);
    }

    return null;
}


/**
 * Generates a unique token.
 *
 * @return string A unique token.
 */
function generate_unique_token(): string
{
    return bin2hex(random_bytes(32)); //More robust than just a random number.
}

/**
 * Stores a token in the database.
 *
 * @param string $token The token to store.
 * @param int $userId The ID of the user associated with the token.
 * @return bool True if token was stored successfully, false otherwise.
 */
function store_token(string $token, int $userId): bool
{
    // Replace this with your actual database query.
    $db = get_database_connection(); // Assumes you have this function
    try {
        $stmt = $db->prepare("INSERT INTO password_tokens (user_id, token, expiry) VALUES (?, ?, ?)");
        $stmt->execute([$userId, $token, time() + (24 * 60 * 60)]); // Expires in 24 hours.  Adjust as needed.
        return true;
    } catch (PDOException $e) {
        error_log("Error storing token: " . $e->getMessage());
        return false;
    }
}


/**
 * Generates a URL for the password reset link.
 *
 * @param int $token_id The ID of the token.
 * @return string The password reset link.
 */
function generate_reset_link(int $token_id): string
{
    // Replace with your actual URL construction.
    return "/reset_password?token=" . urlencode($token_id);
}



/**
 * Sends a password reset email.
 *
 * @param string $email The email address to send the email to.
 * @param string $reset_link The password reset link.
 * @return bool True if email was sent successfully, false otherwise.
 */
function send_password_reset_email(string $email, string $reset_link): bool
{
    //Replace with your actual email sending code.
    // This is just a placeholder.  Implement using a library like PHPMailer or similar.

    //For demonstration purposes:
    error_log("Sending password reset email to: " . $email . " with link: " . $reset_link);

    //Example using PHPMailer (requires installation and configuration)
    //require_once 'vendor/PHPMailer/PHPMailer.php';
    //$mail = new PHPMailer(true);
    //$mail->isSMTP();
    //$mail->Host = 'smtp.example.com';
    //$mail->Port = 587;
    //$mail->SMTPAuth = true;
    //$mail->Username = 'your_username';
    //$mail->Password = 'your_password';
    //$mail->SetFrom('your_email@example.com', 'Your Application');
    //$mail->Subject = 'Password Reset';
    //$mail->Body = '<a href="' . $reset_link . '" >Click here to reset your password</a>';
    //$mail->AltBody = "Password reset link: " . $reset_link;
    //$mail->send();
    //return true;


    return false; // Placeholder -  Replace with actual email sending logic.
}



/**
 *  Example User Class (for demonstration)
 */
class User
{
    private $id;
    private $email;

    public function __construct(int $id, string $email)
    {
        $this->id = $id;
        $this->email = $email;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getEmail(): string
    {
        return $this->email;
    }
}
?>
