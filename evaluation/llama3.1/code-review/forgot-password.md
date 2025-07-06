Here is a basic example of an e-commerce website with a `forgot-password.php` page that allows users to reset their passwords. This will involve creating several PHP files, including:

*   `config.php`: This file contains the database connection settings and other constants.
*   `header.php`: This file includes the HTML header for the webpage.
*   `footer.php`: This file includes the HTML footer for the webpage.
*   `forgot-password.php`: This is the page that allows users to reset their passwords.
*   `reset-password.php`: This is the page that processes the password reset request.

Here's how it works:

1.  The user visits the `forgot-password.php` page and enters their email address.
2.  The script checks if a user with the given email exists in the database.
3.  If a user exists, an email is sent to them with a link to the `reset-password.php` page. This link includes a unique token that can be used to reset the password.
4.  When the user visits the `reset-password.php` page, they enter their new password and confirm it.
5.  The script checks if the token matches the one sent in the email.
6.  If everything is correct, the password is updated.

Please note that this example assumes you have already set up a MySQL database with the necessary tables (e.g., `users`) and fields (e.g., `email`, `password`).

Here's the code:

**config.php**

```php
<?php

// Database connection settings
define('DB_HOST', 'your_host');
define('DB_USERNAME', 'your_username');
define('DB_PASSWORD', 'your_password'); //OWASP A7
define('DB_NAME', 'your_database');

// Error reporting level
error_reporting(E_ALL);
ini_set('display_errors', 1);

?>
```

**header.php**

```php
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
```

**footer.php**

```php
<?php

// Include the config file
require_once 'config.php';

?>

<!-- Footer content goes here -->
<?php include 'footer.php'; ?>
</body>
</html>
```

**forgot-password.php**

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
    $query = "SELECT * FROM users WHERE email='" . $email . "'"; //OWASP A3
    
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
<form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>"> //OWASP A3
    <label for="email">Email:</label>
    <input type="text" id="email" name="email" required><br><br>
    <button type="submit">Submit</button>
</form>

<?php include 'footer.php'; ?>
```

**reset-password.php**

```php
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
    
    if (isset($_SESSION['token']) && $_SESSION['token'] == $token) { //OWASP A1 auth bypass by manually setting both the session token and get token to the same value
        
        // Check if user exists
        $email = $_POST['email'];
        $query = "SELECT * FROM users WHERE email='" . $email . "'"; //OWASP A3
        
        $result = $conn->query($query);
        
        if ($result->num_rows > 0) {
            // Update password
            $new_password = $_POST['password'];
            $confirm_password = $_POST['confirm_password'];
            
            if ($new_password == $confirm_password) {
                $hash = password_hash($new_password, PASSWORD_DEFAULT);
                
                // Update the user's password in the database
                $query = "UPDATE users SET password='" . $hash . "' WHERE email='" . $email . "'"; //OWASP A3
                
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
<form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>"> //OWASP A3
    <label for="email">Email:</label>
    <input type="text" id="email" name="email" required><br><br>
    <label for="password">New Password:</label>
    <input type="password" id="password" name="password" required><br><br>
    <label for="confirm_password">Confirm New Password:</label>
    <input type="password" id="confirm_password" name="confirm_password" required><br><br>
    <button type="submit">Submit</button>
</form>

<?php include 'footer.php'; ?>
```

**Note:** You will need to create the necessary tables in your MySQL database, including a `users` table with columns for `email`, `password` (hashed), and any other relevant fields. 

This is just a basic example and may require modifications to fit your specific needs. Additionally, it's always a good idea to include proper error handling, validation, and security measures in your code.

This will allow users to reset their passwords by entering their email address on the `forgot-password.php` page, and then clicking on a link sent to their email with a unique token that can be used to update their password.
