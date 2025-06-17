

<?php

// Assuming you have a database connection established
// and you have a 'users' table with a 'email' field.

// 1. Check if the request is a password reset request.
if (isset($_POST['reset_password_request'])) {
  // Validate the email address (important for security)
  $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);

  if (empty($email)) {
    // Handle empty email case
    echo "<p style='color:red;'>Please enter your email address.</p>";
    exit;
  }

  //  Add more robust validation here, like checking email format and length if needed

  //  Generate a unique token for the password reset request
  $token = bin2hex(random_bytes(32));

  //  Store the token and the user's email in the database
  //  This is just an example; adjust the query based on your database schema.
  $query = "INSERT INTO password_resets (user_email, reset_token, created_at) 
            VALUES ('$email', '$token', NOW())";

  // Assuming you're using MySQLi (adjust accordingly)
  $result = $mysqli->query($query);

  if ($result) {
    //  Send an email to the user with a link to reset their password
    //  Replace 'your_email_address' with your email address for sending
    $to = $email;
    $subject = 'Password Reset';
    $message = "Click on the following link to reset your password: " . 
                '<a href="' . $_SERVER['PHP_SELF'] . '?reset_token=' . $token . '">Reset Password</a>';
    $headers = 'From: your_email_address'; // Replace with your sending email

    if(mail($to, $subject, $message, $headers)){
        echo "<p style='color:green;'>Password reset link has been sent to your email.</p>";
    } else {
        echo "<p style='color:red;'>Error sending password reset email. Please try again later.</p>";
    }

  } else {
    // Handle database error
    echo "<p style='color:red;'>Error: " . $mysqli->error . "</p>";
  }
}

// 2. Handling the Password Reset Token
if (isset($_GET['reset_token'])) {
  $token = filter_input(INPUT_GET, 'reset_token', FILTER_SANITIZE_STRING);

  // Check if the token exists in the database
  $query = "SELECT user_email, password_reset_code FROM password_resets WHERE password_reset_code = '$token'";
  $result = $mysqli->query($query);

  if ($result) {
    $row = $result->fetch_assoc();
    $user_email = $row['user_email'];
    $reset_token = $row['password_reset_code'];

    // 3. Allow the user to set a new password
    //  This part would typically be in a separate form/page
    echo "<h1>Reset Password</h1>";
    echo "<form method='post' action='" . $_SERVER['PHP_SELF'] . "'>";
    echo "<label for='new_password'>New Password:</label><br>";
    echo "<input type='password' id='new_password' name='new_password' required><br><br>";
    echo "<label for='confirm_password'>Confirm Password:</label><br>";
    echo "<input type='password' id='confirm_password' name='confirm_password' required><br><br>";
    echo "<input type='submit' value='Reset Password'>";
    echo "</form>";

  } else {
    // Token not found
    echo "<p style='color:red;'>Invalid reset token. Please request a new one.</p>";
  }
}

// 4. Process the New Password (Form Submission)
if (isset($_POST['new_password']) && isset($_POST['confirm_password'])) {
    $new_password = filter_input(INPUT_POST, 'new_password', FILTER_SANITIZE_STRING);
    $confirm_password = filter_input(INPUT_POST, 'confirm_password', FILTER_SANITIZE_STRING);

    if ($new_password != $confirm_password) {
        echo "<p style='color:red;'>Passwords do not match.</p>";
    } else {
        // Hash the new password before saving it to the database
        $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

        //  Update the user's password in the database
        $query = "UPDATE users SET password = '$hashed_password' WHERE email = '$user_email'"; // Assuming you have a $user_email variable

        // Replace $user_email with the actual email address to update
        if ($mysqli->query($query)) {
            // Delete the reset token from the database
            $query = "DELETE FROM password_resets WHERE password_reset_code = '$token'";
            if ($mysqli->query($query)) {
                echo "<p style='color:green;'>Password reset successfully. Please log in.</p>";
            } else {
                echo "<p style='color:red;'>Error deleting token.</p>";
            }

        } else {
            echo "<p style='color:red;'>Error updating password.</p>";
        }
    }
}

?>
