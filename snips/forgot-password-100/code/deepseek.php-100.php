
<?php
// Database connection details
$host = 'localhost';
$username = 'your_username';
$password = 'your_password';
$database = 'your_database';

// Connect to database
$conn = new mysqli($host, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_POST['email'])) {
    $email = $_POST['email'];
    
    // Validate email format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        header("Location: forgot_password_form.php?error=Invalid%20email%20format");
        exit();
    }
    
    // Check if email exists in database
    $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows == 0) {
        header("Location: forgot_password_form.php?error=Email%20not%20found");
        exit();
    }
    
    // Generate reset token
    $resetToken = bin2hex(random_bytes(16));
    $expires = strtotime('+30 minutes');
    
    // Update database with reset token and expiration time
    $stmt = $conn->prepare("UPDATE users SET reset_token = ?, reset_expires = ? WHERE email = ?");
    $stmt->bind_param("sis", $resetToken, $expires, $email);
    $stmt->execute();
    
    // Send reset password email
    $to = $email;
    $subject = "Reset Your Password";
    $message = "
        <html>
            <body>
                <h2>Forgot Password?</h2>
                <p>Please click the link below to reset your password:</p>
                <a href='http://your_website.com/reset_password.php?id=" . $id . "&token=" . $resetToken . "'>Reset Password</a>
                <br><br>
                <p>If you didn't request a password reset, please ignore this email.</p>
            </body>
        </html>
    ";
    
    $headers = "MIME-Version: 1.0" . "\r
";
    $headers .= "Content-type:text/html;charset=UTF-8" . "\r
";
    $headers .= "From: your_email@example.com" . "\r
";
    
    mail($to, $subject, $message, $headers);
    
    header("Location: forgot_password_form.php?error=Password%20reset%20link%20sent%20to%20your%20email");
    exit();
}
?>


<?php
// Database connection details
$host = 'localhost';
$username = 'your_username';
$password = 'your_password';
$database = 'your_database';

// Connect to database
$conn = new mysqli($host, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_GET['id']) && isset($_GET['token'])) {
    $id = $_GET['id'];
    $token = $_GET['token'];
    
    // Check if token exists and hasn't expired
    $stmt = $conn->prepare("SELECT id, reset_token, reset_expires FROM users WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows == 0) {
        header("Location: forgot_password_form.php?error=Invalid%20link");
        exit();
    }
    
    $row = $result->fetch_assoc();
    if ($row['reset_token'] != $token || $row['reset_expires'] < time()) {
        header("Location: forgot_password_form.php?error=Expired%20or%20invalid%20link");
        exit();
    }
    
} elseif (isset($_POST['id']) && isset($_POST['token'])) {
    $id = $_POST['id'];
    $token = $_POST['token'];
    $newPassword = $_POST['new_password'];
    $confirmPassword = $_POST['confirm_password'];
    
    if ($newPassword != $confirmPassword) {
        header("Location: reset_password.php?id=$id&token=$token&error=Passwords%20do%20not%20match");
        exit();
    }
    
    // Update password
    $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
    
    $stmt = $conn->prepare("UPDATE users SET password = ?, reset_token = NULL WHERE id = ?");
    $stmt->bind_param("si", $hashedPassword, $id);
    $stmt->execute();
    
    // Clear the reset token
    $stmt = $conn->prepare("UPDATE users SET reset_token = NULL, reset_expires = NULL WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    
    header("Location: login.php?success=Password%20reset%20successful");
    exit();
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Forgot Password</title>
</head>
<body>
    <h2>Forgot Password</h2>
    <form action="send_reset_email.php" method="post">
        <label for="email">Email:</label><br>
        <input type="email" id="email" name="email" required><br><br>
        <button type="submit">Request Reset</button>
    </form>
    <?php
    if (isset($_GET['error'])) {
        echo "<p style='color:red;'>" . $_GET['error'] . "</p>";
    }
    ?>
</body>
</html>


<?php
include('db_connection.php');

if (isset($_POST['email'])) {
    $email = $_POST['email'];
    
    // Check if email exists in the database
    $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        // Generate a random token
        $token = bin2hex(random_bytes(16));
        
        // Update the user's reset token and expiration time
        $expires = date('Y-m-d H:i:s', strtotime('+1 hour'));
        $stmt = $conn->prepare("UPDATE users SET reset_token = ?, reset_expires = ? WHERE email = ?");
        $stmt->bind_param("sss", $token, $expires, $email);
        $stmt->execute();

        // Send the reset email
        $reset_link = "http://$_SERVER[HTTP_HOST]/reset_password.php?token=$token";
        
        $to = $email;
        $subject = 'Password Reset Request';
        $message = "
            <html>
                <head></head>
                <body>
                    <p>Please click the link below to reset your password:</p>
                    <a href='$reset_link'>Reset Password</a><br>
                    <small>This link will expire in 1 hour.</small>
                </body>
            </html>
        ";
        $headers = "From: no-reply@yourdomain.com\r
";
        $headers .= "MIME-Version: 1.0\r
";
        $headers .= "Content-Type: text/html; charset=UTF-8\r
";

        mail($to, $subject, $message, $headers);

        echo "<script>alert('Password reset email has been sent!');</script>";
        echo "<script>window.location.href = 'login.php';</script>";
    } else {
        header("Location: forgot_password.php?error=Email%20not%20found.");
    }
}
?>


<?php
include('db_connection.php');

if (!isset($_GET['token'])) {
    header("Location: forgot_password.php?error=No%20token%20provided.");
    exit();
}

$token = $_GET['token'];

// Check if token is valid and not expired
$stmt = $conn->prepare("
    SELECT id, email 
    FROM users 
    WHERE reset_token = ? AND reset_expires > NOW()
");
$stmt->bind_param("s", $token);
$stmt->execute();
$result = $stmt->get_result();

if (!$row = $result->fetch_assoc()) {
    header("Location: forgot_password.php?error=Invalid%20or%20expired%20link.");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Reset Password</title>
</head>
<body>
    <h2>Reset Password</h2>
    <?php
    if (isset($_GET['error'])) {
        echo "<p style='color:red;'>" . $_GET['error'] . "</p>";
    }
    ?>
    <form action="reset_password.php" method="post">
        <input type="hidden" name="token" value="<?php echo $token; ?>">
        <label for="new_password">New Password:</label><br>
        <input type="password" id="new_password" name="new_password" required><br><br>
        <label for="confirm_password">Confirm New Password:</label><br>
        <input type="password" id="confirm_password" name="confirm_password" required><br><br>
        <button type="submit">Reset Password</button>
    </form>
</body>
</html>

<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $token = $_POST['token'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    if ($new_password !== $confirm_password) {
        header("Location: reset_password.php?token=$token&error=Passwords%20do%20not%20match.");
        exit();
    }

    // Check token again (security measure)
    $stmt = $conn->prepare("
        SELECT id, email 
        FROM users 
        WHERE reset_token = ? AND reset_expires > NOW()
    ");
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $result = $stmt->get_result();

    if (!$row = $result->fetch_assoc()) {
        header("Location: forgot_password.php?error=Invalid%20or%20expired%20link.");
        exit();
    }

    // Update the password
    $new_password_hashed = password_hash($new_password, PASSWORD_DEFAULT);
    
    $stmt = $conn->prepare("
        UPDATE users 
        SET password = ?, reset_token = NULL, reset_expires = NULL 
        WHERE id = ?
    ");
    $stmt->bind_param("si", $new_password_hashed, $row['id']);
    $stmt->execute();

    echo "<script>alert('Password has been reset successfully!');</script>";
    echo "<script>window.location.href = 'login.php';</script>";
}
?>


<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "your_database";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>


<?php
// Include database connection
include('db_connect.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get input email or username
    $input = trim($_POST['email_username']);
    
    if (empty($input)) {
        die("Please enter your email or username");
    }
    
    // Prepare SQL statement to check user existence
    $stmt = $conn->prepare("SELECT id, email FROM users WHERE email = ? OR username = ?");
    $stmt->bind_param("ss", $input, $input);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows == 0) {
        die("No account found with that email or username");
    }
    
    // Get user data
    $user = $result->fetch_assoc();
    
    // Generate reset token
    $resetToken = bin2hex(random_bytes(16));
    $tokenExpiry = date('Y-m-d H:i:s', strtotime('+1 hour'));
    
    // Store token in database
    $stmt = $conn->prepare("UPDATE users SET reset_token = ?, reset_expiry = ? WHERE id = ?");
    $stmt->bind_param("sss", $resetToken, $tokenExpiry, $user['id']);
    if (!$stmt->execute()) {
        die("An error occurred. Please try again later");
    }
    
    // Send reset email
    $to = $user['email'];
    $subject = "Password Reset Request";
    $message = "Please click the following link to reset your password:

";
    $message .= "http://yourwebsite.com/reset-password.php?token=" . $resetToken;
    $headers = "From: no-reply@yourwebsite.com" . "\r
";
    
    if (!mail($to, $subject, $message, $headers)) {
        die("Error sending email. Please try again later");
    }
    
    echo "A password reset link has been sent to your email address.";
}
?>


<?php
// This code should be added to reset-password.php after verifying the token

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $newPassword = trim($_POST['new_password']);
    
    if (empty($newPassword)) {
        die("Please enter a new password");
    }
    
    // Hash the new password
    $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
    
    // Update password in database
    $stmt = $conn->prepare("UPDATE users SET password = ?, reset_token = NULL, reset_expiry = NULL WHERE id = ?");
    $stmt->bind_param("si", $hashedPassword, $user['id']);
    
    if (!$stmt->execute()) {
        die("An error occurred. Please try again");
    }
    
    echo "Your password has been updated successfully!";
}
?>


<?php
// forgot_password.php

require 'database_connection.php'; // Include your database connection file

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];

    // Check if email exists in the database
    $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        echo "Email not registered!";
        exit();
    }

    // Generate a random token
    $token = bin2hex(random_bytes(16));
    $expires = date("Y-m-d H:i:s", time() + 3600); // Token expires in 1 hour

    // Insert the token into the database
    $stmt = $conn->prepare("INSERT INTO password_reset (user_id, token, expires) 
                           SELECT id, ?, ? FROM users WHERE email = ?");
    $stmt->bind_param("sss", $token, $expires, $email);
    $result = $stmt->execute();

    if (!$result) {
        die("Error: " . $conn->error);
    }

    // Send reset email
    $to = $email;
    $subject = "Password Reset Request";
    
    $headers = "From: your_email@example.com\r
";
    $headers .= "Reply-To: your_email@example.com\r
";
    $headers .= "X-Mailer: PHP/" . phpversion();

    $message = "To reset your password, click the following link:

";
    $message .= "http://yourwebsite.com/reset_password.php?token=" . urlencode($token) . "

";
    $message .= "If you did not request a password reset, please ignore this email.";

    if (mail($to, $subject, $message, $headers)) {
        echo "An email has been sent to your address. Please check your inbox.";
    } else {
        echo "There was an error sending the email!";
    }

    // Close database connection
    $conn->close();
}


<?php
// reset_password.php

require 'database_connection.php'; // Include your database connection file

session_start();

if (isset($_GET['token'])) {
    $token = $_GET['token'];

    // Check if token exists in the database and hasn't expired
    $stmt = $conn->prepare("SELECT user_id, email FROM password_reset 
                           WHERE token = ? AND expires > NOW()");
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        die("Invalid or expired reset link!");
    }

    // Store user data in session
    $row = $result->fetch_assoc();
    $_SESSION['reset_user_id'] = $row['user_id'];
    $_SESSION['reset_email'] = $row['email'];

    // Close database connection
    $conn->close();

    // Redirect to password reset form
    header("Location: change_password.php");
} else {
    die("No token provided!");
}


<?php
// change_password.php

require 'database_connection.php'; // Include your database connection file

session_start();

if (isset($_SESSION['reset_user_id']) && isset($_POST['new_password'])) {
    $userId = $_SESSION['reset_user_id'];
    $newPassword = password_hash($_POST['new_password'], PASSWORD_DEFAULT);

    // Update the user's password
    $stmt = $conn->prepare("UPDATE users SET password = ? WHERE id = ?");
    $stmt->bind_param("si", $newPassword, $userId);
    $result = $stmt->execute();

    if ($result) {
        // Clear the session variables and redirect to login page
        unset($_SESSION['reset_user_id']);
        unset($_SESSION['reset_email']);
        header("Location: login.php");
    } else {
        die("Error updating password!");
    }

    // Close database connection
    $conn->close();
} else {
    die("Invalid request!");
}


<?php
// Database connection details
$host = 'localhost';
$dbUsername = 'root';
$dbPassword = '';
$dbName = 'mydatabase';

// Connect to database
$conn = new mysqli($host, $dbUsername, $dbPassword, $dbName);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$emailOrUsername = $_POST['email_or_username'];

// Check if the email or username exists in the database
$sql = "SELECT * FROM users WHERE email='$emailOrUsername' OR username='$emailOrUsername'";
$result = $conn->query($sql);

if ($result->num_rows == 0) {
    die("Email or username does not exist.");
}

$row = $result->fetch_assoc();

// Generate a random token
$token = bin2hex(random_bytes(16));

// Store the token in the database with an expiration time (e.g., 1 hour)
$currentDate = date('Y-m-d H:i:s');
$expirationDate = date('Y-m-d H:i:s', strtotime($currentDate . ' + 1 hour'));

$sql = "UPDATE users SET reset_token='$token', token_expiration='$expirationDate' WHERE id=" . $row['id'];
$conn->query($sql);

// Send email with the reset link
$to = $emailOrUsername;
$subject = "Password Reset Request";
$message = "Please click the following link to reset your password: http://example.com/reset_password.php?token=$token";
$headers = "From: webmaster@example.com";

mail($to, $subject, $message, $headers);

echo "An email with a reset link has been sent to you.";

$conn->close();
?>


<?php
session_start();

// Check if token is provided
if (!isset($_GET['token'])) {
    die("Invalid request.");
}

$token = $_GET['token'];

// Database connection details
$host = 'localhost';
$dbUsername = 'root';
$dbPassword = '';
$dbName = 'mydatabase';

$conn = new mysqli($host, $dbUsername, $dbPassword, $dbName);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if the token exists and is valid
$sql = "SELECT * FROM users WHERE reset_token='$token' AND token_expiration > NOW()";
$result = $conn->query($sql);

if ($result->num_rows == 0) {
    die("Invalid or expired token.");
}

// Show password reset form
?>

<!DOCTYPE html>
<html>
<head>
    <title>Reset Password</title>
</head>
<body>
    <h2>Reset Password</h2>
    <form action="reset_password.php" method="post">
        <input type="password" name="new_password" placeholder="Enter new password" required>
        <br><br>
        <input type="password" name="confirm_password" placeholder="Confirm new password" required>
        <br><br>
        <button type="submit">Reset Password</button>
    </form>
</body>
</html>

<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $newPassword = $_POST['new_password'];
    $confirmPassword = $_POST['confirm_password'];

    if ($newPassword !== $confirmPassword) {
        die("Passwords do not match.");
    }

    // Update the password in the database
    $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
    $sql = "UPDATE users SET password='$hashedPassword', reset_token='', token_expiration='' WHERE reset_token='$token'";
    if ($conn->query($sql)) {
        echo "Password has been reset successfully!";
    } else {
        die("Error resetting password.");
    }
}

$conn->close();
?>


<?php
// Database configuration
$DB_HOST = 'localhost';
$DB_USER = 'username';
$DB_PASS = 'password';
$DB_NAME = 'database_name';

// Connect to database
$conn = new mysqli($DB_HOST, $DB_USER, $DB_PASS, $DB_NAME);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

function sendPasswordResetEmail($email, $token) {
    $to = $email;
    $subject = 'Password Reset Request';
    $message = '
        <html>
            <body>
                <h2>Password Reset</h2>
                <p>Please click the link below to reset your password:</p>
                <a href="http://yourwebsite.com/reset-password.php?token=' . $token . '">Reset Password</a>
                <br><br>
                <p>If you did not request this password reset, please ignore this email.</p>
            </body>
        </html>';
    $headers = "MIME-Version: 1.0" . "\r
";
    $headers .= "Content-type:text/html;charset=UTF-8" . "\r
";
    
    mail($to, $subject, $message, $headers);
}

function forgotPassword($email) {
    global $conn;
    
    // Check if email exists in database
    $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows === 0) {
        return "Email not found in our database!";
    }
    
    // Generate reset token
    $token = bin2hex(mt_rand());
    $expires = date('Y-m-d H:i:s', time() + 3600); // Token expires after 1 hour
    
    // Update the reset token and expiration time in the database
    $stmt = $conn->prepare("UPDATE users SET reset_token = ?, reset_expires = ? WHERE email = ?");
    $stmt->bind_param("sss", $token, $expires, $email);
    $stmt->execute();
    
    // Send password reset link to user's email
    sendPasswordResetEmail($email, $token);
    
    return "Password reset instructions have been sent to your email!";
}

function validateResetToken($token) {
    global $conn;
    
    // Check if token exists and hasn't expired
    $stmt = $conn->prepare("SELECT id, email FROM users WHERE reset_token = ? AND reset_expires > ?");
    $stmt->bind_param("ss", $token, date('Y-m-d H:i:s'));
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows === 0) {
        return false;
    }
    
    return $result->fetch_assoc();
}

function resetPassword($token, $newPassword) {
    global $conn;
    
    // Validate token
    $user = validateResetToken($token);
    if (!$user) {
        return "Invalid or expired reset link!";
    }
    
    // Hash the new password
    $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
    
    // Update the user's password in the database
    $stmt = $conn->prepare("UPDATE users SET password = ?, reset_token = NULL, reset_expires = NULL WHERE email = ?");
    $stmt->bind_param("ss", $hashedPassword, $user['email']);
    $stmt->execute();
    
    return "Your password has been successfully reset!";
}

// Example usage:
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $result = forgotPassword($email);
    echo $result;
}

// Reset password example
if (isset($_GET['token'])) {
    if (!empty($_POST)) {
        $newPassword = $_POST['password'];
        $result = resetPassword($_GET['token'], $newPassword);
        echo $result;
    }
}
?>


<?php
session_start();

// Database connection details
$host = 'localhost';
$user = 'root';
$password = '';
$dbname = 'mydatabase';

// Connect to database
$conn = new mysqli($host, $user, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the email from POST request
    $email = $_POST['email'];
    
    // Validate email format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['error'] = "Invalid email format!";
        header("Location: forgot_password.html");
        exit();
    }

    // Check if the email exists in the database
    $sql = "SELECT id FROM users WHERE email=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 0) {
        $_SESSION['error'] = "Email not found in our records!";
        header("Location: forgot_password.html");
        exit();
    }

    // Generate a unique token for password reset
    $token = bin2hex(random_bytes(16));
    $expires = date('Y-m-d H:i:s', time() + 3600); // Token expires after 1 hour

    // Insert the token into the database
    $sql = "INSERT INTO password_resets (user_id, token, created_at, expires_at) 
            VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $row = $result->fetch_assoc();
    $stmt->bind_param("ssss", $row['id'], $token, date('Y-m-d H:i:s'), $expires);

    if (!$stmt->execute()) {
        $_SESSION['error'] = "An error occurred. Please try again later!";
        header("Location: forgot_password.html");
        exit();
    }

    // Send email with reset link
    $to = $email;
    $subject = "Password Reset Request";
    $message = "
        <html>
            <head>
                <title>Password Reset</title>
            </head>
            <body>
                <h2>Password Reset Request</h2>
                <p>Please click the following link to reset your password:</p>
                <a href='http://localhost/reset_password.php?token=$token'>Reset Password</a>
                <p>If you did not request a password reset, please ignore this email.</p>
            </body>
        </html>
    ";
    $headers = "MIME-Version: 1.0" . "\r
";
    $headers .= "Content-type:text/html;charset=UTF-8" . "\r
";
    $headers .= "From: <your-email@example.com>" . "\r
";

    if (mail($to, $subject, $message, $headers)) {
        $_SESSION['success'] = "A password reset link has been sent to your email!";
        header("Location: forgot_password.html");
    } else {
        $_SESSION['error'] = "An error occurred while sending the email. Please try again later!";
        header("Location: forgot_password.html");
    }
}

$conn->close();
?>


<?php
session_start();

// Database connection details
$host = 'localhost';
$user = 'root';
$password = '';
$dbname = 'mydatabase';

// Connect to database
$conn = new mysqli($host, $user, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_GET['token'])) {
    // Validate the token
    $token = $_GET['token'];
    
    $sql = "SELECT * FROM password_resets WHERE token=? AND expires_at > NOW()";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 0) {
        $_SESSION['error'] = "Invalid or expired token!";
        header("Location: forgot_password.html");
        exit();
    }

    // Get user ID from the token
    $row = $result->fetch_assoc();
    $user_id = $row['user_id'];
} else {
    $_SESSION['error'] = "No token provided!";
    header("Location: forgot_password.html");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get new password and confirm
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    if ($password != $confirm_password) {
        $_SESSION['error'] = "Passwords do not match!";
        header("Location: reset_password.php?token=$token");
        exit();
    }

    // Update the password in the database
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    $sql = "UPDATE users SET password=? WHERE id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("si", $hashed_password, $user_id);

    if (!$stmt->execute()) {
        $_SESSION['error'] = "An error occurred while updating the password!";
        header("Location: reset_password.php?token=$token");
        exit();
    }

    // Delete the token after use
    $sql = "DELETE FROM password_resets WHERE token=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $token);
    $stmt->execute();

    $_SESSION['success'] = "Your password has been reset successfully!";
    header("Location: login.php");
}

$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Reset Password</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #f0f0f0;
        }
        .container {
            max-width: 500px;
            margin: 0 auto;
            background-color: white;
            padding: 30px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .form-group {
            margin-bottom: 20px;
        }
        input {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-sizing: border-box;
        }
        button {
            background-color: #4CAF50;
            color: white;
            padding: 14px 20px;
            margin: 8px 0;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            width: 100%;
        }
        button:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
    <div class="container">
        <?php
        if (isset($_SESSION['error'])) {
            echo "<p style='color: red; margin-bottom: 10px;'>$_SESSION[error]</p>";
            unset($_SESSION['error']);
        }
        ?>
        <h2>Reset Password</h2>
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
            <input type="hidden" name="token" value="<?php echo $token; ?>">
            <div class="form-group">
                <label for="password">New Password:</label>
                <input type="password" id="password" name="password" required>
            </div>
            <div class="form-group">
                <label for="confirm_password">Confirm New Password:</label>
                <input type="password" id="confirm_password" name="confirm_password" required>
            </div>
            <button type="submit">Reset Password</button>
        </form>
    </div>
</body>
</html>


<?php
// Database configuration
$host = 'localhost';
$dbname = 'mydatabase';
$username = 'root';
$password = '';

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get email from form
    $email = $_POST['email'];

    // Check if email exists in database
    $stmt = $conn->prepare("SELECT * FROM users WHERE email = :email");
    $stmt->execute(['email' => $email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        // Generate random token
        $token = bin2hex(random_bytes(32));

        // Store token in database with expiration time (e.g., 1 hour)
        $expiration_time = date('Y-m-d H:i:s', strtotime('+1 hour'));
        
        $stmt = $conn->prepare("UPDATE users SET reset_token = :token, reset_expiry = :expiry WHERE email = :email");
        $stmt->execute([
            'token' => $token,
            'expiry' => $expiration_time,
            'email' => $email
        ]);

        // Send password reset email
        $to = $email;
        $subject = "Password Reset Request";
        
        $message = "<html>
                    <head>
                        <title>Password Reset</title>
                    </head>
                    <body>
                        <h2>Password Reset Request</h2>
                        <p>Hello,</p>
                        <p>We received a password reset request for your account. Click the link below to reset your password:</p>
                        <a href='http://example.com/reset-password.php?token=$token'>Reset Password</a>
                        <p>If you did not request this password reset, please ignore this email.</p>
                        <p>This link will expire in 1 hour.</p>
                    </body>
                   </html>";
        
        $headers = "MIME-Version: 1.0" . "\r
";
        $headers .= "Content-type:text/html;charset=UTF-8" . "\r
";
        $headers .= "From: example@example.com" . "\r
";

        mail($to, $subject, $message, $headers);
        
        echo "Password reset email has been sent to your email address.";
    } else {
        echo "Email not found in our records. Please try again.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Forgot Password</title>
</head>
<body>
    <h2>Forgot Password</h2>
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        <label>Email:</label><br>
        <input type="email" name="email" required><br><br>
        <input type="submit" value="Request Password Reset">
    </form>
</body>
</html>


<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "GET") {
    $token = $_GET['token'];

    // Check if token exists and is valid
    $stmt = $conn->prepare("SELECT * FROM users WHERE reset_token = :token AND reset_expiry > NOW()");
    $stmt->execute(['token' => $token]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        $_SESSION['reset_token'] = $token;
        // Display password reset form
        ?>
        <!DOCTYPE html>
        <html>
        <head>
            <title>Reset Password</title>
        </head>
        <body>
            <h2>Reset Password</h2>
            <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                <label>New Password:</label><br>
                <input type="password" name="new_password" required><br><br>
                <label>Confirm New Password:</label><br>
                <input type="password" name="confirm_password" required><br><br>
                <input type="submit" value="Reset Password">
            </form>
        </body>
        </html>
        <?php
    } else {
        echo "Invalid or expired token. Please request a new password reset.";
        // Redirect to forgot password page after some time
        header("Refresh: 3; url=forgot-password.php");
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_SESSION['reset_token'])) {
        $token = $_SESSION['reset_token'];
        
        $new_password = $_POST['new_password'];
        $confirm_password = $_POST['confirm_password'];

        if ($new_password == $confirm_password) {
            // Hash the new password
            $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

            // Update password in database
            $stmt = $conn->prepare("UPDATE users SET password = :password, reset_token = NULL, reset_expiry = NULL WHERE reset_token = :token");
            $stmt->execute([
                'password' => $hashed_password,
                'token' => $token
            ]);

            // Clear session token and redirect to login page
            unset($_SESSION['reset_token']);
            header("Location: login.php");
        } else {
            echo "Passwords do not match. Please try again.";
        }
    } else {
        echo "Invalid request. Please start over.";
    }
}
?>


<?php
session_start();

// Database connection details
$host = 'localhost';
$username_db = 'root';
$password_db = '';
$db_name = 'your_database';

// Create database connection
$conn = new mysqli($host, $username_db, $password_db, $db_name);

// Check if the form is submitted
if (isset($_POST['reset'])) {
    // Get user email/username from input
    $email = $_POST['email'];
    
    try {
        // Query to check if email exists in database
        $stmt = $conn->prepare("SELECT * FROM users WHERE email=?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows == 1) {
            // Generate temporary password
            $alphabet = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890!@#$%^&*()';
            $temp_pass = substr(str_shuffle($alphabet), 0, 12);
            
            // Hash the temporary password before storing it in database
            $hashed_temp_pass = password_hash($temp_pass, PASSWORD_DEFAULT);
            
            // Update user's password with temporary password
            $update_stmt = $conn->prepare("UPDATE users SET password=? WHERE email=?");
            $update_stmt->bind_param("ss", $hashed_temp_pass, $email);
            $update_stmt->execute();
            
            // Send email to user with temporary password
            $to = $email;
            $subject = 'Your Temporary Password';
            $message = "Dear User,

Your temporary password is: $temp_pass

Please login and change your password immediately.

Best regards,
Your Website Team";
            $headers = 'From: noreply@yourwebsite.com' . "\r
" .
                       'Reply-To: noreply@yourwebsite.com' . "\r
" .
                       'X-Mailer: PHP/' . phpversion();
            
            mail($to, $subject, $message, $headers);
            
            // Redirect to login page with success message
            header("Location: login.php?success=1");
            exit();
        } else {
            // Email doesn't exist in database
            $_SESSION['error'] = "Email address not found in our records!";
            header("Location: forgot_password.php");
            exit();
        }
    } catch (mysqli_sql_exception $e) {
        // Handle any database errors
        $_SESSION['error'] = "An error occurred while processing your request. Please try again later.";
        header("Location: forgot_password.php");
        exit();
    }
}

// Close database connection
$conn->close();

?>

<!DOCTYPE html>
<html>
<head>
    <title>Forgot Password</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #f5f5f5;
        }
        .container {
            max-width: 400px;
            margin: 0 auto;
            background-color: white;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .form-group {
            margin-bottom: 15px;
        }
        input {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-sizing: border-box;
        }
        button {
            width: 100%;
            padding: 10px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        button:hover {
            background-color: #45a049;
        }
        .alert {
            padding: 10px;
            margin-bottom: 20px;
            border-radius: 5px;
        }
        .alert-danger {
            background-color: #f2d7d5;
            color: #c72526;
            border: 1px solid #b91c18;
        }
        .alert-success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #103c12;
        }
    </style>
</head>
<body>
    <div class="container">
        <?php
        if (isset($_SESSION['error'])) {
            echo '<div class="alert alert-danger">' . $_SESSION['error'] . '</div>';
            unset($_SESSION['error']);
        } elseif (isset($_GET['success']) && $_GET['success'] == 1) {
            echo '<div class="alert alert-success">A temporary password has been sent to your email address!</div>';
        }
        ?>
        <h2>Forgot Password</h2>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group">
                <input type="email" name="email" placeholder="Enter your email or username" required>
            </div>
            <button type="submit" name="reset">Reset Password</button>
        </form>
    </div>
</body>
</html>


<?php
session_start();
include('db_connection.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = mysqli_real_escape_string($conn, $_POST['email']);

    // Check if email exists in the database
    $query = "SELECT id FROM users WHERE email='$email'";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) == 1) {
        // Generate a unique token
        $token = bin2hex(random_bytes(32));
        
        // Calculate expiration time (e.g., 1 hour)
        $expires = date('Y-m-d H:i:s', strtotime('+1 hour'));

        // Update the database with the new token and expiration
        $update_query = "UPDATE users SET reset_token='$token', reset_expires='$expires' WHERE email='$email'";
        if (mysqli_query($conn, $update_query)) {
            // Send the reset link to the user's email
            $reset_link = "http://".$_SERVER['HTTP_HOST']."/reset_password.php?token=".$token;
            $subject = "Password Reset Request";
            $message = "Please click on this link to reset your password: ".$reset_link;

            if (mail($email, $subject, $message)) {
                echo "<h3>Reset link has been sent to your email.</h3>";
            } else {
                echo "<h3>Error sending email. Please try again later.</h3>";
            }
        } else {
            echo "<h3>Error resetting password. Please try again later.</h3>";
        }
    } else {
        echo "<h3>Email not found in our records.</h3>";
    }
}
?>


<?php
session_start();
include('db_connection.php');

if (isset($_GET['token'])) {
    $token = mysqli_real_escape_string($conn, $_GET['token']);
    
    // Check if token exists and hasn't expired
    $query = "SELECT id FROM users WHERE reset_token='$token' AND reset_expires > NOW()";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) == 1) {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            // Validate and update the password
            $new_password = mysqli_real_escape_string($conn, $_POST['password']);
            $confirm_password = mysqli_real_escape_string($conn, $_POST['confirm']);

            if ($new_password != $confirm_password) {
                die("Passwords do not match.");
            }

            $user_id = mysqli_fetch_assoc($result)['id'];
            // Update the password and reset token
            $update_query = "UPDATE users SET password='" . md5($new_password) . "', reset_token='', reset_expires='' WHERE id=$user_id";
            if (mysqli_query($conn, $update_query)) {
                echo "<h3>Password has been successfully updated!</h3>";
            } else {
                echo "<h3>Error updating password. Please try again.</h3>";
            }
        } else {
            // Display the reset form
?>
            <h2>Reset Password</h2>
            <form action="reset_password.php?token=<?php echo $token; ?>" method="post">
                New Password: <input type="password" name="password" required><br>
                Confirm Password: <input type="password" name="confirm" required><br><br>
                <button type="submit">Change Password</button>
            </form>
<?php
        }
    } else {
        echo "<h3>Invalid or expired token.</h3>";
    }
} else {
    echo "<h3>No token provided.</h3>";
}
?>


<?php
// Configuration
$host = 'localhost';
$dbname = 'your_database_name';
$username = 'root';
$password = '';

// Database connection
$conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Error reporting and redirects
function redirect($url) {
    header("Location: $url");
    die();
}

function showError($error) {
    echo "<div class='alert alert-danger'>$error</div>";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Forgot Password</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>

<?php
if (isset($_POST['reset'])) {
    $email = $_POST['email'];

    // Validate email input
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        showError("Invalid email format");
        exit();
    }

    try {
        // Check if the email exists in the database
        $stmt = $conn->prepare("SELECT id FROM users WHERE email = :email");
        $stmt->bindParam(':email', $email);
        $stmt->execute();

        if ($stmt->rowCount() == 0) {
            showError("No account found with this email address");
            exit();
        }

        // Generate reset token and expiration time
        $resetToken = bin2hex(random_bytes(16));
        $expires = date('Y-m-d H:i:s', strtotime('+1 hour'));

        // Update the database with the reset token and expiration time
        $stmt = $conn->prepare("UPDATE users SET reset_token = :token, reset_expires = :expires WHERE email = :email");
        $stmt->bindParam(':token', $resetToken);
        $stmt->bindParam(':expires', $expires);
        $stmt->bindParam(':email', $email);
        $stmt->execute();

        // Send the reset link to the user's email
        $to = $email;
        $subject = "Password Reset Request";
        $message = "
            <html>
                <head>
                    <title>Password Reset</title>
                </head>
                <body>
                    <h2>Password Reset Request</h2>
                    <p>We received a password reset request for your account. Click the link below to reset your password:</p>
                    <a href='http://localhost/reset_password.php?token=$resetToken&id=" . $stmt->fetch(PDO::FETCH_ASSOC)['id'] . "'>Reset Password</a>
                    <p>If you did not request this password reset, please ignore this email.</p>
                    <p>This link will expire in 1 hour.</p>
                </body>
            </html>";
        $headers = "MIME-Version: 1.0\r
";
        $headers .= "Content-Type: text/html; charset=UTF-8\r
";
        $headers .= "From: noreply@yourdomain.com\r
";

        mail($to, $subject, $message, $headers);

        // Redirect back to forgot password page with success message
        redirect("forgot_password.php?success=true");

    } catch(PDOException $e) {
        showError("Error occurred while processing your request. Please try again.");
    }
}
?>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-4">
            <h2>Forgot Password</h2>
            <?php if (isset($_GET['success'])) { ?>
                <div class="alert alert-success">
                    An email has been sent to you with instructions to reset your password.
                </div>
            <?php } ?>

            <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                <div class="form-group">
                    <label for="email">Email address</label>
                    <input type="email" class="form-control" id="email" name="email" required>
                </div>
                <button type="submit" name="reset" class="btn btn-primary">Reset Password</button>
            </form>

            <p class="mt-3">Remember your password? <a href="login.php">Login here</a></p>
        </div>
    </div>
</div>

</body>
</html>

<?php
// Close database connection
$conn = null;
?>



<?php
session_start();

// Configuration
$host = 'localhost';
$dbname = 'your_database_name';
$username = 'root';
$password = '';

// Database connection
$conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

function redirect($url) {
    header("Location: $url");
    die();
}

function showError($error) {
    echo "<div class='alert alert-danger'>$error</div>";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Reset Password</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>

<?php
if (!isset($_GET['token']) || !isset($_GET['id'])) {
    showError("Invalid reset link");
    exit();
}

$token = $_GET['token'];
$id = $_GET['id'];

try {
    // Check if the token is valid and not expired
    $stmt = $conn->prepare("
        SELECT id, email 
        FROM users 
        WHERE reset_token = :token 
        AND reset_expires > NOW() 
        AND id = :id");
    $stmt->bindParam(':token', $token);
    $stmt->bindParam(':id', $id);
    $stmt->execute();

    if ($stmt->rowCount() == 0) {
        showError("Invalid or expired reset link");
        exit();
    }

    // If the token is valid, show the password reset form
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
} catch(PDOException $e) {
    showError("Error occurred while processing your request. Please try again.");
}

if (isset($_POST['new_password'])) {
    $newPassword = $_POST['new_password'];
    
    if ($newPassword == '') {
        showError("Please enter a new password");
        exit();
    }

    // Update the user's password
    $stmt = $conn->prepare("
        UPDATE users 
        SET password = :password, reset_token = NULL, reset_expires = NULL 
        WHERE id = :id");
    $stmt->bindParam(':password', password_hash($newPassword, PASSWORD_DEFAULT));
    $stmt->bindParam(':id', $id);
    $stmt->execute();

    // Redirect to success page
    redirect("login.php?message=Password%20reset%20successful");
}
?>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-4">
            <h2>Reset Password</h2>
            <?php if (isset($_GET['success'])) { ?>
                <div class="alert alert-success">
                    Your password has been reset successfully!
                </div>
            <?php } ?>

            <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                <input type="hidden" name="id" value="<?php echo $id; ?>">
                <div class="form-group">
                    <label for="new_password">New Password</label>
                    <input type="password" class="form-control" id="new_password" name="new_password" required>
                </div>
                <button type="submit" class="btn btn-primary">Reset Password</button>
            </form>

            <p class="mt-3">Return to login page? <a href="login.php">Login here</a></p>
        </div>
    </div>
</div>

</body>
</html>

<?php
// Close database connection
$conn = null;
?>


<?php
session_start();

// Database connection parameters
$host = 'localhost';
$dbUsername = 'root';
$dbPassword = '';
$dbName = 'mydatabase';

// Create database connection
$conn = new mysqli($host, $dbUsername, $dbPassword, $dbName);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// If form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['reset'])) {
        // Email provided by user
        $email = $_POST['email'];
        
        // Check if email exists in database
        $sql = "SELECT * FROM users WHERE email=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            // Generate random token
            $token = md5(uniqid(rand(), true));
            
            // Set token expiration time (1 hour)
            $expires = date("Y-m-d H:i:s", time() + 3600);
            
            // Update the database with the new token and expiration time
            $updateSql = "UPDATE users SET reset_token=?, reset_expires=? WHERE email=?";
            $updateStmt = $conn->prepare($updateSql);
            $updateStmt->bind_param("sss", $token, $expires, $email);
            $updateStmt->execute();
            
            // Send email with password reset link
            $to = $email;
            $subject = "Password Reset Request";
            $message = "
                <html>
                    <head></head>
                    <body>
                        <p>Hello,</p>
                        <p>A password reset request was received for your account. Click the link below to reset your password:</p>
                        <a href='http://example.com/reset-password.php?token=$token'>Reset Password</a>
                        <p>If you did not request this password reset, please ignore this email.</p>
                        <p>This link will expire in 1 hour.</p>
                    </body>
                </html>
            ";
            $headers = "MIME-Version: 1.0" . "\r
";
            $headers .= "Content-type:text/html;charset=UTF-8" . "\r
";
            $headers .= "From: noreply@example.com" . "\r
";

            if (mail($to, $subject, $message, $headers)) {
                echo "<script>alert('Password reset link has been sent to your email.');</script>";
            } else {
                echo "<script>alert('Failed to send password reset link. Please try again.');</script>";
            }
        } else {
            echo "<script>alert('Email not found in our records.');</script>";
        }
    }
}

// Reset password form
echo '
<!DOCTYPE html>
<html>
<head>
    <title>Forgot Password</title>
</head>
<body>
    <h2>Forgot Password</h2>
    <form method="POST" action="">
        <input type="email" name="email" placeholder="Enter your email" required><br><br>
        <button type="submit" name="reset">Reset Password</button>
    </form>
</body>
</html>
';

// Close database connection
$conn->close();
?>



<?php
session_start();

// Database connection parameters
$host = 'localhost';
$dbUsername = 'root';
$dbPassword = '';
$dbName = 'mydatabase';

// Create database connection
$conn = new mysqli($host, $dbUsername, $dbPassword, $dbName);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_GET['token'])) {
    $token = $_GET['token'];
    
    // Check if token exists and is valid
    $sql = "SELECT * FROM users WHERE reset_token=? AND reset_expires > NOW()";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        // Show password reset form
        echo '
            <!DOCTYPE html>
            <html>
            <head>
                <title>Reset Password</title>
            </head>
            <body>
                <h2>Reset Password</h2>
                <form method="POST" action="">
                    <input type="password" name="new_password" placeholder="Enter new password" required><br><br>
                    <button type="submit" name="change">Change Password</button>
                </form>
            ';
    } else {
        echo "<script>alert('Invalid or expired reset link. Please request a new password reset.'); window.location.href='forgot-password.php';</script>";
    }
} elseif (isset($_POST['change'])) {
    $token = $_GET['token'];
    
    // Update user's password
    $newPassword = password_hash($_POST['new_password'], PASSWORD_DEFAULT);
    
    $sql = "UPDATE users SET password=?, reset_token=NULL, reset_expires=NULL WHERE reset_token=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $newPassword, $token);
    $stmt->execute();
    
    if ($stmt->affected_rows > 0) {
        echo "<script>alert('Password has been updated!'); window.location.href='login.php';</script>";
    } else {
        echo "<script>alert('Failed to update password. Please try again.'); window.location.href='reset-password.php?token=$token';</script>";
    }
} else {
    // Invalid access
    echo "<script>alert('Invalid request. Please use the reset link from your email.'); window.location.href='forgot-password.php';</script>";
}

// Close database connection
$conn->close();
?>



<?php
// Database configuration
$host = 'localhost';
$username = 'root';
$password = '';
$dbname = 'your_database';

try {
    // Create database connection
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $email = $_POST['email'];
        
        // Check if email exists
        $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->execute([$email]);
        
        if ($stmt->rowCount() == 0) {
            die("Email not found. Please try again.");
        }
        
        // Generate a random token
        $token = bin2hex(random_bytes(16));
        $expires = time() + 3600; // Token expires in 1 hour
        
        // Update tokens table with the new token
        $conn->exec("DELETE FROM password_reset_tokens WHERE email = '$email'");
        $conn->prepare("INSERT INTO password_reset_tokens (token, email, created_at) VALUES (?, ?, ?)")
            ->execute([$token, $email, time()]);
        
        // Send reset link to user's email
        $resetLink = "http://yourwebsite.com/reset_password.php?token=$token";
        $to = $email;
        $subject = "Password Reset Request";
        $message = "Please click the following link to reset your password: 

$resetLink

This link will expire in 1 hour.";
        
        // Send email
        if (mail($to, $subject, $message)) {
            header("Location: forgot_password_success.php");
            exit();
        } else {
            die("Failed to send the reset link. Please try again.");
        }
    }
} catch(PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

// Show form if it's not a POST request
if ($_SERVER["REQUEST_METHOD"] != "POST") {
?>
<!DOCTYPE html>
<html>
<head>
    <title>Forgot Password</title>
</head>
<body>
    <h2>Forgot Password</h2>
    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
        Email: <input type="text" name="email"><br><br>
        <input type="submit" value="Submit">
    </form>
</body>
</html>
<?php
}
?>


<?php
// forgot_password.php

include('db_connection.php'); // Include your database connection file

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = mysqli_real_escape_string($conn, $_POST['email']);

    // Check if email exists in the database
    $sql = "SELECT * FROM users WHERE email = '$email'";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) == 1) {
        // Generate a random token
        $token = bin2hex(random_bytes(16));
        
        // Expiry time: current time + 1 hour
        $expires = date('Y-m-d H:i:s', time() + 3600);

        // Update the database with the token and expiry time
        $updateSql = "UPDATE users SET reset_token = '$token', reset_expiry = '$expires' WHERE email = '$email'";
        mysqli_query($conn, $updateSql);

        // Send the password reset link to user's email
        sendResetEmail($email, $token);
        
        echo "A password reset link has been sent to your email address.";
    } else {
        die("This email does not exist in our database.");
    }
}

// Function to send reset email
function sendResetEmail($email, $token) {
    $to = $email;
    $subject = "Password Reset Request";
    
    // Set the headers
    $headers = "From: your_website@example.com\r
";
    $headers .= "Content-type: text/html; charset=UTF-8\r
";
    
    $message = "
        <html>
            <head>
                <title>Password Reset</title>
            </head>
            <body>
                <h2>Password Reset Request</h2>
                <p>Hello,</p>
                <p>We received a password reset request for your account. Please click the link below to reset your password:</p>
                <a href='http://yourwebsite.com/reset_password.php?token=$token'>Reset Password</a>
                <br><br>
                <p>If you did not request this password reset, please ignore this email.</p>
                <p>This password reset link will expire in 1 hour.</p>
            </body>
        </html>
    ";
    
    mail($to, $subject, $message, $headers);
}
?>


<?php
// reset_password.php

include('db_connection.php'); // Include your database connection file

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $token = mysqli_real_escape_string($conn, $_POST['token']);
    $new_password = mysqli_real_escape_string($conn, $_POST['new_password']);
    $confirm_password = mysqli_real_escape_string($conn, $_POST['confirm_password']);

    // Check if token is valid and not expired
    $sql = "SELECT * FROM users WHERE reset_token = '$token' AND reset_expiry > NOW()";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) == 1) {
        if ($new_password != $confirm_password) {
            die("Passwords do not match. Please try again.");
        }

        // Update the user's password
        $password_hash = md5($new_password); // Use a stronger hashing algorithm like bcrypt in production

        $updateSql = "UPDATE users SET password = '$password_hash', reset_token = '', reset_expiry = '0000-00-00 00:00:00' WHERE reset_token = '$token'";
        mysqli_query($conn, $updateSql);

        echo "Your password has been successfully updated!";
    } else {
        die("Invalid or expired token. Please request a new password reset.");
    }
}
?>


<?php
session_start();
include('db_connection.php'); // Include your database connection

if (isset($_POST['email'])) {
    $email = $_POST['email'];
    
    // Check if email exists in the database
    $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Generate a unique token
        $token = bin2hex(random_bytes(32));
        
        // Set expiration time (1 hour)
        $expires = date('Y-m-d H:i:s', strtotime('+1 hour'));
        
        // Insert token into the database
        $stmt = $conn->prepare("INSERT INTO password_reset (user_id, token, expires) 
                               SELECT id, ?, ? FROM users WHERE email = ?");
        $stmt->bind_param("sss", $token, $expires, $email);
        $stmt->execute();
        
        // Send reset link to the user's email
        $to = $email;
        $subject = "Password Reset Request";
        $message = "Please click the following link to reset your password:

" .
                   "http://yourdomain.com/reset_password.php?token=$token

" .
                   "If you did not request this, please ignore this email.";
        
        mail($to, $subject, $message);
        echo "An email has been sent with instructions to reset your password.";
    } else {
        echo "Email does not exist in our records.";
    }
}
?>


<?php
session_start();
include('db_connection.php');

if (isset($_POST['new_password'], $_POST['confirm_password'], $_POST['token'])) {
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];
    $token = $_POST['token'];
    
    if ($new_password !== $confirm_password) {
        die("Passwords do not match.");
    }
    
    // Check token validity
    $stmt = $conn->prepare("SELECT user_id FROM password_reset 
                           WHERE token = ? AND expires > NOW()");
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();
        $user_id = $row['user_id'];
        
        // Hash the new password
        $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
        
        // Update user's password
        $stmt = $conn->prepare("UPDATE users SET password = ? WHERE id = ?");
        $stmt->bind_param("si", $hashed_password, $user_id);
        $stmt->execute();
        
        // Remove the reset token
        $stmt = $conn->prepare("DELETE FROM password_reset WHERE token = ?");
        $stmt->bind_param("s", $token);
        $stmt->execute();
        
        echo "Password has been reset successfully.";
    } else {
        die("Invalid or expired token.");
    }
}
?>


<?php
// Database configuration
$host = 'localhost';
$user = 'root';
$password = '';
$dbname = 'test';

// Connect to database
$conn = mysqli_connect($host, $user, $password, $dbname);
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

$email = $_POST['email'];

// Check if email exists
$sql = "SELECT id FROM users WHERE email='" . mysqli_real_escape_string($conn, $email) . "'";
$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) == 0) {
    echo "Email not found!";
} else {
    // Generate reset token
    $token = bin2hex(random_bytes(32));
    
    // Store token in database with expiry time (e.g., 30 minutes)
    $sql = "INSERT INTO password_resets (user_id, token, created_at) 
            VALUES (" . mysqli_insert_id($conn) . ", '" . mysqli_real_escape_string($conn, $token) . "', NOW())";
    
    if (mysqli_query($conn, $sql)) {
        // Send email
        $to = $email;
        $subject = "Password Reset Request";
        
        $message = "<html>
                     <head></head>
                     <body>
                         <h2>Password Reset</h2>
                         <p>Please click the following link to reset your password:</p>
                         <a href='http://localhost/reset_password.php?token=$token'>Reset Password</a><br><br>
                         This link will expire in 30 minutes.
                     </body>
                   </html>";
        
        $headers = "MIME-Version: 1.0" . "\r
";
        $headers .= "Content-type:text/html;charset=UTF-8" . "\r
";
        $headers .= "From: noreply@example.com" . "\r
"; // Change to your email
        
        mail($to, $subject, $message, $headers);
        
        echo "A password reset link has been sent to your email!";
    } else {
        echo "Error sending reset link. Please try again.";
    }
}

mysqli_close($conn);
?>


<?php
// Database configuration
$host = 'localhost';
$user = 'root';
$password = '';
$dbname = 'test';

$conn = mysqli_connect($host, $user, $password, $dbname);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

if (isset($_GET['token'])) {
    $token = $_GET['token'];
    
    // Check token validity
    $sql = "SELECT * FROM password_resets WHERE token='" . mysqli_real_escape_string($conn, $token) . "'";
    $result = mysqli_query($conn, $sql);
    
    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        // Check if token is expired
        $created_at = strtotime($row['created_at']);
        $expires_in = 1800; // 30 minutes
        
        if ($created_at + $expires_in >= time()) {
            ?>
            <h2>Reset Password</h2>
            <form action="reset_password.php" method="post">
                <input type="password" name="new_pass" placeholder="New Password" required><br><br>
                <button type="submit">Submit</button>
                <input type="hidden" name="token" value="<?php echo $token; ?>">
            </form>
            <?php
        } else {
            echo "This reset link has expired. Please request a new one.";
        }
    } else {
        echo "Invalid reset link!";
    }
} elseif (isset($_POST['new_pass'])) {
    // Update password
    $token = $_POST['token'];
    
    $sql = "SELECT * FROM password_resets WHERE token='" . mysqli_real_escape_string($conn, $token) . "'";
    $result = mysqli_query($conn, $sql);
    
    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        
        // Check expiration
        $created_at = strtotime($row['created_at']);
        $expires_in = 1800;
        
        if ($created_at + $expires_in >= time()) {
            $new_pass = password_hash($_POST['new_pass'], PASSWORD_DEFAULT);
            
            // Update user's password
            $sql_update_user = "UPDATE users SET password='$new_pass' WHERE id=" . $row['user_id'];
            if (mysqli_query($conn, $sql_update_user)) {
                // Delete reset token
                $sql_delete_token = "DELETE FROM password_resets WHERE token='" . mysqli_real_escape_string($conn, $token) . "'";
                mysqli_query($conn, $sql_delete_token);
                
                echo "Password updated successfully!";
            } else {
                echo "Error updating password.";
            }
        } else {
            echo "This reset link has expired. Please request a new one.";
        }
    } else {
        echo "Invalid token or already used.";
    }
} else {
    echo "Please use the provided link to reset your password!";
}
?>


<?php
// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = mysqli_real_escape_string($conn, trim($_POST['email']));

    // Validate email format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        die("Invalid email format");
    }

    // Check if email exists in database
    $sql = "SELECT id FROM users WHERE email='$email'";
    $result = mysqli_query($conn, $sql);
    
    if (mysqli_num_rows($result) == 0) {
        die("Email not found in our records");
    }

    // Generate a random token for password reset
    $token = bin2hex(openssl_random_pseudo_bytes(32));

    // Insert token into database
    $sql = "UPDATE users SET reset_token='$token' WHERE email='$email'";
    mysqli_query($conn, $sql);

    // Send email with reset link
    $to = $email;
    $subject = "Password Reset Request";
    $message = "Please click the following link to reset your password:

http://example.com/reset_password.php?token=$token&user_id=" . mysqli_insert_id($conn) . "

If you did not request this, please ignore this email.";
    $headers = "From: noreply@example.com";

    if (mail($to, $subject, $message, $headers)) {
        echo "Reset password link has been sent to your email address";
    } else {
        die("Failed to send reset email");
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Forgot Password</title>
</head>
<body>
    <h2>Forgot Password</h2>
    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
        <label>Email:</label><br>
        <input type="email" name="email" required><br><br>
        <input type="submit" value="Submit">
    </form>
</body>
</html>


<?php
session_start();

// Get token and user ID from URL
if (!isset($_GET['token']) || !isset($_GET['user_id'])) {
    die("Invalid request");
}

$token = $_GET['token'];
$user_id = $_GET['user_id'];

// Verify token and user_id in database
$sql = "SELECT * FROM users WHERE id='$user_id' AND reset_token='$token'";
$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) != 1) {
    die("Invalid reset link");
}

// If form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $password = trim($_POST['password']);
    $confirm_password = trim($_POST['confirm_password']);

    // Validate password
    if ($password != $confirm_password) {
        die("Passwords do not match");
    }

    if (strlen($password) < 6) {
        die("Password must be at least 6 characters long");
    }

    // Hash the new password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Update database with new password and clear reset token
    $sql = "UPDATE users SET password='$hashed_password', reset_token='' WHERE id='$user_id'";
    mysqli_query($conn, $sql);

    // Redirect to login page
    header("Location: login.php?message=Password has been successfully updated");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Reset Password</title>
</head>
<body>
    <h2>Reset Password</h2>
    <form action="<?php echo $_SERVER['PHP_SELF']; ?>?token=<?php echo $token; ?>&user_id=<?php echo $user_id; ?>" method="post">
        <label>New Password:</label><br>
        <input type="password" name="password" required><br><br>
        <label>Confirm Password:</label><br>
        <input type="password" name="confirm_password" required><br><br>
        <input type="submit" value="Reset Password">
    </form>
</body>
</html>


<?php
$email = mysqli_real_escape_string($conn, $_POST['email']);
// Further validation can be added here if needed.
?>


$result = $conn->query("SELECT * FROM users WHERE email='$email'");
if ($result->num_rows == 0) {
    die("No account found with this email.");
}


$token = md5(rand());
$current_time = strtotime(date('Y-m-d H:i:s'));
$expiration_time = $current_time + 3600; // Expiry after 1 hour

$conn->query("UPDATE users SET reset_token='$token', token_expiry='$expiration_time' WHERE email='$email'");


$reset_link = "http://yourdomain.com/reset_password.php?token=$token";
mail($email, 'Password Reset', "Click here to reset your password: $reset_link");


if (isset($_GET['token'])) {
    $token = mysqli_real_escape_string($conn, $_GET['token']);
    
    // Check if token exists in database and hasn't expired
    $result = $conn->query("SELECT * FROM users WHERE reset_token='$token' AND token_expiry > '" . time() . "'");
    
    if ($result->num_rows == 1) {
        // Display password reset form
        ?>
        <h2>Reset Password</h2>
        <form action="reset_password.php?token=<?php echo $token ?>" method="post">
            New Password: <input type="password" name="new_pass" required><br>
            Confirm Password: <input type="password" name="confirm_pass" required><br>
            <button type="submit">Reset</button>
        </form>
        <?php
    } else {
        // Token invalid or expired
        die("Invalid token. Please request a new password reset.");
    }
} else {
    die("No token provided.");
}


if (isset($_POST['new_pass']) && isset($_POST['confirm_pass'])) {
    $new_pass = $_POST['new_pass'];
    $confirm_pass = $_POST['confirm_pass'];
    
    if ($new_pass != $confirm_pass) {
        die("Passwords do not match.");
    }
    
    // Hash the new password
    $hash = password_hash($new_pass, PASSWORD_DEFAULT);
    
    $token = mysqli_real_escape_string($conn, $_GET['token']);
    
    // Update user's password and clear reset token
    $conn->query("UPDATE users SET password='$hash', reset_token=NULL WHERE reset_token='$token'");
    
    echo "Password updated successfully!";
}


<?php
include('db_connection.php');
include('functions.php');

if (isset($_POST['email'])) {
    $email = $_POST['email'];
    
    // Check if email or username exists in database
    $stmt = $conn->prepare("SELECT id FROM users WHERE email=? OR username=?");
    $stmt->execute([$email, $email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($user) {
        // Generate a random token and expiration time
        $token = bin2hex(random_bytes(16));
        $expires = date('Y-m-d H:i:s', strtotime('+1 hour'));
        
        // Store token in database
        $stmt = $conn->prepare("INSERT INTO password_reset (user_id, token, expires) VALUES (?, ?, ?)");
        $stmt->execute([$user['id'], $token, $expires]);
        
        // Send reset email
        $resetLink = "http://yourdomain.com/reset_password.php?token=" . $token;
        sendResetEmail($email, $resetLink);
        
        echo "An email has been sent to you with instructions to reset your password.";
    } else {
        die("No account found with that email or username.");
    }
}
?>


<?php
include('db_connection.php');

if (!isset($_GET['token'])) {
    die("No token provided.");
}

$token = $_GET['token'];

// Check if token exists and is valid
$stmt = $conn->prepare("SELECT user_id, expires FROM password_reset WHERE token=?");
$stmt->execute([$token]);
$result = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$result) {
    die("Invalid or expired reset link.");
}

$currentDate = date('Y-m-d H:i:s');
if ($currentDate > $result['expires']) {
    die("Reset link has expired. Please request a new one.");
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Reset Password</title>
</head>
<body>
    <h2>Reset Password</h2>
    <form action="reset_password.php?token=<?php echo $token;?>" method="post">
        <label for="password">New Password:</label><br>
        <input type="password" id="password" name="password"><br><br>
        <label for="confirm">Confirm Password:</label><br>
        <input type="password" id="confirm" name="confirm"><br><br>
        <input type="submit" value="Reset Password">
    </form>
</body>
</html>


<?php
include('db_connection.php');

if (!isset($_GET['token']) || !isset($_POST['password'])) {
    die("Invalid request.");
}

$token = $_GET['token'];
$password = $_POST['password'];
$confirm = $_POST['confirm'];

// Check if token exists and is valid
$stmt = $conn->prepare("SELECT user_id, expires FROM password_reset WHERE token=?");
$stmt->execute([$token]);
$result = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$result) {
    die("Invalid or expired reset link.");
}

$currentDate = date('Y-m-d H:i:s');
if ($currentDate > $result['expires']) {
    die("Reset link has expired. Please request a new one.");
}

// Check if passwords match
if ($password !== $confirm) {
    die("Passwords do not match.");
}

// Hash the password
$hashedPassword = password_hash($password, PASSWORD_DEFAULT);

// Update user's password
$stmt = $conn->prepare("UPDATE users SET password=? WHERE id=?");
$stmt->execute([$hashedPassword, $result['user_id']]);

// Delete used token
$stmt = $conn->prepare("DELETE FROM password_reset WHERE token=?");
$stmt->execute([$token]);

echo "Your password has been reset successfully!";
?>


function sendResetEmail($email, $resetLink) {
    $to = $email;
    $subject = "Password Reset Request";
    $message = "Please click the following link to reset your password:

" . $resetLink . "

This link will expire in 1 hour.";
    $headers = "From: yourname@yourdomain.com";

    mail($to, $subject, $message, $headers);
}


<?php
// config.php - Database connection details
$host = 'localhost';
$username = 'root';
$password = '';
$db_name = 'your_database';

$conn = mysqli_connect($host, $username, $password, $db_name);
if (!$conn) {
    die('Connection failed: ' . mysqli_error());
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Forgot Password</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body class="bg-light">
    <div class="container mt-5">
        <h2 class="text-center mb-4">Forgot Password</h2>
        <form action="forgot_password.php" method="post" class="border p-3 bg-white rounded">
            <div class="form-group">
                <label for="email">Email or Username:</label>
                <input type="text" class="form-control" id="email" name="email_or_username" required>
            </div>
            <button type="submit" class="btn btn-primary">Reset Password</button>
        </form>
    </div>
</body>
</html>

<?php
// forgot_password.php - Handle password reset request
session_start();
require_once 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email_or_username = mysqli_real_escape_string($conn, $_POST['email_or_username']);
    
    // Check if user exists in database
    $sql = "SELECT id, email FROM users WHERE email='$email_or_username' OR username='$email_or_username'";
    $result = mysqli_query($conn, $sql);
    
    if (mysqli_num_rows($result) > 0) {
        $user = mysqli_fetch_assoc($result);
        
        // Generate random reset token
        $reset_token = bin2hex(random_bytes(16));
        $expires = date('Y-m-d H:i:s', time() + 3600); // Token expires in 1 hour
        
        // Store token in database
        $sql = "INSERT INTO password_reset (user_id, reset_token, expires) 
                VALUES (?, ?, ?)";
        
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "iss", $user['id'], $reset_token, $expires);
        mysqli_stmt_execute($stmt);
        
        // Send email with reset link
        $to = $user['email'];
        $subject = 'Password Reset Request';
        $message = 'Please click the following link to reset your password: http://your-site.com/reset_password.php?token=' . $reset_token;
        $headers = 'From: webmaster@your-site.com' . "\r
" .
                   'Reply-To: webmaster@your-site.com' . "\r
" .
                   'X-Mailer: PHP/' . phpversion();
        
        mail($to, $subject, $message, $headers);
        
        echo "<script>alert('Password reset email has been sent!'); window.location.href='index.php';</script>";
    } else {
        echo "<script>alert('User not found!');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Reset Password</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body class="bg-light">
    <div class="container mt-5">
        <?php
        // reset_password.php - Handle password reset
        session_start();
        require_once 'config.php';

        if (isset($_GET['token'])) {
            $reset_token = mysqli_real_escape_string($conn, $_GET['token']);
            
            // Check token validity and expiration
            $sql = "SELECT user_id FROM password_reset 
                    WHERE reset_token='$reset_token' AND expires > NOW()";
            $result = mysqli_query($conn, $sql);
            
            if (mysqli_num_rows($result) == 1) {
                $user_id = mysqli_fetch_assoc($result)['user_id'];
        ?>
        
        <form action="reset_password.php" method="post" class="border p-3 bg-white rounded">
            <div class="form-group">
                <label for="new_password">New Password:</label>
                <input type="password" class="form-control" id="new_password" name="new_password" required>
            </div>
            <div class="form-group">
                <label for="confirm_password">Confirm Password:</label>
                <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
            </div>
            <button type="submit" class="btn btn-primary">Change Password</button>
        </form>

        <?php
                if ($_SERVER["REQUEST_METHOD"] == "POST") {
                    $new_password = $_POST['new_password'];
                    $confirm_password = $_POST['confirm_password'];

                    if ($new_password != $confirm_password) {
                        echo "<script>alert('Passwords do not match!');</script>";
                        exit();
                    }

                    // Hash the new password
                    $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

                    // Update user's password in database
                    $sql = "UPDATE users SET password='$hashed_password' WHERE id=$user_id";
                    mysqli_query($conn, $sql);

                    // Delete the reset token
                    $sql = "DELETE FROM password_reset WHERE reset_token='$reset_token'";
                    mysqli_query($conn, $sql);

                    echo "<script>alert('Password has been changed!'); window.location.href='index.php';</script>";
                }
            } else {
                echo "<h3>Invalid or expired reset token!</h3>";
            }
        } else {
            echo "<h3>No reset token provided!</h3>";
        }
        mysqli_close($conn);
        ?>
    </div>
</body>
</html>


<?php
// Database configuration
$host = "localhost";
$user = "root";
$password = "";
$db_name = "mydatabase";

// Connect to the database
$conn = new mysqli($host, $user, $password, $db_name);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if email is provided and not empty
    if (isset($_POST['email']) && !empty(trim($_POST['email']))) {
        $email = trim($_POST['email']);
        
        // Validate email format
        if (!preg_match("/^([a-zA-Z0-9_\-\.]+)@([a-zA-Z0-9_\-\.]+)\.([a-zA-Z]{2,5})$/", $email)) {
            $message = "Invalid email format";
            header("Location: forgot_password.php?msg=" . urlencode($message));
            exit();
        }
        
        // Check if the email exists in the database
        $sql = "SELECT id FROM users WHERE email = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows === 0) {
            $message = "No account found with this email address";
            header("Location: forgot_password.php?msg=" . urlencode($message));
            exit();
        }
        
        // Generate a random token
        $token = bin2hex(random_bytes(16));
        
        // Calculate the expiration time (30 minutes from now)
        $expiration_time = date('Y-m-d H:i:s', strtotime('+30 minutes'));
        
        // Update the token and expiration time in the database
        $updateSql = "UPDATE users SET reset_token = ?, reset_expires = ? WHERE email = ?";
        $stmt = $conn->prepare($updateSql);
        $stmt->bind_param("sss", $token, $expiration_time, $email);
        $result = $stmt->execute();
        
        if (!$result) {
            die("Error updating record: " . $conn->error);
        }
        
        // Send the reset password email
        $to = $email;
        $subject = "Reset Password Request";
        $message_body = "Please click on the following link to reset your password:

" .
                        "http://example.com/reset_password.php?token=" . $token . "

" .
                        "This link will expire in 30 minutes.";
        
        $headers = "From: noreply@example.com\r
";
        $headers .= "Reply-To: noreply@example.com\r
";
        $headers .= "X-Mailer: PHP/" . phpversion();
        
        if (mail($to, $subject, $message_body, $headers)) {
            $message = "Password reset email has been sent to your email address.";
            header("Location: forgot_password.php?msg=" . urlencode($message));
            exit();
        } else {
            die("Failed to send password reset email");
        }
        
    } else {
        $message = "Email is required";
        header("Location: forgot_password.php?msg=" . urlencode($message));
        exit();
    }
}

// Close database connection
$conn->close();
?>


<?php
include('db_connect.php'); // Include your database connection

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    
    // Sanitize and validate email input
    $email = filter_var($email, FILTER_SANITIZE_EMAIL);
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        die("Invalid email format");
    }
    
    // Check if the email exists in the database
    $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->bind_param('s', $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Generate a unique reset token
        $token = bin2hex(random_bytes(16)); // Generates a 32-character hexadecimal string
        $expires = date('Y-m-d H:i:s', time() + 3600); // Token expires in 1 hour

        // Store the token and expiration in the database
        $stmt = $conn->prepare("INSERT INTO password_reset (user_id, token, expires) VALUES (?, ?, ?)");
        $stmt->bind_param('iss', $result->fetch_assoc()['id'], $token, $expires);
        $stmt->execute();

        // Send reset email
        $to = $email;
        $subject = "Password Reset Request";
        $message = "
            <html>
                <body>
                    <p>Please click the following link to reset your password:</p>
                    <a href='http://your-site.com/reset-password.php?token=$token&email=$email'>Reset Password</a>
                </body>
            </html>
        ";
        $headers = "MIME-Version: 1.0\r
";
        $headers .= "Content-type: text/html; charset=UTF-8\r
";
        $headers .= "From: your-site@example.com\r
";

        mail($to, $subject, $message, $headers);

        // Redirect back to the forgot password page with a success message
        header("Location: forgot-password.php?msg=Check%20your%20email%20for%20the%20reset%20link.");
        exit();
    } else {
        die("Email not found in our records");
    }
}
?>


<?php
include('db_connect.php');

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    $token = $_GET['token'];
    $email = $_GET['email'];

    // Validate inputs
    if (empty($token) || empty($email)) {
        die("Invalid request");
    }

    // Check token validity and expiration
    $stmt = $conn->prepare("SELECT pr.*, u.id FROM password_reset pr JOIN users u ON pr.user_id = u.id WHERE pr.token = ? AND pr.expires > NOW()");
    $stmt->bind_param('s', $token);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 0) {
        die("Invalid or expired reset link");
    }

    // Fetch user data
    $user_data = $result->fetch_assoc();
} else {
    // Handle form submission
    $password = $_POST['password'];
    $token = $_POST['token'];

    // Validate password (minimum length, etc.)
    if (strlen($password) < 8) {
        die("Password must be at least 8 characters");
    }

    // Update the user's password
    $stmt = $conn->prepare("UPDATE users SET password = ? WHERE id = ?");
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    $stmt->bind_param('si', $hashed_password, $user_data['id']);
    $stmt->execute();

    // Delete the reset token
    $stmt = $conn->prepare("DELETE FROM password_reset WHERE token = ?");
    $stmt->bind_param('s', $token);
    $stmt->execute();

    // Redirect to login page with success message
    header("Location: login.php?msg=Password%20reset%20successful.");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Reset Password</title>
</head>
<body>
    <h2>Reset Password</h2>
    
    <?php
    // Check if there's an error message from the server
    if (isset($_GET['error'])) {
        echo "<p style='color: red;'>$_GET[error]</p>";
    }
    ?>

    <form action="reset-password.php" method="post">
        <input type="hidden" name="token" value="<?php echo $token; ?>">
        <input type="password" name="password" placeholder="Enter new password" required><br>
        <button type="submit">Reset Password</button>
    </form>

    <p>Link expired or invalid? <a href="forgot-password.php">Request a new link.</a></p>
</body>
</html>


<?php
session_start();
include("db_connection.php"); // Include your database connection file

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);
    
    if (empty($email)) {
        $error = "Please enter your email address!";
    } else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Invalid email format!";
    } else {
        // Check if email exists in the database
        $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows === 0) {
            $error = "No account found with this email!";
        } else {
            // Generate a unique token
            $token = md5(uniqid(rand(), true));
            
            // Update the database with the token and expiration time
            date_default_timezone_set("UTC");
            $expires = date('Y-m-d H:i:s', time() + 3600); // Token expires in 1 hour
            
            $update_stmt = $conn->prepare("UPDATE users SET reset_token = ?, reset_expires = ? WHERE email = ?");
            $update_stmt->bind_param("sss", $token, $expires, $email);
            $update_stmt->execute();
            
            // Send the password reset link to the user's email
            $to = $email;
            $subject = "Reset Your Password";
            $message = "
                <html>
                    <head></head>
                    <body>
                        <h2>Password Reset Request</h2>
                        <p>Please click the following link to reset your password:</p>
                        <a href='http://yourdomain.com/reset_password.php?token=$token'>Reset Password</a><br><br>
                        <small>If you didn't request this, please ignore this email.</small>
                    </body>
                </html>
            ";
            $headers = "MIME-Version: 1.0" . "\r
";
            $headers .= "Content-type:text/html;charset=UTF-8" . "\r
";
            $headers .= "From: yourname@yourdomain.com" . "\r
"; // Change this to your email
            
            if (mail($to, $subject, $message, $headers)) {
                $success = "We've sent you a password reset link. Check your email!";
            } else {
                $error = "An error occurred while sending the email.";
            }
        }
    }
}
?>


<?php
session_start();
include("db_connection.php"); // Include your database connection file

if (isset($_GET['token'])) {
    $token = $_GET['token'];
    
    // Check if token exists in the database and hasn't expired
    date_default_timezone_set("UTC");
    $current_time = date('Y-m-d H:i:s');
    
    $stmt = $conn->prepare("SELECT id, email FROM users WHERE reset_token = ? AND reset_expires > ?");
    $stmt->bind_param("ss", $token, $current_time);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows === 0) {
        $error = "Invalid or expired token!";
    } else {
        // Show password reset form
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $password = trim($_POST['password']);
            $confirm_password = trim($_POST['confirm_password']);
            
            if (empty($password) || empty($confirm_password)) {
                $error = "Please fill in all fields!";
            } else if ($password !== $confirm_password) {
                $error = "Passwords do not match!";
            } else {
                // Update the password
                $hashed_password = md5($password); // You might want to use a stronger hashing algorithm
                
                $update_stmt = $conn->prepare("UPDATE users SET password = ?, reset_token = NULL WHERE reset_token = ?");
                $update_stmt->bind_param("ss", $hashed_password, $token);
                $update_stmt->execute();
                
                // Clear the token
                $clear_stmt = $conn->prepare("UPDATE users SET reset_token = NULL WHERE reset_token = ?");
                $clear_stmt->bind_param("s", $token);
                $clear_stmt->execute();
                
                $success = "Your password has been updated!";
            }
        } else {
            // Show the form
            $row = $result->fetch_assoc();
            $_SESSION['reset_email'] = $row['email'];
        }
    }
} else {
    $error = "No token provided!";
}
?>


<!DOCTYPE html>
<html>
<head>
    <title>Forgot Password</title>
    <script>
        function validateEmail() {
            const email = document.getElementById('email').value;
            const errorDiv = document.getElementById('error');
            
            if (email === '') {
                errorDiv.textContent = 'Please enter your email address.';
                return false;
            }
            
            // Basic email validation
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!email.match(emailRegex)) {
                errorDiv.textContent = 'Please enter a valid email address.';
                return false;
            }
            
            return true;
        }
    </script>
</head>
<body>
    <h2>Forgot Password</h2>
    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" onsubmit="return validateEmail()">
        <div id="error"></div>
        <p>Please enter your registered email address:</p>
        <input type="email" name="email" id="email" required>
        <br><br>
        <input type="submit" value="Reset Password">
    </form>
</body>
</html>

<?php
// Database configuration
$host = 'localhost';
$username = 'root';
$password = '';
$database = 'mydatabase';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = htmlspecialchars($_POST['email']);
    
    // Sanitize email (prevent SQL injection)
    $conn = new mysqli($host, $username, $password, $database);
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    
    $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->bind_param('s', $email);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows == 0) {
        echo "Email not found. Please check your email.";
    } else {
        // Generate token and expiration time
        $token = uniqid() . rand(1000, 9999);
        $expires = date('Y-m-d H:i:s', strtotime('+60 minutes'));
        
        // Update the database with the new token and expiration
        $updateStmt = $conn->prepare("UPDATE users SET reset_token = ?, reset_expires = ? WHERE email = ?");
        $updateStmt->bind_param('sss', $token, $expires, $email);
        $updateStmt->execute();
        
        // Close connections
        $updateStmt->close();
        $stmt->close();
        $conn->close();
        
        // Send email with reset link
        $to = $email;
        $subject = "Password Reset Request";
        $message = "Please click the following link to reset your password:

http://example.com/reset_password.php?token=$token

If you did not request this, please ignore this email.";
        $headers = "From: noreply@example.com";
        
        mail($to, $subject, $message, $headers);
        
        echo "An email has been sent to $email with instructions to reset your password.";
    }
}
?>


<?php
// Database configuration
$host = 'localhost';
$username = 'root';
$password = '';
$database = 'mydatabase';

if (isset($_GET['token'])) {
    $token = htmlspecialchars($_GET['token']);
    
    // Check if token exists and is valid
    $conn = new mysqli($host, $username, $password, $database);
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    
    // Check token expiration and validity
    $stmt = $conn->prepare("SELECT id FROM users WHERE reset_token = ? AND reset_expires > NOW() AND active = 1");
    $stmt->bind_param('s', $token);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows == 0) {
        echo "Invalid or expired token.";
    } else {
        // Token is valid, show password reset form
?>
<!DOCTYPE html>
<html>
<head>
    <title>Reset Password</title>
</head>
<body>
    <h2>Reset Password</h2>
    <form action="change_password.php" method="post">
        <input type="hidden" name="token" value="<?php echo $token; ?>">
        <p>New Password:</p>
        <input type="password" name="new_password" required>
        <br><br>
        <input type="submit" value="Change Password">
    </form>
</body>
</html>
<?php
    }
    
    // Close connections
    $stmt->close();
    $conn->close();
} else {
    echo "No token provided.";
}
?>


<?php
// Database configuration
$host = 'localhost';
$username = 'root';
$password = '';
$database = 'mydatabase';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $token = htmlspecialchars($_POST['token']);
    $newPassword = htmlspecialchars($_POST['new_password']);
    
    // Validate token again
    $conn = new mysqli($host, $username, $password, $database);
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    
    $stmt = $conn->prepare("SELECT id FROM users WHERE reset_token = ? AND reset_expires > NOW() AND active = 1");
    $stmt->bind_param('s', $token);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows == 0) {
        echo "Invalid or expired token.";
    } else {
        // Update password
        $hash = password_hash($newPassword, PASSWORD_DEFAULT);
        
        $updateStmt = $conn->prepare("UPDATE users SET password = ?, reset_token = NULL WHERE reset_token = ?");
        $updateStmt->bind_param('ss', $hash, $token);
        $updateStmt->execute();
        
        // Invalidate the token
        $invalidateStmt = $conn->prepare("UPDATE users SET reset_token = NULL WHERE reset_token = ?");
        $invalidateStmt->bind_param('s', $token);
        $invalidateStmt->execute();
        
        echo "Password has been successfully updated.";
    }
    
    // Close connections
    $stmt->close();
    $updateStmt->close();
    $invalidateStmt->close();
    $conn->close();
}
?>


<?php
session_start();
include('config.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    
    $sql = "SELECT * FROM users WHERE email='$email'";
    $result = mysqli_query($conn, $sql);
    
    if (mysqli_num_rows($result) > 0) {
        $token = md5(rand(1000, 9999));
        $expires = date('Y-m-d H:i:s', time() + 3600); // Expires in 1 hour
        
        $sql_update = "UPDATE users SET reset_token='$token', expires='$expires' WHERE email='$email'";
        mysqli_query($conn, $sql_update);
        
        $reset_link = "http://$_SERVER[HTTP_HOST]/reset.php?token=$token";
        $to = $email;
        $subject = 'Password Reset Request';
        $message = "Click the link below to reset your password:
$reset_link
If you didn't request this, please ignore.";
        
        mail($to, $subject, $message);
        echo "Check your email for reset instructions.";
    } else {
        echo "Email not found!";
    }
}
?>


<?php
session_start();
include('config.php');

$token = isset($_GET['token']) ? $_GET['token'] : '';
$sql = "SELECT * FROM users WHERE reset_token='$token' AND expires > NOW()";
$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) == 0) {
    die("Invalid or expired link.");
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];
    
    if ($new_password != $confirm_password) {
        echo "Passwords don't match!";
    } else {
        $hash = password_hash($new_password, PASSWORD_DEFAULT);
        $sql_reset = "UPDATE users SET password='$hash', reset_token='', expires='' WHERE reset_token='$token'";
        mysqli_query($conn, $sql_reset);
        header("Location: login.php?success=1");
        exit();
    }
}
?>


<?php
// Include required files
require_once 'config.php';
require_once 'functions.php';

// Start session
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    
    // Check if email exists in database
    $query = "SELECT id, password_reset_token FROM users WHERE email = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        // Generate a new password reset token
        $token = bin2hex(random_bytes(16));
        
        // Create hashed token for security
        $hashedToken = password_hash($token, PASSWORD_DEFAULT);
        
        // Set expiration time (e.g., 30 minutes from now)
        $expirationTime = date('Y-m-d H:i:s', strtotime('+30 minutes'));
        
        // Update the user's record with the new token and expiration
        $updateQuery = "UPDATE users SET password_reset_token = ?, token_expires_at = ? WHERE email = ?";
        $updateStmt = $conn->prepare($updateQuery);
        $updateStmt->bind_param("sss", $hashedToken, $expirationTime, $email);
        
        if ($updateStmt->execute()) {
            // Send reset link to user's email
            $resetLink = "http://$_SERVER[HTTP_HOST]/reset-password.php?token=$token";
            
            $subject = "Password Reset Request";
            $message = "Someone requested a password reset for your account. 

If this was you, please click the following link: 
$resetLink

If this wasn't you, simply ignore this email.
The link will expire in 30 minutes.";
            
            // Use PHP's mail function (or use a library like PHPMailer)
            $headers = "From: noreply@yourdomain.com";
            
            if (mail($email, $subject, $message, $headers)) {
                echo "Password reset email has been sent to your inbox!";
            } else {
                echo "Error sending password reset email. Please try again later.";
            }
        } else {
            echo "An error occurred while updating the password reset token.";
        }
    } else {
        // Email does not exist in database
        echo "No account found with this email address.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Forgot Password</title>
</head>
<body>
    <h1>Forgot Password</h1>
    
    <?php if (isset($_SESSION['error'])) { ?>
        <p style="color: red;"><?php echo $_SESSION['error']; ?></p>
    <?php } ?>
    
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <label for="email">Email:</label><br>
        <input type="text" id="email" name="email"><br><br>
        <input type="submit" value="Send Reset Link">
    </form>
    
    <p>Already have an account? <a href="login.php">Login here</a></p>
</body>
</html>


<?php
// Include database connection file
include 'db_connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    
    // Validate input
    if (empty($email)) {
        die("Please enter your email address");
    }
    
    // Check if the email exists in the database
    $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $row = $stmt->fetch();
    
    if (!$row) {
        die("Email does not exist in our records");
    }
    
    // Generate a random token and expiration time
    $token = bin2hex(openssl_random_pseudo_bytes(16));
    $expiration_time = date('Y-m-d H:i:s', strtotime('+1 hour'));
    
    // Store the token in the database
    $stmt = $conn->prepare("INSERT INTO password_resets (email, token, created_at) VALUES (?, ?, ?)");
    $stmt->execute([$email, sha1($token), $expiration_time]);
    
    // Send email with reset link
    $to = $email;
    $subject = "Password Reset Request";
    $message = "
        <html>
            <head></head>
            <body>
                <h2>Password Reset</h2>
                <p>Please click the following link to reset your password:</p>
                <a href='http://example.com/reset_password.php?token=$token'>Reset Password</a><br><br>
                <small>This token will expire in one hour.</small>
            </body>
        </html>
    ";
    
    // Include PHPMailer for sending emails
    require 'PHPMailer/PHPMailer.php';
    $mail = new PHPMailer();
    $mail->isSMTP(); // Set mailer to use SMTP
    $mail->Host = 'smtp.example.com'; // Specify main and backup server
    $mail->SMTPAuth = true; // Enable SMTP authentication
    $mail->Username = 'your_username@example.com'; // SMTP username
    $mail->Password = 'your_password'; // SMTP password
    
    $mail->setFrom('from@example.com', 'Mailer');
    $mail->addAddress($to, $email);
    
    $mail->isHTML(true); // Set email format to HTML
    $mail->Subject = $subject;
    $mail->Body = $message;
    
    if ($mail->send()) {
        echo "An email has been sent to your account with instructions to reset your password.";
    } else {
        die("Mailer Error: " . $mail->ErrorInfo);
    }
}
?>


<?php
// Include database connection file
include 'db_connection.php';

if (isset($_GET['token'])) {
    $token = $_GET['token'];
    
    // Check if token exists and hasn't expired
    $stmt = $conn->prepare("SELECT * FROM password_resets WHERE token = ? AND created_at > NOW() - INTERVAL 1 HOUR");
    $stmt->execute([sha1($token)]);
    $reset = $stmt->fetch();
    
    if (!$reset) {
        die("Invalid or expired reset link");
    }
    
    // Display reset form
?>
<!DOCTYPE html>
<html>
<head>
    <title>Reset Password</title>
</head>
<body>
    <h2>Reset Your Password</h2>
    <form action="reset_password.php?token=<?php echo $token; ?>" method="post">
        New Password: <input type="password" name="new_password"><br><br>
        Confirm Password: <input type="password" name="confirm_password"><br><br>
        <input type="submit" value="Reset Password">
    </form>
</body>
</html>

<?php
} else if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $token = $_GET['token'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];
    
    // Check passwords match
    if ($new_password != $confirm_password) {
        die("Passwords do not match");
    }
    
    // Update the user's password
    $stmt = $conn->prepare("UPDATE users SET password = ? WHERE id = ?");
    $stmt->execute([password_hash($new_password, PASSWORD_DEFAULT), $reset['user_id']]);
    
    // Delete the reset token after use
    $stmt = $conn->prepare("DELETE FROM password_resets WHERE token = ?");
    $stmt->execute([sha1($token)]);
    
    echo "Password has been successfully updated!";
}
?>


<!DOCTYPE html>
<html>
<head>
    <title>Forgot Password</title>
</head>
<body>
    <h2>Forgot Password</h2>
    
    <?php
    if (isset($_GET['error'])) {
        echo "<p style='color:red;'>".$_GET['error']."</p>";
    }
    ?>

    <form action="send_reset_email.php" method="post">
        <label>Email:</label><br>
        <input type="email" name="email" required><br><br>
        
        <input type="submit" value="Reset Password">
    </form>
</body>
</html>


<?php
// Database configuration
$host = "localhost";
$username = "root";
$password = "";
$db_name = "your_database";

// Connect to database
$conn = mysqli_connect($host, $username, $password, $db_name);
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

$email = $_POST['email'];

// Check if email exists in database
$sql = "SELECT id FROM users WHERE email = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    // Email not found
    header("Location: forgot_password.php?error=Email+not+found");
    exit();
}

// Generate reset token and expiration time
$reset_token = bin2hex(random_bytes(32));
$expires = date('Y-m-d H:i:s', strtotime('+1 hour'));

$sql = "UPDATE users SET reset_token = ?, reset_expires = ? WHERE email = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ss", $reset_token, $expires, $email);

if (!$stmt->execute()) {
    // Error updating token
    header("Location: forgot_password.php?error=An+error+occurred");
    exit();
}

// Send reset email
$to = $email;
$subject = "Password Reset Request";
$message = "
Hello,

Please click the following link to reset your password:
http://yourwebsite.com/reset_password.php?token=$reset_token

This link will expire in 1 hour.

Regards,
Your Website Team
";

$headers = "From: noreply@yourwebsite.com\r
";
$headers .= "Reply-To: noreply@yourwebsite.com\r
";
$headers .= "X-Mailer: PHP/" . phpversion();

mail($to, $subject, $message, $headers);

echo "A password reset link has been sent to your email address.";

// Close database connection
$stmt->close();
$conn->close();
?>


<?php
if (!isset($_GET['token'])) {
    die("Invalid request");
}

$token = $_GET['token'];

// Database configuration
$host = "localhost";
$username = "root";
$password = "";
$db_name = "your_database";

$conn = mysqli_connect($host, $username, $password, $db_name);
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Check if token is valid and not expired
$sql = "SELECT id FROM users WHERE reset_token = ? AND reset_expires > NOW()";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $token);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die("Invalid or expired token");
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate and set new password
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    if ($password != $confirm_password) {
        echo "<p style='color:red;'>Passwords do not match</p>";
    } else if (strlen($password) < 6) {
        echo "<p style='color:red;'>Password must be at least 6 characters long</p>";
    } else {
        // Hash the password
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        
        // Update the database
        $sql = "UPDATE users SET password = ?, reset_token = NULL, reset_expires = NULL WHERE reset_token = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $hashed_password, $token);
        
        if ($stmt->execute()) {
            // Password updated successfully
            header("Location: login.php");
            exit();
        } else {
            echo "<p style='color:red;'>An error occurred while updating your password</p>";
        }
    }
}

?>

<!DOCTYPE html>
<html>
<head>
    <title>Reset Password</title>
</head>
<body>
    <h2>Reset Password</h2>
    
    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
        <label>New Password:</label><br>
        <input type="password" name="password" required><br><br>
        
        <label>Confirm New Password:</label><br>
        <input type="password" name="confirm_password" required><br><br>
        
        <input type="submit" value="Reset Password">
    </form>
</body>
</html>

// Close database connection
$stmt->close();
$conn->close();
?>


<?php
// forgot_password.php

// Include database connection file
include('db_connect.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = mysqli_real_escape_string($conn, $_POST['email']);

    // Check if the email exists in the users table
    $sql = "SELECT id FROM users WHERE email = '$email'";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) == 0) {
        die("Email not found. Please check your email and try again.");
    }

    // Generate a random token
    $token = bin2hex(random_bytes(16));

    // Store the token in the database with an expiration time
    $expires = date('Y-m-d H:i:s', time() + 7200); // Expiration after 2 hours

    $sql = "INSERT INTO password_resets (user_id, token, expires) 
            VALUES ('" . mysqli_insert_id($conn) . "', '$token', '$expires')";
    
    if (!mysqli_query($conn, $sql)) {
        die("Error: " . mysqli_error($conn));
    }

    // Send the reset link to the user's email
    $reset_link = "http://yourdomain.com/reset_password.php?token=$token&id=" . mysqli_insert_id($conn);
    
    $to = $email;
    $subject = "Password Reset Request";
    $message = "Please click on this link to reset your password: $reset_link";
    
    // Send email using PHP's mail function
    if (mail($to, $subject, $message)) {
        echo "An email has been sent to you with instructions to reset your password.";
    } else {
        die("Error sending email. Please try again later.");
    }
}

mysqli_close($conn);
?>


<?php
// reset_password.php

include('db_connect.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $token = $_POST['token'];
    $user_id = $_POST['user_id'];
    $new_password = mysqli_real_escape_string($conn, $_POST['new_password']);
    $confirm_password = mysqli_real_escape_string($conn, $_POST['confirm_password']);

    // Validate token
    $sql = "SELECT * FROM password_resets 
            WHERE user_id = '$user_id' AND token = '$token' AND expires > NOW()";
    
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) != 1) {
        die("Invalid or expired reset link. Please request a new password reset.");
    }

    // Check if passwords match
    if ($new_password !== $confirm_password) {
        die("Passwords do not match. Please try again.");
    }

    // Update the user's password
    $sql = "UPDATE users 
            SET password = '$new_password' 
            WHERE id = '$user_id'";
    
    if (!mysqli_query($conn, $sql)) {
        die("Error updating password: " . mysqli_error($conn));
    }

    // Mark the reset as used
    $sql = "UPDATE password_resets 
            SET used = 1 
            WHERE token = '$token'";
    
    mysqli_query($conn, $sql);

    echo "Password has been successfully updated!";
}

mysqli_close($conn);
?>


<?php
// Database configuration
$host = 'localhost';
$dbUsername = 'username';
$dbPassword = 'password';
$dbName = 'database_name';

// Connect to database
$conn = mysqli_connect($host, $dbUsername, $dbPassword, $dbName);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Check if form is submitted
if (isset($_POST['reset'])) {

    // Get email from form
    $email = $_POST['email'];

    // Validate email input
    if (empty($email)) {
        echo "Email is required";
        exit();
    }

    // Check if user exists in the database
    $sql = "SELECT id FROM users WHERE email = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "s", $email);
    mysqli_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if (mysqli_num_rows($result) == 0) {
        echo "Email does not exist in our database";
        exit();
    }

    // Generate reset token
    $resetToken = bin2hex(random_bytes(16));
    $tokenExpiry = date('Y-m-d H:i:s', time() + 3600); // Token expires after 1 hour

    // Update database with the reset token and expiry time
    $updateSql = "UPDATE users SET reset_token = ?, reset_token_expiry = ? WHERE email = ?";
    $stmt = mysqli_prepare($conn, $updateSql);
    mysqli_stmt_bind_param($stmt, "sss", $resetToken, $tokenExpiry, $email);

    if (!mysqli_execute($stmt)) {
        echo "Error resetting password";
        exit();
    }

    // Send reset email
    $to = $email;
    $subject = "Password Reset Request";
    
    $message = "
        <html>
        <head></head>
        <body>
            <p>Hello,</p>
            <p>We received a request to reset your password. Please click the link below to reset your password:</p>
            <a href='http://example.com/reset-password.php?token=$resetToken'>Reset Password</a>
            <p>If you did not make this request, please ignore this email.</p>
            <p>This link will expire in one hour.</p>
        </body>
        </html>
    ";

    $headers = "MIME-Version: 1.0" . "\r
";
    $headers .= "Content-type:text/html;charset=UTF-8" . "\r
";
    $headers .= "From: example@example.com" . "\r
";

    if (mail($to, $subject, $message, $headers)) {
        echo "A password reset link has been sent to your email address.";
    } else {
        echo "Failed to send the password reset link. Please try again later.";
    }
}

// Close database connection
mysqli_close($conn);
?>


<?php
// Database configuration
$host = 'localhost';
$username = 'root';
$password = '';
$dbname = 'mydatabase';

// Create database connection
$conn = mysqli_connect($host, $username, $password, $dbname);
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

function sendResetEmail($email, $resetToken) {
    // Email content
    $to = $email;
    $subject = 'Password Reset Request';
    $message = '
        <html>
            <head></head>
            <body>
                <h2>Password Reset</h2>
                <p>Please click the link below to reset your password:</p>
                <a href="http://example.com/reset-password.php?token=' . $resetToken . '">Reset Password</a><br><br>
                <small>If you did not request this password reset, please ignore this email.</small>
            </body>
        </html>
    ';
    $headers = 'MIME-Version: 1.0' . "\r
";
    $headers .= 'Content-type: text/html; charset=UTF-8' . "\r
";
    
    // Send email
    mail($to, $subject, $message, $headers);
}

function generateResetToken() {
    return bin2hex(random_bytes(16));
}

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    
    // Check if email exists in database
    $sql = "SELECT id FROM users WHERE email='$email'";
    $result = mysqli_query($conn, $sql);
    
    if (mysqli_num_rows($result) == 1) {
        $resetToken = generateResetToken();
        
        // Store token and expiration time in database
        $expires = date('Y-m-d H:i:s', strtotime('+2 hours'));
        $sql = "INSERT INTO password_resets (user_id, token, expires) VALUES ('$email', '$resetToken', '$expires')";
        mysqli_query($conn, $sql);
        
        // Send reset email
        sendResetEmail($email, $resetToken);
        
        echo 'A password reset link has been sent to your email address.';
    } else {
        echo 'Email not found in our database.';
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Forgot Password</title>
</head>
<body>
    <h2>Forgot Password</h2>
    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
        <label for="email">Enter your email:</label><br>
        <input type="email" id="email" name="email"><br><br>
        <input type="submit" value="Reset Password">
    </form>
</body>
</html>


<?php
// Database configuration
$host = 'localhost';
$username = 'root';
$password = '';
$dbname = 'mydatabase';

$conn = mysqli_connect($host, $username, $password, $dbname);
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $token = $_POST['token'];
    $newPassword = $_POST['new_password'];
    $confirmPassword = $_POST['confirm_password'];
    
    // Check if token is valid and not expired
    $sql = "SELECT * FROM password_resets WHERE token='$token'";
    $result = mysqli_query($conn, $sql);
    
    if (mysqli_num_rows($result) == 1) {
        $row = mysqli_fetch_assoc($result);
        
        // Check if token is expired
        $expires = strtotime($row['expires']);
        if ($expires < time()) {
            echo 'This password reset link has expired.';
            exit;
        }
        
        // Validate passwords
        if ($newPassword != $confirmPassword) {
            echo 'Passwords do not match.';
            exit;
        }
        
        // Update user's password
        $userId = $row['user_id'];
        $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
        $sql = "UPDATE users SET password='$hashedPassword' WHERE id='$userId'";
        mysqli_query($conn, $sql);
        
        // Delete the token from the database
        $sql = "DELETE FROM password_resets WHERE token='$token'";
        mysqli_query($conn, $sql);
        
        echo 'Your password has been successfully reset.';
    } else {
        echo 'Invalid or expired password reset link.';
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Reset Password</title>
</head>
<body>
    <?php
    if (isset($_GET['token'])) {
        $token = $_GET['token'];
        
        // Verify token exists in database
        $sql = "SELECT * FROM password_resets WHERE token='$token'";
        $result = mysqli_query($conn, $sql);
        
        if (mysqli_num_rows($result) == 1) {
            echo '<h2>Reset Password</h2>';
            echo '<form action="' . $_SERVER['PHP_SELF'] . '" method="post">';
            echo '<input type="hidden" name="token" value="' . $token . '">';
            echo '<label for="new_password">New Password:</label><br>';
            echo '<input type="password" id="new_password" name="new_password"><br><br>';
            echo '<label for="confirm_password">Confirm Password:</label><br>';
            echo '<input type="password" id="confirm_password" name="confirm_password"><br><br>';
            echo '<input type="submit" value="Change Password">';
            echo '</form>';
        } else {
            echo 'Invalid or expired password reset link.';
        }
    } else {
        echo 'No token provided. Please request a new password reset.';
    }
    ?>
</body>
</html>


<?php
// Database configuration
$host = 'localhost';
$username = 'root';
$password = '';
$database = 'my_database';

// Create connection
$conn = new mysqli($host, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

function generateResetToken() {
    return bin2hex(random_bytes(16));
}

// Reset token expiration time (e.g., 1 hour)
$expires = date('Y-m-d H:i:s', strtotime('+1 hour'));

// forgot-password.php - This is the initial form
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);
    
    if (empty($email)) {
        die("Email is required!");
    }

    // Check if email exists in database
    $stmt = $conn->prepare("SELECT id, username FROM users WHERE email = ?");
    $stmt->bind_param('s', $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Generate reset token
        $resetToken = generateResetToken();
        
        // Store token in database
        $stmt2 = $conn->prepare("INSERT INTO password_reset_tokens (user_id, token, expires) VALUES (?, ?, ?)");
        $stmt2->bind_param('iss', $result->fetch_assoc()['id'], $resetToken, $expires);
        $stmt2->execute();

        // Send email with reset link
        $to = $email;
        $subject = "Password Reset Request";
        $message = "
            <html>
                <head></head>
                <body>
                    <h2>Password Reset</h2>
                    <p>We received a request to reset your password. Click the link below to reset it:</p>
                    <a href='http://example.com/reset-password.php?token=" . $resetToken . "'>Reset Password</a>
                    <br><br>
                    <p>If you didn't request this, you can safely ignore this email.</p>
                </body>
            </html>";
        $headers = "MIME-Version: 1.0" . "\r
";
        $headers .= "Content-type:text/html;charset=UTF-8" . "\r
";
        $headers .= "From: <your_email@example.com>" . "\r
";

        mail($to, $subject, $message, $headers);

        echo "We've sent you a password reset link. Please check your email.";
    } else {
        die("Email not found in our records!");
    }
}

// reset-password.php - This is the page where users can reset their password
if (isset($_GET['token'])) {
    $resetToken = $_GET['token'];
    
    // Check if token exists and hasn't expired
    $stmt3 = $conn->prepare("SELECT user_id FROM password_reset_tokens WHERE token = ? AND expires > NOW()");
    $stmt3->bind_param('s', $resetToken);
    $stmt3->execute();
    $result2 = $stmt3->get_result();

    if ($result2->num_rows == 0) {
        die("Invalid or expired reset link!");
    }

    // If token is valid and within time limit, display password reset form
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $userId = $result2->fetch_assoc()['user_id'];
        $newPassword = trim($_POST['password']);
        $confirmPassword = trim($_POST['confirm_password']);

        if ($newPassword != $confirmPassword) {
            die("Passwords do not match!");
        }

        // Password requirements (minimum length, etc.)
        if (strlen($newPassword) < 8) {
            die("Password must be at least 8 characters long!");
        }

        // Hash password
        $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);

        // Update user's password
        $stmt4 = $conn->prepare("UPDATE users SET password = ? WHERE id = ?");
        $stmt4->bind_param('si', $hashedPassword, $userId);
        $stmt4->execute();

        // Delete used token
        $stmt5 = $conn->prepare("DELETE FROM password_reset_tokens WHERE token = ?");
        $stmt5->bind_param('s', $resetToken);
        $stmt5->execute();

        echo "Your password has been reset successfully!";
    }
}

// Close database connection
$conn->close();
?>


<?php
include('db_connection.php'); // Include your database connection file

if (isset($_POST['email'])) {
    $email = $_POST['email'];

    // Check if email exists in the database
    $query = "SELECT * FROM users WHERE email = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 0) {
        header("Location: forgot_password_form.php?error=Email not found in our records.");
        exit();
    } else {
        // Generate a unique reset token
        $token = md5(uniqid(rand(), true));
        
        // Check if the email already has an active reset request
        $check_query = "SELECT * FROM password_resets WHERE email = ?";
        $check_stmt = $conn->prepare($check_query);
        $check_stmt->bind_param("s", $email);
        $check_stmt->execute();
        $check_result = $check_stmt->get_result();

        if ($check_result->num_rows > 0) {
            // Update the existing token
            $update_query = "UPDATE password_resets SET token = ?, expire_time = DATE_ADD(NOW(), INTERVAL 1 HOUR) WHERE email = ?";
            $update_stmt = $conn->prepare($update_query);
            $update_stmt->bind_param("ss", $token, $email);
            $update_stmt->execute();
        } else {
            // Insert new reset request
            $insert_query = "INSERT INTO password_resets (email, token, expire_time) 
                            VALUES (?, ?, DATE_ADD(NOW(), INTERVAL 1 HOUR))";
            $insert_stmt = $conn->prepare($insert_query);
            $insert_stmt->bind_param("ss", $email, $token);
            $insert_stmt->execute();
        }

        // Send the reset link to the user's email
        $reset_link = "http://your-site.com/reset_password.php?token=$token";
        $to = $email;
        $subject = "Password Reset Request";
        $message = "Please click on this link to reset your password: 
$reset_link
This link will expire in 1 hour.";
        
        if (mail($to, $subject, $message)) {
            header("Location: forgot_password_form.php?success=We've sent a password reset link to your email.");
            exit();
        } else {
            header("Location: forgot_password_form.php?error=Failed to send the reset email. Please try again.");
            exit();
        }
    }
}
?>


<?php
include('db_connection.php'); // Include your database connection file

if (isset($_GET['token'])) {
    $token = $_GET['token'];

    // Verify if the token exists and is valid
    $query = "SELECT * FROM password_resets 
              WHERE token = ?
              AND expire_time > NOW()";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 0) {
        header("Location: reset_password_form.php?error=Invalid or expired token.");
        exit();
    } else {
        // Update the user's password
        if (isset($_POST['new_password']) && isset($_POST['confirm_password'])) {
            $new_password = $_POST['new_password'];
            $confirm_password = $_POST['confirm_password'];

            if ($new_password != $confirm_password) {
                header("Location: reset_password_form.php?error=Passwords do not match.");
                exit();
            }

            // Update the password in the users table
            $user_data = $result->fetch_assoc();
            $email = $user_data['email'];
            
            $update_query = "UPDATE users 
                            SET password = ?
                            WHERE email = ?";
            $update_stmt = $conn->prepare($update_query);
            $update_stmt->bind_param("ss", md5($new_password), $email); // Use a secure hashing method
            $update_stmt->execute();

            // Delete the reset token
            $delete_query = "DELETE FROM password_resets WHERE token = ?";
            $delete_stmt = $conn->prepare($delete_query);
            $delete_stmt->bind_param("s", $token);
            $delete_stmt->execute();

            header("Location: login.php?success=Password has been successfully reset.");
            exit();
        }
    }
} else {
    header("Location: forgot_password_form.php");
    exit();
}
?>


<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "your_database_name";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>


<?php
// Database connection details
$host = 'localhost';
$username = 'root';
$password = '';
$dbname = 'mydatabase';

// Connect to database
$conn = mysqli_connect($host, $username, $password, $dbname);
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize email input
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    
    if (empty($email)) {
        header("Location: forgot_password_form.php?msg=Please enter your email address.");
        exit();
    }
    
    // Check if email exists in database
    $sql = "SELECT id FROM users WHERE email='$email'";
    $result = mysqli_query($conn, $sql);
    
    if (mysqli_num_rows($result) == 0) {
        header("Location: forgot_password_form.php?msg=Email not found.");
        exit();
    }
    
    // Generate random password reset token
    $token = md5(rand());
    
    // Update the database with the token and expiration time (1 hour)
    $expires = date('Y-m-d H:i:s', time() + 3600);
    $sql = "UPDATE users SET reset_token='$token', reset_expires='$expires' WHERE email='$email'";
    mysqli_query($conn, $sql);
    
    // Send email with reset link
    $to = $email;
    $subject = "Password Reset Request";
    $message = "Please click the following link to reset your password: http://example.com/reset_password.php?token=$token";
    $headers = "From: webmaster@example.com" . "\r
" .
               "Content-type:text/html;charset=UTF-8";
    
    if (mail($to, $subject, $message, $headers)) {
        header("Location: forgot_password_form.php?msg=Password reset link sent to your email.");
    } else {
        header("Location: forgot_password_form.php?msg=Error sending email.");
    }
}

mysqli_close($conn);
?>


<?php
// Database connection details
$host = 'localhost';
$username = 'root';
$password = '';
$dbname = 'mydatabase';

// Connect to database
$conn = mysqli_connect($host, $username, $password, $dbname);
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Check if token is valid and not expired
$token = $_GET['token'];

$sql = "SELECT id, reset_token, reset_expires FROM users WHERE reset_token='$token'";
$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result);

if (!$row || $row['reset_expires'] < date('Y-m-d H:i:s')) {
    die("Invalid or expired password reset link.");
}

?>

<!DOCTYPE html>
<html>
<head>
    <title>Reset Password</title>
</head>
<body>
    <h2>Reset Password</h2>
    
    <form action="reset_password.php" method="post">
        <input type="hidden" name="token" value="<?php echo $token; ?>">
        
        <label for="new_password">New Password:</label><br>
        <input type="password" id="new_password" name="new_password"><br><br>
        
        <label for="confirm_password">Confirm Password:</label><br>
        <input type="password" id="confirm_password" name="confirm_password"><br><br>
        
        <input type="submit" value="Reset Password">
    </form>
</body>
</html>

<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $token = $_POST['token'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];
    
    // Validate password requirements
    if ($new_password != $confirm_password) {
        die("Passwords do not match.");
    }
    
    if (strlen($new_password) < 8) {
        die("Password must be at least 8 characters long.");
    }
    
    // Additional password complexity checks can be added here
    
    // Update the user's password
    $hashed_password = md5($new_password); // Note: Consider using a stronger hashing algorithm like bcrypt
    $sql = "UPDATE users SET password='$hashed_password', reset_token='', reset_expires='' WHERE reset_token='$token'";
    mysqli_query($conn, $sql);
    
    header("Location: login.php?msg=Password has been reset successfully.");
}

mysqli_close($conn);
?>


<?php
// Include database configuration file
require_once 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if email is provided and not empty
    $email = trim($_POST['email']);
    
    // Validate email format
    if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
        // Check if the email exists in the database
        $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->bind_param('s', $email);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            // Generate a unique reset token
            $resetToken = bin2hex(random_bytes(16));
            
            // Set the expiration time (e.g., 1 hour from now)
            $expires = date('Y-m-d H:i:s', strtotime('+1 hour'));
            
            // Update the users table with the new reset token and expiration time
            $stmtReset = $conn->prepare("UPDATE users SET reset_token = ?, reset_expires = ? WHERE email = ?");
            $stmtReset->bind_param('sss', $resetToken, $expires, $email);
            $stmtReset->execute();
            
            // Send the password reset email
            $to = $email;
            $subject = "Password Reset Request";
            $message = "Please click on the following link to reset your password:

";
            $message .= "http://example.com/reset-password.php?token=" . urlencode($resetToken) . "&email=" . urlencode($email);
            $headers = "From: no-reply@example.com\r
";
            mail($to, $subject, $message, $headers);
            
            echo "Password reset email has been sent to your email address.";
        } else {
            echo "Email does not exist in our database.";
        }
    } else {
        echo "Please enter a valid email address.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Forgot Password</title>
</head>
<body>
    <h2>Forgot Password</h2>
    <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
        <input type="email" name="email" placeholder="Enter your email address" required>
        <button type="submit">Reset Password</button>
    </form>
</body>
</html>


<?php
// Include database configuration
include('config.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);

    // Check if email is not empty and valid
    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        die("Invalid email format");
    }

    // Check if email exists in the database
    try {
        $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        if (!$user) {
            die("Email not found. Please check your email and try again.");
        }

        // Generate a random token
        $token = bin2hex(random_bytes(32));

        // Set token expiration to 1 hour from now
        $expires = date('Y-m-d H:i:s', strtotime('+1 hour'));

        // Update the database with the new token and expiration time
        $stmt = $pdo->prepare("UPDATE users SET reset_token = ?, reset_expires = ? WHERE email = ?");
        $stmt->execute([$token, $expires, $email]);

        // Send email to user
        $to = $email;
        $subject = "Password Reset Request";
        
        $message = "
            <html>
            <head></head>
            <body>
                <h2>Password Reset</h2>
                <p>Please click the link below to reset your password:</p>
                <a href='http://example.com/reset_password.php?token=$token&id=$user[id]'>
                    http://example.com/reset_password.php?token=$token&id=$user[id]
                </a>
                <br><br>
                <p>If you did not request this password reset, please ignore this email.</p>
            </body>
            </html>
        ";

        // Set headers for HTML email
        $headers = "MIME-Version: 1.0" . "\r
";
        $headers .= "Content-type:text/html;charset=UTF-8" . "\r
";
        $headers .= "From: <noreply@example.com>" . "\r
";

        // Send the email
        if (mail($to, $subject, $message, $headers)) {
            echo "An email has been sent to your address. Please check your inbox.";
        } else {
            die("Failed to send password reset email.");
        }
    } catch(PDOException $e) {
        die("Database error: " . $e->getMessage());
    }
}
?>


<?php
// Include database configuration
include('config.php');

if (isset($_GET['token']) && isset($_GET['id'])) {
    $token = $_GET['token'];
    $id = $_GET['id'];

    // Check if token is valid and not expired
    try {
        $stmt = $pdo->prepare("SELECT * FROM users WHERE id = ? AND reset_token = ? AND reset_expires > NOW()");
        $stmt->execute([$id, $token]);
        $user = $stmt->fetch();

        if (!$user) {
            die("Invalid or expired token. Please request a new password reset.");
        }

    } catch(PDOException $e) {
        die("Database error: " . $e->getMessage());
    }
} else {
    die("Missing parameters. Please use the link from your email.");
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Reset Password</title>
</head>
<body>
    <h2>Reset Password</h2>
    <form action="reset_password.php?token=<?php echo $token; ?>&id=<?php echo $id; ?>" method="post">
        New Password: <input type="password" name="new_password" required><br>
        Confirm Password: <input type="password" name="confirm_password" required><br>
        <button type="submit">Reset Password</button>
    </form>
</body>
</html>


<?php
// Include database configuration
include('config.php');

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_GET['token']) && isset($_GET['id'])) {
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    // Check if passwords match
    if ($new_password !== $confirm_password) {
        die("Passwords do not match. Please try again.");
    }

    // Validate password length (minimum 8 characters)
    if (strlen($new_password) < 8) {
        die("Password must be at least 8 characters long.");
    }

    $token = $_GET['token'];
    $id = $_GET['id'];

    try {
        // Check token again
        $stmt = $pdo->prepare("SELECT * FROM users WHERE id = ? AND reset_token = ? AND reset_expires > NOW()");
        $stmt->execute([$id, $token]);
        $user = $stmt->fetch();

        if (!$user) {
            die("Invalid or expired token. Please request a new password reset.");
        }

        // Hash the new password
        $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

        // Update the password and clear the reset token
        $stmt = $pdo->prepare("UPDATE users SET password = ?, reset_token = NULL, reset_expires = NULL WHERE id = ?");
        $stmt->execute([$hashed_password, $id]);

        echo "Password successfully updated. You can now <a href='login.php'>login</a> with your new password.";

    } catch(PDOException $e) {
        die("Database error: " . $e->getMessage());
    }
}
?>


<?php
// Database configuration
$host = 'localhost';
$username = 'root';
$password = '';
$dbname = 'mydatabase';

// Create connection
$conn = new mysqli($host, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// If form is submitted
if (isset($_POST['submit'])) {
    // Get email from form
    $email = $_POST['email'];

    // Validate email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "Invalid email format";
        exit();
    }

    // Check if email exists in database
    $sql = "SELECT id FROM users WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        echo "Email not found in database";
        exit();
    }

    // Generate temporary password
    $tempPassword = generateTempPassword(8);

    // Update user's password with temporary password
    $updateSql = "UPDATE users SET password = ? WHERE email = ?";
    $stmt = $conn->prepare($updateSql);
    $stmt->bind_param("ss", $tempPassword, $email);
    $stmt->execute();

    // Send email to user with temporary password
    $to = $email;
    $subject = "Your Temporary Password";
    $message = "Your temporary password is: " . $tempPassword . "
Please login and reset your password.";
    $headers = "From: admin@example.com";

    mail($to, $subject, $message, $headers);

    // Redirect to login page
    header("Location: forgot_password.php?success=1");
    exit();
}

// Function to generate temporary password
function generateTempPassword($length) {
    $characters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
    $password = '';
    for ($i = 0; $i < $length; $i++) {
        $randomIndex = rand(0, strlen($characters) - 1);
        $password .= $characters[$randomIndex];
    }
    return $password;
}

// Close database connection
$conn->close();
?>


<?php
session_start();
include('db_connection.php'); // Include your database connection file

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $username = $_POST['username'];

    // Generate a unique token for password reset
    $token = bin2hex(random_bytes(16));
    $token_expiry = date('Y-m-d H:i:s', strtotime('+1 hour'));

    // Check if user exists with the provided email or username
    $sql = "SELECT * FROM users WHERE email = ? OR username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $email, $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // User exists, store the token in the database
        $row = $result->fetch_assoc();
        $user_id = $row['id'];

        $insert_sql = "INSERT INTO password_reset (user_id, token, created_at) 
                       VALUES (?, ?, ?)";
        $stmt_insert = $conn->prepare($insert_sql);
        $stmt_insert->bind_param("iss", $user_id, $token, $token_expiry);

        if ($stmt_insert->execute()) {
            // Send email with reset link
            $reset_link = "http://yourdomain.com/reset_password.php?token=" . $token;
            $to = $email;
            $subject = "Password Reset Request";
            $message = "Please click the following link to reset your password: 
" . $reset_link . "

This link will expire in 1 hour.";

            if (send_email($to, $subject, $message)) {
                $_SESSION['message'] = "A password reset link has been sent to your email address.";
                header("Location: forgot_password.php");
                exit();
            }
        } else {
            $_SESSION['message'] = "Error storing reset token. Please try again later.";
            header("Location: forgot_password.php");
            exit();
        }
    } else {
        $_SESSION['message'] = "Email or username does not exist.";
        header("Location: forgot_password.php");
        exit();
    }
}

// Function to send email
function send_email($to, $subject, $message) {
    // Use PHPMailer or your preferred method here
    $headers = 'From: webmaster@example.com' . "\r
" .
               'Reply-To: webmaster@example.com' . "\r
" .
               'X-Mailer: PHP/' . phpversion();
    return mail($to, $subject, $message, $headers);
}
?>


<?php
session_start();

include('db_connection.php');

if (!isset($_GET['token'])) {
    die("Invalid request.");
}

$token = $_GET['token'];

// Check if token exists and is valid
$sql = "SELECT * FROM password_reset 
        WHERE token = ? AND created_at > DATE_SUB(NOW(), INTERVAL 1 HOUR)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $token);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    die("Invalid or expired token.");
}

$user_data = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Reset Password</title>
</head>
<body>
    <?php
    if (isset($_SESSION['message'])) {
        echo $_SESSION['message'];
        unset($_SESSION['message']);
    }
    ?>

    <h2>Reset Password</h2>
    <form action="reset_password_process.php" method="post">
        <input type="hidden" name="token" value="<?php echo $token; ?>">
        
        <label for="new_password">New Password:</label><br>
        <input type="password" id="new_password" name="new_password" required><br>

        <label for="confirm_password">Confirm Password:</label><br>
        <input type="password" id="confirm_password" name="confirm_password" required><br>

        <input type="submit" value="Reset Password">
    </form>
</body>
</html>


<?php
session_start();

include('db_connection.php');

if (!isset($_POST['token'])) {
    die("Invalid request.");
}

$token = $_POST['token'];
$new_password = $_POST['new_password'];
$confirm_password = $_POST['confirm_password'];

// Validate passwords match and meet criteria
if ($new_password != $confirm_password) {
    $_SESSION['message'] = "Passwords do not match.";
    header("Location: reset_password.php?token=" . $token);
    exit();
}

if (strlen($new_password) < 8) {
    $_SESSION['message'] = "Password must be at least 8 characters long.";
    header("Location: reset_password.php?token=" . $token);
    exit();
}

// Check if token is valid and not expired
$sql = "SELECT * FROM password_reset 
        WHERE token = ? AND created_at > DATE_SUB(NOW(), INTERVAL 1 HOUR)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $token);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    die("Invalid or expired token.");
}

$user_data = $result->fetch_assoc();

// Update the user's password
$new_password_hashed = password_hash($new_password, PASSWORD_DEFAULT);

$update_sql = "UPDATE users SET password = ? WHERE id = ?";
$stmt_update = $conn->prepare($update_sql);
$stmt_update->bind_param("si", $new_password_hashed, $user_data['user_id']);

if ($stmt_update->execute()) {
    // Invalidate the reset token
    $invalidate_sql = "DELETE FROM password_reset WHERE token = ?";
    $stmt_invalidate = $conn->prepare($invalidate_sql);
    $stmt_invalidate->bind_param("s", $token);
    $stmt_invalidate->execute();

    $_SESSION['message'] = "Password has been successfully reset.";
    header("Location: login.php");
    exit();
} else {
    $_SESSION['message'] = "Error resetting password. Please try again later.";
    header("Location: reset_password.php?token=" . $token);
    exit();
}
?>


<?php
// Database connection
$servername = "localhost";
$username = "username";
$password = "password";
$dbname = "myDB";

$conn = mysqli_connect($servername, $username, $password, $dbname);
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Session start
session_start();

// Error messages initialization
$error = array();
$email = '';

// If form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate email
    if (empty($_POST['email'])) {
        $error[] = "Email is required";
    } else {
        $email = mysqli_real_escape_string($conn, $_POST['email']);
        // Check if email exists in the database
        $sql = "SELECT id FROM users WHERE email='$email'";
        $result = mysqli_query($conn, $sql);
        
        if (mysqli_num_rows($result) == 0) {
            $error[] = "Email not found. Please enter a valid email address.";
        } else {
            // Generate a random token
            $token = bin2hex(random_bytes(16));
            
            // Set token expiration time (e.g., 2 hours)
            $expires = date('Y-m-d H:i:s', time() + 7200);
            
            // Update the token and expiration in the database
            $sql = "UPDATE users SET reset_token='$token', reset_expires='$expires' WHERE email='$email'";
            mysqli_query($conn, $sql);
            
            // Send password reset link to user's email
            $reset_link = "http://$_SERVER[HTTP_HOST]/reset_password.php?token=$token&id=" . mysqli_insert_id($conn);
            send_reset_email($email, $reset_link);
            
            // Success message
            header("Location: forgot_password_success.php");
            exit();
        }
    }
}

// Function to send reset email
function send_reset_email($to, $link) {
    require 'PHPMailer/PHPMailerAutoload.php';
    
    $mail = new PHPMailer;
    
    $mail->isSMTP();
    $mail->Host = 'smtp.example.com'; // Replace with your SMTP server
    $mail->Port = 587; // Replace with your SMTP port
    $mail->SMTPSecure = 'tls'; // Enable TLS encryption, `ssl` also acceptable
    
    $mail->SMTPAuth = true;
    $mail->Username = 'your_email@example.com';
    $mail->Password = 'your_password';
    
    $mail->setFrom('your_email@example.com', 'Your Name');
    $mail->addAddress($to);
    $mail->Subject = 'Password Reset Request';
    $mail->Body    = "Click the following link to reset your password: $link";
    
    if (!$mail->send()) {
        echo 'Mailer Error: ' . $mail->ErrorInfo;
    }
}

// Close database connection
mysqli_close($conn);
?>


<?php
// forgot-password-process.php

$servername = "localhost";
$username = "username";
$password = "password";
$dbname = "myDB";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$email = $_POST['email'];

// Check if email exists in the database
$sql = "SELECT id FROM users WHERE email='$email'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // Generate a random token
    $token = bin2hex(random_bytes(16));
    
    // Store the token and expiration time in the database
    $expires = date('Y-m-d H:i:s', time() + 3600); // Token expires after 1 hour
    
    $sql = "INSERT INTO password_resets (user_id, token, created_at) VALUES ('$id', '$token', '$expires')";
    if ($conn->query($sql)) {
        // Send email with reset link
        $to = $email;
        $subject = "Password Reset Request";
        $message = "Please click the following link to reset your password: http://example.com/reset-password.php?token=$token";
        $headers = "From: noreply@example.com\r
";
        
        if (mail($to, $subject, $message, $headers)) {
            echo "An email has been sent with instructions to reset your password.";
        } else {
            echo "Error sending email.";
        }
    } else {
        echo "Error storing token: " . $conn->error;
    }
} else {
    echo "Email not found in our records.";
}

$conn->close();
?>


<?php
// reset-password.php

$servername = "localhost";
$username = "username";
$password = "password";
$dbname = "myDB";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$token = $_GET['token'];

// Check if token exists and is not expired
$sql = "SELECT * FROM password_resets WHERE token='$token'";
$result = $conn->query($sql);

if ($result->num_rows == 1) {
    $row = $result->fetch_assoc();
    $expires = $row['created_at'];
    
    if (strtotime($expires) < time()) {
        echo "This reset link has expired.";
        exit;
    }
} else {
    echo "Invalid or non-existent token.";
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Process password reset
    $new_password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    
    if ($new_password != $confirm_password) {
        die("Passwords do not match.");
    }
    
    // Update the user's password
    $sql = "UPDATE users SET password='" . password_hash($new_password, PASSWORD_DEFAULT) . "' WHERE id='" . $row['user_id'] . "'";
    if ($conn->query($sql)) {
        // Invalidate the token after use
        $sql = "DELETE FROM password_resets WHERE token='$token'";
        $conn->query($sql);
        
        echo "Password updated successfully!";
    } else {
        echo "Error updating password: " . $conn->error;
    }
}

?>

<!DOCTYPE html>
<html>
<head>
    <title>Reset Password</title>
</head>
<body>
    <h2>Reset Password</h2>
    <form action="<?php echo $_SERVER['PHP_SELF'] ?>?token=<?php echo $token ?>" method="post">
        <label>New Password:</label><br>
        <input type="password" name="password" required><br><br>
        <label>Confirm New Password:</label><br>
        <input type="password" name="confirm_password" required><br><br>
        <input type="submit" value="Reset Password">
    </form>
</body>
</html>

<?php
$conn->close();
?>


<?php
// Include database connection
include 'db_connection.php';

// Function to handle forgot password
function forgotPassword($email) {
    // Check if email is provided
    if (empty($email)) {
        return "Email is required";
    }

    // Validate email format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return "Invalid email format";
    }

    // Check if email exists in the database
    $sql = "SELECT id FROM users WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        return "No account found with this email";
    }

    // Generate reset token
    $token = bin2hex(random_bytes(16));
    
    // Set expiration time (e.g., 1 hour from now)
    $expires = date('Y-m-d H:i:s', strtotime('+1 hour'));

    // Update the database with the new token and expiration time
    $sql = "UPDATE users SET reset_token = ?, reset_expires = ? WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sss", $token, $expires, $email);
    $stmt->execute();

    // Send reset password email
    $subject = 'Password Reset Request';
    $message = "Please click the following link to reset your password:

";
    $message .= "http://example.com/reset-password.php?token=$token";
    
    if (mail($email, $subject, $message)) {
        return "Password reset email has been sent. Please check your inbox.";
    } else {
        return "Failed to send password reset email.";
    }
}

// Example usage:
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    echo forgotPassword($email);
}
?>


<?php
// Include database connection
include 'db_connection.php';

if (isset($_GET['token'])) {
    $token = $_GET['token'];
    
    // Check if token exists and hasn't expired
    $sql = "SELECT id, email FROM users WHERE reset_token = ? AND reset_expires > NOW()";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        die("Invalid or expired token");
    }

    // Show password reset form
    ?>
    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
        New Password: <input type="password" name="new_password"><br>
        Confirm Password: <input type="password" name="confirm_password"><br>
        <input type="hidden" name="token" value="<?php echo $token; ?>">
        <input type="submit" value="Reset Password">
    </form>
    <?php
} else {
    die("No token provided");
}

// Handle password reset submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $token = $_POST['token'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    if ($new_password !== $confirm_password) {
        die("Passwords do not match");
    }

    // Update password
    $sql = "UPDATE users SET password = ?, reset_token = NULL, reset_expires = NULL WHERE reset_token = ?";
    $stmt = $conn->prepare($sql);
    $hashedPassword = password_hash($new_password, PASSWORD_DEFAULT);
    $stmt->bind_param("ss", $hashedPassword, $token);
    $stmt->execute();

    echo "Password has been successfully updated!";
}
?>


<?php
session_start();
include('db_connection.php'); // Include your database connection

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = mysqli_real_escape_string($conn, $_POST['email']);

    // Check if email exists in the database
    $query = "SELECT id FROM users WHERE email='$email'";
    $result = mysqli_query($conn, $query);
    
    if (mysqli_num_rows($result) == 0) {
        die("Email not found. Please check your email and try again.");
    }

    // Generate a random token
    $token = bin2hex(random_bytes(16));
    $expires = date('Y-m-d H:i:s', strtotime('+1 hour'));

    // Update the database with the new token and expiration time
    $updateQuery = "UPDATE users SET reset_token='$token', reset_expires='$expires' WHERE email='$email'";
    mysqli_query($conn, $updateQuery);

    // Send email to user
    $to = $email;
    $subject = 'Password Reset Request';
    
    $message = '
        <html>
            <head>
                <title>Password Reset</title>
            </head>
            <body>
                <h2>Password Reset</h2>
                <p>Please click the following link to reset your password:</p>
                <a href="http://example.com/reset_password.php?token=' . $token . '">Reset Password</a><br><br>
                <small>This link will expire in one hour.</small>
            </body>
        </html>
    ';
    
    // Set headers for email
    $headers = 'MIME-Version: 1.0' . "\r
";
    $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r
";
    $headers .= 'From: noreply@example.com' . "\r
";

    // Send the email
    mail($to, $subject, $message, $headers);

    // Redirect back to a message page
    header("Location: password_reset_sent.php");
}
?>


<?php
session_start();
include('db_connection.php');

if (!isset($_GET['token'])) {
    die("Invalid request.");
}

$token = mysqli_real_escape_string($conn, $_GET['token']);

// Check if the token exists and hasn't expired
$query = "SELECT id, reset_expires FROM users WHERE reset_token='$token'";
$result = mysqli_query($conn, $query);

if (mysqli_num_rows($result) == 0) {
    die("Invalid or expired token.");
}

$row = mysqli_fetch_assoc($result);
$expires = $row['reset_expires'];

if ($expires < date('Y-m-d H:i:s')) {
    die("Token has expired. Please request a new password reset.");
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Reset Password</title>
</head>
<body>
    <h2>Reset Password</h2>
    <form action="reset_password.php?token=<?php echo $token; ?>" method="post">
        <label for="password">New Password:</label><br>
        <input type="password" id="password" name="password"><br><br>
        <label for="confirm_password">Confirm New Password:</label><br>
        <input type="password" id="confirm_password" name="confirm_password"><br><br>
        <input type="submit" value="Reset Password">
    </form>
</body>
</html>

<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $newPassword = mysqli_real_escape_string($conn, $_POST['password']);
    $confirmPassword = mysqli_real_escape_string($conn, $_POST['confirm_password']);

    if ($newPassword != $confirmPassword) {
        die("Passwords do not match.");
    }

    // Update the password
    $hash = password_hash($newPassword, PASSWORD_DEFAULT);
    
    $updateQuery = "UPDATE users SET password='$hash', reset_token='', reset_expires='' WHERE reset_token='$token'";
    mysqli_query($conn, $updateQuery);

    header("Location: password_reset_success.php");
}
?>


<?php
session_start();
include('config.php');

// Function to handle password reset request
function forgotPassword() {
    $email = $_POST['email'];
    
    // Check if email exists in the database
    $stmt = $conn->prepare("SELECT id, username FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        // Generate a random token
        $token = bin2hex(random_bytes(16));
        
        // Insert the token into the database with an expiration time (e.g., 1 hour)
        $expires = date('Y-m-d H:i:s', time() + 3600);
        $stmt = $conn->prepare("INSERT INTO reset_tokens (user_id, token, expires) VALUES (?, ?, ?)");
        $stmt->bind_param("iss", $row['id'], $token, $expires);
        $stmt->execute();
        
        // Send email with reset link
        $resetLink = "http://yourwebsite.com/reset-password.php?token=" . $token;
        sendResetEmail($email, $resetLink);
    } else {
        echo "Email not found in our records.";
    }
}

// Function to validate and process the password reset token
function validateToken() {
    $token = $_GET['token'];
    
    // Check if token exists and hasn't expired
    $stmt = $conn->prepare("SELECT user_id FROM reset_tokens WHERE token = ? AND expires > NOW()");
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        // Token is valid, show password reset form
        echo "<form action='reset-password.php' method='post'>
                <input type='password' name='new_password' placeholder='Enter new password'>
                <button type='submit'>Reset Password</button>
              </form>";
    } else {
        echo "Invalid or expired token.";
    }
}

// Function to handle password reset
function resetPassword() {
    $token = $_GET['token'];
    $newPassword = $_POST['new_password'];
    
    // Check if token is valid
    $stmt = $conn->prepare("SELECT user_id FROM reset_tokens WHERE token = ?");
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        // Update the password
        $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
        $user_id = $row['id'];
        
        $stmt = $conn->prepare("UPDATE users SET password = ? WHERE id = ?");
        $stmt->bind_param("si", $hashedPassword, $user_id);
        $stmt->execute();
        
        // Delete the token after use
        $stmt = $conn->prepare("DELETE FROM reset_tokens WHERE token = ?");
        $stmt->bind_param("s", $token);
        $stmt->execute();
        
        echo "Password reset successful!";
    } else {
        echo "Invalid or expired token.";
    }
}

// Function to send the password reset email
function sendResetEmail($email, $resetLink) {
    $to = $email;
    $subject = "Password Reset Request";
    $message = "Click on the following link to reset your password: " . $resetLink;
    $headers = "From: yourwebsite@example.com\r
";
    
    mail($to, $subject, $message, $headers);
}

// Example usage:
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    forgotPassword();
} elseif (isset($_GET['token'])) {
    validateToken();
}
?>


<?php
// Database connection details
$host = 'localhost';
$username = 'root';
$password = '';
$dbname = 'your_database';

// Create database connection
$conn = new mysqli($host, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Function to handle forgot password process
function forgotPassword($email, $conn) {
    // Validate email
    if (empty($email)) {
        return "Email is required";
    }
    
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return "Invalid email format";
    }

    // Check if email exists in the database
    $sql = "SELECT id FROM users WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        return "Email not found in our records";
    }

    // Generate reset token
    $token = bin2hex(random_bytes(16));
    $expires = date('Y-m-d H:i:s', strtotime('+1 hour'));

    // Update the database with the new token and expiration time
    $updateSql = "UPDATE users SET reset_token = ?, reset_expires = ? WHERE email = ?";
    $stmt = $conn->prepare($updateSql);
    $stmt->bind_param("sss", $token, $expires, $email);

    if (!$stmt->execute()) {
        return "An error occurred. Please try again later";
    }

    // Send reset email
    $to = $email;
    $subject = "Password Reset Request";
    $message = "
        <html>
            <head></head>
            <body>
                <p>You requested a password reset. Click the link below to reset your password:</p>
                <a href='http://yourwebsite.com/reset-password.php?token=" . $token . "&email=" . urlencode($email) . "'>Reset Password</a>
                <br>
                <small>This link will expire in 1 hour.</small>
            </body>
        </html>
    ";
    
    // Set headers for email
    $headers = "MIME-Version: 1.0" . "\r
";
    $headers .= "Content-type:text/html;charset=UTF-8" . "\r
";
    $headers .= "From: <yourwebsite@example.com>" . "\r
";

    if (mail($to, $subject, $message, $headers)) {
        return "Password reset email has been sent to your email address";
    } else {
        return "Failed to send password reset email. Please try again later.";
    }
}

// Example usage:
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $result = forgotPassword($email, $conn);
    echo $result;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Forgot Password</title>
</head>
<body>
    <h2>Forgot Password</h2>
    <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
        <input type="email" name="email" placeholder="Enter your email" required><br><br>
        <button type="submit">Reset Password</button>
    </form>
</body>
</html>

// Close database connection
$conn->close();
?>


<?php
// Database connection
$host = 'localhost';
$username = 'root';
$password = '';
$dbname = 'your_database';

$conn = new mysqli($host, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if form is submitted
if (isset($_POST['submit'])) {
    $email = $_POST['email'];
    
    // Sanitize email input
    $email = mysqli_real_escape_string($conn, $email);

    // Check if email exists in database
    $sql = "SELECT id FROM users WHERE email='$email'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // Generate a random token for password reset
        $token = bin2hex(random_bytes(16));
        
        // Insert token into the database
        $sql_token = "INSERT INTO password_reset (user_id, token) VALUES ('$id', '$token')";
        $conn->query($sql_token);
        
        // Send email with reset link
        $to = $email;
        $subject = 'Password Reset Request';
        $message = 'Please click the following link to reset your password: http://yourwebsite.com/reset_password.php?token=' . $token;
        $headers = 'From: webmaster@yourwebsite.com';

        if (mail($to, $subject, $message, $headers)) {
            echo "An email has been sent with instructions to reset your password.";
        } else {
            echo "Error sending email.";
        }
    } else {
        echo "Email address not found in our records.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Forgot Password</title>
</head>
<body>
    <h2>Forgot Password</h2>
    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
        <label for="email">Email:</label><br>
        <input type="email" id="email" name="email" required><br><br>
        <input type="submit" name="submit" value="Submit">
    </form>
</body>
</html>


<?php
// Database connection
$host = 'localhost';
$username = 'root';
$password = '';
$dbname = 'your_database';

$conn = new mysqli($host, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get token from URL
$token = $_GET['token'];

// Check if token exists in database and is not expired
$sql = "SELECT * FROM password_reset WHERE token='$token'";
$result = $conn->query($sql);

if ($result->num_rows == 1) {
    // Show reset form
    ?>
    <!DOCTYPE html>
    <html>
    <head>
        <title>Reset Password</title>
    </head>
    <body>
        <h2>Reset Password</h2>
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
            <label for="password">New Password:</label><br>
            <input type="password" id="password" name="password" required><br><br>
            <label for="confirm_password">Confirm Password:</label><br>
            <input type="password" id="confirm_password" name="confirm_password" required><br><br>
            <input type="hidden" name="token" value="<?php echo $token; ?>">
            <input type="submit" name="reset" value="Reset Password">
        </form>
    </body>
    </html>
    <?php
} else {
    // Token is invalid or expired
    die("Invalid or expired token.");
}

// Update password if form is submitted
if (isset($_POST['reset'])) {
    $token = $_POST['token'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    if ($password == $confirm_password) {
        // Hash the new password
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        
        // Update the user's password in the database
        $sql_update = "UPDATE users SET password='$hashed_password' WHERE id=(SELECT user_id FROM password_reset WHERE token='$token')";
        $conn->query($sql_update);
        
        // Delete the reset token
        $sql_delete = "DELETE FROM password_reset WHERE token='$token'";
        $conn->query($sql_delete);
        
        echo "Your password has been reset successfully!";
    } else {
        echo "Passwords do not match.";
    }
}
?>


<?php
// Database connection parameters
$host = 'localhost';
$username = 'username';
$password = 'password';
$database = 'my_database';

// Connect to the database
$conn = mysqli_connect($host, $username, $password, $database);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Check if form is submitted
if (isset($_POST['submit'])) {
    // Sanitize input email
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    
    // Validate email format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "Invalid email format";
        exit();
    }
    
    // Check if email exists in the database
    $sql = "SELECT id FROM users WHERE email = '$email'";
    $result = mysqli_query($conn, $sql);
    
    if (mysqli_num_rows($result) == 0) {
        echo "Email not found. Please check your email and try again.";
        exit();
    }
    
    // Generate temporary password
    $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*()';
    $length = strlen($chars);
    $temp_password = substr(str_shuffle($chars), 0, 10);
    
    // Hash the temporary password
    $hashed_password = password_hash($temp_password, PASSWORD_DEFAULT);
    
    // Update the user's password in the database
    $update_sql = "UPDATE users SET password = '$hashed_password' WHERE email = '$email'";
    mysqli_query($conn, $update_sql);
    
    // Send the temporary password to the user's email
    $to = $email;
    $subject = 'Your Temporary Password';
    $message = "Dear User,

Your temporary password is: " . $temp_password . "

Please log in and change your password immediately.

Best regards,
Support Team";
    
    // Set headers
    $headers = 'From: support@yourdomain.com' . "\r
" .
               'Reply-To: support@yourdomain.com' . "\r
" .
               'X-Mailer: PHP/' . phpversion();
    
    if (mail($to, $subject, $message, $headers)) {
        // Redirect to a confirmation page
        header("Location: password_reset_confirmation.php");
        exit();
    } else {
        echo "An error occurred while sending the email. Please try again later.";
        exit();
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Forgot Password</title>
</head>
<body>
    <h2>Forgot Password</h2>
    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
        <label for="email">Email:</label><br>
        <input type="text" id="email" name="email"><br><br>
        <input type="submit" name="submit" value="Submit">
    </form>
</body>
</html>


<?php
// Database connection file
include("db_connect.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    
    // Check if email exists in database
    $sql = "SELECT id FROM users WHERE email = '$email'";
    $result = mysqli_query($conn, $sql);
    
    if (mysqli_num_rows($result) == 1) {
        // Generate a temporary password
        $temp_password = generate_temp_password();
        
        // Update the user's password in the database
        $update_sql = "UPDATE users SET password = '" . md5($temp_password) . "' WHERE email = '$email'";
        mysqli_query($conn, $update_sql);
        
        // Send the temporary password to the user's email
        $to = $email;
        $subject = "Your Temporary Password";
        $message = "Dear User,

Your temporary password is: " . $temp_password . "

Please login and change your password immediately.

Best regards,
Admin Team";
        
        // Set headers for email
        $headers = "From: admin@example.com\r
";
        $headers .= "Reply-To: admin@example.com\r
";
        $headers .= "X-Mailer: PHP/" . phpversion();
        
        if (mail($to, $subject, $message, $headers)) {
            echo "Password reset email has been sent to your email address.";
        } else {
            echo "Failed to send password reset email. Please try again later.";
        }
    } else {
        echo "This email does not exist in our database.";
    }
}

function generate_temp_password() {
    $length = 8;
    $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*";
    $temp_pass = substr(str_shuffle($chars), 0, $length);
    return $temp_pass;
}
?>


<?php
// Database configuration
$host = 'localhost';
$dbname = 'your_database';
$username = 'root';
$password = '';

try {
    $conn = new PDO("mysql:host=$host;dbname=$db", $username, $password);
} catch (PDOException $e) {
    die("Could not connect to the database: " . $e->getMessage());
}

// CSRF token
function generateToken() {
    return bin2hex(random_bytes(32));
}

// Generate reset link
function generateResetLink($token) {
    return 'http://yourwebsite.com/reset-password.php?token=' . $token;
}

// Send email function
function sendEmail($to, $subject, $message) {
    $headers = "From: your_email@example.com\r
";
    $headers .= "Content-Type: text/html; charset=UTF-8\r
";

    mail($to, $subject, $message, $headers);
}

// Check if email exists
function isValidEmail($email) {
    // Add your own validation logic here
    return !empty($email) && filter_var($email, FILTER_VALIDATE_EMAIL);
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $csrfToken = $_POST['csrf_token'];
    if (!isset($_SESSION['csrf_token']) || $csrfToken !== $_SESSION['csrf_token']) {
        die("Invalid token");
    }

    session_unset();
    session_destroy();

    $email = trim($_POST['email']);
    
    if (!isValidEmail($email)) {
        die("Invalid email address");
    }

    // Check if email exists in database
    $stmt = $conn->prepare('SELECT id FROM users WHERE email = :email');
    $stmt->bindParam(':email', $email);
    $stmt->execute();

    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user) {
        die("Email not found");
    }

    // Generate reset token and expiration time
    $resetToken = generateToken();
    $expirationTime = date('Y-m-d H:i:s', strtotime('+1 hour'));

    // Store token in database
    $stmt = $conn->prepare('INSERT INTO password_resets (token, user_id, expires_at) VALUES (:token, :user_id, :expires_at)');
    $stmt->bindParam(':token', $resetToken);
    $stmt->bindParam(':user_id', $user['id']);
    $stmt->bindParam(':expires_at', $expirationTime);
    $stmt->execute();

    // Create reset link
    $resetLink = generateResetLink($resetToken);

    // Send email to user
    $subject = "Password Reset Request";
    $message = "Click the following link to reset your password: <a href='$resetLink'>Reset Password</a><br>
               This link will expire in 1 hour.";

    sendEmail($email, $subject, $message);

    echo "A password reset link has been sent to your email address.";
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Forgot Password</title>
</head>
<body>
    <h2>Forgot Password</h2>
    <?php
    session_start();
    $_SESSION['csrf_token'] = generateToken();
    ?>
    <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
        <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
        <label>Email:</label><br>
        <input type="email" name="email" required><br><br>
        <input type="submit" value="Submit">
    </form>
</body>
</html>


<?php
// Include database connection details
$host = 'localhost';
$username = 'root';
$password = '';
$dbname = 'your_database';

// Connect to the database
$conn = mysqli_connect($host, $username, $password, $dbname);
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

if (isset($_POST['reset'])) { // Assuming the form's submit button name is 'reset'
    $email = $_POST['email'];
    
    // Check if the email exists in the database
    $sql = "SELECT * FROM users WHERE email = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "s", $email);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    
    if (mysqli_num_rows($result) > 0) {
        // Generate a temporary password
        $temp_password = substr(md5(time()), 0, 6); // You can generate a more secure password
        
        // Update the database with the temporary password
        $update_sql = "UPDATE users SET password = ?, temp_password_used = 'no' WHERE email = ?";
        $stmt_update = mysqli_prepare($conn, $update_sql);
        mysqli_stmt_bind_param($stmt_update, "ss", $temp_password, $email);
        
        if (mysqli_stmt_execute($stmt_update)) {
            // Send the temporary password to the user's email
            $to = $email;
            $subject = 'Password Reset';
            $message = "Your temporary password is: $temp_password
Please use this to log in and change your password.";
            
            mail($to, $subject, $message);
            
            // Redirect back with a success message
            header("Location: forgot_password.php?msg=success");
            exit();
        } else {
            echo "Error updating password: " . mysqli_error($conn);
        }
    } else {
        echo "Email does not exist in our records.";
    }
    
    mysqli_close($conn);
} else {
    // Show an error if form wasn't submitted
    header("Location: forgot_password.php?msg=error");
}
?>


if ($row['temp_password_used'] == 'no') {
    // Set session for temp password used
    $_SESSION['id'] = $row['id'];
    header("Location: change_password.php");
    exit();
}


session_start();

if (isset($_SESSION['id'])) {
    $user_id = $_SESSION['id'];
    
    if (isset($_POST['new_password']) && isset($_POST['confirm_new_password'])) {
        $new_password = $_POST['new_password'];
        $confirm_password = $_POST['confirm_new_password'];
        
        if ($new_password == $confirm_password) {
            // Hash the new password
            $hashed_password = md5($new_password); // Consider using a stronger hashing algorithm like bcrypt
            
            // Update the database
            $sql = "UPDATE users SET password = ?, temp_password_used = 'yes' WHERE id = ?";
            $stmt = mysqli_prepare($conn, $sql);
            mysqli_stmt_bind_param($stmt, "si", $hashed_password, $user_id);
            
            if (mysqli_stmt_execute($stmt)) {
                // Clear the session and redirect to login
                session_unset();
                session_destroy();
                header("Location: login.php");
                exit();
            } else {
                echo "Error changing password: " . mysqli_error($conn);
            }
        } else {
            echo "Passwords do not match!";
        }
    }
} else {
    // Redirect unauthorized access
    header("Location: login.php");
}


<?php
// Database configuration
$host = 'localhost';
$username = 'your_username';
$password = 'your_password';
$dbname = 'your_database';

// Create connection
$conn = new mysqli($host, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Function to generate random token
function generateToken() {
    return bin2hex(random_bytes(16));
}

// Function to send password reset email
function sendPasswordResetEmail($email, $resetLink) {
    require 'PHPMailer/PHPMailer.php';
    require 'PHPMailer/SMTP.php';
    require 'PHPMailer/Exception.php';

    try {
        $mail = new PHPMailer\PHPMailer();
        $mail->isSMTP();
        $mail->Host = 'smtp.example.com'; // Replace with your SMTP server
        $mail->SMTPAuth = true;
        $mail->Username = 'your_email@example.com'; // Your email address
        $mail->Password = 'your_password'; // Your email password
        $mail->SMTPSecure = 'tls';
        $mail->Port = 587;

        $mail->setFrom('your_email@example.com', 'Your Name');
        $mail->addAddress($email);
        $mail->Subject = 'Password Reset Request';
        
        $mailContent = "Dear User,<br><br>
                        You have requested to reset your password.<br>
                        Please click the following link to reset your password:<br>
                        <a href='$resetLink'>Reset Password</a><br><br>
                        If you did not request this password reset, please ignore this email.";

        $mail->Body = $mailContent;
        
        if ($mail->send()) {
            return true;
        } else {
            return false;
        }
    } catch (Exception $e) {
        echo "Message could not be sent.Mailer Error: {$mail->ErrorInfo}";
        return false;
    }
}

// Function to handle password reset request
function handlePasswordResetRequest($email, $conn) {
    // Check if email exists in database
    $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        // Generate reset token and expiration time
        $token = generateToken();
        $expires = date('Y-m-d H:i:s', strtotime('+1 hour'));

        // Update database with new token and expiration
        $updateStmt = $conn->prepare("UPDATE users SET reset_token = ?, reset_expires = ? WHERE email = ?");
        $updateStmt->bind_param("sss", $token, $expires, $email);
        $updateStmt->execute();

        if ($updateStmt->affected_rows === 1) {
            // Generate reset link
            $resetLink = "http://yourwebsite.com/reset-password.php?token=$token";
            
            // Send email with reset link
            if (sendPasswordResetEmail($email, $resetLink)) {
                echo "Password reset email sent successfully!";
                return true;
            } else {
                echo "Failed to send password reset email.";
                return false;
            }
        } else {
            echo "Error updating user record.";
            return false;
        }
    } else {
        echo "Email not found in our records.";
        return false;
    }
}

// Example usage:
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    
    // Sanitize input
    $email = filter_var($email, FILTER_SANITIZE_EMAIL);
    
    if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
        handlePasswordResetRequest($email, $conn);
    } else {
        echo "Please enter a valid email address.";
    }
}

// Close database connection
$conn->close();
?>


<?php
// Database configuration
$host = 'localhost';
$username = 'root';
$password = '';
$dbname = 'mydatabase';

// Create connection
$conn = new mysqli($host, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// If form is submitted
if (isset($_POST['email'])) {
    $email = $_POST['email'];
    
    // Check if email exists in database
    $sql = "SELECT * FROM users WHERE email = '$email'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // Generate reset token
        $token = bin2hex(random_bytes(16));
        
        // Set token expiration time (e.g., 1 hour from now)
        $expires = date("Y-m-d H:i:s", strtotime("+1 hour"));
        
        // Update the database with the new token and expiration time
        $sql = "UPDATE users SET reset_token = '$token', reset_expires = '$expires' WHERE email = '$email'";
        if ($conn->query($sql) === TRUE) {
            // Send reset password email
            $to = $email;
            $subject = 'Password Reset Request';
            $message = '
                <html>
                    <head>
                        <title>Password Reset</title>
                    </head>
                    <body>
                        <p>Hello,</p>
                        <p>You requested to reset your password. Click the link below to reset it:</p>
                        <a href="http://yourwebsite.com/reset-password.php?token='.$token.'&email='.$email.'">Reset Password</a>
                        <p>This link will expire in one hour.</p>
                    </body>
                </html>';
            $headers = 'MIME-Version: 1.0' . "\r
";
            $headers .= 'Content-type: text/html; charset=UTF-8' . "\r
";
            
            // Send the email
            mail($to, $subject, $message, $headers);
            echo "An email has been sent to your address with instructions to reset your password.";
        } else {
            echo "Error updating record: " . $conn->error;
        }
    } else {
        echo "Email not found in our records. Please check your email and try again.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Forgot Password</title>
</head>
<body>
    <h2>Forgot Password</h2>
    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
        <label for="email">Email:</label><br>
        <input type="email" id="email" name="email"><br><br>
        <input type="submit" value="Reset Password">
    </form>
</body>
</html>

// Reset password page (reset-password.php)
<?php
// Check if token and email are provided in URL
if (!isset($_GET['token']) || !isset($_GET['email'])) {
    die("Invalid request");
}

$token = $_GET['token'];
$email = $_GET['email'];

// Verify the token and email combination exists and hasn't expired
$sql = "SELECT * FROM users WHERE email = '$email' AND reset_token = '$token' AND reset_expires > NOW()";
$result = $conn->query($sql);

if ($result->num_rows == 1) {
    // Show password reset form
?>
<!DOCTYPE html>
<html>
<head>
    <title>Reset Password</title>
</head>
<body>
    <h2>Reset Password</h2>
    <form action="reset-password.php" method="post">
        <input type="hidden" name="token" value="<?php echo $token; ?>">
        <input type="hidden" name="email" value="<?php echo $email; ?>">
        <label for="new_password">New Password:</label><br>
        <input type="password" id="new_password" name="new_password"><br><br>
        <label for="confirm_password">Confirm Password:</label><br>
        <input type="password" id="confirm_password" name="confirm_password"><br><br>
        <input type="submit" value="Reset Password">
    </form>
</body>
</html>

<?php
} else {
    echo "Invalid or expired reset link.";
}

// Handle password reset form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $token = $_POST['token'];
    $email = $_POST['email'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    if ($new_password != $confirm_password) {
        die("Passwords do not match");
    }

    // Hash the new password
    $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

    // Update the database with the new password and clear the token
    $sql = "UPDATE users SET password = '$hashed_password', reset_token = NULL, reset_expires = NULL WHERE email = '$email'";
    
    if ($conn->query($sql) === TRUE) {
        echo "Password has been successfully updated!";
    } else {
        echo "Error updating password: " . $conn->error;
    }
}

$conn->close();
?>


<?php
// Database configuration
$host = 'localhost';
$dbname = 'your_database';
$user = 'your_username';
$password = 'your_password';

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $user, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

// Generate a random token
function generateToken($length = 32) {
    return bin2hex(random_bytes($length));
}

// Reset password function
function resetPassword($conn, $email) {
    // Check if user exists
    try {
        $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$user) {
            return "Email not found!";
        }
        
        // Generate token and expiration time
        $token = generateToken();
        $expires = date('Y-m-d H:i:s', strtotime('+1 hour'));
        
        // Insert reset token into database
        $stmt = $conn->prepare("INSERT INTO password_resets (user_id, token, expires) VALUES (?, ?, ?)");
        $stmt->execute([$user['id'], $token, $expires]);
        
        // Send email with reset link
        $resetLink = "http://your-site.com/reset_password.php?token=" . $token;
        sendResetEmail($email, $resetLink);
        
        return "Password reset instructions have been sent to your email!";
    } catch(PDOException $e) {
        return "Error: " . $e->getMessage();
    }
}

// Send reset email function
function sendResetEmail($to, $link) {
    $from = 'no-reply@your-site.com';
    $subject = 'Password Reset Request';
    
    $message = "
        <html>
            <body>
                <h2>Password Reset</h2>
                <p>Please click the link below to reset your password:</p>
                <a href='$link'>$link</a>
                <br><br>
                If you did not request this password reset, please ignore this email.
            </body>
        </html>
    ";
    
    // Using PHPMailer for better email handling
    require 'PHPMailer/PHPMailer.php';
    $mail = new PHPMailer();
    
    try {
        $mail->isSMTP();
        $mail->Host = 'smtp.example.com';  // Replace with your SMTP server
        $mail->SMTPAuth = true;
        $mail->Username = 'your_email@example.com';
        $mail->Password = 'your_password';
        $mail->Port = 587;  // or 465 if using SSL
        
        $mail->setFrom($from, 'Your Site Name');
        $mail->addAddress($to);
        
        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body = $message;
        
        if ($mail->send()) {
            return true;
        } else {
            return false;
        }
    } catch (Exception $e) {
        echo "Mailer Error: " . $e->getMessage();
        return false;
    }
}

// Update password function
function updatePassword($conn, $token, $new_password) {
    try {
        // Check if token exists and is valid
        $stmt = $conn->prepare("SELECT user_id FROM password_resets WHERE token = ? AND expires > NOW()");
        $stmt->execute([$token]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$result) {
            return "Invalid or expired token!";
        }
        
        // Hash the new password
        $hashedPassword = password_hash($new_password, PASSWORD_DEFAULT);
        
        // Update user's password
        $stmt = $conn->prepare("UPDATE users SET password = ? WHERE id = ?");
        $stmt->execute([$hashedPassword, $result['user_id']]);
        
        // Delete used token
        $stmt = $conn->prepare("DELETE FROM password_resets WHERE token = ?");
        $stmt->execute([$token]);
        
        return "Password updated successfully!";
    } catch(PDOException $e) {
        return "Error: " . $e->getMessage();
    }
}

// Example usage:
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['email'])) {
        echo resetPassword($conn, $_POST['email']);
    } elseif (isset($_GET['token'], $_POST['new_password'])) {
        echo updatePassword($conn, $_GET['token'], $_POST['new_password']);
    }
}
?>


<?php
// Database configuration
$host = 'localhost';
$username = 'root';
$password = '';
$dbname = 'mydatabase';

// Create connection
$conn = new mysqli($host, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];

    // Sanitize input
    $email = mysqli_real_escape_string($conn, $email);

    // Check if email exists in the database
    $sql = "SELECT id FROM users WHERE email='$email'";
    $result = $conn->query($sql);

    if ($result->num_rows == 1) {
        // Generate a random token
        $token = bin2hex(random_bytes(32));

        // Insert token into the database
        $sql_token = "INSERT INTO password_reset (user_id, token) VALUES (".$result->fetch_assoc()['id'].", '$token')";
        if ($conn->query($sql_token)) {
            // Send email to user with reset link
            $to = $email;
            $subject = "Password Reset Request";
            $message = "
                <html>
                    <head>
                        <title>Password Reset</title>
                    </head>
                    <body>
                        <h2>Password Reset</h2>
                        <p>Please click the following link to reset your password:</p>
                        <a href='http://yourdomain.com/reset_password.php?token=$token'>Reset Password</a><br><br>
                        <small>This link will expire in 1 hour.</small>
                    </body>
                </html>
            ";
            $headers = "MIME-Version: 1.0" . "\r
";
            $headers .= "Content-type:text/html;charset=UTF-8" . "\r
";
            $headers .= "From: yourdomain.com <noreply@yourdomain.com>" . "\r
";

            if (mail($to, $subject, $message, $headers)) {
                echo "An email has been sent to you with a password reset link.";
            } else {
                echo "Error sending email. Please try again later.";
            }
        } else {
            echo "Error resetting password. Please try again later.";
        }
    } else {
        echo "Email not found in our records.";
    }
}

$conn->close();
?>


<?php
// Database configuration
$host = 'localhost';
$username = 'root';
$password = '';
$dbname = 'mydatabase';

// Create connection
$conn = new mysqli($host, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $token = $_GET['token'];
    $new_password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Validate password match
    if ($new_password != $confirm_password) {
        die("Passwords do not match. Please try again.");
    }

    // Hash the new password
    $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

    // Check if token exists and is valid
    $sql = "SELECT user_id FROM password_reset WHERE token='$token'";
    $result = $conn->query($sql);

    if ($result->num_rows == 1) {
        $user_id = $result->fetch_assoc()['user_id'];

        // Update the user's password
        $update_sql = "UPDATE users SET password='$hashed_password' WHERE id=$user_id";
        if ($conn->query($update_sql)) {
            // Delete used token
            $delete_sql = "DELETE FROM password_reset WHERE token='$token'";
            if ($conn->query($delete_sql)) {
                echo "Password reset successful. You can now login with your new password.";
            }
        } else {
            echo "Error updating password. Please try again later.";
        }
    } else {
        echo "Invalid or expired reset link. Please request a new password reset.";
    }
}

$conn->close();
?>


<?php
include("db_connection.php"); // Include your database connection file

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    
    // Validate email format
    if (!preg_match("/^([a-zA-Z0-9_\-\.]+)@([a-zA-Z0-9_\-\.]+)\.([a-zA-Z]{2,5})$/", $email)) {
        header("Location: forgot_password.php?error=Invalid%20Email%20Format");
        exit();
    }
    
    // Check if email exists in the database
    $sql = "SELECT id FROM users WHERE email='$email'";
    $result = mysqli_query($conn, $sql);
    
    if (mysqli_num_rows($result) == 0) {
        header("Location: forgot_password.php?error=Email%20not%20found");
        exit();
    } else {
        // Generate a random string for the reset token
        $token = md5(uniqid(rand(), true));
        
        // Store the token in the database with an expiration time (e.g., 1 hour)
        $expires = date('Y-m-d H:i:s', strtotime('+1 hour'));
        $sql = "INSERT INTO password_resets (user_id, token, expires) VALUES (".$row['id'].", '$token', '$expires')";
        mysqli_query($conn, $sql);
        
        // Send email with reset link
        $to = $email;
        $subject = "Password Reset Request";
        $message = "Please click the following link to reset your password: 

";
        $message .= "http://yourwebsite.com/reset_password.php?token=$token";
        $headers = "From: yourwebsite@example.com";
        
        if (mail($to, $subject, $message, $headers)) {
            header("Location: forgot_password.php?success=Password%20reset%20link%20sent");
            exit();
        } else {
            header("Location: forgot_password.php?error=Email%20could%20not%20be%20sent");
            exit();
        }
    }
}
?>


<?php
include("db_connection.php"); // Include your database connection file

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $token = mysqli_real_escape_string($conn, $_POST['token']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);
    $confirm_password = mysqli_real_escape_string($conn, $_POST['confirm_password']);
    
    // Check if the token is valid and not expired
    $sql = "SELECT user_id FROM password_resets WHERE token='$token' AND expires > NOW()";
    $result = mysqli_query($conn, $sql);
    
    if (mysqli_num_rows($result) == 0) {
        header("Location: reset_password.php?error=Invalid%20or%20expired%20token");
        exit();
    } else {
        // Check if passwords match
        if ($password != $confirm_password) {
            header("Location: reset_password.php?error=Passwords%20do%20not%20match");
            exit();
        }
        
        // Update the user's password
        $row = mysqli_fetch_assoc($result);
        $user_id = $row['user_id'];
        $new_password_hash = md5($password); // You should use a better hashing method like bcrypt
        
        $sql = "UPDATE users SET password='$new_password_hash' WHERE id=$user_id";
        mysqli_query($conn, $sql);
        
        // Delete the token from the database
        $sql = "DELETE FROM password_resets WHERE token='$token'";
        mysqli_query($conn, $sql);
        
        header("Location: login.php?success=Password%20reset%20successful");
        exit();
    }
}
?>


<?php
// Database connection details
$host = 'localhost';
$username = 'root';
$password = '';
$dbname = 'mydatabase';

// Create database connection
$conn = new mysqli($host, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Function to handle password reset request
function forgotPassword($email) {
    global $conn;

    // Generate a random token
    $token = md5(time() . rand(0, 1000));

    // Check if email exists in the database
    $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->bind_param('s', $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Update token and expiration time (1 hour from now)
        $expiration_time = date("Y-m-d H:i:s", time() + 3600); // 1 hour

        $update_stmt = $conn->prepare("UPDATE users SET reset_token = ?, reset_expires = ? WHERE email = ?");
        $update_stmt->bind_param('sss', $token, $expiration_time, $email);
        $update_stmt->execute();

        // Send password reset link to user's email
        $to = $email;
        $subject = "Password Reset Request";
        $message = "Please click the following link to reset your password: 

";
        $message .= "http://example.com/reset-password.php?token=$token

";
        $message .= "This link will expire in 1 hour.";
        
        // Set headers
        $headers = "From: noreply@example.com\r
";
        $headers .= "Reply-To: noreply@example.com\r
";
        $headers .= "X-Mailer: PHP/" . phpversion();

        mail($to, $subject, $message, $headers);

        return "Password reset instructions have been sent to your email.";
    } else {
        return "Email address not found in our records.";
    }
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (!empty($_POST['email'])) {
        $email = $_POST['email'];
        echo forgotPassword($email);
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Forgot Password</title>
</head>
<body>
    <h2>Forgot Password</h2>
    
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        <input type="email" name="email" placeholder="Enter your email address" required><br><br>
        <button type="submit">Reset Password</button>
    </form>

    <?php
    // Show any error/success messages here if needed
    ?>
    
</body>
</html>


<?php
// Include database connection
require_once 'db_connection.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    
    // Check if email exists in the database
    $sql = "SELECT id FROM users WHERE email='$email'";
    $result = mysqli_query($conn, $sql);
    
    if (mysqli_num_rows($result) == 0) {
        die("Email not found. Please try again.");
    }
    
    // Generate a unique token for password reset
    $token = bin2hex(random_bytes(16)); // Generates a 32-character string
    $expires = time() + 7200; // Token expires after 2 hours
    
    // Store the token in the database
    $sql = "INSERT INTO password_resets (email, token, expires) 
            VALUES ('$email', '$token', '$expires')";
    mysqli_query($conn, $sql);
    
    // Send reset email to user
    require_once 'PHPMailer/PHPMailer.php';
    require_once 'PHPMailer/SMTP.php';
    require_once 'PHPMailer/Exception.php';
    
    try {
        $mail = new PHPMailer\PHPMailer();
        
        // SMTP settings
        $mail->isSMTP();
        $mail->Host = 'smtp.example.com'; // Replace with your SMTP host
        $mail->SMTPAuth = true;
        $mail->Username = 'your_email@example.com';
        $mail->Password = 'your_password';
        $mail->Port = 587; // or 465
        
        // Email content
        $mail->setFrom('your_email@example.com', 'Reset Password');
        $mail->addAddress($email);
        
        $reset_link = "http://example.com/reset_password.php?token=$token";
        $message = "
            <h2>Password Reset Request</h2>
            <p>Click the following link to reset your password:</p>
            <a href='$reset_link'>$reset_link</a>
            <br><br>
            <p>If you didn't request this, please ignore this email.</p>
        ";
        
        $mail->isHTML(true);
        $mail->Subject = 'Password Reset Request';
        $mail->Body = $message;
        
        if ($mail->send()) {
            echo "An email has been sent to your address with instructions on how to reset your password.";
        } else {
            die("Email sending failed: " . $mail->ErrorInfo);
        }
    } catch (Exception $e) {
        die("Message could not be sent. Mailer Error: " . $e->getMessage());
    }
    
} else {
    // If form is accessed directly
    header('Location: forgot_password_form.html');
}
?>


<?php
// Include database connection
require_once 'db_connection.php';

if (!isset($_GET['token'])) {
    die("No token provided.");
}

$token = mysqli_real_escape_string($conn, $_GET['token']);

// Check if token exists in database and is not expired
$sql = "SELECT * FROM password_resets 
        WHERE token='$token' AND expires > CURRENT_TIMESTAMP";
$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) == 0) {
    die("Invalid or expired token.");
}

// Show password reset form
?>

<!DOCTYPE html>
<html>
<head>
    <title>Reset Password</title>
</head>
<body>
    <h2>Reset Password</h2>
    <form action="reset_password.php" method="post">
        <input type="hidden" name="token" value="<?php echo $token; ?>">
        <label for="new_password">New Password:</label><br>
        <input type="password" id="new_password" name="new_password" required><br><br>
        <label for="confirm_password">Confirm Password:</label><br>
        <input type="password" id="confirm_password" name="confirm_password" required><br><br>
        <button type="submit">Reset Password</button>
    </form>
</body>
</html>

<?php
// Handle password reset form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $token = mysqli_real_escape_string($conn, $_POST['token']);
    $new_password = mysqli_real_escape_string($conn, $_POST['new_password']);
    $confirm_password = mysqli_real_escape_string($conn, $_POST['confirm_password']);
    
    if ($new_password != $confirm_password) {
        die("Passwords do not match.");
    }
    
    // Hash the new password
    $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
    
    // Update user's password in the database
    $sql = "SELECT email FROM password_resets WHERE token='$token'";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($result);
    $email = $row['email'];
    
    $update_sql = "UPDATE users SET password='$hashed_password' WHERE email='$email'";
    mysqli_query($conn, $update_sql);
    
    // Delete the token from password_resets table
    $delete_sql = "DELETE FROM password_resets WHERE token='$token'";
    mysqli_query($conn, $delete_sql);
    
    echo "Password reset successful!";
}
?>


<?php
// Database configuration
$host = 'localhost';
$username = 'root';
$password = '';
$db_name = 'mydatabase';

// Create connection
$conn = new mysqli($host, $username, $password, $db_name);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Session start
session_start();

// CSRF token generation
function generateToken() {
    return bin2hex(random_bytes(16));
}

// Generate and store CSRF token in session
if (!isset($_SESSION['token'])) {
    $_SESSION['token'] = generateToken();
}

// Email validation function
function validateEmail($email) {
    if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return true;
    } else {
        return false;
    }
}

// Forgot password function
function forgotPassword($conn, $email) {
    // Check if email exists in database
    $sql = "SELECT id FROM users WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        return false;
    }

    // Generate reset token
    $resetToken = generateToken();
    
    // Store reset token and expiration time in database
    $currentTime = time();
    $expirationTime = $currentTime + 3600; // Token expires after 1 hour
    
    $sql = "UPDATE users SET reset_token = ?, reset_expires = ? WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sis", $resetToken, $expirationTime, $email);
    $stmt->execute();

    // Send password reset link to user's email
    $resetLink = "http://yourdomain.com/reset-password.php?token=" . $resetToken;
    
    $to = $email;
    $subject = "Password Reset Request";
    $message = "Please click the following link to reset your password:

" . $resetLink . "

If you did not request this password reset, please ignore this email.";
    
    // Using PHP's mail function (you may want to use a more reliable method like PHPMailer in production)
    if (mail($to, $subject, $message)) {
        return true;
    } else {
        return false;
    }
}

// Reset password function
function resetPassword($conn, $token, $newPassword) {
    // Check token validity and expiration
    $sql = "SELECT id, email FROM users WHERE reset_token = ? AND reset_expires > ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("si", $token, time());
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        return false;
    }

    // Update password
    $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
    
    $sql = "UPDATE users SET password = ?, reset_token = NULL, reset_expires = NULL WHERE reset_token = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $hashedPassword, $token);
    $stmt->execute();

    return true;
}

// Main forgot password form handling
if (isset($_POST['forgot-password'])) {
    // Validate CSRF token
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['token']) {
        die("Invalid request");
    }

    $email = $_POST['email'];
    
    if (!validateEmail($email)) {
        die("Invalid email format");
    }

    if (forgotPassword($conn, $email)) {
        echo "Password reset instructions have been sent to your email address.";
    } else {
        echo "An error occurred. Please try again later.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Forgot Password</title>
</head>
<body>
    <h2>Forgot Password</h2>
    
    <?php
    // If there's an error, display it here
    if (isset($_GET['error'])) {
        echo "<p style='color: red;'>Error: " . $_GET['error'] . "</p>";
    }
    ?>

    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
        <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['token']; ?>">
        
        <label for="email">Email:</label>
        <input type="text" id="email" name="email">
        
        <button type="submit" name="forgot-password">Reset Password</button>
    </form>

    <p>Don't have an account? <a href="signup.php">Sign Up</a></p>
</body>
</html>


<!DOCTYPE html>
<html>
<head>
    <title>Forgot Password</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #f5f5f5;
        }
        .container {
            max-width: 400px;
            margin: 0 auto;
            background-color: white;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .form-group {
            margin-bottom: 20px;
        }
        input[type="email"] {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-sizing: border-box;
        }
        button {
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            width: 100%;
        }
        button:hover {
            background-color: #45a049;
        }
        .message {
            margin-top: 10px;
            color: red;
        }
    </style>
</head>
<body>
    <div class="container">
        <?php
        // Check if message parameter exists
        $message = isset($_GET['message']) ? $_GET['message'] : '';
        if ($message == 'success') {
            echo "<p style='color: green;'>Check your email for password reset instructions!</p>";
        } elseif ($message == 'invalid') {
            echo "<p style='color: red;'>Invalid email or token!</p>";
        }
        ?>
        
        <h2>Forgot Password</h2>
        <form action="forgot_password.php" method="post">
            <div class="form-group">
                <label for="email">Email:</label><br>
                <input type="email" id="email" name="email" required>
            </div>
            <button type="submit">Reset Password</button>
        </form>
    </div>
</body>
</html>


<?php
// Database connection settings
$host = 'localhost';
$dbname = 'your_database_name';
$username = 'your_username';
$password = 'your_password';

try {
    // Connect to database
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get email from form
    $email = $_POST['email'];
    
    // Prepare statement to check if email exists in database
    $stmt = $conn->prepare("SELECT id, username FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($row) {
        // Generate a random token
        $token = bin2hex(random_bytes(32));
        $token_hash = sha1($token);
        
        // Store token in database
        $stmt = $conn->prepare("INSERT INTO password_reset (user_id, token, created_at) VALUES (?, ?, NOW())");
        $stmt->execute([$row['id'], $token_hash]);
        
        // Send email with reset link
        $to = $email;
        $subject = "Password Reset Request";
        $message = "
            <html>
                <body>
                    <h2>Password Reset</h2>
                    <p>Please click the following link to reset your password:</p>
                    <a href='http://yourwebsite.com/reset_password.php?token=$token&username=" . urlencode($row['username']) . "'>Reset Password</a><br>
                    <p>If you did not request this password reset, please ignore this email.</p>
                </body>
            </html>
        ";
        $headers = "MIME-Version: 1.0" . "\r
";
        $headers .= "Content-type:text/html;charset=UTF-8" . "\r
";
        $headers .= "From: yourwebsite.com <noreply@yourwebsite.com>" . "\r
";
        
        mail($to, $subject, $message, $headers);
        
        // Redirect back to forgot password page with success message
        header("Location: forgot_password.php?message=success");
        exit();
    } else {
        // Email not found in database
        echo "<script>alert('Email address not found!'); window.history.back();</script>";
    }
}
?>


<?php
// Database connection settings
$host = 'localhost';
$dbname = 'your_database_name';
$username = 'your_username';
$password = 'your_password';

try {
    // Connect to database
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

// Check if token and username are provided
if (isset($_GET['token']) && isset($_GET['username'])) {
    $token = $_GET['token'];
    $username = $_GET['username'];
    
    // Get hashed token from database
    $stmt = $conn->prepare("SELECT pr.token FROM password_reset pr INNER JOIN users u ON pr.user_id = u.id WHERE u.username = ? AND pr.token = SHA1(?)");
    $stmt->execute([$username, $token]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($row) {
        // Token is valid
        ?>
        <html>
            <head>
                <title>Reset Password</title>
                <style>
                    body {
                        font-family: Arial, sans-serif;
                        margin: 0;
                        padding: 20px;
                        background-color: #f5f5f5;
                    }
                    .container {
                        max-width: 400px;
                        margin: 0 auto;
                        background-color: white;
                        padding: 20px;
                        border-radius: 5px;
                        box-shadow: 0 0 10px rgba(0,0,0,0.1);
                    }
                    .form-group {
                        margin-bottom: 20px;
                    }
                    input[type="password"] {
                        width: 100%;
                        padding: 10px;
                        border: 1px solid #ddd;
                        border-radius: 4px;
                        box-sizing: border-box;
                    }
                    button {
                        background-color: #4CAF50;
                        color: white;
                        padding: 10px 20px;
                        border: none;
                        border-radius: 4px;
                        cursor: pointer;
                        width: 100%;
                    }
                    button:hover {
                        background-color: #45a049;
                    }
                    .message {
                        margin-top: 10px;
                        color: red;
                    }
                </style>
            </head>
            <body>
                <div class="container">
                    <h2>Reset Password</h2>
                    <?php
                    if (isset($_POST['new_password']) && isset($_POST['confirm_password'])) {
                        $new_password = $_POST['new_password'];
                        $confirm_password = $_POST['confirm_password'];
                        
                        if ($new_password == $confirm_password) {
                            // Hash the password
                            $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
                            
                            // Update the password in database
                            $stmt = $conn->prepare("UPDATE users SET password = ? WHERE username = ?");
                            $stmt->execute([$hashed_password, $username]);
                            
                            // Delete the reset token
                            $stmt = $conn->prepare("DELETE FROM password_reset WHERE user_id = (SELECT id FROM users WHERE username = ?)");
                            $stmt->execute([$username]);
                            
                            // Redirect to login page with success message
                            header("Location: login.php?message=success");
                            exit();
                        } else {
                            echo "<div class='message'>Passwords do not match!</div>";
                        }
                    }
                    ?>
                    <form action="<?php $_SERVER['PHP_SELF'] ?>" method="post">
                        <input type="hidden" name="token" value="<?php echo $token; ?>">
                        <div class="form-group">
                            <label for="new_password">New Password:</label><br>
                            <input type="password" id="new_password" name="new_password" required>
                        </div>
                        <div class="form-group">
                            <label for="confirm_password">Confirm Password:</label><br>
                            <input type="password" id="confirm_password" name="confirm_password" required>
                        </div>
                        <button type="submit">Set New Password</button>
                    </form>
                </div>
            </body>
        </html>
        <?php
    } else {
        // Invalid token or username
        header("Location: forgot_password.php?message=invalid");
        exit();
    }
} else {
    // Missing parameters
    header("Location: forgot_password.php?message=invalid");
    exit();
}
?>


<?php
session_start();
include('db_connection.php'); // Include your database connection

if (isset($_POST['email'])) {
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    
    // Check if email exists in the database
    $sql = "SELECT id FROM users WHERE email = '$email'";
    $result = mysqli_query($conn, $sql);
    
    if (mysqli_num_rows($result) == 0) {
        echo "<script>alert('No account found with this email!');</script>";
    } else {
        // Generate a random token
        $token = bin2hex(random_bytes(32)); // 64 characters long
        
        // Store the token and expiration time in the database
        $expires = date("Y-m-d H:i:s", time() + 3600); // Token expires after 1 hour
        
        $sql = "UPDATE users SET reset_token = '$token', reset_expires = '$expires' WHERE email = '$email'";
        mysqli_query($conn, $sql);
        
        // Send the password reset link to the user's email
        $reset_link = "http://your-site.com/reset_password.php?token=$token";
        sendPasswordResetEmail($email, $reset_link, $expires);
        
        echo "<script>alert('Password reset instructions have been sent to your email!');</script>";
    }
}
?>

<?php
function sendPasswordResetEmail($email, $reset_link, $expires) {
    // Set up the email content
    $to = $email;
    $subject = "Password Reset Request";
    
    $message = "
        <html>
            <body>
                <p>We received a password reset request for your account.</p>
                <p>Click on the following link to reset your password:</p>
                <a href='$reset_link'>Reset Password</a><br><br>
                <p>This link will expire at: $expires</p>
            </body>
        </html>
    ";
    
    // Set headers
    $headers = "MIME-Version: 1.0" . "\r
";
    $headers .= "Content-type: text/html; charset=UTF-8" . "\r
";
    $headers .= "From: Your Website <your.email@example.com>" . "\r
";
    
    // Send the email
    mail($to, $subject, $message, $headers);
}
?>


<?php
session_start();
include('db_connection.php'); // Include your database connection

// Check if token is provided and valid
if (isset($_GET['token'])) {
    $token = mysqli_real_escape_string($conn, $_GET['token']);
    
    // Get user data from the database
    $sql = "SELECT id, reset_token, reset_expires FROM users WHERE reset_token = '$token'";
    $result = mysqli_query($conn, $sql);
    
    if (mysqli_num_rows($result) == 0) {
        die("<script>alert('Invalid token!');</script>");
    }
    
    $row = mysqli_fetch_assoc($result);
    // Check if the token has expired
    if ($row['reset_expires'] < date("Y-m-d H:i:s")) {
        die("<script>alert('Token has expired! Please request a new password reset.');</script>");
    }
} else {
    die("<script>alert('No token provided!');</script>");
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Reset Password</title>
</head>
<body>
    <h2>Reset Password</h2>
    
    <?php
    if (isset($_POST['submit'])) {
        // Validate the new password
        $password = $_POST['password'];
        $confirm_password = $_POST['confirm_password'];
        
        if ($password != $confirm_password) {
            die("<script>alert('Passwords do not match!');</script>");
        }
        
        if (strlen($password) < 8) {
            die("<script>alert('Password must be at least 8 characters long!');</script>");
        }
        
        // Check for at least one letter and number
        if (!preg_match("#^[a-zA-Z0-9]+$#", $password)) {
            die("<script>alert('Password must contain only letters and numbers!');</script>");
        }
        
        // Hash the new password
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        
        // Update the database with the new password
        $sql = "UPDATE users SET password = '$hashed_password', reset_token = NULL, reset_expires = NULL WHERE id = {$row['id']}";
        mysqli_query($conn, $sql);
        
        echo "<script>alert('Password has been successfully updated!');</script>";
    }
    ?>
    
    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
        <input type="password" name="password" placeholder="Enter new password" required><br>
        <input type="password" name="confirm_password" placeholder="Confirm new password" required><br><br>
        <button type="submit" name="submit">Reset Password</button>
    </form>
</body>
</html>


<?php
// Database configuration
$host = 'localhost';
$dbname = 'your_database_name';
$username = 'your_username';
$password = 'your_password';

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Could not connect to the database: " . $e->getMessage());
}

// Function to generate a secure token
function generateToken() {
    return bin2hex(random_bytes(16));
}

// Check if email exists in database
function checkEmailExists($email, $conn) {
    try {
        $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->execute([$email]);
        return $stmt->rowCount() > 0;
    } catch (PDOException $e) {
        die("Error checking email: " . $e->getMessage());
    }
}

// Function to generate and store reset token
function generateResetToken($email, $conn) {
    try {
        // Generate a secure token
        $token = generateToken();
        
        // Insert into database
        $stmt = $conn->prepare("INSERT INTO password_resets (user_email, token, created_at) VALUES (?, ?, NOW())");
        $stmt->execute([$email, $token]);
        
        return $token;
    } catch (PDOException $e) {
        die("Error generating reset token: " . $e->getMessage());
    }
}

// Function to send reset password email
function sendResetEmail($email, $resetLink) {
    $to = $email;
    $subject = 'Password Reset Request';
    $message = "<html>
                <body>
                    <p>We received a request to reset your password. Click the link below to reset it:</p>
                    <a href='" . $resetLink . "'>" . $resetLink . "</a><br>
                    <p>If you did not make this request, you can safely ignore this email.</p>
                </body>
               </html>";
    $headers = 'MIME-Version: 1.0' . "\r
";
    $headers .= 'Content-type: text/html; charset=UTF-8' . "\r
";
    
    mail($to, $subject, $message, $headers);
}

// Function to handle password reset request
function handleForgotPassword($email) {
    if (checkEmailExists($email, $conn)) {
        $token = generateResetToken($email, $conn);
        $resetLink = 'http://example.com/reset-password.php?token=' . $token;
        sendResetEmail($email, $resetLink);
        return true;
    } else {
        return false;
    }
}

// Function to validate token and display reset form
function handleTokenValidation($token) {
    try {
        // Check if token exists in database and hasn't expired (assuming 1 hour validity)
        $stmt = $conn->prepare("SELECT user_email FROM password_resets WHERE token = ? AND created_at >= NOW() - INTERVAL 1 HOUR");
        $stmt->execute([$token]);
        
        if ($stmt->rowCount() == 1) {
            // Token is valid
            return true;
        } else {
            // Invalid or expired token
            return false;
        }
    } catch (PDOException $e) {
        die("Error validating token: " . $e->getMessage());
    }
}

// Function to update password after reset
function updatePassword($email, $newPassword) {
    try {
        $stmt = $conn->prepare("UPDATE users SET password = ? WHERE email = ?");
        $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
        $stmt->execute([$hashedPassword, $email]);
        
        // Delete the reset token
        $deleteStmt = $conn->prepare("DELETE FROM password_resets WHERE user_email = ?");
        $deleteStmt->execute([$email]);
        
        return true;
    } catch (PDOException $e) {
        die("Error updating password: " . $e->getMessage());
    }
}

// Example usage for forgot password page
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    
    if (empty($email)) {
        echo "Email is required!";
    } else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "Please enter a valid email!";
    } else {
        if (handleForgotPassword($email)) {
            echo "A password reset link has been sent to your email address!";
        } else {
            echo "Email not found in our database!";
        }
    }
}

// Example usage for reset password page
if (isset($_GET['token'])) {
    $token = $_GET['token'];
    
    if (!handleTokenValidation($token)) {
        die("Invalid or expired token!");
    }
    
    // Display the password reset form
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $newPassword = $_POST['new_password'];
        
        if (empty($newPassword)) {
            echo "Please enter a new password!";
        } else {
            try {
                $stmt = $conn->prepare("SELECT user_email FROM password_resets WHERE token = ?");
                $stmt->execute([$token]);
                $email = $stmt->fetch(PDO::FETCH_ASSOC)['user_email'];
                
                if (updatePassword($email, $newPassword)) {
                    echo "Your password has been successfully reset!";
                    // Optionally, log the user out
                    session_start();
                    $_SESSION = array();
                    session_destroy();
                } else {
                    echo "Error resetting your password!";
                }
            } catch (PDOException $e) {
                die("Error fetching email: " . $e->getMessage());
            }
        }
    }
}

// Close database connection
$conn = null;
?>


<?php
// forgot_password.php

$servername = "localhost";
$username = "username";
$password = "password";
$dbname = "myDB";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$email = $_POST['email'];

// Check if email exists in the database
$sql = "SELECT id FROM users WHERE email = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    header("Location: forgot_password_form.html?error=Email%20not%20found");
    exit();
}

// Generate a random password and reset token
$randomPassword = generateRandomString(8);
$resetToken = generateRandomString(32);

// Update the database with the new password and token
$sql = "UPDATE users SET password = ?, reset_token = ? WHERE email = ?";
$stmt = $conn->prepare($sql);
$passwordHash = password_hash($randomPassword, PASSWORD_DEFAULT);
$stmt->bind_param("sss", $passwordHash, $resetToken, $email);
$stmt->execute();

// Send the reset email
$to = $email;
$subject = "Reset Your Password";
$message = "
    <html>
        <body>
            <h2>Reset Your Password</h2>
            <p>Please click on the following link to set your new password:</p>
            <a href='http://yourdomain.com/reset_password.php?token=$resetToken'>Reset Password</a><br>
            <p>If you did not request this, please ignore this email.</p>
        </body>
    </html
";
$headers = "MIME-Version: 1.0" . "\r
";
$headers .= "Content-type:text/html;charset=UTF-8" . "\r
";
mail($to, $subject, $message, $headers);

header("Location: forgot_password_form.html?success=Please%20check%20your%20email%20for%20reset%20instructions.");
exit();

function generateRandomString($length = 8) {
    $characters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, strlen($characters)-1)];
    }
    return $randomString;
}
?>


<?php
// reset_password.php

$servername = "localhost";
$username = "username";
$password = "password";
$dbname = "myDB";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (!isset($_GET['token']) || !isset($_POST['password']) || !isset($_POST['confirmPassword'])) {
    header("Location: reset_password_form.html?error=Invalid%20request");
    exit();
}

$resetToken = $_GET['token'];
$password = $_POST['password'];
$confirmPassword = $_POST['confirmPassword'];

if ($password != $confirmPassword) {
    header("Location: reset_password_form.html?error=Passwords%20do%20not%20match");
    exit();
}

// Check if the token is valid
$sql = "SELECT id, user_id FROM reset_tokens WHERE token = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $resetToken);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    header("Location: reset_password_form.html?error=Invalid%20or%20expired%20token");
    exit();
}

$row = $result->fetch_assoc();
$user_id = $row['user_id'];

// Update the user's password
$sql = "UPDATE users SET password = ? WHERE id = ?";
$stmt = $conn->prepare($sql);
$passwordHash = password_hash($password, PASSWORD_DEFAULT);
$stmt->bind_param("si", $passwordHash, $user_id);
$stmt->execute();

// Invalidate the reset token
$sql = "DELETE FROM reset_tokens WHERE token = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $resetToken);
$stmt->execute();

header("Location: login.php?success=Password%20has%20been%20reset");
exit();
?>


<?php
// Database connection
$host = 'localhost';
$username = 'root';
$password = '';
$dbname = 'mydatabase';

$conn = new mysqli($host, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Function to send password reset email
function sendPasswordResetEmail($email, $token) {
    $to = $email;
    $subject = 'Password Reset Request';
    $message = '
        <html>
            <head>
                <title>Password Reset</title>
            </head>
            <body>
                <h2>Password Reset Request</h2>
                <p>Please click the link below to reset your password:</p>
                <a href="http://example.com/reset-password.php?token=' . $token . '">Reset Password</a>
                <br><br>
                <p>If you did not request a password reset, please ignore this email.</p>
            </body>
        </html>';
    $headers = 'MIME-Version: 1.0' . "\r
";
    $headers .= 'Content-type: text/html; charset=UTF-8' . "\r
";

    mail($to, $subject, $message, $headers);
}

// Function to generate random token
function generateToken() {
    return bin2hex(random_bytes(32));
}

// Check if email exists in database
function isEmailExists($email) {
    global $conn;
    $sql = "SELECT id FROM users WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    return $result->num_rows > 0;
}

// Reset password form
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    
    if (isEmailExists($email)) {
        // Generate token and store in database
        $token = generateToken();
        
        $sql = "INSERT INTO password_resets (email, token) VALUES (?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $email, $token);
        $stmt->execute();
        
        sendPasswordResetEmail($email, $token);
        echo 'A password reset link has been sent to your email address.';
    } else {
        echo 'Email not found in our records.';
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Forgot Password</title>
</head>
<body>
    <h2>Forgot Password</h2>
    <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
        <label for="email">Email:</label><br>
        <input type="text" id="email" name="email"><br><br>
        <input type="submit" value="Send Reset Link">
    </form>
</body>
</html>

// Reset password page (reset-password.php)
<?php
if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['token'])) {
    $token = $_GET['token'];
    
    // Check if token exists in database
    global $conn;
    $sql = "SELECT email FROM password_resets WHERE token = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Show reset form
        ?>
        <html>
            <head>
                <title>Reset Password</title>
            </head>
            <body>
                <h2>Reset Password</h2>
                <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                    <label for="password">New Password:</label><br>
                    <input type="password" id="password" name="password"><br><br>
                    <input type="hidden" name="token" value="<?php echo $token; ?>">
                    <input type="submit" value="Reset Password">
                </form>
            </body>
        </html>
        <?php
    } else {
        echo 'Invalid or expired token.';
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['token'])) {
    $token = $_POST['token'];
    $password = $_POST['password'];

    // Update password in database
    global $conn;
    $sql = "SELECT email FROM password_resets WHERE token = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $email = $result->fetch_assoc()['email'];
        
        // Update user password
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $sql = "UPDATE users SET password = ? WHERE email = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $hashed_password, $email);
        $stmt->execute();

        // Delete token from database
        $sql = "DELETE FROM password_resets WHERE token = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $token);
        $stmt->execute();

        echo 'Password reset successful. You can now login with your new password.';
    } else {
        echo 'Invalid or expired token.';
    }
}
?>


<?php
// Database configuration
$host = 'localhost';
$username = 'root';
$password = '';
$database = 'my_database';

// Create connection
$conn = new mysqli($host, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Function to send password reset email
function sendPasswordResetEmail($email, $resetLink) {
    $to = $email;
    $subject = 'Password Reset Request';
    $message = "
        <html>
        <body>
            <h2>Password Reset</h2>
            <p>We received a request to reset your password. Please click the following link to reset your password:</p>
            <a href='" . $resetLink . "'>" . $resetLink . "</a><br><br>
            <small>If you did not request this password reset, please ignore this email.</small>
        </body>
        </html>
    ";
    
    // Set headers
    $headers = 'MIME-Version: 1.0' . "\r
";
    $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r
";
    $headers .= 'From: <your_email@example.com>' . "\r
";
    
    // Send email
    mail($to, $subject, $message, $headers);
}

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    
    // Validate email
    if (empty($email)) {
        echo "Email is required!";
        exit();
    }
    
    // Check if email exists in database
    $sql = "SELECT id FROM users WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows == 0) {
        echo "Email not found!";
        exit();
    }
    
    // Generate reset token
    $resetToken = bin2hex(random_bytes(16));
    $resetUrl = 'http://example.com/reset-password.php?token=' . $resetToken;
    $expires = date('Y-m-d H:i:s', time() + 3600); // Token expires in 1 hour
    
    // Insert reset token into database
    $sql = "INSERT INTO password_resets (user_id, token, created_at, expires_at)
            VALUES (?, ?, NOW(), ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iss", $result->fetch_assoc()['id'], $resetToken, $expires);
    
    if ($stmt->execute()) {
        // Send reset email
        sendPasswordResetEmail($email, $resetUrl);
        echo "Password reset link has been sent to your email!";
    } else {
        echo "Error sending password reset request!";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Forgot Password</title>
</head>
<body>
    <h2>Forgot Password</h2>
    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
        <label for="email">Email:</label><br>
        <input type="email" id="email" name="email" required><br><br>
        <button type="submit">Send Reset Link</button>
    </form>
</body>
</html>


<?php
// Database configuration (same as above)
$host = 'localhost';
$username = 'root';
$password = '';
$database = 'my_database';

$conn = new mysqli($host, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get token from URL
if (!isset($_GET['token'])) {
    header("Location: forgot-password.php");
    exit();
}

$resetToken = $_GET['token'];

// Check if token is valid and hasn't expired
$sql = "SELECT user_id FROM password_resets 
        WHERE token = ? AND created_at > DATE_SUB(NOW(), INTERVAL 1 HOUR) AND used = 0";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $resetToken);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    die("Invalid or expired reset token!");
}

$user_id = $result->fetch_assoc()['user_id'];

// If form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $newPassword = $_POST['password'];
    $confirmPassword = $_POST['confirm_password'];
    
    if ($newPassword !== $confirmPassword) {
        die("Passwords do not match!");
    }
    
    // Update user's password
    $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
    $sql = "UPDATE users SET password = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("si", $hashedPassword, $user_id);
    
    if ($stmt->execute()) {
        // Mark token as used
        $sql = "UPDATE password_resets SET used = 1 WHERE token = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $resetToken);
        $stmt->execute();
        
        echo "Password reset successful!";
    } else {
        echo "Error resetting password!";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Reset Password</title>
</head>
<body>
    <h2>Reset Password</h2>
    <form action="<?php echo $_SERVER['PHP_SELF']; ?>?token=<?php echo $resetToken; ?>" method="post">
        <label for="password">New Password:</label><br>
        <input type="password" id="password" name="password" required><br><br>
        <label for="confirm_password">Confirm New Password:</label><br>
        <input type="password" id="confirm_password" name="confirm_password" required><br><br>
        <button type="submit">Reset Password</button>
    </form>
</body>
</html>


<?php
// Include database connection
include('db_connection.php');

if (isset($_POST['submit'])) {
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    
    // Check if email exists in the database
    $query = "SELECT * FROM users WHERE email = '$email'";
    $result = mysqli_query($conn, $query);
    $row = mysqli_fetch_assoc($result);
    
    if (mysqli_num_rows($result) == 0) {
        echo "Email does not exist!";
    } else {
        // Generate reset token
        $resetToken = md5(uniqid());
        
        // Update database with the reset token
        $updateQuery = "UPDATE users SET password_reset_token = '$resetToken' WHERE email = '$email'";
        mysqli_query($conn, $updateQuery);
        
        // Send reset email
        $to = $email;
        $subject = "Password Reset Request";
        $message = "Please click the following link to reset your password: http://yourwebsite.com/reset_password.php?token=$resetToken";
        $headers = "From: yourwebsite.com\r
";
        
        mail($to, $subject, $message, $headers);
        
        echo "Password reset email has been sent!";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Forgot Password</title>
</head>
<body>
    <h2>Forgot Password</h2>
    <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
        <input type="email" name="email" placeholder="Enter your email" required><br><br>
        <input type="submit" name="submit" value="Reset Password">
    </form>
</body>
</html>


<?php
// Include database connection
include('db_connection.php');

if (isset($_GET['token'])) {
    $token = mysqli_real_escape_string($conn, $_GET['token']);
    
    // Check if token exists in the database
    $query = "SELECT * FROM users WHERE password_reset_token = '$token'";
    $result = mysqli_query($conn, $query);
    $row = mysqli_fetch_assoc($result);
    
    if (mysqli_num_rows($result) == 0) {
        echo "Invalid reset link!";
    } else {
        // Show password reset form
?>
<!DOCTYPE html>
<html>
<head>
    <title>Reset Password</title>
</head>
<body>
    <h2>Reset Password</h2>
    <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
        <input type="password" name="new_password" placeholder="Enter new password" required><br><br>
        <input type="password" name="confirm_password" placeholder="Confirm password" required><br><br>
        <input type="hidden" name="token" value="<?php echo $token; ?>">
        <input type="submit" name="change_password" value="Change Password">
    </form>
</body>
</html>

<?php
    }
} else {
    echo "No token provided!";
}
?>

<?php
if (isset($_POST['change_password'])) {
    $new_password = mysqli_real_escape_string($conn, $_POST['new_password']);
    $confirm_password = mysqli_real_escape_string($conn, $_POST['confirm_password']);
    $token = mysqli_real_escape_string($conn, $_POST['token']);
    
    if ($new_password != $confirm_password) {
        echo "Passwords do not match!";
    } else {
        // Update password in the database
        $hash_password = md5($new_password);
        
        $updateQuery = "UPDATE users SET 
                        password = '$hash_password',
                        password_reset_token = '' 
                      WHERE password_reset_token = '$token'";
        mysqli_query($conn, $updateQuery);
        
        echo "Password has been successfully updated!";
    }
}
?>


<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "your_database";

// Create connection
$conn = mysqli_connect($servername, $username, $password, $dbname);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
?>


<?php
// Database configuration
$host = 'localhost';
$dbname = 'mydatabase';
$username = 'root';
$password = '';

try {
    // Create database connection
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Function to send password reset email
    function sendResetEmail($email, $userId) {
        global $conn;
        
        // Generate a random token
        $token = bin2hex(random_bytes(16));
        
        // Store the token in the database
        $stmt = $conn->prepare("INSERT INTO password_resets (user_id, token) VALUES (:userId, :token)");
        $stmt->execute([
            'userId' => $userId,
            'token' => $token
        ]);
        
        // Send email to user
        $subject = "Password Reset Request";
        $message = "Please click the following link to reset your password:

http://example.com/reset_password.php?token=$token";
        mail($email, $subject, $message);
    }

    // Function to handle password reset
    function resetPassword($password, $token) {
        global $conn;
        
        // Check if token exists in the database
        $stmt = $conn->prepare("SELECT * FROM password_resets WHERE token = :token");
        $stmt->execute(['token' => $token]);
        
        if ($stmt->rowCount() > 0) {
            $reset = $stmt->fetch(PDO::FETCH_ASSOC);
            
            // Update the user's password
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $updateStmt = $conn->prepare("UPDATE users SET password = :hash WHERE id = :userId");
            $updateStmt->execute([
                'hash' => $hash,
                'userId' => $reset['user_id']
            ]);
            
            // Delete the reset token
            $deleteStmt = $conn->prepare("DELETE FROM password_resets WHERE token = :token");
            $deleteStmt->execute(['token' => $token]);
            
            return true;
        }
        
        return false;
    }

} catch(PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Forgot Password</title>
</head>
<body>
    <?php
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        try {
            // Verify email exists in database
            $email = $_POST['email'];
            $stmt = $conn->prepare("SELECT id FROM users WHERE email = :email");
            $stmt->execute(['email' => $email]);
            
            if ($stmt->rowCount() > 0) {
                $user = $stmt->fetch(PDO::FETCH_ASSOC);
                sendResetEmail($email, $user['id']);
                echo "A password reset link has been sent to your email address.";
            } else {
                echo "No account found with this email address.";
            }
        } catch(PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    }
    ?>

    <h2>Forgot Password</h2>
    <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
        <label>Email:</label><br>
        <input type="email" name="email" required><br>
        <button type="submit">Send Reset Link</button>
    </form>
</body>
</html>


<?php
session_start();
include('db_connection.php'); // Include your database connection file

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];

    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['message'] = "Please enter a valid email address!";
        header("Location: forgot_password.php");
        exit();
    }

    // Check if the email exists in the database
    $sql = "SELECT id FROM users WHERE email='$email'";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) == 0) {
        $_SESSION['message'] = "Email not found!";
        header("Location: forgot_password.php");
        exit();
    }

    // Generate a random token for password reset
    $token = bin2hex(random_bytes(16));
    
    // Store the token in the database with an expiration time
    $expiration_time = date('Y-m-d H:i:s', time() + 3600); // Token expires after 1 hour
    
    $sql = "INSERT INTO password_reset_tokens (user_id, token, expires) 
            VALUES (?, ?, ?)";
            
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "iss", $id, $token, $expiration_time);
    
    if (mysqli_stmt_execute($stmt)) {
        // Send email with password reset link
        $reset_link = "http://yourwebsite.com/reset_password.php?email=$email&token=$token";
        
        $to = $email;
        $subject = "Password Reset Request";
        $message = "Please click the following link to reset your password: 
" . $reset_link . "

This link will expire in 1 hour.";
        
        // Set up mail headers
        $headers = "From: yourwebsite@example.com\r
";
        $headers .= "Reply-To: yourwebsite@example.com\r
";
        $headers .= "X-Mailer: PHP/" . phpversion();
        
        // Send the email
        if (mail($to, $subject, $message, $headers)) {
            $_SESSION['message'] = "Password reset instructions have been sent to your email address!";
            header("Location: forgot_password.php");
            exit();
        } else {
            $_SESSION['message'] = "Error sending password reset email!";
            header("Location: forgot_password.php");
            exit();
        }
    } else {
        $_SESSION['message'] = "Error resetting password! Please try again.";
        header("Location: forgot_password.php");
        exit();
    }
}

// Close database connection
mysqli_close($conn);
?>


<?php
require_once 'db_connect.php';

if (isset($_POST['email'])) {
    $email = $_POST['email'];
    
    // Query to check if email exists in database
    $stmt = $conn->prepare("SELECT id, username FROM users WHERE email = ?");
    $stmt->bind_param('s', $email);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        // Generate a unique token for password reset
        $token = bin2hex(random_bytes(32));
        $expires = date("Y-m-d H:i:s", time() + 3600); // Token expires after 1 hour
        
        // Store the token in the database
        $stmt_token = $conn->prepare("INSERT INTO password_reset_tokens (user_id, token, expires) VALUES (?, ?, ?)");
        $row = $result->fetch_assoc();
        $stmt_token->bind_param('iss', $row['id'], $token, $expires);
        
        if ($stmt_token->execute()) {
            // Send email with reset link
            $to = $email;
            $subject = "Password Reset Request";
            $message = "Dear " . $row['username'] . ",

Please click the following link to reset your password:

http://localhost/reset_password.php?token=" . $token . "

If you didn't request this password reset, please ignore this email.

Best regards,
Your Website";
            $headers = "From: noreply@yourwebsite.com" . "\r
";
            
            if (mail($to, $subject, $message, $headers)) {
                echo "<script>alert('Reset link has been sent to your email.'); window.location.href='index.php';</script>";
            } else {
                echo "Error sending email.";
            }
        } else {
            echo "Error storing reset token. Please try again later.";
        }
    } else {
        // Email not found in database
        echo "<script>alert('Email address not found.'); window.location.href='index.php';</script>";
    }
    
    $stmt->close();
    $conn->close();
}


<?php
require_once 'db_connect.php';

if (isset($_GET['token'])) {
    $token = $_GET['token'];
} else {
    die("No token provided.");
}

// Check if token exists and is valid
$stmt = $conn->prepare("SELECT * FROM password_reset_tokens WHERE token = ? AND expires > NOW()");
$stmt->bind_param('s', $token);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 1) {
    // Token is valid, show reset form
    if (isset($_POST['password'])) {
        $new_password = $_POST['password'];
        
        // Update the user's password
        $row = $result->fetch_assoc();
        $stmt_update = $conn->prepare("UPDATE users SET password = ? WHERE id = ?");
        $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
        $stmt_update->bind_param('si', $hashed_password, $row['user_id']);
        
        if ($stmt_update->execute()) {
            // Delete the token after use
            $stmt_delete = $conn->prepare("DELETE FROM password_reset_tokens WHERE token = ?");
            $stmt_delete->bind_param('s', $token);
            $stmt_delete->execute();
            
            echo "<script>alert('Password has been reset successfully!'); window.location.href='login.php';</script>";
        } else {
            echo "Error resetting password. Please try again.";
        }
    ?>
    <form action="<?php echo $_SERVER['PHP_SELF'] ?>?token=<?php echo $token ?>" method="post">
        <label for="password">New Password:</label><br>
        <input type="password" id="password" name="password" required><br><br>
        <button type="submit">Reset Password</button>
    </form>
    <?php
    } else {
        // Show the reset form
        ?>
        <h2>Reset Password</h2>
        <p>Please enter your new password:</p>
        <form action="<?php echo $_SERVER['PHP_SELF'] ?>?token=<?php echo $token ?>" method="post">
            <label for="password">New Password:</label><br>
            <input type="password" id="password" name="password" required><br><br>
            <button type="submit">Reset Password</button>
        </form>
        <?php
    }
} else {
    // Token is invalid or expired
    die("Invalid or expired token.");
}

$stmt->close();
$conn->close();
?>


<?php
$host = 'localhost';
$username = 'root'; // Change to your database username
$password = '';     // Change to your database password
$db_name = 'your_database'; // Change to your database name

$conn = new mysqli($host, $username, $password, $db_name);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>


<?php
include('db_connection.php'); // Include your database connection file

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    
    if (empty($email)) {
        header("Location: forgot.php?error=empty");
        exit();
    }
    
    $sql = "SELECT id FROM users WHERE email='$email'";
    $result = mysqli_query($conn, $sql);
    
    if (!$result || mysqli_num_rows($result) == 0) {
        header("Location: forgot.php?error=invalid");
        exit();
    } else {
        $user_id = mysqli_fetch_assoc($result)['id'];
        
        // Generate a random string
        $token = md5(rand());
        $sql_insert = "INSERT INTO password_reset (user_id, token) VALUES ('$user_id', '$token')";
        if (!mysqli_query($conn, $sql_insert)) {
            die('Error inserting token: ' . mysqli_error($conn));
        }
        
        // Send email
        $to = $email;
        $subject = "Password Reset Request";
        $message = "
            <html>
            <head>
                <title>Password Reset</title>
            </head>
            <body>
                <p>Please click the following link to reset your password:</p>
                <a href='http://yourdomain.com/reset_password.php?token=$token&id=$user_id'>
                    http://yourdomain.com/reset_password.php?token=$token&id=$user_id
                </a><br><br>
                <p>If you did not request this password reset, please disregard this email.</p>
            </body>
            </html>
        ";
        $headers = "From: your.email@yourdomain.com\r
";
        $headers .= "Content-type: text/html\r
";
        
        if (mail($to, $subject, $message, $headers)) {
            header("Location: forgot.php?error=sent");
            exit();
        } else {
            echo "Error sending email. Please try again.";
        }
    }
}
?>


<?php
include('db_connection.php'); // Include your database connection file

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $token = mysqli_real_escape_string($conn, $_POST['token']);
    $user_id = mysqli_real_escape_string($conn, $_POST['user_id']);
    $new_password = mysqli_real_escape_string($conn, $_POST['newpassword']);
    
    // Check if token exists and is valid
    $sql_check_token = "SELECT id FROM password_reset WHERE token='$token' AND user_id='$user_id'";
    $result = mysqli_query($conn, $sql_check_token);
    
    if (!$result || mysqli_num_rows($result) == 0) {
        die("Invalid or expired reset link.");
    }
    
    // Update the password in the users table
    $hash_password = md5($new_password); // You should use a more secure hashing method like password_hash()
    $sql_update = "UPDATE users SET password='$hash_password' WHERE id='$user_id'";
    if (!mysqli_query($conn, $sql_update)) {
        die('Error updating password: ' . mysqli_error($conn));
    }
    
    // Delete the reset token after use
    $sql_delete_token = "DELETE FROM password_reset WHERE token='$token'";
    if (mysqli_query($conn, $sql_delete_token)) {
        header("Location: login.php"); // Redirect to login page
        exit();
    }
}
?>


<?php
// db.php
$host = 'localhost';
$username = 'root';
$password = '';
$dbname = 'test';

$conn = new mysqli($host, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>

<?php
// forgot_password.php
include('db.php');

session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    
    // Validate email
    if (empty($email)) {
        die("Email is required");
    }
    
    // Check if email exists in the database
    $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->bind_param('s', $email);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows == 0) {
        die("Email does not exist");
    }
    
    // Generate reset token
    $token = bin2hex(random_bytes(32));
    $exp_time = time() + (1 * 60 * 60); // Expires in 1 hour
    
    // Update the database with the new token and expiry time
    $updateStmt = $conn->prepare("UPDATE users SET reset_token = ?, reset_expiry = ? WHERE email = ?");
    $updateStmt->bind_param('sis', $token, $exp_time, $email);
    $updateStmt->execute();
    
    // Send email with reset link
    $to = $email;
    $subject = "Password Reset Request";
    $message = "Please click the following link to reset your password:

http://example.com/reset_password.php?token=$token

This link expires in 1 hour.";
    $headers = "From: webmaster@example.com\r
";
    
    if (mail($to, $subject, $message, $headers)) {
        echo "An email has been sent with instructions to reset your password.";
    } else {
        die("Failed to send email");
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Forgot Password</title>
</head>
<body>
    <h2>Forgot Password</h2>
    <form action="forgot_password.php" method="post">
        <input type="email" name="email" placeholder="Enter your email" required><br><br>
        <input type="submit" value="Reset Password">
    </form>
</body>
</html>

<?php
// reset_password.php
include('db.php');

session_start();

if (isset($_GET['token'])) {
    $token = $_GET['token'];
    
    // Check if token exists and is not expired
    $stmt = $conn->prepare("SELECT id, email FROM users WHERE reset_token = ? AND reset_expiry > ?");
    $stmt->bind_param('si', $token, time());
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows == 0) {
        die("Invalid or expired token");
    }
    
    // Display password change form
    ?>
    <html>
    <head>
        <title>Reset Password</title>
    </head>
    <body>
        <h2>Reset Password</h2>
        <form action="change_password.php" method="post">
            <input type="password" name="new_password" placeholder="Enter new password" required><br><br>
            <input type="hidden" name="token" value="<?php echo $token; ?>">
            <input type="submit" value="Change Password">
        </form>
    </body>
    </html>
    <?php
} else {
    die("No token provided");
}
?>

<?php
// change_password.php
include('db.php');

session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $token = $_POST['token'];
    $new_password = $_POST['new_password'];
    
    // Check if token is valid and not expired
    $stmt = $conn->prepare("SELECT id, email FROM users WHERE reset_token = ? AND reset_expiry > ?");
    $stmt->bind_param('si', $token, time());
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows == 0) {
        die("Invalid or expired token");
    }
    
    // Hash the new password
    $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
    
    // Update the user's password
    $updateStmt = $conn->prepare("UPDATE users SET password = ?, reset_token = NULL, reset_expiry = NULL WHERE reset_token = ?");
    $updateStmt->bind_param('ss', $hashed_password, $token);
    $updateStmt->execute();
    
    echo "Password has been updated successfully!";
}
?>


<?php
include('db_connection.php'); // Include your database connection file

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = mysqli_real_escape_string($conn, $_POST['email']);

    // Check if email exists in the database
    $sql = "SELECT id FROM users WHERE email = '$email'";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) == 1) {
        // Generate a random token for password reset
        $token = bin2hex(random_bytes(32));

        // Set expiration time for the token (e.g., 30 minutes)
        $expiration_time = date('Y-m-d H:i:s', strtotime('+30 minutes'));

        // Update the database with the new token and expiration time
        $sql = "UPDATE users SET reset_token = '$token', reset_expires = '$expiration_time' WHERE email = '$email'";
        mysqli_query($conn, $sql);

        // Send the password reset email
        send_reset_email($email, $token);
        
        // Redirect to a confirmation page
        header("Location: forgot_password_sent.php");
        exit();
    } else {
        // Email not found in database
        header("Location: forgot_password.php?error=Email%20not%20found.");
        exit();
    }
}

function send_reset_email($email, $token) {
    $to = $email;
    $subject = "Password Reset Request";
    $message = "
        <html>
            <body>
                <p>We received a request to reset your password. Click the link below to reset it:</p>
                <a href='http://example.com/reset_password.php?token=$token'>Reset Password</a><br><br>
                <small>If you did not make this request, please ignore this email.</small>
            </body>
        </html>
    ";
    $headers = "From: noreply@example.com\r
";
    $headers .= "MIME-Version: 1.0\r
";
    $headers .= "Content-Type: text/html; charset=UTF-8\r
";

    mail($to, $subject, $message, $headers);
}
?>


<?php
include('db_connection.php'); // Include your database connection file

if (!isset($_GET['token'])) {
    die("Invalid request.");
}

$token = $_GET['token'];

// Check if the token exists in the database and is not expired
$sql = "SELECT id, email FROM users WHERE reset_token = '$token' AND reset_expires > NOW()";
$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) == 1) {
    // Token is valid
} else {
    die("Invalid or expired token.");
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Reset Password</title>
</head>
<body>
    <?php if (isset($_GET['error'])) { ?>
        <p style="color: red;"><?php echo $_GET['error']; ?></p>
    <?php } ?>

    <h2>Reset Password</h2>
    <form action="reset_password_process.php" method="post">
        <input type="hidden" name="token" value="<?php echo $token; ?>">
        <label for="password">New Password:</label><br>
        <input type="password" id="password" name="password" required><br><br>
        <button type="submit">Reset Password</button>
    </form>
</body>
</html>


<?php
include('db_connection.php'); // Include your database connection file

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $token = $_POST['token'];
    $password = $_POST['password'];

    // Check if token is valid and not expired
    $sql = "SELECT id, email FROM users WHERE reset_token = '$token' AND reset_expires > NOW()";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) == 1) {
        // Update the password
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $sql = "UPDATE users SET password = '$hashed_password', reset_token = '', reset_expires = '' WHERE reset_token = '$token'";
        mysqli_query($conn, $sql);

        // Redirect to success page
        header("Location: reset_password_success.php");
        exit();
    } else {
        // Invalid or expired token
        header("Location: reset_password.php?error=Invalid%20or%20expired%20token.");
        exit();
    }
}
?>


<?php
// Database connection configuration
$host = 'localhost';
$db_username = 'root';
$db_password = '';
$db_name = 'your_database';

// Connect to database
$conn = new mysqli($host, $db_username, $db_password, $db_name);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Error reporting
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Session start
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password</title>
</head>
<body>
<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if form was submitted
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    
    // Check if email exists in database
    $sql = "SELECT id FROM users WHERE email='$email'";
    $result = $conn->query($sql);
    
    if ($result->num_rows > 0) {
        // Generate a random token
        $token = md5(uniqid(rand(), true));
        
        // Update the database with the new token
        $updateSql = "UPDATE users SET reset_token='$token', reset_token_expiry=DATE_ADD(NOW(), INTERVAL 1 HOUR) WHERE email='$email'";
        if ($conn->query($updateSql)) {
            // Send the password reset email
            $to = $email;
            $subject = "Password Reset Request";
            $message = "Please click on this link to reset your password: http://yourwebsite.com/reset-password.php?token=$token
";
            $headers = "From: noreply@yourwebsite.com\r
";
            
            mail($to, $subject, $message, $headers);
            
            echo "An email has been sent to $email with instructions to reset your password.";
        } else {
            echo "Error updating record: " . $conn->error;
        }
    } else {
        echo "Email not found in our records. Please try again.";
    }
}
?>

<h2>Forgot Password</h2>
<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
    Email: <input type="text" name="email"><br><br>
    <input type="submit" value="Submit">
</form>

<?php
// Close database connection
$conn->close();
?>


<?php
// Database connection configuration
$host = 'localhost';
$db_username = 'root';
$db_password = '';
$db_name = 'your_database';

// Connect to database
$conn = new mysqli($host, $db_username, $db_password, $db_name);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Error reporting
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Session start
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial_scale=1.0">
    <title>Reset Password</title>
</head>
<body>
<?php
if (isset($_GET['token'])) {
    $token = mysqli_real_escape_string($conn, $_GET['token']);
    
    // Check if token exists and hasn't expired
    $sql = "SELECT id FROM users WHERE reset_token='$token' AND reset_token_expiry > NOW()";
    $result = $conn->query($sql);
    
    if ($result->num_rows == 1) {
        // Token is valid
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $new_password = mysqli_real_escape_string($conn, $_POST['new_password']);
            
            // Update the password
            $updateSql = "UPDATE users SET password=MD5('$new_password'), reset_token=NULL WHERE reset_token='$token'";
            if ($conn->query($updateSql)) {
                echo "Password updated successfully!";
            } else {
                echo "Error updating password: " . $conn->error;
            }
        }
        
        // Display reset form
        ?>
        <h2>Reset Password</h2>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"])."?token=".$token; ?>" method="post">
            New Password: <input type="password" name="new_password"><br><br>
            Confirm Password: <input type="password" name="confirm_password"><br><br>
            <input type="submit" value="Reset Password">
        </form>
        <?php
    } else {
        echo "Invalid or expired token.";
    }
} else {
    echo "No token provided.";
}
?>

<?php
// Close database connection
$conn->close();
?>


<?php
session_start();
// Database connection details
$host = 'localhost';
$dbname = 'your_database_name';
$username = 'root';
$password = '';

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get user input
    $email = $_POST['email'];

    // Validate email
    if (empty($email)) {
        die("Email is required!");
    }

    // Prepare SQL statement to check if email exists
    $stmt = $conn->prepare("SELECT * FROM users WHERE email=:email");
    $stmt->bindParam(':email', $email);
    $stmt->execute();

    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user) {
        die("No user found with this email address!");
    }

    // Generate a temporary password
    $temp_password = rand(1000, 9999); // You can make this more secure

    // Update the user's password in the database
    $updateStmt = $conn->prepare("UPDATE users SET password=:password WHERE email=:email");
    $updateStmt->bindParam(':password', $temp_password);
    $updateStmt->bindParam(':email', $email);
    $updateStmt->execute();

    // Send the temporary password to the user's email
    $to = $email;
    $subject = "Your Temporary Password";
    $message = "Dear " . $user['username'] . ",

Your temporary password is: " . $temp_password . "

Please login and change your password immediately.

Best regards,
Admin Team";
    $headers = "From: admin@example.com";

    if (mail($to, $subject, $message, $headers)) {
        echo "Password sent successfully! Check your email.";
        header("Refresh:3; url=login.php");
    } else {
        die("Failed to send password!");
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Forgot Password</title>
</head>
<body>
    <h2>Forgot Password</h2>
    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
        <input type="email" name="email" placeholder="Enter your email address" required><br><br>
        <input type="submit" value="Submit">
    </form>
</body>
</html>


<?php
// Database connection
$host = 'localhost';
$username = 'username';
$password = 'password';
$dbname = 'database_name';

$conn = mysqli_connect($host, $username, $password, $dbname);
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

function generateResetToken() {
    // Generate a 40 character random string
    return bin2hex(random_bytes(20));
}

// Reset password form
if (isset($_POST['reset'])) {
    $email = mysqli_real_escape_string($conn, $_POST['email']);

    // Check if email exists in the database
    $sql = "SELECT id FROM users WHERE email='$email'";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) > 0) {
        $token = generateResetToken();
        $expires = date('Y-m-d H:i:s', strtotime('+1 hour'));

        // Store token in database
        $sql = "INSERT INTO password_reset_tokens 
                (user_id, token, expires)
                VALUES ('$email', '$token', '$expires')";

        if (mysqli_query($conn, $sql)) {
            // Send email with reset link
            $to = $email;
            $subject = "Password Reset Request";
            $message = "Please click the following link to reset your password: 
                        http://example.com/reset-password.php?token=$token";
            $headers = "From: webmaster@example.com";

            if (mail($to, $subject, $message, $headers)) {
                echo "An email has been sent with instructions to reset your password.";
            } else {
                echo "There was an error sending the email. Please try again later.";
            }
        } else {
            echo "Error: " . mysqli_error($conn);
        }
    } else {
        echo "Email does not exist in our records.";
    }
}

// Reset password page
if (isset($_GET['token'])) {
    $token = $_GET['token'];
    
    // Check if token exists and is valid
    $sql = "SELECT user_id FROM password_reset_tokens 
            WHERE token='$token' AND expires > NOW()";
    
    $result = mysqli_query($conn, $sql);
    
    if (mysqli_num_rows($result) == 1) {
        // Show password reset form
        if (isset($_POST['new_password'])) {
            $new_password = $_POST['new_password'];
            
            // Update the user's password
            $row = mysqli_fetch_assoc($result);
            $user_id = $row['user_id'];
            
            $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
            
            $sql = "UPDATE users 
                    SET password='$hashed_password'
                    WHERE id='$user_id'";
                    
            if (mysqli_query($conn, $sql)) {
                // Delete token from database
                $sql = "DELETE FROM password_reset_tokens 
                        WHERE token='$token'";
                mysqli_query($conn, $sql);
                
                echo "Your password has been reset successfully!";
            } else {
                echo "Error resetting your password: " . mysqli_error($conn);
            }
        }
    } else {
        // Invalid or expired token
        echo "Invalid or expired token. Please request a new password reset.";
    }
}

// Close database connection
mysqli_close($conn);
?>


<?php
session_start();

// Configuration
$host = "localhost";
$username = "root";
$password = "";
$db_name = "your_database";

// Connect to MySQL
$conn = mysqli_connect($host, $username, $password, $db_name);
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Function to sanitize input
function sanitizeInput($data) {
    return htmlspecialchars(strip_tags(trim($data)));
}

// Process the form if submitted
if (isset($_POST['reset'])) {
    $email = isset($_POST['email']) ? sanitizeInput($_POST['email']) : "";

    // Validate email
    if (empty($email)) {
        $message = "Please enter your email address!";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $message = "Invalid email format!";
    } else {
        // Check if user exists
        $sql = "SELECT id FROM users WHERE email='" . mysqli_real_escape_string($conn, $email) . "'";
        $result = mysqli_query($conn, $sql);
        
        if (mysqli_num_rows($result) == 0) {
            $message = "No account found with this email!";
        } else {
            // Generate token
            $token = md5(uniqid(rand(), true));
            
            // Set expiration time (30 minutes)
            $expires = date("Y-m-d H:i:s", strtotime("+30 minutes"));
            
            // Insert into database
            $insert_sql = "INSERT INTO reset_password (user_id, token, expires) 
                          VALUES ('" . mysqli_real_escape_string($conn, $email) . "', '$token', '$expires')";
            $insert_result = mysqli_query($conn, $insert_sql);
            
            if (!$insert_result) {
                $message = "Error: Please try again later!";
            } else {
                // Send email
                $to = $email;
                $subject = "Password Reset Request";
                $reset_link = "http://your-site.com/reset_password.php?token=$token";
                
                $message_body = "Dear User,

You requested a password reset. Please click the following link to reset your password:
$reset_link

If you did not request this, please ignore this email.
The link will expire in 30 minutes.";
                
                if (mail($to, $subject, $message_body)) {
                    $message = "A password reset link has been sent to your email!";
                } else {
                    $message = "Error sending email. Please try again!";
                }
            }
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Forgot Password</title>
</head>
<body>
    <?php if (isset($message)) { ?>
        <p><?php echo $message; ?></p>
    <?php } ?>

    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        <input type="email" name="email" placeholder="Enter your email..." required>
        <button type="submit" name="reset">Reset Password</button>
    </form>
</body>
</html>

<?php
// Close database connection
mysqli_close($conn);
?>


<?php
// Database configuration
$host = 'localhost';
$username = 'root';
$password = '';
$dbname = 'mydatabase';

// Create connection
$conn = new mysqli($host, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// If form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get email from form data
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    
    // Validate email format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        die("Invalid email format");
    }
    
    // Check if email exists in database
    $sql = "SELECT id FROM users WHERE email = '$email'";
    $result = $conn->query($sql);
    
    if ($result->num_rows > 0) {
        // Generate a random token for password reset
        $token = bin2hex(random_bytes(32));
        
        // Store token in database along with user ID and expiration time (e.g., 1 hour)
        $expiration_time = date('Y-m-d H:i:s', strtotime('+1 hour'));
        
        $sql = "INSERT INTO reset_tokens (user_id, token, expire_time) VALUES (".$result->fetch_assoc()['id'].", '$token', '$expiration_time')";
        $conn->query($sql);
        
        // Send password reset email
        require 'PHPMailer/PHPMailer.php';
        require 'PHPMailer/SMTP.php';
        require 'PHPMailer/Exception.php';
        
        try {
            $mail = new PHPMailer\PHPMailer\PHPMailer();
            
            $mail->isSMTP();
            $mail->Host = 'smtp.example.com'; // Replace with your SMTP server
            $mail->SMTPAuth = true;
            $mail->Username = 'your_email@example.com'; // Your email username
            $mail->Password = 'your_password'; // Your email password
            $mail->SMTPSecure = 'tls';
            $mail->Port = 587;
            
            $mail->setFrom('your_email@example.com', 'Your Name');
            $mail->addAddress($email);
            
            $mail->isHTML(true);
            $mail->Subject = 'Password Reset Request';
            $mail->Body    = '<html>
                                <body>
                                    <p>Hello,</p>
                                    <p>We received a request to reset your password. Please click the link below to reset your password:</p>
                                    <a href="http://yourwebsite.com/reset-password.php?token='.$token.'">Reset Password</a>
                                    <p>If you did not request this password reset, please ignore this email.</p>
                                    <p>Best regards,<br>Your Website Team</p>
                                </body>
                            </html>';
            
            $mail->send();
        } catch (Exception $e) {
            die("Mailer Error: " . $e->getMessage());
        }
        
        // Set success message
        $_SESSION['message'] = 'We have sent you a password reset link. Please check your email.';
        header('Location: login.php');
        exit;
    } else {
        // Email not found in database
        die("Email address not found in our records");
    }
}
?>


<?php
// Database configuration
$host = 'localhost';
$username = 'root';
$password = '';
$dbname = 'your_database';

// Connect to database
$conn = mysqli_connect($host, $username, $password, $dbname);
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];

    // Sanitize email input
    $email = mysqli_real_escape_string($conn, $email);

    // Check if email exists in database
    $sql = "SELECT id FROM users WHERE email='$email'";
    $result = mysqli_query($conn, $sql);
    
    if (mysqli_num_rows($result) == 0) {
        echo "Email does not exist!";
    } else {
        // Generate a unique token for password reset
        $token = md5(uniqid(rand(), true));
        
        // Update the database with the new token and expiration time
        $sql = "UPDATE users SET password_reset_token='$token', 
                            password_reset_expires=NOW() + INTERVAL 1 HOUR
                            WHERE email='$email'";
        mysqli_query($conn, $sql);
        
        // Send email to user with reset link
        $to = $email;
        $subject = "Password Reset Request";
        $message = "
            <html>
                <head>
                    <title>Password Reset</title>
                </head>
                <body>
                    <p>Hello,</p>
                    <p>You have requested a password reset. Please click the link below to reset your password:</p>
                    <a href='http://yourwebsite.com/reset_password.php?token=$token'>Reset Password</a>
                    <p>If you did not request this password reset, please ignore this email.</p>
                    <p>This password reset link will expire in 1 hour.</p>
                </body>
            </html>
        ";
        
        $headers = "From: no-reply@yourwebsite.com\r
";
        $headers .= "Reply-To: no-reply@yourwebsite.com\r
";
        $headers .= "Content-Type: text/html; charset=UTF-8\r
";

        mail($to, $subject, $message, $headers);
        
        echo "A password reset link has been sent to your email!";
    }
}

// Close database connection
mysqli_close($conn);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Forgot Password</title>
</head>
<body>
    <h2>Forgot Password</h2>
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        <input type="email" name="email" placeholder="Enter your email address" required><br><br>
        <button type="submit">Send Reset Link</button>
    </form>
</body>
</html>


<?php
include('db_connection.php'); // Include your database connection

if (isset($_POST['email'])) {
    $email = $_POST['email'];
    
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        header("Location: forgot_password_form.php?error=Invalid email format");
        exit();
    }
    
    // Check if the email exists in the database
    $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->bind_param('s', $email);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows == 0) {
        header("Location: forgot_password_form.php?error=Email not registered");
        exit();
    } else {
        // Generate a random token
        $token = bin2hex(random_bytes(16));
        
        // Set token expiration time (e.g., 1 hour from now)
        $expires = date('Y-m-d H:i:s', strtotime('+1 hour'));
        
        // Insert the token into the database
        $stmt = $conn->prepare("INSERT INTO password_reset (user_id, token, expires) VALUES (?, ?, ?)");
        $stmt->bind_param('iss', $result->fetch_assoc()['id'], $token, $expires);
        $stmt->execute();
        
        // Send email to user with reset link
        $to = $email;
        $subject = "Password Reset Request";
        $message = "Click the following link to reset your password: http://yourwebsite.com/reset_password.php?token=$token";
        $headers = "From: yourwebsite@example.com\r
".
                   "Content-Type: text/plain; charset=UTF-8\r
".
                   "Content-Transfer-Encoding: 7bit";
        
        mail($to, $subject, $message, $headers);
        
        header("Location: forgot_password_form.php?error=Password reset link sent to your email");
    }
} else {
    // If no email was provided
    header("Location: forgot_password_form.php?error=Please enter your email address");
}
?>


<?php
include('db_connection.php'); // Include your database connection

if (!isset($_GET['token'])) {
    die("Invalid link.");
}

$token = $_GET['token'];

// Check if token exists and hasn't expired
$stmt = $conn->prepare("SELECT user_id, expires FROM password_reset WHERE token = ?");
$stmt->bind_param('s', $token);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    die("Invalid or expired reset link.");
}

$row = $result->fetch_assoc();
$expires = $row['expires'];

if (strtotime($expires) < time()) {
    die("Reset link has expired. Please request a new one.");
}

// If token is valid, show password reset form
?>

<!DOCTYPE html>
<html>
<head>
    <title>Reset Password</title>
</head>
<body>
    <h2>Reset Password</h2>
    
    <?php
        if (isset($_GET['error'])) {
            echo "<p style='color:red;'>".$_GET['error']."</p>";
        }
    ?>
    
    <form action="reset_password.php?token=<?php echo $token; ?>" method="post">
        <label for="new_password">New Password:</label><br>
        <input type="password" id="new_password" name="new_password"><br><br>
        
        <label for="confirm_password">Confirm Password:</label><br>
        <input type="password" id="confirm_password" name="confirm_password"><br><br>
        
        <input type="submit" value="Reset Password">
    </form>

<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    if ($new_password != $confirm_password) {
        header("Location: reset_password.php?token=$token&error=Passwords do not match");
        exit();
    }

    // Validate password strength (you can modify these requirements)
    if (strlen($new_password) < 8 || !preg_match('/[a-zA-Z]/', $new_password) || !preg_match('/\d/', $new_password)) {
        header("Location: reset_password.php?token=$token&error=Password must be at least 8 characters long and contain both letters and numbers");
        exit();
    }

    // Hash the new password
    $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

    // Update the user's password in the database
    $stmt = $conn->prepare("UPDATE users SET password = ? WHERE id = ?");
    $stmt->bind_param('si', $hashed_password, $row['user_id']);
    $stmt->execute();

    // Invalidate the reset token
    $stmt = $conn->prepare("DELETE FROM password_reset WHERE token = ?");
    $stmt->bind_param('s', $token);
    $stmt->execute();

    header("Location: login.php?success=Password has been successfully reset");
}
?>
</body>
</html>


<?php
require 'db_connection.php'; // Include your database connection

if (isset($_POST['email'])) {
    $email = mysqli_real_escape_string($conn, $_POST['email']);

    $sql = "SELECT id FROM users WHERE email = '$email'";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) == 0) {
        header("Location: forgot_form.php?error=Email%20not%20found");
        exit();
    }

    // Generate reset token and expiry
    $token = bin2hex(random_bytes(16));
    $expires = date('Y-m-d H:i:s', time() + 3600); // Expiry in 1 hour

    $sql = "INSERT INTO password_resets (email, token, expires) 
            VALUES ('$email', '$token', '$expires')";
    mysqli_query($conn, $sql);

    // Send email with reset link
    $to = $email;
    $subject = "Password Reset Request";
    $message = "Please click the following link to reset your password:

".
               "http://yourdomain.com/reset_password.php?token=$token

".
               "This link will expire in 1 hour.";
    $headers = "From: admin@yourdomain.com";

    mail($to, $subject, $message, $headers);

    header("Location: forgot_form.php?error=Password%20reset%20link%20sent");
    exit();
}
?>


<?php
require 'db_connection.php'; // Include your database connection

if (!isset($_GET['token']) || !isset($_POST['password'])) {
    header("Location: reset_form.php?error=Invalid%20request");
    exit();
}

$token = mysqli_real_escape_string($conn, $_GET['token']);
$password = mysqli_real_escape_string($conn, $_POST['password']);

// Verify token and check expiration
$sql = "SELECT email FROM password_resets 
        WHERE token = '$token' AND expires > NOW()";
$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) == 0) {
    header("Location: reset_form.php?error=Invalid%20or%20expired%20link");
    exit();
}

$email = mysqli_fetch_assoc($result)['email'];

// Update the password
$hash = password_hash($password, PASSWORD_DEFAULT);
$sql = "UPDATE users SET password = '$hash' WHERE email = '$email'";
mysqli_query($conn, $sql);

// Invalidate the token
$sql = "DELETE FROM password_resets WHERE token = '$token'";
mysqli_query($conn, $sql);

header("Location: login.php?success=Password%20reset%20successful");
exit();
?>


<?php
session_start();

// Database configuration
$host = 'localhost';
$dbname = 'your_database_name';
$username = 'your_username';
$password = 'your_password';

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die("Connection failed: " $e->getMessage());
}

// Function to send reset password email
function sendResetEmail($email, $token) {
    $to = $email;
    $subject = "Password Reset Request";
    $message = "
        <html>
            <head></head>
            <body>
                <h2>Password Reset</h2>
                <p>Please click the following link to reset your password:</p>
                <a href='http://yourdomain.com/reset-password.php?token=" . $token . "'>Reset Password</a><br><br>
                <p>If you did not request this password reset, please ignore this email.</p>
            </body>
        </html>
    ";
    $headers = "MIME-Version: 1.0" . "\r
";
    $headers .= "Content-type:text/html;charset=UTF-8" . "\r
";
    mail($to, $subject, $message, $headers);
}

// Function to reset password
function resetPassword($conn) {
    if (isset($_POST['reset'])) {
        $email = $_POST['email'];
        
        // Check if email exists in database
        try {
            $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
            $stmt->execute([$email]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($user) {
                // Generate random token
                $token = md5(uniqid(rand(), true));
                
                // Store token and expiration time in database
                $expires = date('Y-m-d H:i:s', strtotime('+1 hour'));
                $stmt = $conn->prepare("UPDATE users SET reset_token = ?, reset_expires = ? WHERE email = ?");
                $stmt->execute([$token, $expires, $email]);
                
                // Send reset link to user's email
                sendResetEmail($email, $token);
                
                echo "An email has been sent to you with a password reset link.";
            } else {
                echo "This email is not registered in our system.";
            }
        } catch(PDOException $e) {
            die("Error: " . $e->getMessage());
        }
    }
}

// Function to validate and update new password
function validateReset($conn) {
    if (isset($_GET['token'])) {
        $token = $_GET['token'];
        
        // Check token validity and expiration
        try {
            $stmt = $conn->prepare("SELECT * FROM users WHERE reset_token = ?");
            $stmt->execute([$token]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($user && $user['reset_expires'] > date('Y-m-d H:i:s')) {
                // Token is valid and not expired
                if (isset($_POST['submit'])) {
                    $new_password = $_POST['password'];
                    $confirm_password = $_POST['cpassword'];
                    
                    if ($new_password == $confirm_password) {
                        // Update password in database
                        $hash = password_hash($new_password, PASSWORD_DEFAULT);
                        
                        $stmt = $conn->prepare("UPDATE users SET password = ?, reset_token = null WHERE email = ?");
                        $stmt->execute([$hash, $user['email']]);
                        
                        echo "Your password has been successfully updated!";
                    } else {
                        echo "Passwords do not match!";
                    }
                }
            } else {
                echo "Invalid or expired token.";
            }
        } catch(PDOException $e) {
            die("Error: " . $e->getMessage());
        }
    }
}

// Main function
if (isset($_GET['action'])) {
    if ($_GET['action'] == 'reset') {
        resetPassword($conn);
    }
} else {
    validateReset($conn);
}

$conn = null;
?>


<?php
include('db_connection.php'); // Include your database connection

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    
    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        die("Please enter a valid email address.");
    }

    try {
        $stmt = $pdo->prepare("SELECT id, username, password FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$user) {
            die("Email not found in our records. Please check your email.");
        }

        // Generate a unique token
        $token = bin(20);
        
        // Check if the token exists to avoid duplicates
        do {
            $existingTokenStmt = $pdo->prepare("SELECT id FROM password_resets WHERE token = ?");
            $existingTokenStmt->execute([$token]);
        } while ($existingTokenStmt->fetch());

        // Insert into password_resets table
        $insertStmt = $pdo->prepare("INSERT INTO password_resets (user_id, token, created_at) VALUES (?, ?, NOW())");
        $insertStmt->execute([$user['id'], $token]);

        // Send email with reset link
        require 'PHPMailer/PHPMailer.php';
        require 'PHPMailer/SMTP.php';
        require 'PHPMailer/Exception.php';

        try {
            $mail = new PHPMailer\PHPMailer();
            $mail->isSMTP();
            $mail->Host = 'smtp.example.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'your_email@example.com';
            $mail->Password = 'your_password';
            $mail->Port = 587;

            $mail->setFrom('your_email@example.com', 'Your Name');
            $mail->addAddress($email);
            $mail->Subject = 'Reset Your Password';

            $resetLink = "http://example.com/reset.php?token=$token";
            $body = "Please click the following link to reset your password: <br><a href='$resetLink'>$resetLink</a>";
            $mail->Body = $body;

            $mail->send();
            echo "An email with instructions has been sent to you!";
        } catch (Exception $e) {
            die("Error sending email. Please try again later.");
        }
    } catch (PDOException $e) {
        die("Database error occurred: " . $e->getMessage());
    }
}
?>


<?php
include('db_connection.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $token = $_GET['token'];
    
    if (empty($token)) {
        die("Invalid request.");
    }

    // Verify token existence and validity
    try {
        $stmt = $pdo->prepare("SELECT user_id FROM password_resets WHERE token = ? AND created_at > NOW() - INTERVAL 30 MINUTE");
        $stmt->execute([$token]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$result) {
            die("Invalid or expired reset link. Please request a new one.");
        }

        // Validate password
        $new_password = $_POST['new_password'];
        $confirm_password = $_POST['confirm_password'];

        if ($new_password !== $confirm_password) {
            die("Passwords do not match.");
        }

        // Update the user's password
        $hash = password_hash($new_password, PASSWORD_DEFAULT);
        
        $updateStmt = $pdo->prepare("UPDATE users SET password = ? WHERE id = ?");
        $updateStmt->execute([$hash, $result['user_id']]);

        // Invalidate the token
        $invalidateStmt = $pdo->prepare("DELETE FROM password_resets WHERE token = ?");
        $invalidateStmt->execute([$token]);

        echo "Password reset successful! You can now login with your new password.";
    } catch (PDOException $e) {
        die("Database error occurred: " . $e->getMessage());
    }
}
?>


<?php
function sendPasswordResetEmail($recipient_email, $user_name, $site_url) {
    // Set up the email content
    $subject = "Password Reset Request";
    
    $reset_link = $site_url . "/password-reset.php?email=" . urlencode($recipient_email);
    
    $message = "
        <html>
        <head>
            <title>Password Reset</title>
        </head>
        <body style='font-family: Arial, sans-serif; line-height: 1.6;'>
            <h2>Hello " . htmlspecialchars($user_name) . ",</h2>
            
            <p>We received a request to reset your password. Please click the link below to reset it:</p>
            
            <a href='" . $reset_link . "' style='display: inline-block; padding: 10px 20px; background-color: #007bff; color: white; text-decoration: none; border-radius: 5px;'>
                Reset Password
            </a>
            
            <p>If you didn't request this password reset, you can safely ignore this email.</p>
            
            <hr style='margin: 20px 0;'>
            <p>This is an automated message from " . $site_url . ". Please do not reply to this email.</p>
        </body>
        </html>
    ";
    
    // Set up the headers
    $headers = "MIME-Version: 1.0" . "\r
";
    $headers .= "Content-type:text/html;charset=UTF-8" . "\r
";
    $headers .= "From: " . $site_url . "<no-reply@" . str_replace("http://", "", $site_url) . ">" . "\r
";
    
    // Send the email
    mail($recipient_email, $subject, $message, $headers);
}

// Example usage:
// sendPasswordResetEmail('user@example.com', 'John Doe', 'https://your-site.com');
?>


<?php
session_start();
require_once 'db.php'; // Include your database connection file

class ForgotPassword {
    private $db;
    
    public function __construct() {
        $this->db = new DBConnection();
    }
    
    // Check if email exists and send reset link
    public function handleForgotPassword($email) {
        try {
            // Check if email exists in database
            if (!$this->emailExists($email)) {
                throw new Exception("Email not found in our records.");
            }
            
            // Generate a unique token for password reset
            $token = bin2hex(random_bytes(16));
            $expires = date('Y-m-d H:i:s', time() + 3600); // Token expires after 1 hour
            
            // Store the token in database
            $this->storeResetToken($email, $token, $expires);
            
            // Send reset password email
            $this->sendResetEmail($email, $token);
            
            return true;
        } catch (Exception $e) {
            $_SESSION['error'] = $e->getMessage();
            header("Location: login.php");
            exit();
        }
    }
    
    private function emailExists($email) {
        $query = "SELECT id FROM users WHERE email = ?";
        $stmt = $this->db->prepare($query);
        $stmt->execute([$email]);
        
        return $stmt->rowCount() > 0;
    }
    
    private function storeResetToken($email, $token, $expires) {
        $query = "INSERT INTO password_resets (user_id, token, expires) 
                  VALUES (?, ?, ?)";
                  
        $stmt = $this->db->prepare($query);
        
        // Get user ID
        $userIdQuery = "SELECT id FROM users WHERE email = ?";
        $userIdStmt = $this->db->prepare($userIdQuery);
        $userIdStmt->execute([$email]);
        $row = $userIdStmt->fetch(PDO::FETCH_ASSOC);
        $user_id = $row['id'];
        
        $stmt->execute([$user_id, $token, $expires]);
    }
    
    private function sendResetEmail($email, $token) {
        require_once 'PHPMailer/PHPMailer.php';
        require_once 'PHPMailer/SMTP.php';
        require_once 'PHPMailer/Exception.php';
        
        $mail = new PHPMailer(true);
        
        try {
            // Server settings
            $mail->isSMTP();
            $mail->Host = 'smtp.example.com';  // Replace with your SMTP server
            $mail->SMTPAuth = true;
            $mail->Username = 'your_email@example.com'; // Replace with your email
            $mail->Password = 'your_password';          // Replace with your password
            $mail->Port = 587;                          // TCP port to connect to
            
            // Recipients
            $mail->setFrom('your_email@example.com', 'Your Name');
            $mail->addAddress($email);
            
            // Content
            $resetLink = "http://$_SERVER[HTTP_HOST]/reset-password.php?token=$token";
            $body = "Click the following link to reset your password: <a href='$resetLink'>$resetLink</a>";
            $body .= "<br>If you're unable to click the link, copy and paste this token into the reset form: $token";
            
            $mail->isHTML(true);
            $mail->Subject = 'Password Reset Request';
            $mail->Body    = $body;
            
            $mail->send();
        } catch (Exception $e) {
            throw new Exception("Email could not be sent. Error: {$mail->ErrorInfo}");
        }
    }
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    
    $forgotPassword = new ForgotPassword();
    if ($forgotPassword->handleForgotPassword($email)) {
        // Reset password email sent successfully
        $_SESSION['success'] = "We've sent a password reset link to your email address.";
        header("Location: login.php");
        exit();
    }
}
?>


<?php
// Database configuration
$host = 'localhost';
$dbname = 'your_database_name';
$username = 'your_username';
$password = 'your_password';

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

// Generate a random token
function generateToken() {
    return bin2hex(random_bytes(16));
}

// Check if email exists in database
function checkEmail($email, $conn) {
    $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->execute([$email]);
    return $stmt->rowCount();
}

// Store reset token and expiration time
function storeResetToken($userId, $token, $conn) {
    $expirationTime = date('Y-m-d H:i:s', strtotime('+1 hour'));
    $stmt = $conn->prepare("INSERT INTO password_reset (user_id, token, expires_at) VALUES (?, ?, ?)");
    return $stmt->execute([$userId, $token, $expirationTime]);
}

// Send reset email
function sendResetEmail($email, $resetLink) {
    $to = $email;
    $subject = "Password Reset Request";
    $message = "
        <html>
            <head></head>
            <body>
                <p>Hello,</p>
                <p>We received a password reset request. Click the link below to reset your password:</p>
                <a href='$resetLink'>$resetLink</a>
                <br><br>
                If you did not request this, please ignore this email.
            </body>
        </html>";
    $headers = "MIME-Version: 1.0" . "\r
";
    $headers .= "Content-type:text/html;charset=UTF-8" . "\r
";
    $headers .= "From: your_email@example.com" . "\r
";

    return mail($to, $subject, $message, $headers);
}

// Reset password function
function resetPassword($token, $newPassword, $conn) {
    // Verify token exists and is valid
    $stmt = $conn->prepare("SELECT user_id FROM password_reset WHERE token = ? AND expires_at > NOW()");
    $stmt->execute([$token]);
    
    if ($stmt->rowCount() == 1) {
        list($userId) = $stmt->fetch(PDO::FETCH_NUM);
        
        // Update user's password
        $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
        $updateStmt = $conn->prepare("UPDATE users SET password = ? WHERE id = ?");
        if ($updateStmt->execute([$hashedPassword, $userId])) {
            // Invalidate the token
            $invalidateStmt = $conn->prepare("DELETE FROM password_reset WHERE token = ?");
            $invalidateStmt->execute([$token]);
            return true;
        }
    }
    return false;
}

// Check if email exists and send reset link
if (isset($_POST['email'])) {
    $email = $_POST['email'];
    $token = generateToken();
    
    if (checkEmail($email, $conn)) {
        // Get user ID
        $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->execute([$email]);
        list($userId) = $stmt->fetch(PDO::FETCH_NUM);
        
        // Store token
        storeResetToken($userId, $token, $conn);
        
        // Create reset link
        $resetLink = "http://$_SERVER[HTTP_HOST]/reset-password.php?token=$token";
        
        // Send email
        if (sendResetEmail($email, $resetLink)) {
            echo "An email has been sent to your inbox with instructions to reset your password.";
        } else {
            echo "There was an error sending the email. Please try again later.";
        }
    } else {
        echo "This email address does not exist in our database.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Forgot Password</title>
    <style>
        body { font-family: Arial, sans-serif; max-width: 500px; margin: 0 auto; padding: 20px; }
        .form-group { margin-bottom: 10px; }
        input { width: 100%; padding: 8px; }
        button { background-color: #4CAF50; color: white; padding: 10px 20px; border: none; border-radius: 4px; cursor: pointer; }
    </style>
</head>
<body>
    <h1>Forgot Password</h1>
    <p>Please enter your email address to reset your password.</p>
    
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <div class="form-group">
            <input type="email" name="email" placeholder="Enter your email..." required>
        </div>
        <button type="submit">Send Reset Link</button>
    </form>
</body>
</html>

// Create a separate file (reset-password.php) for the password reset form

<?php
session_start();
if (!isset($_GET['token'])) {
    header("Location: forgot-password.php");
    exit();
}

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

// Verify token
$stmt = $conn->prepare("SELECT user_id FROM password_reset WHERE token = ? AND expires_at > NOW()");
$stmt->execute([$_GET['token']]);
if ($stmt->rowCount() != 1) {
    header("Location: forgot-password.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Reset Password</title>
    <style>
        body { font-family: Arial, sans-serif; max-width: 500px; margin: 0 auto; padding: 20px; }
        .form-group { margin-bottom: 10px; }
        input { width: 100%; padding: 8px; }
        button { background-color: #4CAF50; color: white; padding: 10px 20px; border: none; border-radius: 4px; cursor: pointer; }
    </style>
</head>
<body>
    <h1>Reset Password</h1>
    
    <?php if (isset($_GET['success'])) { ?>
        <p>Password reset successfully! <a href="login.php">Click here to login</a>.</p>
    <?php } else { ?>

    <form action="reset-password-process.php" method="post">
        <input type="hidden" name="token" value="<?php echo $_GET['token']; ?>">
        <div class="form-group">
            <input type="password" name="new_password" placeholder="Enter new password..." required>
        </div>
        <div class="form-group">
            <input type="password" name="confirm_password" placeholder="Confirm new password..." required>
        </div>
        <button type="submit">Reset Password</button>
    </form>

    <?php } ?>

</body>
</html>

// Create a separate file (reset-password-process.php) to handle the password reset

<?php
session_start();
if (!isset($_POST['token']) || !isset($_POST['new_password'])) {
    header("Location: forgot-password.php");
    exit();
}

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

// Verify token and reset password
if (resetPassword($_POST['token'], $_POST['new_password'], $conn)) {
    header("Location: reset-password.php?success=1");
    exit();
} else {
    echo "There was an error resetting your password. Please try again.";
}
?>


if (isset($_POST['email'])) {
    $email = $_POST['email'];
    
    // Validate email
    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        die("Invalid email address");
    }
}


try {
    $pdo = new PDO('mysql:host=localhost;dbname=your_database', 'username', 'password');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Prepare and execute the query
    $stmt = $pdo->prepare("SELECT id FROM users WHERE email = :email");
    $stmt->execute(['email' => $email]);
    
    if ($stmt->rowCount() == 0) {
        die("Email not found in our records.");
    }
} catch (PDOException $e) {
    die("Database error: " . $e->getMessage());
}


$token = bin2hex(random_bytes(16));
$expires = date('Y-m-d H:i:s', strtotime('+30 minutes'));

// Update user record with new token and expiry
$stmt = $pdo->prepare("UPDATE users SET reset_token = :token, reset_expires = :expires WHERE email = :email");
$stmt->execute(['token' => $token, 'expires' => $expires, 'email' => $email]);


$subject = "Password Reset Request";
$message = "Dear User,

Please click on the following link to reset your password:

http://example.com/reset.php?token=$token

This link will expire in 30 minutes.

Best regards,
Your Support Team";
$headers = "From: noreply@example.com\r
";

mail($email, $subject, $message, $headers);


echo "An email with instructions to reset your password has been sent to $email.";


<?php
function forgot_password($email) {
    // Database configuration
    $host = 'localhost';
    $db_name = 'your_database';
    $username = 'username';
    $password = 'password';

    try {
        $pdo = new PDO("mysql:host=$host;dbname=$db_name", $username, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Check if email exists
        $stmt = $pdo->prepare("SELECT id FROM users WHERE email = :email");
        $stmt->execute(['email' => $email]);
        if ($stmt->rowCount() == 0) {
            return "Email not found.";
        }

        // Generate token and expiry time
        $token = bin2hex(random_bytes(16));
        $expires = date('Y-m-d H:i:s', strtotime('+30 minutes'));

        // Update user record
        $stmt = $pdo->prepare("UPDATE users SET reset_token = :token, reset_expires = :expires WHERE email = :email");
        $stmt->execute(['token' => $token, 'expires' => $expires, 'email' => $email]);

        // Send email
        $subject = "Password Reset Request";
        $message = "Dear User,

Please click on the following link to reset your password:

http://example.com/reset.php?token=$token

This link will expire in 30 minutes.

Best regards,
Your Support Team";
        $headers = "From: noreply@example.com\r
";

        if (mail($email, $subject, $message, $headers)) {
            return "Password reset instructions sent to your email.";
        } else {
            return "Failed to send email. Please try again later.";
        }

    } catch(PDOException $e) {
        return "Error: " . $e->getMessage();
    }
}

if (isset($_POST['email'])) {
    $email = $_POST['email'];
    if (!empty($email) && filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo forgot_password($email);
    } else {
        die("Invalid email address");
    }
}
?>


<?php
if (isset($_GET['token'])) {
    $token = $_GET['token'];
    
    // Database connection
    $host = 'localhost';
    $db_name = 'your_database';
    $username = 'username';
    $password = 'password';

    try {
        $pdo = new PDO("mysql:host=$host;dbname=$db_name", $username, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Check if token is valid and not expired
        $stmt = $pdo->prepare("SELECT id, reset_expires FROM users WHERE reset_token = :token");
        $stmt->execute(['token' => $token]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$user) {
            die("Invalid or expired token.");
        }

        // Check expiration
        if (strtotime($user['reset_expires']) < time()) {
            die("Token has expired. Please request a new one.");
        }

        // Show password reset form
        ?>
        <form method="post">
            New Password: <input type="password" name="new_password" required><br>
            Confirm Password: <input type="password" name="confirm_password" required><br>
            <input type="hidden" name="token" value="<?php echo $token; ?>">
            <input type="submit" value="Reset Password">
        </form>

        <?php
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $newPassword = $_POST['new_password'];
            $confirmPassword = $_POST['confirm_password'];
            
            if ($newPassword != $confirmPassword) {
                die("Passwords do not match.");
            }

            // Hash the password
            $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);

            // Update password
            $stmt = $pdo->prepare("UPDATE users SET password = :password WHERE reset_token = :token");
            $stmt->execute(['password' => $hashedPassword, 'token' => $token]);

            // Invalidate token
            $stmt = $pdo->prepare("UPDATE users SET reset_token = NULL, reset_expires = NULL WHERE id = :id");
            $stmt->execute(['id' => $user['id']]);

            echo "Password has been successfully updated!";
        }
    } catch(PDOException $e) {
        die("Error: " . $e->getMessage());
    }
} else {
    die("No token provided.");
}
?>


<?php
// Include database configuration file
include('db_config.php');

if(isset($_POST['reset_password'])) {
    // Get email from POST request
    $email = $_POST['email'];
    
    // Validate email
    if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        die("Invalid email format");
    }
    
    // Check if email exists in database
    $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->bind_param('s', $email);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if($result->num_rows == 0) {
        die("Email not registered");
    }
    
    // Generate reset token
    $token = md5(uniqid(rand(), true));
    $expires = date('Y-m-d H:i:s', time() + 3600); // Token expires after 1 hour
    
    // Update database with the reset token and expiration time
    $stmt = $conn->prepare("UPDATE users SET reset_token = ?, reset_expires = ? WHERE email = ?");
    $stmt->bind_param('sss', $token, $expires, $email);
    
    if($stmt->execute()) {
        // Send password reset link to user's email
        $reset_link = "http://example.com/reset_password.php?token=" . $token;
        
        $to = $email;
        $subject = "Password Reset Request";
        $message = "
            <html>
                <body>
                    <p>We received a request to reset your password. Please click the link below to reset it:</p>
                    <a href='$reset_link'>Reset Password</a><br><br>
                    <p>If you did not request this password reset, please ignore this email.</p>
                    <p>This link will expire in 30 minutes.</p>
                </body>
            </html>
        ";
        
        $headers = "MIME-Version: 1.0" . "\r
";
        $headers .= "Content-type:text/html;charset=UTF-8" . "\r
";
        $headers .= "From: <your_email@example.com>" . "\r
";
        
        if(mail($to, $subject, $message, $headers)) {
            // Redirect to login page with success message
            header("Location: login.php?msg=We've sent you a password reset link. Check your email.");
            exit();
        } else {
            die("Failed to send reset link");
        }
    } else {
        die("Error resetting password");
    }
}
?>


<?php
// Database configuration
$host = 'localhost';
$username = 'root';
$password = '';
$database = 'your_database';

// Create connection
$conn = new mysqli($host, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Function to generate a random token
function generateToken() {
    $characters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
    $token = '';
    for ($i = 0; $i < 30; $i++) {
        $token .= $characters[rand(0, strlen($characters) - 1)];
    }
    return $token;
}

// Function to send password reset email
function sendResetEmail($email, $resetToken) {
    $to = $email;
    $subject = 'Password Reset Request';
    $message = "Please click the following link to reset your password:

http://yourwebsite.com/reset-password.php?token=" . $resetToken;
    $headers = 'From: noreply@yourwebsite.com' . "\r
";
    
    if (mail($to, $subject, $message, $headers)) {
        return true;
    } else {
        return false;
    }
}

// Function to handle password reset request
function forgotPassword($email) {
    global $conn;
    
    // Check if email exists in the database
    $sql = "SELECT id FROM users WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();
        $userId = $row['id'];
        
        // Generate a new reset token
        $resetToken = generateToken();
        $expires = date('Y-m-d H:i:s', strtotime('+1 hour'));
        
        // Insert the reset token into the database
        $sqlReset = "INSERT INTO password_resets (user_id, token, expires) VALUES (?, ?, ?)";
        $stmtReset = $conn->prepare($sqlReset);
        $stmtReset->bind_param("iss", $userId, $resetToken, $expires);
        
        if ($stmtReset->execute()) {
            // Send reset email
            if (sendResetEmail($email, $resetToken)) {
                return "A password reset link has been sent to your email address.";
            } else {
                return "An error occurred while sending the reset email.";
            }
        } else {
            return "An error occurred while processing your request.";
        }
    } else {
        return "Email not found in our records.";
    }
}

// Function to validate and reset password
function resetPassword($token, $newPassword) {
    global $conn;
    
    // Check if token exists and is valid
    $sql = "SELECT user_id FROM password_resets WHERE token = ? AND expires > NOW()";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();
        $userId = $row['user_id'];
        
        // Hash the new password
        $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
        
        // Update the user's password
        $sqlUpdate = "UPDATE users SET password = ? WHERE id = ?";
        $stmtUpdate = $conn->prepare($sqlUpdate);
        $stmtUpdate->bind_param("si", $hashedPassword, $userId);
        
        if ($stmtUpdate->execute()) {
            // Delete the reset token
            $sqlDelete = "DELETE FROM password_resets WHERE token = ?";
            $stmtDelete = $conn->prepare($sqlDelete);
            $stmtDelete->bind_param("s", $token);
            $stmtDelete->execute();
            
            return "Password has been successfully reset.";
        } else {
            return "An error occurred while resetting your password.";
        }
    } else {
        return "Invalid or expired token.";
    }
}

// Example usage:
if (isset($_POST['email'])) {
    $email = $_POST['email'];
    echo forgotPassword($email);
}

if (isset($_GET['token']) && isset($_POST['new_password'])) {
    $token = $_GET['token'];
    $newPassword = $_POST['new_password'];
    echo resetPassword($token, $newPassword);
}
?>


<?php
// Database configuration
$host = 'localhost';
$dbname = 'your_database_name';
$username = 'your_username';
$password = 'your_password';

try {
    // Create a new PDO instance
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

// Function to generate a random password
function generatePassword($length = 12) {
    $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*()';
    $password = substr(str_shuffle($chars), 0, $length);
    return $password;
}

// Function to send reset password email
function sendResetPasswordEmail($email, $newPassword) {
    $to = $email;
    $subject = 'Reset Your Password';
    $message = "Dear User,

Your new password is: " . $newPassword . "

Please login and change your password immediately.

Best regards,
The Support Team";
    $headers = 'From: noreply@yourdomain.com' . "\r
" .
               'Reply-To: noreply@yourdomain.com' . "\r
" .
               'X-Mailer: PHP/' . phpversion();

    return mail($to, $subject, $message, $headers);
}

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get email from the form
    $email = $_POST['email'];

    try {
        // Prepare and execute the query
        $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->execute([$email]);
        
        // Check if user exists
        if ($stmt->rowCount() > 0) {
            // Generate a new password
            $newPassword = generatePassword();
            
            // Update the password in the database
            $updateStmt = $conn->prepare("UPDATE users SET password = ? WHERE email = ?");
            $updateStmt->execute([md5($newPassword), $email]);
            
            // Send reset password email
            if (sendResetPasswordEmail($email, $newPassword)) {
                echo "A new password has been sent to your email address.";
            } else {
                echo "Error sending email. Please try again later.";
            }
        } else {
            echo "This email does not exist in our records.";
        }
    } catch (PDOException $e) {
        die("Error: " . $e->getMessage());
    }
}

// Close the database connection
$conn = null;
?>


<?php
session_start();
// Connect to database
require_once 'config.php';

// Error/success messages
$messages = array();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = isset($_POST['email']) ? $_POST['email'] : '';
    
    if (empty($email)) {
        $messages[] = "Please enter your email address.";
    } else {
        // Check if email exists in the database
        $sql = "SELECT id FROM users WHERE email=?";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, 's', $email);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        
        if (mysqli_num_rows($result) == 0) {
            $messages[] = "This email is not registered.";
        } else {
            // Generate a random token
            $token = bin2hex(random_bytes(16));
            
            // Store the token in the database with expiration time (e.g., 1 hour)
            $expiration_time = time() + 3600;
            $sql = "INSERT INTO password_reset_tokens (user_id, token, expires_at) 
                    VALUES (?, ?, ?)";
            $stmt = mysqli_prepare($conn, $sql);
            mysqli_stmt_bind_param($stmt, 'iss', $result->fetch_assoc()['id'], $token, $expiration_time);
            mysqli_stmt_execute($stmt);
            
            // Send reset password email
            $reset_url = "http://".$_SERVER['HTTP_HOST']."/reset_password.php?token=".$token;
            $to = $email;
            $subject = "Reset Your Password";
            $message = "Click the following link to reset your password: 

".$reset_url."

This link will expire in 1 hour.";
            
            if (mail($to, $subject, $message)) {
                $messages[] = "We've sent you a password reset email. Check your inbox!";
            } else {
                $messages[] = "An error occurred while sending the email.";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password</title>
</head>
<body>
    <div class="container">
        <?php foreach ($messages as $message) { ?>
            <div class="alert <?php echo (strpos($message, 'error:') === 0) ? 'error' : 'success'; ?>">
                <?php echo str_replace('error:', '', $message); ?>
            </div>
        <?php } ?>

        <h2>Forgot Password</h2>
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
            <label for="email">Email:</label>
            <input type="text" name="email" id="email">
            <button type="submit">Send Reset Link</button>
        </form>
    </div>
</body>
</html>


<?php
session_start();
require_once 'config.php';

if (!isset($_GET['token'])) {
    die("Invalid request.");
}

$token = $_GET['token'];

// Check if token exists and is valid
$sql = "SELECT * FROM password_reset_tokens WHERE token=? AND expires_at > ?";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, 'si', $token, time());
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if (mysqli_num_rows($result) == 0) {
    die("Invalid or expired token.");
}

// If the token is valid, show the password reset form
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
</head>
<body>
    <div class="container">
        <?php
            if (isset($_POST['submit'])) {
                $password = $_POST['password'];
                $confirm_password = $_POST['confirm_password'];
                
                if ($password !== $confirm_password) {
                    echo "Passwords do not match.";
                } else {
                    // Update the user's password
                    $user_id = $result->fetch_assoc()['user_id'];
                    
                    // Hash the new password
                    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                    
                    $sql = "UPDATE users SET password=? WHERE id=?";
                    $stmt = mysqli_prepare($conn, $sql);
                    mysqli_stmt_bind_param($stmt, 'si', $hashed_password, $user_id);
                    mysqli_stmt_execute($stmt);
                    
                    // Delete the token after use
                    $sql = "DELETE FROM password_reset_tokens WHERE token=?";
                    $stmt = mysqli_prepare($conn, $sql);
                    mysqli_stmt_bind_param($stmt, 's', $token);
                    mysqli_stmt_execute($stmt);
                    
                    echo "Password reset successfully! You can now <a href='login.php'>log in</a>";
                }
            } else {
        ?>
        
        <h2>Reset Password</h2>
        <form action="<?php echo $_SERVER['PHP_SELF'] . '?token=' . $token; ?>" method="POST">
            <label for="password">New Password:</label>
            <input type="password" name="password" id="password">
            
            <label for="confirm_password">Confirm Password:</label>
            <input type="password" name="confirm_password" id="confirm_password">
            
            <button type="submit" name="submit">Reset Password</button>
        </form>
        
        <?php } ?>
    </div>
</body>
</html>


<?php
$host = 'localhost';
$db_user = 'root';
$db_pass = '';
$db_name = 'your_database';

$conn = mysqli_connect($host, $db_user, $db_pass, $db_name);
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Create password_reset_tokens table if it doesn't exist
$sql = "CREATE TABLE IF NOT EXISTS password_reset_tokens (
        id INT AUTO_INCREMENT PRIMARY KEY,
        user_id INT NOT NULL,
        token VARCHAR(255) NOT NULL,
        expires_at INT NOT NULL,
        FOREIGN KEY (user_id) REFERENCES users(id)
    )";
mysqli_query($conn, $sql);
?>


<?php
// Database configuration
$host = 'localhost';
$user = 'root';
$password = '';
$database = 'my_database';

// Connect to database
$conn = new mysqli($host, $user, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Session start
session_start();

// If form submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Get user input
    $email = $_POST['email'];

    // Check if email exists in database
    $sql = "SELECT id, username FROM users WHERE email=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {

        // Generate reset token
        $reset_token = bin2hex(random_bytes(16));

        // Set token expiration time (1 hour from now)
        $expires = date('Y-m-d H:i:s', strtotime('+1 hour'));

        // Update database with reset token and expiration time
        $sql = "UPDATE users SET reset_token=?, expires=? WHERE email=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sss", $reset_token, $expires, $email);
        $stmt->execute();

        // Send password reset email
        $to = $email;
        $subject = "Password Reset Request";
        
        // Email content
        $message = "
            <html>
            <head></head>
            <body>
                <h2>Password Reset</h2>
                <p>Please click the following link to reset your password:</p>
                <a href='http://localhost/reset_password.php?token=$reset_token'>Reset Password</a><br><br>
                <small>This link will expire in 1 hour.</small>
            </body>
            </html>
        ";
        
        // Set headers for email
        $headers = "MIME-Version: 1.0\r
";
        $headers .= "Content-Type: text/html; charset=UTF-8\r
";
        $headers .= "From: webmaster@example.com\r
";

        // Send email
        mail($to, $subject, $message, $headers);

        // Set success message and redirect after 5 seconds
        $_SESSION['message'] = "We've sent a password reset link to your email.";
        header("refresh:5; url=http://localhost/forgot_password.php");
    } else {
        // Email not found in database
        $_SESSION['error'] = "This email address is not registered with us.";
        header("Location: http://localhost/forgot_password.php");
    }
}

// Close database connection
$conn->close();
?>


<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Get token from URL
    $token = $_GET['token'];

    // Get user input
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    // Check if passwords match
    if ($new_password != $confirm_password) {
        $_SESSION['error'] = "Passwords do not match!";
        header("Location: reset_password.php?token=$token");
        exit();
    }

    // Connect to database
    $conn = new mysqli($host, $user, $password, $database);

    // Check token validity
    $sql = "SELECT id, username FROM users WHERE reset_token=? AND expires > NOW()";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {

        // Update password
        $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
        $sql = "UPDATE users SET password=?, reset_token=NULL WHERE reset_token=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $hashed_password, $token);
        $stmt->execute();

        $_SESSION['message'] = "Your password has been reset!";
        header("Location: login.php");
    } else {
        $_SESSION['error'] = "Invalid or expired token!";
        header("Location: forgot_password.php");
    }

    // Close database connection
    $conn->close();
}
?>


<!DOCTYPE html>
<html>
<head>
    <title>Forgot Password</title>
</head>
<body>
    <?php
        $emailError = "";
        
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $email = trim($_POST['email']);
            
            // Check if email is provided and valid
            if (empty($email)) {
                $emailError = "Email is required";
            } else {
                // Proceed to send reset link
                require_once('db_connect.php');
                
                // Prepare SQL query
                $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
                $stmt->bind_param("s", $email);
                $stmt->execute();
                $result = $stmt->get_result();
                
                if ($result->num_rows == 0) {
                    $emailError = "Email not found in our records";
                } else {
                    // Generate a random token
                    $token = bin2hex(random_bytes(16));
                    
                    // Store the token and expiration time in the database
                    $expires = date('Y-m-d H:i:s', strtotime('+30 minutes'));
                    
                    $stmt = $conn->prepare("INSERT INTO password_reset (user_id, token, expires) VALUES (?, ?, ?)");
                    $row = $result->fetch_assoc();
                    $stmt->bind_param("iss", $row['id'], $token, $expires);
                    $stmt->execute();
                    
                    // Send email
                    $resetLink = "http://".$_SERVER['HTTP_HOST']."/reset_password.php?token=".$token;
                    
                    $to = $email;
                    $subject = "Password Reset Request";
                    $message = "Please click the following link to reset your password:

".$resetLink."

This link will expire in 30 minutes.";
                    $headers = "From: yourwebsite@example.com" . "\r
";
                    
                    mail($to, $subject, $message, $headers);
                    
                    echo "<p>Password reset instructions have been sent to your email address.</p>";
                }
            }
        }
    ?>
    
    <h2>Forgot Password</h2>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <?php if ($emailError != "") { ?>
            <div style="color: red;"><?php echo $emailError; ?></div><br>
        <?php } ?>
        Email: <input type="text" name="email" value="<?php echo $_POST['email']; ?>"><br>
        <input type="submit" value="Send Reset Link">
    </form>
</body>
</html>


<?php
session_start();

require_once('db_connect.php');

// Check if token is provided in URL and is valid
if (isset($_GET['token'])) {
    $token = $_GET['token'];
    
    // Get the token details from the database
    $stmt = $conn->prepare("SELECT * FROM password_reset WHERE token = ? AND expires > NOW()");
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows == 0) {
        die("Invalid or expired reset link.");
    } else {
        // Token is valid
        $row = $result->fetch_assoc();
        $_SESSION['reset_user_id'] = $row['user_id'];
        
        // Show password reset form
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $new_password = trim($_POST['new_password']);
            $confirm_password = trim($_POST['confirm_password']);
            
            if ($new_password != $confirm_password) {
                die("Passwords do not match.");
            }
            
            // Minimum password length validation
            if (strlen($new_password) < 6) {
                die("Password must be at least 6 characters long.");
            }
            
            // Hash the new password
            $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
            
            // Update the database
            $stmt = $conn->prepare("UPDATE users SET password = ? WHERE id = ?");
            $stmt->bind_param("si", $hashed_password, $_SESSION['reset_user_id']);
            $stmt->execute();
            
            // Delete the reset token
            $stmt = $conn->prepare("DELETE FROM password_reset WHERE user_id = ?");
            $stmt->bind_param("i", $_SESSION['reset_user_id']);
            $stmt->execute();
            
            echo "Password has been successfully updated. You can now <a href='login.php'>login</a>.";
        } else {
            // Show the form
?>
<!DOCTYPE html>
<html>
<head>
    <title>Reset Password</title>
</head>
<body>
    <h2>Reset Password</h2>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"].'?token='.$token); ?>" method="post">
        New Password: <input type="password" name="new_password"><br>
        Confirm Password: <input type="password" name="confirm_password"><br><br>
        <input type="submit" value="Reset Password">
    </form>
</body>
</html>
<?php
        }
    }
} else {
    die("No token provided.");
}
?>


<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "your_database";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>


// forgot_password.php
<?php
session_start();
include('config.php'); // Include your database configuration file

if (isset($_POST['reset_request'])) {
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    
    if (empty($email)) {
        $error = "Please enter your email address";
    } else {
        // Check if email exists in the database
        $sql = "SELECT * FROM users WHERE email = '$email'";
        $result = mysqli_query($conn, $sql);
        
        if (mysqli_num_rows($result) == 1) {
            // Generate a unique token and expiration time
            $token = bin2hex(random_bytes(32));
            $expires = date('Y-m-d H:i:s', strtotime('+1 hour'));
            
            // Update the user's record with the new token and expiration time
            $update_sql = "UPDATE users SET reset_token = '$token', reset_expires = '$expires' WHERE email = '$email'";
            mysqli_query($conn, $update_sql);
            
            // Send reset password email
            $to = $email;
            $subject = "Password Reset Request";
            $message = "
                <html>
                    <head></head>
                    <body>
                        <p>You have requested to reset your password. Click the link below to set a new password:</p>
                        <a href='http://yourdomain.com/reset_password.php?token=$token'>Reset Password</a><br>
                        <small>This link will expire in 1 hour.</small>
                    </body>
                </html>
            ";
            
            // Set headers for email
            $headers = "MIME-Version: 1.0\r
";
            $headers .= "Content-type: text/html; charset=UTF-8\r
";
            $headers .= "From: yourname@yourdomain.com\r
";
            
            mail($to, $subject, $message, $headers);
            
            // Redirect to password reset confirmation
            header("Location: forgot_password_success.php");
            exit();
        } else {
            $error = "Email address not found in our records";
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Forgot Password</title>
</head>
<body>
    <?php if (isset($error)) { echo "<p style='color:red'>$error</p>"; } ?>
    <h2>Forgot Password</h2>
    <form action="<?php $_PHP_SELF ?>" method="post">
        <input type="email" name="email" placeholder="Enter your email address" required><br><br>
        <input type="submit" name="reset_request" value="Request Reset">
    </form>
</body>
</html>


// reset_password.php
<?php
session_start();
include('config.php');

if (!isset($_GET['token']) || empty($_GET['token'])) {
    header("Location: forgot_password.php");
    exit();
}

$token = mysqli_real_escape_string($conn, $_GET['token']);

$sql = "SELECT * FROM users WHERE reset_token = '$token'";
$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) == 0) {
    // Invalid token
    header("Location: forgot_password.php?error=invalid_token");
    exit();
}

$user = mysqli_fetch_assoc($result);
$expires = $user['reset_expires'];

// Check if the reset link has expired
$current_time = date('Y-m-d H:i:s');
if ($current_time > $expires) {
    // Token has expired, regenerate a new token and send a new email
    $new_token = bin2hex(random_bytes(32));
    $new_expires = date('Y-m-d H:i:s', strtotime('+1 hour'));
    
    $update_sql = "UPDATE users SET reset_token = '$new_token', reset_expires = '$new_expires' WHERE id = {$user['id']}";
    mysqli_query($conn, $update_sql);
    
    // Send new reset password email
    $to = $user['email'];
    $subject = "New Password Reset Request";
    $message = "
        <html>
            <head></head>
            <body>
                <p>Your previous password reset link has expired. Click the new link below to set a new password:</p>
                <a href='http://yourdomain.com/reset_password.php?token=$new_token'>Reset Password</a><br>
                <small>This link will expire in 1 hour.</small>
            </body>
        </html>
    ";
    
    $headers = "MIME-Version: 1.0\r
";
    $headers .= "Content-type: text/html; charset=UTF-8\r
";
    $headers .= "From: yourname@yourdomain.com\r
";
    
    mail($to, $subject, $message, $headers);
    
    header("Location: forgot_password.php?error=expired_token");
    exit();
}

if (isset($_POST['reset_password'])) {
    // Validate and set the new password
    if ($_POST['new_password'] != $_POST['confirm_password']) {
        $error = "Passwords do not match";
    } else {
        $new_password = mysqli_real_escape_string($conn, $_POST['new_password']);
        
        // Update the user's password
        $update_sql = "UPDATE users SET password = '$new_password', reset_token = NULL WHERE id = {$user['id']}";
        mysqli_query($conn, $update_sql);
        
        // Destroy the token after use
        header("Location: login.php?success=1");
        exit();
    }
}

?>

<!DOCTYPE html>
<html>
<head>
    <title>Reset Password</title>
</head>
<body>
    <?php if (isset($error)) { echo "<p style='color:red'>$error</p>"; } ?>
    <h2>Set New Password</h2>
    <form action="<?php $_PHP_SELF ?>?token=<?php echo $token ?>" method="post">
        <input type="password" name="new_password" placeholder="New password" required><br><br>
        <input type="password" name="confirm_password" placeholder="Confirm new password" required><br><br>
        <input type="submit" name="reset_password" value="Set Password">
    </form>
</body>
</html>


<!DOCTYPE html>
<html>
<head>
    <title>Forgot Password</title>
    <!-- Include your CSS -->
    <link rel="stylesheet" type="text/css" href="styles.css">
</head>
<body>
    <div class="container">
        <h2>Forgot Password</h2>
        <form action="reset-password.php" method="post">
            <?php
                // CSRF Token Generation
                session_start();
                if (!isset($_SESSION['token'])) {
                    $_SESSION['token'] = bin_hex(random_bytes(32));
                }
            ?>
            <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['token']; ?>">
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required>
            </div>
            <button type="submit">Reset Password</button>
        </form>
    </div>
</body>
</html>


<?php
session_start();

// Check CSRF token
if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['token']) {
    header("Location: forgot-password.php");
    exit();
}

include 'db_connection.php';

$email = trim($_POST['email']);

// Check if email exists in the database
$sql = "SELECT id FROM users WHERE email = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    header("Location: forgot-password.php?error=no_user");
    exit();
} else {
    // Generate temporary password
    $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*';
    $temp_password = substr(str_shuffle($chars), 0, 12);

    // Hash the temporary password
    $hashed_password = password_hash($temp_password, PASSWORD_BCRYPT);

    // Update the user's password in the database
    $update_sql = "UPDATE users SET password = ? WHERE email = ?";
    $stmt = $conn->prepare($update_sql);
    $stmt->bind_param("ss", $hashed_password, $email);
    $stmt->execute();

    // Send email with temporary password
    $to = $email;
    $subject = 'Password Reset';
    $message = "Your temporary password is: $temp_password
Please change it upon login.";
    $headers = 'From: yoursite@example.com';

    mail($to, $subject, $message, $headers);

    header("Location: forgot-password.php?success=reset");
}

$conn->close();
?>



<?php
$host = 'localhost';
$user = 'root';
$password = '';
$db_name = 'your_database';

$conn = new mysqli($host, $user, $password, $db_name);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>


<?php
// Include necessary files
require_once 'database_connection.php';
require_once 'email_sender.php';

function forgotPassword($email) {
    // Check if email exists in database
    $query = "SELECT id, first_name FROM users WHERE email = ?";
    
    try {
        $stmt = $conn->prepare($query);
        $stmt->execute([$email]);
        
        if ($stmt->rowCount() == 1) {
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            
            // Generate a random token
            $token = bin2hex(random_bytes(32));
            
            // Set the expiration time (e.g., 1 hour from now)
            $expires = date('Y-m-d H:i:s', strtotime('+1 hour'));
            
            // Update the database with the new token and expiration time
            $updateQuery = "UPDATE users SET reset_token = ?, reset_expires = ? WHERE email = ?";
            $updateStmt = $conn->prepare($updateQuery);
            $updateStmt->execute([$token, $expires, $email]);
            
            // Send the password reset link to the user's email
            $resetLink = "http://yourwebsite.com/reset-password.php?token=" . $token;
            $subject = "Password Reset Request";
            $body = "Dear " . $user['first_name'] . ",

You have requested a password reset. Please click on the following link to reset your password:

" . $resetLink . "

This link will expire in 1 hour.

Best regards,
The Team";
            
            sendEmail($email, $subject, $body);
            
            return "Password reset instructions have been sent to your email address.";
        } else {
            return "Email not found in our database. Please check your email and try again.";
        }
    } catch(PDOException $e) {
        return "An error occurred: " . $e->getMessage();
    }
}

// Example usage:
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['email'])) {
        $email = $_POST['email'];
        echo forgotPassword($email);
    }
}
?>


<?php
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    if (isset($_GET['token'])) {
        $token = $_GET['token'];
        
        // Verify token in database and check expiration time
        // If valid, show password reset form
    } else {
        // Invalid or missing token
        die("Invalid request");
    }
} elseif ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['token'], $_POST['password'])) {
        $token = $_POST['token'];
        $newPassword = password_hash($_POST['password'], PASSWORD_DEFAULT);
        
        // Update the user's password in the database
        // Clear or update the reset token and expiration time
        
        // Redirect to login page
    } else {
        die("Invalid request");
    }
}
?>


<?php
session_start();

// Database connection
$host = 'localhost';
$dbUsername = 'root';
$dbPassword = '';
$dbName = 'test';

$conn = new mysqli($host, $dbUsername, $dbPassword, $dbName);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Function to generate random token
function generateToken() {
    return bin2hex(random_bytes(16));
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if email or username is provided
    if (empty($_POST['email']) && empty($_POST['username'])) {
        $error = "Please enter your email or username.";
    } else {
        $email = $_POST['email'];
        $username = $_POST['username'];

        // Prepare and bind
        $stmt = $conn->prepare("SELECT id, email, username FROM users WHERE email=? OR username=?");
        $stmt->bind_param("ss", $email, $username);
        
        if ($stmt->execute()) {
            $result = $stmt->get_result();
            
            if ($result->num_rows > 0) {
                // User exists
                $user = $result->fetch_assoc();
                
                // Generate reset token and expiration time
                $token = generateToken();
                $expirationTime = date('Y-m-d H:i:s', strtotime('+1 hour'));
                
                // Hash the token before storing it in database
                $hashedToken = password_hash($token, PASSWORD_DEFAULT);
                
                // Insert into reset_tokens table
                $resetStmt = $conn->prepare("INSERT INTO reset_tokens (user_id, token, expires) VALUES (?, ?, ?)");
                $resetStmt->bind_param("iss", $user['id'], $hashedToken, $expirationTime);
                
                if ($resetStmt->execute()) {
                    // Send email with reset link
                    $to = $email ?: $username;
                    $subject = "Password Reset Request";
                    $message = "Please click the following link to reset your password:

http://yourdomain.com/reset-password.php?token=$token

This link will expire in 1 hour.";
                    
                    mail($to, $subject, $message);
                    
                    echo "An email has been sent with instructions to reset your password.";
                } else {
                    die("Error storing reset token: " . $conn->error);
                }
            } else {
                $error = "No account found with that email or username.";
            }
        } else {
            die("Database error: " . $conn->error);
        }
    }
}

// Close database connection
$conn->close();
?>


<?php
// Database configuration
$host = 'localhost';
$username_db = 'your_username';
$password_db = 'your_password';
$db_name = 'your_database';

// Create connection
$conn = new mysqli($host, $username_db, $password_db, $db_name);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Function to generate a random password
function generatePassword($length = 10) {
    $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*()';
    $password = substr(str_shuffle($chars), 0, $length);
    return $password;
}

// Function to send email with new password
function sendNewPassword($email, $newPassword) {
    $to = $email;
    $subject = 'Your New Password';
    $message = "Hello,

Your new password is: " . $newPassword . "

Please login and change your password immediately.
";
    
    // Additional headers
    $headers = 'From: webmaster@example.com' . "\r
" .
        'Reply-To: webmaster@example.com' . "\r
" .
        'X-Mailer: PHP/' . phpversion();

    mail($to, $subject, $message, $headers);
}

// Process the form
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    
    // Sanitize input
    $email = mysqli_real_escape_string($conn, $trim(email));
    
    // Check if email exists in database
    $sql = "SELECT * FROM users WHERE email = '$email'";
    $result = $conn->query($sql);
    
    if ($result->num_rows > 0) {
        // Generate new password
        $newPassword = generatePassword();
        
        // Update database with new password
        $hash = password_hash($newPassword, PASSWORD_DEFAULT);
        $updateSql = "UPDATE users SET password = '$hash' WHERE email = '$email'";
        if ($conn->query($updateSql)) {
            // Send email
            sendNewPassword($email, $newPassword);
            
            // Redirect to login page with success message
            header("Location: login.php?msg=Password reset successful. Check your email.");
            exit();
        } else {
            echo "Error updating password: " . $conn->error;
        }
    } else {
        // Email not found
        header("Location: forgot_password.php?error=Email not found in our records.");
        exit();
    }
}
?>

<!-- Forgot Password Form -->
<!DOCTYPE html>
<html>
<head>
    <title>Forgot Password</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #f4f4f4;
        }
        .container {
            max-width: 500px;
            margin: 0 auto;
            background-color: white;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .form-group {
            margin-bottom: 15px;
        }
        input[type="text"] {
            width: 100%;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-sizing: border-box;
        }
        button {
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            width: 100%;
        }
        button:hover {
            background-color: #45a049;
        }
        .message {
            margin-top: 10px;
            color: red;
        }
    </style>
</head>
<body>
    <div class="container">
        <?php
        // Show error message if set in URL parameters
        if (isset($_GET['error'])) {
            echo "<div class='message'>" . $_GET['error'] . "</div>";
        }
        ?>
        
        <h2>Forgot Password</h2>
        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
            <div class="form-group">
                <label for="email">Email or Username:</label><br>
                <input type="text" id="email" name="email" required>
            </div>
            <button type="submit">Reset Password</button>
        </form>
        <p>Don't have an account? <a href="register.php">Register here</a>.</p>
    </div>
</body>
</html>

// Close database connection
$conn->close();
?>


<?php
// index.php - Forgot Password Page

session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];

    // Validate email input
    if (empty($email)) {
        $error = "Please enter your email address.";
    } else {
        // Connect to database
        include('db_connect.php');

        // Prepare SQL statement to check for existing user
        $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows == 0) {
            // No user found with this email
            $error = "No account exists with this email address.";
        } else {
            // Generate a random token for password reset
            $token = bin2hex(random_bytes(16));
            $expires = date('Y-m-d H:i:s', time() + 3600); // Token expires in 1 hour

            // Update user's record with the new token and expiration time
            $updateStmt = $conn->prepare("UPDATE users SET reset_token = ?, reset_expires = ? WHERE email = ?");
            $updateStmt->bind_param("sss", $token, $expires, $email);
            $updateStmt->execute();

            // Send password reset email
            $resetLink = "http://your-website.com/reset_password.php?token=" . $token;
            $to = $email;
            $subject = "Password Reset Request";
            $message = "Please click the following link to reset your password: 
" . $resetLink;
            
            if (mail($to, $subject, $message)) {
                echo "A password reset email has been sent to you. Please check your inbox.";
                header("Refresh:2; url=login.php");
                exit();
            } else {
                $error = "There was an error sending the password reset email.";
            }
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Forgot Password</title>
</head>
<body>
    <?php if (!empty($error)) { echo "<p>$error</p>"; } ?>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        Email: <input type="text" name="email"><br>
        <input type="submit" value="Submit">
    </form>
</body>
</html>

// reset_password.php - Password Reset Page

session_start();

if ($_GET['token']) {
    $token = $_GET['token'];
    
    // Connect to database
    include('db_connect.php');

    // Check if token exists and is valid
    $stmt = $conn->prepare("SELECT id, email FROM users WHERE reset_token = ? AND reset_expires > NOW()");
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 0) {
        // Invalid or expired token
        die("Invalid or expired password reset link. Please request a new one.");
    } else {
        // Show password reset form
        while ($row = $result->fetch_assoc()) {
            $email = $row['email'];
        }
        
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $new_password = $_POST['password'];
            $confirm_password = $_POST['cpassword'];

            // Validate input
            if (empty($new_password) || empty($confirm_password)) {
                $error = "Please fill in all fields.";
            } elseif ($new_password !== $confirm_password) {
                $error = "Passwords do not match.";
            } else {
                // Update user's password
                $hash = password_hash($new_password, PASSWORD_DEFAULT);
                
                $updateStmt = $conn->prepare("UPDATE users SET password = ?, reset_token = NULL, reset_expires = NULL WHERE email = ?");
                $updateStmt->bind_param("ss", $hash, $email);
                $updateStmt->execute();

                // Show success message
                echo "Your password has been updated. <a href='login.php'>Click here to login</a>";
                header("Refresh:2; url=login.php");
                exit();
            }
        }
    }
} else {
    die("No token provided.");
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Reset Password</title>
</head>
<body>
    <?php if (!empty($error)) { echo "<p>$error</p>"; } ?>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>?token=<?php echo $token; ?>" method="post">
        New Password: <input type="password" name="password"><br>
        Confirm Password: <input type="password" name="cpassword"><br>
        <input type="submit" value="Reset Password">
    </form>
</body>
</html>

// db_connect.php - Database Connection

<?php
$servername = "localhost";
$username = "username";
$password = "password";
$dbname = "myDB";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>


<?php
include('db_connection.php'); // Include your database connection

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    
    // Validate email
    if (empty($email)) {
        header("Location: forgot-password-form.php?status=Please enter your email address");
        exit();
    }
    
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        header("Location: forgot-password-form.php?status=Invalid email format");
        exit();
    }
    
    // Check if email exists in the database
    $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows == 0) {
        header("Location: forgot-password-form.php?status=Email not found");
        exit();
    }
    
    // Generate a random token
    $token = bin2hex(openssl_random_pseudo_bytes(16));
    $expires = date('Y-m-d H:i:s', time() + 3600); // Token expires after 1 hour
    
    // Update the database with the token and expiration time
    $updateStmt = $conn->prepare("UPDATE users SET reset_token = ?, reset_expires = ? WHERE email = ?");
    $updateStmt->bind_param("sss", $token, $expires, $email);
    
    if (!$updateStmt->execute()) {
        header("Location: forgot-password-form.php?status=Error resetting password");
        exit();
    }
    
    // Send the password reset link to the user's email
    $to = $email;
    $subject = "Password Reset Request";
    $message = "Please click the following link to reset your password:

";
    $message .= "http://your-website.com/reset-password.php?token=" . $token . "&email=" . urlencode($email);
    $headers = "From: your-website@example.com\r
";
    
    if (mail($to, $subject, $message, $headers)) {
        header("Location: forgot-password-form.php?status=Password reset link sent to your email");
    } else {
        header("Location: forgot-password-form.php?status=Error sending email");
    }
}
?>


<?php
include('db_connection.php'); // Include your database connection

if (isset($_GET['token']) && isset($_GET['email'])) {
    $token = $_GET['token'];
    $email = $_GET['email'];
    
    // Check if token exists and is still valid
    $stmt = $conn->prepare("SELECT id FROM users WHERE reset_token = ? AND email = ? AND reset_expires > NOW()");
    $stmt->bind_param("ss", $token, $email);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows == 0) {
        header("Location: forgot-password-form.php?status=Invalid or expired token");
        exit();
    }
    
    // Show password reset form
    ?>
    <html>
    <head>
        <title>Reset Password</title>
    </head>
    <body>
        <h2>Reset Password</h2>
        <form action="reset-password.php?token=<?php echo $token; ?>&email=<?php echo urlencode($email); ?>" method="post">
            New Password: <input type="password" name="new_password" required><br>
            Confirm Password: <input type="password" name="confirm_password" required><br>
            <button type="submit">Reset Password</button>
        </form>
    </body>
    </html>
    <?php
} else {
    header("Location: forgot-password-form.php?status=Invalid request");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $token = $_GET['token'];
    $email = $_GET['email'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];
    
    if ($new_password != $confirm_password) {
        die("Passwords do not match");
    }
    
    // Update the password
    $stmt = $conn->prepare("UPDATE users SET password = ?, reset_token = NULL, reset_expires = NULL WHERE email = ?");
    $stmt->bind_param("ss", password_hash($new_password, PASSWORD_DEFAULT), $email);
    
    if (!$stmt->execute()) {
        die("Error resetting password");
    }
    
    header("Location: login.php?status=Password reset successfully");
}
?>


<?php
// Database connection details
$host = 'localhost';
$username = 'your_username';
$password = 'your_password';
$database = 'your_database';

// Connect to database
$conn = new mysqli($host, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_POST['email'])) {
    $email = $_POST['email'];
    
    // Validate email format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        header("Location: forgot_password_form.php?error=Invalid%20email%20format");
        exit();
    }
    
    // Check if email exists in database
    $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows == 0) {
        header("Location: forgot_password_form.php?error=Email%20not%20found");
        exit();
    }
    
    // Generate reset token
    $resetToken = bin2hex(random_bytes(16));
    $expires = strtotime('+30 minutes');
    
    // Update database with reset token and expiration time
    $stmt = $conn->prepare("UPDATE users SET reset_token = ?, reset_expires = ? WHERE email = ?");
    $stmt->bind_param("sis", $resetToken, $expires, $email);
    $stmt->execute();
    
    // Send reset password email
    $to = $email;
    $subject = "Reset Your Password";
    $message = "
        <html>
            <body>
                <h2>Forgot Password?</h2>
                <p>Please click the link below to reset your password:</p>
                <a href='http://your_website.com/reset_password.php?id=" . $id . "&token=" . $resetToken . "'>Reset Password</a>
                <br><br>
                <p>If you didn't request a password reset, please ignore this email.</p>
            </body>
        </html>
    ";
    
    $headers = "MIME-Version: 1.0" . "\r
";
    $headers .= "Content-type:text/html;charset=UTF-8" . "\r
";
    $headers .= "From: your_email@example.com" . "\r
";
    
    mail($to, $subject, $message, $headers);
    
    header("Location: forgot_password_form.php?error=Password%20reset%20link%20sent%20to%20your%20email");
    exit();
}
?>


<?php
// Database connection details
$host = 'localhost';
$username = 'your_username';
$password = 'your_password';
$database = 'your_database';

// Connect to database
$conn = new mysqli($host, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_GET['id']) && isset($_GET['token'])) {
    $id = $_GET['id'];
    $token = $_GET['token'];
    
    // Check if token exists and hasn't expired
    $stmt = $conn->prepare("SELECT id, reset_token, reset_expires FROM users WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows == 0) {
        header("Location: forgot_password_form.php?error=Invalid%20link");
        exit();
    }
    
    $row = $result->fetch_assoc();
    if ($row['reset_token'] != $token || $row['reset_expires'] < time()) {
        header("Location: forgot_password_form.php?error=Expired%20or%20invalid%20link");
        exit();
    }
    
} elseif (isset($_POST['id']) && isset($_POST['token'])) {
    $id = $_POST['id'];
    $token = $_POST['token'];
    $newPassword = $_POST['new_password'];
    $confirmPassword = $_POST['confirm_password'];
    
    if ($newPassword != $confirmPassword) {
        header("Location: reset_password.php?id=$id&token=$token&error=Passwords%20do%20not%20match");
        exit();
    }
    
    // Update password
    $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
    
    $stmt = $conn->prepare("UPDATE users SET password = ?, reset_token = NULL WHERE id = ?");
    $stmt->bind_param("si", $hashedPassword, $id);
    $stmt->execute();
    
    // Clear the reset token
    $stmt = $conn->prepare("UPDATE users SET reset_token = NULL, reset_expires = NULL WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    
    header("Location: login.php?success=Password%20reset%20successful");
    exit();
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Forgot Password</title>
</head>
<body>
    <h2>Forgot Password</h2>
    <form action="send_reset_email.php" method="post">
        <label for="email">Email:</label><br>
        <input type="email" id="email" name="email" required><br><br>
        <button type="submit">Request Reset</button>
    </form>
    <?php
    if (isset($_GET['error'])) {
        echo "<p style='color:red;'>" . $_GET['error'] . "</p>";
    }
    ?>
</body>
</html>


<?php
include('db_connection.php');

if (isset($_POST['email'])) {
    $email = $_POST['email'];
    
    // Check if email exists in the database
    $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        // Generate a random token
        $token = bin2hex(random_bytes(16));
        
        // Update the user's reset token and expiration time
        $expires = date('Y-m-d H:i:s', strtotime('+1 hour'));
        $stmt = $conn->prepare("UPDATE users SET reset_token = ?, reset_expires = ? WHERE email = ?");
        $stmt->bind_param("sss", $token, $expires, $email);
        $stmt->execute();

        // Send the reset email
        $reset_link = "http://$_SERVER[HTTP_HOST]/reset_password.php?token=$token";
        
        $to = $email;
        $subject = 'Password Reset Request';
        $message = "
            <html>
                <head></head>
                <body>
                    <p>Please click the link below to reset your password:</p>
                    <a href='$reset_link'>Reset Password</a><br>
                    <small>This link will expire in 1 hour.</small>
                </body>
            </html>
        ";
        $headers = "From: no-reply@yourdomain.com\r
";
        $headers .= "MIME-Version: 1.0\r
";
        $headers .= "Content-Type: text/html; charset=UTF-8\r
";

        mail($to, $subject, $message, $headers);

        echo "<script>alert('Password reset email has been sent!');</script>";
        echo "<script>window.location.href = 'login.php';</script>";
    } else {
        header("Location: forgot_password.php?error=Email%20not%20found.");
    }
}
?>


<?php
include('db_connection.php');

if (!isset($_GET['token'])) {
    header("Location: forgot_password.php?error=No%20token%20provided.");
    exit();
}

$token = $_GET['token'];

// Check if token is valid and not expired
$stmt = $conn->prepare("
    SELECT id, email 
    FROM users 
    WHERE reset_token = ? AND reset_expires > NOW()
");
$stmt->bind_param("s", $token);
$stmt->execute();
$result = $stmt->get_result();

if (!$row = $result->fetch_assoc()) {
    header("Location: forgot_password.php?error=Invalid%20or%20expired%20link.");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Reset Password</title>
</head>
<body>
    <h2>Reset Password</h2>
    <?php
    if (isset($_GET['error'])) {
        echo "<p style='color:red;'>" . $_GET['error'] . "</p>";
    }
    ?>
    <form action="reset_password.php" method="post">
        <input type="hidden" name="token" value="<?php echo $token; ?>">
        <label for="new_password">New Password:</label><br>
        <input type="password" id="new_password" name="new_password" required><br><br>
        <label for="confirm_password">Confirm New Password:</label><br>
        <input type="password" id="confirm_password" name="confirm_password" required><br><br>
        <button type="submit">Reset Password</button>
    </form>
</body>
</html>

<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $token = $_POST['token'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    if ($new_password !== $confirm_password) {
        header("Location: reset_password.php?token=$token&error=Passwords%20do%20not%20match.");
        exit();
    }

    // Check token again (security measure)
    $stmt = $conn->prepare("
        SELECT id, email 
        FROM users 
        WHERE reset_token = ? AND reset_expires > NOW()
    ");
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $result = $stmt->get_result();

    if (!$row = $result->fetch_assoc()) {
        header("Location: forgot_password.php?error=Invalid%20or%20expired%20link.");
        exit();
    }

    // Update the password
    $new_password_hashed = password_hash($new_password, PASSWORD_DEFAULT);
    
    $stmt = $conn->prepare("
        UPDATE users 
        SET password = ?, reset_token = NULL, reset_expires = NULL 
        WHERE id = ?
    ");
    $stmt->bind_param("si", $new_password_hashed, $row['id']);
    $stmt->execute();

    echo "<script>alert('Password has been reset successfully!');</script>";
    echo "<script>window.location.href = 'login.php';</script>";
}
?>


<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "your_database";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>


<?php
// Include database connection
include('db_connect.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get input email or username
    $input = trim($_POST['email_username']);
    
    if (empty($input)) {
        die("Please enter your email or username");
    }
    
    // Prepare SQL statement to check user existence
    $stmt = $conn->prepare("SELECT id, email FROM users WHERE email = ? OR username = ?");
    $stmt->bind_param("ss", $input, $input);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows == 0) {
        die("No account found with that email or username");
    }
    
    // Get user data
    $user = $result->fetch_assoc();
    
    // Generate reset token
    $resetToken = bin2hex(random_bytes(16));
    $tokenExpiry = date('Y-m-d H:i:s', strtotime('+1 hour'));
    
    // Store token in database
    $stmt = $conn->prepare("UPDATE users SET reset_token = ?, reset_expiry = ? WHERE id = ?");
    $stmt->bind_param("sss", $resetToken, $tokenExpiry, $user['id']);
    if (!$stmt->execute()) {
        die("An error occurred. Please try again later");
    }
    
    // Send reset email
    $to = $user['email'];
    $subject = "Password Reset Request";
    $message = "Please click the following link to reset your password:

";
    $message .= "http://yourwebsite.com/reset-password.php?token=" . $resetToken;
    $headers = "From: no-reply@yourwebsite.com" . "\r
";
    
    if (!mail($to, $subject, $message, $headers)) {
        die("Error sending email. Please try again later");
    }
    
    echo "A password reset link has been sent to your email address.";
}
?>


<?php
// This code should be added to reset-password.php after verifying the token

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $newPassword = trim($_POST['new_password']);
    
    if (empty($newPassword)) {
        die("Please enter a new password");
    }
    
    // Hash the new password
    $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
    
    // Update password in database
    $stmt = $conn->prepare("UPDATE users SET password = ?, reset_token = NULL, reset_expiry = NULL WHERE id = ?");
    $stmt->bind_param("si", $hashedPassword, $user['id']);
    
    if (!$stmt->execute()) {
        die("An error occurred. Please try again");
    }
    
    echo "Your password has been updated successfully!";
}
?>


<?php
// forgot_password.php

require 'database_connection.php'; // Include your database connection file

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];

    // Check if email exists in the database
    $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        echo "Email not registered!";
        exit();
    }

    // Generate a random token
    $token = bin2hex(random_bytes(16));
    $expires = date("Y-m-d H:i:s", time() + 3600); // Token expires in 1 hour

    // Insert the token into the database
    $stmt = $conn->prepare("INSERT INTO password_reset (user_id, token, expires) 
                           SELECT id, ?, ? FROM users WHERE email = ?");
    $stmt->bind_param("sss", $token, $expires, $email);
    $result = $stmt->execute();

    if (!$result) {
        die("Error: " . $conn->error);
    }

    // Send reset email
    $to = $email;
    $subject = "Password Reset Request";
    
    $headers = "From: your_email@example.com\r
";
    $headers .= "Reply-To: your_email@example.com\r
";
    $headers .= "X-Mailer: PHP/" . phpversion();

    $message = "To reset your password, click the following link:

";
    $message .= "http://yourwebsite.com/reset_password.php?token=" . urlencode($token) . "

";
    $message .= "If you did not request a password reset, please ignore this email.";

    if (mail($to, $subject, $message, $headers)) {
        echo "An email has been sent to your address. Please check your inbox.";
    } else {
        echo "There was an error sending the email!";
    }

    // Close database connection
    $conn->close();
}


<?php
// reset_password.php

require 'database_connection.php'; // Include your database connection file

session_start();

if (isset($_GET['token'])) {
    $token = $_GET['token'];

    // Check if token exists in the database and hasn't expired
    $stmt = $conn->prepare("SELECT user_id, email FROM password_reset 
                           WHERE token = ? AND expires > NOW()");
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        die("Invalid or expired reset link!");
    }

    // Store user data in session
    $row = $result->fetch_assoc();
    $_SESSION['reset_user_id'] = $row['user_id'];
    $_SESSION['reset_email'] = $row['email'];

    // Close database connection
    $conn->close();

    // Redirect to password reset form
    header("Location: change_password.php");
} else {
    die("No token provided!");
}


<?php
// change_password.php

require 'database_connection.php'; // Include your database connection file

session_start();

if (isset($_SESSION['reset_user_id']) && isset($_POST['new_password'])) {
    $userId = $_SESSION['reset_user_id'];
    $newPassword = password_hash($_POST['new_password'], PASSWORD_DEFAULT);

    // Update the user's password
    $stmt = $conn->prepare("UPDATE users SET password = ? WHERE id = ?");
    $stmt->bind_param("si", $newPassword, $userId);
    $result = $stmt->execute();

    if ($result) {
        // Clear the session variables and redirect to login page
        unset($_SESSION['reset_user_id']);
        unset($_SESSION['reset_email']);
        header("Location: login.php");
    } else {
        die("Error updating password!");
    }

    // Close database connection
    $conn->close();
} else {
    die("Invalid request!");
}


<?php
// Database connection details
$host = 'localhost';
$dbUsername = 'root';
$dbPassword = '';
$dbName = 'mydatabase';

// Connect to database
$conn = new mysqli($host, $dbUsername, $dbPassword, $dbName);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$emailOrUsername = $_POST['email_or_username'];

// Check if the email or username exists in the database
$sql = "SELECT * FROM users WHERE email='$emailOrUsername' OR username='$emailOrUsername'";
$result = $conn->query($sql);

if ($result->num_rows == 0) {
    die("Email or username does not exist.");
}

$row = $result->fetch_assoc();

// Generate a random token
$token = bin2hex(random_bytes(16));

// Store the token in the database with an expiration time (e.g., 1 hour)
$currentDate = date('Y-m-d H:i:s');
$expirationDate = date('Y-m-d H:i:s', strtotime($currentDate . ' + 1 hour'));

$sql = "UPDATE users SET reset_token='$token', token_expiration='$expirationDate' WHERE id=" . $row['id'];
$conn->query($sql);

// Send email with the reset link
$to = $emailOrUsername;
$subject = "Password Reset Request";
$message = "Please click the following link to reset your password: http://example.com/reset_password.php?token=$token";
$headers = "From: webmaster@example.com";

mail($to, $subject, $message, $headers);

echo "An email with a reset link has been sent to you.";

$conn->close();
?>


<?php
session_start();

// Check if token is provided
if (!isset($_GET['token'])) {
    die("Invalid request.");
}

$token = $_GET['token'];

// Database connection details
$host = 'localhost';
$dbUsername = 'root';
$dbPassword = '';
$dbName = 'mydatabase';

$conn = new mysqli($host, $dbUsername, $dbPassword, $dbName);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if the token exists and is valid
$sql = "SELECT * FROM users WHERE reset_token='$token' AND token_expiration > NOW()";
$result = $conn->query($sql);

if ($result->num_rows == 0) {
    die("Invalid or expired token.");
}

// Show password reset form
?>

<!DOCTYPE html>
<html>
<head>
    <title>Reset Password</title>
</head>
<body>
    <h2>Reset Password</h2>
    <form action="reset_password.php" method="post">
        <input type="password" name="new_password" placeholder="Enter new password" required>
        <br><br>
        <input type="password" name="confirm_password" placeholder="Confirm new password" required>
        <br><br>
        <button type="submit">Reset Password</button>
    </form>
</body>
</html>

<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $newPassword = $_POST['new_password'];
    $confirmPassword = $_POST['confirm_password'];

    if ($newPassword !== $confirmPassword) {
        die("Passwords do not match.");
    }

    // Update the password in the database
    $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
    $sql = "UPDATE users SET password='$hashedPassword', reset_token='', token_expiration='' WHERE reset_token='$token'";
    if ($conn->query($sql)) {
        echo "Password has been reset successfully!";
    } else {
        die("Error resetting password.");
    }
}

$conn->close();
?>


<?php
// Database configuration
$DB_HOST = 'localhost';
$DB_USER = 'username';
$DB_PASS = 'password';
$DB_NAME = 'database_name';

// Connect to database
$conn = new mysqli($DB_HOST, $DB_USER, $DB_PASS, $DB_NAME);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

function sendPasswordResetEmail($email, $token) {
    $to = $email;
    $subject = 'Password Reset Request';
    $message = '
        <html>
            <body>
                <h2>Password Reset</h2>
                <p>Please click the link below to reset your password:</p>
                <a href="http://yourwebsite.com/reset-password.php?token=' . $token . '">Reset Password</a>
                <br><br>
                <p>If you did not request this password reset, please ignore this email.</p>
            </body>
        </html>';
    $headers = "MIME-Version: 1.0" . "\r
";
    $headers .= "Content-type:text/html;charset=UTF-8" . "\r
";
    
    mail($to, $subject, $message, $headers);
}

function forgotPassword($email) {
    global $conn;
    
    // Check if email exists in database
    $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows === 0) {
        return "Email not found in our database!";
    }
    
    // Generate reset token
    $token = bin2hex(mt_rand());
    $expires = date('Y-m-d H:i:s', time() + 3600); // Token expires after 1 hour
    
    // Update the reset token and expiration time in the database
    $stmt = $conn->prepare("UPDATE users SET reset_token = ?, reset_expires = ? WHERE email = ?");
    $stmt->bind_param("sss", $token, $expires, $email);
    $stmt->execute();
    
    // Send password reset link to user's email
    sendPasswordResetEmail($email, $token);
    
    return "Password reset instructions have been sent to your email!";
}

function validateResetToken($token) {
    global $conn;
    
    // Check if token exists and hasn't expired
    $stmt = $conn->prepare("SELECT id, email FROM users WHERE reset_token = ? AND reset_expires > ?");
    $stmt->bind_param("ss", $token, date('Y-m-d H:i:s'));
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows === 0) {
        return false;
    }
    
    return $result->fetch_assoc();
}

function resetPassword($token, $newPassword) {
    global $conn;
    
    // Validate token
    $user = validateResetToken($token);
    if (!$user) {
        return "Invalid or expired reset link!";
    }
    
    // Hash the new password
    $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
    
    // Update the user's password in the database
    $stmt = $conn->prepare("UPDATE users SET password = ?, reset_token = NULL, reset_expires = NULL WHERE email = ?");
    $stmt->bind_param("ss", $hashedPassword, $user['email']);
    $stmt->execute();
    
    return "Your password has been successfully reset!";
}

// Example usage:
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $result = forgotPassword($email);
    echo $result;
}

// Reset password example
if (isset($_GET['token'])) {
    if (!empty($_POST)) {
        $newPassword = $_POST['password'];
        $result = resetPassword($_GET['token'], $newPassword);
        echo $result;
    }
}
?>


<?php
session_start();

// Database connection details
$host = 'localhost';
$user = 'root';
$password = '';
$dbname = 'mydatabase';

// Connect to database
$conn = new mysqli($host, $user, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the email from POST request
    $email = $_POST['email'];
    
    // Validate email format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['error'] = "Invalid email format!";
        header("Location: forgot_password.html");
        exit();
    }

    // Check if the email exists in the database
    $sql = "SELECT id FROM users WHERE email=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 0) {
        $_SESSION['error'] = "Email not found in our records!";
        header("Location: forgot_password.html");
        exit();
    }

    // Generate a unique token for password reset
    $token = bin2hex(random_bytes(16));
    $expires = date('Y-m-d H:i:s', time() + 3600); // Token expires after 1 hour

    // Insert the token into the database
    $sql = "INSERT INTO password_resets (user_id, token, created_at, expires_at) 
            VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $row = $result->fetch_assoc();
    $stmt->bind_param("ssss", $row['id'], $token, date('Y-m-d H:i:s'), $expires);

    if (!$stmt->execute()) {
        $_SESSION['error'] = "An error occurred. Please try again later!";
        header("Location: forgot_password.html");
        exit();
    }

    // Send email with reset link
    $to = $email;
    $subject = "Password Reset Request";
    $message = "
        <html>
            <head>
                <title>Password Reset</title>
            </head>
            <body>
                <h2>Password Reset Request</h2>
                <p>Please click the following link to reset your password:</p>
                <a href='http://localhost/reset_password.php?token=$token'>Reset Password</a>
                <p>If you did not request a password reset, please ignore this email.</p>
            </body>
        </html>
    ";
    $headers = "MIME-Version: 1.0" . "\r
";
    $headers .= "Content-type:text/html;charset=UTF-8" . "\r
";
    $headers .= "From: <your-email@example.com>" . "\r
";

    if (mail($to, $subject, $message, $headers)) {
        $_SESSION['success'] = "A password reset link has been sent to your email!";
        header("Location: forgot_password.html");
    } else {
        $_SESSION['error'] = "An error occurred while sending the email. Please try again later!";
        header("Location: forgot_password.html");
    }
}

$conn->close();
?>


<?php
session_start();

// Database connection details
$host = 'localhost';
$user = 'root';
$password = '';
$dbname = 'mydatabase';

// Connect to database
$conn = new mysqli($host, $user, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_GET['token'])) {
    // Validate the token
    $token = $_GET['token'];
    
    $sql = "SELECT * FROM password_resets WHERE token=? AND expires_at > NOW()";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 0) {
        $_SESSION['error'] = "Invalid or expired token!";
        header("Location: forgot_password.html");
        exit();
    }

    // Get user ID from the token
    $row = $result->fetch_assoc();
    $user_id = $row['user_id'];
} else {
    $_SESSION['error'] = "No token provided!";
    header("Location: forgot_password.html");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get new password and confirm
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    if ($password != $confirm_password) {
        $_SESSION['error'] = "Passwords do not match!";
        header("Location: reset_password.php?token=$token");
        exit();
    }

    // Update the password in the database
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    $sql = "UPDATE users SET password=? WHERE id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("si", $hashed_password, $user_id);

    if (!$stmt->execute()) {
        $_SESSION['error'] = "An error occurred while updating the password!";
        header("Location: reset_password.php?token=$token");
        exit();
    }

    // Delete the token after use
    $sql = "DELETE FROM password_resets WHERE token=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $token);
    $stmt->execute();

    $_SESSION['success'] = "Your password has been reset successfully!";
    header("Location: login.php");
}

$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Reset Password</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #f0f0f0;
        }
        .container {
            max-width: 500px;
            margin: 0 auto;
            background-color: white;
            padding: 30px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .form-group {
            margin-bottom: 20px;
        }
        input {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-sizing: border-box;
        }
        button {
            background-color: #4CAF50;
            color: white;
            padding: 14px 20px;
            margin: 8px 0;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            width: 100%;
        }
        button:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
    <div class="container">
        <?php
        if (isset($_SESSION['error'])) {
            echo "<p style='color: red; margin-bottom: 10px;'>$_SESSION[error]</p>";
            unset($_SESSION['error']);
        }
        ?>
        <h2>Reset Password</h2>
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
            <input type="hidden" name="token" value="<?php echo $token; ?>">
            <div class="form-group">
                <label for="password">New Password:</label>
                <input type="password" id="password" name="password" required>
            </div>
            <div class="form-group">
                <label for="confirm_password">Confirm New Password:</label>
                <input type="password" id="confirm_password" name="confirm_password" required>
            </div>
            <button type="submit">Reset Password</button>
        </form>
    </div>
</body>
</html>


<?php
// Database configuration
$host = 'localhost';
$dbname = 'mydatabase';
$username = 'root';
$password = '';

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get email from form
    $email = $_POST['email'];

    // Check if email exists in database
    $stmt = $conn->prepare("SELECT * FROM users WHERE email = :email");
    $stmt->execute(['email' => $email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        // Generate random token
        $token = bin2hex(random_bytes(32));

        // Store token in database with expiration time (e.g., 1 hour)
        $expiration_time = date('Y-m-d H:i:s', strtotime('+1 hour'));
        
        $stmt = $conn->prepare("UPDATE users SET reset_token = :token, reset_expiry = :expiry WHERE email = :email");
        $stmt->execute([
            'token' => $token,
            'expiry' => $expiration_time,
            'email' => $email
        ]);

        // Send password reset email
        $to = $email;
        $subject = "Password Reset Request";
        
        $message = "<html>
                    <head>
                        <title>Password Reset</title>
                    </head>
                    <body>
                        <h2>Password Reset Request</h2>
                        <p>Hello,</p>
                        <p>We received a password reset request for your account. Click the link below to reset your password:</p>
                        <a href='http://example.com/reset-password.php?token=$token'>Reset Password</a>
                        <p>If you did not request this password reset, please ignore this email.</p>
                        <p>This link will expire in 1 hour.</p>
                    </body>
                   </html>";
        
        $headers = "MIME-Version: 1.0" . "\r
";
        $headers .= "Content-type:text/html;charset=UTF-8" . "\r
";
        $headers .= "From: example@example.com" . "\r
";

        mail($to, $subject, $message, $headers);
        
        echo "Password reset email has been sent to your email address.";
    } else {
        echo "Email not found in our records. Please try again.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Forgot Password</title>
</head>
<body>
    <h2>Forgot Password</h2>
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        <label>Email:</label><br>
        <input type="email" name="email" required><br><br>
        <input type="submit" value="Request Password Reset">
    </form>
</body>
</html>


<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "GET") {
    $token = $_GET['token'];

    // Check if token exists and is valid
    $stmt = $conn->prepare("SELECT * FROM users WHERE reset_token = :token AND reset_expiry > NOW()");
    $stmt->execute(['token' => $token]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        $_SESSION['reset_token'] = $token;
        // Display password reset form
        ?>
        <!DOCTYPE html>
        <html>
        <head>
            <title>Reset Password</title>
        </head>
        <body>
            <h2>Reset Password</h2>
            <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                <label>New Password:</label><br>
                <input type="password" name="new_password" required><br><br>
                <label>Confirm New Password:</label><br>
                <input type="password" name="confirm_password" required><br><br>
                <input type="submit" value="Reset Password">
            </form>
        </body>
        </html>
        <?php
    } else {
        echo "Invalid or expired token. Please request a new password reset.";
        // Redirect to forgot password page after some time
        header("Refresh: 3; url=forgot-password.php");
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_SESSION['reset_token'])) {
        $token = $_SESSION['reset_token'];
        
        $new_password = $_POST['new_password'];
        $confirm_password = $_POST['confirm_password'];

        if ($new_password == $confirm_password) {
            // Hash the new password
            $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

            // Update password in database
            $stmt = $conn->prepare("UPDATE users SET password = :password, reset_token = NULL, reset_expiry = NULL WHERE reset_token = :token");
            $stmt->execute([
                'password' => $hashed_password,
                'token' => $token
            ]);

            // Clear session token and redirect to login page
            unset($_SESSION['reset_token']);
            header("Location: login.php");
        } else {
            echo "Passwords do not match. Please try again.";
        }
    } else {
        echo "Invalid request. Please start over.";
    }
}
?>


<?php
session_start();

// Database connection details
$host = 'localhost';
$username_db = 'root';
$password_db = '';
$db_name = 'your_database';

// Create database connection
$conn = new mysqli($host, $username_db, $password_db, $db_name);

// Check if the form is submitted
if (isset($_POST['reset'])) {
    // Get user email/username from input
    $email = $_POST['email'];
    
    try {
        // Query to check if email exists in database
        $stmt = $conn->prepare("SELECT * FROM users WHERE email=?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows == 1) {
            // Generate temporary password
            $alphabet = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890!@#$%^&*()';
            $temp_pass = substr(str_shuffle($alphabet), 0, 12);
            
            // Hash the temporary password before storing it in database
            $hashed_temp_pass = password_hash($temp_pass, PASSWORD_DEFAULT);
            
            // Update user's password with temporary password
            $update_stmt = $conn->prepare("UPDATE users SET password=? WHERE email=?");
            $update_stmt->bind_param("ss", $hashed_temp_pass, $email);
            $update_stmt->execute();
            
            // Send email to user with temporary password
            $to = $email;
            $subject = 'Your Temporary Password';
            $message = "Dear User,

Your temporary password is: $temp_pass

Please login and change your password immediately.

Best regards,
Your Website Team";
            $headers = 'From: noreply@yourwebsite.com' . "\r
" .
                       'Reply-To: noreply@yourwebsite.com' . "\r
" .
                       'X-Mailer: PHP/' . phpversion();
            
            mail($to, $subject, $message, $headers);
            
            // Redirect to login page with success message
            header("Location: login.php?success=1");
            exit();
        } else {
            // Email doesn't exist in database
            $_SESSION['error'] = "Email address not found in our records!";
            header("Location: forgot_password.php");
            exit();
        }
    } catch (mysqli_sql_exception $e) {
        // Handle any database errors
        $_SESSION['error'] = "An error occurred while processing your request. Please try again later.";
        header("Location: forgot_password.php");
        exit();
    }
}

// Close database connection
$conn->close();

?>

<!DOCTYPE html>
<html>
<head>
    <title>Forgot Password</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #f5f5f5;
        }
        .container {
            max-width: 400px;
            margin: 0 auto;
            background-color: white;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .form-group {
            margin-bottom: 15px;
        }
        input {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-sizing: border-box;
        }
        button {
            width: 100%;
            padding: 10px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        button:hover {
            background-color: #45a049;
        }
        .alert {
            padding: 10px;
            margin-bottom: 20px;
            border-radius: 5px;
        }
        .alert-danger {
            background-color: #f2d7d5;
            color: #c72526;
            border: 1px solid #b91c18;
        }
        .alert-success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #103c12;
        }
    </style>
</head>
<body>
    <div class="container">
        <?php
        if (isset($_SESSION['error'])) {
            echo '<div class="alert alert-danger">' . $_SESSION['error'] . '</div>';
            unset($_SESSION['error']);
        } elseif (isset($_GET['success']) && $_GET['success'] == 1) {
            echo '<div class="alert alert-success">A temporary password has been sent to your email address!</div>';
        }
        ?>
        <h2>Forgot Password</h2>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group">
                <input type="email" name="email" placeholder="Enter your email or username" required>
            </div>
            <button type="submit" name="reset">Reset Password</button>
        </form>
    </div>
</body>
</html>


<?php
session_start();
include('db_connection.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = mysqli_real_escape_string($conn, $_POST['email']);

    // Check if email exists in the database
    $query = "SELECT id FROM users WHERE email='$email'";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) == 1) {
        // Generate a unique token
        $token = bin2hex(random_bytes(32));
        
        // Calculate expiration time (e.g., 1 hour)
        $expires = date('Y-m-d H:i:s', strtotime('+1 hour'));

        // Update the database with the new token and expiration
        $update_query = "UPDATE users SET reset_token='$token', reset_expires='$expires' WHERE email='$email'";
        if (mysqli_query($conn, $update_query)) {
            // Send the reset link to the user's email
            $reset_link = "http://".$_SERVER['HTTP_HOST']."/reset_password.php?token=".$token;
            $subject = "Password Reset Request";
            $message = "Please click on this link to reset your password: ".$reset_link;

            if (mail($email, $subject, $message)) {
                echo "<h3>Reset link has been sent to your email.</h3>";
            } else {
                echo "<h3>Error sending email. Please try again later.</h3>";
            }
        } else {
            echo "<h3>Error resetting password. Please try again later.</h3>";
        }
    } else {
        echo "<h3>Email not found in our records.</h3>";
    }
}
?>


<?php
session_start();
include('db_connection.php');

if (isset($_GET['token'])) {
    $token = mysqli_real_escape_string($conn, $_GET['token']);
    
    // Check if token exists and hasn't expired
    $query = "SELECT id FROM users WHERE reset_token='$token' AND reset_expires > NOW()";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) == 1) {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            // Validate and update the password
            $new_password = mysqli_real_escape_string($conn, $_POST['password']);
            $confirm_password = mysqli_real_escape_string($conn, $_POST['confirm']);

            if ($new_password != $confirm_password) {
                die("Passwords do not match.");
            }

            $user_id = mysqli_fetch_assoc($result)['id'];
            // Update the password and reset token
            $update_query = "UPDATE users SET password='" . md5($new_password) . "', reset_token='', reset_expires='' WHERE id=$user_id";
            if (mysqli_query($conn, $update_query)) {
                echo "<h3>Password has been successfully updated!</h3>";
            } else {
                echo "<h3>Error updating password. Please try again.</h3>";
            }
        } else {
            // Display the reset form
?>
            <h2>Reset Password</h2>
            <form action="reset_password.php?token=<?php echo $token; ?>" method="post">
                New Password: <input type="password" name="password" required><br>
                Confirm Password: <input type="password" name="confirm" required><br><br>
                <button type="submit">Change Password</button>
            </form>
<?php
        }
    } else {
        echo "<h3>Invalid or expired token.</h3>";
    }
} else {
    echo "<h3>No token provided.</h3>";
}
?>


<?php
// Configuration
$host = 'localhost';
$dbname = 'your_database_name';
$username = 'root';
$password = '';

// Database connection
$conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Error reporting and redirects
function redirect($url) {
    header("Location: $url");
    die();
}

function showError($error) {
    echo "<div class='alert alert-danger'>$error</div>";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Forgot Password</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>

<?php
if (isset($_POST['reset'])) {
    $email = $_POST['email'];

    // Validate email input
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        showError("Invalid email format");
        exit();
    }

    try {
        // Check if the email exists in the database
        $stmt = $conn->prepare("SELECT id FROM users WHERE email = :email");
        $stmt->bindParam(':email', $email);
        $stmt->execute();

        if ($stmt->rowCount() == 0) {
            showError("No account found with this email address");
            exit();
        }

        // Generate reset token and expiration time
        $resetToken = bin2hex(random_bytes(16));
        $expires = date('Y-m-d H:i:s', strtotime('+1 hour'));

        // Update the database with the reset token and expiration time
        $stmt = $conn->prepare("UPDATE users SET reset_token = :token, reset_expires = :expires WHERE email = :email");
        $stmt->bindParam(':token', $resetToken);
        $stmt->bindParam(':expires', $expires);
        $stmt->bindParam(':email', $email);
        $stmt->execute();

        // Send the reset link to the user's email
        $to = $email;
        $subject = "Password Reset Request";
        $message = "
            <html>
                <head>
                    <title>Password Reset</title>
                </head>
                <body>
                    <h2>Password Reset Request</h2>
                    <p>We received a password reset request for your account. Click the link below to reset your password:</p>
                    <a href='http://localhost/reset_password.php?token=$resetToken&id=" . $stmt->fetch(PDO::FETCH_ASSOC)['id'] . "'>Reset Password</a>
                    <p>If you did not request this password reset, please ignore this email.</p>
                    <p>This link will expire in 1 hour.</p>
                </body>
            </html>";
        $headers = "MIME-Version: 1.0\r
";
        $headers .= "Content-Type: text/html; charset=UTF-8\r
";
        $headers .= "From: noreply@yourdomain.com\r
";

        mail($to, $subject, $message, $headers);

        // Redirect back to forgot password page with success message
        redirect("forgot_password.php?success=true");

    } catch(PDOException $e) {
        showError("Error occurred while processing your request. Please try again.");
    }
}
?>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-4">
            <h2>Forgot Password</h2>
            <?php if (isset($_GET['success'])) { ?>
                <div class="alert alert-success">
                    An email has been sent to you with instructions to reset your password.
                </div>
            <?php } ?>

            <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                <div class="form-group">
                    <label for="email">Email address</label>
                    <input type="email" class="form-control" id="email" name="email" required>
                </div>
                <button type="submit" name="reset" class="btn btn-primary">Reset Password</button>
            </form>

            <p class="mt-3">Remember your password? <a href="login.php">Login here</a></p>
        </div>
    </div>
</div>

</body>
</html>

<?php
// Close database connection
$conn = null;
?>



<?php
session_start();

// Configuration
$host = 'localhost';
$dbname = 'your_database_name';
$username = 'root';
$password = '';

// Database connection
$conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

function redirect($url) {
    header("Location: $url");
    die();
}

function showError($error) {
    echo "<div class='alert alert-danger'>$error</div>";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Reset Password</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>

<?php
if (!isset($_GET['token']) || !isset($_GET['id'])) {
    showError("Invalid reset link");
    exit();
}

$token = $_GET['token'];
$id = $_GET['id'];

try {
    // Check if the token is valid and not expired
    $stmt = $conn->prepare("
        SELECT id, email 
        FROM users 
        WHERE reset_token = :token 
        AND reset_expires > NOW() 
        AND id = :id");
    $stmt->bindParam(':token', $token);
    $stmt->bindParam(':id', $id);
    $stmt->execute();

    if ($stmt->rowCount() == 0) {
        showError("Invalid or expired reset link");
        exit();
    }

    // If the token is valid, show the password reset form
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
} catch(PDOException $e) {
    showError("Error occurred while processing your request. Please try again.");
}

if (isset($_POST['new_password'])) {
    $newPassword = $_POST['new_password'];
    
    if ($newPassword == '') {
        showError("Please enter a new password");
        exit();
    }

    // Update the user's password
    $stmt = $conn->prepare("
        UPDATE users 
        SET password = :password, reset_token = NULL, reset_expires = NULL 
        WHERE id = :id");
    $stmt->bindParam(':password', password_hash($newPassword, PASSWORD_DEFAULT));
    $stmt->bindParam(':id', $id);
    $stmt->execute();

    // Redirect to success page
    redirect("login.php?message=Password%20reset%20successful");
}
?>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-4">
            <h2>Reset Password</h2>
            <?php if (isset($_GET['success'])) { ?>
                <div class="alert alert-success">
                    Your password has been reset successfully!
                </div>
            <?php } ?>

            <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                <input type="hidden" name="id" value="<?php echo $id; ?>">
                <div class="form-group">
                    <label for="new_password">New Password</label>
                    <input type="password" class="form-control" id="new_password" name="new_password" required>
                </div>
                <button type="submit" class="btn btn-primary">Reset Password</button>
            </form>

            <p class="mt-3">Return to login page? <a href="login.php">Login here</a></p>
        </div>
    </div>
</div>

</body>
</html>

<?php
// Close database connection
$conn = null;
?>


<?php
session_start();

// Database connection parameters
$host = 'localhost';
$dbUsername = 'root';
$dbPassword = '';
$dbName = 'mydatabase';

// Create database connection
$conn = new mysqli($host, $dbUsername, $dbPassword, $dbName);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// If form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['reset'])) {
        // Email provided by user
        $email = $_POST['email'];
        
        // Check if email exists in database
        $sql = "SELECT * FROM users WHERE email=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            // Generate random token
            $token = md5(uniqid(rand(), true));
            
            // Set token expiration time (1 hour)
            $expires = date("Y-m-d H:i:s", time() + 3600);
            
            // Update the database with the new token and expiration time
            $updateSql = "UPDATE users SET reset_token=?, reset_expires=? WHERE email=?";
            $updateStmt = $conn->prepare($updateSql);
            $updateStmt->bind_param("sss", $token, $expires, $email);
            $updateStmt->execute();
            
            // Send email with password reset link
            $to = $email;
            $subject = "Password Reset Request";
            $message = "
                <html>
                    <head></head>
                    <body>
                        <p>Hello,</p>
                        <p>A password reset request was received for your account. Click the link below to reset your password:</p>
                        <a href='http://example.com/reset-password.php?token=$token'>Reset Password</a>
                        <p>If you did not request this password reset, please ignore this email.</p>
                        <p>This link will expire in 1 hour.</p>
                    </body>
                </html>
            ";
            $headers = "MIME-Version: 1.0" . "\r
";
            $headers .= "Content-type:text/html;charset=UTF-8" . "\r
";
            $headers .= "From: noreply@example.com" . "\r
";

            if (mail($to, $subject, $message, $headers)) {
                echo "<script>alert('Password reset link has been sent to your email.');</script>";
            } else {
                echo "<script>alert('Failed to send password reset link. Please try again.');</script>";
            }
        } else {
            echo "<script>alert('Email not found in our records.');</script>";
        }
    }
}

// Reset password form
echo '
<!DOCTYPE html>
<html>
<head>
    <title>Forgot Password</title>
</head>
<body>
    <h2>Forgot Password</h2>
    <form method="POST" action="">
        <input type="email" name="email" placeholder="Enter your email" required><br><br>
        <button type="submit" name="reset">Reset Password</button>
    </form>
</body>
</html>
';

// Close database connection
$conn->close();
?>



<?php
session_start();

// Database connection parameters
$host = 'localhost';
$dbUsername = 'root';
$dbPassword = '';
$dbName = 'mydatabase';

// Create database connection
$conn = new mysqli($host, $dbUsername, $dbPassword, $dbName);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_GET['token'])) {
    $token = $_GET['token'];
    
    // Check if token exists and is valid
    $sql = "SELECT * FROM users WHERE reset_token=? AND reset_expires > NOW()";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        // Show password reset form
        echo '
            <!DOCTYPE html>
            <html>
            <head>
                <title>Reset Password</title>
            </head>
            <body>
                <h2>Reset Password</h2>
                <form method="POST" action="">
                    <input type="password" name="new_password" placeholder="Enter new password" required><br><br>
                    <button type="submit" name="change">Change Password</button>
                </form>
            ';
    } else {
        echo "<script>alert('Invalid or expired reset link. Please request a new password reset.'); window.location.href='forgot-password.php';</script>";
    }
} elseif (isset($_POST['change'])) {
    $token = $_GET['token'];
    
    // Update user's password
    $newPassword = password_hash($_POST['new_password'], PASSWORD_DEFAULT);
    
    $sql = "UPDATE users SET password=?, reset_token=NULL, reset_expires=NULL WHERE reset_token=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $newPassword, $token);
    $stmt->execute();
    
    if ($stmt->affected_rows > 0) {
        echo "<script>alert('Password has been updated!'); window.location.href='login.php';</script>";
    } else {
        echo "<script>alert('Failed to update password. Please try again.'); window.location.href='reset-password.php?token=$token';</script>";
    }
} else {
    // Invalid access
    echo "<script>alert('Invalid request. Please use the reset link from your email.'); window.location.href='forgot-password.php';</script>";
}

// Close database connection
$conn->close();
?>



<?php
// Database configuration
$host = 'localhost';
$username = 'root';
$password = '';
$dbname = 'your_database';

try {
    // Create database connection
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $email = $_POST['email'];
        
        // Check if email exists
        $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->execute([$email]);
        
        if ($stmt->rowCount() == 0) {
            die("Email not found. Please try again.");
        }
        
        // Generate a random token
        $token = bin2hex(random_bytes(16));
        $expires = time() + 3600; // Token expires in 1 hour
        
        // Update tokens table with the new token
        $conn->exec("DELETE FROM password_reset_tokens WHERE email = '$email'");
        $conn->prepare("INSERT INTO password_reset_tokens (token, email, created_at) VALUES (?, ?, ?)")
            ->execute([$token, $email, time()]);
        
        // Send reset link to user's email
        $resetLink = "http://yourwebsite.com/reset_password.php?token=$token";
        $to = $email;
        $subject = "Password Reset Request";
        $message = "Please click the following link to reset your password: 

$resetLink

This link will expire in 1 hour.";
        
        // Send email
        if (mail($to, $subject, $message)) {
            header("Location: forgot_password_success.php");
            exit();
        } else {
            die("Failed to send the reset link. Please try again.");
        }
    }
} catch(PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

// Show form if it's not a POST request
if ($_SERVER["REQUEST_METHOD"] != "POST") {
?>
<!DOCTYPE html>
<html>
<head>
    <title>Forgot Password</title>
</head>
<body>
    <h2>Forgot Password</h2>
    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
        Email: <input type="text" name="email"><br><br>
        <input type="submit" value="Submit">
    </form>
</body>
</html>
<?php
}
?>


<?php
// forgot_password.php

include('db_connection.php'); // Include your database connection file

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = mysqli_real_escape_string($conn, $_POST['email']);

    // Check if email exists in the database
    $sql = "SELECT * FROM users WHERE email = '$email'";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) == 1) {
        // Generate a random token
        $token = bin2hex(random_bytes(16));
        
        // Expiry time: current time + 1 hour
        $expires = date('Y-m-d H:i:s', time() + 3600);

        // Update the database with the token and expiry time
        $updateSql = "UPDATE users SET reset_token = '$token', reset_expiry = '$expires' WHERE email = '$email'";
        mysqli_query($conn, $updateSql);

        // Send the password reset link to user's email
        sendResetEmail($email, $token);
        
        echo "A password reset link has been sent to your email address.";
    } else {
        die("This email does not exist in our database.");
    }
}

// Function to send reset email
function sendResetEmail($email, $token) {
    $to = $email;
    $subject = "Password Reset Request";
    
    // Set the headers
    $headers = "From: your_website@example.com\r
";
    $headers .= "Content-type: text/html; charset=UTF-8\r
";
    
    $message = "
        <html>
            <head>
                <title>Password Reset</title>
            </head>
            <body>
                <h2>Password Reset Request</h2>
                <p>Hello,</p>
                <p>We received a password reset request for your account. Please click the link below to reset your password:</p>
                <a href='http://yourwebsite.com/reset_password.php?token=$token'>Reset Password</a>
                <br><br>
                <p>If you did not request this password reset, please ignore this email.</p>
                <p>This password reset link will expire in 1 hour.</p>
            </body>
        </html>
    ";
    
    mail($to, $subject, $message, $headers);
}
?>


<?php
// reset_password.php

include('db_connection.php'); // Include your database connection file

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $token = mysqli_real_escape_string($conn, $_POST['token']);
    $new_password = mysqli_real_escape_string($conn, $_POST['new_password']);
    $confirm_password = mysqli_real_escape_string($conn, $_POST['confirm_password']);

    // Check if token is valid and not expired
    $sql = "SELECT * FROM users WHERE reset_token = '$token' AND reset_expiry > NOW()";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) == 1) {
        if ($new_password != $confirm_password) {
            die("Passwords do not match. Please try again.");
        }

        // Update the user's password
        $password_hash = md5($new_password); // Use a stronger hashing algorithm like bcrypt in production

        $updateSql = "UPDATE users SET password = '$password_hash', reset_token = '', reset_expiry = '0000-00-00 00:00:00' WHERE reset_token = '$token'";
        mysqli_query($conn, $updateSql);

        echo "Your password has been successfully updated!";
    } else {
        die("Invalid or expired token. Please request a new password reset.");
    }
}
?>


<?php
session_start();
include('db_connection.php'); // Include your database connection

if (isset($_POST['email'])) {
    $email = $_POST['email'];
    
    // Check if email exists in the database
    $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Generate a unique token
        $token = bin2hex(random_bytes(32));
        
        // Set expiration time (1 hour)
        $expires = date('Y-m-d H:i:s', strtotime('+1 hour'));
        
        // Insert token into the database
        $stmt = $conn->prepare("INSERT INTO password_reset (user_id, token, expires) 
                               SELECT id, ?, ? FROM users WHERE email = ?");
        $stmt->bind_param("sss", $token, $expires, $email);
        $stmt->execute();
        
        // Send reset link to the user's email
        $to = $email;
        $subject = "Password Reset Request";
        $message = "Please click the following link to reset your password:

" .
                   "http://yourdomain.com/reset_password.php?token=$token

" .
                   "If you did not request this, please ignore this email.";
        
        mail($to, $subject, $message);
        echo "An email has been sent with instructions to reset your password.";
    } else {
        echo "Email does not exist in our records.";
    }
}
?>


<?php
session_start();
include('db_connection.php');

if (isset($_POST['new_password'], $_POST['confirm_password'], $_POST['token'])) {
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];
    $token = $_POST['token'];
    
    if ($new_password !== $confirm_password) {
        die("Passwords do not match.");
    }
    
    // Check token validity
    $stmt = $conn->prepare("SELECT user_id FROM password_reset 
                           WHERE token = ? AND expires > NOW()");
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();
        $user_id = $row['user_id'];
        
        // Hash the new password
        $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
        
        // Update user's password
        $stmt = $conn->prepare("UPDATE users SET password = ? WHERE id = ?");
        $stmt->bind_param("si", $hashed_password, $user_id);
        $stmt->execute();
        
        // Remove the reset token
        $stmt = $conn->prepare("DELETE FROM password_reset WHERE token = ?");
        $stmt->bind_param("s", $token);
        $stmt->execute();
        
        echo "Password has been reset successfully.";
    } else {
        die("Invalid or expired token.");
    }
}
?>


<?php
// Database configuration
$host = 'localhost';
$user = 'root';
$password = '';
$dbname = 'test';

// Connect to database
$conn = mysqli_connect($host, $user, $password, $dbname);
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

$email = $_POST['email'];

// Check if email exists
$sql = "SELECT id FROM users WHERE email='" . mysqli_real_escape_string($conn, $email) . "'";
$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) == 0) {
    echo "Email not found!";
} else {
    // Generate reset token
    $token = bin2hex(random_bytes(32));
    
    // Store token in database with expiry time (e.g., 30 minutes)
    $sql = "INSERT INTO password_resets (user_id, token, created_at) 
            VALUES (" . mysqli_insert_id($conn) . ", '" . mysqli_real_escape_string($conn, $token) . "', NOW())";
    
    if (mysqli_query($conn, $sql)) {
        // Send email
        $to = $email;
        $subject = "Password Reset Request";
        
        $message = "<html>
                     <head></head>
                     <body>
                         <h2>Password Reset</h2>
                         <p>Please click the following link to reset your password:</p>
                         <a href='http://localhost/reset_password.php?token=$token'>Reset Password</a><br><br>
                         This link will expire in 30 minutes.
                     </body>
                   </html>";
        
        $headers = "MIME-Version: 1.0" . "\r
";
        $headers .= "Content-type:text/html;charset=UTF-8" . "\r
";
        $headers .= "From: noreply@example.com" . "\r
"; // Change to your email
        
        mail($to, $subject, $message, $headers);
        
        echo "A password reset link has been sent to your email!";
    } else {
        echo "Error sending reset link. Please try again.";
    }
}

mysqli_close($conn);
?>


<?php
// Database configuration
$host = 'localhost';
$user = 'root';
$password = '';
$dbname = 'test';

$conn = mysqli_connect($host, $user, $password, $dbname);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

if (isset($_GET['token'])) {
    $token = $_GET['token'];
    
    // Check token validity
    $sql = "SELECT * FROM password_resets WHERE token='" . mysqli_real_escape_string($conn, $token) . "'";
    $result = mysqli_query($conn, $sql);
    
    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        // Check if token is expired
        $created_at = strtotime($row['created_at']);
        $expires_in = 1800; // 30 minutes
        
        if ($created_at + $expires_in >= time()) {
            ?>
            <h2>Reset Password</h2>
            <form action="reset_password.php" method="post">
                <input type="password" name="new_pass" placeholder="New Password" required><br><br>
                <button type="submit">Submit</button>
                <input type="hidden" name="token" value="<?php echo $token; ?>">
            </form>
            <?php
        } else {
            echo "This reset link has expired. Please request a new one.";
        }
    } else {
        echo "Invalid reset link!";
    }
} elseif (isset($_POST['new_pass'])) {
    // Update password
    $token = $_POST['token'];
    
    $sql = "SELECT * FROM password_resets WHERE token='" . mysqli_real_escape_string($conn, $token) . "'";
    $result = mysqli_query($conn, $sql);
    
    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        
        // Check expiration
        $created_at = strtotime($row['created_at']);
        $expires_in = 1800;
        
        if ($created_at + $expires_in >= time()) {
            $new_pass = password_hash($_POST['new_pass'], PASSWORD_DEFAULT);
            
            // Update user's password
            $sql_update_user = "UPDATE users SET password='$new_pass' WHERE id=" . $row['user_id'];
            if (mysqli_query($conn, $sql_update_user)) {
                // Delete reset token
                $sql_delete_token = "DELETE FROM password_resets WHERE token='" . mysqli_real_escape_string($conn, $token) . "'";
                mysqli_query($conn, $sql_delete_token);
                
                echo "Password updated successfully!";
            } else {
                echo "Error updating password.";
            }
        } else {
            echo "This reset link has expired. Please request a new one.";
        }
    } else {
        echo "Invalid token or already used.";
    }
} else {
    echo "Please use the provided link to reset your password!";
}
?>


<?php
// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = mysqli_real_escape_string($conn, trim($_POST['email']));

    // Validate email format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        die("Invalid email format");
    }

    // Check if email exists in database
    $sql = "SELECT id FROM users WHERE email='$email'";
    $result = mysqli_query($conn, $sql);
    
    if (mysqli_num_rows($result) == 0) {
        die("Email not found in our records");
    }

    // Generate a random token for password reset
    $token = bin2hex(openssl_random_pseudo_bytes(32));

    // Insert token into database
    $sql = "UPDATE users SET reset_token='$token' WHERE email='$email'";
    mysqli_query($conn, $sql);

    // Send email with reset link
    $to = $email;
    $subject = "Password Reset Request";
    $message = "Please click the following link to reset your password:

http://example.com/reset_password.php?token=$token&user_id=" . mysqli_insert_id($conn) . "

If you did not request this, please ignore this email.";
    $headers = "From: noreply@example.com";

    if (mail($to, $subject, $message, $headers)) {
        echo "Reset password link has been sent to your email address";
    } else {
        die("Failed to send reset email");
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Forgot Password</title>
</head>
<body>
    <h2>Forgot Password</h2>
    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
        <label>Email:</label><br>
        <input type="email" name="email" required><br><br>
        <input type="submit" value="Submit">
    </form>
</body>
</html>


<?php
session_start();

// Get token and user ID from URL
if (!isset($_GET['token']) || !isset($_GET['user_id'])) {
    die("Invalid request");
}

$token = $_GET['token'];
$user_id = $_GET['user_id'];

// Verify token and user_id in database
$sql = "SELECT * FROM users WHERE id='$user_id' AND reset_token='$token'";
$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) != 1) {
    die("Invalid reset link");
}

// If form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $password = trim($_POST['password']);
    $confirm_password = trim($_POST['confirm_password']);

    // Validate password
    if ($password != $confirm_password) {
        die("Passwords do not match");
    }

    if (strlen($password) < 6) {
        die("Password must be at least 6 characters long");
    }

    // Hash the new password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Update database with new password and clear reset token
    $sql = "UPDATE users SET password='$hashed_password', reset_token='' WHERE id='$user_id'";
    mysqli_query($conn, $sql);

    // Redirect to login page
    header("Location: login.php?message=Password has been successfully updated");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Reset Password</title>
</head>
<body>
    <h2>Reset Password</h2>
    <form action="<?php echo $_SERVER['PHP_SELF']; ?>?token=<?php echo $token; ?>&user_id=<?php echo $user_id; ?>" method="post">
        <label>New Password:</label><br>
        <input type="password" name="password" required><br><br>
        <label>Confirm Password:</label><br>
        <input type="password" name="confirm_password" required><br><br>
        <input type="submit" value="Reset Password">
    </form>
</body>
</html>


<?php
$email = mysqli_real_escape_string($conn, $_POST['email']);
// Further validation can be added here if needed.
?>


$result = $conn->query("SELECT * FROM users WHERE email='$email'");
if ($result->num_rows == 0) {
    die("No account found with this email.");
}


$token = md5(rand());
$current_time = strtotime(date('Y-m-d H:i:s'));
$expiration_time = $current_time + 3600; // Expiry after 1 hour

$conn->query("UPDATE users SET reset_token='$token', token_expiry='$expiration_time' WHERE email='$email'");


$reset_link = "http://yourdomain.com/reset_password.php?token=$token";
mail($email, 'Password Reset', "Click here to reset your password: $reset_link");


if (isset($_GET['token'])) {
    $token = mysqli_real_escape_string($conn, $_GET['token']);
    
    // Check if token exists in database and hasn't expired
    $result = $conn->query("SELECT * FROM users WHERE reset_token='$token' AND token_expiry > '" . time() . "'");
    
    if ($result->num_rows == 1) {
        // Display password reset form
        ?>
        <h2>Reset Password</h2>
        <form action="reset_password.php?token=<?php echo $token ?>" method="post">
            New Password: <input type="password" name="new_pass" required><br>
            Confirm Password: <input type="password" name="confirm_pass" required><br>
            <button type="submit">Reset</button>
        </form>
        <?php
    } else {
        // Token invalid or expired
        die("Invalid token. Please request a new password reset.");
    }
} else {
    die("No token provided.");
}


if (isset($_POST['new_pass']) && isset($_POST['confirm_pass'])) {
    $new_pass = $_POST['new_pass'];
    $confirm_pass = $_POST['confirm_pass'];
    
    if ($new_pass != $confirm_pass) {
        die("Passwords do not match.");
    }
    
    // Hash the new password
    $hash = password_hash($new_pass, PASSWORD_DEFAULT);
    
    $token = mysqli_real_escape_string($conn, $_GET['token']);
    
    // Update user's password and clear reset token
    $conn->query("UPDATE users SET password='$hash', reset_token=NULL WHERE reset_token='$token'");
    
    echo "Password updated successfully!";
}


<?php
include('db_connection.php');
include('functions.php');

if (isset($_POST['email'])) {
    $email = $_POST['email'];
    
    // Check if email or username exists in database
    $stmt = $conn->prepare("SELECT id FROM users WHERE email=? OR username=?");
    $stmt->execute([$email, $email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($user) {
        // Generate a random token and expiration time
        $token = bin2hex(random_bytes(16));
        $expires = date('Y-m-d H:i:s', strtotime('+1 hour'));
        
        // Store token in database
        $stmt = $conn->prepare("INSERT INTO password_reset (user_id, token, expires) VALUES (?, ?, ?)");
        $stmt->execute([$user['id'], $token, $expires]);
        
        // Send reset email
        $resetLink = "http://yourdomain.com/reset_password.php?token=" . $token;
        sendResetEmail($email, $resetLink);
        
        echo "An email has been sent to you with instructions to reset your password.";
    } else {
        die("No account found with that email or username.");
    }
}
?>


<?php
include('db_connection.php');

if (!isset($_GET['token'])) {
    die("No token provided.");
}

$token = $_GET['token'];

// Check if token exists and is valid
$stmt = $conn->prepare("SELECT user_id, expires FROM password_reset WHERE token=?");
$stmt->execute([$token]);
$result = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$result) {
    die("Invalid or expired reset link.");
}

$currentDate = date('Y-m-d H:i:s');
if ($currentDate > $result['expires']) {
    die("Reset link has expired. Please request a new one.");
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Reset Password</title>
</head>
<body>
    <h2>Reset Password</h2>
    <form action="reset_password.php?token=<?php echo $token;?>" method="post">
        <label for="password">New Password:</label><br>
        <input type="password" id="password" name="password"><br><br>
        <label for="confirm">Confirm Password:</label><br>
        <input type="password" id="confirm" name="confirm"><br><br>
        <input type="submit" value="Reset Password">
    </form>
</body>
</html>


<?php
include('db_connection.php');

if (!isset($_GET['token']) || !isset($_POST['password'])) {
    die("Invalid request.");
}

$token = $_GET['token'];
$password = $_POST['password'];
$confirm = $_POST['confirm'];

// Check if token exists and is valid
$stmt = $conn->prepare("SELECT user_id, expires FROM password_reset WHERE token=?");
$stmt->execute([$token]);
$result = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$result) {
    die("Invalid or expired reset link.");
}

$currentDate = date('Y-m-d H:i:s');
if ($currentDate > $result['expires']) {
    die("Reset link has expired. Please request a new one.");
}

// Check if passwords match
if ($password !== $confirm) {
    die("Passwords do not match.");
}

// Hash the password
$hashedPassword = password_hash($password, PASSWORD_DEFAULT);

// Update user's password
$stmt = $conn->prepare("UPDATE users SET password=? WHERE id=?");
$stmt->execute([$hashedPassword, $result['user_id']]);

// Delete used token
$stmt = $conn->prepare("DELETE FROM password_reset WHERE token=?");
$stmt->execute([$token]);

echo "Your password has been reset successfully!";
?>


function sendResetEmail($email, $resetLink) {
    $to = $email;
    $subject = "Password Reset Request";
    $message = "Please click the following link to reset your password:

" . $resetLink . "

This link will expire in 1 hour.";
    $headers = "From: yourname@yourdomain.com";

    mail($to, $subject, $message, $headers);
}


<?php
// config.php - Database connection details
$host = 'localhost';
$username = 'root';
$password = '';
$db_name = 'your_database';

$conn = mysqli_connect($host, $username, $password, $db_name);
if (!$conn) {
    die('Connection failed: ' . mysqli_error());
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Forgot Password</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body class="bg-light">
    <div class="container mt-5">
        <h2 class="text-center mb-4">Forgot Password</h2>
        <form action="forgot_password.php" method="post" class="border p-3 bg-white rounded">
            <div class="form-group">
                <label for="email">Email or Username:</label>
                <input type="text" class="form-control" id="email" name="email_or_username" required>
            </div>
            <button type="submit" class="btn btn-primary">Reset Password</button>
        </form>
    </div>
</body>
</html>

<?php
// forgot_password.php - Handle password reset request
session_start();
require_once 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email_or_username = mysqli_real_escape_string($conn, $_POST['email_or_username']);
    
    // Check if user exists in database
    $sql = "SELECT id, email FROM users WHERE email='$email_or_username' OR username='$email_or_username'";
    $result = mysqli_query($conn, $sql);
    
    if (mysqli_num_rows($result) > 0) {
        $user = mysqli_fetch_assoc($result);
        
        // Generate random reset token
        $reset_token = bin2hex(random_bytes(16));
        $expires = date('Y-m-d H:i:s', time() + 3600); // Token expires in 1 hour
        
        // Store token in database
        $sql = "INSERT INTO password_reset (user_id, reset_token, expires) 
                VALUES (?, ?, ?)";
        
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "iss", $user['id'], $reset_token, $expires);
        mysqli_stmt_execute($stmt);
        
        // Send email with reset link
        $to = $user['email'];
        $subject = 'Password Reset Request';
        $message = 'Please click the following link to reset your password: http://your-site.com/reset_password.php?token=' . $reset_token;
        $headers = 'From: webmaster@your-site.com' . "\r
" .
                   'Reply-To: webmaster@your-site.com' . "\r
" .
                   'X-Mailer: PHP/' . phpversion();
        
        mail($to, $subject, $message, $headers);
        
        echo "<script>alert('Password reset email has been sent!'); window.location.href='index.php';</script>";
    } else {
        echo "<script>alert('User not found!');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Reset Password</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body class="bg-light">
    <div class="container mt-5">
        <?php
        // reset_password.php - Handle password reset
        session_start();
        require_once 'config.php';

        if (isset($_GET['token'])) {
            $reset_token = mysqli_real_escape_string($conn, $_GET['token']);
            
            // Check token validity and expiration
            $sql = "SELECT user_id FROM password_reset 
                    WHERE reset_token='$reset_token' AND expires > NOW()";
            $result = mysqli_query($conn, $sql);
            
            if (mysqli_num_rows($result) == 1) {
                $user_id = mysqli_fetch_assoc($result)['user_id'];
        ?>
        
        <form action="reset_password.php" method="post" class="border p-3 bg-white rounded">
            <div class="form-group">
                <label for="new_password">New Password:</label>
                <input type="password" class="form-control" id="new_password" name="new_password" required>
            </div>
            <div class="form-group">
                <label for="confirm_password">Confirm Password:</label>
                <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
            </div>
            <button type="submit" class="btn btn-primary">Change Password</button>
        </form>

        <?php
                if ($_SERVER["REQUEST_METHOD"] == "POST") {
                    $new_password = $_POST['new_password'];
                    $confirm_password = $_POST['confirm_password'];

                    if ($new_password != $confirm_password) {
                        echo "<script>alert('Passwords do not match!');</script>";
                        exit();
                    }

                    // Hash the new password
                    $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

                    // Update user's password in database
                    $sql = "UPDATE users SET password='$hashed_password' WHERE id=$user_id";
                    mysqli_query($conn, $sql);

                    // Delete the reset token
                    $sql = "DELETE FROM password_reset WHERE reset_token='$reset_token'";
                    mysqli_query($conn, $sql);

                    echo "<script>alert('Password has been changed!'); window.location.href='index.php';</script>";
                }
            } else {
                echo "<h3>Invalid or expired reset token!</h3>";
            }
        } else {
            echo "<h3>No reset token provided!</h3>";
        }
        mysqli_close($conn);
        ?>
    </div>
</body>
</html>


<?php
// Database configuration
$host = "localhost";
$user = "root";
$password = "";
$db_name = "mydatabase";

// Connect to the database
$conn = new mysqli($host, $user, $password, $db_name);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if email is provided and not empty
    if (isset($_POST['email']) && !empty(trim($_POST['email']))) {
        $email = trim($_POST['email']);
        
        // Validate email format
        if (!preg_match("/^([a-zA-Z0-9_\-\.]+)@([a-zA-Z0-9_\-\.]+)\.([a-zA-Z]{2,5})$/", $email)) {
            $message = "Invalid email format";
            header("Location: forgot_password.php?msg=" . urlencode($message));
            exit();
        }
        
        // Check if the email exists in the database
        $sql = "SELECT id FROM users WHERE email = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows === 0) {
            $message = "No account found with this email address";
            header("Location: forgot_password.php?msg=" . urlencode($message));
            exit();
        }
        
        // Generate a random token
        $token = bin2hex(random_bytes(16));
        
        // Calculate the expiration time (30 minutes from now)
        $expiration_time = date('Y-m-d H:i:s', strtotime('+30 minutes'));
        
        // Update the token and expiration time in the database
        $updateSql = "UPDATE users SET reset_token = ?, reset_expires = ? WHERE email = ?";
        $stmt = $conn->prepare($updateSql);
        $stmt->bind_param("sss", $token, $expiration_time, $email);
        $result = $stmt->execute();
        
        if (!$result) {
            die("Error updating record: " . $conn->error);
        }
        
        // Send the reset password email
        $to = $email;
        $subject = "Reset Password Request";
        $message_body = "Please click on the following link to reset your password:

" .
                        "http://example.com/reset_password.php?token=" . $token . "

" .
                        "This link will expire in 30 minutes.";
        
        $headers = "From: noreply@example.com\r
";
        $headers .= "Reply-To: noreply@example.com\r
";
        $headers .= "X-Mailer: PHP/" . phpversion();
        
        if (mail($to, $subject, $message_body, $headers)) {
            $message = "Password reset email has been sent to your email address.";
            header("Location: forgot_password.php?msg=" . urlencode($message));
            exit();
        } else {
            die("Failed to send password reset email");
        }
        
    } else {
        $message = "Email is required";
        header("Location: forgot_password.php?msg=" . urlencode($message));
        exit();
    }
}

// Close database connection
$conn->close();
?>


<?php
include('db_connect.php'); // Include your database connection

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    
    // Sanitize and validate email input
    $email = filter_var($email, FILTER_SANITIZE_EMAIL);
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        die("Invalid email format");
    }
    
    // Check if the email exists in the database
    $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->bind_param('s', $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Generate a unique reset token
        $token = bin2hex(random_bytes(16)); // Generates a 32-character hexadecimal string
        $expires = date('Y-m-d H:i:s', time() + 3600); // Token expires in 1 hour

        // Store the token and expiration in the database
        $stmt = $conn->prepare("INSERT INTO password_reset (user_id, token, expires) VALUES (?, ?, ?)");
        $stmt->bind_param('iss', $result->fetch_assoc()['id'], $token, $expires);
        $stmt->execute();

        // Send reset email
        $to = $email;
        $subject = "Password Reset Request";
        $message = "
            <html>
                <body>
                    <p>Please click the following link to reset your password:</p>
                    <a href='http://your-site.com/reset-password.php?token=$token&email=$email'>Reset Password</a>
                </body>
            </html>
        ";
        $headers = "MIME-Version: 1.0\r
";
        $headers .= "Content-type: text/html; charset=UTF-8\r
";
        $headers .= "From: your-site@example.com\r
";

        mail($to, $subject, $message, $headers);

        // Redirect back to the forgot password page with a success message
        header("Location: forgot-password.php?msg=Check%20your%20email%20for%20the%20reset%20link.");
        exit();
    } else {
        die("Email not found in our records");
    }
}
?>


<?php
include('db_connect.php');

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    $token = $_GET['token'];
    $email = $_GET['email'];

    // Validate inputs
    if (empty($token) || empty($email)) {
        die("Invalid request");
    }

    // Check token validity and expiration
    $stmt = $conn->prepare("SELECT pr.*, u.id FROM password_reset pr JOIN users u ON pr.user_id = u.id WHERE pr.token = ? AND pr.expires > NOW()");
    $stmt->bind_param('s', $token);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 0) {
        die("Invalid or expired reset link");
    }

    // Fetch user data
    $user_data = $result->fetch_assoc();
} else {
    // Handle form submission
    $password = $_POST['password'];
    $token = $_POST['token'];

    // Validate password (minimum length, etc.)
    if (strlen($password) < 8) {
        die("Password must be at least 8 characters");
    }

    // Update the user's password
    $stmt = $conn->prepare("UPDATE users SET password = ? WHERE id = ?");
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    $stmt->bind_param('si', $hashed_password, $user_data['id']);
    $stmt->execute();

    // Delete the reset token
    $stmt = $conn->prepare("DELETE FROM password_reset WHERE token = ?");
    $stmt->bind_param('s', $token);
    $stmt->execute();

    // Redirect to login page with success message
    header("Location: login.php?msg=Password%20reset%20successful.");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Reset Password</title>
</head>
<body>
    <h2>Reset Password</h2>
    
    <?php
    // Check if there's an error message from the server
    if (isset($_GET['error'])) {
        echo "<p style='color: red;'>$_GET[error]</p>";
    }
    ?>

    <form action="reset-password.php" method="post">
        <input type="hidden" name="token" value="<?php echo $token; ?>">
        <input type="password" name="password" placeholder="Enter new password" required><br>
        <button type="submit">Reset Password</button>
    </form>

    <p>Link expired or invalid? <a href="forgot-password.php">Request a new link.</a></p>
</body>
</html>


<?php
session_start();
include("db_connection.php"); // Include your database connection file

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);
    
    if (empty($email)) {
        $error = "Please enter your email address!";
    } else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Invalid email format!";
    } else {
        // Check if email exists in the database
        $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows === 0) {
            $error = "No account found with this email!";
        } else {
            // Generate a unique token
            $token = md5(uniqid(rand(), true));
            
            // Update the database with the token and expiration time
            date_default_timezone_set("UTC");
            $expires = date('Y-m-d H:i:s', time() + 3600); // Token expires in 1 hour
            
            $update_stmt = $conn->prepare("UPDATE users SET reset_token = ?, reset_expires = ? WHERE email = ?");
            $update_stmt->bind_param("sss", $token, $expires, $email);
            $update_stmt->execute();
            
            // Send the password reset link to the user's email
            $to = $email;
            $subject = "Reset Your Password";
            $message = "
                <html>
                    <head></head>
                    <body>
                        <h2>Password Reset Request</h2>
                        <p>Please click the following link to reset your password:</p>
                        <a href='http://yourdomain.com/reset_password.php?token=$token'>Reset Password</a><br><br>
                        <small>If you didn't request this, please ignore this email.</small>
                    </body>
                </html>
            ";
            $headers = "MIME-Version: 1.0" . "\r
";
            $headers .= "Content-type:text/html;charset=UTF-8" . "\r
";
            $headers .= "From: yourname@yourdomain.com" . "\r
"; // Change this to your email
            
            if (mail($to, $subject, $message, $headers)) {
                $success = "We've sent you a password reset link. Check your email!";
            } else {
                $error = "An error occurred while sending the email.";
            }
        }
    }
}
?>


<?php
session_start();
include("db_connection.php"); // Include your database connection file

if (isset($_GET['token'])) {
    $token = $_GET['token'];
    
    // Check if token exists in the database and hasn't expired
    date_default_timezone_set("UTC");
    $current_time = date('Y-m-d H:i:s');
    
    $stmt = $conn->prepare("SELECT id, email FROM users WHERE reset_token = ? AND reset_expires > ?");
    $stmt->bind_param("ss", $token, $current_time);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows === 0) {
        $error = "Invalid or expired token!";
    } else {
        // Show password reset form
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $password = trim($_POST['password']);
            $confirm_password = trim($_POST['confirm_password']);
            
            if (empty($password) || empty($confirm_password)) {
                $error = "Please fill in all fields!";
            } else if ($password !== $confirm_password) {
                $error = "Passwords do not match!";
            } else {
                // Update the password
                $hashed_password = md5($password); // You might want to use a stronger hashing algorithm
                
                $update_stmt = $conn->prepare("UPDATE users SET password = ?, reset_token = NULL WHERE reset_token = ?");
                $update_stmt->bind_param("ss", $hashed_password, $token);
                $update_stmt->execute();
                
                // Clear the token
                $clear_stmt = $conn->prepare("UPDATE users SET reset_token = NULL WHERE reset_token = ?");
                $clear_stmt->bind_param("s", $token);
                $clear_stmt->execute();
                
                $success = "Your password has been updated!";
            }
        } else {
            // Show the form
            $row = $result->fetch_assoc();
            $_SESSION['reset_email'] = $row['email'];
        }
    }
} else {
    $error = "No token provided!";
}
?>


<!DOCTYPE html>
<html>
<head>
    <title>Forgot Password</title>
    <script>
        function validateEmail() {
            const email = document.getElementById('email').value;
            const errorDiv = document.getElementById('error');
            
            if (email === '') {
                errorDiv.textContent = 'Please enter your email address.';
                return false;
            }
            
            // Basic email validation
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!email.match(emailRegex)) {
                errorDiv.textContent = 'Please enter a valid email address.';
                return false;
            }
            
            return true;
        }
    </script>
</head>
<body>
    <h2>Forgot Password</h2>
    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" onsubmit="return validateEmail()">
        <div id="error"></div>
        <p>Please enter your registered email address:</p>
        <input type="email" name="email" id="email" required>
        <br><br>
        <input type="submit" value="Reset Password">
    </form>
</body>
</html>

<?php
// Database configuration
$host = 'localhost';
$username = 'root';
$password = '';
$database = 'mydatabase';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = htmlspecialchars($_POST['email']);
    
    // Sanitize email (prevent SQL injection)
    $conn = new mysqli($host, $username, $password, $database);
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    
    $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->bind_param('s', $email);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows == 0) {
        echo "Email not found. Please check your email.";
    } else {
        // Generate token and expiration time
        $token = uniqid() . rand(1000, 9999);
        $expires = date('Y-m-d H:i:s', strtotime('+60 minutes'));
        
        // Update the database with the new token and expiration
        $updateStmt = $conn->prepare("UPDATE users SET reset_token = ?, reset_expires = ? WHERE email = ?");
        $updateStmt->bind_param('sss', $token, $expires, $email);
        $updateStmt->execute();
        
        // Close connections
        $updateStmt->close();
        $stmt->close();
        $conn->close();
        
        // Send email with reset link
        $to = $email;
        $subject = "Password Reset Request";
        $message = "Please click the following link to reset your password:

http://example.com/reset_password.php?token=$token

If you did not request this, please ignore this email.";
        $headers = "From: noreply@example.com";
        
        mail($to, $subject, $message, $headers);
        
        echo "An email has been sent to $email with instructions to reset your password.";
    }
}
?>


<?php
// Database configuration
$host = 'localhost';
$username = 'root';
$password = '';
$database = 'mydatabase';

if (isset($_GET['token'])) {
    $token = htmlspecialchars($_GET['token']);
    
    // Check if token exists and is valid
    $conn = new mysqli($host, $username, $password, $database);
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    
    // Check token expiration and validity
    $stmt = $conn->prepare("SELECT id FROM users WHERE reset_token = ? AND reset_expires > NOW() AND active = 1");
    $stmt->bind_param('s', $token);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows == 0) {
        echo "Invalid or expired token.";
    } else {
        // Token is valid, show password reset form
?>
<!DOCTYPE html>
<html>
<head>
    <title>Reset Password</title>
</head>
<body>
    <h2>Reset Password</h2>
    <form action="change_password.php" method="post">
        <input type="hidden" name="token" value="<?php echo $token; ?>">
        <p>New Password:</p>
        <input type="password" name="new_password" required>
        <br><br>
        <input type="submit" value="Change Password">
    </form>
</body>
</html>
<?php
    }
    
    // Close connections
    $stmt->close();
    $conn->close();
} else {
    echo "No token provided.";
}
?>


<?php
// Database configuration
$host = 'localhost';
$username = 'root';
$password = '';
$database = 'mydatabase';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $token = htmlspecialchars($_POST['token']);
    $newPassword = htmlspecialchars($_POST['new_password']);
    
    // Validate token again
    $conn = new mysqli($host, $username, $password, $database);
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    
    $stmt = $conn->prepare("SELECT id FROM users WHERE reset_token = ? AND reset_expires > NOW() AND active = 1");
    $stmt->bind_param('s', $token);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows == 0) {
        echo "Invalid or expired token.";
    } else {
        // Update password
        $hash = password_hash($newPassword, PASSWORD_DEFAULT);
        
        $updateStmt = $conn->prepare("UPDATE users SET password = ?, reset_token = NULL WHERE reset_token = ?");
        $updateStmt->bind_param('ss', $hash, $token);
        $updateStmt->execute();
        
        // Invalidate the token
        $invalidateStmt = $conn->prepare("UPDATE users SET reset_token = NULL WHERE reset_token = ?");
        $invalidateStmt->bind_param('s', $token);
        $invalidateStmt->execute();
        
        echo "Password has been successfully updated.";
    }
    
    // Close connections
    $stmt->close();
    $updateStmt->close();
    $invalidateStmt->close();
    $conn->close();
}
?>


<?php
session_start();
include('config.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    
    $sql = "SELECT * FROM users WHERE email='$email'";
    $result = mysqli_query($conn, $sql);
    
    if (mysqli_num_rows($result) > 0) {
        $token = md5(rand(1000, 9999));
        $expires = date('Y-m-d H:i:s', time() + 3600); // Expires in 1 hour
        
        $sql_update = "UPDATE users SET reset_token='$token', expires='$expires' WHERE email='$email'";
        mysqli_query($conn, $sql_update);
        
        $reset_link = "http://$_SERVER[HTTP_HOST]/reset.php?token=$token";
        $to = $email;
        $subject = 'Password Reset Request';
        $message = "Click the link below to reset your password:
$reset_link
If you didn't request this, please ignore.";
        
        mail($to, $subject, $message);
        echo "Check your email for reset instructions.";
    } else {
        echo "Email not found!";
    }
}
?>


<?php
session_start();
include('config.php');

$token = isset($_GET['token']) ? $_GET['token'] : '';
$sql = "SELECT * FROM users WHERE reset_token='$token' AND expires > NOW()";
$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) == 0) {
    die("Invalid or expired link.");
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];
    
    if ($new_password != $confirm_password) {
        echo "Passwords don't match!";
    } else {
        $hash = password_hash($new_password, PASSWORD_DEFAULT);
        $sql_reset = "UPDATE users SET password='$hash', reset_token='', expires='' WHERE reset_token='$token'";
        mysqli_query($conn, $sql_reset);
        header("Location: login.php?success=1");
        exit();
    }
}
?>


<?php
// Include required files
require_once 'config.php';
require_once 'functions.php';

// Start session
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    
    // Check if email exists in database
    $query = "SELECT id, password_reset_token FROM users WHERE email = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        // Generate a new password reset token
        $token = bin2hex(random_bytes(16));
        
        // Create hashed token for security
        $hashedToken = password_hash($token, PASSWORD_DEFAULT);
        
        // Set expiration time (e.g., 30 minutes from now)
        $expirationTime = date('Y-m-d H:i:s', strtotime('+30 minutes'));
        
        // Update the user's record with the new token and expiration
        $updateQuery = "UPDATE users SET password_reset_token = ?, token_expires_at = ? WHERE email = ?";
        $updateStmt = $conn->prepare($updateQuery);
        $updateStmt->bind_param("sss", $hashedToken, $expirationTime, $email);
        
        if ($updateStmt->execute()) {
            // Send reset link to user's email
            $resetLink = "http://$_SERVER[HTTP_HOST]/reset-password.php?token=$token";
            
            $subject = "Password Reset Request";
            $message = "Someone requested a password reset for your account. 

If this was you, please click the following link: 
$resetLink

If this wasn't you, simply ignore this email.
The link will expire in 30 minutes.";
            
            // Use PHP's mail function (or use a library like PHPMailer)
            $headers = "From: noreply@yourdomain.com";
            
            if (mail($email, $subject, $message, $headers)) {
                echo "Password reset email has been sent to your inbox!";
            } else {
                echo "Error sending password reset email. Please try again later.";
            }
        } else {
            echo "An error occurred while updating the password reset token.";
        }
    } else {
        // Email does not exist in database
        echo "No account found with this email address.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Forgot Password</title>
</head>
<body>
    <h1>Forgot Password</h1>
    
    <?php if (isset($_SESSION['error'])) { ?>
        <p style="color: red;"><?php echo $_SESSION['error']; ?></p>
    <?php } ?>
    
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <label for="email">Email:</label><br>
        <input type="text" id="email" name="email"><br><br>
        <input type="submit" value="Send Reset Link">
    </form>
    
    <p>Already have an account? <a href="login.php">Login here</a></p>
</body>
</html>


<?php
// Include database connection file
include 'db_connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    
    // Validate input
    if (empty($email)) {
        die("Please enter your email address");
    }
    
    // Check if the email exists in the database
    $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $row = $stmt->fetch();
    
    if (!$row) {
        die("Email does not exist in our records");
    }
    
    // Generate a random token and expiration time
    $token = bin2hex(openssl_random_pseudo_bytes(16));
    $expiration_time = date('Y-m-d H:i:s', strtotime('+1 hour'));
    
    // Store the token in the database
    $stmt = $conn->prepare("INSERT INTO password_resets (email, token, created_at) VALUES (?, ?, ?)");
    $stmt->execute([$email, sha1($token), $expiration_time]);
    
    // Send email with reset link
    $to = $email;
    $subject = "Password Reset Request";
    $message = "
        <html>
            <head></head>
            <body>
                <h2>Password Reset</h2>
                <p>Please click the following link to reset your password:</p>
                <a href='http://example.com/reset_password.php?token=$token'>Reset Password</a><br><br>
                <small>This token will expire in one hour.</small>
            </body>
        </html>
    ";
    
    // Include PHPMailer for sending emails
    require 'PHPMailer/PHPMailer.php';
    $mail = new PHPMailer();
    $mail->isSMTP(); // Set mailer to use SMTP
    $mail->Host = 'smtp.example.com'; // Specify main and backup server
    $mail->SMTPAuth = true; // Enable SMTP authentication
    $mail->Username = 'your_username@example.com'; // SMTP username
    $mail->Password = 'your_password'; // SMTP password
    
    $mail->setFrom('from@example.com', 'Mailer');
    $mail->addAddress($to, $email);
    
    $mail->isHTML(true); // Set email format to HTML
    $mail->Subject = $subject;
    $mail->Body = $message;
    
    if ($mail->send()) {
        echo "An email has been sent to your account with instructions to reset your password.";
    } else {
        die("Mailer Error: " . $mail->ErrorInfo);
    }
}
?>


<?php
// Include database connection file
include 'db_connection.php';

if (isset($_GET['token'])) {
    $token = $_GET['token'];
    
    // Check if token exists and hasn't expired
    $stmt = $conn->prepare("SELECT * FROM password_resets WHERE token = ? AND created_at > NOW() - INTERVAL 1 HOUR");
    $stmt->execute([sha1($token)]);
    $reset = $stmt->fetch();
    
    if (!$reset) {
        die("Invalid or expired reset link");
    }
    
    // Display reset form
?>
<!DOCTYPE html>
<html>
<head>
    <title>Reset Password</title>
</head>
<body>
    <h2>Reset Your Password</h2>
    <form action="reset_password.php?token=<?php echo $token; ?>" method="post">
        New Password: <input type="password" name="new_password"><br><br>
        Confirm Password: <input type="password" name="confirm_password"><br><br>
        <input type="submit" value="Reset Password">
    </form>
</body>
</html>

<?php
} else if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $token = $_GET['token'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];
    
    // Check passwords match
    if ($new_password != $confirm_password) {
        die("Passwords do not match");
    }
    
    // Update the user's password
    $stmt = $conn->prepare("UPDATE users SET password = ? WHERE id = ?");
    $stmt->execute([password_hash($new_password, PASSWORD_DEFAULT), $reset['user_id']]);
    
    // Delete the reset token after use
    $stmt = $conn->prepare("DELETE FROM password_resets WHERE token = ?");
    $stmt->execute([sha1($token)]);
    
    echo "Password has been successfully updated!";
}
?>


<!DOCTYPE html>
<html>
<head>
    <title>Forgot Password</title>
</head>
<body>
    <h2>Forgot Password</h2>
    
    <?php
    if (isset($_GET['error'])) {
        echo "<p style='color:red;'>".$_GET['error']."</p>";
    }
    ?>

    <form action="send_reset_email.php" method="post">
        <label>Email:</label><br>
        <input type="email" name="email" required><br><br>
        
        <input type="submit" value="Reset Password">
    </form>
</body>
</html>


<?php
// Database configuration
$host = "localhost";
$username = "root";
$password = "";
$db_name = "your_database";

// Connect to database
$conn = mysqli_connect($host, $username, $password, $db_name);
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

$email = $_POST['email'];

// Check if email exists in database
$sql = "SELECT id FROM users WHERE email = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    // Email not found
    header("Location: forgot_password.php?error=Email+not+found");
    exit();
}

// Generate reset token and expiration time
$reset_token = bin2hex(random_bytes(32));
$expires = date('Y-m-d H:i:s', strtotime('+1 hour'));

$sql = "UPDATE users SET reset_token = ?, reset_expires = ? WHERE email = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ss", $reset_token, $expires, $email);

if (!$stmt->execute()) {
    // Error updating token
    header("Location: forgot_password.php?error=An+error+occurred");
    exit();
}

// Send reset email
$to = $email;
$subject = "Password Reset Request";
$message = "
Hello,

Please click the following link to reset your password:
http://yourwebsite.com/reset_password.php?token=$reset_token

This link will expire in 1 hour.

Regards,
Your Website Team
";

$headers = "From: noreply@yourwebsite.com\r
";
$headers .= "Reply-To: noreply@yourwebsite.com\r
";
$headers .= "X-Mailer: PHP/" . phpversion();

mail($to, $subject, $message, $headers);

echo "A password reset link has been sent to your email address.";

// Close database connection
$stmt->close();
$conn->close();
?>


<?php
if (!isset($_GET['token'])) {
    die("Invalid request");
}

$token = $_GET['token'];

// Database configuration
$host = "localhost";
$username = "root";
$password = "";
$db_name = "your_database";

$conn = mysqli_connect($host, $username, $password, $db_name);
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Check if token is valid and not expired
$sql = "SELECT id FROM users WHERE reset_token = ? AND reset_expires > NOW()";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $token);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die("Invalid or expired token");
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate and set new password
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    if ($password != $confirm_password) {
        echo "<p style='color:red;'>Passwords do not match</p>";
    } else if (strlen($password) < 6) {
        echo "<p style='color:red;'>Password must be at least 6 characters long</p>";
    } else {
        // Hash the password
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        
        // Update the database
        $sql = "UPDATE users SET password = ?, reset_token = NULL, reset_expires = NULL WHERE reset_token = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $hashed_password, $token);
        
        if ($stmt->execute()) {
            // Password updated successfully
            header("Location: login.php");
            exit();
        } else {
            echo "<p style='color:red;'>An error occurred while updating your password</p>";
        }
    }
}

?>

<!DOCTYPE html>
<html>
<head>
    <title>Reset Password</title>
</head>
<body>
    <h2>Reset Password</h2>
    
    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
        <label>New Password:</label><br>
        <input type="password" name="password" required><br><br>
        
        <label>Confirm New Password:</label><br>
        <input type="password" name="confirm_password" required><br><br>
        
        <input type="submit" value="Reset Password">
    </form>
</body>
</html>

// Close database connection
$stmt->close();
$conn->close();
?>


<?php
// forgot_password.php

// Include database connection file
include('db_connect.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = mysqli_real_escape_string($conn, $_POST['email']);

    // Check if the email exists in the users table
    $sql = "SELECT id FROM users WHERE email = '$email'";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) == 0) {
        die("Email not found. Please check your email and try again.");
    }

    // Generate a random token
    $token = bin2hex(random_bytes(16));

    // Store the token in the database with an expiration time
    $expires = date('Y-m-d H:i:s', time() + 7200); // Expiration after 2 hours

    $sql = "INSERT INTO password_resets (user_id, token, expires) 
            VALUES ('" . mysqli_insert_id($conn) . "', '$token', '$expires')";
    
    if (!mysqli_query($conn, $sql)) {
        die("Error: " . mysqli_error($conn));
    }

    // Send the reset link to the user's email
    $reset_link = "http://yourdomain.com/reset_password.php?token=$token&id=" . mysqli_insert_id($conn);
    
    $to = $email;
    $subject = "Password Reset Request";
    $message = "Please click on this link to reset your password: $reset_link";
    
    // Send email using PHP's mail function
    if (mail($to, $subject, $message)) {
        echo "An email has been sent to you with instructions to reset your password.";
    } else {
        die("Error sending email. Please try again later.");
    }
}

mysqli_close($conn);
?>


<?php
// reset_password.php

include('db_connect.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $token = $_POST['token'];
    $user_id = $_POST['user_id'];
    $new_password = mysqli_real_escape_string($conn, $_POST['new_password']);
    $confirm_password = mysqli_real_escape_string($conn, $_POST['confirm_password']);

    // Validate token
    $sql = "SELECT * FROM password_resets 
            WHERE user_id = '$user_id' AND token = '$token' AND expires > NOW()";
    
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) != 1) {
        die("Invalid or expired reset link. Please request a new password reset.");
    }

    // Check if passwords match
    if ($new_password !== $confirm_password) {
        die("Passwords do not match. Please try again.");
    }

    // Update the user's password
    $sql = "UPDATE users 
            SET password = '$new_password' 
            WHERE id = '$user_id'";
    
    if (!mysqli_query($conn, $sql)) {
        die("Error updating password: " . mysqli_error($conn));
    }

    // Mark the reset as used
    $sql = "UPDATE password_resets 
            SET used = 1 
            WHERE token = '$token'";
    
    mysqli_query($conn, $sql);

    echo "Password has been successfully updated!";
}

mysqli_close($conn);
?>


<?php
// Database configuration
$host = 'localhost';
$dbUsername = 'username';
$dbPassword = 'password';
$dbName = 'database_name';

// Connect to database
$conn = mysqli_connect($host, $dbUsername, $dbPassword, $dbName);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Check if form is submitted
if (isset($_POST['reset'])) {

    // Get email from form
    $email = $_POST['email'];

    // Validate email input
    if (empty($email)) {
        echo "Email is required";
        exit();
    }

    // Check if user exists in the database
    $sql = "SELECT id FROM users WHERE email = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "s", $email);
    mysqli_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if (mysqli_num_rows($result) == 0) {
        echo "Email does not exist in our database";
        exit();
    }

    // Generate reset token
    $resetToken = bin2hex(random_bytes(16));
    $tokenExpiry = date('Y-m-d H:i:s', time() + 3600); // Token expires after 1 hour

    // Update database with the reset token and expiry time
    $updateSql = "UPDATE users SET reset_token = ?, reset_token_expiry = ? WHERE email = ?";
    $stmt = mysqli_prepare($conn, $updateSql);
    mysqli_stmt_bind_param($stmt, "sss", $resetToken, $tokenExpiry, $email);

    if (!mysqli_execute($stmt)) {
        echo "Error resetting password";
        exit();
    }

    // Send reset email
    $to = $email;
    $subject = "Password Reset Request";
    
    $message = "
        <html>
        <head></head>
        <body>
            <p>Hello,</p>
            <p>We received a request to reset your password. Please click the link below to reset your password:</p>
            <a href='http://example.com/reset-password.php?token=$resetToken'>Reset Password</a>
            <p>If you did not make this request, please ignore this email.</p>
            <p>This link will expire in one hour.</p>
        </body>
        </html>
    ";

    $headers = "MIME-Version: 1.0" . "\r
";
    $headers .= "Content-type:text/html;charset=UTF-8" . "\r
";
    $headers .= "From: example@example.com" . "\r
";

    if (mail($to, $subject, $message, $headers)) {
        echo "A password reset link has been sent to your email address.";
    } else {
        echo "Failed to send the password reset link. Please try again later.";
    }
}

// Close database connection
mysqli_close($conn);
?>


<?php
// Database configuration
$host = 'localhost';
$username = 'root';
$password = '';
$dbname = 'mydatabase';

// Create database connection
$conn = mysqli_connect($host, $username, $password, $dbname);
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

function sendResetEmail($email, $resetToken) {
    // Email content
    $to = $email;
    $subject = 'Password Reset Request';
    $message = '
        <html>
            <head></head>
            <body>
                <h2>Password Reset</h2>
                <p>Please click the link below to reset your password:</p>
                <a href="http://example.com/reset-password.php?token=' . $resetToken . '">Reset Password</a><br><br>
                <small>If you did not request this password reset, please ignore this email.</small>
            </body>
        </html>
    ';
    $headers = 'MIME-Version: 1.0' . "\r
";
    $headers .= 'Content-type: text/html; charset=UTF-8' . "\r
";
    
    // Send email
    mail($to, $subject, $message, $headers);
}

function generateResetToken() {
    return bin2hex(random_bytes(16));
}

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    
    // Check if email exists in database
    $sql = "SELECT id FROM users WHERE email='$email'";
    $result = mysqli_query($conn, $sql);
    
    if (mysqli_num_rows($result) == 1) {
        $resetToken = generateResetToken();
        
        // Store token and expiration time in database
        $expires = date('Y-m-d H:i:s', strtotime('+2 hours'));
        $sql = "INSERT INTO password_resets (user_id, token, expires) VALUES ('$email', '$resetToken', '$expires')";
        mysqli_query($conn, $sql);
        
        // Send reset email
        sendResetEmail($email, $resetToken);
        
        echo 'A password reset link has been sent to your email address.';
    } else {
        echo 'Email not found in our database.';
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Forgot Password</title>
</head>
<body>
    <h2>Forgot Password</h2>
    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
        <label for="email">Enter your email:</label><br>
        <input type="email" id="email" name="email"><br><br>
        <input type="submit" value="Reset Password">
    </form>
</body>
</html>


<?php
// Database configuration
$host = 'localhost';
$username = 'root';
$password = '';
$dbname = 'mydatabase';

$conn = mysqli_connect($host, $username, $password, $dbname);
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $token = $_POST['token'];
    $newPassword = $_POST['new_password'];
    $confirmPassword = $_POST['confirm_password'];
    
    // Check if token is valid and not expired
    $sql = "SELECT * FROM password_resets WHERE token='$token'";
    $result = mysqli_query($conn, $sql);
    
    if (mysqli_num_rows($result) == 1) {
        $row = mysqli_fetch_assoc($result);
        
        // Check if token is expired
        $expires = strtotime($row['expires']);
        if ($expires < time()) {
            echo 'This password reset link has expired.';
            exit;
        }
        
        // Validate passwords
        if ($newPassword != $confirmPassword) {
            echo 'Passwords do not match.';
            exit;
        }
        
        // Update user's password
        $userId = $row['user_id'];
        $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
        $sql = "UPDATE users SET password='$hashedPassword' WHERE id='$userId'";
        mysqli_query($conn, $sql);
        
        // Delete the token from the database
        $sql = "DELETE FROM password_resets WHERE token='$token'";
        mysqli_query($conn, $sql);
        
        echo 'Your password has been successfully reset.';
    } else {
        echo 'Invalid or expired password reset link.';
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Reset Password</title>
</head>
<body>
    <?php
    if (isset($_GET['token'])) {
        $token = $_GET['token'];
        
        // Verify token exists in database
        $sql = "SELECT * FROM password_resets WHERE token='$token'";
        $result = mysqli_query($conn, $sql);
        
        if (mysqli_num_rows($result) == 1) {
            echo '<h2>Reset Password</h2>';
            echo '<form action="' . $_SERVER['PHP_SELF'] . '" method="post">';
            echo '<input type="hidden" name="token" value="' . $token . '">';
            echo '<label for="new_password">New Password:</label><br>';
            echo '<input type="password" id="new_password" name="new_password"><br><br>';
            echo '<label for="confirm_password">Confirm Password:</label><br>';
            echo '<input type="password" id="confirm_password" name="confirm_password"><br><br>';
            echo '<input type="submit" value="Change Password">';
            echo '</form>';
        } else {
            echo 'Invalid or expired password reset link.';
        }
    } else {
        echo 'No token provided. Please request a new password reset.';
    }
    ?>
</body>
</html>


<?php
// Database configuration
$host = 'localhost';
$username = 'root';
$password = '';
$database = 'my_database';

// Create connection
$conn = new mysqli($host, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

function generateResetToken() {
    return bin2hex(random_bytes(16));
}

// Reset token expiration time (e.g., 1 hour)
$expires = date('Y-m-d H:i:s', strtotime('+1 hour'));

// forgot-password.php - This is the initial form
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);
    
    if (empty($email)) {
        die("Email is required!");
    }

    // Check if email exists in database
    $stmt = $conn->prepare("SELECT id, username FROM users WHERE email = ?");
    $stmt->bind_param('s', $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Generate reset token
        $resetToken = generateResetToken();
        
        // Store token in database
        $stmt2 = $conn->prepare("INSERT INTO password_reset_tokens (user_id, token, expires) VALUES (?, ?, ?)");
        $stmt2->bind_param('iss', $result->fetch_assoc()['id'], $resetToken, $expires);
        $stmt2->execute();

        // Send email with reset link
        $to = $email;
        $subject = "Password Reset Request";
        $message = "
            <html>
                <head></head>
                <body>
                    <h2>Password Reset</h2>
                    <p>We received a request to reset your password. Click the link below to reset it:</p>
                    <a href='http://example.com/reset-password.php?token=" . $resetToken . "'>Reset Password</a>
                    <br><br>
                    <p>If you didn't request this, you can safely ignore this email.</p>
                </body>
            </html>";
        $headers = "MIME-Version: 1.0" . "\r
";
        $headers .= "Content-type:text/html;charset=UTF-8" . "\r
";
        $headers .= "From: <your_email@example.com>" . "\r
";

        mail($to, $subject, $message, $headers);

        echo "We've sent you a password reset link. Please check your email.";
    } else {
        die("Email not found in our records!");
    }
}

// reset-password.php - This is the page where users can reset their password
if (isset($_GET['token'])) {
    $resetToken = $_GET['token'];
    
    // Check if token exists and hasn't expired
    $stmt3 = $conn->prepare("SELECT user_id FROM password_reset_tokens WHERE token = ? AND expires > NOW()");
    $stmt3->bind_param('s', $resetToken);
    $stmt3->execute();
    $result2 = $stmt3->get_result();

    if ($result2->num_rows == 0) {
        die("Invalid or expired reset link!");
    }

    // If token is valid and within time limit, display password reset form
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $userId = $result2->fetch_assoc()['user_id'];
        $newPassword = trim($_POST['password']);
        $confirmPassword = trim($_POST['confirm_password']);

        if ($newPassword != $confirmPassword) {
            die("Passwords do not match!");
        }

        // Password requirements (minimum length, etc.)
        if (strlen($newPassword) < 8) {
            die("Password must be at least 8 characters long!");
        }

        // Hash password
        $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);

        // Update user's password
        $stmt4 = $conn->prepare("UPDATE users SET password = ? WHERE id = ?");
        $stmt4->bind_param('si', $hashedPassword, $userId);
        $stmt4->execute();

        // Delete used token
        $stmt5 = $conn->prepare("DELETE FROM password_reset_tokens WHERE token = ?");
        $stmt5->bind_param('s', $resetToken);
        $stmt5->execute();

        echo "Your password has been reset successfully!";
    }
}

// Close database connection
$conn->close();
?>


<?php
include('db_connection.php'); // Include your database connection file

if (isset($_POST['email'])) {
    $email = $_POST['email'];

    // Check if email exists in the database
    $query = "SELECT * FROM users WHERE email = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 0) {
        header("Location: forgot_password_form.php?error=Email not found in our records.");
        exit();
    } else {
        // Generate a unique reset token
        $token = md5(uniqid(rand(), true));
        
        // Check if the email already has an active reset request
        $check_query = "SELECT * FROM password_resets WHERE email = ?";
        $check_stmt = $conn->prepare($check_query);
        $check_stmt->bind_param("s", $email);
        $check_stmt->execute();
        $check_result = $check_stmt->get_result();

        if ($check_result->num_rows > 0) {
            // Update the existing token
            $update_query = "UPDATE password_resets SET token = ?, expire_time = DATE_ADD(NOW(), INTERVAL 1 HOUR) WHERE email = ?";
            $update_stmt = $conn->prepare($update_query);
            $update_stmt->bind_param("ss", $token, $email);
            $update_stmt->execute();
        } else {
            // Insert new reset request
            $insert_query = "INSERT INTO password_resets (email, token, expire_time) 
                            VALUES (?, ?, DATE_ADD(NOW(), INTERVAL 1 HOUR))";
            $insert_stmt = $conn->prepare($insert_query);
            $insert_stmt->bind_param("ss", $email, $token);
            $insert_stmt->execute();
        }

        // Send the reset link to the user's email
        $reset_link = "http://your-site.com/reset_password.php?token=$token";
        $to = $email;
        $subject = "Password Reset Request";
        $message = "Please click on this link to reset your password: 
$reset_link
This link will expire in 1 hour.";
        
        if (mail($to, $subject, $message)) {
            header("Location: forgot_password_form.php?success=We've sent a password reset link to your email.");
            exit();
        } else {
            header("Location: forgot_password_form.php?error=Failed to send the reset email. Please try again.");
            exit();
        }
    }
}
?>


<?php
include('db_connection.php'); // Include your database connection file

if (isset($_GET['token'])) {
    $token = $_GET['token'];

    // Verify if the token exists and is valid
    $query = "SELECT * FROM password_resets 
              WHERE token = ?
              AND expire_time > NOW()";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 0) {
        header("Location: reset_password_form.php?error=Invalid or expired token.");
        exit();
    } else {
        // Update the user's password
        if (isset($_POST['new_password']) && isset($_POST['confirm_password'])) {
            $new_password = $_POST['new_password'];
            $confirm_password = $_POST['confirm_password'];

            if ($new_password != $confirm_password) {
                header("Location: reset_password_form.php?error=Passwords do not match.");
                exit();
            }

            // Update the password in the users table
            $user_data = $result->fetch_assoc();
            $email = $user_data['email'];
            
            $update_query = "UPDATE users 
                            SET password = ?
                            WHERE email = ?";
            $update_stmt = $conn->prepare($update_query);
            $update_stmt->bind_param("ss", md5($new_password), $email); // Use a secure hashing method
            $update_stmt->execute();

            // Delete the reset token
            $delete_query = "DELETE FROM password_resets WHERE token = ?";
            $delete_stmt = $conn->prepare($delete_query);
            $delete_stmt->bind_param("s", $token);
            $delete_stmt->execute();

            header("Location: login.php?success=Password has been successfully reset.");
            exit();
        }
    }
} else {
    header("Location: forgot_password_form.php");
    exit();
}
?>


<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "your_database_name";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>


<?php
// Database connection details
$host = 'localhost';
$username = 'root';
$password = '';
$dbname = 'mydatabase';

// Connect to database
$conn = mysqli_connect($host, $username, $password, $dbname);
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize email input
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    
    if (empty($email)) {
        header("Location: forgot_password_form.php?msg=Please enter your email address.");
        exit();
    }
    
    // Check if email exists in database
    $sql = "SELECT id FROM users WHERE email='$email'";
    $result = mysqli_query($conn, $sql);
    
    if (mysqli_num_rows($result) == 0) {
        header("Location: forgot_password_form.php?msg=Email not found.");
        exit();
    }
    
    // Generate random password reset token
    $token = md5(rand());
    
    // Update the database with the token and expiration time (1 hour)
    $expires = date('Y-m-d H:i:s', time() + 3600);
    $sql = "UPDATE users SET reset_token='$token', reset_expires='$expires' WHERE email='$email'";
    mysqli_query($conn, $sql);
    
    // Send email with reset link
    $to = $email;
    $subject = "Password Reset Request";
    $message = "Please click the following link to reset your password: http://example.com/reset_password.php?token=$token";
    $headers = "From: webmaster@example.com" . "\r
" .
               "Content-type:text/html;charset=UTF-8";
    
    if (mail($to, $subject, $message, $headers)) {
        header("Location: forgot_password_form.php?msg=Password reset link sent to your email.");
    } else {
        header("Location: forgot_password_form.php?msg=Error sending email.");
    }
}

mysqli_close($conn);
?>


<?php
// Database connection details
$host = 'localhost';
$username = 'root';
$password = '';
$dbname = 'mydatabase';

// Connect to database
$conn = mysqli_connect($host, $username, $password, $dbname);
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Check if token is valid and not expired
$token = $_GET['token'];

$sql = "SELECT id, reset_token, reset_expires FROM users WHERE reset_token='$token'";
$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result);

if (!$row || $row['reset_expires'] < date('Y-m-d H:i:s')) {
    die("Invalid or expired password reset link.");
}

?>

<!DOCTYPE html>
<html>
<head>
    <title>Reset Password</title>
</head>
<body>
    <h2>Reset Password</h2>
    
    <form action="reset_password.php" method="post">
        <input type="hidden" name="token" value="<?php echo $token; ?>">
        
        <label for="new_password">New Password:</label><br>
        <input type="password" id="new_password" name="new_password"><br><br>
        
        <label for="confirm_password">Confirm Password:</label><br>
        <input type="password" id="confirm_password" name="confirm_password"><br><br>
        
        <input type="submit" value="Reset Password">
    </form>
</body>
</html>

<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $token = $_POST['token'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];
    
    // Validate password requirements
    if ($new_password != $confirm_password) {
        die("Passwords do not match.");
    }
    
    if (strlen($new_password) < 8) {
        die("Password must be at least 8 characters long.");
    }
    
    // Additional password complexity checks can be added here
    
    // Update the user's password
    $hashed_password = md5($new_password); // Note: Consider using a stronger hashing algorithm like bcrypt
    $sql = "UPDATE users SET password='$hashed_password', reset_token='', reset_expires='' WHERE reset_token='$token'";
    mysqli_query($conn, $sql);
    
    header("Location: login.php?msg=Password has been reset successfully.");
}

mysqli_close($conn);
?>


<?php
// Include database configuration file
require_once 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if email is provided and not empty
    $email = trim($_POST['email']);
    
    // Validate email format
    if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
        // Check if the email exists in the database
        $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->bind_param('s', $email);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            // Generate a unique reset token
            $resetToken = bin2hex(random_bytes(16));
            
            // Set the expiration time (e.g., 1 hour from now)
            $expires = date('Y-m-d H:i:s', strtotime('+1 hour'));
            
            // Update the users table with the new reset token and expiration time
            $stmtReset = $conn->prepare("UPDATE users SET reset_token = ?, reset_expires = ? WHERE email = ?");
            $stmtReset->bind_param('sss', $resetToken, $expires, $email);
            $stmtReset->execute();
            
            // Send the password reset email
            $to = $email;
            $subject = "Password Reset Request";
            $message = "Please click on the following link to reset your password:

";
            $message .= "http://example.com/reset-password.php?token=" . urlencode($resetToken) . "&email=" . urlencode($email);
            $headers = "From: no-reply@example.com\r
";
            mail($to, $subject, $message, $headers);
            
            echo "Password reset email has been sent to your email address.";
        } else {
            echo "Email does not exist in our database.";
        }
    } else {
        echo "Please enter a valid email address.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Forgot Password</title>
</head>
<body>
    <h2>Forgot Password</h2>
    <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
        <input type="email" name="email" placeholder="Enter your email address" required>
        <button type="submit">Reset Password</button>
    </form>
</body>
</html>


<?php
// Include database configuration
include('config.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);

    // Check if email is not empty and valid
    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        die("Invalid email format");
    }

    // Check if email exists in the database
    try {
        $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        if (!$user) {
            die("Email not found. Please check your email and try again.");
        }

        // Generate a random token
        $token = bin2hex(random_bytes(32));

        // Set token expiration to 1 hour from now
        $expires = date('Y-m-d H:i:s', strtotime('+1 hour'));

        // Update the database with the new token and expiration time
        $stmt = $pdo->prepare("UPDATE users SET reset_token = ?, reset_expires = ? WHERE email = ?");
        $stmt->execute([$token, $expires, $email]);

        // Send email to user
        $to = $email;
        $subject = "Password Reset Request";
        
        $message = "
            <html>
            <head></head>
            <body>
                <h2>Password Reset</h2>
                <p>Please click the link below to reset your password:</p>
                <a href='http://example.com/reset_password.php?token=$token&id=$user[id]'>
                    http://example.com/reset_password.php?token=$token&id=$user[id]
                </a>
                <br><br>
                <p>If you did not request this password reset, please ignore this email.</p>
            </body>
            </html>
        ";

        // Set headers for HTML email
        $headers = "MIME-Version: 1.0" . "\r
";
        $headers .= "Content-type:text/html;charset=UTF-8" . "\r
";
        $headers .= "From: <noreply@example.com>" . "\r
";

        // Send the email
        if (mail($to, $subject, $message, $headers)) {
            echo "An email has been sent to your address. Please check your inbox.";
        } else {
            die("Failed to send password reset email.");
        }
    } catch(PDOException $e) {
        die("Database error: " . $e->getMessage());
    }
}
?>


<?php
// Include database configuration
include('config.php');

if (isset($_GET['token']) && isset($_GET['id'])) {
    $token = $_GET['token'];
    $id = $_GET['id'];

    // Check if token is valid and not expired
    try {
        $stmt = $pdo->prepare("SELECT * FROM users WHERE id = ? AND reset_token = ? AND reset_expires > NOW()");
        $stmt->execute([$id, $token]);
        $user = $stmt->fetch();

        if (!$user) {
            die("Invalid or expired token. Please request a new password reset.");
        }

    } catch(PDOException $e) {
        die("Database error: " . $e->getMessage());
    }
} else {
    die("Missing parameters. Please use the link from your email.");
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Reset Password</title>
</head>
<body>
    <h2>Reset Password</h2>
    <form action="reset_password.php?token=<?php echo $token; ?>&id=<?php echo $id; ?>" method="post">
        New Password: <input type="password" name="new_password" required><br>
        Confirm Password: <input type="password" name="confirm_password" required><br>
        <button type="submit">Reset Password</button>
    </form>
</body>
</html>


<?php
// Include database configuration
include('config.php');

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_GET['token']) && isset($_GET['id'])) {
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    // Check if passwords match
    if ($new_password !== $confirm_password) {
        die("Passwords do not match. Please try again.");
    }

    // Validate password length (minimum 8 characters)
    if (strlen($new_password) < 8) {
        die("Password must be at least 8 characters long.");
    }

    $token = $_GET['token'];
    $id = $_GET['id'];

    try {
        // Check token again
        $stmt = $pdo->prepare("SELECT * FROM users WHERE id = ? AND reset_token = ? AND reset_expires > NOW()");
        $stmt->execute([$id, $token]);
        $user = $stmt->fetch();

        if (!$user) {
            die("Invalid or expired token. Please request a new password reset.");
        }

        // Hash the new password
        $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

        // Update the password and clear the reset token
        $stmt = $pdo->prepare("UPDATE users SET password = ?, reset_token = NULL, reset_expires = NULL WHERE id = ?");
        $stmt->execute([$hashed_password, $id]);

        echo "Password successfully updated. You can now <a href='login.php'>login</a> with your new password.";

    } catch(PDOException $e) {
        die("Database error: " . $e->getMessage());
    }
}
?>


<?php
// Database configuration
$host = 'localhost';
$username = 'root';
$password = '';
$dbname = 'mydatabase';

// Create connection
$conn = new mysqli($host, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// If form is submitted
if (isset($_POST['submit'])) {
    // Get email from form
    $email = $_POST['email'];

    // Validate email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "Invalid email format";
        exit();
    }

    // Check if email exists in database
    $sql = "SELECT id FROM users WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        echo "Email not found in database";
        exit();
    }

    // Generate temporary password
    $tempPassword = generateTempPassword(8);

    // Update user's password with temporary password
    $updateSql = "UPDATE users SET password = ? WHERE email = ?";
    $stmt = $conn->prepare($updateSql);
    $stmt->bind_param("ss", $tempPassword, $email);
    $stmt->execute();

    // Send email to user with temporary password
    $to = $email;
    $subject = "Your Temporary Password";
    $message = "Your temporary password is: " . $tempPassword . "
Please login and reset your password.";
    $headers = "From: admin@example.com";

    mail($to, $subject, $message, $headers);

    // Redirect to login page
    header("Location: forgot_password.php?success=1");
    exit();
}

// Function to generate temporary password
function generateTempPassword($length) {
    $characters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
    $password = '';
    for ($i = 0; $i < $length; $i++) {
        $randomIndex = rand(0, strlen($characters) - 1);
        $password .= $characters[$randomIndex];
    }
    return $password;
}

// Close database connection
$conn->close();
?>


<?php
session_start();
include('db_connection.php'); // Include your database connection file

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $username = $_POST['username'];

    // Generate a unique token for password reset
    $token = bin2hex(random_bytes(16));
    $token_expiry = date('Y-m-d H:i:s', strtotime('+1 hour'));

    // Check if user exists with the provided email or username
    $sql = "SELECT * FROM users WHERE email = ? OR username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $email, $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // User exists, store the token in the database
        $row = $result->fetch_assoc();
        $user_id = $row['id'];

        $insert_sql = "INSERT INTO password_reset (user_id, token, created_at) 
                       VALUES (?, ?, ?)";
        $stmt_insert = $conn->prepare($insert_sql);
        $stmt_insert->bind_param("iss", $user_id, $token, $token_expiry);

        if ($stmt_insert->execute()) {
            // Send email with reset link
            $reset_link = "http://yourdomain.com/reset_password.php?token=" . $token;
            $to = $email;
            $subject = "Password Reset Request";
            $message = "Please click the following link to reset your password: 
" . $reset_link . "

This link will expire in 1 hour.";

            if (send_email($to, $subject, $message)) {
                $_SESSION['message'] = "A password reset link has been sent to your email address.";
                header("Location: forgot_password.php");
                exit();
            }
        } else {
            $_SESSION['message'] = "Error storing reset token. Please try again later.";
            header("Location: forgot_password.php");
            exit();
        }
    } else {
        $_SESSION['message'] = "Email or username does not exist.";
        header("Location: forgot_password.php");
        exit();
    }
}

// Function to send email
function send_email($to, $subject, $message) {
    // Use PHPMailer or your preferred method here
    $headers = 'From: webmaster@example.com' . "\r
" .
               'Reply-To: webmaster@example.com' . "\r
" .
               'X-Mailer: PHP/' . phpversion();
    return mail($to, $subject, $message, $headers);
}
?>


<?php
session_start();

include('db_connection.php');

if (!isset($_GET['token'])) {
    die("Invalid request.");
}

$token = $_GET['token'];

// Check if token exists and is valid
$sql = "SELECT * FROM password_reset 
        WHERE token = ? AND created_at > DATE_SUB(NOW(), INTERVAL 1 HOUR)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $token);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    die("Invalid or expired token.");
}

$user_data = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Reset Password</title>
</head>
<body>
    <?php
    if (isset($_SESSION['message'])) {
        echo $_SESSION['message'];
        unset($_SESSION['message']);
    }
    ?>

    <h2>Reset Password</h2>
    <form action="reset_password_process.php" method="post">
        <input type="hidden" name="token" value="<?php echo $token; ?>">
        
        <label for="new_password">New Password:</label><br>
        <input type="password" id="new_password" name="new_password" required><br>

        <label for="confirm_password">Confirm Password:</label><br>
        <input type="password" id="confirm_password" name="confirm_password" required><br>

        <input type="submit" value="Reset Password">
    </form>
</body>
</html>


<?php
session_start();

include('db_connection.php');

if (!isset($_POST['token'])) {
    die("Invalid request.");
}

$token = $_POST['token'];
$new_password = $_POST['new_password'];
$confirm_password = $_POST['confirm_password'];

// Validate passwords match and meet criteria
if ($new_password != $confirm_password) {
    $_SESSION['message'] = "Passwords do not match.";
    header("Location: reset_password.php?token=" . $token);
    exit();
}

if (strlen($new_password) < 8) {
    $_SESSION['message'] = "Password must be at least 8 characters long.";
    header("Location: reset_password.php?token=" . $token);
    exit();
}

// Check if token is valid and not expired
$sql = "SELECT * FROM password_reset 
        WHERE token = ? AND created_at > DATE_SUB(NOW(), INTERVAL 1 HOUR)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $token);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    die("Invalid or expired token.");
}

$user_data = $result->fetch_assoc();

// Update the user's password
$new_password_hashed = password_hash($new_password, PASSWORD_DEFAULT);

$update_sql = "UPDATE users SET password = ? WHERE id = ?";
$stmt_update = $conn->prepare($update_sql);
$stmt_update->bind_param("si", $new_password_hashed, $user_data['user_id']);

if ($stmt_update->execute()) {
    // Invalidate the reset token
    $invalidate_sql = "DELETE FROM password_reset WHERE token = ?";
    $stmt_invalidate = $conn->prepare($invalidate_sql);
    $stmt_invalidate->bind_param("s", $token);
    $stmt_invalidate->execute();

    $_SESSION['message'] = "Password has been successfully reset.";
    header("Location: login.php");
    exit();
} else {
    $_SESSION['message'] = "Error resetting password. Please try again later.";
    header("Location: reset_password.php?token=" . $token);
    exit();
}
?>


<?php
// Database connection
$servername = "localhost";
$username = "username";
$password = "password";
$dbname = "myDB";

$conn = mysqli_connect($servername, $username, $password, $dbname);
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Session start
session_start();

// Error messages initialization
$error = array();
$email = '';

// If form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate email
    if (empty($_POST['email'])) {
        $error[] = "Email is required";
    } else {
        $email = mysqli_real_escape_string($conn, $_POST['email']);
        // Check if email exists in the database
        $sql = "SELECT id FROM users WHERE email='$email'";
        $result = mysqli_query($conn, $sql);
        
        if (mysqli_num_rows($result) == 0) {
            $error[] = "Email not found. Please enter a valid email address.";
        } else {
            // Generate a random token
            $token = bin2hex(random_bytes(16));
            
            // Set token expiration time (e.g., 2 hours)
            $expires = date('Y-m-d H:i:s', time() + 7200);
            
            // Update the token and expiration in the database
            $sql = "UPDATE users SET reset_token='$token', reset_expires='$expires' WHERE email='$email'";
            mysqli_query($conn, $sql);
            
            // Send password reset link to user's email
            $reset_link = "http://$_SERVER[HTTP_HOST]/reset_password.php?token=$token&id=" . mysqli_insert_id($conn);
            send_reset_email($email, $reset_link);
            
            // Success message
            header("Location: forgot_password_success.php");
            exit();
        }
    }
}

// Function to send reset email
function send_reset_email($to, $link) {
    require 'PHPMailer/PHPMailerAutoload.php';
    
    $mail = new PHPMailer;
    
    $mail->isSMTP();
    $mail->Host = 'smtp.example.com'; // Replace with your SMTP server
    $mail->Port = 587; // Replace with your SMTP port
    $mail->SMTPSecure = 'tls'; // Enable TLS encryption, `ssl` also acceptable
    
    $mail->SMTPAuth = true;
    $mail->Username = 'your_email@example.com';
    $mail->Password = 'your_password';
    
    $mail->setFrom('your_email@example.com', 'Your Name');
    $mail->addAddress($to);
    $mail->Subject = 'Password Reset Request';
    $mail->Body    = "Click the following link to reset your password: $link";
    
    if (!$mail->send()) {
        echo 'Mailer Error: ' . $mail->ErrorInfo;
    }
}

// Close database connection
mysqli_close($conn);
?>


<?php
// forgot-password-process.php

$servername = "localhost";
$username = "username";
$password = "password";
$dbname = "myDB";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$email = $_POST['email'];

// Check if email exists in the database
$sql = "SELECT id FROM users WHERE email='$email'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // Generate a random token
    $token = bin2hex(random_bytes(16));
    
    // Store the token and expiration time in the database
    $expires = date('Y-m-d H:i:s', time() + 3600); // Token expires after 1 hour
    
    $sql = "INSERT INTO password_resets (user_id, token, created_at) VALUES ('$id', '$token', '$expires')";
    if ($conn->query($sql)) {
        // Send email with reset link
        $to = $email;
        $subject = "Password Reset Request";
        $message = "Please click the following link to reset your password: http://example.com/reset-password.php?token=$token";
        $headers = "From: noreply@example.com\r
";
        
        if (mail($to, $subject, $message, $headers)) {
            echo "An email has been sent with instructions to reset your password.";
        } else {
            echo "Error sending email.";
        }
    } else {
        echo "Error storing token: " . $conn->error;
    }
} else {
    echo "Email not found in our records.";
}

$conn->close();
?>


<?php
// reset-password.php

$servername = "localhost";
$username = "username";
$password = "password";
$dbname = "myDB";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$token = $_GET['token'];

// Check if token exists and is not expired
$sql = "SELECT * FROM password_resets WHERE token='$token'";
$result = $conn->query($sql);

if ($result->num_rows == 1) {
    $row = $result->fetch_assoc();
    $expires = $row['created_at'];
    
    if (strtotime($expires) < time()) {
        echo "This reset link has expired.";
        exit;
    }
} else {
    echo "Invalid or non-existent token.";
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Process password reset
    $new_password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    
    if ($new_password != $confirm_password) {
        die("Passwords do not match.");
    }
    
    // Update the user's password
    $sql = "UPDATE users SET password='" . password_hash($new_password, PASSWORD_DEFAULT) . "' WHERE id='" . $row['user_id'] . "'";
    if ($conn->query($sql)) {
        // Invalidate the token after use
        $sql = "DELETE FROM password_resets WHERE token='$token'";
        $conn->query($sql);
        
        echo "Password updated successfully!";
    } else {
        echo "Error updating password: " . $conn->error;
    }
}

?>

<!DOCTYPE html>
<html>
<head>
    <title>Reset Password</title>
</head>
<body>
    <h2>Reset Password</h2>
    <form action="<?php echo $_SERVER['PHP_SELF'] ?>?token=<?php echo $token ?>" method="post">
        <label>New Password:</label><br>
        <input type="password" name="password" required><br><br>
        <label>Confirm New Password:</label><br>
        <input type="password" name="confirm_password" required><br><br>
        <input type="submit" value="Reset Password">
    </form>
</body>
</html>

<?php
$conn->close();
?>


<?php
// Include database connection
include 'db_connection.php';

// Function to handle forgot password
function forgotPassword($email) {
    // Check if email is provided
    if (empty($email)) {
        return "Email is required";
    }

    // Validate email format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return "Invalid email format";
    }

    // Check if email exists in the database
    $sql = "SELECT id FROM users WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        return "No account found with this email";
    }

    // Generate reset token
    $token = bin2hex(random_bytes(16));
    
    // Set expiration time (e.g., 1 hour from now)
    $expires = date('Y-m-d H:i:s', strtotime('+1 hour'));

    // Update the database with the new token and expiration time
    $sql = "UPDATE users SET reset_token = ?, reset_expires = ? WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sss", $token, $expires, $email);
    $stmt->execute();

    // Send reset password email
    $subject = 'Password Reset Request';
    $message = "Please click the following link to reset your password:

";
    $message .= "http://example.com/reset-password.php?token=$token";
    
    if (mail($email, $subject, $message)) {
        return "Password reset email has been sent. Please check your inbox.";
    } else {
        return "Failed to send password reset email.";
    }
}

// Example usage:
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    echo forgotPassword($email);
}
?>


<?php
// Include database connection
include 'db_connection.php';

if (isset($_GET['token'])) {
    $token = $_GET['token'];
    
    // Check if token exists and hasn't expired
    $sql = "SELECT id, email FROM users WHERE reset_token = ? AND reset_expires > NOW()";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        die("Invalid or expired token");
    }

    // Show password reset form
    ?>
    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
        New Password: <input type="password" name="new_password"><br>
        Confirm Password: <input type="password" name="confirm_password"><br>
        <input type="hidden" name="token" value="<?php echo $token; ?>">
        <input type="submit" value="Reset Password">
    </form>
    <?php
} else {
    die("No token provided");
}

// Handle password reset submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $token = $_POST['token'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    if ($new_password !== $confirm_password) {
        die("Passwords do not match");
    }

    // Update password
    $sql = "UPDATE users SET password = ?, reset_token = NULL, reset_expires = NULL WHERE reset_token = ?";
    $stmt = $conn->prepare($sql);
    $hashedPassword = password_hash($new_password, PASSWORD_DEFAULT);
    $stmt->bind_param("ss", $hashedPassword, $token);
    $stmt->execute();

    echo "Password has been successfully updated!";
}
?>


<?php
session_start();
include('db_connection.php'); // Include your database connection

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = mysqli_real_escape_string($conn, $_POST['email']);

    // Check if email exists in the database
    $query = "SELECT id FROM users WHERE email='$email'";
    $result = mysqli_query($conn, $query);
    
    if (mysqli_num_rows($result) == 0) {
        die("Email not found. Please check your email and try again.");
    }

    // Generate a random token
    $token = bin2hex(random_bytes(16));
    $expires = date('Y-m-d H:i:s', strtotime('+1 hour'));

    // Update the database with the new token and expiration time
    $updateQuery = "UPDATE users SET reset_token='$token', reset_expires='$expires' WHERE email='$email'";
    mysqli_query($conn, $updateQuery);

    // Send email to user
    $to = $email;
    $subject = 'Password Reset Request';
    
    $message = '
        <html>
            <head>
                <title>Password Reset</title>
            </head>
            <body>
                <h2>Password Reset</h2>
                <p>Please click the following link to reset your password:</p>
                <a href="http://example.com/reset_password.php?token=' . $token . '">Reset Password</a><br><br>
                <small>This link will expire in one hour.</small>
            </body>
        </html>
    ';
    
    // Set headers for email
    $headers = 'MIME-Version: 1.0' . "\r
";
    $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r
";
    $headers .= 'From: noreply@example.com' . "\r
";

    // Send the email
    mail($to, $subject, $message, $headers);

    // Redirect back to a message page
    header("Location: password_reset_sent.php");
}
?>


<?php
session_start();
include('db_connection.php');

if (!isset($_GET['token'])) {
    die("Invalid request.");
}

$token = mysqli_real_escape_string($conn, $_GET['token']);

// Check if the token exists and hasn't expired
$query = "SELECT id, reset_expires FROM users WHERE reset_token='$token'";
$result = mysqli_query($conn, $query);

if (mysqli_num_rows($result) == 0) {
    die("Invalid or expired token.");
}

$row = mysqli_fetch_assoc($result);
$expires = $row['reset_expires'];

if ($expires < date('Y-m-d H:i:s')) {
    die("Token has expired. Please request a new password reset.");
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Reset Password</title>
</head>
<body>
    <h2>Reset Password</h2>
    <form action="reset_password.php?token=<?php echo $token; ?>" method="post">
        <label for="password">New Password:</label><br>
        <input type="password" id="password" name="password"><br><br>
        <label for="confirm_password">Confirm New Password:</label><br>
        <input type="password" id="confirm_password" name="confirm_password"><br><br>
        <input type="submit" value="Reset Password">
    </form>
</body>
</html>

<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $newPassword = mysqli_real_escape_string($conn, $_POST['password']);
    $confirmPassword = mysqli_real_escape_string($conn, $_POST['confirm_password']);

    if ($newPassword != $confirmPassword) {
        die("Passwords do not match.");
    }

    // Update the password
    $hash = password_hash($newPassword, PASSWORD_DEFAULT);
    
    $updateQuery = "UPDATE users SET password='$hash', reset_token='', reset_expires='' WHERE reset_token='$token'";
    mysqli_query($conn, $updateQuery);

    header("Location: password_reset_success.php");
}
?>


<?php
session_start();
include('config.php');

// Function to handle password reset request
function forgotPassword() {
    $email = $_POST['email'];
    
    // Check if email exists in the database
    $stmt = $conn->prepare("SELECT id, username FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        // Generate a random token
        $token = bin2hex(random_bytes(16));
        
        // Insert the token into the database with an expiration time (e.g., 1 hour)
        $expires = date('Y-m-d H:i:s', time() + 3600);
        $stmt = $conn->prepare("INSERT INTO reset_tokens (user_id, token, expires) VALUES (?, ?, ?)");
        $stmt->bind_param("iss", $row['id'], $token, $expires);
        $stmt->execute();
        
        // Send email with reset link
        $resetLink = "http://yourwebsite.com/reset-password.php?token=" . $token;
        sendResetEmail($email, $resetLink);
    } else {
        echo "Email not found in our records.";
    }
}

// Function to validate and process the password reset token
function validateToken() {
    $token = $_GET['token'];
    
    // Check if token exists and hasn't expired
    $stmt = $conn->prepare("SELECT user_id FROM reset_tokens WHERE token = ? AND expires > NOW()");
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        // Token is valid, show password reset form
        echo "<form action='reset-password.php' method='post'>
                <input type='password' name='new_password' placeholder='Enter new password'>
                <button type='submit'>Reset Password</button>
              </form>";
    } else {
        echo "Invalid or expired token.";
    }
}

// Function to handle password reset
function resetPassword() {
    $token = $_GET['token'];
    $newPassword = $_POST['new_password'];
    
    // Check if token is valid
    $stmt = $conn->prepare("SELECT user_id FROM reset_tokens WHERE token = ?");
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        // Update the password
        $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
        $user_id = $row['id'];
        
        $stmt = $conn->prepare("UPDATE users SET password = ? WHERE id = ?");
        $stmt->bind_param("si", $hashedPassword, $user_id);
        $stmt->execute();
        
        // Delete the token after use
        $stmt = $conn->prepare("DELETE FROM reset_tokens WHERE token = ?");
        $stmt->bind_param("s", $token);
        $stmt->execute();
        
        echo "Password reset successful!";
    } else {
        echo "Invalid or expired token.";
    }
}

// Function to send the password reset email
function sendResetEmail($email, $resetLink) {
    $to = $email;
    $subject = "Password Reset Request";
    $message = "Click on the following link to reset your password: " . $resetLink;
    $headers = "From: yourwebsite@example.com\r
";
    
    mail($to, $subject, $message, $headers);
}

// Example usage:
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    forgotPassword();
} elseif (isset($_GET['token'])) {
    validateToken();
}
?>


<?php
// Database connection details
$host = 'localhost';
$username = 'root';
$password = '';
$dbname = 'your_database';

// Create database connection
$conn = new mysqli($host, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Function to handle forgot password process
function forgotPassword($email, $conn) {
    // Validate email
    if (empty($email)) {
        return "Email is required";
    }
    
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return "Invalid email format";
    }

    // Check if email exists in the database
    $sql = "SELECT id FROM users WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        return "Email not found in our records";
    }

    // Generate reset token
    $token = bin2hex(random_bytes(16));
    $expires = date('Y-m-d H:i:s', strtotime('+1 hour'));

    // Update the database with the new token and expiration time
    $updateSql = "UPDATE users SET reset_token = ?, reset_expires = ? WHERE email = ?";
    $stmt = $conn->prepare($updateSql);
    $stmt->bind_param("sss", $token, $expires, $email);

    if (!$stmt->execute()) {
        return "An error occurred. Please try again later";
    }

    // Send reset email
    $to = $email;
    $subject = "Password Reset Request";
    $message = "
        <html>
            <head></head>
            <body>
                <p>You requested a password reset. Click the link below to reset your password:</p>
                <a href='http://yourwebsite.com/reset-password.php?token=" . $token . "&email=" . urlencode($email) . "'>Reset Password</a>
                <br>
                <small>This link will expire in 1 hour.</small>
            </body>
        </html>
    ";
    
    // Set headers for email
    $headers = "MIME-Version: 1.0" . "\r
";
    $headers .= "Content-type:text/html;charset=UTF-8" . "\r
";
    $headers .= "From: <yourwebsite@example.com>" . "\r
";

    if (mail($to, $subject, $message, $headers)) {
        return "Password reset email has been sent to your email address";
    } else {
        return "Failed to send password reset email. Please try again later.";
    }
}

// Example usage:
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $result = forgotPassword($email, $conn);
    echo $result;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Forgot Password</title>
</head>
<body>
    <h2>Forgot Password</h2>
    <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
        <input type="email" name="email" placeholder="Enter your email" required><br><br>
        <button type="submit">Reset Password</button>
    </form>
</body>
</html>

// Close database connection
$conn->close();
?>


<?php
// Database connection
$host = 'localhost';
$username = 'root';
$password = '';
$dbname = 'your_database';

$conn = new mysqli($host, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if form is submitted
if (isset($_POST['submit'])) {
    $email = $_POST['email'];
    
    // Sanitize email input
    $email = mysqli_real_escape_string($conn, $email);

    // Check if email exists in database
    $sql = "SELECT id FROM users WHERE email='$email'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // Generate a random token for password reset
        $token = bin2hex(random_bytes(16));
        
        // Insert token into the database
        $sql_token = "INSERT INTO password_reset (user_id, token) VALUES ('$id', '$token')";
        $conn->query($sql_token);
        
        // Send email with reset link
        $to = $email;
        $subject = 'Password Reset Request';
        $message = 'Please click the following link to reset your password: http://yourwebsite.com/reset_password.php?token=' . $token;
        $headers = 'From: webmaster@yourwebsite.com';

        if (mail($to, $subject, $message, $headers)) {
            echo "An email has been sent with instructions to reset your password.";
        } else {
            echo "Error sending email.";
        }
    } else {
        echo "Email address not found in our records.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Forgot Password</title>
</head>
<body>
    <h2>Forgot Password</h2>
    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
        <label for="email">Email:</label><br>
        <input type="email" id="email" name="email" required><br><br>
        <input type="submit" name="submit" value="Submit">
    </form>
</body>
</html>


<?php
// Database connection
$host = 'localhost';
$username = 'root';
$password = '';
$dbname = 'your_database';

$conn = new mysqli($host, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get token from URL
$token = $_GET['token'];

// Check if token exists in database and is not expired
$sql = "SELECT * FROM password_reset WHERE token='$token'";
$result = $conn->query($sql);

if ($result->num_rows == 1) {
    // Show reset form
    ?>
    <!DOCTYPE html>
    <html>
    <head>
        <title>Reset Password</title>
    </head>
    <body>
        <h2>Reset Password</h2>
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
            <label for="password">New Password:</label><br>
            <input type="password" id="password" name="password" required><br><br>
            <label for="confirm_password">Confirm Password:</label><br>
            <input type="password" id="confirm_password" name="confirm_password" required><br><br>
            <input type="hidden" name="token" value="<?php echo $token; ?>">
            <input type="submit" name="reset" value="Reset Password">
        </form>
    </body>
    </html>
    <?php
} else {
    // Token is invalid or expired
    die("Invalid or expired token.");
}

// Update password if form is submitted
if (isset($_POST['reset'])) {
    $token = $_POST['token'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    if ($password == $confirm_password) {
        // Hash the new password
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        
        // Update the user's password in the database
        $sql_update = "UPDATE users SET password='$hashed_password' WHERE id=(SELECT user_id FROM password_reset WHERE token='$token')";
        $conn->query($sql_update);
        
        // Delete the reset token
        $sql_delete = "DELETE FROM password_reset WHERE token='$token'";
        $conn->query($sql_delete);
        
        echo "Your password has been reset successfully!";
    } else {
        echo "Passwords do not match.";
    }
}
?>


<?php
// Database connection parameters
$host = 'localhost';
$username = 'username';
$password = 'password';
$database = 'my_database';

// Connect to the database
$conn = mysqli_connect($host, $username, $password, $database);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Check if form is submitted
if (isset($_POST['submit'])) {
    // Sanitize input email
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    
    // Validate email format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "Invalid email format";
        exit();
    }
    
    // Check if email exists in the database
    $sql = "SELECT id FROM users WHERE email = '$email'";
    $result = mysqli_query($conn, $sql);
    
    if (mysqli_num_rows($result) == 0) {
        echo "Email not found. Please check your email and try again.";
        exit();
    }
    
    // Generate temporary password
    $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*()';
    $length = strlen($chars);
    $temp_password = substr(str_shuffle($chars), 0, 10);
    
    // Hash the temporary password
    $hashed_password = password_hash($temp_password, PASSWORD_DEFAULT);
    
    // Update the user's password in the database
    $update_sql = "UPDATE users SET password = '$hashed_password' WHERE email = '$email'";
    mysqli_query($conn, $update_sql);
    
    // Send the temporary password to the user's email
    $to = $email;
    $subject = 'Your Temporary Password';
    $message = "Dear User,

Your temporary password is: " . $temp_password . "

Please log in and change your password immediately.

Best regards,
Support Team";
    
    // Set headers
    $headers = 'From: support@yourdomain.com' . "\r
" .
               'Reply-To: support@yourdomain.com' . "\r
" .
               'X-Mailer: PHP/' . phpversion();
    
    if (mail($to, $subject, $message, $headers)) {
        // Redirect to a confirmation page
        header("Location: password_reset_confirmation.php");
        exit();
    } else {
        echo "An error occurred while sending the email. Please try again later.";
        exit();
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Forgot Password</title>
</head>
<body>
    <h2>Forgot Password</h2>
    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
        <label for="email">Email:</label><br>
        <input type="text" id="email" name="email"><br><br>
        <input type="submit" name="submit" value="Submit">
    </form>
</body>
</html>


<?php
// Database connection file
include("db_connect.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    
    // Check if email exists in database
    $sql = "SELECT id FROM users WHERE email = '$email'";
    $result = mysqli_query($conn, $sql);
    
    if (mysqli_num_rows($result) == 1) {
        // Generate a temporary password
        $temp_password = generate_temp_password();
        
        // Update the user's password in the database
        $update_sql = "UPDATE users SET password = '" . md5($temp_password) . "' WHERE email = '$email'";
        mysqli_query($conn, $update_sql);
        
        // Send the temporary password to the user's email
        $to = $email;
        $subject = "Your Temporary Password";
        $message = "Dear User,

Your temporary password is: " . $temp_password . "

Please login and change your password immediately.

Best regards,
Admin Team";
        
        // Set headers for email
        $headers = "From: admin@example.com\r
";
        $headers .= "Reply-To: admin@example.com\r
";
        $headers .= "X-Mailer: PHP/" . phpversion();
        
        if (mail($to, $subject, $message, $headers)) {
            echo "Password reset email has been sent to your email address.";
        } else {
            echo "Failed to send password reset email. Please try again later.";
        }
    } else {
        echo "This email does not exist in our database.";
    }
}

function generate_temp_password() {
    $length = 8;
    $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*";
    $temp_pass = substr(str_shuffle($chars), 0, $length);
    return $temp_pass;
}
?>


<?php
// Database configuration
$host = 'localhost';
$dbname = 'your_database';
$username = 'root';
$password = '';

try {
    $conn = new PDO("mysql:host=$host;dbname=$db", $username, $password);
} catch (PDOException $e) {
    die("Could not connect to the database: " . $e->getMessage());
}

// CSRF token
function generateToken() {
    return bin2hex(random_bytes(32));
}

// Generate reset link
function generateResetLink($token) {
    return 'http://yourwebsite.com/reset-password.php?token=' . $token;
}

// Send email function
function sendEmail($to, $subject, $message) {
    $headers = "From: your_email@example.com\r
";
    $headers .= "Content-Type: text/html; charset=UTF-8\r
";

    mail($to, $subject, $message, $headers);
}

// Check if email exists
function isValidEmail($email) {
    // Add your own validation logic here
    return !empty($email) && filter_var($email, FILTER_VALIDATE_EMAIL);
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $csrfToken = $_POST['csrf_token'];
    if (!isset($_SESSION['csrf_token']) || $csrfToken !== $_SESSION['csrf_token']) {
        die("Invalid token");
    }

    session_unset();
    session_destroy();

    $email = trim($_POST['email']);
    
    if (!isValidEmail($email)) {
        die("Invalid email address");
    }

    // Check if email exists in database
    $stmt = $conn->prepare('SELECT id FROM users WHERE email = :email');
    $stmt->bindParam(':email', $email);
    $stmt->execute();

    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user) {
        die("Email not found");
    }

    // Generate reset token and expiration time
    $resetToken = generateToken();
    $expirationTime = date('Y-m-d H:i:s', strtotime('+1 hour'));

    // Store token in database
    $stmt = $conn->prepare('INSERT INTO password_resets (token, user_id, expires_at) VALUES (:token, :user_id, :expires_at)');
    $stmt->bindParam(':token', $resetToken);
    $stmt->bindParam(':user_id', $user['id']);
    $stmt->bindParam(':expires_at', $expirationTime);
    $stmt->execute();

    // Create reset link
    $resetLink = generateResetLink($resetToken);

    // Send email to user
    $subject = "Password Reset Request";
    $message = "Click the following link to reset your password: <a href='$resetLink'>Reset Password</a><br>
               This link will expire in 1 hour.";

    sendEmail($email, $subject, $message);

    echo "A password reset link has been sent to your email address.";
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Forgot Password</title>
</head>
<body>
    <h2>Forgot Password</h2>
    <?php
    session_start();
    $_SESSION['csrf_token'] = generateToken();
    ?>
    <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
        <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
        <label>Email:</label><br>
        <input type="email" name="email" required><br><br>
        <input type="submit" value="Submit">
    </form>
</body>
</html>


<?php
// Include database connection details
$host = 'localhost';
$username = 'root';
$password = '';
$dbname = 'your_database';

// Connect to the database
$conn = mysqli_connect($host, $username, $password, $dbname);
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

if (isset($_POST['reset'])) { // Assuming the form's submit button name is 'reset'
    $email = $_POST['email'];
    
    // Check if the email exists in the database
    $sql = "SELECT * FROM users WHERE email = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "s", $email);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    
    if (mysqli_num_rows($result) > 0) {
        // Generate a temporary password
        $temp_password = substr(md5(time()), 0, 6); // You can generate a more secure password
        
        // Update the database with the temporary password
        $update_sql = "UPDATE users SET password = ?, temp_password_used = 'no' WHERE email = ?";
        $stmt_update = mysqli_prepare($conn, $update_sql);
        mysqli_stmt_bind_param($stmt_update, "ss", $temp_password, $email);
        
        if (mysqli_stmt_execute($stmt_update)) {
            // Send the temporary password to the user's email
            $to = $email;
            $subject = 'Password Reset';
            $message = "Your temporary password is: $temp_password
Please use this to log in and change your password.";
            
            mail($to, $subject, $message);
            
            // Redirect back with a success message
            header("Location: forgot_password.php?msg=success");
            exit();
        } else {
            echo "Error updating password: " . mysqli_error($conn);
        }
    } else {
        echo "Email does not exist in our records.";
    }
    
    mysqli_close($conn);
} else {
    // Show an error if form wasn't submitted
    header("Location: forgot_password.php?msg=error");
}
?>


if ($row['temp_password_used'] == 'no') {
    // Set session for temp password used
    $_SESSION['id'] = $row['id'];
    header("Location: change_password.php");
    exit();
}


session_start();

if (isset($_SESSION['id'])) {
    $user_id = $_SESSION['id'];
    
    if (isset($_POST['new_password']) && isset($_POST['confirm_new_password'])) {
        $new_password = $_POST['new_password'];
        $confirm_password = $_POST['confirm_new_password'];
        
        if ($new_password == $confirm_password) {
            // Hash the new password
            $hashed_password = md5($new_password); // Consider using a stronger hashing algorithm like bcrypt
            
            // Update the database
            $sql = "UPDATE users SET password = ?, temp_password_used = 'yes' WHERE id = ?";
            $stmt = mysqli_prepare($conn, $sql);
            mysqli_stmt_bind_param($stmt, "si", $hashed_password, $user_id);
            
            if (mysqli_stmt_execute($stmt)) {
                // Clear the session and redirect to login
                session_unset();
                session_destroy();
                header("Location: login.php");
                exit();
            } else {
                echo "Error changing password: " . mysqli_error($conn);
            }
        } else {
            echo "Passwords do not match!";
        }
    }
} else {
    // Redirect unauthorized access
    header("Location: login.php");
}


<?php
// Database configuration
$host = 'localhost';
$username = 'your_username';
$password = 'your_password';
$dbname = 'your_database';

// Create connection
$conn = new mysqli($host, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Function to generate random token
function generateToken() {
    return bin2hex(random_bytes(16));
}

// Function to send password reset email
function sendPasswordResetEmail($email, $resetLink) {
    require 'PHPMailer/PHPMailer.php';
    require 'PHPMailer/SMTP.php';
    require 'PHPMailer/Exception.php';

    try {
        $mail = new PHPMailer\PHPMailer();
        $mail->isSMTP();
        $mail->Host = 'smtp.example.com'; // Replace with your SMTP server
        $mail->SMTPAuth = true;
        $mail->Username = 'your_email@example.com'; // Your email address
        $mail->Password = 'your_password'; // Your email password
        $mail->SMTPSecure = 'tls';
        $mail->Port = 587;

        $mail->setFrom('your_email@example.com', 'Your Name');
        $mail->addAddress($email);
        $mail->Subject = 'Password Reset Request';
        
        $mailContent = "Dear User,<br><br>
                        You have requested to reset your password.<br>
                        Please click the following link to reset your password:<br>
                        <a href='$resetLink'>Reset Password</a><br><br>
                        If you did not request this password reset, please ignore this email.";

        $mail->Body = $mailContent;
        
        if ($mail->send()) {
            return true;
        } else {
            return false;
        }
    } catch (Exception $e) {
        echo "Message could not be sent.Mailer Error: {$mail->ErrorInfo}";
        return false;
    }
}

// Function to handle password reset request
function handlePasswordResetRequest($email, $conn) {
    // Check if email exists in database
    $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        // Generate reset token and expiration time
        $token = generateToken();
        $expires = date('Y-m-d H:i:s', strtotime('+1 hour'));

        // Update database with new token and expiration
        $updateStmt = $conn->prepare("UPDATE users SET reset_token = ?, reset_expires = ? WHERE email = ?");
        $updateStmt->bind_param("sss", $token, $expires, $email);
        $updateStmt->execute();

        if ($updateStmt->affected_rows === 1) {
            // Generate reset link
            $resetLink = "http://yourwebsite.com/reset-password.php?token=$token";
            
            // Send email with reset link
            if (sendPasswordResetEmail($email, $resetLink)) {
                echo "Password reset email sent successfully!";
                return true;
            } else {
                echo "Failed to send password reset email.";
                return false;
            }
        } else {
            echo "Error updating user record.";
            return false;
        }
    } else {
        echo "Email not found in our records.";
        return false;
    }
}

// Example usage:
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    
    // Sanitize input
    $email = filter_var($email, FILTER_SANITIZE_EMAIL);
    
    if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
        handlePasswordResetRequest($email, $conn);
    } else {
        echo "Please enter a valid email address.";
    }
}

// Close database connection
$conn->close();
?>


<?php
// Database configuration
$host = 'localhost';
$username = 'root';
$password = '';
$dbname = 'mydatabase';

// Create connection
$conn = new mysqli($host, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// If form is submitted
if (isset($_POST['email'])) {
    $email = $_POST['email'];
    
    // Check if email exists in database
    $sql = "SELECT * FROM users WHERE email = '$email'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // Generate reset token
        $token = bin2hex(random_bytes(16));
        
        // Set token expiration time (e.g., 1 hour from now)
        $expires = date("Y-m-d H:i:s", strtotime("+1 hour"));
        
        // Update the database with the new token and expiration time
        $sql = "UPDATE users SET reset_token = '$token', reset_expires = '$expires' WHERE email = '$email'";
        if ($conn->query($sql) === TRUE) {
            // Send reset password email
            $to = $email;
            $subject = 'Password Reset Request';
            $message = '
                <html>
                    <head>
                        <title>Password Reset</title>
                    </head>
                    <body>
                        <p>Hello,</p>
                        <p>You requested to reset your password. Click the link below to reset it:</p>
                        <a href="http://yourwebsite.com/reset-password.php?token='.$token.'&email='.$email.'">Reset Password</a>
                        <p>This link will expire in one hour.</p>
                    </body>
                </html>';
            $headers = 'MIME-Version: 1.0' . "\r
";
            $headers .= 'Content-type: text/html; charset=UTF-8' . "\r
";
            
            // Send the email
            mail($to, $subject, $message, $headers);
            echo "An email has been sent to your address with instructions to reset your password.";
        } else {
            echo "Error updating record: " . $conn->error;
        }
    } else {
        echo "Email not found in our records. Please check your email and try again.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Forgot Password</title>
</head>
<body>
    <h2>Forgot Password</h2>
    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
        <label for="email">Email:</label><br>
        <input type="email" id="email" name="email"><br><br>
        <input type="submit" value="Reset Password">
    </form>
</body>
</html>

// Reset password page (reset-password.php)
<?php
// Check if token and email are provided in URL
if (!isset($_GET['token']) || !isset($_GET['email'])) {
    die("Invalid request");
}

$token = $_GET['token'];
$email = $_GET['email'];

// Verify the token and email combination exists and hasn't expired
$sql = "SELECT * FROM users WHERE email = '$email' AND reset_token = '$token' AND reset_expires > NOW()";
$result = $conn->query($sql);

if ($result->num_rows == 1) {
    // Show password reset form
?>
<!DOCTYPE html>
<html>
<head>
    <title>Reset Password</title>
</head>
<body>
    <h2>Reset Password</h2>
    <form action="reset-password.php" method="post">
        <input type="hidden" name="token" value="<?php echo $token; ?>">
        <input type="hidden" name="email" value="<?php echo $email; ?>">
        <label for="new_password">New Password:</label><br>
        <input type="password" id="new_password" name="new_password"><br><br>
        <label for="confirm_password">Confirm Password:</label><br>
        <input type="password" id="confirm_password" name="confirm_password"><br><br>
        <input type="submit" value="Reset Password">
    </form>
</body>
</html>

<?php
} else {
    echo "Invalid or expired reset link.";
}

// Handle password reset form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $token = $_POST['token'];
    $email = $_POST['email'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    if ($new_password != $confirm_password) {
        die("Passwords do not match");
    }

    // Hash the new password
    $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

    // Update the database with the new password and clear the token
    $sql = "UPDATE users SET password = '$hashed_password', reset_token = NULL, reset_expires = NULL WHERE email = '$email'";
    
    if ($conn->query($sql) === TRUE) {
        echo "Password has been successfully updated!";
    } else {
        echo "Error updating password: " . $conn->error;
    }
}

$conn->close();
?>


<?php
// Database configuration
$host = 'localhost';
$dbname = 'your_database';
$user = 'your_username';
$password = 'your_password';

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $user, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

// Generate a random token
function generateToken($length = 32) {
    return bin2hex(random_bytes($length));
}

// Reset password function
function resetPassword($conn, $email) {
    // Check if user exists
    try {
        $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$user) {
            return "Email not found!";
        }
        
        // Generate token and expiration time
        $token = generateToken();
        $expires = date('Y-m-d H:i:s', strtotime('+1 hour'));
        
        // Insert reset token into database
        $stmt = $conn->prepare("INSERT INTO password_resets (user_id, token, expires) VALUES (?, ?, ?)");
        $stmt->execute([$user['id'], $token, $expires]);
        
        // Send email with reset link
        $resetLink = "http://your-site.com/reset_password.php?token=" . $token;
        sendResetEmail($email, $resetLink);
        
        return "Password reset instructions have been sent to your email!";
    } catch(PDOException $e) {
        return "Error: " . $e->getMessage();
    }
}

// Send reset email function
function sendResetEmail($to, $link) {
    $from = 'no-reply@your-site.com';
    $subject = 'Password Reset Request';
    
    $message = "
        <html>
            <body>
                <h2>Password Reset</h2>
                <p>Please click the link below to reset your password:</p>
                <a href='$link'>$link</a>
                <br><br>
                If you did not request this password reset, please ignore this email.
            </body>
        </html>
    ";
    
    // Using PHPMailer for better email handling
    require 'PHPMailer/PHPMailer.php';
    $mail = new PHPMailer();
    
    try {
        $mail->isSMTP();
        $mail->Host = 'smtp.example.com';  // Replace with your SMTP server
        $mail->SMTPAuth = true;
        $mail->Username = 'your_email@example.com';
        $mail->Password = 'your_password';
        $mail->Port = 587;  // or 465 if using SSL
        
        $mail->setFrom($from, 'Your Site Name');
        $mail->addAddress($to);
        
        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body = $message;
        
        if ($mail->send()) {
            return true;
        } else {
            return false;
        }
    } catch (Exception $e) {
        echo "Mailer Error: " . $e->getMessage();
        return false;
    }
}

// Update password function
function updatePassword($conn, $token, $new_password) {
    try {
        // Check if token exists and is valid
        $stmt = $conn->prepare("SELECT user_id FROM password_resets WHERE token = ? AND expires > NOW()");
        $stmt->execute([$token]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$result) {
            return "Invalid or expired token!";
        }
        
        // Hash the new password
        $hashedPassword = password_hash($new_password, PASSWORD_DEFAULT);
        
        // Update user's password
        $stmt = $conn->prepare("UPDATE users SET password = ? WHERE id = ?");
        $stmt->execute([$hashedPassword, $result['user_id']]);
        
        // Delete used token
        $stmt = $conn->prepare("DELETE FROM password_resets WHERE token = ?");
        $stmt->execute([$token]);
        
        return "Password updated successfully!";
    } catch(PDOException $e) {
        return "Error: " . $e->getMessage();
    }
}

// Example usage:
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['email'])) {
        echo resetPassword($conn, $_POST['email']);
    } elseif (isset($_GET['token'], $_POST['new_password'])) {
        echo updatePassword($conn, $_GET['token'], $_POST['new_password']);
    }
}
?>


<?php
// Database configuration
$host = 'localhost';
$username = 'root';
$password = '';
$dbname = 'mydatabase';

// Create connection
$conn = new mysqli($host, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];

    // Sanitize input
    $email = mysqli_real_escape_string($conn, $email);

    // Check if email exists in the database
    $sql = "SELECT id FROM users WHERE email='$email'";
    $result = $conn->query($sql);

    if ($result->num_rows == 1) {
        // Generate a random token
        $token = bin2hex(random_bytes(32));

        // Insert token into the database
        $sql_token = "INSERT INTO password_reset (user_id, token) VALUES (".$result->fetch_assoc()['id'].", '$token')";
        if ($conn->query($sql_token)) {
            // Send email to user with reset link
            $to = $email;
            $subject = "Password Reset Request";
            $message = "
                <html>
                    <head>
                        <title>Password Reset</title>
                    </head>
                    <body>
                        <h2>Password Reset</h2>
                        <p>Please click the following link to reset your password:</p>
                        <a href='http://yourdomain.com/reset_password.php?token=$token'>Reset Password</a><br><br>
                        <small>This link will expire in 1 hour.</small>
                    </body>
                </html>
            ";
            $headers = "MIME-Version: 1.0" . "\r
";
            $headers .= "Content-type:text/html;charset=UTF-8" . "\r
";
            $headers .= "From: yourdomain.com <noreply@yourdomain.com>" . "\r
";

            if (mail($to, $subject, $message, $headers)) {
                echo "An email has been sent to you with a password reset link.";
            } else {
                echo "Error sending email. Please try again later.";
            }
        } else {
            echo "Error resetting password. Please try again later.";
        }
    } else {
        echo "Email not found in our records.";
    }
}

$conn->close();
?>


<?php
// Database configuration
$host = 'localhost';
$username = 'root';
$password = '';
$dbname = 'mydatabase';

// Create connection
$conn = new mysqli($host, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $token = $_GET['token'];
    $new_password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Validate password match
    if ($new_password != $confirm_password) {
        die("Passwords do not match. Please try again.");
    }

    // Hash the new password
    $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

    // Check if token exists and is valid
    $sql = "SELECT user_id FROM password_reset WHERE token='$token'";
    $result = $conn->query($sql);

    if ($result->num_rows == 1) {
        $user_id = $result->fetch_assoc()['user_id'];

        // Update the user's password
        $update_sql = "UPDATE users SET password='$hashed_password' WHERE id=$user_id";
        if ($conn->query($update_sql)) {
            // Delete used token
            $delete_sql = "DELETE FROM password_reset WHERE token='$token'";
            if ($conn->query($delete_sql)) {
                echo "Password reset successful. You can now login with your new password.";
            }
        } else {
            echo "Error updating password. Please try again later.";
        }
    } else {
        echo "Invalid or expired reset link. Please request a new password reset.";
    }
}

$conn->close();
?>


<?php
include("db_connection.php"); // Include your database connection file

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    
    // Validate email format
    if (!preg_match("/^([a-zA-Z0-9_\-\.]+)@([a-zA-Z0-9_\-\.]+)\.([a-zA-Z]{2,5})$/", $email)) {
        header("Location: forgot_password.php?error=Invalid%20Email%20Format");
        exit();
    }
    
    // Check if email exists in the database
    $sql = "SELECT id FROM users WHERE email='$email'";
    $result = mysqli_query($conn, $sql);
    
    if (mysqli_num_rows($result) == 0) {
        header("Location: forgot_password.php?error=Email%20not%20found");
        exit();
    } else {
        // Generate a random string for the reset token
        $token = md5(uniqid(rand(), true));
        
        // Store the token in the database with an expiration time (e.g., 1 hour)
        $expires = date('Y-m-d H:i:s', strtotime('+1 hour'));
        $sql = "INSERT INTO password_resets (user_id, token, expires) VALUES (".$row['id'].", '$token', '$expires')";
        mysqli_query($conn, $sql);
        
        // Send email with reset link
        $to = $email;
        $subject = "Password Reset Request";
        $message = "Please click the following link to reset your password: 

";
        $message .= "http://yourwebsite.com/reset_password.php?token=$token";
        $headers = "From: yourwebsite@example.com";
        
        if (mail($to, $subject, $message, $headers)) {
            header("Location: forgot_password.php?success=Password%20reset%20link%20sent");
            exit();
        } else {
            header("Location: forgot_password.php?error=Email%20could%20not%20be%20sent");
            exit();
        }
    }
}
?>


<?php
include("db_connection.php"); // Include your database connection file

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $token = mysqli_real_escape_string($conn, $_POST['token']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);
    $confirm_password = mysqli_real_escape_string($conn, $_POST['confirm_password']);
    
    // Check if the token is valid and not expired
    $sql = "SELECT user_id FROM password_resets WHERE token='$token' AND expires > NOW()";
    $result = mysqli_query($conn, $sql);
    
    if (mysqli_num_rows($result) == 0) {
        header("Location: reset_password.php?error=Invalid%20or%20expired%20token");
        exit();
    } else {
        // Check if passwords match
        if ($password != $confirm_password) {
            header("Location: reset_password.php?error=Passwords%20do%20not%20match");
            exit();
        }
        
        // Update the user's password
        $row = mysqli_fetch_assoc($result);
        $user_id = $row['user_id'];
        $new_password_hash = md5($password); // You should use a better hashing method like bcrypt
        
        $sql = "UPDATE users SET password='$new_password_hash' WHERE id=$user_id";
        mysqli_query($conn, $sql);
        
        // Delete the token from the database
        $sql = "DELETE FROM password_resets WHERE token='$token'";
        mysqli_query($conn, $sql);
        
        header("Location: login.php?success=Password%20reset%20successful");
        exit();
    }
}
?>


<?php
// Database connection details
$host = 'localhost';
$username = 'root';
$password = '';
$dbname = 'mydatabase';

// Create database connection
$conn = new mysqli($host, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Function to handle password reset request
function forgotPassword($email) {
    global $conn;

    // Generate a random token
    $token = md5(time() . rand(0, 1000));

    // Check if email exists in the database
    $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->bind_param('s', $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Update token and expiration time (1 hour from now)
        $expiration_time = date("Y-m-d H:i:s", time() + 3600); // 1 hour

        $update_stmt = $conn->prepare("UPDATE users SET reset_token = ?, reset_expires = ? WHERE email = ?");
        $update_stmt->bind_param('sss', $token, $expiration_time, $email);
        $update_stmt->execute();

        // Send password reset link to user's email
        $to = $email;
        $subject = "Password Reset Request";
        $message = "Please click the following link to reset your password: 

";
        $message .= "http://example.com/reset-password.php?token=$token

";
        $message .= "This link will expire in 1 hour.";
        
        // Set headers
        $headers = "From: noreply@example.com\r
";
        $headers .= "Reply-To: noreply@example.com\r
";
        $headers .= "X-Mailer: PHP/" . phpversion();

        mail($to, $subject, $message, $headers);

        return "Password reset instructions have been sent to your email.";
    } else {
        return "Email address not found in our records.";
    }
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (!empty($_POST['email'])) {
        $email = $_POST['email'];
        echo forgotPassword($email);
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Forgot Password</title>
</head>
<body>
    <h2>Forgot Password</h2>
    
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        <input type="email" name="email" placeholder="Enter your email address" required><br><br>
        <button type="submit">Reset Password</button>
    </form>

    <?php
    // Show any error/success messages here if needed
    ?>
    
</body>
</html>


<?php
// Include database connection
require_once 'db_connection.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    
    // Check if email exists in the database
    $sql = "SELECT id FROM users WHERE email='$email'";
    $result = mysqli_query($conn, $sql);
    
    if (mysqli_num_rows($result) == 0) {
        die("Email not found. Please try again.");
    }
    
    // Generate a unique token for password reset
    $token = bin2hex(random_bytes(16)); // Generates a 32-character string
    $expires = time() + 7200; // Token expires after 2 hours
    
    // Store the token in the database
    $sql = "INSERT INTO password_resets (email, token, expires) 
            VALUES ('$email', '$token', '$expires')";
    mysqli_query($conn, $sql);
    
    // Send reset email to user
    require_once 'PHPMailer/PHPMailer.php';
    require_once 'PHPMailer/SMTP.php';
    require_once 'PHPMailer/Exception.php';
    
    try {
        $mail = new PHPMailer\PHPMailer();
        
        // SMTP settings
        $mail->isSMTP();
        $mail->Host = 'smtp.example.com'; // Replace with your SMTP host
        $mail->SMTPAuth = true;
        $mail->Username = 'your_email@example.com';
        $mail->Password = 'your_password';
        $mail->Port = 587; // or 465
        
        // Email content
        $mail->setFrom('your_email@example.com', 'Reset Password');
        $mail->addAddress($email);
        
        $reset_link = "http://example.com/reset_password.php?token=$token";
        $message = "
            <h2>Password Reset Request</h2>
            <p>Click the following link to reset your password:</p>
            <a href='$reset_link'>$reset_link</a>
            <br><br>
            <p>If you didn't request this, please ignore this email.</p>
        ";
        
        $mail->isHTML(true);
        $mail->Subject = 'Password Reset Request';
        $mail->Body = $message;
        
        if ($mail->send()) {
            echo "An email has been sent to your address with instructions on how to reset your password.";
        } else {
            die("Email sending failed: " . $mail->ErrorInfo);
        }
    } catch (Exception $e) {
        die("Message could not be sent. Mailer Error: " . $e->getMessage());
    }
    
} else {
    // If form is accessed directly
    header('Location: forgot_password_form.html');
}
?>


<?php
// Include database connection
require_once 'db_connection.php';

if (!isset($_GET['token'])) {
    die("No token provided.");
}

$token = mysqli_real_escape_string($conn, $_GET['token']);

// Check if token exists in database and is not expired
$sql = "SELECT * FROM password_resets 
        WHERE token='$token' AND expires > CURRENT_TIMESTAMP";
$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) == 0) {
    die("Invalid or expired token.");
}

// Show password reset form
?>

<!DOCTYPE html>
<html>
<head>
    <title>Reset Password</title>
</head>
<body>
    <h2>Reset Password</h2>
    <form action="reset_password.php" method="post">
        <input type="hidden" name="token" value="<?php echo $token; ?>">
        <label for="new_password">New Password:</label><br>
        <input type="password" id="new_password" name="new_password" required><br><br>
        <label for="confirm_password">Confirm Password:</label><br>
        <input type="password" id="confirm_password" name="confirm_password" required><br><br>
        <button type="submit">Reset Password</button>
    </form>
</body>
</html>

<?php
// Handle password reset form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $token = mysqli_real_escape_string($conn, $_POST['token']);
    $new_password = mysqli_real_escape_string($conn, $_POST['new_password']);
    $confirm_password = mysqli_real_escape_string($conn, $_POST['confirm_password']);
    
    if ($new_password != $confirm_password) {
        die("Passwords do not match.");
    }
    
    // Hash the new password
    $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
    
    // Update user's password in the database
    $sql = "SELECT email FROM password_resets WHERE token='$token'";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($result);
    $email = $row['email'];
    
    $update_sql = "UPDATE users SET password='$hashed_password' WHERE email='$email'";
    mysqli_query($conn, $update_sql);
    
    // Delete the token from password_resets table
    $delete_sql = "DELETE FROM password_resets WHERE token='$token'";
    mysqli_query($conn, $delete_sql);
    
    echo "Password reset successful!";
}
?>


<?php
// Database configuration
$host = 'localhost';
$username = 'root';
$password = '';
$db_name = 'mydatabase';

// Create connection
$conn = new mysqli($host, $username, $password, $db_name);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Session start
session_start();

// CSRF token generation
function generateToken() {
    return bin2hex(random_bytes(16));
}

// Generate and store CSRF token in session
if (!isset($_SESSION['token'])) {
    $_SESSION['token'] = generateToken();
}

// Email validation function
function validateEmail($email) {
    if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return true;
    } else {
        return false;
    }
}

// Forgot password function
function forgotPassword($conn, $email) {
    // Check if email exists in database
    $sql = "SELECT id FROM users WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        return false;
    }

    // Generate reset token
    $resetToken = generateToken();
    
    // Store reset token and expiration time in database
    $currentTime = time();
    $expirationTime = $currentTime + 3600; // Token expires after 1 hour
    
    $sql = "UPDATE users SET reset_token = ?, reset_expires = ? WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sis", $resetToken, $expirationTime, $email);
    $stmt->execute();

    // Send password reset link to user's email
    $resetLink = "http://yourdomain.com/reset-password.php?token=" . $resetToken;
    
    $to = $email;
    $subject = "Password Reset Request";
    $message = "Please click the following link to reset your password:

" . $resetLink . "

If you did not request this password reset, please ignore this email.";
    
    // Using PHP's mail function (you may want to use a more reliable method like PHPMailer in production)
    if (mail($to, $subject, $message)) {
        return true;
    } else {
        return false;
    }
}

// Reset password function
function resetPassword($conn, $token, $newPassword) {
    // Check token validity and expiration
    $sql = "SELECT id, email FROM users WHERE reset_token = ? AND reset_expires > ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("si", $token, time());
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        return false;
    }

    // Update password
    $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
    
    $sql = "UPDATE users SET password = ?, reset_token = NULL, reset_expires = NULL WHERE reset_token = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $hashedPassword, $token);
    $stmt->execute();

    return true;
}

// Main forgot password form handling
if (isset($_POST['forgot-password'])) {
    // Validate CSRF token
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['token']) {
        die("Invalid request");
    }

    $email = $_POST['email'];
    
    if (!validateEmail($email)) {
        die("Invalid email format");
    }

    if (forgotPassword($conn, $email)) {
        echo "Password reset instructions have been sent to your email address.";
    } else {
        echo "An error occurred. Please try again later.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Forgot Password</title>
</head>
<body>
    <h2>Forgot Password</h2>
    
    <?php
    // If there's an error, display it here
    if (isset($_GET['error'])) {
        echo "<p style='color: red;'>Error: " . $_GET['error'] . "</p>";
    }
    ?>

    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
        <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['token']; ?>">
        
        <label for="email">Email:</label>
        <input type="text" id="email" name="email">
        
        <button type="submit" name="forgot-password">Reset Password</button>
    </form>

    <p>Don't have an account? <a href="signup.php">Sign Up</a></p>
</body>
</html>


<!DOCTYPE html>
<html>
<head>
    <title>Forgot Password</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #f5f5f5;
        }
        .container {
            max-width: 400px;
            margin: 0 auto;
            background-color: white;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .form-group {
            margin-bottom: 20px;
        }
        input[type="email"] {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-sizing: border-box;
        }
        button {
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            width: 100%;
        }
        button:hover {
            background-color: #45a049;
        }
        .message {
            margin-top: 10px;
            color: red;
        }
    </style>
</head>
<body>
    <div class="container">
        <?php
        // Check if message parameter exists
        $message = isset($_GET['message']) ? $_GET['message'] : '';
        if ($message == 'success') {
            echo "<p style='color: green;'>Check your email for password reset instructions!</p>";
        } elseif ($message == 'invalid') {
            echo "<p style='color: red;'>Invalid email or token!</p>";
        }
        ?>
        
        <h2>Forgot Password</h2>
        <form action="forgot_password.php" method="post">
            <div class="form-group">
                <label for="email">Email:</label><br>
                <input type="email" id="email" name="email" required>
            </div>
            <button type="submit">Reset Password</button>
        </form>
    </div>
</body>
</html>


<?php
// Database connection settings
$host = 'localhost';
$dbname = 'your_database_name';
$username = 'your_username';
$password = 'your_password';

try {
    // Connect to database
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get email from form
    $email = $_POST['email'];
    
    // Prepare statement to check if email exists in database
    $stmt = $conn->prepare("SELECT id, username FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($row) {
        // Generate a random token
        $token = bin2hex(random_bytes(32));
        $token_hash = sha1($token);
        
        // Store token in database
        $stmt = $conn->prepare("INSERT INTO password_reset (user_id, token, created_at) VALUES (?, ?, NOW())");
        $stmt->execute([$row['id'], $token_hash]);
        
        // Send email with reset link
        $to = $email;
        $subject = "Password Reset Request";
        $message = "
            <html>
                <body>
                    <h2>Password Reset</h2>
                    <p>Please click the following link to reset your password:</p>
                    <a href='http://yourwebsite.com/reset_password.php?token=$token&username=" . urlencode($row['username']) . "'>Reset Password</a><br>
                    <p>If you did not request this password reset, please ignore this email.</p>
                </body>
            </html>
        ";
        $headers = "MIME-Version: 1.0" . "\r
";
        $headers .= "Content-type:text/html;charset=UTF-8" . "\r
";
        $headers .= "From: yourwebsite.com <noreply@yourwebsite.com>" . "\r
";
        
        mail($to, $subject, $message, $headers);
        
        // Redirect back to forgot password page with success message
        header("Location: forgot_password.php?message=success");
        exit();
    } else {
        // Email not found in database
        echo "<script>alert('Email address not found!'); window.history.back();</script>";
    }
}
?>


<?php
// Database connection settings
$host = 'localhost';
$dbname = 'your_database_name';
$username = 'your_username';
$password = 'your_password';

try {
    // Connect to database
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

// Check if token and username are provided
if (isset($_GET['token']) && isset($_GET['username'])) {
    $token = $_GET['token'];
    $username = $_GET['username'];
    
    // Get hashed token from database
    $stmt = $conn->prepare("SELECT pr.token FROM password_reset pr INNER JOIN users u ON pr.user_id = u.id WHERE u.username = ? AND pr.token = SHA1(?)");
    $stmt->execute([$username, $token]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($row) {
        // Token is valid
        ?>
        <html>
            <head>
                <title>Reset Password</title>
                <style>
                    body {
                        font-family: Arial, sans-serif;
                        margin: 0;
                        padding: 20px;
                        background-color: #f5f5f5;
                    }
                    .container {
                        max-width: 400px;
                        margin: 0 auto;
                        background-color: white;
                        padding: 20px;
                        border-radius: 5px;
                        box-shadow: 0 0 10px rgba(0,0,0,0.1);
                    }
                    .form-group {
                        margin-bottom: 20px;
                    }
                    input[type="password"] {
                        width: 100%;
                        padding: 10px;
                        border: 1px solid #ddd;
                        border-radius: 4px;
                        box-sizing: border-box;
                    }
                    button {
                        background-color: #4CAF50;
                        color: white;
                        padding: 10px 20px;
                        border: none;
                        border-radius: 4px;
                        cursor: pointer;
                        width: 100%;
                    }
                    button:hover {
                        background-color: #45a049;
                    }
                    .message {
                        margin-top: 10px;
                        color: red;
                    }
                </style>
            </head>
            <body>
                <div class="container">
                    <h2>Reset Password</h2>
                    <?php
                    if (isset($_POST['new_password']) && isset($_POST['confirm_password'])) {
                        $new_password = $_POST['new_password'];
                        $confirm_password = $_POST['confirm_password'];
                        
                        if ($new_password == $confirm_password) {
                            // Hash the password
                            $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
                            
                            // Update the password in database
                            $stmt = $conn->prepare("UPDATE users SET password = ? WHERE username = ?");
                            $stmt->execute([$hashed_password, $username]);
                            
                            // Delete the reset token
                            $stmt = $conn->prepare("DELETE FROM password_reset WHERE user_id = (SELECT id FROM users WHERE username = ?)");
                            $stmt->execute([$username]);
                            
                            // Redirect to login page with success message
                            header("Location: login.php?message=success");
                            exit();
                        } else {
                            echo "<div class='message'>Passwords do not match!</div>";
                        }
                    }
                    ?>
                    <form action="<?php $_SERVER['PHP_SELF'] ?>" method="post">
                        <input type="hidden" name="token" value="<?php echo $token; ?>">
                        <div class="form-group">
                            <label for="new_password">New Password:</label><br>
                            <input type="password" id="new_password" name="new_password" required>
                        </div>
                        <div class="form-group">
                            <label for="confirm_password">Confirm Password:</label><br>
                            <input type="password" id="confirm_password" name="confirm_password" required>
                        </div>
                        <button type="submit">Set New Password</button>
                    </form>
                </div>
            </body>
        </html>
        <?php
    } else {
        // Invalid token or username
        header("Location: forgot_password.php?message=invalid");
        exit();
    }
} else {
    // Missing parameters
    header("Location: forgot_password.php?message=invalid");
    exit();
}
?>


<?php
session_start();
include('db_connection.php'); // Include your database connection

if (isset($_POST['email'])) {
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    
    // Check if email exists in the database
    $sql = "SELECT id FROM users WHERE email = '$email'";
    $result = mysqli_query($conn, $sql);
    
    if (mysqli_num_rows($result) == 0) {
        echo "<script>alert('No account found with this email!');</script>";
    } else {
        // Generate a random token
        $token = bin2hex(random_bytes(32)); // 64 characters long
        
        // Store the token and expiration time in the database
        $expires = date("Y-m-d H:i:s", time() + 3600); // Token expires after 1 hour
        
        $sql = "UPDATE users SET reset_token = '$token', reset_expires = '$expires' WHERE email = '$email'";
        mysqli_query($conn, $sql);
        
        // Send the password reset link to the user's email
        $reset_link = "http://your-site.com/reset_password.php?token=$token";
        sendPasswordResetEmail($email, $reset_link, $expires);
        
        echo "<script>alert('Password reset instructions have been sent to your email!');</script>";
    }
}
?>

<?php
function sendPasswordResetEmail($email, $reset_link, $expires) {
    // Set up the email content
    $to = $email;
    $subject = "Password Reset Request";
    
    $message = "
        <html>
            <body>
                <p>We received a password reset request for your account.</p>
                <p>Click on the following link to reset your password:</p>
                <a href='$reset_link'>Reset Password</a><br><br>
                <p>This link will expire at: $expires</p>
            </body>
        </html>
    ";
    
    // Set headers
    $headers = "MIME-Version: 1.0" . "\r
";
    $headers .= "Content-type: text/html; charset=UTF-8" . "\r
";
    $headers .= "From: Your Website <your.email@example.com>" . "\r
";
    
    // Send the email
    mail($to, $subject, $message, $headers);
}
?>


<?php
session_start();
include('db_connection.php'); // Include your database connection

// Check if token is provided and valid
if (isset($_GET['token'])) {
    $token = mysqli_real_escape_string($conn, $_GET['token']);
    
    // Get user data from the database
    $sql = "SELECT id, reset_token, reset_expires FROM users WHERE reset_token = '$token'";
    $result = mysqli_query($conn, $sql);
    
    if (mysqli_num_rows($result) == 0) {
        die("<script>alert('Invalid token!');</script>");
    }
    
    $row = mysqli_fetch_assoc($result);
    // Check if the token has expired
    if ($row['reset_expires'] < date("Y-m-d H:i:s")) {
        die("<script>alert('Token has expired! Please request a new password reset.');</script>");
    }
} else {
    die("<script>alert('No token provided!');</script>");
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Reset Password</title>
</head>
<body>
    <h2>Reset Password</h2>
    
    <?php
    if (isset($_POST['submit'])) {
        // Validate the new password
        $password = $_POST['password'];
        $confirm_password = $_POST['confirm_password'];
        
        if ($password != $confirm_password) {
            die("<script>alert('Passwords do not match!');</script>");
        }
        
        if (strlen($password) < 8) {
            die("<script>alert('Password must be at least 8 characters long!');</script>");
        }
        
        // Check for at least one letter and number
        if (!preg_match("#^[a-zA-Z0-9]+$#", $password)) {
            die("<script>alert('Password must contain only letters and numbers!');</script>");
        }
        
        // Hash the new password
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        
        // Update the database with the new password
        $sql = "UPDATE users SET password = '$hashed_password', reset_token = NULL, reset_expires = NULL WHERE id = {$row['id']}";
        mysqli_query($conn, $sql);
        
        echo "<script>alert('Password has been successfully updated!');</script>";
    }
    ?>
    
    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
        <input type="password" name="password" placeholder="Enter new password" required><br>
        <input type="password" name="confirm_password" placeholder="Confirm new password" required><br><br>
        <button type="submit" name="submit">Reset Password</button>
    </form>
</body>
</html>


<?php
// Database configuration
$host = 'localhost';
$dbname = 'your_database_name';
$username = 'your_username';
$password = 'your_password';

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Could not connect to the database: " . $e->getMessage());
}

// Function to generate a secure token
function generateToken() {
    return bin2hex(random_bytes(16));
}

// Check if email exists in database
function checkEmailExists($email, $conn) {
    try {
        $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->execute([$email]);
        return $stmt->rowCount() > 0;
    } catch (PDOException $e) {
        die("Error checking email: " . $e->getMessage());
    }
}

// Function to generate and store reset token
function generateResetToken($email, $conn) {
    try {
        // Generate a secure token
        $token = generateToken();
        
        // Insert into database
        $stmt = $conn->prepare("INSERT INTO password_resets (user_email, token, created_at) VALUES (?, ?, NOW())");
        $stmt->execute([$email, $token]);
        
        return $token;
    } catch (PDOException $e) {
        die("Error generating reset token: " . $e->getMessage());
    }
}

// Function to send reset password email
function sendResetEmail($email, $resetLink) {
    $to = $email;
    $subject = 'Password Reset Request';
    $message = "<html>
                <body>
                    <p>We received a request to reset your password. Click the link below to reset it:</p>
                    <a href='" . $resetLink . "'>" . $resetLink . "</a><br>
                    <p>If you did not make this request, you can safely ignore this email.</p>
                </body>
               </html>";
    $headers = 'MIME-Version: 1.0' . "\r
";
    $headers .= 'Content-type: text/html; charset=UTF-8' . "\r
";
    
    mail($to, $subject, $message, $headers);
}

// Function to handle password reset request
function handleForgotPassword($email) {
    if (checkEmailExists($email, $conn)) {
        $token = generateResetToken($email, $conn);
        $resetLink = 'http://example.com/reset-password.php?token=' . $token;
        sendResetEmail($email, $resetLink);
        return true;
    } else {
        return false;
    }
}

// Function to validate token and display reset form
function handleTokenValidation($token) {
    try {
        // Check if token exists in database and hasn't expired (assuming 1 hour validity)
        $stmt = $conn->prepare("SELECT user_email FROM password_resets WHERE token = ? AND created_at >= NOW() - INTERVAL 1 HOUR");
        $stmt->execute([$token]);
        
        if ($stmt->rowCount() == 1) {
            // Token is valid
            return true;
        } else {
            // Invalid or expired token
            return false;
        }
    } catch (PDOException $e) {
        die("Error validating token: " . $e->getMessage());
    }
}

// Function to update password after reset
function updatePassword($email, $newPassword) {
    try {
        $stmt = $conn->prepare("UPDATE users SET password = ? WHERE email = ?");
        $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
        $stmt->execute([$hashedPassword, $email]);
        
        // Delete the reset token
        $deleteStmt = $conn->prepare("DELETE FROM password_resets WHERE user_email = ?");
        $deleteStmt->execute([$email]);
        
        return true;
    } catch (PDOException $e) {
        die("Error updating password: " . $e->getMessage());
    }
}

// Example usage for forgot password page
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    
    if (empty($email)) {
        echo "Email is required!";
    } else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "Please enter a valid email!";
    } else {
        if (handleForgotPassword($email)) {
            echo "A password reset link has been sent to your email address!";
        } else {
            echo "Email not found in our database!";
        }
    }
}

// Example usage for reset password page
if (isset($_GET['token'])) {
    $token = $_GET['token'];
    
    if (!handleTokenValidation($token)) {
        die("Invalid or expired token!");
    }
    
    // Display the password reset form
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $newPassword = $_POST['new_password'];
        
        if (empty($newPassword)) {
            echo "Please enter a new password!";
        } else {
            try {
                $stmt = $conn->prepare("SELECT user_email FROM password_resets WHERE token = ?");
                $stmt->execute([$token]);
                $email = $stmt->fetch(PDO::FETCH_ASSOC)['user_email'];
                
                if (updatePassword($email, $newPassword)) {
                    echo "Your password has been successfully reset!";
                    // Optionally, log the user out
                    session_start();
                    $_SESSION = array();
                    session_destroy();
                } else {
                    echo "Error resetting your password!";
                }
            } catch (PDOException $e) {
                die("Error fetching email: " . $e->getMessage());
            }
        }
    }
}

// Close database connection
$conn = null;
?>


<?php
// forgot_password.php

$servername = "localhost";
$username = "username";
$password = "password";
$dbname = "myDB";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$email = $_POST['email'];

// Check if email exists in the database
$sql = "SELECT id FROM users WHERE email = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    header("Location: forgot_password_form.html?error=Email%20not%20found");
    exit();
}

// Generate a random password and reset token
$randomPassword = generateRandomString(8);
$resetToken = generateRandomString(32);

// Update the database with the new password and token
$sql = "UPDATE users SET password = ?, reset_token = ? WHERE email = ?";
$stmt = $conn->prepare($sql);
$passwordHash = password_hash($randomPassword, PASSWORD_DEFAULT);
$stmt->bind_param("sss", $passwordHash, $resetToken, $email);
$stmt->execute();

// Send the reset email
$to = $email;
$subject = "Reset Your Password";
$message = "
    <html>
        <body>
            <h2>Reset Your Password</h2>
            <p>Please click on the following link to set your new password:</p>
            <a href='http://yourdomain.com/reset_password.php?token=$resetToken'>Reset Password</a><br>
            <p>If you did not request this, please ignore this email.</p>
        </body>
    </html
";
$headers = "MIME-Version: 1.0" . "\r
";
$headers .= "Content-type:text/html;charset=UTF-8" . "\r
";
mail($to, $subject, $message, $headers);

header("Location: forgot_password_form.html?success=Please%20check%20your%20email%20for%20reset%20instructions.");
exit();

function generateRandomString($length = 8) {
    $characters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, strlen($characters)-1)];
    }
    return $randomString;
}
?>


<?php
// reset_password.php

$servername = "localhost";
$username = "username";
$password = "password";
$dbname = "myDB";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (!isset($_GET['token']) || !isset($_POST['password']) || !isset($_POST['confirmPassword'])) {
    header("Location: reset_password_form.html?error=Invalid%20request");
    exit();
}

$resetToken = $_GET['token'];
$password = $_POST['password'];
$confirmPassword = $_POST['confirmPassword'];

if ($password != $confirmPassword) {
    header("Location: reset_password_form.html?error=Passwords%20do%20not%20match");
    exit();
}

// Check if the token is valid
$sql = "SELECT id, user_id FROM reset_tokens WHERE token = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $resetToken);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    header("Location: reset_password_form.html?error=Invalid%20or%20expired%20token");
    exit();
}

$row = $result->fetch_assoc();
$user_id = $row['user_id'];

// Update the user's password
$sql = "UPDATE users SET password = ? WHERE id = ?";
$stmt = $conn->prepare($sql);
$passwordHash = password_hash($password, PASSWORD_DEFAULT);
$stmt->bind_param("si", $passwordHash, $user_id);
$stmt->execute();

// Invalidate the reset token
$sql = "DELETE FROM reset_tokens WHERE token = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $resetToken);
$stmt->execute();

header("Location: login.php?success=Password%20has%20been%20reset");
exit();
?>


<?php
// Database connection
$host = 'localhost';
$username = 'root';
$password = '';
$dbname = 'mydatabase';

$conn = new mysqli($host, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Function to send password reset email
function sendPasswordResetEmail($email, $token) {
    $to = $email;
    $subject = 'Password Reset Request';
    $message = '
        <html>
            <head>
                <title>Password Reset</title>
            </head>
            <body>
                <h2>Password Reset Request</h2>
                <p>Please click the link below to reset your password:</p>
                <a href="http://example.com/reset-password.php?token=' . $token . '">Reset Password</a>
                <br><br>
                <p>If you did not request a password reset, please ignore this email.</p>
            </body>
        </html>';
    $headers = 'MIME-Version: 1.0' . "\r
";
    $headers .= 'Content-type: text/html; charset=UTF-8' . "\r
";

    mail($to, $subject, $message, $headers);
}

// Function to generate random token
function generateToken() {
    return bin2hex(random_bytes(32));
}

// Check if email exists in database
function isEmailExists($email) {
    global $conn;
    $sql = "SELECT id FROM users WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    return $result->num_rows > 0;
}

// Reset password form
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    
    if (isEmailExists($email)) {
        // Generate token and store in database
        $token = generateToken();
        
        $sql = "INSERT INTO password_resets (email, token) VALUES (?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $email, $token);
        $stmt->execute();
        
        sendPasswordResetEmail($email, $token);
        echo 'A password reset link has been sent to your email address.';
    } else {
        echo 'Email not found in our records.';
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Forgot Password</title>
</head>
<body>
    <h2>Forgot Password</h2>
    <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
        <label for="email">Email:</label><br>
        <input type="text" id="email" name="email"><br><br>
        <input type="submit" value="Send Reset Link">
    </form>
</body>
</html>

// Reset password page (reset-password.php)
<?php
if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['token'])) {
    $token = $_GET['token'];
    
    // Check if token exists in database
    global $conn;
    $sql = "SELECT email FROM password_resets WHERE token = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Show reset form
        ?>
        <html>
            <head>
                <title>Reset Password</title>
            </head>
            <body>
                <h2>Reset Password</h2>
                <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                    <label for="password">New Password:</label><br>
                    <input type="password" id="password" name="password"><br><br>
                    <input type="hidden" name="token" value="<?php echo $token; ?>">
                    <input type="submit" value="Reset Password">
                </form>
            </body>
        </html>
        <?php
    } else {
        echo 'Invalid or expired token.';
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['token'])) {
    $token = $_POST['token'];
    $password = $_POST['password'];

    // Update password in database
    global $conn;
    $sql = "SELECT email FROM password_resets WHERE token = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $email = $result->fetch_assoc()['email'];
        
        // Update user password
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $sql = "UPDATE users SET password = ? WHERE email = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $hashed_password, $email);
        $stmt->execute();

        // Delete token from database
        $sql = "DELETE FROM password_resets WHERE token = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $token);
        $stmt->execute();

        echo 'Password reset successful. You can now login with your new password.';
    } else {
        echo 'Invalid or expired token.';
    }
}
?>


<?php
// Database configuration
$host = 'localhost';
$username = 'root';
$password = '';
$database = 'my_database';

// Create connection
$conn = new mysqli($host, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Function to send password reset email
function sendPasswordResetEmail($email, $resetLink) {
    $to = $email;
    $subject = 'Password Reset Request';
    $message = "
        <html>
        <body>
            <h2>Password Reset</h2>
            <p>We received a request to reset your password. Please click the following link to reset your password:</p>
            <a href='" . $resetLink . "'>" . $resetLink . "</a><br><br>
            <small>If you did not request this password reset, please ignore this email.</small>
        </body>
        </html>
    ";
    
    // Set headers
    $headers = 'MIME-Version: 1.0' . "\r
";
    $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r
";
    $headers .= 'From: <your_email@example.com>' . "\r
";
    
    // Send email
    mail($to, $subject, $message, $headers);
}

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    
    // Validate email
    if (empty($email)) {
        echo "Email is required!";
        exit();
    }
    
    // Check if email exists in database
    $sql = "SELECT id FROM users WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows == 0) {
        echo "Email not found!";
        exit();
    }
    
    // Generate reset token
    $resetToken = bin2hex(random_bytes(16));
    $resetUrl = 'http://example.com/reset-password.php?token=' . $resetToken;
    $expires = date('Y-m-d H:i:s', time() + 3600); // Token expires in 1 hour
    
    // Insert reset token into database
    $sql = "INSERT INTO password_resets (user_id, token, created_at, expires_at)
            VALUES (?, ?, NOW(), ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iss", $result->fetch_assoc()['id'], $resetToken, $expires);
    
    if ($stmt->execute()) {
        // Send reset email
        sendPasswordResetEmail($email, $resetUrl);
        echo "Password reset link has been sent to your email!";
    } else {
        echo "Error sending password reset request!";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Forgot Password</title>
</head>
<body>
    <h2>Forgot Password</h2>
    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
        <label for="email">Email:</label><br>
        <input type="email" id="email" name="email" required><br><br>
        <button type="submit">Send Reset Link</button>
    </form>
</body>
</html>


<?php
// Database configuration (same as above)
$host = 'localhost';
$username = 'root';
$password = '';
$database = 'my_database';

$conn = new mysqli($host, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get token from URL
if (!isset($_GET['token'])) {
    header("Location: forgot-password.php");
    exit();
}

$resetToken = $_GET['token'];

// Check if token is valid and hasn't expired
$sql = "SELECT user_id FROM password_resets 
        WHERE token = ? AND created_at > DATE_SUB(NOW(), INTERVAL 1 HOUR) AND used = 0";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $resetToken);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    die("Invalid or expired reset token!");
}

$user_id = $result->fetch_assoc()['user_id'];

// If form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $newPassword = $_POST['password'];
    $confirmPassword = $_POST['confirm_password'];
    
    if ($newPassword !== $confirmPassword) {
        die("Passwords do not match!");
    }
    
    // Update user's password
    $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
    $sql = "UPDATE users SET password = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("si", $hashedPassword, $user_id);
    
    if ($stmt->execute()) {
        // Mark token as used
        $sql = "UPDATE password_resets SET used = 1 WHERE token = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $resetToken);
        $stmt->execute();
        
        echo "Password reset successful!";
    } else {
        echo "Error resetting password!";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Reset Password</title>
</head>
<body>
    <h2>Reset Password</h2>
    <form action="<?php echo $_SERVER['PHP_SELF']; ?>?token=<?php echo $resetToken; ?>" method="post">
        <label for="password">New Password:</label><br>
        <input type="password" id="password" name="password" required><br><br>
        <label for="confirm_password">Confirm New Password:</label><br>
        <input type="password" id="confirm_password" name="confirm_password" required><br><br>
        <button type="submit">Reset Password</button>
    </form>
</body>
</html>


<?php
// Include database connection
include('db_connection.php');

if (isset($_POST['submit'])) {
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    
    // Check if email exists in the database
    $query = "SELECT * FROM users WHERE email = '$email'";
    $result = mysqli_query($conn, $query);
    $row = mysqli_fetch_assoc($result);
    
    if (mysqli_num_rows($result) == 0) {
        echo "Email does not exist!";
    } else {
        // Generate reset token
        $resetToken = md5(uniqid());
        
        // Update database with the reset token
        $updateQuery = "UPDATE users SET password_reset_token = '$resetToken' WHERE email = '$email'";
        mysqli_query($conn, $updateQuery);
        
        // Send reset email
        $to = $email;
        $subject = "Password Reset Request";
        $message = "Please click the following link to reset your password: http://yourwebsite.com/reset_password.php?token=$resetToken";
        $headers = "From: yourwebsite.com\r
";
        
        mail($to, $subject, $message, $headers);
        
        echo "Password reset email has been sent!";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Forgot Password</title>
</head>
<body>
    <h2>Forgot Password</h2>
    <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
        <input type="email" name="email" placeholder="Enter your email" required><br><br>
        <input type="submit" name="submit" value="Reset Password">
    </form>
</body>
</html>


<?php
// Include database connection
include('db_connection.php');

if (isset($_GET['token'])) {
    $token = mysqli_real_escape_string($conn, $_GET['token']);
    
    // Check if token exists in the database
    $query = "SELECT * FROM users WHERE password_reset_token = '$token'";
    $result = mysqli_query($conn, $query);
    $row = mysqli_fetch_assoc($result);
    
    if (mysqli_num_rows($result) == 0) {
        echo "Invalid reset link!";
    } else {
        // Show password reset form
?>
<!DOCTYPE html>
<html>
<head>
    <title>Reset Password</title>
</head>
<body>
    <h2>Reset Password</h2>
    <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
        <input type="password" name="new_password" placeholder="Enter new password" required><br><br>
        <input type="password" name="confirm_password" placeholder="Confirm password" required><br><br>
        <input type="hidden" name="token" value="<?php echo $token; ?>">
        <input type="submit" name="change_password" value="Change Password">
    </form>
</body>
</html>

<?php
    }
} else {
    echo "No token provided!";
}
?>

<?php
if (isset($_POST['change_password'])) {
    $new_password = mysqli_real_escape_string($conn, $_POST['new_password']);
    $confirm_password = mysqli_real_escape_string($conn, $_POST['confirm_password']);
    $token = mysqli_real_escape_string($conn, $_POST['token']);
    
    if ($new_password != $confirm_password) {
        echo "Passwords do not match!";
    } else {
        // Update password in the database
        $hash_password = md5($new_password);
        
        $updateQuery = "UPDATE users SET 
                        password = '$hash_password',
                        password_reset_token = '' 
                      WHERE password_reset_token = '$token'";
        mysqli_query($conn, $updateQuery);
        
        echo "Password has been successfully updated!";
    }
}
?>


<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "your_database";

// Create connection
$conn = mysqli_connect($servername, $username, $password, $dbname);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
?>


<?php
// Database configuration
$host = 'localhost';
$dbname = 'mydatabase';
$username = 'root';
$password = '';

try {
    // Create database connection
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Function to send password reset email
    function sendResetEmail($email, $userId) {
        global $conn;
        
        // Generate a random token
        $token = bin2hex(random_bytes(16));
        
        // Store the token in the database
        $stmt = $conn->prepare("INSERT INTO password_resets (user_id, token) VALUES (:userId, :token)");
        $stmt->execute([
            'userId' => $userId,
            'token' => $token
        ]);
        
        // Send email to user
        $subject = "Password Reset Request";
        $message = "Please click the following link to reset your password:

http://example.com/reset_password.php?token=$token";
        mail($email, $subject, $message);
    }

    // Function to handle password reset
    function resetPassword($password, $token) {
        global $conn;
        
        // Check if token exists in the database
        $stmt = $conn->prepare("SELECT * FROM password_resets WHERE token = :token");
        $stmt->execute(['token' => $token]);
        
        if ($stmt->rowCount() > 0) {
            $reset = $stmt->fetch(PDO::FETCH_ASSOC);
            
            // Update the user's password
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $updateStmt = $conn->prepare("UPDATE users SET password = :hash WHERE id = :userId");
            $updateStmt->execute([
                'hash' => $hash,
                'userId' => $reset['user_id']
            ]);
            
            // Delete the reset token
            $deleteStmt = $conn->prepare("DELETE FROM password_resets WHERE token = :token");
            $deleteStmt->execute(['token' => $token]);
            
            return true;
        }
        
        return false;
    }

} catch(PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Forgot Password</title>
</head>
<body>
    <?php
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        try {
            // Verify email exists in database
            $email = $_POST['email'];
            $stmt = $conn->prepare("SELECT id FROM users WHERE email = :email");
            $stmt->execute(['email' => $email]);
            
            if ($stmt->rowCount() > 0) {
                $user = $stmt->fetch(PDO::FETCH_ASSOC);
                sendResetEmail($email, $user['id']);
                echo "A password reset link has been sent to your email address.";
            } else {
                echo "No account found with this email address.";
            }
        } catch(PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    }
    ?>

    <h2>Forgot Password</h2>
    <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
        <label>Email:</label><br>
        <input type="email" name="email" required><br>
        <button type="submit">Send Reset Link</button>
    </form>
</body>
</html>


<?php
session_start();
include('db_connection.php'); // Include your database connection file

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];

    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['message'] = "Please enter a valid email address!";
        header("Location: forgot_password.php");
        exit();
    }

    // Check if the email exists in the database
    $sql = "SELECT id FROM users WHERE email='$email'";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) == 0) {
        $_SESSION['message'] = "Email not found!";
        header("Location: forgot_password.php");
        exit();
    }

    // Generate a random token for password reset
    $token = bin2hex(random_bytes(16));
    
    // Store the token in the database with an expiration time
    $expiration_time = date('Y-m-d H:i:s', time() + 3600); // Token expires after 1 hour
    
    $sql = "INSERT INTO password_reset_tokens (user_id, token, expires) 
            VALUES (?, ?, ?)";
            
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "iss", $id, $token, $expiration_time);
    
    if (mysqli_stmt_execute($stmt)) {
        // Send email with password reset link
        $reset_link = "http://yourwebsite.com/reset_password.php?email=$email&token=$token";
        
        $to = $email;
        $subject = "Password Reset Request";
        $message = "Please click the following link to reset your password: 
" . $reset_link . "

This link will expire in 1 hour.";
        
        // Set up mail headers
        $headers = "From: yourwebsite@example.com\r
";
        $headers .= "Reply-To: yourwebsite@example.com\r
";
        $headers .= "X-Mailer: PHP/" . phpversion();
        
        // Send the email
        if (mail($to, $subject, $message, $headers)) {
            $_SESSION['message'] = "Password reset instructions have been sent to your email address!";
            header("Location: forgot_password.php");
            exit();
        } else {
            $_SESSION['message'] = "Error sending password reset email!";
            header("Location: forgot_password.php");
            exit();
        }
    } else {
        $_SESSION['message'] = "Error resetting password! Please try again.";
        header("Location: forgot_password.php");
        exit();
    }
}

// Close database connection
mysqli_close($conn);
?>


<?php
require_once 'db_connect.php';

if (isset($_POST['email'])) {
    $email = $_POST['email'];
    
    // Query to check if email exists in database
    $stmt = $conn->prepare("SELECT id, username FROM users WHERE email = ?");
    $stmt->bind_param('s', $email);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        // Generate a unique token for password reset
        $token = bin2hex(random_bytes(32));
        $expires = date("Y-m-d H:i:s", time() + 3600); // Token expires after 1 hour
        
        // Store the token in the database
        $stmt_token = $conn->prepare("INSERT INTO password_reset_tokens (user_id, token, expires) VALUES (?, ?, ?)");
        $row = $result->fetch_assoc();
        $stmt_token->bind_param('iss', $row['id'], $token, $expires);
        
        if ($stmt_token->execute()) {
            // Send email with reset link
            $to = $email;
            $subject = "Password Reset Request";
            $message = "Dear " . $row['username'] . ",

Please click the following link to reset your password:

http://localhost/reset_password.php?token=" . $token . "

If you didn't request this password reset, please ignore this email.

Best regards,
Your Website";
            $headers = "From: noreply@yourwebsite.com" . "\r
";
            
            if (mail($to, $subject, $message, $headers)) {
                echo "<script>alert('Reset link has been sent to your email.'); window.location.href='index.php';</script>";
            } else {
                echo "Error sending email.";
            }
        } else {
            echo "Error storing reset token. Please try again later.";
        }
    } else {
        // Email not found in database
        echo "<script>alert('Email address not found.'); window.location.href='index.php';</script>";
    }
    
    $stmt->close();
    $conn->close();
}


<?php
require_once 'db_connect.php';

if (isset($_GET['token'])) {
    $token = $_GET['token'];
} else {
    die("No token provided.");
}

// Check if token exists and is valid
$stmt = $conn->prepare("SELECT * FROM password_reset_tokens WHERE token = ? AND expires > NOW()");
$stmt->bind_param('s', $token);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 1) {
    // Token is valid, show reset form
    if (isset($_POST['password'])) {
        $new_password = $_POST['password'];
        
        // Update the user's password
        $row = $result->fetch_assoc();
        $stmt_update = $conn->prepare("UPDATE users SET password = ? WHERE id = ?");
        $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
        $stmt_update->bind_param('si', $hashed_password, $row['user_id']);
        
        if ($stmt_update->execute()) {
            // Delete the token after use
            $stmt_delete = $conn->prepare("DELETE FROM password_reset_tokens WHERE token = ?");
            $stmt_delete->bind_param('s', $token);
            $stmt_delete->execute();
            
            echo "<script>alert('Password has been reset successfully!'); window.location.href='login.php';</script>";
        } else {
            echo "Error resetting password. Please try again.";
        }
    ?>
    <form action="<?php echo $_SERVER['PHP_SELF'] ?>?token=<?php echo $token ?>" method="post">
        <label for="password">New Password:</label><br>
        <input type="password" id="password" name="password" required><br><br>
        <button type="submit">Reset Password</button>
    </form>
    <?php
    } else {
        // Show the reset form
        ?>
        <h2>Reset Password</h2>
        <p>Please enter your new password:</p>
        <form action="<?php echo $_SERVER['PHP_SELF'] ?>?token=<?php echo $token ?>" method="post">
            <label for="password">New Password:</label><br>
            <input type="password" id="password" name="password" required><br><br>
            <button type="submit">Reset Password</button>
        </form>
        <?php
    }
} else {
    // Token is invalid or expired
    die("Invalid or expired token.");
}

$stmt->close();
$conn->close();
?>


<?php
$host = 'localhost';
$username = 'root'; // Change to your database username
$password = '';     // Change to your database password
$db_name = 'your_database'; // Change to your database name

$conn = new mysqli($host, $username, $password, $db_name);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>


<?php
include('db_connection.php'); // Include your database connection file

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    
    if (empty($email)) {
        header("Location: forgot.php?error=empty");
        exit();
    }
    
    $sql = "SELECT id FROM users WHERE email='$email'";
    $result = mysqli_query($conn, $sql);
    
    if (!$result || mysqli_num_rows($result) == 0) {
        header("Location: forgot.php?error=invalid");
        exit();
    } else {
        $user_id = mysqli_fetch_assoc($result)['id'];
        
        // Generate a random string
        $token = md5(rand());
        $sql_insert = "INSERT INTO password_reset (user_id, token) VALUES ('$user_id', '$token')";
        if (!mysqli_query($conn, $sql_insert)) {
            die('Error inserting token: ' . mysqli_error($conn));
        }
        
        // Send email
        $to = $email;
        $subject = "Password Reset Request";
        $message = "
            <html>
            <head>
                <title>Password Reset</title>
            </head>
            <body>
                <p>Please click the following link to reset your password:</p>
                <a href='http://yourdomain.com/reset_password.php?token=$token&id=$user_id'>
                    http://yourdomain.com/reset_password.php?token=$token&id=$user_id
                </a><br><br>
                <p>If you did not request this password reset, please disregard this email.</p>
            </body>
            </html>
        ";
        $headers = "From: your.email@yourdomain.com\r
";
        $headers .= "Content-type: text/html\r
";
        
        if (mail($to, $subject, $message, $headers)) {
            header("Location: forgot.php?error=sent");
            exit();
        } else {
            echo "Error sending email. Please try again.";
        }
    }
}
?>


<?php
include('db_connection.php'); // Include your database connection file

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $token = mysqli_real_escape_string($conn, $_POST['token']);
    $user_id = mysqli_real_escape_string($conn, $_POST['user_id']);
    $new_password = mysqli_real_escape_string($conn, $_POST['newpassword']);
    
    // Check if token exists and is valid
    $sql_check_token = "SELECT id FROM password_reset WHERE token='$token' AND user_id='$user_id'";
    $result = mysqli_query($conn, $sql_check_token);
    
    if (!$result || mysqli_num_rows($result) == 0) {
        die("Invalid or expired reset link.");
    }
    
    // Update the password in the users table
    $hash_password = md5($new_password); // You should use a more secure hashing method like password_hash()
    $sql_update = "UPDATE users SET password='$hash_password' WHERE id='$user_id'";
    if (!mysqli_query($conn, $sql_update)) {
        die('Error updating password: ' . mysqli_error($conn));
    }
    
    // Delete the reset token after use
    $sql_delete_token = "DELETE FROM password_reset WHERE token='$token'";
    if (mysqli_query($conn, $sql_delete_token)) {
        header("Location: login.php"); // Redirect to login page
        exit();
    }
}
?>


<?php
// db.php
$host = 'localhost';
$username = 'root';
$password = '';
$dbname = 'test';

$conn = new mysqli($host, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>

<?php
// forgot_password.php
include('db.php');

session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    
    // Validate email
    if (empty($email)) {
        die("Email is required");
    }
    
    // Check if email exists in the database
    $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->bind_param('s', $email);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows == 0) {
        die("Email does not exist");
    }
    
    // Generate reset token
    $token = bin2hex(random_bytes(32));
    $exp_time = time() + (1 * 60 * 60); // Expires in 1 hour
    
    // Update the database with the new token and expiry time
    $updateStmt = $conn->prepare("UPDATE users SET reset_token = ?, reset_expiry = ? WHERE email = ?");
    $updateStmt->bind_param('sis', $token, $exp_time, $email);
    $updateStmt->execute();
    
    // Send email with reset link
    $to = $email;
    $subject = "Password Reset Request";
    $message = "Please click the following link to reset your password:

http://example.com/reset_password.php?token=$token

This link expires in 1 hour.";
    $headers = "From: webmaster@example.com\r
";
    
    if (mail($to, $subject, $message, $headers)) {
        echo "An email has been sent with instructions to reset your password.";
    } else {
        die("Failed to send email");
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Forgot Password</title>
</head>
<body>
    <h2>Forgot Password</h2>
    <form action="forgot_password.php" method="post">
        <input type="email" name="email" placeholder="Enter your email" required><br><br>
        <input type="submit" value="Reset Password">
    </form>
</body>
</html>

<?php
// reset_password.php
include('db.php');

session_start();

if (isset($_GET['token'])) {
    $token = $_GET['token'];
    
    // Check if token exists and is not expired
    $stmt = $conn->prepare("SELECT id, email FROM users WHERE reset_token = ? AND reset_expiry > ?");
    $stmt->bind_param('si', $token, time());
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows == 0) {
        die("Invalid or expired token");
    }
    
    // Display password change form
    ?>
    <html>
    <head>
        <title>Reset Password</title>
    </head>
    <body>
        <h2>Reset Password</h2>
        <form action="change_password.php" method="post">
            <input type="password" name="new_password" placeholder="Enter new password" required><br><br>
            <input type="hidden" name="token" value="<?php echo $token; ?>">
            <input type="submit" value="Change Password">
        </form>
    </body>
    </html>
    <?php
} else {
    die("No token provided");
}
?>

<?php
// change_password.php
include('db.php');

session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $token = $_POST['token'];
    $new_password = $_POST['new_password'];
    
    // Check if token is valid and not expired
    $stmt = $conn->prepare("SELECT id, email FROM users WHERE reset_token = ? AND reset_expiry > ?");
    $stmt->bind_param('si', $token, time());
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows == 0) {
        die("Invalid or expired token");
    }
    
    // Hash the new password
    $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
    
    // Update the user's password
    $updateStmt = $conn->prepare("UPDATE users SET password = ?, reset_token = NULL, reset_expiry = NULL WHERE reset_token = ?");
    $updateStmt->bind_param('ss', $hashed_password, $token);
    $updateStmt->execute();
    
    echo "Password has been updated successfully!";
}
?>


<?php
include('db_connection.php'); // Include your database connection file

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = mysqli_real_escape_string($conn, $_POST['email']);

    // Check if email exists in the database
    $sql = "SELECT id FROM users WHERE email = '$email'";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) == 1) {
        // Generate a random token for password reset
        $token = bin2hex(random_bytes(32));

        // Set expiration time for the token (e.g., 30 minutes)
        $expiration_time = date('Y-m-d H:i:s', strtotime('+30 minutes'));

        // Update the database with the new token and expiration time
        $sql = "UPDATE users SET reset_token = '$token', reset_expires = '$expiration_time' WHERE email = '$email'";
        mysqli_query($conn, $sql);

        // Send the password reset email
        send_reset_email($email, $token);
        
        // Redirect to a confirmation page
        header("Location: forgot_password_sent.php");
        exit();
    } else {
        // Email not found in database
        header("Location: forgot_password.php?error=Email%20not%20found.");
        exit();
    }
}

function send_reset_email($email, $token) {
    $to = $email;
    $subject = "Password Reset Request";
    $message = "
        <html>
            <body>
                <p>We received a request to reset your password. Click the link below to reset it:</p>
                <a href='http://example.com/reset_password.php?token=$token'>Reset Password</a><br><br>
                <small>If you did not make this request, please ignore this email.</small>
            </body>
        </html>
    ";
    $headers = "From: noreply@example.com\r
";
    $headers .= "MIME-Version: 1.0\r
";
    $headers .= "Content-Type: text/html; charset=UTF-8\r
";

    mail($to, $subject, $message, $headers);
}
?>


<?php
include('db_connection.php'); // Include your database connection file

if (!isset($_GET['token'])) {
    die("Invalid request.");
}

$token = $_GET['token'];

// Check if the token exists in the database and is not expired
$sql = "SELECT id, email FROM users WHERE reset_token = '$token' AND reset_expires > NOW()";
$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) == 1) {
    // Token is valid
} else {
    die("Invalid or expired token.");
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Reset Password</title>
</head>
<body>
    <?php if (isset($_GET['error'])) { ?>
        <p style="color: red;"><?php echo $_GET['error']; ?></p>
    <?php } ?>

    <h2>Reset Password</h2>
    <form action="reset_password_process.php" method="post">
        <input type="hidden" name="token" value="<?php echo $token; ?>">
        <label for="password">New Password:</label><br>
        <input type="password" id="password" name="password" required><br><br>
        <button type="submit">Reset Password</button>
    </form>
</body>
</html>


<?php
include('db_connection.php'); // Include your database connection file

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $token = $_POST['token'];
    $password = $_POST['password'];

    // Check if token is valid and not expired
    $sql = "SELECT id, email FROM users WHERE reset_token = '$token' AND reset_expires > NOW()";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) == 1) {
        // Update the password
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $sql = "UPDATE users SET password = '$hashed_password', reset_token = '', reset_expires = '' WHERE reset_token = '$token'";
        mysqli_query($conn, $sql);

        // Redirect to success page
        header("Location: reset_password_success.php");
        exit();
    } else {
        // Invalid or expired token
        header("Location: reset_password.php?error=Invalid%20or%20expired%20token.");
        exit();
    }
}
?>


<?php
// Database connection configuration
$host = 'localhost';
$db_username = 'root';
$db_password = '';
$db_name = 'your_database';

// Connect to database
$conn = new mysqli($host, $db_username, $db_password, $db_name);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Error reporting
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Session start
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password</title>
</head>
<body>
<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if form was submitted
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    
    // Check if email exists in database
    $sql = "SELECT id FROM users WHERE email='$email'";
    $result = $conn->query($sql);
    
    if ($result->num_rows > 0) {
        // Generate a random token
        $token = md5(uniqid(rand(), true));
        
        // Update the database with the new token
        $updateSql = "UPDATE users SET reset_token='$token', reset_token_expiry=DATE_ADD(NOW(), INTERVAL 1 HOUR) WHERE email='$email'";
        if ($conn->query($updateSql)) {
            // Send the password reset email
            $to = $email;
            $subject = "Password Reset Request";
            $message = "Please click on this link to reset your password: http://yourwebsite.com/reset-password.php?token=$token
";
            $headers = "From: noreply@yourwebsite.com\r
";
            
            mail($to, $subject, $message, $headers);
            
            echo "An email has been sent to $email with instructions to reset your password.";
        } else {
            echo "Error updating record: " . $conn->error;
        }
    } else {
        echo "Email not found in our records. Please try again.";
    }
}
?>

<h2>Forgot Password</h2>
<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
    Email: <input type="text" name="email"><br><br>
    <input type="submit" value="Submit">
</form>

<?php
// Close database connection
$conn->close();
?>


<?php
// Database connection configuration
$host = 'localhost';
$db_username = 'root';
$db_password = '';
$db_name = 'your_database';

// Connect to database
$conn = new mysqli($host, $db_username, $db_password, $db_name);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Error reporting
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Session start
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial_scale=1.0">
    <title>Reset Password</title>
</head>
<body>
<?php
if (isset($_GET['token'])) {
    $token = mysqli_real_escape_string($conn, $_GET['token']);
    
    // Check if token exists and hasn't expired
    $sql = "SELECT id FROM users WHERE reset_token='$token' AND reset_token_expiry > NOW()";
    $result = $conn->query($sql);
    
    if ($result->num_rows == 1) {
        // Token is valid
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $new_password = mysqli_real_escape_string($conn, $_POST['new_password']);
            
            // Update the password
            $updateSql = "UPDATE users SET password=MD5('$new_password'), reset_token=NULL WHERE reset_token='$token'";
            if ($conn->query($updateSql)) {
                echo "Password updated successfully!";
            } else {
                echo "Error updating password: " . $conn->error;
            }
        }
        
        // Display reset form
        ?>
        <h2>Reset Password</h2>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"])."?token=".$token; ?>" method="post">
            New Password: <input type="password" name="new_password"><br><br>
            Confirm Password: <input type="password" name="confirm_password"><br><br>
            <input type="submit" value="Reset Password">
        </form>
        <?php
    } else {
        echo "Invalid or expired token.";
    }
} else {
    echo "No token provided.";
}
?>

<?php
// Close database connection
$conn->close();
?>


<?php
session_start();
// Database connection details
$host = 'localhost';
$dbname = 'your_database_name';
$username = 'root';
$password = '';

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get user input
    $email = $_POST['email'];

    // Validate email
    if (empty($email)) {
        die("Email is required!");
    }

    // Prepare SQL statement to check if email exists
    $stmt = $conn->prepare("SELECT * FROM users WHERE email=:email");
    $stmt->bindParam(':email', $email);
    $stmt->execute();

    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user) {
        die("No user found with this email address!");
    }

    // Generate a temporary password
    $temp_password = rand(1000, 9999); // You can make this more secure

    // Update the user's password in the database
    $updateStmt = $conn->prepare("UPDATE users SET password=:password WHERE email=:email");
    $updateStmt->bindParam(':password', $temp_password);
    $updateStmt->bindParam(':email', $email);
    $updateStmt->execute();

    // Send the temporary password to the user's email
    $to = $email;
    $subject = "Your Temporary Password";
    $message = "Dear " . $user['username'] . ",

Your temporary password is: " . $temp_password . "

Please login and change your password immediately.

Best regards,
Admin Team";
    $headers = "From: admin@example.com";

    if (mail($to, $subject, $message, $headers)) {
        echo "Password sent successfully! Check your email.";
        header("Refresh:3; url=login.php");
    } else {
        die("Failed to send password!");
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Forgot Password</title>
</head>
<body>
    <h2>Forgot Password</h2>
    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
        <input type="email" name="email" placeholder="Enter your email address" required><br><br>
        <input type="submit" value="Submit">
    </form>
</body>
</html>


<?php
// Database connection
$host = 'localhost';
$username = 'username';
$password = 'password';
$dbname = 'database_name';

$conn = mysqli_connect($host, $username, $password, $dbname);
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

function generateResetToken() {
    // Generate a 40 character random string
    return bin2hex(random_bytes(20));
}

// Reset password form
if (isset($_POST['reset'])) {
    $email = mysqli_real_escape_string($conn, $_POST['email']);

    // Check if email exists in the database
    $sql = "SELECT id FROM users WHERE email='$email'";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) > 0) {
        $token = generateResetToken();
        $expires = date('Y-m-d H:i:s', strtotime('+1 hour'));

        // Store token in database
        $sql = "INSERT INTO password_reset_tokens 
                (user_id, token, expires)
                VALUES ('$email', '$token', '$expires')";

        if (mysqli_query($conn, $sql)) {
            // Send email with reset link
            $to = $email;
            $subject = "Password Reset Request";
            $message = "Please click the following link to reset your password: 
                        http://example.com/reset-password.php?token=$token";
            $headers = "From: webmaster@example.com";

            if (mail($to, $subject, $message, $headers)) {
                echo "An email has been sent with instructions to reset your password.";
            } else {
                echo "There was an error sending the email. Please try again later.";
            }
        } else {
            echo "Error: " . mysqli_error($conn);
        }
    } else {
        echo "Email does not exist in our records.";
    }
}

// Reset password page
if (isset($_GET['token'])) {
    $token = $_GET['token'];
    
    // Check if token exists and is valid
    $sql = "SELECT user_id FROM password_reset_tokens 
            WHERE token='$token' AND expires > NOW()";
    
    $result = mysqli_query($conn, $sql);
    
    if (mysqli_num_rows($result) == 1) {
        // Show password reset form
        if (isset($_POST['new_password'])) {
            $new_password = $_POST['new_password'];
            
            // Update the user's password
            $row = mysqli_fetch_assoc($result);
            $user_id = $row['user_id'];
            
            $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
            
            $sql = "UPDATE users 
                    SET password='$hashed_password'
                    WHERE id='$user_id'";
                    
            if (mysqli_query($conn, $sql)) {
                // Delete token from database
                $sql = "DELETE FROM password_reset_tokens 
                        WHERE token='$token'";
                mysqli_query($conn, $sql);
                
                echo "Your password has been reset successfully!";
            } else {
                echo "Error resetting your password: " . mysqli_error($conn);
            }
        }
    } else {
        // Invalid or expired token
        echo "Invalid or expired token. Please request a new password reset.";
    }
}

// Close database connection
mysqli_close($conn);
?>


<?php
session_start();

// Configuration
$host = "localhost";
$username = "root";
$password = "";
$db_name = "your_database";

// Connect to MySQL
$conn = mysqli_connect($host, $username, $password, $db_name);
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Function to sanitize input
function sanitizeInput($data) {
    return htmlspecialchars(strip_tags(trim($data)));
}

// Process the form if submitted
if (isset($_POST['reset'])) {
    $email = isset($_POST['email']) ? sanitizeInput($_POST['email']) : "";

    // Validate email
    if (empty($email)) {
        $message = "Please enter your email address!";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $message = "Invalid email format!";
    } else {
        // Check if user exists
        $sql = "SELECT id FROM users WHERE email='" . mysqli_real_escape_string($conn, $email) . "'";
        $result = mysqli_query($conn, $sql);
        
        if (mysqli_num_rows($result) == 0) {
            $message = "No account found with this email!";
        } else {
            // Generate token
            $token = md5(uniqid(rand(), true));
            
            // Set expiration time (30 minutes)
            $expires = date("Y-m-d H:i:s", strtotime("+30 minutes"));
            
            // Insert into database
            $insert_sql = "INSERT INTO reset_password (user_id, token, expires) 
                          VALUES ('" . mysqli_real_escape_string($conn, $email) . "', '$token', '$expires')";
            $insert_result = mysqli_query($conn, $insert_sql);
            
            if (!$insert_result) {
                $message = "Error: Please try again later!";
            } else {
                // Send email
                $to = $email;
                $subject = "Password Reset Request";
                $reset_link = "http://your-site.com/reset_password.php?token=$token";
                
                $message_body = "Dear User,

You requested a password reset. Please click the following link to reset your password:
$reset_link

If you did not request this, please ignore this email.
The link will expire in 30 minutes.";
                
                if (mail($to, $subject, $message_body)) {
                    $message = "A password reset link has been sent to your email!";
                } else {
                    $message = "Error sending email. Please try again!";
                }
            }
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Forgot Password</title>
</head>
<body>
    <?php if (isset($message)) { ?>
        <p><?php echo $message; ?></p>
    <?php } ?>

    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        <input type="email" name="email" placeholder="Enter your email..." required>
        <button type="submit" name="reset">Reset Password</button>
    </form>
</body>
</html>

<?php
// Close database connection
mysqli_close($conn);
?>


<?php
// Database configuration
$host = 'localhost';
$username = 'root';
$password = '';
$dbname = 'mydatabase';

// Create connection
$conn = new mysqli($host, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// If form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get email from form data
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    
    // Validate email format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        die("Invalid email format");
    }
    
    // Check if email exists in database
    $sql = "SELECT id FROM users WHERE email = '$email'";
    $result = $conn->query($sql);
    
    if ($result->num_rows > 0) {
        // Generate a random token for password reset
        $token = bin2hex(random_bytes(32));
        
        // Store token in database along with user ID and expiration time (e.g., 1 hour)
        $expiration_time = date('Y-m-d H:i:s', strtotime('+1 hour'));
        
        $sql = "INSERT INTO reset_tokens (user_id, token, expire_time) VALUES (".$result->fetch_assoc()['id'].", '$token', '$expiration_time')";
        $conn->query($sql);
        
        // Send password reset email
        require 'PHPMailer/PHPMailer.php';
        require 'PHPMailer/SMTP.php';
        require 'PHPMailer/Exception.php';
        
        try {
            $mail = new PHPMailer\PHPMailer\PHPMailer();
            
            $mail->isSMTP();
            $mail->Host = 'smtp.example.com'; // Replace with your SMTP server
            $mail->SMTPAuth = true;
            $mail->Username = 'your_email@example.com'; // Your email username
            $mail->Password = 'your_password'; // Your email password
            $mail->SMTPSecure = 'tls';
            $mail->Port = 587;
            
            $mail->setFrom('your_email@example.com', 'Your Name');
            $mail->addAddress($email);
            
            $mail->isHTML(true);
            $mail->Subject = 'Password Reset Request';
            $mail->Body    = '<html>
                                <body>
                                    <p>Hello,</p>
                                    <p>We received a request to reset your password. Please click the link below to reset your password:</p>
                                    <a href="http://yourwebsite.com/reset-password.php?token='.$token.'">Reset Password</a>
                                    <p>If you did not request this password reset, please ignore this email.</p>
                                    <p>Best regards,<br>Your Website Team</p>
                                </body>
                            </html>';
            
            $mail->send();
        } catch (Exception $e) {
            die("Mailer Error: " . $e->getMessage());
        }
        
        // Set success message
        $_SESSION['message'] = 'We have sent you a password reset link. Please check your email.';
        header('Location: login.php');
        exit;
    } else {
        // Email not found in database
        die("Email address not found in our records");
    }
}
?>


<?php
// Database configuration
$host = 'localhost';
$username = 'root';
$password = '';
$dbname = 'your_database';

// Connect to database
$conn = mysqli_connect($host, $username, $password, $dbname);
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];

    // Sanitize email input
    $email = mysqli_real_escape_string($conn, $email);

    // Check if email exists in database
    $sql = "SELECT id FROM users WHERE email='$email'";
    $result = mysqli_query($conn, $sql);
    
    if (mysqli_num_rows($result) == 0) {
        echo "Email does not exist!";
    } else {
        // Generate a unique token for password reset
        $token = md5(uniqid(rand(), true));
        
        // Update the database with the new token and expiration time
        $sql = "UPDATE users SET password_reset_token='$token', 
                            password_reset_expires=NOW() + INTERVAL 1 HOUR
                            WHERE email='$email'";
        mysqli_query($conn, $sql);
        
        // Send email to user with reset link
        $to = $email;
        $subject = "Password Reset Request";
        $message = "
            <html>
                <head>
                    <title>Password Reset</title>
                </head>
                <body>
                    <p>Hello,</p>
                    <p>You have requested a password reset. Please click the link below to reset your password:</p>
                    <a href='http://yourwebsite.com/reset_password.php?token=$token'>Reset Password</a>
                    <p>If you did not request this password reset, please ignore this email.</p>
                    <p>This password reset link will expire in 1 hour.</p>
                </body>
            </html>
        ";
        
        $headers = "From: no-reply@yourwebsite.com\r
";
        $headers .= "Reply-To: no-reply@yourwebsite.com\r
";
        $headers .= "Content-Type: text/html; charset=UTF-8\r
";

        mail($to, $subject, $message, $headers);
        
        echo "A password reset link has been sent to your email!";
    }
}

// Close database connection
mysqli_close($conn);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Forgot Password</title>
</head>
<body>
    <h2>Forgot Password</h2>
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        <input type="email" name="email" placeholder="Enter your email address" required><br><br>
        <button type="submit">Send Reset Link</button>
    </form>
</body>
</html>


<?php
include('db_connection.php'); // Include your database connection

if (isset($_POST['email'])) {
    $email = $_POST['email'];
    
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        header("Location: forgot_password_form.php?error=Invalid email format");
        exit();
    }
    
    // Check if the email exists in the database
    $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->bind_param('s', $email);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows == 0) {
        header("Location: forgot_password_form.php?error=Email not registered");
        exit();
    } else {
        // Generate a random token
        $token = bin2hex(random_bytes(16));
        
        // Set token expiration time (e.g., 1 hour from now)
        $expires = date('Y-m-d H:i:s', strtotime('+1 hour'));
        
        // Insert the token into the database
        $stmt = $conn->prepare("INSERT INTO password_reset (user_id, token, expires) VALUES (?, ?, ?)");
        $stmt->bind_param('iss', $result->fetch_assoc()['id'], $token, $expires);
        $stmt->execute();
        
        // Send email to user with reset link
        $to = $email;
        $subject = "Password Reset Request";
        $message = "Click the following link to reset your password: http://yourwebsite.com/reset_password.php?token=$token";
        $headers = "From: yourwebsite@example.com\r
".
                   "Content-Type: text/plain; charset=UTF-8\r
".
                   "Content-Transfer-Encoding: 7bit";
        
        mail($to, $subject, $message, $headers);
        
        header("Location: forgot_password_form.php?error=Password reset link sent to your email");
    }
} else {
    // If no email was provided
    header("Location: forgot_password_form.php?error=Please enter your email address");
}
?>


<?php
include('db_connection.php'); // Include your database connection

if (!isset($_GET['token'])) {
    die("Invalid link.");
}

$token = $_GET['token'];

// Check if token exists and hasn't expired
$stmt = $conn->prepare("SELECT user_id, expires FROM password_reset WHERE token = ?");
$stmt->bind_param('s', $token);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    die("Invalid or expired reset link.");
}

$row = $result->fetch_assoc();
$expires = $row['expires'];

if (strtotime($expires) < time()) {
    die("Reset link has expired. Please request a new one.");
}

// If token is valid, show password reset form
?>

<!DOCTYPE html>
<html>
<head>
    <title>Reset Password</title>
</head>
<body>
    <h2>Reset Password</h2>
    
    <?php
        if (isset($_GET['error'])) {
            echo "<p style='color:red;'>".$_GET['error']."</p>";
        }
    ?>
    
    <form action="reset_password.php?token=<?php echo $token; ?>" method="post">
        <label for="new_password">New Password:</label><br>
        <input type="password" id="new_password" name="new_password"><br><br>
        
        <label for="confirm_password">Confirm Password:</label><br>
        <input type="password" id="confirm_password" name="confirm_password"><br><br>
        
        <input type="submit" value="Reset Password">
    </form>

<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    if ($new_password != $confirm_password) {
        header("Location: reset_password.php?token=$token&error=Passwords do not match");
        exit();
    }

    // Validate password strength (you can modify these requirements)
    if (strlen($new_password) < 8 || !preg_match('/[a-zA-Z]/', $new_password) || !preg_match('/\d/', $new_password)) {
        header("Location: reset_password.php?token=$token&error=Password must be at least 8 characters long and contain both letters and numbers");
        exit();
    }

    // Hash the new password
    $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

    // Update the user's password in the database
    $stmt = $conn->prepare("UPDATE users SET password = ? WHERE id = ?");
    $stmt->bind_param('si', $hashed_password, $row['user_id']);
    $stmt->execute();

    // Invalidate the reset token
    $stmt = $conn->prepare("DELETE FROM password_reset WHERE token = ?");
    $stmt->bind_param('s', $token);
    $stmt->execute();

    header("Location: login.php?success=Password has been successfully reset");
}
?>
</body>
</html>


<?php
require 'db_connection.php'; // Include your database connection

if (isset($_POST['email'])) {
    $email = mysqli_real_escape_string($conn, $_POST['email']);

    $sql = "SELECT id FROM users WHERE email = '$email'";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) == 0) {
        header("Location: forgot_form.php?error=Email%20not%20found");
        exit();
    }

    // Generate reset token and expiry
    $token = bin2hex(random_bytes(16));
    $expires = date('Y-m-d H:i:s', time() + 3600); // Expiry in 1 hour

    $sql = "INSERT INTO password_resets (email, token, expires) 
            VALUES ('$email', '$token', '$expires')";
    mysqli_query($conn, $sql);

    // Send email with reset link
    $to = $email;
    $subject = "Password Reset Request";
    $message = "Please click the following link to reset your password:

".
               "http://yourdomain.com/reset_password.php?token=$token

".
               "This link will expire in 1 hour.";
    $headers = "From: admin@yourdomain.com";

    mail($to, $subject, $message, $headers);

    header("Location: forgot_form.php?error=Password%20reset%20link%20sent");
    exit();
}
?>


<?php
require 'db_connection.php'; // Include your database connection

if (!isset($_GET['token']) || !isset($_POST['password'])) {
    header("Location: reset_form.php?error=Invalid%20request");
    exit();
}

$token = mysqli_real_escape_string($conn, $_GET['token']);
$password = mysqli_real_escape_string($conn, $_POST['password']);

// Verify token and check expiration
$sql = "SELECT email FROM password_resets 
        WHERE token = '$token' AND expires > NOW()";
$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) == 0) {
    header("Location: reset_form.php?error=Invalid%20or%20expired%20link");
    exit();
}

$email = mysqli_fetch_assoc($result)['email'];

// Update the password
$hash = password_hash($password, PASSWORD_DEFAULT);
$sql = "UPDATE users SET password = '$hash' WHERE email = '$email'";
mysqli_query($conn, $sql);

// Invalidate the token
$sql = "DELETE FROM password_resets WHERE token = '$token'";
mysqli_query($conn, $sql);

header("Location: login.php?success=Password%20reset%20successful");
exit();
?>


<?php
session_start();

// Database configuration
$host = 'localhost';
$dbname = 'your_database_name';
$username = 'your_username';
$password = 'your_password';

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die("Connection failed: " $e->getMessage());
}

// Function to send reset password email
function sendResetEmail($email, $token) {
    $to = $email;
    $subject = "Password Reset Request";
    $message = "
        <html>
            <head></head>
            <body>
                <h2>Password Reset</h2>
                <p>Please click the following link to reset your password:</p>
                <a href='http://yourdomain.com/reset-password.php?token=" . $token . "'>Reset Password</a><br><br>
                <p>If you did not request this password reset, please ignore this email.</p>
            </body>
        </html>
    ";
    $headers = "MIME-Version: 1.0" . "\r
";
    $headers .= "Content-type:text/html;charset=UTF-8" . "\r
";
    mail($to, $subject, $message, $headers);
}

// Function to reset password
function resetPassword($conn) {
    if (isset($_POST['reset'])) {
        $email = $_POST['email'];
        
        // Check if email exists in database
        try {
            $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
            $stmt->execute([$email]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($user) {
                // Generate random token
                $token = md5(uniqid(rand(), true));
                
                // Store token and expiration time in database
                $expires = date('Y-m-d H:i:s', strtotime('+1 hour'));
                $stmt = $conn->prepare("UPDATE users SET reset_token = ?, reset_expires = ? WHERE email = ?");
                $stmt->execute([$token, $expires, $email]);
                
                // Send reset link to user's email
                sendResetEmail($email, $token);
                
                echo "An email has been sent to you with a password reset link.";
            } else {
                echo "This email is not registered in our system.";
            }
        } catch(PDOException $e) {
            die("Error: " . $e->getMessage());
        }
    }
}

// Function to validate and update new password
function validateReset($conn) {
    if (isset($_GET['token'])) {
        $token = $_GET['token'];
        
        // Check token validity and expiration
        try {
            $stmt = $conn->prepare("SELECT * FROM users WHERE reset_token = ?");
            $stmt->execute([$token]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($user && $user['reset_expires'] > date('Y-m-d H:i:s')) {
                // Token is valid and not expired
                if (isset($_POST['submit'])) {
                    $new_password = $_POST['password'];
                    $confirm_password = $_POST['cpassword'];
                    
                    if ($new_password == $confirm_password) {
                        // Update password in database
                        $hash = password_hash($new_password, PASSWORD_DEFAULT);
                        
                        $stmt = $conn->prepare("UPDATE users SET password = ?, reset_token = null WHERE email = ?");
                        $stmt->execute([$hash, $user['email']]);
                        
                        echo "Your password has been successfully updated!";
                    } else {
                        echo "Passwords do not match!";
                    }
                }
            } else {
                echo "Invalid or expired token.";
            }
        } catch(PDOException $e) {
            die("Error: " . $e->getMessage());
        }
    }
}

// Main function
if (isset($_GET['action'])) {
    if ($_GET['action'] == 'reset') {
        resetPassword($conn);
    }
} else {
    validateReset($conn);
}

$conn = null;
?>


<?php
include('db_connection.php'); // Include your database connection

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    
    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        die("Please enter a valid email address.");
    }

    try {
        $stmt = $pdo->prepare("SELECT id, username, password FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$user) {
            die("Email not found in our records. Please check your email.");
        }

        // Generate a unique token
        $token = bin(20);
        
        // Check if the token exists to avoid duplicates
        do {
            $existingTokenStmt = $pdo->prepare("SELECT id FROM password_resets WHERE token = ?");
            $existingTokenStmt->execute([$token]);
        } while ($existingTokenStmt->fetch());

        // Insert into password_resets table
        $insertStmt = $pdo->prepare("INSERT INTO password_resets (user_id, token, created_at) VALUES (?, ?, NOW())");
        $insertStmt->execute([$user['id'], $token]);

        // Send email with reset link
        require 'PHPMailer/PHPMailer.php';
        require 'PHPMailer/SMTP.php';
        require 'PHPMailer/Exception.php';

        try {
            $mail = new PHPMailer\PHPMailer();
            $mail->isSMTP();
            $mail->Host = 'smtp.example.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'your_email@example.com';
            $mail->Password = 'your_password';
            $mail->Port = 587;

            $mail->setFrom('your_email@example.com', 'Your Name');
            $mail->addAddress($email);
            $mail->Subject = 'Reset Your Password';

            $resetLink = "http://example.com/reset.php?token=$token";
            $body = "Please click the following link to reset your password: <br><a href='$resetLink'>$resetLink</a>";
            $mail->Body = $body;

            $mail->send();
            echo "An email with instructions has been sent to you!";
        } catch (Exception $e) {
            die("Error sending email. Please try again later.");
        }
    } catch (PDOException $e) {
        die("Database error occurred: " . $e->getMessage());
    }
}
?>


<?php
include('db_connection.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $token = $_GET['token'];
    
    if (empty($token)) {
        die("Invalid request.");
    }

    // Verify token existence and validity
    try {
        $stmt = $pdo->prepare("SELECT user_id FROM password_resets WHERE token = ? AND created_at > NOW() - INTERVAL 30 MINUTE");
        $stmt->execute([$token]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$result) {
            die("Invalid or expired reset link. Please request a new one.");
        }

        // Validate password
        $new_password = $_POST['new_password'];
        $confirm_password = $_POST['confirm_password'];

        if ($new_password !== $confirm_password) {
            die("Passwords do not match.");
        }

        // Update the user's password
        $hash = password_hash($new_password, PASSWORD_DEFAULT);
        
        $updateStmt = $pdo->prepare("UPDATE users SET password = ? WHERE id = ?");
        $updateStmt->execute([$hash, $result['user_id']]);

        // Invalidate the token
        $invalidateStmt = $pdo->prepare("DELETE FROM password_resets WHERE token = ?");
        $invalidateStmt->execute([$token]);

        echo "Password reset successful! You can now login with your new password.";
    } catch (PDOException $e) {
        die("Database error occurred: " . $e->getMessage());
    }
}
?>


<?php
function sendPasswordResetEmail($recipient_email, $user_name, $site_url) {
    // Set up the email content
    $subject = "Password Reset Request";
    
    $reset_link = $site_url . "/password-reset.php?email=" . urlencode($recipient_email);
    
    $message = "
        <html>
        <head>
            <title>Password Reset</title>
        </head>
        <body style='font-family: Arial, sans-serif; line-height: 1.6;'>
            <h2>Hello " . htmlspecialchars($user_name) . ",</h2>
            
            <p>We received a request to reset your password. Please click the link below to reset it:</p>
            
            <a href='" . $reset_link . "' style='display: inline-block; padding: 10px 20px; background-color: #007bff; color: white; text-decoration: none; border-radius: 5px;'>
                Reset Password
            </a>
            
            <p>If you didn't request this password reset, you can safely ignore this email.</p>
            
            <hr style='margin: 20px 0;'>
            <p>This is an automated message from " . $site_url . ". Please do not reply to this email.</p>
        </body>
        </html>
    ";
    
    // Set up the headers
    $headers = "MIME-Version: 1.0" . "\r
";
    $headers .= "Content-type:text/html;charset=UTF-8" . "\r
";
    $headers .= "From: " . $site_url . "<no-reply@" . str_replace("http://", "", $site_url) . ">" . "\r
";
    
    // Send the email
    mail($recipient_email, $subject, $message, $headers);
}

// Example usage:
// sendPasswordResetEmail('user@example.com', 'John Doe', 'https://your-site.com');
?>


<?php
session_start();
require_once 'db.php'; // Include your database connection file

class ForgotPassword {
    private $db;
    
    public function __construct() {
        $this->db = new DBConnection();
    }
    
    // Check if email exists and send reset link
    public function handleForgotPassword($email) {
        try {
            // Check if email exists in database
            if (!$this->emailExists($email)) {
                throw new Exception("Email not found in our records.");
            }
            
            // Generate a unique token for password reset
            $token = bin2hex(random_bytes(16));
            $expires = date('Y-m-d H:i:s', time() + 3600); // Token expires after 1 hour
            
            // Store the token in database
            $this->storeResetToken($email, $token, $expires);
            
            // Send reset password email
            $this->sendResetEmail($email, $token);
            
            return true;
        } catch (Exception $e) {
            $_SESSION['error'] = $e->getMessage();
            header("Location: login.php");
            exit();
        }
    }
    
    private function emailExists($email) {
        $query = "SELECT id FROM users WHERE email = ?";
        $stmt = $this->db->prepare($query);
        $stmt->execute([$email]);
        
        return $stmt->rowCount() > 0;
    }
    
    private function storeResetToken($email, $token, $expires) {
        $query = "INSERT INTO password_resets (user_id, token, expires) 
                  VALUES (?, ?, ?)";
                  
        $stmt = $this->db->prepare($query);
        
        // Get user ID
        $userIdQuery = "SELECT id FROM users WHERE email = ?";
        $userIdStmt = $this->db->prepare($userIdQuery);
        $userIdStmt->execute([$email]);
        $row = $userIdStmt->fetch(PDO::FETCH_ASSOC);
        $user_id = $row['id'];
        
        $stmt->execute([$user_id, $token, $expires]);
    }
    
    private function sendResetEmail($email, $token) {
        require_once 'PHPMailer/PHPMailer.php';
        require_once 'PHPMailer/SMTP.php';
        require_once 'PHPMailer/Exception.php';
        
        $mail = new PHPMailer(true);
        
        try {
            // Server settings
            $mail->isSMTP();
            $mail->Host = 'smtp.example.com';  // Replace with your SMTP server
            $mail->SMTPAuth = true;
            $mail->Username = 'your_email@example.com'; // Replace with your email
            $mail->Password = 'your_password';          // Replace with your password
            $mail->Port = 587;                          // TCP port to connect to
            
            // Recipients
            $mail->setFrom('your_email@example.com', 'Your Name');
            $mail->addAddress($email);
            
            // Content
            $resetLink = "http://$_SERVER[HTTP_HOST]/reset-password.php?token=$token";
            $body = "Click the following link to reset your password: <a href='$resetLink'>$resetLink</a>";
            $body .= "<br>If you're unable to click the link, copy and paste this token into the reset form: $token";
            
            $mail->isHTML(true);
            $mail->Subject = 'Password Reset Request';
            $mail->Body    = $body;
            
            $mail->send();
        } catch (Exception $e) {
            throw new Exception("Email could not be sent. Error: {$mail->ErrorInfo}");
        }
    }
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    
    $forgotPassword = new ForgotPassword();
    if ($forgotPassword->handleForgotPassword($email)) {
        // Reset password email sent successfully
        $_SESSION['success'] = "We've sent a password reset link to your email address.";
        header("Location: login.php");
        exit();
    }
}
?>


<?php
// Database configuration
$host = 'localhost';
$dbname = 'your_database_name';
$username = 'your_username';
$password = 'your_password';

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

// Generate a random token
function generateToken() {
    return bin2hex(random_bytes(16));
}

// Check if email exists in database
function checkEmail($email, $conn) {
    $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->execute([$email]);
    return $stmt->rowCount();
}

// Store reset token and expiration time
function storeResetToken($userId, $token, $conn) {
    $expirationTime = date('Y-m-d H:i:s', strtotime('+1 hour'));
    $stmt = $conn->prepare("INSERT INTO password_reset (user_id, token, expires_at) VALUES (?, ?, ?)");
    return $stmt->execute([$userId, $token, $expirationTime]);
}

// Send reset email
function sendResetEmail($email, $resetLink) {
    $to = $email;
    $subject = "Password Reset Request";
    $message = "
        <html>
            <head></head>
            <body>
                <p>Hello,</p>
                <p>We received a password reset request. Click the link below to reset your password:</p>
                <a href='$resetLink'>$resetLink</a>
                <br><br>
                If you did not request this, please ignore this email.
            </body>
        </html>";
    $headers = "MIME-Version: 1.0" . "\r
";
    $headers .= "Content-type:text/html;charset=UTF-8" . "\r
";
    $headers .= "From: your_email@example.com" . "\r
";

    return mail($to, $subject, $message, $headers);
}

// Reset password function
function resetPassword($token, $newPassword, $conn) {
    // Verify token exists and is valid
    $stmt = $conn->prepare("SELECT user_id FROM password_reset WHERE token = ? AND expires_at > NOW()");
    $stmt->execute([$token]);
    
    if ($stmt->rowCount() == 1) {
        list($userId) = $stmt->fetch(PDO::FETCH_NUM);
        
        // Update user's password
        $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
        $updateStmt = $conn->prepare("UPDATE users SET password = ? WHERE id = ?");
        if ($updateStmt->execute([$hashedPassword, $userId])) {
            // Invalidate the token
            $invalidateStmt = $conn->prepare("DELETE FROM password_reset WHERE token = ?");
            $invalidateStmt->execute([$token]);
            return true;
        }
    }
    return false;
}

// Check if email exists and send reset link
if (isset($_POST['email'])) {
    $email = $_POST['email'];
    $token = generateToken();
    
    if (checkEmail($email, $conn)) {
        // Get user ID
        $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->execute([$email]);
        list($userId) = $stmt->fetch(PDO::FETCH_NUM);
        
        // Store token
        storeResetToken($userId, $token, $conn);
        
        // Create reset link
        $resetLink = "http://$_SERVER[HTTP_HOST]/reset-password.php?token=$token";
        
        // Send email
        if (sendResetEmail($email, $resetLink)) {
            echo "An email has been sent to your inbox with instructions to reset your password.";
        } else {
            echo "There was an error sending the email. Please try again later.";
        }
    } else {
        echo "This email address does not exist in our database.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Forgot Password</title>
    <style>
        body { font-family: Arial, sans-serif; max-width: 500px; margin: 0 auto; padding: 20px; }
        .form-group { margin-bottom: 10px; }
        input { width: 100%; padding: 8px; }
        button { background-color: #4CAF50; color: white; padding: 10px 20px; border: none; border-radius: 4px; cursor: pointer; }
    </style>
</head>
<body>
    <h1>Forgot Password</h1>
    <p>Please enter your email address to reset your password.</p>
    
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <div class="form-group">
            <input type="email" name="email" placeholder="Enter your email..." required>
        </div>
        <button type="submit">Send Reset Link</button>
    </form>
</body>
</html>

// Create a separate file (reset-password.php) for the password reset form

<?php
session_start();
if (!isset($_GET['token'])) {
    header("Location: forgot-password.php");
    exit();
}

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

// Verify token
$stmt = $conn->prepare("SELECT user_id FROM password_reset WHERE token = ? AND expires_at > NOW()");
$stmt->execute([$_GET['token']]);
if ($stmt->rowCount() != 1) {
    header("Location: forgot-password.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Reset Password</title>
    <style>
        body { font-family: Arial, sans-serif; max-width: 500px; margin: 0 auto; padding: 20px; }
        .form-group { margin-bottom: 10px; }
        input { width: 100%; padding: 8px; }
        button { background-color: #4CAF50; color: white; padding: 10px 20px; border: none; border-radius: 4px; cursor: pointer; }
    </style>
</head>
<body>
    <h1>Reset Password</h1>
    
    <?php if (isset($_GET['success'])) { ?>
        <p>Password reset successfully! <a href="login.php">Click here to login</a>.</p>
    <?php } else { ?>

    <form action="reset-password-process.php" method="post">
        <input type="hidden" name="token" value="<?php echo $_GET['token']; ?>">
        <div class="form-group">
            <input type="password" name="new_password" placeholder="Enter new password..." required>
        </div>
        <div class="form-group">
            <input type="password" name="confirm_password" placeholder="Confirm new password..." required>
        </div>
        <button type="submit">Reset Password</button>
    </form>

    <?php } ?>

</body>
</html>

// Create a separate file (reset-password-process.php) to handle the password reset

<?php
session_start();
if (!isset($_POST['token']) || !isset($_POST['new_password'])) {
    header("Location: forgot-password.php");
    exit();
}

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

// Verify token and reset password
if (resetPassword($_POST['token'], $_POST['new_password'], $conn)) {
    header("Location: reset-password.php?success=1");
    exit();
} else {
    echo "There was an error resetting your password. Please try again.";
}
?>


if (isset($_POST['email'])) {
    $email = $_POST['email'];
    
    // Validate email
    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        die("Invalid email address");
    }
}


try {
    $pdo = new PDO('mysql:host=localhost;dbname=your_database', 'username', 'password');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Prepare and execute the query
    $stmt = $pdo->prepare("SELECT id FROM users WHERE email = :email");
    $stmt->execute(['email' => $email]);
    
    if ($stmt->rowCount() == 0) {
        die("Email not found in our records.");
    }
} catch (PDOException $e) {
    die("Database error: " . $e->getMessage());
}


$token = bin2hex(random_bytes(16));
$expires = date('Y-m-d H:i:s', strtotime('+30 minutes'));

// Update user record with new token and expiry
$stmt = $pdo->prepare("UPDATE users SET reset_token = :token, reset_expires = :expires WHERE email = :email");
$stmt->execute(['token' => $token, 'expires' => $expires, 'email' => $email]);


$subject = "Password Reset Request";
$message = "Dear User,

Please click on the following link to reset your password:

http://example.com/reset.php?token=$token

This link will expire in 30 minutes.

Best regards,
Your Support Team";
$headers = "From: noreply@example.com\r
";

mail($email, $subject, $message, $headers);


echo "An email with instructions to reset your password has been sent to $email.";


<?php
function forgot_password($email) {
    // Database configuration
    $host = 'localhost';
    $db_name = 'your_database';
    $username = 'username';
    $password = 'password';

    try {
        $pdo = new PDO("mysql:host=$host;dbname=$db_name", $username, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Check if email exists
        $stmt = $pdo->prepare("SELECT id FROM users WHERE email = :email");
        $stmt->execute(['email' => $email]);
        if ($stmt->rowCount() == 0) {
            return "Email not found.";
        }

        // Generate token and expiry time
        $token = bin2hex(random_bytes(16));
        $expires = date('Y-m-d H:i:s', strtotime('+30 minutes'));

        // Update user record
        $stmt = $pdo->prepare("UPDATE users SET reset_token = :token, reset_expires = :expires WHERE email = :email");
        $stmt->execute(['token' => $token, 'expires' => $expires, 'email' => $email]);

        // Send email
        $subject = "Password Reset Request";
        $message = "Dear User,

Please click on the following link to reset your password:

http://example.com/reset.php?token=$token

This link will expire in 30 minutes.

Best regards,
Your Support Team";
        $headers = "From: noreply@example.com\r
";

        if (mail($email, $subject, $message, $headers)) {
            return "Password reset instructions sent to your email.";
        } else {
            return "Failed to send email. Please try again later.";
        }

    } catch(PDOException $e) {
        return "Error: " . $e->getMessage();
    }
}

if (isset($_POST['email'])) {
    $email = $_POST['email'];
    if (!empty($email) && filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo forgot_password($email);
    } else {
        die("Invalid email address");
    }
}
?>


<?php
if (isset($_GET['token'])) {
    $token = $_GET['token'];
    
    // Database connection
    $host = 'localhost';
    $db_name = 'your_database';
    $username = 'username';
    $password = 'password';

    try {
        $pdo = new PDO("mysql:host=$host;dbname=$db_name", $username, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Check if token is valid and not expired
        $stmt = $pdo->prepare("SELECT id, reset_expires FROM users WHERE reset_token = :token");
        $stmt->execute(['token' => $token]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$user) {
            die("Invalid or expired token.");
        }

        // Check expiration
        if (strtotime($user['reset_expires']) < time()) {
            die("Token has expired. Please request a new one.");
        }

        // Show password reset form
        ?>
        <form method="post">
            New Password: <input type="password" name="new_password" required><br>
            Confirm Password: <input type="password" name="confirm_password" required><br>
            <input type="hidden" name="token" value="<?php echo $token; ?>">
            <input type="submit" value="Reset Password">
        </form>

        <?php
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $newPassword = $_POST['new_password'];
            $confirmPassword = $_POST['confirm_password'];
            
            if ($newPassword != $confirmPassword) {
                die("Passwords do not match.");
            }

            // Hash the password
            $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);

            // Update password
            $stmt = $pdo->prepare("UPDATE users SET password = :password WHERE reset_token = :token");
            $stmt->execute(['password' => $hashedPassword, 'token' => $token]);

            // Invalidate token
            $stmt = $pdo->prepare("UPDATE users SET reset_token = NULL, reset_expires = NULL WHERE id = :id");
            $stmt->execute(['id' => $user['id']]);

            echo "Password has been successfully updated!";
        }
    } catch(PDOException $e) {
        die("Error: " . $e->getMessage());
    }
} else {
    die("No token provided.");
}
?>


<?php
// Include database configuration file
include('db_config.php');

if(isset($_POST['reset_password'])) {
    // Get email from POST request
    $email = $_POST['email'];
    
    // Validate email
    if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        die("Invalid email format");
    }
    
    // Check if email exists in database
    $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->bind_param('s', $email);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if($result->num_rows == 0) {
        die("Email not registered");
    }
    
    // Generate reset token
    $token = md5(uniqid(rand(), true));
    $expires = date('Y-m-d H:i:s', time() + 3600); // Token expires after 1 hour
    
    // Update database with the reset token and expiration time
    $stmt = $conn->prepare("UPDATE users SET reset_token = ?, reset_expires = ? WHERE email = ?");
    $stmt->bind_param('sss', $token, $expires, $email);
    
    if($stmt->execute()) {
        // Send password reset link to user's email
        $reset_link = "http://example.com/reset_password.php?token=" . $token;
        
        $to = $email;
        $subject = "Password Reset Request";
        $message = "
            <html>
                <body>
                    <p>We received a request to reset your password. Please click the link below to reset it:</p>
                    <a href='$reset_link'>Reset Password</a><br><br>
                    <p>If you did not request this password reset, please ignore this email.</p>
                    <p>This link will expire in 30 minutes.</p>
                </body>
            </html>
        ";
        
        $headers = "MIME-Version: 1.0" . "\r
";
        $headers .= "Content-type:text/html;charset=UTF-8" . "\r
";
        $headers .= "From: <your_email@example.com>" . "\r
";
        
        if(mail($to, $subject, $message, $headers)) {
            // Redirect to login page with success message
            header("Location: login.php?msg=We've sent you a password reset link. Check your email.");
            exit();
        } else {
            die("Failed to send reset link");
        }
    } else {
        die("Error resetting password");
    }
}
?>


<?php
// Database configuration
$host = 'localhost';
$username = 'root';
$password = '';
$database = 'your_database';

// Create connection
$conn = new mysqli($host, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Function to generate a random token
function generateToken() {
    $characters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
    $token = '';
    for ($i = 0; $i < 30; $i++) {
        $token .= $characters[rand(0, strlen($characters) - 1)];
    }
    return $token;
}

// Function to send password reset email
function sendResetEmail($email, $resetToken) {
    $to = $email;
    $subject = 'Password Reset Request';
    $message = "Please click the following link to reset your password:

http://yourwebsite.com/reset-password.php?token=" . $resetToken;
    $headers = 'From: noreply@yourwebsite.com' . "\r
";
    
    if (mail($to, $subject, $message, $headers)) {
        return true;
    } else {
        return false;
    }
}

// Function to handle password reset request
function forgotPassword($email) {
    global $conn;
    
    // Check if email exists in the database
    $sql = "SELECT id FROM users WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();
        $userId = $row['id'];
        
        // Generate a new reset token
        $resetToken = generateToken();
        $expires = date('Y-m-d H:i:s', strtotime('+1 hour'));
        
        // Insert the reset token into the database
        $sqlReset = "INSERT INTO password_resets (user_id, token, expires) VALUES (?, ?, ?)";
        $stmtReset = $conn->prepare($sqlReset);
        $stmtReset->bind_param("iss", $userId, $resetToken, $expires);
        
        if ($stmtReset->execute()) {
            // Send reset email
            if (sendResetEmail($email, $resetToken)) {
                return "A password reset link has been sent to your email address.";
            } else {
                return "An error occurred while sending the reset email.";
            }
        } else {
            return "An error occurred while processing your request.";
        }
    } else {
        return "Email not found in our records.";
    }
}

// Function to validate and reset password
function resetPassword($token, $newPassword) {
    global $conn;
    
    // Check if token exists and is valid
    $sql = "SELECT user_id FROM password_resets WHERE token = ? AND expires > NOW()";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();
        $userId = $row['user_id'];
        
        // Hash the new password
        $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
        
        // Update the user's password
        $sqlUpdate = "UPDATE users SET password = ? WHERE id = ?";
        $stmtUpdate = $conn->prepare($sqlUpdate);
        $stmtUpdate->bind_param("si", $hashedPassword, $userId);
        
        if ($stmtUpdate->execute()) {
            // Delete the reset token
            $sqlDelete = "DELETE FROM password_resets WHERE token = ?";
            $stmtDelete = $conn->prepare($sqlDelete);
            $stmtDelete->bind_param("s", $token);
            $stmtDelete->execute();
            
            return "Password has been successfully reset.";
        } else {
            return "An error occurred while resetting your password.";
        }
    } else {
        return "Invalid or expired token.";
    }
}

// Example usage:
if (isset($_POST['email'])) {
    $email = $_POST['email'];
    echo forgotPassword($email);
}

if (isset($_GET['token']) && isset($_POST['new_password'])) {
    $token = $_GET['token'];
    $newPassword = $_POST['new_password'];
    echo resetPassword($token, $newPassword);
}
?>


<?php
// Database configuration
$host = 'localhost';
$dbname = 'your_database_name';
$username = 'your_username';
$password = 'your_password';

try {
    // Create a new PDO instance
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

// Function to generate a random password
function generatePassword($length = 12) {
    $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*()';
    $password = substr(str_shuffle($chars), 0, $length);
    return $password;
}

// Function to send reset password email
function sendResetPasswordEmail($email, $newPassword) {
    $to = $email;
    $subject = 'Reset Your Password';
    $message = "Dear User,

Your new password is: " . $newPassword . "

Please login and change your password immediately.

Best regards,
The Support Team";
    $headers = 'From: noreply@yourdomain.com' . "\r
" .
               'Reply-To: noreply@yourdomain.com' . "\r
" .
               'X-Mailer: PHP/' . phpversion();

    return mail($to, $subject, $message, $headers);
}

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get email from the form
    $email = $_POST['email'];

    try {
        // Prepare and execute the query
        $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->execute([$email]);
        
        // Check if user exists
        if ($stmt->rowCount() > 0) {
            // Generate a new password
            $newPassword = generatePassword();
            
            // Update the password in the database
            $updateStmt = $conn->prepare("UPDATE users SET password = ? WHERE email = ?");
            $updateStmt->execute([md5($newPassword), $email]);
            
            // Send reset password email
            if (sendResetPasswordEmail($email, $newPassword)) {
                echo "A new password has been sent to your email address.";
            } else {
                echo "Error sending email. Please try again later.";
            }
        } else {
            echo "This email does not exist in our records.";
        }
    } catch (PDOException $e) {
        die("Error: " . $e->getMessage());
    }
}

// Close the database connection
$conn = null;
?>


<?php
session_start();
// Connect to database
require_once 'config.php';

// Error/success messages
$messages = array();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = isset($_POST['email']) ? $_POST['email'] : '';
    
    if (empty($email)) {
        $messages[] = "Please enter your email address.";
    } else {
        // Check if email exists in the database
        $sql = "SELECT id FROM users WHERE email=?";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, 's', $email);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        
        if (mysqli_num_rows($result) == 0) {
            $messages[] = "This email is not registered.";
        } else {
            // Generate a random token
            $token = bin2hex(random_bytes(16));
            
            // Store the token in the database with expiration time (e.g., 1 hour)
            $expiration_time = time() + 3600;
            $sql = "INSERT INTO password_reset_tokens (user_id, token, expires_at) 
                    VALUES (?, ?, ?)";
            $stmt = mysqli_prepare($conn, $sql);
            mysqli_stmt_bind_param($stmt, 'iss', $result->fetch_assoc()['id'], $token, $expiration_time);
            mysqli_stmt_execute($stmt);
            
            // Send reset password email
            $reset_url = "http://".$_SERVER['HTTP_HOST']."/reset_password.php?token=".$token;
            $to = $email;
            $subject = "Reset Your Password";
            $message = "Click the following link to reset your password: 

".$reset_url."

This link will expire in 1 hour.";
            
            if (mail($to, $subject, $message)) {
                $messages[] = "We've sent you a password reset email. Check your inbox!";
            } else {
                $messages[] = "An error occurred while sending the email.";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password</title>
</head>
<body>
    <div class="container">
        <?php foreach ($messages as $message) { ?>
            <div class="alert <?php echo (strpos($message, 'error:') === 0) ? 'error' : 'success'; ?>">
                <?php echo str_replace('error:', '', $message); ?>
            </div>
        <?php } ?>

        <h2>Forgot Password</h2>
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
            <label for="email">Email:</label>
            <input type="text" name="email" id="email">
            <button type="submit">Send Reset Link</button>
        </form>
    </div>
</body>
</html>


<?php
session_start();
require_once 'config.php';

if (!isset($_GET['token'])) {
    die("Invalid request.");
}

$token = $_GET['token'];

// Check if token exists and is valid
$sql = "SELECT * FROM password_reset_tokens WHERE token=? AND expires_at > ?";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, 'si', $token, time());
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if (mysqli_num_rows($result) == 0) {
    die("Invalid or expired token.");
}

// If the token is valid, show the password reset form
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
</head>
<body>
    <div class="container">
        <?php
            if (isset($_POST['submit'])) {
                $password = $_POST['password'];
                $confirm_password = $_POST['confirm_password'];
                
                if ($password !== $confirm_password) {
                    echo "Passwords do not match.";
                } else {
                    // Update the user's password
                    $user_id = $result->fetch_assoc()['user_id'];
                    
                    // Hash the new password
                    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                    
                    $sql = "UPDATE users SET password=? WHERE id=?";
                    $stmt = mysqli_prepare($conn, $sql);
                    mysqli_stmt_bind_param($stmt, 'si', $hashed_password, $user_id);
                    mysqli_stmt_execute($stmt);
                    
                    // Delete the token after use
                    $sql = "DELETE FROM password_reset_tokens WHERE token=?";
                    $stmt = mysqli_prepare($conn, $sql);
                    mysqli_stmt_bind_param($stmt, 's', $token);
                    mysqli_stmt_execute($stmt);
                    
                    echo "Password reset successfully! You can now <a href='login.php'>log in</a>";
                }
            } else {
        ?>
        
        <h2>Reset Password</h2>
        <form action="<?php echo $_SERVER['PHP_SELF'] . '?token=' . $token; ?>" method="POST">
            <label for="password">New Password:</label>
            <input type="password" name="password" id="password">
            
            <label for="confirm_password">Confirm Password:</label>
            <input type="password" name="confirm_password" id="confirm_password">
            
            <button type="submit" name="submit">Reset Password</button>
        </form>
        
        <?php } ?>
    </div>
</body>
</html>


<?php
$host = 'localhost';
$db_user = 'root';
$db_pass = '';
$db_name = 'your_database';

$conn = mysqli_connect($host, $db_user, $db_pass, $db_name);
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Create password_reset_tokens table if it doesn't exist
$sql = "CREATE TABLE IF NOT EXISTS password_reset_tokens (
        id INT AUTO_INCREMENT PRIMARY KEY,
        user_id INT NOT NULL,
        token VARCHAR(255) NOT NULL,
        expires_at INT NOT NULL,
        FOREIGN KEY (user_id) REFERENCES users(id)
    )";
mysqli_query($conn, $sql);
?>


<?php
// Database configuration
$host = 'localhost';
$user = 'root';
$password = '';
$database = 'my_database';

// Connect to database
$conn = new mysqli($host, $user, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Session start
session_start();

// If form submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Get user input
    $email = $_POST['email'];

    // Check if email exists in database
    $sql = "SELECT id, username FROM users WHERE email=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {

        // Generate reset token
        $reset_token = bin2hex(random_bytes(16));

        // Set token expiration time (1 hour from now)
        $expires = date('Y-m-d H:i:s', strtotime('+1 hour'));

        // Update database with reset token and expiration time
        $sql = "UPDATE users SET reset_token=?, expires=? WHERE email=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sss", $reset_token, $expires, $email);
        $stmt->execute();

        // Send password reset email
        $to = $email;
        $subject = "Password Reset Request";
        
        // Email content
        $message = "
            <html>
            <head></head>
            <body>
                <h2>Password Reset</h2>
                <p>Please click the following link to reset your password:</p>
                <a href='http://localhost/reset_password.php?token=$reset_token'>Reset Password</a><br><br>
                <small>This link will expire in 1 hour.</small>
            </body>
            </html>
        ";
        
        // Set headers for email
        $headers = "MIME-Version: 1.0\r
";
        $headers .= "Content-Type: text/html; charset=UTF-8\r
";
        $headers .= "From: webmaster@example.com\r
";

        // Send email
        mail($to, $subject, $message, $headers);

        // Set success message and redirect after 5 seconds
        $_SESSION['message'] = "We've sent a password reset link to your email.";
        header("refresh:5; url=http://localhost/forgot_password.php");
    } else {
        // Email not found in database
        $_SESSION['error'] = "This email address is not registered with us.";
        header("Location: http://localhost/forgot_password.php");
    }
}

// Close database connection
$conn->close();
?>


<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Get token from URL
    $token = $_GET['token'];

    // Get user input
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    // Check if passwords match
    if ($new_password != $confirm_password) {
        $_SESSION['error'] = "Passwords do not match!";
        header("Location: reset_password.php?token=$token");
        exit();
    }

    // Connect to database
    $conn = new mysqli($host, $user, $password, $database);

    // Check token validity
    $sql = "SELECT id, username FROM users WHERE reset_token=? AND expires > NOW()";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {

        // Update password
        $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
        $sql = "UPDATE users SET password=?, reset_token=NULL WHERE reset_token=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $hashed_password, $token);
        $stmt->execute();

        $_SESSION['message'] = "Your password has been reset!";
        header("Location: login.php");
    } else {
        $_SESSION['error'] = "Invalid or expired token!";
        header("Location: forgot_password.php");
    }

    // Close database connection
    $conn->close();
}
?>


<!DOCTYPE html>
<html>
<head>
    <title>Forgot Password</title>
</head>
<body>
    <?php
        $emailError = "";
        
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $email = trim($_POST['email']);
            
            // Check if email is provided and valid
            if (empty($email)) {
                $emailError = "Email is required";
            } else {
                // Proceed to send reset link
                require_once('db_connect.php');
                
                // Prepare SQL query
                $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
                $stmt->bind_param("s", $email);
                $stmt->execute();
                $result = $stmt->get_result();
                
                if ($result->num_rows == 0) {
                    $emailError = "Email not found in our records";
                } else {
                    // Generate a random token
                    $token = bin2hex(random_bytes(16));
                    
                    // Store the token and expiration time in the database
                    $expires = date('Y-m-d H:i:s', strtotime('+30 minutes'));
                    
                    $stmt = $conn->prepare("INSERT INTO password_reset (user_id, token, expires) VALUES (?, ?, ?)");
                    $row = $result->fetch_assoc();
                    $stmt->bind_param("iss", $row['id'], $token, $expires);
                    $stmt->execute();
                    
                    // Send email
                    $resetLink = "http://".$_SERVER['HTTP_HOST']."/reset_password.php?token=".$token;
                    
                    $to = $email;
                    $subject = "Password Reset Request";
                    $message = "Please click the following link to reset your password:

".$resetLink."

This link will expire in 30 minutes.";
                    $headers = "From: yourwebsite@example.com" . "\r
";
                    
                    mail($to, $subject, $message, $headers);
                    
                    echo "<p>Password reset instructions have been sent to your email address.</p>";
                }
            }
        }
    ?>
    
    <h2>Forgot Password</h2>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <?php if ($emailError != "") { ?>
            <div style="color: red;"><?php echo $emailError; ?></div><br>
        <?php } ?>
        Email: <input type="text" name="email" value="<?php echo $_POST['email']; ?>"><br>
        <input type="submit" value="Send Reset Link">
    </form>
</body>
</html>


<?php
session_start();

require_once('db_connect.php');

// Check if token is provided in URL and is valid
if (isset($_GET['token'])) {
    $token = $_GET['token'];
    
    // Get the token details from the database
    $stmt = $conn->prepare("SELECT * FROM password_reset WHERE token = ? AND expires > NOW()");
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows == 0) {
        die("Invalid or expired reset link.");
    } else {
        // Token is valid
        $row = $result->fetch_assoc();
        $_SESSION['reset_user_id'] = $row['user_id'];
        
        // Show password reset form
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $new_password = trim($_POST['new_password']);
            $confirm_password = trim($_POST['confirm_password']);
            
            if ($new_password != $confirm_password) {
                die("Passwords do not match.");
            }
            
            // Minimum password length validation
            if (strlen($new_password) < 6) {
                die("Password must be at least 6 characters long.");
            }
            
            // Hash the new password
            $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
            
            // Update the database
            $stmt = $conn->prepare("UPDATE users SET password = ? WHERE id = ?");
            $stmt->bind_param("si", $hashed_password, $_SESSION['reset_user_id']);
            $stmt->execute();
            
            // Delete the reset token
            $stmt = $conn->prepare("DELETE FROM password_reset WHERE user_id = ?");
            $stmt->bind_param("i", $_SESSION['reset_user_id']);
            $stmt->execute();
            
            echo "Password has been successfully updated. You can now <a href='login.php'>login</a>.";
        } else {
            // Show the form
?>
<!DOCTYPE html>
<html>
<head>
    <title>Reset Password</title>
</head>
<body>
    <h2>Reset Password</h2>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"].'?token='.$token); ?>" method="post">
        New Password: <input type="password" name="new_password"><br>
        Confirm Password: <input type="password" name="confirm_password"><br><br>
        <input type="submit" value="Reset Password">
    </form>
</body>
</html>
<?php
        }
    }
} else {
    die("No token provided.");
}
?>


<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "your_database";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>


// forgot_password.php
<?php
session_start();
include('config.php'); // Include your database configuration file

if (isset($_POST['reset_request'])) {
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    
    if (empty($email)) {
        $error = "Please enter your email address";
    } else {
        // Check if email exists in the database
        $sql = "SELECT * FROM users WHERE email = '$email'";
        $result = mysqli_query($conn, $sql);
        
        if (mysqli_num_rows($result) == 1) {
            // Generate a unique token and expiration time
            $token = bin2hex(random_bytes(32));
            $expires = date('Y-m-d H:i:s', strtotime('+1 hour'));
            
            // Update the user's record with the new token and expiration time
            $update_sql = "UPDATE users SET reset_token = '$token', reset_expires = '$expires' WHERE email = '$email'";
            mysqli_query($conn, $update_sql);
            
            // Send reset password email
            $to = $email;
            $subject = "Password Reset Request";
            $message = "
                <html>
                    <head></head>
                    <body>
                        <p>You have requested to reset your password. Click the link below to set a new password:</p>
                        <a href='http://yourdomain.com/reset_password.php?token=$token'>Reset Password</a><br>
                        <small>This link will expire in 1 hour.</small>
                    </body>
                </html>
            ";
            
            // Set headers for email
            $headers = "MIME-Version: 1.0\r
";
            $headers .= "Content-type: text/html; charset=UTF-8\r
";
            $headers .= "From: yourname@yourdomain.com\r
";
            
            mail($to, $subject, $message, $headers);
            
            // Redirect to password reset confirmation
            header("Location: forgot_password_success.php");
            exit();
        } else {
            $error = "Email address not found in our records";
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Forgot Password</title>
</head>
<body>
    <?php if (isset($error)) { echo "<p style='color:red'>$error</p>"; } ?>
    <h2>Forgot Password</h2>
    <form action="<?php $_PHP_SELF ?>" method="post">
        <input type="email" name="email" placeholder="Enter your email address" required><br><br>
        <input type="submit" name="reset_request" value="Request Reset">
    </form>
</body>
</html>


// reset_password.php
<?php
session_start();
include('config.php');

if (!isset($_GET['token']) || empty($_GET['token'])) {
    header("Location: forgot_password.php");
    exit();
}

$token = mysqli_real_escape_string($conn, $_GET['token']);

$sql = "SELECT * FROM users WHERE reset_token = '$token'";
$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) == 0) {
    // Invalid token
    header("Location: forgot_password.php?error=invalid_token");
    exit();
}

$user = mysqli_fetch_assoc($result);
$expires = $user['reset_expires'];

// Check if the reset link has expired
$current_time = date('Y-m-d H:i:s');
if ($current_time > $expires) {
    // Token has expired, regenerate a new token and send a new email
    $new_token = bin2hex(random_bytes(32));
    $new_expires = date('Y-m-d H:i:s', strtotime('+1 hour'));
    
    $update_sql = "UPDATE users SET reset_token = '$new_token', reset_expires = '$new_expires' WHERE id = {$user['id']}";
    mysqli_query($conn, $update_sql);
    
    // Send new reset password email
    $to = $user['email'];
    $subject = "New Password Reset Request";
    $message = "
        <html>
            <head></head>
            <body>
                <p>Your previous password reset link has expired. Click the new link below to set a new password:</p>
                <a href='http://yourdomain.com/reset_password.php?token=$new_token'>Reset Password</a><br>
                <small>This link will expire in 1 hour.</small>
            </body>
        </html>
    ";
    
    $headers = "MIME-Version: 1.0\r
";
    $headers .= "Content-type: text/html; charset=UTF-8\r
";
    $headers .= "From: yourname@yourdomain.com\r
";
    
    mail($to, $subject, $message, $headers);
    
    header("Location: forgot_password.php?error=expired_token");
    exit();
}

if (isset($_POST['reset_password'])) {
    // Validate and set the new password
    if ($_POST['new_password'] != $_POST['confirm_password']) {
        $error = "Passwords do not match";
    } else {
        $new_password = mysqli_real_escape_string($conn, $_POST['new_password']);
        
        // Update the user's password
        $update_sql = "UPDATE users SET password = '$new_password', reset_token = NULL WHERE id = {$user['id']}";
        mysqli_query($conn, $update_sql);
        
        // Destroy the token after use
        header("Location: login.php?success=1");
        exit();
    }
}

?>

<!DOCTYPE html>
<html>
<head>
    <title>Reset Password</title>
</head>
<body>
    <?php if (isset($error)) { echo "<p style='color:red'>$error</p>"; } ?>
    <h2>Set New Password</h2>
    <form action="<?php $_PHP_SELF ?>?token=<?php echo $token ?>" method="post">
        <input type="password" name="new_password" placeholder="New password" required><br><br>
        <input type="password" name="confirm_password" placeholder="Confirm new password" required><br><br>
        <input type="submit" name="reset_password" value="Set Password">
    </form>
</body>
</html>


<!DOCTYPE html>
<html>
<head>
    <title>Forgot Password</title>
    <!-- Include your CSS -->
    <link rel="stylesheet" type="text/css" href="styles.css">
</head>
<body>
    <div class="container">
        <h2>Forgot Password</h2>
        <form action="reset-password.php" method="post">
            <?php
                // CSRF Token Generation
                session_start();
                if (!isset($_SESSION['token'])) {
                    $_SESSION['token'] = bin_hex(random_bytes(32));
                }
            ?>
            <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['token']; ?>">
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required>
            </div>
            <button type="submit">Reset Password</button>
        </form>
    </div>
</body>
</html>


<?php
session_start();

// Check CSRF token
if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['token']) {
    header("Location: forgot-password.php");
    exit();
}

include 'db_connection.php';

$email = trim($_POST['email']);

// Check if email exists in the database
$sql = "SELECT id FROM users WHERE email = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    header("Location: forgot-password.php?error=no_user");
    exit();
} else {
    // Generate temporary password
    $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*';
    $temp_password = substr(str_shuffle($chars), 0, 12);

    // Hash the temporary password
    $hashed_password = password_hash($temp_password, PASSWORD_BCRYPT);

    // Update the user's password in the database
    $update_sql = "UPDATE users SET password = ? WHERE email = ?";
    $stmt = $conn->prepare($update_sql);
    $stmt->bind_param("ss", $hashed_password, $email);
    $stmt->execute();

    // Send email with temporary password
    $to = $email;
    $subject = 'Password Reset';
    $message = "Your temporary password is: $temp_password
Please change it upon login.";
    $headers = 'From: yoursite@example.com';

    mail($to, $subject, $message, $headers);

    header("Location: forgot-password.php?success=reset");
}

$conn->close();
?>



<?php
$host = 'localhost';
$user = 'root';
$password = '';
$db_name = 'your_database';

$conn = new mysqli($host, $user, $password, $db_name);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>


<?php
// Include necessary files
require_once 'database_connection.php';
require_once 'email_sender.php';

function forgotPassword($email) {
    // Check if email exists in database
    $query = "SELECT id, first_name FROM users WHERE email = ?";
    
    try {
        $stmt = $conn->prepare($query);
        $stmt->execute([$email]);
        
        if ($stmt->rowCount() == 1) {
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            
            // Generate a random token
            $token = bin2hex(random_bytes(32));
            
            // Set the expiration time (e.g., 1 hour from now)
            $expires = date('Y-m-d H:i:s', strtotime('+1 hour'));
            
            // Update the database with the new token and expiration time
            $updateQuery = "UPDATE users SET reset_token = ?, reset_expires = ? WHERE email = ?";
            $updateStmt = $conn->prepare($updateQuery);
            $updateStmt->execute([$token, $expires, $email]);
            
            // Send the password reset link to the user's email
            $resetLink = "http://yourwebsite.com/reset-password.php?token=" . $token;
            $subject = "Password Reset Request";
            $body = "Dear " . $user['first_name'] . ",

You have requested a password reset. Please click on the following link to reset your password:

" . $resetLink . "

This link will expire in 1 hour.

Best regards,
The Team";
            
            sendEmail($email, $subject, $body);
            
            return "Password reset instructions have been sent to your email address.";
        } else {
            return "Email not found in our database. Please check your email and try again.";
        }
    } catch(PDOException $e) {
        return "An error occurred: " . $e->getMessage();
    }
}

// Example usage:
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['email'])) {
        $email = $_POST['email'];
        echo forgotPassword($email);
    }
}
?>


<?php
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    if (isset($_GET['token'])) {
        $token = $_GET['token'];
        
        // Verify token in database and check expiration time
        // If valid, show password reset form
    } else {
        // Invalid or missing token
        die("Invalid request");
    }
} elseif ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['token'], $_POST['password'])) {
        $token = $_POST['token'];
        $newPassword = password_hash($_POST['password'], PASSWORD_DEFAULT);
        
        // Update the user's password in the database
        // Clear or update the reset token and expiration time
        
        // Redirect to login page
    } else {
        die("Invalid request");
    }
}
?>


<?php
session_start();

// Database connection
$host = 'localhost';
$dbUsername = 'root';
$dbPassword = '';
$dbName = 'test';

$conn = new mysqli($host, $dbUsername, $dbPassword, $dbName);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Function to generate random token
function generateToken() {
    return bin2hex(random_bytes(16));
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if email or username is provided
    if (empty($_POST['email']) && empty($_POST['username'])) {
        $error = "Please enter your email or username.";
    } else {
        $email = $_POST['email'];
        $username = $_POST['username'];

        // Prepare and bind
        $stmt = $conn->prepare("SELECT id, email, username FROM users WHERE email=? OR username=?");
        $stmt->bind_param("ss", $email, $username);
        
        if ($stmt->execute()) {
            $result = $stmt->get_result();
            
            if ($result->num_rows > 0) {
                // User exists
                $user = $result->fetch_assoc();
                
                // Generate reset token and expiration time
                $token = generateToken();
                $expirationTime = date('Y-m-d H:i:s', strtotime('+1 hour'));
                
                // Hash the token before storing it in database
                $hashedToken = password_hash($token, PASSWORD_DEFAULT);
                
                // Insert into reset_tokens table
                $resetStmt = $conn->prepare("INSERT INTO reset_tokens (user_id, token, expires) VALUES (?, ?, ?)");
                $resetStmt->bind_param("iss", $user['id'], $hashedToken, $expirationTime);
                
                if ($resetStmt->execute()) {
                    // Send email with reset link
                    $to = $email ?: $username;
                    $subject = "Password Reset Request";
                    $message = "Please click the following link to reset your password:

http://yourdomain.com/reset-password.php?token=$token

This link will expire in 1 hour.";
                    
                    mail($to, $subject, $message);
                    
                    echo "An email has been sent with instructions to reset your password.";
                } else {
                    die("Error storing reset token: " . $conn->error);
                }
            } else {
                $error = "No account found with that email or username.";
            }
        } else {
            die("Database error: " . $conn->error);
        }
    }
}

// Close database connection
$conn->close();
?>


<?php
// Database configuration
$host = 'localhost';
$username_db = 'your_username';
$password_db = 'your_password';
$db_name = 'your_database';

// Create connection
$conn = new mysqli($host, $username_db, $password_db, $db_name);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Function to generate a random password
function generatePassword($length = 10) {
    $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*()';
    $password = substr(str_shuffle($chars), 0, $length);
    return $password;
}

// Function to send email with new password
function sendNewPassword($email, $newPassword) {
    $to = $email;
    $subject = 'Your New Password';
    $message = "Hello,

Your new password is: " . $newPassword . "

Please login and change your password immediately.
";
    
    // Additional headers
    $headers = 'From: webmaster@example.com' . "\r
" .
        'Reply-To: webmaster@example.com' . "\r
" .
        'X-Mailer: PHP/' . phpversion();

    mail($to, $subject, $message, $headers);
}

// Process the form
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    
    // Sanitize input
    $email = mysqli_real_escape_string($conn, $trim(email));
    
    // Check if email exists in database
    $sql = "SELECT * FROM users WHERE email = '$email'";
    $result = $conn->query($sql);
    
    if ($result->num_rows > 0) {
        // Generate new password
        $newPassword = generatePassword();
        
        // Update database with new password
        $hash = password_hash($newPassword, PASSWORD_DEFAULT);
        $updateSql = "UPDATE users SET password = '$hash' WHERE email = '$email'";
        if ($conn->query($updateSql)) {
            // Send email
            sendNewPassword($email, $newPassword);
            
            // Redirect to login page with success message
            header("Location: login.php?msg=Password reset successful. Check your email.");
            exit();
        } else {
            echo "Error updating password: " . $conn->error;
        }
    } else {
        // Email not found
        header("Location: forgot_password.php?error=Email not found in our records.");
        exit();
    }
}
?>

<!-- Forgot Password Form -->
<!DOCTYPE html>
<html>
<head>
    <title>Forgot Password</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #f4f4f4;
        }
        .container {
            max-width: 500px;
            margin: 0 auto;
            background-color: white;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .form-group {
            margin-bottom: 15px;
        }
        input[type="text"] {
            width: 100%;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-sizing: border-box;
        }
        button {
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            width: 100%;
        }
        button:hover {
            background-color: #45a049;
        }
        .message {
            margin-top: 10px;
            color: red;
        }
    </style>
</head>
<body>
    <div class="container">
        <?php
        // Show error message if set in URL parameters
        if (isset($_GET['error'])) {
            echo "<div class='message'>" . $_GET['error'] . "</div>";
        }
        ?>
        
        <h2>Forgot Password</h2>
        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
            <div class="form-group">
                <label for="email">Email or Username:</label><br>
                <input type="text" id="email" name="email" required>
            </div>
            <button type="submit">Reset Password</button>
        </form>
        <p>Don't have an account? <a href="register.php">Register here</a>.</p>
    </div>
</body>
</html>

// Close database connection
$conn->close();
?>


<?php
// index.php - Forgot Password Page

session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];

    // Validate email input
    if (empty($email)) {
        $error = "Please enter your email address.";
    } else {
        // Connect to database
        include('db_connect.php');

        // Prepare SQL statement to check for existing user
        $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows == 0) {
            // No user found with this email
            $error = "No account exists with this email address.";
        } else {
            // Generate a random token for password reset
            $token = bin2hex(random_bytes(16));
            $expires = date('Y-m-d H:i:s', time() + 3600); // Token expires in 1 hour

            // Update user's record with the new token and expiration time
            $updateStmt = $conn->prepare("UPDATE users SET reset_token = ?, reset_expires = ? WHERE email = ?");
            $updateStmt->bind_param("sss", $token, $expires, $email);
            $updateStmt->execute();

            // Send password reset email
            $resetLink = "http://your-website.com/reset_password.php?token=" . $token;
            $to = $email;
            $subject = "Password Reset Request";
            $message = "Please click the following link to reset your password: 
" . $resetLink;
            
            if (mail($to, $subject, $message)) {
                echo "A password reset email has been sent to you. Please check your inbox.";
                header("Refresh:2; url=login.php");
                exit();
            } else {
                $error = "There was an error sending the password reset email.";
            }
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Forgot Password</title>
</head>
<body>
    <?php if (!empty($error)) { echo "<p>$error</p>"; } ?>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        Email: <input type="text" name="email"><br>
        <input type="submit" value="Submit">
    </form>
</body>
</html>

// reset_password.php - Password Reset Page

session_start();

if ($_GET['token']) {
    $token = $_GET['token'];
    
    // Connect to database
    include('db_connect.php');

    // Check if token exists and is valid
    $stmt = $conn->prepare("SELECT id, email FROM users WHERE reset_token = ? AND reset_expires > NOW()");
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 0) {
        // Invalid or expired token
        die("Invalid or expired password reset link. Please request a new one.");
    } else {
        // Show password reset form
        while ($row = $result->fetch_assoc()) {
            $email = $row['email'];
        }
        
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $new_password = $_POST['password'];
            $confirm_password = $_POST['cpassword'];

            // Validate input
            if (empty($new_password) || empty($confirm_password)) {
                $error = "Please fill in all fields.";
            } elseif ($new_password !== $confirm_password) {
                $error = "Passwords do not match.";
            } else {
                // Update user's password
                $hash = password_hash($new_password, PASSWORD_DEFAULT);
                
                $updateStmt = $conn->prepare("UPDATE users SET password = ?, reset_token = NULL, reset_expires = NULL WHERE email = ?");
                $updateStmt->bind_param("ss", $hash, $email);
                $updateStmt->execute();

                // Show success message
                echo "Your password has been updated. <a href='login.php'>Click here to login</a>";
                header("Refresh:2; url=login.php");
                exit();
            }
        }
    }
} else {
    die("No token provided.");
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Reset Password</title>
</head>
<body>
    <?php if (!empty($error)) { echo "<p>$error</p>"; } ?>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>?token=<?php echo $token; ?>" method="post">
        New Password: <input type="password" name="password"><br>
        Confirm Password: <input type="password" name="cpassword"><br>
        <input type="submit" value="Reset Password">
    </form>
</body>
</html>

// db_connect.php - Database Connection

<?php
$servername = "localhost";
$username = "username";
$password = "password";
$dbname = "myDB";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>


<?php
include('db_connection.php'); // Include your database connection

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    
    // Validate email
    if (empty($email)) {
        header("Location: forgot-password-form.php?status=Please enter your email address");
        exit();
    }
    
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        header("Location: forgot-password-form.php?status=Invalid email format");
        exit();
    }
    
    // Check if email exists in the database
    $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows == 0) {
        header("Location: forgot-password-form.php?status=Email not found");
        exit();
    }
    
    // Generate a random token
    $token = bin2hex(openssl_random_pseudo_bytes(16));
    $expires = date('Y-m-d H:i:s', time() + 3600); // Token expires after 1 hour
    
    // Update the database with the token and expiration time
    $updateStmt = $conn->prepare("UPDATE users SET reset_token = ?, reset_expires = ? WHERE email = ?");
    $updateStmt->bind_param("sss", $token, $expires, $email);
    
    if (!$updateStmt->execute()) {
        header("Location: forgot-password-form.php?status=Error resetting password");
        exit();
    }
    
    // Send the password reset link to the user's email
    $to = $email;
    $subject = "Password Reset Request";
    $message = "Please click the following link to reset your password:

";
    $message .= "http://your-website.com/reset-password.php?token=" . $token . "&email=" . urlencode($email);
    $headers = "From: your-website@example.com\r
";
    
    if (mail($to, $subject, $message, $headers)) {
        header("Location: forgot-password-form.php?status=Password reset link sent to your email");
    } else {
        header("Location: forgot-password-form.php?status=Error sending email");
    }
}
?>


<?php
include('db_connection.php'); // Include your database connection

if (isset($_GET['token']) && isset($_GET['email'])) {
    $token = $_GET['token'];
    $email = $_GET['email'];
    
    // Check if token exists and is still valid
    $stmt = $conn->prepare("SELECT id FROM users WHERE reset_token = ? AND email = ? AND reset_expires > NOW()");
    $stmt->bind_param("ss", $token, $email);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows == 0) {
        header("Location: forgot-password-form.php?status=Invalid or expired token");
        exit();
    }
    
    // Show password reset form
    ?>
    <html>
    <head>
        <title>Reset Password</title>
    </head>
    <body>
        <h2>Reset Password</h2>
        <form action="reset-password.php?token=<?php echo $token; ?>&email=<?php echo urlencode($email); ?>" method="post">
            New Password: <input type="password" name="new_password" required><br>
            Confirm Password: <input type="password" name="confirm_password" required><br>
            <button type="submit">Reset Password</button>
        </form>
    </body>
    </html>
    <?php
} else {
    header("Location: forgot-password-form.php?status=Invalid request");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $token = $_GET['token'];
    $email = $_GET['email'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];
    
    if ($new_password != $confirm_password) {
        die("Passwords do not match");
    }
    
    // Update the password
    $stmt = $conn->prepare("UPDATE users SET password = ?, reset_token = NULL, reset_expires = NULL WHERE email = ?");
    $stmt->bind_param("ss", password_hash($new_password, PASSWORD_DEFAULT), $email);
    
    if (!$stmt->execute()) {
        die("Error resetting password");
    }
    
    header("Location: login.php?status=Password reset successfully");
}
?>

