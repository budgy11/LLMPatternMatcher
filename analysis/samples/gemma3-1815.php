

<?php

// Database connection details (replace with your actual credentials)
$db_host = 'localhost';
$db_name = 'your_database_name';
$db_user = 'your_username';
$db_password = 'your_password';

// Function to reset password
function forgot_password($email, $new_password, $db) {
    // 1. Validate Email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return "Invalid email format.";
    }

    // 2. Check if user exists
    $stmt = $db->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user) {
        return "User with this email does not exist.";
    }

    // 3. Hash the new password (Important for security!)
    $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

    // 4. Update the password in the database
    $stmt = $db->prepare("UPDATE users SET password = ? WHERE id = ?");
    $stmt->execute([$hashed_password, $user['id']]);

    return "Password reset email sent. Check your inbox!";
}


// --- Example Usage (Demonstration - NOT for direct use in a production environment)
// IMPORTANT:  This example shows how to use the function.  It's a simplified demonstration.
//   In a real application, you would typically have a form to collect the email and new password.

// Create a PDO database connection
try {
    $db = new PDO("mysql:host=$db_host;dbname=$db_name;charset=utf8mb4", $db_user, $db_password);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // Enable exception handling
} catch (PDOException $e) {
    echo "Database connection failed: " . $e->getMessage();
    exit;
}


// Example Usage (Replace with actual user input from a form)
$email = $_POST['email']; // Get email from form
$new_password = $_POST['new_password'];

// Call the forgot_password function
$result = forgot_password($email, $new_password, $db);

// Display the result
echo $result;

// Close the database connection (important!)
$db = null;

?>
