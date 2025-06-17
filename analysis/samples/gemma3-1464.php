

<?php

// Assume you have a database connection established.
// Replace these with your actual database connection details.
$servername = "localhost";
$username = "your_username";
$password = "your_password";
$dbname = "your_database";

// ---  Helper Functions (Important for Security) ---

/**
 * Generates a secure, random password string.
 *
 * @param int $length The desired length of the password.  Default is 12.
 * @return string A secure random password.
 */
function generateRandomPassword($length = 12) {
    $characters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*()_+-={}[]|;:\'",<.>?/';
    $password = '';
    $passwordLength = $length;
    $max = strlen($characters) - 1;
    
    for ($i = 0; $i < $passwordLength; $i++) {
        $password .= $characters[rand(0, $max)];
    }
    return $password;
}

/**
 *  Sends an email.
 *
 * @param string $to The email address to send to.
 * @param string $subject The email subject.
 * @param string $message The email body.
 * @return bool True on success, false on failure.
 */
function sendEmail($to, $subject, $message) {
    // Replace with your email sending configuration
    $to = $to;
    $from = "your_email@example.com"; // Your email address
    $headers = "From: " . $from . "\r
";
    $headers .= "Reply-To: " . $from . "\r
";

    mail($to, $subject, $message, $headers);
    return true; //  Assume success, you'll need to handle exceptions in real code
}


/**
 * Checks if an email exists in the database.
 *
 * @param string $email The email address to search for.
 * @return bool True if the email exists, false otherwise.
 */
function emailExists($email) {
    global $servername, $username, $password, $dbname;

    try {
        $conn = new mysqli($servername, $username, $password, $dbname);

        // Check connection
        if ($conn->connect_error) {
            throw new Exception("Connection failed: " . $conn->connect_error);
        }

        $sql = "SELECT email FROM users WHERE email = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $email); // "s" indicates a string parameter
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            return true;
        } else {
            return false;
        }

        $stmt->close();
        $conn->close();

    } catch (Exception $e) {
        error_log("Database error: " . $e->getMessage()); // Log the error
        return false; // Return false in case of error
    }
}



// --- Forgot Password Function ---
function forgotPassword($email) {
    // 1. Check if the email exists.
    if (!emailExists($email)) {
        return "Email not found."; //  Or you could log this
    }

    // 2. Generate a temporary password.
    $tempPassword = generateRandomPassword();

    // 3.  Store the temporary password in the database.  This is a simplified example.
    //     In a production environment, you would likely:
    //      - Send the password to an email (as in this example)
    //      - Set an expiry time for the password
    //      - Store the temporary password securely.

    try {
        global $servername, $username, $password, $dbname;
        $conn = new mysqli($servername, $username, $password, $dbname);

        // Check connection
        if ($conn->connect_error) {
            throw new Exception("Connection failed: " . $conn->connect_error);
        }

        $sql = "UPDATE users SET temp_password = ? WHERE email = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $tempPassword, $email);  // "ss" indicates two string parameters
        $stmt->execute();

        if ($stmt->affected_rows === 0) {
            throw new Exception("Failed to update temporary password.");
        }

        $stmt->close();
        $conn->close();

    } catch (Exception $e) {
        error_log("Database error: " . $e->getMessage());
        return "Failed to generate temporary password.  Please try again.";
    }

    // 4. Send the temporary password to the user's email address.
    $subject = "Forgot Password";
    $message = "Your temporary password is: " . $tempPassword . "

Please change your password as soon as possible.";
    if (sendEmail($email, $subject, $message)) {
        return "Temporary password has been sent to your email address.  Please change your password immediately.";
    } else {
        return "Failed to send temporary password. Please check your email settings.";
    }
}


// --- Example Usage (Testing) ---
//  This is for demonstration purposes only.  Don't put actual user input directly into this.
/*
$email = "testuser@example.com"; // Replace with a valid email address

$result = forgotPassword($email);
echo $result;
*/


?>
