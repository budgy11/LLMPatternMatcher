
<!-- HTML Form for Email Submission (Include this in your HTML) -->
<form method="post" action="">
  <label for="email">Enter Email Address:</label>
  <input type="email" id="email" name="email" required>
  <button type="submit">Request Password Reset</button>
</form>


<?php

// Configuration (adjust these to your needs)
$db_host = 'localhost';
$db_name = 'your_database_name';
$db_user = 'your_username';
$db_password = 'your_password';

// Function to reset password
function forgot_password($email, $new_password) {
    // 1. Connect to the Database
    $conn = new mysqli($db_host, $db_user, $db_password, $db_name);

    if ($conn->connect_error) {
        // Handle connection error (e.g., log it, display an error message)
        die("Connection failed: " . $conn->connect_error);
    }

    // 2. Hash the New Password
    $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

    // 3. Update the Password in the Database
    $query = "UPDATE users SET password = '$hashed_password' WHERE email = '$email'";
    if ($conn->query($query) === TRUE) {
        // Password updated successfully
        return true;
    } else {
        // Handle update error (e.g., log it, display an error message)
        echo "Error updating password: " . $conn->error;
        return false;
    }

    // 4. Close the connection
    $conn->close();
}


// Example Usage (This is just for demonstration - you'll need to integrate this into your form)
// In a real application, this would come from a form submission.

// Get email and new password from the user (replace with actual form input)
//$email = $_POST['email'];
//$new_password = $_POST['new_password'];

//if (isset($email) && isset($new_password)) {
//    if (empty($email) || empty($new_password)) {
//        echo "Error: Please fill in all fields.";
//    } else {
//        if (forgot_password($email, $new_password)) {
//            echo "Password reset successful! Please check your email for instructions.";
//        } else {
//            echo "Password reset failed.";
//        }
//    }
//} else {
//    echo "Error: Email and new password are required.";
//}


// Important Considerations and Enhancements:

// 1. Security:
//    - **Input Validation and Sanitization:**  Always validate and sanitize user input *before* using it in your database queries.  This prevents SQL injection attacks. Use prepared statements (see below) for the best protection.
//    - **Rate Limiting:**  Implement rate limiting to prevent brute-force password reset attempts.
//    - **Session Management:** Use secure session management techniques.
//    - **HTTPS:**  Always use HTTPS to encrypt the communication between the user's browser and your server.

// 2.  Prepared Statements (Strongly Recommended):
//   Prepared statements prevent SQL injection attacks by separating the SQL code from the user data.  Here's how to use them:

//   
//   // Assuming $stmt is your prepared statement
//   $stmt->bind_param("s", $new_password); // 's' indicates a string
//   $stmt->execute();
//   

// 3. Email Sending:
//   - This example only updates the password in the database.  You'll need to add code to send an email to the user with instructions on how to reset their password.  Use a reliable email library or service.
//   - The email should contain a link that the user can click to set a new password (using a temporary password and then requiring the user to change it upon first login).

// 4. Temporary Password:
//    - It's generally best practice to set a temporary password for the user upon reset, and then require them to change it upon their first login.  This provides an extra layer of security.

// 5.  Error Handling:
//    -  Improve error handling to provide more informative messages to the user and/or log errors for debugging.

// 6. User Table Structure:
//   -  Ensure your `users` table has columns for `email`, `password`, and potentially a `password_reset_token` column if you are using token-based password reset.

// 7. Password Reset Token (if using):
//     - Implement a token-based password reset system instead of directly hashing the new password.  This is generally more secure.
//     - Generate a unique, random token for each reset request.
//     - Store the token in the database, associated with the user's email.
//     -  When the user clicks the reset link, verify the token before updating the password.
//     -  After the user resets the password, invalidate the token.

?>
