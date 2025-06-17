

<?php

// Assuming you have a database connection established and a 'users' table
// with a 'email' column.  This is a basic example and should be
// adapted to your specific database setup and security best practices.

// Function to handle password reset requests
function forgot_password($email) {
    // 1. Check if the email exists
    $user = getUserByEmail($email); //  Call a function to retrieve the user object

    if (!$user) {
        return "Email not found.";
    }

    // 2. Generate a unique token (for security)
    $token = generateUniqueToken();

    // 3. Store the token in the database associated with the user's email.
    // IMPORTANT:  Hash the token *before* storing it in the database.
    storeToken($user->id, $token);


    // 4.  Send an email with a link containing the token
    $subject = "Password Reset Request";
    $to = $email;
    $headers = "From: your_email@example.com" . "\r
" .
              "Reply-To: your_email@example.com";
    $message = "Click on the following link to reset your password: " . $_SERVER['REQUEST_SCHEME'] . "://yourwebsite.com/reset_password?token=" . urlencode($token);  // Use urlencode
    mail($to, $subject, $message, $headers);


    return "Password reset email has been sent. Check your inbox.";
}

// Dummy functions - Replace with your actual database queries
// Example database interaction

class User {
    public $id;
    public $email;

    public function __construct($id, $email) {
        $this->id = $id;
        $this->email = $email;
    }
}


function getUserByEmail($email) {
    // Replace this with your actual database query
    // Example using a dummy array (replace with your DB query)
    $users = [
        ['id' => 1, 'email' => 'test@example.com'],
        ['id' => 2, 'email' => 'another@example.com'],
    ];

    foreach ($users as $user) {
        if ($user['email'] === $email) {
            return new User($user['id'], $user['email']);
        }
    }
    return null;
}


function storeToken($userId, $token) {
    // Replace this with your actual database query to store the token
    // Example using a dummy database - replace with your actual DB interaction
    // This is just a placeholder. You'll need to adapt this.

    // In a real application, you'd insert this into a 'tokens' table.
    // The 'tokens' table would likely have columns like:
    // - user_id (foreign key referencing the 'users' table)
    // - token
    // - expiry_date (to ensure the token expires)
    // You would probably also include a column for 'created_at'
    // and 'updated_at' for tracking.

    // This is for demonstration only.
    echo "Token stored in database (simulated)." . PHP_EOL;
}

function generateUniqueToken() {
    return bin2hex(random_bytes(32));  // Generate a cryptographically secure token
}


// Example Usage
//  Simulate a password reset request
$email = 'test@example.com'; // Replace with the user's email

$result = forgot_password($email);
echo $result . PHP_EOL;

?>
