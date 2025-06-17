

<?php

// Include the database connection file (assuming you have one)
require_once 'db_config.php';

/**
 * Registers a new user.
 *
 * @param string $username  The desired username.
 * @param string $password  The user's password.
 * @param string $email     The user's email address.
 * @return bool True if registration was successful, false otherwise.
 */
function registerUser(string $username, string $password, string $email) {

    // 1. Sanitize and Validate Input
    $username = trim($username);
    $password = trim($password);
    $email = trim($email);

    // Check for empty values
    if (empty($username) || empty($password) || empty($email)) {
        return false; // Or throw an exception:  throw new Exception("All fields are required.");
    }

    // Basic username validation (you should expand this!)
    if (strlen($username) < 3 || strlen($username) > 20) {
        return false; //  Or throw an exception: throw new Exception("Username must be between 3 and 20 characters.");
    }

    // Basic password validation (you should expand this!)
    if (strlen($password) < 6 || strlen($password) > 30) {
        return false; // Or throw an exception: throw new Exception("Password must be between 6 and 30 characters.");
    }

    // Email validation (using a simple regex - improve for production)
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return false; // Or throw an exception: throw new Exception("Invalid email format.");
    }

    // 2. Hash the Password (VERY IMPORTANT for security!)
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // 3. Prepare the SQL Query
    $sql = "INSERT INTO users (username, password, email) VALUES (?, ?, ?)";

    // 4. Prepare and Execute the Query
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sss", $username, $hashedPassword, $email); // s = string

    if ($stmt->execute()) {
        return true;
    } else {
        // Handle the error - log it, display a generic error message, etc.
        error_log("Error registering user: " . $stmt->error); // Log for debugging
        return false;
    }

    // 5. Close the statement
    $stmt->close();
}


// Example Usage (in your registration form processing logic):
/*
$username = $_POST['username'];
$password = $_POST['password'];
$email = $_POST['email'];

if (registerUser($username, $password, $email)) {
    echo "Registration successful! Please check your email to verify.";
    // Redirect to a welcome page, send a confirmation email, etc.
} else {
    echo "Registration failed. Please try again.";
}
*/

?>
