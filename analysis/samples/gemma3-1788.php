

<?php

// Database connection details - REPLACE WITH YOUR ACTUAL DETAILS
$db_host = "localhost";
$db_name = "your_database_name";
$db_user = "your_database_username";
$db_pass = "your_database_password";

// Function to reset password
function forgot_password($email, $new_password, $db_host, $db_name, $db_user, $db_pass) {
    // Validate email format (basic check)
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return "Invalid email address.";
    }

    // Connect to the database
    $conn = new mysqli($db_host, $db_user, $db_pass, $db_name);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Hash the new password
    $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

    // Prepare the SQL query
    $sql = "UPDATE users SET password = '$hashed_password' WHERE email = '$email'";

    // Execute the query
    if ($conn->query($sql) === TRUE) {
        return "Password reset email sent successfully.";
    } else {
        return "Error resetting password. " . $conn->error;
    }

    $conn->close();
}


// Example Usage (for demonstration - this would be handled by a form submission)
// $email = $_POST['email'];
// $new_password = $_POST['new_password'];

// if (isset($email) && isset($new_password)) {
//     $result = forgot_password($email, $new_password, $db_host, $db_name, $db_user, $db_pass);
//     echo $result;
// } else {
//     echo "Please fill in the email and new password fields.";
// }


//  ---  IMPORTANT:  Replace this with your actual form handling code  ---
//  This example shows the basic structure. You'll need to add HTML form elements
//  to capture the email and new password.  Make sure to sanitize and validate
//  all user input to prevent security vulnerabilities.
//
//  Example HTML Form:
//
//  <form method="post" action="">
//      <label for="email">Email:</label>
//      <input type="email" id="email" name="email" required><br><br>
//
//      <label for="new_password">New Password:</label>
//      <input type="password" id="new_password" name="new_password" required><br><br>
//
//      <button type="submit">Reset Password</button>
//  </form>
?>
