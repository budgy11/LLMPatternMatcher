

<?php

// Assume you have a database connection established and a 'users' table with a 'email' column.
// This is a simplified example for demonstration purposes.  In a real application,
// you'd want to implement proper security measures like password hashing and
// prevent brute-force attacks.

// Function to handle the forgot password flow
function forgot_password($email) {
    // 1. Validate Email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return "Invalid email format.";
    }

    // 2. Check if the user exists
    $user = get_user_by_email($email); // Replace with your database query

    if ($user === null) {
        return "User not found.";
    }

    // 3. Generate a unique token (Important for security)
    $token = generate_unique_token();

    // 4. Store the token in the database, associated with the user's email
    store_token_in_db($token, $email);  // Replace with your database update query

    // 5.  Create a link to the reset password page, including the token
    $reset_url = "reset_password.php?token=" . $token; //  'reset_password.php' is an example.
                                                       //  Adjust this to your actual reset password page.


    // 6. Send an email to the user with the reset link
    send_reset_password_email($user['username'], $reset_url); // Replace with your email sending function

    return "Password reset link sent to your email address.";
}


// ------------------  Placeholder Functions - Replace with your actual implementation ------------------

// Placeholder function to retrieve user by email (Replace with your database query)
function get_user_by_email($email) {
    // Example - Replace with your actual database query
    // This is just a placeholder to demonstrate the flow
    $users = [
        ['id' => 1, 'username' => 'john.doe', 'email' => 'john.doe@example.com'],
        ['id' => 2, 'username' => 'jane.smith', 'email' => 'jane.smith@example.com']
    ];
    foreach ($users as $user) {
        if ($user['email'] === $email) {
            return $user;
        }
    }
    return null;
}


// Placeholder function to generate a unique token.  Use a cryptographically secure method!
function generate_unique_token() {
    return bin2hex(random_bytes(32)); //  bin2hex for a strong hash.  random_bytes is generally better.
}


// Placeholder function to store the token in the database.
function store_token_in_db($token, $email) {
    //  Replace with your database update query
    //  Example (conceptual):
    //  $query = "UPDATE users SET reset_token = '$token' WHERE email = '$email'";
    //  execute($query);
}

// Placeholder function to send a password reset email.
function send_reset_password_email($username, $reset_url) {
    // Replace with your email sending implementation.
    echo "Sending reset email to: " . $username . " with link: " . $reset_url . "
";
    //In a real application, you'd send the email.
}


// ------------------ Example Usage ------------------

// Example 1:  Successful Reset Link Generation
$result = forgot_password('john.doe@example.com');
echo $result . "
";

// Example 2:  User Not Found
$result = forgot_password('nonexistent@example.com');
echo $result . "
";

?>
