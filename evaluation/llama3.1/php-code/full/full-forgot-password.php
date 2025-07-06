
<?php

// Database connection settings
define('DB_HOST', 'your_host');
define('DB_USERNAME', 'your_username');
define('DB_PASSWORD', 'your_password');
define('DB_NAME', 'your_database');

// Error reporting level
error_reporting(E_ALL);
ini_set('display_errors', 1);

?>

<?php

// Include the config file
require_once 'config.php';

?>

<!DOCTYPE html>
<html>
<head>
    <title>Forgot Password</title>
    <link rel="stylesheet" href="styles.css">
</head>

<body>
    <!-- Header content goes here -->
    <?php include 'navbar.php'; ?>

<?php

// Include the config file
require_once 'config.php';

?>

<!-- Footer content goes here -->
<?php include 'footer.php'; ?>
</body>
</html>

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

<?php

// Include the header and config files
include 'header.php';
require_once 'config.php';

if (isset($_GET['token'])) {
    // Check if token matches session token
    $conn = new mysqli(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME);
    
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    
    $token = $_GET['token'];
    
    if (isset($_SESSION['token']) && $_SESSION['token'] == $token) {
        
        // Check if user exists
        $email = $_POST['email'];
        $query = "SELECT * FROM users WHERE email='" . $email . "'";
        
        $result = $conn->query($query);
        
        if ($result->num_rows > 0) {
            // Update password
            $new_password = $_POST['password'];
            $confirm_password = $_POST['confirm_password'];
            
            if ($new_password == $confirm_password) {
                $hash = password_hash($new_password, PASSWORD_DEFAULT);
                
                // Update the user's password in the database
                $query = "UPDATE users SET password='" . $hash . "' WHERE email='" . $email . "'";
                
                $conn->query($query);
                
                echo "<p>Password updated successfully!</p>";
            } else {
                echo "<p>Passwords don't match.</p>";
            }
        } else {
            echo "<p>User not found.</p>";
        }
    } else {
        echo "<p>Invalid token.</p>";
    }
    
    $conn->close();
}

?>

<!-- Display form to enter new password and confirm it -->
<form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
    <label for="email">Email:</label>
    <input type="text" id="email" name="email" required><br><br>
    <label for="password">New Password:</label>
    <input type="password" id="password" name="password" required><br><br>
    <label for="confirm_password">Confirm New Password:</label>
    <input type="password" id="confirm_password" name="confirm_password" required><br><br>
    <button type="submit">Submit</button>
</form>

<?php include 'footer.php'; ?>
