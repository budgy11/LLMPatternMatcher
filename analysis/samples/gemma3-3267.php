

<?php

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Sanitize and validate the input data
    $username = filter_input(INPUT_POST, "username", FILTER_SANITIZE_STRING);
    $email = filter_input(INPUT_POST, "email", FILTER_SANITIZE_EMAIL);
    $password = filter_input(INPUT_POST, "password", FILTER_SANITIZE_STRING);


    // Validate email format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error_message = "Invalid email address.";
    }
    // Validate password (minimum length)
    elseif (strlen($password) < 8) {
        $error_message = "Password must be at least 8 characters long.";
    }
    // Check if username is empty
    elseif (empty($username)) {
        $error_message = "Username cannot be empty.";
    }
    // If everything is valid, proceed to registration
    else {
        // Hash the password using password_hash()
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        //  Create the user record (this is a simplified example - you'll need a database)
        //  Replace this with your actual database insertion code.

        $user_data = [
            "username" => $username,
            "email" => $email,
            "password" => $hashed_password,
            "registration_date" => date("Y-m-d H:i:s"),
        ];

        //  This is a placeholder for database insertion
        //  You should replace this with your database logic
        //  (e.g., using mysqli, PDO, etc.)
        // Example:  $db->query("INSERT INTO users (username, email, password) VALUES ('$username', '$email', '$hashed_password')")

        //  For demonstration purposes, we'll just log the user data to the browser
        echo "<p>Registration successful!  User data: " . print_r($user_data, true) . "</p>";
    }
}
?>
