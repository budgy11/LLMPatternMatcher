```php
<?php

// Include the header and config files
include 'header.php';
require_once 'config.php';

if (isset($_POST['email'])) {
    // Check if user exists in database
    $conn = new mysqli(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME);
    
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    
    $email = $_POST['email'];
    $query = "SELECT * FROM users WHERE email='" . $email . "'";
    
    $result = $conn->query($query);
    
    if ($result->num_rows > 0) {
        // If user exists, generate a token and send it to their email
        $token = bin2hex(random_bytes(32));
        
        // Send the email
        $subject = "Reset your password";
        $message = "
            <html>
                <body>
                    <p>Click on this link to reset your password:</p>
                    <p><a href='reset-password.php?token=$token'>Reset Password</a></p>
                </body>
            </html>";
        
        $to = $email;
        $from = 'your_email@example.com';
        $headers = "From: " . $from;
        mail($to, $subject, $message, $headers);
        
        // Store the token in a session
        $_SESSION['token'] = $token;
    }
    
    $conn->close();
}

?>

<!-- Display form to enter email address -->
<form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
    <label for="email">Email:</label>
    <input type="text" id="email" name="email" required><br><br>
    <button type="submit">Submit</button>
</form>

<?php include 'footer.php'; ?>
```