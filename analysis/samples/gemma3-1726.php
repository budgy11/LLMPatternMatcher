
<!DOCTYPE html>
<html>
<head>
  <title>Forgot Password</title>
</head>
<body>

  <h1>Forgot Password</h1>

  <form method="post" action="">
    <label for="email">Email:</label>
    <input type="email" id="email" name="email" required>
    <button type="submit">Reset Password</button>
  </form>

</body>
</html>


<?php

// Database connection details (replace with your actual values)
$dbHost = "localhost";
$dbName = "your_database_name";
$dbUser = "your_username";
$dbPass = "your_password";

// Function to handle the forgot password flow
function forgotPassword($email) {
    // 1. Check if the email exists in the database
    $conn = new mysqli($dbHost, $dbUser, $dbPass, $dbName);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $sql = "SELECT id, email FROM users WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email); // "s" indicates a string parameter
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 0) {
        // Email not found
        $stmt->close();
        $conn->close();
        return false;
    } else {
        // Email found, generate a token and send an email
        $token = bin2hex(random_bytes(32)); // Generate a random token
        $user_id = $result->fetch_assoc()['id'];

        // Store the token in the database
        $sql_insert = "INSERT INTO password_tokens (user_id, token, expires_at) VALUES (?, ?, NOW())";
        $stmt_insert = $conn->prepare($sql_insert);
        $stmt_insert->bind_param("is", $user_id, $token);
        $stmt_insert->execute();

        $stmt_insert->close();

        // Send the password reset email
        $to = $email;
        $subject = 'Password Reset';
        $message = "Please click the following link to reset your password: " . "<a href='" . $_SERVER['PHP_SELF'] . "?reset=$token'>" . $_SERVER['PHP_SELF'] . "?reset=$token</a>"; // VERY IMPORTANT - use $_SERVER['PHP_SELF']
        $headers = "From: your_email@example.com"; // Replace with your email address

        mail($to, $message, $headers);

        // Close the database connection
        $stmt->close();
        $conn->close();

        return true;
    }
}

// Example Usage (This would likely come from a form submission)
// $email = $_POST['email'];  // Get email from a form
// if (isset($email) && !empty($email)) {
//    if (forgotPassword($email)) {
//        echo "Password reset email sent to " . $email;
//    } else {
//        echo "Email not found or password reset failed.";
//    }
// }

?>
