

<?php

// **Important:**  This is a simplified example for demonstration purposes only.
// In a real-world application, you **MUST** implement robust security measures 
// such as hashing passwords, using secure communication (HTTPS), and preventing brute-force attacks.

// 1. Database Connection (Replace with your actual database credentials)
$db_host = 'localhost';
$db_name = 'your_database_name';
$db_user = 'your_username';
$db_password = 'your_password';

// 2.  Helper Function to Generate a Unique Token
function generateUniqueToken() {
    return bin2hex(random_bytes(32));
}

// 3. Forgot Password Function
function forgotPassword($email, $token) {
    // 1. Validate Email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return "Invalid email address.";
    }

    // 2. Check if the email exists in the user database
    $query = "SELECT id, username FROM users WHERE email = '$email'";
    $result = mysqli_query($db_host, $query);

    if (mysqli_num_rows($result) == 0) {
        return "User not found.";
    }

    // 3.  Generate a Token and Store it in the Database
    $generated_token = generateUniqueToken();
    $query = "UPDATE users SET reset_token = '$generated_token' WHERE email = '$email'";
    mysqli_query($db_host, $query);

    // 4.  Send an Email (Replace with your email sending logic)
    $to = $email;
    $subject = 'Password Reset';
    $message = "Please use the following link to reset your password: " . '<a href="' . $_SERVER['PHP_SELF'] . '?token=' . $generated_token . '"' . ' >Reset Password</a>';  // Use the same page for link
    $headers = "From: your_email@example.com"; // Replace with your email address

    mail($to, $message, $headers);

    return "Password reset link has been sent to your email.";
}


// **Example Usage (for testing - do NOT expose this in a production environment)**
// Assuming you've got a form to submit email and token.
// You'd typically handle this in a web form.

// Example - Simulate receiving email and token
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST["email"];
    $token = $_POST["token"];

    if (empty($email) || empty($token)) {
      echo "Error: Email and Token are required.";
    } else {
        $result = forgotPassword($email, $token);
        echo $result;
    }
}
?>
