

<?php

// ... (database connection code as before) ...

// Function to reset password with prepared statements
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

    // Prepare the SQL query using a prepared statement
    $sql = "UPDATE users SET password = ? WHERE email = ?";

    // Prepare the statement
    $stmt = $conn->prepare($sql);

    // Bind the parameters
    $stmt->bind_param("s", $hashed_password, $email); // "s" indicates a string parameter

    // Execute the query
    if ($stmt->execute() === TRUE) {
        return "Password reset email sent successfully.";
    } else {
        return "Error resetting password. " . $conn->error;
    }

    $stmt->close();
    $conn->close();
}

// ... (rest of the code) ...
?>
