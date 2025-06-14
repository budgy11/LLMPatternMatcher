
<?php

// Configuration
define('SECRET_KEY', 'your_secret_key_here');
define('PASSWORD_RESET_EXPIRES_IN_HOURS', 1);

// Function to send password reset email
function send_password_reset_email($user_id, $token) {
  // Get user details
  $user = get_user_by_id($user_id);
  
  if (!$user) {
    return false;
  }
  
  // Set email content
  $subject = 'Reset your password';
  $message = '
    <p>Dear ' . $user->username . ',</p>
    <p>We received a request to reset your password. To do so, click on the link below:</p>
    <p><a href="' . $_SERVER['HTTP_HOST'] . '/reset-password?token=' . $token . '">Reset Password</a></p>
  ';
  
  // Send email
  mail($user->email, $subject, $message);
  
  return true;
}

// Function to reset password
function reset_password($token) {
  // Validate token
  $password_reset = get_password_reset_by_token($token);
  
  if (!$password_reset || $password_reset->expires_at < time()) {
    return false;
  }
  
  // Get user ID from token
  $user_id = $password_reset->user_id;
  
  // Set new password (for example, a random one)
  $new_password = bin2hex(random_bytes(32));
  
  // Update user's password
  update_user_password($user_id, $new_password);
  
  return true;
}

// Function to get user by ID
function get_user_by_id($id) {
  global $conn;
  
  $query = 'SELECT * FROM users WHERE id = :id';
  $stmt = $conn->prepare($query);
  $stmt->bindParam(':id', $id);
  $stmt->execute();
  
  return $stmt->fetchObject();
}

// Function to get password reset by token
function get_password_reset_by_token($token) {
  global $conn;
  
  $query = 'SELECT * FROM password_resets WHERE token = :token AND expires_at > :expires_at';
  $stmt = $conn->prepare($query);
  $stmt->bindParam(':token', $token);
  $stmt->bindParam(':expires_at', time());
  $stmt->execute();
  
  return $stmt->fetchObject();
}

// Function to update user's password
function update_user_password($user_id, $new_password) {
  global $conn;
  
  $query = 'UPDATE users SET password_hash = :password_hash WHERE id = :id';
  $stmt = $conn->prepare($query);
  $stmt->bindParam(':id', $user_id);
  $stmt->bindParam(':password_hash', hash('sha256', $new_password));
  $stmt->execute();
}

// Handle form submission
if (isset($_POST['forgot-password'])) {
  // Get user's email
  $email = trim($_POST['email']);
  
  // Check if email is valid
  if (!preg_match('/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/', $email)) {
    die('Invalid email address');
  }
  
  // Get user ID from database
  $query = 'SELECT id FROM users WHERE email = :email';
  $stmt = $conn->prepare($query);
  $stmt->bindParam(':email', $email);
  $stmt->execute();
  
  if ($result = $stmt->fetchObject()) {
    $user_id = $result->id;
    
    // Generate password reset token
    $token = bin2hex(random_bytes(32));
    
    // Insert new password reset record into database
    $query = 'INSERT INTO password_resets (user_id, token) VALUES (:user_id, :token)';
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':user_id', $user_id);
    $stmt->bindParam(':token', $token);
    $stmt->execute();
    
    // Send email with reset link
    send_password_reset_email($user_id, $token);
  } else {
    die('Email address not found');
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
  
  <?php if (isset($_POST['forgot-password'])) : ?>
    <p>Email sent with password reset link.</p>
  <?php endif; ?>
  
  <form method="post">
    <label for="email">Email:</label>
    <input type="text" id="email" name="email" placeholder="example@example.com">
    <button type="submit" name="forgot-password">Send Password Reset Link</button>
  </form>
</body>
</html>


<?php

// Configuration
$dbHost = 'localhost';
$dbUsername = 'your_username';
$dbPassword = 'your_password';
$dbName = 'your_database';

// Connect to database
$conn = new mysqli($dbHost, $dbUsername, $dbPassword, $dbName);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Function to generate a random token
function generateToken() {
    return bin2hex(random_bytes(16));
}

// Function to reset password
function resetPassword($token, $email, $newPassword) {
    // Query to update user's reset token and expires
    $query = "UPDATE users SET reset_token = ?, reset_expires = NOW() + INTERVAL 30 MINUTE WHERE email = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ss", $token, $email);

    if ($stmt->execute()) {
        // Hash the new password
        $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
        // Query to update user's password hash
        $query = "UPDATE users SET password_hash = ? WHERE email = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("ss", $hashedPassword, $email);

        if ($stmt->execute()) {
            return true;
        } else {
            // Error updating user's password hash
            echo "Error updating user's password hash: " . $conn->error;
        }
    } else {
        // Error resetting token and expires
        echo "Error resetting token and expires: " . $conn->error;
    }

    return false;
}

// Function to send reset link via email
function sendResetLink($email) {
    // Generate a random token
    $token = generateToken();

    // Query to select user's id, email, and current reset token (if any)
    $query = "SELECT id, email, reset_token FROM users WHERE email = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $email);

    if ($stmt->execute()) {
        $result = $stmt->get_result();

        // If user exists and doesn't have a current reset token
        if ($row = $result->fetch_assoc() && !$row['reset_token']) {
            // Update user's reset token and expires
            $query = "UPDATE users SET reset_token = ?, reset_expires = NOW() + INTERVAL 30 MINUTE WHERE email = ?";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("ss", $token, $email);

            if ($stmt->execute()) {
                // Send email with reset link
                $subject = "Reset your password";
                $body = "Click this link to reset your password: <a href='http://example.com/reset-password.php?token=" . $token . "'>Reset Password</a>";
                $from = 'your_email@example.com';
                $to = $email;

                mail($to, $subject, $body, "From: $from\r
");

                return true;
            } else {
                // Error updating user's reset token and expires
                echo "Error updating user's reset token and expires: " . $conn->error;
            }
        } else {
            // User doesn't exist or already has a current reset token
            echo "User doesn't exist or already has a current reset token";
        }
    } else {
        // Error selecting user's id, email, and current reset token
        echo "Error selecting user's id, email, and current reset token: " . $conn->error;
    }

    return false;
}

// Handle forgotten password request
if (isset($_POST['email'])) {
    $email = $_POST['email'];
    sendResetLink($email);
} else if (isset($_GET['token'])) {
    // Validate token and check expiration time
    $token = $_GET['token'];

    // Query to select user's id, email, and current reset token (if any)
    $query = "SELECT id, email, reset_token FROM users WHERE reset_token = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $token);

    if ($stmt->execute()) {
        $result = $stmt->get_result();

        // If user exists and has a current reset token
        if ($row = $result->fetch_assoc()) {
            // Display password reset form
            echo "<h1>Reset Password</h1>";
            echo "<form action='reset-password.php' method='post'>";
            echo "<label for='newPassword'>New Password:</label><br>";
            echo "<input type='password' id='newPassword' name='newPassword'><br>";
            echo "<label for='confirmPassword'>Confirm New Password:</label><br>";
            echo "<input type='password' id='confirmPassword' name='confirmPassword'><br>";
            echo "<button type='submit' class='btn btn-primary'>Reset Password</button>";
            echo "</form>";

            // If form submitted
            if (isset($_POST['newPassword'])) {
                $newPassword = $_POST['newPassword'];
                $confirmPassword = $_POST['confirmPassword'];

                if ($newPassword === $confirmPassword) {
                    // Reset password using token and new password
                    resetPassword($token, $row['email'], $newPassword);
                } else {
                    echo "Passwords do not match";
                }
            }
        } else {
            echo "Invalid or expired token";
        }
    } else {
        // Error selecting user's id, email, and current reset token
        echo "Error selecting user's id, email, and current reset token: " . $conn->error;
    }
} else {
    // Display forgotten password form
    echo "<h1>Forgot Password</h1>";
    echo "<form action='forgot-password.php' method='post'>";
    echo "<label for='email'>Email:</label><br>";
    echo "<input type='text' id='email' name='email'><br>";
    echo "<button type='submit' class='btn btn-primary'>Send Reset Link</button>";
    echo "</form>";
}

// Close database connection
$conn->close();

?>


<?php

// Configuration
$databaseHost = 'localhost';
$databaseName = 'your_database_name';
$username = 'your_username';
$password = 'your_password';

// Connect to database
$conn = mysqli_connect($databaseHost, $username, $password, $databaseName);

if (!$conn) {
  die("Connection failed: " . mysqli_connect_error());
}

// Check if form has been submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

  // Get email from form
  $email = $_POST['email'];

  // Validate email
  if (empty($email)) {
    echo 'Please enter your email address.';
    exit;
  }

  // Query database to retrieve user ID
  $query = "SELECT id FROM users WHERE email = '$email'";
  $result = mysqli_query($conn, $query);

  if (mysqli_num_rows($result) > 0) {

    // Retrieve user ID and generate new password
    $row = mysqli_fetch_assoc($result);
    $userId = $row['id'];
    $newPassword = bin2hex(random_bytes(16));

    // Update password in database
    $query = "UPDATE users SET password_hash = '$newPassword' WHERE id = '$userId'";
    mysqli_query($conn, $query);

    // Send email with new password to user
    sendEmail($email, $newPassword);

  } else {
    echo 'Email not found.';
  }

} else {

  // Display form
?>

<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
  <input type="text" name="email" placeholder="Enter your email address">
  <button type="submit">Send New Password</button>
</form>

<?php

}

// Function to send email with new password
function sendEmail($to, $newPassword) {
  $subject = 'New Password';
  $body = "Your new password is: $newPassword";
  mail($to, $subject, $body);
}


<form action="" method="post">
    <input type="email" name="email" placeholder="Enter your email address">
    <button type="submit">Send Reset Link</button>
</form>


<?php

// Define the email configuration
$smtpHost = 'your-smtp-host';
$smtpPort = 587;
$fromEmail = 'your-email@example.com';
$fromName = 'Your Name';

// Check if the form has been submitted
if (isset($_POST['email'])) {

    // Connect to the database (assuming you're using a MySQL database)
    $conn = new mysqli('localhost', 'username', 'password', 'database');

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Get the user's email address from the form submission
    $email = $_POST['email'];

    // Query to check if the email address is valid
    $query = "SELECT * FROM users WHERE email = '$email'";

    // Execute the query
    $result = $conn->query($query);

    // Check if the email address is valid
    if ($result->num_rows > 0) {

        // Get the user's ID from the database
        $userId = $result->fetch_assoc()['id'];

        // Generate a password reset token
        $token = bin2hex(random_bytes(32));

        // Insert the password reset token into the database
        $query = "UPDATE users SET password_reset_token = '$token' WHERE id = '$userId'";
        $conn->query($query);

        // Send an email with a password reset link
        sendEmail($email, $token);
    }

    // Close the connection
    $conn->close();
}

// Function to send an email with a password reset link
function sendEmail($email, $token) {
    $headers = 'From: ' . $fromName . ' <' . $fromEmail . '>' . "\r
";
    $headers .= 'Content-Type: text/html; charset=UTF-8' . "\r
";

    $message = '<p>Hello!</p>';
    $message .= '<p>Click this link to reset your password:</p>';
    $message .= '<p><a href="' . $_SERVER['HTTP_HOST'] . '/reset-password.php?token=' . $token . '">Reset Password</a></p>';

    mail($email, 'Password Reset', $message, $headers);
}

?>


<?php

// Check if the form has been submitted
if (isset($_POST['new_password'])) {

    // Connect to the database (assuming you're using a MySQL database)
    $conn = new mysqli('localhost', 'username', 'password', 'database');

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Get the token from the URL parameter
    $token = $_GET['token'];

    // Query to check if the token is valid
    $query = "SELECT * FROM users WHERE password_reset_token = '$token'";

    // Execute the query
    $result = $conn->query($query);

    // Check if the token is valid
    if ($result->num_rows > 0) {

        // Get the user's ID from the database
        $userId = $result->fetch_assoc()['id'];

        // Insert the new password into the database
        $newPassword = $_POST['new_password'];
        $query = "UPDATE users SET password = '$newPassword' WHERE id = '$userId'";
        $conn->query($query);

        // Delete the token from the database
        $query = "DELETE FROM users WHERE password_reset_token = '$token'";
        $conn->query($query);
    }

    // Close the connection
    $conn->close();
}

?>


<?php

// Configuration
define('DB_HOST', 'localhost');
define('DB_USERNAME', 'your_username');
define('DB_PASSWORD', 'your_password');
define('DB_NAME', 'your_database');

// Connect to database
$conn = new mysqli(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

function forgotPassword($email)
{
    global $conn;

    // Query to retrieve user by email
    $query = "SELECT * FROM users WHERE email = '$email'";
    $result = $conn->query($query);

    if ($result->num_rows > 0) {
        // User found, generate new password and send reset link via email

        // Generate random password
        $newPassword = substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyz"), 0, 8);

        // Update user's password in database
        $query = "UPDATE users SET password = '$newPassword' WHERE email = '$email'";
        $conn->query($query);

        // Send reset link via email (example using PHPMailer)
        require_once 'PHPMailer/PHPMailerAutoload.php';

        $mail = new PHPMailer();

        $mail->isSMTP();
        $mail->Host       = 'smtp.example.com';
        $mail->SMTPAuth    = true;
        $mail->Username    = 'your_email@example.com';
        $mail->Password    = 'your_password';
        $mail->Port        = 587;

        $mail->setFrom('your_email@example.com', 'Your Name');
        $mail->addAddress($email);
        $mail->Subject = 'Reset Password';

        // Send email with reset link
        $resetLink = "http://example.com/reset-password.php?email=$email&newPassword=$newPassword";
        $body = "<p>Please click on the following link to reset your password:</p><p>$resetLink</p>";
        $mail->Body    = $body;

        if (!$mail->send()) {
            echo 'Message could not be sent.';
            echo 'Mailer Error: ' . $mail->ErrorInfo;
        } else {
            echo 'Email sent successfully!';
        }

        return true; // Reset link sent
    }

    return false; // User not found
}

// Example usage:
$email = "example@example.com";
if (forgotPassword($email)) {
    echo "Reset link sent to $email.";
} else {
    echo "User not found with email $email.";
}
?>


<?php

// Configuration settings
define('SITE_URL', 'http://example.com');
define('ADMIN_EMAIL', 'admin@example.com');

// Database connection settings
$db_host = 'localhost';
$db_username = 'your_username';
$db_password = 'your_password';
$db_name = 'your_database';

// Connect to database
$conn = new mysqli($db_host, $db_username, $db_password, $db_name);

if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

// Process forgot password request
if (isset($_POST['forgot_password'])) {
  $email = $_POST['email'];
  
  // Check if email exists in database
  $query = "SELECT * FROM users WHERE email = '$email'";
  $result = $conn->query($query);

  if ($result->num_rows > 0) {
    // Generate reset token and store it in user's record
    $token = bin2hex(random_bytes(32));
    $update_query = "UPDATE users SET password_reset_token = '$token' WHERE email = '$email'";
    $conn->query($update_query);

    // Send email with reset link
    sendEmail($email, $token);
  } else {
    echo "Email not found";
  }
}

// Function to send email with reset link
function sendEmail($email, $token) {
  $headers = 'From: Admin <' . ADMIN_EMAIL . '>' . "\r
";
  $subject = 'Reset Your Password';
  $body = '
    Dear user,

    Click on the following link to reset your password:

    ' . SITE_URL . '/reset-password.php?email=' . $email . '&token=' . $token . '

    Best regards,
    Admin
  ';

  mail($email, $subject, $body, $headers);
}

?>


<?php

// Configuration settings
define('SITE_URL', 'http://example.com');

// Database connection settings
$db_host = 'localhost';
$db_username = 'your_username';
$db_password = 'your_password';
$db_name = 'your_database';

// Connect to database
$conn = new mysqli($db_host, $db_username, $db_password, $db_name);

if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

// Process reset password request
if (isset($_GET['email']) && isset($_GET['token'])) {
  $email = $_GET['email'];
  $token = $_GET['token'];

  // Check if token is valid and user exists in database
  $query = "SELECT * FROM users WHERE email = '$email' AND password_reset_token = '$token'";
  $result = $conn->query($query);

  if ($result->num_rows > 0) {
    // Generate new password and store it in user's record
    $new_password = bin2hex(random_bytes(32));
    $update_query = "UPDATE users SET password = '$new_password' WHERE email = '$email'";
    $conn->query($update_query);

    echo 'Your password has been reset. Please log in with your new password.';
  } else {
    echo 'Invalid token or user not found';
  }
}

?>


<?php
require_once 'config.php'; // database connection settings

// handle form submission (forgot password)
if (isset($_POST['submit'])) {
  $email = $_POST['email'];
  forgot_password($email);
}

?>

<!DOCTYPE html>
<html>
<head>
  <title>Forgot Password</title>
</head>
<body>

<h1>Forgot Password</h1>

<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
  <label>Email:</label>
  <input type="text" name="email" value="<?php echo (isset($_POST['email'])) ? $_POST['email'] : ''; ?>">
  <br><br>
  <button type="submit" name="submit">Submit</button>
</form>

<?php
// forgot password function
function forgot_password($email) {
  global $db; // database connection

  // check if email exists in database
  $query = "SELECT * FROM users WHERE email = '$email'";
  $result = mysqli_query($db, $query);

  if (mysqli_num_rows($result) > 0) {
    // generate new password and send it to user's email
    $new_password = substr(md5(uniqid()), 0, 8); // generate random password
    $update_query = "UPDATE users SET password = '$new_password' WHERE email = '$email'";
    mysqli_query($db, $update_query);

    // send new password via email
    $to = $email;
    $subject = 'Your New Password';
    $message = 'Hello,
                Your new password is: '.$new_password.'
                Please log in with this new password.
                Sincerely,
                [Your Name]';
    mail($to, $subject, $message);

    echo "New password sent to your email.";
  } else {
    echo "Email not found. Please try again.";
  }
}
?>
</body>
</html>


<?php
// database connection settings
$db = mysqli_connect('localhost', 'username', 'password', 'database_name');

// error handling
if (!$db) {
  die("Connection failed: " . mysqli_connect_error());
}
?>


<?php

// Configuration
$db_host = 'localhost';
$db_username = 'your_username';
$db_password = 'your_password';
$db_name = 'your_database';

// Connect to database
$conn = new mysqli($db_host, $db_username, $db_password, $db_name);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Function to send password reset email
function send_reset_email($email, $token)
{
    // Set up SMTP server
    $smtp_server = 'your_smtp_server';
    $smtp_username = 'your_smtp_username';
    $smtp_password = 'your_smtp_password';

    // Send email using PHPMailer library (install via composer)
    require_once './vendor/autoload.php';
    use PHPMailer\PHPMailer;

    $mail = new PHPMailer();
    $mail->isSMTP();
    $mail->Host = $smtp_server;
    $mail->SMTPAuth = true;
    $mail->Username = $smtp_username;
    $mail->Password = $smtp_password;
    $mail->Port = 587;

    // Set email content
    $subject = 'Reset your password';
    $body = '
        Hello,

        To reset your password, click on the following link:
        <a href="' . $token . '">Reset Password</a>

        Best regards,
        Your Website
    ';

    $mail->setFrom('your_email@example.com', 'Your Name');
    $mail->addAddress($email);

    // Send email
    if (!$mail->send()) {
        echo "Mailer Error: " . $mail->ErrorInfo;
    } else {
        echo "Email sent successfully!";
    }
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get user input
    $email = $_POST['email'];

    // Check if email exists in database
    $sql = "SELECT * FROM users WHERE email = '$email'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // Generate password reset token (e.g. a random string)
        $token = bin2hex(random_bytes(32));

        // Update user data with token
        $sql = "UPDATE users SET password_reset_token = '$token' WHERE email = '$email'";
        $conn->query($sql);

        // Send password reset email
        send_reset_email($email, $token);
    } else {
        echo "Email not found in database.";
    }
}

// Close database connection
$conn->close();

?>


<?php

// Database connection settings
$dbHost = 'localhost';
$dbUsername = 'username';
$dbPassword = 'password';
$dbName = 'database';

// Email configuration
$fromEmail = 'your-email@example.com';
$fromName = 'Your Website';

// Set password reset token length
$tokenLength = 64;

// Create a connection to the database
$conn = new mysqli($dbHost, $dbUsername, $dbPassword, $dbName);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if form was submitted
if (isset($_POST['email'])) {

    // Validate email address
    $email = trim($_POST['email']);
    $email = filter_var($email, FILTER_VALIDATE_EMAIL);

    if ($email === false) {
        echo 'Invalid email';
        exit;
    }

    // Check if user exists in database
    $query = "SELECT * FROM users WHERE email = '$email'";
    $result = $conn->query($query);

    if ($result->num_rows > 0) {

        // Generate password reset token
        $token = bin2hex(random_bytes($tokenLength));

        // Update user's token in database
        $query = "UPDATE users SET password_reset_token = '$token' WHERE email = '$email'";
        $conn->query($query);

        // Send email with password reset link
        $subject = 'Reset Your Password';
        $body = "
            <p>Dear $email,</p>
            <p>To reset your password, click the following link:</p>
            <a href='reset_password.php?token=$token'>$token</a>
            <p>This link will only work for 1 hour.</p>
            ";
        $headers = 'From: ' . $fromName . ' <' . $fromEmail . '>' . "\r
" .
            'Reply-To: ' . $fromEmail . "\r
" .
            'X-Mailer: PHP/' . phpversion();

        mail($email, $subject, $body, $headers);

        echo 'A password reset link has been sent to your email. Please check your inbox.';
    } else {
        echo 'User not found';
    }
}

?>


<?php

// Database connection settings
$dbHost = 'localhost';
$dbUsername = 'username';
$dbPassword = 'password';
$dbName = 'database';

// Email configuration
$fromEmail = 'your-email@example.com';
$fromName = 'Your Website';

// Set password reset token length
$tokenLength = 64;

// Create a connection to the database
$conn = new mysqli($dbHost, $dbUsername, $dbPassword, $dbName);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if form was submitted
if (isset($_POST['password'])) {

    // Validate password and confirm password
    $password = trim($_POST['password']);
    $confirmPassword = trim($_POST['confirm_password']);

    if ($password !== $confirmPassword) {
        echo 'Passwords do not match';
        exit;
    }

    // Check if token is valid
    $token = $_GET['token'];
    $query = "SELECT * FROM users WHERE password_reset_token = '$token'";
    $result = $conn->query($query);

    if ($result->num_rows > 0) {

        // Update user's password in database
        $query = "UPDATE users SET password = '$password' WHERE password_reset_token = '$token'";
        $conn->query($query);

        // Delete token from database
        $query = "DELETE FROM users WHERE password_reset_token = '$token'";
        $conn->query($query);

        echo 'Password updated successfully';
    } else {
        echo 'Invalid token';
    }
}

?>


<?php

// Database connection settings
$dsn = 'mysql:host=localhost;dbname=mydatabase';
$username = 'myusername';
$password = 'mypassword';

try {
    // Establish database connection
    $pdo = new PDO($dsn, $username, $password);
} catch (PDOException $e) {
    echo 'Connection failed: ' . $e->getMessage();
    exit;
}

// Function to send password reset email
function sendPasswordResetEmail($email)
{
    // Generate a random token for the user
    $token = bin2hex(random_bytes(32));

    // Insert the token into the database (we'll create a new table later)
    $stmt = $pdo->prepare('INSERT INTO forgot_password_tokens SET email = :email, token = :token');
    $stmt->execute([':email' => $email, ':token' => $token]);

    // Email template for password reset
    $emailTemplate = 'Hello %s,
You have requested a password reset. Click this link to set a new password:
<a href="http://example.com/reset-password.php?token=%s">%s</a>

Best regards,
The Example Team';

    // Send the email using PHPMailer or your preferred method
    $emailBody = sprintf($emailTemplate, $_POST['username'], $token, $token);
    mail($_POST['email'], 'Password Reset', $emailBody);

    echo 'Email sent successfully!';
}

// Handle form submission (forgot password form)
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Check if the user exists in the database
    $stmt = $pdo->prepare('SELECT * FROM users WHERE email = :email');
    $stmt->execute([':email' => $_POST['email']]);
    $user = $stmt->fetch();

    if ($user) {
        // Send password reset email
        sendPasswordResetEmail($_POST['email']);
    } else {
        echo 'User not found!';
    }
} ?>


<?php
// Configuration
$database_host = 'localhost';
$database_username = 'username';
$database_password = 'password';
$database_name = 'database';

// Connect to database
$conn = new mysqli($database_host, $database_username, $database_password, $database_name);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get email from form submission
$email = $_POST['email'];

// Check if email is valid
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo 'Invalid email address';
    exit;
}

// Query to retrieve user ID and password reset token
$stmt = $conn->prepare("SELECT id, password_reset_token FROM users WHERE email = ?");
$stmt->bind_param('s', $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    // User exists, retrieve data
    while ($row = $result->fetch_assoc()) {
        $userId = $row['id'];
        $passwordResetToken = $row['password_reset_token'];

        // Generate a random password and update the database with new password
        $newPassword = substr(str_shuffle('0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ'), 0, 10);
        $stmt = $conn->prepare("UPDATE users SET password = ?, password_reset_token = ? WHERE id = ?");
        $stmt->bind_param('sss', $newPassword, $passwordResetToken, $userId);
        $stmt->execute();

        // Send email with new password and password reset link
        sendEmail($email, $newPassword);

        echo 'New password sent to your email';
    }
} else {
    echo 'User not found';
}

// Close database connection
$conn->close();
?>

<!-- Form to enter email for forgot password -->
<form action="" method="post">
  <input type="text" name="email" placeholder="Enter your email address">
  <button type="submit">Send new password</button>
</form>


<?php

// Configuration (use a mail server or library like PHPMailer)
$fromEmail = 'your-email@example.com';
$fromName = 'Your Name';

// Email content
$message = '
Dear '. $name .',

Your new password is: ' . $newPassword . '

To log in to your account, use the following link:
<a href="' . $passwordResetLink . '">Click here</a>

Best regards,
' . $fromName;

// Send email using mail() function
$headers = "From: " . $fromEmail;
$headers .= "Content-Type: text/html; charset=UTF-8";
mail($to, 'New Password', $message, $headers);

?>


<?php

// Configuration
$db_host = 'localhost';
$db_username = 'your_database_username';
$db_password = 'your_database_password';
$db_name = 'your_database_name';

// Connect to database
$conn = new mysqli($db_host, $db_username, $db_password, $db_name);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

function sendPasswordResetEmail($email, $resetLink)
{
    // Email configuration (replace with your own email settings)
    $to = $email;
    $subject = 'Forgot Password';
    $message = '<p>Please click on the following link to reset your password:</p><p>' . $resetLink . '</p>';
    $headers = "From: no-reply@example.com\r
";
    $headers .= "Content-Type: text/html; charset=ISO-8859-1\r
";

    mail($to, $subject, $message, $headers);
}

function checkPasswordResetToken($token)
{
    // Retrieve user data from database
    $query = "SELECT * FROM users WHERE password_reset_token = '" . mysqli_real_escape_string($conn, $token) . "'";
    $result = $conn->query($query);

    if ($result->num_rows > 0) {
        return true; // Token is valid
    } else {
        return false; // Token is invalid or expired
    }
}

function resetPassword($new_password)
{
    // Retrieve user data from database
    $query = "UPDATE users SET password_hash = '" . mysqli_real_escape_string($conn, password_hash($new_password, PASSWORD_DEFAULT)) . "' WHERE id = '1'"; // Replace with actual user ID
    $result = $conn->query($query);

    if ($result) {
        return true; // Password reset successful
    } else {
        return false; // Password reset failed
    }
}

// Handle forgot password form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];

    // Check if email is registered in database
    $query = "SELECT * FROM users WHERE email = '" . mysqli_real_escape_string($conn, $email) . "'";
    $result = $conn->query($query);

    if ($result->num_rows > 0) {
        // Generate password reset token
        $token = bin2hex(random_bytes(16));

        // Update user data in database with new token
        $query = "UPDATE users SET password_reset_token = '" . mysqli_real_escape_string($conn, $token) . "' WHERE email = '" . mysqli_real_escape_string($conn, $email) . "'";
        $result = $conn->query($query);

        if ($result) {
            // Send email with reset link
            $resetLink = "https://example.com/reset-password/" . $token;
            sendPasswordResetEmail($email, $resetLink);
            echo "An email has been sent to your registered email address with a password reset link.";
        } else {
            echo "Failed to update user data.";
        }
    } else {
        echo "No account found with this email address.";
    }
}

// Close database connection
$conn->close();

?>


<?php

require_once 'db.php'; // assume you have a db.php file that connects to your database

// Set up email settings (update with your own credentials)
$from_email = "your-email@example.com";
$smtp_server = "your-smtp-server";
$smtp_username = "your-smtp-username";
$smtp_password = "your-smtp-password";

// Check if form has been submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get the user's email from the POST data
    $email = $_POST['email'];

    // Query database to find the user with the given email
    $query = "SELECT * FROM users WHERE email = :email";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':email', $email);
    $stmt->execute();
    $user_data = $stmt->fetch();

    // If user exists, generate a random password and send them an email
    if ($user_data) {
        // Generate a random password
        $password = substr(uniqid(mt_rand(), true), 0, 10);

        // Update the user's password in the database (use a secure method to store passwords)
        $query = "UPDATE users SET password_hash = :password_hash WHERE email = :email";
        $stmt = $db->prepare($query);
        $stmt->bindParam(':email', $email);
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $stmt->bindParam(':password_hash', $hashed_password);
        $stmt->execute();

        // Send the user an email with a link to reset their password
        $subject = "Reset your password";
        $message = "Click this link to reset your password: <a href='" . $_SERVER['HTTP_HOST'] . "/reset-password.php?email=" . urlencode($email) . "&password=" . urlencode($password) . "'>Reset Password</a>";
        send_email($from_email, $smtp_server, $smtp_username, $smtp_password, $subject, $message);
    }

    // Display a success message if user was found
    echo "<p>Password reset email sent to your email address.</p>";
}

// Function to send an email using PHPMailer (update with your own credentials)
function send_email($from_email, $smtp_server, $smtp_username, $smtp_password, $subject, $message) {
    require_once 'PHPMailer/PHPMailer.php';
    require_once 'PHPMailer/SMTP.php';

    $mail = new PHPMailer\PHPMailer();
    $mail->isSMTP();
    $mail->Host = $smtp_server;
    $mail->Username = $smtp_username;
    $mail->Password = $smtp_password;
    $mail->Port = 587;
    $mail->SMTPSecure = 'tls';
    $mail->setFrom($from_email);
    $mail->addAddress($from_email);
    $mail->Subject = $subject;
    $mail->Body = $message;
    if (!$mail->send()) {
        echo "Mailer Error: " . $mail->ErrorInfo;
    }
}

?>

<!-- HTML form to submit the user's email -->
<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
    <input type="email" name="email" placeholder="Enter your email address"><br><br>
    <button type="submit">Submit</button>
</form>



function forgot_password($email) {
  // Check if email exists in database
  $query = "SELECT * FROM users WHERE email = '$email'";
  $result = mysqli_query($conn, $query);
  
  if (mysqli_num_rows($result) == 0) {
    // Email not found
    return array('error' => 'Email not found');
  }
  
  // Generate a random reset token
  $reset_token = bin2hex(random_bytes(16));
  
  // Update user's reset token in database
  $query = "UPDATE users SET reset_token = '$reset_token' WHERE email = '$email'";
  mysqli_query($conn, $query);
  
  // Send password reset email
  send_reset_email($email, $reset_token);
  
  return array('success' => 'Email sent with password reset link');
}

function send_reset_email($email, $reset_token) {
  // Set up email headers and body
  $to = $email;
  $subject = "Reset your password";
  $body = "Click this link to reset your password: <a href='http://example.com/reset-password?token=$reset_token'>Reset Password</a>";
  
  // Send email using PHPMailer or a similar library
  $mail = new PHPMailer();
  $mail->isSMTP();
  $mail->Host = 'smtp.example.com';
  $mail->Port = 587;
  $mail->SMTPAuth = true;
  $mail->Username = 'your_email@example.com';
  $mail->Password = 'your_password';
  $mail->setFrom('your_email@example.com', 'Your Name');
  $mail->addAddress($to);
  $mail->Subject = $subject;
  $mail->Body = $body;
  $mail->send();
}


$email = $_POST['email'];
$result = forgot_password($email);

if ($result['error']) {
  echo 'Error: ' . $result['error'];
} else {
  echo 'Email sent with password reset link';
}


CREATE TABLE users (
  id INT PRIMARY KEY,
  email VARCHAR(255) NOT NULL,
  password_hash CHAR(60) NOT NULL,
  reset_token VARCHAR(100) NOT NULL,
  reset_expires TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP + INTERVAL 1 HOUR
);


<?php

// Configuration
$secret_key = 'your-secret-key';

// Check if form has been submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $email = $_POST['email'];

  // Validate email address
  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo "Invalid email address.";
    exit;
  }

  // Query database for user with matching email address
  $db = new mysqli('your-host', 'your-username', 'your-password', 'your-database');
  if ($db->connect_error) {
    die("Connection failed: " . $db->connect_error);
  }
  $query = "SELECT * FROM users WHERE email = '$email'";
  $result = $db->query($query);

  // Check if user exists
  if ($result->num_rows > 0) {
    $user_data = $result->fetch_assoc();

    // Generate a random reset token
    $reset_token = bin2hex(random_bytes(16));

    // Update database with new reset token
    $update_query = "UPDATE users SET reset_token = '$reset_token' WHERE email = '$email'";
    $db->query($update_query);

    // Send password reset email (optional)
    $to_email = $user_data['email'];
    $subject = 'Password Reset';
    $message = "Click here to reset your password: <a href='" . $_SERVER['HTTP_HOST'] . "/reset_password.php?token=" . $reset_token . "'>Reset Password</a>";
    mail($to_email, $subject, $message);

    echo "A password reset email has been sent to your email address.";
  } else {
    echo "Email address not found.";
  }

  // Close database connection
  $db->close();
}

?>


<?php

// Configuration
$secret_key = 'your-secret-key';

// Check if form has been submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $token = $_GET['token'];
  $new_password = $_POST['new_password'];
  $confirm_password = $_POST['confirm_password'];

  // Validate password and confirmation
  if ($new_password !== $confirm_password) {
    echo "Passwords do not match.";
    exit;
  }

  // Query database for user with matching reset token
  $db = new mysqli('your-host', 'your-username', 'your-password', 'your-database');
  if ($db->connect_error) {
    die("Connection failed: " . $db->connect_error);
  }
  $query = "SELECT * FROM users WHERE reset_token = '$token'";
  $result = $db->query($query);

  // Check if user exists
  if ($result->num_rows > 0) {
    $user_data = $result->fetch_assoc();

    // Update database with new password hash
    $update_query = "UPDATE users SET password_hash = '" . password_hash($new_password, PASSWORD_DEFAULT) . "' WHERE email = '$user_data[email]'";
    $db->query($update_query);

    // Delete reset token from database
    $delete_query = "DELETE FROM users WHERE reset_token = '$token'";
    $db->query($delete_query);

    echo "Password has been successfully updated.";
  } else {
    echo "Invalid reset token.";
  }

  // Close database connection
  $db->close();
}

?>


CREATE TABLE users (
  id INT PRIMARY KEY AUTO_INCREMENT,
  email VARCHAR(255) NOT NULL UNIQUE,
  password VARCHAR(255) NOT NULL,
  reset_token VARCHAR(255),
  reset_token_expires TIMESTAMP
);


<?php

// Set up database connection
$conn = mysqli_connect('localhost', 'username', 'password', 'database');

// Check connection
if (!$conn) {
  die("Connection failed: " . mysqli_connect_error());
}

// Define function to send reset token via email
function send_reset_token($email, $token) {
  // Get user's name from database (optional)
  $query = "SELECT name FROM users WHERE email = '$email'";
  $result = mysqli_query($conn, $query);
  if ($result && mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
      $name = $row['name'];
    }
  }

  // Send email with reset token
  $subject = "Reset Password";
  $body = "
    Dear $name,

    Your password reset token is: $token

    Please visit our website to reset your password.

    Best regards,
    [Your Name]
  ";

  mail($email, $subject, $body);
}

// Define function to handle forgot password request
function forgot_password() {
  // Get user input (email)
  $email = $_POST['email'];

  // Check if email exists in database
  $query = "SELECT * FROM users WHERE email = '$email'";
  $result = mysqli_query($conn, $query);
  if ($result && mysqli_num_rows($result) > 0) {
    // Get user's ID from database
    while ($row = mysqli_fetch_assoc($result)) {
      $user_id = $row['id'];
    }

    // Generate random reset token and store it in database
    $token = bin2hex(random_bytes(32));
    $query = "UPDATE users SET reset_token = '$token', reset_token_expires = NOW() + INTERVAL 30 MINUTE WHERE id = '$user_id'";
    mysqli_query($conn, $query);

    // Send email with reset token
    send_reset_token($email, $token);
  } else {
    echo 'Email not found';
  }
}

// Handle form submission (POST)
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  forgot_password();
}

?>


<?php

// Define the database connection parameters
$db_host = 'localhost';
$db_username = 'your_database_username';
$db_password = 'your_database_password';
$db_name = 'your_database_name';

// Create a connection to the database
$conn = new mysqli($db_host, $db_username, $db_password, $db_name);

// Check if the connection was successful
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Define the function to send the password reset email
function send_reset_email($email) {
    // Generate a random password and store it in a session variable
    $password = rand(100000, 999999); // Generate a 6-digit random number
    $_SESSION['password'] = $password;

    // SQL query to update the user's password
    $sql = "UPDATE users SET password = '$password' WHERE email = '$email'";
    if ($conn->query($sql) === TRUE) {
        // Send an email with the new password and a link to reset it
        $to = $email;
        $subject = 'Reset your password';
        $body = '
            Dear user,
            
            Your temporary password is: '.$password.'
            
            Click on this link to reset your password: <a href="reset_password.php?email='.$email.'&token='.uniqid().'">Reset Password</a>
            ';
        mail($to, $subject, $body);

        echo 'Email sent successfully!';
    } else {
        echo 'Error updating user data: ' . $conn->error;
    }
}

// Check if the form has been submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get the email address from the form input
    $email = $_POST['email'];

    // Validate the email address
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo 'Invalid email address';
        return;
    }

    // Call the function to send the password reset email
    send_reset_email($email);
}

// Close the database connection
$conn->close();

?>


<?php

// Define the database connection parameters (same as above)

// Check if the form has been submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get the email address and token from the URL query string
    $email = $_GET['email'];
    $token = $_GET['token'];

    // SQL query to update the user's password (using the session variable)
    $sql = "UPDATE users SET password = '".$_SESSION['password']."' WHERE email = '$email' AND token = '$token'";
    if ($conn->query($sql) === TRUE) {
        echo 'Password reset successfully!';
    } else {
        echo 'Error updating user data: ' . $conn->error;
    }
}

// Close the database connection
$conn->close();

?>


// config.php (database connection settings)
<?php
$host = 'localhost';
$dbname = 'mydatabase';
$username = 'myuser';
$password = 'mypassword';

$conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
?>

// forgot_password.php
<?php
require_once 'config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // get user input
    $email = $_POST['email'];

    // check if email address exists in database
    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user) {
        // generate password reset token
        $token = bin2hex(random_bytes(32));

        // update user's record with password reset token
        $stmt = $conn->prepare("UPDATE users SET password_reset_token = ? WHERE email = ?");
        $stmt->execute([$token, $email]);

        // send password reset link via email
        $subject = 'Reset Your Password';
        $body = '<a href="' . $_SERVER['REQUEST_URI'] . '?token=' . $token . '">Click here to reset your password</a>';
        mail($user['email'], $subject, $body);

        echo "Password reset link sent to your email. Please check your inbox.";
    } else {
        echo "Email address not found in our database.";
    }
}
?>

<!-- form -->
<form action="" method="post">
    <label for="email">Enter your email address:</label>
    <input type="email" id="email" name="email">
    <button type="submit">Send Password Reset Link</button>
</form>


// reset_password.php
<?php
require_once 'config.php';

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    // get token from URL parameter
    $token = $_GET['token'];

    // validate token and check if it matches user's record
    $stmt = $conn->prepare("SELECT * FROM users WHERE password_reset_token = ?");
    $stmt->execute([$token]);
    $user = $stmt->fetch();

    if ($user) {
        // create a new form for password reset
        ?>
        <form action="" method="post">
            <label for="new_password">Enter your new password:</label>
            <input type="password" id="new_password" name="new_password">
            <button type="submit">Reset Password</button>
        </form>

        <?php
    } else {
        echo "Invalid token. Please try again.";
    }
}
?>

<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // get user input (new password)
    $new_password = $_POST['new_password'];

    // update user's record with new password
    $stmt = $conn->prepare("UPDATE users SET password = ? WHERE id = ?");
    $stmt->execute([$new_password, $user['id']]);

    echo "Password reset successfully!";
}
?>


// db.php
<?php
define('DB_HOST', 'localhost');
define('DB_USER', 'username');
define('DB_PASSWORD', 'password');
define('DB_NAME', 'database_name');

function connectToDatabase() {
    $conn = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    return $conn;
}
?>


<?php
require_once 'db.php';

if (isset($_POST['submit'])) {

    // Connect to database
    $conn = connectToDatabase();

    // Sanitize input
    $username = sanitizeInput($_POST['username']);

    // Query for user's email and password
    $query = "SELECT email, password FROM users WHERE username='$username'";
    $result = $conn->query($query);

    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $email = $row['email'];
            $password = $row['password'];

            // Send email to user with password
            sendEmail($username, $email, $password);
            echo "Password has been sent to your registered email address.";
        }
    } else {
        echo "User not found.";
    }

    // Close database connection
    $conn->close();

} else {
?>
<!-- Form for forgot password -->
<form action="" method="post">
    <label>Username:</label>
    <input type="text" name="username"><br><br>
    <input type="submit" name="submit" value="Submit">
</form>
<?php
}

// Function to send email with user's password
function sendEmail($username, $email, $password) {
    // Email subject and body
    $subject = 'Your Password for '.$username.'';
    $body = 'Your current password is: '.$password.'';

    // Send email using PHP mail function (for simplicity)
    if(mail($email, $subject, $body)) {
        echo "Email sent successfully.";
    } else {
        echo "Failed to send email.";
    }
}
?>


// Update db.php with the following code:
function hashPassword($password) {
    return password_hash($password, PASSWORD_DEFAULT);
}

function verifyPassword($input_password, $stored_password) {
    if(password_verify($input_password, $stored_password)) {
        return true;
    } else {
        return false;
    }
}


CREATE TABLE users (
  id INT PRIMARY KEY AUTO_INCREMENT,
  email VARCHAR(255) NOT NULL,
  password_hash VARCHAR(255) NOT NULL
);


<?php

// Configuration
$secret_key = 'your_secret_key_here'; // Keep this secret!
$max_attempts = 5; // Maximum number of attempts before locking account
$lockout_time = 300; // Time in seconds to lock out account (e.g. 5 minutes)

// Validate request
if ($_SERVER['REQUEST_METHOD'] != 'POST') {
    header('Location: index.php');
    exit;
}

// Extract data from form submission
$email = $_POST['email'];

// Check if email exists in database
$query = "SELECT * FROM users WHERE email = '$email'";
$result = mysqli_query($conn, $query);
if (mysqli_num_rows($result) == 0) {
    echo 'Email not found.';
    exit;
}

// Get user ID and password hash
$user_id = mysqli_fetch_assoc($result)['id'];
$password_hash = mysqli_fetch_assoc($result)['password_hash'];

// Check for lockout status
$lockout_timestamp = isset($_SESSION['lockout_timestamp']) ? $_SESSION['lockout_timestamp'] : 0;
if ($lockout_timestamp > time()) {
    echo 'Your account has been locked out. Please try again in 5 minutes.';
    exit;
}

// Generate random password and send email with reset link
$password = substr(str_shuffle('abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789'), 0, 10);
$email_body = "Reset your password: <a href='reset_password.php?token=$password&user_id=$user_id'>Click here</a>";
$subject = 'Password Reset';

// Send email using PHPMailer or similar library
$mail->setFrom('your_email@example.com', 'Your Name');
$mail->addAddress($email);
$mail->Subject = $subject;
$mail->Body = $email_body;
if (!$mail->send()) {
    echo 'Error sending email: ' . $mail->ErrorInfo;
    exit;
}

// Store password in session for later use
$_SESSION['password'] = $password;

echo "A password reset link has been sent to your email. Please click on the link and follow instructions.";

?>


<?php

// Configuration
$secret_key = 'your_secret_key_here'; // Keep this secret!

// Validate request
if ($_SERVER['REQUEST_METHOD'] != 'POST') {
    header('Location: index.php');
    exit;
}

// Extract data from form submission
$token = $_GET['token'];
$user_id = $_GET['user_id'];

// Check if token is valid
if (isset($_SESSION['password']) && $_SESSION['password'] == $token) {
    // User has already submitted new password, redirect to login page
    header('Location: login.php');
    exit;
}

// Generate random password and store it in session for later use
$password = substr(str_shuffle('abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789'), 0, 10);
$_SESSION['password'] = $password;

echo "Enter your new password below. You will be redirected to the login page after submission.";

?>


<?php

// Validate request
if ($_SERVER['REQUEST_METHOD'] != 'POST') {
    header('Location: index.php');
    exit;
}

// Extract data from form submission
$email = $_POST['email'];
$password = $_POST['password'];

// Check if user has already submitted new password
if (isset($_SESSION['password'])) {
    // User has already reset their password, remove session variable and proceed to login
    unset($_SESSION['password']);
}

// Login logic goes here...

?>


<?php

// Include configuration files
require_once 'config.php';

// Check if the form has been submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get user input
    $email = trim($_POST['email']);

    // Validate email address
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        die('Invalid email address');
    }

    // Check if the user exists in the database
    $stmt = $mysqli->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 0) {
        die('No account found with this email address');
    }

    // Generate a reset token
    $reset_token = bin2hex(random_bytes(32));
    $expiry_time = time() + (60 * 5); // 5 minutes

    // Update the user's reset token in the database
    $stmt = $mysqli->prepare("UPDATE users SET reset_token = ?, expiry_time = ? WHERE email = ?");
    $stmt->bind_param("ss", $reset_token, $expiry_time, $email);
    $stmt->execute();

    // Send a password reset email to the user
    send_reset_email($email, $reset_token);

    echo 'A password reset email has been sent to your email address';
} else {
?>
<form action="" method="post">
    <label for="email">Email Address:</label>
    <input type="email" id="email" name="email" required><br><br>
    <button type="submit">Reset Password</button>
</form>
<?php
}


function send_reset_email($email, $reset_token) {
    // Configuration variables
    $from_email = 'your-email@example.com';
    $from_password = 'your-password';

    // Create a message
    $subject = 'Password Reset for Your Account';
    $body = "Click here to reset your password: <a href='http://your-website.com/reset_password.php?email=$email&reset_token=$reset_token'>Reset Password</a>";

    // Send the email using PHPMailer
    require_once 'PHPMailerAutoload.php';

    $mail = new PHPMailer();

    $mail->isSMTP();
    $mail->Host = 'smtp.example.com';
    $mail->Port = 587;
    $mail->Username = $from_email;
    $mail->Password = $from_password;

    $mail->setFrom($from_email, 'Your Name');
    $mail->addAddress($email);

    $mail->Subject = $subject;
    $mail->Body = $body;
    $mail->AltBody = 'This is a plain-text message body';

    if (!$mail->send()) {
        echo 'Error sending email';
    } else {
        // Update the user's reset token in the database
        // (not shown in this example)
    }
}


<?php

// Include configuration files
require_once 'config.php';

// Check if the form has been submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get user input
    $email = trim($_POST['email']);
    $reset_token = trim($_POST['reset_token']);

    // Validate reset token and email address
    $stmt = $mysqli->prepare("SELECT id FROM users WHERE email = ? AND reset_token = ?");
    $stmt->bind_param("ss", $email, $reset_token);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 0) {
        die('Invalid reset token or email address');
    }

    // Get the user's current password hash
    $user_id = $result->fetch_assoc()['id'];
    $current_password_hash = get_password_hash($user_id);

    // Check if the reset token has expired
    if (time() > $stmt->get_result()->fetch_assoc()['expiry_time']) {
        die('Reset token has expired');
    }

    // Get the new password from user input
    $new_password = trim($_POST['new_password']);

    // Hash and store the new password in the database
    update_password_hash($user_id, hash_password($new_password));

    echo 'Password reset successfully';
} else {
?>
<form action="" method="post">
    <label for="email">Email Address:</label>
    <input type="email" id="email" name="email" value="<?php echo $_GET['email'] ?>" required readonly><br><br>
    <label for="reset_token">Reset Token:</label>
    <input type="text" id="reset_token" name="reset_token" value="<?php echo $_GET['reset_token'] ?>" required readonly><br><br>
    <label for="new_password">New Password:</label>
    <input type="password" id="new_password" name="new_password" required><br><br>
    <button type="submit">Reset Password</button>
</form>
<?php
}


function get_password_hash($user_id) {
    $stmt = $mysqli->prepare("SELECT password FROM users WHERE id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    return $stmt->get_result()->fetch_assoc()['password'];
}

function update_password_hash($user_id, $new_password_hash) {
    $stmt = $mysqli->prepare("UPDATE users SET password = ? WHERE id = ?");
    $stmt->bind_param("si", $new_password_hash, $user_id);
    $stmt->execute();
}

function hash_password($password) {
    // Use a strong hashing algorithm like bcrypt or Argon2
    return password_hash($password, PASSWORD_DEFAULT);
}


<?php

// Configuration
$secret_key = 'your_secret_key_here'; // Replace with your secret key

// Function to send password reset email
function send_reset_email($email, $token) {
  $subject = 'Reset Password';
  $body = '<p>Click on the link below to reset your password:</p><a href="' . $_SERVER['HTTP_HOST'] . '/reset-password?token=' . $token . '">Reset Password</a>';
  mail($email, $subject, $body);
}

// Function to handle forgot password request
function forgot_password() {
  if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get form data
    $email = $_POST['email'];

    // Validate email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
      echo 'Invalid email address';
      exit;
    }

    // Retrieve user from database
    $db = new PDO('mysql:host=localhost;dbname=your_database_name', 'your_username', 'your_password');
    $stmt = $db->prepare('SELECT * FROM users WHERE email = :email AND reset_token IS NULL');
    $stmt->execute([':email' => $email]);
    $user = $stmt->fetch();

    if ($user) {
      // Generate random token
      $token = bin2hex(random_bytes(16));

      // Store token in database
      $db->exec('UPDATE users SET reset_token = :token, reset_expires = NOW() + INTERVAL 1 HOUR WHERE email = :email');
      $db->close();

      // Send password reset email
      send_reset_email($email, $token);

      echo 'Reset email sent';
    } else {
      echo 'Email not found';
    }
  } else {
    // Display form
    ?>
    <h1>Forgot Password</h1>
    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
      <label for="email">Email:</label>
      <input type="text" id="email" name="email"><br><br>
      <button type="submit">Submit</button>
    </form>
    <?php
  }
}

forgot_password();

?>


<?php
require_once 'dbconnect.php'; // connect to database

if (isset($_POST['submit'])) {
  $email = $_POST['email'];
  
  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo 'Invalid email address';
    exit;
  }
  
  $sql = "SELECT * FROM users WHERE email = '$email'";
  $result = mysqli_query($conn, $sql);
  
  if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
      $user_id = $row['id'];
      $username = $row['username'];
      
      // generate reset password token
      $token = bin2hex(random_bytes(32));
      $sql = "UPDATE users SET reset_token = '$token' WHERE id = '$user_id'";
      mysqli_query($conn, $sql);
      
      // send email with reset link
      $subject = 'Reset your password';
      $message = "Click on the link below to reset your password:
<a href='http://example.com/reset_password.php?token=$token&email=$email'>Reset Password</a>";
      mail($email, $subject, $message);
      
      echo 'Password reset email sent. Please check your email for further instructions.';
    }
  } else {
    echo 'Email not found in database';
  }
} else {
?>
<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
  <label for="email">Enter your email address:</label>
  <input type="email" id="email" name="email"><br><br>
  <button type="submit" name="submit">Send Reset Email</button>
</form>
<?php } ?>


<?php
require_once 'dbconnect.php'; // connect to database

if (isset($_GET['token']) && isset($_GET['email'])) {
  $token = $_GET['token'];
  $email = $_GET['email'];
  
  $sql = "SELECT * FROM users WHERE email = '$email' AND reset_token = '$token'";
  $result = mysqli_query($conn, $sql);
  
  if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
      $user_id = $row['id'];
      
      // allow user to change password
      ?>
<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
  <label for="new_password">Enter new password:</label>
  <input type="password" id="new_password" name="new_password"><br><br>
  <button type="submit" name="submit">Change Password</button>
</form>
<?php
    }
  } else {
    echo 'Invalid token';
  }
} else {
?>
<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
  <label for="email">Enter your email address:</label>
  <input type="email" id="email" name="email"><br><br>
  <button type="submit" name="submit">Send Reset Email</button>
</form>
<?php } ?>

<?php
if (isset($_POST['submit'])) {
  $new_password = $_POST['new_password'];
  
  if (!preg_match('/^(?=.*[a-zA-Z])(?=.*[0-9]).{8,}$/', $new_password)) {
    echo 'Password must be at least 8 characters long and contain a letter and a number';
    exit;
  }
  
  $sql = "UPDATE users SET password = '$new_password' WHERE id = '$user_id'";
  mysqli_query($conn, $sql);
  
  echo 'Password changed successfully!';
}
?>


<?php

require_once 'database.php'; // assuming you have a database connection class

// Check if the form has been submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $email = $_POST['email'];
  
  // Validate email address
  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    header('Location: forgot_password.php?error=invalid_email');
    exit;
  }
  
  // Query the database to retrieve the user's ID and password hash
  $query = "SELECT id, password_hash FROM users WHERE email = ?";
  $stmt = $db->prepare($query);
  $stmt->execute([$email]);
  $result = $stmt->fetch();
  
  if ($result) {
    // Generate a reset token
    $token = bin2hex(random_bytes(32));
    
    // Update the user's record with the new reset token
    $query = "UPDATE users SET password_reset_token = ? WHERE id = ?";
    $stmt = $db->prepare($query);
    $stmt->execute([$token, $result['id']]);
    
    // Send a password reset email to the user
    send_password_reset_email($email, $token);
    
    header('Location: login.php?success=forgot_password');
    exit;
  } else {
    header('Location: forgot_password.php?error=invalid_email_or_password');
    exit;
  }
}

// Function to generate and send a password reset email
function send_password_reset_email($email, $token) {
  $subject = 'Password Reset';
  $message = "Click the link below to reset your password:
  <a href='reset_password.php?token={$token}'>Reset Password</a>";
  
  mail($email, $subject, $message);
}

// Display the forgot password form
?>

<form action="" method="post">
  <label for="email">Email:</label>
  <input type="email" name="email" id="email" required>
  <button type="submit">Submit</button>
</form>

<?php if (isset($_GET['error'])): ?>
  <p style="color: red;">Error: <?= $_GET['error'] ?></p>
<?php endif; ?>


<?php

class Database {
  private $db;
  
  public function __construct() {
    // Connect to the database
    $this->db = new PDO('mysql:host=localhost;dbname=mydatabase', 'username', 'password');
  }
  
  public function prepare($query) {
    return $this->db->prepare($query);
  }
  
  public function execute($stmt, $params) {
    return $stmt->execute($params);
  }
  
  public function fetch($result) {
    return $result->fetch();
  }
}


<?php

// Configuration settings
$site_name = 'Your Site Name';
$from_email = 'your-email@example.com';
$smtp_password = 'your-smtp-password'; // if using SMTP server, set your password here

// Database connection settings
$db_host = 'localhost';
$db_username = 'your-db-username';
$db_password = 'your-db-password';
$db_name = 'your-db-name';

// Connect to the database
$conn = new mysqli($db_host, $db_username, $db_password, $db_name);

// Check if the user has submitted the forgot password form
if (isset($_POST['submit'])) {

    // Get the email address from the form data
    $email = $_POST['email'];

    // Query to retrieve the user's data
    $query = "SELECT * FROM users WHERE email = '$email'";
    $result = mysqli_query($conn, $query);

    // If a user is found, generate a temporary password and send them an email with the link to reset their password
    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {

            // Generate a random temporary password
            $password = uniqid('', true);
            $hashed_password = md5($password);

            // Query to update the user's hashed password in the database
            $query_update = "UPDATE users SET hashed_password = '$hashed_password' WHERE email = '$email'";
            mysqli_query($conn, $query_update);

            // Send an email with a link to reset their password
            $subject = 'Temporary Password Reset Link';
            $body = '
                <html>
                    <head></head>
                    <body>
                        Hello '.$row['first_name'].',
                        <p>Please click on the following link to reset your password:</p>
                        <a href="reset-password.php?email='.$email.'&hashed_password='.$hashed_password.'">Reset Password</a>
                    </body>
                </html>';

            // If using SMTP server, use this configuration
            if (isset($smtp_password)) {
                $headers = 'From: ' . $from_email . "\r
" .
                            'Reply-To: ' . $email . "\r
" .
                            'X-Mailer: PHP/' . phpversion();
                mail($email, $subject, $body, $headers);
            } else { // If not using SMTP server, use this configuration
                mail($email, $subject, $body);
            }
        }
    }

    // Display a message to let the user know their password has been reset and an email sent with the link to reset it.
    echo 'A temporary password has been generated and emailed to you. Please check your email for further instructions.';
} else {
    ?>
    <form action="" method="post">
        <input type="email" name="email" placeholder="Enter your Email Address">
        <button type="submit" name="submit">Submit</button>
    </form>
    <?php
}
?>


<?php

// Configuration settings
$site_name = 'Your Site Name';
$from_email = 'your-email@example.com';

// Database connection settings
$db_host = 'localhost';
$db_username = 'your-db-username';
$db_password = 'your-db-password';
$db_name = 'your-db-name';

// Connect to the database
$conn = new mysqli($db_host, $db_username, $db_password, $db_name);

// Get the email address and hashed password from the URL parameters
$email = $_GET['email'];
$hashed_password = $_GET['hashed_password'];

// Query to retrieve the user's data
$query = "SELECT * FROM users WHERE email = '$email' AND hashed_password = '$hashed_password'";
$result = mysqli_query($conn, $query);

// If a user is found and their hashed password matches, display a form to let them change their password.
if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        ?>
        <form action="" method="post">
            <input type="hidden" name="email" value="<?php echo $email; ?>">
            <input type="password" name="new_password" placeholder="Enter New Password">
            <button type="submit" name="change_password">Change Password</button>
        </form>
        <?php
    }
} else {
    // Display an error message if the user does not exist or their hashed password does not match.
    echo 'Invalid email address or temporary password.';
}

// If the form has been submitted, update the user's hashed password in the database with their new password.
if (isset($_POST['change_password'])) {

    // Get the email address and new password from the form data
    $email = $_POST['email'];
    $new_password = md5($_POST['new_password']);

    // Query to update the user's hashed password in the database
    $query_update = "UPDATE users SET hashed_password = '$new_password' WHERE email = '$email'";
    mysqli_query($conn, $query_update);

    // Display a success message and let them log in with their new password.
    echo 'Password changed successfully. You can now log in with your new password.';
}
?>


// config.php (assuming you have a database connection configuration file)
require 'config.php';

// functions.php (assuming you have a custom functions file)
function send_reset_email($email, $token) {
  // Send an email with the reset link
  $to = $email;
  $subject = "Reset Your Password";
  $body = "Click here to reset your password: <a href='http://example.com/reset-password?token=" . $token . "'>Reset Password</a>";
  mail($to, $subject, $body);
}

function forgot_password() {
  // Get the email from the form
  $email = $_POST['email'];

  // Check if the email exists in the database
  $query = "SELECT * FROM users WHERE email = :email";
  $stmt = $pdo->prepare($query);
  $stmt->bindParam(':email', $email);
  $stmt->execute();
  $user = $stmt->fetch();

  if ($user) {
    // Generate a random token
    $token = bin2hex(random_bytes(16));

    // Update the user's reset token in the database
    $query = "UPDATE users SET reset_token = :token WHERE email = :email";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':token', $token);
    $stmt->bindParam(':email', $email);
    $stmt->execute();

    // Send a reset email to the user
    send_reset_email($email, $token);

    // Display a success message to the user
    echo "Password reset link sent to your email.";
  } else {
    // Display an error message if the email is not found
    echo "Email not found in our records.";
  }
}

// Handle form submission
if (isset($_POST['submit'])) {
  forgot_password();
}


function hash_password($password) {
    return password_hash($password, PASSWORD_DEFAULT);
}

function verify_password($hashed_password, $password) {
    return password_verify($password, $hashed_password);
}


function forgot_password($email) {
    // Check if user exists in database
    $query = "SELECT * FROM users WHERE email = '$email'";
    $result = mysqli_query($conn, $query);
    if (mysqli_num_rows($result) == 0) {
        echo "User not found.";
        return;
    }

    // Generate a random token
    $token = bin2hex(random_bytes(32));

    // Update user's password reset token in database
    $query = "UPDATE users SET password_reset_token = '$token' WHERE email = '$email'";
    mysqli_query($conn, $query);

    // Send password reset link to user via email
    $subject = "Reset Password";
    $message = "Click here to reset your password: <a href='https://example.com/reset-password/$token'>Reset Password</a>";
    mail($email, $subject, $message);
}


function reset_password($token) {
    // Check if token is valid
    $query = "SELECT * FROM users WHERE password_reset_token = '$token'";
    $result = mysqli_query($conn, $query);
    if (mysqli_num_rows($result) == 0) {
        echo "Invalid token.";
        return;
    }

    // Get user's email from database
    $row = mysqli_fetch_assoc($result);
    $email = $row['email'];

    // Display password reset form to user
    ?>
    <form action="reset-password.php" method="post">
        <input type="password" name="new_password" placeholder="New Password">
        <button type="submit">Reset Password</button>
    </form>
    <?php

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        // Verify new password
        $new_password = $_POST['new_password'];
        if (strlen($new_password) < 8) {
            echo "Password must be at least 8 characters long.";
            return;
        }

        // Update user's password in database
        $hashed_password = hash_password($new_password);
        $query = "UPDATE users SET password = '$hashed_password', password_reset_token = '' WHERE email = '$email'";
        mysqli_query($conn, $query);

        echo "Password reset successfully.";
    }
}


forgot_password('example@example.com');


<?php

// Configuration settings
define('DB_HOST', 'your_database_host');
define('DB_USER', 'your_database_username');
define('DB_PASSWORD', 'your_database_password');
define('DB_NAME', 'your_database_name');

// Connect to database
$mysqli = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
if ($mysqli->connect_errno) {
    echo "Failed to connect to MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
    exit();
}

// Set POST variables from form submission
$email = $_POST['email'];
$token = md5(uniqid(mt_rand(), true));

// Check if email is in database
$stmt = $mysqli->prepare("SELECT id, email FROM users WHERE email = ?");
$stmt->bind_param('s', $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    // Email found in database, generate and send password reset token
    $row = $result->fetch_assoc();
    $token = base64_encode(serialize($row));
    $subject = 'Reset your password';
    $message = 'Click this link to reset your password: <a href="http://your-website.com/reset_password.php?token=' . $token . '">Reset Password</a>';
    mail($email, $subject, $message);

    // Store token in database for future use
    $stmt2 = $mysqli->prepare("UPDATE users SET password_token = ? WHERE email = ?");
    $stmt2->bind_param('ss', $token, $email);
    $stmt2->execute();
} else {
    echo 'Email not found in database';
}

// Close connection to database
$mysqli->close();

?>


<?php

// Configuration settings
define('DB_HOST', 'your_database_host');
define('DB_USER', 'your_database_username');
define('DB_PASSWORD', 'your_database_password');
define('DB_NAME', 'your_database_name');

// Connect to database
$mysqli = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
if ($mysqli->connect_errno) {
    echo "Failed to connect to MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
    exit();
}

// Get token from URL parameter
$token = base64_decode($_GET['token']);
$token_array = unserialize($token);

// Check if token is valid and matches email in database
$stmt = $mysqli->prepare("SELECT id, email FROM users WHERE password_token = ? AND email = ?");
$stmt->bind_param('ss', $token, $token_array['email']);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    // Token is valid, prompt user to enter new password
    echo 'Enter your new password: <input type="password" name="new_password">';
    echo '<input type="submit" value="Reset Password">';

    // Update database with new password when form submitted
    if ($_POST['new_password']) {
        $stmt2 = $mysqli->prepare("UPDATE users SET password = ? WHERE id = ?");
        $stmt2->bind_param('ss', $_POST['new_password'], $token_array['id']);
        $stmt2->execute();

        // Clear token from database
        $stmt3 = $mysqli->prepare("UPDATE users SET password_token = NULL WHERE email = ?");
        $stmt3->bind_param('s', $token_array['email']);
        $stmt3->execute();
    }
} else {
    echo 'Invalid or expired token';
}

// Close connection to database
$mysqli->close();

?>


<?php

// Configuration settings
define('SITE_URL', 'http://example.com');
define('SECRET_KEY', 'your_secret_key_here');

require_once 'db_connect.php'; // assume this is your database connection script

if (isset($_POST['username'])) {
  $username = $_POST['username'];
  $email = $_POST['email'];

  // Check if the user exists
  $query = "SELECT id FROM users WHERE username = '$username' AND email = '$email'";
  $result = mysqli_query($conn, $query);

  if (mysqli_num_rows($result) > 0) {
    // Generate a random token and store it in the database
    $token = bin2hex(random_bytes(32));
    $updateQuery = "UPDATE users SET resetToken = '$token' WHERE username = '$username'";
    mysqli_query($conn, $updateQuery);

    // Send password reset email to the user's email address
    sendPasswordResetEmail($email, $token);
  } else {
    echo 'Error: User not found';
  }
} else {
  ?>

  <form action="" method="post">
    <label for="username">Username:</label>
    <input type="text" id="username" name="username"><br><br>
    <label for="email">Email:</label>
    <input type="email" id="email" name="email"><br><br>
    <button type="submit" name="submit">Send Reset Link</button>
  </form>

  <?php
}

function sendPasswordResetEmail($email, $token) {
  $subject = 'Reset Your Password';
  $body = "Click on the following link to reset your password: <a href='" . SITE_URL . "/reset_password.php?token=$token'>$siteUrl/reset_password.php?token=$token</a>";
  mail($email, $subject, $body);
}


<?php

require_once 'db_connect.php'; // assume this is your database connection script

if (isset($_GET['token'])) {
  $token = $_GET['token'];

  // Check if the token exists in the database
  $query = "SELECT id FROM users WHERE resetToken = '$token'";
  $result = mysqli_query($conn, $query);

  if (mysqli_num_rows($result) > 0) {
    // Get the user's data from the database
    $userQuery = "SELECT * FROM users WHERE resetToken = '$token'";
    $userData = mysqli_fetch_assoc(mysqli_query($conn, $userQuery));

    // Allow the user to reset their password
    echo 'Enter your new password: ';
    echo '<input type="password" id="newPassword" name="newPassword"><br><br>';
    echo '<button type="submit" name="reset">Reset Password</button>';

    if (isset($_POST['reset'])) {
      $newPassword = $_POST['newPassword'];
      $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);

      // Update the user's password in the database
      $updateQuery = "UPDATE users SET passwordHash = '$hashedPassword' WHERE resetToken = '$token'";
      mysqli_query($conn, $updateQuery);

      echo 'Your password has been successfully updated!';
    }
  } else {
    echo 'Error: Invalid token';
  }
}
?>


<?php

// Configuration settings
$database_host = 'localhost';
$database_username = 'your_username';
$database_password = 'your_password';
$database_name = 'your_database';

// Connect to database
$mysqli = new mysqli($database_host, $database_username, $database_password, $database_name);
if ($mysqli->connect_errno) {
    die("Error connecting to database: " . $mysqli->connect_error);
}

// Function to send reset link via email
function send_reset_link($email, $reset_token) {
    // Send email using your preferred method (e.g., PHPMailer)
    // For demonstration purposes, we'll just print the email body
    echo "Subject: Reset Password
";
    echo "To: $email
";
    echo "From: your_email@example.com
";
    echo "
";
    echo "Click here to reset password: http://your-website.com/reset-password.php?token=$reset_token
";
}

// Handle form submission
if (isset($_POST['submit'])) {
    // Get email from form input
    $email = $_POST['email'];

    // Check if user exists in database
    $query = "SELECT * FROM users WHERE email = '$email'";
    $result = $mysqli->query($query);
    if ($result->num_rows > 0) {
        // User found, generate reset token and send email
        $reset_token = bin2hex(random_bytes(32));
        $mysqli->query("UPDATE users SET reset_token = '$reset_token' WHERE email = '$email'");
        send_reset_link($email, $reset_token);
        echo "Reset link sent to your email!";
    } else {
        echo "Email not found. Please try again.";
    }
}

// Display form
?>
<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
    <label for="email">Email:</label>
    <input type="text" id="email" name="email"><br><br>
    <input type="submit" value="Send Reset Link" name="submit">
</form>


<?php
require_once 'dbconfig.php'; // assume this contains your database connection settings

if (isset($_POST['forgot-password'])) {
  $email = $_POST['email'];
  
  // validate input
  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo "Invalid email address";
    exit;
  }
  
  // generate reset token
  $resetToken = bin2hex(random_bytes(32));
  
  // update user's password reset field in database
  try {
    $stmt = $pdo->prepare("UPDATE users SET password_reset_token = :token WHERE email = :email");
    $stmt->execute([':token' => $resetToken, ':email' => $email]);
    
    // send password reset link to user via email
    sendPasswordResetEmail($email, $resetToken);
  } catch (PDOException $e) {
    echo "Error updating password reset token";
    exit;
  }
}

// function to send password reset email
function sendPasswordResetEmail($email, $resetToken)
{
  // configure email settings (SMTP server, from address, etc.)
  $fromEmail = 'your-email@example.com';
  $subject = 'Reset Your Password';
  
  // construct email body with password reset link
  $body = "<p>Click this link to reset your password:</p><a href='reset-password.php?token=$resetToken'>Reset Password</a>";
  
  try {
    mail($email, $subject, $body, 'From: ' . $fromEmail);
    echo "Password reset email sent to $email";
  } catch (Exception $e) {
    echo "Error sending password reset email: " . $e->getMessage();
  }
}

// function to handle password reset form submission
if (isset($_POST['reset-password'])) {
  // extract token from URL
  $token = $_GET['token'];
  
  // validate input fields
  if (!isset($_POST['new-password']) || !isset($_POST['confirm-new-password'])) {
    echo "Error: both new password and confirm new password are required";
    exit;
  }
  
  // hash new password and update user's password in database
  try {
    $stmt = $pdo->prepare("UPDATE users SET password = :password WHERE password_reset_token = :token");
    $stmt->execute([':password' => password_hash($_POST['new-password'], PASSWORD_DEFAULT), ':token' => $token]);
    
    // reset password reset token in database
    $stmt = $pdo->prepare("UPDATE users SET password_reset_token = NULL WHERE password_reset_token = :token");
    $stmt->execute([':token' => $token]);
  } catch (PDOException $e) {
    echo "Error updating user's password";
  }
}
?>


<?php
// Configuration
$dbHost = 'localhost';
$dbUsername = 'your_username';
$dbPassword = 'your_password';
$dbName = 'your_database';

// Connect to database
$conn = mysqli_connect($dbHost, $dbUsername, $dbPassword, $dbName);

if (!$conn) {
  die("Connection failed: " . mysqli_connect_error());
}

function sendEmail($to_email, $token) {
  // Email configuration
  $fromEmail = 'your_email@example.com';
  $fromName = 'Your Name';
  $subject = 'Reset Your Password';
  $body = "
    Hi,
    
    Click on the following link to reset your password:
    <a href='http://example.com/reset_password.php?token=$token'>Reset Password</a>
    
    Best regards,
    Your Name
  ";

  // Send email using mail() function
  $headers = 'From: ' . $fromEmail . "\r
" .
             'Subject: ' . $subject . "\r
";
  mail($to_email, $subject, $body, $headers);
}

function checkTokenExists($token) {
  global $conn;
  $query = "SELECT * FROM users WHERE reset_token = '$token'";
  $result = mysqli_query($conn, $query);

  if (mysqli_num_rows($result) > 0) {
    return true;
  } else {
    return false;
  }
}

function updatePassword($token, $new_password) {
  global $conn;
  $query = "UPDATE users SET password_hash = '$new_password', reset_token = NULL WHERE reset_token = '$token'";
  mysqli_query($conn, $query);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  // Get user email
  $email = $_POST['email'];

  // Check if email exists in database
  $query = "SELECT * FROM users WHERE email = '$email'";
  $result = mysqli_query($conn, $query);

  if (mysqli_num_rows($result) > 0) {
    // Get user data
    $row = mysqli_fetch_assoc($result);
    $token = md5(uniqid(mt_rand(), true));

    // Update user data in database with new reset token
    $updateQuery = "UPDATE users SET reset_token = '$token' WHERE email = '$email'";
    mysqli_query($conn, $updateQuery);

    // Send email to user
    sendEmail($email, $token);
  } else {
    echo 'Email not found!';
  }
}

// If GET request with token is made
if (isset($_GET['token'])) {
  // Check if token exists in database
  $checkToken = checkTokenExists($_GET['token']);

  if ($checkToken) {
    // Update password using the provided token
    updatePassword($_GET['token'], $_POST['new_password']);
    echo 'Password updated!';
  } else {
    echo 'Invalid token!';
  }
}
?>


<?php

// Include database connection settings
require_once 'db_config.php';

// Check if form has been submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  // Get email from form input
  $email = $_POST['email'];

  // Validate email
  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo 'Invalid email address';
    exit;
  }

  // Query database for user with matching email
  $query = "SELECT * FROM users WHERE email = '$email'";
  $result = mysqli_query($conn, $query);

  // If user exists, send reset token via email and update database
  if ($row = mysqli_fetch_assoc($result)) {
    // Generate random reset token
    $reset_token = bin2hex(random_bytes(32));

    // Send email with reset link (we'll use a simple link for demonstration purposes)
    $to      = $email;
    $subject = 'Reset Password';
    $message = 'Click here to reset your password: <a href="' . $_SERVER['HTTP_HOST'] . '/reset_password.php?token=' . $reset_token . '">Reset</a>';
    $headers = 'From: info@example.com' . "\r
" .
      'Content-Type: text/html; charset=UTF-8';
    mail($to, $subject, $message, $headers);

    // Update database with reset token
    $query = "UPDATE users SET reset_token = '$reset_token', reset_expires = NOW() + INTERVAL 1 HOUR WHERE email = '$email'";
    mysqli_query($conn, $query);

    echo 'Reset link sent to your email';
  } else {
    echo 'Email address not found in our database';
  }
}

?>


<?php

// Include database connection settings
require_once 'db_config.php';

// Check if form has been submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  // Get token from URL parameter
  $token = $_GET['token'];

  // Validate token (we'll use a simple validation for demonstration purposes)
  if (!empty($token)) {
    // Query database for user with matching token
    $query = "SELECT * FROM users WHERE reset_token = '$token'";
    $result = mysqli_query($conn, $query);

    // If user exists, update password and remove reset token from database
    if ($row = mysqli_fetch_assoc($result)) {
      // Get new password from form input
      $new_password = $_POST['password'];

      // Hash new password (we'll use a simple hashing algorithm for demonstration purposes)
      $hashed_password = hash('sha256', $new_password);

      // Update database with new password
      $query = "UPDATE users SET password_hash = '$hashed_password' WHERE reset_token = '$token'";
      mysqli_query($conn, $query);

      echo 'Password updated successfully';
    } else {
      echo 'Invalid token';
    }
  }
}

?>


<?php

// Configuration
$dbHost = 'localhost';
$dbUsername = 'your_username';
$dbPassword = 'your_password';
$dbName = 'your_database';

// Connect to database
$conn = new mysqli($dbHost, $dbUsername, $dbPassword, $dbName);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Function to reset password
function forgot_password($email)
{
    // SQL query to select user by email
    $sql = "SELECT * FROM users WHERE email = '$email'";
    
    // Execute query and get result
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // Get the user's ID and password hash
        $user = $result->fetch_assoc();

        // Generate a random password
        $password = substr(str_shuffle('abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789'), 0, 10);

        // Update the password in database (with hashing)
        $new_password_hash = password_hash($password, PASSWORD_DEFAULT);
        $sql = "UPDATE users SET password_hash = '$new_password_hash' WHERE id = '" . $user['id'] . "'";
        $conn->query($sql);

        // Send email with new password
        $to = $email;
        $subject = 'Reset Password';
        $body = 'Your new password is: ' . $password;

        mail($to, $subject, $body);
        
        echo "New password sent to your email";
    } else {
        echo "Email not found in database";
    }
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    
    // Check if email is valid (e.g. contains '@')
    if (strpos($email, '@') !== false) {
        forgot_password($email);
    } else {
        echo "Invalid email address";
    }
}

// Close database connection
$conn->close();

?>


<?php

// Configuration
define('RESET_TOKEN_EXPIRE', 60 * 15); // 15 minutes
define('RESET_PASSWORD_LINK_LENGTH', 30);

// Function to send password reset email
function sendPasswordResetEmail($email, $resetToken) {
  $subject = 'Reset Your Password';
  $message = '
    <p>Dear user,</p>
    <p>You are receiving this email because you requested a password reset for your account.</p>
    <p>To reset your password, click on the following link:</p>
    <a href="' . site_url('reset-password/' . $resetToken) . '">' . site_url('reset-password/' . $resetToken) . '</a>
  ';
  mail($email, $subject, $message);
}

// Function to generate reset token
function generateResetToken() {
  return bin2hex(random_bytes(16));
}

// Function to verify password reset link
function verifyPasswordResetLink($token) {
  global $wpdb;
  $result = $wpdb->get_row("SELECT * FROM users WHERE reset_token = '$token'");
  if ($result && time() <= strtotime($result->reset_expires)) {
    return true;
  }
  return false;
}

// Function to update user password
function updatePassword($userId, $newPassword) {
  global $wpdb;
  $wpdb->update('users', array(
    'password' => hash('sha256', $newPassword)
  ), array(
    'id' => $userId
  ));
}

// Forgot Password function
function forgotPassword($email) {
  global $wpdb, $resetToken;
  
  // Check if user exists
  $user = $wpdb->get_row("SELECT * FROM users WHERE email = '$email'");
  if (!$user) {
    return array('success' => false, 'message' => 'User not found');
  }
  
  // Generate reset token and set expiration time
  $resetToken = generateResetToken();
  $expiresAt = date('Y-m-d H:i:s', strtotime('+15 minutes'));
  
  // Update user with new reset token and expiration time
  $wpdb->update('users', array(
    'reset_token' => $resetToken,
    'reset_expires' => $expiresAt
  ), array(
    'id' => $user->id
  ));
  
  // Send password reset email
  sendPasswordResetEmail($email, $resetToken);
  
  return array('success' => true, 'message' => 'A password reset link has been sent to your email');
}

?>


$email = 'user@example.com';
$result = forgotPassword($email);
echo json_encode($result); // Output: {"success":true,"message":"A password reset link has been sent to your email"}


<?php

// Configuration settings
$db_host = 'localhost';
$db_username = 'your_username';
$db_password = 'your_password';
$db_name = 'your_database';

// Connect to the database
$conn = new mysqli($db_host, $db_username, $db_password, $db_name);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get the email address from the form submission
$email = $_POST['email'];

// Validate the email address (optional)
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo 'Invalid email address';
    exit;
}

// Query the database to retrieve the user's ID and password reset token
$query = "SELECT id, password_reset_token FROM users WHERE email = '$email'";
$result = $conn->query($query);

// Check if a result was returned (i.e., the user exists)
if ($result->num_rows > 0) {
    // Get the user's data from the result set
    while ($row = $result->fetch_assoc()) {
        $user_id = $row['id'];
        $password_reset_token = $row['password_reset_token'];

        // Generate a password reset link
        $reset_link = "http://your-website.com/reset_password.php?token=" . $password_reset_token;

        // Send an email to the user with the password reset link (optional)
        $to = $email;
        $subject = 'Password Reset Link';
        $body = 'Click this link to reset your password: ' . $reset_link;
        mail($to, $subject, $body);

        echo 'A password reset link has been sent to your email address.';
    }
} else {
    echo 'No user found with that email address';
}

// Close the database connection
$conn->close();

?>


<?php

// Configuration settings
$db_host = 'localhost';
$db_username = 'your_username';
$db_password = 'your_password';
$db_name = 'your_database';

// Connect to the database
$conn = new mysqli($db_host, $db_username, $db_password, $db_name);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get the password reset token from the URL parameter
$token = $_GET['token'];

// Query the database to retrieve the user's data
$query = "SELECT id, email FROM users WHERE password_reset_token = '$token'";
$result = $conn->query($query);

// Check if a result was returned (i.e., the token is valid)
if ($result->num_rows > 0) {
    // Get the user's data from the result set
    while ($row = $result->fetch_assoc()) {
        $user_id = $row['id'];
        $email = $row['email'];

        // Generate a new password (e.g., using bcrypt)
        $new_password = password_hash('newpassword', PASSWORD_BCRYPT);

        // Update the user's password in the database
        $query = "UPDATE users SET password = '$new_password' WHERE id = '$user_id'";
        $conn->query($query);

        echo 'Your password has been successfully reset.';
    }
} else {
    echo 'Invalid password reset token';
}

// Close the database connection
$conn->close();

?>


<?php

// Configuration settings
$db_host = 'localhost';
$db_username = 'your_database_username';
$db_password = 'your_database_password';
$db_name = 'your_database_name';

// Connect to database
$conn = mysqli_connect($db_host, $db_username, $db_password, $db_name);

if (!$conn) {
    die('Could not connect: ' . mysqli_error());
}

// Define function to send password reset email
function send_password_reset_email($email, $token) {
    // Replace with your own email service (e.g. Mailgun, Sendgrid)
    $subject = 'Password Reset';
    $body = "Click this link to reset your password: <a href='http://example.com/reset-password?token=$token'>Reset Password</a>";
    mail($email, $subject, $body);
}

// Handle forgot password form submission
if (isset($_POST['forgot_password'])) {
    // Get email from form input
    $email = $_POST['email'];

    // Query database to get user credentials
    $query = "SELECT * FROM users WHERE email='$email'";
    $result = mysqli_query($conn, $query);

    if ($result && mysqli_num_rows($result) > 0) {
        // Get user data from query result
        $user_data = mysqli_fetch_assoc($result);
        $token = bin2hex(random_bytes(32));

        // Update password reset token in database
        $query = "UPDATE users SET password_reset_token='$token' WHERE email='$email'";
        mysqli_query($conn, $query);

        // Send password reset email
        send_password_reset_email($user_data['email'], $token);
    } else {
        echo 'Invalid email or password';
    }
}

// Close database connection
mysqli_close($conn);

?>


<?php

// Configuration
define('DB_HOST', 'your_host');
define('DB_USER', 'your_username');
define('DB_PASSWORD', 'your_password');
define('DB_NAME', 'your_database');

function connectToDatabase() {
    $conn = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    return $conn;
}

function sendPasswordResetEmail($email, $token) {
    // You can use a library like PHPMailer for more complex email handling
    echo "Sending password reset link to $email..."; // Replace with actual email sending code
}

function forgotPassword() {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $usernameOrEmail = $_POST['username_or_email'];
        $conn = connectToDatabase();
        
        if ($conn) {
            $query = "SELECT * FROM users WHERE (email='$usernameOrEmail' OR username='$usernameOrEmail')";
            $result = $conn->query($query);
            
            if ($result->num_rows > 0) {
                // Generate a unique token for password reset
                $token = bin2hex(random_bytes(32));
                
                // Store the new token in the database
                $updateQuery = "UPDATE users SET reset_token='$token' WHERE (email='$usernameOrEmail' OR username='$usernameOrEmail')";
                if ($conn->query($updateQuery) === TRUE) {
                    echo "Please check your email for password reset link.";
                    
                    // Send the password reset link to the user
                    sendPasswordResetEmail($usernameOrEmail, $token);
                } else {
                    echo "Error updating user: " . $conn->error;
                }
            } else {
                echo "No account found with that username/email.";
            }
            
            $conn->close();
        } else {
            die("Connection to database failed.");
        }
    }
}

// Display the form for forgotten password
?>
<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
    <label>Enter your email or username:</label>
    <input type="text" name="username_or_email"><br><br>
    <input type="submit" value="Submit">
</form>

<?php
forgotPassword();
?>


<?php

// Configuration variables
$db_host = 'localhost';
$db_username = 'your_username';
$db_password = 'your_password';
$db_name = 'your_database';

// Connect to database
$conn = mysqli_connect($db_host, $db_username, $db_password, $db_name);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

function forgotPassword() {
    global $conn;
    
    // Get user input
    if (isset($_POST['email'])) {
        $email = $_POST['email'];
        
        // Validate email address
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            echo "Invalid email address.";
            return false;
        }
        
        // Check if user exists in database
        $sql = "SELECT * FROM users WHERE email = '$email'";
        $result = mysqli_query($conn, $sql);
        
        if (mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                // Generate new password
                $new_password = substr(md5(uniqid()), 0, 8);
                
                // Update user's password in database
                $sql_update = "UPDATE users SET password_hash = '$new_password' WHERE email = '$email'";
                mysqli_query($conn, $sql_update);
                
                // Send new password to user via email (replace with your own mail function)
                sendEmail($email, 'New Password:', $new_password);
                
                echo "New password has been sent to your email.";
                return true;
            }
        } else {
            echo "User not found.";
            return false;
        }
    }
}

function sendEmail($to_email, $subject, $body) {
    // Your own mail function here (e.g. using PHPMailer)
    // For simplicity, let's use the built-in mail() function
    $mail = mail($to_email, $subject, $body);
    
    if ($mail) {
        echo "Email sent successfully.";
    } else {
        echo "Failed to send email.";
    }
}

if (isset($_POST['submit'])) {
    forgotPassword();
} else {
    // Display form
    ?>
    <form action="" method="post">
        <label for="email">Enter your email address:</label>
        <input type="text" id="email" name="email"><br><br>
        <button type="submit" name="submit">Submit</button>
    </form>
    <?php
}
?>


<?php

require_once 'config.php'; // Your database configuration file

// Validate form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = trim($_POST['email']);
    
    if (empty($email)) {
        echo json_encode(array('error' => 'Please enter your email address.'));
        exit;
    }

    $query = "SELECT * FROM users WHERE email = '$email'";
    $result = mysqli_query($conn, $query);
    
    // If the user exists
    if ($result->num_rows == 1) {
        $user_data = $result->fetch_assoc();
        
        // Generate a random token for password reset
        $token = uniqid('', true);
        $hashed_token = hash('sha256', $token); // Hash the token to prevent SQL injection
        
        // Update user data with new hashed token
        $query_update = "UPDATE users SET token = '$hashed_token' WHERE id = '$user_data[id]'";
        mysqli_query($conn, $query_update);
        
        // Send a password reset link via email
        sendPasswordResetEmail($email, $token);

        echo json_encode(array('success' => 'Please check your email for the password reset link.'));
    } else {
        echo json_encode(array('error' => 'No user found with this email address.'));
    }
    
    // Close database connection
    mysqli_close($conn);
} else {
    header("Location: index.php"); // Your login/index page URL
}

// Function to send password reset email using PHPMailer
function sendPasswordResetEmail($email, $token) {
    require_once 'PHPMailer/PHPMailerAutoload.php'; // Make sure you have the PHPMailer library in your project
    
    $mail = new PHPMailer(true);
    
    try {
        $mail->isSMTP();
        $mail->Host = 'smtp.example.com'; // Your SMTP server
        $mail->SMTPAuth = true;
        $mail->Username = 'your_email@example.com';
        $mail->Password = 'your_password';
        
        $mail->setFrom('your_email@example.com', 'Your Name');
        $mail->addAddress($email);
        
        $body = "You can reset your password by clicking on this link: <a href='reset_password.php?token=" . urlencode($token) . "'>Reset Password</a>";
        $mail->isHTML(true);
        $mail->Subject = 'Password Reset';
        $mail->Body = $body;
        
        if (!$mail->send()) {
            echo 'Message could not be sent.';
            echo 'Mailer Error: ' . $mail->ErrorInfo;
        } else {
            // Password reset email sent successfully
        }
    } catch (Exception $e) {
        echo 'Message could not be sent. Mailer Error: ', $mail->ErrorInfo;
    }
}


<?php

require_once 'config.php'; // Your database configuration file

// Validate form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $token = $_POST['token'];
    
    if (empty($token)) {
        echo json_encode(array('error' => 'Please enter the token from your email.'));
        exit;
    }

    $query = "SELECT * FROM users WHERE token = '$token'";
    $result = mysqli_query($conn, $query);
    
    // If the user exists
    if ($result->num_rows == 1) {
        $user_data = $result->fetch_assoc();
        
        // Update user data with new password
        $new_password = hash('sha256', $_POST['new_password']);
        $query_update = "UPDATE users SET password = '$new_password' WHERE id = '$user_data[id]'";
        mysqli_query($conn, $query_update);
        
        echo json_encode(array('success' => 'Your password has been successfully reset.'));
    } else {
        echo json_encode(array('error' => 'Invalid token. Please try again.'));
    }
    
    // Close database connection
    mysqli_close($conn);
} else {
    if (isset($_GET['token'])) {
        $token = $_GET['token'];
        
        // Display the reset password form
        echo '<form method="post">';
        echo '<label for="new_password">New Password:</label>';
        echo '<input type="password" name="new_password" id="new_password"><br><br>';
        echo '<button type="submit">Reset Password</button>';
        echo '</form>';
    } else {
        header("Location: index.php"); // Your login/index page URL
    }
}

?>


<?php
function forgot_password($email) {
  // Check if email exists in database
  $conn = mysqli_connect("localhost", "username", "password", "database");
  if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
  }

  $query = "SELECT * FROM users WHERE email = '$email'";
  $result = mysqli_query($conn, $query);
  if (mysqli_num_rows($result) > 0) {
    // Email exists, generate new password and send it to user
    $new_password = substr(md5(uniqid()), 0, 8); // Generate new password
    $update_query = "UPDATE users SET password = '$new_password' WHERE email = '$email'";
    mysqli_query($conn, $update_query);

    // Send email with new password
    $to = $email;
    $subject = "Reset Password";
    $message = "Your new password is: $new_password";
    mail($to, $subject, $message);
    echo "New password has been sent to your email.";
  } else {
    echo "Email not found in database.";
  }

  mysqli_close($conn);
}
?>


<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $email = $_POST['email'];
  forgot_password($email);
}
?>


<?php

// Set up database connection
$host = 'localhost';
$username = 'your_username';
$password = 'your_password';
$dbname = 'your_database';

$conn = new mysqli($host, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Define function to check if user exists
function check_user_exists($email) {
    global $conn;
    $query = "SELECT id FROM users WHERE email = '$email'";
    $result = $conn->query($query);
    return $result->num_rows > 0;
}

// Define function to send forgot password email
function send_forgot_password_email($user_id, $email) {
    global $conn;

    // Generate random token (e.g. for email verification)
    $token = bin2hex(random_bytes(32));

    // Update user's email in database with new token
    $query = "UPDATE users SET forgot_password_token = '$token' WHERE id = '$user_id'";
    $conn->query($query);

    // Send email to user with reset link
    $to = $email;
    $subject = 'Reset your password';
    $body = "Click this link to reset your password: <a href='http://your-website.com/reset-password.php?token=$token'>Reset Password</a>";

    $headers = 'From: your-email@example.com' . "\r
";
    mail($to, $subject, $body, $headers);

    return true;
}

// Handle forgot password form submission
if (isset($_POST['forgot_password'])) {
    $email = $_POST['email'];

    // Check if user exists
    if (check_user_exists($email)) {
        $user_id = $conn->query("SELECT id FROM users WHERE email = '$email'")->fetch_assoc()['id'];
        send_forgot_password_email($user_id, $email);
    } else {
        echo "Email not found";
    }
}

// Display forgot password form
?>
<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
    <label>Email:</label>
    <input type="text" name="email" value="<?php echo isset($_POST['email']) ? $_POST['email'] : ''; ?>">
    <button type="submit" name="forgot_password">Submit</button>
</form>

<?php
// Close database connection
$conn->close();
?>


<?php

// Set up database connection
$host = 'localhost';
$username = 'your_username';
$password = 'your_password';
$dbname = 'your_database';

$conn = new mysqli($host, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Define function to check if token is valid
function check_token_valid($token) {
    global $conn;

    // Get user ID from database using token
    $query = "SELECT id FROM users WHERE forgot_password_token = '$token'";
    $result = $conn->query($query);

    return $result->num_rows > 0;
}

// Define function to update password
function update_password($user_id, $new_password) {
    global $conn;

    // Hash new password and update user's password in database
    $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
    $query = "UPDATE users SET password_hash = '$hashed_password' WHERE id = '$user_id'";
    $conn->query($query);

    return true;
}

// Handle reset password form submission
if (isset($_POST['reset_password'])) {
    $token = $_GET['token'];

    // Check if token is valid
    if (check_token_valid($token)) {
        $user_id = $conn->query("SELECT id FROM users WHERE forgot_password_token = '$token'")->fetch_assoc()['id'];
        update_password($user_id, $_POST['new_password']);
    } else {
        echo "Invalid token";
    }
}

// Display reset password form
?>
<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
    <label>New Password:</label>
    <input type="password" name="new_password" value="<?php echo isset($_POST['new_password']) ? $_POST['new_password'] : ''; ?>">
    <button type="submit" name="reset_password">Submit</button>
</form>

<?php
// Close database connection
$conn->close();
?>


<?php

// Configuration
define('SECRET_KEY', 'your_secret_key_here');
define('MAX_RETRIES', 5);

// Function to send forgot password email
function sendForgotPasswordEmail($email, $retries = 0)
{
    // Check if user exists
    $user = getUserByEmail($email);
    if (!$user) {
        return false;
    }

    // Generate reset token
    $resetToken = generateResetToken();

    // Save reset token to database
    saveResetToken($user['id'], $resetToken);

    // Send email with reset link
    $subject = 'Forgot Password';
    $body = '<p>Click the following link to reset your password:</p>
            <a href="' . $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['HTTP_HOST'] . '/reset-password?token=' . $resetToken . '">Reset Password</a>';
    sendEmail($email, $subject, $body);

    return true;
}

// Function to generate reset token
function generateResetToken()
{
    // Use a cryptographically secure pseudo-random number generator (CSPRNG)
    $randomBytes = random_bytes(32);
    $resetToken = bin2hex($randomBytes);
    return $resetToken;
}

// Function to save reset token in database
function saveResetToken($userId, $resetToken)
{
    // Update user record with reset token
    $query = "UPDATE users SET reset_token = '$resetToken' WHERE id = '$userId'";
    mysqli_query($conn, $query);
}

// Function to send email (using a library like PHPMailer or SwiftMailer)
function sendEmail($email, $subject, $body)
{
    // Implement your own email sending logic here
}

// Example usage:
$email = 'user@example.com';
if (sendForgotPasswordEmail($email)) {
    echo "Reset link sent successfully!";
} else {
    echo "Error sending reset link. Please try again.";
}


<?php

// Include configuration file
require_once 'config.php';

// Check if form has been submitted
if (isset($_POST['submit'])) {
  // Get input values
  $email = $_POST['email'];

  // Validate email address
  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo "Invalid email address.";
    exit;
  }

  // Retrieve user data from database
  $sql = "SELECT * FROM users WHERE email = ?";
  $stmt = $pdo->prepare($sql);
  $stmt->execute([$email]);
  $user = $stmt->fetch();

  if (!$user) {
    echo "No account found with that email address.";
    exit;
  }

  // Generate reset token
  $resetToken = bin2hex(random_bytes(32));
  $expiresAt = time() + (60 * 60 * 24); // 1 day from now

  // Update user data in database
  $sql = "UPDATE users SET reset_token = ?, expires_at = ? WHERE id = ?";
  $stmt = $pdo->prepare($sql);
  $stmt->execute([$resetToken, $expiresAt, $user['id']]);

  // Send password reset email
  sendPasswordResetEmail($email, $resetToken);

  echo "A password reset link has been sent to your email address.";
} else {
  // Display forgot password form
  ?>
  <form action="" method="post">
    <label for="email">Enter your email address:</label>
    <input type="text" id="email" name="email">
    <button type="submit" name="submit">Send Reset Link</button>
  </form>
  <?php
}

// Function to send password reset email
function sendPasswordResetEmail($email, $resetToken) {
  // Configuration variables (replace with your own)
  $fromEmail = 'your-email@example.com';
  $fromName = 'Your Website';

  // Email template
  $subject = 'Password Reset Link';
  $body = "
    Dear user,

    You have requested to reset your password. To do so, click on the following link:

    <a href='http://example.com/reset-password?token=<?php echo $resetToken; ?>'>Reset Password</a>

    If you did not request a password reset, please ignore this email.

    Best regards,
    " . $fromName;

  // Send email using PHPMailer or mail() function
  // ...
}


// file: forgot_password.php

// Configuration
$database = array(
    'host' => 'localhost',
    'username' => 'your_username',
    'password' => 'your_password',
    'database' => 'your_database'
);

function send_reset_link($email, $username) {
    // Check if email exists in database
    $query = "SELECT * FROM users WHERE email = '$email'";
    $result = mysqli_query($database['connection'], $query);
    if (mysqli_num_rows($result) == 0) {
        echo "Email not found";
        return;
    }

    // Generate random password
    $password = substr(md5(uniqid(rand(), true)), 0, 10);

    // Update user's password in database
    $query = "UPDATE users SET password = '$password' WHERE email = '$email'";
    mysqli_query($database['connection'], $query);

    // Send reset link to user's email
    $subject = 'Reset Your Password';
    $body = "
        Dear $username,
        
        We have received a request to reset your password. To do so, please click on the following link:
        
        <a href='reset_password.php?email=$email&password=$password'>Reset Password</a>
        
        Best regards,
        [Your Name]
    ";
    mail($email, $subject, $body);

    echo "Password reset link sent to your email.";
}

function forgot_password() {
    // Handle form submission
    if (isset($_POST['submit'])) {
        $email = $_POST['email'];
        $username = $_POST['username'];

        send_reset_link($email, $username);
    }
}


// file: reset_password.php

// Configuration
$database = array(
    'host' => 'localhost',
    'username' => 'your_username',
    'password' => 'your_password',
    'database' => 'your_database'
);

function update_password($email, $password) {
    // Update user's password in database
    $query = "UPDATE users SET password = '$password' WHERE email = '$email'";
    mysqli_query($database['connection'], $query);
}

function reset_password() {
    // Handle form submission
    if (isset($_POST['submit'])) {
        $email = $_POST['email'];
        $password = $_POST['password'];

        update_password($email, $password);

        echo "Password updated successfully.";
    }
}


<?php

// Include PHPMailer library
require_once 'vendor/autoload.php';

use PHPMailer\PHPMailer;

// Configuration variables
$server = 'smtp.gmail.com';
$port = 587;
$username = 'your_email@gmail.com'; // replace with your email address
$password = 'your_password'; // replace with your password

// Email verification code
$verifyCode = md5(uniqid(mt_rand(), true));

// Forgot password form
if (isset($_POST['forgot'])) {

    // Check if username or email is provided
    $usernameOrEmail = $_POST['username_or_email'];

    try {
        // Connect to database
        $conn = new PDO('mysql:host=localhost;dbname=your_database', 'your_username', 'your_password');

        // Query to retrieve user data based on username or email
        $stmt = $conn->prepare("SELECT * FROM users WHERE (username=:username_or_email OR email=:username_or_email)");
        $stmt->bindParam(':username_or_email', $usernameOrEmail);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            // Retrieve user data
            $user = $stmt->fetch();

            // Generate reset password link
            $resetLink = "http://your_domain.com/resetPassword.php?verifyCode=$verifyCode&userId={$user['id']}";

            // Send email with reset password link
            sendEmail($usernameOrEmail, $verifyCode);

        } else {
            echo 'User not found';
        }

    } catch (PDOException $e) {
        echo "Error connecting to database: " . $e->getMessage();
    }
}

// Function to send email using PHPMailer
function sendEmail($to, $verifyCode)
{
    // Create a new instance of PHPMailer
    $mail = new PHPMailer(true);

    try {
        // Configuration settings for sending email
        $mail->isSMTP();
        $mail->Host = $server;
        $mail->Port = $port;
        $mail->SMTPAuth = true;
        $mail->Username = $username;
        $mail->Password = $password;

        // Email settings
        $mail->setFrom($username, 'Your Website');
        $mail->addAddress($to);
        $mail->Subject = "Reset Password Link";
        $mail->Body = "Click on the link below to reset your password: <a href='$resetLink'>Reset Password</a>";

        // Send email
        $mail->send();

    } catch (Exception $e) {
        echo "Error sending email: " . $e->getMessage();
    }
}

?>


<?php

// Include PHPMailer library
require_once 'vendor/autoload.php';

use PHPMailer\PHPMailer;

// Configuration variables
$server = 'smtp.gmail.com';
$port = 587;
$username = 'your_email@gmail.com'; // replace with your email address
$password = 'your_password'; // replace with your password

// Verify code and user ID from URL parameters
$verifyCode = $_GET['verifyCode'];
$userId = $_GET['userId'];

try {
    // Connect to database
    $conn = new PDO('mysql:host=localhost;dbname=your_database', 'your_username', 'your_password');

    // Query to retrieve user data based on user ID and verify code
    $stmt = $conn->prepare("SELECT * FROM users WHERE id=:userId AND verifyCode=:verifyCode");
    $stmt->bindParam(':userId', $userId);
    $stmt->bindParam(':verifyCode', $verifyCode);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        // Retrieve user data
        $user = $stmt->fetch();

        // Prompt for new password
        echo 'Enter your new password: <input type="password" name="new_password"><br>';
        echo '<input type="submit" name="change_password">';

    } else {
        echo 'Invalid verify code or user ID';
    }

} catch (PDOException $e) {
    echo "Error connecting to database: " . $e->getMessage();
}

if (isset($_POST['change_password'])) {

    // Get new password from form
    $newPassword = $_POST['new_password'];

    try {
        // Update user data with new password
        $conn = new PDO('mysql:host=localhost;dbname=your_database', 'your_username', 'your_password');

        $stmt = $conn->prepare("UPDATE users SET password=:password WHERE id=:userId");
        $stmt->bindParam(':password', $newPassword);
        $stmt->bindParam(':userId', $userId);
        $stmt->execute();

        echo 'Your password has been changed successfully';

    } catch (PDOException $e) {
        echo "Error updating database: " . $e->getMessage();
    }
}

?>


function forgot_password($email, $username, $new_password) {
  // Connect to database
  $conn = mysqli_connect("localhost", "username", "password", "database");

  // Check if user exists
  $query = "SELECT * FROM users WHERE email='$email' AND username='$username'";
  $result = mysqli_query($conn, $query);
  if (mysqli_num_rows($result) == 0) {
    return array("error" => "User not found");
  }

  // Generate a reset password token
  $token = substr(hash('sha256', microtime(true)), 0, 32);

  // Update user record with reset password token
  $query = "UPDATE users SET password_reset_token='$token' WHERE email='$email'";
  mysqli_query($conn, $query);

  // Send email to user with instructions on how to reset their password
  $subject = "Reset Your Password";
  $body = "
    Dear $username,

    Click the following link to reset your password: <a href='http://yourwebsite.com/reset-password?token=$token'>Reset Password</a>

    Best regards,
    Your Website Team";

  // Send email using PHPMailer or mail function
  send_email($email, $subject, $body);

  return array("success" => "Email sent with instructions on how to reset your password");
}


function send_email($to, $subject, $body) {
  // Use PHPMailer or mail function to send email
  if (isset($_ENV["SMTP_SERVER"])) {
    require_once 'PHPMailer/src/PHPMailer.php';
    require_once 'PHPMailer/src/SMTP.php';

    $mail = new PHPMailer\PHPMailer\PHPMailer();
    $mail->isSMTP();
    $mail->Host = $_ENV["SMTP_SERVER"];
    $mail->Username = $_ENV["SMTP_USERNAME"];
    $mail->Password = $_ENV["SMTP_PASSWORD"];
    $mail->Port = 587;
    $mail->SMTPAuth = true;

    $mail->setFrom($_ENV["FROM_EMAIL"], "Your Website Team");
    $mail->addAddress($to);
    $mail->Subject = $subject;
    $mail->Body = $body;

    if (!$mail->send()) {
      return array("error" => "Email not sent");
    }
  } else {
    mail($to, $subject, $body);
  }

  return array("success" => "Email sent");
}


function reset_password($token) {
  // Connect to database
  $conn = mysqli_connect("localhost", "username", "password", "database");

  // Check if token is valid
  $query = "SELECT * FROM users WHERE password_reset_token='$token'";
  $result = mysqli_query($conn, $query);
  if (mysqli_num_rows($result) == 0) {
    return array("error" => "Invalid token");
  }

  // Get user data from database
  $user_data = mysqli_fetch_assoc($result);

  // Update user record with new password
  $new_password_hash = password_hash($token, PASSWORD_DEFAULT);
  $query = "UPDATE users SET password='$new_password_hash' WHERE id='{$user_data['id']}'";
  mysqli_query($conn, $query);

  return array("success" => "Password reset successfully");
}


<form action="forgot-password.php" method="post">
  <input type="email" name="email" placeholder="Enter your email address">
  <input type="text" name="username" placeholder="Enter your username">
  <button type="submit">Forgot Password</button>
</form>

<?php
if (isset($_POST["email"]) && isset($_POST["username"])) {
  $result = forgot_password($_POST["email"], $_POST["username"]);
  if ($result["error"]) {
    echo "Error: " . $result["error"];
  } else {
    echo "Email sent with instructions on how to reset your password";
  }
}
?>


<?php
// Configuration
$secret_key = 'your_secret_key_here'; // Use a random secret key for security

function sendEmail($email, $token) {
  // Send an email with a link to reset password
  // This is a simplified example, you may want to use a dedicated email library or service
  echo "Sending email to $email...
";
  // mail($email, 'Reset Password', 'Click this link to reset your password: https://example.com/reset_password.php?token=' . urlencode($token));
}

// Check if form has been submitted
if (isset($_POST['submit'])) {
  // Get the user's email address
  $email = $_POST['email'];

  // Validate email address
  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo 'Invalid email address.';
    exit;
  }

  // Retrieve the user's data from the database
  try {
    $pdo = new PDO('mysql:host=localhost;dbname=your_database', 'your_username', 'your_password');
    $stmt = $pdo->prepare('SELECT id, reset_token FROM users WHERE email = :email');
    $stmt->bindParam(':email', $email);
    $stmt->execute();
    $user_data = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user_data) {
      echo 'No account found with that email address.';
      exit;
    }

    // Generate a random reset token
    $token = bin2hex(random_bytes(16));

    // Update the user's data in the database
    $pdo->beginTransaction();
    $stmt = $pdo->prepare('UPDATE users SET reset_token = :token, expires_at = NOW() + INTERVAL 1 HOUR WHERE id = :id');
    $stmt->bindParam(':id', $user_data['id']);
    $stmt->bindParam(':token', $token);
    $stmt->execute();
    $pdo->commit();

    // Send an email with a link to reset password
    sendEmail($email, $token);

  } catch (PDOException $e) {
    echo 'Error: ' . $e->getMessage();
  }
}

// Display the forgot password form
?>
<form action="" method="post">
  <label for="email">Email:</label>
  <input type="email" id="email" name="email"><br><br>
  <input type="submit" name="submit" value="Submit">
</form>


<?php
require_once 'db.php'; // assume db.php contains your database connection settings

function forgot_password() {
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $username = trim($_POST['username']);
        $email = trim($_POST['email']);

        // Validate input data
        if (empty($username) || empty($email)) {
            $_SESSION['error'] = 'Please enter both username and email';
            return;
        }

        // Query database to retrieve user's information
        $query = "SELECT * FROM users WHERE username = '$username' AND email = '$email'";
        $result = mysqli_query($conn, $query);

        if (mysqli_num_rows($result) == 1) {
            // Retrieve user's information
            $row = mysqli_fetch_assoc($result);
            $user_id = $row['id'];
            $password_reset_token = bin2hex(random_bytes(32));
            $expiration_date = date('Y-m-d H:i:s', strtotime('+30 minutes')); // set expiration time to 30 minutes

            // Update user's information in database
            $query = "UPDATE users SET password_reset_token = '$password_reset_token', expiration_date = '$expiration_date' WHERE id = '$user_id'";
            mysqli_query($conn, $query);

            // Send email with password reset link
            send_password_reset_email($email, $password_reset_token);
        } else {
            $_SESSION['error'] = 'Invalid username or email';
        }
    }

    // Handle email sending (optional)
    function send_password_reset_email($email, $token) {
        $subject = 'Reset your password';
        $message = '
            <p>Hello %username%,

You have requested to reset your password. Please click on the following link to proceed:

            <a href="http://example.com/reset-password/%token%">Reset Password</a>

            If you did not request this, please ignore this email.

            Best regards,
            Your website
        ';

        // Send email using PHPMailer or other email library (not shown here)
    }
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    forgot_password();
}
?>


<?php

require 'config.php'; // include database connection settings

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $email = $_POST['email'];
  
  if (empty($email)) {
    echo "Email is required";
    exit;
  }
  
  // validate email format
  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo "Invalid email address";
    exit;
  }
  
  $query = "SELECT * FROM users WHERE email = '$email'";
  $result = mysqli_query($conn, $query);
  
  if (mysqli_num_rows($result) > 0) {
    // user exists
    $user_data = mysqli_fetch_assoc($result);
    
    // generate a random password
    $new_password = substr(str_shuffle('0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ'), 0, 8);
    
    // update the user's password
    $query = "UPDATE users SET password_hash = '$new_password' WHERE email = '$email'";
    mysqli_query($conn, $query);
    
    // send a reset password email (not implemented here)
    // ...
    
    echo "Reset link sent to your email";
  } else {
    echo "Email not found in our records.";
  }
} else {
?>
  <form action="" method="post">
    Email: <input type="email" name="email"><br>
    <input type="submit" value="Send Reset Link">
  </form>
<?php
}


<?php

require 'config.php'; // include database connection settings

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $email = $_POST['email'];
  $new_password = $_POST['new_password'];
  
  if (empty($email) || empty($new_password)) {
    echo "Email and new password are required";
    exit;
  }
  
  // validate email format
  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo "Invalid email address";
    exit;
  }
  
  $query = "SELECT * FROM users WHERE email = '$email'";
  $result = mysqli_query($conn, $query);
  
  if (mysqli_num_rows($result) > 0) {
    // user exists
    $user_data = mysqli_fetch_assoc($result);
    
    // update the user's password
    $query = "UPDATE users SET password_hash = '$new_password' WHERE email = '$email'";
    mysqli_query($conn, $query);
    
    echo "Password updated successfully";
  } else {
    echo "Email not found in our records.";
  }
} else {
?>
  <form action="" method="post">
    Email: <input type="email" name="email"><br>
    New Password: <input type="password" name="new_password"><br>
    Confirm New Password: <input type="password" name="confirm_new_password"><br>
    <input type="submit" value="Reset Password">
  </form>
<?php
}


<?php

// database connection settings
$host = 'localhost';
$username = 'your_username';
$password = 'your_password';
$dbname = 'your_database_name';

$conn = mysqli_connect($host, $username, $password, $dbname);

if (!$conn) {
  die("Connection failed: " . mysqli_connect_error());
}


<?php

// Configuration
define('DB_HOST', 'your_host');
define('DB_USER', 'your_user');
define('DB_PASSWORD', 'your_password');
define('DB_NAME', 'your_database');

// Connect to database
$conn = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get user input
$username = $_POST['username'];
$email = $_POST['email'];

// Check if username and email exist in database
$stmt = $conn->prepare("SELECT * FROM users WHERE username = ? AND email = ?");
$stmt->bind_param('ss', $username, $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    // User exists, generate new password and send reset link
    $row = $result->fetch_assoc();
    
    // Generate new password
    $new_password = substr(md5(uniqid(mt_rand(), true)), 0, 10);
    
    // Update user password in database
    $update_stmt = $conn->prepare("UPDATE users SET password_hash = ? WHERE id = ?");
    $update_stmt->bind_param('si', md5($new_password), $row['id']);
    $update_stmt->execute();
    
    // Send reset link via email (this example uses PHPMailer)
    require_once 'PHPMailerAutoload.php';
    $mail = new PHPMailer;
    $mail->isSMTP();
    $mail->Host = 'smtp.example.com';
    $mail->Port = 587;
    $mail->SMTPAuth = true;
    $mail->Username = 'your_email@example.com';
    $mail->Password = 'your_password';
    $mail->setFrom('your_email@example.com', 'Your Name');
    $mail->addAddress($email);
    $mail->Subject = 'Reset Password';
    $mail->Body = 'Click here to reset your password: <a href="https://example.com/reset-password.php?username=' . urlencode($username) . '&token=' . md5($new_password) . '">Reset Password</a>';
    
    if (!$mail->send()) {
        echo 'Message could not be sent.';
        echo 'Mailer Error: ' . $mail->ErrorInfo;
    } else {
        echo 'Email sent successfully!';
    }
} else {
    echo 'Username or email does not exist in our database.';
}

// Close connection
$conn->close();

?>


<?php

// Configuration
define('DB_HOST', 'your_host');
define('DB_USER', 'your_user');
define('DB_PASSWORD', 'your_password');
define('DB_NAME', 'your_database');

// Connect to database
$conn = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get user input
$username = $_GET['username'];
$token = $_GET['token'];

// Check if username and token exist in database
$stmt = $conn->prepare("SELECT * FROM users WHERE username = ? AND password_hash = ?");
$stmt->bind_param('ss', $username, md5($token));
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    // User exists, update new password
    $row = $result->fetch_assoc();
    
    // Update user password in database
    $update_stmt = $conn->prepare("UPDATE users SET password_hash = ? WHERE id = ?");
    $update_stmt->bind_param('si', md5($_POST['new_password']), $row['id']);
    $update_stmt->execute();
    
    echo 'Password updated successfully!';
} else {
    echo 'Username or token does not exist in our database.';
}

// Close connection
$conn->close();

?>


<?php

// Configuration settings
define('SITE_EMAIL', 'your_email@example.com');
define('SITE_NAME', 'Your Website Name');

// Check if form has been submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get user input data
    $email = $_POST['email'];

    // Validate email address
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "Invalid email address.";
        exit;
    }

    // Check if user exists in database
    require_once 'db.php';
    $query = "SELECT * FROM users WHERE email = '$email'";
    $result = mysqli_query($conn, $query);
    if (mysqli_num_rows($result) == 0) {
        echo "Email not found.";
        exit;
    }

    // Generate a random password
    $new_password = substr(md5(uniqid(mt_rand(), true)), 0, 8);

    // Update user's password in database
    $query = "UPDATE users SET password = '$new_password' WHERE email = '$email'";
    mysqli_query($conn, $query);

    // Send email with new password to user
    $subject = 'Your new password for ' . SITE_NAME;
    $message = '
        Dear User,
        
        Your new password is: ' . $new_password . '

        Best regards,
        ' . SITE_NAME;
    $headers = "From: " . SITE_EMAIL;
    mail($email, $subject, $message, $headers);

    echo "New password has been sent to your email.";
} else {
    ?>
    <h1>Forgot Password</h1>
    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
        <label for="email">Email:</label>
        <input type="text" id="email" name="email" required>
        <button type="submit">Submit</button>
    </form>
    <?php
}
?>


<?php

// Database connection settings
$conn = mysqli_connect("localhost", "username", "password", "database");

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

?>


<?php

// Configuration settings
$smtp_server = 'your_smtp_server';
$smtp_port = 587;
$from_email = 'your_email@example.com';
$password_recovery_url = 'https://example.com/password-recovery';

// Verify the email is not empty and contains a valid email address
if (!isset($_POST['email']) || !filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
    echo json_encode(array('error' => 'Invalid email address'));
    exit;
}

$email = $_POST['email'];

// Retrieve user data from database (e.g., MySQL)
$user_data_query = "SELECT * FROM users WHERE email = '$email'";
$result = mysqli_query($conn, $user_data_query);

if (mysqli_num_rows($result) == 0) {
    echo json_encode(array('error' => 'Email address not found'));
    exit;
}

$user_id = mysqli_fetch_assoc($result)['id'];

// Generate a random password reset token
$password_reset_token = bin2hex(random_bytes(32));

// Update user data with the password reset token in database (e.g., MySQL)
$query = "UPDATE users SET password_reset_token = '$password_reset_token' WHERE id = '$user_id'";
mysqli_query($conn, $query);

// Send an email to the user with a link to reset their password
$subject = 'Password Recovery';
$message = '
Dear '. $_POST['email'] .',

Please click on the following link to reset your password:
' . $password_recovery_url . '?token=' . $password_reset_token;

$headers = 'From: ' . $from_email . "\r
" .
    'Reply-To: ' . $from_email . "\r
" .
    'Content-Type: text/html; charset=UTF-8\r
';

mail($email, $subject, $message, $headers);

echo json_encode(array('success' => 'Email sent with password recovery link'));

?>


// Forgot Password Function

function forgot_password($username) {
  // Get user data from the database
  $user = get_user_by_username($username);

  if (!$user) {
    return array('error' => 'Username not found');
  }

  // Generate a reset token
  $reset_token = bin2hex(random_bytes(16));

  // Update user data with reset token in the database
  update_user_with_reset_token($user['id'], $reset_token);

  // Send password reset email to the user's email address
  send_password_reset_email($user, $reset_token);
}

// Get user by username function

function get_user_by_username($username) {
  global $db;
  $query = "SELECT * FROM users WHERE username = '$username'";
  $result = mysqli_query($db, $query);

  if (mysqli_num_rows($result) > 0) {
    return mysqli_fetch_assoc($result);
  }

  return false;
}

// Update user with reset token function

function update_user_with_reset_token($user_id, $reset_token) {
  global $db;
  $query = "UPDATE users SET password_reset_token = '$reset_token' WHERE id = '$user_id'";
  mysqli_query($db, $query);
}

// Send password reset email function

function send_password_reset_email($user, $reset_token) {
  $to = $user['email'];
  $subject = 'Password Reset';
  $body = "Please click on the following link to reset your password: <a href='http://example.com/reset-password?token=" . $reset_token . "'>Reset Password</a>";

  mail($to, $subject, $body);
}

// Check if the user has a valid reset token function

function check_reset_token($token) {
  global $db;
  $query = "SELECT * FROM users WHERE password_reset_token = '$token'";
  $result = mysqli_query($db, $query);

  if (mysqli_num_rows($result) > 0) {
    return true; // User has a valid reset token
  }

  return false;
}

// Reset user's password function

function reset_password($token, $new_password) {
  global $db;

  if (!check_reset_token($token)) {
    return array('error' => 'Invalid or expired reset token');
  }

  // Update user data with new password in the database
  update_user_with_new_password($new_password);
}


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $token = $_GET['token'];
  $new_password = $_POST['new_password'];

  if ($token && !empty($new_password)) {
    reset_password($token, $new_password);
  } else {
    echo 'Invalid or expired reset token';
  }
}


<?php

// Configuration constants
define('RESET_TOKEN_LIFETIME', 3600); // Token is valid for an hour
define('SMTP_SERVER', 'smtp.example.com'); // Your SMTP server address
define('SMTP_PORT', 587);
define('FROM_EMAIL', 'your_email@example.com');
define('FROM_NAME', 'Your Website Name');

// Email configuration for sending reset links
function sendEmail($to, $subject, $body) {
    $config = [
        'host' => SMTP_SERVER,
        'port' => SMTP_PORT,
        'user' => FROM_EMAIL,
        'password' => 'your_email_password',
        'crypt' => 'ssl'
    ];

    // Sending email
    if (mail($to, $subject, $body, "From: $FROM_NAME <$FROM_EMAIL>")) {
        return true;
    } else {
        error_log("Error sending email to $to");
        return false;
    }
}

// Forgot Password Functionality
function forgotPassword() {
    global $conn; // Assuming you're using a global connection object

    if (isset($_POST['email'])) {
        $email = $_POST['email'];
        
        // Validate Email
        $query = "SELECT * FROM users WHERE email = '$email'";
        $result = mysqli_query($conn, $query);
        
        if ($row = mysqli_fetch_assoc($result)) {
            // Generate a reset token
            $resetToken = substr(bin2hex(random_bytes(32)), 0, 32);
            $updateQuery = "UPDATE users SET reset_token = '$resetToken' WHERE email = '$email'";
            
            if (mysqli_query($conn, $updateQuery)) {
                // Create and send the password reset link
                $link = "http://".$_SERVER['HTTP_HOST']."/reset_password.php?token=$resetToken";
                $subject = 'Password Reset Link';
                $body = "
                    Dear $email,
                    
                    You are receiving this email because you have requested a password reset for your account.
                    
                    Please click on the following link to reset your password: $link
                    
                    Best regards,
                    Your Website Name
                ";
                
                if (sendEmail($email, $subject, $body)) {
                    echo 'A password reset link has been sent to your email.';
                } else {
                    echo 'Error sending password reset link.';
                }
            } else {
                error_log("Error updating user record");
            }
        } else {
            echo 'The provided email is not in our records or may be incorrect.';
        }
    } else {
        if (isset($_GET['token'])) {
            $resetToken = $_GET['token'];
            
            // Check if token is valid and has been used
            $query = "SELECT * FROM users WHERE reset_token = '$resetToken'";
            $result = mysqli_query($conn, $query);
            
            if ($row = mysqli_fetch_assoc($result)) {
                // If the token hasn't expired yet, allow user to change their password
                if (strtotime($row['created_at']) + RESET_TOKEN_LIFETIME >= time()) {
                    echo "Please enter your new password below.";
                    
                    // Form for updating password
                    ?>
                    <form action="" method="post">
                        <input type="password" name="new_password">
                        <input type="submit" value="Change Password">
                    </form>
                    
                    <?php
                    
                    if (isset($_POST['new_password'])) {
                        $newPassword = $_POST['new_password'];
                        
                        // Update the user's password
                        $updateQuery = "UPDATE users SET reset_token = NULL, password_hash = '$newPassword' WHERE email = '".$row['email']."'";
                        
                        if (mysqli_query($conn, $updateQuery)) {
                            echo 'Your password has been successfully changed.';
                        } else {
                            error_log("Error updating user record");
                        }
                    }
                } else {
                    echo "The provided reset token is expired or invalid.";
                }
            } else {
                echo "The provided reset token is invalid or has not been used yet.";
            }
        }
    }
}

// Initial Setup
if (isset($_POST['email'])) {
    forgotPassword();
}
?>


<?php

require_once 'db_config.php'; // assuming you have a db_config.php file for database connection settings

// Check if form has been submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];

    // Validate email address
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo 'Invalid email address';
        exit;
    }

    // Retrieve user data from database
    $query = "SELECT * FROM users WHERE email = '$email'";
    $result = mysqli_query($db_connection, $query);

    if (mysqli_num_rows($result) == 1) {
        $user_data = mysqli_fetch_assoc($result);

        // Generate a new password reset token
        $token = bin2hex(random_bytes(32));

        // Update user data with new token
        $query = "UPDATE users SET password_reset_token = '$token' WHERE email = '$email'";
        mysqli_query($db_connection, $query);

        // Send email to user with password reset link
        $subject = 'Password Reset Link';
        $message = '
            <p>Click the following link to reset your password:</p>
            <p><a href="' . $_SERVER['HTTP_HOST'] . '/reset_password.php?token=' . $token . '">Reset Password</a></p>
        ';
        mail($email, $subject, $message);

        echo 'Password reset email sent';
    } else {
        echo 'Email address not found in database';
    }
}

?>

<form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
    <label for="email">Email Address:</label>
    <input type="email" id="email" name="email">
    <button type="submit">Send Password Reset Email</button>
</form>


<?php

require_once 'db_config.php';

// Check if form has been submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $token = $_POST['token'];
    $new_password = $_POST['new_password'];

    // Validate token and new password
    if (!isset($token) || !isset($new_password)) {
        echo 'Invalid request';
        exit;
    }

    // Retrieve user data from database using token
    $query = "SELECT * FROM users WHERE password_reset_token = '$token'";
    $result = mysqli_query($db_connection, $query);

    if (mysqli_num_rows($result) == 1) {
        $user_data = mysqli_fetch_assoc($result);

        // Update user data with new password
        $new_password_hash = password_hash($new_password, PASSWORD_DEFAULT);
        $query = "UPDATE users SET password_hash = '$new_password_hash', password_reset_token = NULL WHERE email = '$user_data[email]'";
        mysqli_query($db_connection, $query);

        echo 'Password reset successfully';
    } else {
        echo 'Invalid token';
    }
}

?>

<form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
    <label for="token">Token:</label>
    <input type="text" id="token" name="token">
    <label for="new_password">New Password:</label>
    <input type="password" id="new_password" name="new_password">
    <button type="submit">Reset Password</button>
</form>


<?php

// Configuration
$config = array(
  'email_from' => 'your-email@example.com',
  'email_to' => '',
  'smtp_host' => 'your-smtp-host',
  'smtp_port' => '587'
);

function forgot_password($email) {
  // Get user data from database
  $user_data = get_user_by_email($email);
  
  if (!$user_data) {
    return false;
  }
  
  // Generate reset token and expiration time
  $reset_token = bin2hex(random_bytes(32));
  $reset_token_expires = date('Y-m-d H:i:s', strtotime('+1 hour'));
  
  // Update user data in database with new reset token and expiration time
  update_user_data($user_data['id'], 'reset_token', $reset_token);
  update_user_data($user_data['id'], 'reset_token_expires', $reset_token_expires);
  
  // Send email to user with reset link
  send_reset_email($email, $reset_token);
}

function get_user_by_email($email) {
  global $config;
  
  $db = new PDO('mysql:host=localhost;dbname=your-database', 'your-username', 'your-password');
  $stmt = $db->prepare("SELECT * FROM users WHERE email = ?");
  $stmt->execute(array($email));
  return $stmt->fetch();
}

function update_user_data($id, $field, $value) {
  global $config;
  
  $db = new PDO('mysql:host=localhost;dbname=your-database', 'your-username', 'your-password');
  $stmt = $db->prepare("UPDATE users SET `$field` = ? WHERE id = ?");
  $stmt->execute(array($value, $id));
}

function send_reset_email($email, $reset_token) {
  global $config;
  
  $headers = "From: {$config['email_from']}\r
";
  $headers .= "Content-Type: text/html; charset=UTF-8\r
";
  
  $message = "
    <h1>Reset Password</h1>
    <p>Please click on the following link to reset your password:</p>
    <a href='http://your-website.com/reset-password.php?token={$reset_token}'>Reset Password</a>
  ";
  
  mail($email, 'Reset Password', $message, $headers);
}

// Example usage:
$email = 'example@example.com';
forgot_password($email);

?>


<?php

// Configuration
$config = array(
  'token_cookie_name' => 'reset_token'
);

function verify_reset_token($token) {
  global $config;
  
  // Get user data from database using reset token
  $user_data = get_user_by_reset_token($token);
  
  if (!$user_data) {
    return false;
  }
  
  // Check expiration time
  if (strtotime($user_data['reset_token_expires']) < strtotime('now')) {
    return false;
  }
  
  return $user_data;
}

function get_user_by_reset_token($token) {
  global $config;
  
  $db = new PDO('mysql:host=localhost;dbname=your-database', 'your-username', 'your-password');
  $stmt = $db->prepare("SELECT * FROM users WHERE reset_token = ?");
  $stmt->execute(array($token));
  return $stmt->fetch();
}

function get_user_data_by_reset_token($token) {
  global $config;
  
  // Get user data from database using reset token
  $user_data = verify_reset_token($token);
  
  if (!$user_data) {
    return null;
  }
  
  return $user_data;
}

// Example usage:
$token = $_GET['token'];
$user_data = get_user_data_by_reset_token($token);

if ($user_data) {
  // Display password reset form
  echo '
    <h1>Reset Password</h1>
    <form action="reset-password-post.php" method="post">
      <input type="password" name="new_password">
      <input type="submit" value="Reset Password">
    </form>
  ';
} else {
  // Display error message
  echo '<p>Error: Invalid reset token.</p>';
}

?>


<?php

// Configuration
$config = array(
  'token_cookie_name' => 'reset_token'
);

function verify_reset_token($token) {
  global $config;
  
  // Get user data from database using reset token
  $user_data = get_user_by_reset_token($token);
  
  if (!$user_data) {
    return false;
  }
  
  // Check expiration time
  if (strtotime($user_data['reset_token_expires']) < strtotime('now')) {
    return false;
  }
  
  return $user_data;
}

function update_password($id, $new_password_hash) {
  global $config;
  
  $db = new PDO('mysql:host=localhost;dbname=your-database', 'your-username', 'your-password');
  $stmt = $db->prepare("UPDATE users SET password_hash = ? WHERE id = ?");
  $stmt->execute(array($new_password_hash, $id));
}

// Example usage:
$token = $_GET['token'];
$new_password = $_POST['new_password'];

$user_data = verify_reset_token($token);

if ($user_data) {
  // Update user's password
  update_password($user_data['id'], password_hash($new_password, PASSWORD_DEFAULT));
  
  // Display success message
  echo '<p>Password updated successfully.</p>';
} else {
  // Display error message
  echo '<p>Error: Invalid reset token.</p>';
}

?>


<?php

// Configuration
define('DB_HOST', 'your_host');
define('DB_USER', 'your_user');
define('DB_PASSWORD', 'your_password');
define('DB_NAME', 'your_database');

require_once 'db-connection.php'; // Establishes database connection

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $email = trim($_POST['email']);

  if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid email address']);
    exit;
  }

  // Retrieve user ID from database
  $query = "SELECT id FROM users WHERE email = ?";
  $stmt = $mysqli->prepare($query);
  $stmt->bind_param('s', $email);
  $stmt->execute();
  $result = $stmt->get_result();

  if ($result->num_rows === 0) {
    http_response_code(400);
    echo json_encode(['error' => 'Email address not found']);
    exit;
  }

  // Generate password reset token
  $token = bin2hex(random_bytes(32));
  $query = "INSERT INTO reset_passwords (token, user_id) VALUES (?, ?)";
  $stmt = $mysqli->prepare($query);
  $stmt->bind_param('si', $token, $user_id);
  $stmt->execute();

  // Send email with password reset link
  $to = $email;
  $subject = 'Password Reset Request';
  $body = "Please click the following link to reset your password:

";
  $body .= '<a href="http://your-site.com/reset-password.php?token=' . $token . '">Reset Password</a>';

  mail($to, $subject, $body);

  echo json_encode(['message' => 'Password reset email sent']);
}
?>


<?php

// Configuration
define('DB_HOST', 'your_host');
define('DB_USER', 'your_user');
define('DB_PASSWORD', 'your_password');
define('DB_NAME', 'your_database');

require_once 'db-connection.php'; // Establishes database connection

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $token = trim($_POST['token']);

  if (empty($token)) {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid token']);
    exit;
  }

  // Retrieve user ID from reset_passwords table
  $query = "SELECT user_id FROM reset_passwords WHERE token = ?";
  $stmt = $mysqli->prepare($query);
  $stmt->bind_param('s', $token);
  $stmt->execute();
  $result = $stmt->get_result();

  if ($result->num_rows === 0) {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid token']);
    exit;
  }

  // Get user ID
  $user_id = $result->fetch_assoc()['user_id'];

  // Update password (for demonstration purposes only)
  $new_password = 'new-password';
  $query = "UPDATE users SET password_hash = ? WHERE id = ?";
  $stmt = $mysqli->prepare($query);
  $stmt->bind_param('si', sha256($new_password), $user_id);
  $stmt->execute();

  echo json_encode(['message' => 'Password updated successfully']);
}
?>


<?php

// Configuration
define('PASSWORD_RESET_LINK_EXPIRE', 3600); // expire in 1 hour
define('PASSWORD_RESET_LINK_SECRET', 'your-secret-key'); // secret key for token generation

// Forgot Password Function
function forgot_password($email) {
    // Get user data from database
    $user = get_user_by_email($email);

    if ($user === false) {
        return array('error' => 'User not found');
    }

    // Generate a password reset link
    $token = generate_reset_token($user->id, PASSWORD_RESET_LINK_EXPIRE);
    $reset_link = $_SERVER['HTTP_HOST'] . '/password-reset/' . $token;

    // Send email with password reset link
    send_password_reset_email($email, $reset_link);

    return array('success' => 'Password reset link sent to your email');
}

// Get user data by email function
function get_user_by_email($email) {
    // Query database to retrieve user data
    $query = "SELECT * FROM users WHERE email = '$email'";
    $result = mysqli_query($conn, $query);

    if ($result === false) {
        return false;
    }

    return $result->fetch_assoc();
}

// Generate password reset token function
function generate_reset_token($user_id, $expire_in) {
    // Use a secret key and the user ID to generate a unique token
    $token = hash_hmac('sha256', $user_id . time(), PASSWORD_RESET_LINK_SECRET);
    return $token;
}

// Send password reset email function
function send_password_reset_email($email, $reset_link) {
    // Set up email headers and body
    $subject = 'Reset Your Password';
    $body = "Click the link below to reset your password:

" . $reset_link;

    // Send email using PHP's mail() function or a library like SwiftMailer
    // ...
}

// Handle password reset request (e.g. via POST)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $result = forgot_password($email);

    if ($result['success']) {
        echo json_encode(array('message' => 'Password reset link sent to your email'));
    } else {
        echo json_encode($result);
    }
}
?>


// Forgot Password Function
function forgot_password($email) {
  // Connect to database
  $conn = mysqli_connect("localhost", "username", "password", "database_name");

  // Check if email exists in database
  $query = "SELECT * FROM users WHERE email = '$email'";
  $result = mysqli_query($conn, $query);
  $num_rows = mysqli_num_rows($result);

  if ($num_rows == 0) {
    echo "Email not found.";
    return false;
  }

  // Generate a random password
  $password = substr(md5(uniqid(rand(), true)), 0, 8);
  $password_hash = hash('sha256', $password);

  // Update user's password in database
  $query = "UPDATE users SET password_hash = '$password_hash' WHERE email = '$email'";
  mysqli_query($conn, $query);

  // Send email with reset link
  send_reset_email($email, $password);
}

// Function to send reset email
function send_reset_email($email, $password) {
  $subject = "Reset Password";
  $body = "Click here to reset your password: <a href='http://example.com/reset_password?email=$email&password=$password'>Reset Password</a>";
  mail($email, $subject, $body);
}


// Reset Password Function
function reset_password() {
  // Check if user has submitted form data
  if (!isset($_POST['email']) || !isset($_POST['password'])) {
    echo "Invalid request.";
    return false;
  }

  // Connect to database
  $conn = mysqli_connect("localhost", "username", "password", "database_name");

  // Get email and password from form data
  $email = $_POST['email'];
  $password = $_POST['password'];

  // Check if user's email matches the one in the reset link
  $query = "SELECT * FROM users WHERE email = '$email'";
  $result = mysqli_query($conn, $query);
  $num_rows = mysqli_num_rows($result);

  if ($num_rows == 0) {
    echo "Email not found.";
    return false;
  }

  // Update user's password in database
  $query = "UPDATE users SET password_hash = '$password' WHERE email = '$email'";
  mysqli_query($conn, $query);
}


// Example use case: forgot password form
echo "<form action='forget_password.php' method='post'>";
echo "Email: <input type='text' name='email'>";
echo "<button type='submit'>Submit</button>";
echo "</form>";

if (isset($_POST['email'])) {
  $email = $_POST['email'];
  forgot_password($email);
}


<?php

// Configuration
define('DB_HOST', 'localhost');
define('DB_USERNAME', 'your_username');
define('DB_PASSWORD', 'your_password');
define('DB_NAME', 'your_database');

// Connect to database
$conn = new mysqli(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Process form submission (forgot password)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Get user input
    $username = $_POST['username'];
    $email = $_POST['email'];

    // Validate user input
    if (!isset($username) || !isset($email)) {
        echo "Error: Username and email are required.";
        exit;
    }

    // Query database to retrieve user data
    $query = "SELECT * FROM users WHERE username = '$username' AND email = '$email'";
    $result = $conn->query($query);

    if ($result->num_rows > 0) {

        // Retrieve user data from result set
        $row = $result->fetch_assoc();

        // Generate a random password reset token
        $token = bin2hex(random_bytes(32));
        $expires_in = time() + (60 * 60 * 24); // expires in 1 day

        // Insert token into database (for security, store token separately from user data)
        $query = "INSERT INTO password_reset_tokens (username, token, expires_at) VALUES ('$username', '$token', '$expires_in')";
        $conn->query($query);

        // Send email with password reset link
        sendPasswordResetEmail($row['email'], $token);
    } else {
        echo "Error: Username or email not found.";
    }
}

// Close database connection
$conn->close();

// Helper function to send password reset email
function sendPasswordResetEmail($email, $token) {
    // Your email sending logic goes here (e.g., using PHPMailer)
    // For demonstration purposes, we'll use a simple echo statement
    echo "Sending email to $email with password reset token: $token
";
}

?>


<?php
require_once 'config.php'; // configuration file with database credentials

function sendResetEmail($username, $email) {
  // Create reset link with token
  $token = bin2hex(random_bytes(32));
  $resetLink = "http://example.com/reset-password/$token";

  // Send email using PHPMailer or a similar library
  $mail->setFrom('no-reply@example.com', 'Password Reset');
  $mail->addAddress($email);
  $mail->Subject = 'Reset Password';
  $mail->Body = 'Click the link below to reset your password: <a href="' . $resetLink . '">Reset Password</a>';
  $mail->send();

  // Store token in database
  $sql = "INSERT INTO password_resets (username, token) VALUES (:username, :token)";
  $stmt = $db->prepare($sql);
  $stmt->bindParam(':username', $username);
  $stmt->bindParam(':token', $token);
  $stmt->execute();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $email = $_POST['email'];
  $username = $_POST['username'];

  // Check if user exists
  $sql = "SELECT * FROM users WHERE email = :email AND username = :username";
  $stmt = $db->prepare($sql);
  $stmt->bindParam(':email', $email);
  $stmt->bindParam(':username', $username);
  $stmt->execute();
  $user = $stmt->fetch();

  if ($user) {
    // Send reset email
    sendResetEmail($username, $email);

    echo 'Password reset link sent to your email.';
  } else {
    echo 'Username or email not found.';
  }
} else {
?>
<form method="post">
  <label for="email">Email:</label>
  <input type="email" id="email" name="email"><br><br>
  <label for="username">Username:</label>
  <input type="text" id="username" name="username"><br><br>
  <button type="submit">Send Reset Link</button>
</form>
<?php
}
?>


<?php
require_once 'config.php';

function resetPassword($token) {
  // Check if token exists in database
  $sql = "SELECT * FROM password_resets WHERE token = :token";
  $stmt = $db->prepare($sql);
  $stmt->bindParam(':token', $token);
  $stmt->execute();
  $reset = $stmt->fetch();

  if ($reset) {
    // Prompt user to enter new password
    echo 'Enter your new password:<br>';
    $newPassword = $_POST['new_password'];

    // Hash and store new password in database
    $sql = "UPDATE users SET password_hash = :password_hash WHERE id = :id";
    $stmt = $db->prepare($sql);
    $stmt->bindParam(':password_hash', password_hash($newPassword, PASSWORD_DEFAULT));
    $stmt->bindParam(':id', $reset['user_id']);
    $stmt->execute();

    // Delete reset token from database
    $sql = "DELETE FROM password_resets WHERE token = :token";
    $stmt = $db->prepare($sql);
    $stmt->bindParam(':token', $token);
    $stmt->execute();

    echo 'Password reset successfully!';
  } else {
    echo 'Invalid token.';
  }
}

if (isset($_GET['token'])) {
  $token = $_GET['token'];
  resetPassword($token);
} else {
?>
<form method="post">
  <label for="new_password">New Password:</label>
  <input type="password" id="new_password" name="new_password"><br><br>
  <button type="submit">Reset Password</button>
</form>
<?php
}
?>


// forgot_password.php

require_once 'db_connect.php'; // database connection script

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $email = $_POST['email'];

    // Validate input
    if (empty($username) || empty($email)) {
        echo 'Error: Username and email are required.';
        exit;
    }

    // Query database to retrieve user data
    $query = "SELECT * FROM users WHERE username = '$username' AND email = '$email'";
    $result = mysqli_query($conn, $query);

    if ($result->num_rows == 0) {
        echo 'Error: User not found.';
        exit;
    }

    // Generate a random password
    $password = substr(md5(uniqid()), 0, 8);

    // Update user password in database
    $query = "UPDATE users SET password = '$password' WHERE username = '$username'";
    mysqli_query($conn, $query);

    // Send email with reset link
    send_email($email, $password);
}

function send_email($to_email, $password) {
    $subject = 'Password Reset';
    $body = "Dear user,

Your new password is: $password

Best regards,
[Your Application Name]";

    // Send email using your preferred method (e.g., PHPMailer, mail())
    mail($to_email, $subject, $body);
}

// Display forgot password form
echo '<form action="' . $_SERVER['PHP_SELF'] . '" method="post">';
echo 'Enter Username: <input type="text" name="username"><br>';
echo 'Enter Email: <input type="email" name="email"><br>';
echo '<button type="submit">Submit</button>';
echo '</form>';

?>


<?php

// Configuration
$db_host = 'localhost';
$db_username = 'your_username';
$db_password = 'your_password';
$db_name = 'your_database';

// Connect to database
$conn = new mysqli($db_host, $db_username, $db_password, $db_name);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Function to check if user exists and send reset link
function forgotPassword() {
  global $conn;

  // Get form data
  $email = $_POST['email'];

  // Check if email is valid
  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo "Invalid email";
    return;
  }

  // Query database to get user's ID and password
  $query = "SELECT id, password FROM users WHERE email = '$email'";
  $result = $conn->query($query);

  // Check if user exists
  if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $userId = $row['id'];
    $password = $row['password'];

    // Generate reset link (very basic example, not secure!)
    $resetLink = "http://yourwebsite.com/reset-password.php?email=$email&token=123456";

    // Send email with reset link
    sendEmail($email, "Reset Password", $resetLink);
  } else {
    echo "User does not exist";
  }
}

// Function to send email using PHPMailer (not included in this example)
function sendEmail($to, $subject, $message) {
  // Create a new instance of PHPMailer
  require_once 'PHPMailer/PHPMailer.php';
  $mail = new PHPMailer\PHPMailer\PHPMailer();

  // Set up SMTP settings
  $mail->isSMTP();
  $mail->Host = 'smtp.yourwebsite.com';
  $mail->Port = 587;
  $mail->SMTPAuth = true;
  $mail->Username = 'your_email@yourwebsite.com';
  $mail->Password = 'your_password';

  // Set up email content
  $mail->setFrom('your_email@yourwebsite.com', 'Your Name');
  $mail->addAddress($to);
  $mail->Subject = $subject;
  $mail->Body = $message;

  if (!$mail->send()) {
    echo "Email not sent: " . $mail->ErrorInfo;
  }
}

// Handle form submission
if (isset($_POST['submit'])) {
  forgotPassword();
} else {
  // Display form
  ?>
  <form action="<?php $_SERVER['PHP_SELF']; ?>" method="post">
    <label for="email">Email:</label>
    <input type="text" name="email"><br><br>
    <input type="submit" name="submit" value="Submit">
  </form>
  <?php
}
?>


<?php

// Configuration
define('SITE_URL', 'https://example.com');

function forgot_password($email) {
  // Check if email is valid
  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    return array('error' => 'Invalid email address');
  }

  // Query database to check if user exists
  $conn = mysqli_connect("localhost", "username", "password", "database");
  if (!$conn) {
    die("Connection failed: " . mysqli_error($conn));
  }
  $query = "SELECT * FROM users WHERE email = '$email'";
  $result = mysqli_query($conn, $query);
  if (mysqli_num_rows($result) == 0) {
    // User does not exist
    return array('error' => 'User not found');
  }

  // Generate reset token and password reset link
  $token = bin2hex(random_bytes(16));
  $password_reset_link = SITE_URL . '/reset-password?token=' . $token;

  // Insert token into database for later verification
  $query = "INSERT INTO password_resets (email, token) VALUES ('$email', '$token')";
  mysqli_query($conn, $query);

  // Send email with password reset link
  $to = $email;
  $subject = 'Reset your password';
  $message = '
    <p>Dear user,</p>
    <p>To reset your password, please click the following link:</p>
    <a href="' . $password_reset_link . '">' . $password_reset_link . '</a>
  ';
  mail($to, $subject, $message);

  // Return success message
  return array('success' => 'Password reset email sent');
}

?>


$email = 'user@example.com';
$response = forgot_password($email);
echo json_encode($response); // Output: {"success": "Password reset email sent"}


<?php

// Configuration
require_once 'config.php';

// Check if form has been submitted
if (isset($_POST['submit'])) {
  // Get form data
  $email = $_POST['email'];

  // Query database to retrieve user's email address
  $query = "SELECT * FROM users WHERE email = '$email'";
  $result = mysqli_query($conn, $query);

  if ($result) {
    // If user exists, generate new password and send it via email
    while ($row = mysqli_fetch_assoc($result)) {
      $new_password = substr(md5(uniqid()), 0, 8);
      $query_update = "UPDATE users SET password_hash = '" . password_hash($new_password, PASSWORD_DEFAULT) . "' WHERE id = '$row[id]'";
      mysqli_query($conn, $query_update);

      // Send email with new password
      send_email($email, $new_password);
    }
  } else {
    echo 'Error: User not found';
  }

} else {
  ?>
  <html>
    <body>
      <h1>Forgot Password</h1>
      <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
        Email Address: <input type="email" name="email"><br><br>
        <input type="submit" value="Send New Password" name="submit">
      </form>
    </body>
  </html>
  <?php
}

// Function to send email with new password
function send_email($to, $password) {
  $subject = 'New Password';
  $message = 'Your new password is: ' . $password;
  mail($to, $subject, $message);
}

?>


<?php

// Include your database connection settings
require_once('config.php');

if (isset($_POST['submit'])) {
    // Sanitize input
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);

    // Check if email exists in database
    $query = "SELECT * FROM users WHERE email = '$email'";
    $result = mysqli_query($conn, $query);
    
    if (mysqli_num_rows($result) > 0) {
        // Generate a token and store it in the password_reset table
        $token = bin2hex(random_bytes(32));
        $insertQuery = "INSERT INTO password_reset SET email='$email', token='$token'";
        mysqli_query($conn, $insertQuery);

        // Send an email to the user with the link to reset their password
        sendPasswordResetEmail($email, $token);
        
        echo '<div class="alert alert-success">A password reset email has been sent to your email address.</div>';
    } else {
        echo '<div class="alert alert-danger">No account found with that email.</div>';
    }
}

function sendPasswordResetEmail($email, $token) {
    // Your SMTP settings
    $smtpServer = 'your-smtp-server';
    $smtpPort = 587;
    $fromEmail = 'your-email@gmail.com';
    $password = 'your-password';

    // Email content
    $subject = "Password Reset Link";
    $body = "
        Hi there,
        
        You are receiving this email because we received a password reset request for your account.
        
        To reset your password, please click on the following link: 
        <a href='http://your-website.com/reset-password.php?token=$token'>$token</a>
    ";

    // Send email using SMTP
    $headers = "From: $fromEmail\r
";
    $headers .= "Bcc: $email\r
";
    $headers .= "MIME-Version: 1.0\r
";
    $headers .= "Content-Type: text/html; charset=UTF-8\r
";
    mail($email, $subject, $body, $headers);
}
?>


<?php

// Include your database connection settings
require_once('config.php');

if (isset($_GET['token'])) {
    // Check if token exists in password_reset table
    $query = "SELECT * FROM password_reset WHERE token='".$_GET['token']."'";
    $result = mysqli_query($conn, $query);
    
    if (mysqli_num_rows($result) > 0) {
        // Get the email and user ID from the password_reset table
        $row = mysqli_fetch_assoc($result);
        $email = $row['email'];
        
        // Create a form to ask for new password
        echo '<form action="update-password.php" method="post">
            <label>Enter your new password:</label>
            <input type="password" name="new_password"><br><br>
            <label>Confirm your new password:</label>
            <input type="password" name="confirm_password"><br><br>
            <input type="submit" value="Reset Password">
        </form>';
    } else {
        echo '<div class="alert alert-danger">Invalid token.</div>';
    }
}
?>


<?php

// Include your database connection settings
require_once('config.php');

if (isset($_POST['new_password'])) {
    // Check if both passwords match
    if ($_POST['new_password'] == $_POST['confirm_password']) {
        // Sanitize input
        $new_password = password_hash($_POST['new_password'], PASSWORD_DEFAULT);
        
        // Update the user's password in database
        $updateQuery = "UPDATE users SET password='$new_password' WHERE email='".$_SESSION['email']."'";
        mysqli_query($conn, $updateQuery);
        
        echo '<div class="alert alert-success">Password updated successfully.</div>';
    } else {
        echo '<div class="alert alert-danger">Passwords do not match.</div>';
    }
}
?>


<?php

// Configuration variables
define('RESET_LINK', 'https://example.com/reset-password.php');
define('ADMIN_EMAIL', 'admin@example.com');

// Function to generate a random token
function generateToken() {
    return bin2hex(random_bytes(16));
}

// Function to send reset email
function sendResetEmail($email, $token) {
    // Email headers
    $headers = "From: Admin <" . ADMIN_EMAIL . ">" . "\r
";
    $headers .= "Content-Type: text/html; charset=utf-8\r
";

    // Email body
    $body = "
        <h1>Reset Your Password</h1>
        <p>Please click the link below to reset your password:</p>
        <a href='" . RESET_LINK . "?token=" . $token . "'>Reset Password</a>
    ";

    // Send email using mail() function
    mail($email, 'Reset Password', $body, $headers);
}

// Forgot password function
function forgotPassword($email) {
    // Generate a random token
    $token = generateToken();

    // Store the token in the database (replace with your own database functions)
    storeTokenInDatabase($email, $token);

    // Send reset email
    sendResetEmail($email, $token);
}

// Example usage:
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];

    if ($email) {
        forgotPassword($email);
        echo "An email has been sent to your account with a link to reset your password.";
    } else {
        echo "Invalid email address.";
    }
} else {
    ?>
    <h1>Forgot Password</h1>
    <form action="" method="post">
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required><br><br>
        <button type="submit">Submit</button>
    </form>
    <?php
}

?>


CREATE TABLE users (
  id INT PRIMARY KEY,
  email VARCHAR(255) NOT NULL,
  password_hash VARCHAR(255) NOT NULL
);


<?php

// Configuration
$db_host = 'localhost';
$db_username = 'your_username';
$db_password = 'your_password';
$db_name = 'your_database';

// Connect to database
$conn = new mysqli($db_host, $db_username, $db_password, $db_name);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Function to check if email exists in the database
function check_email_exists($email) {
  global $conn;
  $query = "SELECT * FROM users WHERE email = '$email'";
  $result = $conn->query($query);

  if ($result->num_rows > 0) {
    return true;
  } else {
    return false;
  }
}

// Function to generate a password reset token
function generate_token() {
  return bin2hex(random_bytes(32));
}

// Handle forgot password request
if (isset($_POST['forgot_password'])) {

  // Get email from form input
  $email = $_POST['email'];

  // Check if email exists in the database
  if (check_email_exists($email)) {

    // Generate a password reset token
    $token = generate_token();

    // Update user's token in the database
    $query = "UPDATE users SET password_reset_token = '$token' WHERE email = '$email'";
    $conn->query($query);

    // Send password reset email to user
    $subject = 'Password Reset Request';
    $body = 'Click on this link to reset your password: <a href="http://yourwebsite.com/reset_password.php?token=' . $token . '">Reset Password</a>';
    mail($email, $subject, $body);

    echo "Email sent with password reset link. Please check your inbox.";
  } else {
    echo "Email not found in the database.";
  }
}

// Close connection
$conn->close();

?>


<?php

// Configuration
$db_host = 'localhost';
$db_username = 'your_username';
$db_password = 'your_password';
$db_name = 'your_database';

// Connect to database
$conn = new mysqli($db_host, $db_username, $db_password, $db_name);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Function to update password in the database
function update_password($token, $new_password) {
  global $conn;
  $query = "UPDATE users SET password_hash = '$new_password', password_reset_token = NULL WHERE password_reset_token = '$token'";
  $conn->query($query);

  if ($conn->affected_rows == 1) {
    return true;
  } else {
    return false;
  }
}

// Handle password reset request
if (isset($_POST['reset_password'])) {

  // Get token and new password from form input
  $token = $_GET['token'];
  $new_password = $_POST['new_password'];

  // Check if token exists in the database
  $query = "SELECT * FROM users WHERE password_reset_token = '$token'";
  $result = $conn->query($query);

  if ($result->num_rows > 0) {

    // Update user's password in the database
    if (update_password($token, password_hash($new_password, PASSWORD_DEFAULT))) {
      echo "Password updated successfully.";
    } else {
      echo "Error updating password.";
    }
  } else {
    echo "Token not found in the database.";
  }
}

// Close connection
$conn->close();

?>


<?php

require_once 'vendor/autoload.php'; // Load PHPMailer library

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Configuration
$mail = new PHPMailer(true);

$mail->isSMTP();
$mail->Host = 'smtp.example.com';
$mail->Port = 587;
$mail->Username = 'your_email@example.com';
$mail->Password = 'your_password';

$mail->setFrom('your_email@example.com', 'Your Name');

// Function to send password reset link via email
function forgot_password($email) {
    global $mail;

    // Retrieve user data from database
    $query = "SELECT id, password_hash FROM users WHERE email = :email";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':email', $email);
    $stmt->execute();

    if ($user = $stmt->fetch()) {
        // Generate new password and hash
        $new_password = bin2hex(random_bytes(16));
        $new_password_hash = password_hash($new_password, PASSWORD_DEFAULT);

        // Update user data in database
        $query = "UPDATE users SET password_hash = :password_hash WHERE id = :id";
        $stmt = $db->prepare($query);
        $stmt->bindParam(':password_hash', $new_password_hash);
        $stmt->bindParam(':id', $user['id']);
        $stmt->execute();

        // Send email with password reset link
        $link = 'http://example.com/reset-password.php?id=' . $user['id'] . '&token=' . hash('sha256', $new_password);
        $mail->addAddress($email);
        $mail->Subject = 'Password Reset Link';
        $mail->Body = 'Click this link to reset your password: ' . $link;
        $mail->send();

        return $new_password; // Return new password
    } else {
        throw new Exception('User not found');
    }
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    try {
        $new_password = forgot_password($email);
        echo "A password reset link has been sent to your email.";
    } catch (Exception $e) {
        echo "Error: " . $e->getMessage();
    }
} else {
    // Display form
    ?>
    <form action="" method="post">
        <label>Email:</label>
        <input type="email" name="email" required>
        <button type="submit">Send Password Reset Link</button>
    </form>
    <?php
}


<?php

// Configuration
$db = new PDO('mysql:host=localhost;dbname=your_database', 'your_username', 'your_password');

// Retrieve user data from database using ID and token
$query = "SELECT * FROM users WHERE id = :id AND password_hash = :password_hash";
$stmt = $db->prepare($query);
$stmt->bindParam(':id', $_GET['id']);
$stmt->bindParam(':password_hash', hash('sha256', $_POST['token']));
$stmt->execute();

if ($user = $stmt->fetch()) {
    // Update user data in database
    $new_password = $_POST['new_password'];
    $new_password_hash = password_hash($new_password, PASSWORD_DEFAULT);
    $query = "UPDATE users SET password_hash = :password_hash WHERE id = :id";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':password_hash', $new_password_hash);
    $stmt->bindParam(':id', $user['id']);
    $stmt->execute();

    echo "Your password has been updated successfully.";
} else {
    throw new Exception('Invalid token');
}
?>
<form action="" method="post">
    <label>New Password:</label>
    <input type="password" name="new_password" required>
    <button type="submit">Update Password</button>
</form>


<?php

// Configuration
$db_host = 'your_database_host';
$db_username = 'your_database_username';
$db_password = 'your_database_password';
$db_name = 'your_database_name';

// Connect to database
$conn = new mysqli($db_host, $db_username, $db_password, $db_name);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Function to check if email exists in database
function emailExists($email) {
  global $conn;
  $query = "SELECT * FROM users WHERE email = '$email'";
  $result = $conn->query($query);
  return ($result->num_rows > 0);
}

// Function to send password reset email
function sendResetEmail($email, $passwordResetToken) {
  global $conn;
  $subject = 'Password Reset Request';
  $body = "Click the link below to reset your password:
http://yourwebsite.com/reset-password?token=$passwordResetToken";
  mail($email, $subject, $body);
}

// Function to update user's password
function updatePassword($email, $newPassword) {
  global $conn;
  $query = "UPDATE users SET password_hash = '$newPassword' WHERE email = '$email'";
  $conn->query($query);
}

// Handle forgot password form submission
if (isset($_POST['forgot_password'])) {
  $email = $_POST['email'];

  // Check if email exists in database
  if (emailExists($email)) {
    // Generate random password reset token
    $passwordResetToken = bin2hex(random_bytes(32));

    // Update user's password to be a temporary token
    updatePassword($email, $passwordResetToken);

    // Send password reset email
    sendResetEmail($email, $passwordResetToken);
  } else {
    echo "Email not found.";
  }
}

?>


<?php

// Configuration
$db_host = 'your_database_host';
$db_username = 'your_database_username';
$db_password = 'your_database_password';
$db_name = 'your_database_name';

// Connect to database
$conn = new mysqli($db_host, $db_username, $db_password, $db_name);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Function to verify password reset token
function verifyToken($token) {
  global $conn;
  $query = "SELECT * FROM users WHERE password_hash = '$token'";
  $result = $conn->query($query);
  return ($result->num_rows > 0);
}

// Handle password reset form submission
if (isset($_POST['reset_password'])) {
  $token = $_POST['token'];
  $newPassword = $_POST['new_password'];

  // Verify password reset token
  if (verifyToken($token)) {
    // Update user's password to new password
    updatePassword($email, $newPassword);
  } else {
    echo "Invalid token.";
  }
}

?>


<?php

// Include database connection settings
require_once 'db_config.php';

// Set error reporting to display any errors
error_reporting(E_ALL);

// Check if user submitted the form
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Form fields: email, token (for security)
    $email = $_POST['email'];
    $token = $_POST['token'];

    // Validate form data
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "Invalid email address";
        exit;
    }

    // Retrieve user's hashed password and reset token from database
    try {
        $sql = "SELECT id, password_reset_token FROM users WHERE email = :email";
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        $user_data = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user_data) {
            // Compare reset tokens
            if (hash('sha256', $_POST['token']) == $user_data['password_reset_token']) {
                // Update user's password and generate new reset token
                $new_password = hash('sha256', random_bytes(32));
                $new_token = hash('sha256', random_bytes(32));

                try {
                    $sql = "UPDATE users SET password = :new_password, password_reset_token = :new_token WHERE email = :email";
                    $stmt = $db->prepare($sql);
                    $stmt->bindParam(':new_password', $new_password);
                    $stmt->bindParam(':new_token', $new_token);
                    $stmt->bindParam(':email', $email);
                    $stmt->execute();

                    // Send password reset instructions via email
                    $subject = "Password Reset Instructions";
                    $body = "Dear user, your new password is: $new_password. Please log in with this password.";
                    mail($email, $subject, $body);

                    echo "Password updated successfully! New password sent to your email.";
                } catch (PDOException $e) {
                    echo "Error updating password: " . $e->getMessage();
                }
            } else {
                echo "Invalid reset token";
            }

        } else {
            echo "Email address not found in database";
        }

    } catch (PDOException $e) {
        echo "Database error: " . $e->getMessage();
    }

} // End of form submission check

?>


<?php

// Configuration
$db_host = 'localhost';
$db_username = 'username';
$db_password = 'password';
$db_name = 'database';

// Connect to database
$conn = mysqli_connect($db_host, $db_username, $db_password, $db_name);

if (!$conn) {
  die("Connection failed: " . mysqli_error($conn));
}

// Function to send email with reset link
function send_reset_email($email, $reset_token) {
  // Send an email to the user with a reset link
  $subject = 'Reset Your Password';
  $body = '<p>Please click on the following link to reset your password:</p><p><a href="' . site_url('reset_password', array('token' => $reset_token)) . '">Reset Password</a></p>';
  mail($email, $subject, $body);
}

// Function to create and store a reset token
function create_reset_token() {
  // Generate a random string for the reset token
  $reset_token = bin2hex(random_bytes(32));
  return $reset_token;
}

// Function to send email with reset link
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $email = $_POST['email'];
  $query = "SELECT * FROM users WHERE email = '$email'";
  $result = mysqli_query($conn, $query);
  if (mysqli_num_rows($result) > 0) {
    // Get the user's row
    $user_row = mysqli_fetch_assoc($result);

    // Create a reset token and store it in the database
    $reset_token = create_reset_token();
    $query = "UPDATE users SET reset_token = '$reset_token', reset_expires = NOW() + INTERVAL 1 HOUR WHERE email = '$email'";
    mysqli_query($conn, $query);

    // Send an email with a reset link
    send_reset_email($email, $reset_token);
    echo 'Email sent to <strong>' . $email . '</strong> with a password reset link. Please check your email.';
  } else {
    echo 'Email not found. Try again!';
  }
} else {
  // Display the form
?>

<form action="<?php echo site_url('forgot_password', array('method' => 'post')); ?>" method="post">
  <label for="email">Enter your email:</label>
  <input type="email" id="email" name="email" required>
  <button type="submit">Send Reset Link</button>
</form>

<?php
}
?>


<?php

// Configuration settings
define('DB_HOST', 'your_database_host');
define('DB_USERNAME', 'your_database_username');
define('DB_PASSWORD', 'your_database_password');
define('DB_NAME', 'your_database_name');

// Connect to database
$mysqli = new mysqli(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME);

if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

// Check if form has been submitted
if (isset($_POST['email'])) {

    // Get user's email from form data
    $email = $_POST['email'];

    // SQL query to retrieve user's information
    $query = "SELECT * FROM users WHERE email = '$email'";
    $result = $mysqli->query($query);

    if ($result->num_rows > 0) {

        // Retrieve user's information
        while ($row = $result->fetch_assoc()) {
            $user_id = $row['id'];
            $username = $row['username'];
            $email = $row['email'];

            // Generate a password reset token (random string)
            $token = substr(str_shuffle('abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789'), 0, 32);

            // Update user's information with new token
            $update_query = "UPDATE users SET token = '$token' WHERE id = '$user_id'";
            $mysqli->query($update_query);

            // Send email to user with password reset link
            $to = $email;
            $subject = 'Password Reset Link';
            $message = '<p>Click on the following link to reset your password:</p><a href="http://' . $_SERVER['HTTP_HOST'] . '/reset_password.php?token=' . $token . '">Reset Password</a>';
            mail($to, $subject, $message);

            echo 'A password reset email has been sent to your email address.';
        }
    } else {
        echo 'No user found with that email address.';
    }

} else {

    // Form not submitted, display form
    ?>

    <h1>Forgot Password</h1>

    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
        <label for="email">Email:</label>
        <input type="text" id="email" name="email" required><br><br>
        <input type="submit" value="Submit">
    </form>

    <?php
}

// Close database connection
$mysqli->close();

?>


<?php

// Configuration settings
define('DB_HOST', 'your_host');
define('DB_USER', 'your_user');
define('DB_PASSWORD', 'your_password');
define('DB_NAME', 'your_database');

// Connect to database
$conn = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Function to send password reset email
function send_password_reset_email($email, $username) {
    // Generate a random token for the user
    $token = bin2hex(random_bytes(32));

    // Update the user's database record with the new token
    $query = "UPDATE users SET password_token = '$token' WHERE email = '$email'";
    if ($conn->query($query) === TRUE) {
        // Send email to user with password reset link
        $subject = 'Reset Your Password';
        $body = "
            Dear $username,
            
            You have requested to reset your password. Please click on the following link to reset your password:
            <a href='http://your_site.com/reset-password.php?token=$token'>Reset Password</a>
        
            If you did not request a password reset, please ignore this email.
        ";
        $from = 'your_email@example.com';
        $to = $email;
        mail($to, $subject, $body, "From: $from");

        echo "Email sent successfully.";
    } else {
        echo "Error updating user record: " . $conn->error;
    }
}

// Handle forgot password form submission
if (isset($_POST['forgot_password'])) {
    // Get email address from input field
    $email = $_POST['email'];

    // Check if email exists in database
    $query = "SELECT * FROM users WHERE email = '$email'";
    $result = $conn->query($query);

    if ($result->num_rows > 0) {
        // Get user's username from database record
        while ($row = $result->fetch_assoc()) {
            $username = $row['username'];

            // Send password reset email to user
            send_password_reset_email($email, $username);
        }
    } else {
        echo "Email address not found in database.";
    }
}

// Close database connection
$conn->close();

?>


<?php

// Configuration settings
define('DB_HOST', 'your_host');
define('DB_USER', 'your_user');
define('DB_PASSWORD', 'your_password');
define('DB_NAME', 'your_database');

// Connect to database
$conn = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Function to update user's password
function update_password($token, $new_password) {
    // Get user's database record using token
    $query = "SELECT * FROM users WHERE password_token = '$token'";
    $result = $conn->query($query);

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            // Update user's password in database
            $new_password_hash = password_hash($new_password, PASSWORD_DEFAULT);
            $query = "UPDATE users SET password = '$new_password_hash' WHERE id = '$row[id]'";
            if ($conn->query($query) === TRUE) {
                echo "Password updated successfully.";
            } else {
                echo "Error updating user record: " . $conn->error;
            }
        }
    } else {
        echo "Invalid token.";
    }
}

// Handle password reset form submission
if (isset($_POST['reset_password'])) {
    // Get token and new password from input fields
    $token = $_POST['token'];
    $new_password = $_POST['new_password'];

    // Update user's password in database using token
    update_password($token, $new_password);
}

// Close database connection
$conn->close();

?>


<?php

// Config variables
define('SITE_EMAIL', 'your@email.com');
define('PASSWORD_RESET_LINK_EXPIRATION_TIME', 3600); // 1 hour

// Function to send password reset link via email
function sendPasswordResetEmail($email) {
    $query = "SELECT * FROM users WHERE email = '$email'";
    $result = mysqli_query($conn, $query);
    
    if (mysqli_num_rows($result) > 0) {
        // Get the user's data
        $row = mysqli_fetch_assoc($result);
        
        // Generate a random password reset token
        $token = bin2hex(random_bytes(32));
        
        // Insert the token into the database with expiration time
        $query = "INSERT INTO password_reset_tokens (user_id, token, created_at) VALUES ('$row[id]', '$token', NOW())";
        mysqli_query($conn, $query);
        
        // Send email to user with reset link
        $to = $email;
        $subject = 'Reset your password';
        $body = "Click on the following link to reset your password: <a href='" . SITE_URL . "/reset-password.php?token=$token'>Reset Password</a>";
        mail($to, $subject, $body);
        
        echo "Email sent successfully!";
    } else {
        echo "User not found.";
    }
}

// Forgot password form handler
if (isset($_POST['forgot_password'])) {
    $email = $_POST['email'];
    
    if (!empty($email)) {
        sendPasswordResetEmail($email);
    } else {
        echo "Please enter your email address.";
    }
} ?>


<?php

// Check if the token is valid
if (isset($_GET['token'])) {
    $query = "SELECT * FROM password_reset_tokens WHERE token = '".$_GET['token']."'";
    $result = mysqli_query($conn, $query);
    
    if (mysqli_num_rows($result) > 0) {
        // Get the user's data
        $row = mysqli_fetch_assoc($result);
        
        // Check if the token has expired
        if (time() - strtotime($row['created_at']) < PASSWORD_RESET_LINK_EXPIRATION_TIME) {
            // Display password reset form
            echo "Enter new password: <input type='password' name='new_password'><br><br>";
            echo "<button name='reset_password'>Reset Password</button>";
            
            if (isset($_POST['reset_password'])) {
                $new_password = $_POST['new_password'];
                
                // Hash the new password
                $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
                
                // Update the user's password in database
                $query = "UPDATE users SET password_hash = '$hashed_password' WHERE id = '$row[id]'";
                mysqli_query($conn, $query);
                
                echo "Password reset successfully!";
            }
        } else {
            echo "Token has expired. Please request a new token.";
        }
    } else {
        echo "Invalid token.";
    }
} ?>


<?php

// Database connection settings
define('DB_HOST', 'localhost');
define('DB_USERNAME', 'your_username');
define('DB_PASSWORD', 'your_password');
define('DB_NAME', 'your_database');

// Email configuration
define('EMAIL_ADDRESS', 'your_email_address');
define('EMAIL_PASSWORD', 'your_email_password');

// Connect to database
$conn = new mysqli(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get user input
$email = $_POST['email'];

// Check if email exists in database
$query = "SELECT * FROM users WHERE email = '$email'";
$result = $conn->query($query);

if ($result->num_rows > 0) {
    // Email found, generate new password and send it via email
    $newPassword = substr(str_shuffle('abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789'), 0, 10);
    
    // Update user's password in database
    $updateQuery = "UPDATE users SET password = '$newPassword' WHERE email = '$email'";
    $conn->query($updateQuery);

    // Send new password via email using PHPMailer (or a similar library)
    require_once 'PHPMailer/src/PHPMailer.php';
    $mail = new PHPMailer\PHPMailer\PHPMailer();
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->Port = 587;
    $mail->SMTPSecure = 'tls';
    $mail->SMTPAuth = true;
    $mail->Username = EMAIL_ADDRESS;
    $mail->Password = EMAIL_PASSWORD;
    $mail->setFrom(EMAIL_ADDRESS, 'Your Name');
    $mail->addAddress($email);
    $mail->Subject = 'New Password for Your Account';
    $mail->Body = 'Your new password is: ' . $newPassword;
    if (!$mail->send()) {
        echo 'Error sending email';
        exit();
    }
    
    echo 'A new password has been sent to your email.';
} else {
    echo 'Email not found in database.';
}

// Close database connection
$conn->close();

?>


<?php

?>

<h1>Forgot Password</h1>

<form action="forgot_password.php" method="post">
  <label for="email">Email:</label>
  <input type="text" id="email" name="email"><br><br>
  <button type="submit">Submit</button>
</form>



<?php

// Configuration
define('PASSWORD_SALT', 'your_password_salt_here');
define('RESET_TOKEN_EXPIRE_HOURS', 1);

// Function to generate reset token
function generateResetToken() {
  return bin2hex(random_bytes(32));
}

// Function to send password reset email
function sendPasswordResetEmail($email, $reset_token) {
  // Use a library like SwiftMailer or PHPMailer to send the email
  // For this example, we'll use a simple email sending function
  $subject = 'Password Reset Request';
  $message = "Dear user,

You have requested a password reset. Please click on the following link to reset your password:
";
  $message .= "<a href='http://yourwebsite.com/reset-password?token=$reset_token'>Reset Password</a>";
  mail($email, $subject, $message);
}

// Handle forgot password request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  // Get the user's email address
  $email = $_POST['email'];
  
  // Check if the email is valid
  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    header('Location: /forgot-password?error=Invalid+email+address');
    exit;
  }
  
  // Find the user in the database
  $stmt = $pdo->prepare("SELECT * FROM users WHERE email = :email");
  $stmt->execute(['email' => $email]);
  $user = $stmt->fetch();
  
  if ($user) {
    // Generate a new reset token
    $reset_token = generateResetToken();
    
    // Store the reset token in the user's record
    $pdo->exec("UPDATE users SET reset_token = '$reset_token', reset_expires_at = CURRENT_TIMESTAMP + INTERVAL " . RESET_TOKEN_EXPIRE_HOURS . " HOUR WHERE id = :id", ['id' => $user['id']]);
    
    // Send a password reset email to the user
    sendPasswordResetEmail($email, $reset_token);
    
    header('Location: /forgot-password?success=Password+reset+email+sent');
  } else {
    header('Location: /forgot-password?error=User+not+found');
  }
} else {
  // Display the forgot password form
  ?>
  <h1>Forgot Password</h1>
  <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
    <label for="email">Email:</label>
    <input type="text" name="email" id="email"><br><br>
    <button type="submit">Send Reset Email</button>
  </form>
  <?php
}

?>


<?php
// Include the database connection settings
require_once 'db-config.php';

// Set up form handling and error handling
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);

    // Validate user input
    if (empty($username) || empty($email)) {
        echo "Error: Both username and email are required.";
        exit;
    }

    // Retrieve the user data from the database
    try {
        $query = "SELECT * FROM users WHERE username = :username OR email = :email";
        $stmt = $db->prepare($query);
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':email', $email);

        if ($stmt->execute()) {
            $user = $stmt->fetch();

            // If a user exists, generate a reset token and send the password reset email
            if (!empty($user)) {
                // Generate a random reset token (replace with your own logic)
                $resetToken = bin2hex(random_bytes(32));

                // Update the user's reset token in the database
                try {
                    $query = "UPDATE users SET reset_token = :resetToken WHERE id = :id";
                    $stmt = $db->prepare($query);
                    $stmt->bindParam(':resetToken', $resetToken);
                    $stmt->bindParam(':id', $user['id']);

                    if ($stmt->execute()) {
                        // Send the password reset email
                        sendPasswordResetEmail($email, $resetToken);

                        echo "A password reset link has been sent to your email address.";
                    } else {
                        throw new Exception("Error: Unable to update user data.");
                    }
                } catch (Exception $e) {
                    // Handle database errors
                    echo "Database Error: " . $e->getMessage();
                }
            } else {
                echo "Error: User not found with provided credentials.";
            }
        } else {
            throw new Exception("Error: Database query failed.");
        }
    } catch (Exception $e) {
        // Handle SQL errors
        echo "Database Error: " . $e->getMessage();
    }
}

function sendPasswordResetEmail($email, $resetToken)
{
    // Email template for the password reset link
    $subject = 'Reset Your Password';
    $body = "<p>Click on the following link to reset your password:</p><p><a href='http://example.com/reset-password.php?token=" . $resetToken . "'>" . $resetToken . "</a></p>";

    // Send the email (use a library like PHPMailer for a secure implementation)
    mail($email, $subject, $body);
}
?>


<?php
// Include the database connection settings
require_once 'db-config.php';

// Set up form handling and error handling
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve the reset token from the URL query string
    $token = $_GET['token'];

    // Validate user input (e.g., password, confirm password)
    if (empty($_POST['password'])) {
        echo "Error: Password is required.";
        exit;
    }

    // Update the user's password and reset token in the database
    try {
        $query = "UPDATE users SET password_hash = :passwordHash, reset_token = '' WHERE reset_token = :resetToken";
        $stmt = $db->prepare($query);
        $stmt->bindParam(':passwordHash', $_POST['password']);
        $stmt->bindParam(':resetToken', $token);

        if ($stmt->execute()) {
            echo "Password updated successfully.";
        } else {
            throw new Exception("Error: Unable to update user data.");
        }
    } catch (Exception $e) {
        // Handle database errors
        echo "Database Error: " . $e->getMessage();
    }
}
?>


<?php

// Configuration
$database = 'your_database_name';
$username = 'your_username';
$password = 'your_password';

// Connect to database
$conn = new mysqli($database, $username, $password);

// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

// Function to send password reset email
function sendResetEmail($email) {
  // Email configuration (replace with your SMTP settings)
  $server = 'your_smtp_server';
  $port = 587;
  $username = 'your_email_username';
  $password = 'your_email_password';

  // Mail transport settings
  $transport = Swift_SmtpTransport::newInstance($server, $port)
    ->setUsername($username)
    ->setPassword($password);

  // Create a message
  $message = Swift_Message::newInstance()
    ->setSubject('Reset your password')
    ->setFrom(array('your_email@example.com' => 'Your Name'))
    ->setTo(array($email))
    ->setBody('Click this link to reset your password: <a href="' . $_SERVER['REQUEST_URI'] . '?reset=' . md5($email) . '">Reset Password</a>');

  // Send the email
  $mailer = Swift_Mailer::newInstance($transport);
  if ($mailer->send($message)) {
    return true;
  } else {
    return false;
  }
}

// Handle forgot password form submission
if (isset($_POST['forgot_password'])) {
  $email = $_POST['email'];

  // Validate email address
  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo 'Invalid email address';
    exit();
  }

  // Query database for user with matching email address
  $sql = "SELECT * FROM users WHERE email = '$email'";
  $result = $conn->query($sql);
  $user = $result->fetch_assoc();

  if ($user) {
    // Send password reset email
    sendResetEmail($email);

    echo 'Password reset link has been sent to your email';
  } else {
    echo 'No user with that email address exists';
  }
}

// Display forgot password form
?>
<form action="" method="post">
  <input type="email" name="email" placeholder="Enter your email address">
  <button type="submit" name="forgot_password">Forgot Password</button>
</form>


<?php

// Configuration
define('DB_HOST', 'your_database_host');
define('DB_USERNAME', 'your_database_username');
define('DB_PASSWORD', 'your_database_password');
define('DB_NAME', 'your_database_name');

// Connect to database
$conn = new mysqli(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Define variables
$email = $_POST['email'];

// Validate email
if (empty($email)) {
    echo 'Please enter your email address.';
    exit;
}

// Check if user exists
$sql = "SELECT * FROM users WHERE email = '$email'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // User found, generate new password and send to email
    $new_password = bin2hex(random_bytes(32));
    $sql = "UPDATE users SET password_hash = SHA2('$new_password', 512) WHERE email = '$email'";
    $conn->query($sql);

    // Send email with new password
    $to = $email;
    $subject = 'Your new password is: ' . $new_password;
    $message = 'Your new password is: ' . $new_password;
    $headers = 'From: your_email@example.com' . "\r
" .
        'Reply-To: your_email@example.com' . "\r
" .
        'X-Mailer: PHP/' . phpversion();
    mail($to, $subject, $message, $headers);

    echo 'Your new password has been sent to ' . $email;
} else {
    echo 'User not found.';
}

$conn->close();

?>


<?php
require_once 'dbconnect.php'; // database connection file

if (isset($_POST['forgot_password'])) {
    $email = $_POST['email'];

    // Check if the email is valid and exists in the database
    $query = "SELECT * FROM users WHERE email = '$email'";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) > 0) {
        // Generate a random token for password reset
        $token = bin2hex(random_bytes(32));

        // Update the user's record with the new token
        $update_query = "UPDATE users SET token = '$token' WHERE email = '$email'";
        mysqli_query($conn, $update_query);

        // Send an email to the user with a link to reset their password
        $subject = 'Reset Your Password';
        $message = "
            <html>
                <body>
                    <p>Click on this link to reset your password:</p>
                    <a href='http://example.com/reset_password.php?token=$token'>Reset Password</a>
                </body>
            </html>
        ";

        // Send the email
        $headers = 'MIME-Version: 1.0' . "\r
";
        $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r
";
        $headers .= 'From: no-reply@example.com' . "\r
";

        mail($email, $subject, $message, $headers);

        echo '<script>alert("Email sent to reset password!"); window.location.href = "login.php";</script>';
    } else {
        echo '<script>alert("Invalid email or not registered!"); window.location.href = "login.php";</script>';
    }
}
?>


<?php
require_once 'dbconnect.php'; // database connection file

if (isset($_GET['token'])) {
    $token = $_GET['token'];

    // Check if the token is valid and exists in the database
    $query = "SELECT * FROM users WHERE token = '$token'";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) > 0) {
        // If the user's record is found, update their password with the new one they entered
        echo '
            <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                <input type="password" name="new_password">
                <button type="submit">Change Password</button>
            </form>
        ';

        if (isset($_POST['change_password'])) {
            $new_password = $_POST['new_password'];

            // Hash the new password and update the user's record
            $hashed_new_password = password_hash($new_password, PASSWORD_DEFAULT);
            $update_query = "UPDATE users SET password = '$hashed_new_password' WHERE token = '$token'";
            mysqli_query($conn, $update_query);

            echo '<script>alert("Password changed!"); window.location.href = "login.php";</script>';
        }
    } else {
        echo 'Invalid Token';
    }
}
?>


<?php
$conn = mysqli_connect('localhost', 'username', 'password', 'database_name');
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

function query($query) {
    global $conn;
    return mysqli_query($conn, $query);
}
?>


<?php

// Configuration variables (update these to match your database)
$db_host = 'localhost';
$db_username = 'your_username';
$db_password = 'your_password';
$db_name = 'your_database';

// Connect to database
$conn = new mysqli($db_host, $db_username, $db_password, $db_name);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

function sendResetEmail($user_id, $token) {
  // Send email with reset link (using a library like PHPMailer or SwiftMailer)
  $email = 'your_email@example.com';
  $subject = 'Password Reset Link';

  $body = '
    <p>Hello!</p>
    <p>Click the link below to reset your password:</p>
    <a href="' . $_SERVER['HTTP_HOST'] . '/reset-password/' . $token . '">Reset Password</a>
  ';

  // Send email using your chosen library
}

function generateRandomToken() {
  return bin2hex(random_bytes(32));
}

function createPasswordResetToken($user_id) {
  $conn = new mysqli($db_host, $db_username, $db_password, $db_name);
  
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  $token = generateRandomToken();
  $expires_at = date('Y-m-d H:i:s', strtotime('+1 day'));

  $stmt = $conn->prepare('INSERT INTO password_reset_tokens (user_id, token, expires_at) VALUES (?, ?, ?)');
  $stmt->bind_param('is', $user_id, $token, $expires_at);
  $stmt->execute();

  return $token;
}

function forgotPassword($email) {
  $conn = new mysqli($db_host, $db_username, $db_password, $db_name);

  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  $stmt = $conn->prepare('SELECT id FROM users WHERE email = ?');
  $stmt->bind_param('s', $email);
  $stmt->execute();
  $result = $stmt->get_result();

  if ($result->num_rows > 0) {
    $user_id = $result->fetch_assoc()['id'];
    
    $token = createPasswordResetToken($user_id);
    
    sendResetEmail($user_id, $token);

    echo 'A password reset link has been sent to your email.';
  } else {
    echo 'User not found.';
  }
}

?>


forgotPassword('user@example.com');


function resetPassword($token) {
  $conn = new mysqli($db_host, $db_username, $db_password, $db_name);

  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  $stmt = $conn->prepare('SELECT user_id FROM password_reset_tokens WHERE token = ? AND expires_at > NOW()');
  $stmt->bind_param('s', $token);
  $stmt->execute();
  $result = $stmt->get_result();

  if ($result->num_rows > 0) {
    $user_id = $result->fetch_assoc()['user_id'];

    // Prompt user to enter new password
    $new_password = $_POST['new_password'];
    $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

    $stmt = $conn->prepare('UPDATE users SET password_hash = ? WHERE id = ?');
    $stmt->bind_param('si', $hashed_password, $user_id);
    $stmt->execute();

    // Delete password reset token
    $stmt = $conn->prepare('DELETE FROM password_reset_tokens WHERE user_id = ? AND token = ?');
    $stmt->bind_param('ii', $user_id, $token);
    $stmt->execute();

    echo 'Password reset successfully!';
  } else {
    echo 'Invalid or expired token.';
  }
}


<?php

require_once 'config.php'; // Load your database connection settings

if (isset($_POST['submit'])) {
  $email = $_POST['email'];

  try {
    // Get user data from database
    $stmt = $pdo->prepare('SELECT * FROM users WHERE email = :email');
    $stmt->execute([':email' => $email]);
    $user = $stmt->fetch();

    if ($user) {
      // Generate a new password and send it to the user's email address
      $new_password = generatePassword();
      mail($email, 'Reset Password', 'New Password: ' . $new_password);

      // Update the user's password in the database
      $stmt = $pdo->prepare('UPDATE users SET password_hash = :password WHERE id = :id');
      $stmt->execute([':password' => hash('sha256', $new_password), ':id' => $user['id']]);

      echo 'New password sent to your email address.';
    } else {
      echo 'Email not found in our database.';
    }
  } catch (PDOException $e) {
    echo 'Error sending new password: ' . $e->getMessage();
  }
}

function generatePassword() {
  // Generate a random password using a combination of letters and numbers
  $password = '';
  for ($i = 0; $i < 10; $i++) {
    $charType = rand(1, 2); // Randomly select between letter and number
    if ($charType == 1) { // Letter
      $password .= chr(rand(65, 90)); // Uppercase letter
    } else { // Number
      $password .= rand(0, 9);
    }
  }
  return $password;
}

?>


<?php

// Configuration
define('RESET_TOKEN_EXPIRE', 60); // Token expiration time in minutes
define('PASSWORD_RESET_TEMPLATE_DIR', 'path/to/template/directory');

// Function to send password reset email
function send_reset_email($email, $name) {
    $token = bin2hex(random_bytes(32));
    $reset_token = array(
        "user_id" => get_user_id_by_email($email),
        "token" => $token,
        "expires_at" => date("Y-m-d H:i:s", time() + (RESET_TOKEN_EXPIRE * 60))
    );
    
    // Update reset token in database
    update_reset_token($reset_token);
    
    // Send email with reset link
    send_email_with_reset_link($email, $name, $token);
}

// Function to send password reset email
function send_email_with_reset_link($to_email, $username, $token) {
    $subject = "Password Reset for Your Account";
    $body = "Please click on this link to reset your password: <a href='" . URLROOT . "/reset-password/" . $token . "'>" . URLROOT . "/reset-password/" . $token . "</a>";
    
    // Implement actual email sending logic here. For example using PHPMailer or SwiftMailer
}

// Function to handle password reset form submission
function handle_password_reset_submission($email, $password) {
    $user = get_user_by_email($email);
    if ($user) {
        update_password($user['id'], $password);
        
        // After updating the user's password, consider deleting their reset token.
        delete_reset_token($user['id']);
        
        return true; // Password updated successfully
    }
    
    return false;
}

// Function to retrieve a user by email (helper function)
function get_user_by_email($email) {
    $conn = mysqli_connect("localhost", "username", "password", "database");
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    
    // Query to retrieve user data
    $query = "SELECT * FROM users WHERE email = '$email'";
    $result = mysqli_query($conn, $query);
    if (mysqli_num_rows($result) > 0) {
        return mysqli_fetch_assoc($result);
    } else {
        return null;
    }
}

// Function to retrieve a user's id by email (helper function)
function get_user_id_by_email($email) {
    $user = get_user_by_email($email);
    if ($user) {
        return $user['id'];
    } else {
        return null;
    }
}

// Function to update password
function update_password($user_id, $password) {
    // Update user's password here using your chosen hashing method.
}

// Function to update reset token in database (helper function)
function update_reset_token($token) {
    $conn = mysqli_connect("localhost", "username", "password", "database");
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    
    // Insert new or update existing reset token
}

// Function to delete a reset token (helper function)
function delete_reset_token($user_id) {
    $conn = mysqli_connect("localhost", "username", "password", "database");
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    
    // Delete the reset token associated with a user
}

// Example usage:
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    send_reset_email($email, '');
}

?>


<?php

// Include database connection file
require_once 'db_connection.php';

// Check if form has been submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  // Get user input
  $email = $_POST['email'];

  // Validate email
  if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo "Invalid email address.";
    exit;
  }

  // Query database for user with matching email
  $query = "SELECT * FROM users WHERE email = '$email'";
  $result = mysqli_query($conn, $query);

  if (mysqli_num_rows($result) > 0) {
    // Retrieve user data
    $user_data = mysqli_fetch_assoc($result);

    // Generate random password and send it to the user's email
    $new_password = bin2hex(random_bytes(16));
    $subject = "Your new password";
    $message = "
      <p>Hello $user_data[username],</p>
      <p>Your new password is: $new_password</p>
      <p>Please log in with this new password.</p>
    ";
    mail($email, $subject, $message);

    // Update user's password
    $query = "UPDATE users SET password = '$new_password' WHERE email = '$email'";
    mysqli_query($conn, $query);

    echo "New password has been sent to your email.";
  } else {
    echo "No user found with this email address.";
  }
}

// Close database connection
mysqli_close($conn);


// Define a function to reset passwords
function forgot_password($username, $email) {
    // Check if the user exists
    $query = "SELECT * FROM users WHERE username = '$username' AND email = '$email'";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) == 0) {
        return 'User not found';
    }

    // Generate a reset token and store it in the database
    $reset_token = bin2hex(random_bytes(16));
    $update_query = "UPDATE users SET reset_token = '$reset_token' WHERE username = '$username'";
    mysqli_query($conn, $update_query);

    // Send an email with the reset link
    send_reset_email($email, $reset_token);
}

// Define a function to send the reset email
function send_reset_email($email, $reset_token) {
    // Set up mail server credentials (replace with your own)
    $server = 'smtp.gmail.com';
    $port = 587;
    $username = 'your-email@gmail.com';
    $password = 'your-password';

    // Compose the email
    $subject = 'Reset Password Link';
    $body = "Click this link to reset your password: <a href='https://example.com/reset-password?token=$reset_token'>Reset Password</a>";

    try {
        // Send the email using mail()
        mail($email, $subject, $body, "From: $username");
        echo 'Email sent successfully!';
    } catch (Exception $e) {
        echo 'Error sending email: ' . $e->getMessage();
    }
}

// Example usage:
$username = 'johndoe';
$email = 'john@example.com';

forgot_password($username, $email);


<?php

// Configuration settings
define('DB_HOST', 'localhost');
define('DB_USER', 'your_username');
define('DB_PASSWORD', 'your_password');
define('DB_NAME', 'your_database');

// Connect to database
try {
    $pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASSWORD);
} catch (PDOException $e) {
    exit('Error: ' . $e->getMessage());
}

// Form submission handling
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    // Get form data
    $email = $_POST['email'];

    // Validate email address
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "Invalid email address.";
        exit;
    }

    // Find user by email address
    $stmt = $pdo->prepare('SELECT * FROM users WHERE email = :email');
    $stmt->bindParam(':email', $email);
    $stmt->execute();

    // Get the result
    $user = $stmt->fetch();

    if (!$user) {
        echo "Email not found.";
        exit;
    }

    // Generate new password and send to user via email
    $newPassword = substr(md5(uniqid(mt_rand(), true)), 0, 8);
    $to      = $email;
    $subject = 'Your New Password';
    $message = 'New password: ' . $newPassword;
    $headers = 'From: your_email@example.com' . "\r
" .
        'Reply-To: no-reply@example.com' . "\r
" .
        'X-Mailer: PHP/' . phpversion();

    mail($to, $subject, $message, $headers);

    echo "A new password has been sent to your email address.";
} else {

?>
<!DOCTYPE html>
<html>
<head>
    <title>Forgot Password</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            text-align: center;
        }
    </style>
</head>

<body>

<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
    Email Address: <br>
    <input type="text" name="email"><br><br>
    <input type="submit" value="Submit">
</form>

<?php } ?>


<?php

// Configuration
define('DB_HOST', 'localhost');
define('DB_USER', 'your_username');
define('DB_PASSWORD', 'your_password');
define('DB_NAME', 'your_database');

// Connect to database
$mysqli = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

// Function to send reset password email
function send_reset_email($email) {
    // Replace with your own mail server configuration
    $mail = new PHPMailer();
    $mail->isSMTP();
    $mail->Host = 'smtp.example.com';
    $mail->Port = 587;
    $mail->SMTPAuth = true;
    $mail->Username = 'your_email@example.com';
    $mail->Password = 'your_password';

    // Set email content
    $mail->setFrom('your_email@example.com', 'Your Name');
    $mail->addAddress($email);
    $mail->Subject = 'Reset Your Password';
    $mail->Body = '
        Hello!

        To reset your password, please click on the following link:
        <a href="' . htmlspecialchars($_SERVER['HTTP_HOST'] . '/reset_password.php?email=' . urlencode($email)) . '">Reset Password</a>
    ';

    // Send email
    if (!$mail->send()) {
        echo 'Mailer Error: ' . $mail->ErrorInfo;
    }
}

// Function to reset password
function reset_password($email) {
    // Retrieve user ID from database
    $query = "SELECT id FROM users WHERE email = '$email'";
    $result = $mysqli->query($query);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $user_id = $row['id'];

        // Generate new password and hash it
        $new_password = substr(str_shuffle('abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789'), 0, 12);
        $password_hash = password_hash($new_password, PASSWORD_DEFAULT);

        // Update user password in database
        $query = "UPDATE users SET password_hash = '$password_hash' WHERE id = '$user_id'";
        $mysqli->query($query);

        return $new_password;
    } else {
        return null;
    }
}

// Handle forgot password form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];

    // Check if email exists in database
    $query = "SELECT * FROM users WHERE email = '$email'";
    $result = $mysqli->query($query);

    if ($result->num_rows > 0) {
        send_reset_email($email);
        echo 'Reset password link sent to your email.';
    } else {
        echo 'Email not found in database.';
    }
}

// Close database connection
$mysqli->close();

?>


<?php

// Configuration
define('DB_HOST', 'localhost');
define('DB_USER', 'your_username');
define('DB_PASSWORD', 'your_password');
define('DB_NAME', 'your_database');

// Connect to database
$mysqli = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

// Retrieve user ID from database
$email = $_GET['email'];
$query = "SELECT id FROM users WHERE email = '$email'";
$result = $mysqli->query($query);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $user_id = $row['id'];

    // Retrieve new password from database
    $query = "SELECT password_hash FROM users WHERE id = '$user_id'";
    $password_result = $mysqli->query($query);
    $new_password_row = $password_result->fetch_assoc();

    echo 'Your new password is: <strong>' . $new_password_row['password_hash'] . '</strong>';
} else {
    echo 'Email not found in database.';
}

// Close database connection
$mysqli->close();

?>


<?php
// Include the database connection settings (replace with your own)
require_once 'config.php';

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the email from the form input
    $email = $_POST['email'];

    // Validate the email address
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo 'Invalid email address';
        exit;
    }

    // Query to get the user's ID and password hash (for security)
    $query = "SELECT id, password FROM users WHERE email = '$email'";
    $result = mysqli_query($conn, $query);

    // Check if a user with that email exists
    if (mysqli_num_rows($result) == 1) {
        // Get the user's ID and password hash
        $row = mysqli_fetch_assoc($result);
        $userId = $row['id'];
        $passwordHash = $row['password'];

        // Generate a random token for password reset
        $token = bin2hex(random_bytes(32));

        // Insert the token into the database (replace with your own table name)
        $query = "INSERT INTO password_reset_tokens (user_id, token) VALUES ('$userId', '$token')";
        mysqli_query($conn, $query);

        // Send an email with a link to reset the password
        $subject = 'Reset Your Password';
        $body = '
            <p>Hello,' . $email . ',</p>
            <p>To reset your password, click on this link:</p>
            <a href="http://yourwebsite.com/reset_password.php?token=' . $token . '">Reset Password</a>
        ';
        sendEmail($email, $subject, $body);
    } else {
        echo 'No user found with that email address';
    }
}

// Function to send an email using PHPMailer (replace with your own mail function)
function sendEmail($to, $subject, $body) {
    require_once 'PHPMailer/src/Exception.php';
    require_once 'PHPMailer/src/PHPMailer.php';
    require_once 'PHPMailer/src/SMTP.php';

    $mail = new PHPMailer\PHPMailer\PHPMailer();

    try {
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com'; // Replace with your own SMTP server
        $mail->Port = 587;
        $mail->SMTPAuth = true;
        $mail->Username = 'your-email@gmail.com';
        $mail->Password = 'your-password';

        $mail->setFrom('your-email@gmail.com', 'Your Name');
        $mail->addAddress($to);
        $mail->Subject = $subject;
        $mail->Body = $body;

        if (!$mail->send()) {
            throw new Exception('Error sending email: ' . $mail->ErrorInfo);
        }
    } catch (Exception $e) {
        echo 'Email not sent: ' . $e->getMessage();
    }
}
?>


<?php
// Check if the token has been passed in the URL
$token = $_GET['token'];

// Query to get the user's ID from the database (replace with your own table name)
$query = "SELECT id FROM password_reset_tokens WHERE token = '$token'";
$result = mysqli_query($conn, $query);

// Check if a valid token exists for this user
if (mysqli_num_rows($result) == 1) {
    // Get the user's ID
    $row = mysqli_fetch_assoc($result);
    $userId = $row['id'];

    // Display the password reset form
    echo '
        <form action="reset_password.php" method="post">
            <label for="password">New Password:</label>
            <input type="password" id="password" name="password"><br><br>
            <button type="submit" class="btn btn-primary">Reset Password</button>
        </form>
    ';

    // Check if the form has been submitted
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Get the new password from the form input
        $newPassword = $_POST['password'];

        // Query to update the user's password (replace with your own table name)
        $query = "UPDATE users SET password = '$newPassword' WHERE id = '$userId'";
        mysqli_query($conn, $query);

        // Remove the token from the database
        $query = "DELETE FROM password_reset_tokens WHERE token = '$token'";
        mysqli_query($conn, $query);

        echo 'Your password has been reset successfully!';
    }
} else {
    echo 'Invalid or expired token';
}
?>


<?php

// Assuming we have a database connection object called $db

function forgot_password($username, $email) {
  // Retrieve user data from database based on username and email
  $query = "SELECT * FROM users WHERE username = '$username' AND email = '$email'";
  $result = mysqli_query($db, $query);
  
  if (mysqli_num_rows($result) > 0) {
    // Generate a reset token and store it in the user's record
    $reset_token = bin2hex(random_bytes(16));
    $update_query = "UPDATE users SET reset_token = '$reset_token' WHERE username = '$username' AND email = '$email'";
    mysqli_query($db, $update_query);
    
    // Send an email to the user with a password reset link
    send_password_reset_email($email, $reset_token);
  } else {
    echo "Username and/or email not found.";
  }
}

function send_password_reset_email($email, $reset_token) {
  // Generate a password reset link (e.g., https://example.com/reset-password?token=xyz123)
  $reset_link = "https://example.com/reset-password?token=$reset_token";
  
  // Send an email to the user with instructions on how to reset their password
  $subject = "Reset Your Password";
  $body = "
    <p>Hello $username,</p>
    <p>To reset your password, please click on this link:</p>
    <a href='$reset_link'>$reset_link</a>
  ";
  
  // Use a library like PHPMailer or SwiftMailer to send the email
}

// Example usage:
forgot_password("john", "john@example.com");

?>


<?php

function reset_password($token) {
  // Retrieve the user's data from database based on the token
  $query = "SELECT * FROM users WHERE reset_token = '$token'";
  $result = mysqli_query($db, $query);
  
  if (mysqli_num_rows($result) > 0) {
    // Update the user's password in the database
    $new_password = trim($_POST['password']);
    $hash_password = hash_password($new_password); // Use a library like PHPass or bcrypt to hash the password
    $update_query = "UPDATE users SET password = '$hash_password', reset_token = '' WHERE reset_token = '$token'";
    mysqli_query($db, $update_query);
    
    echo "Password updated successfully!";
  } else {
    echo "Invalid token.";
  }
}

function hash_password($password) {
  // Use a library like PHPass or bcrypt to hash the password
}

// Example usage:
if (isset($_POST['token'])) {
  reset_password(trim($_POST['token']));
}
?>


<?php

require 'db.php'; // Include your database connection script here.

function sendPasswordResetEmail($username, $token) {
    $emailBody = "Click on this link to reset your password: <a href='" . url('reset_password', array('token' => $token)) . "'>" . url('reset_password', array('token' => $token)) . "</a>";
    // Send the email using PHPMailer or a similar library, using $username's email address.
}

function handleForgotPasswordRequest() {
  global $db;

  $username = $_POST['username'];
  $email = $_POST['email'];

  if (empty($username) || empty($email)) {
      echo "Please enter both your username and email.";
      exit;
  }

  // Find the user in the database
  $query = "SELECT * FROM users WHERE username = '$username' AND email = '$email'";
  $result = mysqli_query($db, $query);

  if (mysqli_num_rows($result) == 0) {
      echo "Username or email not found.";
      exit;
  }

  // Generate a password reset token
  $token = bin2hex(random_bytes(16));

  // Update the user's entry in the database to include their new token and expiration time
  $query = "UPDATE users SET password_reset_token = '$token', reset_expires_at = NOW() + INTERVAL 1 DAY WHERE username = '$username' AND email = '$email'";
  mysqli_query($db, $query);

  sendPasswordResetEmail($username, $token);
}

if (isset($_POST['submit'])) {
    handleForgotPasswordRequest();
}
?>


// Include the database connection settings
include 'db_config.php';

// Check if the form has been submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Extract the username from the form data
    $username = $_POST['username'];

    // Query the database to retrieve the user's email and password
    $query = "SELECT * FROM users WHERE username = '$username'";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) > 0) {
        // Retrieve the user's email and password from the result set
        $row = mysqli_fetch_assoc($result);
        $email = $row['email'];
        $password = $row['password'];

        // Generate a random password reset token
        $token = bin2hex(random_bytes(16));

        // Update the user's database record with the new password reset token
        $query = "UPDATE users SET password_reset_token = '$token' WHERE username = '$username'";
        mysqli_query($conn, $query);

        // Send an email to the user with a link to reset their password
        send_password_reset_email($email, $token);
    } else {
        echo 'Error: Username not found';
    }
}

// Function to send a password reset email
function send_password_reset_email($email, $token) {
    // Define the email template
    $subject = 'Reset Your Password';
    $body = '
    <p>Dear '.$username.',</p>
    <p>You are receiving this email because we received a request to reset your password.</p>
    <p>To reset your password, click on the following link:</p>
    <a href="' . base_url('reset_password.php?token=' . $token) . '">Reset Password</a>
    ';

    // Send the email using PHPMailer or your preferred email library
    mail($email, $subject, $body);
}


<?php

// Configuration
$dsn = 'mysql:host=localhost;dbname=your_database';
$username = 'your_username';
$password = 'your_password';

// Database Connection
try {
    $conn = new PDO($dsn, $username, $password);
} catch (PDOException $e) {
    echo 'Connection failed: ' . $e->getMessage();
}

// Forgot Password Form
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get user input
    $email = $_POST['email'];

    // Validate email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo 'Invalid email address';
        exit;
    }

    // Query database to retrieve user data
    try {
        $stmt = $conn->prepare('SELECT * FROM users WHERE email = :email');
        $stmt->bindParam(':email', $email);
        $stmt->execute();

        if ($stmt->rowCount() == 0) {
            echo 'Email not found';
            exit;
        }

        // Retrieve user data
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        // Generate a random password
        $new_password = bin2hex(random_bytes(16));

        // Update user password in database
        try {
            $stmt = $conn->prepare('UPDATE users SET password = :password WHERE email = :email');
            $stmt->bindParam(':password', $new_password);
            $stmt->bindParam(':email', $email);
            $stmt->execute();

            // Send password reset email
            sendPasswordResetEmail($user, $new_password);

            echo 'New password sent to your email';
        } catch (PDOException $e) {
            echo 'Error updating password: ' . $e->getMessage();
        }
    } catch (PDOException $e) {
        echo 'Error retrieving user data: ' . $e->getMessage();
    }
}

// Function to send password reset email
function sendPasswordResetEmail($user, $new_password)
{
    // Configuration
    $from_email = 'your_email@example.com';
    $subject = 'Your new password';

    // Email template
    $email_template = "
        Dear {name},

        Your new password is: {$new_password}

        Best regards,
        Your Name
    ";

    // Replace placeholders in email template
    $email_template = str_replace('{name}', $user['name'], $email_template);

    // Send email using PHPMailer (or your preferred email library)
    $mail = new PHPMailer;
    $mail->isSMTP();
    $mail->Host = 'smtp.example.com';
    $mail->Port = 587;
    $mail->Username = $from_email;
    $mail->Password = 'your_password';
    $mail->setFrom($from_email, 'Your Name');
    $mail->addAddress($user['email']);
    $mail->Subject = $subject;
    $mail->Body = $email_template;

    if (!$mail->send()) {
        echo 'Error sending email: ' . $mail->ErrorInfo;
    }
}


<?php

// Configuration
$db_host = 'localhost';
$db_username = 'your_username';
$db_password = 'your_password';
$db_name = 'your_database';

// Connect to database
$conn = new mysqli($db_host, $db_username, $db_password, $db_name);

if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

// Check if form has been submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  // Get user's email from form
  $email = $_POST['email'];

  // Check if email is valid and exists in database
  $query = "SELECT * FROM users WHERE email = '$email'";
  $result = $conn->query($query);

  if ($result->num_rows > 0) {
    // Get user's ID from result
    $row = $result->fetch_assoc();
    $user_id = $row['id'];

    // Generate new password and store it in database
    $new_password = substr(str_shuffle('abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789'), 0, 8);
    $query = "UPDATE users SET password_hash = SHA1('$new_password') WHERE id = '$user_id'";
    $conn->query($query);

    // Send email with new password to user
    $subject = 'Your new password';
    $message = "Dear user,

Your new password is: $new_password

Best regards, [Your Name]";
    mail($email, $subject, $message);
  }

  // Redirect user back to login page
  header('Location: login.php');
  exit;
}

?>


<?php
require_once 'config.php';

// Check if the form has been submitted
if (isset($_POST['submit'])) {
    // Get user input
    $email = trim($_POST['email']);

    // Validate email address
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "Invalid email address";
        return;
    }

    // Query the database to retrieve the user's ID
    $query = "SELECT id FROM users WHERE email = '$email'";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) == 1) {
        // Retrieve the user's ID and email address
        $row = mysqli_fetch_assoc($result);
        $userId = $row['id'];
        $userEmail = $row['email'];

        // Generate a password reset token
        $token = bin2hex(random_bytes(32));

        // Store the token in the database
        $query = "UPDATE users SET token = '$token' WHERE id = '$userId'";
        mysqli_query($conn, $query);

        // Send an email with the password reset link
        sendPasswordResetEmail($userEmail, $token);
    } else {
        echo "No account found with this email address";
    }
}

// Function to send a password reset email
function sendPasswordResetEmail($email, $token) {
    // Get the user's name from the database (optional)
    $query = "SELECT username FROM users WHERE email = '$email'";
    $result = mysqli_query($conn, $query);
    $row = mysqli_fetch_assoc($result);
    $username = $row['username'];

    // Email content
    $subject = "Password Reset Request";
    $body = "
        <p>Hello $username,</p>
        <p>You have requested to reset your password. Please click on the following link to reset your password:</p>
        <a href='https://example.com/reset-password.php?token=$token'>Reset Password</a>
        <p>If you did not request a password reset, please ignore this email.</p>
    ";

    // Send the email using PHPMailer (or any other mail library)
    $mail = new PHPMailer();
    $mail->isSMTP();
    $mail->Host = 'smtp.example.com';
    $mail->Port = 587;
    $mail->Username = 'your-email@example.com';
    $mail->Password = 'your-password';
    $mail->setFrom('your-email@example.com', 'Your Name');
    $mail->addAddress($email);
    $mail->Subject = $subject;
    $mail->Body = $body;
    if (!$mail->send()) {
        echo "Error sending email: " . $mail->ErrorInfo;
    }
}
?>


<?php

// Configuration settings
define('EMAIL_ADDRESS', 'admin@example.com'); // Email address to send emails from
define('SITE_NAME', 'Your Website Name'); // Website name

// Check if the form has been submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $email = $_POST['email'];
  
  // Validate email address
  if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo "Invalid email address.";
    exit;
  }
  
  // Get user details from database
  $db = new PDO('mysql:host=localhost;dbname=your_database', 'username', 'password');
  $stmt = $db->prepare("SELECT * FROM users WHERE email = :email");
  $stmt->bindParam(':email', $email);
  $stmt->execute();
  
  // If user exists
  if ($user = $stmt->fetch()) {
    // Generate a new password and store it in the database
    $new_password = generatePassword();
    $hashed_new_password = password_hash($new_password, PASSWORD_DEFAULT);
    
    // Update user's password in database
    $update_stmt = $db->prepare("UPDATE users SET password_hash = :password WHERE email = :email");
    $update_stmt->bindParam(':password', $hashed_new_password);
    $update_stmt->bindParam(':email', $email);
    $update_stmt->execute();
    
    // Send the new password to user via email
    sendEmail($user['username'], $new_password, $email);
  } else {
    echo "User does not exist.";
  }
}

function generatePassword() {
  // Function to generate a strong password
  $length = 12;
  $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
  $password = '';
  for ($i = 0; $i < $length; $i++) {
    $password .= substr($chars, rand(0, strlen($chars) - 1), 1);
  }
  return $password;
}

function sendEmail($username, $new_password, $email) {
  // Function to send email
  $subject = "New Password for Your Account";
  $message = "Hello $username,

Your new password is: $new_password

Best regards,
$SITE_NAME";
  
  $headers = "From: " . EMAIL_ADDRESS . "\r
";
  $headers .= "Content-type: text/html\r
";
  
  mail($email, $subject, $message, $headers);
}

?>


<?php

// Include your database connection script or use PDO
require_once 'db.php';

if (isset($_POST['submit'])) {
    // Get the user's email address from the form submission
    $email = $_POST['email'];

    // Check if the email address exists in the database
    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = :email");
    $stmt->bindParam(':email', $email);
    $stmt->execute();

    // If the email is found, generate a reset token and update the user's record
    if ($user = $stmt->fetch()) {
        // Generate a random reset token (e.g. a UUID)
        $reset_token = bin2hex(random_bytes(32));

        // Update the user's record with the new reset token
        $update_stmt = $pdo->prepare("UPDATE users SET reset_token = :token WHERE id = :id");
        $update_stmt->bindParam(':token', $reset_token);
        $update_stmt->bindParam(':id', $user['id']);
        $update_stmt->execute();

        // Send an email to the user with a password reset link
        $subject = "Reset Password";
        $body = "Click here to reset your password: <a href='https://yourwebsite.com/reset_password.php?token=$reset_token'>Reset Password</a>";
        mail($email, $subject, $body);

        echo "Password reset email sent to $email. Please check your inbox and follow the link to reset your password.";
    } else {
        echo "Email address not found.";
    }
}

// Display the forgot password form
?>
<form method="post" action="">
  <label for="email">Email Address:</label>
  <input type="email" id="email" name="email"><br><br>
  <button type="submit" name="submit">Send Password Reset Email</button>
</form>

<?php


<?php

// Include your database connection script or use PDO
require_once 'db.php';

if (isset($_GET['token'])) {
    // Get the reset token from the URL parameter
    $reset_token = $_GET['token'];

    // Check if the reset token is valid and not expired
    $stmt = $pdo->prepare("SELECT * FROM users WHERE reset_token = :token AND reset_expires > NOW()");
    $stmt->bindParam(':token', $reset_token);
    $stmt->execute();

    // If the token is valid, display a password change form
    if ($user = $stmt->fetch()) {
        ?>
        <form method="post" action="">
          <label for="password">New Password:</label>
          <input type="password" id="password" name="password"><br><br>
          <label for="confirm_password">Confirm New Password:</label>
          <input type="password" id="confirm_password" name="confirm_password"><br><br>
          <button type="submit" name="submit">Change Password</button>
        </form>

        <?php
    } else {
        echo "Invalid or expired reset token.";
    }
}

// Update the user's password if the form is submitted
if (isset($_POST['submit'])) {
    // Get the new password from the form submission
    $new_password = $_POST['password'];

    // Check if the passwords match
    if ($_POST['password'] == $_POST['confirm_password']) {
        // Update the user's record with the new password
        $update_stmt = $pdo->prepare("UPDATE users SET password_hash = :hash WHERE id = :id");
        $update_stmt->bindParam(':hash', password_hash($new_password, PASSWORD_DEFAULT));
        $update_stmt->bindParam(':id', $user['id']);
        $update_stmt->execute();

        echo "Password changed successfully!";
    } else {
        echo "Passwords do not match.";
    }
}

// Display the reset password form
?>


<?php

// Database configuration
$dbHost = 'localhost';
$dbUsername = 'your_username';
$dbPassword = 'your_password';
$dbName = 'your_database';

// Create connection and select database
$conn = new mysqli($dbHost, $dbUsername, $dbPassword, $dbName);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Function to send forgot password email
function sendForgotPasswordEmail($username, $email, $new_password)
{
    // Define the message that will be sent in the email
    $message = "
        Dear $username,

        Your new password is: $new_password

        Best regards,
        Your Website
    ";

    // Send the email using your preferred method (e.g., PHPMailer)
    // For this example, we'll use the built-in mail function
    mail($email, "Forgot Password", $message);
}

// Function to reset password
function resetPassword($username, $new_password)
{
    global $conn;

    // Prepare and execute query to update password
    $stmt = $conn->prepare("UPDATE users SET password = ? WHERE username = ?");
    $stmt->bind_param('ss', $new_password, $username);
    $stmt->execute();

    // Get the new password hash (we're using bcrypt)
    $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

    return $hashed_password;
}

// Check if the user exists and send a reset link
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $email = $_POST['email'];

    // Query to get the user's email address
    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ? AND email = ?");
    $stmt->bind_param('ss', $username, $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // User exists, send a reset link
        $user = $result->fetch_assoc();

        // Generate a new password (this example uses a simple password generator)
        $new_password = substr(str_shuffle('abcdefghijklmnopqrstuvwxyz'), 0, 8);

        // Update the user's email address with their new password
        $hashed_password = resetPassword($username, $new_password);
        sendForgotPasswordEmail($username, $email, $new_password);
    } else {
        echo "User not found";
    }
}

?>


<?php

// Configuration
define('SITE_URL', 'http://example.com/'); // replace with your site URL
define('EMAIL_ADDRESS', 'admin@example.com'); // your email address for reset emails

require_once 'db_connection.php'; // Your database connection script

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];

    if (empty($email)) {
        echo "Email is required.";
        exit;
    }

    $sql = "SELECT * FROM users WHERE email = '$email'";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) == 0) {
        echo "Email not found. Please check your email and try again.";
        exit;
    } else {
        // Get the user's ID
        $row = mysqli_fetch_assoc($result);
        $userId = $row['id'];

        // Generate a random token for password reset
        $token = bin2hex(random_bytes(32));

        // Store the token in the database
        $sql = "UPDATE users SET password_reset_token = '$token' WHERE id = '$userId'";
        mysqli_query($conn, $sql);

        // Send email with reset link
        sendResetEmail($email, $token);
    }
}

function sendResetEmail($email, $token) {
    $subject = 'Reset Your Password';
    $body = '
        <p>Dear user,</p>
        <p>To reset your password, please click on this link:</p>
        <a href="' . SITE_URL . 'reset_password.php?token=' . $token . '">Reset Your Password</a>
        <p>Best regards,</p>
    ';

    mail($email, $subject, $body);
}

?>


<?php

require_once 'db_connection.php'; // Your database connection script

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    $token = $_GET['token'];

    if (empty($token)) {
        echo "Invalid request.";
        exit;
    }

    $sql = "SELECT * FROM users WHERE password_reset_token = '$token'";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) == 0) {
        echo "Token is invalid or has expired. Please try resetting your password again.";
        exit;
    } else {
        // The user's data is available here
        // You can create a form for the new password and store it in the database.

        ?>
        <form action="update_password.php" method="post">
            <label>Enter New Password:</label>
            <input type="password" name="new_password"><br><br>
            <input type="submit" value="Change Password">
        </form>

        <?php
    }
}

?>


<?php

require_once 'db_connection.php'; // Your database connection script

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $newPassword = $_POST['new_password'];

    if (empty($newPassword)) {
        echo "New password is required.";
        exit;
    }

    $token = $_GET['token'];
    $sql = "UPDATE users SET password_reset_token = '', password = '$newPassword' WHERE password_reset_token = '$token'";
    mysqli_query($conn, $sql);

    echo "Your password has been updated successfully!";
}

?>


<?php
$conn = new mysqli('localhost', 'username', 'password', 'database');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>


<?php

// Configuration
define('SECRET_KEY', 'your_secret_key_here');
define('PASSWORD_RESET_EXPIRES_IN_HOURS', 1);

// Function to send password reset email
function send_password_reset_email($user_id, $token) {
  // Get user details
  $user = get_user_by_id($user_id);
  
  if (!$user) {
    return false;
  }
  
  // Set email content
  $subject = 'Reset your password';
  $message = '
    <p>Dear ' . $user->username . ',</p>
    <p>We received a request to reset your password. To do so, click on the link below:</p>
    <p><a href="' . $_SERVER['HTTP_HOST'] . '/reset-password?token=' . $token . '">Reset Password</a></p>
  ';
  
  // Send email
  mail($user->email, $subject, $message);
  
  return true;
}

// Function to reset password
function reset_password($token) {
  // Validate token
  $password_reset = get_password_reset_by_token($token);
  
  if (!$password_reset || $password_reset->expires_at < time()) {
    return false;
  }
  
  // Get user ID from token
  $user_id = $password_reset->user_id;
  
  // Set new password (for example, a random one)
  $new_password = bin2hex(random_bytes(32));
  
  // Update user's password
  update_user_password($user_id, $new_password);
  
  return true;
}

// Function to get user by ID
function get_user_by_id($id) {
  global $conn;
  
  $query = 'SELECT * FROM users WHERE id = :id';
  $stmt = $conn->prepare($query);
  $stmt->bindParam(':id', $id);
  $stmt->execute();
  
  return $stmt->fetchObject();
}

// Function to get password reset by token
function get_password_reset_by_token($token) {
  global $conn;
  
  $query = 'SELECT * FROM password_resets WHERE token = :token AND expires_at > :expires_at';
  $stmt = $conn->prepare($query);
  $stmt->bindParam(':token', $token);
  $stmt->bindParam(':expires_at', time());
  $stmt->execute();
  
  return $stmt->fetchObject();
}

// Function to update user's password
function update_user_password($user_id, $new_password) {
  global $conn;
  
  $query = 'UPDATE users SET password_hash = :password_hash WHERE id = :id';
  $stmt = $conn->prepare($query);
  $stmt->bindParam(':id', $user_id);
  $stmt->bindParam(':password_hash', hash('sha256', $new_password));
  $stmt->execute();
}

// Handle form submission
if (isset($_POST['forgot-password'])) {
  // Get user's email
  $email = trim($_POST['email']);
  
  // Check if email is valid
  if (!preg_match('/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/', $email)) {
    die('Invalid email address');
  }
  
  // Get user ID from database
  $query = 'SELECT id FROM users WHERE email = :email';
  $stmt = $conn->prepare($query);
  $stmt->bindParam(':email', $email);
  $stmt->execute();
  
  if ($result = $stmt->fetchObject()) {
    $user_id = $result->id;
    
    // Generate password reset token
    $token = bin2hex(random_bytes(32));
    
    // Insert new password reset record into database
    $query = 'INSERT INTO password_resets (user_id, token) VALUES (:user_id, :token)';
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':user_id', $user_id);
    $stmt->bindParam(':token', $token);
    $stmt->execute();
    
    // Send email with reset link
    send_password_reset_email($user_id, $token);
  } else {
    die('Email address not found');
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
  
  <?php if (isset($_POST['forgot-password'])) : ?>
    <p>Email sent with password reset link.</p>
  <?php endif; ?>
  
  <form method="post">
    <label for="email">Email:</label>
    <input type="text" id="email" name="email" placeholder="example@example.com">
    <button type="submit" name="forgot-password">Send Password Reset Link</button>
  </form>
</body>
</html>


<?php

// Configuration
$dbHost = 'localhost';
$dbUsername = 'your_username';
$dbPassword = 'your_password';
$dbName = 'your_database';

// Connect to database
$conn = new mysqli($dbHost, $dbUsername, $dbPassword, $dbName);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Function to generate a random token
function generateToken() {
    return bin2hex(random_bytes(16));
}

// Function to reset password
function resetPassword($token, $email, $newPassword) {
    // Query to update user's reset token and expires
    $query = "UPDATE users SET reset_token = ?, reset_expires = NOW() + INTERVAL 30 MINUTE WHERE email = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ss", $token, $email);

    if ($stmt->execute()) {
        // Hash the new password
        $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
        // Query to update user's password hash
        $query = "UPDATE users SET password_hash = ? WHERE email = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("ss", $hashedPassword, $email);

        if ($stmt->execute()) {
            return true;
        } else {
            // Error updating user's password hash
            echo "Error updating user's password hash: " . $conn->error;
        }
    } else {
        // Error resetting token and expires
        echo "Error resetting token and expires: " . $conn->error;
    }

    return false;
}

// Function to send reset link via email
function sendResetLink($email) {
    // Generate a random token
    $token = generateToken();

    // Query to select user's id, email, and current reset token (if any)
    $query = "SELECT id, email, reset_token FROM users WHERE email = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $email);

    if ($stmt->execute()) {
        $result = $stmt->get_result();

        // If user exists and doesn't have a current reset token
        if ($row = $result->fetch_assoc() && !$row['reset_token']) {
            // Update user's reset token and expires
            $query = "UPDATE users SET reset_token = ?, reset_expires = NOW() + INTERVAL 30 MINUTE WHERE email = ?";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("ss", $token, $email);

            if ($stmt->execute()) {
                // Send email with reset link
                $subject = "Reset your password";
                $body = "Click this link to reset your password: <a href='http://example.com/reset-password.php?token=" . $token . "'>Reset Password</a>";
                $from = 'your_email@example.com';
                $to = $email;

                mail($to, $subject, $body, "From: $from\r
");

                return true;
            } else {
                // Error updating user's reset token and expires
                echo "Error updating user's reset token and expires: " . $conn->error;
            }
        } else {
            // User doesn't exist or already has a current reset token
            echo "User doesn't exist or already has a current reset token";
        }
    } else {
        // Error selecting user's id, email, and current reset token
        echo "Error selecting user's id, email, and current reset token: " . $conn->error;
    }

    return false;
}

// Handle forgotten password request
if (isset($_POST['email'])) {
    $email = $_POST['email'];
    sendResetLink($email);
} else if (isset($_GET['token'])) {
    // Validate token and check expiration time
    $token = $_GET['token'];

    // Query to select user's id, email, and current reset token (if any)
    $query = "SELECT id, email, reset_token FROM users WHERE reset_token = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $token);

    if ($stmt->execute()) {
        $result = $stmt->get_result();

        // If user exists and has a current reset token
        if ($row = $result->fetch_assoc()) {
            // Display password reset form
            echo "<h1>Reset Password</h1>";
            echo "<form action='reset-password.php' method='post'>";
            echo "<label for='newPassword'>New Password:</label><br>";
            echo "<input type='password' id='newPassword' name='newPassword'><br>";
            echo "<label for='confirmPassword'>Confirm New Password:</label><br>";
            echo "<input type='password' id='confirmPassword' name='confirmPassword'><br>";
            echo "<button type='submit' class='btn btn-primary'>Reset Password</button>";
            echo "</form>";

            // If form submitted
            if (isset($_POST['newPassword'])) {
                $newPassword = $_POST['newPassword'];
                $confirmPassword = $_POST['confirmPassword'];

                if ($newPassword === $confirmPassword) {
                    // Reset password using token and new password
                    resetPassword($token, $row['email'], $newPassword);
                } else {
                    echo "Passwords do not match";
                }
            }
        } else {
            echo "Invalid or expired token";
        }
    } else {
        // Error selecting user's id, email, and current reset token
        echo "Error selecting user's id, email, and current reset token: " . $conn->error;
    }
} else {
    // Display forgotten password form
    echo "<h1>Forgot Password</h1>";
    echo "<form action='forgot-password.php' method='post'>";
    echo "<label for='email'>Email:</label><br>";
    echo "<input type='text' id='email' name='email'><br>";
    echo "<button type='submit' class='btn btn-primary'>Send Reset Link</button>";
    echo "</form>";
}

// Close database connection
$conn->close();

?>


<?php

// Configuration
$databaseHost = 'localhost';
$databaseName = 'your_database_name';
$username = 'your_username';
$password = 'your_password';

// Connect to database
$conn = mysqli_connect($databaseHost, $username, $password, $databaseName);

if (!$conn) {
  die("Connection failed: " . mysqli_connect_error());
}

// Check if form has been submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

  // Get email from form
  $email = $_POST['email'];

  // Validate email
  if (empty($email)) {
    echo 'Please enter your email address.';
    exit;
  }

  // Query database to retrieve user ID
  $query = "SELECT id FROM users WHERE email = '$email'";
  $result = mysqli_query($conn, $query);

  if (mysqli_num_rows($result) > 0) {

    // Retrieve user ID and generate new password
    $row = mysqli_fetch_assoc($result);
    $userId = $row['id'];
    $newPassword = bin2hex(random_bytes(16));

    // Update password in database
    $query = "UPDATE users SET password_hash = '$newPassword' WHERE id = '$userId'";
    mysqli_query($conn, $query);

    // Send email with new password to user
    sendEmail($email, $newPassword);

  } else {
    echo 'Email not found.';
  }

} else {

  // Display form
?>

<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
  <input type="text" name="email" placeholder="Enter your email address">
  <button type="submit">Send New Password</button>
</form>

<?php

}

// Function to send email with new password
function sendEmail($to, $newPassword) {
  $subject = 'New Password';
  $body = "Your new password is: $newPassword";
  mail($to, $subject, $body);
}


<form action="" method="post">
    <input type="email" name="email" placeholder="Enter your email address">
    <button type="submit">Send Reset Link</button>
</form>


<?php

// Define the email configuration
$smtpHost = 'your-smtp-host';
$smtpPort = 587;
$fromEmail = 'your-email@example.com';
$fromName = 'Your Name';

// Check if the form has been submitted
if (isset($_POST['email'])) {

    // Connect to the database (assuming you're using a MySQL database)
    $conn = new mysqli('localhost', 'username', 'password', 'database');

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Get the user's email address from the form submission
    $email = $_POST['email'];

    // Query to check if the email address is valid
    $query = "SELECT * FROM users WHERE email = '$email'";

    // Execute the query
    $result = $conn->query($query);

    // Check if the email address is valid
    if ($result->num_rows > 0) {

        // Get the user's ID from the database
        $userId = $result->fetch_assoc()['id'];

        // Generate a password reset token
        $token = bin2hex(random_bytes(32));

        // Insert the password reset token into the database
        $query = "UPDATE users SET password_reset_token = '$token' WHERE id = '$userId'";
        $conn->query($query);

        // Send an email with a password reset link
        sendEmail($email, $token);
    }

    // Close the connection
    $conn->close();
}

// Function to send an email with a password reset link
function sendEmail($email, $token) {
    $headers = 'From: ' . $fromName . ' <' . $fromEmail . '>' . "\r
";
    $headers .= 'Content-Type: text/html; charset=UTF-8' . "\r
";

    $message = '<p>Hello!</p>';
    $message .= '<p>Click this link to reset your password:</p>';
    $message .= '<p><a href="' . $_SERVER['HTTP_HOST'] . '/reset-password.php?token=' . $token . '">Reset Password</a></p>';

    mail($email, 'Password Reset', $message, $headers);
}

?>


<?php

// Check if the form has been submitted
if (isset($_POST['new_password'])) {

    // Connect to the database (assuming you're using a MySQL database)
    $conn = new mysqli('localhost', 'username', 'password', 'database');

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Get the token from the URL parameter
    $token = $_GET['token'];

    // Query to check if the token is valid
    $query = "SELECT * FROM users WHERE password_reset_token = '$token'";

    // Execute the query
    $result = $conn->query($query);

    // Check if the token is valid
    if ($result->num_rows > 0) {

        // Get the user's ID from the database
        $userId = $result->fetch_assoc()['id'];

        // Insert the new password into the database
        $newPassword = $_POST['new_password'];
        $query = "UPDATE users SET password = '$newPassword' WHERE id = '$userId'";
        $conn->query($query);

        // Delete the token from the database
        $query = "DELETE FROM users WHERE password_reset_token = '$token'";
        $conn->query($query);
    }

    // Close the connection
    $conn->close();
}

?>


<?php

// Configuration
define('DB_HOST', 'localhost');
define('DB_USERNAME', 'your_username');
define('DB_PASSWORD', 'your_password');
define('DB_NAME', 'your_database');

// Connect to database
$conn = new mysqli(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

function forgotPassword($email)
{
    global $conn;

    // Query to retrieve user by email
    $query = "SELECT * FROM users WHERE email = '$email'";
    $result = $conn->query($query);

    if ($result->num_rows > 0) {
        // User found, generate new password and send reset link via email

        // Generate random password
        $newPassword = substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyz"), 0, 8);

        // Update user's password in database
        $query = "UPDATE users SET password = '$newPassword' WHERE email = '$email'";
        $conn->query($query);

        // Send reset link via email (example using PHPMailer)
        require_once 'PHPMailer/PHPMailerAutoload.php';

        $mail = new PHPMailer();

        $mail->isSMTP();
        $mail->Host       = 'smtp.example.com';
        $mail->SMTPAuth    = true;
        $mail->Username    = 'your_email@example.com';
        $mail->Password    = 'your_password';
        $mail->Port        = 587;

        $mail->setFrom('your_email@example.com', 'Your Name');
        $mail->addAddress($email);
        $mail->Subject = 'Reset Password';

        // Send email with reset link
        $resetLink = "http://example.com/reset-password.php?email=$email&newPassword=$newPassword";
        $body = "<p>Please click on the following link to reset your password:</p><p>$resetLink</p>";
        $mail->Body    = $body;

        if (!$mail->send()) {
            echo 'Message could not be sent.';
            echo 'Mailer Error: ' . $mail->ErrorInfo;
        } else {
            echo 'Email sent successfully!';
        }

        return true; // Reset link sent
    }

    return false; // User not found
}

// Example usage:
$email = "example@example.com";
if (forgotPassword($email)) {
    echo "Reset link sent to $email.";
} else {
    echo "User not found with email $email.";
}
?>


<?php

// Configuration settings
define('SITE_URL', 'http://example.com');
define('ADMIN_EMAIL', 'admin@example.com');

// Database connection settings
$db_host = 'localhost';
$db_username = 'your_username';
$db_password = 'your_password';
$db_name = 'your_database';

// Connect to database
$conn = new mysqli($db_host, $db_username, $db_password, $db_name);

if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

// Process forgot password request
if (isset($_POST['forgot_password'])) {
  $email = $_POST['email'];
  
  // Check if email exists in database
  $query = "SELECT * FROM users WHERE email = '$email'";
  $result = $conn->query($query);

  if ($result->num_rows > 0) {
    // Generate reset token and store it in user's record
    $token = bin2hex(random_bytes(32));
    $update_query = "UPDATE users SET password_reset_token = '$token' WHERE email = '$email'";
    $conn->query($update_query);

    // Send email with reset link
    sendEmail($email, $token);
  } else {
    echo "Email not found";
  }
}

// Function to send email with reset link
function sendEmail($email, $token) {
  $headers = 'From: Admin <' . ADMIN_EMAIL . '>' . "\r
";
  $subject = 'Reset Your Password';
  $body = '
    Dear user,

    Click on the following link to reset your password:

    ' . SITE_URL . '/reset-password.php?email=' . $email . '&token=' . $token . '

    Best regards,
    Admin
  ';

  mail($email, $subject, $body, $headers);
}

?>


<?php

// Configuration settings
define('SITE_URL', 'http://example.com');

// Database connection settings
$db_host = 'localhost';
$db_username = 'your_username';
$db_password = 'your_password';
$db_name = 'your_database';

// Connect to database
$conn = new mysqli($db_host, $db_username, $db_password, $db_name);

if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

// Process reset password request
if (isset($_GET['email']) && isset($_GET['token'])) {
  $email = $_GET['email'];
  $token = $_GET['token'];

  // Check if token is valid and user exists in database
  $query = "SELECT * FROM users WHERE email = '$email' AND password_reset_token = '$token'";
  $result = $conn->query($query);

  if ($result->num_rows > 0) {
    // Generate new password and store it in user's record
    $new_password = bin2hex(random_bytes(32));
    $update_query = "UPDATE users SET password = '$new_password' WHERE email = '$email'";
    $conn->query($update_query);

    echo 'Your password has been reset. Please log in with your new password.';
  } else {
    echo 'Invalid token or user not found';
  }
}

?>


<?php
require_once 'config.php'; // database connection settings

// handle form submission (forgot password)
if (isset($_POST['submit'])) {
  $email = $_POST['email'];
  forgot_password($email);
}

?>

<!DOCTYPE html>
<html>
<head>
  <title>Forgot Password</title>
</head>
<body>

<h1>Forgot Password</h1>

<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
  <label>Email:</label>
  <input type="text" name="email" value="<?php echo (isset($_POST['email'])) ? $_POST['email'] : ''; ?>">
  <br><br>
  <button type="submit" name="submit">Submit</button>
</form>

<?php
// forgot password function
function forgot_password($email) {
  global $db; // database connection

  // check if email exists in database
  $query = "SELECT * FROM users WHERE email = '$email'";
  $result = mysqli_query($db, $query);

  if (mysqli_num_rows($result) > 0) {
    // generate new password and send it to user's email
    $new_password = substr(md5(uniqid()), 0, 8); // generate random password
    $update_query = "UPDATE users SET password = '$new_password' WHERE email = '$email'";
    mysqli_query($db, $update_query);

    // send new password via email
    $to = $email;
    $subject = 'Your New Password';
    $message = 'Hello,
                Your new password is: '.$new_password.'
                Please log in with this new password.
                Sincerely,
                [Your Name]';
    mail($to, $subject, $message);

    echo "New password sent to your email.";
  } else {
    echo "Email not found. Please try again.";
  }
}
?>
</body>
</html>


<?php
// database connection settings
$db = mysqli_connect('localhost', 'username', 'password', 'database_name');

// error handling
if (!$db) {
  die("Connection failed: " . mysqli_connect_error());
}
?>


<?php

// Configuration
$db_host = 'localhost';
$db_username = 'your_username';
$db_password = 'your_password';
$db_name = 'your_database';

// Connect to database
$conn = new mysqli($db_host, $db_username, $db_password, $db_name);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Function to send password reset email
function send_reset_email($email, $token)
{
    // Set up SMTP server
    $smtp_server = 'your_smtp_server';
    $smtp_username = 'your_smtp_username';
    $smtp_password = 'your_smtp_password';

    // Send email using PHPMailer library (install via composer)
    require_once './vendor/autoload.php';
    use PHPMailer\PHPMailer;

    $mail = new PHPMailer();
    $mail->isSMTP();
    $mail->Host = $smtp_server;
    $mail->SMTPAuth = true;
    $mail->Username = $smtp_username;
    $mail->Password = $smtp_password;
    $mail->Port = 587;

    // Set email content
    $subject = 'Reset your password';
    $body = '
        Hello,

        To reset your password, click on the following link:
        <a href="' . $token . '">Reset Password</a>

        Best regards,
        Your Website
    ';

    $mail->setFrom('your_email@example.com', 'Your Name');
    $mail->addAddress($email);

    // Send email
    if (!$mail->send()) {
        echo "Mailer Error: " . $mail->ErrorInfo;
    } else {
        echo "Email sent successfully!";
    }
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get user input
    $email = $_POST['email'];

    // Check if email exists in database
    $sql = "SELECT * FROM users WHERE email = '$email'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // Generate password reset token (e.g. a random string)
        $token = bin2hex(random_bytes(32));

        // Update user data with token
        $sql = "UPDATE users SET password_reset_token = '$token' WHERE email = '$email'";
        $conn->query($sql);

        // Send password reset email
        send_reset_email($email, $token);
    } else {
        echo "Email not found in database.";
    }
}

// Close database connection
$conn->close();

?>


<?php

// Database connection settings
$dbHost = 'localhost';
$dbUsername = 'username';
$dbPassword = 'password';
$dbName = 'database';

// Email configuration
$fromEmail = 'your-email@example.com';
$fromName = 'Your Website';

// Set password reset token length
$tokenLength = 64;

// Create a connection to the database
$conn = new mysqli($dbHost, $dbUsername, $dbPassword, $dbName);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if form was submitted
if (isset($_POST['email'])) {

    // Validate email address
    $email = trim($_POST['email']);
    $email = filter_var($email, FILTER_VALIDATE_EMAIL);

    if ($email === false) {
        echo 'Invalid email';
        exit;
    }

    // Check if user exists in database
    $query = "SELECT * FROM users WHERE email = '$email'";
    $result = $conn->query($query);

    if ($result->num_rows > 0) {

        // Generate password reset token
        $token = bin2hex(random_bytes($tokenLength));

        // Update user's token in database
        $query = "UPDATE users SET password_reset_token = '$token' WHERE email = '$email'";
        $conn->query($query);

        // Send email with password reset link
        $subject = 'Reset Your Password';
        $body = "
            <p>Dear $email,</p>
            <p>To reset your password, click the following link:</p>
            <a href='reset_password.php?token=$token'>$token</a>
            <p>This link will only work for 1 hour.</p>
            ";
        $headers = 'From: ' . $fromName . ' <' . $fromEmail . '>' . "\r
" .
            'Reply-To: ' . $fromEmail . "\r
" .
            'X-Mailer: PHP/' . phpversion();

        mail($email, $subject, $body, $headers);

        echo 'A password reset link has been sent to your email. Please check your inbox.';
    } else {
        echo 'User not found';
    }
}

?>


<?php

// Database connection settings
$dbHost = 'localhost';
$dbUsername = 'username';
$dbPassword = 'password';
$dbName = 'database';

// Email configuration
$fromEmail = 'your-email@example.com';
$fromName = 'Your Website';

// Set password reset token length
$tokenLength = 64;

// Create a connection to the database
$conn = new mysqli($dbHost, $dbUsername, $dbPassword, $dbName);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if form was submitted
if (isset($_POST['password'])) {

    // Validate password and confirm password
    $password = trim($_POST['password']);
    $confirmPassword = trim($_POST['confirm_password']);

    if ($password !== $confirmPassword) {
        echo 'Passwords do not match';
        exit;
    }

    // Check if token is valid
    $token = $_GET['token'];
    $query = "SELECT * FROM users WHERE password_reset_token = '$token'";
    $result = $conn->query($query);

    if ($result->num_rows > 0) {

        // Update user's password in database
        $query = "UPDATE users SET password = '$password' WHERE password_reset_token = '$token'";
        $conn->query($query);

        // Delete token from database
        $query = "DELETE FROM users WHERE password_reset_token = '$token'";
        $conn->query($query);

        echo 'Password updated successfully';
    } else {
        echo 'Invalid token';
    }
}

?>


<?php

// Database connection settings
$dsn = 'mysql:host=localhost;dbname=mydatabase';
$username = 'myusername';
$password = 'mypassword';

try {
    // Establish database connection
    $pdo = new PDO($dsn, $username, $password);
} catch (PDOException $e) {
    echo 'Connection failed: ' . $e->getMessage();
    exit;
}

// Function to send password reset email
function sendPasswordResetEmail($email)
{
    // Generate a random token for the user
    $token = bin2hex(random_bytes(32));

    // Insert the token into the database (we'll create a new table later)
    $stmt = $pdo->prepare('INSERT INTO forgot_password_tokens SET email = :email, token = :token');
    $stmt->execute([':email' => $email, ':token' => $token]);

    // Email template for password reset
    $emailTemplate = 'Hello %s,
You have requested a password reset. Click this link to set a new password:
<a href="http://example.com/reset-password.php?token=%s">%s</a>

Best regards,
The Example Team';

    // Send the email using PHPMailer or your preferred method
    $emailBody = sprintf($emailTemplate, $_POST['username'], $token, $token);
    mail($_POST['email'], 'Password Reset', $emailBody);

    echo 'Email sent successfully!';
}

// Handle form submission (forgot password form)
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Check if the user exists in the database
    $stmt = $pdo->prepare('SELECT * FROM users WHERE email = :email');
    $stmt->execute([':email' => $_POST['email']]);
    $user = $stmt->fetch();

    if ($user) {
        // Send password reset email
        sendPasswordResetEmail($_POST['email']);
    } else {
        echo 'User not found!';
    }
} ?>


<?php
// Configuration
$database_host = 'localhost';
$database_username = 'username';
$database_password = 'password';
$database_name = 'database';

// Connect to database
$conn = new mysqli($database_host, $database_username, $database_password, $database_name);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get email from form submission
$email = $_POST['email'];

// Check if email is valid
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo 'Invalid email address';
    exit;
}

// Query to retrieve user ID and password reset token
$stmt = $conn->prepare("SELECT id, password_reset_token FROM users WHERE email = ?");
$stmt->bind_param('s', $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    // User exists, retrieve data
    while ($row = $result->fetch_assoc()) {
        $userId = $row['id'];
        $passwordResetToken = $row['password_reset_token'];

        // Generate a random password and update the database with new password
        $newPassword = substr(str_shuffle('0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ'), 0, 10);
        $stmt = $conn->prepare("UPDATE users SET password = ?, password_reset_token = ? WHERE id = ?");
        $stmt->bind_param('sss', $newPassword, $passwordResetToken, $userId);
        $stmt->execute();

        // Send email with new password and password reset link
        sendEmail($email, $newPassword);

        echo 'New password sent to your email';
    }
} else {
    echo 'User not found';
}

// Close database connection
$conn->close();
?>

<!-- Form to enter email for forgot password -->
<form action="" method="post">
  <input type="text" name="email" placeholder="Enter your email address">
  <button type="submit">Send new password</button>
</form>


<?php

// Configuration (use a mail server or library like PHPMailer)
$fromEmail = 'your-email@example.com';
$fromName = 'Your Name';

// Email content
$message = '
Dear '. $name .',

Your new password is: ' . $newPassword . '

To log in to your account, use the following link:
<a href="' . $passwordResetLink . '">Click here</a>

Best regards,
' . $fromName;

// Send email using mail() function
$headers = "From: " . $fromEmail;
$headers .= "Content-Type: text/html; charset=UTF-8";
mail($to, 'New Password', $message, $headers);

?>


<?php

// Configuration
$db_host = 'localhost';
$db_username = 'your_database_username';
$db_password = 'your_database_password';
$db_name = 'your_database_name';

// Connect to database
$conn = new mysqli($db_host, $db_username, $db_password, $db_name);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

function sendPasswordResetEmail($email, $resetLink)
{
    // Email configuration (replace with your own email settings)
    $to = $email;
    $subject = 'Forgot Password';
    $message = '<p>Please click on the following link to reset your password:</p><p>' . $resetLink . '</p>';
    $headers = "From: no-reply@example.com\r
";
    $headers .= "Content-Type: text/html; charset=ISO-8859-1\r
";

    mail($to, $subject, $message, $headers);
}

function checkPasswordResetToken($token)
{
    // Retrieve user data from database
    $query = "SELECT * FROM users WHERE password_reset_token = '" . mysqli_real_escape_string($conn, $token) . "'";
    $result = $conn->query($query);

    if ($result->num_rows > 0) {
        return true; // Token is valid
    } else {
        return false; // Token is invalid or expired
    }
}

function resetPassword($new_password)
{
    // Retrieve user data from database
    $query = "UPDATE users SET password_hash = '" . mysqli_real_escape_string($conn, password_hash($new_password, PASSWORD_DEFAULT)) . "' WHERE id = '1'"; // Replace with actual user ID
    $result = $conn->query($query);

    if ($result) {
        return true; // Password reset successful
    } else {
        return false; // Password reset failed
    }
}

// Handle forgot password form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];

    // Check if email is registered in database
    $query = "SELECT * FROM users WHERE email = '" . mysqli_real_escape_string($conn, $email) . "'";
    $result = $conn->query($query);

    if ($result->num_rows > 0) {
        // Generate password reset token
        $token = bin2hex(random_bytes(16));

        // Update user data in database with new token
        $query = "UPDATE users SET password_reset_token = '" . mysqli_real_escape_string($conn, $token) . "' WHERE email = '" . mysqli_real_escape_string($conn, $email) . "'";
        $result = $conn->query($query);

        if ($result) {
            // Send email with reset link
            $resetLink = "https://example.com/reset-password/" . $token;
            sendPasswordResetEmail($email, $resetLink);
            echo "An email has been sent to your registered email address with a password reset link.";
        } else {
            echo "Failed to update user data.";
        }
    } else {
        echo "No account found with this email address.";
    }
}

// Close database connection
$conn->close();

?>


<?php

require_once 'db.php'; // assume you have a db.php file that connects to your database

// Set up email settings (update with your own credentials)
$from_email = "your-email@example.com";
$smtp_server = "your-smtp-server";
$smtp_username = "your-smtp-username";
$smtp_password = "your-smtp-password";

// Check if form has been submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get the user's email from the POST data
    $email = $_POST['email'];

    // Query database to find the user with the given email
    $query = "SELECT * FROM users WHERE email = :email";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':email', $email);
    $stmt->execute();
    $user_data = $stmt->fetch();

    // If user exists, generate a random password and send them an email
    if ($user_data) {
        // Generate a random password
        $password = substr(uniqid(mt_rand(), true), 0, 10);

        // Update the user's password in the database (use a secure method to store passwords)
        $query = "UPDATE users SET password_hash = :password_hash WHERE email = :email";
        $stmt = $db->prepare($query);
        $stmt->bindParam(':email', $email);
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $stmt->bindParam(':password_hash', $hashed_password);
        $stmt->execute();

        // Send the user an email with a link to reset their password
        $subject = "Reset your password";
        $message = "Click this link to reset your password: <a href='" . $_SERVER['HTTP_HOST'] . "/reset-password.php?email=" . urlencode($email) . "&password=" . urlencode($password) . "'>Reset Password</a>";
        send_email($from_email, $smtp_server, $smtp_username, $smtp_password, $subject, $message);
    }

    // Display a success message if user was found
    echo "<p>Password reset email sent to your email address.</p>";
}

// Function to send an email using PHPMailer (update with your own credentials)
function send_email($from_email, $smtp_server, $smtp_username, $smtp_password, $subject, $message) {
    require_once 'PHPMailer/PHPMailer.php';
    require_once 'PHPMailer/SMTP.php';

    $mail = new PHPMailer\PHPMailer();
    $mail->isSMTP();
    $mail->Host = $smtp_server;
    $mail->Username = $smtp_username;
    $mail->Password = $smtp_password;
    $mail->Port = 587;
    $mail->SMTPSecure = 'tls';
    $mail->setFrom($from_email);
    $mail->addAddress($from_email);
    $mail->Subject = $subject;
    $mail->Body = $message;
    if (!$mail->send()) {
        echo "Mailer Error: " . $mail->ErrorInfo;
    }
}

?>

<!-- HTML form to submit the user's email -->
<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
    <input type="email" name="email" placeholder="Enter your email address"><br><br>
    <button type="submit">Submit</button>
</form>



function forgot_password($email) {
  // Check if email exists in database
  $query = "SELECT * FROM users WHERE email = '$email'";
  $result = mysqli_query($conn, $query);
  
  if (mysqli_num_rows($result) == 0) {
    // Email not found
    return array('error' => 'Email not found');
  }
  
  // Generate a random reset token
  $reset_token = bin2hex(random_bytes(16));
  
  // Update user's reset token in database
  $query = "UPDATE users SET reset_token = '$reset_token' WHERE email = '$email'";
  mysqli_query($conn, $query);
  
  // Send password reset email
  send_reset_email($email, $reset_token);
  
  return array('success' => 'Email sent with password reset link');
}

function send_reset_email($email, $reset_token) {
  // Set up email headers and body
  $to = $email;
  $subject = "Reset your password";
  $body = "Click this link to reset your password: <a href='http://example.com/reset-password?token=$reset_token'>Reset Password</a>";
  
  // Send email using PHPMailer or a similar library
  $mail = new PHPMailer();
  $mail->isSMTP();
  $mail->Host = 'smtp.example.com';
  $mail->Port = 587;
  $mail->SMTPAuth = true;
  $mail->Username = 'your_email@example.com';
  $mail->Password = 'your_password';
  $mail->setFrom('your_email@example.com', 'Your Name');
  $mail->addAddress($to);
  $mail->Subject = $subject;
  $mail->Body = $body;
  $mail->send();
}


$email = $_POST['email'];
$result = forgot_password($email);

if ($result['error']) {
  echo 'Error: ' . $result['error'];
} else {
  echo 'Email sent with password reset link';
}


CREATE TABLE users (
  id INT PRIMARY KEY,
  email VARCHAR(255) NOT NULL,
  password_hash CHAR(60) NOT NULL,
  reset_token VARCHAR(100) NOT NULL,
  reset_expires TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP + INTERVAL 1 HOUR
);


<?php

// Configuration
$secret_key = 'your-secret-key';

// Check if form has been submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $email = $_POST['email'];

  // Validate email address
  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo "Invalid email address.";
    exit;
  }

  // Query database for user with matching email address
  $db = new mysqli('your-host', 'your-username', 'your-password', 'your-database');
  if ($db->connect_error) {
    die("Connection failed: " . $db->connect_error);
  }
  $query = "SELECT * FROM users WHERE email = '$email'";
  $result = $db->query($query);

  // Check if user exists
  if ($result->num_rows > 0) {
    $user_data = $result->fetch_assoc();

    // Generate a random reset token
    $reset_token = bin2hex(random_bytes(16));

    // Update database with new reset token
    $update_query = "UPDATE users SET reset_token = '$reset_token' WHERE email = '$email'";
    $db->query($update_query);

    // Send password reset email (optional)
    $to_email = $user_data['email'];
    $subject = 'Password Reset';
    $message = "Click here to reset your password: <a href='" . $_SERVER['HTTP_HOST'] . "/reset_password.php?token=" . $reset_token . "'>Reset Password</a>";
    mail($to_email, $subject, $message);

    echo "A password reset email has been sent to your email address.";
  } else {
    echo "Email address not found.";
  }

  // Close database connection
  $db->close();
}

?>


<?php

// Configuration
$secret_key = 'your-secret-key';

// Check if form has been submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $token = $_GET['token'];
  $new_password = $_POST['new_password'];
  $confirm_password = $_POST['confirm_password'];

  // Validate password and confirmation
  if ($new_password !== $confirm_password) {
    echo "Passwords do not match.";
    exit;
  }

  // Query database for user with matching reset token
  $db = new mysqli('your-host', 'your-username', 'your-password', 'your-database');
  if ($db->connect_error) {
    die("Connection failed: " . $db->connect_error);
  }
  $query = "SELECT * FROM users WHERE reset_token = '$token'";
  $result = $db->query($query);

  // Check if user exists
  if ($result->num_rows > 0) {
    $user_data = $result->fetch_assoc();

    // Update database with new password hash
    $update_query = "UPDATE users SET password_hash = '" . password_hash($new_password, PASSWORD_DEFAULT) . "' WHERE email = '$user_data[email]'";
    $db->query($update_query);

    // Delete reset token from database
    $delete_query = "DELETE FROM users WHERE reset_token = '$token'";
    $db->query($delete_query);

    echo "Password has been successfully updated.";
  } else {
    echo "Invalid reset token.";
  }

  // Close database connection
  $db->close();
}

?>


CREATE TABLE users (
  id INT PRIMARY KEY AUTO_INCREMENT,
  email VARCHAR(255) NOT NULL UNIQUE,
  password VARCHAR(255) NOT NULL,
  reset_token VARCHAR(255),
  reset_token_expires TIMESTAMP
);


<?php

// Set up database connection
$conn = mysqli_connect('localhost', 'username', 'password', 'database');

// Check connection
if (!$conn) {
  die("Connection failed: " . mysqli_connect_error());
}

// Define function to send reset token via email
function send_reset_token($email, $token) {
  // Get user's name from database (optional)
  $query = "SELECT name FROM users WHERE email = '$email'";
  $result = mysqli_query($conn, $query);
  if ($result && mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
      $name = $row['name'];
    }
  }

  // Send email with reset token
  $subject = "Reset Password";
  $body = "
    Dear $name,

    Your password reset token is: $token

    Please visit our website to reset your password.

    Best regards,
    [Your Name]
  ";

  mail($email, $subject, $body);
}

// Define function to handle forgot password request
function forgot_password() {
  // Get user input (email)
  $email = $_POST['email'];

  // Check if email exists in database
  $query = "SELECT * FROM users WHERE email = '$email'";
  $result = mysqli_query($conn, $query);
  if ($result && mysqli_num_rows($result) > 0) {
    // Get user's ID from database
    while ($row = mysqli_fetch_assoc($result)) {
      $user_id = $row['id'];
    }

    // Generate random reset token and store it in database
    $token = bin2hex(random_bytes(32));
    $query = "UPDATE users SET reset_token = '$token', reset_token_expires = NOW() + INTERVAL 30 MINUTE WHERE id = '$user_id'";
    mysqli_query($conn, $query);

    // Send email with reset token
    send_reset_token($email, $token);
  } else {
    echo 'Email not found';
  }
}

// Handle form submission (POST)
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  forgot_password();
}

?>


<?php

// Define the database connection parameters
$db_host = 'localhost';
$db_username = 'your_database_username';
$db_password = 'your_database_password';
$db_name = 'your_database_name';

// Create a connection to the database
$conn = new mysqli($db_host, $db_username, $db_password, $db_name);

// Check if the connection was successful
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Define the function to send the password reset email
function send_reset_email($email) {
    // Generate a random password and store it in a session variable
    $password = rand(100000, 999999); // Generate a 6-digit random number
    $_SESSION['password'] = $password;

    // SQL query to update the user's password
    $sql = "UPDATE users SET password = '$password' WHERE email = '$email'";
    if ($conn->query($sql) === TRUE) {
        // Send an email with the new password and a link to reset it
        $to = $email;
        $subject = 'Reset your password';
        $body = '
            Dear user,
            
            Your temporary password is: '.$password.'
            
            Click on this link to reset your password: <a href="reset_password.php?email='.$email.'&token='.uniqid().'">Reset Password</a>
            ';
        mail($to, $subject, $body);

        echo 'Email sent successfully!';
    } else {
        echo 'Error updating user data: ' . $conn->error;
    }
}

// Check if the form has been submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get the email address from the form input
    $email = $_POST['email'];

    // Validate the email address
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo 'Invalid email address';
        return;
    }

    // Call the function to send the password reset email
    send_reset_email($email);
}

// Close the database connection
$conn->close();

?>


<?php

// Define the database connection parameters (same as above)

// Check if the form has been submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get the email address and token from the URL query string
    $email = $_GET['email'];
    $token = $_GET['token'];

    // SQL query to update the user's password (using the session variable)
    $sql = "UPDATE users SET password = '".$_SESSION['password']."' WHERE email = '$email' AND token = '$token'";
    if ($conn->query($sql) === TRUE) {
        echo 'Password reset successfully!';
    } else {
        echo 'Error updating user data: ' . $conn->error;
    }
}

// Close the database connection
$conn->close();

?>


// config.php (database connection settings)
<?php
$host = 'localhost';
$dbname = 'mydatabase';
$username = 'myuser';
$password = 'mypassword';

$conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
?>

// forgot_password.php
<?php
require_once 'config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // get user input
    $email = $_POST['email'];

    // check if email address exists in database
    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user) {
        // generate password reset token
        $token = bin2hex(random_bytes(32));

        // update user's record with password reset token
        $stmt = $conn->prepare("UPDATE users SET password_reset_token = ? WHERE email = ?");
        $stmt->execute([$token, $email]);

        // send password reset link via email
        $subject = 'Reset Your Password';
        $body = '<a href="' . $_SERVER['REQUEST_URI'] . '?token=' . $token . '">Click here to reset your password</a>';
        mail($user['email'], $subject, $body);

        echo "Password reset link sent to your email. Please check your inbox.";
    } else {
        echo "Email address not found in our database.";
    }
}
?>

<!-- form -->
<form action="" method="post">
    <label for="email">Enter your email address:</label>
    <input type="email" id="email" name="email">
    <button type="submit">Send Password Reset Link</button>
</form>


// reset_password.php
<?php
require_once 'config.php';

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    // get token from URL parameter
    $token = $_GET['token'];

    // validate token and check if it matches user's record
    $stmt = $conn->prepare("SELECT * FROM users WHERE password_reset_token = ?");
    $stmt->execute([$token]);
    $user = $stmt->fetch();

    if ($user) {
        // create a new form for password reset
        ?>
        <form action="" method="post">
            <label for="new_password">Enter your new password:</label>
            <input type="password" id="new_password" name="new_password">
            <button type="submit">Reset Password</button>
        </form>

        <?php
    } else {
        echo "Invalid token. Please try again.";
    }
}
?>

<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // get user input (new password)
    $new_password = $_POST['new_password'];

    // update user's record with new password
    $stmt = $conn->prepare("UPDATE users SET password = ? WHERE id = ?");
    $stmt->execute([$new_password, $user['id']]);

    echo "Password reset successfully!";
}
?>


// db.php
<?php
define('DB_HOST', 'localhost');
define('DB_USER', 'username');
define('DB_PASSWORD', 'password');
define('DB_NAME', 'database_name');

function connectToDatabase() {
    $conn = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    return $conn;
}
?>


<?php
require_once 'db.php';

if (isset($_POST['submit'])) {

    // Connect to database
    $conn = connectToDatabase();

    // Sanitize input
    $username = sanitizeInput($_POST['username']);

    // Query for user's email and password
    $query = "SELECT email, password FROM users WHERE username='$username'";
    $result = $conn->query($query);

    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $email = $row['email'];
            $password = $row['password'];

            // Send email to user with password
            sendEmail($username, $email, $password);
            echo "Password has been sent to your registered email address.";
        }
    } else {
        echo "User not found.";
    }

    // Close database connection
    $conn->close();

} else {
?>
<!-- Form for forgot password -->
<form action="" method="post">
    <label>Username:</label>
    <input type="text" name="username"><br><br>
    <input type="submit" name="submit" value="Submit">
</form>
<?php
}

// Function to send email with user's password
function sendEmail($username, $email, $password) {
    // Email subject and body
    $subject = 'Your Password for '.$username.'';
    $body = 'Your current password is: '.$password.'';

    // Send email using PHP mail function (for simplicity)
    if(mail($email, $subject, $body)) {
        echo "Email sent successfully.";
    } else {
        echo "Failed to send email.";
    }
}
?>


// Update db.php with the following code:
function hashPassword($password) {
    return password_hash($password, PASSWORD_DEFAULT);
}

function verifyPassword($input_password, $stored_password) {
    if(password_verify($input_password, $stored_password)) {
        return true;
    } else {
        return false;
    }
}


CREATE TABLE users (
  id INT PRIMARY KEY AUTO_INCREMENT,
  email VARCHAR(255) NOT NULL,
  password_hash VARCHAR(255) NOT NULL
);


<?php

// Configuration
$secret_key = 'your_secret_key_here'; // Keep this secret!
$max_attempts = 5; // Maximum number of attempts before locking account
$lockout_time = 300; // Time in seconds to lock out account (e.g. 5 minutes)

// Validate request
if ($_SERVER['REQUEST_METHOD'] != 'POST') {
    header('Location: index.php');
    exit;
}

// Extract data from form submission
$email = $_POST['email'];

// Check if email exists in database
$query = "SELECT * FROM users WHERE email = '$email'";
$result = mysqli_query($conn, $query);
if (mysqli_num_rows($result) == 0) {
    echo 'Email not found.';
    exit;
}

// Get user ID and password hash
$user_id = mysqli_fetch_assoc($result)['id'];
$password_hash = mysqli_fetch_assoc($result)['password_hash'];

// Check for lockout status
$lockout_timestamp = isset($_SESSION['lockout_timestamp']) ? $_SESSION['lockout_timestamp'] : 0;
if ($lockout_timestamp > time()) {
    echo 'Your account has been locked out. Please try again in 5 minutes.';
    exit;
}

// Generate random password and send email with reset link
$password = substr(str_shuffle('abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789'), 0, 10);
$email_body = "Reset your password: <a href='reset_password.php?token=$password&user_id=$user_id'>Click here</a>";
$subject = 'Password Reset';

// Send email using PHPMailer or similar library
$mail->setFrom('your_email@example.com', 'Your Name');
$mail->addAddress($email);
$mail->Subject = $subject;
$mail->Body = $email_body;
if (!$mail->send()) {
    echo 'Error sending email: ' . $mail->ErrorInfo;
    exit;
}

// Store password in session for later use
$_SESSION['password'] = $password;

echo "A password reset link has been sent to your email. Please click on the link and follow instructions.";

?>


<?php

// Configuration
$secret_key = 'your_secret_key_here'; // Keep this secret!

// Validate request
if ($_SERVER['REQUEST_METHOD'] != 'POST') {
    header('Location: index.php');
    exit;
}

// Extract data from form submission
$token = $_GET['token'];
$user_id = $_GET['user_id'];

// Check if token is valid
if (isset($_SESSION['password']) && $_SESSION['password'] == $token) {
    // User has already submitted new password, redirect to login page
    header('Location: login.php');
    exit;
}

// Generate random password and store it in session for later use
$password = substr(str_shuffle('abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789'), 0, 10);
$_SESSION['password'] = $password;

echo "Enter your new password below. You will be redirected to the login page after submission.";

?>


<?php

// Validate request
if ($_SERVER['REQUEST_METHOD'] != 'POST') {
    header('Location: index.php');
    exit;
}

// Extract data from form submission
$email = $_POST['email'];
$password = $_POST['password'];

// Check if user has already submitted new password
if (isset($_SESSION['password'])) {
    // User has already reset their password, remove session variable and proceed to login
    unset($_SESSION['password']);
}

// Login logic goes here...

?>


<?php

// Include configuration files
require_once 'config.php';

// Check if the form has been submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get user input
    $email = trim($_POST['email']);

    // Validate email address
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        die('Invalid email address');
    }

    // Check if the user exists in the database
    $stmt = $mysqli->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 0) {
        die('No account found with this email address');
    }

    // Generate a reset token
    $reset_token = bin2hex(random_bytes(32));
    $expiry_time = time() + (60 * 5); // 5 minutes

    // Update the user's reset token in the database
    $stmt = $mysqli->prepare("UPDATE users SET reset_token = ?, expiry_time = ? WHERE email = ?");
    $stmt->bind_param("ss", $reset_token, $expiry_time, $email);
    $stmt->execute();

    // Send a password reset email to the user
    send_reset_email($email, $reset_token);

    echo 'A password reset email has been sent to your email address';
} else {
?>
<form action="" method="post">
    <label for="email">Email Address:</label>
    <input type="email" id="email" name="email" required><br><br>
    <button type="submit">Reset Password</button>
</form>
<?php
}


function send_reset_email($email, $reset_token) {
    // Configuration variables
    $from_email = 'your-email@example.com';
    $from_password = 'your-password';

    // Create a message
    $subject = 'Password Reset for Your Account';
    $body = "Click here to reset your password: <a href='http://your-website.com/reset_password.php?email=$email&reset_token=$reset_token'>Reset Password</a>";

    // Send the email using PHPMailer
    require_once 'PHPMailerAutoload.php';

    $mail = new PHPMailer();

    $mail->isSMTP();
    $mail->Host = 'smtp.example.com';
    $mail->Port = 587;
    $mail->Username = $from_email;
    $mail->Password = $from_password;

    $mail->setFrom($from_email, 'Your Name');
    $mail->addAddress($email);

    $mail->Subject = $subject;
    $mail->Body = $body;
    $mail->AltBody = 'This is a plain-text message body';

    if (!$mail->send()) {
        echo 'Error sending email';
    } else {
        // Update the user's reset token in the database
        // (not shown in this example)
    }
}


<?php

// Include configuration files
require_once 'config.php';

// Check if the form has been submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get user input
    $email = trim($_POST['email']);
    $reset_token = trim($_POST['reset_token']);

    // Validate reset token and email address
    $stmt = $mysqli->prepare("SELECT id FROM users WHERE email = ? AND reset_token = ?");
    $stmt->bind_param("ss", $email, $reset_token);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 0) {
        die('Invalid reset token or email address');
    }

    // Get the user's current password hash
    $user_id = $result->fetch_assoc()['id'];
    $current_password_hash = get_password_hash($user_id);

    // Check if the reset token has expired
    if (time() > $stmt->get_result()->fetch_assoc()['expiry_time']) {
        die('Reset token has expired');
    }

    // Get the new password from user input
    $new_password = trim($_POST['new_password']);

    // Hash and store the new password in the database
    update_password_hash($user_id, hash_password($new_password));

    echo 'Password reset successfully';
} else {
?>
<form action="" method="post">
    <label for="email">Email Address:</label>
    <input type="email" id="email" name="email" value="<?php echo $_GET['email'] ?>" required readonly><br><br>
    <label for="reset_token">Reset Token:</label>
    <input type="text" id="reset_token" name="reset_token" value="<?php echo $_GET['reset_token'] ?>" required readonly><br><br>
    <label for="new_password">New Password:</label>
    <input type="password" id="new_password" name="new_password" required><br><br>
    <button type="submit">Reset Password</button>
</form>
<?php
}


function get_password_hash($user_id) {
    $stmt = $mysqli->prepare("SELECT password FROM users WHERE id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    return $stmt->get_result()->fetch_assoc()['password'];
}

function update_password_hash($user_id, $new_password_hash) {
    $stmt = $mysqli->prepare("UPDATE users SET password = ? WHERE id = ?");
    $stmt->bind_param("si", $new_password_hash, $user_id);
    $stmt->execute();
}

function hash_password($password) {
    // Use a strong hashing algorithm like bcrypt or Argon2
    return password_hash($password, PASSWORD_DEFAULT);
}


<?php

// Configuration
$secret_key = 'your_secret_key_here'; // Replace with your secret key

// Function to send password reset email
function send_reset_email($email, $token) {
  $subject = 'Reset Password';
  $body = '<p>Click on the link below to reset your password:</p><a href="' . $_SERVER['HTTP_HOST'] . '/reset-password?token=' . $token . '">Reset Password</a>';
  mail($email, $subject, $body);
}

// Function to handle forgot password request
function forgot_password() {
  if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get form data
    $email = $_POST['email'];

    // Validate email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
      echo 'Invalid email address';
      exit;
    }

    // Retrieve user from database
    $db = new PDO('mysql:host=localhost;dbname=your_database_name', 'your_username', 'your_password');
    $stmt = $db->prepare('SELECT * FROM users WHERE email = :email AND reset_token IS NULL');
    $stmt->execute([':email' => $email]);
    $user = $stmt->fetch();

    if ($user) {
      // Generate random token
      $token = bin2hex(random_bytes(16));

      // Store token in database
      $db->exec('UPDATE users SET reset_token = :token, reset_expires = NOW() + INTERVAL 1 HOUR WHERE email = :email');
      $db->close();

      // Send password reset email
      send_reset_email($email, $token);

      echo 'Reset email sent';
    } else {
      echo 'Email not found';
    }
  } else {
    // Display form
    ?>
    <h1>Forgot Password</h1>
    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
      <label for="email">Email:</label>
      <input type="text" id="email" name="email"><br><br>
      <button type="submit">Submit</button>
    </form>
    <?php
  }
}

forgot_password();

?>


<?php
require_once 'dbconnect.php'; // connect to database

if (isset($_POST['submit'])) {
  $email = $_POST['email'];
  
  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo 'Invalid email address';
    exit;
  }
  
  $sql = "SELECT * FROM users WHERE email = '$email'";
  $result = mysqli_query($conn, $sql);
  
  if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
      $user_id = $row['id'];
      $username = $row['username'];
      
      // generate reset password token
      $token = bin2hex(random_bytes(32));
      $sql = "UPDATE users SET reset_token = '$token' WHERE id = '$user_id'";
      mysqli_query($conn, $sql);
      
      // send email with reset link
      $subject = 'Reset your password';
      $message = "Click on the link below to reset your password:
<a href='http://example.com/reset_password.php?token=$token&email=$email'>Reset Password</a>";
      mail($email, $subject, $message);
      
      echo 'Password reset email sent. Please check your email for further instructions.';
    }
  } else {
    echo 'Email not found in database';
  }
} else {
?>
<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
  <label for="email">Enter your email address:</label>
  <input type="email" id="email" name="email"><br><br>
  <button type="submit" name="submit">Send Reset Email</button>
</form>
<?php } ?>


<?php
require_once 'dbconnect.php'; // connect to database

if (isset($_GET['token']) && isset($_GET['email'])) {
  $token = $_GET['token'];
  $email = $_GET['email'];
  
  $sql = "SELECT * FROM users WHERE email = '$email' AND reset_token = '$token'";
  $result = mysqli_query($conn, $sql);
  
  if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
      $user_id = $row['id'];
      
      // allow user to change password
      ?>
<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
  <label for="new_password">Enter new password:</label>
  <input type="password" id="new_password" name="new_password"><br><br>
  <button type="submit" name="submit">Change Password</button>
</form>
<?php
    }
  } else {
    echo 'Invalid token';
  }
} else {
?>
<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
  <label for="email">Enter your email address:</label>
  <input type="email" id="email" name="email"><br><br>
  <button type="submit" name="submit">Send Reset Email</button>
</form>
<?php } ?>

<?php
if (isset($_POST['submit'])) {
  $new_password = $_POST['new_password'];
  
  if (!preg_match('/^(?=.*[a-zA-Z])(?=.*[0-9]).{8,}$/', $new_password)) {
    echo 'Password must be at least 8 characters long and contain a letter and a number';
    exit;
  }
  
  $sql = "UPDATE users SET password = '$new_password' WHERE id = '$user_id'";
  mysqli_query($conn, $sql);
  
  echo 'Password changed successfully!';
}
?>


<?php

require_once 'database.php'; // assuming you have a database connection class

// Check if the form has been submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $email = $_POST['email'];
  
  // Validate email address
  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    header('Location: forgot_password.php?error=invalid_email');
    exit;
  }
  
  // Query the database to retrieve the user's ID and password hash
  $query = "SELECT id, password_hash FROM users WHERE email = ?";
  $stmt = $db->prepare($query);
  $stmt->execute([$email]);
  $result = $stmt->fetch();
  
  if ($result) {
    // Generate a reset token
    $token = bin2hex(random_bytes(32));
    
    // Update the user's record with the new reset token
    $query = "UPDATE users SET password_reset_token = ? WHERE id = ?";
    $stmt = $db->prepare($query);
    $stmt->execute([$token, $result['id']]);
    
    // Send a password reset email to the user
    send_password_reset_email($email, $token);
    
    header('Location: login.php?success=forgot_password');
    exit;
  } else {
    header('Location: forgot_password.php?error=invalid_email_or_password');
    exit;
  }
}

// Function to generate and send a password reset email
function send_password_reset_email($email, $token) {
  $subject = 'Password Reset';
  $message = "Click the link below to reset your password:
  <a href='reset_password.php?token={$token}'>Reset Password</a>";
  
  mail($email, $subject, $message);
}

// Display the forgot password form
?>

<form action="" method="post">
  <label for="email">Email:</label>
  <input type="email" name="email" id="email" required>
  <button type="submit">Submit</button>
</form>

<?php if (isset($_GET['error'])): ?>
  <p style="color: red;">Error: <?= $_GET['error'] ?></p>
<?php endif; ?>


<?php

class Database {
  private $db;
  
  public function __construct() {
    // Connect to the database
    $this->db = new PDO('mysql:host=localhost;dbname=mydatabase', 'username', 'password');
  }
  
  public function prepare($query) {
    return $this->db->prepare($query);
  }
  
  public function execute($stmt, $params) {
    return $stmt->execute($params);
  }
  
  public function fetch($result) {
    return $result->fetch();
  }
}


<?php

// Configuration settings
$site_name = 'Your Site Name';
$from_email = 'your-email@example.com';
$smtp_password = 'your-smtp-password'; // if using SMTP server, set your password here

// Database connection settings
$db_host = 'localhost';
$db_username = 'your-db-username';
$db_password = 'your-db-password';
$db_name = 'your-db-name';

// Connect to the database
$conn = new mysqli($db_host, $db_username, $db_password, $db_name);

// Check if the user has submitted the forgot password form
if (isset($_POST['submit'])) {

    // Get the email address from the form data
    $email = $_POST['email'];

    // Query to retrieve the user's data
    $query = "SELECT * FROM users WHERE email = '$email'";
    $result = mysqli_query($conn, $query);

    // If a user is found, generate a temporary password and send them an email with the link to reset their password
    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {

            // Generate a random temporary password
            $password = uniqid('', true);
            $hashed_password = md5($password);

            // Query to update the user's hashed password in the database
            $query_update = "UPDATE users SET hashed_password = '$hashed_password' WHERE email = '$email'";
            mysqli_query($conn, $query_update);

            // Send an email with a link to reset their password
            $subject = 'Temporary Password Reset Link';
            $body = '
                <html>
                    <head></head>
                    <body>
                        Hello '.$row['first_name'].',
                        <p>Please click on the following link to reset your password:</p>
                        <a href="reset-password.php?email='.$email.'&hashed_password='.$hashed_password.'">Reset Password</a>
                    </body>
                </html>';

            // If using SMTP server, use this configuration
            if (isset($smtp_password)) {
                $headers = 'From: ' . $from_email . "\r
" .
                            'Reply-To: ' . $email . "\r
" .
                            'X-Mailer: PHP/' . phpversion();
                mail($email, $subject, $body, $headers);
            } else { // If not using SMTP server, use this configuration
                mail($email, $subject, $body);
            }
        }
    }

    // Display a message to let the user know their password has been reset and an email sent with the link to reset it.
    echo 'A temporary password has been generated and emailed to you. Please check your email for further instructions.';
} else {
    ?>
    <form action="" method="post">
        <input type="email" name="email" placeholder="Enter your Email Address">
        <button type="submit" name="submit">Submit</button>
    </form>
    <?php
}
?>


<?php

// Configuration settings
$site_name = 'Your Site Name';
$from_email = 'your-email@example.com';

// Database connection settings
$db_host = 'localhost';
$db_username = 'your-db-username';
$db_password = 'your-db-password';
$db_name = 'your-db-name';

// Connect to the database
$conn = new mysqli($db_host, $db_username, $db_password, $db_name);

// Get the email address and hashed password from the URL parameters
$email = $_GET['email'];
$hashed_password = $_GET['hashed_password'];

// Query to retrieve the user's data
$query = "SELECT * FROM users WHERE email = '$email' AND hashed_password = '$hashed_password'";
$result = mysqli_query($conn, $query);

// If a user is found and their hashed password matches, display a form to let them change their password.
if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        ?>
        <form action="" method="post">
            <input type="hidden" name="email" value="<?php echo $email; ?>">
            <input type="password" name="new_password" placeholder="Enter New Password">
            <button type="submit" name="change_password">Change Password</button>
        </form>
        <?php
    }
} else {
    // Display an error message if the user does not exist or their hashed password does not match.
    echo 'Invalid email address or temporary password.';
}

// If the form has been submitted, update the user's hashed password in the database with their new password.
if (isset($_POST['change_password'])) {

    // Get the email address and new password from the form data
    $email = $_POST['email'];
    $new_password = md5($_POST['new_password']);

    // Query to update the user's hashed password in the database
    $query_update = "UPDATE users SET hashed_password = '$new_password' WHERE email = '$email'";
    mysqli_query($conn, $query_update);

    // Display a success message and let them log in with their new password.
    echo 'Password changed successfully. You can now log in with your new password.';
}
?>


// config.php (assuming you have a database connection configuration file)
require 'config.php';

// functions.php (assuming you have a custom functions file)
function send_reset_email($email, $token) {
  // Send an email with the reset link
  $to = $email;
  $subject = "Reset Your Password";
  $body = "Click here to reset your password: <a href='http://example.com/reset-password?token=" . $token . "'>Reset Password</a>";
  mail($to, $subject, $body);
}

function forgot_password() {
  // Get the email from the form
  $email = $_POST['email'];

  // Check if the email exists in the database
  $query = "SELECT * FROM users WHERE email = :email";
  $stmt = $pdo->prepare($query);
  $stmt->bindParam(':email', $email);
  $stmt->execute();
  $user = $stmt->fetch();

  if ($user) {
    // Generate a random token
    $token = bin2hex(random_bytes(16));

    // Update the user's reset token in the database
    $query = "UPDATE users SET reset_token = :token WHERE email = :email";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':token', $token);
    $stmt->bindParam(':email', $email);
    $stmt->execute();

    // Send a reset email to the user
    send_reset_email($email, $token);

    // Display a success message to the user
    echo "Password reset link sent to your email.";
  } else {
    // Display an error message if the email is not found
    echo "Email not found in our records.";
  }
}

// Handle form submission
if (isset($_POST['submit'])) {
  forgot_password();
}


function hash_password($password) {
    return password_hash($password, PASSWORD_DEFAULT);
}

function verify_password($hashed_password, $password) {
    return password_verify($password, $hashed_password);
}


function forgot_password($email) {
    // Check if user exists in database
    $query = "SELECT * FROM users WHERE email = '$email'";
    $result = mysqli_query($conn, $query);
    if (mysqli_num_rows($result) == 0) {
        echo "User not found.";
        return;
    }

    // Generate a random token
    $token = bin2hex(random_bytes(32));

    // Update user's password reset token in database
    $query = "UPDATE users SET password_reset_token = '$token' WHERE email = '$email'";
    mysqli_query($conn, $query);

    // Send password reset link to user via email
    $subject = "Reset Password";
    $message = "Click here to reset your password: <a href='https://example.com/reset-password/$token'>Reset Password</a>";
    mail($email, $subject, $message);
}


function reset_password($token) {
    // Check if token is valid
    $query = "SELECT * FROM users WHERE password_reset_token = '$token'";
    $result = mysqli_query($conn, $query);
    if (mysqli_num_rows($result) == 0) {
        echo "Invalid token.";
        return;
    }

    // Get user's email from database
    $row = mysqli_fetch_assoc($result);
    $email = $row['email'];

    // Display password reset form to user
    ?>
    <form action="reset-password.php" method="post">
        <input type="password" name="new_password" placeholder="New Password">
        <button type="submit">Reset Password</button>
    </form>
    <?php

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        // Verify new password
        $new_password = $_POST['new_password'];
        if (strlen($new_password) < 8) {
            echo "Password must be at least 8 characters long.";
            return;
        }

        // Update user's password in database
        $hashed_password = hash_password($new_password);
        $query = "UPDATE users SET password = '$hashed_password', password_reset_token = '' WHERE email = '$email'";
        mysqli_query($conn, $query);

        echo "Password reset successfully.";
    }
}


forgot_password('example@example.com');


<?php

// Configuration settings
define('DB_HOST', 'your_database_host');
define('DB_USER', 'your_database_username');
define('DB_PASSWORD', 'your_database_password');
define('DB_NAME', 'your_database_name');

// Connect to database
$mysqli = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
if ($mysqli->connect_errno) {
    echo "Failed to connect to MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
    exit();
}

// Set POST variables from form submission
$email = $_POST['email'];
$token = md5(uniqid(mt_rand(), true));

// Check if email is in database
$stmt = $mysqli->prepare("SELECT id, email FROM users WHERE email = ?");
$stmt->bind_param('s', $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    // Email found in database, generate and send password reset token
    $row = $result->fetch_assoc();
    $token = base64_encode(serialize($row));
    $subject = 'Reset your password';
    $message = 'Click this link to reset your password: <a href="http://your-website.com/reset_password.php?token=' . $token . '">Reset Password</a>';
    mail($email, $subject, $message);

    // Store token in database for future use
    $stmt2 = $mysqli->prepare("UPDATE users SET password_token = ? WHERE email = ?");
    $stmt2->bind_param('ss', $token, $email);
    $stmt2->execute();
} else {
    echo 'Email not found in database';
}

// Close connection to database
$mysqli->close();

?>


<?php

// Configuration settings
define('DB_HOST', 'your_database_host');
define('DB_USER', 'your_database_username');
define('DB_PASSWORD', 'your_database_password');
define('DB_NAME', 'your_database_name');

// Connect to database
$mysqli = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
if ($mysqli->connect_errno) {
    echo "Failed to connect to MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
    exit();
}

// Get token from URL parameter
$token = base64_decode($_GET['token']);
$token_array = unserialize($token);

// Check if token is valid and matches email in database
$stmt = $mysqli->prepare("SELECT id, email FROM users WHERE password_token = ? AND email = ?");
$stmt->bind_param('ss', $token, $token_array['email']);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    // Token is valid, prompt user to enter new password
    echo 'Enter your new password: <input type="password" name="new_password">';
    echo '<input type="submit" value="Reset Password">';

    // Update database with new password when form submitted
    if ($_POST['new_password']) {
        $stmt2 = $mysqli->prepare("UPDATE users SET password = ? WHERE id = ?");
        $stmt2->bind_param('ss', $_POST['new_password'], $token_array['id']);
        $stmt2->execute();

        // Clear token from database
        $stmt3 = $mysqli->prepare("UPDATE users SET password_token = NULL WHERE email = ?");
        $stmt3->bind_param('s', $token_array['email']);
        $stmt3->execute();
    }
} else {
    echo 'Invalid or expired token';
}

// Close connection to database
$mysqli->close();

?>


<?php

// Configuration settings
define('SITE_URL', 'http://example.com');
define('SECRET_KEY', 'your_secret_key_here');

require_once 'db_connect.php'; // assume this is your database connection script

if (isset($_POST['username'])) {
  $username = $_POST['username'];
  $email = $_POST['email'];

  // Check if the user exists
  $query = "SELECT id FROM users WHERE username = '$username' AND email = '$email'";
  $result = mysqli_query($conn, $query);

  if (mysqli_num_rows($result) > 0) {
    // Generate a random token and store it in the database
    $token = bin2hex(random_bytes(32));
    $updateQuery = "UPDATE users SET resetToken = '$token' WHERE username = '$username'";
    mysqli_query($conn, $updateQuery);

    // Send password reset email to the user's email address
    sendPasswordResetEmail($email, $token);
  } else {
    echo 'Error: User not found';
  }
} else {
  ?>

  <form action="" method="post">
    <label for="username">Username:</label>
    <input type="text" id="username" name="username"><br><br>
    <label for="email">Email:</label>
    <input type="email" id="email" name="email"><br><br>
    <button type="submit" name="submit">Send Reset Link</button>
  </form>

  <?php
}

function sendPasswordResetEmail($email, $token) {
  $subject = 'Reset Your Password';
  $body = "Click on the following link to reset your password: <a href='" . SITE_URL . "/reset_password.php?token=$token'>$siteUrl/reset_password.php?token=$token</a>";
  mail($email, $subject, $body);
}


<?php

require_once 'db_connect.php'; // assume this is your database connection script

if (isset($_GET['token'])) {
  $token = $_GET['token'];

  // Check if the token exists in the database
  $query = "SELECT id FROM users WHERE resetToken = '$token'";
  $result = mysqli_query($conn, $query);

  if (mysqli_num_rows($result) > 0) {
    // Get the user's data from the database
    $userQuery = "SELECT * FROM users WHERE resetToken = '$token'";
    $userData = mysqli_fetch_assoc(mysqli_query($conn, $userQuery));

    // Allow the user to reset their password
    echo 'Enter your new password: ';
    echo '<input type="password" id="newPassword" name="newPassword"><br><br>';
    echo '<button type="submit" name="reset">Reset Password</button>';

    if (isset($_POST['reset'])) {
      $newPassword = $_POST['newPassword'];
      $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);

      // Update the user's password in the database
      $updateQuery = "UPDATE users SET passwordHash = '$hashedPassword' WHERE resetToken = '$token'";
      mysqli_query($conn, $updateQuery);

      echo 'Your password has been successfully updated!';
    }
  } else {
    echo 'Error: Invalid token';
  }
}
?>


<?php

// Configuration settings
$database_host = 'localhost';
$database_username = 'your_username';
$database_password = 'your_password';
$database_name = 'your_database';

// Connect to database
$mysqli = new mysqli($database_host, $database_username, $database_password, $database_name);
if ($mysqli->connect_errno) {
    die("Error connecting to database: " . $mysqli->connect_error);
}

// Function to send reset link via email
function send_reset_link($email, $reset_token) {
    // Send email using your preferred method (e.g., PHPMailer)
    // For demonstration purposes, we'll just print the email body
    echo "Subject: Reset Password
";
    echo "To: $email
";
    echo "From: your_email@example.com
";
    echo "
";
    echo "Click here to reset password: http://your-website.com/reset-password.php?token=$reset_token
";
}

// Handle form submission
if (isset($_POST['submit'])) {
    // Get email from form input
    $email = $_POST['email'];

    // Check if user exists in database
    $query = "SELECT * FROM users WHERE email = '$email'";
    $result = $mysqli->query($query);
    if ($result->num_rows > 0) {
        // User found, generate reset token and send email
        $reset_token = bin2hex(random_bytes(32));
        $mysqli->query("UPDATE users SET reset_token = '$reset_token' WHERE email = '$email'");
        send_reset_link($email, $reset_token);
        echo "Reset link sent to your email!";
    } else {
        echo "Email not found. Please try again.";
    }
}

// Display form
?>
<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
    <label for="email">Email:</label>
    <input type="text" id="email" name="email"><br><br>
    <input type="submit" value="Send Reset Link" name="submit">
</form>


<?php
require_once 'dbconfig.php'; // assume this contains your database connection settings

if (isset($_POST['forgot-password'])) {
  $email = $_POST['email'];
  
  // validate input
  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo "Invalid email address";
    exit;
  }
  
  // generate reset token
  $resetToken = bin2hex(random_bytes(32));
  
  // update user's password reset field in database
  try {
    $stmt = $pdo->prepare("UPDATE users SET password_reset_token = :token WHERE email = :email");
    $stmt->execute([':token' => $resetToken, ':email' => $email]);
    
    // send password reset link to user via email
    sendPasswordResetEmail($email, $resetToken);
  } catch (PDOException $e) {
    echo "Error updating password reset token";
    exit;
  }
}

// function to send password reset email
function sendPasswordResetEmail($email, $resetToken)
{
  // configure email settings (SMTP server, from address, etc.)
  $fromEmail = 'your-email@example.com';
  $subject = 'Reset Your Password';
  
  // construct email body with password reset link
  $body = "<p>Click this link to reset your password:</p><a href='reset-password.php?token=$resetToken'>Reset Password</a>";
  
  try {
    mail($email, $subject, $body, 'From: ' . $fromEmail);
    echo "Password reset email sent to $email";
  } catch (Exception $e) {
    echo "Error sending password reset email: " . $e->getMessage();
  }
}

// function to handle password reset form submission
if (isset($_POST['reset-password'])) {
  // extract token from URL
  $token = $_GET['token'];
  
  // validate input fields
  if (!isset($_POST['new-password']) || !isset($_POST['confirm-new-password'])) {
    echo "Error: both new password and confirm new password are required";
    exit;
  }
  
  // hash new password and update user's password in database
  try {
    $stmt = $pdo->prepare("UPDATE users SET password = :password WHERE password_reset_token = :token");
    $stmt->execute([':password' => password_hash($_POST['new-password'], PASSWORD_DEFAULT), ':token' => $token]);
    
    // reset password reset token in database
    $stmt = $pdo->prepare("UPDATE users SET password_reset_token = NULL WHERE password_reset_token = :token");
    $stmt->execute([':token' => $token]);
  } catch (PDOException $e) {
    echo "Error updating user's password";
  }
}
?>


<?php
// Configuration
$dbHost = 'localhost';
$dbUsername = 'your_username';
$dbPassword = 'your_password';
$dbName = 'your_database';

// Connect to database
$conn = mysqli_connect($dbHost, $dbUsername, $dbPassword, $dbName);

if (!$conn) {
  die("Connection failed: " . mysqli_connect_error());
}

function sendEmail($to_email, $token) {
  // Email configuration
  $fromEmail = 'your_email@example.com';
  $fromName = 'Your Name';
  $subject = 'Reset Your Password';
  $body = "
    Hi,
    
    Click on the following link to reset your password:
    <a href='http://example.com/reset_password.php?token=$token'>Reset Password</a>
    
    Best regards,
    Your Name
  ";

  // Send email using mail() function
  $headers = 'From: ' . $fromEmail . "\r
" .
             'Subject: ' . $subject . "\r
";
  mail($to_email, $subject, $body, $headers);
}

function checkTokenExists($token) {
  global $conn;
  $query = "SELECT * FROM users WHERE reset_token = '$token'";
  $result = mysqli_query($conn, $query);

  if (mysqli_num_rows($result) > 0) {
    return true;
  } else {
    return false;
  }
}

function updatePassword($token, $new_password) {
  global $conn;
  $query = "UPDATE users SET password_hash = '$new_password', reset_token = NULL WHERE reset_token = '$token'";
  mysqli_query($conn, $query);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  // Get user email
  $email = $_POST['email'];

  // Check if email exists in database
  $query = "SELECT * FROM users WHERE email = '$email'";
  $result = mysqli_query($conn, $query);

  if (mysqli_num_rows($result) > 0) {
    // Get user data
    $row = mysqli_fetch_assoc($result);
    $token = md5(uniqid(mt_rand(), true));

    // Update user data in database with new reset token
    $updateQuery = "UPDATE users SET reset_token = '$token' WHERE email = '$email'";
    mysqli_query($conn, $updateQuery);

    // Send email to user
    sendEmail($email, $token);
  } else {
    echo 'Email not found!';
  }
}

// If GET request with token is made
if (isset($_GET['token'])) {
  // Check if token exists in database
  $checkToken = checkTokenExists($_GET['token']);

  if ($checkToken) {
    // Update password using the provided token
    updatePassword($_GET['token'], $_POST['new_password']);
    echo 'Password updated!';
  } else {
    echo 'Invalid token!';
  }
}
?>


<?php

// Include database connection settings
require_once 'db_config.php';

// Check if form has been submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  // Get email from form input
  $email = $_POST['email'];

  // Validate email
  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo 'Invalid email address';
    exit;
  }

  // Query database for user with matching email
  $query = "SELECT * FROM users WHERE email = '$email'";
  $result = mysqli_query($conn, $query);

  // If user exists, send reset token via email and update database
  if ($row = mysqli_fetch_assoc($result)) {
    // Generate random reset token
    $reset_token = bin2hex(random_bytes(32));

    // Send email with reset link (we'll use a simple link for demonstration purposes)
    $to      = $email;
    $subject = 'Reset Password';
    $message = 'Click here to reset your password: <a href="' . $_SERVER['HTTP_HOST'] . '/reset_password.php?token=' . $reset_token . '">Reset</a>';
    $headers = 'From: info@example.com' . "\r
" .
      'Content-Type: text/html; charset=UTF-8';
    mail($to, $subject, $message, $headers);

    // Update database with reset token
    $query = "UPDATE users SET reset_token = '$reset_token', reset_expires = NOW() + INTERVAL 1 HOUR WHERE email = '$email'";
    mysqli_query($conn, $query);

    echo 'Reset link sent to your email';
  } else {
    echo 'Email address not found in our database';
  }
}

?>


<?php

// Include database connection settings
require_once 'db_config.php';

// Check if form has been submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  // Get token from URL parameter
  $token = $_GET['token'];

  // Validate token (we'll use a simple validation for demonstration purposes)
  if (!empty($token)) {
    // Query database for user with matching token
    $query = "SELECT * FROM users WHERE reset_token = '$token'";
    $result = mysqli_query($conn, $query);

    // If user exists, update password and remove reset token from database
    if ($row = mysqli_fetch_assoc($result)) {
      // Get new password from form input
      $new_password = $_POST['password'];

      // Hash new password (we'll use a simple hashing algorithm for demonstration purposes)
      $hashed_password = hash('sha256', $new_password);

      // Update database with new password
      $query = "UPDATE users SET password_hash = '$hashed_password' WHERE reset_token = '$token'";
      mysqli_query($conn, $query);

      echo 'Password updated successfully';
    } else {
      echo 'Invalid token';
    }
  }
}

?>


<?php

// Configuration
$dbHost = 'localhost';
$dbUsername = 'your_username';
$dbPassword = 'your_password';
$dbName = 'your_database';

// Connect to database
$conn = new mysqli($dbHost, $dbUsername, $dbPassword, $dbName);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Function to reset password
function forgot_password($email)
{
    // SQL query to select user by email
    $sql = "SELECT * FROM users WHERE email = '$email'";
    
    // Execute query and get result
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // Get the user's ID and password hash
        $user = $result->fetch_assoc();

        // Generate a random password
        $password = substr(str_shuffle('abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789'), 0, 10);

        // Update the password in database (with hashing)
        $new_password_hash = password_hash($password, PASSWORD_DEFAULT);
        $sql = "UPDATE users SET password_hash = '$new_password_hash' WHERE id = '" . $user['id'] . "'";
        $conn->query($sql);

        // Send email with new password
        $to = $email;
        $subject = 'Reset Password';
        $body = 'Your new password is: ' . $password;

        mail($to, $subject, $body);
        
        echo "New password sent to your email";
    } else {
        echo "Email not found in database";
    }
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    
    // Check if email is valid (e.g. contains '@')
    if (strpos($email, '@') !== false) {
        forgot_password($email);
    } else {
        echo "Invalid email address";
    }
}

// Close database connection
$conn->close();

?>


<?php

// Configuration
define('RESET_TOKEN_EXPIRE', 60 * 15); // 15 minutes
define('RESET_PASSWORD_LINK_LENGTH', 30);

// Function to send password reset email
function sendPasswordResetEmail($email, $resetToken) {
  $subject = 'Reset Your Password';
  $message = '
    <p>Dear user,</p>
    <p>You are receiving this email because you requested a password reset for your account.</p>
    <p>To reset your password, click on the following link:</p>
    <a href="' . site_url('reset-password/' . $resetToken) . '">' . site_url('reset-password/' . $resetToken) . '</a>
  ';
  mail($email, $subject, $message);
}

// Function to generate reset token
function generateResetToken() {
  return bin2hex(random_bytes(16));
}

// Function to verify password reset link
function verifyPasswordResetLink($token) {
  global $wpdb;
  $result = $wpdb->get_row("SELECT * FROM users WHERE reset_token = '$token'");
  if ($result && time() <= strtotime($result->reset_expires)) {
    return true;
  }
  return false;
}

// Function to update user password
function updatePassword($userId, $newPassword) {
  global $wpdb;
  $wpdb->update('users', array(
    'password' => hash('sha256', $newPassword)
  ), array(
    'id' => $userId
  ));
}

// Forgot Password function
function forgotPassword($email) {
  global $wpdb, $resetToken;
  
  // Check if user exists
  $user = $wpdb->get_row("SELECT * FROM users WHERE email = '$email'");
  if (!$user) {
    return array('success' => false, 'message' => 'User not found');
  }
  
  // Generate reset token and set expiration time
  $resetToken = generateResetToken();
  $expiresAt = date('Y-m-d H:i:s', strtotime('+15 minutes'));
  
  // Update user with new reset token and expiration time
  $wpdb->update('users', array(
    'reset_token' => $resetToken,
    'reset_expires' => $expiresAt
  ), array(
    'id' => $user->id
  ));
  
  // Send password reset email
  sendPasswordResetEmail($email, $resetToken);
  
  return array('success' => true, 'message' => 'A password reset link has been sent to your email');
}

?>


$email = 'user@example.com';
$result = forgotPassword($email);
echo json_encode($result); // Output: {"success":true,"message":"A password reset link has been sent to your email"}


<?php

// Configuration settings
$db_host = 'localhost';
$db_username = 'your_username';
$db_password = 'your_password';
$db_name = 'your_database';

// Connect to the database
$conn = new mysqli($db_host, $db_username, $db_password, $db_name);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get the email address from the form submission
$email = $_POST['email'];

// Validate the email address (optional)
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo 'Invalid email address';
    exit;
}

// Query the database to retrieve the user's ID and password reset token
$query = "SELECT id, password_reset_token FROM users WHERE email = '$email'";
$result = $conn->query($query);

// Check if a result was returned (i.e., the user exists)
if ($result->num_rows > 0) {
    // Get the user's data from the result set
    while ($row = $result->fetch_assoc()) {
        $user_id = $row['id'];
        $password_reset_token = $row['password_reset_token'];

        // Generate a password reset link
        $reset_link = "http://your-website.com/reset_password.php?token=" . $password_reset_token;

        // Send an email to the user with the password reset link (optional)
        $to = $email;
        $subject = 'Password Reset Link';
        $body = 'Click this link to reset your password: ' . $reset_link;
        mail($to, $subject, $body);

        echo 'A password reset link has been sent to your email address.';
    }
} else {
    echo 'No user found with that email address';
}

// Close the database connection
$conn->close();

?>


<?php

// Configuration settings
$db_host = 'localhost';
$db_username = 'your_username';
$db_password = 'your_password';
$db_name = 'your_database';

// Connect to the database
$conn = new mysqli($db_host, $db_username, $db_password, $db_name);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get the password reset token from the URL parameter
$token = $_GET['token'];

// Query the database to retrieve the user's data
$query = "SELECT id, email FROM users WHERE password_reset_token = '$token'";
$result = $conn->query($query);

// Check if a result was returned (i.e., the token is valid)
if ($result->num_rows > 0) {
    // Get the user's data from the result set
    while ($row = $result->fetch_assoc()) {
        $user_id = $row['id'];
        $email = $row['email'];

        // Generate a new password (e.g., using bcrypt)
        $new_password = password_hash('newpassword', PASSWORD_BCRYPT);

        // Update the user's password in the database
        $query = "UPDATE users SET password = '$new_password' WHERE id = '$user_id'";
        $conn->query($query);

        echo 'Your password has been successfully reset.';
    }
} else {
    echo 'Invalid password reset token';
}

// Close the database connection
$conn->close();

?>


<?php

// Configuration settings
$db_host = 'localhost';
$db_username = 'your_database_username';
$db_password = 'your_database_password';
$db_name = 'your_database_name';

// Connect to database
$conn = mysqli_connect($db_host, $db_username, $db_password, $db_name);

if (!$conn) {
    die('Could not connect: ' . mysqli_error());
}

// Define function to send password reset email
function send_password_reset_email($email, $token) {
    // Replace with your own email service (e.g. Mailgun, Sendgrid)
    $subject = 'Password Reset';
    $body = "Click this link to reset your password: <a href='http://example.com/reset-password?token=$token'>Reset Password</a>";
    mail($email, $subject, $body);
}

// Handle forgot password form submission
if (isset($_POST['forgot_password'])) {
    // Get email from form input
    $email = $_POST['email'];

    // Query database to get user credentials
    $query = "SELECT * FROM users WHERE email='$email'";
    $result = mysqli_query($conn, $query);

    if ($result && mysqli_num_rows($result) > 0) {
        // Get user data from query result
        $user_data = mysqli_fetch_assoc($result);
        $token = bin2hex(random_bytes(32));

        // Update password reset token in database
        $query = "UPDATE users SET password_reset_token='$token' WHERE email='$email'";
        mysqli_query($conn, $query);

        // Send password reset email
        send_password_reset_email($user_data['email'], $token);
    } else {
        echo 'Invalid email or password';
    }
}

// Close database connection
mysqli_close($conn);

?>


<?php

// Configuration
define('DB_HOST', 'your_host');
define('DB_USER', 'your_username');
define('DB_PASSWORD', 'your_password');
define('DB_NAME', 'your_database');

function connectToDatabase() {
    $conn = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    return $conn;
}

function sendPasswordResetEmail($email, $token) {
    // You can use a library like PHPMailer for more complex email handling
    echo "Sending password reset link to $email..."; // Replace with actual email sending code
}

function forgotPassword() {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $usernameOrEmail = $_POST['username_or_email'];
        $conn = connectToDatabase();
        
        if ($conn) {
            $query = "SELECT * FROM users WHERE (email='$usernameOrEmail' OR username='$usernameOrEmail')";
            $result = $conn->query($query);
            
            if ($result->num_rows > 0) {
                // Generate a unique token for password reset
                $token = bin2hex(random_bytes(32));
                
                // Store the new token in the database
                $updateQuery = "UPDATE users SET reset_token='$token' WHERE (email='$usernameOrEmail' OR username='$usernameOrEmail')";
                if ($conn->query($updateQuery) === TRUE) {
                    echo "Please check your email for password reset link.";
                    
                    // Send the password reset link to the user
                    sendPasswordResetEmail($usernameOrEmail, $token);
                } else {
                    echo "Error updating user: " . $conn->error;
                }
            } else {
                echo "No account found with that username/email.";
            }
            
            $conn->close();
        } else {
            die("Connection to database failed.");
        }
    }
}

// Display the form for forgotten password
?>
<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
    <label>Enter your email or username:</label>
    <input type="text" name="username_or_email"><br><br>
    <input type="submit" value="Submit">
</form>

<?php
forgotPassword();
?>


<?php

// Configuration variables
$db_host = 'localhost';
$db_username = 'your_username';
$db_password = 'your_password';
$db_name = 'your_database';

// Connect to database
$conn = mysqli_connect($db_host, $db_username, $db_password, $db_name);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

function forgotPassword() {
    global $conn;
    
    // Get user input
    if (isset($_POST['email'])) {
        $email = $_POST['email'];
        
        // Validate email address
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            echo "Invalid email address.";
            return false;
        }
        
        // Check if user exists in database
        $sql = "SELECT * FROM users WHERE email = '$email'";
        $result = mysqli_query($conn, $sql);
        
        if (mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                // Generate new password
                $new_password = substr(md5(uniqid()), 0, 8);
                
                // Update user's password in database
                $sql_update = "UPDATE users SET password_hash = '$new_password' WHERE email = '$email'";
                mysqli_query($conn, $sql_update);
                
                // Send new password to user via email (replace with your own mail function)
                sendEmail($email, 'New Password:', $new_password);
                
                echo "New password has been sent to your email.";
                return true;
            }
        } else {
            echo "User not found.";
            return false;
        }
    }
}

function sendEmail($to_email, $subject, $body) {
    // Your own mail function here (e.g. using PHPMailer)
    // For simplicity, let's use the built-in mail() function
    $mail = mail($to_email, $subject, $body);
    
    if ($mail) {
        echo "Email sent successfully.";
    } else {
        echo "Failed to send email.";
    }
}

if (isset($_POST['submit'])) {
    forgotPassword();
} else {
    // Display form
    ?>
    <form action="" method="post">
        <label for="email">Enter your email address:</label>
        <input type="text" id="email" name="email"><br><br>
        <button type="submit" name="submit">Submit</button>
    </form>
    <?php
}
?>


<?php

require_once 'config.php'; // Your database configuration file

// Validate form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = trim($_POST['email']);
    
    if (empty($email)) {
        echo json_encode(array('error' => 'Please enter your email address.'));
        exit;
    }

    $query = "SELECT * FROM users WHERE email = '$email'";
    $result = mysqli_query($conn, $query);
    
    // If the user exists
    if ($result->num_rows == 1) {
        $user_data = $result->fetch_assoc();
        
        // Generate a random token for password reset
        $token = uniqid('', true);
        $hashed_token = hash('sha256', $token); // Hash the token to prevent SQL injection
        
        // Update user data with new hashed token
        $query_update = "UPDATE users SET token = '$hashed_token' WHERE id = '$user_data[id]'";
        mysqli_query($conn, $query_update);
        
        // Send a password reset link via email
        sendPasswordResetEmail($email, $token);

        echo json_encode(array('success' => 'Please check your email for the password reset link.'));
    } else {
        echo json_encode(array('error' => 'No user found with this email address.'));
    }
    
    // Close database connection
    mysqli_close($conn);
} else {
    header("Location: index.php"); // Your login/index page URL
}

// Function to send password reset email using PHPMailer
function sendPasswordResetEmail($email, $token) {
    require_once 'PHPMailer/PHPMailerAutoload.php'; // Make sure you have the PHPMailer library in your project
    
    $mail = new PHPMailer(true);
    
    try {
        $mail->isSMTP();
        $mail->Host = 'smtp.example.com'; // Your SMTP server
        $mail->SMTPAuth = true;
        $mail->Username = 'your_email@example.com';
        $mail->Password = 'your_password';
        
        $mail->setFrom('your_email@example.com', 'Your Name');
        $mail->addAddress($email);
        
        $body = "You can reset your password by clicking on this link: <a href='reset_password.php?token=" . urlencode($token) . "'>Reset Password</a>";
        $mail->isHTML(true);
        $mail->Subject = 'Password Reset';
        $mail->Body = $body;
        
        if (!$mail->send()) {
            echo 'Message could not be sent.';
            echo 'Mailer Error: ' . $mail->ErrorInfo;
        } else {
            // Password reset email sent successfully
        }
    } catch (Exception $e) {
        echo 'Message could not be sent. Mailer Error: ', $mail->ErrorInfo;
    }
}


<?php

require_once 'config.php'; // Your database configuration file

// Validate form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $token = $_POST['token'];
    
    if (empty($token)) {
        echo json_encode(array('error' => 'Please enter the token from your email.'));
        exit;
    }

    $query = "SELECT * FROM users WHERE token = '$token'";
    $result = mysqli_query($conn, $query);
    
    // If the user exists
    if ($result->num_rows == 1) {
        $user_data = $result->fetch_assoc();
        
        // Update user data with new password
        $new_password = hash('sha256', $_POST['new_password']);
        $query_update = "UPDATE users SET password = '$new_password' WHERE id = '$user_data[id]'";
        mysqli_query($conn, $query_update);
        
        echo json_encode(array('success' => 'Your password has been successfully reset.'));
    } else {
        echo json_encode(array('error' => 'Invalid token. Please try again.'));
    }
    
    // Close database connection
    mysqli_close($conn);
} else {
    if (isset($_GET['token'])) {
        $token = $_GET['token'];
        
        // Display the reset password form
        echo '<form method="post">';
        echo '<label for="new_password">New Password:</label>';
        echo '<input type="password" name="new_password" id="new_password"><br><br>';
        echo '<button type="submit">Reset Password</button>';
        echo '</form>';
    } else {
        header("Location: index.php"); // Your login/index page URL
    }
}

?>


<?php
function forgot_password($email) {
  // Check if email exists in database
  $conn = mysqli_connect("localhost", "username", "password", "database");
  if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
  }

  $query = "SELECT * FROM users WHERE email = '$email'";
  $result = mysqli_query($conn, $query);
  if (mysqli_num_rows($result) > 0) {
    // Email exists, generate new password and send it to user
    $new_password = substr(md5(uniqid()), 0, 8); // Generate new password
    $update_query = "UPDATE users SET password = '$new_password' WHERE email = '$email'";
    mysqli_query($conn, $update_query);

    // Send email with new password
    $to = $email;
    $subject = "Reset Password";
    $message = "Your new password is: $new_password";
    mail($to, $subject, $message);
    echo "New password has been sent to your email.";
  } else {
    echo "Email not found in database.";
  }

  mysqli_close($conn);
}
?>


<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $email = $_POST['email'];
  forgot_password($email);
}
?>


<?php

// Set up database connection
$host = 'localhost';
$username = 'your_username';
$password = 'your_password';
$dbname = 'your_database';

$conn = new mysqli($host, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Define function to check if user exists
function check_user_exists($email) {
    global $conn;
    $query = "SELECT id FROM users WHERE email = '$email'";
    $result = $conn->query($query);
    return $result->num_rows > 0;
}

// Define function to send forgot password email
function send_forgot_password_email($user_id, $email) {
    global $conn;

    // Generate random token (e.g. for email verification)
    $token = bin2hex(random_bytes(32));

    // Update user's email in database with new token
    $query = "UPDATE users SET forgot_password_token = '$token' WHERE id = '$user_id'";
    $conn->query($query);

    // Send email to user with reset link
    $to = $email;
    $subject = 'Reset your password';
    $body = "Click this link to reset your password: <a href='http://your-website.com/reset-password.php?token=$token'>Reset Password</a>";

    $headers = 'From: your-email@example.com' . "\r
";
    mail($to, $subject, $body, $headers);

    return true;
}

// Handle forgot password form submission
if (isset($_POST['forgot_password'])) {
    $email = $_POST['email'];

    // Check if user exists
    if (check_user_exists($email)) {
        $user_id = $conn->query("SELECT id FROM users WHERE email = '$email'")->fetch_assoc()['id'];
        send_forgot_password_email($user_id, $email);
    } else {
        echo "Email not found";
    }
}

// Display forgot password form
?>
<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
    <label>Email:</label>
    <input type="text" name="email" value="<?php echo isset($_POST['email']) ? $_POST['email'] : ''; ?>">
    <button type="submit" name="forgot_password">Submit</button>
</form>

<?php
// Close database connection
$conn->close();
?>


<?php

// Set up database connection
$host = 'localhost';
$username = 'your_username';
$password = 'your_password';
$dbname = 'your_database';

$conn = new mysqli($host, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Define function to check if token is valid
function check_token_valid($token) {
    global $conn;

    // Get user ID from database using token
    $query = "SELECT id FROM users WHERE forgot_password_token = '$token'";
    $result = $conn->query($query);

    return $result->num_rows > 0;
}

// Define function to update password
function update_password($user_id, $new_password) {
    global $conn;

    // Hash new password and update user's password in database
    $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
    $query = "UPDATE users SET password_hash = '$hashed_password' WHERE id = '$user_id'";
    $conn->query($query);

    return true;
}

// Handle reset password form submission
if (isset($_POST['reset_password'])) {
    $token = $_GET['token'];

    // Check if token is valid
    if (check_token_valid($token)) {
        $user_id = $conn->query("SELECT id FROM users WHERE forgot_password_token = '$token'")->fetch_assoc()['id'];
        update_password($user_id, $_POST['new_password']);
    } else {
        echo "Invalid token";
    }
}

// Display reset password form
?>
<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
    <label>New Password:</label>
    <input type="password" name="new_password" value="<?php echo isset($_POST['new_password']) ? $_POST['new_password'] : ''; ?>">
    <button type="submit" name="reset_password">Submit</button>
</form>

<?php
// Close database connection
$conn->close();
?>


<?php

// Configuration
define('SECRET_KEY', 'your_secret_key_here');
define('MAX_RETRIES', 5);

// Function to send forgot password email
function sendForgotPasswordEmail($email, $retries = 0)
{
    // Check if user exists
    $user = getUserByEmail($email);
    if (!$user) {
        return false;
    }

    // Generate reset token
    $resetToken = generateResetToken();

    // Save reset token to database
    saveResetToken($user['id'], $resetToken);

    // Send email with reset link
    $subject = 'Forgot Password';
    $body = '<p>Click the following link to reset your password:</p>
            <a href="' . $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['HTTP_HOST'] . '/reset-password?token=' . $resetToken . '">Reset Password</a>';
    sendEmail($email, $subject, $body);

    return true;
}

// Function to generate reset token
function generateResetToken()
{
    // Use a cryptographically secure pseudo-random number generator (CSPRNG)
    $randomBytes = random_bytes(32);
    $resetToken = bin2hex($randomBytes);
    return $resetToken;
}

// Function to save reset token in database
function saveResetToken($userId, $resetToken)
{
    // Update user record with reset token
    $query = "UPDATE users SET reset_token = '$resetToken' WHERE id = '$userId'";
    mysqli_query($conn, $query);
}

// Function to send email (using a library like PHPMailer or SwiftMailer)
function sendEmail($email, $subject, $body)
{
    // Implement your own email sending logic here
}

// Example usage:
$email = 'user@example.com';
if (sendForgotPasswordEmail($email)) {
    echo "Reset link sent successfully!";
} else {
    echo "Error sending reset link. Please try again.";
}


<?php

// Include configuration file
require_once 'config.php';

// Check if form has been submitted
if (isset($_POST['submit'])) {
  // Get input values
  $email = $_POST['email'];

  // Validate email address
  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo "Invalid email address.";
    exit;
  }

  // Retrieve user data from database
  $sql = "SELECT * FROM users WHERE email = ?";
  $stmt = $pdo->prepare($sql);
  $stmt->execute([$email]);
  $user = $stmt->fetch();

  if (!$user) {
    echo "No account found with that email address.";
    exit;
  }

  // Generate reset token
  $resetToken = bin2hex(random_bytes(32));
  $expiresAt = time() + (60 * 60 * 24); // 1 day from now

  // Update user data in database
  $sql = "UPDATE users SET reset_token = ?, expires_at = ? WHERE id = ?";
  $stmt = $pdo->prepare($sql);
  $stmt->execute([$resetToken, $expiresAt, $user['id']]);

  // Send password reset email
  sendPasswordResetEmail($email, $resetToken);

  echo "A password reset link has been sent to your email address.";
} else {
  // Display forgot password form
  ?>
  <form action="" method="post">
    <label for="email">Enter your email address:</label>
    <input type="text" id="email" name="email">
    <button type="submit" name="submit">Send Reset Link</button>
  </form>
  <?php
}

// Function to send password reset email
function sendPasswordResetEmail($email, $resetToken) {
  // Configuration variables (replace with your own)
  $fromEmail = 'your-email@example.com';
  $fromName = 'Your Website';

  // Email template
  $subject = 'Password Reset Link';
  $body = "
    Dear user,

    You have requested to reset your password. To do so, click on the following link:

    <a href='http://example.com/reset-password?token=<?php echo $resetToken; ?>'>Reset Password</a>

    If you did not request a password reset, please ignore this email.

    Best regards,
    " . $fromName;

  // Send email using PHPMailer or mail() function
  // ...
}


// file: forgot_password.php

// Configuration
$database = array(
    'host' => 'localhost',
    'username' => 'your_username',
    'password' => 'your_password',
    'database' => 'your_database'
);

function send_reset_link($email, $username) {
    // Check if email exists in database
    $query = "SELECT * FROM users WHERE email = '$email'";
    $result = mysqli_query($database['connection'], $query);
    if (mysqli_num_rows($result) == 0) {
        echo "Email not found";
        return;
    }

    // Generate random password
    $password = substr(md5(uniqid(rand(), true)), 0, 10);

    // Update user's password in database
    $query = "UPDATE users SET password = '$password' WHERE email = '$email'";
    mysqli_query($database['connection'], $query);

    // Send reset link to user's email
    $subject = 'Reset Your Password';
    $body = "
        Dear $username,
        
        We have received a request to reset your password. To do so, please click on the following link:
        
        <a href='reset_password.php?email=$email&password=$password'>Reset Password</a>
        
        Best regards,
        [Your Name]
    ";
    mail($email, $subject, $body);

    echo "Password reset link sent to your email.";
}

function forgot_password() {
    // Handle form submission
    if (isset($_POST['submit'])) {
        $email = $_POST['email'];
        $username = $_POST['username'];

        send_reset_link($email, $username);
    }
}


// file: reset_password.php

// Configuration
$database = array(
    'host' => 'localhost',
    'username' => 'your_username',
    'password' => 'your_password',
    'database' => 'your_database'
);

function update_password($email, $password) {
    // Update user's password in database
    $query = "UPDATE users SET password = '$password' WHERE email = '$email'";
    mysqli_query($database['connection'], $query);
}

function reset_password() {
    // Handle form submission
    if (isset($_POST['submit'])) {
        $email = $_POST['email'];
        $password = $_POST['password'];

        update_password($email, $password);

        echo "Password updated successfully.";
    }
}


<?php

// Include PHPMailer library
require_once 'vendor/autoload.php';

use PHPMailer\PHPMailer;

// Configuration variables
$server = 'smtp.gmail.com';
$port = 587;
$username = 'your_email@gmail.com'; // replace with your email address
$password = 'your_password'; // replace with your password

// Email verification code
$verifyCode = md5(uniqid(mt_rand(), true));

// Forgot password form
if (isset($_POST['forgot'])) {

    // Check if username or email is provided
    $usernameOrEmail = $_POST['username_or_email'];

    try {
        // Connect to database
        $conn = new PDO('mysql:host=localhost;dbname=your_database', 'your_username', 'your_password');

        // Query to retrieve user data based on username or email
        $stmt = $conn->prepare("SELECT * FROM users WHERE (username=:username_or_email OR email=:username_or_email)");
        $stmt->bindParam(':username_or_email', $usernameOrEmail);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            // Retrieve user data
            $user = $stmt->fetch();

            // Generate reset password link
            $resetLink = "http://your_domain.com/resetPassword.php?verifyCode=$verifyCode&userId={$user['id']}";

            // Send email with reset password link
            sendEmail($usernameOrEmail, $verifyCode);

        } else {
            echo 'User not found';
        }

    } catch (PDOException $e) {
        echo "Error connecting to database: " . $e->getMessage();
    }
}

// Function to send email using PHPMailer
function sendEmail($to, $verifyCode)
{
    // Create a new instance of PHPMailer
    $mail = new PHPMailer(true);

    try {
        // Configuration settings for sending email
        $mail->isSMTP();
        $mail->Host = $server;
        $mail->Port = $port;
        $mail->SMTPAuth = true;
        $mail->Username = $username;
        $mail->Password = $password;

        // Email settings
        $mail->setFrom($username, 'Your Website');
        $mail->addAddress($to);
        $mail->Subject = "Reset Password Link";
        $mail->Body = "Click on the link below to reset your password: <a href='$resetLink'>Reset Password</a>";

        // Send email
        $mail->send();

    } catch (Exception $e) {
        echo "Error sending email: " . $e->getMessage();
    }
}

?>


<?php

// Include PHPMailer library
require_once 'vendor/autoload.php';

use PHPMailer\PHPMailer;

// Configuration variables
$server = 'smtp.gmail.com';
$port = 587;
$username = 'your_email@gmail.com'; // replace with your email address
$password = 'your_password'; // replace with your password

// Verify code and user ID from URL parameters
$verifyCode = $_GET['verifyCode'];
$userId = $_GET['userId'];

try {
    // Connect to database
    $conn = new PDO('mysql:host=localhost;dbname=your_database', 'your_username', 'your_password');

    // Query to retrieve user data based on user ID and verify code
    $stmt = $conn->prepare("SELECT * FROM users WHERE id=:userId AND verifyCode=:verifyCode");
    $stmt->bindParam(':userId', $userId);
    $stmt->bindParam(':verifyCode', $verifyCode);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        // Retrieve user data
        $user = $stmt->fetch();

        // Prompt for new password
        echo 'Enter your new password: <input type="password" name="new_password"><br>';
        echo '<input type="submit" name="change_password">';

    } else {
        echo 'Invalid verify code or user ID';
    }

} catch (PDOException $e) {
    echo "Error connecting to database: " . $e->getMessage();
}

if (isset($_POST['change_password'])) {

    // Get new password from form
    $newPassword = $_POST['new_password'];

    try {
        // Update user data with new password
        $conn = new PDO('mysql:host=localhost;dbname=your_database', 'your_username', 'your_password');

        $stmt = $conn->prepare("UPDATE users SET password=:password WHERE id=:userId");
        $stmt->bindParam(':password', $newPassword);
        $stmt->bindParam(':userId', $userId);
        $stmt->execute();

        echo 'Your password has been changed successfully';

    } catch (PDOException $e) {
        echo "Error updating database: " . $e->getMessage();
    }
}

?>


function forgot_password($email, $username, $new_password) {
  // Connect to database
  $conn = mysqli_connect("localhost", "username", "password", "database");

  // Check if user exists
  $query = "SELECT * FROM users WHERE email='$email' AND username='$username'";
  $result = mysqli_query($conn, $query);
  if (mysqli_num_rows($result) == 0) {
    return array("error" => "User not found");
  }

  // Generate a reset password token
  $token = substr(hash('sha256', microtime(true)), 0, 32);

  // Update user record with reset password token
  $query = "UPDATE users SET password_reset_token='$token' WHERE email='$email'";
  mysqli_query($conn, $query);

  // Send email to user with instructions on how to reset their password
  $subject = "Reset Your Password";
  $body = "
    Dear $username,

    Click the following link to reset your password: <a href='http://yourwebsite.com/reset-password?token=$token'>Reset Password</a>

    Best regards,
    Your Website Team";

  // Send email using PHPMailer or mail function
  send_email($email, $subject, $body);

  return array("success" => "Email sent with instructions on how to reset your password");
}


function send_email($to, $subject, $body) {
  // Use PHPMailer or mail function to send email
  if (isset($_ENV["SMTP_SERVER"])) {
    require_once 'PHPMailer/src/PHPMailer.php';
    require_once 'PHPMailer/src/SMTP.php';

    $mail = new PHPMailer\PHPMailer\PHPMailer();
    $mail->isSMTP();
    $mail->Host = $_ENV["SMTP_SERVER"];
    $mail->Username = $_ENV["SMTP_USERNAME"];
    $mail->Password = $_ENV["SMTP_PASSWORD"];
    $mail->Port = 587;
    $mail->SMTPAuth = true;

    $mail->setFrom($_ENV["FROM_EMAIL"], "Your Website Team");
    $mail->addAddress($to);
    $mail->Subject = $subject;
    $mail->Body = $body;

    if (!$mail->send()) {
      return array("error" => "Email not sent");
    }
  } else {
    mail($to, $subject, $body);
  }

  return array("success" => "Email sent");
}


function reset_password($token) {
  // Connect to database
  $conn = mysqli_connect("localhost", "username", "password", "database");

  // Check if token is valid
  $query = "SELECT * FROM users WHERE password_reset_token='$token'";
  $result = mysqli_query($conn, $query);
  if (mysqli_num_rows($result) == 0) {
    return array("error" => "Invalid token");
  }

  // Get user data from database
  $user_data = mysqli_fetch_assoc($result);

  // Update user record with new password
  $new_password_hash = password_hash($token, PASSWORD_DEFAULT);
  $query = "UPDATE users SET password='$new_password_hash' WHERE id='{$user_data['id']}'";
  mysqli_query($conn, $query);

  return array("success" => "Password reset successfully");
}


<form action="forgot-password.php" method="post">
  <input type="email" name="email" placeholder="Enter your email address">
  <input type="text" name="username" placeholder="Enter your username">
  <button type="submit">Forgot Password</button>
</form>

<?php
if (isset($_POST["email"]) && isset($_POST["username"])) {
  $result = forgot_password($_POST["email"], $_POST["username"]);
  if ($result["error"]) {
    echo "Error: " . $result["error"];
  } else {
    echo "Email sent with instructions on how to reset your password";
  }
}
?>


<?php
// Configuration
$secret_key = 'your_secret_key_here'; // Use a random secret key for security

function sendEmail($email, $token) {
  // Send an email with a link to reset password
  // This is a simplified example, you may want to use a dedicated email library or service
  echo "Sending email to $email...
";
  // mail($email, 'Reset Password', 'Click this link to reset your password: https://example.com/reset_password.php?token=' . urlencode($token));
}

// Check if form has been submitted
if (isset($_POST['submit'])) {
  // Get the user's email address
  $email = $_POST['email'];

  // Validate email address
  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo 'Invalid email address.';
    exit;
  }

  // Retrieve the user's data from the database
  try {
    $pdo = new PDO('mysql:host=localhost;dbname=your_database', 'your_username', 'your_password');
    $stmt = $pdo->prepare('SELECT id, reset_token FROM users WHERE email = :email');
    $stmt->bindParam(':email', $email);
    $stmt->execute();
    $user_data = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user_data) {
      echo 'No account found with that email address.';
      exit;
    }

    // Generate a random reset token
    $token = bin2hex(random_bytes(16));

    // Update the user's data in the database
    $pdo->beginTransaction();
    $stmt = $pdo->prepare('UPDATE users SET reset_token = :token, expires_at = NOW() + INTERVAL 1 HOUR WHERE id = :id');
    $stmt->bindParam(':id', $user_data['id']);
    $stmt->bindParam(':token', $token);
    $stmt->execute();
    $pdo->commit();

    // Send an email with a link to reset password
    sendEmail($email, $token);

  } catch (PDOException $e) {
    echo 'Error: ' . $e->getMessage();
  }
}

// Display the forgot password form
?>
<form action="" method="post">
  <label for="email">Email:</label>
  <input type="email" id="email" name="email"><br><br>
  <input type="submit" name="submit" value="Submit">
</form>


<?php
require_once 'db.php'; // assume db.php contains your database connection settings

function forgot_password() {
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $username = trim($_POST['username']);
        $email = trim($_POST['email']);

        // Validate input data
        if (empty($username) || empty($email)) {
            $_SESSION['error'] = 'Please enter both username and email';
            return;
        }

        // Query database to retrieve user's information
        $query = "SELECT * FROM users WHERE username = '$username' AND email = '$email'";
        $result = mysqli_query($conn, $query);

        if (mysqli_num_rows($result) == 1) {
            // Retrieve user's information
            $row = mysqli_fetch_assoc($result);
            $user_id = $row['id'];
            $password_reset_token = bin2hex(random_bytes(32));
            $expiration_date = date('Y-m-d H:i:s', strtotime('+30 minutes')); // set expiration time to 30 minutes

            // Update user's information in database
            $query = "UPDATE users SET password_reset_token = '$password_reset_token', expiration_date = '$expiration_date' WHERE id = '$user_id'";
            mysqli_query($conn, $query);

            // Send email with password reset link
            send_password_reset_email($email, $password_reset_token);
        } else {
            $_SESSION['error'] = 'Invalid username or email';
        }
    }

    // Handle email sending (optional)
    function send_password_reset_email($email, $token) {
        $subject = 'Reset your password';
        $message = '
            <p>Hello %username%,

You have requested to reset your password. Please click on the following link to proceed:

            <a href="http://example.com/reset-password/%token%">Reset Password</a>

            If you did not request this, please ignore this email.

            Best regards,
            Your website
        ';

        // Send email using PHPMailer or other email library (not shown here)
    }
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    forgot_password();
}
?>


<?php

require 'config.php'; // include database connection settings

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $email = $_POST['email'];
  
  if (empty($email)) {
    echo "Email is required";
    exit;
  }
  
  // validate email format
  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo "Invalid email address";
    exit;
  }
  
  $query = "SELECT * FROM users WHERE email = '$email'";
  $result = mysqli_query($conn, $query);
  
  if (mysqli_num_rows($result) > 0) {
    // user exists
    $user_data = mysqli_fetch_assoc($result);
    
    // generate a random password
    $new_password = substr(str_shuffle('0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ'), 0, 8);
    
    // update the user's password
    $query = "UPDATE users SET password_hash = '$new_password' WHERE email = '$email'";
    mysqli_query($conn, $query);
    
    // send a reset password email (not implemented here)
    // ...
    
    echo "Reset link sent to your email";
  } else {
    echo "Email not found in our records.";
  }
} else {
?>
  <form action="" method="post">
    Email: <input type="email" name="email"><br>
    <input type="submit" value="Send Reset Link">
  </form>
<?php
}


<?php

require 'config.php'; // include database connection settings

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $email = $_POST['email'];
  $new_password = $_POST['new_password'];
  
  if (empty($email) || empty($new_password)) {
    echo "Email and new password are required";
    exit;
  }
  
  // validate email format
  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo "Invalid email address";
    exit;
  }
  
  $query = "SELECT * FROM users WHERE email = '$email'";
  $result = mysqli_query($conn, $query);
  
  if (mysqli_num_rows($result) > 0) {
    // user exists
    $user_data = mysqli_fetch_assoc($result);
    
    // update the user's password
    $query = "UPDATE users SET password_hash = '$new_password' WHERE email = '$email'";
    mysqli_query($conn, $query);
    
    echo "Password updated successfully";
  } else {
    echo "Email not found in our records.";
  }
} else {
?>
  <form action="" method="post">
    Email: <input type="email" name="email"><br>
    New Password: <input type="password" name="new_password"><br>
    Confirm New Password: <input type="password" name="confirm_new_password"><br>
    <input type="submit" value="Reset Password">
  </form>
<?php
}


<?php

// database connection settings
$host = 'localhost';
$username = 'your_username';
$password = 'your_password';
$dbname = 'your_database_name';

$conn = mysqli_connect($host, $username, $password, $dbname);

if (!$conn) {
  die("Connection failed: " . mysqli_connect_error());
}


<?php

// Configuration
define('DB_HOST', 'your_host');
define('DB_USER', 'your_user');
define('DB_PASSWORD', 'your_password');
define('DB_NAME', 'your_database');

// Connect to database
$conn = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get user input
$username = $_POST['username'];
$email = $_POST['email'];

// Check if username and email exist in database
$stmt = $conn->prepare("SELECT * FROM users WHERE username = ? AND email = ?");
$stmt->bind_param('ss', $username, $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    // User exists, generate new password and send reset link
    $row = $result->fetch_assoc();
    
    // Generate new password
    $new_password = substr(md5(uniqid(mt_rand(), true)), 0, 10);
    
    // Update user password in database
    $update_stmt = $conn->prepare("UPDATE users SET password_hash = ? WHERE id = ?");
    $update_stmt->bind_param('si', md5($new_password), $row['id']);
    $update_stmt->execute();
    
    // Send reset link via email (this example uses PHPMailer)
    require_once 'PHPMailerAutoload.php';
    $mail = new PHPMailer;
    $mail->isSMTP();
    $mail->Host = 'smtp.example.com';
    $mail->Port = 587;
    $mail->SMTPAuth = true;
    $mail->Username = 'your_email@example.com';
    $mail->Password = 'your_password';
    $mail->setFrom('your_email@example.com', 'Your Name');
    $mail->addAddress($email);
    $mail->Subject = 'Reset Password';
    $mail->Body = 'Click here to reset your password: <a href="https://example.com/reset-password.php?username=' . urlencode($username) . '&token=' . md5($new_password) . '">Reset Password</a>';
    
    if (!$mail->send()) {
        echo 'Message could not be sent.';
        echo 'Mailer Error: ' . $mail->ErrorInfo;
    } else {
        echo 'Email sent successfully!';
    }
} else {
    echo 'Username or email does not exist in our database.';
}

// Close connection
$conn->close();

?>


<?php

// Configuration
define('DB_HOST', 'your_host');
define('DB_USER', 'your_user');
define('DB_PASSWORD', 'your_password');
define('DB_NAME', 'your_database');

// Connect to database
$conn = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get user input
$username = $_GET['username'];
$token = $_GET['token'];

// Check if username and token exist in database
$stmt = $conn->prepare("SELECT * FROM users WHERE username = ? AND password_hash = ?");
$stmt->bind_param('ss', $username, md5($token));
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    // User exists, update new password
    $row = $result->fetch_assoc();
    
    // Update user password in database
    $update_stmt = $conn->prepare("UPDATE users SET password_hash = ? WHERE id = ?");
    $update_stmt->bind_param('si', md5($_POST['new_password']), $row['id']);
    $update_stmt->execute();
    
    echo 'Password updated successfully!';
} else {
    echo 'Username or token does not exist in our database.';
}

// Close connection
$conn->close();

?>


<?php

// Configuration settings
define('SITE_EMAIL', 'your_email@example.com');
define('SITE_NAME', 'Your Website Name');

// Check if form has been submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get user input data
    $email = $_POST['email'];

    // Validate email address
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "Invalid email address.";
        exit;
    }

    // Check if user exists in database
    require_once 'db.php';
    $query = "SELECT * FROM users WHERE email = '$email'";
    $result = mysqli_query($conn, $query);
    if (mysqli_num_rows($result) == 0) {
        echo "Email not found.";
        exit;
    }

    // Generate a random password
    $new_password = substr(md5(uniqid(mt_rand(), true)), 0, 8);

    // Update user's password in database
    $query = "UPDATE users SET password = '$new_password' WHERE email = '$email'";
    mysqli_query($conn, $query);

    // Send email with new password to user
    $subject = 'Your new password for ' . SITE_NAME;
    $message = '
        Dear User,
        
        Your new password is: ' . $new_password . '

        Best regards,
        ' . SITE_NAME;
    $headers = "From: " . SITE_EMAIL;
    mail($email, $subject, $message, $headers);

    echo "New password has been sent to your email.";
} else {
    ?>
    <h1>Forgot Password</h1>
    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
        <label for="email">Email:</label>
        <input type="text" id="email" name="email" required>
        <button type="submit">Submit</button>
    </form>
    <?php
}
?>


<?php

// Database connection settings
$conn = mysqli_connect("localhost", "username", "password", "database");

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

?>


<?php

// Configuration settings
$smtp_server = 'your_smtp_server';
$smtp_port = 587;
$from_email = 'your_email@example.com';
$password_recovery_url = 'https://example.com/password-recovery';

// Verify the email is not empty and contains a valid email address
if (!isset($_POST['email']) || !filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
    echo json_encode(array('error' => 'Invalid email address'));
    exit;
}

$email = $_POST['email'];

// Retrieve user data from database (e.g., MySQL)
$user_data_query = "SELECT * FROM users WHERE email = '$email'";
$result = mysqli_query($conn, $user_data_query);

if (mysqli_num_rows($result) == 0) {
    echo json_encode(array('error' => 'Email address not found'));
    exit;
}

$user_id = mysqli_fetch_assoc($result)['id'];

// Generate a random password reset token
$password_reset_token = bin2hex(random_bytes(32));

// Update user data with the password reset token in database (e.g., MySQL)
$query = "UPDATE users SET password_reset_token = '$password_reset_token' WHERE id = '$user_id'";
mysqli_query($conn, $query);

// Send an email to the user with a link to reset their password
$subject = 'Password Recovery';
$message = '
Dear '. $_POST['email'] .',

Please click on the following link to reset your password:
' . $password_recovery_url . '?token=' . $password_reset_token;

$headers = 'From: ' . $from_email . "\r
" .
    'Reply-To: ' . $from_email . "\r
" .
    'Content-Type: text/html; charset=UTF-8\r
';

mail($email, $subject, $message, $headers);

echo json_encode(array('success' => 'Email sent with password recovery link'));

?>


// Forgot Password Function

function forgot_password($username) {
  // Get user data from the database
  $user = get_user_by_username($username);

  if (!$user) {
    return array('error' => 'Username not found');
  }

  // Generate a reset token
  $reset_token = bin2hex(random_bytes(16));

  // Update user data with reset token in the database
  update_user_with_reset_token($user['id'], $reset_token);

  // Send password reset email to the user's email address
  send_password_reset_email($user, $reset_token);
}

// Get user by username function

function get_user_by_username($username) {
  global $db;
  $query = "SELECT * FROM users WHERE username = '$username'";
  $result = mysqli_query($db, $query);

  if (mysqli_num_rows($result) > 0) {
    return mysqli_fetch_assoc($result);
  }

  return false;
}

// Update user with reset token function

function update_user_with_reset_token($user_id, $reset_token) {
  global $db;
  $query = "UPDATE users SET password_reset_token = '$reset_token' WHERE id = '$user_id'";
  mysqli_query($db, $query);
}

// Send password reset email function

function send_password_reset_email($user, $reset_token) {
  $to = $user['email'];
  $subject = 'Password Reset';
  $body = "Please click on the following link to reset your password: <a href='http://example.com/reset-password?token=" . $reset_token . "'>Reset Password</a>";

  mail($to, $subject, $body);
}

// Check if the user has a valid reset token function

function check_reset_token($token) {
  global $db;
  $query = "SELECT * FROM users WHERE password_reset_token = '$token'";
  $result = mysqli_query($db, $query);

  if (mysqli_num_rows($result) > 0) {
    return true; // User has a valid reset token
  }

  return false;
}

// Reset user's password function

function reset_password($token, $new_password) {
  global $db;

  if (!check_reset_token($token)) {
    return array('error' => 'Invalid or expired reset token');
  }

  // Update user data with new password in the database
  update_user_with_new_password($new_password);
}


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $token = $_GET['token'];
  $new_password = $_POST['new_password'];

  if ($token && !empty($new_password)) {
    reset_password($token, $new_password);
  } else {
    echo 'Invalid or expired reset token';
  }
}


<?php

// Configuration constants
define('RESET_TOKEN_LIFETIME', 3600); // Token is valid for an hour
define('SMTP_SERVER', 'smtp.example.com'); // Your SMTP server address
define('SMTP_PORT', 587);
define('FROM_EMAIL', 'your_email@example.com');
define('FROM_NAME', 'Your Website Name');

// Email configuration for sending reset links
function sendEmail($to, $subject, $body) {
    $config = [
        'host' => SMTP_SERVER,
        'port' => SMTP_PORT,
        'user' => FROM_EMAIL,
        'password' => 'your_email_password',
        'crypt' => 'ssl'
    ];

    // Sending email
    if (mail($to, $subject, $body, "From: $FROM_NAME <$FROM_EMAIL>")) {
        return true;
    } else {
        error_log("Error sending email to $to");
        return false;
    }
}

// Forgot Password Functionality
function forgotPassword() {
    global $conn; // Assuming you're using a global connection object

    if (isset($_POST['email'])) {
        $email = $_POST['email'];
        
        // Validate Email
        $query = "SELECT * FROM users WHERE email = '$email'";
        $result = mysqli_query($conn, $query);
        
        if ($row = mysqli_fetch_assoc($result)) {
            // Generate a reset token
            $resetToken = substr(bin2hex(random_bytes(32)), 0, 32);
            $updateQuery = "UPDATE users SET reset_token = '$resetToken' WHERE email = '$email'";
            
            if (mysqli_query($conn, $updateQuery)) {
                // Create and send the password reset link
                $link = "http://".$_SERVER['HTTP_HOST']."/reset_password.php?token=$resetToken";
                $subject = 'Password Reset Link';
                $body = "
                    Dear $email,
                    
                    You are receiving this email because you have requested a password reset for your account.
                    
                    Please click on the following link to reset your password: $link
                    
                    Best regards,
                    Your Website Name
                ";
                
                if (sendEmail($email, $subject, $body)) {
                    echo 'A password reset link has been sent to your email.';
                } else {
                    echo 'Error sending password reset link.';
                }
            } else {
                error_log("Error updating user record");
            }
        } else {
            echo 'The provided email is not in our records or may be incorrect.';
        }
    } else {
        if (isset($_GET['token'])) {
            $resetToken = $_GET['token'];
            
            // Check if token is valid and has been used
            $query = "SELECT * FROM users WHERE reset_token = '$resetToken'";
            $result = mysqli_query($conn, $query);
            
            if ($row = mysqli_fetch_assoc($result)) {
                // If the token hasn't expired yet, allow user to change their password
                if (strtotime($row['created_at']) + RESET_TOKEN_LIFETIME >= time()) {
                    echo "Please enter your new password below.";
                    
                    // Form for updating password
                    ?>
                    <form action="" method="post">
                        <input type="password" name="new_password">
                        <input type="submit" value="Change Password">
                    </form>
                    
                    <?php
                    
                    if (isset($_POST['new_password'])) {
                        $newPassword = $_POST['new_password'];
                        
                        // Update the user's password
                        $updateQuery = "UPDATE users SET reset_token = NULL, password_hash = '$newPassword' WHERE email = '".$row['email']."'";
                        
                        if (mysqli_query($conn, $updateQuery)) {
                            echo 'Your password has been successfully changed.';
                        } else {
                            error_log("Error updating user record");
                        }
                    }
                } else {
                    echo "The provided reset token is expired or invalid.";
                }
            } else {
                echo "The provided reset token is invalid or has not been used yet.";
            }
        }
    }
}

// Initial Setup
if (isset($_POST['email'])) {
    forgotPassword();
}
?>


<?php

require_once 'db_config.php'; // assuming you have a db_config.php file for database connection settings

// Check if form has been submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];

    // Validate email address
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo 'Invalid email address';
        exit;
    }

    // Retrieve user data from database
    $query = "SELECT * FROM users WHERE email = '$email'";
    $result = mysqli_query($db_connection, $query);

    if (mysqli_num_rows($result) == 1) {
        $user_data = mysqli_fetch_assoc($result);

        // Generate a new password reset token
        $token = bin2hex(random_bytes(32));

        // Update user data with new token
        $query = "UPDATE users SET password_reset_token = '$token' WHERE email = '$email'";
        mysqli_query($db_connection, $query);

        // Send email to user with password reset link
        $subject = 'Password Reset Link';
        $message = '
            <p>Click the following link to reset your password:</p>
            <p><a href="' . $_SERVER['HTTP_HOST'] . '/reset_password.php?token=' . $token . '">Reset Password</a></p>
        ';
        mail($email, $subject, $message);

        echo 'Password reset email sent';
    } else {
        echo 'Email address not found in database';
    }
}

?>

<form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
    <label for="email">Email Address:</label>
    <input type="email" id="email" name="email">
    <button type="submit">Send Password Reset Email</button>
</form>


<?php

require_once 'db_config.php';

// Check if form has been submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $token = $_POST['token'];
    $new_password = $_POST['new_password'];

    // Validate token and new password
    if (!isset($token) || !isset($new_password)) {
        echo 'Invalid request';
        exit;
    }

    // Retrieve user data from database using token
    $query = "SELECT * FROM users WHERE password_reset_token = '$token'";
    $result = mysqli_query($db_connection, $query);

    if (mysqli_num_rows($result) == 1) {
        $user_data = mysqli_fetch_assoc($result);

        // Update user data with new password
        $new_password_hash = password_hash($new_password, PASSWORD_DEFAULT);
        $query = "UPDATE users SET password_hash = '$new_password_hash', password_reset_token = NULL WHERE email = '$user_data[email]'";
        mysqli_query($db_connection, $query);

        echo 'Password reset successfully';
    } else {
        echo 'Invalid token';
    }
}

?>

<form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
    <label for="token">Token:</label>
    <input type="text" id="token" name="token">
    <label for="new_password">New Password:</label>
    <input type="password" id="new_password" name="new_password">
    <button type="submit">Reset Password</button>
</form>


<?php

// Configuration
$config = array(
  'email_from' => 'your-email@example.com',
  'email_to' => '',
  'smtp_host' => 'your-smtp-host',
  'smtp_port' => '587'
);

function forgot_password($email) {
  // Get user data from database
  $user_data = get_user_by_email($email);
  
  if (!$user_data) {
    return false;
  }
  
  // Generate reset token and expiration time
  $reset_token = bin2hex(random_bytes(32));
  $reset_token_expires = date('Y-m-d H:i:s', strtotime('+1 hour'));
  
  // Update user data in database with new reset token and expiration time
  update_user_data($user_data['id'], 'reset_token', $reset_token);
  update_user_data($user_data['id'], 'reset_token_expires', $reset_token_expires);
  
  // Send email to user with reset link
  send_reset_email($email, $reset_token);
}

function get_user_by_email($email) {
  global $config;
  
  $db = new PDO('mysql:host=localhost;dbname=your-database', 'your-username', 'your-password');
  $stmt = $db->prepare("SELECT * FROM users WHERE email = ?");
  $stmt->execute(array($email));
  return $stmt->fetch();
}

function update_user_data($id, $field, $value) {
  global $config;
  
  $db = new PDO('mysql:host=localhost;dbname=your-database', 'your-username', 'your-password');
  $stmt = $db->prepare("UPDATE users SET `$field` = ? WHERE id = ?");
  $stmt->execute(array($value, $id));
}

function send_reset_email($email, $reset_token) {
  global $config;
  
  $headers = "From: {$config['email_from']}\r
";
  $headers .= "Content-Type: text/html; charset=UTF-8\r
";
  
  $message = "
    <h1>Reset Password</h1>
    <p>Please click on the following link to reset your password:</p>
    <a href='http://your-website.com/reset-password.php?token={$reset_token}'>Reset Password</a>
  ";
  
  mail($email, 'Reset Password', $message, $headers);
}

// Example usage:
$email = 'example@example.com';
forgot_password($email);

?>


<?php

// Configuration
$config = array(
  'token_cookie_name' => 'reset_token'
);

function verify_reset_token($token) {
  global $config;
  
  // Get user data from database using reset token
  $user_data = get_user_by_reset_token($token);
  
  if (!$user_data) {
    return false;
  }
  
  // Check expiration time
  if (strtotime($user_data['reset_token_expires']) < strtotime('now')) {
    return false;
  }
  
  return $user_data;
}

function get_user_by_reset_token($token) {
  global $config;
  
  $db = new PDO('mysql:host=localhost;dbname=your-database', 'your-username', 'your-password');
  $stmt = $db->prepare("SELECT * FROM users WHERE reset_token = ?");
  $stmt->execute(array($token));
  return $stmt->fetch();
}

function get_user_data_by_reset_token($token) {
  global $config;
  
  // Get user data from database using reset token
  $user_data = verify_reset_token($token);
  
  if (!$user_data) {
    return null;
  }
  
  return $user_data;
}

// Example usage:
$token = $_GET['token'];
$user_data = get_user_data_by_reset_token($token);

if ($user_data) {
  // Display password reset form
  echo '
    <h1>Reset Password</h1>
    <form action="reset-password-post.php" method="post">
      <input type="password" name="new_password">
      <input type="submit" value="Reset Password">
    </form>
  ';
} else {
  // Display error message
  echo '<p>Error: Invalid reset token.</p>';
}

?>


<?php

// Configuration
$config = array(
  'token_cookie_name' => 'reset_token'
);

function verify_reset_token($token) {
  global $config;
  
  // Get user data from database using reset token
  $user_data = get_user_by_reset_token($token);
  
  if (!$user_data) {
    return false;
  }
  
  // Check expiration time
  if (strtotime($user_data['reset_token_expires']) < strtotime('now')) {
    return false;
  }
  
  return $user_data;
}

function update_password($id, $new_password_hash) {
  global $config;
  
  $db = new PDO('mysql:host=localhost;dbname=your-database', 'your-username', 'your-password');
  $stmt = $db->prepare("UPDATE users SET password_hash = ? WHERE id = ?");
  $stmt->execute(array($new_password_hash, $id));
}

// Example usage:
$token = $_GET['token'];
$new_password = $_POST['new_password'];

$user_data = verify_reset_token($token);

if ($user_data) {
  // Update user's password
  update_password($user_data['id'], password_hash($new_password, PASSWORD_DEFAULT));
  
  // Display success message
  echo '<p>Password updated successfully.</p>';
} else {
  // Display error message
  echo '<p>Error: Invalid reset token.</p>';
}

?>


<?php

// Configuration
define('DB_HOST', 'your_host');
define('DB_USER', 'your_user');
define('DB_PASSWORD', 'your_password');
define('DB_NAME', 'your_database');

require_once 'db-connection.php'; // Establishes database connection

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $email = trim($_POST['email']);

  if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid email address']);
    exit;
  }

  // Retrieve user ID from database
  $query = "SELECT id FROM users WHERE email = ?";
  $stmt = $mysqli->prepare($query);
  $stmt->bind_param('s', $email);
  $stmt->execute();
  $result = $stmt->get_result();

  if ($result->num_rows === 0) {
    http_response_code(400);
    echo json_encode(['error' => 'Email address not found']);
    exit;
  }

  // Generate password reset token
  $token = bin2hex(random_bytes(32));
  $query = "INSERT INTO reset_passwords (token, user_id) VALUES (?, ?)";
  $stmt = $mysqli->prepare($query);
  $stmt->bind_param('si', $token, $user_id);
  $stmt->execute();

  // Send email with password reset link
  $to = $email;
  $subject = 'Password Reset Request';
  $body = "Please click the following link to reset your password:

";
  $body .= '<a href="http://your-site.com/reset-password.php?token=' . $token . '">Reset Password</a>';

  mail($to, $subject, $body);

  echo json_encode(['message' => 'Password reset email sent']);
}
?>


<?php

// Configuration
define('DB_HOST', 'your_host');
define('DB_USER', 'your_user');
define('DB_PASSWORD', 'your_password');
define('DB_NAME', 'your_database');

require_once 'db-connection.php'; // Establishes database connection

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $token = trim($_POST['token']);

  if (empty($token)) {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid token']);
    exit;
  }

  // Retrieve user ID from reset_passwords table
  $query = "SELECT user_id FROM reset_passwords WHERE token = ?";
  $stmt = $mysqli->prepare($query);
  $stmt->bind_param('s', $token);
  $stmt->execute();
  $result = $stmt->get_result();

  if ($result->num_rows === 0) {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid token']);
    exit;
  }

  // Get user ID
  $user_id = $result->fetch_assoc()['user_id'];

  // Update password (for demonstration purposes only)
  $new_password = 'new-password';
  $query = "UPDATE users SET password_hash = ? WHERE id = ?";
  $stmt = $mysqli->prepare($query);
  $stmt->bind_param('si', sha256($new_password), $user_id);
  $stmt->execute();

  echo json_encode(['message' => 'Password updated successfully']);
}
?>


<?php

// Configuration
define('PASSWORD_RESET_LINK_EXPIRE', 3600); // expire in 1 hour
define('PASSWORD_RESET_LINK_SECRET', 'your-secret-key'); // secret key for token generation

// Forgot Password Function
function forgot_password($email) {
    // Get user data from database
    $user = get_user_by_email($email);

    if ($user === false) {
        return array('error' => 'User not found');
    }

    // Generate a password reset link
    $token = generate_reset_token($user->id, PASSWORD_RESET_LINK_EXPIRE);
    $reset_link = $_SERVER['HTTP_HOST'] . '/password-reset/' . $token;

    // Send email with password reset link
    send_password_reset_email($email, $reset_link);

    return array('success' => 'Password reset link sent to your email');
}

// Get user data by email function
function get_user_by_email($email) {
    // Query database to retrieve user data
    $query = "SELECT * FROM users WHERE email = '$email'";
    $result = mysqli_query($conn, $query);

    if ($result === false) {
        return false;
    }

    return $result->fetch_assoc();
}

// Generate password reset token function
function generate_reset_token($user_id, $expire_in) {
    // Use a secret key and the user ID to generate a unique token
    $token = hash_hmac('sha256', $user_id . time(), PASSWORD_RESET_LINK_SECRET);
    return $token;
}

// Send password reset email function
function send_password_reset_email($email, $reset_link) {
    // Set up email headers and body
    $subject = 'Reset Your Password';
    $body = "Click the link below to reset your password:

" . $reset_link;

    // Send email using PHP's mail() function or a library like SwiftMailer
    // ...
}

// Handle password reset request (e.g. via POST)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $result = forgot_password($email);

    if ($result['success']) {
        echo json_encode(array('message' => 'Password reset link sent to your email'));
    } else {
        echo json_encode($result);
    }
}
?>


// Forgot Password Function
function forgot_password($email) {
  // Connect to database
  $conn = mysqli_connect("localhost", "username", "password", "database_name");

  // Check if email exists in database
  $query = "SELECT * FROM users WHERE email = '$email'";
  $result = mysqli_query($conn, $query);
  $num_rows = mysqli_num_rows($result);

  if ($num_rows == 0) {
    echo "Email not found.";
    return false;
  }

  // Generate a random password
  $password = substr(md5(uniqid(rand(), true)), 0, 8);
  $password_hash = hash('sha256', $password);

  // Update user's password in database
  $query = "UPDATE users SET password_hash = '$password_hash' WHERE email = '$email'";
  mysqli_query($conn, $query);

  // Send email with reset link
  send_reset_email($email, $password);
}

// Function to send reset email
function send_reset_email($email, $password) {
  $subject = "Reset Password";
  $body = "Click here to reset your password: <a href='http://example.com/reset_password?email=$email&password=$password'>Reset Password</a>";
  mail($email, $subject, $body);
}


// Reset Password Function
function reset_password() {
  // Check if user has submitted form data
  if (!isset($_POST['email']) || !isset($_POST['password'])) {
    echo "Invalid request.";
    return false;
  }

  // Connect to database
  $conn = mysqli_connect("localhost", "username", "password", "database_name");

  // Get email and password from form data
  $email = $_POST['email'];
  $password = $_POST['password'];

  // Check if user's email matches the one in the reset link
  $query = "SELECT * FROM users WHERE email = '$email'";
  $result = mysqli_query($conn, $query);
  $num_rows = mysqli_num_rows($result);

  if ($num_rows == 0) {
    echo "Email not found.";
    return false;
  }

  // Update user's password in database
  $query = "UPDATE users SET password_hash = '$password' WHERE email = '$email'";
  mysqli_query($conn, $query);
}


// Example use case: forgot password form
echo "<form action='forget_password.php' method='post'>";
echo "Email: <input type='text' name='email'>";
echo "<button type='submit'>Submit</button>";
echo "</form>";

if (isset($_POST['email'])) {
  $email = $_POST['email'];
  forgot_password($email);
}


<?php

// Configuration
define('DB_HOST', 'localhost');
define('DB_USERNAME', 'your_username');
define('DB_PASSWORD', 'your_password');
define('DB_NAME', 'your_database');

// Connect to database
$conn = new mysqli(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Process form submission (forgot password)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Get user input
    $username = $_POST['username'];
    $email = $_POST['email'];

    // Validate user input
    if (!isset($username) || !isset($email)) {
        echo "Error: Username and email are required.";
        exit;
    }

    // Query database to retrieve user data
    $query = "SELECT * FROM users WHERE username = '$username' AND email = '$email'";
    $result = $conn->query($query);

    if ($result->num_rows > 0) {

        // Retrieve user data from result set
        $row = $result->fetch_assoc();

        // Generate a random password reset token
        $token = bin2hex(random_bytes(32));
        $expires_in = time() + (60 * 60 * 24); // expires in 1 day

        // Insert token into database (for security, store token separately from user data)
        $query = "INSERT INTO password_reset_tokens (username, token, expires_at) VALUES ('$username', '$token', '$expires_in')";
        $conn->query($query);

        // Send email with password reset link
        sendPasswordResetEmail($row['email'], $token);
    } else {
        echo "Error: Username or email not found.";
    }
}

// Close database connection
$conn->close();

// Helper function to send password reset email
function sendPasswordResetEmail($email, $token) {
    // Your email sending logic goes here (e.g., using PHPMailer)
    // For demonstration purposes, we'll use a simple echo statement
    echo "Sending email to $email with password reset token: $token
";
}

?>


<?php
require_once 'config.php'; // configuration file with database credentials

function sendResetEmail($username, $email) {
  // Create reset link with token
  $token = bin2hex(random_bytes(32));
  $resetLink = "http://example.com/reset-password/$token";

  // Send email using PHPMailer or a similar library
  $mail->setFrom('no-reply@example.com', 'Password Reset');
  $mail->addAddress($email);
  $mail->Subject = 'Reset Password';
  $mail->Body = 'Click the link below to reset your password: <a href="' . $resetLink . '">Reset Password</a>';
  $mail->send();

  // Store token in database
  $sql = "INSERT INTO password_resets (username, token) VALUES (:username, :token)";
  $stmt = $db->prepare($sql);
  $stmt->bindParam(':username', $username);
  $stmt->bindParam(':token', $token);
  $stmt->execute();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $email = $_POST['email'];
  $username = $_POST['username'];

  // Check if user exists
  $sql = "SELECT * FROM users WHERE email = :email AND username = :username";
  $stmt = $db->prepare($sql);
  $stmt->bindParam(':email', $email);
  $stmt->bindParam(':username', $username);
  $stmt->execute();
  $user = $stmt->fetch();

  if ($user) {
    // Send reset email
    sendResetEmail($username, $email);

    echo 'Password reset link sent to your email.';
  } else {
    echo 'Username or email not found.';
  }
} else {
?>
<form method="post">
  <label for="email">Email:</label>
  <input type="email" id="email" name="email"><br><br>
  <label for="username">Username:</label>
  <input type="text" id="username" name="username"><br><br>
  <button type="submit">Send Reset Link</button>
</form>
<?php
}
?>


<?php
require_once 'config.php';

function resetPassword($token) {
  // Check if token exists in database
  $sql = "SELECT * FROM password_resets WHERE token = :token";
  $stmt = $db->prepare($sql);
  $stmt->bindParam(':token', $token);
  $stmt->execute();
  $reset = $stmt->fetch();

  if ($reset) {
    // Prompt user to enter new password
    echo 'Enter your new password:<br>';
    $newPassword = $_POST['new_password'];

    // Hash and store new password in database
    $sql = "UPDATE users SET password_hash = :password_hash WHERE id = :id";
    $stmt = $db->prepare($sql);
    $stmt->bindParam(':password_hash', password_hash($newPassword, PASSWORD_DEFAULT));
    $stmt->bindParam(':id', $reset['user_id']);
    $stmt->execute();

    // Delete reset token from database
    $sql = "DELETE FROM password_resets WHERE token = :token";
    $stmt = $db->prepare($sql);
    $stmt->bindParam(':token', $token);
    $stmt->execute();

    echo 'Password reset successfully!';
  } else {
    echo 'Invalid token.';
  }
}

if (isset($_GET['token'])) {
  $token = $_GET['token'];
  resetPassword($token);
} else {
?>
<form method="post">
  <label for="new_password">New Password:</label>
  <input type="password" id="new_password" name="new_password"><br><br>
  <button type="submit">Reset Password</button>
</form>
<?php
}
?>


// forgot_password.php

require_once 'db_connect.php'; // database connection script

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $email = $_POST['email'];

    // Validate input
    if (empty($username) || empty($email)) {
        echo 'Error: Username and email are required.';
        exit;
    }

    // Query database to retrieve user data
    $query = "SELECT * FROM users WHERE username = '$username' AND email = '$email'";
    $result = mysqli_query($conn, $query);

    if ($result->num_rows == 0) {
        echo 'Error: User not found.';
        exit;
    }

    // Generate a random password
    $password = substr(md5(uniqid()), 0, 8);

    // Update user password in database
    $query = "UPDATE users SET password = '$password' WHERE username = '$username'";
    mysqli_query($conn, $query);

    // Send email with reset link
    send_email($email, $password);
}

function send_email($to_email, $password) {
    $subject = 'Password Reset';
    $body = "Dear user,

Your new password is: $password

Best regards,
[Your Application Name]";

    // Send email using your preferred method (e.g., PHPMailer, mail())
    mail($to_email, $subject, $body);
}

// Display forgot password form
echo '<form action="' . $_SERVER['PHP_SELF'] . '" method="post">';
echo 'Enter Username: <input type="text" name="username"><br>';
echo 'Enter Email: <input type="email" name="email"><br>';
echo '<button type="submit">Submit</button>';
echo '</form>';

?>


<?php

// Configuration
$db_host = 'localhost';
$db_username = 'your_username';
$db_password = 'your_password';
$db_name = 'your_database';

// Connect to database
$conn = new mysqli($db_host, $db_username, $db_password, $db_name);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Function to check if user exists and send reset link
function forgotPassword() {
  global $conn;

  // Get form data
  $email = $_POST['email'];

  // Check if email is valid
  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo "Invalid email";
    return;
  }

  // Query database to get user's ID and password
  $query = "SELECT id, password FROM users WHERE email = '$email'";
  $result = $conn->query($query);

  // Check if user exists
  if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $userId = $row['id'];
    $password = $row['password'];

    // Generate reset link (very basic example, not secure!)
    $resetLink = "http://yourwebsite.com/reset-password.php?email=$email&token=123456";

    // Send email with reset link
    sendEmail($email, "Reset Password", $resetLink);
  } else {
    echo "User does not exist";
  }
}

// Function to send email using PHPMailer (not included in this example)
function sendEmail($to, $subject, $message) {
  // Create a new instance of PHPMailer
  require_once 'PHPMailer/PHPMailer.php';
  $mail = new PHPMailer\PHPMailer\PHPMailer();

  // Set up SMTP settings
  $mail->isSMTP();
  $mail->Host = 'smtp.yourwebsite.com';
  $mail->Port = 587;
  $mail->SMTPAuth = true;
  $mail->Username = 'your_email@yourwebsite.com';
  $mail->Password = 'your_password';

  // Set up email content
  $mail->setFrom('your_email@yourwebsite.com', 'Your Name');
  $mail->addAddress($to);
  $mail->Subject = $subject;
  $mail->Body = $message;

  if (!$mail->send()) {
    echo "Email not sent: " . $mail->ErrorInfo;
  }
}

// Handle form submission
if (isset($_POST['submit'])) {
  forgotPassword();
} else {
  // Display form
  ?>
  <form action="<?php $_SERVER['PHP_SELF']; ?>" method="post">
    <label for="email">Email:</label>
    <input type="text" name="email"><br><br>
    <input type="submit" name="submit" value="Submit">
  </form>
  <?php
}
?>


<?php

// Configuration
define('SITE_URL', 'https://example.com');

function forgot_password($email) {
  // Check if email is valid
  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    return array('error' => 'Invalid email address');
  }

  // Query database to check if user exists
  $conn = mysqli_connect("localhost", "username", "password", "database");
  if (!$conn) {
    die("Connection failed: " . mysqli_error($conn));
  }
  $query = "SELECT * FROM users WHERE email = '$email'";
  $result = mysqli_query($conn, $query);
  if (mysqli_num_rows($result) == 0) {
    // User does not exist
    return array('error' => 'User not found');
  }

  // Generate reset token and password reset link
  $token = bin2hex(random_bytes(16));
  $password_reset_link = SITE_URL . '/reset-password?token=' . $token;

  // Insert token into database for later verification
  $query = "INSERT INTO password_resets (email, token) VALUES ('$email', '$token')";
  mysqli_query($conn, $query);

  // Send email with password reset link
  $to = $email;
  $subject = 'Reset your password';
  $message = '
    <p>Dear user,</p>
    <p>To reset your password, please click the following link:</p>
    <a href="' . $password_reset_link . '">' . $password_reset_link . '</a>
  ';
  mail($to, $subject, $message);

  // Return success message
  return array('success' => 'Password reset email sent');
}

?>


$email = 'user@example.com';
$response = forgot_password($email);
echo json_encode($response); // Output: {"success": "Password reset email sent"}


<?php

// Configuration
require_once 'config.php';

// Check if form has been submitted
if (isset($_POST['submit'])) {
  // Get form data
  $email = $_POST['email'];

  // Query database to retrieve user's email address
  $query = "SELECT * FROM users WHERE email = '$email'";
  $result = mysqli_query($conn, $query);

  if ($result) {
    // If user exists, generate new password and send it via email
    while ($row = mysqli_fetch_assoc($result)) {
      $new_password = substr(md5(uniqid()), 0, 8);
      $query_update = "UPDATE users SET password_hash = '" . password_hash($new_password, PASSWORD_DEFAULT) . "' WHERE id = '$row[id]'";
      mysqli_query($conn, $query_update);

      // Send email with new password
      send_email($email, $new_password);
    }
  } else {
    echo 'Error: User not found';
  }

} else {
  ?>
  <html>
    <body>
      <h1>Forgot Password</h1>
      <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
        Email Address: <input type="email" name="email"><br><br>
        <input type="submit" value="Send New Password" name="submit">
      </form>
    </body>
  </html>
  <?php
}

// Function to send email with new password
function send_email($to, $password) {
  $subject = 'New Password';
  $message = 'Your new password is: ' . $password;
  mail($to, $subject, $message);
}

?>


<?php

// Include your database connection settings
require_once('config.php');

if (isset($_POST['submit'])) {
    // Sanitize input
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);

    // Check if email exists in database
    $query = "SELECT * FROM users WHERE email = '$email'";
    $result = mysqli_query($conn, $query);
    
    if (mysqli_num_rows($result) > 0) {
        // Generate a token and store it in the password_reset table
        $token = bin2hex(random_bytes(32));
        $insertQuery = "INSERT INTO password_reset SET email='$email', token='$token'";
        mysqli_query($conn, $insertQuery);

        // Send an email to the user with the link to reset their password
        sendPasswordResetEmail($email, $token);
        
        echo '<div class="alert alert-success">A password reset email has been sent to your email address.</div>';
    } else {
        echo '<div class="alert alert-danger">No account found with that email.</div>';
    }
}

function sendPasswordResetEmail($email, $token) {
    // Your SMTP settings
    $smtpServer = 'your-smtp-server';
    $smtpPort = 587;
    $fromEmail = 'your-email@gmail.com';
    $password = 'your-password';

    // Email content
    $subject = "Password Reset Link";
    $body = "
        Hi there,
        
        You are receiving this email because we received a password reset request for your account.
        
        To reset your password, please click on the following link: 
        <a href='http://your-website.com/reset-password.php?token=$token'>$token</a>
    ";

    // Send email using SMTP
    $headers = "From: $fromEmail\r
";
    $headers .= "Bcc: $email\r
";
    $headers .= "MIME-Version: 1.0\r
";
    $headers .= "Content-Type: text/html; charset=UTF-8\r
";
    mail($email, $subject, $body, $headers);
}
?>


<?php

// Include your database connection settings
require_once('config.php');

if (isset($_GET['token'])) {
    // Check if token exists in password_reset table
    $query = "SELECT * FROM password_reset WHERE token='".$_GET['token']."'";
    $result = mysqli_query($conn, $query);
    
    if (mysqli_num_rows($result) > 0) {
        // Get the email and user ID from the password_reset table
        $row = mysqli_fetch_assoc($result);
        $email = $row['email'];
        
        // Create a form to ask for new password
        echo '<form action="update-password.php" method="post">
            <label>Enter your new password:</label>
            <input type="password" name="new_password"><br><br>
            <label>Confirm your new password:</label>
            <input type="password" name="confirm_password"><br><br>
            <input type="submit" value="Reset Password">
        </form>';
    } else {
        echo '<div class="alert alert-danger">Invalid token.</div>';
    }
}
?>


<?php

// Include your database connection settings
require_once('config.php');

if (isset($_POST['new_password'])) {
    // Check if both passwords match
    if ($_POST['new_password'] == $_POST['confirm_password']) {
        // Sanitize input
        $new_password = password_hash($_POST['new_password'], PASSWORD_DEFAULT);
        
        // Update the user's password in database
        $updateQuery = "UPDATE users SET password='$new_password' WHERE email='".$_SESSION['email']."'";
        mysqli_query($conn, $updateQuery);
        
        echo '<div class="alert alert-success">Password updated successfully.</div>';
    } else {
        echo '<div class="alert alert-danger">Passwords do not match.</div>';
    }
}
?>


<?php

// Configuration variables
define('RESET_LINK', 'https://example.com/reset-password.php');
define('ADMIN_EMAIL', 'admin@example.com');

// Function to generate a random token
function generateToken() {
    return bin2hex(random_bytes(16));
}

// Function to send reset email
function sendResetEmail($email, $token) {
    // Email headers
    $headers = "From: Admin <" . ADMIN_EMAIL . ">" . "\r
";
    $headers .= "Content-Type: text/html; charset=utf-8\r
";

    // Email body
    $body = "
        <h1>Reset Your Password</h1>
        <p>Please click the link below to reset your password:</p>
        <a href='" . RESET_LINK . "?token=" . $token . "'>Reset Password</a>
    ";

    // Send email using mail() function
    mail($email, 'Reset Password', $body, $headers);
}

// Forgot password function
function forgotPassword($email) {
    // Generate a random token
    $token = generateToken();

    // Store the token in the database (replace with your own database functions)
    storeTokenInDatabase($email, $token);

    // Send reset email
    sendResetEmail($email, $token);
}

// Example usage:
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];

    if ($email) {
        forgotPassword($email);
        echo "An email has been sent to your account with a link to reset your password.";
    } else {
        echo "Invalid email address.";
    }
} else {
    ?>
    <h1>Forgot Password</h1>
    <form action="" method="post">
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required><br><br>
        <button type="submit">Submit</button>
    </form>
    <?php
}

?>


CREATE TABLE users (
  id INT PRIMARY KEY,
  email VARCHAR(255) NOT NULL,
  password_hash VARCHAR(255) NOT NULL
);


<?php

// Configuration
$db_host = 'localhost';
$db_username = 'your_username';
$db_password = 'your_password';
$db_name = 'your_database';

// Connect to database
$conn = new mysqli($db_host, $db_username, $db_password, $db_name);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Function to check if email exists in the database
function check_email_exists($email) {
  global $conn;
  $query = "SELECT * FROM users WHERE email = '$email'";
  $result = $conn->query($query);

  if ($result->num_rows > 0) {
    return true;
  } else {
    return false;
  }
}

// Function to generate a password reset token
function generate_token() {
  return bin2hex(random_bytes(32));
}

// Handle forgot password request
if (isset($_POST['forgot_password'])) {

  // Get email from form input
  $email = $_POST['email'];

  // Check if email exists in the database
  if (check_email_exists($email)) {

    // Generate a password reset token
    $token = generate_token();

    // Update user's token in the database
    $query = "UPDATE users SET password_reset_token = '$token' WHERE email = '$email'";
    $conn->query($query);

    // Send password reset email to user
    $subject = 'Password Reset Request';
    $body = 'Click on this link to reset your password: <a href="http://yourwebsite.com/reset_password.php?token=' . $token . '">Reset Password</a>';
    mail($email, $subject, $body);

    echo "Email sent with password reset link. Please check your inbox.";
  } else {
    echo "Email not found in the database.";
  }
}

// Close connection
$conn->close();

?>


<?php

// Configuration
$db_host = 'localhost';
$db_username = 'your_username';
$db_password = 'your_password';
$db_name = 'your_database';

// Connect to database
$conn = new mysqli($db_host, $db_username, $db_password, $db_name);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Function to update password in the database
function update_password($token, $new_password) {
  global $conn;
  $query = "UPDATE users SET password_hash = '$new_password', password_reset_token = NULL WHERE password_reset_token = '$token'";
  $conn->query($query);

  if ($conn->affected_rows == 1) {
    return true;
  } else {
    return false;
  }
}

// Handle password reset request
if (isset($_POST['reset_password'])) {

  // Get token and new password from form input
  $token = $_GET['token'];
  $new_password = $_POST['new_password'];

  // Check if token exists in the database
  $query = "SELECT * FROM users WHERE password_reset_token = '$token'";
  $result = $conn->query($query);

  if ($result->num_rows > 0) {

    // Update user's password in the database
    if (update_password($token, password_hash($new_password, PASSWORD_DEFAULT))) {
      echo "Password updated successfully.";
    } else {
      echo "Error updating password.";
    }
  } else {
    echo "Token not found in the database.";
  }
}

// Close connection
$conn->close();

?>


<?php

require_once 'vendor/autoload.php'; // Load PHPMailer library

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Configuration
$mail = new PHPMailer(true);

$mail->isSMTP();
$mail->Host = 'smtp.example.com';
$mail->Port = 587;
$mail->Username = 'your_email@example.com';
$mail->Password = 'your_password';

$mail->setFrom('your_email@example.com', 'Your Name');

// Function to send password reset link via email
function forgot_password($email) {
    global $mail;

    // Retrieve user data from database
    $query = "SELECT id, password_hash FROM users WHERE email = :email";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':email', $email);
    $stmt->execute();

    if ($user = $stmt->fetch()) {
        // Generate new password and hash
        $new_password = bin2hex(random_bytes(16));
        $new_password_hash = password_hash($new_password, PASSWORD_DEFAULT);

        // Update user data in database
        $query = "UPDATE users SET password_hash = :password_hash WHERE id = :id";
        $stmt = $db->prepare($query);
        $stmt->bindParam(':password_hash', $new_password_hash);
        $stmt->bindParam(':id', $user['id']);
        $stmt->execute();

        // Send email with password reset link
        $link = 'http://example.com/reset-password.php?id=' . $user['id'] . '&token=' . hash('sha256', $new_password);
        $mail->addAddress($email);
        $mail->Subject = 'Password Reset Link';
        $mail->Body = 'Click this link to reset your password: ' . $link;
        $mail->send();

        return $new_password; // Return new password
    } else {
        throw new Exception('User not found');
    }
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    try {
        $new_password = forgot_password($email);
        echo "A password reset link has been sent to your email.";
    } catch (Exception $e) {
        echo "Error: " . $e->getMessage();
    }
} else {
    // Display form
    ?>
    <form action="" method="post">
        <label>Email:</label>
        <input type="email" name="email" required>
        <button type="submit">Send Password Reset Link</button>
    </form>
    <?php
}


<?php

// Configuration
$db = new PDO('mysql:host=localhost;dbname=your_database', 'your_username', 'your_password');

// Retrieve user data from database using ID and token
$query = "SELECT * FROM users WHERE id = :id AND password_hash = :password_hash";
$stmt = $db->prepare($query);
$stmt->bindParam(':id', $_GET['id']);
$stmt->bindParam(':password_hash', hash('sha256', $_POST['token']));
$stmt->execute();

if ($user = $stmt->fetch()) {
    // Update user data in database
    $new_password = $_POST['new_password'];
    $new_password_hash = password_hash($new_password, PASSWORD_DEFAULT);
    $query = "UPDATE users SET password_hash = :password_hash WHERE id = :id";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':password_hash', $new_password_hash);
    $stmt->bindParam(':id', $user['id']);
    $stmt->execute();

    echo "Your password has been updated successfully.";
} else {
    throw new Exception('Invalid token');
}
?>
<form action="" method="post">
    <label>New Password:</label>
    <input type="password" name="new_password" required>
    <button type="submit">Update Password</button>
</form>


<?php

// Configuration
$db_host = 'your_database_host';
$db_username = 'your_database_username';
$db_password = 'your_database_password';
$db_name = 'your_database_name';

// Connect to database
$conn = new mysqli($db_host, $db_username, $db_password, $db_name);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Function to check if email exists in database
function emailExists($email) {
  global $conn;
  $query = "SELECT * FROM users WHERE email = '$email'";
  $result = $conn->query($query);
  return ($result->num_rows > 0);
}

// Function to send password reset email
function sendResetEmail($email, $passwordResetToken) {
  global $conn;
  $subject = 'Password Reset Request';
  $body = "Click the link below to reset your password:
http://yourwebsite.com/reset-password?token=$passwordResetToken";
  mail($email, $subject, $body);
}

// Function to update user's password
function updatePassword($email, $newPassword) {
  global $conn;
  $query = "UPDATE users SET password_hash = '$newPassword' WHERE email = '$email'";
  $conn->query($query);
}

// Handle forgot password form submission
if (isset($_POST['forgot_password'])) {
  $email = $_POST['email'];

  // Check if email exists in database
  if (emailExists($email)) {
    // Generate random password reset token
    $passwordResetToken = bin2hex(random_bytes(32));

    // Update user's password to be a temporary token
    updatePassword($email, $passwordResetToken);

    // Send password reset email
    sendResetEmail($email, $passwordResetToken);
  } else {
    echo "Email not found.";
  }
}

?>


<?php

// Configuration
$db_host = 'your_database_host';
$db_username = 'your_database_username';
$db_password = 'your_database_password';
$db_name = 'your_database_name';

// Connect to database
$conn = new mysqli($db_host, $db_username, $db_password, $db_name);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Function to verify password reset token
function verifyToken($token) {
  global $conn;
  $query = "SELECT * FROM users WHERE password_hash = '$token'";
  $result = $conn->query($query);
  return ($result->num_rows > 0);
}

// Handle password reset form submission
if (isset($_POST['reset_password'])) {
  $token = $_POST['token'];
  $newPassword = $_POST['new_password'];

  // Verify password reset token
  if (verifyToken($token)) {
    // Update user's password to new password
    updatePassword($email, $newPassword);
  } else {
    echo "Invalid token.";
  }
}

?>


<?php

// Include database connection settings
require_once 'db_config.php';

// Set error reporting to display any errors
error_reporting(E_ALL);

// Check if user submitted the form
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Form fields: email, token (for security)
    $email = $_POST['email'];
    $token = $_POST['token'];

    // Validate form data
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "Invalid email address";
        exit;
    }

    // Retrieve user's hashed password and reset token from database
    try {
        $sql = "SELECT id, password_reset_token FROM users WHERE email = :email";
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        $user_data = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user_data) {
            // Compare reset tokens
            if (hash('sha256', $_POST['token']) == $user_data['password_reset_token']) {
                // Update user's password and generate new reset token
                $new_password = hash('sha256', random_bytes(32));
                $new_token = hash('sha256', random_bytes(32));

                try {
                    $sql = "UPDATE users SET password = :new_password, password_reset_token = :new_token WHERE email = :email";
                    $stmt = $db->prepare($sql);
                    $stmt->bindParam(':new_password', $new_password);
                    $stmt->bindParam(':new_token', $new_token);
                    $stmt->bindParam(':email', $email);
                    $stmt->execute();

                    // Send password reset instructions via email
                    $subject = "Password Reset Instructions";
                    $body = "Dear user, your new password is: $new_password. Please log in with this password.";
                    mail($email, $subject, $body);

                    echo "Password updated successfully! New password sent to your email.";
                } catch (PDOException $e) {
                    echo "Error updating password: " . $e->getMessage();
                }
            } else {
                echo "Invalid reset token";
            }

        } else {
            echo "Email address not found in database";
        }

    } catch (PDOException $e) {
        echo "Database error: " . $e->getMessage();
    }

} // End of form submission check

?>


<?php

// Configuration
$db_host = 'localhost';
$db_username = 'username';
$db_password = 'password';
$db_name = 'database';

// Connect to database
$conn = mysqli_connect($db_host, $db_username, $db_password, $db_name);

if (!$conn) {
  die("Connection failed: " . mysqli_error($conn));
}

// Function to send email with reset link
function send_reset_email($email, $reset_token) {
  // Send an email to the user with a reset link
  $subject = 'Reset Your Password';
  $body = '<p>Please click on the following link to reset your password:</p><p><a href="' . site_url('reset_password', array('token' => $reset_token)) . '">Reset Password</a></p>';
  mail($email, $subject, $body);
}

// Function to create and store a reset token
function create_reset_token() {
  // Generate a random string for the reset token
  $reset_token = bin2hex(random_bytes(32));
  return $reset_token;
}

// Function to send email with reset link
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $email = $_POST['email'];
  $query = "SELECT * FROM users WHERE email = '$email'";
  $result = mysqli_query($conn, $query);
  if (mysqli_num_rows($result) > 0) {
    // Get the user's row
    $user_row = mysqli_fetch_assoc($result);

    // Create a reset token and store it in the database
    $reset_token = create_reset_token();
    $query = "UPDATE users SET reset_token = '$reset_token', reset_expires = NOW() + INTERVAL 1 HOUR WHERE email = '$email'";
    mysqli_query($conn, $query);

    // Send an email with a reset link
    send_reset_email($email, $reset_token);
    echo 'Email sent to <strong>' . $email . '</strong> with a password reset link. Please check your email.';
  } else {
    echo 'Email not found. Try again!';
  }
} else {
  // Display the form
?>

<form action="<?php echo site_url('forgot_password', array('method' => 'post')); ?>" method="post">
  <label for="email">Enter your email:</label>
  <input type="email" id="email" name="email" required>
  <button type="submit">Send Reset Link</button>
</form>

<?php
}
?>


<?php

// Configuration settings
define('DB_HOST', 'your_database_host');
define('DB_USERNAME', 'your_database_username');
define('DB_PASSWORD', 'your_database_password');
define('DB_NAME', 'your_database_name');

// Connect to database
$mysqli = new mysqli(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME);

if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

// Check if form has been submitted
if (isset($_POST['email'])) {

    // Get user's email from form data
    $email = $_POST['email'];

    // SQL query to retrieve user's information
    $query = "SELECT * FROM users WHERE email = '$email'";
    $result = $mysqli->query($query);

    if ($result->num_rows > 0) {

        // Retrieve user's information
        while ($row = $result->fetch_assoc()) {
            $user_id = $row['id'];
            $username = $row['username'];
            $email = $row['email'];

            // Generate a password reset token (random string)
            $token = substr(str_shuffle('abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789'), 0, 32);

            // Update user's information with new token
            $update_query = "UPDATE users SET token = '$token' WHERE id = '$user_id'";
            $mysqli->query($update_query);

            // Send email to user with password reset link
            $to = $email;
            $subject = 'Password Reset Link';
            $message = '<p>Click on the following link to reset your password:</p><a href="http://' . $_SERVER['HTTP_HOST'] . '/reset_password.php?token=' . $token . '">Reset Password</a>';
            mail($to, $subject, $message);

            echo 'A password reset email has been sent to your email address.';
        }
    } else {
        echo 'No user found with that email address.';
    }

} else {

    // Form not submitted, display form
    ?>

    <h1>Forgot Password</h1>

    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
        <label for="email">Email:</label>
        <input type="text" id="email" name="email" required><br><br>
        <input type="submit" value="Submit">
    </form>

    <?php
}

// Close database connection
$mysqli->close();

?>


<?php

// Configuration settings
define('DB_HOST', 'your_host');
define('DB_USER', 'your_user');
define('DB_PASSWORD', 'your_password');
define('DB_NAME', 'your_database');

// Connect to database
$conn = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Function to send password reset email
function send_password_reset_email($email, $username) {
    // Generate a random token for the user
    $token = bin2hex(random_bytes(32));

    // Update the user's database record with the new token
    $query = "UPDATE users SET password_token = '$token' WHERE email = '$email'";
    if ($conn->query($query) === TRUE) {
        // Send email to user with password reset link
        $subject = 'Reset Your Password';
        $body = "
            Dear $username,
            
            You have requested to reset your password. Please click on the following link to reset your password:
            <a href='http://your_site.com/reset-password.php?token=$token'>Reset Password</a>
        
            If you did not request a password reset, please ignore this email.
        ";
        $from = 'your_email@example.com';
        $to = $email;
        mail($to, $subject, $body, "From: $from");

        echo "Email sent successfully.";
    } else {
        echo "Error updating user record: " . $conn->error;
    }
}

// Handle forgot password form submission
if (isset($_POST['forgot_password'])) {
    // Get email address from input field
    $email = $_POST['email'];

    // Check if email exists in database
    $query = "SELECT * FROM users WHERE email = '$email'";
    $result = $conn->query($query);

    if ($result->num_rows > 0) {
        // Get user's username from database record
        while ($row = $result->fetch_assoc()) {
            $username = $row['username'];

            // Send password reset email to user
            send_password_reset_email($email, $username);
        }
    } else {
        echo "Email address not found in database.";
    }
}

// Close database connection
$conn->close();

?>


<?php

// Configuration settings
define('DB_HOST', 'your_host');
define('DB_USER', 'your_user');
define('DB_PASSWORD', 'your_password');
define('DB_NAME', 'your_database');

// Connect to database
$conn = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Function to update user's password
function update_password($token, $new_password) {
    // Get user's database record using token
    $query = "SELECT * FROM users WHERE password_token = '$token'";
    $result = $conn->query($query);

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            // Update user's password in database
            $new_password_hash = password_hash($new_password, PASSWORD_DEFAULT);
            $query = "UPDATE users SET password = '$new_password_hash' WHERE id = '$row[id]'";
            if ($conn->query($query) === TRUE) {
                echo "Password updated successfully.";
            } else {
                echo "Error updating user record: " . $conn->error;
            }
        }
    } else {
        echo "Invalid token.";
    }
}

// Handle password reset form submission
if (isset($_POST['reset_password'])) {
    // Get token and new password from input fields
    $token = $_POST['token'];
    $new_password = $_POST['new_password'];

    // Update user's password in database using token
    update_password($token, $new_password);
}

// Close database connection
$conn->close();

?>


<?php

// Config variables
define('SITE_EMAIL', 'your@email.com');
define('PASSWORD_RESET_LINK_EXPIRATION_TIME', 3600); // 1 hour

// Function to send password reset link via email
function sendPasswordResetEmail($email) {
    $query = "SELECT * FROM users WHERE email = '$email'";
    $result = mysqli_query($conn, $query);
    
    if (mysqli_num_rows($result) > 0) {
        // Get the user's data
        $row = mysqli_fetch_assoc($result);
        
        // Generate a random password reset token
        $token = bin2hex(random_bytes(32));
        
        // Insert the token into the database with expiration time
        $query = "INSERT INTO password_reset_tokens (user_id, token, created_at) VALUES ('$row[id]', '$token', NOW())";
        mysqli_query($conn, $query);
        
        // Send email to user with reset link
        $to = $email;
        $subject = 'Reset your password';
        $body = "Click on the following link to reset your password: <a href='" . SITE_URL . "/reset-password.php?token=$token'>Reset Password</a>";
        mail($to, $subject, $body);
        
        echo "Email sent successfully!";
    } else {
        echo "User not found.";
    }
}

// Forgot password form handler
if (isset($_POST['forgot_password'])) {
    $email = $_POST['email'];
    
    if (!empty($email)) {
        sendPasswordResetEmail($email);
    } else {
        echo "Please enter your email address.";
    }
} ?>


<?php

// Check if the token is valid
if (isset($_GET['token'])) {
    $query = "SELECT * FROM password_reset_tokens WHERE token = '".$_GET['token']."'";
    $result = mysqli_query($conn, $query);
    
    if (mysqli_num_rows($result) > 0) {
        // Get the user's data
        $row = mysqli_fetch_assoc($result);
        
        // Check if the token has expired
        if (time() - strtotime($row['created_at']) < PASSWORD_RESET_LINK_EXPIRATION_TIME) {
            // Display password reset form
            echo "Enter new password: <input type='password' name='new_password'><br><br>";
            echo "<button name='reset_password'>Reset Password</button>";
            
            if (isset($_POST['reset_password'])) {
                $new_password = $_POST['new_password'];
                
                // Hash the new password
                $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
                
                // Update the user's password in database
                $query = "UPDATE users SET password_hash = '$hashed_password' WHERE id = '$row[id]'";
                mysqli_query($conn, $query);
                
                echo "Password reset successfully!";
            }
        } else {
            echo "Token has expired. Please request a new token.";
        }
    } else {
        echo "Invalid token.";
    }
} ?>


<?php

// Database connection settings
define('DB_HOST', 'localhost');
define('DB_USERNAME', 'your_username');
define('DB_PASSWORD', 'your_password');
define('DB_NAME', 'your_database');

// Email configuration
define('EMAIL_ADDRESS', 'your_email_address');
define('EMAIL_PASSWORD', 'your_email_password');

// Connect to database
$conn = new mysqli(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get user input
$email = $_POST['email'];

// Check if email exists in database
$query = "SELECT * FROM users WHERE email = '$email'";
$result = $conn->query($query);

if ($result->num_rows > 0) {
    // Email found, generate new password and send it via email
    $newPassword = substr(str_shuffle('abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789'), 0, 10);
    
    // Update user's password in database
    $updateQuery = "UPDATE users SET password = '$newPassword' WHERE email = '$email'";
    $conn->query($updateQuery);

    // Send new password via email using PHPMailer (or a similar library)
    require_once 'PHPMailer/src/PHPMailer.php';
    $mail = new PHPMailer\PHPMailer\PHPMailer();
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->Port = 587;
    $mail->SMTPSecure = 'tls';
    $mail->SMTPAuth = true;
    $mail->Username = EMAIL_ADDRESS;
    $mail->Password = EMAIL_PASSWORD;
    $mail->setFrom(EMAIL_ADDRESS, 'Your Name');
    $mail->addAddress($email);
    $mail->Subject = 'New Password for Your Account';
    $mail->Body = 'Your new password is: ' . $newPassword;
    if (!$mail->send()) {
        echo 'Error sending email';
        exit();
    }
    
    echo 'A new password has been sent to your email.';
} else {
    echo 'Email not found in database.';
}

// Close database connection
$conn->close();

?>


<?php

?>

<h1>Forgot Password</h1>

<form action="forgot_password.php" method="post">
  <label for="email">Email:</label>
  <input type="text" id="email" name="email"><br><br>
  <button type="submit">Submit</button>
</form>



<?php

// Configuration
define('PASSWORD_SALT', 'your_password_salt_here');
define('RESET_TOKEN_EXPIRE_HOURS', 1);

// Function to generate reset token
function generateResetToken() {
  return bin2hex(random_bytes(32));
}

// Function to send password reset email
function sendPasswordResetEmail($email, $reset_token) {
  // Use a library like SwiftMailer or PHPMailer to send the email
  // For this example, we'll use a simple email sending function
  $subject = 'Password Reset Request';
  $message = "Dear user,

You have requested a password reset. Please click on the following link to reset your password:
";
  $message .= "<a href='http://yourwebsite.com/reset-password?token=$reset_token'>Reset Password</a>";
  mail($email, $subject, $message);
}

// Handle forgot password request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  // Get the user's email address
  $email = $_POST['email'];
  
  // Check if the email is valid
  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    header('Location: /forgot-password?error=Invalid+email+address');
    exit;
  }
  
  // Find the user in the database
  $stmt = $pdo->prepare("SELECT * FROM users WHERE email = :email");
  $stmt->execute(['email' => $email]);
  $user = $stmt->fetch();
  
  if ($user) {
    // Generate a new reset token
    $reset_token = generateResetToken();
    
    // Store the reset token in the user's record
    $pdo->exec("UPDATE users SET reset_token = '$reset_token', reset_expires_at = CURRENT_TIMESTAMP + INTERVAL " . RESET_TOKEN_EXPIRE_HOURS . " HOUR WHERE id = :id", ['id' => $user['id']]);
    
    // Send a password reset email to the user
    sendPasswordResetEmail($email, $reset_token);
    
    header('Location: /forgot-password?success=Password+reset+email+sent');
  } else {
    header('Location: /forgot-password?error=User+not+found');
  }
} else {
  // Display the forgot password form
  ?>
  <h1>Forgot Password</h1>
  <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
    <label for="email">Email:</label>
    <input type="text" name="email" id="email"><br><br>
    <button type="submit">Send Reset Email</button>
  </form>
  <?php
}

?>


<?php
// Include the database connection settings
require_once 'db-config.php';

// Set up form handling and error handling
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);

    // Validate user input
    if (empty($username) || empty($email)) {
        echo "Error: Both username and email are required.";
        exit;
    }

    // Retrieve the user data from the database
    try {
        $query = "SELECT * FROM users WHERE username = :username OR email = :email";
        $stmt = $db->prepare($query);
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':email', $email);

        if ($stmt->execute()) {
            $user = $stmt->fetch();

            // If a user exists, generate a reset token and send the password reset email
            if (!empty($user)) {
                // Generate a random reset token (replace with your own logic)
                $resetToken = bin2hex(random_bytes(32));

                // Update the user's reset token in the database
                try {
                    $query = "UPDATE users SET reset_token = :resetToken WHERE id = :id";
                    $stmt = $db->prepare($query);
                    $stmt->bindParam(':resetToken', $resetToken);
                    $stmt->bindParam(':id', $user['id']);

                    if ($stmt->execute()) {
                        // Send the password reset email
                        sendPasswordResetEmail($email, $resetToken);

                        echo "A password reset link has been sent to your email address.";
                    } else {
                        throw new Exception("Error: Unable to update user data.");
                    }
                } catch (Exception $e) {
                    // Handle database errors
                    echo "Database Error: " . $e->getMessage();
                }
            } else {
                echo "Error: User not found with provided credentials.";
            }
        } else {
            throw new Exception("Error: Database query failed.");
        }
    } catch (Exception $e) {
        // Handle SQL errors
        echo "Database Error: " . $e->getMessage();
    }
}

function sendPasswordResetEmail($email, $resetToken)
{
    // Email template for the password reset link
    $subject = 'Reset Your Password';
    $body = "<p>Click on the following link to reset your password:</p><p><a href='http://example.com/reset-password.php?token=" . $resetToken . "'>" . $resetToken . "</a></p>";

    // Send the email (use a library like PHPMailer for a secure implementation)
    mail($email, $subject, $body);
}
?>


<?php
// Include the database connection settings
require_once 'db-config.php';

// Set up form handling and error handling
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve the reset token from the URL query string
    $token = $_GET['token'];

    // Validate user input (e.g., password, confirm password)
    if (empty($_POST['password'])) {
        echo "Error: Password is required.";
        exit;
    }

    // Update the user's password and reset token in the database
    try {
        $query = "UPDATE users SET password_hash = :passwordHash, reset_token = '' WHERE reset_token = :resetToken";
        $stmt = $db->prepare($query);
        $stmt->bindParam(':passwordHash', $_POST['password']);
        $stmt->bindParam(':resetToken', $token);

        if ($stmt->execute()) {
            echo "Password updated successfully.";
        } else {
            throw new Exception("Error: Unable to update user data.");
        }
    } catch (Exception $e) {
        // Handle database errors
        echo "Database Error: " . $e->getMessage();
    }
}
?>


<?php

// Configuration
$database = 'your_database_name';
$username = 'your_username';
$password = 'your_password';

// Connect to database
$conn = new mysqli($database, $username, $password);

// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

// Function to send password reset email
function sendResetEmail($email) {
  // Email configuration (replace with your SMTP settings)
  $server = 'your_smtp_server';
  $port = 587;
  $username = 'your_email_username';
  $password = 'your_email_password';

  // Mail transport settings
  $transport = Swift_SmtpTransport::newInstance($server, $port)
    ->setUsername($username)
    ->setPassword($password);

  // Create a message
  $message = Swift_Message::newInstance()
    ->setSubject('Reset your password')
    ->setFrom(array('your_email@example.com' => 'Your Name'))
    ->setTo(array($email))
    ->setBody('Click this link to reset your password: <a href="' . $_SERVER['REQUEST_URI'] . '?reset=' . md5($email) . '">Reset Password</a>');

  // Send the email
  $mailer = Swift_Mailer::newInstance($transport);
  if ($mailer->send($message)) {
    return true;
  } else {
    return false;
  }
}

// Handle forgot password form submission
if (isset($_POST['forgot_password'])) {
  $email = $_POST['email'];

  // Validate email address
  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo 'Invalid email address';
    exit();
  }

  // Query database for user with matching email address
  $sql = "SELECT * FROM users WHERE email = '$email'";
  $result = $conn->query($sql);
  $user = $result->fetch_assoc();

  if ($user) {
    // Send password reset email
    sendResetEmail($email);

    echo 'Password reset link has been sent to your email';
  } else {
    echo 'No user with that email address exists';
  }
}

// Display forgot password form
?>
<form action="" method="post">
  <input type="email" name="email" placeholder="Enter your email address">
  <button type="submit" name="forgot_password">Forgot Password</button>
</form>


<?php

// Configuration
define('DB_HOST', 'your_database_host');
define('DB_USERNAME', 'your_database_username');
define('DB_PASSWORD', 'your_database_password');
define('DB_NAME', 'your_database_name');

// Connect to database
$conn = new mysqli(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Define variables
$email = $_POST['email'];

// Validate email
if (empty($email)) {
    echo 'Please enter your email address.';
    exit;
}

// Check if user exists
$sql = "SELECT * FROM users WHERE email = '$email'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // User found, generate new password and send to email
    $new_password = bin2hex(random_bytes(32));
    $sql = "UPDATE users SET password_hash = SHA2('$new_password', 512) WHERE email = '$email'";
    $conn->query($sql);

    // Send email with new password
    $to = $email;
    $subject = 'Your new password is: ' . $new_password;
    $message = 'Your new password is: ' . $new_password;
    $headers = 'From: your_email@example.com' . "\r
" .
        'Reply-To: your_email@example.com' . "\r
" .
        'X-Mailer: PHP/' . phpversion();
    mail($to, $subject, $message, $headers);

    echo 'Your new password has been sent to ' . $email;
} else {
    echo 'User not found.';
}

$conn->close();

?>


<?php
require_once 'dbconnect.php'; // database connection file

if (isset($_POST['forgot_password'])) {
    $email = $_POST['email'];

    // Check if the email is valid and exists in the database
    $query = "SELECT * FROM users WHERE email = '$email'";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) > 0) {
        // Generate a random token for password reset
        $token = bin2hex(random_bytes(32));

        // Update the user's record with the new token
        $update_query = "UPDATE users SET token = '$token' WHERE email = '$email'";
        mysqli_query($conn, $update_query);

        // Send an email to the user with a link to reset their password
        $subject = 'Reset Your Password';
        $message = "
            <html>
                <body>
                    <p>Click on this link to reset your password:</p>
                    <a href='http://example.com/reset_password.php?token=$token'>Reset Password</a>
                </body>
            </html>
        ";

        // Send the email
        $headers = 'MIME-Version: 1.0' . "\r
";
        $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r
";
        $headers .= 'From: no-reply@example.com' . "\r
";

        mail($email, $subject, $message, $headers);

        echo '<script>alert("Email sent to reset password!"); window.location.href = "login.php";</script>';
    } else {
        echo '<script>alert("Invalid email or not registered!"); window.location.href = "login.php";</script>';
    }
}
?>


<?php
require_once 'dbconnect.php'; // database connection file

if (isset($_GET['token'])) {
    $token = $_GET['token'];

    // Check if the token is valid and exists in the database
    $query = "SELECT * FROM users WHERE token = '$token'";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) > 0) {
        // If the user's record is found, update their password with the new one they entered
        echo '
            <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                <input type="password" name="new_password">
                <button type="submit">Change Password</button>
            </form>
        ';

        if (isset($_POST['change_password'])) {
            $new_password = $_POST['new_password'];

            // Hash the new password and update the user's record
            $hashed_new_password = password_hash($new_password, PASSWORD_DEFAULT);
            $update_query = "UPDATE users SET password = '$hashed_new_password' WHERE token = '$token'";
            mysqli_query($conn, $update_query);

            echo '<script>alert("Password changed!"); window.location.href = "login.php";</script>';
        }
    } else {
        echo 'Invalid Token';
    }
}
?>


<?php
$conn = mysqli_connect('localhost', 'username', 'password', 'database_name');
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

function query($query) {
    global $conn;
    return mysqli_query($conn, $query);
}
?>


<?php

// Configuration variables (update these to match your database)
$db_host = 'localhost';
$db_username = 'your_username';
$db_password = 'your_password';
$db_name = 'your_database';

// Connect to database
$conn = new mysqli($db_host, $db_username, $db_password, $db_name);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

function sendResetEmail($user_id, $token) {
  // Send email with reset link (using a library like PHPMailer or SwiftMailer)
  $email = 'your_email@example.com';
  $subject = 'Password Reset Link';

  $body = '
    <p>Hello!</p>
    <p>Click the link below to reset your password:</p>
    <a href="' . $_SERVER['HTTP_HOST'] . '/reset-password/' . $token . '">Reset Password</a>
  ';

  // Send email using your chosen library
}

function generateRandomToken() {
  return bin2hex(random_bytes(32));
}

function createPasswordResetToken($user_id) {
  $conn = new mysqli($db_host, $db_username, $db_password, $db_name);
  
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  $token = generateRandomToken();
  $expires_at = date('Y-m-d H:i:s', strtotime('+1 day'));

  $stmt = $conn->prepare('INSERT INTO password_reset_tokens (user_id, token, expires_at) VALUES (?, ?, ?)');
  $stmt->bind_param('is', $user_id, $token, $expires_at);
  $stmt->execute();

  return $token;
}

function forgotPassword($email) {
  $conn = new mysqli($db_host, $db_username, $db_password, $db_name);

  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  $stmt = $conn->prepare('SELECT id FROM users WHERE email = ?');
  $stmt->bind_param('s', $email);
  $stmt->execute();
  $result = $stmt->get_result();

  if ($result->num_rows > 0) {
    $user_id = $result->fetch_assoc()['id'];
    
    $token = createPasswordResetToken($user_id);
    
    sendResetEmail($user_id, $token);

    echo 'A password reset link has been sent to your email.';
  } else {
    echo 'User not found.';
  }
}

?>


forgotPassword('user@example.com');


function resetPassword($token) {
  $conn = new mysqli($db_host, $db_username, $db_password, $db_name);

  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  $stmt = $conn->prepare('SELECT user_id FROM password_reset_tokens WHERE token = ? AND expires_at > NOW()');
  $stmt->bind_param('s', $token);
  $stmt->execute();
  $result = $stmt->get_result();

  if ($result->num_rows > 0) {
    $user_id = $result->fetch_assoc()['user_id'];

    // Prompt user to enter new password
    $new_password = $_POST['new_password'];
    $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

    $stmt = $conn->prepare('UPDATE users SET password_hash = ? WHERE id = ?');
    $stmt->bind_param('si', $hashed_password, $user_id);
    $stmt->execute();

    // Delete password reset token
    $stmt = $conn->prepare('DELETE FROM password_reset_tokens WHERE user_id = ? AND token = ?');
    $stmt->bind_param('ii', $user_id, $token);
    $stmt->execute();

    echo 'Password reset successfully!';
  } else {
    echo 'Invalid or expired token.';
  }
}


<?php

require_once 'config.php'; // Load your database connection settings

if (isset($_POST['submit'])) {
  $email = $_POST['email'];

  try {
    // Get user data from database
    $stmt = $pdo->prepare('SELECT * FROM users WHERE email = :email');
    $stmt->execute([':email' => $email]);
    $user = $stmt->fetch();

    if ($user) {
      // Generate a new password and send it to the user's email address
      $new_password = generatePassword();
      mail($email, 'Reset Password', 'New Password: ' . $new_password);

      // Update the user's password in the database
      $stmt = $pdo->prepare('UPDATE users SET password_hash = :password WHERE id = :id');
      $stmt->execute([':password' => hash('sha256', $new_password), ':id' => $user['id']]);

      echo 'New password sent to your email address.';
    } else {
      echo 'Email not found in our database.';
    }
  } catch (PDOException $e) {
    echo 'Error sending new password: ' . $e->getMessage();
  }
}

function generatePassword() {
  // Generate a random password using a combination of letters and numbers
  $password = '';
  for ($i = 0; $i < 10; $i++) {
    $charType = rand(1, 2); // Randomly select between letter and number
    if ($charType == 1) { // Letter
      $password .= chr(rand(65, 90)); // Uppercase letter
    } else { // Number
      $password .= rand(0, 9);
    }
  }
  return $password;
}

?>


<?php

// Configuration
define('RESET_TOKEN_EXPIRE', 60); // Token expiration time in minutes
define('PASSWORD_RESET_TEMPLATE_DIR', 'path/to/template/directory');

// Function to send password reset email
function send_reset_email($email, $name) {
    $token = bin2hex(random_bytes(32));
    $reset_token = array(
        "user_id" => get_user_id_by_email($email),
        "token" => $token,
        "expires_at" => date("Y-m-d H:i:s", time() + (RESET_TOKEN_EXPIRE * 60))
    );
    
    // Update reset token in database
    update_reset_token($reset_token);
    
    // Send email with reset link
    send_email_with_reset_link($email, $name, $token);
}

// Function to send password reset email
function send_email_with_reset_link($to_email, $username, $token) {
    $subject = "Password Reset for Your Account";
    $body = "Please click on this link to reset your password: <a href='" . URLROOT . "/reset-password/" . $token . "'>" . URLROOT . "/reset-password/" . $token . "</a>";
    
    // Implement actual email sending logic here. For example using PHPMailer or SwiftMailer
}

// Function to handle password reset form submission
function handle_password_reset_submission($email, $password) {
    $user = get_user_by_email($email);
    if ($user) {
        update_password($user['id'], $password);
        
        // After updating the user's password, consider deleting their reset token.
        delete_reset_token($user['id']);
        
        return true; // Password updated successfully
    }
    
    return false;
}

// Function to retrieve a user by email (helper function)
function get_user_by_email($email) {
    $conn = mysqli_connect("localhost", "username", "password", "database");
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    
    // Query to retrieve user data
    $query = "SELECT * FROM users WHERE email = '$email'";
    $result = mysqli_query($conn, $query);
    if (mysqli_num_rows($result) > 0) {
        return mysqli_fetch_assoc($result);
    } else {
        return null;
    }
}

// Function to retrieve a user's id by email (helper function)
function get_user_id_by_email($email) {
    $user = get_user_by_email($email);
    if ($user) {
        return $user['id'];
    } else {
        return null;
    }
}

// Function to update password
function update_password($user_id, $password) {
    // Update user's password here using your chosen hashing method.
}

// Function to update reset token in database (helper function)
function update_reset_token($token) {
    $conn = mysqli_connect("localhost", "username", "password", "database");
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    
    // Insert new or update existing reset token
}

// Function to delete a reset token (helper function)
function delete_reset_token($user_id) {
    $conn = mysqli_connect("localhost", "username", "password", "database");
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    
    // Delete the reset token associated with a user
}

// Example usage:
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    send_reset_email($email, '');
}

?>


<?php

// Include database connection file
require_once 'db_connection.php';

// Check if form has been submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  // Get user input
  $email = $_POST['email'];

  // Validate email
  if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo "Invalid email address.";
    exit;
  }

  // Query database for user with matching email
  $query = "SELECT * FROM users WHERE email = '$email'";
  $result = mysqli_query($conn, $query);

  if (mysqli_num_rows($result) > 0) {
    // Retrieve user data
    $user_data = mysqli_fetch_assoc($result);

    // Generate random password and send it to the user's email
    $new_password = bin2hex(random_bytes(16));
    $subject = "Your new password";
    $message = "
      <p>Hello $user_data[username],</p>
      <p>Your new password is: $new_password</p>
      <p>Please log in with this new password.</p>
    ";
    mail($email, $subject, $message);

    // Update user's password
    $query = "UPDATE users SET password = '$new_password' WHERE email = '$email'";
    mysqli_query($conn, $query);

    echo "New password has been sent to your email.";
  } else {
    echo "No user found with this email address.";
  }
}

// Close database connection
mysqli_close($conn);


// Define a function to reset passwords
function forgot_password($username, $email) {
    // Check if the user exists
    $query = "SELECT * FROM users WHERE username = '$username' AND email = '$email'";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) == 0) {
        return 'User not found';
    }

    // Generate a reset token and store it in the database
    $reset_token = bin2hex(random_bytes(16));
    $update_query = "UPDATE users SET reset_token = '$reset_token' WHERE username = '$username'";
    mysqli_query($conn, $update_query);

    // Send an email with the reset link
    send_reset_email($email, $reset_token);
}

// Define a function to send the reset email
function send_reset_email($email, $reset_token) {
    // Set up mail server credentials (replace with your own)
    $server = 'smtp.gmail.com';
    $port = 587;
    $username = 'your-email@gmail.com';
    $password = 'your-password';

    // Compose the email
    $subject = 'Reset Password Link';
    $body = "Click this link to reset your password: <a href='https://example.com/reset-password?token=$reset_token'>Reset Password</a>";

    try {
        // Send the email using mail()
        mail($email, $subject, $body, "From: $username");
        echo 'Email sent successfully!';
    } catch (Exception $e) {
        echo 'Error sending email: ' . $e->getMessage();
    }
}

// Example usage:
$username = 'johndoe';
$email = 'john@example.com';

forgot_password($username, $email);


<?php

// Configuration settings
define('DB_HOST', 'localhost');
define('DB_USER', 'your_username');
define('DB_PASSWORD', 'your_password');
define('DB_NAME', 'your_database');

// Connect to database
try {
    $pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASSWORD);
} catch (PDOException $e) {
    exit('Error: ' . $e->getMessage());
}

// Form submission handling
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    // Get form data
    $email = $_POST['email'];

    // Validate email address
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "Invalid email address.";
        exit;
    }

    // Find user by email address
    $stmt = $pdo->prepare('SELECT * FROM users WHERE email = :email');
    $stmt->bindParam(':email', $email);
    $stmt->execute();

    // Get the result
    $user = $stmt->fetch();

    if (!$user) {
        echo "Email not found.";
        exit;
    }

    // Generate new password and send to user via email
    $newPassword = substr(md5(uniqid(mt_rand(), true)), 0, 8);
    $to      = $email;
    $subject = 'Your New Password';
    $message = 'New password: ' . $newPassword;
    $headers = 'From: your_email@example.com' . "\r
" .
        'Reply-To: no-reply@example.com' . "\r
" .
        'X-Mailer: PHP/' . phpversion();

    mail($to, $subject, $message, $headers);

    echo "A new password has been sent to your email address.";
} else {

?>
<!DOCTYPE html>
<html>
<head>
    <title>Forgot Password</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            text-align: center;
        }
    </style>
</head>

<body>

<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
    Email Address: <br>
    <input type="text" name="email"><br><br>
    <input type="submit" value="Submit">
</form>

<?php } ?>


<?php

// Configuration
define('DB_HOST', 'localhost');
define('DB_USER', 'your_username');
define('DB_PASSWORD', 'your_password');
define('DB_NAME', 'your_database');

// Connect to database
$mysqli = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

// Function to send reset password email
function send_reset_email($email) {
    // Replace with your own mail server configuration
    $mail = new PHPMailer();
    $mail->isSMTP();
    $mail->Host = 'smtp.example.com';
    $mail->Port = 587;
    $mail->SMTPAuth = true;
    $mail->Username = 'your_email@example.com';
    $mail->Password = 'your_password';

    // Set email content
    $mail->setFrom('your_email@example.com', 'Your Name');
    $mail->addAddress($email);
    $mail->Subject = 'Reset Your Password';
    $mail->Body = '
        Hello!

        To reset your password, please click on the following link:
        <a href="' . htmlspecialchars($_SERVER['HTTP_HOST'] . '/reset_password.php?email=' . urlencode($email)) . '">Reset Password</a>
    ';

    // Send email
    if (!$mail->send()) {
        echo 'Mailer Error: ' . $mail->ErrorInfo;
    }
}

// Function to reset password
function reset_password($email) {
    // Retrieve user ID from database
    $query = "SELECT id FROM users WHERE email = '$email'";
    $result = $mysqli->query($query);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $user_id = $row['id'];

        // Generate new password and hash it
        $new_password = substr(str_shuffle('abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789'), 0, 12);
        $password_hash = password_hash($new_password, PASSWORD_DEFAULT);

        // Update user password in database
        $query = "UPDATE users SET password_hash = '$password_hash' WHERE id = '$user_id'";
        $mysqli->query($query);

        return $new_password;
    } else {
        return null;
    }
}

// Handle forgot password form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];

    // Check if email exists in database
    $query = "SELECT * FROM users WHERE email = '$email'";
    $result = $mysqli->query($query);

    if ($result->num_rows > 0) {
        send_reset_email($email);
        echo 'Reset password link sent to your email.';
    } else {
        echo 'Email not found in database.';
    }
}

// Close database connection
$mysqli->close();

?>


<?php

// Configuration
define('DB_HOST', 'localhost');
define('DB_USER', 'your_username');
define('DB_PASSWORD', 'your_password');
define('DB_NAME', 'your_database');

// Connect to database
$mysqli = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

// Retrieve user ID from database
$email = $_GET['email'];
$query = "SELECT id FROM users WHERE email = '$email'";
$result = $mysqli->query($query);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $user_id = $row['id'];

    // Retrieve new password from database
    $query = "SELECT password_hash FROM users WHERE id = '$user_id'";
    $password_result = $mysqli->query($query);
    $new_password_row = $password_result->fetch_assoc();

    echo 'Your new password is: <strong>' . $new_password_row['password_hash'] . '</strong>';
} else {
    echo 'Email not found in database.';
}

// Close database connection
$mysqli->close();

?>


<?php
// Include the database connection settings (replace with your own)
require_once 'config.php';

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the email from the form input
    $email = $_POST['email'];

    // Validate the email address
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo 'Invalid email address';
        exit;
    }

    // Query to get the user's ID and password hash (for security)
    $query = "SELECT id, password FROM users WHERE email = '$email'";
    $result = mysqli_query($conn, $query);

    // Check if a user with that email exists
    if (mysqli_num_rows($result) == 1) {
        // Get the user's ID and password hash
        $row = mysqli_fetch_assoc($result);
        $userId = $row['id'];
        $passwordHash = $row['password'];

        // Generate a random token for password reset
        $token = bin2hex(random_bytes(32));

        // Insert the token into the database (replace with your own table name)
        $query = "INSERT INTO password_reset_tokens (user_id, token) VALUES ('$userId', '$token')";
        mysqli_query($conn, $query);

        // Send an email with a link to reset the password
        $subject = 'Reset Your Password';
        $body = '
            <p>Hello,' . $email . ',</p>
            <p>To reset your password, click on this link:</p>
            <a href="http://yourwebsite.com/reset_password.php?token=' . $token . '">Reset Password</a>
        ';
        sendEmail($email, $subject, $body);
    } else {
        echo 'No user found with that email address';
    }
}

// Function to send an email using PHPMailer (replace with your own mail function)
function sendEmail($to, $subject, $body) {
    require_once 'PHPMailer/src/Exception.php';
    require_once 'PHPMailer/src/PHPMailer.php';
    require_once 'PHPMailer/src/SMTP.php';

    $mail = new PHPMailer\PHPMailer\PHPMailer();

    try {
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com'; // Replace with your own SMTP server
        $mail->Port = 587;
        $mail->SMTPAuth = true;
        $mail->Username = 'your-email@gmail.com';
        $mail->Password = 'your-password';

        $mail->setFrom('your-email@gmail.com', 'Your Name');
        $mail->addAddress($to);
        $mail->Subject = $subject;
        $mail->Body = $body;

        if (!$mail->send()) {
            throw new Exception('Error sending email: ' . $mail->ErrorInfo);
        }
    } catch (Exception $e) {
        echo 'Email not sent: ' . $e->getMessage();
    }
}
?>


<?php
// Check if the token has been passed in the URL
$token = $_GET['token'];

// Query to get the user's ID from the database (replace with your own table name)
$query = "SELECT id FROM password_reset_tokens WHERE token = '$token'";
$result = mysqli_query($conn, $query);

// Check if a valid token exists for this user
if (mysqli_num_rows($result) == 1) {
    // Get the user's ID
    $row = mysqli_fetch_assoc($result);
    $userId = $row['id'];

    // Display the password reset form
    echo '
        <form action="reset_password.php" method="post">
            <label for="password">New Password:</label>
            <input type="password" id="password" name="password"><br><br>
            <button type="submit" class="btn btn-primary">Reset Password</button>
        </form>
    ';

    // Check if the form has been submitted
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Get the new password from the form input
        $newPassword = $_POST['password'];

        // Query to update the user's password (replace with your own table name)
        $query = "UPDATE users SET password = '$newPassword' WHERE id = '$userId'";
        mysqli_query($conn, $query);

        // Remove the token from the database
        $query = "DELETE FROM password_reset_tokens WHERE token = '$token'";
        mysqli_query($conn, $query);

        echo 'Your password has been reset successfully!';
    }
} else {
    echo 'Invalid or expired token';
}
?>


<?php

// Assuming we have a database connection object called $db

function forgot_password($username, $email) {
  // Retrieve user data from database based on username and email
  $query = "SELECT * FROM users WHERE username = '$username' AND email = '$email'";
  $result = mysqli_query($db, $query);
  
  if (mysqli_num_rows($result) > 0) {
    // Generate a reset token and store it in the user's record
    $reset_token = bin2hex(random_bytes(16));
    $update_query = "UPDATE users SET reset_token = '$reset_token' WHERE username = '$username' AND email = '$email'";
    mysqli_query($db, $update_query);
    
    // Send an email to the user with a password reset link
    send_password_reset_email($email, $reset_token);
  } else {
    echo "Username and/or email not found.";
  }
}

function send_password_reset_email($email, $reset_token) {
  // Generate a password reset link (e.g., https://example.com/reset-password?token=xyz123)
  $reset_link = "https://example.com/reset-password?token=$reset_token";
  
  // Send an email to the user with instructions on how to reset their password
  $subject = "Reset Your Password";
  $body = "
    <p>Hello $username,</p>
    <p>To reset your password, please click on this link:</p>
    <a href='$reset_link'>$reset_link</a>
  ";
  
  // Use a library like PHPMailer or SwiftMailer to send the email
}

// Example usage:
forgot_password("john", "john@example.com");

?>


<?php

function reset_password($token) {
  // Retrieve the user's data from database based on the token
  $query = "SELECT * FROM users WHERE reset_token = '$token'";
  $result = mysqli_query($db, $query);
  
  if (mysqli_num_rows($result) > 0) {
    // Update the user's password in the database
    $new_password = trim($_POST['password']);
    $hash_password = hash_password($new_password); // Use a library like PHPass or bcrypt to hash the password
    $update_query = "UPDATE users SET password = '$hash_password', reset_token = '' WHERE reset_token = '$token'";
    mysqli_query($db, $update_query);
    
    echo "Password updated successfully!";
  } else {
    echo "Invalid token.";
  }
}

function hash_password($password) {
  // Use a library like PHPass or bcrypt to hash the password
}

// Example usage:
if (isset($_POST['token'])) {
  reset_password(trim($_POST['token']));
}
?>


<?php

require 'db.php'; // Include your database connection script here.

function sendPasswordResetEmail($username, $token) {
    $emailBody = "Click on this link to reset your password: <a href='" . url('reset_password', array('token' => $token)) . "'>" . url('reset_password', array('token' => $token)) . "</a>";
    // Send the email using PHPMailer or a similar library, using $username's email address.
}

function handleForgotPasswordRequest() {
  global $db;

  $username = $_POST['username'];
  $email = $_POST['email'];

  if (empty($username) || empty($email)) {
      echo "Please enter both your username and email.";
      exit;
  }

  // Find the user in the database
  $query = "SELECT * FROM users WHERE username = '$username' AND email = '$email'";
  $result = mysqli_query($db, $query);

  if (mysqli_num_rows($result) == 0) {
      echo "Username or email not found.";
      exit;
  }

  // Generate a password reset token
  $token = bin2hex(random_bytes(16));

  // Update the user's entry in the database to include their new token and expiration time
  $query = "UPDATE users SET password_reset_token = '$token', reset_expires_at = NOW() + INTERVAL 1 DAY WHERE username = '$username' AND email = '$email'";
  mysqli_query($db, $query);

  sendPasswordResetEmail($username, $token);
}

if (isset($_POST['submit'])) {
    handleForgotPasswordRequest();
}
?>


// Include the database connection settings
include 'db_config.php';

// Check if the form has been submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Extract the username from the form data
    $username = $_POST['username'];

    // Query the database to retrieve the user's email and password
    $query = "SELECT * FROM users WHERE username = '$username'";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) > 0) {
        // Retrieve the user's email and password from the result set
        $row = mysqli_fetch_assoc($result);
        $email = $row['email'];
        $password = $row['password'];

        // Generate a random password reset token
        $token = bin2hex(random_bytes(16));

        // Update the user's database record with the new password reset token
        $query = "UPDATE users SET password_reset_token = '$token' WHERE username = '$username'";
        mysqli_query($conn, $query);

        // Send an email to the user with a link to reset their password
        send_password_reset_email($email, $token);
    } else {
        echo 'Error: Username not found';
    }
}

// Function to send a password reset email
function send_password_reset_email($email, $token) {
    // Define the email template
    $subject = 'Reset Your Password';
    $body = '
    <p>Dear '.$username.',</p>
    <p>You are receiving this email because we received a request to reset your password.</p>
    <p>To reset your password, click on the following link:</p>
    <a href="' . base_url('reset_password.php?token=' . $token) . '">Reset Password</a>
    ';

    // Send the email using PHPMailer or your preferred email library
    mail($email, $subject, $body);
}


<?php

// Configuration
$dsn = 'mysql:host=localhost;dbname=your_database';
$username = 'your_username';
$password = 'your_password';

// Database Connection
try {
    $conn = new PDO($dsn, $username, $password);
} catch (PDOException $e) {
    echo 'Connection failed: ' . $e->getMessage();
}

// Forgot Password Form
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get user input
    $email = $_POST['email'];

    // Validate email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo 'Invalid email address';
        exit;
    }

    // Query database to retrieve user data
    try {
        $stmt = $conn->prepare('SELECT * FROM users WHERE email = :email');
        $stmt->bindParam(':email', $email);
        $stmt->execute();

        if ($stmt->rowCount() == 0) {
            echo 'Email not found';
            exit;
        }

        // Retrieve user data
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        // Generate a random password
        $new_password = bin2hex(random_bytes(16));

        // Update user password in database
        try {
            $stmt = $conn->prepare('UPDATE users SET password = :password WHERE email = :email');
            $stmt->bindParam(':password', $new_password);
            $stmt->bindParam(':email', $email);
            $stmt->execute();

            // Send password reset email
            sendPasswordResetEmail($user, $new_password);

            echo 'New password sent to your email';
        } catch (PDOException $e) {
            echo 'Error updating password: ' . $e->getMessage();
        }
    } catch (PDOException $e) {
        echo 'Error retrieving user data: ' . $e->getMessage();
    }
}

// Function to send password reset email
function sendPasswordResetEmail($user, $new_password)
{
    // Configuration
    $from_email = 'your_email@example.com';
    $subject = 'Your new password';

    // Email template
    $email_template = "
        Dear {name},

        Your new password is: {$new_password}

        Best regards,
        Your Name
    ";

    // Replace placeholders in email template
    $email_template = str_replace('{name}', $user['name'], $email_template);

    // Send email using PHPMailer (or your preferred email library)
    $mail = new PHPMailer;
    $mail->isSMTP();
    $mail->Host = 'smtp.example.com';
    $mail->Port = 587;
    $mail->Username = $from_email;
    $mail->Password = 'your_password';
    $mail->setFrom($from_email, 'Your Name');
    $mail->addAddress($user['email']);
    $mail->Subject = $subject;
    $mail->Body = $email_template;

    if (!$mail->send()) {
        echo 'Error sending email: ' . $mail->ErrorInfo;
    }
}


<?php

// Configuration
$db_host = 'localhost';
$db_username = 'your_username';
$db_password = 'your_password';
$db_name = 'your_database';

// Connect to database
$conn = new mysqli($db_host, $db_username, $db_password, $db_name);

if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

// Check if form has been submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  // Get user's email from form
  $email = $_POST['email'];

  // Check if email is valid and exists in database
  $query = "SELECT * FROM users WHERE email = '$email'";
  $result = $conn->query($query);

  if ($result->num_rows > 0) {
    // Get user's ID from result
    $row = $result->fetch_assoc();
    $user_id = $row['id'];

    // Generate new password and store it in database
    $new_password = substr(str_shuffle('abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789'), 0, 8);
    $query = "UPDATE users SET password_hash = SHA1('$new_password') WHERE id = '$user_id'";
    $conn->query($query);

    // Send email with new password to user
    $subject = 'Your new password';
    $message = "Dear user,

Your new password is: $new_password

Best regards, [Your Name]";
    mail($email, $subject, $message);
  }

  // Redirect user back to login page
  header('Location: login.php');
  exit;
}

?>


<?php
require_once 'config.php';

// Check if the form has been submitted
if (isset($_POST['submit'])) {
    // Get user input
    $email = trim($_POST['email']);

    // Validate email address
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "Invalid email address";
        return;
    }

    // Query the database to retrieve the user's ID
    $query = "SELECT id FROM users WHERE email = '$email'";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) == 1) {
        // Retrieve the user's ID and email address
        $row = mysqli_fetch_assoc($result);
        $userId = $row['id'];
        $userEmail = $row['email'];

        // Generate a password reset token
        $token = bin2hex(random_bytes(32));

        // Store the token in the database
        $query = "UPDATE users SET token = '$token' WHERE id = '$userId'";
        mysqli_query($conn, $query);

        // Send an email with the password reset link
        sendPasswordResetEmail($userEmail, $token);
    } else {
        echo "No account found with this email address";
    }
}

// Function to send a password reset email
function sendPasswordResetEmail($email, $token) {
    // Get the user's name from the database (optional)
    $query = "SELECT username FROM users WHERE email = '$email'";
    $result = mysqli_query($conn, $query);
    $row = mysqli_fetch_assoc($result);
    $username = $row['username'];

    // Email content
    $subject = "Password Reset Request";
    $body = "
        <p>Hello $username,</p>
        <p>You have requested to reset your password. Please click on the following link to reset your password:</p>
        <a href='https://example.com/reset-password.php?token=$token'>Reset Password</a>
        <p>If you did not request a password reset, please ignore this email.</p>
    ";

    // Send the email using PHPMailer (or any other mail library)
    $mail = new PHPMailer();
    $mail->isSMTP();
    $mail->Host = 'smtp.example.com';
    $mail->Port = 587;
    $mail->Username = 'your-email@example.com';
    $mail->Password = 'your-password';
    $mail->setFrom('your-email@example.com', 'Your Name');
    $mail->addAddress($email);
    $mail->Subject = $subject;
    $mail->Body = $body;
    if (!$mail->send()) {
        echo "Error sending email: " . $mail->ErrorInfo;
    }
}
?>


<?php

// Configuration settings
define('EMAIL_ADDRESS', 'admin@example.com'); // Email address to send emails from
define('SITE_NAME', 'Your Website Name'); // Website name

// Check if the form has been submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $email = $_POST['email'];
  
  // Validate email address
  if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo "Invalid email address.";
    exit;
  }
  
  // Get user details from database
  $db = new PDO('mysql:host=localhost;dbname=your_database', 'username', 'password');
  $stmt = $db->prepare("SELECT * FROM users WHERE email = :email");
  $stmt->bindParam(':email', $email);
  $stmt->execute();
  
  // If user exists
  if ($user = $stmt->fetch()) {
    // Generate a new password and store it in the database
    $new_password = generatePassword();
    $hashed_new_password = password_hash($new_password, PASSWORD_DEFAULT);
    
    // Update user's password in database
    $update_stmt = $db->prepare("UPDATE users SET password_hash = :password WHERE email = :email");
    $update_stmt->bindParam(':password', $hashed_new_password);
    $update_stmt->bindParam(':email', $email);
    $update_stmt->execute();
    
    // Send the new password to user via email
    sendEmail($user['username'], $new_password, $email);
  } else {
    echo "User does not exist.";
  }
}

function generatePassword() {
  // Function to generate a strong password
  $length = 12;
  $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
  $password = '';
  for ($i = 0; $i < $length; $i++) {
    $password .= substr($chars, rand(0, strlen($chars) - 1), 1);
  }
  return $password;
}

function sendEmail($username, $new_password, $email) {
  // Function to send email
  $subject = "New Password for Your Account";
  $message = "Hello $username,

Your new password is: $new_password

Best regards,
$SITE_NAME";
  
  $headers = "From: " . EMAIL_ADDRESS . "\r
";
  $headers .= "Content-type: text/html\r
";
  
  mail($email, $subject, $message, $headers);
}

?>


<?php

// Include your database connection script or use PDO
require_once 'db.php';

if (isset($_POST['submit'])) {
    // Get the user's email address from the form submission
    $email = $_POST['email'];

    // Check if the email address exists in the database
    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = :email");
    $stmt->bindParam(':email', $email);
    $stmt->execute();

    // If the email is found, generate a reset token and update the user's record
    if ($user = $stmt->fetch()) {
        // Generate a random reset token (e.g. a UUID)
        $reset_token = bin2hex(random_bytes(32));

        // Update the user's record with the new reset token
        $update_stmt = $pdo->prepare("UPDATE users SET reset_token = :token WHERE id = :id");
        $update_stmt->bindParam(':token', $reset_token);
        $update_stmt->bindParam(':id', $user['id']);
        $update_stmt->execute();

        // Send an email to the user with a password reset link
        $subject = "Reset Password";
        $body = "Click here to reset your password: <a href='https://yourwebsite.com/reset_password.php?token=$reset_token'>Reset Password</a>";
        mail($email, $subject, $body);

        echo "Password reset email sent to $email. Please check your inbox and follow the link to reset your password.";
    } else {
        echo "Email address not found.";
    }
}

// Display the forgot password form
?>
<form method="post" action="">
  <label for="email">Email Address:</label>
  <input type="email" id="email" name="email"><br><br>
  <button type="submit" name="submit">Send Password Reset Email</button>
</form>

<?php


<?php

// Include your database connection script or use PDO
require_once 'db.php';

if (isset($_GET['token'])) {
    // Get the reset token from the URL parameter
    $reset_token = $_GET['token'];

    // Check if the reset token is valid and not expired
    $stmt = $pdo->prepare("SELECT * FROM users WHERE reset_token = :token AND reset_expires > NOW()");
    $stmt->bindParam(':token', $reset_token);
    $stmt->execute();

    // If the token is valid, display a password change form
    if ($user = $stmt->fetch()) {
        ?>
        <form method="post" action="">
          <label for="password">New Password:</label>
          <input type="password" id="password" name="password"><br><br>
          <label for="confirm_password">Confirm New Password:</label>
          <input type="password" id="confirm_password" name="confirm_password"><br><br>
          <button type="submit" name="submit">Change Password</button>
        </form>

        <?php
    } else {
        echo "Invalid or expired reset token.";
    }
}

// Update the user's password if the form is submitted
if (isset($_POST['submit'])) {
    // Get the new password from the form submission
    $new_password = $_POST['password'];

    // Check if the passwords match
    if ($_POST['password'] == $_POST['confirm_password']) {
        // Update the user's record with the new password
        $update_stmt = $pdo->prepare("UPDATE users SET password_hash = :hash WHERE id = :id");
        $update_stmt->bindParam(':hash', password_hash($new_password, PASSWORD_DEFAULT));
        $update_stmt->bindParam(':id', $user['id']);
        $update_stmt->execute();

        echo "Password changed successfully!";
    } else {
        echo "Passwords do not match.";
    }
}

// Display the reset password form
?>


<?php

// Database configuration
$dbHost = 'localhost';
$dbUsername = 'your_username';
$dbPassword = 'your_password';
$dbName = 'your_database';

// Create connection and select database
$conn = new mysqli($dbHost, $dbUsername, $dbPassword, $dbName);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Function to send forgot password email
function sendForgotPasswordEmail($username, $email, $new_password)
{
    // Define the message that will be sent in the email
    $message = "
        Dear $username,

        Your new password is: $new_password

        Best regards,
        Your Website
    ";

    // Send the email using your preferred method (e.g., PHPMailer)
    // For this example, we'll use the built-in mail function
    mail($email, "Forgot Password", $message);
}

// Function to reset password
function resetPassword($username, $new_password)
{
    global $conn;

    // Prepare and execute query to update password
    $stmt = $conn->prepare("UPDATE users SET password = ? WHERE username = ?");
    $stmt->bind_param('ss', $new_password, $username);
    $stmt->execute();

    // Get the new password hash (we're using bcrypt)
    $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

    return $hashed_password;
}

// Check if the user exists and send a reset link
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $email = $_POST['email'];

    // Query to get the user's email address
    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ? AND email = ?");
    $stmt->bind_param('ss', $username, $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // User exists, send a reset link
        $user = $result->fetch_assoc();

        // Generate a new password (this example uses a simple password generator)
        $new_password = substr(str_shuffle('abcdefghijklmnopqrstuvwxyz'), 0, 8);

        // Update the user's email address with their new password
        $hashed_password = resetPassword($username, $new_password);
        sendForgotPasswordEmail($username, $email, $new_password);
    } else {
        echo "User not found";
    }
}

?>


<?php

// Configuration
define('SITE_URL', 'http://example.com/'); // replace with your site URL
define('EMAIL_ADDRESS', 'admin@example.com'); // your email address for reset emails

require_once 'db_connection.php'; // Your database connection script

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];

    if (empty($email)) {
        echo "Email is required.";
        exit;
    }

    $sql = "SELECT * FROM users WHERE email = '$email'";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) == 0) {
        echo "Email not found. Please check your email and try again.";
        exit;
    } else {
        // Get the user's ID
        $row = mysqli_fetch_assoc($result);
        $userId = $row['id'];

        // Generate a random token for password reset
        $token = bin2hex(random_bytes(32));

        // Store the token in the database
        $sql = "UPDATE users SET password_reset_token = '$token' WHERE id = '$userId'";
        mysqli_query($conn, $sql);

        // Send email with reset link
        sendResetEmail($email, $token);
    }
}

function sendResetEmail($email, $token) {
    $subject = 'Reset Your Password';
    $body = '
        <p>Dear user,</p>
        <p>To reset your password, please click on this link:</p>
        <a href="' . SITE_URL . 'reset_password.php?token=' . $token . '">Reset Your Password</a>
        <p>Best regards,</p>
    ';

    mail($email, $subject, $body);
}

?>


<?php

require_once 'db_connection.php'; // Your database connection script

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    $token = $_GET['token'];

    if (empty($token)) {
        echo "Invalid request.";
        exit;
    }

    $sql = "SELECT * FROM users WHERE password_reset_token = '$token'";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) == 0) {
        echo "Token is invalid or has expired. Please try resetting your password again.";
        exit;
    } else {
        // The user's data is available here
        // You can create a form for the new password and store it in the database.

        ?>
        <form action="update_password.php" method="post">
            <label>Enter New Password:</label>
            <input type="password" name="new_password"><br><br>
            <input type="submit" value="Change Password">
        </form>

        <?php
    }
}

?>


<?php

require_once 'db_connection.php'; // Your database connection script

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $newPassword = $_POST['new_password'];

    if (empty($newPassword)) {
        echo "New password is required.";
        exit;
    }

    $token = $_GET['token'];
    $sql = "UPDATE users SET password_reset_token = '', password = '$newPassword' WHERE password_reset_token = '$token'";
    mysqli_query($conn, $sql);

    echo "Your password has been updated successfully!";
}

?>


<?php
$conn = new mysqli('localhost', 'username', 'password', 'database');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>

