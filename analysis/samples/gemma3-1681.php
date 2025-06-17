

<?php

// Database connection details - REPLACE WITH YOUR ACTUAL CREDENTIALS!
$host = "localhost";
$username = "your_username";
$password = "your_password";
$database = "your_database_name";

// ---------------------  Forgot Password Function ---------------------

/**
 * Resets a user's password.
 *
 * @param string $email The user's email address.
 * @return string  A message indicating success or failure.
 */
function forgot_password(string $email): string {
    // 1. Check if the email exists in the database.
    $conn = new mysqli($host, $username, $password, $database);

    if ($conn->connect_error) {
        return "Failed to connect to the database: " . $conn->connect_error;
    }

    $query = "SELECT id, email FROM users WHERE email = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $email); // "s" indicates a string parameter
    $stmt->execute();

    $result = $stmt->get_result();

    if ($result->num_rows == 0) {
        $stmt->close();
        $conn->close();
        return "Email not found.";
    }

    $user = $result->fetch_assoc();
    $stmt->close();
    $conn->close();

    // 2. Generate a unique token for password reset.
    $reset_token = bin2hex(random_bytes(32));

    // 3.  Store the token in the database.
    $conn = new mysqli($host, $username, $password, $database);

    if ($conn->connect_error) {
        return "Failed to connect to the database: " . $conn->connect_error;
    }

    $query = "UPDATE users SET reset_token = ? WHERE email = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ss", $reset_token, $email); // "s" indicates a string parameter
    $stmt->execute();
    $stmt->close();
    $conn->close();


    // 4. Send an email with the reset link. (IMPLEMENT THIS PART - See below)
    $subject = "Password Reset Request";
    $message = "Click the link below to reset your password: " . $_SERVER["PHP_SELF"] . "?email=$email&token=$reset_token"; // Using the current page to generate the URL
    $headers = "From: your_email@example.com";  // Replace with your email

    mail($email, $subject, $message, $headers);


    return "Password reset link has been sent to your email address.";
}

// ---------------------  Example Usage (For testing - DO NOT USE IN PRODUCTION) ---------------------
// This code is for testing only.  Never expose your actual database credentials in a production environment.

// Assuming you have a form to submit the email address
// (See example HTML form below)

//  For testing, you can trigger the function directly like this:
//  $result = forgot_password("test@example.com");
//  echo $result;

?>
