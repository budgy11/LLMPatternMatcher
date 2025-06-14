
<?php

// Database connection details - Replace with your actual credentials
$host = "localhost";
$username = "your_db_username";
$password = "your_db_password";
$dbname = "your_db_name";

// Function to reset password
function forgotPassword($email, $dbname, $host, $username, $password) {
  // 1. Validate Email
  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    return "Invalid email address.";
  }

  // 2.  Check if user exists
  $conn = new mysqli($host, $username, $password, $dbname);
  if ($conn->connect_error) {
    return "Connection failed: " . $conn->connect_error;
  }

  $sql = "SELECT id, username FROM users WHERE email = '$email'";
  $result = $conn->query($sql);

  if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
    $userId = $user['id'];
    $userName = $user['username'];

    // 3. Generate a unique token
    $token = bin2hex(random_bytes(32)); // Secure random token
    
    // 4.  Store token in database for user
    $sql = "UPDATE users SET reset_token = '$token' WHERE id = '$userId'";
    if (!$conn->query($sql)) {
      return "Error updating token: " . $conn->error;
    }

    // 5.  Send email with reset link
    $subject = "Password Reset Link";
    $to = $email;
    $headers = "From: your_email@example.com"; // Replace with your email
    $resetLink = "http://localhost/reset_password.php?token=$token"; // Replace with your reset link URL
    $message = "Click on the link above to reset your password.";

    mail($to, $message, $headers);
    return "Password reset email has been sent to $email.";

  } else {
    return "User not found.";
  }

  $conn->close();
}


// Example usage (Handle form submission)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $email = $_POST["email"];
  $result = forgotPassword($email, $dbname, $host, $username, $password);
  echo "<p>" . $result . "</p>";
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>Forgot Password</title>
</head>
<body>

  <h1>Forgot Password</h1>

  <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
    <label for="email">Email:</label>
    <input type="email" id="email" name="email" required>
    <button type="submit">Reset Password</button>
  </form>

</body>
</html>


<?php

// Configuration (Replace with your actual values)
$db_host = 'localhost';
$db_name = 'your_database_name';
$db_user = 'your_database_username';
$db_password = 'your_database_password';

// Function to reset password
function forgotPassword($email, $new_password) {
  // 1. Connect to the database
  $conn = new mysqli($db_host, $db_user, $db_password, $db_name);

  // Check connection
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  // 2. Prepare and execute the SQL query
  $sql = "UPDATE users SET password = ? WHERE email = ?";
  $stmt = $conn->prepare($sql);

  if ($stmt) {
    $stmt->bind_param("ss", $new_password, $email);  // Use 'ss' for string

    if ($stmt->execute()) {
      // Password reset successful
      $stmt->close();
      return true;
    } else {
      // Password reset failed
      $stmt->close();
      return false;
    }
  } else {
    // Prepare statement failed
    $stmt = null; // Ensure $stmt is null to avoid issues
    return false;
  }
}


// Example Usage (For testing - **DO NOT USE THIS IN A PRODUCTION ENVIRONMENT**)
// **Important Security Considerations - See Below**

// Test email and new password (replace with actual values for testing)
$test_email = 'test@example.com';  // Replace with a valid email address for testing
$new_password = 'NewPassword123'; // Replace with a strong password


// Call the function to reset the password
if (forgotPassword($test_email, $new_password)) {
  echo "Password reset successfully for $test_email";
} else {
  echo "Password reset failed for $test_email.  Check the database and email configuration.";
}


// **IMPORTANT SECURITY CONSIDERATIONS AND BEST PRACTICES**

// 1.  Salt and Hashing:
//     *  **NEVER** store passwords in plain text in your database.
//     *  Always hash passwords using a strong hashing algorithm (like bcrypt or Argon2) *before* storing them in the database.
//     *  Use a unique salt for each user.
//     *  Use a library for password hashing (e.g., password_hash() and password_verify() in PHP).
//     *  This example is for demonstration purposes only and does *not* include proper password hashing.

// 2.  Email Verification (Crucial!)
//     *   **ALWAYS** send an email to the user containing a temporary, unique link.  This link should:
//         *  Be a unique token that can be verified.
//         *  Expire after a short period (e.g., 30 minutes).
//         *  Lead to a secure page where the user can set a new, permanent password.
//     *  This prevents attackers from guessing the password and resetting it without user interaction.

// 3.  Rate Limiting:
//     *  Implement rate limiting to prevent brute-force password reset attempts.

// 4.  Input Validation:
//     *   Thoroughly validate the email address and password input.  Check for valid email formats, sanitize input to prevent SQL injection, and limit password length.

// 5.  Security Audits:
//     *  Regularly review your code for security vulnerabilities.
//     *  Consider using a security scanner.

// 6.  Error Handling:
//     *  Implement robust error handling to catch and log any errors that occur during the password reset process.  *Never* display detailed error messages to the user in a production environment.  Log them securely.

// 7.  Temporary Token:
//     * Use a unique, randomly generated token to represent the reset request. This token should be stored in the database along with the email.  This token is used to verify that the user is actually requesting a password reset.

// 8.  HTTPS:
//     *  Always use HTTPS to encrypt communication between the user's browser and your server.


?>


<?php

// Assuming you have a database connection established (e.g., $db)
// And a 'users' table with a 'email' column

// Function to handle password reset requests
function forgotPassword($email) {
  // 1. Validate Email (Important for security!)
  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    return "Invalid email address.";
  }

  // 2. Check if the email exists in the database
  $query = "SELECT id, password_reset_token, expiry_timestamp FROM users WHERE email = ?";
  $stmt = $db->prepare($query); // Assuming $db is your database connection
  $stmt->execute([$email]);
  $user = $stmt->fetch(PDO::FETCH_ASSOC);

  if (!$user) {
    return "Email not found.";
  }

  // 3. Generate a unique password reset token
  $token = bin2hex(random_bytes(32)); // Generate a secure random token

  // 4. Store the token in the database for this user
  $query = "INSERT INTO password_resets (user_id, token, expiry_timestamp) VALUES (?, ?, ?)";
  $stmt = $db->prepare($query);
  $stmt->execute([$user['id'], $token, time() + (60 * 60 * 24)]); // Token valid for 24 hours

  // 5. Send the password reset email (implementation omitted for brevity)
  //  - This would involve sending an email with a link containing the token
  //    (e.g.,  https://yourwebsite.com/reset_password.php?token=$token)
  //  -  Use a library or function for sending emails (e.g., PHPMailer)

  return "Password reset email sent.  Please check your inbox.";

}

// Example usage (for demonstration only - don't expose this in a production environment!)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $email = $_POST['email'];
  $reset_message = forgotPassword($email);
  echo "<p>" . $reset_message . "</p>";
}
?>

<!-- HTML Form to submit email for reset -->
<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
  <label for="email">Email:</label>
  <input type="email" id="email" name="email" required>
  <button type="submit">Reset Password</button>
</form>


<?php

/**
 * Forgot Password Function
 *
 * This function handles the password reset process.  It generates a unique,
 * time-limited token, sends an email to the user with a link to reset their
 * password, and stores the token and expiration date in the database.
 *
 * @param string $email The user's email address.
 * @param string $resetUrl  The base URL for the reset password page.
 * @param string $secretKey  A secret key used for signing the token (important for security).
 * @return bool True on success, false on failure.
 */
function forgot_password(string $email, string $resetUrl, string $secretKey) {
  // 1. Generate a Unique Token
  $token = bin2hex(random_bytes(32));

  // 2.  Set Expiration Time -  Example: 1 hour
  $expirationTime = time() + 3600; // Add 1 hour to the current timestamp

  // 3. Hash the Token with the Secret Key
  $hashedToken = hash_file('sha256', $token); // Use a strong hashing algorithm

  // 4. Store the Token and Expiration Time in the Database
  $db = new PDO("mysql:host=localhost;dbname=your_database_name", "your_db_user", "your_db_password"); // Replace with your database credentials.  Ensure PDO is properly configured.
  try {
    $stmt = $db->prepare("INSERT INTO forgot_password_tokens (email, token, hashed_token, expiration_time) VALUES (?, ?, ?, ?)");
    $stmt->execute([$email, $token, $hashedToken, $expirationTime]);
    return true;
  } catch (PDOException $e) {
    // Handle database errors (e.g., email already exists)
    error_log("Error creating forgot password token: " . $e->getMessage()); //Log for debugging
    return false;
  }

  // 5. Send the Password Reset Email (Example -  You'll need to adapt this)
  $subject = "Password Reset Request";
  $message = "Click this link to reset your password: " . $resetUrl . "/reset?token=" . $token;
  $headers = "From: your_email@example.com\r
"; // Replace with your sender email

  mail($email, $subject, $message, $headers); // Uses PHP's built-in mail function.  Consider using a library like PHPMailer.



  return true;
}


// Example Usage (for demonstration - adapt to your application)
//  This is for testing only.  Don't use this directly in production without proper error handling and security measures.
/*
$email = "testuser@example.com";
$resetUrl = "http://localhost/reset_password";
$secretKey = "your_secret_key"; //  Generate a strong, random secret key.  Don't hardcode it in production.

if (forgot_password($email, $resetUrl, $secretKey)) {
  echo "Password reset request sent successfully!";
} else {
  echo "Failed to send password reset request.";
}
*/



//  Important Security Considerations:
//  - **Secret Key:**  Generate a truly random and strong secret key. Store it securely (e.g., environment variables, a secrets management system).  Never commit this to your codebase.
//  - **Token Expiration:** Short expiration times are crucial for security. 1 hour is a good starting point.
//  - **Hashing:**  Always hash the token with a strong hashing algorithm like SHA256.
//  - **Input Validation & Sanitization:**  Validate and sanitize the email address to prevent injection attacks.
//  - **Rate Limiting:**  Implement rate limiting to prevent brute-force attacks.
//  - **HTTPS:**  Always use HTTPS to encrypt the communication between the user and your server.
//  - **Database Security:**  Protect your database from unauthorized access.  Use parameterized queries to prevent SQL injection.

?>


<?php

// Ensure this file is protected to prevent direct access to the database
if (!defined('PH__PROCESS_GUARD')) {
    die('Access denied');
}


/**
 * Forgets a user's password and sends a password reset link.
 *
 * @param string $email The user's email address.
 * @return bool True if a reset link was sent, false otherwise.
 */
function forgot_password(string $email): bool
{
    // 1. Validate Email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        error_log("Invalid email format provided: " . $email); // Log for debugging
        return false;
    }

    // 2. Check if User Exists
    $user = get_user_by_email($email);
    if (!$user) {
        error_log("User with email " . $email . " not found");
        return false;
    }

    // 3. Generate a Unique Token
    $token = generate_unique_token();

    // 4. Store Token in Database (Hash it for security)
    $hashed_token = hash('sha256', $token); // Use SHA256 for stronger hashing
    $result = save_token_to_database($user->id, $hashed_token);
    if (!$result) {
        error_log("Failed to save token to database for user " . $email);
        return false;
    }

    // 5.  Construct the Password Reset Link
    $reset_link = generate_reset_link($user->email, $token);

    // 6. Send the Reset Email
    if (!send_reset_email($user->email, $reset_link)) {
        error_log("Failed to send password reset email to " . $email);
        // Optional:  You could delete the token from the database here,
        // if you want to ensure the reset link isn't usable if the email
        // fails to send.  However, this increases complexity.
        return false;
    }

    return true;
}


/**
 * Placeholder function to retrieve a user by email.  Implement your database query here.
 * @param string $email
 * @return User|null
 */
function get_user_by_email(string $email): ?User {
    // Replace with your actual database query
    // This is just a dummy example.
    // You'd normally fetch the user from your database table.

    // Example:  Assuming you have a User class
    //  $user =  DB::query("SELECT * FROM users WHERE email = ?", $email)->first();
    //  return $user;

    // Dummy User class for demonstration.
    class User {
        public $id;
        public $email;

        public function __construct(int $id, string $email) {
            $this->id = $id;
            $this->email = $email;
        }
    }

    return new User(1, $email); // Placeholder return
}

/**
 * Placeholder function to generate a unique token.
 * @return string
 */
function generate_unique_token(): string
{
    return bin2hex(random_bytes(32)); // Using bin2hex for better security
}

/**
 * Placeholder function to save the token to the database.
 * @param int $user_id
 * @param string $hashed_token
 * @return bool
 */
function save_token_to_database(int $user_id, string $hashed_token): bool
{
    // Replace with your database insert query
    // Example:
    // DB::table('password_resets')->insert([
    //     'user_id' => $user_id,
    //     'token' => $hashed_token,
    //     'created_at' => time(),
    // ]);
    // Return true if the insert was successful, false otherwise.

    return true; // Placeholder return
}


/**
 * Placeholder function to generate the password reset link.
 * @param string $email
 * @param string $token
 * @return string
 */
function generate_reset_link(string $email, string $token): string
{
    return "/reset_password?email=" . urlencode($email) . "&token=" . urlencode($token);
}


/**
 * Placeholder function to send the password reset email.
 * @param string $email
 * @param string $reset_link
 */
function send_reset_email(string $email, string $reset_link): bool
{
    // Replace with your email sending logic (e.g., using PHPMailer)
    // This is a placeholder - you need to implement this using your preferred email library.

    // Example:
    // $mail = new PHPMailer();
    // $mail->addAddress($email);
    // $mail->Subject = "Password Reset";
    // $mail->Body = "Click this link to reset your password: " . $reset_link;
    // $mail->send();

    // Placeholder:  Simulate sending
    echo "Simulated sending email to: " . $email . " with link: " . $reset_link;
    return true;
}

// Example Usage (Illustrative -  Replace with your actual user data)
$email = "test@example.com";
if (forgot_password($email)) {
    echo "Password reset email sent to " . $email . "
";
} else {
    echo "Failed to send password reset email to " . $email . "
";
}

?>


<?php

// Configuration - Replace with your database credentials and email settings
$db_host = 'localhost';
$db_name = 'your_database_name';
$db_user = 'your_db_user';
$db_pass = 'your_db_password';
$from_email = 'your_email@example.com';
$from_name = 'Your Website Name';


// Function to reset password
function forgot_password($email, $new_password) {
    // 1. Connect to the database
    $conn = new mysqli($db_host, $db_user, $db_pass, $db_name);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // 2. Sanitize and Validate Inputs
    $email = trim($email); // Remove leading/trailing whitespace
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return "Invalid email address.";
    }
    $new_password = trim($new_password);
    if (empty($new_password)) {
        return "Password cannot be empty.";
    }

    // 3. Hash the new password (Important for Security)
    $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

    // 4. Update the password in the database
    $sql = "UPDATE users SET password = '$hashed_password' WHERE email = '$email'";

    if ($conn->query($sql) === TRUE) {
        return "Password reset successfully.  Please check your email.";
    } else {
        return "Error resetting password. " . $conn->error;
    }

    // 5. Close the connection
    $conn->close();
}


// Example Usage (This is just for testing, integrate this into your form)
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $new_password = $_POST['new_password'];

    $reset_message = forgot_password($email, $new_password);
    echo $reset_message; // Display the result (e.g., error message or success)
} else {
    // If not a POST request, show a form for the user to enter their email and a new password.
    ?>
    <form method="post" action="">
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required><br><br>

        <label for="new_password">New Password:</label>
        <input type="password" id="new_password" name="new_password" required><br><br>

        <button type="submit">Reset Password</button>
    </form>
    <?php
}
?>


<?php

// Assuming you have a database connection established
// $db = new mysqli($host, $username, $password, $database);
// if ($db->connect_error) {
//     die("Connection failed: " . $db->connect_error);
// }

// Function to handle the forgot password process
function forgotPassword($email, $newPassword, $db) {
    // 1. Validate Email (Optional but recommended)
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return "Invalid email format.";
    }

    // 2. Check if the email exists in the database
    $stmt = $db->prepare("SELECT id, email FROM users WHERE email = ?");
    $email_to_check = $email;
    $stmt->bind_param("s", $email_to_check); // 's' indicates a string parameter
    $stmt->execute();

    $result = $stmt->get_result();

    if ($result->num_rows == 0) {
        $stmt->close();
        return "User not found.";
    }

    $user = $result->fetch_assoc();
    $stmt->close();

    // 3. Generate a unique token or use a hashed password reset token
    $token = bin2hex(random_bytes(32)); // Generate a random, secure token.  Consider a more robust method if security is paramount.

    // 4. Hash the token for security
    $hashed_token = password_hash($token, PASSWORD_DEFAULT);

    // 5. Update the user's record with the hashed token
    $stmt = $db->prepare("UPDATE users SET password_reset_token = ? WHERE email = ?");
    $stmt->bind_param("ss", $hashed_token, $email);
    $stmt->execute();

    if (!$stmt->affected_rows > 0) {
        $stmt->close();
        return "Failed to update password reset token.";
    }

    $stmt->close();

    // 6. Send an email with the reset link
    $subject = "Password Reset Link";
    $message = "Please click on the following link to reset your password: " . "<a href='" . $_SERVER['PHP_SELF'] . "?reset=" . urlencode($token) . "'>Reset Password</a>"; // Include the reset token in the URL
    $headers = "From: your_email@example.com";  // Replace with your email address

    mail($email, $subject, $message, $headers);

    // 7. Return a success message
    return "Password reset link sent to your email.";
}

// Example Usage (Simulated for demonstration)
//  This would typically be handled by a form submission.
//  For demonstration, let's simulate getting email and new password.
//  In a real application, you'd get this data from a form.
$email = "test@example.com"; // Replace with the user's email
$newPassword = "P@sswOrd123";  // Replace with the desired new password

// Simulate the database connection
// For demonstration, we create a mock database object
class MockDB {
    public function prepare($query) {
        // In a real application, this would use a prepared statement
        // For demonstration, we'll just return a dummy result
        return null;
    }

    public function bind_param($type, $value) {
        // Do nothing for demonstration
    }

    public function execute() {
        // Dummy result for demonstration
        return array(
            'num_rows' => 1, // Assume user exists
            'fetch_assoc' => function() {
                return array(
                    'id' => 1,
                    'email' => 'test@example.com'
                );
            }
        );
    }

    public function affected_rows() {
        return 1;
    }

    public function close() {
        // Do nothing for demonstration
    }
}
$db = new MockDB();


$result = forgotPassword($email, $newPassword, $db);
echo $result;

?>


<?php

// Assuming you have a database connection established (e.g., $db connection)

/**
 * Forgets a user's password.
 *
 * @param string $email The email address of the user requesting a password reset.
 * @return bool True on success, false on failure.
 */
function forgotPassword(string $email): bool
{
    // 1. Validate Input
    if (empty($email)) {
        error_log("Forgot Password: Empty email provided."); // Log for debugging
        return false;
    }

    // Sanitize the email address to prevent SQL injection
    $email = filter_var($email, FILTER_SANITIZE_EMAIL);

    if (filter_var($email, FILTER_VALIDATE_EMAIL) === false) {
        error_log("Forgot Password: Invalid email format provided.");
        return false;
    }

    // 2. Check if the user exists
    $user = getUserByEmail($email);  // Implement this function (see below)

    if ($user === false) {
        error_log("Forgot Password: User not found with email: " . $email);
        return false;
    }

    // 3. Generate a unique token
    $token = generateUniqueToken();

    // 4. Store the token in the database associated with the user
    //  (This is the crucial part – adapt to your database schema)
    $result = storeToken($user['id'], $token); // Implement this function (see below)

    if (!$result) {
        error_log("Forgot Password: Failed to store token for user: " . $email);
        return false;
    }


    // 5. Send the password reset email
    //  (Implement this function – sendmail, etc.)
    sendPasswordResetEmail($user['email'], $token);


    return true;
}



/**
 * Helper function to get a user by their email.  This is a placeholder.
 *  You must implement this function based on your database structure.
 *
 * @param string $email The email address of the user.
 * @return bool|array The user object if found, false if not found.
 */
function getUserByEmail(string $email): bool|array
{
    // Replace this with your database query.  This is just a dummy example.
    // Assuming a table named 'users' with columns 'id', 'email', 'password', etc.
    $db = new PDO("mysql:host=localhost;dbname=your_database_name", "your_username", "your_password");
    $stmt = $db->prepare("SELECT * FROM users WHERE email = :email");
    $stmt->bindParam(':email', $email);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        $stmt->fetch(PDO::FETCH_ASSOC);  // Get the first row as an associative array
        return $stmt->fetch(PDO::FETCH_ASSOC);
    } else {
        return false;
    }
}


/**
 * Helper function to generate a unique token.
 *  This could use a library or a simple random string generator.
 *
 * @return string A unique token.
 */
function generateUniqueToken(): string
{
    return bin2hex(random_bytes(32)); // Example - generates a 32-byte (256-bit) random string.
}



/**
 * Helper function to store the token in the database.
 *
 * @param int $userId The ID of the user.
 * @param string $token The unique token.
 * @return bool True on success, false on failure.
 */
function storeToken(int $userId, string $token): bool
{
    // Replace with your database code to store the token.
    // Example (using PDO):

    $db = new PDO("mysql:host=localhost;dbname=your_database_name", "your_username", "your_password");
    $stmt = $db->prepare("INSERT INTO user_tokens (user_id, token, expires_at) VALUES (:user_id, :token, :expires_at)");
    $expires_at = date('Y-m-d H:i:s', time() + 3600); // Token expires in 1 hour
    $stmt->bindParam(':user_id', $userId);
    $stmt->bindParam(':token', $token);
    $stmt->bindParam(':expires_at', $expires_at);
    return $stmt->execute();
}



/**
 * Helper function to send the password reset email.
 *
 * @param string $email The email address of the user.
 * @param string $token The unique token.
 * @return void
 */
function sendPasswordResetEmail(string $email, string $token)
{
    //  Replace with your email sending logic (e.g., sendmail, SMTP, etc.)
    $subject = "Password Reset Request";
    $message = "Please click on the following link to reset your password: " . "<a href='" . base_url() . "/reset_password?token=" . $token . "'>Reset Password</a>";

    // Example using mail() - may need to configure your server properly
    mail($email, $subject, $message);
}

// Example Usage (For testing - replace with your actual logic)
// if (isset($_POST['email'])) {
//     $email = $_POST['email'];
//     if (forgotPassword($email)) {
//         echo "Password reset email has been sent to " . $email;
//     } else {
//         echo "Failed to process password reset request.";
//     }
// }

?>


<?php

/**
 * Forgot Password Function
 * 
 * This function generates a temporary password and sends it to the user's email.
 * 
 * @param string $email      The email address of the user.
 * @param string $resetLinkPrefix The prefix for the reset link (e.g., "https://example.com/reset-password/")
 * @param string $tokenLifetimeSeconds  The amount of time (in seconds) the reset token is valid.
 * @param string $fromEmail   The email address to send the reset password link from.
 * 
 * @return bool True on success, false on failure.
 */
function forgotPassword(string $email, string $resetLinkPrefix, int $tokenLifetimeSeconds, string $fromEmail)
{
    // 1. Validate Email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        error_log("Invalid email address provided: " . $email); // Log the error for debugging
        return false;
    }

    // 2. Generate a Unique Token
    $token = bin2hex(random_bytes(32)); // Generate a strong, random token

    // 3. Hash the Token (For Security)
    $hashedToken = password_hash($token, PASSWORD_DEFAULT);

    // 4. Store the Token in the Database (Replace with your database logic)
    // IMPORTANT: This is a placeholder. You MUST implement this logic
    // using your database connection and table structure.
    $user_id = getUserIDFromEmail($email); // Function to get user ID from email - replace with your implementation
    if ($user_id === null) {
        error_log("User not found for email: " . $email);
        return false;
    }

    // Store the token and user ID.  Replace this with your DB query.
    // In a real application, you would likely use prepared statements 
    // to prevent SQL injection vulnerabilities.
    $success = storeToken($user_id, $token);  // Function to store the token - replace with your implementation
    if (!$success) {
        error_log("Failed to store token for user: " . $email);
        return false;
    }

    // 5. Create the Reset Link
    $resetLink = $resetLinkPrefix . "?" . http_build_query(['token' => $token]);

    // 6. Send the Email
    $subject = "Password Reset Request";
    $message = "To reset your password, please click on the following link: " . $resetLink;
    $headers = "From: " . $fromEmail . "\r
";
    $headers .= "Reply-To: " . $fromEmail . "\r
";

    if (mail($email, $subject, $message, $headers)) {
        return true;
    } else {
        error_log("Failed to send email to: " . $email);
        // Consider removing the token if the email fails to send.
        // This prevents it from being used indefinitely if email delivery is unreliable.
        // removeToken($user_id, $token); // Implement this function
        return false;
    }
}

/**
 * Placeholder functions - Replace with your own implementations
 * These are placeholders for database interaction and token removal.
 */

/**
 * Placeholder function to get user ID from email.  Replace with your database query.
 * @param string $email
 * @return int|null
 */
function getUserIDFromEmail(string $email): ?int
{
    // Replace this with your actual database query to get the user ID.
    // Example:
    // $result = mysqli_query($conn, "SELECT id FROM users WHERE email = '$email'");
    // if (mysqli_num_rows($result) > 0) {
    //   return mysqli_fetch_assoc($result)['id'];
    // }
    // return null;
    return null; // Placeholder
}

/**
 * Placeholder function to store the token in the database.
 * Replace with your database query.
 * @param int $userId
 * @param string $token
 */
function storeToken(int $userId, string $token): bool
{
    // Replace this with your actual database query.
    // Example:
    // mysqli_query($conn, "INSERT INTO reset_tokens (user_id, token, expires_at) VALUES ($userId, '$token', NOW() + INTERVAL 3600 SECOND)");  // Expires in 1 hour
    return true; // Placeholder
}


/**
 * Placeholder function to remove the token from the database.
 * Replace with your database query.
 * @param int $userId
 * @param string $token
 */
function removeToken(int $userId, string $token): bool
{
    // Replace this with your actual database query.
    // Example:
    // mysqli_query($conn, "DELETE FROM reset_tokens WHERE user_id = $userId AND token = '$token'");
    return true; // Placeholder
}

// Example Usage (Replace with your actual email, prefix, and from email)
// $email = "test@example.com";
// $resetLinkPrefix = "https://yourwebsite.com/reset-password/";
// $tokenLifetimeSeconds = 7200; // 2 hours
// $fromEmail = "noreply@yourwebsite.com";
//
// if (forgotPassword($email, $resetLinkPrefix, $tokenLifetimeSeconds, $fromEmail)) {
//     echo "Password reset email sent!";
// } else {
//     echo "Password reset failed.";
// }

?>


<?php

// Database connection details - Replace with your actual credentials
$host = 'localhost';
$username = 'your_username';
$password = 'your_password';
$database = 'your_database';

//  Reset Password Function
function reset_password($email, $new_password) {
  // 1. Validate Email
  $email = filter_var($email, FILTER_SANITIZE_EMAIL); // Sanitize email for security
  if (empty($email)) {
    return false; // Invalid email
  }

  // 2. Database Connection
  try {
    $pdo = new PDO("mysql:host=$host;dbname=$database", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERR_EXCEPTION);
  } catch (PDOException $e) {
    error_log("Database connection error: " . $e->getMessage()); // Log error for debugging
    return false;
  }


  // 3.  Check if User Exists
  $stmt = $pdo->prepare("SELECT id, email FROM users WHERE email = :email");
  $stmt->bindParam(':email', $email, PDO::PARAM_STR);
  $stmt->execute();

  $user = $stmt->fetch(PDO::FETCH_ASSOC);

  if (empty($user)) {
    // User not found
    return false;
  }

  // 4.  Hash the New Password (Important for Security!)
  $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

  // 5. Update the Password
  $stmt = $pdo->prepare("UPDATE users SET password = :password WHERE id = :id");
  $stmt->bindParam(':password', $hashed_password, PDO::PARAM_STR);
  $stmt->bindParam(':id', $user['id'], PDO::PARAM_INT);
  $stmt->execute();

  if ($stmt->rowCount() === 0) {
    // Update failed
    return false;
  }

  return true; // Password reset successful
}



// Example Usage (Demonstration - Don't use directly in production without validation and sanitization)
//  This is for demonstration purposes only - NEVER expose this directly to the user.
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['email']) && isset($_POST['new_password'])) {
  $email = $_POST['email'];
  $new_password = $_POST['new_password'];

  if (reset_password($email, $new_password)) {
    echo "<p style='color: green;'>Password reset successfully! Check your email.</p>";
  } else {
    echo "<p style='color: red;'>Password reset failed. Please try again.</p>";
  }
}

?>

<!-- HTML Form for the Reset Password Request -->
<form method="post" action="">
  <label for="email">Email:</label>
  <input type="email" id="email" name="email" required><br><br>
  <button type="submit">Reset Password</button>
</form>


<?php

// Assuming you have a database connection established (e.g., $db connection)

/**
 * Generates a unique token and saves it to the database.
 *
 * @param string $email The email address to reset the password for.
 * @return string|false The unique token if generated successfully, or false if an error occurs.
 */
function generateResetToken() {
  $token = bin2hex(random_bytes(32)); // Generates a secure, random 32-byte token
  return $token;
}

/**
 * Creates a password reset link.
 *
 * @param string $email The email address to reset the password for.
 * @return string|false The generated password reset link if successful, or false if an error occurs.
 */
function createResetLink(string $email) {
    $token = generateResetToken();

    // Example:  You might save the token to the database, along with the email.
    // This is just a placeholder - you'll need to implement your database saving logic.
    // For demonstration, we'll just return the token string.
    //  Don't just return the token, you *must* store it securely!
    return $token;
}


/**
 * Resets the user's password based on the token.
 *
 * @param string $token The password reset token.
 * @param string $newPassword The new password for the user.
 * @param string $email The user's email address.
 * @return bool True if the password was successfully reset, false otherwise.
 */
function resetPassword(string $token, string $newPassword, string $email) {
    // 1. Verify the Token
    $query = "SELECT id, email FROM users WHERE email = ?";
    $stmt = $db->prepare($query); // Use your database connection
    $stmt->bind_param("s", $email);
    $stmt->execute();

    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        $stmt->close();
        return false; // User not found
    }

    $user = $result->fetch_assoc();
    $userId = $user['id'];

    // 2. Check if the token exists for this user
    $query = "SELECT id FROM reset_tokens WHERE token = ? AND user_id = ?";
    $stmt = $db->prepare($query);
    $stmt->bind_param("ss", $token, $userId);
    $stmt->execute();

    if ($stmt->error) {
        error_log("Database error: " . $stmt->error); // Log the error for debugging.
        $stmt->close();
        return false;
    }

    if ($stmt->error) {
        $stmt->close();
        return false;
    }

    if ($stmt->affected_rows === 0) {
        $stmt->close();
        return false; // Token not found for this user
    }
    
    // 3. Update the user's password
    $query = "UPDATE users SET password = ? WHERE id = ?";
    $stmt = $db->prepare($query);
    $stmt->bind_param("ss", password_hash($newPassword, PASSWORD_DEFAULT), $userId);
    $stmt->execute();
    $stmt->close();


    // 4. Delete the token from the database after successful password reset.
    $query = "DELETE FROM reset_tokens WHERE token = ? AND user_id = ?";
    $stmt = $db->prepare($query);
    $stmt->bind_param("ss", $token, $userId);
    $stmt->execute();
    $stmt->close();

    return true;
}


// Example Usage (illustrative only - adapt to your application)
/*
$email = "testuser@example.com";
$newPassword = "NewSecurePassword123";


// Generate a reset link
$resetLink = createResetLink($email);
echo "Reset Link: " . $resetLink . "<br>";


// Reset the password (assuming you've received the $resetLink from a form submission)
if (resetPassword($resetLink, $newPassword, $email)) {
    echo "Password reset successfully!";
} else {
    echo "Password reset failed.";
}
*/


?>


<?php

/**
 * Forgot Password Function
 *
 * This function generates a random token and stores it in the database
 * associated with the user's email address. It then sends an email to
 * the user with a link to reset their password.
 *
 * @param string $email The email address to send the reset password link to.
 * @param string $token  A random, unique token to protect the password reset link.
 * @param string $secret_key  The secret key used to encrypt the token.
 * @param string $reset_url The URL where the reset password page is located.
 * @return bool True on success, false on failure.
 */
function forgot_password(string $email, string $token, string $secret_key, string $reset_url) {
    // 1. Validate Email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        error_log("Invalid email address provided."); // Log for debugging - important!
        return false;
    }

    // 2. Store Token in Database (Example using a simple array - REPLACE with your database logic)
    //  **IMPORTANT:**  This is just an example.  You *must* replace this with your actual
    //  database storage mechanism (e.g., SQL insert).
    $tokens = get_stored_tokens(); // Function to retrieve stored tokens from the database.  Replace with your retrieval logic.
    $tokens[$email] = $token; // Store the token
    set_stored_tokens($tokens); //Function to store the tokens in the database

    // 3. Generate Reset Link
    $reset_link = $reset_url . "?token=" . urlencode($token);

    // 4. Send Password Reset Email
    $to = $email;
    $subject = "Password Reset";
    $message = "To reset your password, please click on the following link: " . $reset_link;
    $headers = "From: Your Website <admin@yourwebsite.com>"; // Replace with your email address

    if (mail($to, $message, $headers)) {
        return true;
    } else {
        error_log("Failed to send password reset email."); // Log failure
        return false;
    }
}

/**
 *  Placeholder functions for database interaction.  **Replace with your actual database code.**
 */

function get_stored_tokens() {
  // Replace this with your code to retrieve tokens from the database
  // Example:
  // return [
  //   'user1@example.com' => 'random_token_1',
  //   'user2@example.com' => 'random_token_2'
  // ];
  return []; // Return an empty array for now
}

function set_stored_tokens($tokens) {
  // Replace this with your code to store tokens in the database.
  // Example:
  //  //  $db = new DatabaseConnection();
  //  //  $sql = "DELETE FROM reset_tokens"; //Clear the table
  //  //  $db->query($sql);

  //  foreach ($tokens as $email => $token) {
  //    $sql = "INSERT INTO reset_tokens (email, token) VALUES ('" . $email . "', '" . $token . "')";
  //    $db->query($sql);
  //  }
}


// Example Usage (FOR TESTING - DO NOT USE IN PRODUCTION WITHOUT SECURITY MEASURES)
//  $email = "testuser@example.com";
//  $token = "random_unique_token_string_123";
//  $secret_key = "YourSecretKeyHere";
//  $reset_url = "http://localhost:8000/reset_password"; //  Adjust to your URL

//  if (forgot_password($email, $token, $secret_key, $reset_url)) {
//      echo "Password reset email sent successfully!";
//  } else {
//      echo "Failed to send password reset email.";
//  }

?>


<?php

// Assuming you have a database connection established (e.g., $db)

/**
 * Forgets a user's password.
 *
 * @param string $email The user's email address.
 * @return bool True if a password reset link has been sent, false otherwise.
 */
function forgotPassword(string $email) {
  // 1. Validate Email (Basic) -  Expand this for more robust validation if needed.
  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    error_log("Invalid email address provided: " . $email); // Log the invalid email
    return false;
  }

  // 2. Check if the user exists.  This is crucial.
  $user = getUserByEmail($email); // Assume you have a function to fetch the user
  if (!$user) {
    error_log("User with email " . $email . " not found.");
    return false;
  }

  // 3. Generate a Unique Token (Important for security)
  $token = generateUniqueToken();

  // 4. Store the Token and User ID in the Database
  $result = storeTokenForUser($user->id, $token); // Assume you have a function for this
  if (!$result) {
    error_log("Failed to store token for user " . $email);
    return false;
  }

  // 5. Send the Password Reset Email
  $subject = "Password Reset";
  $message = "To reset your password, please click on the following link: " .  base_url() . "/reset-password?token=" . $token;  // Use your base URL
  $headers = "From: " . get_option('admin_email') . "\r
"; //Replace with your email
  $result = sendEmail($email, $subject, $message, $headers);
  if (!$result) {
    error_log("Failed to send email for password reset to " . $email);
    // Optionally, you might try deleting the token if email sending fails.
    // deleteTokenForUser($user->id, $token);
    return false;
  }

  return true;
}


/**
 *  Helper function to get a user by email.  Replace with your actual implementation.
 * @param string $email
 * @return object|null User object or null if not found.
 */
function getUserByEmail(string $email) {
    // Replace this with your database query logic
    // This is a placeholder example.
    $users = [
        ['id' => 1, 'email' => 'test@example.com', 'password' => 'password123'],
        ['id' => 2, 'email' => 'another@example.com', 'password' => 'secret']
    ];

    foreach ($users as $user) {
        if ($user['email'] === $email) {
            return new stdClass(); // Create a new object for the user
        }
    }

    return null;
}


/**
 * Generate a unique token.  Consider using a library for cryptographically secure random strings.
 * @return string
 */
function generateUniqueToken() {
    return bin2hex(random_bytes(32)); // More secure than mt_rand
}

/**
 *  Placeholder function to store the token in the database.
 * @param int $userId
 * @param string $token
 * @return bool
 */
function storeTokenForUser(int $userId, string $token) {
    // Your database logic here.
    // For example:
    // $query = "INSERT INTO password_tokens (user_id, token, expires_at) VALUES ($userId, '$token', NOW() + INTERVAL 7 DAY)";
    // executeQuery($query);
    return true;
}


/**
 *  Placeholder function to send an email.
 * @param string $to
 * @param string $subject
 * @param string $message
 * @param string $headers
 * @return bool
 */
function sendEmail(string $to, string $subject, string $message, string $headers) {
    // Replace this with your email sending logic (e.g., using PHPMailer or similar).
    // This is a placeholder for demonstration purposes.

    // Simulate successful sending
    error_log("Simulated sending email to: " . $to . " with subject: " . $subject);
    return true;
}

/**
 *  Placeholder function to delete the token.
 * @param int $userId
 * @param string $token
 * @return bool
 */
function deleteTokenForUser(int $userId, string $token) {
    //Your database logic here to delete the token record.
    return true;
}

/**
 *  Returns the base url of your website.  Useful for generating reset links.
 * @return string
 */
function base_url() {
    // Replace this with your actual base URL.
    return "http://localhost/your-website";
}


// Example Usage:
$email = "test@example.com";

if (forgotPassword($email)) {
    echo "Password reset email has been sent to " . $email . ".  Check your inbox.";
} else {
    echo "Failed to initiate password reset for " . $email;
}

?>


<?php

// Database connection details (Replace with your actual values)
$host = 'localhost';
$username = 'your_username';
$password = 'your_password';
$database = 'your_database_name';

// Function to handle password reset requests
function forgot_password($email) {
    // 1. Validate email format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return "Invalid email address.";
    }

    // 2. Fetch user data from the database
    $pdo = new PDO("mysql:host=$host;dbname=$database", $username, $password);
    $stmt = $pdo->prepare("SELECT id, password, email FROM users WHERE email = :email");
    $stmt->bindParam(':email', $email);
    $stmt->execute();

    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    // 3. Check if the user exists
    if ($user) {
        // 4. Generate a unique token
        $token = bin2hex(random_bytes(32)); // Generates a cryptographically secure random token
        // 5.  Store the token and user ID in the database
        try {
            $pdo->prepare("UPDATE users SET password_reset_token = :token, password_reset_token_expiry = :expiry WHERE email = :email")
                  ->bindParam(':token', $token)
                  ->bindParam(':expiry', date('Y-m-d H:i:s', time() + 3600)) // Expires after 1 hour
                  ->bindParam(':email', $email)
                  ->execute();
            return $token; // Return the token to the user
        } catch (PDOException $e) {
            // Handle database errors (e.g., duplicate token)
            error_log("Error resetting password: " . $e->getMessage()); // Log the error for debugging
            return "An error occurred while generating the reset token. Please try again.";
        }

    } else {
        return "User not found.";
    }
}


// Example Usage (for demonstration purposes - DON'T use this in a real web application directly)
// $email = 'test@example.com';
// $resetToken = forgot_password($email);

// if ($resetToken == "Invalid email address.") {
//     echo $resetToken; // Display the error message
// } elseif ($resetToken == "User not found.") {
//     echo $resetToken;
// } else {
//     echo "Password reset link sent to: " . $resetToken;
// }
?>


<?php

// Assuming you have a database connection established
// This is a simplified example, adapt to your database structure
// and security practices.

// Configuration (Change these to your actual values)
$db_host = 'localhost';
$db_name = 'your_database_name';
$db_user = 'your_database_user';
$db_password = 'your_database_password';

// Function to handle the forgot password process
function forgot_password($email) {
  // 1. Validate email format (basic check)
  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    return "Invalid email address.";
  }

  // 2. Check if the email exists in the user table
  $conn = new mysqli($db_host, $db_user, $db_password, $db_name);
  if ($conn->connect_error) {
    return "Database connection failed: " . $conn->connect_error;
  }

  $query = "SELECT id, password, email FROM users WHERE email = '$email'";
  $result = $conn->query($query);

  if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
    $userId = $user['id'];
    $hashedPassword = $user['password']; //  Important:  Store hashed passwords
    // 3. Generate a unique token
    $token = bin2hex(random_bytes(32)); // Generate a secure random token
    // 4. Update the user record with the token (add to password column or create a separate 'tokens' table)
    $update_query = "UPDATE users SET token = '$token' WHERE id = '$userId'";
    if (!$conn->query($update_query)) {
      return "Error updating user data.";
    }

    // 5. Send the password reset email
    $to = $email;
    $subject = "Password Reset";
    $message = "Click on the following link to reset your password: " . $_SERVER['REQUEST_SCHEME'] . "://yourdomain.com/reset_password.php?token=$token"; // Use HTTPS if possible
    $headers = "From: your_email@example.com";

    mail($to, $message, $headers);

    return "Password reset link sent to your email.  Check your inbox!";
  } else {
    return "User with this email address not found.";
  }

  $conn->close();
}


// Example Usage (for testing -  This will not work directly without a form)
//  This demonstrates how you would call the function.
/*
$email = "test@example.com"; // Replace with the user's email
$resetMessage = forgot_password($email);
echo $resetMessage;
*/


// **IMPORTANT SECURITY NOTES & BEST PRACTICES**

// 1. **Hashing Passwords:**  NEVER store passwords in plain text.  Always hash them using a strong hashing algorithm like bcrypt or Argon2.  The example uses `$user['password']`, which represents the *hashed* password.

// 2. **Token Expiration:** Implement an expiration time for the password reset token.  This prevents attackers from using the token after it has expired. You can store the expiration time in the database (e.g., a 'token_expiry' column).

// 3. **Secure Token Generation:** Use `random_bytes()` to generate cryptographically secure random tokens. `bin2hex()` converts the bytes into a hexadecimal string, making it suitable for URL parameters.

// 4. **HTTPS:** ALWAYS use HTTPS to protect the password reset link and the user's email address.

// 5. **Rate Limiting:** Implement rate limiting to prevent brute-force attacks.

// 6. **Input Validation:** Validate all user inputs (email format, token, etc.).

// 7. **Error Handling:** Provide informative error messages to the user.

// 8. **Security Audits:** Regularly review your code for security vulnerabilities.

// 9. **Separate Tables (Recommended):** For improved security and organization, consider using separate tables for users and tokens. This isolates the tokens, making it harder for attackers to compromise the password reset process.

// 10. **Email Verification:** Send a verification email to the user to confirm they received the reset link.

// 11. **Don't Reveal Sensitive Information in Error Messages:**  Avoid revealing database details or other sensitive information in error messages that might be exposed to users.


<?php

// Database connection details (Replace with your actual details)
$host = "localhost";
$username = "your_username";
$password = "your_password";
$dbname = "your_database_name";

// Function to handle the forgot password process
function forgotPassword($email) {
    // 1. Validate Email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return "Invalid email address.";
    }

    // 2. Check if the user exists
    $conn = new mysqli($host, $username, $password, $dbname);

    if ($conn->connect_error) {
        return "Connection failed: " . $conn->connect_error;
    }

    $sql = "SELECT id, password FROM users WHERE email = '$email'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        $userId = $user['id'];
        $hashedPassword = $user['password']; //Retrieve the hashed password.

        // 3. Generate a unique token
        $token = bin2hex(random_bytes(32)); // Generate a secure random token
        
        // 4. Update the user's record with the token
        $sql = "UPDATE users SET reset_token = '$token' WHERE id = '$userId'";
        if ($conn->query($sql) === TRUE) {
            // 5. Send an email with the token and a link
            $emailTo = $email;
            $subject = "Password Reset Link";
            $message = "Click on the following link to reset your password: " . "<a href='" . $_SERVER['PHP_SELF'] . "?reset=" . urlencode($token) . "'>Reset Password</a>";
            $headers = "From: your_email@example.com" . "\r
";

            if (mail($emailTo, $subject, $message, $headers)) {
                return "Password reset link sent to your email.  Please check your inbox.";
            } else {
                return "Failed to send password reset email.";
            }
        } else {
            return "Error updating user record.";
        }
    } else {
        return "User not found.";
    }

    $conn->close();
}


// Example usage (this is just for demonstration, don't use this directly in a form)
// $email = $_POST['email']; // Get email from form
// $resetMessage = forgotPassword($email);
// echo $resetMessage;

?>

<!DOCTYPE html>
<html>
<head>
    <title>Forgot Password</title>
</head>
<body>

    <h1>Forgot Password</h1>

    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required>
        <button type="submit">Reset Password</button>
    </form>

</body>
</html>


// Example:  Using password_hash()
$password = $_POST['password'];  // Get the user's new password
$hashedPassword = password_hash($password, PASSWORD_DEFAULT);

// Then, in your query:
$sql = "UPDATE users SET password = '$hashedPassword' WHERE id = '$userId'";


<?php

// Replace with your actual database connection details
$db_host = "localhost";
$db_name = "your_database_name";
$db_user = "your_database_username";
$db_password = "your_database_password";

/**
 * Resets a user's password.
 *
 * @param string $email The user's email address.
 * @return bool True if the password reset was initiated, false otherwise.
 */
function forgot_password(string $email) {
  // 1. Check if the email exists
  $conn = new mysqli($db_host, $db_user, $db_password, $db_name);

  if ($conn->connect_error) {
    error_log("Database connection failed: " . $conn->connect_error);
    return false;
  }

  $query = "SELECT id, email FROM users WHERE email = ?";
  $stmt = $conn->prepare($query);

  if ($stmt === false) {
    error_log("Prepare statement failed: " . $conn->error);
    return false;
  }

  $email_to_check = $email;  // Pass the email to the prepared statement
  $stmt->bind_param("s", $email_to_check);

  if (!$stmt->execute()) {
    error_log("Execute statement failed: " . $stmt->error);
    return false;
  }

  $result = $stmt->get_result();

  if ($result->num_rows == 0) {
    // Email doesn't exist
    $stmt->close();
    return false;
  }

  $user = $result->fetch_assoc();
  $userId = $user['id'];
  $stmt->close();

  // 2. Generate a unique reset token
  $reset_token = bin2hex(random_bytes(32)); // Use a secure random string

  // 3. Store the token in the database
  $conn->query("UPDATE users SET reset_token = ? WHERE id = ?", $reset_token, $userId);

  // 4.  Send the reset link (implementation is omitted for brevity)
  // This would typically involve sending an email with a link containing the token.

  // You would then have a link like:  https://yourwebsite.com/reset_password.php?token=$reset_token

  return true;
}

// Example usage (for testing - remove for production)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $email = $_POST["email"];

  if (isset($email) && !empty($email)) {
    $reset_success = forgot_password($email);

    if ($reset_success) {
      echo "<p>Password reset link has been sent to your email address.</p>";
      echo "<p>Please check your inbox.</p>";
    } else {
      echo "<p>An error occurred while attempting to reset your password.</p>";
      echo "<p>Please check your email address and try again.</p>";
    }
  } else {
    echo "<p>Please enter your email address.</p>";
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

  <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
    <label for="email">Email:</label>
    <input type="email" id="email" name="email" required>
    <button type="submit">Reset Password</button>
  </form>

</body>
</html>


<?php

// Assuming you have a database connection established (e.g., $db)
//  and a 'users' table with 'email' and 'password' columns.
//  This is a simplified example; in a real-world scenario, you'd
//  add more security measures.

function forgot_password($email, $db) {
  // 1. Validate Email
  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    return "Invalid email address.";
  }

  // 2. Check if user exists
  $result = $db->query("SELECT id, password FROM users WHERE email = '$email'");

  if ($result->num_rows === 0) {
    return "User not found.";
  }

  $user = $result->fetch_assoc();
  $userId = $user['id'];
  $hashedPassword = $user['password']; //This should be hashed in your database!

  // 3. Generate a temporary password
  $tempPassword = generate_temp_password();

  // 4. Update the database with the temporary password
  $db->query("UPDATE users SET password = '$tempPassword' WHERE id = '$userId'");

  // 5.  Store the temporary password in a temporary table or session 
  //     (This is crucial - don't just log it in the user's database).
  //     Example using a temporary table:
  $db->query("INSERT INTO password_resets (user_id, reset_token, expires_at)
            VALUES ($userId, '$tempPassword', NOW() + INTERVAL 1 HOUR)");


  // 6. Return a reset link (email the user with this link)
  return "<a href='reset_password.php?token=$tempPassword'>Click here to reset your password</a>";

  // Or, you can return the token directly if you are handling the reset in the same page.
  //return $tempPassword;
}

//Helper function to generate a temporary password
function generate_temp_password() {
  $length = 12; // Adjust as needed
  $characters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
  $password = '';
  $character_length = strlen($characters);
  for ($i = 0; $i < $length; $i++) {
    $random_number = rand(0, $character_length - 1);
    $password .= substr($characters, $random_number);
  }
  return $password;
}



//Example Usage (Illustrative - needs actual database setup)
// $email = "test@example.com";
// $resetLink = forgot_password($email);
// echo $resetLink;

?>


<?php

// Database connection details - Replace with your actual values
$host = "localhost";
$username = "your_username";
$password = "your_password";
$dbname = "your_database_name";

// Function to handle password reset
function forgotPassword($email) {
    // 1. Generate a unique, secure token
    $token = bin2hex(random_bytes(32));

    // 2. Create a temporary password reset link
    $resetLink = "/reset_password.php?token=" . $token . "&email=" . urlencode($email);

    // 3.  Prepare the SQL query
    $query = "INSERT INTO password_resets (email, token, expires) VALUES ('" . $email . "', '" . $token . "', NOW() + INTERVAL 1 HOUR)"; 

    // 4.  Database connection
    $conn = new mysqli($host, $username, $password, $dbname);

    // 5. Check for connection errors
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // 6. Execute the query
    if ($conn->query($query) === TRUE) {
        // 7. Send email (Implementation needed - See below for an example)
        sendResetPasswordEmail($email, $resetLink);
        echo "Password reset email sent to " . $email;
    } else {
        echo "Error creating password reset link: " . $conn->error;
    }

    // 8. Close the connection
    $conn->close();
}


// Function to send the password reset email (Placeholder - Replace with your actual email sending logic)
function sendResetPasswordEmail($email, $resetLink) {
    // This is a placeholder.  You'll need to replace this with your email sending code.
    // This example just prints the email link to the console.

    // In a real application, you'd use a library like PHPMailer or SwiftMailer
    // to send the email.  Make sure you configure your email settings correctly.

    echo "<br>Password reset link: <a href='" . $resetLink . "'>Click here to reset your password</a>";
}

// Example usage:  (Call this function with the user's email address)
// You'd typically get the email from a form submission.

// Example - For demonstration purposes ONLY.  Do NOT use this in a production environment!
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST["email"];
    if (!empty($email)) {
        forgotPassword($email);
    } else {
        echo "Please enter your email address.";
    }
}


?>


<?php

// Assuming you have a database connection established
// For example:
// $db = new mysqli("localhost", "username", "password", "database_name");
// if (!$db) {
//     die("Connection failed.");
// }

/**
 * Resets a user's password.
 *
 * @param string $email The user's email address.
 * @return bool True on success, false on failure.
 */
function forgotPassword(string $email) {
  // Sanitize the email address (important for security!)
  $email = filter_var($email, FILTER_SANITIZE_EMAIL);

  // Validate email format
  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    return false;
  }

  // 1.  Check if the email exists in the database.
  //     Use a prepared statement to prevent SQL injection!
  $stmt = $db->prepare("SELECT id, password_reset_token FROM users WHERE email = ?");
  $stmt->bind_param("s", $email);
  $stmt->execute();

  // Check if the query was successful
  if ($stmt->errno) {
    error_log("Error executing query: " . $stmt->error);  // Log the error for debugging
    $stmt->close();
    return false;
  }

  $result = $stmt->get_result();

  if ($result->num_rows == 0) {
    $stmt->close();
    return false; // Email not found
  }

  $user = $result->fetch_assoc();
  $stmt->close();

  // 2. Generate a unique, secure, and temporary password reset token.
  $resetToken = bin2hex(random_bytes(32));  // Use a cryptographically secure random number generator

  // 3.  Generate a password reset link with the token.
  $resetLink = "http://yourdomain.com/reset-password?token=" . $resetToken;

  // 4.  Update the user's record with the token.
  $stmt = $db->prepare("UPDATE users SET password_reset_token = ? WHERE email = ?");
  $stmt->bind_param("ss", $resetToken, $email);
  $result = $stmt->execute();

  if ($stmt->errno) {
    error_log("Error updating user: " . $stmt->error);
    $stmt->close();
    return false;
  }

  // 5.  Send an email to the user with the reset link.
  //  (Implement email sending logic here - using PHPMailer or similar)
  $subject = "Password Reset Link";
  $message = "Click the following link to reset your password: " . $resetLink;
  $headers = "From: your_email@example.com\r
";
  // sendmail_path($message, $headers); // Using sendmail_path  (ensure sendmail_path is defined and accessible)
  // Alternative email sending using PHPMailer:
  // require_once 'vendor/PHPMailer/PHPMailer.php';
  // $mail = new PHPMailer(true);
  // $mail->isSMTP();
  // $mail->Host = 'smtp.example.com';
  // $mail->Port = 587;
  // $mail->Username = 'your_email@example.com';
  // $mail->Password = 'your_password';
  // $mail->SetFrom('your_email@example.com', 'Your Application');
  // $mail->Subject = $subject;
  // $mail->Body = $message;
  // $mail->AltBody = $message;  // For non-HTML email clients
  // $mail->send();


  return true; // Success!
}

// Example usage:
$email = "test@example.com"; // Replace with the user's email

if (forgotPassword($email)) {
  echo "Password reset email sent to " . $email . ". Please check their inbox.";
} else {
  echo "Failed to generate password reset link for " . $email . ".";
}

?>


<?php

// Database connection details (replace with your actual details)
$host = 'localhost';
$username = 'your_username';
$password = 'your_password';
$database = 'your_database';

// Function to reset password
function forgotPassword($email, $newPassword) {
  // 1. Connect to the database
  $conn = new mysqli($host, $username, $password, $database);

  // Check connection
  if ($conn->connect_error) {
    return "Connection failed: " . $conn->connect_error;
  }

  // 2.  Securely Hash the New Password
  $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);

  // 3. Prepare and Execute the SQL Query
  $sql = "UPDATE users SET password = '$hashedPassword' WHERE email = '$email'";

  if ($conn->query($sql) === TRUE) {
    return "Password reset successful. Please check your email for further instructions.";
  } else {
    return "Error resetting password: " . $conn->error;
  }

  // 4. Close the connection
  $conn->close();
}

// Example Usage (Demonstration - Don't expose this directly in a public-facing application!)
//  This part demonstrates how to call the function.
//  In a real application, you'd get the email and new password from a form submission.

// Simulate getting data from a form (for demonstration only)
$email = 'test@example.com';  // Replace with a valid email address
$newPassword = 'newSecurePassword123';  // Replace with the desired new password

// Call the function
$result = forgotPassword($email, $newPassword);

// Display the result
echo $result;

?>


<?php

// ... (Database connection details - same as before)

// Function to reset password (using prepared statement)
function forgotPassword($email, $newPassword) {
  // 1. Connect to the database
  $conn = new mysqli($host, $username, $password, $database);

  // Check connection
  if ($conn->connect_error) {
    return "Connection failed: " . $conn->connect_error;
  }

  // 2. Prepare the SQL statement
  $sql = "UPDATE users SET password = ? WHERE email = ?";  // Use placeholders instead of direct variable substitution
  $stmt = $conn->prepare($sql);

  // 3. Bind the parameters
  $stmt->bind_param("s", $newPassword, $email);  // 's' indicates a string

  // 4. Execute the statement
  if ($stmt->execute()) {
    return "Password reset successful. Please check your email for further instructions.";
  } else {
    return "Error resetting password: " . $conn->error;
  }

  // 5. Close the connection
  $conn->close();

  // 6. Close the statement
  $stmt->close();
}


<?php

/**
 * Forgot Password Function
 *
 * This function allows a user to request a password reset.
 * It generates a unique token and stores it in the database
 * along with the user's email.  A reset link is then emailed
 * to the user.
 *
 * @param string $email The email address of the user.
 * @return bool True on success, false on failure.
 */
function forgot_password(string $email)
{
    // 1. Validate Email (Basic validation - you should expand this)
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        error_log("Invalid email address provided: " . $email); // Log for debugging
        return false;
    }

    // 2. Generate a Unique Token
    $token = bin2hex(random_bytes(32));

    // 3. Hash the Token (Important for Security)
    $hashed_token = password_hash($token, PASSWORD_DEFAULT);

    // 4. Store Token and Email in Database
    // Assuming you have a database connection called $db
    // and a table named 'users' with columns 'email' and 'reset_token'
    $sql = "INSERT INTO users (email, reset_token) VALUES ('$email', '$hashed_token')";

    if ($db->query($sql) === false) {
        error_log("Database error: " . $db->error);  //Log database errors
        return false;
    }

    // 5. Send Email with Reset Link
    $subject = "Password Reset Link";
    $message = "Click this link to reset your password: " . $_SERVER['REQUEST_SCHEME'] . "://localhost/reset_password.php?token=$token"; // Construct the full URL
    $headers = "From: your_email@example.com"; // Replace with your email
    
    if (mail($email, $subject, $message, $headers)) {
        return true;
    } else {
        error_log("Failed to send email: " . error_get_last()['message']);
        // Handle email sending failure (e.g., check mail settings, SMTP configuration)
        return false;
    }
}

// Example Usage (Simulated Database - Replace with your actual database connection)
// Assume $db is your database connection object

// $email = "test@example.com";
// if (forgot_password($email)) {
//     echo "Password reset email sent to " . $email;
// } else {
//     echo "Failed to send password reset email.";
// }

?>


<?php

// Assuming you have a database connection established
// (e.g., $db = mysqli_connect("localhost", "username", "password", "database_name");)

// Function to reset password
function forgot_password($email) {
    // 1. Generate a unique, secure token
    $token = bin2hex(random_bytes(32));

    // 2. Store the token and expiration time in the database
    //   (Important:  Always expire tokens after a certain duration.)
    //  This example assumes you have a 'users' table with 'email' and 'password' columns
    //  and a 'reset_tokens' table with 'email' and 'token' columns.
    //  You'll need to adapt this part to your specific database schema.

    // Check if the email already has a reset token
    $stmt = $db->prepare("SELECT id FROM reset_tokens WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Token already exists.  Update the existing token
        $stmt = $db->prepare("UPDATE reset_tokens SET token = ?, expiration = NOW() WHERE email = ? AND token = ?");
        $stmt->bind_param("ss", $token, $email, $token); // token is used as a placeholder to update
        $result = $stmt->execute();

        if ($result) {
            // Success:  Send an email (implementation not included - see below)
            //  You would typically use a mail function or an email library.
            echo "Password reset link generated. Check your email for a reset link.";
        } else {
            // Handle error
            error_log("Error updating reset token: " . $db->error);
            echo "Error updating token. Please try again.";
        }
    } else {
        // 1. Insert a new reset token into the database
        $stmt = $db->prepare("INSERT INTO reset_tokens (email, token, expiration) VALUES (?, ?, NOW())");
        $stmt->bind_param("ss", $email, $token);

        if ($stmt->execute()) {
            // 2. Send an email to the user with the reset link
            $reset_url = "https://yourwebsite.com/reset_password?token=" . $token; // Replace with your actual URL

            //  Implementation for sending the email (simplified example - customize!)
            $subject = "Password Reset Request";
            $message = "Click the link below to reset your password: " . $reset_url;
            $headers = "From: yourwebsite@example.com"; // Replace with your email address

            // Use mail() function (may require configuration)
            if (mail($email, $subject, $message, $headers)) {
                echo "Password reset link generated. Check your email for a reset link.";
            } else {
                // Handle error
                error_log("Error sending email: " . mail($email, $subject, $message, $headers));
                echo "Error sending email. Please check your email settings.";
            }

        } else {
            // Handle error
            error_log("Error inserting reset token: " . $db->error);
            echo "Error generating reset token. Please try again.";
        }
    }

    $stmt->close();
    $result->close();
}

// Example usage (This is just a demonstration, don't use it directly in production)
// Replace 'user@example.com' with the actual email address
// forgot_password('user@example.com');


// **Important Security Considerations and Improvements:**

// 1. **Salt and Hash Passwords:**  Never store passwords in plain text. Always hash them using a strong hashing algorithm (e.g., bcrypt, Argon2) before storing them in the database.  This function assumes you're already doing that for the user's password.

// 2. **Token Expiration:**  Tokens *must* expire after a certain period (e.g., 1 hour, 12 hours). This prevents attackers from using old, potentially compromised tokens.  The `expiration = NOW()` sets the expiration time.

// 3. **Token Uniqueness:** Ensure the generated tokens are truly unique. `random_bytes(32)` provides a high degree of uniqueness.

// 4. **Rate Limiting:** Implement rate limiting to prevent brute-force attacks on the password reset endpoint.  Limit the number of reset requests from the same IP address within a given time frame.

// 5. **Input Validation and Sanitization:** Always validate and sanitize user input (email address, etc.) to prevent SQL injection and other security vulnerabilities.

// 6. **HTTPS:**  Always use HTTPS to encrypt the communication between the user's browser and your server.

// 7. **Error Handling:**  Implement robust error handling and logging to help you debug issues and identify potential security breaches.  Don't display detailed error messages to the user in a production environment.

// 8. **Email Sending Implementation:**  The `mail()` function is often unreliable across different servers and configurations. Consider using a dedicated email library or service (e.g., PHPMailer, SwiftMailer, SendGrid, Mailgun) for more reliable email sending.

// 9. **Security Audits:** Regularly conduct security audits to identify and address potential vulnerabilities.

?>


<?php

// This is a simplified example and should be used with caution.
// In a real-world scenario, you'd want to add:
// - Stronger security measures (e.g., hashing passwords properly)
// - Email validation
// - Input sanitization to prevent vulnerabilities like SQL injection
// - Logging for debugging and security audits
// - Rate limiting to prevent brute-force attacks

// **IMPORTANT:**  Never store passwords in plain text.  Always hash them.
// This example uses a placeholder for hashing, you MUST implement a secure hashing method.

class UserForgotPassword {

    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function resetPassword($email) {
        // 1. Check if the user exists
        $user = $this->db->query("SELECT id, email FROM users WHERE email = ?", [$email]); // Replace 'users' with your table name
        if ($user->rowCount() === 0) {
            return false; // User not found
        }

        // 2. Generate a unique, time-based token
        $token = bin2hex(random_bytes(32)); // Use a strong random number generator

        // 3. Store the token in the database, linked to the user's ID
        $this->db->query("UPDATE users SET reset_token = ? WHERE email = ?", [$token, $email]);


        // 4.  Send an email with a link containing the token.
        $subject = 'Password Reset Link';
        $to = $email;
        $from = 'your_email@example.com'; // Replace with your email
        $message = "Click this link to reset your password: " . $_SERVER['REQUEST_SCHEME'] . "://yourdomain.com/reset-password?token=" . $token; // Replace 'yourdomain.com'
        $headers = "From: " . $from . "\r
";
        $headers .= "Reply-To: " . $from . "\r
";

        mail($to, $message, $headers); // Using PHP's built-in mail function - may require configuration.


        return true;
    }

    public function verifyToken($token) {
        // 1. Check if the token exists in the database
        $user = $this->db->query("SELECT id, email FROM users WHERE reset_token = ?", [$token]);

        if ($user->rowCount() === 0) {
            return false; // Invalid token
        }

        // 2.  If the token exists, delete it from the database to invalidate it.
        $this->db->query("UPDATE users SET reset_token = NULL WHERE reset_token = ?", [$token]);

        return true;
    }

    public function setPasswordForUser($email, $newPassword) {
        // 1.  Securely hash the new password
        $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);

        // 2.  Update the user's password in the database
        $this->db->query("UPDATE users SET password = ? WHERE email = ?", [$hashedPassword, $email]);

        return true;
    }
}



// **Example Usage (illustrative - replace with your actual database connection)**
$db = new PDO("mysql:host=localhost;dbname=your_database", "your_username", "your_password");

// 1. Initiate password reset
$email = "testuser@example.com"; // Replace with the user's email
$reset = new UserForgotPassword($db);
$reset->resetPassword($email);

echo "Password reset link sent to " . $email . "
";


//  Later, when the user clicks the link (in a separate page):
//  1.  Verify the token
//  2.  If the token is valid, set the user's password using the setPasswordForUser method
//  3.  Invalidate the token (delete from the database)
?>


<?php

// This is a simplified example and should be used with caution.
// In a real-world scenario, you should implement robust security measures
// like rate limiting, email verification, and using secure password hashing.

/**
 * Resets a user's password.
 *
 * @param string $email The user's email address.
 * @param string $newPassword The new password.
 * @param string $dbHost     The database host.
 * @param string $dbName     The database name.
 * @param string $dbUser     The database username.
 * @param string $dbPassword The database password.
 *
 * @return bool True if password reset email was sent successfully, false otherwise.
 */
function forgotPassword(string $email, string $newPassword, string $dbHost, string $dbName, string $dbUser, string $dbPassword)
{
  // 1. Database Connection
  try {
    $conn = new PDO("mysql:host=$dbHost;dbname=$dbName", $dbUser, $dbPassword);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  } catch (PDOException $e) {
    error_log("Database connection error: " . $e->getMessage()); // Log the error
    return false;
  }


  // 2. Check if user exists
  try {
    $stmt = $conn->prepare("SELECT id, email FROM users WHERE email = :email");
    $stmt->bindParam(':email', $email, PDO::PARAM_STR);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
      // 3. Generate a unique token
      $token = bin2hex(random_bytes(32)); // Generate a random token

      // 4. Hash the new password
      $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);

      // 5. Update user's password and add token
      $stmt = $conn->prepare("UPDATE users SET password = :password, reset_token = :token, reset_token_expiry = :expiry  WHERE id = :user_id");
      $stmt->bindParam(':password', $hashedPassword, PDO::PARAM_STR);
      $stmt->bindParam(':token', $token, PDO::PARAM_STR);
      $stmt->bindParam(':expiry', date('Y-m-d H:i:s', time() + 3600), PDO::PARAM_STR); // Token expires in 1 hour
      $stmt->bindParam(':user_id', $user['id'], PDO::PARAM_INT);
      $stmt->execute();

      // 6. Send Password Reset Email (Placeholder - Replace with your email sending logic)
      $resetLink = "http://yourdomain.com/reset-password?token=$token";
      $subject = "Password Reset Request";
      $message = "Please reset your password: " . $resetLink;

      // In a real application, you would use a library or service
      // to send the email.  This is just a placeholder.
      // You might use PHPMailer, SwiftMailer, or integrate with a third-party service.
      // For example:
      // sendEmail($email, $subject, $message);



      return true;  // Password reset email sent
    } else {
      // User not found
      return false;
    }
  } catch (PDOException $e) {
    error_log("Database error during password reset: " . $e->getMessage());
    return false;
  }
}


// Example Usage (for testing -  DO NOT USE IN PRODUCTION WITHOUT SECURITY HARDENING)
// Replace with your database credentials and email sending logic
$email = 'testuser@example.com';
$newPassword = 'NewSecurePassword123';
$dbHost = 'localhost';
$dbName = 'your_database_name';
$dbUser = 'your_db_user';
$dbPassword = 'your_db_password';

if (forgotPassword($email, $newPassword, $dbHost, $dbName, $dbUser, $dbPassword)) {
  echo "Password reset email has been sent. Check your email!";
} else {
  echo "Failed to reset password. Please check your email and try again, or contact support.";
}

?>


<?php

// Database connection details (replace with your actual credentials)
$host = "localhost";
$username = "your_username";
$password = "your_password";
$database = "your_database";

// Function to handle the forgot password process
function forgotPassword($email) {
  // 1. Validate email format (basic check)
  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    return "Invalid email address.";
  }

  // 2.  Check if the user exists in the database
  $conn = new mysqli($host, $username, $password, $database);

  if ($conn->connect_error) {
    return "Connection failed: " . $conn->connect_error;
  }

  $query = "SELECT id, email FROM users WHERE email = '$email'";
  $result = $conn->query($query);

  if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $userId = $row['id'];
    $userEmail = $row['email'];

    // 3. Generate a unique, secure token
    $token = bin2hex(random_bytes(32)); // Generate a 32-byte random string
    
    // 4.  Store the token in the database -  use a 'temp_password_token' column
    $insertQuery = "UPDATE users SET temp_password_token = '$token' WHERE id = '$userId'";
    if (!$conn->query($insertQuery)) {
      return "Error updating database.  " . $conn->error;
    }

    // 5.  Send an email to the user with the token and a link
    $to = $email;
    $subject = "Password Reset";
    $message = "Please click on the following link to reset your password:
" .
               "<a href='" . $_SERVER['PHP_SELF'] . "?reset=$token'>Reset Password</a>"; // Use $_SERVER['PHP_SELF']
    $headers = "From: your_email@example.com"; // Replace with your email

    if (mail($to, $subject, $message, $headers)) {
      return "Password reset email sent to $email.  Please check your inbox.";
    } else {
      return "Failed to send password reset email. Check your server's mail configuration.";
    }
  } else {
    return "User not found.";
  }
}

// Example usage (from the reset.php page)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $email = $_POST["email"];
  $resetToken = $_POST["reset_token"];

  if (empty($email) || empty($reset_token)) {
    echo "Error: Both email and token are required.";
  } else {
    $result = forgotPassword($email, $reset_token);
    echo $result; // Display the result
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

  <form method="post" action="reset.php">
    <label for="email">Email:</label>
    <input type="email" id="email" name="email" required><br><br>

    <input type="hidden" name="reset_token" value="">

    <input type="submit" value="Request Password Reset">
  </form>

  <hr>

  <form method="post" action="reset.php">
    <label for="email">Email:</label>
    <input type="email" id="email" name="email" required><br><br>

    <input type="hidden" name="reset_token" value="">

    <label for="reset_token">Reset Token:</label>
    <input type="text" id="reset_token" name="reset_token" required><br><br>

    <input type="submit" value="Reset Password">
  </form>

</body>
</html>


<?php

// Database connection details - Replace with your actual values
$db_host = "localhost";
$db_name = "your_database_name";
$db_user = "your_database_username";
$db_password = "your_database_password";

// Function to reset password
function forgot_password($email)
{
    // 1. Check if email exists in the database
    $conn = new mysqli($db_host, $db_user, $db_password, $db_name);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $sql = "SELECT id, email FROM users WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user_id = $result->fetch_assoc()['id'];
        $user_email = $result->fetch_assoc()['email'];

        // 2. Generate a unique token for the reset link
        $token = bin2hex(random_bytes(32)); // Generates a secure random string

        // 3. Store the token in the database associated with the user
        $sql = "UPDATE users SET password_reset_token = ? WHERE email = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $token, $email);

        if ($stmt->execute() === TRUE) {
            // 4.  Send an email with the reset link
            $reset_link = "http://localhost/reset_password.php?token=" . $token; // Replace with your website URL
            $to = $email;
            $subject = "Password Reset";
            $message = "Click on the link below to reset your password: " . $reset_link;
            $headers = "From: your_email@example.com"; // Replace with your email address

            mail($to, $subject, $message, $headers);

            echo "Password reset link sent to $email";
        } else {
            echo "Error updating password reset token: " . $conn->error;
        }

    } else {
        echo "Email not found";
    }

    $stmt->close();
    $conn->close();
}

// Example Usage (Demonstration -  Don't use this directly in a production environment)
//  This is only for testing the function.  Securely validate and sanitize user inputs before using.
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST["email"];
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

    <form method="post" action="">
        <label for="email">Enter your email:</label>
        <input type="email" id="email" name="email" required>
        <button type="submit">Reset Password</button>
    </form>

</body>
</html>


<?php

/**
 * Forgets a user's password.
 *
 * This function generates a random token, stores it in the database 
 * associated with the user's account, and sends an email to the user 
 * containing a link to reset their password.
 *
 * @param string $email The email address of the user.
 * @param string $token A random, unique token.  This should be generated securely.
 * @param string $reset_link_expiry The expiry time for the reset link (e.g., '24 hours').
 * @return bool True on success, false on failure.
 */
function forgot_password(string $email, string $token, string $reset_link_expiry) {
  // 1. Validate Input (Important Security Step)
  if (empty($email)) {
    error_log("Forgot password: Empty email provided.");
    return false;
  }

  if (empty($token)) {
    error_log("Forgot password: Empty token provided.");
    return false;
  }

  // 2. Retrieve User
  $user = get_user_by_email($email); // Implement this function
  if (!$user) {
    error_log("Forgot password: User with email $email not found.");
    return false;
  }

  // 3. Generate Reset Link
  $reset_link = generate_reset_link( $user->id, $token, $reset_link_expiry ); // Implement this function

  // 4. Store Reset Token (in the database)
  if (!store_reset_token( $user->id, $token, $reset_link_expiry )) {
    error_log("Forgot password: Failed to store reset token for user $email.");
    return false;
  }

  // 5. Send Password Reset Email
  if (!send_password_reset_email($user->email, $reset_link)) {
    error_log("Forgot password: Failed to send password reset email to $email.");
    // You might want to consider deleting the token from the database 
    // to prevent abuse if the email fails.  However, deleting it could 
    // be problematic if the email delivery is eventually successful.
    // delete_reset_token( $user->id, $token );
    return false;
  }


  return true;
}



/**
 * Placeholder function to get a user by email. 
 *  **IMPORTANT:** Replace with your actual database query.
 *  This is just an example.
 *
 * @param string $email The email address.
 * @return object|null  A user object if found, null otherwise.
 */
function get_user_by_email(string $email) {
  // Replace this with your actual database query
  // Example using a hypothetical database connection:
  // $db = get_db_connection();
  // $stmt = $db->prepare("SELECT * FROM users WHERE email = ?");
  // $stmt->execute([$email]);
  // $user = $stmt->fetch();

  // Simulate a user object
  $user = new stdClass();
  $user->id = 123;
  $user->email = $email;
  return $user;
}


/**
 * Placeholder function to generate a reset link. 
 * **IMPORTANT:** Implement a secure random token generation.
 *
 * @param int $userId The ID of the user.
 * @param string $token The generated token.
 * @param string $expiry The expiry time for the link.
 * @return string The generated reset link.
 */
function generate_reset_link(int $userId, string $token, string $expiry) {
  // Implement a secure random token generation method here.
  // Example:
  $reset_link = "https://yourdomain.com/reset-password?token=$token&expiry=$expiry";
  return $reset_link;
}


/**
 * Placeholder function to store the reset token in the database.
 * **IMPORTANT:**  Use parameterized queries to prevent SQL injection.
 *
 * @param int $userId The ID of the user.
 * @param string $token The generated token.
 * @param string $expiry The expiry time for the link.
 * @return bool True on success, false on failure.
 */
function store_reset_token(int $userId, string $token, string $expiry) {
  // Replace with your actual database query
  // Example using a hypothetical database connection:
  // $db = get_db_connection();
  // $stmt = $db->prepare("INSERT INTO reset_tokens (user_id, token, expiry) VALUES (?, ?, ?)");
  // $stmt->execute([$userId, $token, $expiry]);
  // return $db->lastInsertId() > 0;

  return true; // Simulate success
}


/**
 * Placeholder function to delete the reset token.
 *  Use this if the email fails and you want to immediately remove the token.
 *  Consider the implications before deleting the token.
 *
 * @param int $userId The ID of the user.
 * @param string $token The token.
 * @return bool True on success, false on failure.
 */
function delete_reset_token(int $userId, string $token) {
  // Replace with your actual database query
  // Example:
  // $db = get_db_connection();
  // $stmt = $db->prepare("DELETE FROM reset_tokens WHERE user_id = ? AND token = ?");
  // $stmt->execute([$userId, $token]);
  // return $stmt->rowCount() > 0;

  return true; // Simulate success
}


/**
 * Placeholder function to send the password reset email.
 * **IMPORTANT:**  Use a reliable email sending service.
 *
 * @param string $email The email address.
 * @param string $reset_link The generated reset link.
 */
function send_password_reset_email(string $email, string $reset_link) {
  // Replace with your actual email sending logic.
  // Use a reliable email sending service (e.g., SendGrid, Mailgun).
  // Example (Simulated):
  // $subject = "Password Reset";
  // $body = "Click this link to reset your password: " . $reset_link;
  // send_email($email, $subject, $body);

  // Simulate success
  return true;
}
?>


<?php

// Assuming you have a database connection established
// (e.g., $db connection object)
// and that you've defined a 'users' table with an 'email' column.

// Function to handle password reset
function forgotPassword($email, $resetToken, $resetHash, $db) {
  // 1. Check if the token exists in the database
  $query = "SELECT id FROM reset_tokens WHERE email = ? AND token = ?";
  $stmt = $db->prepare($query);
  $stmt->bind_param("ss", $email, $resetToken);
  $stmt->execute();

  if ($stmt->rowCount() == 0) {
    // Token not found
    return false;
  }

  // 2. If the token exists, update the 'used' column to true
  $query = "UPDATE reset_tokens SET used = 1 WHERE email = ? AND token = ?";
  $stmt = $db->prepare($query);
  $stmt->bind_param("ss", $email, $resetToken);
  $stmt->execute();

  if ($stmt->rowCount() == 0) {
    // Error updating token -  rare, but handle it.
    error_log("Error updating reset token: " . $db->error); // Log for debugging
    return false;
  }

  // 3. Send an email with a reset link (implement this part)
  //   -  Generate a temporary password and email it to the user
  //   -  Include a link to the password reset form with the token
  //   -  Set an expiration time for the token
  
  // Example:  (Replace with your email sending logic)
  $subject = "Password Reset Link";
  $to = $email;
  $body = "Click on this link to reset your password: " . $_SERVER['REQUEST_URI'] . "?token=" . $resetToken;

  //  Use a real email sending function here (e.g., sendmail, PHPMailer)
  //  Example using PHP's built-in mail function (simplest, but often unreliable):
  mail($to, $subject, $body);


  return true; // Token updated successfully
}


// Example Usage (for testing - this doesn't actually send an email)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $email = $_POST["email"];
  $resetToken = $_POST["reset_token"];
  $resetHash = $_POST["reset_hash"];  // This would be a hash of a temp password.  Don't store plain text passwords!

  //  Simulate a database connection (replace with your actual connection)
  $db = new mysqli("localhost", "username", "password", "database_name");

  if ($db->connect_error) {
    die("Connection failed: " . $db->connect_error);
  }

  if (forgotPassword($email, $resetToken, $resetHash, $db)) {
    echo "Password reset request sent.  Check your email.";
  } else {
    echo "Password reset request failed.  Possibly an invalid token.";
  }
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>Password Reset</title>
</head>
<body>

  <h1>Password Reset</h1>

  <form method="post" action="">
    <label for="email">Email:</label>
    <input type="email" id="email" name="email" required><br><br>

    <input type="hidden" name="reset_token" value="<?php echo isset($_GET['token']) ? $_GET['token'] : ''; ?>">

    <input type="submit" value="Reset Password">
  </form>

  <p>If you forgot your password, enter your email address to receive a reset link.</p>

</body>
</html>


<?php

// Assuming you have a database connection established
// (e.g., $db = new PDO(...) or similar)

// Function to handle the forgot password process
function forgot_password($email) {
    // 1. Validate Email (Important for security!)
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return "Invalid email address.";
    }

    // 2. Generate a Unique Token
    $token = bin2hex(random_bytes(32));  // More secure random token

    // 3. Store the Token in the Database (associated with the email)
    // This is where you'd insert a record into your users table
    // with columns like 'email', 'token', and 'token_expiry'
    // This example assumes a 'users' table with 'email' and 'password' columns.
    //  Adjust to your actual database schema.

    // Assuming you have a database connection called $db
    try {
        $stmt = $db->prepare("INSERT INTO users (email, token, token_expiry) VALUES (:email, :token, :expiry)");
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':token', $token);
        $stmt->bindParam(':expiry', date('Y-m-d H:i:s', strtotime('+30 minutes'))); // Token expires after 30 minutes. Adjust as needed.
        $stmt->execute();
    } catch (PDOException $e) {
        return "Error inserting token into database: " . $e->getMessage();
    }

    // 4. Send an Email with the Reset Link
    $subject = "Password Reset Request";
    $to = $email;
    $headers = "From: your_email@example.com" . "\r
";
    $link = "http://yourwebsite.com/reset_password.php?token=$token"; // Replace with your actual reset password page URL.  **Use HTTPS in production!**

    $message = "Click the link below to reset your password:
" . $link;

    mail($to, $message, $headers);

    return "Password reset link has been sent to your email address. Please check your inbox (and spam folder!).";
}


// Example Usage (This is just for demonstration - not for production)
// $email = "testuser@example.com"; // Replace with the user's email
// $result = forgot_password($email);
// echo $result;
?>


<?php

// Assuming you have a database connection established
// (e.g., $db = mysqli_connect("host", "user", "password", "database");)

/**
 * Resets a user's password.
 *
 * @param string $email The user's email address.
 * @param string $new_password The new password.
 * @return bool True if password reset was successful, false otherwise.
 */
function forgot_password(string $email, string $new_password) {
    // 1. Validate input (important for security)
    if (empty($email) || empty($new_password)) {
        return false; // Or throw an exception - better practice for robust applications
    }

    // 2. Check if the user exists
    $stmt = mysqli_prepare($db, "SELECT id FROM users WHERE email = ?");
    mysqli_stmt_bind_param($stmt, "s", $email); // 's' for string
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if (mysqli_num_rows($result) == 0) {
        mysqli_stmt_close($stmt);
        return false; // User not found
    }

    $user_id = mysqli_fetch_assoc($result)['id'];
    mysqli_stmt_close($stmt);

    // 3. Hash the new password
    $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

    // 4. Update the password in the database
    $stmt = mysqli_prepare($db, "UPDATE users SET password = ? WHERE id = ?");
    mysqli_stmt_bind_param($stmt, "ss", $hashed_password, $user_id);
    if (mysqli_stmt_execute($stmt)) {
        mysqli_stmt_close($stmt);
        return true;
    } else {
        // Handle database error (important!)
        error_log("Error resetting password for user: " . $email); // Log the error for debugging
        return false;
    }
}


// Example Usage (Illustrative - Replace with your actual form processing)
//  (Assuming you have a form with email and new_password fields)

//  $email = $_POST['email'];
//  $new_password = $_POST['new_password'];

//  if (forgot_password($email, $new_password)) {
//      echo "Password reset email sent.  Check your inbox.";
//      // Send email to confirm the password reset
//  } else {
//      echo "Error resetting password. Please try again.";
//  }


?>


<?php

// IMPORTANT:  This is a simplified example for demonstration purposes only.
// In a production environment, you MUST:
// 1.  Store passwords securely (hashed and salted).
// 2.  Implement robust security measures against brute-force attacks.
// 3.  Use a proper email sending library to avoid spam and ensure delivery.
// 4.  Never log or store email reset tokens.
// 5.  Consider adding CAPTCHA or other verification steps.

// Database connection details (Replace with your actual credentials)
$db_host = 'localhost';
$db_name = 'your_database_name';
$db_user = 'your_database_user';
$db_password = 'your_database_password';

/**
 * Resets the user's password.
 *
 * @param string $email The user's email address.
 * @return bool True if the password reset was initiated successfully, false otherwise.
 */
function forgot_password(string $email): bool
{
    // 1. Connect to the database
    $conn = new mysqli($db_host, $db_user, $db_password, $db_name);

    // Check connection
    if ($conn->connect_error) {
        error_log("Failed to connect to database: " . $conn->connect_error);  // Log the error
        return false;
    }

    // 2. Check if the user exists
    $sql = "SELECT id, email FROM users WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email); // "s" indicates a string parameter
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 0) {
        $stmt->close();
        $conn->close();
        return false; // User not found
    }

    $user = $result->fetch_assoc();
    $userId = $user['id'];
    $stmt->close();


    // 3. Generate a unique reset token
    $resetToken = bin2hex(random_bytes(32)); //  A strong, random token

    // 4. Store the token in the database associated with the user
    $sql = "INSERT INTO password_resets (user_id, token, expires_at) VALUES (?, ?, NOW())";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iis", $userId, $resetToken, $resetToken); // i = integer, s = string
    $stmt->execute();
    $stmt->close();



    // 5. Send the password reset email
    $to = $email;
    $subject = 'Password Reset';
    $message = "Click the following link to reset your password: " .  $_SERVER['PHP_SELF'] . "?reset_token=" . $resetToken; //Use the current script name to generate a direct link.
    $headers = "From: your_email@example.com"; // Replace with your email address

    mail($to, $subject, $message, $headers);

    // 6.  Close the connection
    $conn->close();

    return true;
}


// Example Usage (This would typically be in a form submission handler)
if (isset($_GET['reset_token'])) {
    $resetToken = $_GET['reset_token'];

    if (forgot_password($resetToken)) {
        echo "Password reset email sent to " . $resetToken . ".  Check your inbox.";
    } else {
        echo "Error: Could not reset password.";
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
    <form method="get" action="your_script_name.php"> <!-- Replace with the actual filename -->
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required>
        <button type="submit">Reset Password</button>
    </form>
</body>
</html>


<?php

// This is a simplified example for demonstration purposes only.
// For a production environment, you should:
// 1. Use a secure password reset mechanism (e.g., token-based).
// 2. Sanitize and validate all inputs rigorously.
// 3. Implement proper error handling and logging.
// 4. Consider using a dedicated password reset library for added security.


function forgot_password($email, $password_reset_token, $reset_link_base_url, $secret_key) {
    // 1. Validate Email (Basic - consider stricter validation in production)
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return "Invalid email address.";
    }

    // 2. Check if a password reset token exists for this email
    $reset_token = md5($email . $reset_token . $secret_key); //  Use a strong hashing algorithm!
    
    $query = "SELECT id FROM password_resets WHERE email = '$email' AND token = '$reset_token'";
    $result = mysqli_query($GLOBALS['db'], $query); // Use a prepared statement for security!
    
    if (mysqli_num_rows($result) == 0) {
        return "Invalid password reset token.";
    }

    // 3.  (In a real application) You'd likely generate a new token and
    //     expire the old one.
    //     This is just a simplified example.

    // 4. (In a real application)  You would redirect to a page where the user
    //     can set a new password, using the token to verify their request.
    //     This is a placeholder for that logic.
    return "Password reset link has been sent to your email address.";
}


// Example Usage (Illustrative - Replace with your actual database and configuration)
//  (Don't use this directly in production - it's just for demonstration)

// Assume you have a database connection established (e.g., $GLOBALS['db'] is your connection)

$email = "test@example.com"; // Replace with a valid email address
$reset_token = "random_token_123";  // Generate a unique, random token
$reset_link_base_url = "http://yourwebsite.com/reset-password";

$result = forgot_password($email, $reset_token, $reset_link_base_url, "your_secret_key");

echo $result;  // Output: Password reset link has been sent to your email address.



// Important Security Notes and Best Practices:

// 1. Token Generation and Security:
//    - Use a cryptographically secure random number generator (CSPRNG) for generating the password reset token.  `random_bytes()` or `openssl_random_pseudo_bytes()` are better than `rand()` or `mt_rand()`
//    - The token should be a long, random string.
//    - Store the token in a database securely.
//    - The token should be time-limited (e.g., expire after 30 minutes).

// 2. Hashing:
//    - **Never** store passwords in plain text. Use a strong password hashing algorithm like `password_hash()` or `bcrypt`.

// 3. Prepared Statements (Critical for Security):
//    - **Always** use prepared statements to prevent SQL injection attacks.  The example uses `mysqli_query()` which can be vulnerable if not properly secured.  Switch to prepared statements (e.g., `mysqli_stmt`) for a robust solution.

// 4. Input Validation:
//    - Thoroughly validate all user inputs to prevent vulnerabilities.  Use `filter_var()` with appropriate filters (e.g., `FILTER_VALIDATE_EMAIL`, `FILTER_SANITIZE_EMAIL`).

// 5. Error Handling and Logging:
//    - Implement proper error handling to gracefully handle unexpected situations.
//    - Log errors and suspicious activity for debugging and security monitoring.

// 6. Session Management:
//    - Use sessions to track user activity and maintain security.

// 7. Rate Limiting:
//     - Implement rate limiting to prevent brute-force attacks.

// 8. HTTPS:
//     - Always use HTTPS to encrypt communication between the user's browser and your server.
?>


<?php

// Database connection details (Replace with your actual credentials)
$host = "localhost";
$username = "your_username";
$password = "your_password";
$database = "your_database_name";

// Function to send an email (Requires PHP mail function)
function sendPasswordResetEmail($email, $resetToken) {
  // Implement your email sending logic here using a library like PHPMailer
  // For example:
  $to = $email;
  $subject = 'Password Reset';
  $message = "Click on this link to reset your password: <a href='" . $_SERVER['PHP_SELF'] . "?reset_token=$resetToken'>Reset Password</a>";
  $headers = "From: your_email@example.com";

  mail($to, $message, $headers);
}


// Function to handle password reset request
function forgotPassword($email) {
  // 1. Validate Email
  if (empty($email)) {
    return "Error: Email address is required.";
  }

  // 2. Check if email exists in the user table
  $conn = new mysqli($host, $username, $password, $database);
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  $sql = "SELECT id, email FROM users WHERE email = '$email'";
  $result = $conn->query($sql);

  if ($result->num_rows > 0) {
    $user_id = $result->fetch_assoc()['id'];
    $resetToken = generateRandomToken(); // Generate a unique token
    $conn->query("UPDATE users SET reset_token = '$resetToken' WHERE id = '$user_id'");

    // Send password reset email
    sendPasswordResetEmail($email, $resetToken);

    return "Password reset email sent to $email. Please check your inbox.";

  } else {
    return "Error: Email address not found.";
  }

  $conn->close();
}


// Function to generate a random token (for security)
function generateRandomToken() {
  return bin2hex(random_bytes(32)); // Generate 32-byte random data
}

// --- Example Usage (for testing - should be handled by a form in a real application)
// if (isset($_POST['forgot_email'])) {
//   $email = $_POST['forgot_email'];
//   $result = forgotPassword($email);
//   echo $result;
// }

?>


<?php

// IMPORTANT: This is a simplified example for demonstration purposes.
// In a real-world scenario, you should *never* store passwords in plain text.
// Use a strong hashing algorithm like bcrypt or Argon2.

// Assume you have a database connection established (e.g., $db)
// and a user table with a 'email' column.

function forgot_password($email, $db) {
  // 1. Validate Email
  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    return ['status' => 'error', 'message' => 'Invalid email address.'];
  }

  // 2. Check if the user exists
  $result = $db->query("SELECT id, email FROM users WHERE email = '$email'");

  if ($result->num_rows === 0) {
    return ['status' => 'error', 'message' => 'User not found.'];
  }

  $user = $result->fetch_assoc();

  // 3. Generate a unique, time-based token
  $token = bin2hex(random_bytes(32)); // Generate a secure random token

  // 4. Store the token and user ID in the database
  $db->query("UPDATE users SET password_reset_token = '$token' WHERE email = '$email'");

  // 5. Send an email to the user with a link containing the token
  //    (This is where you would integrate with an email sending service.)
  //    For example:
  //    $subject = 'Password Reset Link';
  //    $message = "Please click the link below to reset your password: " . $base_url . "/reset_password?token=$token";
  //    $headers = 'From: your_email@example.com' . "\r
";
  //    $result = mail($email, $subject, $message, $headers);

  // 6. Return a success response
  return ['status' => 'success', 'message' => 'Password reset link sent to your email.'];
}


// Example Usage (Demonstration - REPLACE with actual database interaction)
//  --  This is just a placeholder to demonstrate the function.
//  --  In a real application, you'll use a database connection.

// Mock Database Connection (For demonstration only)
class MockDatabase {
    public function query($sql) {
        // Simulate query results for testing.  Replace with actual DB interaction.
        if (strpos($sql, "SELECT") === 0) {
            if (strpos($sql, "users WHERE email = 'test@example.com'") === 0) {
                return new \stdClass();
            }
        }
        return new \stdClass();
    }
}


$db = new MockDatabase(); // Using a mock database

$email = 'test@example.com';

$result = forgot_password($email, $db);

if ($result['status'] === 'success') {
  echo "Password reset link sent to $email.
";
} else {
  echo "Error: " . $result['message'] . "
";
}
?>


<?php

// Assuming you have a database connection established and named $db

/**
 * Resets a user's password.
 *
 * @param string $email The email address of the user.
 * @param string $newPassword The new password.
 * @return bool True on success, false on failure.
 */
function forgot_password(string $email, string $newPassword) {
  global $db; // Assuming $db is your database connection

  // 1.  Check if the email exists in the database.  Case-insensitive comparison is important.
  $stmt = $db->prepare("SELECT id FROM users WHERE email = ?");
  $email_lower = strtolower($email);  // Convert to lowercase for comparison
  $stmt->bind_param("s", $email_lower);
  $stmt->execute();
  $result = $stmt->get_result();

  if ($result->num_rows == 0) {
    $stmt->close();
    return false; // Email not found
  }

  $user_id = $result->fetch_assoc()['id'];
  $stmt->close();


  // 2.  Hash the new password before updating the database.  **CRITICAL SECURITY STEP**
  $hashed_password = password_hash($newPassword, PASSWORD_DEFAULT);

  // 3.  Update the user's password in the database.
  $stmt = $db->prepare("UPDATE users SET password = ? WHERE id = ?");
  $stmt->bind_param("ss", $hashed_password, $user_id);
  $exec_result = $stmt->execute();

  if (!$exec_result) {
    // Handle database errors.  Important for debugging!
    error_log("Error updating password for user " . $email . ": " . $db->error);
    $stmt->close();
    return false;
  }

  $stmt->close();

  return true; // Password reset successful
}


// Example Usage (Illustrative -  This would be part of your form handling)

//  Assuming you have $email and $new_password from form input.

// $email = $_POST['email'];
// $newPassword = $_POST['new_password'];

// if (isset($email) && isset($newPassword)) {
//   if (forgot_password($email, $newPassword)) {
//     echo "Password reset email sent. Please check your inbox.";
//   } else {
//     echo "Error resetting password. Please try again.";
//   }
// } else {
//   echo "Invalid input. Please enter your email and a new password.";
// }

?>


<?php

// Assuming you have a database connection established (e.g., $db)
// and a user table with email and password fields.

// Function to handle the forgot password request
function forgotPassword($email) {
  // 1. Validate the email
  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    return "Invalid email address.";
  }

  // 2. Check if the user exists in the database
  $stmt = $db->prepare("SELECT id, password FROM users WHERE email = ?");
  $stmt->execute([$email]);
  $user = $stmt->fetch(PDO::FETCH_ASSOC);

  if (!$user) {
    return "User not found.";
  }

  // 3. Generate a unique token
  $token = bin2hex(random_bytes(32));

  // 4.  Prepare the password reset token
  $reset_token = $token . "_" . $timestamp = date("Y-m-d H:i:s", time());


  // 5. Hash the password (Important for security!)
  $hashedPassword = password_hash($user['password'], PASSWORD_DEFAULT);

  // 6.  Store the token in the database associated with the user
  $stmt = $db->prepare("UPDATE users SET password_reset_token = ?, password_reset_token_expiry = ? WHERE email = ?");
  $stmt->execute([$token, date("Y-m-d H:i:s", time() + 60 * 60 * 24), $email]); // Store for 24 hours

  // 7. Send the password reset email (Example - Customize this!)
  $to = $email;
  $subject = 'Password Reset';
  $message = "Click <a href='" . $_SERVER['PHP_SELF'] . "?reset=$token'</a> to reset your password.";
  $headers = "From: your_email@example.com" . "\r
";

  mail($to, $message, $headers);

  return "Password reset email sent. Please check your inbox.";
}

// Example Usage (This would typically be called from a form submission)
// You would validate the form input and then call this function.

// Example Form Handling (Illustrative - Adjust to your form structure)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $email = $_POST["email"];
  $reset_token = $_GET["reset"];
    //Security - Sanitize the Reset Token
  $reset_token = filter_var($reset_token, FILTER_SANITIZE_STRING);
    if($reset_token == ""){
      $response = "Invalid Reset Token";
    }else{
      $response = forgotPassword($email);
    }


}else{
  $response = "Please fill in the email field.";
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>Forgot Password</title>
</head>
<body>

  <h1>Forgot Password</h1>

  <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
    <label for="email">Email:</label>
    <input type="email" id="email" name="email" required>
    <button type="submit">Reset Password</button>
  </form>

  <?php
    if(isset($response)){
      echo "<p>" . htmlspecialchars($response) . "</p>";
    }
  ?>

</body>
</html>


<?php

// Database connection details (replace with your actual details)
$host = "localhost";
$username = "your_username";
$password = "your_password";
$dbname = "your_database";

// Function to handle password reset
function forgot_password($email)
{
    // 1. Check if the email exists in the database
    $conn = new mysqli($host, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $sql = "SELECT id, email FROM users WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email); // "s" indicates a string
    $stmt->execute();

    $result = $stmt->get_result();

    if ($result->num_rows == 0) {
        $stmt->close();
        $conn->close();
        return false; // Email not found
    }

    $user = $result->fetch_assoc();
    $userId = $user['id'];
    $userEmail = $user['email'];
    $stmt->close();

    // 2. Generate a unique token
    $token = bin2hex(random_bytes(32));

    // 3. Store the token and user ID in the database
    $sql = "UPDATE users SET reset_token = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $token, $userId);
    $stmt->execute();

    if (!$stmt->affected_rows > 0) {
        $stmt->close();
        $conn->close();
        return false; // Failed to store the token
    }

    // 4.  Send an email with the reset link
    $to = $email;
    $subject = "Password Reset Link";
    $message = "Click this link to reset your password: " . "<a href='" . $_SERVER["PHP_SELF"] . "?reset=" . urlencode($token) . "'>Reset Password</a>";
    $headers = "From: your_email@example.com"; // Replace with your email address

    mail($to, $subject, $message, $headers);

    // 5. Close the connection
    $conn->close();

    return true; // Password reset link sent successfully
}


// Example Usage (This should be in a separate file or part of a form)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST["email"];

    if (empty($email)) {
        echo "Error: Please enter your email address.";
    } else {
        if (forgot_password($email)) {
            echo "Password reset link has been sent to your email address.";
        } else {
            echo "Error: Failed to send password reset link. Please try again.";
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

    <h2>Forgot Password</h2>

    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required>
        <button type="submit">Reset Password</button>
    </form>

</body>
</html>


<?php

/**
 * Forgets a user's password.
 *
 * This function generates a unique, time-based token and sends an email
 * containing a link to reset the password.
 *
 * @param string $email The email address of the user to reset the password for.
 * @param string $token A unique token to verify the request. (Optional, used for security)
 * @return bool True on success, false on failure.
 */
function forgot_password(string $email, string $token = '') {
  // 1. Check if the email exists in the database
  $user = get_user_by_email($email);  // Implement this function - See Example Below
  if (!$user) {
    return false; // User does not exist
  }

  // 2. Generate a unique token
  $token = generate_unique_token(); // Implement this function - See Example Below

  // 3. Store the token in the database, associated with the user's email
  save_token_to_database($token, $email); // Implement this function - See Example Below


  // 4. Build the password reset link
  $reset_link = "/reset_password?token=" . urlencode($token) . "&email=" . urlencode($email);

  // 5. Send the password reset email
  $subject = "Password Reset Request";
  $message = "Please click the following link to reset your password: " . $reset_link;

  $headers = "From: Your Website <noreply@yourwebsite.com>";  // Replace with your actual email address

  $result = send_email($email, $subject, $message, $headers); // Implement this function - See Example Below
  if ($result === true) { // Assuming send_email returns true on success
    return true;
  } else {
    // Handle email sending failure (log, display error, etc.)
    error_log("Failed to send password reset email for " . $email);
    return false;
  }
}


// ------------------------------------------------------------------
//  Placeholder functions - You *MUST* implement these!
// ------------------------------------------------------------------

/**
 * Retrieves a user from the database based on their email address.
 *
 * @param string $email The email address to search for.
 * @return object|null User object if found, null otherwise.
 */
function get_user_by_email(string $email): ?object {
  // **IMPORTANT:** Replace this with your actual database query.
  // This is just a placeholder.
  // Example using mysqli:
  // $conn = mysqli_connect("your_db_host", "your_db_user", "your_db_password", "your_db_name");
  // if (!$conn) {
  //   die("Connection failed: " . mysqli_connect_error());
  // }

  // $sql = "SELECT * FROM users WHERE email = '$email'";
  // $result = mysqli_query($conn, $sql);

  // if (mysqli_num_rows($result) > 0) {
  //   $row = mysqli_fetch_assoc($result);
  //   return $row;
  // } else {
  //   return null;
  // }

  // **Dummy User Object** - Remove this when integrating with your database
  $user = (object) [
    'id' => 1,
    'email' => 'test@example.com',
    'password' => 'hashed_password'
  ];
  return $user;
}


/**
 * Generates a unique, time-based token.
 *
 * @return string A unique token.
 */
function generate_unique_token(): string {
  return bin2hex(random_bytes(32)); // More secure than generating a random string
}


/**
 * Saves the token to the database, associated with the user's email.
 *
 * @param string $token The token to save.
 * @param string $email The email address to associate with the token.
 * @return void
 */
function save_token_to_database(string $token, string $email) {
  // **IMPORTANT:** Implement your database logic here.
  // Example using mysqli:
  // $conn = mysqli_connect("your_db_host", "your_db_user", "your_db_password", "your_db_name");
  // if (!$conn) {
  //   die("Connection failed: " . mysqli_connect_error());
  // }

  // $sql = "INSERT INTO tokens (email, token, expiry_date) VALUES ('$email', '$token', NOW())";
  // if (mysqli_query($conn, $sql)) {
  //   //  Success
  // } else {
  //   // Handle error
  // }

  // **Dummy Database Logic** - Remove this when integrating with your database
  //  This just stores the token in a variable to demonstrate functionality
  $_SESSION['reset_token'] = $token;
  $_SESSION['reset_expiry'] = date('Y-m-d H:i:s', time() + 3600); // Expires in 1 hour
}



/**
 * Sends an email.
 *
 * @param string $to       The recipient's email address.
 * @param string $subject  The email subject.
 * @param string $body     The email body.
 * @param string $headers  Email headers.
 * @return bool True on success, false on failure.
 */
function send_email(string $to, string $subject, string $body, string $headers) {
  // **IMPORTANT:**  Replace this with your actual email sending code.
  // This is just a placeholder.  Use a library like PHPMailer.

  // Example using PHPMailer (requires installation and configuration)
  // require_once 'PHPMailer/PHPMailerAutoload.php';
  // $mail = new PHPMailer();
  // $mail->Mailer = 'PHPMailer';
  // $mail->SMTPDebugEnable = false; // Set to true for debugging
  // $mail->isSMTP();                       // Set to true for SMTP
  // $mail->Host       = 'smtp.example.com';
  // $mail->SMTPAuth   = true;                    // Enable SMTP authentication
  // $mail->Username   = 'your_smtp_username';
  // $mail->Password   = 'your_smtp_password';
  // $mail->Port = 587;                         // Port for submission
  // $mail->SetFrom('your_website@example.com', 'Your Website');
  // $mail->Subject = $subject;
  // $mail->Body = $body;
  // $mail->AltBody = $body;
  // $mail->AddAttachment('attachment.php', 'Attachment name');  // Add attachments
  // $result = $mail->send($to, $headers);

  // **Dummy Email Sending** - Remove this when integrating with your email provider
  // For demonstration purposes, just return true.
  return true;
}
?>


<?php

// Assuming you have a database connection established (e.g., $db)

/**
 * Forgets a user's password and sends a password reset link.
 *
 * @param string $email The email address of the user.
 * @return bool True if a reset link was successfully generated and sent, false otherwise.
 */
function forgotPassword(string $email): bool
{
    // Validate email format (basic check, use a proper validation library in production)
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        error_log("Invalid email format provided: " . $email); // Log for debugging
        return false;
    }

    // 1. Generate a unique, secure token (using a cryptographically secure random function)
    $token = bin2hex(random_bytes(32));

    // 2. Hash the token (for security)
    $hashedToken = password_hash($token, PASSWORD_DEFAULT);  // Using password_hash for security.

    // 3. Store the token and user ID in the database
    $query = "INSERT INTO password_resets (user_id, token, expires_at)
              VALUES (:user_id, :token, :expires_at)";

    $stmt = $db->prepare($query); // Assuming $db is your database connection
    $stmt->bindParam(':user_id', $userId);
    $stmt->bindParam(':token', $hashedToken);
    $stmt->bindParam(':expires_at', time() + (2 * 60 * 60)); // Token expires in 2 hours (example)
    $result = $stmt->execute();

    if (!$result) {
        error_log("Error inserting reset token into database: " . print_r($stmt->errorInfo(), true)); // Log the error
        return false;
    }

    // 4.  Send the password reset link (email)
    $to = $email;
    $subject = "Password Reset Link";
    $message = "Click this link to reset your password: " . $_SERVER['HTTPS'] . "://" . $_SERVER['HTTP_HOST'] . "/reset_password?token=" . $token;
    $headers = "From: Your Website <noreply@yourwebsite.com>"; // Replace with your actual sender email

    if (mail($to, $subject, $message, $headers)) {
        return true;
    } else {
        error_log("Failed to send password reset email to " . $email);
        // Optionally, delete the reset token from the database if the email fails.
        // This is crucial to prevent abuse.
        deleteResetToken($userId, $token);
        return false;
    }
}


/**
 * Deletes a password reset token from the database.
 *
 * @param int $userId The ID of the user.
 * @param string $token The token to delete.
 */
function deleteResetToken(int $userId, string $token): void
{
    $query = "DELETE FROM password_resets WHERE user_id = :user_id AND token = :token";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':user_id', $userId);
    $stmt->bindParam(':token', $token);
    $stmt->execute();
}


// Example Usage (in your web form handling logic)

//  ... (form submission handling) ...

//  $email = $_POST['email']; // Get the email from the form

//  if (forgotPassword($email)) {
//      echo "Password reset email sent. Check your inbox.";
//  } else {
//      echo "An error occurred while sending the password reset email.";
//  }

?>


<?php

/**
 * Forgets a user's password.
 *
 * This function generates a unique, time-based token to be sent to the user's email.
 * The user can then use this token to reset their password.
 *
 * @param string $email The email address of the user.
 * @param string $token A unique, time-based token.  This is generated internally.
 * @param string $reset_url The URL to redirect the user to after they use the reset token.
 * @return bool True if the token was successfully sent, false otherwise.
 */
function forgot_password(string $email, string $token, string $reset_url): bool
{
    // 1. Validate Input (Basic - Add more robust validation as needed)
    if (empty($email)) {
        error_log("Forgot password: Empty email provided");
        return false;
    }
    if (empty($token)) {
        error_log("Forgot password: Empty token provided");
        return false;
    }

    // 2.  Store the token in the database (Replace with your actual database logic)
    // This is a simplified example - use prepared statements to prevent SQL injection!
    $db_connection = new PDO('mysql:host=localhost;dbname=your_database_name', 'your_username', 'your_password');  // Replace with your credentials
    try {
        $stmt = $db_connection->prepare("INSERT INTO forgot_passwords (email, token, expiry) VALUES (?, ?, NOW())");
        $stmt->execute([$email, $token]);
    } catch (PDOException $e) {
        error_log("Forgot password: Database error: " . $e->getMessage());
        return false;
    }

    // 3. Send the reset email
    $subject = "Password Reset";
    $message = "Please click on the following link to reset your password: " . $reset_url;
    $headers = "From: your_email@example.com";  // Replace with your email

    if (mail($email, $subject, $message, $headers)) {
        error_log("Forgot password: Email sent to " . $email);
        return true;
    } else {
        error_log("Forgot password: Failed to send email to " . $email);
        return false;
    }
}


// Example usage (for testing - NEVER put sensitive data directly in your code)
// Replace these with actual values
$email = "testuser@example.com";  // Replace with a valid email
$token = "abcdef1234567890";
$reset_url = "https://yourwebsite.com/reset-password?token=" . urlencode($token); // Encode the token for the URL

if (forgot_password($email, $token, $reset_url)) {
    echo "Password reset email sent to " . $email;
} else {
    echo "Failed to send password reset email.";
}

?>


<?php

/**
 *  Forgot Password Function
 *
 *  This function generates a unique token, sends an email with a reset link,
 *  and updates the user's password reset status in the database.
 *
 * @param string $email The user's email address.
 * @param string $newPassword The new password for the user.
 * @param string $token  A unique token generated for the reset link. (Generated internally)
 * @param PDO $pdo      The PDO database connection object.
 *
 * @return bool True if the password reset process was initiated successfully,
 *              false otherwise (e.g., email sending failure, invalid email).
 */
function forgotPassword(string $email, string $newPassword, PDO $pdo)
{
    // 1. Generate a Unique Token
    $token = bin2hex(random_bytes(32)); // Use a cryptographically secure random function
    //  'bin2hex' converts the byte array from random_bytes() into a hexadecimal string.
    //  This is a standard way to create a unique token.


    // 2.  Prepare the SQL Query
    $sql = "UPDATE users SET password = :password, password_reset_token = :token, password_reset_token_expiry = :expiry, password_reset_status = 'valid' WHERE email = :email";

    // 3. Prepare the statement
    $stmt = $pdo->prepare($sql);


    // 4. Bind Parameters
    $stmt->bindParam(':password', $newPassword);
    $stmt->bindParam(':token', $token);
    $stmt->bindParam(':expiry', date('Y-m-d H:i:s', time() + (60 * 60 * 24))); // Expire after 24 hours
    $stmt->bindParam(':email', $email);


    // 5. Execute the Query
    if ($stmt->execute()) {
        // 6. Generate the Reset Link (Email Content)
        $resetLink = "https://yourwebsite.com/reset-password?token=" . $token;  // Replace yourwebsite.com

        // 7.  Send the Email (Implement your email sending logic here)
        $to = $email;
        $subject = 'Password Reset';
        $message = "Click the link below to reset your password:
" . $resetLink;
        $headers = 'From: yourwebsite@example.com' . "\r
";

        if (mail($to, $subject, $message, $headers)) {
            // Email sent successfully.
            return true;
        } else {
            // Email sending failed
            // Optionally log the error here.  Crucial for debugging.
            error_log("Error sending password reset email for " . $email);
            return false;
        }
    } else {
        // Query failed - likely an invalid email or other database issue
        error_log("Error updating password for " . $email);
        return false;
    }
}


// Example Usage (Replace with your actual database connection)
//  Important:  This is just an example.  You MUST adapt this to your specific setup.

// Dummy database connection (replace with your actual connection)
$host = 'localhost';
$db_name = 'your_database_name';
$db_user = 'your_database_user';
$db_password = 'your_database_password';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db_name;charset=utf8", $db_user, $db_password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}


$email = 'testuser@example.com';  // Replace with the user's email
$newPassword = 'newSecurePassword123';  // Replace with the new password

if (forgotPassword($email, $newPassword, $pdo)) {
    echo "Password reset email sent successfully!";
} else {
    echo "Failed to initiate password reset.";
}

?>


<?php

// **Important Security Notes:**
// * This code is a basic example and should NOT be used in a production environment without significant security enhancements.
// * NEVER store passwords directly in the database.  Always hash them using a strong hashing algorithm like bcrypt or Argon2.
// * This example uses a simple email verification. For a real application, you'd want to use a more robust system.
// * Consider using a dedicated password reset service for better security and user experience.
// * Implement rate limiting and anti-abuse measures to prevent brute-force attacks.

// Configuration
$db_host = 'localhost';
$db_name = 'your_database_name';
$db_user = 'your_database_user';
$db_password = 'your_database_password';
$from_email = 'your_email@example.com'; // Sender email address
$website_url = 'https://yourwebsite.com'; // Website URL

// Function to reset the password
function forgot_password($email, $website_url) {
  // 1. Database Connection (Replace with your actual database connection)
  $conn = new mysqli($db_host, $db_user, $db_password, $db_name);

  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  // 2. Generate a unique token
  $token = bin2hex(random_bytes(32)); // Generate a cryptographically secure token

  // 3. Store the token in the database (associate with the email)
  $sql = "INSERT INTO forgot_password_tokens (email, token, expiry_timestamp)
          VALUES ('$email', '$token', NOW() + INTERVAL 24 HOUR)"; // Expires after 24 hours

  if ($conn->query($sql) === FALSE) {
    echo "Error inserting token: " . $conn->error;
    return false;
  }

  // 4.  Create and send the password reset email
  $subject = 'Password Reset Request';
  $message = "Hello,

" .
             "You have requested a password reset for your account.

" .
             "Please click on the following link to reset your password:
" .
             "<a href='" . $website_url . "/reset_password.php?token=$token'>Reset Password</a>

" .
             "If you did not request this password reset, please ignore this email.

" .
             "Sincerely,
" .
             $website_url;

  $headers = "From: " . $from_email . "\r
";
  $headers .= "Reply-To: " . $from_email . "\r
";

  if (!mail($email, $subject, $message, $headers)) {
    echo "Error sending email.
";
    // Attempt to delete the token in case email sending failed.  This is crucial.
    $delete_sql = "DELETE FROM forgot_password_tokens WHERE email = '$email'";
    if ($conn->query($delete_sql) === FALSE) {
        echo "Error deleting token: " . $conn->error;
    }
    return false;
  }

  return true;
}

// Example Usage (Simulated Form Submission)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL); // Sanitize email

  if (empty($email)) {
    echo "Please enter your email address.";
  } else {
    if (forgot_password($email, $website_url)) {
      echo "Password reset email sent to " . $email;
    } else {
      echo "Password reset failed.";
    }
  }
}

?>


<?php

// This is a simplified example and should be used in a production environment
// with proper security measures.

// Database connection (replace with your actual credentials)
$host = 'localhost';
$username = 'your_db_user';
$password = 'your_db_password';
$database = 'your_database_name';

// Reset token generation function (should use a strong, random function)
function generateRandomString($length = 32) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyz';
    $randomString = '';
    $charactersLength = strlen($characters);
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}


// Reset password function
function forgotPassword($email, $host, $username, $password, $database) {
    // 1. Validate Email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return 'Invalid email address.';
    }

    // 2. Check if user exists
    $stmt = $conn->prepare("SELECT id, email FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 0) {
        $stmt->close();
        return 'User not found.';
    }

    $user = $result->fetch_assoc();
    $userId = $user['id'];
    $stmt->close();



    // 3. Generate a unique token
    $resetToken = generateRandomString();

    // 4.  Create a reset token record in the database
    $stmt = $conn->prepare("INSERT INTO password_resets (user_id, token, expiry) VALUES (?, ?, ?)");
    $stmt->bind_param("iii", $userId, $resetToken, time() + 60); // Expires in 60 minutes
    $result = $stmt->execute();

    if ($result === false) {
        // Handle database errors here (e.g., try again, log error)
        error_log("Error creating password reset record: " . error_get_last()['message']);
        return 'Failed to generate reset token.';
    }

    $resetId = $conn->insert_id; // Get the ID of the inserted row.  Important for later retrieval.
    $stmt->close();


    // 5.  Send reset email
    $subject = 'Password Reset';
    $message = "Click on this link to reset your password: " . '<a href="' . $_SERVER['PHP_SELF'] . '?reset=' . $resetToken . '" >Reset Password</a>';
    $headers = "From: your_email@example.com" . "\r
";

    if(mail($email, $subject, $message, $headers)) {
        return 'Password reset email has been sent.';
    } else {
        return 'Failed to send password reset email.';
    }
}


// Example Usage (Simulated - replace with your form submission)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST["email"];

    // Replace this with your actual database connection
    $conn = new mysqli($host, $username, $password, $database);
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }


    $result = forgotPassword($email, $host, $username, $password, $database);
    echo $result;
}

?>

<!DOCTYPE html>
<html>
<head>
    <title>Forgot Password</title>
</head>
<body>

    <h1>Forgot Password</h1>

    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required>
        <button type="submit">Forgot Password</button>
    </form>

</body>
</html>


<?php

// Ensure this file is in a secure location and not directly accessible via web browser.
// This is just an example for demonstration purposes.
// In a production environment, you should use a proper database and secure authentication practices.

// Database credentials (replace with your actual credentials)
$db_host = 'localhost';
$db_name = 'your_database_name';
$db_user = 'your_db_user';
$db_password = 'your_db_password';


function forgot_password($email) {
  // 1. Check if the email exists in the user table
  $conn = new mysqli($db_host, $db_user, $db_password, $db_name);

  if ($conn->connect_error) {
    // Handle connection error appropriately (e.g., log it, display an error message)
    die("Connection failed: " . $conn->connect_error);
  }

  $sql = "SELECT id, email FROM users WHERE email = ?";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param("s", $email); // "s" indicates a string parameter
  $stmt->execute();

  $result = $stmt->get_result();

  if ($result->num_rows == 0) {
    $stmt->close();
    $conn->close();
    return false; // Email not found
  }

  $user_id = $result->fetch_assoc()['id']; //get the id
  $stmt->close();
  $conn->close();


  // 2. Generate a unique token for the password reset
  $token = bin2hex(random_bytes(32)); // Generate a random string (32 bytes = 256 bits)

  // 3. Store the token and user ID in the database
  $conn = new mysqli($db_host, $db_user, $db_password, $db_name);
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  $sql = "INSERT INTO password_resets (user_id, token, expires_at) VALUES (?, ?, NOW())";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param("ss", $user_id, $token);
  $stmt->execute();

  $stmt->close();
  $conn->close();


  // 4. Send an email with the token and a link to reset the password
  $email_body = "Hello,

" .
                "Please use the following link to reset your password:
" .
                "http://yourwebsite.com/reset_password?token=" . $token . "

" .
                "This link will expire in 24 hours.";  // Adjust expiration time as needed

  //  Replace with your email sending logic (e.g., using PHPMailer, SMTP, etc.)
  //  Example (placeholder):
  //  send_email($email, "Password Reset", $email_body);

  return true; // Password reset request initiated successfully
}


// Example Usage (for demonstration - replace with your form submission handling)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST["email"];
    if (isset($email)) {
      if (forgot_password($email)) {
        echo "Password reset email sent to " . $email;
      } else {
        echo "Email not found or an error occurred during the password reset process.";
      }
    } else {
      echo "Email not provided.";
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

  <form method="post" action="">
    <label for="email">Email:</label>
    <input type="email" id="email" name="email" required>
    <button type="submit">Request Password Reset</button>
  </form>
</body>
</html>


<?php

// Assume you have a database connection established and a user table named 'users'
// with a 'password' column.  This is a simplified example and you'll need 
// to adapt it to your specific database setup.

function forgot_password($email) {
  // 1. Validate Email
  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    return 'Invalid email address.';
  }

  // 2. Check if user exists
  $user = getUserByEmail($email); // Function to retrieve user by email
  if (!$user) {
    return 'User not found.';
  }

  // 3. Generate a Unique Token
  $token = generate_unique_token();

  // 4. Store Token and User ID in a temporary table (or session)
  store_token_for_user($user['id'], $token);  // Function to store the token

  // 5.  Create a reset link
  $reset_link = generate_reset_link($token, $user['email']);

  // 6. Return the reset link to the user
  return $reset_link;
}

// ------------------------------------------------------------------
// Placeholder Functions (Implement these according to your setup)
// ------------------------------------------------------------------

// Function to retrieve user by email (replace with your database query)
function getUserByEmail($email) {
  // Example (using a dummy database - REPLACE with your actual query)
  // This is just a placeholder;  implement your actual database query here.
  $users = [
    ['id' => 1, 'email' => 'test@example.com', 'password' => 'hashed_password'],
    ['id' => 2, 'email' => 'another@example.com', 'password' => 'another_hashed_password']
  ];

  foreach ($users as $user) {
    if ($user['email'] === $email) {
      return $user;
    }
  }
  return null;
}


// Function to generate a unique token (UUID is generally a good choice)
function generate_unique_token() {
  return bin2hex(random_bytes(32)); // Generate a 32-byte UUID
}


// Function to store the token in a temporary table (or session)
function store_token_for_user($user_id, $token) {
  // In a real application, you would insert a record into a temporary table
  // with columns 'user_id' and 'token'.  This is just a placeholder.
  //  Example (using a dummy temporary table):
  //  $sql = "INSERT INTO reset_tokens (user_id, token) VALUES ($user_id, '$token')";
  //  // Execute the query here
  //  return true;

  //  For simplicity in this example, we'll just simulate it:
  echo "Simulating token storage for user ID: " . $user_id . " with token: " . $token . "
";
}


// Function to generate the reset link (including token and email)
function generate_reset_link($token, $email) {
  return "http://yourdomain.com/reset_password?token=" . urlencode($token) . "&email=" . urlencode($email);
}

// ------------------------------------------------------------------
// Example Usage
// ------------------------------------------------------------------

$email_to_reset = 'test@example.com';

$reset_link = forgot_password($email_to_reset);

if ($reset_link === 'Invalid email address.') {
  echo $reset_link . "
";
} elseif ($reset_link === 'User not found.') {
  echo $reset_link . "
";
} else {
  echo "Reset link: " . $reset_link . "
";
  //  Send the reset_link to the user via email or other means
}
?>


<?php

/**
 * Forgets a user's password.
 *
 * This function generates a unique token, sends an email with a link to reset the password,
 * and stores the token in the database.
 *
 * @param string $email The email address of the user.
 * @param string $baseUrl The base URL of your website.  Used for the reset link.
 * @return bool True if the email was sent successfully, false otherwise.
 */
function forgotPassword(string $email, string $baseUrl): bool
{
  // 1. Generate a Unique Token
  $token = bin2hex(random_bytes(32));

  // 2. Prepare the Reset Link
  $resetLink = $baseUrl . '/reset-password?token=' . $token;

  // 3. Prepare the Email Message
  $subject = "Password Reset Request";
  $message = "Click the following link to reset your password: " . $resetLink;
  $headers = "From: " .  $baseUrl . "\r
";
  $headers .= "Reply-To: " . $email . "\r
";

  // 4. Send the Email (using PHPMailer -  Install via Composer: `composer require phpmailer/phpmailer`)
  if (sendEmail($email, $subject, $message, $headers)) {
    // 5. Store the Token in the Database
    saveToken($email, $token);
    return true;
  } else {
    // Handle email sending failure -  Log it or show an error message
    error_log("Failed to send password reset email for " . $email);
    return false;
  }
}


/**
 * Placeholder for sending email (Replace with your actual email sending logic).
 *  This is a placeholder function.  You *must* implement this using a real email library.
 *  Example using PHPMailer:
 *  $mail = new PHPMailer\PHPMailer\PHPMailer();
 *  $mail->SMTPDebugEnable = true;
 *  // Configure SMTP settings (replace with your details)
 *  $mail->isSMTP();
 *  $mail->Host       = 'smtp.gmail.com';
 *  $mail->SMTPAuth   = true;
 *  $mail->Username   = 'your_email@gmail.com';
 *  $mail->Password   = 'your_password';
 *  $mail->Port = 587;
 *  $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
 *
 *  $mail->setFrom($email, $email);
 *  $mail->addAddress('user@example.com'); // Change to user's email
 *  $mail->Subject = $subject;
 *  $mail->Body    = $message;
 *
 *  if(!$mail->send()){
 *     return false;
 *  }
 *  return true;
 *
 * @param string $email       The email address.
 * @param string $subject     The email subject.
 * @param string $message     The email body.
 * @param string $headers     Email headers.
 * @return bool True on success, false on failure.
 */
function sendEmail(string $email, string $subject, string $message, string $headers): bool
{
  // Replace this with your actual email sending logic
  // For testing, you can simply echo the email details.
  error_log("Sending email: To: " . $email . "
Subject: " . $subject . "
Message: " . $message . "
Headers: " . $headers);
  return true; // Placeholder - Always return true for testing.  Implement real sending here.
}


/**
 * Placeholder for saving the token in the database.  Replace with your database logic.
 *
 * @param string $email       The user's email address.
 * @param string $token       The generated token.
 */
function saveToken(string $email, string $token): void
{
  // Placeholder - Replace with your database saving logic
  // Example (using a simple array - **NOT RECOMMENDED FOR PRODUCTION**):
  // $_SESSION['reset_token'] = $token;
  // This is just a demo - for a real application, use a database.

  // Example using database (using PDO):
  // $db = new PDO("mysql:host=localhost;dbname=your_database", "username", "password");
  // $stmt = $db->prepare("INSERT INTO password_resets (email, token, created_at) VALUES (?, ?, NOW())");
  // $stmt->execute([$email, $token]);
}

// Example Usage (for testing - do not use this in your application directly)
// In a real application, you'd get the email from a form submission.
// For this example, we're hardcoding the email.
$baseUrl = 'http://localhost/your-website';  // Replace with your website's base URL

// Simulate a user requesting a password reset
if (isset($_GET['email'])) {
    $email = $_GET['email'];
    if (forgotPassword($email, $baseUrl)) {
        echo "Password reset email has been sent to " . $email;
    } else {
        echo "Failed to send password reset email.";
    }
}
?>


<?php

// Ensure this file is protected (e.g., .htaccess or server configuration)
// to prevent direct access to the password reset file.

// Database connection details (replace with your actual credentials)
$host = 'localhost';
$username = 'your_db_username';
$password = 'your_db_password';
$database = 'your_db_name';

// Email configuration (replace with your email settings)
$from_email = 'your_email@example.com';
$to_email = '%user_email%'; // Use placeholder for user's email
$subject = 'Password Reset Request';
$headers = "From: $from_email\r
";
$headers .= "Reply-To: $from_email\r
";

// Generate a unique, secure token
function generate_token() {
    return bin2hex(random_bytes(32));
}

// Check if the request is valid
if (isset($_POST['email']) && isset($_POST['token'])) {
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL); // Sanitize email input
    $token = filter_var($_POST['token'], FILTER_SANITIZE_STRING); // Sanitize token input

    // Validate email format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "Invalid email format.";
        exit;
    }

    // Check if the token exists in the database (important security step)
    $result = mysqli_query($GLOBALS['database'], "SELECT id, email FROM users WHERE email = '$email' AND token = '$token'");

    if (mysqli_num_rows($result) > 0) {
        // Token exists, proceed with password reset
        $user_id = mysqli_fetch_assoc($result)['id'];
        $user_email = mysqli_fetch_assoc($result)['email'];

        //  Create a temporary password (strong password)
        $temp_password = 'P@$$wOrd'; // Example -  Use a stronger password generation method in a real app.
        //  Generate a unique token for the reset process
        $reset_token = generate_token();

        // Update the user's record with the temporary password and the new reset token
        mysqli_query($GLOBALS['database'], "UPDATE users SET password = '$temp_password', token = '$reset_token', password_reset_expiry = NOW() WHERE id = '$user_id'");

        // Send the password reset email
        $message = "Please use the following link to reset your password:
" .
                   '<a href="' . $_SERVER['REQUEST_URI'] . '?token=' . $reset_token . '">Reset Password</a>';  // Use the same URL for the reset link
        mail($to_email, $subject, $message, $headers);

        echo "Password reset link sent to your email.  Check your inbox.";

    } else {
        echo "Invalid token or user not found.";
    }
} else {
    echo "Invalid request.";
}

// Example usage (for testing - usually handled through a form)
//  This is just an example -  integrate this with a form to collect the email.
//  Implement proper error handling and security best practices.
?>


   function hash_password($password) {
       return password_hash($password, PASSWORD_DEFAULT);
   }
   
   Then, when the user creates an account, hash the password before storing it in the database.  When the user tries to log in, verify the entered password against the hashed value in the database.

3. **Database Abstraction Layer:**  For larger applications, using a database abstraction layer (like PDO or a specific database library) is *highly* recommended.  It provides a more secure and portable way to interact with the database.

4. **Rate Limiting:**  Implement rate limiting to prevent brute-force attacks.  Limit the number of password reset requests from a single IP address within a certain timeframe.

5. **CSRF Protection:** Implement CSRF (Cross-Site Request Forgery) protection to prevent attackers from forging requests to reset passwords.

6. **HTTPS:**  Always use HTTPS to encrypt the communication between the user's browser and the server, protecting the user's credentials during transmission.

7. **Error Logging:**  Implement error logging to track any errors or suspicious activity.

8. **Testing:**  Thoroughly test the password reset functionality to ensure it works as expected and is secure.

9. **Regular Security Audits:** Conduct regular security audits to identify and address any vulnerabilities.

10. **Session Management:** Implement proper session management to ensure user sessions are secure.

This improved code provides a more secure and robust password reset functionality. However, remember that security is an ongoing process. Implement the best practices mentioned above and stay up-to-date with the latest security threats.


<?php

// Assuming you have a database connection established (e.g., $db)

/**
 * Resets a user's password.
 *
 * @param string $email The user's email address.
 * @return bool True if a password reset email was sent successfully, false otherwise.
 */
function forgotPassword(string $email) {
    // Sanitize input - crucial for security
    $email = filter_var($email, FILTER_SANITIZE_EMAIL);

    // Validate email format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        error_log("Invalid email format provided.");
        return false;
    }


    // 1. Check if the user exists
    $user = getUserById($email); // Implement this function (see below)

    if (!$user) {
        error_log("User with email {$email} not found.");
        return false;
    }

    // 2. Generate a unique token
    $token = generateUniqueToken(); // Implement this function (see below)

    // 3. Store the token and expiry in the database (for the user)
    //    This is the key step.  You *must* secure this.
    updateUserToken($user['id'], $token, time() + (3600 * 24)); // Token valid for 24 hours

    // 4.  Send a password reset email
    $resetLink = generateResetLink($token); // Implement this function (see below)
    sendPasswordResetEmail($user['email'], $resetLink);

    return true;
}

/**
 * Placeholder function to get user by email.  Replace with your database query.
 *
 * @param string $email The user's email address.
 * @return array|null The user object or null if not found.
 */
function getUserById(string $email): ?array {
    // **IMPORTANT:  Replace this with your actual database query.**
    // This is just a placeholder to demonstrate the flow.
    // You'll use a query like:
    // $stmt = $db->prepare("SELECT * FROM users WHERE email = ?");
    // $stmt->execute([$email]);
    // $user = $stmt->fetch(PDO::FETCH_ASSOC);
    // return $user;

    // Example return for demonstration:
    return [
        'id' => 1,
        'email' => 'test@example.com',
        'password' => 'hashed_password' // This should be properly hashed
    ];
}


/**
 * Placeholder function to generate a unique token.
 *  Consider using a library for cryptographically secure random number generation.
 *
 * @return string A unique token.
 */
function generateUniqueToken(): string {
    return bin2hex(random_bytes(32)); // Use bin2hex for a hexadecimal representation
}


/**
 * Placeholder function to generate the password reset link.
 *
 * @param string $token The token.
 * @return string The password reset link.
 */
function generateResetLink(string $token): string {
    return 'https://yourwebsite.com/reset-password?token=' . urlencode($token); // Replace with your URL
}


/**
 * Placeholder function to send a password reset email.
 *
 * @param string $email The recipient's email address.
 * @param string $resetLink The password reset link.
 */
function sendPasswordResetEmail(string $email, string $resetLink): void {
    // Implement your email sending logic here.
    // This is just a placeholder.
    echo "Sending password reset email to: " . $email . " with link: " . $resetLink . "
";
    // Example using PHPMailer (you'll need to install it: composer require phpmailer/phpmailer)
    //  require_once 'PHPMailer/PHPMailerAutoload.php';
    //  $mail = new PHPMailer(true);
    //  $mail->isSMTP();
    //  $mail->Host       = 'smtp.example.com';
    //  $mail->SMTPAuth   = true;
    //  $mail->Username   = 'your_email@example.com';
    //  $mail->Password   = 'your_password';
    //  $mail->Port = 587;
    //  $mail->SetFrom('your_email@example.com', 'Your Website Name');
    //  $mail->Subject = 'Password Reset';
    //  $mail->Body    = 'Click this link to reset your password: ' . $resetLink;
    //  $mail->AltBody = $mail->Body;
    //  if ($mail->send()) {
    //      // Email sent successfully
    //  } else {
    //      error_log("Failed to send email.");
    //  }
}


/**
 * Placeholder function to update the user's token in the database.
 *  **IMPORTANT:**  This is where you'd insert or update the 'token' and 'expiry' columns
 *  in your user table.
 *
 * @param int $userId The user's ID.
 * @param string $token The token.
 * @param int $expiry Timestamp when the token expires.
 */
function updateUserToken(int $userId, string $token, int $expiry): void {
    // **IMPORTANT: Replace this with your actual database update query.**
    // Example (using PDO):
    // $stmt = $db->prepare("UPDATE users SET token = ?, expiry = ? WHERE id = ?");
    // $stmt->execute([$token, $expiry, $userId]);
}


// Example usage (call this function to initiate the process)
// $email = "test@example.com";
// if (forgotPassword($email)) {
//   echo "Password reset email sent to {$email}
";
// } else {
//   echo "Failed to send password reset email.
";
// }


?>


<?php

// Database connection details (replace with your actual credentials)
$db_host = 'localhost';
$db_name = 'your_database_name';
$db_user = 'your_username';
$db_password = 'your_password';

// Function to reset password
function forgot_password($email, $new_password, $db) {
    // 1. Validate Email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return "Invalid email format.";
    }

    // 2. Check if user exists
    $stmt = $db->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user) {
        return "User with this email does not exist.";
    }

    // 3. Hash the new password (Important for security!)
    $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

    // 4. Update the password in the database
    $stmt = $db->prepare("UPDATE users SET password = ? WHERE id = ?");
    $stmt->execute([$hashed_password, $user['id']]);

    return "Password reset email sent. Check your inbox!";
}


// --- Example Usage (Demonstration - NOT for direct use in a production environment)
// IMPORTANT:  This example shows how to use the function.  It's a simplified demonstration.
//   In a real application, you would typically have a form to collect the email and new password.

// Create a PDO database connection
try {
    $db = new PDO("mysql:host=$db_host;dbname=$db_name;charset=utf8mb4", $db_user, $db_password);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // Enable exception handling
} catch (PDOException $e) {
    echo "Database connection failed: " . $e->getMessage();
    exit;
}


// Example Usage (Replace with actual user input from a form)
$email = $_POST['email']; // Get email from form
$new_password = $_POST['new_password'];

// Call the forgot_password function
$result = forgot_password($email, $new_password, $db);

// Display the result
echo $result;

// Close the database connection (important!)
$db = null;

?>


<?php

// Assuming you have a database connection established and a 'users' table
// with a 'email' column and potentially a 'password' column.

function forgot_password($email, $reset_token, $expiry_time = 3600) { //default expiry of 1 hour

  // 1. Validate Email
  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    return "Invalid email address.";
  }

  // 2. Check if a reset token already exists for this email
  $query = "SELECT id, token, created_at FROM reset_tokens WHERE email = ? AND token = ? AND expiry_time > NOW()";
  $stmt = $conn->prepare($query);
  $stmt->bind_param("ss", $email, $reset_token);
  $stmt->execute();

  if ($stmt->num_rows > 0) {
    // Token exists, proceed with password reset
    //  Ideally, you'd update the token's expiry time here 
    //  to force a new reset link to be generated.  For simplicity, we'll just
    //  return the token.
    $result = $stmt->fetch_assoc();
    return $result['token']; // Or return the entire result array if needed
  } else {
    // Token does not exist
    return "Invalid reset token.  Please request a new one.";
  }
}



// Example Usage (assuming $conn is your database connection)
//  This is just for testing; in a real application, you would
//  handle the form submission and user interaction.

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $email = $_POST["email"];
  $reset_token = $_POST["reset_token"];

  $new_reset_token = forgot_password($email, $reset_token);

  if ($new_reset_token == "Invalid reset token.  Please request a new one.") {
    echo "<p style='color: red;'>$new_reset_token</p>";
  } else {
      echo "<p style='color: green;'>Reset token: $new_reset_token.  Please use this in the password reset form.</p>";
      // In a real application, you would send an email with this token.
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

  <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
    <label for="email">Email:</label>
    <input type="email" id="email" name="email" required><br><br>

    <!--  In a real application, you would generate a random token
         and store it in the database.  For this example, we'll
         just have the user enter a token. -->
    <label for="reset_token">Reset Token:</label>
    <input type="text" id="reset_token" name="reset_token" required><br><br>

    <button type="submit">Reset Password</button>
  </form>

</body>
</html>


<?php

/**
 * Forgot Password Function
 *
 * This function handles the forgot password process.  It:
 * 1. Generates a unique, time-based token.
 * 2. Sends an email to the user with a link containing the token.
 * 3. Stores the token in the database associated with the user's ID.
 * 4.  Returns a success message or an error message.
 *
 * @param string $email        The user's email address.
 * @param string $site_url    The URL of your website.
 * @param string $reset_token_prefix  (Optional) Prefix for the reset token.  Good for security.
 *
 * @return string  A success or error message.
 */
function forgot_password(string $email, string $site_url, string $reset_token_prefix = 'reset') {

    // 1. Generate a unique, time-based token
    $token = $reset_token_prefix . md5(time());

    // 2.  Check if the email exists in the database.  You'll need a database connection here!
    // Assuming you have a database connection variable called $db
    // $user = $db->query("SELECT id, email FROM users WHERE email = '$email' LIMIT 1")->fetch_assoc();

    // This is a placeholder.  Replace with your actual database query.
    // This example assumes you have a `users` table with `id` and `email` columns.
    $user = $db->query("SELECT id, email FROM users WHERE email = '$email' LIMIT 1")->fetch_assoc();
    
    if (!$user) {
        return "Email not found.";
    }

    // 3.  Store the token in the database
    $result = $db->query("UPDATE users SET reset_token = '$token', reset_token_expiry = NOW() + INTERVAL 1 HOUR WHERE id = $user['id']");

    if (!$result) {
        return "Error updating user information.";
    }

    // 4.  Create the reset password link
    $reset_link = $site_url . "/reset_password.php?token=$token";  // Example URL
    

    // 5.  Send the password reset email.  You'll need to implement this.
    // This is a placeholder - you'll need to replace this with your email sending logic.
    $subject = "Password Reset";
    $message = "Click on the following link to reset your password: " . $reset_link;
    
    // In a real application, you'd use a function like:
    // send_email($user['email'], $subject, $message); 

    return "Password reset email sent to $email";
}


// Example Usage (replace with your actual database connection and settings)
//  This is just an example, it doesn't actually connect to a database
// and won't work as is.

// Assume $db is your database connection object (e.g., mysqli_connect)
//$db = mysqli_connect("localhost", "username", "password", "database_name");

//if (!$db) {
//    die("Database connection failed");
// }


// $email = "test@example.com";
// $reset_message = forgot_password($email, "https://example.com");
// echo $reset_message;


?>


<?php

// Assuming you have a database connection established and a 'users' table with a 'email' column.

//  This is a basic example and should be adapted to your specific setup.
//  For production, you should always sanitize inputs, use prepared statements,
//  and implement proper security measures.

function forgot_password($email) {
  // 1. Check if the email exists in the database.
  $query = "SELECT id, password FROM users WHERE email = ?";
  $stmt = $conn->prepare($query); // Assuming $conn is your database connection object.
  $stmt->bind_param("s", $email);
  $stmt->execute();
  $result = $stmt->get_result();

  if ($result->num_rows == 0) {
    // Email not found
    $stmt->close();
    return false;
  }

  $user = $result->fetch_assoc();
  $stmt->close();

  // 2. Generate a unique, secure token.  Important for security!
  $token = bin2hex(random_bytes(32)); // More secure than simple rand()

  // 3.  Create a password reset link.
  $reset_link = "http://yourdomain.com/reset_password?token=" . $token . "&user_id=" . $user['id'];  // Replace with your domain.
                                   //  This URL should be safe and not expose sensitive data.

  // 4.  Store the token and user ID in the database.
  $query = "INSERT INTO password_resets (user_id, token, expiry) VALUES (?, ?, ?)";
  $stmt = $conn->prepare($query);
  $stmt->bind_param("sss", $user['id'], $token, date("Y-m-d H:i:s", time() + 3600)); // Expire after 1 hour.  Adjust as needed.
  $result = $stmt->execute();

  if (!$result) {
      // Handle errors - log them, display a message, etc.  Don't just silently fail.
      error_log("Error creating password reset entry: " . error_get_last()['message']);
      return false;
  }


  // 5.  Send an email to the user with the reset link.
  $to = $email;
  $subject = "Password Reset";
  $message = "Click on the link below to reset your password:
" . $reset_link;
  $headers = "From: your_email@example.com\r
";
  mail($to, $subject, $message, $headers);  // Use a robust mailer library instead of mail() for production.


  return true;
}


// Example usage (after a user submits the forgot password form)
// $email = $_POST['email'];  // Get email from the form.
// if (forgot_password($email)) {
//   echo "Password reset email has been sent to " . $email;
// } else {
//   echo "Failed to generate password reset link.";
// }


?>


<?php

// Assuming you have a database connection established (e.g., $db)
// and have a 'users' table with a 'email' field.

/**
 * Resets a user's password.
 *
 * @param string $email The user's email address.
 * @return bool True if password reset email sent successfully, false otherwise.
 */
function forgot_password(string $email): bool
{
  // 1. Validate Email
  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    error_log("Invalid email format provided.");
    return false;
  }

  // 2. Check if the user exists
  $user = get_user_by_email($email); // Assuming this function exists - defined below

  if ($user === null) {
    error_log("User with email $email not found.");
    return false;
  }

  // 3. Generate a unique token
  $token = generate_unique_token();

  // 4. Store the token in the database associated with the user
  $result = store_token_for_user($user['id'], $token);

  if (!$result) {
    error_log("Failed to store token for user $email.");
    return false;
  }

  // 5. Send the password reset email
  $subject = "Password Reset Request";
  $message = "Click on this link to reset your password: " .  $_SERVER['PHP_SELF'] . "?token=" . urlencode($token); //  IMPORTANT: Security Considerations below!
  $headers = "From: your_email@example.com\r
";

  if (send_email($email, $subject, $message, $headers) ) {
    return true;
  } else {
    error_log("Failed to send password reset email to $email.");
    // Consider deleting the token if email sending fails.
    delete_token_for_user($user['id']); // Rollback
    return false;
  }
}


//  Dummy functions for demonstration - Replace with your actual implementations

/**
 * Retrieves a user from the database based on their email.
 *
 * @param string $email The user's email address.
 * @return null|array  An associative array representing the user, or null if not found.
 */
function get_user_by_email(string $email): ?array
{
  // Replace this with your actual database query
  // This is a placeholder - use your database connection
  $users = [
    ['id' => 1, 'email' => 'test@example.com'],
    ['id' => 2, 'email' => 'another@example.com']
  ];

  foreach ($users as $user) {
    if ($user['email'] === $email) {
      return $user;
    }
  }
  return null;
}


/**
 * Generates a unique token for password reset.
 *
 * @return string A unique token.
 */
function generate_unique_token(): string
{
    return bin2hex(random_bytes(32)); // Recommended for security
}


/**
 * Stores a token for a user in the database.
 *
 * @param int $userId The ID of the user.
 * @param string $token The token to store.
 * @return bool True if the token was successfully stored, false otherwise.
 */
function store_token_for_user(int $userId, string $token): bool
{
  // Replace this with your actual database insertion logic
  // Example:
  // $query = "INSERT INTO password_tokens (user_id, token, expires_at) VALUES ($userId, '$token', NOW() + INTERVAL 30 MINUTE)";
  // Execute the query and check the result.

  // Placeholder:
  return true;
}


/**
 * Deletes a token for a user from the database.
 *
 * @param int $userId The ID of the user.
 * @return bool True if the token was successfully deleted, false otherwise.
 */
function delete_token_for_user(int $userId): bool
{
  // Replace this with your actual database query
  // Example:
  // $query = "DELETE FROM password_tokens WHERE user_id = $userId";
  // Execute the query and check the result.

  // Placeholder:
  return true;
}


/**
 * Sends an email.
 *
 * @param string $to The recipient's email address.
 * @param string $subject The email subject.
 * @param string $body The email body.
 * @param string $headers Email headers.
 * @return bool True if the email was sent successfully, false otherwise.
 */
function send_email(string $to, string $subject, string $body, string $headers): bool
{
    // Replace this with your actual email sending implementation
    // (e.g., using PHP's mail() function or a library like PHPMailer)

    // Example (using the mail() function - be aware of limitations and security):
    // return mail($to, $subject, $body, $headers);

    // Placeholder - always return true to prevent errors during testing
    return true;
}


// Example Usage (demonstration)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $email = $_POST['email'];
  if (isset($email) && forgot_password($email)) {
    echo "Password reset email sent to " . $email;
  } else {
    echo "Failed to reset password.";
  }
}
?>

<!-- HTML Form for the Password Reset Request -->
<form method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">
  <label for="email">Email:</label>
  <input type="email" id="email" name="email" required>
  <button type="submit">Request Password Reset</button>
</form>


<?php

// Ensure this file is protected to prevent direct access to the database.
// You should also use a more secure method for generating and sending
// the reset token (e.g., using a library for cryptographically secure random numbers).

// Database connection details (Replace with your actual credentials)
$db_host = "localhost";
$db_name = "your_database_name";
$db_user = "your_db_user";
$db_password = "your_db_password";

// Function to generate a unique token
function generateUniqueToken() {
    return bin2hex(random_bytes(32)); // Generates a 32-byte (256-bit) random binary string
}

// Function to send the reset password email (Requires an email setup)
function sendResetPasswordEmail($email, $token) {
    // Implement your email sending logic here.
    // This is just a placeholder.  You'll need to use a library or
    // your email server to actually send the email.

    // Example (replace with your email sending code):
    $subject = "Password Reset";
    $message = "Click on the following link to reset your password: " . "<a href='" . $_SERVER["PHP_SELF"] . "?reset=true&token=" . $token . "'>Reset Password</a>";

    // In a real application, you'd use a library like PHPMailer or SwiftMailer.
    // Example using PHPMailer (requires installation):
    // $mail = new PHPMailer(true);
    // $mail->isSMTP();
    // $mail->Host = 'smtp.example.com';
    // $mail->Port = 587;
    // $mail->Username = 'your_email@example.com';
    // $mail->Password = 'your_password';
    // $mail->SetFrom('your_email@example.com', 'Your Application Name');
    // $mail->Subject = $subject;
    // $mail->Body = $message;
    // $mail->AltBody = $message;  // For email clients that don't support HTML
    // $mail->Send();

    echo "Reset email sent to $email.  Check your inbox!"; // Placeholder
}


// Function to reset the password
function resetPassword($email, $token) {
    // 1. Verify the token
    $query = "SELECT * FROM users WHERE email = '$email' AND token = '$token'";
    $result = mysqli_query($GLOBALS['db_conn'], $query);

    if (mysqli_num_rows($result) > 0) {
        // Token is valid, proceed with password reset

        // 2.  Generate a new, strong password (example)
        $new_password = generateStrongPassword(); // Replace with your password generation function
        $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

        // 3.  Update the user's password in the database
        $update_query = "UPDATE users SET password = '$hashed_password', token = '', last_reset = NOW() WHERE email = '$email'";
        mysqli_query($GLOBALS['db_conn'], $update_query);

        echo "Password reset successful! Please check your email for instructions.";

    } else {
        echo "Invalid token or user not found.";
    }
}


//  ------------------  Example Usage (Simplified for demonstration)  ------------------

// Check if the reset link is clicked
if (isset($_GET['reset']) && isset($_GET['token'])) {
    $email = $_GET['email']; // Potentially add validation here
    $token = $_GET['token'];

    // Validate email (simple example - improve this!)
    if (empty($email) || empty($token)) {
        echo "Error: Email and token are required.";
    } else {
        resetPassword($email, $token);
    }
}

//  ------------------  Database Connection  ------------------
$GLOBALS['db_conn'] = mysqli_connect($db_host, $db_user, $db_password, $db_name);

if (mysqli_connect_error()) {
    die("Connection failed: " . mysqli_connect_error());
}

// Add other necessary database setup/validation here, such as checking if the user exists

?>


<?php

// Assuming you have a database connection established and a 'users' table with an 'email' field
// and a 'password' field.  This example uses a simple username/password approach,
// but in a real application, you'd likely use hashing and salting for security.

// Database connection details - Replace with your actual values
$db_host = 'localhost';
$db_name = 'your_database_name';
$db_user = 'your_username';
$db_password = 'your_password';

/**
 * Resets a user's password using email.
 *
 * @param string $email The email address of the user.
 * @return string  A message indicating success or failure.
 */
function forgot_password($email) {
    // Validate email format (basic check - improve for production)
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return "Invalid email address.";
    }

    // Check if the user exists
    $stmt = $GLOBALS['conn']->prepare("SELECT id, username FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 0) {
        $stmt->close();
        return "User not found.";
    }

    $user = $result->fetch_assoc();
    $stmt->close();

    // Generate a temporary password (for demo purposes - use a cryptographically secure method in production)
    $temp_password = 'TempPassword123';  // Replace with a more robust method

    // Send the password reset email (This is a placeholder - implement your email sending logic here)
    $to = $email;
    $subject = 'Password Reset';
    $message = "To reset your password, please click on this link: " .  $_SERVER['PHP_SELF'] . "?reset=" . urlencode($temp_password);
    $headers = "From: your_email@example.com" . "\r
";

    if (mail($to, $subject, $message, $headers)) {
        return "Password reset link sent to your email address.  Check your inbox.";
    } else {
        return "Failed to send password reset email. Please try again.";
    }
}

// Example Usage (this part would typically be from a form submission)
if ($_SERVER["REQUEST_METHOD"] == "GET") {
    // Get the email from the URL parameter (for testing purposes)
    $email = $_GET["email"];

    // Call the forgot_password function
    $result = forgot_password($email);

    // Display the result
    echo "<p>" . $result . "</p>";
} else {
    // Handle POST request (if the form is submitted)
    $email = $_POST["email"];
    $result = forgot_password($email);
    echo "<p>" . $result . "</p>";
}

?>

<!DOCTYPE html>
<html>
<head>
    <title>Forgot Password</title>
</head>
<body>

    <h1>Forgot Password</h1>

    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required>
        <button type="submit">Reset Password</button>
    </form>

</body>
</html>


<?php

// Database connection details (Replace with your actual details)
$host = "localhost";
$username = "your_username";
$password = "your_password";
$database = "your_database_name";

// Function to reset password
function forgot_password($email, $password_reset_token, $expiration_time_seconds = 60*60*24) { // Default 24 hours
  // 1. Database connection
  $conn = new mysqli($host, $username, $password, $database);

  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  // 2. Prepare the SQL query
  $sql = "UPDATE users SET password = ? , password_reset_token = ? WHERE email = ?";

  // 3. Prepare the statement
  $stmt = $conn->prepare($sql);

  if ($stmt === false) {
    die("Error preparing statement: " . $conn->error);
  }

  // 4. Bind the parameters
  $new_password = "NewSecurePassword123!"; // A placeholder -  Never directly insert a user-provided password.
  $reset_token = $password_reset_token;
  $email = $email;

  $stmt->bind_param("ssi", $new_password, $reset_token, $email);

  // 5. Execute the query
  if (!$stmt->execute()) {
    die("Error executing query: " . $stmt->error);
  }

  // 6. Close the statement and connection
  $stmt->close();
  $conn->close();

  // 7.  Consider sending an email with the link to reset the password
  //     (This part is not included in this basic function but is *essential*
  //     for a real-world implementation)
  return true; // Indicate success
}


// Example Usage (for demonstration - NOT recommended for production)
// IMPORTANT:  This is just an example.  You should *never* directly
//  use user-provided data in a real application without proper sanitization and validation.
//  In a real application, you would generate a unique and secure token.
//  Also, you would send an email to the user with a link containing the token.

// $user_email = "test@example.com"; // Replace with a valid email address
// $reset_token = "unique_token_123";  // Generate a unique token -  Crucial for security
//
// if (forgot_password($user_email, $reset_token)) {
//   echo "Password reset link sent to " . $user_email;
// } else {
//   echo "Failed to reset password.";
// }


?>


<?php

// This is a simplified example for demonstration purposes.
// In a production environment, you should:
// 1.  Securely store passwords (e.g., hash them with bcrypt or Argon2).
// 2.  Implement rate limiting to prevent brute-force attacks.
// 3.  Use email libraries to send actual emails instead of just printing to the screen.
// 4.  Handle errors gracefully and securely.


// Configuration (replace with your database details)
$db_host = "localhost";
$db_name = "your_database_name";
$db_user = "your_db_user";
$db_password = "your_db_password";

// Function to reset password
function forgot_password($email, $new_password, $db) {
  // 1. Validate Email (Basic)
  if (empty($email)) {
    return "Invalid email address.";
  }

  // 2.  Check if user exists (Simple check - improve with more robust validation)
  $result = $db->query("SELECT id, email FROM users WHERE email = '$email'");
  if ($result->num_rows == 0) {
    return "User not found.";
  }

  $user_data = $result->fetch_assoc();
  $user_id = $user_data['id'];
  $user_email = $user_data['email'];

  // 3. Hash the New Password (Crucial for Security!)
  $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

  // 4. Update the Password in the Database
  $sql = "UPDATE users SET password = '$hashed_password' WHERE id = '$user_id'";
  if (!$db->query($sql)) {
    return "Error updating password. " . $db->error;
  }

  return "Password reset email sent. Check your inbox.";
}


// Example Usage (Simulate a form submission)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $email = $_POST["email"];
  $new_password = $_POST["new_password"];

  // Database Connection (replace with your connection logic)
  $db = new mysqli($db_host, $db_user, $db_password, $db_name);

  if ($db->connect_error) {
    die("Connection failed: " . $db->connect_error);
  }

  $result = forgot_password($email, $new_password, $db);
  echo $result; // Output the result to the user
} else {
  // Display the form (for demonstration)
  ?>
  <form method="post" action="">
    <label for="email">Email:</label>
    <input type="email" id="email" name="email" required><br><br>

    <label for="new_password">New Password:</label>
    <input type="password" id="new_password" name="new_password" required><br><br>

    <button type="submit">Reset Password</button>
  </form>
  <?php
}
?>


<?php

// Replace with your database connection details
$db_host = "localhost";
$db_name = "your_database_name";
$db_user = "your_username";
$db_password = "your_password";

// Check if the request method is POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {

  // Get the email address from the form
  $email = $_POST["email"];

  // Validate the email address (basic validation - you should improve this)
  if (empty($email)) {
    $message = "Email address is required.";
  } else {
    // Hash the password (important for security)
    $hashed_password = password_hash("default_password", PASSWORD_DEFAULT);  // You'll need a default password for this example

    // Prepare the SQL query
    $sql = "SELECT id, email FROM users WHERE email = '$email'";

    // Execute the query
    $result = mysqli_query($db_connection, $sql);

    // Check if the query was successful
    if ($result) {
      // Check if any user was found
      if (mysqli_num_rows($result) > 0) {
        // Set the password reset token (a unique, random string)
        $reset_token = bin2hex(random_bytes(32));

        // Prepare the update query
        $update_query = "UPDATE users SET reset_token = '$reset_token' WHERE email = '$email'";

        // Execute the update query
        mysqli_query($db_connection, $update_query);

        // Send an email to the user with the reset link
        $to = $email;
        $subject = "Password Reset";
        $message = "Click on the following link to reset your password: " . "<a href='reset_password.php?token=$reset_token'>Reset Password</a>";
        $headers = "From: your_email@example.com";  // Change this to your email

        mail($to, $message, $headers);


        $message = "Password reset link has been sent to your email address.";
      } else {
        $message = "No user found with this email address.";
      }
    } else {
      $message = "Error querying the database.";
    }
  }
}

// Start the session
session_start();

// Display any error messages
if (isset($message)) {
  echo "<p style='color: red;'>$message</p>";
}

?>

<!-- HTML Form -->
<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
  <label for="email">Email Address:</label>
  <input type="email" id="email" name="email" placeholder="Your Email" required>
  <button type="submit">Reset Password</button>
</form>


<?php

// Assuming you have a database connection established (e.g., $db connection)

/**
 * Resets a user's password.
 *
 * @param string $email The user's email address.
 * @return bool True if password reset initiated successfully, false otherwise.
 */
function forgot_password(string $email): bool
{
  // 1. Validate Email
  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    error_log("Invalid email format provided: " . $email);  // Log the invalid email for debugging
    return false;
  }

  // 2. Check if User Exists
  $user = get_user_by_email($email); // Implement this function (see example below)

  if ($user === null) {
    error_log("User with email " . $email . " not found."); // Log the user not found
    return false;
  }


  // 3. Generate a Unique Token (Important for security!)
  $token = generate_unique_token(); // Implement this function (see example below)


  // 4. Store Token and User ID in Database (Temporary)
  //    This is a temporary link - don't store sensitive information directly.
  $result = store_token_for_user($user['id'], $token);

  if (!$result) {
      error_log("Failed to store token for user " . $email);
      return false;
  }


  // 5. Send Password Reset Email
  $subject = "Password Reset Request";
  $message = "Click this link to reset your password: " .  $_SERVER['REQUEST_SCHEME'] . "://" . $_SERVER['HTTP_HOST'] . "/reset_password?token=" . $token;
  $headers = "From: your_website@example.com\r
";
  $sent = send_email($email, $subject, $message, $headers);

  if (!$sent) {
      error_log("Failed to send password reset email to " . $email);
      // Optionally, you could delete the token from the database if email sending fails.
      // delete_token_for_user($user['id']);
      return false;
  }


  return true;
}



/**
 * Helper function to get user by email (Placeholder - Implement your database logic here)
 *
 * @param string $email The email address to search for.
 * @return array|null An array containing user data if found, or null if not.
 */
function get_user_by_email(string $email): ?array {
    // **Replace this with your actual database query**
    // Example:
    // $query = "SELECT * FROM users WHERE email = '$email'";
    // $result = mysqli_query($db, $query); // Or use PDO or your database driver
    // if (mysqli_num_rows($result) > 0) {
    //   $user = mysqli_fetch_assoc($result);
    //   return $user;
    // } else {
    //   return null;
    // }

  // Placeholder for demonstration:
  $users = [
    ['id' => 1, 'email' => 'test@example.com']
  ];
  foreach($users as $user){
    if($user['email'] == $email){
      return $user;
    }
  }
  return null;
}



/**
 * Helper function to generate a unique token.
 * This should be a cryptographically secure random string.
 *
 * @return string A unique token.
 */
function generate_unique_token(): string {
    return bin2hex(random_bytes(32)); // More secure than rand()
}

/**
 * Helper function to store the token for a user (implementation depends on your database)
 *
 * @param int $userId The ID of the user.
 * @param string $token The token to store.
 * @return bool True if token stored successfully, false otherwise.
 */
function store_token_for_user(int $userId, string $token): bool {
    // **Replace this with your actual database logic**
    // Example using mysqli:
    // $query = "INSERT INTO password_tokens (user_id, token, expiry_date) VALUES ($userId, '$token', NOW() + INTERVAL 30 DAY)";
    // if (mysqli_query($db, $query) === false) {
    //   return false;
    // }
    return true;
}



/**
 * Helper function to delete the token from the database.
 * (Optional - For added security.  Consider if the token is short-lived)
 * @param int $userId
 * @return bool
 */
function delete_token_for_user(int $userId): bool {
    // Implement your database logic to delete the token.
    // Example:
    // $query = "DELETE FROM password_tokens WHERE user_id = $userId";
    // if (mysqli_query($db, $query) === false) {
    //   return false;
    // }
    return true;
}



/**
 * Placeholder function to send an email.
 * You'll need to integrate with your email sending service here.
 * @param string $to
 * @param string $subject
 * @param string $message
 * @param string $headers
 * @return bool
 */
function send_email(string $to, string $subject, string $message, string $headers): bool {
    // **Replace this with your actual email sending logic**
    // Example using a basic SMTP implementation (not recommended for production):
    // $smtp = new PHPMailer();
    // $smtp->SMTPDebugEnable = false;
    // $smtp->Host = 'smtp.example.com';
    // $smtp->Port = 587;
    // $smtp->Username = 'your_username';
    // $smtp->Password = 'your_password';
    // $smtp->SetFrom('your_website@example.com', 'Your Website');
    // $smtp->AddAddress($to);
    // $smtp->Subject = $subject;
    // $smtp->MsgBody = $message, 'html';
    // $smtp->IsHTML(true);
    // $smtp->Send();

    return true; // Placeholder - always returns true
}


<?php

// Database connection details (Replace with your actual credentials)
$db_host = 'localhost';
$db_name = 'your_database_name';
$db_user = 'your_database_username';
$db_password = 'your_database_password';

//  ---  Function to reset password  ---
function forgot_password($email, $new_password, $db) {
    global $db_host, $db_name, $db_user, $db_password;

    // 1. Verify Email Exists
    $stmt = $db->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 0) {
        return "Email not found.";
    }

    $user_id = $result->fetch_assoc()['id'];

    // 2.  Hash the New Password
    $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);


    // 3. Update Password in Database
    $stmt = $db->prepare("UPDATE users SET password = ? WHERE id = ?");
    $stmt->bind_param("ss", $hashed_password, $user_id);

    if ($stmt->execute() === false) {
        return "Failed to update password. Database error: " . $stmt->error;
    }
    
    $stmt->close();

    return "Password reset email sent.";

}


// Example Usage (Illustrative - Replace with your actual form handling)
// This is just for demonstration; you should implement proper form handling
// for security (CSRF protection, input validation, etc.)

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST["email"];
    $new_password = $_POST["new_password"];

    //  Database Connection (Establish Connection - crucial for any database operations)
    global $db_host, $db_name, $db_user, $db_password;

    $db = new mysqli($db_host, $db_user, $db_password, $db_name);

    if ($db->connect_error) {
        die("Connection failed: " . $db->connect_error);
    }


    $result = forgot_password($email, $new_password, $db);
    echo $result; // Output:  "Password reset email sent." or an error message
    $db->close();
}

?>

<!DOCTYPE html>
<html>
<head>
    <title>Forgot Password</title>
</head>
<body>

    <h1>Forgot Password</h1>

    <form method="post" action="">
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required><br><br>

        <label for="new_password">New Password:</label>
        <input type="password" id="new_password" name="new_password" required><br><br>

        <button type="submit">Reset Password</button>
    </form>

</body>
</html>


<?php

// Assuming you have a database connection established (e.g., $db connection)

/**
 * Resets a user's password based on their email address.
 *
 * @param string $email The user's email address.
 * @return bool True if a reset link was sent, false otherwise.
 */
function forgot_password(string $email)
{
    // 1. Validate Email (Important!)
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        error_log("Invalid email address provided: " . $email); // Log for debugging
        return false;
    }

    // 2. Check if the user exists
    $user = get_user_by_email($email);  //  Implement this function (see example below)
    if (!$user) {
        error_log("User with email " . $email . " not found.");
        return false;
    }

    // 3. Generate a Unique Token and Store it in the Database
    $token = generate_unique_token();
    $result = $db->query("UPDATE users SET reset_token = '$token' WHERE email = '$email'");

    if ($result === false) {
        error_log("Error updating user's reset token: " . $db->error);
        return false;
    }

    // 4. Send the Password Reset Email
    $subject = "Password Reset Request";
    $message = "Please click on the following link to reset your password: <a href='" . $_SERVER['PHP_SELF'] . "?reset_token=$token'>Reset Password</a>";
    $headers = "From: your_email@example.com";  // Replace with your email
    $sent = send_email($email, $subject, $message, $headers);


    if (!$sent) {
        // Handle email sending failure (e.g., log the error, try again later)
        error_log("Failed to send password reset email to " . $email);
        //Optionally, you could try to manually trigger a retry.
        return false;
    }

    return true;
}


/**
 * Example function to retrieve user data by email (Replace with your actual database query)
 * This is a placeholder and needs to be adapted to your database schema.
 *
 * @param string $email The email address to search for.
 * @return array|null An associative array representing the user data, or null if not found.
 */
function get_user_by_email(string $email): ?array
{
    $query = "SELECT * FROM users WHERE email = '$email'";
    $result = $db->query($query);

    if ($result->num_rows > 0) {
        return $result->fetch_assoc();
    }

    return null;
}


/**
 * Generates a unique token for password resets.  Should be cryptographically secure.
 *
 * @return string A unique token.
 */
function generate_unique_token(): string
{
    return bin2hex(random_bytes(32)); // Uses a cryptographically secure random number generator.
}



// Example Usage (Demonstration - Don't use in production without proper validation)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST["email"];
    if (forgot_password($email)) {
        echo "Password reset email has been sent to " . $email;
    } else {
        echo "Failed to send password reset email.";
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

    <form method="post" action="">
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required>
        <button type="submit">Reset Password</button>
    </form>

</body>
</html>


<?php

// Database configuration (Replace with your actual values)
$db_host = 'localhost';
$db_user = 'your_username';
$db_pass = 'your_password';
$db_name = 'your_database_name';

// Function to handle the forgot password process
function forgot_password($email) {
  // 1. Validate Email
  $email = filter_var($email, FILTER_SANITIZE_EMAIL);  // Sanitize input
  if(empty($email)){
    return "Invalid email address.";
  }


  // 2.  Check if User Exists
  $conn = new mysqli($db_host, $db_user, $db_pass, $db_name);
  if ($conn->connect_error) {
    return "Connection failed: " . $conn->connect_error;
  }

  $sql = "SELECT id, email FROM users WHERE email = '$email'";
  $result = $conn->query($sql);

  if ($result->num_rows > 0) {
    $user_id = $result->fetch_assoc()['id'];
    $user_email = $result->fetch_assoc()['email'];

    // 3. Generate a Unique Token
    $token = bin2hex(random_bytes(32)); // Generate a cryptographically secure token

    // 4.  Store the Token in the Database (Temporary)
    $sql = "INSERT INTO password_resets (user_id, token, expires_at) VALUES ($user_id, '$token', NOW() + INTERVAL 24 HOUR)"; // Expires after 24 hours

    if ($conn->query($sql) === TRUE) {
      // 5.  Send the Reset Link (Email) -  Implement this part
      $to = $email;
      $subject = 'Password Reset Link';
      $message = "Click on the following link to reset your password: " . "<a href='" . $_SERVER['PHP_SELF'] . "?reset=$token'>" . $_SERVER['PHP_SELF'] . "?reset=$token</a>";  // Use PHP_SELF to ensure correct link
      $headers = "From: your_email@example.com"; // Replace with your email
      mail($to, $message, $headers);

      return "Password reset link sent to your email. Please check your inbox.";
    } else {
      return "Error inserting token into database: " . $conn->error;
    }
  } else {
    return "User not found with email: $email";
  }

  $conn->close();
}

// Example Usage (for testing - typically handled through a form)
if ($_SERVER["REQUEST_METHOD"] == "GET") {
  if (isset($_GET['reset'])) {
    $reset_token = $_GET['reset'];
    $result = forgot_password($reset_token);
    echo $result;
  } else {
     echo "Enter email to reset your password.";
  }
}
?>


// Delete the token after reset is confirmed
$sql = "DELETE FROM password_resets WHERE token = '$token' AND user_id = $user_id";
if ($conn->query($sql) === TRUE) {
    // Token deleted successfully
}


<?php

/**
 * Forgets a user's password.
 *
 * This function generates a unique, secure token, sends an email with a
 * reset link, and updates the user's password in the database.
 *
 * @param string $email The user's email address.
 * @param PDO $pdo  The PDO database connection object.
 * @return bool True if the password reset process started successfully,
 *             false otherwise (e.g., email not sent).
 */
function forgot_password(string $email, PDO $pdo) {
    // 1. Generate a secure, unique token.  Use a strong random string.
    $token = bin2hex(random_bytes(32));

    // 2. Prepare the reset token and user ID for the database.
    $sql = "SELECT id FROM users WHERE email = :email";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':email', $email, PDO::PARAM_STR);
    $stmt->execute();

    $user_id = null;
    if ($stmt->fetch(PDO::FETCH_ASSOC)) {
        $user_id = $stmt->fetch(PDO::FETCH_ASSOC)['id'];
    }

    if (!$user_id) {
        return false; // User not found
    }

    // 3.  Store the token in the database, linked to the user.
    $sql = "INSERT INTO password_resets (user_id, token, expires_at)
            VALUES (:user_id, :token, :expires_at)";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $stmt->bindParam(':token', $token, PDO::PARAM_STR);
    $expires_at = time() + (2 * 60 * 60); // Expires in 2 hours
    $stmt->bindParam(':expires_at', $expires_at, PDO::PARAM_INT);
    $stmt->execute();



    // 4.  Send the password reset email.
    $to = $email;
    $subject = 'Password Reset';
    $message = "Click this link to reset your password: " . '<a href="' . $_SERVER['PHP_SELF'] . '?reset=' . $token . '"' . ' >Reset Password</a>';
    $headers = 'From: your_email@example.com' . "\r
";

    if (mail($to, $subject, $message, $headers)) {
        return true;
    } else {
        // Log an error if the email fails to send
        error_log("Failed to send email for password reset: " . $email);
        return false;
    }
}


// Example Usage (Illustrative -  Don't include this directly in your main application code!)
// To use this, you'd typically have it called from a form submission handler.
// This example shows how you *would* call it, but this is just for demonstration.

//  This is just an example. Replace with your actual database connection.
/*
$pdo = new PDO("mysql:host=localhost;dbname=your_database_name", "your_username", "your_password");

if (isset($_POST['email'])) {
    $email = $_POST['email'];
    if (forgot_password($email, $pdo)) {
        echo "Password reset email has been sent to $email.";
    } else {
        echo "Failed to initiate password reset.";
    }
}
*/
?>


<?php

/**
 *  Forgot Password Function
 *
 *  This function sends an email with a password reset link to the user's registered email address.
 *
 *  @param string $email The email address of the user.
 *  @return bool True if the email was sent successfully, false otherwise.
 */
function forgot_password(string $email): bool
{
    // 1. Validate Email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        error_log("Invalid email address provided: " . $email); // Log invalid email
        return false;
    }

    // 2. Check if the user exists
    $user = get_user_by_email($email);  // Assumes you have this function
    if (!$user) {
        error_log("User with email " . $email . " not found.");
        return false;
    }

    // 3. Generate a unique token and expiration time
    $token = generate_unique_token();
    $expiration_time = time() + (2 * 60 * 60); // Token valid for 2 hours. Adjust as needed.
    
    // 4. Store token and expiration time in the database
    if (!store_token_in_database($user->id, $token, $expiration_time)) {
      error_log("Failed to store token in database for user " . $email);
      return false;
    }
    
    // 5. Build the reset link
    $reset_link = app_url() . "/reset_password?token=" . urlencode($token); // Use app_url() or your base URL

    // 6. Send the reset email
    if (!send_reset_password_email($email, $reset_link)) {
        error_log("Failed to send reset password email to " . $email);
        // You might want to handle this failure differently (e.g., display an error message)
        return false;
    }

    return true;
}


/**
 * Placeholder functions - Replace with your actual implementations.
 */
function get_user_by_email(string $email): ?User // Assuming User is your user model
{
    // Replace with your database query to retrieve the user by email
    // Example:
    // $result = mysqli_query($conn, "SELECT * FROM users WHERE email = '$email'");
    // if (mysqli_num_rows($result) > 0) {
    //    $user = new User();
    //    $user->load($result->fetch_assoc());
    //    return $user;
    // }
    // return null;

    // Dummy User class
    class User {
        public $id;
        public $email;
        public function load($data) {
            $this->id = $data['id'];
            $this->email = $data['email'];
        }
    }

    return null;
}

function generate_unique_token(): string
{
    return bin2hex(random_bytes(32)); // Generate a cryptographically secure random token
}

function store_token_in_database(int $user_id, string $token, int $expiration_time): bool
{
  // Replace with your database query to store the token and expiration time
  // Example:
  // $result = mysqli_query($conn, "INSERT INTO password_tokens (user_id, token, expiration_time) VALUES ($user_id, '$token', $expiration_time)");
  // return mysqli_affected_rows($conn) > 0;

  return true; //Dummy implementation
}

function urlencode($string) {
  // Use built-in urlencode function for portability
  return urlencode($string);
}

function send_reset_password_email(string $email, string $reset_link): bool
{
    // Replace with your email sending logic
    // Example:
    // $subject = "Password Reset";
    // $body = "Click the following link to reset your password: " . $reset_link;
    // $headers = "From: your_email@example.com\r
";
    // return mail($email, $subject, $body, $headers);
    return true; //Dummy implementation
}

function app_url() {
    // Return your application's base URL
    return "http://localhost/your_app/";
}
?>


<?php

// Assuming you have a database connection established
// $db = new PDO(...);  // Replace with your database connection

// **Important: Replace these with your actual values**
$table_name = 'users'; // Table name where user accounts are stored
$email_column = 'email';
$password_column = 'password';
$reset_token_column = 'reset_token';
$expiry_time = 60 * 60 * 24; // Expire reset token after 24 hours (seconds)
$secret_key = 'your_secret_key_here'; // A strong, random secret key

/**
 * Generates a unique reset token.
 *
 * @return string Unique reset token.
 */
function generateUniqueToken() {
  return bin2hex(random_bytes(32));
}


/**
 * Creates a reset token for a user.
 *
 * @param PDO $db  Database connection.
 * @param string $email  User's email.
 * @return bool True on success, false on failure.
 */
function createResetToken(PDO $db, string $email) {
  $token = generateUniqueToken();

  $stmt = $db->prepare("SELECT id FROM {$table_name} WHERE {$email_column} = :email");
  $stmt->bindParam(':email', $email, PDO::PARAM_STR);
  $stmt->execute();

  $user_id = $stmt->fetch(PDO::FETCH_ASSOC);

  if ($user_id) {
    $stmt = $db->prepare("UPDATE {$table_name} SET {$reset_token_column} = :token, expiry_time = :expiry_time WHERE id = :user_id");
    $stmt->bindParam(':user_id', $user_id['id'], PDO::PARAM_INT);
    $stmt->bindParam(':token', $token, PDO::PARAM_STR);
    $stmt->bindParam(':expiry_time', $expiry_time, PDO::PARAM_INT);
    return $stmt->execute();
  } else {
    return false; // User not found
  }
}


/**
 * Resets the password for a given email and token.
 *
 * @param PDO $db Database connection.
 * @param string $email User's email.
 * @param string $token Token.
 * @return bool True on success, false on failure.
 */
function resetPassword(PDO $db, string $email, string $token) {
  $stmt = $db->prepare("SELECT id FROM {$table_name} WHERE {$email_column} = :email AND {$reset_token_column} = :token");
  $stmt->bindParam(':email', $email, PDO::PARAM_STR);
  $stmt->bindParam(':token', $token, PDO::PARAM_STR);
  $stmt->execute();

  $user_id = $stmt->fetch(PDO::FETCH_ASSOC);

  if ($user_id) {
    // Generate a new password (for demonstration - use a proper password generation method in production)
    $new_password = 'password123';  // **IMPORTANT:  This is just an example!  Never use this in production.**

    $stmt = $db->prepare("UPDATE {$table_name} SET {$password_column} = :password, {$reset_token_column} = '', expiry_time = '' WHERE id = :user_id");
    $stmt->bindParam(':password', $new_password, PDO::PARAM_STR);
    $stmt->bindParam(':user_id', $user_id['id'], PDO::PARAM_INT);
    return $stmt->execute();
  } else {
    return false; // Token or user not found
  }
}


/**
 * Example Usage (For demonstration purposes only - handle this in a real application)
 */
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $email = $_POST["email"];
  $reset_token = $_POST["reset_token"];

  // Create a reset token if one doesn't exist. This is the trigger to start the process.
  if (!createResetToken($db, $email)) {
    echo "<p>Failed to generate reset token.</p>";
  } else {
    //Reset Password
    if (resetPassword($db, $email, $reset_token)) {
      echo "<p>Password reset successful!  Please check your email.</p>";
    } else {
      echo "<p>Invalid reset token or user not found.</p>";
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

  <h1>Forgot Password</h1>

  <form method="post" action="">
    <label for="email">Email:</label>
    <input type="email" id="email" name="email" required>
    <button type="submit" name="reset_button">Reset Password</button>
  </form>

</body>
</html>


<?php

// Assuming you have a database connection established (e.g., $db)
// and a 'users' table with 'email' and 'password' columns.

function forgotPassword($email) {
  // 1. Check if the email exists in the database
  $stmt = $db->prepare("SELECT id FROM users WHERE email = ?");
  $stmt->execute([$email]);
  $user = $stmt->fetch(PDO::FETCH_ASSOC);

  if (!$user) {
    return false; // Email not found
  }

  // 2. Generate a unique, time-based token
  $token = bin2hex(random_bytes(32));  // Secure random bytes

  // 3. Store the token and user ID in the database
  $stmt = $db->prepare("INSERT INTO password_resets (user_id, token, expires_at) 
                         VALUES (?, ?, NOW())");
  $stmt->execute([$user['id'], $token]);

  // 4. Send the password reset email (implementation details depend on your email setup)
  $resetLink = "https://yourwebsite.com/reset-password?token=" . $token; // Replace with your actual website URL
  sendResetPasswordEmail($email, $resetLink);  //  See helper function below for details

  return true; // Password reset request submitted successfully
}


//Helper function to send the password reset email.  Replace with your email sending logic.
function sendResetPasswordEmail($email, $resetLink) {
    //  Implement your email sending logic here.  This is just a placeholder.

    // Example using a simple email copy/paste:
    $to = $email;
    $subject = "Password Reset Request";
    $message = "Please click the following link to reset your password:
" . $resetLink;
    $headers = "From: yourname@example.com\r
";

    mail($to, $message, $headers);
    // Alternatively, use a more robust email library (e.g., PHPMailer) for better control and handling.
}



// Example Usage (Illustrative):
// $email = $_POST['email']; // Get email from form submission
// if (isset($email)) {
//   if (forgotPassword($email)) {
//     echo "Password reset email sent to " . $email;
//   } else {
//     echo "Invalid email or email already exists.";
//   }
// }

?>


<?php

/**
 * Forgets a user's password.
 *
 * This function generates a unique, time-based token to be emailed to the user.
 * After the user confirms the password reset link, the token is invalidated.
 *
 * @param string $email The user's email address.
 * @return bool True if an email was sent successfully, false otherwise.
 */
function forgotPassword(string $email) {
  // 1. Validate Email
  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    error_log("Invalid email address provided: " . $email); // Log for debugging
    return false;
  }

  // 2. Generate a Unique Token
  $token = bin2hex(random_bytes(32)); // Generate a cryptographically secure random token

  // 3. Store Token and User ID in Database (Example - Adapt to your database structure)
  $db = new PDO('mysql:host=localhost;dbname=your_database_name', 'your_username', 'your_password'); // Replace with your database credentials

  try {
    $stmt = $db->prepare("INSERT INTO password_resets (user_id, token, expires_at) VALUES (:user_id, :token, :expires_at)");
    $stmt->bindParam(':user_id', (int)$email);  // Use (int) to ensure integer type
    $stmt->bindParam(':token', $token);
    $stmt->bindParam(':expires_at', time() + (60 * 60 * 24)); // Expire in 24 hours
    $stmt->execute();

    // 4. Send Reset Email
    $resetLink = 'http://' . $_SERVER['HTTP_HOST'] . '/reset_password?token=' . $token; // Build the reset link
    $subject = 'Password Reset';
    $message = "Click the link below to reset your password:
" . $resetLink;
    $headers = "From: Your Website <admin@yourwebsite.com>\r
";

    if (mail($email, $subject, $message, $headers)) {
      return true;
    } else {
      // Log the email sending failure
      error_log("Failed to send email to " . $email);
      return false;
    }
  } catch (PDOException $e) {
    // Log the database error
    error_log("Database error: " . $e->getMessage());
    return false;
  }
}

// Example Usage:
// $email = 'user@example.com';
// if (forgotPassword($email)) {
//   echo "Password reset email sent to " . $email;
// } else {
//   echo "Failed to send password reset email.";
// }

?>


<?php

// Assuming you have a database connection established
// (e.g., $db = mysqli_connect('host', 'username', 'password', 'database'));

/**
 * Resets a user's password.
 *
 * @param string $email The email address of the user.
 * @return string  A message indicating success or failure.
 */
function forgotPassword(string $email): string
{
    // 1. Validate Input (Important!)
    if (empty($email)) {
        return "Error: Email address is required.";
    }

    // 2. Check if the user exists in the database
    $stmt = $db->prepare("SELECT id, email FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        $userId = $user['id'];
        $stmt->close(); // Close the query

        // 3. Generate a unique reset token
        $resetToken = bin2hex(random_bytes(32)); // Generate a cryptographically secure token
        $stmt = $db->prepare("INSERT INTO password_resets (user_id, token, expires_at) VALUES (?, ?, ?)");
        $stmt->bind_param("iii", $userId, $resetToken, time() + 3600); // Expires in 1 hour.  Adjust as needed.
        $stmt->execute();
        $stmt->close();

        // 4. Send the reset password link (Implementation depends on your email setup)
        $resetLink = "https://yourwebsite.com/reset_password?token=$resetToken"; // Replace with your actual domain
        // You'll likely need to implement code here to send an email
        // with this link. Example:
        // sendEmail( $email, "Reset Password", "Click this link to reset your password: $resetLink");


        return "Password reset link sent to your email address. Please check your inbox.";

    } else {
        return "User not found.";
    }
}


// Example Usage (Illustrative - Replace with your actual logic)
// $email = "testuser@example.com";
// $result = forgotPassword($email);
// echo $result;


// Placeholder for sendEmail function (You'll need to implement this)
/**
 * Sends an email.
 * @param string $to
 * @param string $subject
 * @param string $message
 */
function sendEmail(string $to, string $subject, string $message) {
  // TODO: Implement your email sending logic here (using PHPMailer, etc.)
  // Example:
  // $mail = new PHPMailer(true);
  // $mail->SetFrom("your_email@example.com", "Your Website");
  // $mail->AddAddress($to, "User Name");  // Get user name from database
  // $mail->Subject = $subject;
  // $mail->MsgBody = $message, 'html'); // or 'text'
  // $mail->send();
}

?>


<?php

// This is a simplified example and should be used with caution in a production environment.
// Security is paramount!  This example lacks robust security measures and should be adapted 
// to your specific needs and a production-ready setup.

// Database connection (replace with your actual connection details)
$dbHost = "localhost";
$dbName = "your_database_name";
$dbUser = "your_database_user";
$dbPass = "your_database_password";

// 1. Reset Password Request Form Handling
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST["email"]);

    // Basic email validation (improve this!)
    if (empty($email)) {
        $error = "Please enter your email address.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Invalid email address.";
    } else {
        //  IMPORTANT:  This is a simplified example.  In a real application,
        //  you would:
        //  1.  Check if the user exists in the database.
        //  2.  Generate a unique, time-based token.
        //  3.  Store the token in the database, linked to the user's email.
        //  4.  Send an email containing a link with the token.
        //  5.  Implement password reset logic on the link click.
        //  This example skips those steps for brevity.

        // Simulate a reset link generation (DO NOT USE IN PRODUCTION!)
        $resetToken = bin2hex(random_bytes(32));  // Generate a secure random token

        //  Store the token in the database
        //  (Replace with your actual database insert query)
        //  Example:
        //  $sql = "INSERT INTO password_resets (email, token, expiry) VALUES ('$email', '$resetToken', NOW() + INTERVAL 1 HOUR)";
        //  mysqli_query($conn, $sql);


        // Display a message indicating the reset link has been generated.
        echo "<p>An email has been sent to $email with instructions to reset your password.</p>";
        echo "<p>You can view the email in your inbox or spam folder.</p>";

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

<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
    <label for="email">Email Address:</label>
    <input type="email" id="email" name="email" placeholder="your.email@example.com" value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>">
    <?php if (isset($error)) { ?>
        <p class="error"><?php echo $error; ?></p>
    <?php } ?>
    <button type="submit">Request Password Reset</button>
</form>

</body>
</html>


<?php

/**
 * Resets a user's password.
 *
 * @param string $email The user's email address.
 * @param string $newPassword The new password.
 * @param string $secretKey (Optional) A secret key for verification.  Highly recommended.
 * @return bool True on success, false on failure.
 */
function forgotPassword(string $email, string $newPassword, string $secretKey = "") {
    // 1. Validate Input
    if (empty($email) || empty($newPassword)) {
        return false; // Required fields must be provided
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return false; // Invalid email format
    }

    if (strlen($newPassword) < 8) { //  Example minimum password length
        return false; //  Minimum length requirement
    }

    // 2.  Check if a user exists
    $user = getUserById($email); //  Assuming you have a function to fetch user by email

    if (!$user) {
        return false; // User not found
    }

    // 3. Verify Secret Key (Important for security)
    if ($secretKey && !verifySecretKey($user->id, $secretKey)) {
        return false; // Invalid secret key
    }

    // 4.  Reset Password
    $hashedNewPassword = password_hash($newPassword, PASSWORD_DEFAULT); // Hash the new password

    // 5.  Update User's Password
    $result = updateUserPassword($user->id, $hashedNewPassword);

    if (!$result) {
        return false; // Failed to update password
    }

    // 6. Generate and Send Password Reset Email (Optional, but highly recommended)
    // Send an email containing a link to reset the password
    sendPasswordResetEmail($email, $hashedNewPassword); // Assuming you have this function

    return true; // Password reset successful
}


/**
 * Dummy Functions - Replace with your actual implementations
 */
function getUserById(string $email) {
    // Replace this with your database query to fetch the user by email.
    // This is a placeholder.
    $users = [
        ['id' => 1, 'email' => 'test@example.com'],
        ['id' => 2, 'email' => 'another@example.com'],
    ];

    foreach ($users as $user) {
        if ($user['email'] === $email) {
            return $user;
        }
    }
    return null;
}


function verifySecretKey(int $userId, string $secretKey) {
    // In a real implementation, compare the provided secret key
    // to a hash stored for the user.
    // This is just a placeholder.
    // You would typically store the hashed secret key in the database
    // alongside the user's ID.
    // For this example, we'll just return true if the secret key is "secret123".
    return $secretKey === "secret123";
}

function updateUserPassword(int $userId, string $hashedPassword) {
    // Replace this with your database update query to set the password.
    // This is a placeholder.
    // Example:  UPDATE users SET password = '$hashedPassword' WHERE id = $userId;
    echo "Updating password for user ID: " . $userId . " with hashed password: " . $hashedPassword . PHP_EOL;
    return true;
}

function sendPasswordResetEmail(string $email, string $hashedPassword) {
    //  Replace this with your email sending implementation
    //  Example:  Use PHPMailer, SwiftMailer, or another email library
    echo "Sending password reset email to: " . $email . PHP_EOL;
    echo "Hashed password: " . $hashedPassword . PHP_EOL;
}



// --- Example Usage ---
$email = 'test@example.com';
$newPassword = 'MyNewPassword123';
$secretKey = "secret123"; // Include this for verification

if (forgotPassword($email, $newPassword, $secretKey)) {
    echo "Password reset successful for $email" . PHP_EOL;
} else {
    echo "Password reset failed for $email" . PHP_EOL;
}


?>


<?php

/**
 * Forgets a user's password.
 *
 * This function sends an email to the user's registered email address
 * containing a temporary password.  It also logs the reset attempt.
 *
 * @param string $email The email address of the user requesting a password reset.
 * @param string $baseUrl The base URL for the password reset link.  
 *                         Should include the domain and path (e.g., 'https://example.com/reset_password').
 * @param string $salt The salt used for hashing the password.  Important for security.
 *
 * @return bool True on success, false on failure (e.g., email not sent).
 */
function forgotPassword(string $email, string $baseUrl, string $salt)
{
    // Validate email format (basic check - improve this for production)
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        error_log("Invalid email format provided: " . $email); // Log the error
        return false;
    }

    // Generate a temporary password
    $temporaryPassword = generateTemporaryPassword(6); // Adjust length as needed

    // Hash the temporary password
    $hashedTemporaryPassword = password_hash($temporaryPassword, PASSWORD_DEFAULT, ['salt' => $salt]);

    // Store the temporary password and user ID in the database
    $userId = getUserIDByEmail($email); //  Assume you have a function to get the user ID
    if (!$userId) {
        error_log("User with email " . $email . " not found.");
        return false;
    }

    // Store the temporary password and timestamp in the database.  Consider using a dedicated
    // table for reset tokens to avoid collisions.
    $resetToken = password_hash($temporaryPassword . '_' . $userId, PASSWORD_DEFAULT, ['salt' => $salt]); // Add userId to token
    
    // Store data in database (replace with your actual database interaction)
    // Example:
    // $sql = "INSERT INTO password_resets (user_id, token, created_at) VALUES ($userId, '$resetToken', NOW())";
    // mysqli_query($connection, $sql);  // Replace $connection with your database connection

    // Send the password reset email
    if (!sendResetPasswordEmail($email, $temporaryPassword, $baseUrl)) {
        error_log("Failed to send password reset email to " . $email);
        // Consider handling this differently depending on your requirements.
        // You might try sending the email again later, or return an error.
        return false;
    }

    return true;
}

/**
 * Generates a random temporary password.
 * 
 * @param int $length The desired length of the password.
 * @return string The generated temporary password.
 */
function generateTemporaryPassword(int $length = 6) {
    $characters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*()_+-={}[]|\'":<>?/';
    $password = '';
    $passwordLength = strlen($characters);

    for ($i = 0; $i < $length; $i++) {
        $char = $characters[rand(0, $charLength)];
        $password .= $char;
    }
    return $password;
}


/**
 * Placeholder function for sending the password reset email.
 * 
 * @param string $email The email address of the user.
 * @param string $temporaryPassword The temporary password.
 * @param string $baseUrl The base URL for the password reset link.
 * @return bool True on success, false on failure.
 */
function sendResetPasswordEmail(string $email, string $temporaryPassword, string $baseUrl) {
    // Implement your email sending logic here using a library like PHPMailer.
    // This is a placeholder.

    $subject = "Password Reset Link";
    $message = "Please click the following link to reset your password: " . $baseUrl . "/reset_password?token=" . urlencode($temporaryPassword); 

    //  In a real implementation, you would use a mail library
    //  to send the email.  For example:
    //  $mail = new PHPMailer\PHPMailer\PHPMailer();
    //  $mail->SetSMTPDebugEnable(true);
    //  $mail->addAddress($email, 'User');
    //  $mail->Subject = $subject;
    //  $mail->Body = $message;
    //  $mail->send();

    return true; // Assume success for this example
}


/**
 * Placeholder function for getting the user ID by email.
 * 
 * @param string $email The email address of the user.
 * @return int|null The user ID if found, null otherwise.
 */
function getUserIDByEmail(string $email) {
  // Implement your logic to retrieve the user ID from the database
  // based on the email address. This is a placeholder.
  // Example:
  // $result = mysqli_query($connection, "SELECT id FROM users WHERE email = '$email'");
  // if ($result) {
  //   $row = mysqli_fetch_assoc($result);
  //   return $row['id'];
  // } else {
  //   return null;
  // }
    return null; // Placeholder
}

?>


<?php

// Assuming you have a database connection established (e.g., $db)

// 1. Input Validation: Check if the email is valid.
if (empty($_POST['email'])) {
    $error = "Please enter your email address.";
} else {
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL); // Sanitize the email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Invalid email address.";
    }
}

// 2.  Password Reset Token Generation (Important for Security)
function generateResetToken() {
    return bin2hex(random_bytes(32)); // Generate a cryptographically secure random token
}

// 3.  Database Query (Replace with your actual database structure)
// In a real application, you'd likely have a user table with an 'email' and 'password' column
// For this example, we'll assume a simplified table structure.

// Example Table Structure:
// Users Table:
//   - id (INT, PRIMARY KEY)
//   - email (VARCHAR)
//   - password (VARCHAR)
//   - reset_token (VARCHAR, UNIQUE)

function resetPassword($email, $resetToken) {
    // Example Database Query (Replace with your actual query)
    $sql = "UPDATE users SET reset_token = NULL WHERE email = '$email' AND reset_token = '$resetToken'";
    $result = mysqli_query($GLOBALS['db'], $sql); //Use your database connection here.
    return $result;
}


// 4.  Handle the Reset Request (POST Request)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if the token is provided
    if (isset($_POST['reset_token'])) {
        $resetToken = filter_var($_POST['reset_token'], FILTER_SANITIZE_STRING);

        // Reset the password (This would typically be a link to a page with a form)
        $resetResult = resetPassword($email, $resetToken);

        if ($resetResult) {
            // Password reset successful - Redirect to a page for the user to set a new password.
            echo "<p>Password reset link sent to your email.  Please set a new password.</p>";
            //  Implement code to redirect the user to a page with a form to enter the new password.
        } else {
            // Handle the error
            echo "<p>Error resetting password.  Please try again.</p>";
            // Log the error for debugging.
        }
    } else {
        echo "<p>Invalid or missing reset token.</p>";
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

    <?php if (isset($error)): ?>
        <p style="color: red;"><?php echo $error; ?></p>
    <?php endif; ?>

    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        <label for="email">Email Address:</label>
        <input type="email" id="email" name="email" placeholder="Your email" value="<?php echo isset($email) ? htmlspecialchars($email) : ''; ?>">
        <button type="submit">Reset Password</button>
    </form>

</body>
</html>


<?php

// Assuming you have a database connection established and named $db

/**
 * Resets a user's password.
 *
 * @param string $email The email address of the user requesting password reset.
 * @return string A message indicating success or failure.
 */
function forgot_password(string $email) {
    // 1. Validate Input (Important for Security)
    if (empty($email)) {
        return "Error: Email address is required.";
    }

    // Sanitize the email address to prevent potential vulnerabilities
    $email = filter_var($email, FILTER_SANITIZE_EMAIL);
    if (filter_var($email, FILTER_VALIDATE_EMAIL) === false) {
        return "Error: Invalid email address format.";
    }

    // 2. Check if User Exists
    $user = getUserByEmail($email); // Assuming you have a function to retrieve user by email
    if ($user === null) {
        return "Error: User not found.";
    }

    // 3. Generate a Unique Token and Store it
    $token = generate_unique_token();  // Assuming you have a function to generate a unique token
    $expiry_time = time() + (3600 * 24); // Token expires in 24 hours (adjust as needed)

    // Store the token and expiry time in the database, linked to the user.
    // This is a crucial step.  The example below is illustrative;
    // your actual implementation will vary based on your database schema.
    store_token($user->id, $token, $expiry_time);

    // 4. Send the Password Reset Email
    $subject = "Password Reset Request";
    $headers = "From: your_email@example.com" . "\r
"; // Replace with your email
    $message = "Click on this link to reset your password: " . base_url . "reset_password?token=" . urlencode($token);
    $result = send_email($email, $subject, $headers, $message);

    if ($result) {
        return "Password reset link has been sent to your email.";
    } else {
        return "Error: Failed to send email.";
    }
}


/**
 * Placeholder functions (You'll need to implement these based on your application)
 */
// Example: Get user by email (Replace with your actual database query)
function getUserByEmail(string $email): ?User {
    // Example database query - Replace with your actual query
    // Assuming you have a User class/model
    // This is just a placeholder - replace with your logic
    //  $db = get_database_connection();  // Get database connection
    //  $query = "SELECT * FROM users WHERE email = ?";
    //  $stmt = $db->prepare($query);
    //  $stmt->execute([$email]);
    //  $user = $stmt->fetch(PDO::FETCH_ASSOC);
    //  if ($user) {
    //      return new User($user); // Create a User object
    //  }
    //  return null;

    // Placeholder for demo - returns a dummy user object
    return new User(['id' => 1, 'email' => 'test@example.com']);
}

// Example: Generate a unique token (You'll need to implement this)
function generate_unique_token(): string {
    return bin2hex(random_bytes(32)); // A secure random string
}

// Example: Store the token and expiry time in the database (Implement this)
function store_token(int $userId, string $token, int $expiry_time): void {
  // Implement the logic to store the token and expiry time in your database
  //  e.g., using a database query to update the user's record.
  // Example:
  // $db = get_database_connection();
  // $query = "UPDATE users SET token = ?, expiry_time = ? WHERE id = ?";
  // $stmt = $db->prepare($query);
  // $stmt->execute([$token, $expiry_time, $userId]);
}

// Example: Send an email (You'll need to implement this, likely using a library)
function send_email(string $to, string $subject, string $headers, string $message): bool {
    //  Implement the email sending logic using a library like PHPMailer
    //  This is a placeholder -  replace with your actual email sending code.
    //  For demonstration, simply return true.
    //  e.g.,
    //  $mailer = new PHPMailer(true);
    //  $mailer->addAddress($to, $to);
    //  $mailer->setFrom('your_email@example.com', 'Your Name');
    //  $mailer->Subject = $subject;
    //  $mailer->Body = $message;
    //  return $mailer->send();

    // Placeholder
    return true;
}



// Example User Class (Customize as needed)
class User {
    public int $id;
    public string $email;

    public function __construct(array $data) {
        $this->id = $data['id'];
        $this->email = $data['email'];
    }
}



// Example Usage:
$email = "test@example.com"; // Replace with a user's email
$result = forgot_password($email);
echo $result . "
";

?>


<?php

// Assuming you have a database connection established (e.g., $db)

/**
 * Forgets a user's password and sends a password reset link.
 *
 * @param string $email The user's email address.
 * @return bool True if an email was sent, false otherwise.
 */
function forgotPassword(string $email): bool
{
    // 1. Validate Email (Important Security Step!)
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        error_log("Invalid email format provided: " . $email); // Log for debugging
        return false;
    }

    // 2. Check if the user exists
    $user = getUserByEmail($email);

    if (!$user) {
        error_log("User with email: " . $email . " not found."); // Log for debugging
        return false;
    }

    // 3. Generate a Unique Token and Timestamp
    $token = generateUniqueToken();
    $timestamp = time();

    // 4. Create the Reset Token Record (Store this in your database)
    //   *  Email
    //   *  Token
    //   *  Expiration Time
    resetTokenRecord = [
        'email' => $email,
        'token' => $token,
        'expiry' => $timestamp + (60 * 60 * 24) // Expires in 24 hours
    ];

    // Save the record to the database.  Replace this with your actual database query
    if (!saveResetToken($resetTokenRecord)) {
        error_log("Failed to save reset token record for " . $email);
        return false;
    }


    // 5.  Send the Password Reset Email (Implement your email sending logic here)
    $subject = "Password Reset Request";
    $message = "Click the following link to reset your password: " . base_url() . "/reset-password?token=" . $token; // Replace base_url()

    $headers = "From: " . get_sender_email(); //Replace with your sender email address
    if (!sendEmail($subject, $message, $headers)) {
        error_log("Failed to send password reset email for " . $email);
        //Optionally, you could delete the token from the database if email sending fails
        deleteResetToken($email, $token);
        return false;
    }

    return true;
}

/**
 *  Dummy function to simulate getting user data from database.  Replace with your actual query.
 *  @param string $email
 *  @return array|null User data, or null if not found.
 */
function getUserByEmail(string $email): ?array {
    // Example using a dummy user. Replace with your database query
    $users = [
        ['id' => 1, 'email' => 'test@example.com', 'password' => 'hashed_password'],
    ];

    foreach ($users as $user) {
        if ($user['email'] === $email) {
            return $user;
        }
    }
    return null;
}


/**
 * Dummy function to generate a unique token.
 * @return string
 */
function generateUniqueToken(): string {
    return bin2hex(random_bytes(32)); //Generate a 32-byte (256-bit) random string.
}

/**
 * Dummy function to save the reset token record to the database. Replace with your actual database query.
 * @param array $resetTokenRecord
 * @return bool
 */
function saveResetToken(array $resetTokenRecord): bool {
    //Replace with your actual database insert query.  For demonstration, we just return true.
    //Example:
    // $sql = "INSERT INTO reset_tokens (email, token, expiry) VALUES (?, ?, ?)";
    // $stmt = $db->prepare($sql);
    // $stmt->execute([$resetTokenRecord['email'], $resetTokenRecord['token'], $resetTokenRecord['expiry']]);
    return true;
}


/**
 * Dummy function to delete the reset token record. Replace with your actual database query.
 * @param string $email
 * @param string $token
 * @return bool
 */
function deleteResetToken(string $email, string $token): bool {
    //Replace with your actual database delete query.
    //Example:
    //$sql = "DELETE FROM reset_tokens WHERE email = ? AND token = ?";
    //$stmt = $db->prepare($sql);
    //$stmt->execute([$email, $token]);
    return true;
}

/**
 * Dummy function for sending emails.  Replace with your actual email sending logic.
 * @param string $subject
 * @param string $message
 * @param string $headers
 * @return bool
 */
function sendEmail(string $subject, string $message, string $headers): bool {
    // Simulate email sending.  Replace with your actual email sending logic.
    // You'll likely use a library like PHPMailer or SwiftMailer for actual sending.
    error_log("Simulating sending email: Subject: " . $subject . ", Message: " . $message);
    return true;
}

/**
 * Dummy function to get the base URL.  Replace with your actual base URL.
 * @return string
 */
function base_url(): string {
    // Replace with your application's base URL
    return "http://localhost/my_app/";
}

/**
 * Dummy function to get the sender email.  Replace with your actual sender email.
 * @return string
 */
function get_sender_email(): string {
    // Replace with your actual sender email address
    return "noreply@example.com";
}

// Example Usage:
$email = "test@example.com"; // Replace with the user's email
if (forgotPassword($email)) {
    echo "Password reset email has been sent to " . $email;
} else {
    echo "Failed to send password reset email for " . $email;
}

?>


<?php

// This is a simplified example for demonstration purposes only.
// In a production environment, you MUST implement robust security measures.

// Database connection (replace with your actual credentials)
$host = 'localhost';
$username = 'your_username';
$password = 'your_password';
$database = 'your_database';

// Function to handle password reset
function forgot_password($email) {
  // 1. Validate Email
  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    return 'Invalid email address.';
  }

  // 2. Check if the email exists in the user table
  $conn = new mysqli($host, $username, $password, $database);

  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  $sql = "SELECT id, password_reset_token FROM users WHERE email = '$email'";
  $result = $conn->query($sql);

  if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
    $id = $user['id'];
    $token = $user['password_reset_token'];

    // 3. Generate a new, unique token (important for security)
    $new_token = bin2hex(random_bytes(32)); 

    // 4. Update the password_reset_token with the new token
    $update_sql = "UPDATE users SET password_reset_token = '$new_token' WHERE id = '$id'";
    if ($conn->query($update_sql) === TRUE) {
      // 5. Send an email with the reset link
      $to = $email;
      $subject = 'Password Reset Link';
      $message = "Please click this link to reset your password: " . $_SERVER['PHP_SELF'] . "?reset=" . $new_token;  // Use PHP_SELF to ensure the correct script is called.
      $headers = "From: your_email@example.com" . "\r
";
      mail($to, $message, $headers);

      return 'Password reset email sent to ' . $email . '. Please check your inbox.';
    } else {
      return 'Error updating password reset token.';
    }
  } else {
    return 'User not found.';
  }

  $conn->close();
}

// Example Usage (Handle form submission - NOT FOR PRODUCTION)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $email = $_POST["email"];
  $reset_message = forgot_password($email);
  echo $reset_message;
}

?>

<!DOCTYPE html>
<html>
<head>
  <title>Forgot Password</title>
</head>
<body>

  <h1>Forgot Password</h1>

  <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
    <label for="email">Email:</label>
    <input type="email" id="email" name="email" required>
    <button type="submit">Reset Password</button>
  </form>

</body>
</html>


<?php

// Assume you have a database connection established (e.g., $db)
// This is a simplified example - replace with your actual database connection
// For demonstration purposes, we'll use a hardcoded user and password.

// Example user data (replace with your database)
$users = [
    'john.doe@example.com' => 'password123',
    'jane.smith@example.com' => 'securePass456'
];

/**
 * Forgets a user's password.
 *
 * @param string $email The user's email address.
 * @return bool True if password reset email sent successfully, false otherwise.
 */
function forgotPassword(string $email)
{
    // Validate email format (basic)
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return false;
    }

    // Check if the user exists
    if (isset($users[$email])) {
        // Generate a unique token for password reset
        $token = bin2hex(random_bytes(32)); // Secure random token

        // Store the token and expiry time in the database for the user
        // (Replace this with your database logic)
        // Example:
        // $db->query("INSERT INTO password_resets (email, token, expiry) VALUES ('$email', '$token', NOW() + INTERVAL 24 HOUR)");

        // For demonstration, we'll just log the token (replace with actual email sending)
        error_log("Password reset token generated for: " . $email . " - Token: " . $token);

        //  Here you would send an email containing a link with the token.
        //  Example (this is just a placeholder):
        //  $subject = "Password Reset Link";
        //  $headers = "From: noreply@example.com\r
";
        //  $body = "Click this link to reset your password: http://example.com/reset?token=$token";
        //  mail($email, $subject, $body, $headers);

        return true;
    } else {
        return false;
    }
}

// Example usage:
$emailToReset = 'john.doe@example.com';

if (forgotPassword($emailToReset)) {
    echo "Password reset email sent to " . $emailToReset . " successfully." . PHP_EOL;
} else {
    echo "User not found or password reset email could not be sent." . PHP_EOL;
}

$emailToReset = 'nonexistent@example.com'; // Test with an invalid email

if (forgotPassword($emailToReset)) {
    echo "Password reset email sent to " . $emailToReset . " successfully." . PHP_EOL;
} else {
    echo "User not found or password reset email could not be sent." . PHP_EOL;
}

?>


<?php

/**
 * Forgot Password Function
 *
 * This function allows users to reset their password.
 * It generates a unique, secure token and sends an email with a link to 
 * a password reset page.
 *
 * @param string $email The email address of the user.
 * @param string $token  A unique token to associate with the password reset request.
 * @param string $baseUrl The base URL of the password reset page.
 * @return bool True if the email was sent successfully, false otherwise.
 */
function forgotPassword(string $email, string $token, string $baseUrl)
{
    // Validate email and token (important for security)
    if (empty($email) || empty($token)) {
        error_log("Forgot Password: Missing email or token."); // Log for debugging
        return false;
    }

    // Build the reset link
    $resetLink = $baseUrl . "?token=" . urlencode($token);

    // Email setup
    $to = $email;
    $subject = 'Password Reset';
    $message = "Please click the following link to reset your password: " . $resetLink;
    $headers = "From: Your Website <admin@yourwebsite.com>"; // Replace with your email address

    // Send the email (using PHP's built-in mail function -  Consider alternatives for production)
    if (mail($to, $subject, $message, $headers)) {
        error_log("Forgot Password: Email sent to " . $email);
        return true;
    } else {
        error_log("Forgot Password: Failed to send email to " . $email);
        return false;
    }
}

// Example Usage (For testing purposes - Remove or adjust for your application)
// $email = "testuser@example.com";
// $token = "abcdef1234567890";
// $baseUrl = "http://localhost/reset_password/";  // Replace with your password reset URL

// if (forgotPassword($email, $token, $baseUrl)) {
//     echo "Password reset email sent to " . $email;
// } else {
//     echo "Failed to send password reset email.";
// }

?>


<?php

// Assuming you have a database connection established (e.g., $pdo)

/**
 * Resets the user's password.
 *
 * @param string $email The user's email address.
 * @param string $newPassword The new password.
 * @return bool True if the password reset was successful, false otherwise.
 */
function forgotPassword(string $email, string $newPassword) {
  // 1. Validate Inputs (Important for security!)
  if (empty($email) || empty($newPassword)) {
    return false; // Or throw an exception: throw new InvalidArgumentException("Email and password are required.");
  }

  // 2. Check if the email exists in the database.
  $stmt = $pdo->prepare("SELECT id FROM users WHERE email = :email");
  $stmt->bindParam(':email', $email, PDO::PARAM_STR);
  $stmt->execute();

  $user = $stmt->fetch(PDO::FETCH_ASSOC);

  if ($user === false) {
    return false; // Email not found
  }

  // 3.  Hash the new password
  $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);

  // 4. Update the user's password in the database
  $stmt = $pdo->prepare("UPDATE users SET password = :password WHERE id = :id");
  $stmt->bindParam(':password', $hashedPassword, PDO::PARAM_STR);
  $stmt->bindParam(':id', $user['id'], PDO::PARAM_INT);
  $stmt->execute();


  return true; // Password reset successful
}

// Example Usage:
// In your form, after the user submits the form...
/*
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $email = $_POST["email"];
  $newPassword = $_POST["newPassword"];

  if (forgotPassword($email, $newPassword)) {
    echo "Password reset email sent.  Check your inbox!";
  } else {
    echo "Error: Password reset failed. Please try again.";
  }
}
*/


// Example:  Illustrative only -  DO NOT put the actual password generation code in your app!
// In a real application, you would send an email with a link to a password reset form.
// This example shows how to generate a temporary password for testing.
function generateTemporaryPassword() {
  return bin2hex(random_bytes(16)); // Creates a strong random string
}
?>


<?php

// Assuming you have a database connection established and named $db

// Function to reset a user's password
function forgot_password($email, $new_password, $db) {
    // 1. Check if the email exists in the database
    $query = "SELECT id FROM users WHERE email = ?";
    $stmt = $db->prepare($query);
    $stmt->execute([$email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user) {
        // Email doesn't exist
        return false;
    }

    // 2. Hash the new password
    $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

    // 3. Update the user's password in the database
    $query = "UPDATE users SET password = ? WHERE id = ?";
    $stmt = $db->prepare($query);
    $stmt->execute([$hashed_password, $user['id']]);

    if ($stmt->rowCount() === 0) {
        // Update failed
        return false;
    }

    // 4. (Optional) Send an email notification (recommended)
    // This would involve sending an email to the user with a link to reset their password.
    // See the commented-out example below.

    return true;
}


// Example Usage (Illustrative - Replace with your actual email and password)
//
// $email = "testuser@example.com";
// $new_password = "new_secure_password";
//
// if (forgot_password($email, $new_password, $db)) {
//     echo "Password reset successful!  Check your email for instructions.";
// } else {
//     echo "Password reset failed.  Please try again.";
// }

// Example of sending an email (Requires configuration - SMTP/Email Setup)
/*
// Send email notification
function send_password_reset_email($email, $reset_link) {
  $to = $email;
  $subject = 'Password Reset';
  $message = "Please click on the following link to reset your password: " . $reset_link;
  $headers = "From: your_email@example.com" . "\r
" .
            "Reply-To: your_email@example.com";

  mail($to, $message, $headers);
}

// Example of generating the reset link (In a real application, you would use a token-based approach for security)
// $reset_link = "https://yourwebsite.com/reset_password?token=" . md5($email . time());
// send_password_reset_email($email, $reset_link);
*/

?>


<?php

// Assuming you have a database connection established (e.g., $db)

/**
 * Resets a user's password.
 *
 * @param string $email The user's email address.
 * @return bool True if a reset token was generated and sent, false otherwise.
 */
function forgot_password(string $email): bool
{
    // 1. Check if the email exists in the database
    $user = getUserByEmail($email);

    if (!$user) {
        // User not found
        return false;
    }

    // 2. Generate a unique reset token
    $token = generate_unique_token(); // Implement this function (see below)

    // 3. Store the token in the database, associated with the user's email.
    //    This is crucial for security.  Don't just store a plain token.
    //    Ideally, you'd hash the token and store the hash.  Storing the raw
    //    token directly is vulnerable to attacks.
    store_reset_token($user->id, $token);  //Implement this function (see below)

    // 4. Send the reset email.
    $subject = 'Password Reset';
    $body = "Please click the following link to reset your password: " .  base_url() . "/reset_password?token=" . $token; // Replace with your base URL
    $headers = "From: " . get_admin_email() . "\r
"; // Replace with your admin email
    $result = send_email($email, $subject, $body, $headers);


    if ($result) {
        return true;
    } else {
        // Email sending failed.  Consider logging this error for debugging.
        return false;
    }
}


/**
 *  Dummy functions - Implement these based on your setup.
 */

/**
 * Gets a user by email.  Implement this to connect to your database.
 * @param string $email
 * @return User | null
 */
function getUserByEmail(string $email): ?User {
    // *** IMPLEMENT THIS FUNCTION ***
    // This is a placeholder. Replace with your database query.
    // Example (using a hypothetical User class):
    // $result = mysqli_query($db, "SELECT * FROM users WHERE email = '$email'");
    // if (mysqli_num_rows($result) > 0) {
    //   $row = mysqli_fetch_assoc($result);
    //   return new User($row);
    // }
    // return null;
}



/**
 * Generates a unique token.  Use a cryptographically secure random number generator.
 * @return string
 */
function generate_unique_token(): string
{
    return bin2hex(random_bytes(32)); // A 32-byte (256-bit) random number.
}



/**
 * Stores the reset token in the database.  HASH the token for security.
 * @param int $userId
 * @param string $token
 * @return void
 */
function store_reset_token(int $userId, string $token): void
{
    // *** IMPLEMENT THIS FUNCTION ***
    // Example (using a hypothetical database table called 'reset_tokens'):
    // mysqli_query($db, "INSERT INTO reset_tokens (user_id, token, created_at) VALUES ('$userId', '$token', NOW())");
    // OR using an ORM:
    // $this->db->insert('reset_tokens', ['user_id' => $userId, 'token' => $token, 'created_at' => date('Y-m-d H:i:s')]);
}


/**
 * Sends an email.  Replace this with your email sending implementation.
 * @param string $to
 * @param string $subject
 * @param string $body
 * @param string $headers
 * @return bool
 */
function send_email(string $to, string $subject, string $body, string $headers): bool
{
    // *** IMPLEMENT THIS FUNCTION ***
    // Example (using a placeholder):
    // error_log("Sending email to: " . $to . " Subject: " . $subject); //Log for debugging
    // return true; //Replace with your actual email sending code.

    //Real Email sending example - using PHPMailer (install via Composer)
    require_once 'vendor/phpmailer/phpmailer/src/Exception.php';
    require_once 'vendor/phpmailer/phpmailer/src/Exception.php';

    $mail = new \PHPMailer\PHPMailer\PHPMailer();
    $mail->isSMTP();
    $mail->Host = 'smtp.example.com';
    $mail->SMTPAuth = true;
    $mail->Username = 'your_smtp_username';
    $mail->Password = 'your_smtp_password';
    $mail->Port = 587;
    $mail->SMART_HOST = true;
    $mail->setFrom('your_email@example.com', 'Your Website Name');
    $mail->addAddress($to, 'User Name');
    $mail->Subject = $subject;
    $mail->Body = $body;
    $mail->isHTML(false); // Set to true if you're sending HTML emails
    if ($mail->send()) {
        return true;
    } else {
        error_log("Error sending email: " . print_r($mail->getSMTPError(), true));
        return false;
    }

}


/**
 * Returns the base URL of your website.  This is how the reset URL will be constructed.
 * @return string
 */
function base_url(): string
{
    // Replace with your actual base URL.
    return 'http://localhost/your_website'; //Example.
}


/**
 * Returns the admin email address.
 * @return string
 */
function get_admin_email(): string {
    return 'admin@your_website.com';
}


?>


<?php

// Database connection details (replace with your actual credentials)
$host = "localhost";
$username = "your_db_username";
$password = "your_db_password";
$database = "your_db_name";

// --- Function to reset password ---
function resetPassword($email) {
  // 1. Validate Email
  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    return "Invalid email address.";
  }

  // 2. Check if user exists
  $conn = new mysqli($host, $username, $password, $database);

  if ($conn->connect_error) {
    return "Connection failed: " . $conn->connect_error;
  }

  $sql = "SELECT id, username FROM users WHERE email = '$email'";
  $result = $conn->query($sql);

  if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $userId = $row['id'];
    $username = $row['username'];

    // 3. Generate a unique token (important for security)
    $token = bin2hex(random_bytes(32));

    // 4.  Store the token and expiration time in the database
    $sql = "INSERT INTO password_resets (user_id, token, expires_at) VALUES ($userId, '$token', NOW() + INTERVAL 7 DAY)"; // Expires in 7 days
    if (!$conn->query($sql)) {
      return "Error inserting reset token: " . $conn->error;
    }


    // 5. Email the user with the reset link
    $subject = "Password Reset Link";
    $message = "Click this link to reset your password: <a href='" . $_SERVER["PHP_SELF"] . "?reset=$token'</a>";
    $headers = "From: your_email@example.com";  // Replace with your email

    if (mail($email, $subject, $message, $headers)) {
      return "Password reset email sent to $email.";
    } else {
      return "Failed to send password reset email.";
    }
  } else {
    return "User with email $email not found.";
  }

  $conn->close();
}



// --- Example Usage (for testing - DO NOT include this in a production environment) ---
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $email = $_POST["email"];
  $resetMessage = resetPassword($email);
  echo "<br>" . $resetMessage;
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>Forgot Password</title>
</head>
<body>

  <h1>Forgot Password</h1>

  <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
    <label for="email">Email:</label>
    <input type="email" id="email" name="email" required>
    <button type="submit">Reset Password</button>
  </form>

</body>
</html>


<?php

// Assuming you have a database connection established and a user table
// with an 'email' field.

// This is a simplified example - replace with your actual database connection
// and database query logic.

function forgot_password($email) {
  // 1. Validate the email format (basic check)
  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    return 'Invalid email format.';
  }

  // 2. Check if the user exists in the database
  // (Replace with your actual database query)
  $user = get_user_by_email($email); // Function to retrieve user by email

  if ($user === null) {
    return 'User not found.';
  }


  // 3. Generate a unique, temporary password
  $temp_password = generate_unique_password(8); // Generates an 8-character password

  // 4. Store the temporary password for the user (in a secure way - e.g., hashed)
  //  This is a placeholder.  DO NOT store plain-text passwords!
  store_temporary_password($user['id'], $temp_password);

  // 5.  Send an email to the user with the temporary password
  $subject = 'Forgot Password - Your Temporary Password';
  $message = "Your temporary password is: " . $temp_password . "

Please change your password as soon as possible.";
  $headers = 'From: your_website@example.com' . "\r
" .
            'Reply-To: your_website@example.com' . "\r
" .
            'Content-Type: text/plain; charset=UTF-8';

  mail($email, $message, $headers);

  return 'Password reset email sent to ' . $email . '.';

}


// --- Helper Functions (Implement these based on your database and security needs) ---

// Placeholder - Replace with your database query logic
function get_user_by_email($email) {
  // Example (replace with your actual query)
  // This is just a placeholder, assuming you have a user table with an 'id' and 'email' column.
  //  A real implementation would use a database query to find the user by email.
  //  For demonstration purposes, we'll just return a dummy user.
  return array(
    'id' => 123,
    'email' => $email
  );
}



function generate_unique_password($length = 8) {
    $characters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
    $password = '';
    $char_length = strlen($characters);

    for ($i = 0; $i < $length; $i++) {
        $random = mt_rand(0, $char_length - 1);
        $password .= $characters[$random];
    }

    return $password;
}


function store_temporary_password($user_id, $password) {
  //  IMPORTANT:  DO NOT STORE PASSWORDS IN PLAIN TEXT!

  //  This is a placeholder.  You *MUST* hash the password before storing it.

  //  Example (using password_hash - you'll need to adapt it to your database)
  $hashed_password = password_hash($password, PASSWORD_DEFAULT);

  //  Update the database table with the hashed password
  //  (Replace this with your actual database update query)
  //  Example:
  //  $sql = "UPDATE users SET temp_password = '$hashed_password' WHERE id = $user_id";
  //  mysqli_query($conn, $sql);
}



// --- Example Usage ---
// $email = 'test@example.com';  // Replace with the user's email

// $result = forgot_password($email);
// echo $result;
?>


<?php

// Database connection details (replace with your actual details)
$host = "localhost";
$username = "your_username";
$password = "your_password";
$dbname = "your_database";

// 1. Get the email from the form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST["email"];

    // 2. Validate the email
    if (empty($email)) {
        $error = "Please enter your email address.";
    } else {
        // 3.  Check if the email exists in the database
        $conn = new mysqli($host, $username, $password, $dbname);

        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        $sql = "SELECT id, email FROM users WHERE email = '$email'";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            // Email exists, generate a unique token and send a reset password email
            $token = bin2hex(random_bytes(32)); // Generate a random token
            $reset_link = "http://yourwebsite.com/reset_password.php?token=" . $token; // Replace with your website URL

            // Prepare the reset password email
            $to = $email;
            $subject = "Password Reset";
            $message = "Click on the link below to reset your password:
" . $reset_link;
            $headers = "From: your_email@example.com\r
";
            mail($to, $subject, $message, $headers);

            // Store the token in the database (for later retrieval) -  This is crucial!
            $conn->query("UPDATE users SET token = '$token' WHERE email = '$email'");

            $success = "Password reset email sent to $email.  Check your inbox.";
        } else {
            $error = "Email address not found.";
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

    <h2>Forgot Password</h2>

    <?php if (isset($error)) { ?>
        <p style="color: red;"><?php echo $error; ?></p>
    <?php } ?>

    <?php if (isset($success)) { ?>
        <p style="color: green;"><?php echo $success; ?></p>
    <?php } ?>

    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        <label for="email">Email Address:</label>
        <input type="email" id="email" name="email" placeholder="Your Email" required>
        <button type="submit">Reset Password</button>
    </form>

</body>
</html>


<?php

// Replace with your database credentials and table names
$db_host = 'localhost';
$db_name = 'your_database_name';
$db_user = 'your_db_user';
$db_password = 'your_db_password';

// Function to reset password
function forgot_password($email, $new_password, $db) {
  // Check if the email exists in the database
  $stmt = $db->prepare("SELECT id FROM users WHERE email = ?");
  $stmt->execute([$email]);
  $user = $stmt->fetch(PDO::FETCH_ASSOC);

  if (!$user) {
    return false; // Email not found
  }

  // Hash the new password
  $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

  // Update the user's password in the database
  $stmt = $db->prepare("UPDATE users SET password = ? WHERE id = ?");
  $stmt->execute([$hashed_password, $user['id']]);

  return true; // Password reset successful
}


// Example Usage (Demonstration - DO NOT USE IN PRODUCTION WITHOUT SECURE HANDLING)
// This is just for demonstration, it's not a complete and secure form!
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $email = $_POST["email"];
  $new_password = $_POST["new_password"];

  // Validate input (important for security - this is basic)
  if (empty($email) || empty($new_password)) {
    echo "Error: Email and password are required.";
  } else {
    // Call the forgot_password function
    $success = forgot_password($email, $new_password, $db);

    if ($success) {
      echo "Password reset email sent successfully!";
    } else {
      echo "Error: Could not reset password.  Please check your email or contact support.";
    }
  }
}

// Database connection (using PDO - recommended)
try {
  $db = new PDO("mysql:host=$db_host;dbname=$db_name;charset=utf8mb4", $db_user, $db_password);
  $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // Enable exception handling
} catch(PDOException $e) {
  echo "Connection failed: " . $e->getMessage();
  exit;
}

?>

<!DOCTYPE html>
<html>
<head>
  <title>Forgot Password</title>
</head>
<body>

  <h1>Forgot Password</h1>

  <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
    <label for="email">Email:</label>
    <input type="email" id="email" name="email" required><br><br>

    <input type="password" id="new_password" name="new_password" required><br><br>

    <button type="submit">Reset Password</button>
  </form>

</body>
</html>


<?php

// Assuming you have a database connection established
// (e.g., using mysqli or PDO)
// For simplicity, let's assume $db is a mysqli connection object

// Function to handle the forgot password process
function forgotPassword($email) {
  // 1. Validate Email (Important for security)
  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    return "Invalid email address.";
  }

  // 2. Check if the user exists
  $result = mysqli_query($db, "SELECT id, username FROM users WHERE email = '$email'");
  if (mysqli_num_rows($result) == 0) {
    return "User not found.";
  }

  // 3. Generate a Unique Token
  $token = bin2hex(random_bytes(32));

  // 4. Update the User's Record with the Token
  mysqli_query($db, "UPDATE users SET password_reset_token = '$token', password_reset_token_expiry = NOW() + INTERVAL 1 HOUR WHERE email = '$email'");

  // 5. Send the Password Reset Email
  $to = $email;
  $subject = "Password Reset Request";
  $message = "Please use the following link to reset your password: " . $_SERVER['REQUEST_URI'] . 
              "<br><br>This link will expire in 1 hour.";
  $headers = "From: your_website@example.com"; // Replace with your actual email address

  mail($to, $message, $headers);

  return "Password reset email sent. Please check your inbox.";
}


// Example Usage (Illustrative -  This would be part of your form processing)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $email = $_POST["email"];
  $reset_result = forgotPassword($email);
  echo $reset_result;
}

?>

<!DOCTYPE html>
<html>
<head>
  <title>Forgot Password</title>
</head>
<body>

  <h1>Forgot Password</h1>

  <form method="post" action="<?php echo htmlspecialchars($_SERVER["REQUEST_URI"]); ?>">
    <label for="email">Email:</label>
    <input type="email" id="email" name="email" required>
    <button type="submit">Reset Password</button>
  </form>

</body>
</html>


<?php

// Assuming you have a database connection established (e.g., $db_connection)
// This is just a basic example, adapt to your database setup

function forgotPassword($email) {
    // 1. Check if the email exists in the database
    $stmt = $db_connection->prepare("SELECT id, email FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user) {
        // Email not found
        return false;
    }

    // 2. Generate a unique token
    $token = bin2hex(random_bytes(32));  // Using random_bytes for better security

    // 3. Hash the token (for security) - This is CRUCIAL!
    $hashed_token = password_hash($token, PASSWORD_DEFAULT);

    // 4. Store the token in the database for the user
    $stmt = $db_connection->prepare("UPDATE users SET reset_token = ?, reset_token_expiry = ? WHERE email = ?");
    $stmt->execute([$hashed_token, time() + 3600, $email]); // Store expiry 1 hour from now

    // 5.  Email the user with a reset link
    $reset_link = "http://yourwebsite.com/reset-password?token=$token"; // Replace with your website URL

    // Email the user
    $to = $email;
    $subject = "Password Reset Request";
    $message = "Please click on the following link to reset your password: " . $reset_link;
    $headers = "From: yourwebsite@example.com"; // Replace with your email address

    mail($to, $subject, $message, $headers);

    return true;
}


// Example usage (assuming you've called forgotPassword() with an email)
// $email_to_reset = "testuser@example.com"; // Replace with the user's email
// $result = forgotPassword($email_to_reset);

// if ($result) {
//     echo "Password reset email has been sent.";
// } else {
//     echo "User not found or email reset link could not be generated.";
// }


//Important Security Notes & Enhancements:

// 1.  Database Connection:  Replace `$db_connection` with your actual database connection object.  This example assumes you're using PDO.

// 2.  Error Handling:  Add more robust error handling.  Check the return values of your database queries.  Log errors.

// 3.  Password Hashing:  The `password_hash()` function is critical.  Never store passwords in plain text.

// 4.  Token Uniqueness and Randomness:  `random_bytes()` is used to generate the token.  This is the most secure method for generating random data.  Using `mt_rand()` or `rand()` is generally not recommended for security-sensitive operations.

// 5.  Token Expiry:  The `reset_token_expiry` field and `time() + 3600` (one hour) are added to prevent tokens from being used indefinitely. Adjust the expiry time as needed.  Consider logging expiry events.

// 6.  Rate Limiting: Implement rate limiting on password reset requests to prevent brute-force attacks.  Store the number of reset attempts per user and block access if the limit is exceeded.

// 7.  Secure URL:  The `reset_link` URL should be generated securely.  Don't include the token directly in the URL string in a production environment.  Use URL encoding or a more secure method.

// 8.  Input Validation:  Validate the email address to prevent malicious input.

// 9.  Security Audits: Regularly perform security audits of your code to identify and address vulnerabilities.

// 10. CSRF Protection: Implement CSRF (Cross-Site Request Forgery) protection, especially if you are generating the reset link on a form submission.

// 11. Logging:  Log password reset requests, including the user's email, the token generated, and the timestamp. This is important for debugging and security monitoring.
?>


<?php

/**
 * Resets a user's password via email.
 *
 * @param string $email The email address of the user.
 * @param string $newPassword The new password to set.
 * @param string $websiteUrl The URL of your website. (Optional, for link in email)
 * @return bool True on success, false on failure.
 */
function forgotPassword(string $email, string $newPassword, string $websiteUrl = '') {
  // **Security Note:**  This is a simplified example for demonstration.
  // In a production environment, you *MUST* implement robust security measures,
  // including:
  // 1.  Hashing the password before storing it in the database.
  // 2.  Validating email format.
  // 3.  Rate limiting to prevent brute-force attacks.
  // 4.  Using a dedicated email sending service (e.g., SendGrid, Mailgun)
  //     for better deliverability and security.

  // Check if email exists in the database (replace with your actual database logic)
  $user = getUserByEmail($email);

  if (!$user) {
    return false; // User not found
  }

  // Update the user's password in the database (replace with your actual database logic)
  if (!updateUserPassword($user, $newPassword)) {
    return false; // Password update failed
  }


  // Send password reset email
  $subject = 'Password Reset';
  $body = "Please use the following link to reset your password:
" .
          "<a href='" . $websiteUrl . "/reset_password?token=" . generateResetToken($user->id) . "'>Reset Password</a>";
  $headers = "From: " . 'Your Website Name <noreply@yourwebsite.com>' . "\r
";
  // Use mail() for simplicity, but consider a dedicated email sending service.
  if (mail($email, $subject, $body, $headers)) {
    return true;
  } else {
    // Email sending failed - you should log this error.
    return false;
  }
}


/**
 * Placeholder function to retrieve a user by email.  Replace with your database query.
 *
 * @param string $email The email address to search for.
 * @return object|null User object if found, null otherwise.
 */
function getUserByEmail(string $email) {
  // Replace this with your actual database query.
  // Example (assuming you have a 'users' table with an 'email' column)
  // $db = new PDO(/* your database connection details */);
  // $stmt = $db->prepare("SELECT * FROM users WHERE email = ?");
  // $stmt->execute([$email]);
  // $user = $stmt->fetch(PDO::FETCH_OBJ);
  // return $user;

  // Mock user object for demonstration
  $user = new stdClass();
  $user->id = 123; // Example user ID
  return $user;
}


/**
 * Placeholder function to update a user's password in the database.
 * Replace with your actual database query.
 *
 * @param object $user The user object to update.
 * @param string $newPassword The new password to set.
 * @return bool True on success, false on failure.
 */
function updateUserPassword(object $user, string $newPassword) {
  // Replace this with your actual database query.
  // Example:
  // $db = new PDO(/* your database connection details */);
  // $stmt = $db->prepare("UPDATE users SET password = ? WHERE id = ?");
  // $stmt->execute([password_hash($newPassword, PASSWORD_DEFAULT), $user->id]);
  // return true;

  // Mock success for demonstration
  return true;
}

/**
 * Generates a unique reset token.
 *
 * @param int $userId The ID of the user.
 * @return string  A unique token.
 */
function generateResetToken() {
  return bin2hex(random_bytes(32)); // Use a cryptographically secure random number generator
}



// --- Example Usage ---
// You would call this function from your form submission code:

// $email = $_POST['email'];
// $newPassword = $_POST['newPassword'];

// if (isset($email) && isset($newPassword)) {
//   if (forgotPassword($email, $newPassword)) {
//     echo "Password reset email has been sent.  Check your inbox!";
//   } else {
//     echo "Error resetting password. Please try again.";
//   }
// } else {
//   echo "Please enter your email and a new password.";
// }


// --- Note:  This is a VERY simplified example. ---
// In a real application, you would:
// 1.  Validate the email and password input thoroughly.
// 2.  Use a dedicated email sending service for better reliability and security.
// 3.  Implement robust security measures to protect against attacks.
// 4.  Handle errors gracefully.


<?php

// Assuming you have a database connection established (e.g., $db connection)

/**
 * Resets a user's password.
 *
 * @param string $email The user's email address.
 * @return bool True if the password was reset successfully, false otherwise.
 */
function forgotPassword(string $email)
{
    // 1. Validate Email (Important!)
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        error_log("Invalid email format provided."); // Log the error for debugging
        return false;
    }

    // 2. Check if User Exists
    $user = getUserByEmail($email); //  Assumed function to get user by email
    if (!$user) {
        error_log("User with email '$email' not found."); //Log the error
        return false;
    }

    // 3. Generate a Unique Token (for security)
    $token = generateUniqueToken(); //  Assumed function to generate a unique token

    // 4. Store Token and User ID in a Temporary Table
    //    (This is important for security -  don't store tokens directly in the main user table)
    $query = "INSERT INTO password_reset_tokens (user_id, token, expires_at)
              VALUES ($user->id, '$token', NOW() + INTERVAL 24 HOUR)";
    mysqli_query($GLOBALS['db'], $query); // Use mysqli_query or PDO for better security.

    // 5.  Send Password Reset Email (Email Logic - Not Implemented Here)
    //   This is where you would send an email with a link containing the token.
    //   The email link should lead to a page where the user can enter a new password.
    //   Example:
    //   $subject = "Password Reset";
    //   $to = $user->email;
    //   $headers = "From: your-email@example.com";
    //   $link = "/reset-password?token=$token";
    //   mail($to, $subject, $link, $headers);
    //   echo "Password reset email sent to $email. Check your inbox.";


    return true; // Indicate successful token generation and storage.
}


/**
 * Assumed function to get user by email.  Implement this based on your database.
 * @param string $email
 * @return mysqli_result|null
 */
function getUserByEmail(string $email) {
    // Replace with your database query
    // This is just a placeholder.  Adjust to your database and setup.
    // Example using mysqli:
    // $query = "SELECT * FROM users WHERE email = '$email'";
    // $result = mysqli_query($GLOBALS['db'], $query);
    // if (mysqli_num_rows($result) > 0) {
    //   return mysqli_fetch_assoc($result);
    // } else {
    //   return null;
    // }
    //  A placeholder returning a dummy user object
    return new \stdClass(); // Returns a new empty object.
}


/**
 * Assumed function to generate a unique token.
 * @return string
 */
function generateUniqueToken()
{
    return bin2hex(random_bytes(32)); // Generates a 32-byte random hex string
}



// Example Usage (Illustrative - Not part of the forgotPassword function)
// You would typically trigger this through a form submission.
// $email = $_POST['email'];
// if (isset($email)) {
//     if (forgotPassword($email)) {
//         echo "Password reset email sent to $email.";
//     } else {
//         echo "Failed to generate password reset token.";
//     }
// }


?>


<?php

// Database credentials (Replace with your actual credentials)
$host = 'localhost';
$username = 'your_username';
$password = 'your_password';
$database = 'your_database';

// Function to reset password
function forgot_password($email) {
  // 1. Validate Email
  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    return "Invalid email format.";
  }

  // 2. Check if user exists
  $conn = new mysqli($host, $username, $password, $database);

  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  $sql = "SELECT id, username FROM users WHERE email = '$email'";
  $result = $conn->query($sql);

  if ($result->num_rows > 0) {
    $user_id = $result->fetch_assoc()['id'];
    $username = $result->fetch_assoc()['username'];

    // 3. Generate a unique token
    $token = bin2hex(random_bytes(32));

    // 4.  Store Token and User ID in a temporary table (for security)
    $conn->query("CREATE TEMPORARY TABLE IF NOT EXISTS reset_tokens (user_id INT, token VARCHAR(32))");
    $conn->query("INSERT INTO reset_tokens (user_id, token) VALUES ($user_id, '$token')");


    // 5.  Prepare the reset email
    $to = $email;
    $subject = 'Password Reset Request';
    $message = "Click this link to reset your password: " . '<a href="' . $_SERVER['PHP_SELF'] . '?reset=true&token=' . $token . '"' . ' >Reset Password</a>';
    $headers = "From: your_email@example.com"; // Replace with your email

    mail($to, $subject, $message, $headers);

    // 6. Clean up temporary table
    $conn->query("DROP TEMPORARY TABLE IF EXISTS reset_tokens");


    return "Password reset email sent to $email.";

  } else {
    return "User not found.";
  }

  $conn->close();
}

// ---  Example Usage (For testing - remove this section when integrating into your application) ---
//  This is just for demonstrating the function.  In a real application, you'd
//  likely handle this via a form submission.

if (isset($_GET['reset']) && isset($_GET['token'])) {
  $token = $_GET['token'];

  //  This is where you would check if the token exists in your database.
  //  This example doesn't include the check for simplicity, but it's crucial
  //  for security in a production environment.

  $reset_result = forgot_password($token); // Using the token as the email (for demonstration)
  echo $reset_result; // This should be the output from the forgot_password function
}

?>


<?php

// Configuration (Important - Replace with your actual values)
$db_host = 'localhost';
$db_name = 'your_database_name';
$db_user = 'your_database_username';
$db_password = 'your_database_password';

// Function to reset password
function forgot_password($email, $new_password) {
    // 1. Connect to the database
    $conn = new mysqli($db_host, $db_user, $db_password, $db_name);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // 2. Hash the new password -  IMPORTANT for security!
    $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

    // 3. Update the password in the database
    $sql = "UPDATE users SET password = '$hashed_password' WHERE email = '$email'";

    if ($conn->query($sql) === TRUE) {
        echo "New password set successfully! Please check your email for login instructions.";
    } else {
        echo "Error updating password: " . $conn->error;
    }

    $conn->close();
}


// Example usage (This is just for demonstration - you'll need a form to submit this)
// In a real application, you would handle the form submission here.
// This example shows how the function would be called.
// For a real application, you'll need to implement a form with an email field.

// Example: Let's say the user's email is 'test@example.com' and the new password is 'newPassword123'
//forgot_password('test@example.com', 'newPassword123');
?>


<?php

/**
 * Forgot Password Function
 *
 * This function handles the forgot password process, generating a temporary
 * password and sending it to the user's email.
 *
 * @param string $email The user's email address.
 * @param string $resetLinkBase URL where the reset links will be generated.  
 *                              Should be a URL that doesn't require authentication.
 * @return bool True if the reset email was sent successfully, false otherwise.
 */
function forgotPassword(string $email, string $resetLinkBase) {
  // 1. Sanitize and Validate Email
  $email = filter_var($email, FILTER_SANITIZE_EMAIL);
  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    error_log("Invalid email address provided: " . $email); // Log for debugging
    return false;
  }

  // 2. Generate a Temporary Password (e.g., random string)
  $resetPassword = generateRandomString(8); //  Adjust length as needed
  
  // 3. Store the Reset Token in the Database
  $token = hash('sha256', $resetPassword . time());  // Use a more secure hashing algorithm
  
  // IMPORTANT: In a real-world application, you would store this data in a database.
  // For demonstration purposes, we'll use a simple array.
  $resetData = [
    'email' => $email,
    'token' => $token,
    'expiry' => time() + 3600  // Expire after 1 hour
  ];
  
  // 4.  Simulate Database Storage (Replace with your database logic)
  // In a real app, you'd use something like:
  // $db->insert('forgot_passwords', $resetData); 
  
  // 5. Generate the Reset Link
  $resetLink = $resetLinkBase . '?token=' . urlencode($token);

  // 6. Send the Reset Email
  $to = $email;
  $subject = 'Password Reset';
  $message = "To reset your password, please click on the following link: " . $resetLink;
  $headers = "From: YourWebsite <noreply@yourwebsite.com>"; // Customize the sender address
  
  if (mail($to, $subject, $message, $headers)) {
    return true;
  } else {
    error_log("Failed to send reset email to: " . $email); // Log email sending failure
    return false;
  }
}

/**
 * Generates a random string of the specified length.
 *
 * @param int $length The desired length of the random string.
 * @return string The generated random string.
 */
function generateRandomString(int $length) {
  $characters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
  $randomString = '';
  $length = $length;
  for ($i = 0; $i < $length; $i++) {
    $char = $characters[rand(0, strlen($characters) - 1)];
    $randomString .= $char;
  }
  return $randomString;
}

// --- Example Usage ---
// Replace with your actual email address
$email = "testuser@example.com";
$resetLinkBase = "https://yourwebsite.com/reset-password";  // Change this to your website's base URL

if (forgotPassword($email, $resetLinkBase)) {
  echo "Reset email sent successfully! Check your inbox.";
} else {
  echo "Failed to send reset email. Please check your email settings and try again.";
}

?>


<?php

// Assuming you have a database connection established and stored in a variable like $db

/**
 * Forgets the password for a user.
 *
 * @param string $email The email address of the user.
 * @return bool True on success, false on failure.
 */
function forgot_password(string $email): bool
{
    // 1. Validate Input (Crucial for Security)
    if (empty($email)) {
        error_log("Forgot Password: Empty email provided."); // Log the error
        return false;
    }

    // 2.  Check if the user exists
    $user = get_user_by_email($email); // Implement this function (see below)
    if (!$user) {
        error_log("Forgot Password: User with email {$email} not found.");
        return false;
    }

    // 3. Generate a Unique Token (Securely)
    $token = generate_unique_token(); // Implement this function (see below)

    // 4.  Store Token in Database (Associating with User)
    //    This is where you'd update the 'token' column in your user table.
    update_user_token($user['id'], $token); // Implement this function (see below)

    // 5.  Send Password Reset Email
    //    You'll need to format and send an email with a link to the reset page
    //    The email link should include the token.
    send_password_reset_email($user['email'], $token); // Implement this function (see below)

    return true;
}



/**
 *  Helper functions (Implement these based on your database schema)
 */

/**
 *  Retrieves a user's data by email.  This is just an example.
 *  Replace with your actual database query.
 *
 *  @param string $email
 *  @return array|null An associative array containing user data if found, null otherwise.
 */
function get_user_by_email(string $email): ?array
{
    // Replace this with your database query to get user data by email
    // Example (using MySQLi) -  Adapt to your database system
    $conn = new mysqli("localhost", "username", "password", "database");

    if ($conn->connect_error) {
      die("Connection failed: " . $conn->connect_error);
    }

    $sql = "SELECT id, email FROM users WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();

    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        $stmt->close();
        return $row;
    } else {
        $stmt->close();
        return null;
    }
}



/**
 * Generates a unique token. Use a cryptographically secure random number generator.
 *
 * @return string A unique token.
 */
function generate_unique_token(): string
{
    return bin2hex(random_bytes(32));  // More secure than rand()
}



/**
 * Updates the 'token' column in the user's record with the given token.
 *
 * @param int $userId The ID of the user.
 * @param string $token The token to store.
 */
function update_user_token(int $userId, string $token): void
{
    // Replace this with your database query to update the 'token' column.
    // Example (using MySQLi)
    $conn = new mysqli("localhost", "username", "password", "database");

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $sql = "UPDATE users SET token = ? WHERE id = ?";
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("ss", $token, $userId);
        if ($stmt->execute()) {
            $stmt->close();
        } else {
            error_log("Error updating token for user ID {$userId}: " . $conn->error);
        }
    } else {
        error_log("Error preparing update statement: " . $conn->error);
    }

    $conn->close();
}



/**
 * Sends an email with a password reset link.
 *
 * @param string $email The email address of the user.
 * @param string $token The token associated with the password reset request.
 */
function send_password_reset_email(string $email, string $token): void
{
    // Implement the logic to send the email. This is a placeholder.
    // Use a reliable email sending service (e.g., SendGrid, Mailgun, AWS SES)
    // or configure your own SMTP server.

    //Example of what the email content might look like:
    $subject = 'Password Reset';
    $message = "Click on the following link to reset your password: " . "<a href='" . $_SERVER['PHP_SELF'] . "?reset_token=" . urlencode($token) . "'>Reset Password</a>";
    //  Replace this with your actual email sending function
    //  For example:
    //  mail($email, $subject, $message);

    error_log("Password reset email sent to {$email} with token {$token}");
}


// Example Usage (IMPORTANT:  Use this only in testing.  Never in production!)
//  To test, you'd call this from a form submission (e.g., using POST)
//  and the reset_token would be passed from the form to the server.
//  Uncomment the code below for testing ONLY.
/*
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST["email"];

    if (forgot_password($email)) {
        echo "Password reset email sent to {$email}.";
    } else {
        echo "Failed to initiate password reset.";
    }
}
*/

?>


<?php

// Assuming you have a database connection established
// Replace 'your_db_connection' with your actual connection details
$db = new mysqli('localhost', 'username', 'password', 'database_name');

// Check connection
if ($db->connect_error) {
    die("Connection failed: " . $db->connect_error);
}

/**
 * Forgets a user's password and sends a password reset email.
 *
 * @param string $email The user's email address.
 * @return bool True if email was sent, false otherwise.
 */
function forgot_password($email) {
  // Sanitize the email to prevent potential security issues
  $email = filter_var($email, FILTER_SANITIZE_EMAIL);

  // Validate email format
  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    return false; // Invalid email format
  }

  // Check if the user exists
  $result = $db->query("SELECT id, username FROM users WHERE email = '$email'");

  if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
    $userId = $user['id'];
    $username = $user['username'];

    // Generate a unique, random password reset token
    $reset_token = bin2hex(random_bytes(32));

    // Store the token in the database for the user
    $db->query("UPDATE users SET reset_token = '$reset_token' WHERE id = $userId");

    // Prepare a password reset email
    $to = $email;
    $subject = 'Password Reset';
    $message = "Click this link to reset your password: " . '<a href="' . $_SERVER['PHP_SELF'] . '?reset=' . urlencode($reset_token) . '"' . ' target="_blank">' . '<a href="' . $_SERVER['PHP_SELF'] . '?reset=' . urlencode($reset_token) . '"' . ' target="_blank">' . $_SERVER['PHP_SELF'] . '?reset=' . urlencode($reset_token) . '</a>';
    $headers = "From: your_email@example.com" . "\r
";
    mail($to, $message, $headers);

    return true; // Email sent successfully
  } else {
    return false; // User not found
  }
}


// Example Usage (This is for demonstration - you'll need to handle form submission and validation)
if ($_SERVER["REQUEST_METHOD"] == "GET") {
    // Get the email from the form (assuming a form submitted)
    $email = $_POST['email']; // Get the email from the form
    if (forgot_password($email)) {
        echo "Password reset email has been sent to " . $email;
    } else {
        echo "Email not found or an error occurred.";
    }
}

// Prevent direct access to this script
if ($_SERVER["REQUEST_METHOD"] != "GET") {
  header("Location: index.php"); // Redirect to a welcome page or login form.
}


?>


<?php

// Configuration (Change these to your specific setup)
$db_host = 'localhost';
$db_name = 'your_database_name';
$db_user = 'your_database_user';
$db_password = 'your_database_password';

// Function to handle password reset
function forgot_password($email) {
  // 1. Validate Email
  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    return "Invalid email address.";
  }

  // 2. Generate a unique token
  $token = bin2hex(random_bytes(32)); // Secure random token

  // 3. Prepare the query
  $mysqli = new mysqli($db_host, $db_user, $db_password, $db_name);

  if ($mysqli->connect_errno) {
    return "Failed to connect to database: " . $mysqli->connect_error;
  }

  $sql = "INSERT INTO password_resets (email, token, created_at) VALUES (?, ?, NOW())";
  $stmt = $mysqli->prepare($sql);

  if (!$stmt) {
    return "Failed to prepare statement.";
  }

  $stmt->bind_param("s", $email, $token);

  if (!$stmt->execute()) {
    return "Failed to insert data: " . $stmt->error;
  }

  $stmt->close();
  $mysqli->close();

  // 4.  Send the reset email (Implementation details depend on your email sending method)
  //     This is a placeholder.  Replace with your actual email sending code.
  send_reset_email($email, $token);  // Call the function to send the email.

  return "Password reset email sent to $email.";
}


// Placeholder function for sending reset email.  REPLACE THIS with your actual email sending code.
function send_reset_email($email, $token) {
  //  This is just a demo.  In a real application, you'd use a library like PHPMailer or
  //  a service like SendGrid or Mailgun.

  $subject = 'Password Reset - Your Account';
  $message = "Click the following link to reset your password:  http://yourwebsite.com/reset_password.php?token=" . $token;
  $headers = 'From: your_email@example.com' . "\r
";

  // In a real application, you'd use mail() or a library to send the email.
  // Example using mail():
  // mail($email, $subject, $message, $headers);

  echo "Simulating sending email to $email with token $token"; // For demonstration purposes.
}

// Example Usage (For testing)
// You'll likely want to call this from a form, not directly like this.

// Example 1: Successful reset
$result = forgot_password("testuser@example.com");
echo $result . "<br><br>";

// Example 2: Invalid email
$result = forgot_password("invalid-email");
echo $result . "<br><br>";
?>


<?php

// Include the database connection file (assuming it's named database.php)
require_once 'database.php';

/**
 * Forgets a user's password.
 *
 * @param string $email The user's email address.
 * @return bool True if a password reset email was sent, false otherwise.
 */
function forgot_password(string $email): bool
{
  // Sanitize the email address to prevent SQL injection
  $email = mysqli_real_escape_string($connection, $email);

  // Check if the email exists in the users table
  $result = mysqli_query($connection, "SELECT id, username FROM users WHERE email = '$email'");

  if (mysqli_num_rows($result) > 0) {
    // Email exists, generate a unique token and store it
    $token = bin2hex(random_bytes(32));  // Generate a random token
    $hashed_token = password_hash($token, PASSWORD_DEFAULT); // Hash the token for security

    // Store the token in the database (associated with the user)
    $sql = "UPDATE users SET reset_token = '$hashed_token' WHERE email = '$email'";

    if (mysqli_query($connection, $sql)) {
      // Send a password reset email (implementation depends on your email sending system)
      // This is just a placeholder - replace with your email sending code
      send_password_reset_email($email, $token);

      return true;
    } else {
      // Error sending the email or updating the database
      error_log("Error updating user with token: " . mysqli_error($connection));
      return false;
    }
  } else {
    // Email does not exist
    return false;
  }
}

/**
 * Placeholder function for sending the password reset email.
 *
 * @param string $email The user's email address.
 * @param string $token The generated reset token.
 */
function send_password_reset_email(string $email, string $token): void
{
  // Replace with your email sending logic here.
  // This is a placeholder - it does not actually send an email.
  error_log("Password reset email sent to: " . $email . " with token: " . $token);
  // Example using a basic email sending function (you'd need to configure this)
  /*
  $to = $email;
  $subject = 'Password Reset';
  $message = 'Click this link to reset your password: ' . 'http://yourwebsite.com/reset_password.php?token=' . $token;
  $headers = 'From: yourwebsite@example.com' . "\r
";

  mail($to, $message, $headers);
  */
}


// Example Usage (for testing - DO NOT use this directly in a deployed application)
//  This is just an example for demonstration and should not be directly used in a production environment.

/*
$user_email = "testuser@example.com"; // Replace with a valid email address
if (forgot_password($user_email)) {
    echo "Password reset email sent to $user_email";
} else {
    echo "Failed to generate password reset token.";
}
*/

?>


<?php

// Replace with your database connection details
$host = "localhost";
$username = "your_db_username";
$password = "your_db_password";
$database = "your_database_name";

// Check if the reset token is present in the query string
if (isset($_GET['token'])) {
  $token = $_GET['token'];

  // 1. Validate the token
  if (!isValidToken($token)) {
    echo "Invalid reset token.";
    exit;
  }

  // 2. Retrieve the user's email from the database based on the token
  $user = getUserByToken($token);

  if ($user) {
    // 3. Generate a new, unique reset token and send an email with a link
    $newToken = generateUniqueToken();
    $email = $user['email'];

    //  --  Email Sending Logic --
    //  Replace with your email sending library/function
    $subject = "Password Reset - Your Account";
    $message = "Click on this link to reset your password: " . base_url() . "/reset-password?token=" . $newToken;
    $headers = "From: your_email@example.com" . "\r
"; // Replace with your email address
    mail($email, $message, $headers);

    // 4.  Update the user's record with the new token (optional, but good practice)
    updateUserToken($user['id'], $newToken);

    echo "Reset link has been sent to your email.";
  } else {
    echo "User not found with that token.";
  }
} else {
  echo "Please provide a reset token.";
}


// --- Helper Functions ---

// 1. Validate the token
function isValidToken($token) {
    // Implement your token validation logic here. 
    // This could involve checking against a database table 
    // that stores used tokens and their expiration times.

    // Example: (Replace with your actual validation)
    return true; //  Placeholder -  Replace with your actual validation
}


// 2. Retrieve the user by token
function getUserByToken($token) {
  global $host, $username, $password, $database;

  // Database connection
  $conn = new mysqli($host, $username, $password, $database);

  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  // SQL Query
  $sql = "SELECT * FROM users WHERE reset_token = '$token'"; // Assuming 'reset_token' column in your users table

  $result = $conn->query($sql);

  if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
    return $user;
  } else {
    return null;
  }

  $conn->close();
}



// 3. Generate a unique token
function generateUniqueToken() {
  return bin2hex(random_bytes(32)); // Returns a 32-byte random string.
}


// 4. Update the user's token (optional, but recommended)
function updateUserToken($userId, $newToken) {
    global $host, $username, $password, $database;

    $conn = new mysqli($host, $username, $password, $database);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $sql = "UPDATE users SET reset_token = '$newToken' WHERE id = $userId";
    if ($conn->query($sql) === TRUE) {
        //echo "User token updated successfully";
    } else {
        echo "Error updating token: " . $conn->error;
    }

    $conn->close();
}


// Example base_url function (requires you to define it)
//  This assumes you are using URL rewriting.
function base_url() {
    // Adjust this based on your application setup.
    return "http://localhost/your_project_name/";
}

?>


<?php

// Assuming you have a database connection established (e.g., $db)

/**
 * Resets a user's password.
 *
 * @param string $email The user's email address.
 * @return bool True if a reset link was generated and sent, false otherwise.
 */
function forgotPassword(string $email)
{
    // 1. Check if the email exists in the database
    $user = db_get_user_by_email($email); // Replace with your DB query
    if (!$user) {
        error_log("User with email {$email} not found.");  // Log for debugging
        return false;
    }


    // 2. Generate a unique reset token
    $resetToken = generateUniqueToken();

    // 3. Store the token and user ID in the database
    $result = db_create_reset_token($user->id, $resetToken);

    if (!$result) {
        error_log("Failed to create reset token for user {$email}.");
        return false;
    }

    // 4. Generate the reset link
    $resetLink = generateResetLink($resetToken);

    // 5. Send the reset link via email
    if (!sendEmailWithResetLink($user->email, $resetLink) ) {
      //Handle email sending failure - log, display message, etc.
        error_log("Failed to send reset email to {$user->email}");
        //Optionally:  Delete the reset token from the database to prevent abuse.
        db_delete_reset_token($resetToken, $user->id);
        return false;
    }


    // 6. Return true, indicating success
    return true;
}



/**
 * Placeholder function to retrieve a user by email (replace with your DB query)
 * @param string $email
 * @return User|null  A User object or null if not found
 */
function db_get_user_by_email(string $email): ?User {
    // Example using a fictional User class
    // Replace this with your actual database query
    // This is a simplified example.  Don't use this directly in production.

    //Example using a fictional User Class
    //Replace with your database query
    //This is a simplified example.  Don't use this directly in production.

    // Assume User class:
    // class User {
    //     public $id;
    //     public $email;
    //     // ... other user attributes
    // }

    $user = new User();
    $user->email = $email;  // Simulate fetching from the database
    return $user;
}


/**
 * Placeholder function to generate a unique token.
 * In a real application, use a robust library for generating cryptographically secure tokens.
 * @return string
 */
function generateUniqueToken(): string
{
    return bin2hex(random_bytes(32));  // Generate a random 32-byte (256-bit) hex string
}


/**
 * Placeholder function to generate the reset link.
 * @param string $token
 * @return string
 */
function generateResetLink(string $token): string
{
    return "http://example.com/reset-password?token=" . urlencode($token);
}


/**
 * Placeholder function to send the email with the reset link.
 * Replace with your email sending logic.
 * @param string $email
 * @param string $resetLink
 */
function sendEmailWithResetLink(string $email, string $resetLink): bool
{
    //  Replace this with your actual email sending implementation.
    //  Use a library like PHPMailer or Swift Mailer for robust email sending.

    // Simulate sending an email (for testing)
    error_log("Simulating sending reset email to {$email} with link: {$resetLink}");
    return true;
}


/**
 * Placeholder function to delete a reset token from the database.
 * @param string $token
 * @param int $userId
 */
function db_delete_reset_token(string $token, int $userId): bool {
  // Replace with your database deletion logic
  // Example:
  // $result = db_query("DELETE FROM reset_tokens WHERE token = '$token' AND user_id = $userId");
  // return $result->rowCount > 0;

  error_log("Simulating deleting reset token for user {$userId} with token: {$token}");
  return true;
}




// Example Usage (Testing)
if (isset($_POST['email'])) {
    $email = $_POST['email'];
    if (forgotPassword($email)) {
        echo "Reset link sent to {$email}. Check your email.";
    } else {
        echo "Failed to generate reset link. Please try again.";
    }
}

?>


<?php

// Assuming you have a database connection established
// (e.g., using mysqli or PDO)
// For this example, we'll assume a simple $db connection is already set up

// Function to handle password reset requests
function forgot_password($email) {
  // 1. Validate the email
  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    return "Invalid email address.";
  }

  // 2. Generate a unique token
  $token = bin2hex(random_bytes(32)); //  More secure than older methods
  // Consider storing the token in a database table (e.g., 'reset_tokens')

  // 3.  Set up the token expiry (optional, but recommended)
  //   -  This prevents tokens from being used indefinitely.
  $expiry = time() + 3600; // Token expires in 1 hour (3600 seconds)


  // 4.  Simulate database insertion (replace with your actual database logic)
  //   This part simulates inserting the token into a database table.
  //   In a real application, this would use a database query to insert
  //   the email and token into the 'reset_tokens' table.

  $reset_token_data = [
    'email' => $email,
    'token' => $token,
    'expiry' => $expiry,
    'created_at' => time() // Record the creation timestamp
  ];

  //  Example:  Storing in an array for demonstration.
  //  In a real application, this would be a database insertion.
  //  $db->insert('reset_tokens', $reset_token_data);


  // 5.  Send the reset link (e.g., via email)
  //   -  Build the reset link URL
  $reset_link = "http://yourdomain.com/reset_password?token=" . $token . "&expiry=" . $expiry;

  //   -  Send the email. You can use a library like PHPMailer.
  //   -  Example using a simple echo to demonstrate the link.
  //   echo "<p>Please click the following link to reset your password:</p><a href='" . $reset_link . "' >Reset Password</a>";


  // 6.  Return a success message (or any relevant information)
  return "A password reset link has been sent to your email address.  Please check your inbox.";
}


// Example usage:
$email_to_reset = "testuser@example.com"; // Replace with the user's email
$reset_message = forgot_password($email_to_reset);
echo $reset_message;
?>


<?php

// Assuming you have a database connection established and available as $db

/**
 * Resets a user's password.
 *
 * @param string $email The email address of the user requesting a password reset.
 * @return bool True if a password reset email was sent, false otherwise.
 */
function forgot_password(string $email): bool
{
    // 1. Validate Email (important for security)
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        error_log("Invalid email address provided."); // Log for debugging
        return false;
    }

    // 2. Check if User Exists
    $user = getUserByEmail($email); // Assumes you have a function to fetch user by email
    if ($user === null) {
        error_log("User with email $email not found."); // Log for debugging
        return false;
    }

    // 3. Generate a Unique Token
    $reset_token = generate_unique_token();

    // 4. Store Token in Database (Temporary - Expire after some time)
    //    - Key: User ID
    //    - Value: Token
    save_reset_token($user['id'], $reset_token);

    // 5. Send Password Reset Email
    $subject = "Password Reset Request";
    $headers = "From: your_email@example.com"; // Replace with your email
    $message = "Click <a href='" . $_SERVER['PHP_SELF'] . "?reset=" . urlencode($reset_token) . "'>here</a> to reset your password.";

    if (send_email($user['email'], $subject, $headers, $message)) {
        return true;
    } else {
        error_log("Failed to send password reset email.");  // Log for debugging
        return false;
    }
}


/**
 * Dummy functions for illustration purposes.
 * Replace these with your actual implementation.
 */

function getUserByEmail(string $email): ?array
{
    // Replace this with your actual database query to fetch the user.
    // Example:
    // $query = "SELECT * FROM users WHERE email = ?";
    // $stmt = $db->prepare($query);
    // $stmt->execute([$email]);
    // $user = $stmt->fetch(PDO::FETCH_ASSOC);
    // return $user ?: null;

    // Dummy data for testing
    $dummy_users = [
        ['id' => 1, 'email' => 'test@example.com', 'password' => 'password123'],
        ['id' => 2, 'email' => 'another@example.com', 'password' => 'securepass']
    ];
    foreach($dummy_users as $user) {
        if ($user['email'] === $email) {
            return $user;
        }
    }
    return null;
}

function generate_unique_token(): string
{
    return bin2hex(random_bytes(32)); // Use a cryptographically secure random number generator
}

function save_reset_token(int $userId, string $token): void
{
    // Implement your database logic here to save the token
    // Example:
    // $query = "INSERT INTO password_resets (user_id, token, expiry_date) VALUES (?, ?, ?)";
    // $stmt = $db->prepare($query);
    // $stmt->execute([$userId, $token, time() + 3600]); // Token expires in 1 hour
}

function send_email(string $to, string $subject, string $headers, string $message): bool
{
    // Implement your email sending logic here.
    // Use a library like PHPMailer: https://github.com/PHPMailer/PHPMailer
    // For example:
    // $mail = new PHPMailer(true);
    // $mail->SetFrom('your_email@example.com', 'Your Name');
    // $mail->AddAddress($to);
    // $mail->SetSubject($subject);
    // $mail->MsgBody($message, 'html');
    // return $mail->send();

    // Dummy implementation - just return true for testing
    echo "Sending email to $to with subject $subject
";
    return true;
}



// Example Usage (for testing)
// You would typically call this function from a form submission.
// For testing, you can call it directly
// $email = "test@example.com";
// if (forgot_password($email)) {
//     echo "Password reset email sent to $email.
";
// } else {
//     echo "Failed to send password reset email.
";
// }
?>


<?php

// Database connection details - Replace with your actual credentials
$host = "localhost";
$username = "your_db_username";
$password = "your_db_password";
$dbname = "your_db_name";

// Function to reset password
function forgotPassword($email, $dbname, $host, $username, $password) {
  // 1. Validate Email
  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    return "Invalid email address.";
  }

  // 2.  Check if user exists
  $conn = new mysqli($host, $username, $password, $dbname);
  if ($conn->connect_error) {
    return "Connection failed: " . $conn->connect_error;
  }

  $sql = "SELECT id, username FROM users WHERE email = '$email'";
  $result = $conn->query($sql);

  if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
    $userId = $user['id'];
    $userName = $user['username'];

    // 3. Generate a unique token
    $token = bin2hex(random_bytes(32)); // Secure random token
    
    // 4.  Store token in database for user
    $sql = "UPDATE users SET reset_token = '$token' WHERE id = '$userId'";
    if (!$conn->query($sql)) {
      return "Error updating token: " . $conn->error;
    }

    // 5.  Send email with reset link
    $subject = "Password Reset Link";
    $to = $email;
    $headers = "From: your_email@example.com"; // Replace with your email
    $resetLink = "http://localhost/reset_password.php?token=$token"; // Replace with your reset link URL
    $message = "Click on the link above to reset your password.";

    mail($to, $message, $headers);
    return "Password reset email has been sent to $email.";

  } else {
    return "User not found.";
  }

  $conn->close();
}


// Example usage (Handle form submission)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $email = $_POST["email"];
  $result = forgotPassword($email, $dbname, $host, $username, $password);
  echo "<p>" . $result . "</p>";
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>Forgot Password</title>
</head>
<body>

  <h1>Forgot Password</h1>

  <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
    <label for="email">Email:</label>
    <input type="email" id="email" name="email" required>
    <button type="submit">Reset Password</button>
  </form>

</body>
</html>


<?php

// Configuration (Replace with your actual values)
$db_host = 'localhost';
$db_name = 'your_database_name';
$db_user = 'your_database_username';
$db_password = 'your_database_password';

// Function to reset password
function forgotPassword($email, $new_password) {
  // 1. Connect to the database
  $conn = new mysqli($db_host, $db_user, $db_password, $db_name);

  // Check connection
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  // 2. Prepare and execute the SQL query
  $sql = "UPDATE users SET password = ? WHERE email = ?";
  $stmt = $conn->prepare($sql);

  if ($stmt) {
    $stmt->bind_param("ss", $new_password, $email);  // Use 'ss' for string

    if ($stmt->execute()) {
      // Password reset successful
      $stmt->close();
      return true;
    } else {
      // Password reset failed
      $stmt->close();
      return false;
    }
  } else {
    // Prepare statement failed
    $stmt = null; // Ensure $stmt is null to avoid issues
    return false;
  }
}


// Example Usage (For testing - **DO NOT USE THIS IN A PRODUCTION ENVIRONMENT**)
// **Important Security Considerations - See Below**

// Test email and new password (replace with actual values for testing)
$test_email = 'test@example.com';  // Replace with a valid email address for testing
$new_password = 'NewPassword123'; // Replace with a strong password


// Call the function to reset the password
if (forgotPassword($test_email, $new_password)) {
  echo "Password reset successfully for $test_email";
} else {
  echo "Password reset failed for $test_email.  Check the database and email configuration.";
}


// **IMPORTANT SECURITY CONSIDERATIONS AND BEST PRACTICES**

// 1.  Salt and Hashing:
//     *  **NEVER** store passwords in plain text in your database.
//     *  Always hash passwords using a strong hashing algorithm (like bcrypt or Argon2) *before* storing them in the database.
//     *  Use a unique salt for each user.
//     *  Use a library for password hashing (e.g., password_hash() and password_verify() in PHP).
//     *  This example is for demonstration purposes only and does *not* include proper password hashing.

// 2.  Email Verification (Crucial!)
//     *   **ALWAYS** send an email to the user containing a temporary, unique link.  This link should:
//         *  Be a unique token that can be verified.
//         *  Expire after a short period (e.g., 30 minutes).
//         *  Lead to a secure page where the user can set a new, permanent password.
//     *  This prevents attackers from guessing the password and resetting it without user interaction.

// 3.  Rate Limiting:
//     *  Implement rate limiting to prevent brute-force password reset attempts.

// 4.  Input Validation:
//     *   Thoroughly validate the email address and password input.  Check for valid email formats, sanitize input to prevent SQL injection, and limit password length.

// 5.  Security Audits:
//     *  Regularly review your code for security vulnerabilities.
//     *  Consider using a security scanner.

// 6.  Error Handling:
//     *  Implement robust error handling to catch and log any errors that occur during the password reset process.  *Never* display detailed error messages to the user in a production environment.  Log them securely.

// 7.  Temporary Token:
//     * Use a unique, randomly generated token to represent the reset request. This token should be stored in the database along with the email.  This token is used to verify that the user is actually requesting a password reset.

// 8.  HTTPS:
//     *  Always use HTTPS to encrypt communication between the user's browser and your server.


?>


<?php

// Assuming you have a database connection established (e.g., $db)
// And a 'users' table with a 'email' column

// Function to handle password reset requests
function forgotPassword($email) {
  // 1. Validate Email (Important for security!)
  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    return "Invalid email address.";
  }

  // 2. Check if the email exists in the database
  $query = "SELECT id, password_reset_token, expiry_timestamp FROM users WHERE email = ?";
  $stmt = $db->prepare($query); // Assuming $db is your database connection
  $stmt->execute([$email]);
  $user = $stmt->fetch(PDO::FETCH_ASSOC);

  if (!$user) {
    return "Email not found.";
  }

  // 3. Generate a unique password reset token
  $token = bin2hex(random_bytes(32)); // Generate a secure random token

  // 4. Store the token in the database for this user
  $query = "INSERT INTO password_resets (user_id, token, expiry_timestamp) VALUES (?, ?, ?)";
  $stmt = $db->prepare($query);
  $stmt->execute([$user['id'], $token, time() + (60 * 60 * 24)]); // Token valid for 24 hours

  // 5. Send the password reset email (implementation omitted for brevity)
  //  - This would involve sending an email with a link containing the token
  //    (e.g.,  https://yourwebsite.com/reset_password.php?token=$token)
  //  -  Use a library or function for sending emails (e.g., PHPMailer)

  return "Password reset email sent.  Please check your inbox.";

}

// Example usage (for demonstration only - don't expose this in a production environment!)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $email = $_POST['email'];
  $reset_message = forgotPassword($email);
  echo "<p>" . $reset_message . "</p>";
}
?>

<!-- HTML Form to submit email for reset -->
<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
  <label for="email">Email:</label>
  <input type="email" id="email" name="email" required>
  <button type="submit">Reset Password</button>
</form>


<?php

/**
 * Forgot Password Function
 *
 * This function handles the password reset process.  It generates a unique,
 * time-limited token, sends an email to the user with a link to reset their
 * password, and stores the token and expiration date in the database.
 *
 * @param string $email The user's email address.
 * @param string $resetUrl  The base URL for the reset password page.
 * @param string $secretKey  A secret key used for signing the token (important for security).
 * @return bool True on success, false on failure.
 */
function forgot_password(string $email, string $resetUrl, string $secretKey) {
  // 1. Generate a Unique Token
  $token = bin2hex(random_bytes(32));

  // 2.  Set Expiration Time -  Example: 1 hour
  $expirationTime = time() + 3600; // Add 1 hour to the current timestamp

  // 3. Hash the Token with the Secret Key
  $hashedToken = hash_file('sha256', $token); // Use a strong hashing algorithm

  // 4. Store the Token and Expiration Time in the Database
  $db = new PDO("mysql:host=localhost;dbname=your_database_name", "your_db_user", "your_db_password"); // Replace with your database credentials.  Ensure PDO is properly configured.
  try {
    $stmt = $db->prepare("INSERT INTO forgot_password_tokens (email, token, hashed_token, expiration_time) VALUES (?, ?, ?, ?)");
    $stmt->execute([$email, $token, $hashedToken, $expirationTime]);
    return true;
  } catch (PDOException $e) {
    // Handle database errors (e.g., email already exists)
    error_log("Error creating forgot password token: " . $e->getMessage()); //Log for debugging
    return false;
  }

  // 5. Send the Password Reset Email (Example -  You'll need to adapt this)
  $subject = "Password Reset Request";
  $message = "Click this link to reset your password: " . $resetUrl . "/reset?token=" . $token;
  $headers = "From: your_email@example.com\r
"; // Replace with your sender email

  mail($email, $subject, $message, $headers); // Uses PHP's built-in mail function.  Consider using a library like PHPMailer.



  return true;
}


// Example Usage (for demonstration - adapt to your application)
//  This is for testing only.  Don't use this directly in production without proper error handling and security measures.
/*
$email = "testuser@example.com";
$resetUrl = "http://localhost/reset_password";
$secretKey = "your_secret_key"; //  Generate a strong, random secret key.  Don't hardcode it in production.

if (forgot_password($email, $resetUrl, $secretKey)) {
  echo "Password reset request sent successfully!";
} else {
  echo "Failed to send password reset request.";
}
*/



//  Important Security Considerations:
//  - **Secret Key:**  Generate a truly random and strong secret key. Store it securely (e.g., environment variables, a secrets management system).  Never commit this to your codebase.
//  - **Token Expiration:** Short expiration times are crucial for security. 1 hour is a good starting point.
//  - **Hashing:**  Always hash the token with a strong hashing algorithm like SHA256.
//  - **Input Validation & Sanitization:**  Validate and sanitize the email address to prevent injection attacks.
//  - **Rate Limiting:**  Implement rate limiting to prevent brute-force attacks.
//  - **HTTPS:**  Always use HTTPS to encrypt the communication between the user and your server.
//  - **Database Security:**  Protect your database from unauthorized access.  Use parameterized queries to prevent SQL injection.

?>


<?php

// Ensure this file is protected to prevent direct access to the database
if (!defined('PH__PROCESS_GUARD')) {
    die('Access denied');
}


/**
 * Forgets a user's password and sends a password reset link.
 *
 * @param string $email The user's email address.
 * @return bool True if a reset link was sent, false otherwise.
 */
function forgot_password(string $email): bool
{
    // 1. Validate Email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        error_log("Invalid email format provided: " . $email); // Log for debugging
        return false;
    }

    // 2. Check if User Exists
    $user = get_user_by_email($email);
    if (!$user) {
        error_log("User with email " . $email . " not found");
        return false;
    }

    // 3. Generate a Unique Token
    $token = generate_unique_token();

    // 4. Store Token in Database (Hash it for security)
    $hashed_token = hash('sha256', $token); // Use SHA256 for stronger hashing
    $result = save_token_to_database($user->id, $hashed_token);
    if (!$result) {
        error_log("Failed to save token to database for user " . $email);
        return false;
    }

    // 5.  Construct the Password Reset Link
    $reset_link = generate_reset_link($user->email, $token);

    // 6. Send the Reset Email
    if (!send_reset_email($user->email, $reset_link)) {
        error_log("Failed to send password reset email to " . $email);
        // Optional:  You could delete the token from the database here,
        // if you want to ensure the reset link isn't usable if the email
        // fails to send.  However, this increases complexity.
        return false;
    }

    return true;
}


/**
 * Placeholder function to retrieve a user by email.  Implement your database query here.
 * @param string $email
 * @return User|null
 */
function get_user_by_email(string $email): ?User {
    // Replace with your actual database query
    // This is just a dummy example.
    // You'd normally fetch the user from your database table.

    // Example:  Assuming you have a User class
    //  $user =  DB::query("SELECT * FROM users WHERE email = ?", $email)->first();
    //  return $user;

    // Dummy User class for demonstration.
    class User {
        public $id;
        public $email;

        public function __construct(int $id, string $email) {
            $this->id = $id;
            $this->email = $email;
        }
    }

    return new User(1, $email); // Placeholder return
}

/**
 * Placeholder function to generate a unique token.
 * @return string
 */
function generate_unique_token(): string
{
    return bin2hex(random_bytes(32)); // Using bin2hex for better security
}

/**
 * Placeholder function to save the token to the database.
 * @param int $user_id
 * @param string $hashed_token
 * @return bool
 */
function save_token_to_database(int $user_id, string $hashed_token): bool
{
    // Replace with your database insert query
    // Example:
    // DB::table('password_resets')->insert([
    //     'user_id' => $user_id,
    //     'token' => $hashed_token,
    //     'created_at' => time(),
    // ]);
    // Return true if the insert was successful, false otherwise.

    return true; // Placeholder return
}


/**
 * Placeholder function to generate the password reset link.
 * @param string $email
 * @param string $token
 * @return string
 */
function generate_reset_link(string $email, string $token): string
{
    return "/reset_password?email=" . urlencode($email) . "&token=" . urlencode($token);
}


/**
 * Placeholder function to send the password reset email.
 * @param string $email
 * @param string $reset_link
 */
function send_reset_email(string $email, string $reset_link): bool
{
    // Replace with your email sending logic (e.g., using PHPMailer)
    // This is a placeholder - you need to implement this using your preferred email library.

    // Example:
    // $mail = new PHPMailer();
    // $mail->addAddress($email);
    // $mail->Subject = "Password Reset";
    // $mail->Body = "Click this link to reset your password: " . $reset_link;
    // $mail->send();

    // Placeholder:  Simulate sending
    echo "Simulated sending email to: " . $email . " with link: " . $reset_link;
    return true;
}

// Example Usage (Illustrative -  Replace with your actual user data)
$email = "test@example.com";
if (forgot_password($email)) {
    echo "Password reset email sent to " . $email . "
";
} else {
    echo "Failed to send password reset email to " . $email . "
";
}

?>


<?php

// Configuration - Replace with your database credentials and email settings
$db_host = 'localhost';
$db_name = 'your_database_name';
$db_user = 'your_db_user';
$db_pass = 'your_db_password';
$from_email = 'your_email@example.com';
$from_name = 'Your Website Name';


// Function to reset password
function forgot_password($email, $new_password) {
    // 1. Connect to the database
    $conn = new mysqli($db_host, $db_user, $db_pass, $db_name);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // 2. Sanitize and Validate Inputs
    $email = trim($email); // Remove leading/trailing whitespace
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return "Invalid email address.";
    }
    $new_password = trim($new_password);
    if (empty($new_password)) {
        return "Password cannot be empty.";
    }

    // 3. Hash the new password (Important for Security)
    $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

    // 4. Update the password in the database
    $sql = "UPDATE users SET password = '$hashed_password' WHERE email = '$email'";

    if ($conn->query($sql) === TRUE) {
        return "Password reset successfully.  Please check your email.";
    } else {
        return "Error resetting password. " . $conn->error;
    }

    // 5. Close the connection
    $conn->close();
}


// Example Usage (This is just for testing, integrate this into your form)
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $new_password = $_POST['new_password'];

    $reset_message = forgot_password($email, $new_password);
    echo $reset_message; // Display the result (e.g., error message or success)
} else {
    // If not a POST request, show a form for the user to enter their email and a new password.
    ?>
    <form method="post" action="">
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required><br><br>

        <label for="new_password">New Password:</label>
        <input type="password" id="new_password" name="new_password" required><br><br>

        <button type="submit">Reset Password</button>
    </form>
    <?php
}
?>


<?php

// Assuming you have a database connection established
// $db = new mysqli($host, $username, $password, $database);
// if ($db->connect_error) {
//     die("Connection failed: " . $db->connect_error);
// }

// Function to handle the forgot password process
function forgotPassword($email, $newPassword, $db) {
    // 1. Validate Email (Optional but recommended)
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return "Invalid email format.";
    }

    // 2. Check if the email exists in the database
    $stmt = $db->prepare("SELECT id, email FROM users WHERE email = ?");
    $email_to_check = $email;
    $stmt->bind_param("s", $email_to_check); // 's' indicates a string parameter
    $stmt->execute();

    $result = $stmt->get_result();

    if ($result->num_rows == 0) {
        $stmt->close();
        return "User not found.";
    }

    $user = $result->fetch_assoc();
    $stmt->close();

    // 3. Generate a unique token or use a hashed password reset token
    $token = bin2hex(random_bytes(32)); // Generate a random, secure token.  Consider a more robust method if security is paramount.

    // 4. Hash the token for security
    $hashed_token = password_hash($token, PASSWORD_DEFAULT);

    // 5. Update the user's record with the hashed token
    $stmt = $db->prepare("UPDATE users SET password_reset_token = ? WHERE email = ?");
    $stmt->bind_param("ss", $hashed_token, $email);
    $stmt->execute();

    if (!$stmt->affected_rows > 0) {
        $stmt->close();
        return "Failed to update password reset token.";
    }

    $stmt->close();

    // 6. Send an email with the reset link
    $subject = "Password Reset Link";
    $message = "Please click on the following link to reset your password: " . "<a href='" . $_SERVER['PHP_SELF'] . "?reset=" . urlencode($token) . "'>Reset Password</a>"; // Include the reset token in the URL
    $headers = "From: your_email@example.com";  // Replace with your email address

    mail($email, $subject, $message, $headers);

    // 7. Return a success message
    return "Password reset link sent to your email.";
}

// Example Usage (Simulated for demonstration)
//  This would typically be handled by a form submission.
//  For demonstration, let's simulate getting email and new password.
//  In a real application, you'd get this data from a form.
$email = "test@example.com"; // Replace with the user's email
$newPassword = "P@sswOrd123";  // Replace with the desired new password

// Simulate the database connection
// For demonstration, we create a mock database object
class MockDB {
    public function prepare($query) {
        // In a real application, this would use a prepared statement
        // For demonstration, we'll just return a dummy result
        return null;
    }

    public function bind_param($type, $value) {
        // Do nothing for demonstration
    }

    public function execute() {
        // Dummy result for demonstration
        return array(
            'num_rows' => 1, // Assume user exists
            'fetch_assoc' => function() {
                return array(
                    'id' => 1,
                    'email' => 'test@example.com'
                );
            }
        );
    }

    public function affected_rows() {
        return 1;
    }

    public function close() {
        // Do nothing for demonstration
    }
}
$db = new MockDB();


$result = forgotPassword($email, $newPassword, $db);
echo $result;

?>


<?php

// Assuming you have a database connection established (e.g., $db connection)

/**
 * Forgets a user's password.
 *
 * @param string $email The email address of the user requesting a password reset.
 * @return bool True on success, false on failure.
 */
function forgotPassword(string $email): bool
{
    // 1. Validate Input
    if (empty($email)) {
        error_log("Forgot Password: Empty email provided."); // Log for debugging
        return false;
    }

    // Sanitize the email address to prevent SQL injection
    $email = filter_var($email, FILTER_SANITIZE_EMAIL);

    if (filter_var($email, FILTER_VALIDATE_EMAIL) === false) {
        error_log("Forgot Password: Invalid email format provided.");
        return false;
    }

    // 2. Check if the user exists
    $user = getUserByEmail($email);  // Implement this function (see below)

    if ($user === false) {
        error_log("Forgot Password: User not found with email: " . $email);
        return false;
    }

    // 3. Generate a unique token
    $token = generateUniqueToken();

    // 4. Store the token in the database associated with the user
    //  (This is the crucial part – adapt to your database schema)
    $result = storeToken($user['id'], $token); // Implement this function (see below)

    if (!$result) {
        error_log("Forgot Password: Failed to store token for user: " . $email);
        return false;
    }


    // 5. Send the password reset email
    //  (Implement this function – sendmail, etc.)
    sendPasswordResetEmail($user['email'], $token);


    return true;
}



/**
 * Helper function to get a user by their email.  This is a placeholder.
 *  You must implement this function based on your database structure.
 *
 * @param string $email The email address of the user.
 * @return bool|array The user object if found, false if not found.
 */
function getUserByEmail(string $email): bool|array
{
    // Replace this with your database query.  This is just a dummy example.
    // Assuming a table named 'users' with columns 'id', 'email', 'password', etc.
    $db = new PDO("mysql:host=localhost;dbname=your_database_name", "your_username", "your_password");
    $stmt = $db->prepare("SELECT * FROM users WHERE email = :email");
    $stmt->bindParam(':email', $email);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        $stmt->fetch(PDO::FETCH_ASSOC);  // Get the first row as an associative array
        return $stmt->fetch(PDO::FETCH_ASSOC);
    } else {
        return false;
    }
}


/**
 * Helper function to generate a unique token.
 *  This could use a library or a simple random string generator.
 *
 * @return string A unique token.
 */
function generateUniqueToken(): string
{
    return bin2hex(random_bytes(32)); // Example - generates a 32-byte (256-bit) random string.
}



/**
 * Helper function to store the token in the database.
 *
 * @param int $userId The ID of the user.
 * @param string $token The unique token.
 * @return bool True on success, false on failure.
 */
function storeToken(int $userId, string $token): bool
{
    // Replace with your database code to store the token.
    // Example (using PDO):

    $db = new PDO("mysql:host=localhost;dbname=your_database_name", "your_username", "your_password");
    $stmt = $db->prepare("INSERT INTO user_tokens (user_id, token, expires_at) VALUES (:user_id, :token, :expires_at)");
    $expires_at = date('Y-m-d H:i:s', time() + 3600); // Token expires in 1 hour
    $stmt->bindParam(':user_id', $userId);
    $stmt->bindParam(':token', $token);
    $stmt->bindParam(':expires_at', $expires_at);
    return $stmt->execute();
}



/**
 * Helper function to send the password reset email.
 *
 * @param string $email The email address of the user.
 * @param string $token The unique token.
 * @return void
 */
function sendPasswordResetEmail(string $email, string $token)
{
    //  Replace with your email sending logic (e.g., sendmail, SMTP, etc.)
    $subject = "Password Reset Request";
    $message = "Please click on the following link to reset your password: " . "<a href='" . base_url() . "/reset_password?token=" . $token . "'>Reset Password</a>";

    // Example using mail() - may need to configure your server properly
    mail($email, $subject, $message);
}

// Example Usage (For testing - replace with your actual logic)
// if (isset($_POST['email'])) {
//     $email = $_POST['email'];
//     if (forgotPassword($email)) {
//         echo "Password reset email has been sent to " . $email;
//     } else {
//         echo "Failed to process password reset request.";
//     }
// }

?>


<?php

/**
 * Forgot Password Function
 * 
 * This function generates a temporary password and sends it to the user's email.
 * 
 * @param string $email      The email address of the user.
 * @param string $resetLinkPrefix The prefix for the reset link (e.g., "https://example.com/reset-password/")
 * @param string $tokenLifetimeSeconds  The amount of time (in seconds) the reset token is valid.
 * @param string $fromEmail   The email address to send the reset password link from.
 * 
 * @return bool True on success, false on failure.
 */
function forgotPassword(string $email, string $resetLinkPrefix, int $tokenLifetimeSeconds, string $fromEmail)
{
    // 1. Validate Email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        error_log("Invalid email address provided: " . $email); // Log the error for debugging
        return false;
    }

    // 2. Generate a Unique Token
    $token = bin2hex(random_bytes(32)); // Generate a strong, random token

    // 3. Hash the Token (For Security)
    $hashedToken = password_hash($token, PASSWORD_DEFAULT);

    // 4. Store the Token in the Database (Replace with your database logic)
    // IMPORTANT: This is a placeholder. You MUST implement this logic
    // using your database connection and table structure.
    $user_id = getUserIDFromEmail($email); // Function to get user ID from email - replace with your implementation
    if ($user_id === null) {
        error_log("User not found for email: " . $email);
        return false;
    }

    // Store the token and user ID.  Replace this with your DB query.
    // In a real application, you would likely use prepared statements 
    // to prevent SQL injection vulnerabilities.
    $success = storeToken($user_id, $token);  // Function to store the token - replace with your implementation
    if (!$success) {
        error_log("Failed to store token for user: " . $email);
        return false;
    }

    // 5. Create the Reset Link
    $resetLink = $resetLinkPrefix . "?" . http_build_query(['token' => $token]);

    // 6. Send the Email
    $subject = "Password Reset Request";
    $message = "To reset your password, please click on the following link: " . $resetLink;
    $headers = "From: " . $fromEmail . "\r
";
    $headers .= "Reply-To: " . $fromEmail . "\r
";

    if (mail($email, $subject, $message, $headers)) {
        return true;
    } else {
        error_log("Failed to send email to: " . $email);
        // Consider removing the token if the email fails to send.
        // This prevents it from being used indefinitely if email delivery is unreliable.
        // removeToken($user_id, $token); // Implement this function
        return false;
    }
}

/**
 * Placeholder functions - Replace with your own implementations
 * These are placeholders for database interaction and token removal.
 */

/**
 * Placeholder function to get user ID from email.  Replace with your database query.
 * @param string $email
 * @return int|null
 */
function getUserIDFromEmail(string $email): ?int
{
    // Replace this with your actual database query to get the user ID.
    // Example:
    // $result = mysqli_query($conn, "SELECT id FROM users WHERE email = '$email'");
    // if (mysqli_num_rows($result) > 0) {
    //   return mysqli_fetch_assoc($result)['id'];
    // }
    // return null;
    return null; // Placeholder
}

/**
 * Placeholder function to store the token in the database.
 * Replace with your database query.
 * @param int $userId
 * @param string $token
 */
function storeToken(int $userId, string $token): bool
{
    // Replace this with your actual database query.
    // Example:
    // mysqli_query($conn, "INSERT INTO reset_tokens (user_id, token, expires_at) VALUES ($userId, '$token', NOW() + INTERVAL 3600 SECOND)");  // Expires in 1 hour
    return true; // Placeholder
}


/**
 * Placeholder function to remove the token from the database.
 * Replace with your database query.
 * @param int $userId
 * @param string $token
 */
function removeToken(int $userId, string $token): bool
{
    // Replace this with your actual database query.
    // Example:
    // mysqli_query($conn, "DELETE FROM reset_tokens WHERE user_id = $userId AND token = '$token'");
    return true; // Placeholder
}

// Example Usage (Replace with your actual email, prefix, and from email)
// $email = "test@example.com";
// $resetLinkPrefix = "https://yourwebsite.com/reset-password/";
// $tokenLifetimeSeconds = 7200; // 2 hours
// $fromEmail = "noreply@yourwebsite.com";
//
// if (forgotPassword($email, $resetLinkPrefix, $tokenLifetimeSeconds, $fromEmail)) {
//     echo "Password reset email sent!";
// } else {
//     echo "Password reset failed.";
// }

?>


<?php

// Database connection details - Replace with your actual credentials
$host = 'localhost';
$username = 'your_username';
$password = 'your_password';
$database = 'your_database';

//  Reset Password Function
function reset_password($email, $new_password) {
  // 1. Validate Email
  $email = filter_var($email, FILTER_SANITIZE_EMAIL); // Sanitize email for security
  if (empty($email)) {
    return false; // Invalid email
  }

  // 2. Database Connection
  try {
    $pdo = new PDO("mysql:host=$host;dbname=$database", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERR_EXCEPTION);
  } catch (PDOException $e) {
    error_log("Database connection error: " . $e->getMessage()); // Log error for debugging
    return false;
  }


  // 3.  Check if User Exists
  $stmt = $pdo->prepare("SELECT id, email FROM users WHERE email = :email");
  $stmt->bindParam(':email', $email, PDO::PARAM_STR);
  $stmt->execute();

  $user = $stmt->fetch(PDO::FETCH_ASSOC);

  if (empty($user)) {
    // User not found
    return false;
  }

  // 4.  Hash the New Password (Important for Security!)
  $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

  // 5. Update the Password
  $stmt = $pdo->prepare("UPDATE users SET password = :password WHERE id = :id");
  $stmt->bindParam(':password', $hashed_password, PDO::PARAM_STR);
  $stmt->bindParam(':id', $user['id'], PDO::PARAM_INT);
  $stmt->execute();

  if ($stmt->rowCount() === 0) {
    // Update failed
    return false;
  }

  return true; // Password reset successful
}



// Example Usage (Demonstration - Don't use directly in production without validation and sanitization)
//  This is for demonstration purposes only - NEVER expose this directly to the user.
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['email']) && isset($_POST['new_password'])) {
  $email = $_POST['email'];
  $new_password = $_POST['new_password'];

  if (reset_password($email, $new_password)) {
    echo "<p style='color: green;'>Password reset successfully! Check your email.</p>";
  } else {
    echo "<p style='color: red;'>Password reset failed. Please try again.</p>";
  }
}

?>

<!-- HTML Form for the Reset Password Request -->
<form method="post" action="">
  <label for="email">Email:</label>
  <input type="email" id="email" name="email" required><br><br>
  <button type="submit">Reset Password</button>
</form>


<?php

// Assuming you have a database connection established (e.g., $db connection)

/**
 * Generates a unique token and saves it to the database.
 *
 * @param string $email The email address to reset the password for.
 * @return string|false The unique token if generated successfully, or false if an error occurs.
 */
function generateResetToken() {
  $token = bin2hex(random_bytes(32)); // Generates a secure, random 32-byte token
  return $token;
}

/**
 * Creates a password reset link.
 *
 * @param string $email The email address to reset the password for.
 * @return string|false The generated password reset link if successful, or false if an error occurs.
 */
function createResetLink(string $email) {
    $token = generateResetToken();

    // Example:  You might save the token to the database, along with the email.
    // This is just a placeholder - you'll need to implement your database saving logic.
    // For demonstration, we'll just return the token string.
    //  Don't just return the token, you *must* store it securely!
    return $token;
}


/**
 * Resets the user's password based on the token.
 *
 * @param string $token The password reset token.
 * @param string $newPassword The new password for the user.
 * @param string $email The user's email address.
 * @return bool True if the password was successfully reset, false otherwise.
 */
function resetPassword(string $token, string $newPassword, string $email) {
    // 1. Verify the Token
    $query = "SELECT id, email FROM users WHERE email = ?";
    $stmt = $db->prepare($query); // Use your database connection
    $stmt->bind_param("s", $email);
    $stmt->execute();

    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        $stmt->close();
        return false; // User not found
    }

    $user = $result->fetch_assoc();
    $userId = $user['id'];

    // 2. Check if the token exists for this user
    $query = "SELECT id FROM reset_tokens WHERE token = ? AND user_id = ?";
    $stmt = $db->prepare($query);
    $stmt->bind_param("ss", $token, $userId);
    $stmt->execute();

    if ($stmt->error) {
        error_log("Database error: " . $stmt->error); // Log the error for debugging.
        $stmt->close();
        return false;
    }

    if ($stmt->error) {
        $stmt->close();
        return false;
    }

    if ($stmt->affected_rows === 0) {
        $stmt->close();
        return false; // Token not found for this user
    }
    
    // 3. Update the user's password
    $query = "UPDATE users SET password = ? WHERE id = ?";
    $stmt = $db->prepare($query);
    $stmt->bind_param("ss", password_hash($newPassword, PASSWORD_DEFAULT), $userId);
    $stmt->execute();
    $stmt->close();


    // 4. Delete the token from the database after successful password reset.
    $query = "DELETE FROM reset_tokens WHERE token = ? AND user_id = ?";
    $stmt = $db->prepare($query);
    $stmt->bind_param("ss", $token, $userId);
    $stmt->execute();
    $stmt->close();

    return true;
}


// Example Usage (illustrative only - adapt to your application)
/*
$email = "testuser@example.com";
$newPassword = "NewSecurePassword123";


// Generate a reset link
$resetLink = createResetLink($email);
echo "Reset Link: " . $resetLink . "<br>";


// Reset the password (assuming you've received the $resetLink from a form submission)
if (resetPassword($resetLink, $newPassword, $email)) {
    echo "Password reset successfully!";
} else {
    echo "Password reset failed.";
}
*/


?>


<?php

/**
 * Forgot Password Function
 *
 * This function generates a random token and stores it in the database
 * associated with the user's email address. It then sends an email to
 * the user with a link to reset their password.
 *
 * @param string $email The email address to send the reset password link to.
 * @param string $token  A random, unique token to protect the password reset link.
 * @param string $secret_key  The secret key used to encrypt the token.
 * @param string $reset_url The URL where the reset password page is located.
 * @return bool True on success, false on failure.
 */
function forgot_password(string $email, string $token, string $secret_key, string $reset_url) {
    // 1. Validate Email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        error_log("Invalid email address provided."); // Log for debugging - important!
        return false;
    }

    // 2. Store Token in Database (Example using a simple array - REPLACE with your database logic)
    //  **IMPORTANT:**  This is just an example.  You *must* replace this with your actual
    //  database storage mechanism (e.g., SQL insert).
    $tokens = get_stored_tokens(); // Function to retrieve stored tokens from the database.  Replace with your retrieval logic.
    $tokens[$email] = $token; // Store the token
    set_stored_tokens($tokens); //Function to store the tokens in the database

    // 3. Generate Reset Link
    $reset_link = $reset_url . "?token=" . urlencode($token);

    // 4. Send Password Reset Email
    $to = $email;
    $subject = "Password Reset";
    $message = "To reset your password, please click on the following link: " . $reset_link;
    $headers = "From: Your Website <admin@yourwebsite.com>"; // Replace with your email address

    if (mail($to, $message, $headers)) {
        return true;
    } else {
        error_log("Failed to send password reset email."); // Log failure
        return false;
    }
}

/**
 *  Placeholder functions for database interaction.  **Replace with your actual database code.**
 */

function get_stored_tokens() {
  // Replace this with your code to retrieve tokens from the database
  // Example:
  // return [
  //   'user1@example.com' => 'random_token_1',
  //   'user2@example.com' => 'random_token_2'
  // ];
  return []; // Return an empty array for now
}

function set_stored_tokens($tokens) {
  // Replace this with your code to store tokens in the database.
  // Example:
  //  //  $db = new DatabaseConnection();
  //  //  $sql = "DELETE FROM reset_tokens"; //Clear the table
  //  //  $db->query($sql);

  //  foreach ($tokens as $email => $token) {
  //    $sql = "INSERT INTO reset_tokens (email, token) VALUES ('" . $email . "', '" . $token . "')";
  //    $db->query($sql);
  //  }
}


// Example Usage (FOR TESTING - DO NOT USE IN PRODUCTION WITHOUT SECURITY MEASURES)
//  $email = "testuser@example.com";
//  $token = "random_unique_token_string_123";
//  $secret_key = "YourSecretKeyHere";
//  $reset_url = "http://localhost:8000/reset_password"; //  Adjust to your URL

//  if (forgot_password($email, $token, $secret_key, $reset_url)) {
//      echo "Password reset email sent successfully!";
//  } else {
//      echo "Failed to send password reset email.";
//  }

?>


<?php

// Assuming you have a database connection established (e.g., $db)

/**
 * Forgets a user's password.
 *
 * @param string $email The user's email address.
 * @return bool True if a password reset link has been sent, false otherwise.
 */
function forgotPassword(string $email) {
  // 1. Validate Email (Basic) -  Expand this for more robust validation if needed.
  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    error_log("Invalid email address provided: " . $email); // Log the invalid email
    return false;
  }

  // 2. Check if the user exists.  This is crucial.
  $user = getUserByEmail($email); // Assume you have a function to fetch the user
  if (!$user) {
    error_log("User with email " . $email . " not found.");
    return false;
  }

  // 3. Generate a Unique Token (Important for security)
  $token = generateUniqueToken();

  // 4. Store the Token and User ID in the Database
  $result = storeTokenForUser($user->id, $token); // Assume you have a function for this
  if (!$result) {
    error_log("Failed to store token for user " . $email);
    return false;
  }

  // 5. Send the Password Reset Email
  $subject = "Password Reset";
  $message = "To reset your password, please click on the following link: " .  base_url() . "/reset-password?token=" . $token;  // Use your base URL
  $headers = "From: " . get_option('admin_email') . "\r
"; //Replace with your email
  $result = sendEmail($email, $subject, $message, $headers);
  if (!$result) {
    error_log("Failed to send email for password reset to " . $email);
    // Optionally, you might try deleting the token if email sending fails.
    // deleteTokenForUser($user->id, $token);
    return false;
  }

  return true;
}


/**
 *  Helper function to get a user by email.  Replace with your actual implementation.
 * @param string $email
 * @return object|null User object or null if not found.
 */
function getUserByEmail(string $email) {
    // Replace this with your database query logic
    // This is a placeholder example.
    $users = [
        ['id' => 1, 'email' => 'test@example.com', 'password' => 'password123'],
        ['id' => 2, 'email' => 'another@example.com', 'password' => 'secret']
    ];

    foreach ($users as $user) {
        if ($user['email'] === $email) {
            return new stdClass(); // Create a new object for the user
        }
    }

    return null;
}


/**
 * Generate a unique token.  Consider using a library for cryptographically secure random strings.
 * @return string
 */
function generateUniqueToken() {
    return bin2hex(random_bytes(32)); // More secure than mt_rand
}

/**
 *  Placeholder function to store the token in the database.
 * @param int $userId
 * @param string $token
 * @return bool
 */
function storeTokenForUser(int $userId, string $token) {
    // Your database logic here.
    // For example:
    // $query = "INSERT INTO password_tokens (user_id, token, expires_at) VALUES ($userId, '$token', NOW() + INTERVAL 7 DAY)";
    // executeQuery($query);
    return true;
}


/**
 *  Placeholder function to send an email.
 * @param string $to
 * @param string $subject
 * @param string $message
 * @param string $headers
 * @return bool
 */
function sendEmail(string $to, string $subject, string $message, string $headers) {
    // Replace this with your email sending logic (e.g., using PHPMailer or similar).
    // This is a placeholder for demonstration purposes.

    // Simulate successful sending
    error_log("Simulated sending email to: " . $to . " with subject: " . $subject);
    return true;
}

/**
 *  Placeholder function to delete the token.
 * @param int $userId
 * @param string $token
 * @return bool
 */
function deleteTokenForUser(int $userId, string $token) {
    //Your database logic here to delete the token record.
    return true;
}

/**
 *  Returns the base url of your website.  Useful for generating reset links.
 * @return string
 */
function base_url() {
    // Replace this with your actual base URL.
    return "http://localhost/your-website";
}


// Example Usage:
$email = "test@example.com";

if (forgotPassword($email)) {
    echo "Password reset email has been sent to " . $email . ".  Check your inbox.";
} else {
    echo "Failed to initiate password reset for " . $email;
}

?>


<?php

// Database connection details (Replace with your actual values)
$host = 'localhost';
$username = 'your_username';
$password = 'your_password';
$database = 'your_database_name';

// Function to handle password reset requests
function forgot_password($email) {
    // 1. Validate email format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return "Invalid email address.";
    }

    // 2. Fetch user data from the database
    $pdo = new PDO("mysql:host=$host;dbname=$database", $username, $password);
    $stmt = $pdo->prepare("SELECT id, password, email FROM users WHERE email = :email");
    $stmt->bindParam(':email', $email);
    $stmt->execute();

    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    // 3. Check if the user exists
    if ($user) {
        // 4. Generate a unique token
        $token = bin2hex(random_bytes(32)); // Generates a cryptographically secure random token
        // 5.  Store the token and user ID in the database
        try {
            $pdo->prepare("UPDATE users SET password_reset_token = :token, password_reset_token_expiry = :expiry WHERE email = :email")
                  ->bindParam(':token', $token)
                  ->bindParam(':expiry', date('Y-m-d H:i:s', time() + 3600)) // Expires after 1 hour
                  ->bindParam(':email', $email)
                  ->execute();
            return $token; // Return the token to the user
        } catch (PDOException $e) {
            // Handle database errors (e.g., duplicate token)
            error_log("Error resetting password: " . $e->getMessage()); // Log the error for debugging
            return "An error occurred while generating the reset token. Please try again.";
        }

    } else {
        return "User not found.";
    }
}


// Example Usage (for demonstration purposes - DON'T use this in a real web application directly)
// $email = 'test@example.com';
// $resetToken = forgot_password($email);

// if ($resetToken == "Invalid email address.") {
//     echo $resetToken; // Display the error message
// } elseif ($resetToken == "User not found.") {
//     echo $resetToken;
// } else {
//     echo "Password reset link sent to: " . $resetToken;
// }
?>


<?php

// Assuming you have a database connection established
// This is a simplified example, adapt to your database structure
// and security practices.

// Configuration (Change these to your actual values)
$db_host = 'localhost';
$db_name = 'your_database_name';
$db_user = 'your_database_user';
$db_password = 'your_database_password';

// Function to handle the forgot password process
function forgot_password($email) {
  // 1. Validate email format (basic check)
  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    return "Invalid email address.";
  }

  // 2. Check if the email exists in the user table
  $conn = new mysqli($db_host, $db_user, $db_password, $db_name);
  if ($conn->connect_error) {
    return "Database connection failed: " . $conn->connect_error;
  }

  $query = "SELECT id, password, email FROM users WHERE email = '$email'";
  $result = $conn->query($query);

  if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
    $userId = $user['id'];
    $hashedPassword = $user['password']; //  Important:  Store hashed passwords
    // 3. Generate a unique token
    $token = bin2hex(random_bytes(32)); // Generate a secure random token
    // 4. Update the user record with the token (add to password column or create a separate 'tokens' table)
    $update_query = "UPDATE users SET token = '$token' WHERE id = '$userId'";
    if (!$conn->query($update_query)) {
      return "Error updating user data.";
    }

    // 5. Send the password reset email
    $to = $email;
    $subject = "Password Reset";
    $message = "Click on the following link to reset your password: " . $_SERVER['REQUEST_SCHEME'] . "://yourdomain.com/reset_password.php?token=$token"; // Use HTTPS if possible
    $headers = "From: your_email@example.com";

    mail($to, $message, $headers);

    return "Password reset link sent to your email.  Check your inbox!";
  } else {
    return "User with this email address not found.";
  }

  $conn->close();
}


// Example Usage (for testing -  This will not work directly without a form)
//  This demonstrates how you would call the function.
/*
$email = "test@example.com"; // Replace with the user's email
$resetMessage = forgot_password($email);
echo $resetMessage;
*/


// **IMPORTANT SECURITY NOTES & BEST PRACTICES**

// 1. **Hashing Passwords:**  NEVER store passwords in plain text.  Always hash them using a strong hashing algorithm like bcrypt or Argon2.  The example uses `$user['password']`, which represents the *hashed* password.

// 2. **Token Expiration:** Implement an expiration time for the password reset token.  This prevents attackers from using the token after it has expired. You can store the expiration time in the database (e.g., a 'token_expiry' column).

// 3. **Secure Token Generation:** Use `random_bytes()` to generate cryptographically secure random tokens. `bin2hex()` converts the bytes into a hexadecimal string, making it suitable for URL parameters.

// 4. **HTTPS:** ALWAYS use HTTPS to protect the password reset link and the user's email address.

// 5. **Rate Limiting:** Implement rate limiting to prevent brute-force attacks.

// 6. **Input Validation:** Validate all user inputs (email format, token, etc.).

// 7. **Error Handling:** Provide informative error messages to the user.

// 8. **Security Audits:** Regularly review your code for security vulnerabilities.

// 9. **Separate Tables (Recommended):** For improved security and organization, consider using separate tables for users and tokens. This isolates the tokens, making it harder for attackers to compromise the password reset process.

// 10. **Email Verification:** Send a verification email to the user to confirm they received the reset link.

// 11. **Don't Reveal Sensitive Information in Error Messages:**  Avoid revealing database details or other sensitive information in error messages that might be exposed to users.


<?php

// Database connection details (Replace with your actual details)
$host = "localhost";
$username = "your_username";
$password = "your_password";
$dbname = "your_database_name";

// Function to handle the forgot password process
function forgotPassword($email) {
    // 1. Validate Email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return "Invalid email address.";
    }

    // 2. Check if the user exists
    $conn = new mysqli($host, $username, $password, $dbname);

    if ($conn->connect_error) {
        return "Connection failed: " . $conn->connect_error;
    }

    $sql = "SELECT id, password FROM users WHERE email = '$email'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        $userId = $user['id'];
        $hashedPassword = $user['password']; //Retrieve the hashed password.

        // 3. Generate a unique token
        $token = bin2hex(random_bytes(32)); // Generate a secure random token
        
        // 4. Update the user's record with the token
        $sql = "UPDATE users SET reset_token = '$token' WHERE id = '$userId'";
        if ($conn->query($sql) === TRUE) {
            // 5. Send an email with the token and a link
            $emailTo = $email;
            $subject = "Password Reset Link";
            $message = "Click on the following link to reset your password: " . "<a href='" . $_SERVER['PHP_SELF'] . "?reset=" . urlencode($token) . "'>Reset Password</a>";
            $headers = "From: your_email@example.com" . "\r
";

            if (mail($emailTo, $subject, $message, $headers)) {
                return "Password reset link sent to your email.  Please check your inbox.";
            } else {
                return "Failed to send password reset email.";
            }
        } else {
            return "Error updating user record.";
        }
    } else {
        return "User not found.";
    }

    $conn->close();
}


// Example usage (this is just for demonstration, don't use this directly in a form)
// $email = $_POST['email']; // Get email from form
// $resetMessage = forgotPassword($email);
// echo $resetMessage;

?>

<!DOCTYPE html>
<html>
<head>
    <title>Forgot Password</title>
</head>
<body>

    <h1>Forgot Password</h1>

    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required>
        <button type="submit">Reset Password</button>
    </form>

</body>
</html>


// Example:  Using password_hash()
$password = $_POST['password'];  // Get the user's new password
$hashedPassword = password_hash($password, PASSWORD_DEFAULT);

// Then, in your query:
$sql = "UPDATE users SET password = '$hashedPassword' WHERE id = '$userId'";


<?php

// Replace with your actual database connection details
$db_host = "localhost";
$db_name = "your_database_name";
$db_user = "your_database_username";
$db_password = "your_database_password";

/**
 * Resets a user's password.
 *
 * @param string $email The user's email address.
 * @return bool True if the password reset was initiated, false otherwise.
 */
function forgot_password(string $email) {
  // 1. Check if the email exists
  $conn = new mysqli($db_host, $db_user, $db_password, $db_name);

  if ($conn->connect_error) {
    error_log("Database connection failed: " . $conn->connect_error);
    return false;
  }

  $query = "SELECT id, email FROM users WHERE email = ?";
  $stmt = $conn->prepare($query);

  if ($stmt === false) {
    error_log("Prepare statement failed: " . $conn->error);
    return false;
  }

  $email_to_check = $email;  // Pass the email to the prepared statement
  $stmt->bind_param("s", $email_to_check);

  if (!$stmt->execute()) {
    error_log("Execute statement failed: " . $stmt->error);
    return false;
  }

  $result = $stmt->get_result();

  if ($result->num_rows == 0) {
    // Email doesn't exist
    $stmt->close();
    return false;
  }

  $user = $result->fetch_assoc();
  $userId = $user['id'];
  $stmt->close();

  // 2. Generate a unique reset token
  $reset_token = bin2hex(random_bytes(32)); // Use a secure random string

  // 3. Store the token in the database
  $conn->query("UPDATE users SET reset_token = ? WHERE id = ?", $reset_token, $userId);

  // 4.  Send the reset link (implementation is omitted for brevity)
  // This would typically involve sending an email with a link containing the token.

  // You would then have a link like:  https://yourwebsite.com/reset_password.php?token=$reset_token

  return true;
}

// Example usage (for testing - remove for production)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $email = $_POST["email"];

  if (isset($email) && !empty($email)) {
    $reset_success = forgot_password($email);

    if ($reset_success) {
      echo "<p>Password reset link has been sent to your email address.</p>";
      echo "<p>Please check your inbox.</p>";
    } else {
      echo "<p>An error occurred while attempting to reset your password.</p>";
      echo "<p>Please check your email address and try again.</p>";
    }
  } else {
    echo "<p>Please enter your email address.</p>";
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

  <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
    <label for="email">Email:</label>
    <input type="email" id="email" name="email" required>
    <button type="submit">Reset Password</button>
  </form>

</body>
</html>


<?php

// Assuming you have a database connection established (e.g., $db)
//  and a 'users' table with 'email' and 'password' columns.
//  This is a simplified example; in a real-world scenario, you'd
//  add more security measures.

function forgot_password($email, $db) {
  // 1. Validate Email
  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    return "Invalid email address.";
  }

  // 2. Check if user exists
  $result = $db->query("SELECT id, password FROM users WHERE email = '$email'");

  if ($result->num_rows === 0) {
    return "User not found.";
  }

  $user = $result->fetch_assoc();
  $userId = $user['id'];
  $hashedPassword = $user['password']; //This should be hashed in your database!

  // 3. Generate a temporary password
  $tempPassword = generate_temp_password();

  // 4. Update the database with the temporary password
  $db->query("UPDATE users SET password = '$tempPassword' WHERE id = '$userId'");

  // 5.  Store the temporary password in a temporary table or session 
  //     (This is crucial - don't just log it in the user's database).
  //     Example using a temporary table:
  $db->query("INSERT INTO password_resets (user_id, reset_token, expires_at)
            VALUES ($userId, '$tempPassword', NOW() + INTERVAL 1 HOUR)");


  // 6. Return a reset link (email the user with this link)
  return "<a href='reset_password.php?token=$tempPassword'>Click here to reset your password</a>";

  // Or, you can return the token directly if you are handling the reset in the same page.
  //return $tempPassword;
}

//Helper function to generate a temporary password
function generate_temp_password() {
  $length = 12; // Adjust as needed
  $characters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
  $password = '';
  $character_length = strlen($characters);
  for ($i = 0; $i < $length; $i++) {
    $random_number = rand(0, $character_length - 1);
    $password .= substr($characters, $random_number);
  }
  return $password;
}



//Example Usage (Illustrative - needs actual database setup)
// $email = "test@example.com";
// $resetLink = forgot_password($email);
// echo $resetLink;

?>


<?php

// Database connection details - Replace with your actual values
$host = "localhost";
$username = "your_username";
$password = "your_password";
$dbname = "your_database_name";

// Function to handle password reset
function forgotPassword($email) {
    // 1. Generate a unique, secure token
    $token = bin2hex(random_bytes(32));

    // 2. Create a temporary password reset link
    $resetLink = "/reset_password.php?token=" . $token . "&email=" . urlencode($email);

    // 3.  Prepare the SQL query
    $query = "INSERT INTO password_resets (email, token, expires) VALUES ('" . $email . "', '" . $token . "', NOW() + INTERVAL 1 HOUR)"; 

    // 4.  Database connection
    $conn = new mysqli($host, $username, $password, $dbname);

    // 5. Check for connection errors
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // 6. Execute the query
    if ($conn->query($query) === TRUE) {
        // 7. Send email (Implementation needed - See below for an example)
        sendResetPasswordEmail($email, $resetLink);
        echo "Password reset email sent to " . $email;
    } else {
        echo "Error creating password reset link: " . $conn->error;
    }

    // 8. Close the connection
    $conn->close();
}


// Function to send the password reset email (Placeholder - Replace with your actual email sending logic)
function sendResetPasswordEmail($email, $resetLink) {
    // This is a placeholder.  You'll need to replace this with your email sending code.
    // This example just prints the email link to the console.

    // In a real application, you'd use a library like PHPMailer or SwiftMailer
    // to send the email.  Make sure you configure your email settings correctly.

    echo "<br>Password reset link: <a href='" . $resetLink . "'>Click here to reset your password</a>";
}

// Example usage:  (Call this function with the user's email address)
// You'd typically get the email from a form submission.

// Example - For demonstration purposes ONLY.  Do NOT use this in a production environment!
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST["email"];
    if (!empty($email)) {
        forgotPassword($email);
    } else {
        echo "Please enter your email address.";
    }
}


?>


<?php

// Assuming you have a database connection established
// For example:
// $db = new mysqli("localhost", "username", "password", "database_name");
// if (!$db) {
//     die("Connection failed.");
// }

/**
 * Resets a user's password.
 *
 * @param string $email The user's email address.
 * @return bool True on success, false on failure.
 */
function forgotPassword(string $email) {
  // Sanitize the email address (important for security!)
  $email = filter_var($email, FILTER_SANITIZE_EMAIL);

  // Validate email format
  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    return false;
  }

  // 1.  Check if the email exists in the database.
  //     Use a prepared statement to prevent SQL injection!
  $stmt = $db->prepare("SELECT id, password_reset_token FROM users WHERE email = ?");
  $stmt->bind_param("s", $email);
  $stmt->execute();

  // Check if the query was successful
  if ($stmt->errno) {
    error_log("Error executing query: " . $stmt->error);  // Log the error for debugging
    $stmt->close();
    return false;
  }

  $result = $stmt->get_result();

  if ($result->num_rows == 0) {
    $stmt->close();
    return false; // Email not found
  }

  $user = $result->fetch_assoc();
  $stmt->close();

  // 2. Generate a unique, secure, and temporary password reset token.
  $resetToken = bin2hex(random_bytes(32));  // Use a cryptographically secure random number generator

  // 3.  Generate a password reset link with the token.
  $resetLink = "http://yourdomain.com/reset-password?token=" . $resetToken;

  // 4.  Update the user's record with the token.
  $stmt = $db->prepare("UPDATE users SET password_reset_token = ? WHERE email = ?");
  $stmt->bind_param("ss", $resetToken, $email);
  $result = $stmt->execute();

  if ($stmt->errno) {
    error_log("Error updating user: " . $stmt->error);
    $stmt->close();
    return false;
  }

  // 5.  Send an email to the user with the reset link.
  //  (Implement email sending logic here - using PHPMailer or similar)
  $subject = "Password Reset Link";
  $message = "Click the following link to reset your password: " . $resetLink;
  $headers = "From: your_email@example.com\r
";
  // sendmail_path($message, $headers); // Using sendmail_path  (ensure sendmail_path is defined and accessible)
  // Alternative email sending using PHPMailer:
  // require_once 'vendor/PHPMailer/PHPMailer.php';
  // $mail = new PHPMailer(true);
  // $mail->isSMTP();
  // $mail->Host = 'smtp.example.com';
  // $mail->Port = 587;
  // $mail->Username = 'your_email@example.com';
  // $mail->Password = 'your_password';
  // $mail->SetFrom('your_email@example.com', 'Your Application');
  // $mail->Subject = $subject;
  // $mail->Body = $message;
  // $mail->AltBody = $message;  // For non-HTML email clients
  // $mail->send();


  return true; // Success!
}

// Example usage:
$email = "test@example.com"; // Replace with the user's email

if (forgotPassword($email)) {
  echo "Password reset email sent to " . $email . ". Please check their inbox.";
} else {
  echo "Failed to generate password reset link for " . $email . ".";
}

?>


<?php

// Database connection details (replace with your actual details)
$host = 'localhost';
$username = 'your_username';
$password = 'your_password';
$database = 'your_database';

// Function to reset password
function forgotPassword($email, $newPassword) {
  // 1. Connect to the database
  $conn = new mysqli($host, $username, $password, $database);

  // Check connection
  if ($conn->connect_error) {
    return "Connection failed: " . $conn->connect_error;
  }

  // 2.  Securely Hash the New Password
  $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);

  // 3. Prepare and Execute the SQL Query
  $sql = "UPDATE users SET password = '$hashedPassword' WHERE email = '$email'";

  if ($conn->query($sql) === TRUE) {
    return "Password reset successful. Please check your email for further instructions.";
  } else {
    return "Error resetting password: " . $conn->error;
  }

  // 4. Close the connection
  $conn->close();
}

// Example Usage (Demonstration - Don't expose this directly in a public-facing application!)
//  This part demonstrates how to call the function.
//  In a real application, you'd get the email and new password from a form submission.

// Simulate getting data from a form (for demonstration only)
$email = 'test@example.com';  // Replace with a valid email address
$newPassword = 'newSecurePassword123';  // Replace with the desired new password

// Call the function
$result = forgotPassword($email, $newPassword);

// Display the result
echo $result;

?>


<?php

// ... (Database connection details - same as before)

// Function to reset password (using prepared statement)
function forgotPassword($email, $newPassword) {
  // 1. Connect to the database
  $conn = new mysqli($host, $username, $password, $database);

  // Check connection
  if ($conn->connect_error) {
    return "Connection failed: " . $conn->connect_error;
  }

  // 2. Prepare the SQL statement
  $sql = "UPDATE users SET password = ? WHERE email = ?";  // Use placeholders instead of direct variable substitution
  $stmt = $conn->prepare($sql);

  // 3. Bind the parameters
  $stmt->bind_param("s", $newPassword, $email);  // 's' indicates a string

  // 4. Execute the statement
  if ($stmt->execute()) {
    return "Password reset successful. Please check your email for further instructions.";
  } else {
    return "Error resetting password: " . $conn->error;
  }

  // 5. Close the connection
  $conn->close();

  // 6. Close the statement
  $stmt->close();
}


<?php

/**
 * Forgot Password Function
 *
 * This function allows a user to request a password reset.
 * It generates a unique token and stores it in the database
 * along with the user's email.  A reset link is then emailed
 * to the user.
 *
 * @param string $email The email address of the user.
 * @return bool True on success, false on failure.
 */
function forgot_password(string $email)
{
    // 1. Validate Email (Basic validation - you should expand this)
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        error_log("Invalid email address provided: " . $email); // Log for debugging
        return false;
    }

    // 2. Generate a Unique Token
    $token = bin2hex(random_bytes(32));

    // 3. Hash the Token (Important for Security)
    $hashed_token = password_hash($token, PASSWORD_DEFAULT);

    // 4. Store Token and Email in Database
    // Assuming you have a database connection called $db
    // and a table named 'users' with columns 'email' and 'reset_token'
    $sql = "INSERT INTO users (email, reset_token) VALUES ('$email', '$hashed_token')";

    if ($db->query($sql) === false) {
        error_log("Database error: " . $db->error);  //Log database errors
        return false;
    }

    // 5. Send Email with Reset Link
    $subject = "Password Reset Link";
    $message = "Click this link to reset your password: " . $_SERVER['REQUEST_SCHEME'] . "://localhost/reset_password.php?token=$token"; // Construct the full URL
    $headers = "From: your_email@example.com"; // Replace with your email
    
    if (mail($email, $subject, $message, $headers)) {
        return true;
    } else {
        error_log("Failed to send email: " . error_get_last()['message']);
        // Handle email sending failure (e.g., check mail settings, SMTP configuration)
        return false;
    }
}

// Example Usage (Simulated Database - Replace with your actual database connection)
// Assume $db is your database connection object

// $email = "test@example.com";
// if (forgot_password($email)) {
//     echo "Password reset email sent to " . $email;
// } else {
//     echo "Failed to send password reset email.";
// }

?>


<?php

// Assuming you have a database connection established
// (e.g., $db = mysqli_connect("localhost", "username", "password", "database_name");)

// Function to reset password
function forgot_password($email) {
    // 1. Generate a unique, secure token
    $token = bin2hex(random_bytes(32));

    // 2. Store the token and expiration time in the database
    //   (Important:  Always expire tokens after a certain duration.)
    //  This example assumes you have a 'users' table with 'email' and 'password' columns
    //  and a 'reset_tokens' table with 'email' and 'token' columns.
    //  You'll need to adapt this part to your specific database schema.

    // Check if the email already has a reset token
    $stmt = $db->prepare("SELECT id FROM reset_tokens WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Token already exists.  Update the existing token
        $stmt = $db->prepare("UPDATE reset_tokens SET token = ?, expiration = NOW() WHERE email = ? AND token = ?");
        $stmt->bind_param("ss", $token, $email, $token); // token is used as a placeholder to update
        $result = $stmt->execute();

        if ($result) {
            // Success:  Send an email (implementation not included - see below)
            //  You would typically use a mail function or an email library.
            echo "Password reset link generated. Check your email for a reset link.";
        } else {
            // Handle error
            error_log("Error updating reset token: " . $db->error);
            echo "Error updating token. Please try again.";
        }
    } else {
        // 1. Insert a new reset token into the database
        $stmt = $db->prepare("INSERT INTO reset_tokens (email, token, expiration) VALUES (?, ?, NOW())");
        $stmt->bind_param("ss", $email, $token);

        if ($stmt->execute()) {
            // 2. Send an email to the user with the reset link
            $reset_url = "https://yourwebsite.com/reset_password?token=" . $token; // Replace with your actual URL

            //  Implementation for sending the email (simplified example - customize!)
            $subject = "Password Reset Request";
            $message = "Click the link below to reset your password: " . $reset_url;
            $headers = "From: yourwebsite@example.com"; // Replace with your email address

            // Use mail() function (may require configuration)
            if (mail($email, $subject, $message, $headers)) {
                echo "Password reset link generated. Check your email for a reset link.";
            } else {
                // Handle error
                error_log("Error sending email: " . mail($email, $subject, $message, $headers));
                echo "Error sending email. Please check your email settings.";
            }

        } else {
            // Handle error
            error_log("Error inserting reset token: " . $db->error);
            echo "Error generating reset token. Please try again.";
        }
    }

    $stmt->close();
    $result->close();
}

// Example usage (This is just a demonstration, don't use it directly in production)
// Replace 'user@example.com' with the actual email address
// forgot_password('user@example.com');


// **Important Security Considerations and Improvements:**

// 1. **Salt and Hash Passwords:**  Never store passwords in plain text. Always hash them using a strong hashing algorithm (e.g., bcrypt, Argon2) before storing them in the database.  This function assumes you're already doing that for the user's password.

// 2. **Token Expiration:**  Tokens *must* expire after a certain period (e.g., 1 hour, 12 hours). This prevents attackers from using old, potentially compromised tokens.  The `expiration = NOW()` sets the expiration time.

// 3. **Token Uniqueness:** Ensure the generated tokens are truly unique. `random_bytes(32)` provides a high degree of uniqueness.

// 4. **Rate Limiting:** Implement rate limiting to prevent brute-force attacks on the password reset endpoint.  Limit the number of reset requests from the same IP address within a given time frame.

// 5. **Input Validation and Sanitization:** Always validate and sanitize user input (email address, etc.) to prevent SQL injection and other security vulnerabilities.

// 6. **HTTPS:**  Always use HTTPS to encrypt the communication between the user's browser and your server.

// 7. **Error Handling:**  Implement robust error handling and logging to help you debug issues and identify potential security breaches.  Don't display detailed error messages to the user in a production environment.

// 8. **Email Sending Implementation:**  The `mail()` function is often unreliable across different servers and configurations. Consider using a dedicated email library or service (e.g., PHPMailer, SwiftMailer, SendGrid, Mailgun) for more reliable email sending.

// 9. **Security Audits:** Regularly conduct security audits to identify and address potential vulnerabilities.

?>


<?php

// This is a simplified example and should be used with caution.
// In a real-world scenario, you'd want to add:
// - Stronger security measures (e.g., hashing passwords properly)
// - Email validation
// - Input sanitization to prevent vulnerabilities like SQL injection
// - Logging for debugging and security audits
// - Rate limiting to prevent brute-force attacks

// **IMPORTANT:**  Never store passwords in plain text.  Always hash them.
// This example uses a placeholder for hashing, you MUST implement a secure hashing method.

class UserForgotPassword {

    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function resetPassword($email) {
        // 1. Check if the user exists
        $user = $this->db->query("SELECT id, email FROM users WHERE email = ?", [$email]); // Replace 'users' with your table name
        if ($user->rowCount() === 0) {
            return false; // User not found
        }

        // 2. Generate a unique, time-based token
        $token = bin2hex(random_bytes(32)); // Use a strong random number generator

        // 3. Store the token in the database, linked to the user's ID
        $this->db->query("UPDATE users SET reset_token = ? WHERE email = ?", [$token, $email]);


        // 4.  Send an email with a link containing the token.
        $subject = 'Password Reset Link';
        $to = $email;
        $from = 'your_email@example.com'; // Replace with your email
        $message = "Click this link to reset your password: " . $_SERVER['REQUEST_SCHEME'] . "://yourdomain.com/reset-password?token=" . $token; // Replace 'yourdomain.com'
        $headers = "From: " . $from . "\r
";
        $headers .= "Reply-To: " . $from . "\r
";

        mail($to, $message, $headers); // Using PHP's built-in mail function - may require configuration.


        return true;
    }

    public function verifyToken($token) {
        // 1. Check if the token exists in the database
        $user = $this->db->query("SELECT id, email FROM users WHERE reset_token = ?", [$token]);

        if ($user->rowCount() === 0) {
            return false; // Invalid token
        }

        // 2.  If the token exists, delete it from the database to invalidate it.
        $this->db->query("UPDATE users SET reset_token = NULL WHERE reset_token = ?", [$token]);

        return true;
    }

    public function setPasswordForUser($email, $newPassword) {
        // 1.  Securely hash the new password
        $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);

        // 2.  Update the user's password in the database
        $this->db->query("UPDATE users SET password = ? WHERE email = ?", [$hashedPassword, $email]);

        return true;
    }
}



// **Example Usage (illustrative - replace with your actual database connection)**
$db = new PDO("mysql:host=localhost;dbname=your_database", "your_username", "your_password");

// 1. Initiate password reset
$email = "testuser@example.com"; // Replace with the user's email
$reset = new UserForgotPassword($db);
$reset->resetPassword($email);

echo "Password reset link sent to " . $email . "
";


//  Later, when the user clicks the link (in a separate page):
//  1.  Verify the token
//  2.  If the token is valid, set the user's password using the setPasswordForUser method
//  3.  Invalidate the token (delete from the database)
?>


<?php

// This is a simplified example and should be used with caution.
// In a real-world scenario, you should implement robust security measures
// like rate limiting, email verification, and using secure password hashing.

/**
 * Resets a user's password.
 *
 * @param string $email The user's email address.
 * @param string $newPassword The new password.
 * @param string $dbHost     The database host.
 * @param string $dbName     The database name.
 * @param string $dbUser     The database username.
 * @param string $dbPassword The database password.
 *
 * @return bool True if password reset email was sent successfully, false otherwise.
 */
function forgotPassword(string $email, string $newPassword, string $dbHost, string $dbName, string $dbUser, string $dbPassword)
{
  // 1. Database Connection
  try {
    $conn = new PDO("mysql:host=$dbHost;dbname=$dbName", $dbUser, $dbPassword);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  } catch (PDOException $e) {
    error_log("Database connection error: " . $e->getMessage()); // Log the error
    return false;
  }


  // 2. Check if user exists
  try {
    $stmt = $conn->prepare("SELECT id, email FROM users WHERE email = :email");
    $stmt->bindParam(':email', $email, PDO::PARAM_STR);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
      // 3. Generate a unique token
      $token = bin2hex(random_bytes(32)); // Generate a random token

      // 4. Hash the new password
      $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);

      // 5. Update user's password and add token
      $stmt = $conn->prepare("UPDATE users SET password = :password, reset_token = :token, reset_token_expiry = :expiry  WHERE id = :user_id");
      $stmt->bindParam(':password', $hashedPassword, PDO::PARAM_STR);
      $stmt->bindParam(':token', $token, PDO::PARAM_STR);
      $stmt->bindParam(':expiry', date('Y-m-d H:i:s', time() + 3600), PDO::PARAM_STR); // Token expires in 1 hour
      $stmt->bindParam(':user_id', $user['id'], PDO::PARAM_INT);
      $stmt->execute();

      // 6. Send Password Reset Email (Placeholder - Replace with your email sending logic)
      $resetLink = "http://yourdomain.com/reset-password?token=$token";
      $subject = "Password Reset Request";
      $message = "Please reset your password: " . $resetLink;

      // In a real application, you would use a library or service
      // to send the email.  This is just a placeholder.
      // You might use PHPMailer, SwiftMailer, or integrate with a third-party service.
      // For example:
      // sendEmail($email, $subject, $message);



      return true;  // Password reset email sent
    } else {
      // User not found
      return false;
    }
  } catch (PDOException $e) {
    error_log("Database error during password reset: " . $e->getMessage());
    return false;
  }
}


// Example Usage (for testing -  DO NOT USE IN PRODUCTION WITHOUT SECURITY HARDENING)
// Replace with your database credentials and email sending logic
$email = 'testuser@example.com';
$newPassword = 'NewSecurePassword123';
$dbHost = 'localhost';
$dbName = 'your_database_name';
$dbUser = 'your_db_user';
$dbPassword = 'your_db_password';

if (forgotPassword($email, $newPassword, $dbHost, $dbName, $dbUser, $dbPassword)) {
  echo "Password reset email has been sent. Check your email!";
} else {
  echo "Failed to reset password. Please check your email and try again, or contact support.";
}

?>


<?php

// Database connection details (replace with your actual credentials)
$host = "localhost";
$username = "your_username";
$password = "your_password";
$database = "your_database";

// Function to handle the forgot password process
function forgotPassword($email) {
  // 1. Validate email format (basic check)
  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    return "Invalid email address.";
  }

  // 2.  Check if the user exists in the database
  $conn = new mysqli($host, $username, $password, $database);

  if ($conn->connect_error) {
    return "Connection failed: " . $conn->connect_error;
  }

  $query = "SELECT id, email FROM users WHERE email = '$email'";
  $result = $conn->query($query);

  if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $userId = $row['id'];
    $userEmail = $row['email'];

    // 3. Generate a unique, secure token
    $token = bin2hex(random_bytes(32)); // Generate a 32-byte random string
    
    // 4.  Store the token in the database -  use a 'temp_password_token' column
    $insertQuery = "UPDATE users SET temp_password_token = '$token' WHERE id = '$userId'";
    if (!$conn->query($insertQuery)) {
      return "Error updating database.  " . $conn->error;
    }

    // 5.  Send an email to the user with the token and a link
    $to = $email;
    $subject = "Password Reset";
    $message = "Please click on the following link to reset your password:
" .
               "<a href='" . $_SERVER['PHP_SELF'] . "?reset=$token'>Reset Password</a>"; // Use $_SERVER['PHP_SELF']
    $headers = "From: your_email@example.com"; // Replace with your email

    if (mail($to, $subject, $message, $headers)) {
      return "Password reset email sent to $email.  Please check your inbox.";
    } else {
      return "Failed to send password reset email. Check your server's mail configuration.";
    }
  } else {
    return "User not found.";
  }
}

// Example usage (from the reset.php page)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $email = $_POST["email"];
  $resetToken = $_POST["reset_token"];

  if (empty($email) || empty($reset_token)) {
    echo "Error: Both email and token are required.";
  } else {
    $result = forgotPassword($email, $reset_token);
    echo $result; // Display the result
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

  <form method="post" action="reset.php">
    <label for="email">Email:</label>
    <input type="email" id="email" name="email" required><br><br>

    <input type="hidden" name="reset_token" value="">

    <input type="submit" value="Request Password Reset">
  </form>

  <hr>

  <form method="post" action="reset.php">
    <label for="email">Email:</label>
    <input type="email" id="email" name="email" required><br><br>

    <input type="hidden" name="reset_token" value="">

    <label for="reset_token">Reset Token:</label>
    <input type="text" id="reset_token" name="reset_token" required><br><br>

    <input type="submit" value="Reset Password">
  </form>

</body>
</html>


<?php

// Database connection details - Replace with your actual values
$db_host = "localhost";
$db_name = "your_database_name";
$db_user = "your_database_username";
$db_password = "your_database_password";

// Function to reset password
function forgot_password($email)
{
    // 1. Check if email exists in the database
    $conn = new mysqli($db_host, $db_user, $db_password, $db_name);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $sql = "SELECT id, email FROM users WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user_id = $result->fetch_assoc()['id'];
        $user_email = $result->fetch_assoc()['email'];

        // 2. Generate a unique token for the reset link
        $token = bin2hex(random_bytes(32)); // Generates a secure random string

        // 3. Store the token in the database associated with the user
        $sql = "UPDATE users SET password_reset_token = ? WHERE email = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $token, $email);

        if ($stmt->execute() === TRUE) {
            // 4.  Send an email with the reset link
            $reset_link = "http://localhost/reset_password.php?token=" . $token; // Replace with your website URL
            $to = $email;
            $subject = "Password Reset";
            $message = "Click on the link below to reset your password: " . $reset_link;
            $headers = "From: your_email@example.com"; // Replace with your email address

            mail($to, $subject, $message, $headers);

            echo "Password reset link sent to $email";
        } else {
            echo "Error updating password reset token: " . $conn->error;
        }

    } else {
        echo "Email not found";
    }

    $stmt->close();
    $conn->close();
}

// Example Usage (Demonstration -  Don't use this directly in a production environment)
//  This is only for testing the function.  Securely validate and sanitize user inputs before using.
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST["email"];
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

    <form method="post" action="">
        <label for="email">Enter your email:</label>
        <input type="email" id="email" name="email" required>
        <button type="submit">Reset Password</button>
    </form>

</body>
</html>


<?php

/**
 * Forgets a user's password.
 *
 * This function generates a random token, stores it in the database 
 * associated with the user's account, and sends an email to the user 
 * containing a link to reset their password.
 *
 * @param string $email The email address of the user.
 * @param string $token A random, unique token.  This should be generated securely.
 * @param string $reset_link_expiry The expiry time for the reset link (e.g., '24 hours').
 * @return bool True on success, false on failure.
 */
function forgot_password(string $email, string $token, string $reset_link_expiry) {
  // 1. Validate Input (Important Security Step)
  if (empty($email)) {
    error_log("Forgot password: Empty email provided.");
    return false;
  }

  if (empty($token)) {
    error_log("Forgot password: Empty token provided.");
    return false;
  }

  // 2. Retrieve User
  $user = get_user_by_email($email); // Implement this function
  if (!$user) {
    error_log("Forgot password: User with email $email not found.");
    return false;
  }

  // 3. Generate Reset Link
  $reset_link = generate_reset_link( $user->id, $token, $reset_link_expiry ); // Implement this function

  // 4. Store Reset Token (in the database)
  if (!store_reset_token( $user->id, $token, $reset_link_expiry )) {
    error_log("Forgot password: Failed to store reset token for user $email.");
    return false;
  }

  // 5. Send Password Reset Email
  if (!send_password_reset_email($user->email, $reset_link)) {
    error_log("Forgot password: Failed to send password reset email to $email.");
    // You might want to consider deleting the token from the database 
    // to prevent abuse if the email fails.  However, deleting it could 
    // be problematic if the email delivery is eventually successful.
    // delete_reset_token( $user->id, $token );
    return false;
  }


  return true;
}



/**
 * Placeholder function to get a user by email. 
 *  **IMPORTANT:** Replace with your actual database query.
 *  This is just an example.
 *
 * @param string $email The email address.
 * @return object|null  A user object if found, null otherwise.
 */
function get_user_by_email(string $email) {
  // Replace this with your actual database query
  // Example using a hypothetical database connection:
  // $db = get_db_connection();
  // $stmt = $db->prepare("SELECT * FROM users WHERE email = ?");
  // $stmt->execute([$email]);
  // $user = $stmt->fetch();

  // Simulate a user object
  $user = new stdClass();
  $user->id = 123;
  $user->email = $email;
  return $user;
}


/**
 * Placeholder function to generate a reset link. 
 * **IMPORTANT:** Implement a secure random token generation.
 *
 * @param int $userId The ID of the user.
 * @param string $token The generated token.
 * @param string $expiry The expiry time for the link.
 * @return string The generated reset link.
 */
function generate_reset_link(int $userId, string $token, string $expiry) {
  // Implement a secure random token generation method here.
  // Example:
  $reset_link = "https://yourdomain.com/reset-password?token=$token&expiry=$expiry";
  return $reset_link;
}


/**
 * Placeholder function to store the reset token in the database.
 * **IMPORTANT:**  Use parameterized queries to prevent SQL injection.
 *
 * @param int $userId The ID of the user.
 * @param string $token The generated token.
 * @param string $expiry The expiry time for the link.
 * @return bool True on success, false on failure.
 */
function store_reset_token(int $userId, string $token, string $expiry) {
  // Replace with your actual database query
  // Example using a hypothetical database connection:
  // $db = get_db_connection();
  // $stmt = $db->prepare("INSERT INTO reset_tokens (user_id, token, expiry) VALUES (?, ?, ?)");
  // $stmt->execute([$userId, $token, $expiry]);
  // return $db->lastInsertId() > 0;

  return true; // Simulate success
}


/**
 * Placeholder function to delete the reset token.
 *  Use this if the email fails and you want to immediately remove the token.
 *  Consider the implications before deleting the token.
 *
 * @param int $userId The ID of the user.
 * @param string $token The token.
 * @return bool True on success, false on failure.
 */
function delete_reset_token(int $userId, string $token) {
  // Replace with your actual database query
  // Example:
  // $db = get_db_connection();
  // $stmt = $db->prepare("DELETE FROM reset_tokens WHERE user_id = ? AND token = ?");
  // $stmt->execute([$userId, $token]);
  // return $stmt->rowCount() > 0;

  return true; // Simulate success
}


/**
 * Placeholder function to send the password reset email.
 * **IMPORTANT:**  Use a reliable email sending service.
 *
 * @param string $email The email address.
 * @param string $reset_link The generated reset link.
 */
function send_password_reset_email(string $email, string $reset_link) {
  // Replace with your actual email sending logic.
  // Use a reliable email sending service (e.g., SendGrid, Mailgun).
  // Example (Simulated):
  // $subject = "Password Reset";
  // $body = "Click this link to reset your password: " . $reset_link;
  // send_email($email, $subject, $body);

  // Simulate success
  return true;
}
?>


<?php

// Assuming you have a database connection established
// (e.g., $db connection object)
// and that you've defined a 'users' table with an 'email' column.

// Function to handle password reset
function forgotPassword($email, $resetToken, $resetHash, $db) {
  // 1. Check if the token exists in the database
  $query = "SELECT id FROM reset_tokens WHERE email = ? AND token = ?";
  $stmt = $db->prepare($query);
  $stmt->bind_param("ss", $email, $resetToken);
  $stmt->execute();

  if ($stmt->rowCount() == 0) {
    // Token not found
    return false;
  }

  // 2. If the token exists, update the 'used' column to true
  $query = "UPDATE reset_tokens SET used = 1 WHERE email = ? AND token = ?";
  $stmt = $db->prepare($query);
  $stmt->bind_param("ss", $email, $resetToken);
  $stmt->execute();

  if ($stmt->rowCount() == 0) {
    // Error updating token -  rare, but handle it.
    error_log("Error updating reset token: " . $db->error); // Log for debugging
    return false;
  }

  // 3. Send an email with a reset link (implement this part)
  //   -  Generate a temporary password and email it to the user
  //   -  Include a link to the password reset form with the token
  //   -  Set an expiration time for the token
  
  // Example:  (Replace with your email sending logic)
  $subject = "Password Reset Link";
  $to = $email;
  $body = "Click on this link to reset your password: " . $_SERVER['REQUEST_URI'] . "?token=" . $resetToken;

  //  Use a real email sending function here (e.g., sendmail, PHPMailer)
  //  Example using PHP's built-in mail function (simplest, but often unreliable):
  mail($to, $subject, $body);


  return true; // Token updated successfully
}


// Example Usage (for testing - this doesn't actually send an email)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $email = $_POST["email"];
  $resetToken = $_POST["reset_token"];
  $resetHash = $_POST["reset_hash"];  // This would be a hash of a temp password.  Don't store plain text passwords!

  //  Simulate a database connection (replace with your actual connection)
  $db = new mysqli("localhost", "username", "password", "database_name");

  if ($db->connect_error) {
    die("Connection failed: " . $db->connect_error);
  }

  if (forgotPassword($email, $resetToken, $resetHash, $db)) {
    echo "Password reset request sent.  Check your email.";
  } else {
    echo "Password reset request failed.  Possibly an invalid token.";
  }
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>Password Reset</title>
</head>
<body>

  <h1>Password Reset</h1>

  <form method="post" action="">
    <label for="email">Email:</label>
    <input type="email" id="email" name="email" required><br><br>

    <input type="hidden" name="reset_token" value="<?php echo isset($_GET['token']) ? $_GET['token'] : ''; ?>">

    <input type="submit" value="Reset Password">
  </form>

  <p>If you forgot your password, enter your email address to receive a reset link.</p>

</body>
</html>


<?php

// Assuming you have a database connection established
// (e.g., $db = new PDO(...) or similar)

// Function to handle the forgot password process
function forgot_password($email) {
    // 1. Validate Email (Important for security!)
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return "Invalid email address.";
    }

    // 2. Generate a Unique Token
    $token = bin2hex(random_bytes(32));  // More secure random token

    // 3. Store the Token in the Database (associated with the email)
    // This is where you'd insert a record into your users table
    // with columns like 'email', 'token', and 'token_expiry'
    // This example assumes a 'users' table with 'email' and 'password' columns.
    //  Adjust to your actual database schema.

    // Assuming you have a database connection called $db
    try {
        $stmt = $db->prepare("INSERT INTO users (email, token, token_expiry) VALUES (:email, :token, :expiry)");
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':token', $token);
        $stmt->bindParam(':expiry', date('Y-m-d H:i:s', strtotime('+30 minutes'))); // Token expires after 30 minutes. Adjust as needed.
        $stmt->execute();
    } catch (PDOException $e) {
        return "Error inserting token into database: " . $e->getMessage();
    }

    // 4. Send an Email with the Reset Link
    $subject = "Password Reset Request";
    $to = $email;
    $headers = "From: your_email@example.com" . "\r
";
    $link = "http://yourwebsite.com/reset_password.php?token=$token"; // Replace with your actual reset password page URL.  **Use HTTPS in production!**

    $message = "Click the link below to reset your password:
" . $link;

    mail($to, $message, $headers);

    return "Password reset link has been sent to your email address. Please check your inbox (and spam folder!).";
}


// Example Usage (This is just for demonstration - not for production)
// $email = "testuser@example.com"; // Replace with the user's email
// $result = forgot_password($email);
// echo $result;
?>


<?php

// Assuming you have a database connection established
// (e.g., $db = mysqli_connect("host", "user", "password", "database");)

/**
 * Resets a user's password.
 *
 * @param string $email The user's email address.
 * @param string $new_password The new password.
 * @return bool True if password reset was successful, false otherwise.
 */
function forgot_password(string $email, string $new_password) {
    // 1. Validate input (important for security)
    if (empty($email) || empty($new_password)) {
        return false; // Or throw an exception - better practice for robust applications
    }

    // 2. Check if the user exists
    $stmt = mysqli_prepare($db, "SELECT id FROM users WHERE email = ?");
    mysqli_stmt_bind_param($stmt, "s", $email); // 's' for string
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if (mysqli_num_rows($result) == 0) {
        mysqli_stmt_close($stmt);
        return false; // User not found
    }

    $user_id = mysqli_fetch_assoc($result)['id'];
    mysqli_stmt_close($stmt);

    // 3. Hash the new password
    $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

    // 4. Update the password in the database
    $stmt = mysqli_prepare($db, "UPDATE users SET password = ? WHERE id = ?");
    mysqli_stmt_bind_param($stmt, "ss", $hashed_password, $user_id);
    if (mysqli_stmt_execute($stmt)) {
        mysqli_stmt_close($stmt);
        return true;
    } else {
        // Handle database error (important!)
        error_log("Error resetting password for user: " . $email); // Log the error for debugging
        return false;
    }
}


// Example Usage (Illustrative - Replace with your actual form processing)
//  (Assuming you have a form with email and new_password fields)

//  $email = $_POST['email'];
//  $new_password = $_POST['new_password'];

//  if (forgot_password($email, $new_password)) {
//      echo "Password reset email sent.  Check your inbox.";
//      // Send email to confirm the password reset
//  } else {
//      echo "Error resetting password. Please try again.";
//  }


?>


<?php

// IMPORTANT:  This is a simplified example for demonstration purposes only.
// In a production environment, you MUST:
// 1.  Store passwords securely (hashed and salted).
// 2.  Implement robust security measures against brute-force attacks.
// 3.  Use a proper email sending library to avoid spam and ensure delivery.
// 4.  Never log or store email reset tokens.
// 5.  Consider adding CAPTCHA or other verification steps.

// Database connection details (Replace with your actual credentials)
$db_host = 'localhost';
$db_name = 'your_database_name';
$db_user = 'your_database_user';
$db_password = 'your_database_password';

/**
 * Resets the user's password.
 *
 * @param string $email The user's email address.
 * @return bool True if the password reset was initiated successfully, false otherwise.
 */
function forgot_password(string $email): bool
{
    // 1. Connect to the database
    $conn = new mysqli($db_host, $db_user, $db_password, $db_name);

    // Check connection
    if ($conn->connect_error) {
        error_log("Failed to connect to database: " . $conn->connect_error);  // Log the error
        return false;
    }

    // 2. Check if the user exists
    $sql = "SELECT id, email FROM users WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email); // "s" indicates a string parameter
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 0) {
        $stmt->close();
        $conn->close();
        return false; // User not found
    }

    $user = $result->fetch_assoc();
    $userId = $user['id'];
    $stmt->close();


    // 3. Generate a unique reset token
    $resetToken = bin2hex(random_bytes(32)); //  A strong, random token

    // 4. Store the token in the database associated with the user
    $sql = "INSERT INTO password_resets (user_id, token, expires_at) VALUES (?, ?, NOW())";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iis", $userId, $resetToken, $resetToken); // i = integer, s = string
    $stmt->execute();
    $stmt->close();



    // 5. Send the password reset email
    $to = $email;
    $subject = 'Password Reset';
    $message = "Click the following link to reset your password: " .  $_SERVER['PHP_SELF'] . "?reset_token=" . $resetToken; //Use the current script name to generate a direct link.
    $headers = "From: your_email@example.com"; // Replace with your email address

    mail($to, $subject, $message, $headers);

    // 6.  Close the connection
    $conn->close();

    return true;
}


// Example Usage (This would typically be in a form submission handler)
if (isset($_GET['reset_token'])) {
    $resetToken = $_GET['reset_token'];

    if (forgot_password($resetToken)) {
        echo "Password reset email sent to " . $resetToken . ".  Check your inbox.";
    } else {
        echo "Error: Could not reset password.";
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
    <form method="get" action="your_script_name.php"> <!-- Replace with the actual filename -->
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required>
        <button type="submit">Reset Password</button>
    </form>
</body>
</html>


<?php

// This is a simplified example for demonstration purposes only.
// For a production environment, you should:
// 1. Use a secure password reset mechanism (e.g., token-based).
// 2. Sanitize and validate all inputs rigorously.
// 3. Implement proper error handling and logging.
// 4. Consider using a dedicated password reset library for added security.


function forgot_password($email, $password_reset_token, $reset_link_base_url, $secret_key) {
    // 1. Validate Email (Basic - consider stricter validation in production)
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return "Invalid email address.";
    }

    // 2. Check if a password reset token exists for this email
    $reset_token = md5($email . $reset_token . $secret_key); //  Use a strong hashing algorithm!
    
    $query = "SELECT id FROM password_resets WHERE email = '$email' AND token = '$reset_token'";
    $result = mysqli_query($GLOBALS['db'], $query); // Use a prepared statement for security!
    
    if (mysqli_num_rows($result) == 0) {
        return "Invalid password reset token.";
    }

    // 3.  (In a real application) You'd likely generate a new token and
    //     expire the old one.
    //     This is just a simplified example.

    // 4. (In a real application)  You would redirect to a page where the user
    //     can set a new password, using the token to verify their request.
    //     This is a placeholder for that logic.
    return "Password reset link has been sent to your email address.";
}


// Example Usage (Illustrative - Replace with your actual database and configuration)
//  (Don't use this directly in production - it's just for demonstration)

// Assume you have a database connection established (e.g., $GLOBALS['db'] is your connection)

$email = "test@example.com"; // Replace with a valid email address
$reset_token = "random_token_123";  // Generate a unique, random token
$reset_link_base_url = "http://yourwebsite.com/reset-password";

$result = forgot_password($email, $reset_token, $reset_link_base_url, "your_secret_key");

echo $result;  // Output: Password reset link has been sent to your email address.



// Important Security Notes and Best Practices:

// 1. Token Generation and Security:
//    - Use a cryptographically secure random number generator (CSPRNG) for generating the password reset token.  `random_bytes()` or `openssl_random_pseudo_bytes()` are better than `rand()` or `mt_rand()`
//    - The token should be a long, random string.
//    - Store the token in a database securely.
//    - The token should be time-limited (e.g., expire after 30 minutes).

// 2. Hashing:
//    - **Never** store passwords in plain text. Use a strong password hashing algorithm like `password_hash()` or `bcrypt`.

// 3. Prepared Statements (Critical for Security):
//    - **Always** use prepared statements to prevent SQL injection attacks.  The example uses `mysqli_query()` which can be vulnerable if not properly secured.  Switch to prepared statements (e.g., `mysqli_stmt`) for a robust solution.

// 4. Input Validation:
//    - Thoroughly validate all user inputs to prevent vulnerabilities.  Use `filter_var()` with appropriate filters (e.g., `FILTER_VALIDATE_EMAIL`, `FILTER_SANITIZE_EMAIL`).

// 5. Error Handling and Logging:
//    - Implement proper error handling to gracefully handle unexpected situations.
//    - Log errors and suspicious activity for debugging and security monitoring.

// 6. Session Management:
//    - Use sessions to track user activity and maintain security.

// 7. Rate Limiting:
//     - Implement rate limiting to prevent brute-force attacks.

// 8. HTTPS:
//     - Always use HTTPS to encrypt communication between the user's browser and your server.
?>


<?php

// Database connection details (Replace with your actual credentials)
$host = "localhost";
$username = "your_username";
$password = "your_password";
$database = "your_database_name";

// Function to send an email (Requires PHP mail function)
function sendPasswordResetEmail($email, $resetToken) {
  // Implement your email sending logic here using a library like PHPMailer
  // For example:
  $to = $email;
  $subject = 'Password Reset';
  $message = "Click on this link to reset your password: <a href='" . $_SERVER['PHP_SELF'] . "?reset_token=$resetToken'>Reset Password</a>";
  $headers = "From: your_email@example.com";

  mail($to, $message, $headers);
}


// Function to handle password reset request
function forgotPassword($email) {
  // 1. Validate Email
  if (empty($email)) {
    return "Error: Email address is required.";
  }

  // 2. Check if email exists in the user table
  $conn = new mysqli($host, $username, $password, $database);
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  $sql = "SELECT id, email FROM users WHERE email = '$email'";
  $result = $conn->query($sql);

  if ($result->num_rows > 0) {
    $user_id = $result->fetch_assoc()['id'];
    $resetToken = generateRandomToken(); // Generate a unique token
    $conn->query("UPDATE users SET reset_token = '$resetToken' WHERE id = '$user_id'");

    // Send password reset email
    sendPasswordResetEmail($email, $resetToken);

    return "Password reset email sent to $email. Please check your inbox.";

  } else {
    return "Error: Email address not found.";
  }

  $conn->close();
}


// Function to generate a random token (for security)
function generateRandomToken() {
  return bin2hex(random_bytes(32)); // Generate 32-byte random data
}

// --- Example Usage (for testing - should be handled by a form in a real application)
// if (isset($_POST['forgot_email'])) {
//   $email = $_POST['forgot_email'];
//   $result = forgotPassword($email);
//   echo $result;
// }

?>


<?php

// IMPORTANT: This is a simplified example for demonstration purposes.
// In a real-world scenario, you should *never* store passwords in plain text.
// Use a strong hashing algorithm like bcrypt or Argon2.

// Assume you have a database connection established (e.g., $db)
// and a user table with a 'email' column.

function forgot_password($email, $db) {
  // 1. Validate Email
  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    return ['status' => 'error', 'message' => 'Invalid email address.'];
  }

  // 2. Check if the user exists
  $result = $db->query("SELECT id, email FROM users WHERE email = '$email'");

  if ($result->num_rows === 0) {
    return ['status' => 'error', 'message' => 'User not found.'];
  }

  $user = $result->fetch_assoc();

  // 3. Generate a unique, time-based token
  $token = bin2hex(random_bytes(32)); // Generate a secure random token

  // 4. Store the token and user ID in the database
  $db->query("UPDATE users SET password_reset_token = '$token' WHERE email = '$email'");

  // 5. Send an email to the user with a link containing the token
  //    (This is where you would integrate with an email sending service.)
  //    For example:
  //    $subject = 'Password Reset Link';
  //    $message = "Please click the link below to reset your password: " . $base_url . "/reset_password?token=$token";
  //    $headers = 'From: your_email@example.com' . "\r
";
  //    $result = mail($email, $subject, $message, $headers);

  // 6. Return a success response
  return ['status' => 'success', 'message' => 'Password reset link sent to your email.'];
}


// Example Usage (Demonstration - REPLACE with actual database interaction)
//  --  This is just a placeholder to demonstrate the function.
//  --  In a real application, you'll use a database connection.

// Mock Database Connection (For demonstration only)
class MockDatabase {
    public function query($sql) {
        // Simulate query results for testing.  Replace with actual DB interaction.
        if (strpos($sql, "SELECT") === 0) {
            if (strpos($sql, "users WHERE email = 'test@example.com'") === 0) {
                return new \stdClass();
            }
        }
        return new \stdClass();
    }
}


$db = new MockDatabase(); // Using a mock database

$email = 'test@example.com';

$result = forgot_password($email, $db);

if ($result['status'] === 'success') {
  echo "Password reset link sent to $email.
";
} else {
  echo "Error: " . $result['message'] . "
";
}
?>


<?php

// Assuming you have a database connection established and named $db

/**
 * Resets a user's password.
 *
 * @param string $email The email address of the user.
 * @param string $newPassword The new password.
 * @return bool True on success, false on failure.
 */
function forgot_password(string $email, string $newPassword) {
  global $db; // Assuming $db is your database connection

  // 1.  Check if the email exists in the database.  Case-insensitive comparison is important.
  $stmt = $db->prepare("SELECT id FROM users WHERE email = ?");
  $email_lower = strtolower($email);  // Convert to lowercase for comparison
  $stmt->bind_param("s", $email_lower);
  $stmt->execute();
  $result = $stmt->get_result();

  if ($result->num_rows == 0) {
    $stmt->close();
    return false; // Email not found
  }

  $user_id = $result->fetch_assoc()['id'];
  $stmt->close();


  // 2.  Hash the new password before updating the database.  **CRITICAL SECURITY STEP**
  $hashed_password = password_hash($newPassword, PASSWORD_DEFAULT);

  // 3.  Update the user's password in the database.
  $stmt = $db->prepare("UPDATE users SET password = ? WHERE id = ?");
  $stmt->bind_param("ss", $hashed_password, $user_id);
  $exec_result = $stmt->execute();

  if (!$exec_result) {
    // Handle database errors.  Important for debugging!
    error_log("Error updating password for user " . $email . ": " . $db->error);
    $stmt->close();
    return false;
  }

  $stmt->close();

  return true; // Password reset successful
}


// Example Usage (Illustrative -  This would be part of your form handling)

//  Assuming you have $email and $new_password from form input.

// $email = $_POST['email'];
// $newPassword = $_POST['new_password'];

// if (isset($email) && isset($newPassword)) {
//   if (forgot_password($email, $newPassword)) {
//     echo "Password reset email sent. Please check your inbox.";
//   } else {
//     echo "Error resetting password. Please try again.";
//   }
// } else {
//   echo "Invalid input. Please enter your email and a new password.";
// }

?>


<?php

// Assuming you have a database connection established (e.g., $db)
// and a user table with email and password fields.

// Function to handle the forgot password request
function forgotPassword($email) {
  // 1. Validate the email
  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    return "Invalid email address.";
  }

  // 2. Check if the user exists in the database
  $stmt = $db->prepare("SELECT id, password FROM users WHERE email = ?");
  $stmt->execute([$email]);
  $user = $stmt->fetch(PDO::FETCH_ASSOC);

  if (!$user) {
    return "User not found.";
  }

  // 3. Generate a unique token
  $token = bin2hex(random_bytes(32));

  // 4.  Prepare the password reset token
  $reset_token = $token . "_" . $timestamp = date("Y-m-d H:i:s", time());


  // 5. Hash the password (Important for security!)
  $hashedPassword = password_hash($user['password'], PASSWORD_DEFAULT);

  // 6.  Store the token in the database associated with the user
  $stmt = $db->prepare("UPDATE users SET password_reset_token = ?, password_reset_token_expiry = ? WHERE email = ?");
  $stmt->execute([$token, date("Y-m-d H:i:s", time() + 60 * 60 * 24), $email]); // Store for 24 hours

  // 7. Send the password reset email (Example - Customize this!)
  $to = $email;
  $subject = 'Password Reset';
  $message = "Click <a href='" . $_SERVER['PHP_SELF'] . "?reset=$token'</a> to reset your password.";
  $headers = "From: your_email@example.com" . "\r
";

  mail($to, $message, $headers);

  return "Password reset email sent. Please check your inbox.";
}

// Example Usage (This would typically be called from a form submission)
// You would validate the form input and then call this function.

// Example Form Handling (Illustrative - Adjust to your form structure)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $email = $_POST["email"];
  $reset_token = $_GET["reset"];
    //Security - Sanitize the Reset Token
  $reset_token = filter_var($reset_token, FILTER_SANITIZE_STRING);
    if($reset_token == ""){
      $response = "Invalid Reset Token";
    }else{
      $response = forgotPassword($email);
    }


}else{
  $response = "Please fill in the email field.";
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>Forgot Password</title>
</head>
<body>

  <h1>Forgot Password</h1>

  <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
    <label for="email">Email:</label>
    <input type="email" id="email" name="email" required>
    <button type="submit">Reset Password</button>
  </form>

  <?php
    if(isset($response)){
      echo "<p>" . htmlspecialchars($response) . "</p>";
    }
  ?>

</body>
</html>


<?php

// Database connection details (replace with your actual details)
$host = "localhost";
$username = "your_username";
$password = "your_password";
$dbname = "your_database";

// Function to handle password reset
function forgot_password($email)
{
    // 1. Check if the email exists in the database
    $conn = new mysqli($host, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $sql = "SELECT id, email FROM users WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email); // "s" indicates a string
    $stmt->execute();

    $result = $stmt->get_result();

    if ($result->num_rows == 0) {
        $stmt->close();
        $conn->close();
        return false; // Email not found
    }

    $user = $result->fetch_assoc();
    $userId = $user['id'];
    $userEmail = $user['email'];
    $stmt->close();

    // 2. Generate a unique token
    $token = bin2hex(random_bytes(32));

    // 3. Store the token and user ID in the database
    $sql = "UPDATE users SET reset_token = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $token, $userId);
    $stmt->execute();

    if (!$stmt->affected_rows > 0) {
        $stmt->close();
        $conn->close();
        return false; // Failed to store the token
    }

    // 4.  Send an email with the reset link
    $to = $email;
    $subject = "Password Reset Link";
    $message = "Click this link to reset your password: " . "<a href='" . $_SERVER["PHP_SELF"] . "?reset=" . urlencode($token) . "'>Reset Password</a>";
    $headers = "From: your_email@example.com"; // Replace with your email address

    mail($to, $subject, $message, $headers);

    // 5. Close the connection
    $conn->close();

    return true; // Password reset link sent successfully
}


// Example Usage (This should be in a separate file or part of a form)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST["email"];

    if (empty($email)) {
        echo "Error: Please enter your email address.";
    } else {
        if (forgot_password($email)) {
            echo "Password reset link has been sent to your email address.";
        } else {
            echo "Error: Failed to send password reset link. Please try again.";
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

    <h2>Forgot Password</h2>

    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required>
        <button type="submit">Reset Password</button>
    </form>

</body>
</html>


<?php

/**
 * Forgets a user's password.
 *
 * This function generates a unique, time-based token and sends an email
 * containing a link to reset the password.
 *
 * @param string $email The email address of the user to reset the password for.
 * @param string $token A unique token to verify the request. (Optional, used for security)
 * @return bool True on success, false on failure.
 */
function forgot_password(string $email, string $token = '') {
  // 1. Check if the email exists in the database
  $user = get_user_by_email($email);  // Implement this function - See Example Below
  if (!$user) {
    return false; // User does not exist
  }

  // 2. Generate a unique token
  $token = generate_unique_token(); // Implement this function - See Example Below

  // 3. Store the token in the database, associated with the user's email
  save_token_to_database($token, $email); // Implement this function - See Example Below


  // 4. Build the password reset link
  $reset_link = "/reset_password?token=" . urlencode($token) . "&email=" . urlencode($email);

  // 5. Send the password reset email
  $subject = "Password Reset Request";
  $message = "Please click the following link to reset your password: " . $reset_link;

  $headers = "From: Your Website <noreply@yourwebsite.com>";  // Replace with your actual email address

  $result = send_email($email, $subject, $message, $headers); // Implement this function - See Example Below
  if ($result === true) { // Assuming send_email returns true on success
    return true;
  } else {
    // Handle email sending failure (log, display error, etc.)
    error_log("Failed to send password reset email for " . $email);
    return false;
  }
}


// ------------------------------------------------------------------
//  Placeholder functions - You *MUST* implement these!
// ------------------------------------------------------------------

/**
 * Retrieves a user from the database based on their email address.
 *
 * @param string $email The email address to search for.
 * @return object|null User object if found, null otherwise.
 */
function get_user_by_email(string $email): ?object {
  // **IMPORTANT:** Replace this with your actual database query.
  // This is just a placeholder.
  // Example using mysqli:
  // $conn = mysqli_connect("your_db_host", "your_db_user", "your_db_password", "your_db_name");
  // if (!$conn) {
  //   die("Connection failed: " . mysqli_connect_error());
  // }

  // $sql = "SELECT * FROM users WHERE email = '$email'";
  // $result = mysqli_query($conn, $sql);

  // if (mysqli_num_rows($result) > 0) {
  //   $row = mysqli_fetch_assoc($result);
  //   return $row;
  // } else {
  //   return null;
  // }

  // **Dummy User Object** - Remove this when integrating with your database
  $user = (object) [
    'id' => 1,
    'email' => 'test@example.com',
    'password' => 'hashed_password'
  ];
  return $user;
}


/**
 * Generates a unique, time-based token.
 *
 * @return string A unique token.
 */
function generate_unique_token(): string {
  return bin2hex(random_bytes(32)); // More secure than generating a random string
}


/**
 * Saves the token to the database, associated with the user's email.
 *
 * @param string $token The token to save.
 * @param string $email The email address to associate with the token.
 * @return void
 */
function save_token_to_database(string $token, string $email) {
  // **IMPORTANT:** Implement your database logic here.
  // Example using mysqli:
  // $conn = mysqli_connect("your_db_host", "your_db_user", "your_db_password", "your_db_name");
  // if (!$conn) {
  //   die("Connection failed: " . mysqli_connect_error());
  // }

  // $sql = "INSERT INTO tokens (email, token, expiry_date) VALUES ('$email', '$token', NOW())";
  // if (mysqli_query($conn, $sql)) {
  //   //  Success
  // } else {
  //   // Handle error
  // }

  // **Dummy Database Logic** - Remove this when integrating with your database
  //  This just stores the token in a variable to demonstrate functionality
  $_SESSION['reset_token'] = $token;
  $_SESSION['reset_expiry'] = date('Y-m-d H:i:s', time() + 3600); // Expires in 1 hour
}



/**
 * Sends an email.
 *
 * @param string $to       The recipient's email address.
 * @param string $subject  The email subject.
 * @param string $body     The email body.
 * @param string $headers  Email headers.
 * @return bool True on success, false on failure.
 */
function send_email(string $to, string $subject, string $body, string $headers) {
  // **IMPORTANT:**  Replace this with your actual email sending code.
  // This is just a placeholder.  Use a library like PHPMailer.

  // Example using PHPMailer (requires installation and configuration)
  // require_once 'PHPMailer/PHPMailerAutoload.php';
  // $mail = new PHPMailer();
  // $mail->Mailer = 'PHPMailer';
  // $mail->SMTPDebugEnable = false; // Set to true for debugging
  // $mail->isSMTP();                       // Set to true for SMTP
  // $mail->Host       = 'smtp.example.com';
  // $mail->SMTPAuth   = true;                    // Enable SMTP authentication
  // $mail->Username   = 'your_smtp_username';
  // $mail->Password   = 'your_smtp_password';
  // $mail->Port = 587;                         // Port for submission
  // $mail->SetFrom('your_website@example.com', 'Your Website');
  // $mail->Subject = $subject;
  // $mail->Body = $body;
  // $mail->AltBody = $body;
  // $mail->AddAttachment('attachment.php', 'Attachment name');  // Add attachments
  // $result = $mail->send($to, $headers);

  // **Dummy Email Sending** - Remove this when integrating with your email provider
  // For demonstration purposes, just return true.
  return true;
}
?>


<?php

// Assuming you have a database connection established (e.g., $db)

/**
 * Forgets a user's password and sends a password reset link.
 *
 * @param string $email The email address of the user.
 * @return bool True if a reset link was successfully generated and sent, false otherwise.
 */
function forgotPassword(string $email): bool
{
    // Validate email format (basic check, use a proper validation library in production)
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        error_log("Invalid email format provided: " . $email); // Log for debugging
        return false;
    }

    // 1. Generate a unique, secure token (using a cryptographically secure random function)
    $token = bin2hex(random_bytes(32));

    // 2. Hash the token (for security)
    $hashedToken = password_hash($token, PASSWORD_DEFAULT);  // Using password_hash for security.

    // 3. Store the token and user ID in the database
    $query = "INSERT INTO password_resets (user_id, token, expires_at)
              VALUES (:user_id, :token, :expires_at)";

    $stmt = $db->prepare($query); // Assuming $db is your database connection
    $stmt->bindParam(':user_id', $userId);
    $stmt->bindParam(':token', $hashedToken);
    $stmt->bindParam(':expires_at', time() + (2 * 60 * 60)); // Token expires in 2 hours (example)
    $result = $stmt->execute();

    if (!$result) {
        error_log("Error inserting reset token into database: " . print_r($stmt->errorInfo(), true)); // Log the error
        return false;
    }

    // 4.  Send the password reset link (email)
    $to = $email;
    $subject = "Password Reset Link";
    $message = "Click this link to reset your password: " . $_SERVER['HTTPS'] . "://" . $_SERVER['HTTP_HOST'] . "/reset_password?token=" . $token;
    $headers = "From: Your Website <noreply@yourwebsite.com>"; // Replace with your actual sender email

    if (mail($to, $subject, $message, $headers)) {
        return true;
    } else {
        error_log("Failed to send password reset email to " . $email);
        // Optionally, delete the reset token from the database if the email fails.
        // This is crucial to prevent abuse.
        deleteResetToken($userId, $token);
        return false;
    }
}


/**
 * Deletes a password reset token from the database.
 *
 * @param int $userId The ID of the user.
 * @param string $token The token to delete.
 */
function deleteResetToken(int $userId, string $token): void
{
    $query = "DELETE FROM password_resets WHERE user_id = :user_id AND token = :token";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':user_id', $userId);
    $stmt->bindParam(':token', $token);
    $stmt->execute();
}


// Example Usage (in your web form handling logic)

//  ... (form submission handling) ...

//  $email = $_POST['email']; // Get the email from the form

//  if (forgotPassword($email)) {
//      echo "Password reset email sent. Check your inbox.";
//  } else {
//      echo "An error occurred while sending the password reset email.";
//  }

?>


<?php

/**
 * Forgets a user's password.
 *
 * This function generates a unique, time-based token to be sent to the user's email.
 * The user can then use this token to reset their password.
 *
 * @param string $email The email address of the user.
 * @param string $token A unique, time-based token.  This is generated internally.
 * @param string $reset_url The URL to redirect the user to after they use the reset token.
 * @return bool True if the token was successfully sent, false otherwise.
 */
function forgot_password(string $email, string $token, string $reset_url): bool
{
    // 1. Validate Input (Basic - Add more robust validation as needed)
    if (empty($email)) {
        error_log("Forgot password: Empty email provided");
        return false;
    }
    if (empty($token)) {
        error_log("Forgot password: Empty token provided");
        return false;
    }

    // 2.  Store the token in the database (Replace with your actual database logic)
    // This is a simplified example - use prepared statements to prevent SQL injection!
    $db_connection = new PDO('mysql:host=localhost;dbname=your_database_name', 'your_username', 'your_password');  // Replace with your credentials
    try {
        $stmt = $db_connection->prepare("INSERT INTO forgot_passwords (email, token, expiry) VALUES (?, ?, NOW())");
        $stmt->execute([$email, $token]);
    } catch (PDOException $e) {
        error_log("Forgot password: Database error: " . $e->getMessage());
        return false;
    }

    // 3. Send the reset email
    $subject = "Password Reset";
    $message = "Please click on the following link to reset your password: " . $reset_url;
    $headers = "From: your_email@example.com";  // Replace with your email

    if (mail($email, $subject, $message, $headers)) {
        error_log("Forgot password: Email sent to " . $email);
        return true;
    } else {
        error_log("Forgot password: Failed to send email to " . $email);
        return false;
    }
}


// Example usage (for testing - NEVER put sensitive data directly in your code)
// Replace these with actual values
$email = "testuser@example.com";  // Replace with a valid email
$token = "abcdef1234567890";
$reset_url = "https://yourwebsite.com/reset-password?token=" . urlencode($token); // Encode the token for the URL

if (forgot_password($email, $token, $reset_url)) {
    echo "Password reset email sent to " . $email;
} else {
    echo "Failed to send password reset email.";
}

?>


<?php

/**
 *  Forgot Password Function
 *
 *  This function generates a unique token, sends an email with a reset link,
 *  and updates the user's password reset status in the database.
 *
 * @param string $email The user's email address.
 * @param string $newPassword The new password for the user.
 * @param string $token  A unique token generated for the reset link. (Generated internally)
 * @param PDO $pdo      The PDO database connection object.
 *
 * @return bool True if the password reset process was initiated successfully,
 *              false otherwise (e.g., email sending failure, invalid email).
 */
function forgotPassword(string $email, string $newPassword, PDO $pdo)
{
    // 1. Generate a Unique Token
    $token = bin2hex(random_bytes(32)); // Use a cryptographically secure random function
    //  'bin2hex' converts the byte array from random_bytes() into a hexadecimal string.
    //  This is a standard way to create a unique token.


    // 2.  Prepare the SQL Query
    $sql = "UPDATE users SET password = :password, password_reset_token = :token, password_reset_token_expiry = :expiry, password_reset_status = 'valid' WHERE email = :email";

    // 3. Prepare the statement
    $stmt = $pdo->prepare($sql);


    // 4. Bind Parameters
    $stmt->bindParam(':password', $newPassword);
    $stmt->bindParam(':token', $token);
    $stmt->bindParam(':expiry', date('Y-m-d H:i:s', time() + (60 * 60 * 24))); // Expire after 24 hours
    $stmt->bindParam(':email', $email);


    // 5. Execute the Query
    if ($stmt->execute()) {
        // 6. Generate the Reset Link (Email Content)
        $resetLink = "https://yourwebsite.com/reset-password?token=" . $token;  // Replace yourwebsite.com

        // 7.  Send the Email (Implement your email sending logic here)
        $to = $email;
        $subject = 'Password Reset';
        $message = "Click the link below to reset your password:
" . $resetLink;
        $headers = 'From: yourwebsite@example.com' . "\r
";

        if (mail($to, $subject, $message, $headers)) {
            // Email sent successfully.
            return true;
        } else {
            // Email sending failed
            // Optionally log the error here.  Crucial for debugging.
            error_log("Error sending password reset email for " . $email);
            return false;
        }
    } else {
        // Query failed - likely an invalid email or other database issue
        error_log("Error updating password for " . $email);
        return false;
    }
}


// Example Usage (Replace with your actual database connection)
//  Important:  This is just an example.  You MUST adapt this to your specific setup.

// Dummy database connection (replace with your actual connection)
$host = 'localhost';
$db_name = 'your_database_name';
$db_user = 'your_database_user';
$db_password = 'your_database_password';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db_name;charset=utf8", $db_user, $db_password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}


$email = 'testuser@example.com';  // Replace with the user's email
$newPassword = 'newSecurePassword123';  // Replace with the new password

if (forgotPassword($email, $newPassword, $pdo)) {
    echo "Password reset email sent successfully!";
} else {
    echo "Failed to initiate password reset.";
}

?>


<?php

// **Important Security Notes:**
// * This code is a basic example and should NOT be used in a production environment without significant security enhancements.
// * NEVER store passwords directly in the database.  Always hash them using a strong hashing algorithm like bcrypt or Argon2.
// * This example uses a simple email verification. For a real application, you'd want to use a more robust system.
// * Consider using a dedicated password reset service for better security and user experience.
// * Implement rate limiting and anti-abuse measures to prevent brute-force attacks.

// Configuration
$db_host = 'localhost';
$db_name = 'your_database_name';
$db_user = 'your_database_user';
$db_password = 'your_database_password';
$from_email = 'your_email@example.com'; // Sender email address
$website_url = 'https://yourwebsite.com'; // Website URL

// Function to reset the password
function forgot_password($email, $website_url) {
  // 1. Database Connection (Replace with your actual database connection)
  $conn = new mysqli($db_host, $db_user, $db_password, $db_name);

  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  // 2. Generate a unique token
  $token = bin2hex(random_bytes(32)); // Generate a cryptographically secure token

  // 3. Store the token in the database (associate with the email)
  $sql = "INSERT INTO forgot_password_tokens (email, token, expiry_timestamp)
          VALUES ('$email', '$token', NOW() + INTERVAL 24 HOUR)"; // Expires after 24 hours

  if ($conn->query($sql) === FALSE) {
    echo "Error inserting token: " . $conn->error;
    return false;
  }

  // 4.  Create and send the password reset email
  $subject = 'Password Reset Request';
  $message = "Hello,

" .
             "You have requested a password reset for your account.

" .
             "Please click on the following link to reset your password:
" .
             "<a href='" . $website_url . "/reset_password.php?token=$token'>Reset Password</a>

" .
             "If you did not request this password reset, please ignore this email.

" .
             "Sincerely,
" .
             $website_url;

  $headers = "From: " . $from_email . "\r
";
  $headers .= "Reply-To: " . $from_email . "\r
";

  if (!mail($email, $subject, $message, $headers)) {
    echo "Error sending email.
";
    // Attempt to delete the token in case email sending failed.  This is crucial.
    $delete_sql = "DELETE FROM forgot_password_tokens WHERE email = '$email'";
    if ($conn->query($delete_sql) === FALSE) {
        echo "Error deleting token: " . $conn->error;
    }
    return false;
  }

  return true;
}

// Example Usage (Simulated Form Submission)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL); // Sanitize email

  if (empty($email)) {
    echo "Please enter your email address.";
  } else {
    if (forgot_password($email, $website_url)) {
      echo "Password reset email sent to " . $email;
    } else {
      echo "Password reset failed.";
    }
  }
}

?>


<?php

// This is a simplified example and should be used in a production environment
// with proper security measures.

// Database connection (replace with your actual credentials)
$host = 'localhost';
$username = 'your_db_user';
$password = 'your_db_password';
$database = 'your_database_name';

// Reset token generation function (should use a strong, random function)
function generateRandomString($length = 32) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyz';
    $randomString = '';
    $charactersLength = strlen($characters);
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}


// Reset password function
function forgotPassword($email, $host, $username, $password, $database) {
    // 1. Validate Email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return 'Invalid email address.';
    }

    // 2. Check if user exists
    $stmt = $conn->prepare("SELECT id, email FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 0) {
        $stmt->close();
        return 'User not found.';
    }

    $user = $result->fetch_assoc();
    $userId = $user['id'];
    $stmt->close();



    // 3. Generate a unique token
    $resetToken = generateRandomString();

    // 4.  Create a reset token record in the database
    $stmt = $conn->prepare("INSERT INTO password_resets (user_id, token, expiry) VALUES (?, ?, ?)");
    $stmt->bind_param("iii", $userId, $resetToken, time() + 60); // Expires in 60 minutes
    $result = $stmt->execute();

    if ($result === false) {
        // Handle database errors here (e.g., try again, log error)
        error_log("Error creating password reset record: " . error_get_last()['message']);
        return 'Failed to generate reset token.';
    }

    $resetId = $conn->insert_id; // Get the ID of the inserted row.  Important for later retrieval.
    $stmt->close();


    // 5.  Send reset email
    $subject = 'Password Reset';
    $message = "Click on this link to reset your password: " . '<a href="' . $_SERVER['PHP_SELF'] . '?reset=' . $resetToken . '" >Reset Password</a>';
    $headers = "From: your_email@example.com" . "\r
";

    if(mail($email, $subject, $message, $headers)) {
        return 'Password reset email has been sent.';
    } else {
        return 'Failed to send password reset email.';
    }
}


// Example Usage (Simulated - replace with your form submission)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST["email"];

    // Replace this with your actual database connection
    $conn = new mysqli($host, $username, $password, $database);
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }


    $result = forgotPassword($email, $host, $username, $password, $database);
    echo $result;
}

?>

<!DOCTYPE html>
<html>
<head>
    <title>Forgot Password</title>
</head>
<body>

    <h1>Forgot Password</h1>

    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required>
        <button type="submit">Forgot Password</button>
    </form>

</body>
</html>


<?php

// Ensure this file is in a secure location and not directly accessible via web browser.
// This is just an example for demonstration purposes.
// In a production environment, you should use a proper database and secure authentication practices.

// Database credentials (replace with your actual credentials)
$db_host = 'localhost';
$db_name = 'your_database_name';
$db_user = 'your_db_user';
$db_password = 'your_db_password';


function forgot_password($email) {
  // 1. Check if the email exists in the user table
  $conn = new mysqli($db_host, $db_user, $db_password, $db_name);

  if ($conn->connect_error) {
    // Handle connection error appropriately (e.g., log it, display an error message)
    die("Connection failed: " . $conn->connect_error);
  }

  $sql = "SELECT id, email FROM users WHERE email = ?";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param("s", $email); // "s" indicates a string parameter
  $stmt->execute();

  $result = $stmt->get_result();

  if ($result->num_rows == 0) {
    $stmt->close();
    $conn->close();
    return false; // Email not found
  }

  $user_id = $result->fetch_assoc()['id']; //get the id
  $stmt->close();
  $conn->close();


  // 2. Generate a unique token for the password reset
  $token = bin2hex(random_bytes(32)); // Generate a random string (32 bytes = 256 bits)

  // 3. Store the token and user ID in the database
  $conn = new mysqli($db_host, $db_user, $db_password, $db_name);
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  $sql = "INSERT INTO password_resets (user_id, token, expires_at) VALUES (?, ?, NOW())";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param("ss", $user_id, $token);
  $stmt->execute();

  $stmt->close();
  $conn->close();


  // 4. Send an email with the token and a link to reset the password
  $email_body = "Hello,

" .
                "Please use the following link to reset your password:
" .
                "http://yourwebsite.com/reset_password?token=" . $token . "

" .
                "This link will expire in 24 hours.";  // Adjust expiration time as needed

  //  Replace with your email sending logic (e.g., using PHPMailer, SMTP, etc.)
  //  Example (placeholder):
  //  send_email($email, "Password Reset", $email_body);

  return true; // Password reset request initiated successfully
}


// Example Usage (for demonstration - replace with your form submission handling)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST["email"];
    if (isset($email)) {
      if (forgot_password($email)) {
        echo "Password reset email sent to " . $email;
      } else {
        echo "Email not found or an error occurred during the password reset process.";
      }
    } else {
      echo "Email not provided.";
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

  <form method="post" action="">
    <label for="email">Email:</label>
    <input type="email" id="email" name="email" required>
    <button type="submit">Request Password Reset</button>
  </form>
</body>
</html>


<?php

// Assume you have a database connection established and a user table named 'users'
// with a 'password' column.  This is a simplified example and you'll need 
// to adapt it to your specific database setup.

function forgot_password($email) {
  // 1. Validate Email
  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    return 'Invalid email address.';
  }

  // 2. Check if user exists
  $user = getUserByEmail($email); // Function to retrieve user by email
  if (!$user) {
    return 'User not found.';
  }

  // 3. Generate a Unique Token
  $token = generate_unique_token();

  // 4. Store Token and User ID in a temporary table (or session)
  store_token_for_user($user['id'], $token);  // Function to store the token

  // 5.  Create a reset link
  $reset_link = generate_reset_link($token, $user['email']);

  // 6. Return the reset link to the user
  return $reset_link;
}

// ------------------------------------------------------------------
// Placeholder Functions (Implement these according to your setup)
// ------------------------------------------------------------------

// Function to retrieve user by email (replace with your database query)
function getUserByEmail($email) {
  // Example (using a dummy database - REPLACE with your actual query)
  // This is just a placeholder;  implement your actual database query here.
  $users = [
    ['id' => 1, 'email' => 'test@example.com', 'password' => 'hashed_password'],
    ['id' => 2, 'email' => 'another@example.com', 'password' => 'another_hashed_password']
  ];

  foreach ($users as $user) {
    if ($user['email'] === $email) {
      return $user;
    }
  }
  return null;
}


// Function to generate a unique token (UUID is generally a good choice)
function generate_unique_token() {
  return bin2hex(random_bytes(32)); // Generate a 32-byte UUID
}


// Function to store the token in a temporary table (or session)
function store_token_for_user($user_id, $token) {
  // In a real application, you would insert a record into a temporary table
  // with columns 'user_id' and 'token'.  This is just a placeholder.
  //  Example (using a dummy temporary table):
  //  $sql = "INSERT INTO reset_tokens (user_id, token) VALUES ($user_id, '$token')";
  //  // Execute the query here
  //  return true;

  //  For simplicity in this example, we'll just simulate it:
  echo "Simulating token storage for user ID: " . $user_id . " with token: " . $token . "
";
}


// Function to generate the reset link (including token and email)
function generate_reset_link($token, $email) {
  return "http://yourdomain.com/reset_password?token=" . urlencode($token) . "&email=" . urlencode($email);
}

// ------------------------------------------------------------------
// Example Usage
// ------------------------------------------------------------------

$email_to_reset = 'test@example.com';

$reset_link = forgot_password($email_to_reset);

if ($reset_link === 'Invalid email address.') {
  echo $reset_link . "
";
} elseif ($reset_link === 'User not found.') {
  echo $reset_link . "
";
} else {
  echo "Reset link: " . $reset_link . "
";
  //  Send the reset_link to the user via email or other means
}
?>


<?php

/**
 * Forgets a user's password.
 *
 * This function generates a unique token, sends an email with a link to reset the password,
 * and stores the token in the database.
 *
 * @param string $email The email address of the user.
 * @param string $baseUrl The base URL of your website.  Used for the reset link.
 * @return bool True if the email was sent successfully, false otherwise.
 */
function forgotPassword(string $email, string $baseUrl): bool
{
  // 1. Generate a Unique Token
  $token = bin2hex(random_bytes(32));

  // 2. Prepare the Reset Link
  $resetLink = $baseUrl . '/reset-password?token=' . $token;

  // 3. Prepare the Email Message
  $subject = "Password Reset Request";
  $message = "Click the following link to reset your password: " . $resetLink;
  $headers = "From: " .  $baseUrl . "\r
";
  $headers .= "Reply-To: " . $email . "\r
";

  // 4. Send the Email (using PHPMailer -  Install via Composer: `composer require phpmailer/phpmailer`)
  if (sendEmail($email, $subject, $message, $headers)) {
    // 5. Store the Token in the Database
    saveToken($email, $token);
    return true;
  } else {
    // Handle email sending failure -  Log it or show an error message
    error_log("Failed to send password reset email for " . $email);
    return false;
  }
}


/**
 * Placeholder for sending email (Replace with your actual email sending logic).
 *  This is a placeholder function.  You *must* implement this using a real email library.
 *  Example using PHPMailer:
 *  $mail = new PHPMailer\PHPMailer\PHPMailer();
 *  $mail->SMTPDebugEnable = true;
 *  // Configure SMTP settings (replace with your details)
 *  $mail->isSMTP();
 *  $mail->Host       = 'smtp.gmail.com';
 *  $mail->SMTPAuth   = true;
 *  $mail->Username   = 'your_email@gmail.com';
 *  $mail->Password   = 'your_password';
 *  $mail->Port = 587;
 *  $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
 *
 *  $mail->setFrom($email, $email);
 *  $mail->addAddress('user@example.com'); // Change to user's email
 *  $mail->Subject = $subject;
 *  $mail->Body    = $message;
 *
 *  if(!$mail->send()){
 *     return false;
 *  }
 *  return true;
 *
 * @param string $email       The email address.
 * @param string $subject     The email subject.
 * @param string $message     The email body.
 * @param string $headers     Email headers.
 * @return bool True on success, false on failure.
 */
function sendEmail(string $email, string $subject, string $message, string $headers): bool
{
  // Replace this with your actual email sending logic
  // For testing, you can simply echo the email details.
  error_log("Sending email: To: " . $email . "
Subject: " . $subject . "
Message: " . $message . "
Headers: " . $headers);
  return true; // Placeholder - Always return true for testing.  Implement real sending here.
}


/**
 * Placeholder for saving the token in the database.  Replace with your database logic.
 *
 * @param string $email       The user's email address.
 * @param string $token       The generated token.
 */
function saveToken(string $email, string $token): void
{
  // Placeholder - Replace with your database saving logic
  // Example (using a simple array - **NOT RECOMMENDED FOR PRODUCTION**):
  // $_SESSION['reset_token'] = $token;
  // This is just a demo - for a real application, use a database.

  // Example using database (using PDO):
  // $db = new PDO("mysql:host=localhost;dbname=your_database", "username", "password");
  // $stmt = $db->prepare("INSERT INTO password_resets (email, token, created_at) VALUES (?, ?, NOW())");
  // $stmt->execute([$email, $token]);
}

// Example Usage (for testing - do not use this in your application directly)
// In a real application, you'd get the email from a form submission.
// For this example, we're hardcoding the email.
$baseUrl = 'http://localhost/your-website';  // Replace with your website's base URL

// Simulate a user requesting a password reset
if (isset($_GET['email'])) {
    $email = $_GET['email'];
    if (forgotPassword($email, $baseUrl)) {
        echo "Password reset email has been sent to " . $email;
    } else {
        echo "Failed to send password reset email.";
    }
}
?>


<?php

// Ensure this file is protected (e.g., .htaccess or server configuration)
// to prevent direct access to the password reset file.

// Database connection details (replace with your actual credentials)
$host = 'localhost';
$username = 'your_db_username';
$password = 'your_db_password';
$database = 'your_db_name';

// Email configuration (replace with your email settings)
$from_email = 'your_email@example.com';
$to_email = '%user_email%'; // Use placeholder for user's email
$subject = 'Password Reset Request';
$headers = "From: $from_email\r
";
$headers .= "Reply-To: $from_email\r
";

// Generate a unique, secure token
function generate_token() {
    return bin2hex(random_bytes(32));
}

// Check if the request is valid
if (isset($_POST['email']) && isset($_POST['token'])) {
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL); // Sanitize email input
    $token = filter_var($_POST['token'], FILTER_SANITIZE_STRING); // Sanitize token input

    // Validate email format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "Invalid email format.";
        exit;
    }

    // Check if the token exists in the database (important security step)
    $result = mysqli_query($GLOBALS['database'], "SELECT id, email FROM users WHERE email = '$email' AND token = '$token'");

    if (mysqli_num_rows($result) > 0) {
        // Token exists, proceed with password reset
        $user_id = mysqli_fetch_assoc($result)['id'];
        $user_email = mysqli_fetch_assoc($result)['email'];

        //  Create a temporary password (strong password)
        $temp_password = 'P@$$wOrd'; // Example -  Use a stronger password generation method in a real app.
        //  Generate a unique token for the reset process
        $reset_token = generate_token();

        // Update the user's record with the temporary password and the new reset token
        mysqli_query($GLOBALS['database'], "UPDATE users SET password = '$temp_password', token = '$reset_token', password_reset_expiry = NOW() WHERE id = '$user_id'");

        // Send the password reset email
        $message = "Please use the following link to reset your password:
" .
                   '<a href="' . $_SERVER['REQUEST_URI'] . '?token=' . $reset_token . '">Reset Password</a>';  // Use the same URL for the reset link
        mail($to_email, $subject, $message, $headers);

        echo "Password reset link sent to your email.  Check your inbox.";

    } else {
        echo "Invalid token or user not found.";
    }
} else {
    echo "Invalid request.";
}

// Example usage (for testing - usually handled through a form)
//  This is just an example -  integrate this with a form to collect the email.
//  Implement proper error handling and security best practices.
?>


   function hash_password($password) {
       return password_hash($password, PASSWORD_DEFAULT);
   }
   
   Then, when the user creates an account, hash the password before storing it in the database.  When the user tries to log in, verify the entered password against the hashed value in the database.

3. **Database Abstraction Layer:**  For larger applications, using a database abstraction layer (like PDO or a specific database library) is *highly* recommended.  It provides a more secure and portable way to interact with the database.

4. **Rate Limiting:**  Implement rate limiting to prevent brute-force attacks.  Limit the number of password reset requests from a single IP address within a certain timeframe.

5. **CSRF Protection:** Implement CSRF (Cross-Site Request Forgery) protection to prevent attackers from forging requests to reset passwords.

6. **HTTPS:**  Always use HTTPS to encrypt the communication between the user's browser and the server, protecting the user's credentials during transmission.

7. **Error Logging:**  Implement error logging to track any errors or suspicious activity.

8. **Testing:**  Thoroughly test the password reset functionality to ensure it works as expected and is secure.

9. **Regular Security Audits:** Conduct regular security audits to identify and address any vulnerabilities.

10. **Session Management:** Implement proper session management to ensure user sessions are secure.

This improved code provides a more secure and robust password reset functionality. However, remember that security is an ongoing process. Implement the best practices mentioned above and stay up-to-date with the latest security threats.


<?php

// Assuming you have a database connection established (e.g., $db)

/**
 * Resets a user's password.
 *
 * @param string $email The user's email address.
 * @return bool True if a password reset email was sent successfully, false otherwise.
 */
function forgotPassword(string $email) {
    // Sanitize input - crucial for security
    $email = filter_var($email, FILTER_SANITIZE_EMAIL);

    // Validate email format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        error_log("Invalid email format provided.");
        return false;
    }


    // 1. Check if the user exists
    $user = getUserById($email); // Implement this function (see below)

    if (!$user) {
        error_log("User with email {$email} not found.");
        return false;
    }

    // 2. Generate a unique token
    $token = generateUniqueToken(); // Implement this function (see below)

    // 3. Store the token and expiry in the database (for the user)
    //    This is the key step.  You *must* secure this.
    updateUserToken($user['id'], $token, time() + (3600 * 24)); // Token valid for 24 hours

    // 4.  Send a password reset email
    $resetLink = generateResetLink($token); // Implement this function (see below)
    sendPasswordResetEmail($user['email'], $resetLink);

    return true;
}

/**
 * Placeholder function to get user by email.  Replace with your database query.
 *
 * @param string $email The user's email address.
 * @return array|null The user object or null if not found.
 */
function getUserById(string $email): ?array {
    // **IMPORTANT:  Replace this with your actual database query.**
    // This is just a placeholder to demonstrate the flow.
    // You'll use a query like:
    // $stmt = $db->prepare("SELECT * FROM users WHERE email = ?");
    // $stmt->execute([$email]);
    // $user = $stmt->fetch(PDO::FETCH_ASSOC);
    // return $user;

    // Example return for demonstration:
    return [
        'id' => 1,
        'email' => 'test@example.com',
        'password' => 'hashed_password' // This should be properly hashed
    ];
}


/**
 * Placeholder function to generate a unique token.
 *  Consider using a library for cryptographically secure random number generation.
 *
 * @return string A unique token.
 */
function generateUniqueToken(): string {
    return bin2hex(random_bytes(32)); // Use bin2hex for a hexadecimal representation
}


/**
 * Placeholder function to generate the password reset link.
 *
 * @param string $token The token.
 * @return string The password reset link.
 */
function generateResetLink(string $token): string {
    return 'https://yourwebsite.com/reset-password?token=' . urlencode($token); // Replace with your URL
}


/**
 * Placeholder function to send a password reset email.
 *
 * @param string $email The recipient's email address.
 * @param string $resetLink The password reset link.
 */
function sendPasswordResetEmail(string $email, string $resetLink): void {
    // Implement your email sending logic here.
    // This is just a placeholder.
    echo "Sending password reset email to: " . $email . " with link: " . $resetLink . "
";
    // Example using PHPMailer (you'll need to install it: composer require phpmailer/phpmailer)
    //  require_once 'PHPMailer/PHPMailerAutoload.php';
    //  $mail = new PHPMailer(true);
    //  $mail->isSMTP();
    //  $mail->Host       = 'smtp.example.com';
    //  $mail->SMTPAuth   = true;
    //  $mail->Username   = 'your_email@example.com';
    //  $mail->Password   = 'your_password';
    //  $mail->Port = 587;
    //  $mail->SetFrom('your_email@example.com', 'Your Website Name');
    //  $mail->Subject = 'Password Reset';
    //  $mail->Body    = 'Click this link to reset your password: ' . $resetLink;
    //  $mail->AltBody = $mail->Body;
    //  if ($mail->send()) {
    //      // Email sent successfully
    //  } else {
    //      error_log("Failed to send email.");
    //  }
}


/**
 * Placeholder function to update the user's token in the database.
 *  **IMPORTANT:**  This is where you'd insert or update the 'token' and 'expiry' columns
 *  in your user table.
 *
 * @param int $userId The user's ID.
 * @param string $token The token.
 * @param int $expiry Timestamp when the token expires.
 */
function updateUserToken(int $userId, string $token, int $expiry): void {
    // **IMPORTANT: Replace this with your actual database update query.**
    // Example (using PDO):
    // $stmt = $db->prepare("UPDATE users SET token = ?, expiry = ? WHERE id = ?");
    // $stmt->execute([$token, $expiry, $userId]);
}


// Example usage (call this function to initiate the process)
// $email = "test@example.com";
// if (forgotPassword($email)) {
//   echo "Password reset email sent to {$email}
";
// } else {
//   echo "Failed to send password reset email.
";
// }


?>


<?php

// Database connection details (replace with your actual credentials)
$db_host = 'localhost';
$db_name = 'your_database_name';
$db_user = 'your_username';
$db_password = 'your_password';

// Function to reset password
function forgot_password($email, $new_password, $db) {
    // 1. Validate Email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return "Invalid email format.";
    }

    // 2. Check if user exists
    $stmt = $db->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user) {
        return "User with this email does not exist.";
    }

    // 3. Hash the new password (Important for security!)
    $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

    // 4. Update the password in the database
    $stmt = $db->prepare("UPDATE users SET password = ? WHERE id = ?");
    $stmt->execute([$hashed_password, $user['id']]);

    return "Password reset email sent. Check your inbox!";
}


// --- Example Usage (Demonstration - NOT for direct use in a production environment)
// IMPORTANT:  This example shows how to use the function.  It's a simplified demonstration.
//   In a real application, you would typically have a form to collect the email and new password.

// Create a PDO database connection
try {
    $db = new PDO("mysql:host=$db_host;dbname=$db_name;charset=utf8mb4", $db_user, $db_password);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // Enable exception handling
} catch (PDOException $e) {
    echo "Database connection failed: " . $e->getMessage();
    exit;
}


// Example Usage (Replace with actual user input from a form)
$email = $_POST['email']; // Get email from form
$new_password = $_POST['new_password'];

// Call the forgot_password function
$result = forgot_password($email, $new_password, $db);

// Display the result
echo $result;

// Close the database connection (important!)
$db = null;

?>


<?php

// Assuming you have a database connection established and a 'users' table
// with a 'email' column and potentially a 'password' column.

function forgot_password($email, $reset_token, $expiry_time = 3600) { //default expiry of 1 hour

  // 1. Validate Email
  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    return "Invalid email address.";
  }

  // 2. Check if a reset token already exists for this email
  $query = "SELECT id, token, created_at FROM reset_tokens WHERE email = ? AND token = ? AND expiry_time > NOW()";
  $stmt = $conn->prepare($query);
  $stmt->bind_param("ss", $email, $reset_token);
  $stmt->execute();

  if ($stmt->num_rows > 0) {
    // Token exists, proceed with password reset
    //  Ideally, you'd update the token's expiry time here 
    //  to force a new reset link to be generated.  For simplicity, we'll just
    //  return the token.
    $result = $stmt->fetch_assoc();
    return $result['token']; // Or return the entire result array if needed
  } else {
    // Token does not exist
    return "Invalid reset token.  Please request a new one.";
  }
}



// Example Usage (assuming $conn is your database connection)
//  This is just for testing; in a real application, you would
//  handle the form submission and user interaction.

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $email = $_POST["email"];
  $reset_token = $_POST["reset_token"];

  $new_reset_token = forgot_password($email, $reset_token);

  if ($new_reset_token == "Invalid reset token.  Please request a new one.") {
    echo "<p style='color: red;'>$new_reset_token</p>";
  } else {
      echo "<p style='color: green;'>Reset token: $new_reset_token.  Please use this in the password reset form.</p>";
      // In a real application, you would send an email with this token.
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

  <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
    <label for="email">Email:</label>
    <input type="email" id="email" name="email" required><br><br>

    <!--  In a real application, you would generate a random token
         and store it in the database.  For this example, we'll
         just have the user enter a token. -->
    <label for="reset_token">Reset Token:</label>
    <input type="text" id="reset_token" name="reset_token" required><br><br>

    <button type="submit">Reset Password</button>
  </form>

</body>
</html>


<?php

/**
 * Forgot Password Function
 *
 * This function handles the forgot password process.  It:
 * 1. Generates a unique, time-based token.
 * 2. Sends an email to the user with a link containing the token.
 * 3. Stores the token in the database associated with the user's ID.
 * 4.  Returns a success message or an error message.
 *
 * @param string $email        The user's email address.
 * @param string $site_url    The URL of your website.
 * @param string $reset_token_prefix  (Optional) Prefix for the reset token.  Good for security.
 *
 * @return string  A success or error message.
 */
function forgot_password(string $email, string $site_url, string $reset_token_prefix = 'reset') {

    // 1. Generate a unique, time-based token
    $token = $reset_token_prefix . md5(time());

    // 2.  Check if the email exists in the database.  You'll need a database connection here!
    // Assuming you have a database connection variable called $db
    // $user = $db->query("SELECT id, email FROM users WHERE email = '$email' LIMIT 1")->fetch_assoc();

    // This is a placeholder.  Replace with your actual database query.
    // This example assumes you have a `users` table with `id` and `email` columns.
    $user = $db->query("SELECT id, email FROM users WHERE email = '$email' LIMIT 1")->fetch_assoc();
    
    if (!$user) {
        return "Email not found.";
    }

    // 3.  Store the token in the database
    $result = $db->query("UPDATE users SET reset_token = '$token', reset_token_expiry = NOW() + INTERVAL 1 HOUR WHERE id = $user['id']");

    if (!$result) {
        return "Error updating user information.";
    }

    // 4.  Create the reset password link
    $reset_link = $site_url . "/reset_password.php?token=$token";  // Example URL
    

    // 5.  Send the password reset email.  You'll need to implement this.
    // This is a placeholder - you'll need to replace this with your email sending logic.
    $subject = "Password Reset";
    $message = "Click on the following link to reset your password: " . $reset_link;
    
    // In a real application, you'd use a function like:
    // send_email($user['email'], $subject, $message); 

    return "Password reset email sent to $email";
}


// Example Usage (replace with your actual database connection and settings)
//  This is just an example, it doesn't actually connect to a database
// and won't work as is.

// Assume $db is your database connection object (e.g., mysqli_connect)
//$db = mysqli_connect("localhost", "username", "password", "database_name");

//if (!$db) {
//    die("Database connection failed");
// }


// $email = "test@example.com";
// $reset_message = forgot_password($email, "https://example.com");
// echo $reset_message;


?>


<?php

// Assuming you have a database connection established and a 'users' table with a 'email' column.

//  This is a basic example and should be adapted to your specific setup.
//  For production, you should always sanitize inputs, use prepared statements,
//  and implement proper security measures.

function forgot_password($email) {
  // 1. Check if the email exists in the database.
  $query = "SELECT id, password FROM users WHERE email = ?";
  $stmt = $conn->prepare($query); // Assuming $conn is your database connection object.
  $stmt->bind_param("s", $email);
  $stmt->execute();
  $result = $stmt->get_result();

  if ($result->num_rows == 0) {
    // Email not found
    $stmt->close();
    return false;
  }

  $user = $result->fetch_assoc();
  $stmt->close();

  // 2. Generate a unique, secure token.  Important for security!
  $token = bin2hex(random_bytes(32)); // More secure than simple rand()

  // 3.  Create a password reset link.
  $reset_link = "http://yourdomain.com/reset_password?token=" . $token . "&user_id=" . $user['id'];  // Replace with your domain.
                                   //  This URL should be safe and not expose sensitive data.

  // 4.  Store the token and user ID in the database.
  $query = "INSERT INTO password_resets (user_id, token, expiry) VALUES (?, ?, ?)";
  $stmt = $conn->prepare($query);
  $stmt->bind_param("sss", $user['id'], $token, date("Y-m-d H:i:s", time() + 3600)); // Expire after 1 hour.  Adjust as needed.
  $result = $stmt->execute();

  if (!$result) {
      // Handle errors - log them, display a message, etc.  Don't just silently fail.
      error_log("Error creating password reset entry: " . error_get_last()['message']);
      return false;
  }


  // 5.  Send an email to the user with the reset link.
  $to = $email;
  $subject = "Password Reset";
  $message = "Click on the link below to reset your password:
" . $reset_link;
  $headers = "From: your_email@example.com\r
";
  mail($to, $subject, $message, $headers);  // Use a robust mailer library instead of mail() for production.


  return true;
}


// Example usage (after a user submits the forgot password form)
// $email = $_POST['email'];  // Get email from the form.
// if (forgot_password($email)) {
//   echo "Password reset email has been sent to " . $email;
// } else {
//   echo "Failed to generate password reset link.";
// }


?>


<?php

// Assuming you have a database connection established (e.g., $db)
// and have a 'users' table with a 'email' field.

/**
 * Resets a user's password.
 *
 * @param string $email The user's email address.
 * @return bool True if password reset email sent successfully, false otherwise.
 */
function forgot_password(string $email): bool
{
  // 1. Validate Email
  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    error_log("Invalid email format provided.");
    return false;
  }

  // 2. Check if the user exists
  $user = get_user_by_email($email); // Assuming this function exists - defined below

  if ($user === null) {
    error_log("User with email $email not found.");
    return false;
  }

  // 3. Generate a unique token
  $token = generate_unique_token();

  // 4. Store the token in the database associated with the user
  $result = store_token_for_user($user['id'], $token);

  if (!$result) {
    error_log("Failed to store token for user $email.");
    return false;
  }

  // 5. Send the password reset email
  $subject = "Password Reset Request";
  $message = "Click on this link to reset your password: " .  $_SERVER['PHP_SELF'] . "?token=" . urlencode($token); //  IMPORTANT: Security Considerations below!
  $headers = "From: your_email@example.com\r
";

  if (send_email($email, $subject, $message, $headers) ) {
    return true;
  } else {
    error_log("Failed to send password reset email to $email.");
    // Consider deleting the token if email sending fails.
    delete_token_for_user($user['id']); // Rollback
    return false;
  }
}


//  Dummy functions for demonstration - Replace with your actual implementations

/**
 * Retrieves a user from the database based on their email.
 *
 * @param string $email The user's email address.
 * @return null|array  An associative array representing the user, or null if not found.
 */
function get_user_by_email(string $email): ?array
{
  // Replace this with your actual database query
  // This is a placeholder - use your database connection
  $users = [
    ['id' => 1, 'email' => 'test@example.com'],
    ['id' => 2, 'email' => 'another@example.com']
  ];

  foreach ($users as $user) {
    if ($user['email'] === $email) {
      return $user;
    }
  }
  return null;
}


/**
 * Generates a unique token for password reset.
 *
 * @return string A unique token.
 */
function generate_unique_token(): string
{
    return bin2hex(random_bytes(32)); // Recommended for security
}


/**
 * Stores a token for a user in the database.
 *
 * @param int $userId The ID of the user.
 * @param string $token The token to store.
 * @return bool True if the token was successfully stored, false otherwise.
 */
function store_token_for_user(int $userId, string $token): bool
{
  // Replace this with your actual database insertion logic
  // Example:
  // $query = "INSERT INTO password_tokens (user_id, token, expires_at) VALUES ($userId, '$token', NOW() + INTERVAL 30 MINUTE)";
  // Execute the query and check the result.

  // Placeholder:
  return true;
}


/**
 * Deletes a token for a user from the database.
 *
 * @param int $userId The ID of the user.
 * @return bool True if the token was successfully deleted, false otherwise.
 */
function delete_token_for_user(int $userId): bool
{
  // Replace this with your actual database query
  // Example:
  // $query = "DELETE FROM password_tokens WHERE user_id = $userId";
  // Execute the query and check the result.

  // Placeholder:
  return true;
}


/**
 * Sends an email.
 *
 * @param string $to The recipient's email address.
 * @param string $subject The email subject.
 * @param string $body The email body.
 * @param string $headers Email headers.
 * @return bool True if the email was sent successfully, false otherwise.
 */
function send_email(string $to, string $subject, string $body, string $headers): bool
{
    // Replace this with your actual email sending implementation
    // (e.g., using PHP's mail() function or a library like PHPMailer)

    // Example (using the mail() function - be aware of limitations and security):
    // return mail($to, $subject, $body, $headers);

    // Placeholder - always return true to prevent errors during testing
    return true;
}


// Example Usage (demonstration)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $email = $_POST['email'];
  if (isset($email) && forgot_password($email)) {
    echo "Password reset email sent to " . $email;
  } else {
    echo "Failed to reset password.";
  }
}
?>

<!-- HTML Form for the Password Reset Request -->
<form method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">
  <label for="email">Email:</label>
  <input type="email" id="email" name="email" required>
  <button type="submit">Request Password Reset</button>
</form>


<?php

// Ensure this file is protected to prevent direct access to the database.
// You should also use a more secure method for generating and sending
// the reset token (e.g., using a library for cryptographically secure random numbers).

// Database connection details (Replace with your actual credentials)
$db_host = "localhost";
$db_name = "your_database_name";
$db_user = "your_db_user";
$db_password = "your_db_password";

// Function to generate a unique token
function generateUniqueToken() {
    return bin2hex(random_bytes(32)); // Generates a 32-byte (256-bit) random binary string
}

// Function to send the reset password email (Requires an email setup)
function sendResetPasswordEmail($email, $token) {
    // Implement your email sending logic here.
    // This is just a placeholder.  You'll need to use a library or
    // your email server to actually send the email.

    // Example (replace with your email sending code):
    $subject = "Password Reset";
    $message = "Click on the following link to reset your password: " . "<a href='" . $_SERVER["PHP_SELF"] . "?reset=true&token=" . $token . "'>Reset Password</a>";

    // In a real application, you'd use a library like PHPMailer or SwiftMailer.
    // Example using PHPMailer (requires installation):
    // $mail = new PHPMailer(true);
    // $mail->isSMTP();
    // $mail->Host = 'smtp.example.com';
    // $mail->Port = 587;
    // $mail->Username = 'your_email@example.com';
    // $mail->Password = 'your_password';
    // $mail->SetFrom('your_email@example.com', 'Your Application Name');
    // $mail->Subject = $subject;
    // $mail->Body = $message;
    // $mail->AltBody = $message;  // For email clients that don't support HTML
    // $mail->Send();

    echo "Reset email sent to $email.  Check your inbox!"; // Placeholder
}


// Function to reset the password
function resetPassword($email, $token) {
    // 1. Verify the token
    $query = "SELECT * FROM users WHERE email = '$email' AND token = '$token'";
    $result = mysqli_query($GLOBALS['db_conn'], $query);

    if (mysqli_num_rows($result) > 0) {
        // Token is valid, proceed with password reset

        // 2.  Generate a new, strong password (example)
        $new_password = generateStrongPassword(); // Replace with your password generation function
        $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

        // 3.  Update the user's password in the database
        $update_query = "UPDATE users SET password = '$hashed_password', token = '', last_reset = NOW() WHERE email = '$email'";
        mysqli_query($GLOBALS['db_conn'], $update_query);

        echo "Password reset successful! Please check your email for instructions.";

    } else {
        echo "Invalid token or user not found.";
    }
}


//  ------------------  Example Usage (Simplified for demonstration)  ------------------

// Check if the reset link is clicked
if (isset($_GET['reset']) && isset($_GET['token'])) {
    $email = $_GET['email']; // Potentially add validation here
    $token = $_GET['token'];

    // Validate email (simple example - improve this!)
    if (empty($email) || empty($token)) {
        echo "Error: Email and token are required.";
    } else {
        resetPassword($email, $token);
    }
}

//  ------------------  Database Connection  ------------------
$GLOBALS['db_conn'] = mysqli_connect($db_host, $db_user, $db_password, $db_name);

if (mysqli_connect_error()) {
    die("Connection failed: " . mysqli_connect_error());
}

// Add other necessary database setup/validation here, such as checking if the user exists

?>


<?php

// Assuming you have a database connection established and a 'users' table with an 'email' field
// and a 'password' field.  This example uses a simple username/password approach,
// but in a real application, you'd likely use hashing and salting for security.

// Database connection details - Replace with your actual values
$db_host = 'localhost';
$db_name = 'your_database_name';
$db_user = 'your_username';
$db_password = 'your_password';

/**
 * Resets a user's password using email.
 *
 * @param string $email The email address of the user.
 * @return string  A message indicating success or failure.
 */
function forgot_password($email) {
    // Validate email format (basic check - improve for production)
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return "Invalid email address.";
    }

    // Check if the user exists
    $stmt = $GLOBALS['conn']->prepare("SELECT id, username FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 0) {
        $stmt->close();
        return "User not found.";
    }

    $user = $result->fetch_assoc();
    $stmt->close();

    // Generate a temporary password (for demo purposes - use a cryptographically secure method in production)
    $temp_password = 'TempPassword123';  // Replace with a more robust method

    // Send the password reset email (This is a placeholder - implement your email sending logic here)
    $to = $email;
    $subject = 'Password Reset';
    $message = "To reset your password, please click on this link: " .  $_SERVER['PHP_SELF'] . "?reset=" . urlencode($temp_password);
    $headers = "From: your_email@example.com" . "\r
";

    if (mail($to, $subject, $message, $headers)) {
        return "Password reset link sent to your email address.  Check your inbox.";
    } else {
        return "Failed to send password reset email. Please try again.";
    }
}

// Example Usage (this part would typically be from a form submission)
if ($_SERVER["REQUEST_METHOD"] == "GET") {
    // Get the email from the URL parameter (for testing purposes)
    $email = $_GET["email"];

    // Call the forgot_password function
    $result = forgot_password($email);

    // Display the result
    echo "<p>" . $result . "</p>";
} else {
    // Handle POST request (if the form is submitted)
    $email = $_POST["email"];
    $result = forgot_password($email);
    echo "<p>" . $result . "</p>";
}

?>

<!DOCTYPE html>
<html>
<head>
    <title>Forgot Password</title>
</head>
<body>

    <h1>Forgot Password</h1>

    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required>
        <button type="submit">Reset Password</button>
    </form>

</body>
</html>


<?php

// Database connection details (Replace with your actual details)
$host = "localhost";
$username = "your_username";
$password = "your_password";
$database = "your_database_name";

// Function to reset password
function forgot_password($email, $password_reset_token, $expiration_time_seconds = 60*60*24) { // Default 24 hours
  // 1. Database connection
  $conn = new mysqli($host, $username, $password, $database);

  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  // 2. Prepare the SQL query
  $sql = "UPDATE users SET password = ? , password_reset_token = ? WHERE email = ?";

  // 3. Prepare the statement
  $stmt = $conn->prepare($sql);

  if ($stmt === false) {
    die("Error preparing statement: " . $conn->error);
  }

  // 4. Bind the parameters
  $new_password = "NewSecurePassword123!"; // A placeholder -  Never directly insert a user-provided password.
  $reset_token = $password_reset_token;
  $email = $email;

  $stmt->bind_param("ssi", $new_password, $reset_token, $email);

  // 5. Execute the query
  if (!$stmt->execute()) {
    die("Error executing query: " . $stmt->error);
  }

  // 6. Close the statement and connection
  $stmt->close();
  $conn->close();

  // 7.  Consider sending an email with the link to reset the password
  //     (This part is not included in this basic function but is *essential*
  //     for a real-world implementation)
  return true; // Indicate success
}


// Example Usage (for demonstration - NOT recommended for production)
// IMPORTANT:  This is just an example.  You should *never* directly
//  use user-provided data in a real application without proper sanitization and validation.
//  In a real application, you would generate a unique and secure token.
//  Also, you would send an email to the user with a link containing the token.

// $user_email = "test@example.com"; // Replace with a valid email address
// $reset_token = "unique_token_123";  // Generate a unique token -  Crucial for security
//
// if (forgot_password($user_email, $reset_token)) {
//   echo "Password reset link sent to " . $user_email;
// } else {
//   echo "Failed to reset password.";
// }


?>


<?php

// This is a simplified example for demonstration purposes.
// In a production environment, you should:
// 1.  Securely store passwords (e.g., hash them with bcrypt or Argon2).
// 2.  Implement rate limiting to prevent brute-force attacks.
// 3.  Use email libraries to send actual emails instead of just printing to the screen.
// 4.  Handle errors gracefully and securely.


// Configuration (replace with your database details)
$db_host = "localhost";
$db_name = "your_database_name";
$db_user = "your_db_user";
$db_password = "your_db_password";

// Function to reset password
function forgot_password($email, $new_password, $db) {
  // 1. Validate Email (Basic)
  if (empty($email)) {
    return "Invalid email address.";
  }

  // 2.  Check if user exists (Simple check - improve with more robust validation)
  $result = $db->query("SELECT id, email FROM users WHERE email = '$email'");
  if ($result->num_rows == 0) {
    return "User not found.";
  }

  $user_data = $result->fetch_assoc();
  $user_id = $user_data['id'];
  $user_email = $user_data['email'];

  // 3. Hash the New Password (Crucial for Security!)
  $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

  // 4. Update the Password in the Database
  $sql = "UPDATE users SET password = '$hashed_password' WHERE id = '$user_id'";
  if (!$db->query($sql)) {
    return "Error updating password. " . $db->error;
  }

  return "Password reset email sent. Check your inbox.";
}


// Example Usage (Simulate a form submission)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $email = $_POST["email"];
  $new_password = $_POST["new_password"];

  // Database Connection (replace with your connection logic)
  $db = new mysqli($db_host, $db_user, $db_password, $db_name);

  if ($db->connect_error) {
    die("Connection failed: " . $db->connect_error);
  }

  $result = forgot_password($email, $new_password, $db);
  echo $result; // Output the result to the user
} else {
  // Display the form (for demonstration)
  ?>
  <form method="post" action="">
    <label for="email">Email:</label>
    <input type="email" id="email" name="email" required><br><br>

    <label for="new_password">New Password:</label>
    <input type="password" id="new_password" name="new_password" required><br><br>

    <button type="submit">Reset Password</button>
  </form>
  <?php
}
?>


<?php

// Replace with your database connection details
$db_host = "localhost";
$db_name = "your_database_name";
$db_user = "your_username";
$db_password = "your_password";

// Check if the request method is POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {

  // Get the email address from the form
  $email = $_POST["email"];

  // Validate the email address (basic validation - you should improve this)
  if (empty($email)) {
    $message = "Email address is required.";
  } else {
    // Hash the password (important for security)
    $hashed_password = password_hash("default_password", PASSWORD_DEFAULT);  // You'll need a default password for this example

    // Prepare the SQL query
    $sql = "SELECT id, email FROM users WHERE email = '$email'";

    // Execute the query
    $result = mysqli_query($db_connection, $sql);

    // Check if the query was successful
    if ($result) {
      // Check if any user was found
      if (mysqli_num_rows($result) > 0) {
        // Set the password reset token (a unique, random string)
        $reset_token = bin2hex(random_bytes(32));

        // Prepare the update query
        $update_query = "UPDATE users SET reset_token = '$reset_token' WHERE email = '$email'";

        // Execute the update query
        mysqli_query($db_connection, $update_query);

        // Send an email to the user with the reset link
        $to = $email;
        $subject = "Password Reset";
        $message = "Click on the following link to reset your password: " . "<a href='reset_password.php?token=$reset_token'>Reset Password</a>";
        $headers = "From: your_email@example.com";  // Change this to your email

        mail($to, $message, $headers);


        $message = "Password reset link has been sent to your email address.";
      } else {
        $message = "No user found with this email address.";
      }
    } else {
      $message = "Error querying the database.";
    }
  }
}

// Start the session
session_start();

// Display any error messages
if (isset($message)) {
  echo "<p style='color: red;'>$message</p>";
}

?>

<!-- HTML Form -->
<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
  <label for="email">Email Address:</label>
  <input type="email" id="email" name="email" placeholder="Your Email" required>
  <button type="submit">Reset Password</button>
</form>


<?php

// Assuming you have a database connection established (e.g., $db connection)

/**
 * Resets a user's password.
 *
 * @param string $email The user's email address.
 * @return bool True if password reset initiated successfully, false otherwise.
 */
function forgot_password(string $email): bool
{
  // 1. Validate Email
  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    error_log("Invalid email format provided: " . $email);  // Log the invalid email for debugging
    return false;
  }

  // 2. Check if User Exists
  $user = get_user_by_email($email); // Implement this function (see example below)

  if ($user === null) {
    error_log("User with email " . $email . " not found."); // Log the user not found
    return false;
  }


  // 3. Generate a Unique Token (Important for security!)
  $token = generate_unique_token(); // Implement this function (see example below)


  // 4. Store Token and User ID in Database (Temporary)
  //    This is a temporary link - don't store sensitive information directly.
  $result = store_token_for_user($user['id'], $token);

  if (!$result) {
      error_log("Failed to store token for user " . $email);
      return false;
  }


  // 5. Send Password Reset Email
  $subject = "Password Reset Request";
  $message = "Click this link to reset your password: " .  $_SERVER['REQUEST_SCHEME'] . "://" . $_SERVER['HTTP_HOST'] . "/reset_password?token=" . $token;
  $headers = "From: your_website@example.com\r
";
  $sent = send_email($email, $subject, $message, $headers);

  if (!$sent) {
      error_log("Failed to send password reset email to " . $email);
      // Optionally, you could delete the token from the database if email sending fails.
      // delete_token_for_user($user['id']);
      return false;
  }


  return true;
}



/**
 * Helper function to get user by email (Placeholder - Implement your database logic here)
 *
 * @param string $email The email address to search for.
 * @return array|null An array containing user data if found, or null if not.
 */
function get_user_by_email(string $email): ?array {
    // **Replace this with your actual database query**
    // Example:
    // $query = "SELECT * FROM users WHERE email = '$email'";
    // $result = mysqli_query($db, $query); // Or use PDO or your database driver
    // if (mysqli_num_rows($result) > 0) {
    //   $user = mysqli_fetch_assoc($result);
    //   return $user;
    // } else {
    //   return null;
    // }

  // Placeholder for demonstration:
  $users = [
    ['id' => 1, 'email' => 'test@example.com']
  ];
  foreach($users as $user){
    if($user['email'] == $email){
      return $user;
    }
  }
  return null;
}



/**
 * Helper function to generate a unique token.
 * This should be a cryptographically secure random string.
 *
 * @return string A unique token.
 */
function generate_unique_token(): string {
    return bin2hex(random_bytes(32)); // More secure than rand()
}

/**
 * Helper function to store the token for a user (implementation depends on your database)
 *
 * @param int $userId The ID of the user.
 * @param string $token The token to store.
 * @return bool True if token stored successfully, false otherwise.
 */
function store_token_for_user(int $userId, string $token): bool {
    // **Replace this with your actual database logic**
    // Example using mysqli:
    // $query = "INSERT INTO password_tokens (user_id, token, expiry_date) VALUES ($userId, '$token', NOW() + INTERVAL 30 DAY)";
    // if (mysqli_query($db, $query) === false) {
    //   return false;
    // }
    return true;
}



/**
 * Helper function to delete the token from the database.
 * (Optional - For added security.  Consider if the token is short-lived)
 * @param int $userId
 * @return bool
 */
function delete_token_for_user(int $userId): bool {
    // Implement your database logic to delete the token.
    // Example:
    // $query = "DELETE FROM password_tokens WHERE user_id = $userId";
    // if (mysqli_query($db, $query) === false) {
    //   return false;
    // }
    return true;
}



/**
 * Placeholder function to send an email.
 * You'll need to integrate with your email sending service here.
 * @param string $to
 * @param string $subject
 * @param string $message
 * @param string $headers
 * @return bool
 */
function send_email(string $to, string $subject, string $message, string $headers): bool {
    // **Replace this with your actual email sending logic**
    // Example using a basic SMTP implementation (not recommended for production):
    // $smtp = new PHPMailer();
    // $smtp->SMTPDebugEnable = false;
    // $smtp->Host = 'smtp.example.com';
    // $smtp->Port = 587;
    // $smtp->Username = 'your_username';
    // $smtp->Password = 'your_password';
    // $smtp->SetFrom('your_website@example.com', 'Your Website');
    // $smtp->AddAddress($to);
    // $smtp->Subject = $subject;
    // $smtp->MsgBody = $message, 'html';
    // $smtp->IsHTML(true);
    // $smtp->Send();

    return true; // Placeholder - always returns true
}


<?php

// Database connection details (Replace with your actual credentials)
$db_host = 'localhost';
$db_name = 'your_database_name';
$db_user = 'your_database_username';
$db_password = 'your_database_password';

//  ---  Function to reset password  ---
function forgot_password($email, $new_password, $db) {
    global $db_host, $db_name, $db_user, $db_password;

    // 1. Verify Email Exists
    $stmt = $db->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 0) {
        return "Email not found.";
    }

    $user_id = $result->fetch_assoc()['id'];

    // 2.  Hash the New Password
    $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);


    // 3. Update Password in Database
    $stmt = $db->prepare("UPDATE users SET password = ? WHERE id = ?");
    $stmt->bind_param("ss", $hashed_password, $user_id);

    if ($stmt->execute() === false) {
        return "Failed to update password. Database error: " . $stmt->error;
    }
    
    $stmt->close();

    return "Password reset email sent.";

}


// Example Usage (Illustrative - Replace with your actual form handling)
// This is just for demonstration; you should implement proper form handling
// for security (CSRF protection, input validation, etc.)

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST["email"];
    $new_password = $_POST["new_password"];

    //  Database Connection (Establish Connection - crucial for any database operations)
    global $db_host, $db_name, $db_user, $db_password;

    $db = new mysqli($db_host, $db_user, $db_password, $db_name);

    if ($db->connect_error) {
        die("Connection failed: " . $db->connect_error);
    }


    $result = forgot_password($email, $new_password, $db);
    echo $result; // Output:  "Password reset email sent." or an error message
    $db->close();
}

?>

<!DOCTYPE html>
<html>
<head>
    <title>Forgot Password</title>
</head>
<body>

    <h1>Forgot Password</h1>

    <form method="post" action="">
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required><br><br>

        <label for="new_password">New Password:</label>
        <input type="password" id="new_password" name="new_password" required><br><br>

        <button type="submit">Reset Password</button>
    </form>

</body>
</html>


<?php

// Assuming you have a database connection established (e.g., $db connection)

/**
 * Resets a user's password based on their email address.
 *
 * @param string $email The user's email address.
 * @return bool True if a reset link was sent, false otherwise.
 */
function forgot_password(string $email)
{
    // 1. Validate Email (Important!)
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        error_log("Invalid email address provided: " . $email); // Log for debugging
        return false;
    }

    // 2. Check if the user exists
    $user = get_user_by_email($email);  //  Implement this function (see example below)
    if (!$user) {
        error_log("User with email " . $email . " not found.");
        return false;
    }

    // 3. Generate a Unique Token and Store it in the Database
    $token = generate_unique_token();
    $result = $db->query("UPDATE users SET reset_token = '$token' WHERE email = '$email'");

    if ($result === false) {
        error_log("Error updating user's reset token: " . $db->error);
        return false;
    }

    // 4. Send the Password Reset Email
    $subject = "Password Reset Request";
    $message = "Please click on the following link to reset your password: <a href='" . $_SERVER['PHP_SELF'] . "?reset_token=$token'>Reset Password</a>";
    $headers = "From: your_email@example.com";  // Replace with your email
    $sent = send_email($email, $subject, $message, $headers);


    if (!$sent) {
        // Handle email sending failure (e.g., log the error, try again later)
        error_log("Failed to send password reset email to " . $email);
        //Optionally, you could try to manually trigger a retry.
        return false;
    }

    return true;
}


/**
 * Example function to retrieve user data by email (Replace with your actual database query)
 * This is a placeholder and needs to be adapted to your database schema.
 *
 * @param string $email The email address to search for.
 * @return array|null An associative array representing the user data, or null if not found.
 */
function get_user_by_email(string $email): ?array
{
    $query = "SELECT * FROM users WHERE email = '$email'";
    $result = $db->query($query);

    if ($result->num_rows > 0) {
        return $result->fetch_assoc();
    }

    return null;
}


/**
 * Generates a unique token for password resets.  Should be cryptographically secure.
 *
 * @return string A unique token.
 */
function generate_unique_token(): string
{
    return bin2hex(random_bytes(32)); // Uses a cryptographically secure random number generator.
}



// Example Usage (Demonstration - Don't use in production without proper validation)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST["email"];
    if (forgot_password($email)) {
        echo "Password reset email has been sent to " . $email;
    } else {
        echo "Failed to send password reset email.";
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

    <form method="post" action="">
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required>
        <button type="submit">Reset Password</button>
    </form>

</body>
</html>


<?php

// Database configuration (Replace with your actual values)
$db_host = 'localhost';
$db_user = 'your_username';
$db_pass = 'your_password';
$db_name = 'your_database_name';

// Function to handle the forgot password process
function forgot_password($email) {
  // 1. Validate Email
  $email = filter_var($email, FILTER_SANITIZE_EMAIL);  // Sanitize input
  if(empty($email)){
    return "Invalid email address.";
  }


  // 2.  Check if User Exists
  $conn = new mysqli($db_host, $db_user, $db_pass, $db_name);
  if ($conn->connect_error) {
    return "Connection failed: " . $conn->connect_error;
  }

  $sql = "SELECT id, email FROM users WHERE email = '$email'";
  $result = $conn->query($sql);

  if ($result->num_rows > 0) {
    $user_id = $result->fetch_assoc()['id'];
    $user_email = $result->fetch_assoc()['email'];

    // 3. Generate a Unique Token
    $token = bin2hex(random_bytes(32)); // Generate a cryptographically secure token

    // 4.  Store the Token in the Database (Temporary)
    $sql = "INSERT INTO password_resets (user_id, token, expires_at) VALUES ($user_id, '$token', NOW() + INTERVAL 24 HOUR)"; // Expires after 24 hours

    if ($conn->query($sql) === TRUE) {
      // 5.  Send the Reset Link (Email) -  Implement this part
      $to = $email;
      $subject = 'Password Reset Link';
      $message = "Click on the following link to reset your password: " . "<a href='" . $_SERVER['PHP_SELF'] . "?reset=$token'>" . $_SERVER['PHP_SELF'] . "?reset=$token</a>";  // Use PHP_SELF to ensure correct link
      $headers = "From: your_email@example.com"; // Replace with your email
      mail($to, $message, $headers);

      return "Password reset link sent to your email. Please check your inbox.";
    } else {
      return "Error inserting token into database: " . $conn->error;
    }
  } else {
    return "User not found with email: $email";
  }

  $conn->close();
}

// Example Usage (for testing - typically handled through a form)
if ($_SERVER["REQUEST_METHOD"] == "GET") {
  if (isset($_GET['reset'])) {
    $reset_token = $_GET['reset'];
    $result = forgot_password($reset_token);
    echo $result;
  } else {
     echo "Enter email to reset your password.";
  }
}
?>


// Delete the token after reset is confirmed
$sql = "DELETE FROM password_resets WHERE token = '$token' AND user_id = $user_id";
if ($conn->query($sql) === TRUE) {
    // Token deleted successfully
}


<?php

/**
 * Forgets a user's password.
 *
 * This function generates a unique, secure token, sends an email with a
 * reset link, and updates the user's password in the database.
 *
 * @param string $email The user's email address.
 * @param PDO $pdo  The PDO database connection object.
 * @return bool True if the password reset process started successfully,
 *             false otherwise (e.g., email not sent).
 */
function forgot_password(string $email, PDO $pdo) {
    // 1. Generate a secure, unique token.  Use a strong random string.
    $token = bin2hex(random_bytes(32));

    // 2. Prepare the reset token and user ID for the database.
    $sql = "SELECT id FROM users WHERE email = :email";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':email', $email, PDO::PARAM_STR);
    $stmt->execute();

    $user_id = null;
    if ($stmt->fetch(PDO::FETCH_ASSOC)) {
        $user_id = $stmt->fetch(PDO::FETCH_ASSOC)['id'];
    }

    if (!$user_id) {
        return false; // User not found
    }

    // 3.  Store the token in the database, linked to the user.
    $sql = "INSERT INTO password_resets (user_id, token, expires_at)
            VALUES (:user_id, :token, :expires_at)";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $stmt->bindParam(':token', $token, PDO::PARAM_STR);
    $expires_at = time() + (2 * 60 * 60); // Expires in 2 hours
    $stmt->bindParam(':expires_at', $expires_at, PDO::PARAM_INT);
    $stmt->execute();



    // 4.  Send the password reset email.
    $to = $email;
    $subject = 'Password Reset';
    $message = "Click this link to reset your password: " . '<a href="' . $_SERVER['PHP_SELF'] . '?reset=' . $token . '"' . ' >Reset Password</a>';
    $headers = 'From: your_email@example.com' . "\r
";

    if (mail($to, $subject, $message, $headers)) {
        return true;
    } else {
        // Log an error if the email fails to send
        error_log("Failed to send email for password reset: " . $email);
        return false;
    }
}


// Example Usage (Illustrative -  Don't include this directly in your main application code!)
// To use this, you'd typically have it called from a form submission handler.
// This example shows how you *would* call it, but this is just for demonstration.

//  This is just an example. Replace with your actual database connection.
/*
$pdo = new PDO("mysql:host=localhost;dbname=your_database_name", "your_username", "your_password");

if (isset($_POST['email'])) {
    $email = $_POST['email'];
    if (forgot_password($email, $pdo)) {
        echo "Password reset email has been sent to $email.";
    } else {
        echo "Failed to initiate password reset.";
    }
}
*/
?>


<?php

/**
 *  Forgot Password Function
 *
 *  This function sends an email with a password reset link to the user's registered email address.
 *
 *  @param string $email The email address of the user.
 *  @return bool True if the email was sent successfully, false otherwise.
 */
function forgot_password(string $email): bool
{
    // 1. Validate Email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        error_log("Invalid email address provided: " . $email); // Log invalid email
        return false;
    }

    // 2. Check if the user exists
    $user = get_user_by_email($email);  // Assumes you have this function
    if (!$user) {
        error_log("User with email " . $email . " not found.");
        return false;
    }

    // 3. Generate a unique token and expiration time
    $token = generate_unique_token();
    $expiration_time = time() + (2 * 60 * 60); // Token valid for 2 hours. Adjust as needed.
    
    // 4. Store token and expiration time in the database
    if (!store_token_in_database($user->id, $token, $expiration_time)) {
      error_log("Failed to store token in database for user " . $email);
      return false;
    }
    
    // 5. Build the reset link
    $reset_link = app_url() . "/reset_password?token=" . urlencode($token); // Use app_url() or your base URL

    // 6. Send the reset email
    if (!send_reset_password_email($email, $reset_link)) {
        error_log("Failed to send reset password email to " . $email);
        // You might want to handle this failure differently (e.g., display an error message)
        return false;
    }

    return true;
}


/**
 * Placeholder functions - Replace with your actual implementations.
 */
function get_user_by_email(string $email): ?User // Assuming User is your user model
{
    // Replace with your database query to retrieve the user by email
    // Example:
    // $result = mysqli_query($conn, "SELECT * FROM users WHERE email = '$email'");
    // if (mysqli_num_rows($result) > 0) {
    //    $user = new User();
    //    $user->load($result->fetch_assoc());
    //    return $user;
    // }
    // return null;

    // Dummy User class
    class User {
        public $id;
        public $email;
        public function load($data) {
            $this->id = $data['id'];
            $this->email = $data['email'];
        }
    }

    return null;
}

function generate_unique_token(): string
{
    return bin2hex(random_bytes(32)); // Generate a cryptographically secure random token
}

function store_token_in_database(int $user_id, string $token, int $expiration_time): bool
{
  // Replace with your database query to store the token and expiration time
  // Example:
  // $result = mysqli_query($conn, "INSERT INTO password_tokens (user_id, token, expiration_time) VALUES ($user_id, '$token', $expiration_time)");
  // return mysqli_affected_rows($conn) > 0;

  return true; //Dummy implementation
}

function urlencode($string) {
  // Use built-in urlencode function for portability
  return urlencode($string);
}

function send_reset_password_email(string $email, string $reset_link): bool
{
    // Replace with your email sending logic
    // Example:
    // $subject = "Password Reset";
    // $body = "Click the following link to reset your password: " . $reset_link;
    // $headers = "From: your_email@example.com\r
";
    // return mail($email, $subject, $body, $headers);
    return true; //Dummy implementation
}

function app_url() {
    // Return your application's base URL
    return "http://localhost/your_app/";
}
?>


<?php

// Assuming you have a database connection established
// $db = new PDO(...);  // Replace with your database connection

// **Important: Replace these with your actual values**
$table_name = 'users'; // Table name where user accounts are stored
$email_column = 'email';
$password_column = 'password';
$reset_token_column = 'reset_token';
$expiry_time = 60 * 60 * 24; // Expire reset token after 24 hours (seconds)
$secret_key = 'your_secret_key_here'; // A strong, random secret key

/**
 * Generates a unique reset token.
 *
 * @return string Unique reset token.
 */
function generateUniqueToken() {
  return bin2hex(random_bytes(32));
}


/**
 * Creates a reset token for a user.
 *
 * @param PDO $db  Database connection.
 * @param string $email  User's email.
 * @return bool True on success, false on failure.
 */
function createResetToken(PDO $db, string $email) {
  $token = generateUniqueToken();

  $stmt = $db->prepare("SELECT id FROM {$table_name} WHERE {$email_column} = :email");
  $stmt->bindParam(':email', $email, PDO::PARAM_STR);
  $stmt->execute();

  $user_id = $stmt->fetch(PDO::FETCH_ASSOC);

  if ($user_id) {
    $stmt = $db->prepare("UPDATE {$table_name} SET {$reset_token_column} = :token, expiry_time = :expiry_time WHERE id = :user_id");
    $stmt->bindParam(':user_id', $user_id['id'], PDO::PARAM_INT);
    $stmt->bindParam(':token', $token, PDO::PARAM_STR);
    $stmt->bindParam(':expiry_time', $expiry_time, PDO::PARAM_INT);
    return $stmt->execute();
  } else {
    return false; // User not found
  }
}


/**
 * Resets the password for a given email and token.
 *
 * @param PDO $db Database connection.
 * @param string $email User's email.
 * @param string $token Token.
 * @return bool True on success, false on failure.
 */
function resetPassword(PDO $db, string $email, string $token) {
  $stmt = $db->prepare("SELECT id FROM {$table_name} WHERE {$email_column} = :email AND {$reset_token_column} = :token");
  $stmt->bindParam(':email', $email, PDO::PARAM_STR);
  $stmt->bindParam(':token', $token, PDO::PARAM_STR);
  $stmt->execute();

  $user_id = $stmt->fetch(PDO::FETCH_ASSOC);

  if ($user_id) {
    // Generate a new password (for demonstration - use a proper password generation method in production)
    $new_password = 'password123';  // **IMPORTANT:  This is just an example!  Never use this in production.**

    $stmt = $db->prepare("UPDATE {$table_name} SET {$password_column} = :password, {$reset_token_column} = '', expiry_time = '' WHERE id = :user_id");
    $stmt->bindParam(':password', $new_password, PDO::PARAM_STR);
    $stmt->bindParam(':user_id', $user_id['id'], PDO::PARAM_INT);
    return $stmt->execute();
  } else {
    return false; // Token or user not found
  }
}


/**
 * Example Usage (For demonstration purposes only - handle this in a real application)
 */
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $email = $_POST["email"];
  $reset_token = $_POST["reset_token"];

  // Create a reset token if one doesn't exist. This is the trigger to start the process.
  if (!createResetToken($db, $email)) {
    echo "<p>Failed to generate reset token.</p>";
  } else {
    //Reset Password
    if (resetPassword($db, $email, $reset_token)) {
      echo "<p>Password reset successful!  Please check your email.</p>";
    } else {
      echo "<p>Invalid reset token or user not found.</p>";
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

  <h1>Forgot Password</h1>

  <form method="post" action="">
    <label for="email">Email:</label>
    <input type="email" id="email" name="email" required>
    <button type="submit" name="reset_button">Reset Password</button>
  </form>

</body>
</html>


<?php

// Assuming you have a database connection established (e.g., $db)
// and a 'users' table with 'email' and 'password' columns.

function forgotPassword($email) {
  // 1. Check if the email exists in the database
  $stmt = $db->prepare("SELECT id FROM users WHERE email = ?");
  $stmt->execute([$email]);
  $user = $stmt->fetch(PDO::FETCH_ASSOC);

  if (!$user) {
    return false; // Email not found
  }

  // 2. Generate a unique, time-based token
  $token = bin2hex(random_bytes(32));  // Secure random bytes

  // 3. Store the token and user ID in the database
  $stmt = $db->prepare("INSERT INTO password_resets (user_id, token, expires_at) 
                         VALUES (?, ?, NOW())");
  $stmt->execute([$user['id'], $token]);

  // 4. Send the password reset email (implementation details depend on your email setup)
  $resetLink = "https://yourwebsite.com/reset-password?token=" . $token; // Replace with your actual website URL
  sendResetPasswordEmail($email, $resetLink);  //  See helper function below for details

  return true; // Password reset request submitted successfully
}


//Helper function to send the password reset email.  Replace with your email sending logic.
function sendResetPasswordEmail($email, $resetLink) {
    //  Implement your email sending logic here.  This is just a placeholder.

    // Example using a simple email copy/paste:
    $to = $email;
    $subject = "Password Reset Request";
    $message = "Please click the following link to reset your password:
" . $resetLink;
    $headers = "From: yourname@example.com\r
";

    mail($to, $message, $headers);
    // Alternatively, use a more robust email library (e.g., PHPMailer) for better control and handling.
}



// Example Usage (Illustrative):
// $email = $_POST['email']; // Get email from form submission
// if (isset($email)) {
//   if (forgotPassword($email)) {
//     echo "Password reset email sent to " . $email;
//   } else {
//     echo "Invalid email or email already exists.";
//   }
// }

?>


<?php

/**
 * Forgets a user's password.
 *
 * This function generates a unique, time-based token to be emailed to the user.
 * After the user confirms the password reset link, the token is invalidated.
 *
 * @param string $email The user's email address.
 * @return bool True if an email was sent successfully, false otherwise.
 */
function forgotPassword(string $email) {
  // 1. Validate Email
  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    error_log("Invalid email address provided: " . $email); // Log for debugging
    return false;
  }

  // 2. Generate a Unique Token
  $token = bin2hex(random_bytes(32)); // Generate a cryptographically secure random token

  // 3. Store Token and User ID in Database (Example - Adapt to your database structure)
  $db = new PDO('mysql:host=localhost;dbname=your_database_name', 'your_username', 'your_password'); // Replace with your database credentials

  try {
    $stmt = $db->prepare("INSERT INTO password_resets (user_id, token, expires_at) VALUES (:user_id, :token, :expires_at)");
    $stmt->bindParam(':user_id', (int)$email);  // Use (int) to ensure integer type
    $stmt->bindParam(':token', $token);
    $stmt->bindParam(':expires_at', time() + (60 * 60 * 24)); // Expire in 24 hours
    $stmt->execute();

    // 4. Send Reset Email
    $resetLink = 'http://' . $_SERVER['HTTP_HOST'] . '/reset_password?token=' . $token; // Build the reset link
    $subject = 'Password Reset';
    $message = "Click the link below to reset your password:
" . $resetLink;
    $headers = "From: Your Website <admin@yourwebsite.com>\r
";

    if (mail($email, $subject, $message, $headers)) {
      return true;
    } else {
      // Log the email sending failure
      error_log("Failed to send email to " . $email);
      return false;
    }
  } catch (PDOException $e) {
    // Log the database error
    error_log("Database error: " . $e->getMessage());
    return false;
  }
}

// Example Usage:
// $email = 'user@example.com';
// if (forgotPassword($email)) {
//   echo "Password reset email sent to " . $email;
// } else {
//   echo "Failed to send password reset email.";
// }

?>


<?php

// Assuming you have a database connection established
// (e.g., $db = mysqli_connect('host', 'username', 'password', 'database'));

/**
 * Resets a user's password.
 *
 * @param string $email The email address of the user.
 * @return string  A message indicating success or failure.
 */
function forgotPassword(string $email): string
{
    // 1. Validate Input (Important!)
    if (empty($email)) {
        return "Error: Email address is required.";
    }

    // 2. Check if the user exists in the database
    $stmt = $db->prepare("SELECT id, email FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        $userId = $user['id'];
        $stmt->close(); // Close the query

        // 3. Generate a unique reset token
        $resetToken = bin2hex(random_bytes(32)); // Generate a cryptographically secure token
        $stmt = $db->prepare("INSERT INTO password_resets (user_id, token, expires_at) VALUES (?, ?, ?)");
        $stmt->bind_param("iii", $userId, $resetToken, time() + 3600); // Expires in 1 hour.  Adjust as needed.
        $stmt->execute();
        $stmt->close();

        // 4. Send the reset password link (Implementation depends on your email setup)
        $resetLink = "https://yourwebsite.com/reset_password?token=$resetToken"; // Replace with your actual domain
        // You'll likely need to implement code here to send an email
        // with this link. Example:
        // sendEmail( $email, "Reset Password", "Click this link to reset your password: $resetLink");


        return "Password reset link sent to your email address. Please check your inbox.";

    } else {
        return "User not found.";
    }
}


// Example Usage (Illustrative - Replace with your actual logic)
// $email = "testuser@example.com";
// $result = forgotPassword($email);
// echo $result;


// Placeholder for sendEmail function (You'll need to implement this)
/**
 * Sends an email.
 * @param string $to
 * @param string $subject
 * @param string $message
 */
function sendEmail(string $to, string $subject, string $message) {
  // TODO: Implement your email sending logic here (using PHPMailer, etc.)
  // Example:
  // $mail = new PHPMailer(true);
  // $mail->SetFrom("your_email@example.com", "Your Website");
  // $mail->AddAddress($to, "User Name");  // Get user name from database
  // $mail->Subject = $subject;
  // $mail->MsgBody = $message, 'html'); // or 'text'
  // $mail->send();
}

?>


<?php

// This is a simplified example and should be used with caution in a production environment.
// Security is paramount!  This example lacks robust security measures and should be adapted 
// to your specific needs and a production-ready setup.

// Database connection (replace with your actual connection details)
$dbHost = "localhost";
$dbName = "your_database_name";
$dbUser = "your_database_user";
$dbPass = "your_database_password";

// 1. Reset Password Request Form Handling
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST["email"]);

    // Basic email validation (improve this!)
    if (empty($email)) {
        $error = "Please enter your email address.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Invalid email address.";
    } else {
        //  IMPORTANT:  This is a simplified example.  In a real application,
        //  you would:
        //  1.  Check if the user exists in the database.
        //  2.  Generate a unique, time-based token.
        //  3.  Store the token in the database, linked to the user's email.
        //  4.  Send an email containing a link with the token.
        //  5.  Implement password reset logic on the link click.
        //  This example skips those steps for brevity.

        // Simulate a reset link generation (DO NOT USE IN PRODUCTION!)
        $resetToken = bin2hex(random_bytes(32));  // Generate a secure random token

        //  Store the token in the database
        //  (Replace with your actual database insert query)
        //  Example:
        //  $sql = "INSERT INTO password_resets (email, token, expiry) VALUES ('$email', '$resetToken', NOW() + INTERVAL 1 HOUR)";
        //  mysqli_query($conn, $sql);


        // Display a message indicating the reset link has been generated.
        echo "<p>An email has been sent to $email with instructions to reset your password.</p>";
        echo "<p>You can view the email in your inbox or spam folder.</p>";

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

<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
    <label for="email">Email Address:</label>
    <input type="email" id="email" name="email" placeholder="your.email@example.com" value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>">
    <?php if (isset($error)) { ?>
        <p class="error"><?php echo $error; ?></p>
    <?php } ?>
    <button type="submit">Request Password Reset</button>
</form>

</body>
</html>


<?php

/**
 * Resets a user's password.
 *
 * @param string $email The user's email address.
 * @param string $newPassword The new password.
 * @param string $secretKey (Optional) A secret key for verification.  Highly recommended.
 * @return bool True on success, false on failure.
 */
function forgotPassword(string $email, string $newPassword, string $secretKey = "") {
    // 1. Validate Input
    if (empty($email) || empty($newPassword)) {
        return false; // Required fields must be provided
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return false; // Invalid email format
    }

    if (strlen($newPassword) < 8) { //  Example minimum password length
        return false; //  Minimum length requirement
    }

    // 2.  Check if a user exists
    $user = getUserById($email); //  Assuming you have a function to fetch user by email

    if (!$user) {
        return false; // User not found
    }

    // 3. Verify Secret Key (Important for security)
    if ($secretKey && !verifySecretKey($user->id, $secretKey)) {
        return false; // Invalid secret key
    }

    // 4.  Reset Password
    $hashedNewPassword = password_hash($newPassword, PASSWORD_DEFAULT); // Hash the new password

    // 5.  Update User's Password
    $result = updateUserPassword($user->id, $hashedNewPassword);

    if (!$result) {
        return false; // Failed to update password
    }

    // 6. Generate and Send Password Reset Email (Optional, but highly recommended)
    // Send an email containing a link to reset the password
    sendPasswordResetEmail($email, $hashedNewPassword); // Assuming you have this function

    return true; // Password reset successful
}


/**
 * Dummy Functions - Replace with your actual implementations
 */
function getUserById(string $email) {
    // Replace this with your database query to fetch the user by email.
    // This is a placeholder.
    $users = [
        ['id' => 1, 'email' => 'test@example.com'],
        ['id' => 2, 'email' => 'another@example.com'],
    ];

    foreach ($users as $user) {
        if ($user['email'] === $email) {
            return $user;
        }
    }
    return null;
}


function verifySecretKey(int $userId, string $secretKey) {
    // In a real implementation, compare the provided secret key
    // to a hash stored for the user.
    // This is just a placeholder.
    // You would typically store the hashed secret key in the database
    // alongside the user's ID.
    // For this example, we'll just return true if the secret key is "secret123".
    return $secretKey === "secret123";
}

function updateUserPassword(int $userId, string $hashedPassword) {
    // Replace this with your database update query to set the password.
    // This is a placeholder.
    // Example:  UPDATE users SET password = '$hashedPassword' WHERE id = $userId;
    echo "Updating password for user ID: " . $userId . " with hashed password: " . $hashedPassword . PHP_EOL;
    return true;
}

function sendPasswordResetEmail(string $email, string $hashedPassword) {
    //  Replace this with your email sending implementation
    //  Example:  Use PHPMailer, SwiftMailer, or another email library
    echo "Sending password reset email to: " . $email . PHP_EOL;
    echo "Hashed password: " . $hashedPassword . PHP_EOL;
}



// --- Example Usage ---
$email = 'test@example.com';
$newPassword = 'MyNewPassword123';
$secretKey = "secret123"; // Include this for verification

if (forgotPassword($email, $newPassword, $secretKey)) {
    echo "Password reset successful for $email" . PHP_EOL;
} else {
    echo "Password reset failed for $email" . PHP_EOL;
}


?>


<?php

/**
 * Forgets a user's password.
 *
 * This function sends an email to the user's registered email address
 * containing a temporary password.  It also logs the reset attempt.
 *
 * @param string $email The email address of the user requesting a password reset.
 * @param string $baseUrl The base URL for the password reset link.  
 *                         Should include the domain and path (e.g., 'https://example.com/reset_password').
 * @param string $salt The salt used for hashing the password.  Important for security.
 *
 * @return bool True on success, false on failure (e.g., email not sent).
 */
function forgotPassword(string $email, string $baseUrl, string $salt)
{
    // Validate email format (basic check - improve this for production)
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        error_log("Invalid email format provided: " . $email); // Log the error
        return false;
    }

    // Generate a temporary password
    $temporaryPassword = generateTemporaryPassword(6); // Adjust length as needed

    // Hash the temporary password
    $hashedTemporaryPassword = password_hash($temporaryPassword, PASSWORD_DEFAULT, ['salt' => $salt]);

    // Store the temporary password and user ID in the database
    $userId = getUserIDByEmail($email); //  Assume you have a function to get the user ID
    if (!$userId) {
        error_log("User with email " . $email . " not found.");
        return false;
    }

    // Store the temporary password and timestamp in the database.  Consider using a dedicated
    // table for reset tokens to avoid collisions.
    $resetToken = password_hash($temporaryPassword . '_' . $userId, PASSWORD_DEFAULT, ['salt' => $salt]); // Add userId to token
    
    // Store data in database (replace with your actual database interaction)
    // Example:
    // $sql = "INSERT INTO password_resets (user_id, token, created_at) VALUES ($userId, '$resetToken', NOW())";
    // mysqli_query($connection, $sql);  // Replace $connection with your database connection

    // Send the password reset email
    if (!sendResetPasswordEmail($email, $temporaryPassword, $baseUrl)) {
        error_log("Failed to send password reset email to " . $email);
        // Consider handling this differently depending on your requirements.
        // You might try sending the email again later, or return an error.
        return false;
    }

    return true;
}

/**
 * Generates a random temporary password.
 * 
 * @param int $length The desired length of the password.
 * @return string The generated temporary password.
 */
function generateTemporaryPassword(int $length = 6) {
    $characters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*()_+-={}[]|\'":<>?/';
    $password = '';
    $passwordLength = strlen($characters);

    for ($i = 0; $i < $length; $i++) {
        $char = $characters[rand(0, $charLength)];
        $password .= $char;
    }
    return $password;
}


/**
 * Placeholder function for sending the password reset email.
 * 
 * @param string $email The email address of the user.
 * @param string $temporaryPassword The temporary password.
 * @param string $baseUrl The base URL for the password reset link.
 * @return bool True on success, false on failure.
 */
function sendResetPasswordEmail(string $email, string $temporaryPassword, string $baseUrl) {
    // Implement your email sending logic here using a library like PHPMailer.
    // This is a placeholder.

    $subject = "Password Reset Link";
    $message = "Please click the following link to reset your password: " . $baseUrl . "/reset_password?token=" . urlencode($temporaryPassword); 

    //  In a real implementation, you would use a mail library
    //  to send the email.  For example:
    //  $mail = new PHPMailer\PHPMailer\PHPMailer();
    //  $mail->SetSMTPDebugEnable(true);
    //  $mail->addAddress($email, 'User');
    //  $mail->Subject = $subject;
    //  $mail->Body = $message;
    //  $mail->send();

    return true; // Assume success for this example
}


/**
 * Placeholder function for getting the user ID by email.
 * 
 * @param string $email The email address of the user.
 * @return int|null The user ID if found, null otherwise.
 */
function getUserIDByEmail(string $email) {
  // Implement your logic to retrieve the user ID from the database
  // based on the email address. This is a placeholder.
  // Example:
  // $result = mysqli_query($connection, "SELECT id FROM users WHERE email = '$email'");
  // if ($result) {
  //   $row = mysqli_fetch_assoc($result);
  //   return $row['id'];
  // } else {
  //   return null;
  // }
    return null; // Placeholder
}

?>


<?php

// Assuming you have a database connection established (e.g., $db)

// 1. Input Validation: Check if the email is valid.
if (empty($_POST['email'])) {
    $error = "Please enter your email address.";
} else {
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL); // Sanitize the email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Invalid email address.";
    }
}

// 2.  Password Reset Token Generation (Important for Security)
function generateResetToken() {
    return bin2hex(random_bytes(32)); // Generate a cryptographically secure random token
}

// 3.  Database Query (Replace with your actual database structure)
// In a real application, you'd likely have a user table with an 'email' and 'password' column
// For this example, we'll assume a simplified table structure.

// Example Table Structure:
// Users Table:
//   - id (INT, PRIMARY KEY)
//   - email (VARCHAR)
//   - password (VARCHAR)
//   - reset_token (VARCHAR, UNIQUE)

function resetPassword($email, $resetToken) {
    // Example Database Query (Replace with your actual query)
    $sql = "UPDATE users SET reset_token = NULL WHERE email = '$email' AND reset_token = '$resetToken'";
    $result = mysqli_query($GLOBALS['db'], $sql); //Use your database connection here.
    return $result;
}


// 4.  Handle the Reset Request (POST Request)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if the token is provided
    if (isset($_POST['reset_token'])) {
        $resetToken = filter_var($_POST['reset_token'], FILTER_SANITIZE_STRING);

        // Reset the password (This would typically be a link to a page with a form)
        $resetResult = resetPassword($email, $resetToken);

        if ($resetResult) {
            // Password reset successful - Redirect to a page for the user to set a new password.
            echo "<p>Password reset link sent to your email.  Please set a new password.</p>";
            //  Implement code to redirect the user to a page with a form to enter the new password.
        } else {
            // Handle the error
            echo "<p>Error resetting password.  Please try again.</p>";
            // Log the error for debugging.
        }
    } else {
        echo "<p>Invalid or missing reset token.</p>";
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

    <?php if (isset($error)): ?>
        <p style="color: red;"><?php echo $error; ?></p>
    <?php endif; ?>

    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        <label for="email">Email Address:</label>
        <input type="email" id="email" name="email" placeholder="Your email" value="<?php echo isset($email) ? htmlspecialchars($email) : ''; ?>">
        <button type="submit">Reset Password</button>
    </form>

</body>
</html>


<?php

// Assuming you have a database connection established and named $db

/**
 * Resets a user's password.
 *
 * @param string $email The email address of the user requesting password reset.
 * @return string A message indicating success or failure.
 */
function forgot_password(string $email) {
    // 1. Validate Input (Important for Security)
    if (empty($email)) {
        return "Error: Email address is required.";
    }

    // Sanitize the email address to prevent potential vulnerabilities
    $email = filter_var($email, FILTER_SANITIZE_EMAIL);
    if (filter_var($email, FILTER_VALIDATE_EMAIL) === false) {
        return "Error: Invalid email address format.";
    }

    // 2. Check if User Exists
    $user = getUserByEmail($email); // Assuming you have a function to retrieve user by email
    if ($user === null) {
        return "Error: User not found.";
    }

    // 3. Generate a Unique Token and Store it
    $token = generate_unique_token();  // Assuming you have a function to generate a unique token
    $expiry_time = time() + (3600 * 24); // Token expires in 24 hours (adjust as needed)

    // Store the token and expiry time in the database, linked to the user.
    // This is a crucial step.  The example below is illustrative;
    // your actual implementation will vary based on your database schema.
    store_token($user->id, $token, $expiry_time);

    // 4. Send the Password Reset Email
    $subject = "Password Reset Request";
    $headers = "From: your_email@example.com" . "\r
"; // Replace with your email
    $message = "Click on this link to reset your password: " . base_url . "reset_password?token=" . urlencode($token);
    $result = send_email($email, $subject, $headers, $message);

    if ($result) {
        return "Password reset link has been sent to your email.";
    } else {
        return "Error: Failed to send email.";
    }
}


/**
 * Placeholder functions (You'll need to implement these based on your application)
 */
// Example: Get user by email (Replace with your actual database query)
function getUserByEmail(string $email): ?User {
    // Example database query - Replace with your actual query
    // Assuming you have a User class/model
    // This is just a placeholder - replace with your logic
    //  $db = get_database_connection();  // Get database connection
    //  $query = "SELECT * FROM users WHERE email = ?";
    //  $stmt = $db->prepare($query);
    //  $stmt->execute([$email]);
    //  $user = $stmt->fetch(PDO::FETCH_ASSOC);
    //  if ($user) {
    //      return new User($user); // Create a User object
    //  }
    //  return null;

    // Placeholder for demo - returns a dummy user object
    return new User(['id' => 1, 'email' => 'test@example.com']);
}

// Example: Generate a unique token (You'll need to implement this)
function generate_unique_token(): string {
    return bin2hex(random_bytes(32)); // A secure random string
}

// Example: Store the token and expiry time in the database (Implement this)
function store_token(int $userId, string $token, int $expiry_time): void {
  // Implement the logic to store the token and expiry time in your database
  //  e.g., using a database query to update the user's record.
  // Example:
  // $db = get_database_connection();
  // $query = "UPDATE users SET token = ?, expiry_time = ? WHERE id = ?";
  // $stmt = $db->prepare($query);
  // $stmt->execute([$token, $expiry_time, $userId]);
}

// Example: Send an email (You'll need to implement this, likely using a library)
function send_email(string $to, string $subject, string $headers, string $message): bool {
    //  Implement the email sending logic using a library like PHPMailer
    //  This is a placeholder -  replace with your actual email sending code.
    //  For demonstration, simply return true.
    //  e.g.,
    //  $mailer = new PHPMailer(true);
    //  $mailer->addAddress($to, $to);
    //  $mailer->setFrom('your_email@example.com', 'Your Name');
    //  $mailer->Subject = $subject;
    //  $mailer->Body = $message;
    //  return $mailer->send();

    // Placeholder
    return true;
}



// Example User Class (Customize as needed)
class User {
    public int $id;
    public string $email;

    public function __construct(array $data) {
        $this->id = $data['id'];
        $this->email = $data['email'];
    }
}



// Example Usage:
$email = "test@example.com"; // Replace with a user's email
$result = forgot_password($email);
echo $result . "
";

?>


<?php

// Assuming you have a database connection established (e.g., $db)

/**
 * Forgets a user's password and sends a password reset link.
 *
 * @param string $email The user's email address.
 * @return bool True if an email was sent, false otherwise.
 */
function forgotPassword(string $email): bool
{
    // 1. Validate Email (Important Security Step!)
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        error_log("Invalid email format provided: " . $email); // Log for debugging
        return false;
    }

    // 2. Check if the user exists
    $user = getUserByEmail($email);

    if (!$user) {
        error_log("User with email: " . $email . " not found."); // Log for debugging
        return false;
    }

    // 3. Generate a Unique Token and Timestamp
    $token = generateUniqueToken();
    $timestamp = time();

    // 4. Create the Reset Token Record (Store this in your database)
    //   *  Email
    //   *  Token
    //   *  Expiration Time
    resetTokenRecord = [
        'email' => $email,
        'token' => $token,
        'expiry' => $timestamp + (60 * 60 * 24) // Expires in 24 hours
    ];

    // Save the record to the database.  Replace this with your actual database query
    if (!saveResetToken($resetTokenRecord)) {
        error_log("Failed to save reset token record for " . $email);
        return false;
    }


    // 5.  Send the Password Reset Email (Implement your email sending logic here)
    $subject = "Password Reset Request";
    $message = "Click the following link to reset your password: " . base_url() . "/reset-password?token=" . $token; // Replace base_url()

    $headers = "From: " . get_sender_email(); //Replace with your sender email address
    if (!sendEmail($subject, $message, $headers)) {
        error_log("Failed to send password reset email for " . $email);
        //Optionally, you could delete the token from the database if email sending fails
        deleteResetToken($email, $token);
        return false;
    }

    return true;
}

/**
 *  Dummy function to simulate getting user data from database.  Replace with your actual query.
 *  @param string $email
 *  @return array|null User data, or null if not found.
 */
function getUserByEmail(string $email): ?array {
    // Example using a dummy user. Replace with your database query
    $users = [
        ['id' => 1, 'email' => 'test@example.com', 'password' => 'hashed_password'],
    ];

    foreach ($users as $user) {
        if ($user['email'] === $email) {
            return $user;
        }
    }
    return null;
}


/**
 * Dummy function to generate a unique token.
 * @return string
 */
function generateUniqueToken(): string {
    return bin2hex(random_bytes(32)); //Generate a 32-byte (256-bit) random string.
}

/**
 * Dummy function to save the reset token record to the database. Replace with your actual database query.
 * @param array $resetTokenRecord
 * @return bool
 */
function saveResetToken(array $resetTokenRecord): bool {
    //Replace with your actual database insert query.  For demonstration, we just return true.
    //Example:
    // $sql = "INSERT INTO reset_tokens (email, token, expiry) VALUES (?, ?, ?)";
    // $stmt = $db->prepare($sql);
    // $stmt->execute([$resetTokenRecord['email'], $resetTokenRecord['token'], $resetTokenRecord['expiry']]);
    return true;
}


/**
 * Dummy function to delete the reset token record. Replace with your actual database query.
 * @param string $email
 * @param string $token
 * @return bool
 */
function deleteResetToken(string $email, string $token): bool {
    //Replace with your actual database delete query.
    //Example:
    //$sql = "DELETE FROM reset_tokens WHERE email = ? AND token = ?";
    //$stmt = $db->prepare($sql);
    //$stmt->execute([$email, $token]);
    return true;
}

/**
 * Dummy function for sending emails.  Replace with your actual email sending logic.
 * @param string $subject
 * @param string $message
 * @param string $headers
 * @return bool
 */
function sendEmail(string $subject, string $message, string $headers): bool {
    // Simulate email sending.  Replace with your actual email sending logic.
    // You'll likely use a library like PHPMailer or SwiftMailer for actual sending.
    error_log("Simulating sending email: Subject: " . $subject . ", Message: " . $message);
    return true;
}

/**
 * Dummy function to get the base URL.  Replace with your actual base URL.
 * @return string
 */
function base_url(): string {
    // Replace with your application's base URL
    return "http://localhost/my_app/";
}

/**
 * Dummy function to get the sender email.  Replace with your actual sender email.
 * @return string
 */
function get_sender_email(): string {
    // Replace with your actual sender email address
    return "noreply@example.com";
}

// Example Usage:
$email = "test@example.com"; // Replace with the user's email
if (forgotPassword($email)) {
    echo "Password reset email has been sent to " . $email;
} else {
    echo "Failed to send password reset email for " . $email;
}

?>


<?php

// This is a simplified example for demonstration purposes only.
// In a production environment, you MUST implement robust security measures.

// Database connection (replace with your actual credentials)
$host = 'localhost';
$username = 'your_username';
$password = 'your_password';
$database = 'your_database';

// Function to handle password reset
function forgot_password($email) {
  // 1. Validate Email
  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    return 'Invalid email address.';
  }

  // 2. Check if the email exists in the user table
  $conn = new mysqli($host, $username, $password, $database);

  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  $sql = "SELECT id, password_reset_token FROM users WHERE email = '$email'";
  $result = $conn->query($sql);

  if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
    $id = $user['id'];
    $token = $user['password_reset_token'];

    // 3. Generate a new, unique token (important for security)
    $new_token = bin2hex(random_bytes(32)); 

    // 4. Update the password_reset_token with the new token
    $update_sql = "UPDATE users SET password_reset_token = '$new_token' WHERE id = '$id'";
    if ($conn->query($update_sql) === TRUE) {
      // 5. Send an email with the reset link
      $to = $email;
      $subject = 'Password Reset Link';
      $message = "Please click this link to reset your password: " . $_SERVER['PHP_SELF'] . "?reset=" . $new_token;  // Use PHP_SELF to ensure the correct script is called.
      $headers = "From: your_email@example.com" . "\r
";
      mail($to, $message, $headers);

      return 'Password reset email sent to ' . $email . '. Please check your inbox.';
    } else {
      return 'Error updating password reset token.';
    }
  } else {
    return 'User not found.';
  }

  $conn->close();
}

// Example Usage (Handle form submission - NOT FOR PRODUCTION)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $email = $_POST["email"];
  $reset_message = forgot_password($email);
  echo $reset_message;
}

?>

<!DOCTYPE html>
<html>
<head>
  <title>Forgot Password</title>
</head>
<body>

  <h1>Forgot Password</h1>

  <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
    <label for="email">Email:</label>
    <input type="email" id="email" name="email" required>
    <button type="submit">Reset Password</button>
  </form>

</body>
</html>


<?php

// Assume you have a database connection established (e.g., $db)
// This is a simplified example - replace with your actual database connection
// For demonstration purposes, we'll use a hardcoded user and password.

// Example user data (replace with your database)
$users = [
    'john.doe@example.com' => 'password123',
    'jane.smith@example.com' => 'securePass456'
];

/**
 * Forgets a user's password.
 *
 * @param string $email The user's email address.
 * @return bool True if password reset email sent successfully, false otherwise.
 */
function forgotPassword(string $email)
{
    // Validate email format (basic)
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return false;
    }

    // Check if the user exists
    if (isset($users[$email])) {
        // Generate a unique token for password reset
        $token = bin2hex(random_bytes(32)); // Secure random token

        // Store the token and expiry time in the database for the user
        // (Replace this with your database logic)
        // Example:
        // $db->query("INSERT INTO password_resets (email, token, expiry) VALUES ('$email', '$token', NOW() + INTERVAL 24 HOUR)");

        // For demonstration, we'll just log the token (replace with actual email sending)
        error_log("Password reset token generated for: " . $email . " - Token: " . $token);

        //  Here you would send an email containing a link with the token.
        //  Example (this is just a placeholder):
        //  $subject = "Password Reset Link";
        //  $headers = "From: noreply@example.com\r
";
        //  $body = "Click this link to reset your password: http://example.com/reset?token=$token";
        //  mail($email, $subject, $body, $headers);

        return true;
    } else {
        return false;
    }
}

// Example usage:
$emailToReset = 'john.doe@example.com';

if (forgotPassword($emailToReset)) {
    echo "Password reset email sent to " . $emailToReset . " successfully." . PHP_EOL;
} else {
    echo "User not found or password reset email could not be sent." . PHP_EOL;
}

$emailToReset = 'nonexistent@example.com'; // Test with an invalid email

if (forgotPassword($emailToReset)) {
    echo "Password reset email sent to " . $emailToReset . " successfully." . PHP_EOL;
} else {
    echo "User not found or password reset email could not be sent." . PHP_EOL;
}

?>


<?php

/**
 * Forgot Password Function
 *
 * This function allows users to reset their password.
 * It generates a unique, secure token and sends an email with a link to 
 * a password reset page.
 *
 * @param string $email The email address of the user.
 * @param string $token  A unique token to associate with the password reset request.
 * @param string $baseUrl The base URL of the password reset page.
 * @return bool True if the email was sent successfully, false otherwise.
 */
function forgotPassword(string $email, string $token, string $baseUrl)
{
    // Validate email and token (important for security)
    if (empty($email) || empty($token)) {
        error_log("Forgot Password: Missing email or token."); // Log for debugging
        return false;
    }

    // Build the reset link
    $resetLink = $baseUrl . "?token=" . urlencode($token);

    // Email setup
    $to = $email;
    $subject = 'Password Reset';
    $message = "Please click the following link to reset your password: " . $resetLink;
    $headers = "From: Your Website <admin@yourwebsite.com>"; // Replace with your email address

    // Send the email (using PHP's built-in mail function -  Consider alternatives for production)
    if (mail($to, $subject, $message, $headers)) {
        error_log("Forgot Password: Email sent to " . $email);
        return true;
    } else {
        error_log("Forgot Password: Failed to send email to " . $email);
        return false;
    }
}

// Example Usage (For testing purposes - Remove or adjust for your application)
// $email = "testuser@example.com";
// $token = "abcdef1234567890";
// $baseUrl = "http://localhost/reset_password/";  // Replace with your password reset URL

// if (forgotPassword($email, $token, $baseUrl)) {
//     echo "Password reset email sent to " . $email;
// } else {
//     echo "Failed to send password reset email.";
// }

?>


<?php

// Assuming you have a database connection established (e.g., $pdo)

/**
 * Resets the user's password.
 *
 * @param string $email The user's email address.
 * @param string $newPassword The new password.
 * @return bool True if the password reset was successful, false otherwise.
 */
function forgotPassword(string $email, string $newPassword) {
  // 1. Validate Inputs (Important for security!)
  if (empty($email) || empty($newPassword)) {
    return false; // Or throw an exception: throw new InvalidArgumentException("Email and password are required.");
  }

  // 2. Check if the email exists in the database.
  $stmt = $pdo->prepare("SELECT id FROM users WHERE email = :email");
  $stmt->bindParam(':email', $email, PDO::PARAM_STR);
  $stmt->execute();

  $user = $stmt->fetch(PDO::FETCH_ASSOC);

  if ($user === false) {
    return false; // Email not found
  }

  // 3.  Hash the new password
  $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);

  // 4. Update the user's password in the database
  $stmt = $pdo->prepare("UPDATE users SET password = :password WHERE id = :id");
  $stmt->bindParam(':password', $hashedPassword, PDO::PARAM_STR);
  $stmt->bindParam(':id', $user['id'], PDO::PARAM_INT);
  $stmt->execute();


  return true; // Password reset successful
}

// Example Usage:
// In your form, after the user submits the form...
/*
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $email = $_POST["email"];
  $newPassword = $_POST["newPassword"];

  if (forgotPassword($email, $newPassword)) {
    echo "Password reset email sent.  Check your inbox!";
  } else {
    echo "Error: Password reset failed. Please try again.";
  }
}
*/


// Example:  Illustrative only -  DO NOT put the actual password generation code in your app!
// In a real application, you would send an email with a link to a password reset form.
// This example shows how to generate a temporary password for testing.
function generateTemporaryPassword() {
  return bin2hex(random_bytes(16)); // Creates a strong random string
}
?>


<?php

// Assuming you have a database connection established and named $db

// Function to reset a user's password
function forgot_password($email, $new_password, $db) {
    // 1. Check if the email exists in the database
    $query = "SELECT id FROM users WHERE email = ?";
    $stmt = $db->prepare($query);
    $stmt->execute([$email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user) {
        // Email doesn't exist
        return false;
    }

    // 2. Hash the new password
    $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

    // 3. Update the user's password in the database
    $query = "UPDATE users SET password = ? WHERE id = ?";
    $stmt = $db->prepare($query);
    $stmt->execute([$hashed_password, $user['id']]);

    if ($stmt->rowCount() === 0) {
        // Update failed
        return false;
    }

    // 4. (Optional) Send an email notification (recommended)
    // This would involve sending an email to the user with a link to reset their password.
    // See the commented-out example below.

    return true;
}


// Example Usage (Illustrative - Replace with your actual email and password)
//
// $email = "testuser@example.com";
// $new_password = "new_secure_password";
//
// if (forgot_password($email, $new_password, $db)) {
//     echo "Password reset successful!  Check your email for instructions.";
// } else {
//     echo "Password reset failed.  Please try again.";
// }

// Example of sending an email (Requires configuration - SMTP/Email Setup)
/*
// Send email notification
function send_password_reset_email($email, $reset_link) {
  $to = $email;
  $subject = 'Password Reset';
  $message = "Please click on the following link to reset your password: " . $reset_link;
  $headers = "From: your_email@example.com" . "\r
" .
            "Reply-To: your_email@example.com";

  mail($to, $message, $headers);
}

// Example of generating the reset link (In a real application, you would use a token-based approach for security)
// $reset_link = "https://yourwebsite.com/reset_password?token=" . md5($email . time());
// send_password_reset_email($email, $reset_link);
*/

?>


<?php

// Assuming you have a database connection established (e.g., $db)

/**
 * Resets a user's password.
 *
 * @param string $email The user's email address.
 * @return bool True if a reset token was generated and sent, false otherwise.
 */
function forgot_password(string $email): bool
{
    // 1. Check if the email exists in the database
    $user = getUserByEmail($email);

    if (!$user) {
        // User not found
        return false;
    }

    // 2. Generate a unique reset token
    $token = generate_unique_token(); // Implement this function (see below)

    // 3. Store the token in the database, associated with the user's email.
    //    This is crucial for security.  Don't just store a plain token.
    //    Ideally, you'd hash the token and store the hash.  Storing the raw
    //    token directly is vulnerable to attacks.
    store_reset_token($user->id, $token);  //Implement this function (see below)

    // 4. Send the reset email.
    $subject = 'Password Reset';
    $body = "Please click the following link to reset your password: " .  base_url() . "/reset_password?token=" . $token; // Replace with your base URL
    $headers = "From: " . get_admin_email() . "\r
"; // Replace with your admin email
    $result = send_email($email, $subject, $body, $headers);


    if ($result) {
        return true;
    } else {
        // Email sending failed.  Consider logging this error for debugging.
        return false;
    }
}


/**
 *  Dummy functions - Implement these based on your setup.
 */

/**
 * Gets a user by email.  Implement this to connect to your database.
 * @param string $email
 * @return User | null
 */
function getUserByEmail(string $email): ?User {
    // *** IMPLEMENT THIS FUNCTION ***
    // This is a placeholder. Replace with your database query.
    // Example (using a hypothetical User class):
    // $result = mysqli_query($db, "SELECT * FROM users WHERE email = '$email'");
    // if (mysqli_num_rows($result) > 0) {
    //   $row = mysqli_fetch_assoc($result);
    //   return new User($row);
    // }
    // return null;
}



/**
 * Generates a unique token.  Use a cryptographically secure random number generator.
 * @return string
 */
function generate_unique_token(): string
{
    return bin2hex(random_bytes(32)); // A 32-byte (256-bit) random number.
}



/**
 * Stores the reset token in the database.  HASH the token for security.
 * @param int $userId
 * @param string $token
 * @return void
 */
function store_reset_token(int $userId, string $token): void
{
    // *** IMPLEMENT THIS FUNCTION ***
    // Example (using a hypothetical database table called 'reset_tokens'):
    // mysqli_query($db, "INSERT INTO reset_tokens (user_id, token, created_at) VALUES ('$userId', '$token', NOW())");
    // OR using an ORM:
    // $this->db->insert('reset_tokens', ['user_id' => $userId, 'token' => $token, 'created_at' => date('Y-m-d H:i:s')]);
}


/**
 * Sends an email.  Replace this with your email sending implementation.
 * @param string $to
 * @param string $subject
 * @param string $body
 * @param string $headers
 * @return bool
 */
function send_email(string $to, string $subject, string $body, string $headers): bool
{
    // *** IMPLEMENT THIS FUNCTION ***
    // Example (using a placeholder):
    // error_log("Sending email to: " . $to . " Subject: " . $subject); //Log for debugging
    // return true; //Replace with your actual email sending code.

    //Real Email sending example - using PHPMailer (install via Composer)
    require_once 'vendor/phpmailer/phpmailer/src/Exception.php';
    require_once 'vendor/phpmailer/phpmailer/src/Exception.php';

    $mail = new \PHPMailer\PHPMailer\PHPMailer();
    $mail->isSMTP();
    $mail->Host = 'smtp.example.com';
    $mail->SMTPAuth = true;
    $mail->Username = 'your_smtp_username';
    $mail->Password = 'your_smtp_password';
    $mail->Port = 587;
    $mail->SMART_HOST = true;
    $mail->setFrom('your_email@example.com', 'Your Website Name');
    $mail->addAddress($to, 'User Name');
    $mail->Subject = $subject;
    $mail->Body = $body;
    $mail->isHTML(false); // Set to true if you're sending HTML emails
    if ($mail->send()) {
        return true;
    } else {
        error_log("Error sending email: " . print_r($mail->getSMTPError(), true));
        return false;
    }

}


/**
 * Returns the base URL of your website.  This is how the reset URL will be constructed.
 * @return string
 */
function base_url(): string
{
    // Replace with your actual base URL.
    return 'http://localhost/your_website'; //Example.
}


/**
 * Returns the admin email address.
 * @return string
 */
function get_admin_email(): string {
    return 'admin@your_website.com';
}


?>


<?php

// Database connection details (replace with your actual credentials)
$host = "localhost";
$username = "your_db_username";
$password = "your_db_password";
$database = "your_db_name";

// --- Function to reset password ---
function resetPassword($email) {
  // 1. Validate Email
  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    return "Invalid email address.";
  }

  // 2. Check if user exists
  $conn = new mysqli($host, $username, $password, $database);

  if ($conn->connect_error) {
    return "Connection failed: " . $conn->connect_error;
  }

  $sql = "SELECT id, username FROM users WHERE email = '$email'";
  $result = $conn->query($sql);

  if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $userId = $row['id'];
    $username = $row['username'];

    // 3. Generate a unique token (important for security)
    $token = bin2hex(random_bytes(32));

    // 4.  Store the token and expiration time in the database
    $sql = "INSERT INTO password_resets (user_id, token, expires_at) VALUES ($userId, '$token', NOW() + INTERVAL 7 DAY)"; // Expires in 7 days
    if (!$conn->query($sql)) {
      return "Error inserting reset token: " . $conn->error;
    }


    // 5. Email the user with the reset link
    $subject = "Password Reset Link";
    $message = "Click this link to reset your password: <a href='" . $_SERVER["PHP_SELF"] . "?reset=$token'</a>";
    $headers = "From: your_email@example.com";  // Replace with your email

    if (mail($email, $subject, $message, $headers)) {
      return "Password reset email sent to $email.";
    } else {
      return "Failed to send password reset email.";
    }
  } else {
    return "User with email $email not found.";
  }

  $conn->close();
}



// --- Example Usage (for testing - DO NOT include this in a production environment) ---
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $email = $_POST["email"];
  $resetMessage = resetPassword($email);
  echo "<br>" . $resetMessage;
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>Forgot Password</title>
</head>
<body>

  <h1>Forgot Password</h1>

  <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
    <label for="email">Email:</label>
    <input type="email" id="email" name="email" required>
    <button type="submit">Reset Password</button>
  </form>

</body>
</html>


<?php

// Assuming you have a database connection established and a user table
// with an 'email' field.

// This is a simplified example - replace with your actual database connection
// and database query logic.

function forgot_password($email) {
  // 1. Validate the email format (basic check)
  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    return 'Invalid email format.';
  }

  // 2. Check if the user exists in the database
  // (Replace with your actual database query)
  $user = get_user_by_email($email); // Function to retrieve user by email

  if ($user === null) {
    return 'User not found.';
  }


  // 3. Generate a unique, temporary password
  $temp_password = generate_unique_password(8); // Generates an 8-character password

  // 4. Store the temporary password for the user (in a secure way - e.g., hashed)
  //  This is a placeholder.  DO NOT store plain-text passwords!
  store_temporary_password($user['id'], $temp_password);

  // 5.  Send an email to the user with the temporary password
  $subject = 'Forgot Password - Your Temporary Password';
  $message = "Your temporary password is: " . $temp_password . "

Please change your password as soon as possible.";
  $headers = 'From: your_website@example.com' . "\r
" .
            'Reply-To: your_website@example.com' . "\r
" .
            'Content-Type: text/plain; charset=UTF-8';

  mail($email, $message, $headers);

  return 'Password reset email sent to ' . $email . '.';

}


// --- Helper Functions (Implement these based on your database and security needs) ---

// Placeholder - Replace with your database query logic
function get_user_by_email($email) {
  // Example (replace with your actual query)
  // This is just a placeholder, assuming you have a user table with an 'id' and 'email' column.
  //  A real implementation would use a database query to find the user by email.
  //  For demonstration purposes, we'll just return a dummy user.
  return array(
    'id' => 123,
    'email' => $email
  );
}



function generate_unique_password($length = 8) {
    $characters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
    $password = '';
    $char_length = strlen($characters);

    for ($i = 0; $i < $length; $i++) {
        $random = mt_rand(0, $char_length - 1);
        $password .= $characters[$random];
    }

    return $password;
}


function store_temporary_password($user_id, $password) {
  //  IMPORTANT:  DO NOT STORE PASSWORDS IN PLAIN TEXT!

  //  This is a placeholder.  You *MUST* hash the password before storing it.

  //  Example (using password_hash - you'll need to adapt it to your database)
  $hashed_password = password_hash($password, PASSWORD_DEFAULT);

  //  Update the database table with the hashed password
  //  (Replace this with your actual database update query)
  //  Example:
  //  $sql = "UPDATE users SET temp_password = '$hashed_password' WHERE id = $user_id";
  //  mysqli_query($conn, $sql);
}



// --- Example Usage ---
// $email = 'test@example.com';  // Replace with the user's email

// $result = forgot_password($email);
// echo $result;
?>


<?php

// Database connection details (replace with your actual details)
$host = "localhost";
$username = "your_username";
$password = "your_password";
$dbname = "your_database";

// 1. Get the email from the form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST["email"];

    // 2. Validate the email
    if (empty($email)) {
        $error = "Please enter your email address.";
    } else {
        // 3.  Check if the email exists in the database
        $conn = new mysqli($host, $username, $password, $dbname);

        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        $sql = "SELECT id, email FROM users WHERE email = '$email'";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            // Email exists, generate a unique token and send a reset password email
            $token = bin2hex(random_bytes(32)); // Generate a random token
            $reset_link = "http://yourwebsite.com/reset_password.php?token=" . $token; // Replace with your website URL

            // Prepare the reset password email
            $to = $email;
            $subject = "Password Reset";
            $message = "Click on the link below to reset your password:
" . $reset_link;
            $headers = "From: your_email@example.com\r
";
            mail($to, $subject, $message, $headers);

            // Store the token in the database (for later retrieval) -  This is crucial!
            $conn->query("UPDATE users SET token = '$token' WHERE email = '$email'");

            $success = "Password reset email sent to $email.  Check your inbox.";
        } else {
            $error = "Email address not found.";
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

    <h2>Forgot Password</h2>

    <?php if (isset($error)) { ?>
        <p style="color: red;"><?php echo $error; ?></p>
    <?php } ?>

    <?php if (isset($success)) { ?>
        <p style="color: green;"><?php echo $success; ?></p>
    <?php } ?>

    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        <label for="email">Email Address:</label>
        <input type="email" id="email" name="email" placeholder="Your Email" required>
        <button type="submit">Reset Password</button>
    </form>

</body>
</html>


<?php

// Replace with your database credentials and table names
$db_host = 'localhost';
$db_name = 'your_database_name';
$db_user = 'your_db_user';
$db_password = 'your_db_password';

// Function to reset password
function forgot_password($email, $new_password, $db) {
  // Check if the email exists in the database
  $stmt = $db->prepare("SELECT id FROM users WHERE email = ?");
  $stmt->execute([$email]);
  $user = $stmt->fetch(PDO::FETCH_ASSOC);

  if (!$user) {
    return false; // Email not found
  }

  // Hash the new password
  $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

  // Update the user's password in the database
  $stmt = $db->prepare("UPDATE users SET password = ? WHERE id = ?");
  $stmt->execute([$hashed_password, $user['id']]);

  return true; // Password reset successful
}


// Example Usage (Demonstration - DO NOT USE IN PRODUCTION WITHOUT SECURE HANDLING)
// This is just for demonstration, it's not a complete and secure form!
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $email = $_POST["email"];
  $new_password = $_POST["new_password"];

  // Validate input (important for security - this is basic)
  if (empty($email) || empty($new_password)) {
    echo "Error: Email and password are required.";
  } else {
    // Call the forgot_password function
    $success = forgot_password($email, $new_password, $db);

    if ($success) {
      echo "Password reset email sent successfully!";
    } else {
      echo "Error: Could not reset password.  Please check your email or contact support.";
    }
  }
}

// Database connection (using PDO - recommended)
try {
  $db = new PDO("mysql:host=$db_host;dbname=$db_name;charset=utf8mb4", $db_user, $db_password);
  $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // Enable exception handling
} catch(PDOException $e) {
  echo "Connection failed: " . $e->getMessage();
  exit;
}

?>

<!DOCTYPE html>
<html>
<head>
  <title>Forgot Password</title>
</head>
<body>

  <h1>Forgot Password</h1>

  <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
    <label for="email">Email:</label>
    <input type="email" id="email" name="email" required><br><br>

    <input type="password" id="new_password" name="new_password" required><br><br>

    <button type="submit">Reset Password</button>
  </form>

</body>
</html>


<?php

// Assuming you have a database connection established
// (e.g., using mysqli or PDO)
// For simplicity, let's assume $db is a mysqli connection object

// Function to handle the forgot password process
function forgotPassword($email) {
  // 1. Validate Email (Important for security)
  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    return "Invalid email address.";
  }

  // 2. Check if the user exists
  $result = mysqli_query($db, "SELECT id, username FROM users WHERE email = '$email'");
  if (mysqli_num_rows($result) == 0) {
    return "User not found.";
  }

  // 3. Generate a Unique Token
  $token = bin2hex(random_bytes(32));

  // 4. Update the User's Record with the Token
  mysqli_query($db, "UPDATE users SET password_reset_token = '$token', password_reset_token_expiry = NOW() + INTERVAL 1 HOUR WHERE email = '$email'");

  // 5. Send the Password Reset Email
  $to = $email;
  $subject = "Password Reset Request";
  $message = "Please use the following link to reset your password: " . $_SERVER['REQUEST_URI'] . 
              "<br><br>This link will expire in 1 hour.";
  $headers = "From: your_website@example.com"; // Replace with your actual email address

  mail($to, $message, $headers);

  return "Password reset email sent. Please check your inbox.";
}


// Example Usage (Illustrative -  This would be part of your form processing)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $email = $_POST["email"];
  $reset_result = forgotPassword($email);
  echo $reset_result;
}

?>

<!DOCTYPE html>
<html>
<head>
  <title>Forgot Password</title>
</head>
<body>

  <h1>Forgot Password</h1>

  <form method="post" action="<?php echo htmlspecialchars($_SERVER["REQUEST_URI"]); ?>">
    <label for="email">Email:</label>
    <input type="email" id="email" name="email" required>
    <button type="submit">Reset Password</button>
  </form>

</body>
</html>


<?php

// Assuming you have a database connection established (e.g., $db_connection)
// This is just a basic example, adapt to your database setup

function forgotPassword($email) {
    // 1. Check if the email exists in the database
    $stmt = $db_connection->prepare("SELECT id, email FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user) {
        // Email not found
        return false;
    }

    // 2. Generate a unique token
    $token = bin2hex(random_bytes(32));  // Using random_bytes for better security

    // 3. Hash the token (for security) - This is CRUCIAL!
    $hashed_token = password_hash($token, PASSWORD_DEFAULT);

    // 4. Store the token in the database for the user
    $stmt = $db_connection->prepare("UPDATE users SET reset_token = ?, reset_token_expiry = ? WHERE email = ?");
    $stmt->execute([$hashed_token, time() + 3600, $email]); // Store expiry 1 hour from now

    // 5.  Email the user with a reset link
    $reset_link = "http://yourwebsite.com/reset-password?token=$token"; // Replace with your website URL

    // Email the user
    $to = $email;
    $subject = "Password Reset Request";
    $message = "Please click on the following link to reset your password: " . $reset_link;
    $headers = "From: yourwebsite@example.com"; // Replace with your email address

    mail($to, $subject, $message, $headers);

    return true;
}


// Example usage (assuming you've called forgotPassword() with an email)
// $email_to_reset = "testuser@example.com"; // Replace with the user's email
// $result = forgotPassword($email_to_reset);

// if ($result) {
//     echo "Password reset email has been sent.";
// } else {
//     echo "User not found or email reset link could not be generated.";
// }


//Important Security Notes & Enhancements:

// 1.  Database Connection:  Replace `$db_connection` with your actual database connection object.  This example assumes you're using PDO.

// 2.  Error Handling:  Add more robust error handling.  Check the return values of your database queries.  Log errors.

// 3.  Password Hashing:  The `password_hash()` function is critical.  Never store passwords in plain text.

// 4.  Token Uniqueness and Randomness:  `random_bytes()` is used to generate the token.  This is the most secure method for generating random data.  Using `mt_rand()` or `rand()` is generally not recommended for security-sensitive operations.

// 5.  Token Expiry:  The `reset_token_expiry` field and `time() + 3600` (one hour) are added to prevent tokens from being used indefinitely. Adjust the expiry time as needed.  Consider logging expiry events.

// 6.  Rate Limiting: Implement rate limiting on password reset requests to prevent brute-force attacks.  Store the number of reset attempts per user and block access if the limit is exceeded.

// 7.  Secure URL:  The `reset_link` URL should be generated securely.  Don't include the token directly in the URL string in a production environment.  Use URL encoding or a more secure method.

// 8.  Input Validation:  Validate the email address to prevent malicious input.

// 9.  Security Audits: Regularly perform security audits of your code to identify and address vulnerabilities.

// 10. CSRF Protection: Implement CSRF (Cross-Site Request Forgery) protection, especially if you are generating the reset link on a form submission.

// 11. Logging:  Log password reset requests, including the user's email, the token generated, and the timestamp. This is important for debugging and security monitoring.
?>


<?php

/**
 * Resets a user's password via email.
 *
 * @param string $email The email address of the user.
 * @param string $newPassword The new password to set.
 * @param string $websiteUrl The URL of your website. (Optional, for link in email)
 * @return bool True on success, false on failure.
 */
function forgotPassword(string $email, string $newPassword, string $websiteUrl = '') {
  // **Security Note:**  This is a simplified example for demonstration.
  // In a production environment, you *MUST* implement robust security measures,
  // including:
  // 1.  Hashing the password before storing it in the database.
  // 2.  Validating email format.
  // 3.  Rate limiting to prevent brute-force attacks.
  // 4.  Using a dedicated email sending service (e.g., SendGrid, Mailgun)
  //     for better deliverability and security.

  // Check if email exists in the database (replace with your actual database logic)
  $user = getUserByEmail($email);

  if (!$user) {
    return false; // User not found
  }

  // Update the user's password in the database (replace with your actual database logic)
  if (!updateUserPassword($user, $newPassword)) {
    return false; // Password update failed
  }


  // Send password reset email
  $subject = 'Password Reset';
  $body = "Please use the following link to reset your password:
" .
          "<a href='" . $websiteUrl . "/reset_password?token=" . generateResetToken($user->id) . "'>Reset Password</a>";
  $headers = "From: " . 'Your Website Name <noreply@yourwebsite.com>' . "\r
";
  // Use mail() for simplicity, but consider a dedicated email sending service.
  if (mail($email, $subject, $body, $headers)) {
    return true;
  } else {
    // Email sending failed - you should log this error.
    return false;
  }
}


/**
 * Placeholder function to retrieve a user by email.  Replace with your database query.
 *
 * @param string $email The email address to search for.
 * @return object|null User object if found, null otherwise.
 */
function getUserByEmail(string $email) {
  // Replace this with your actual database query.
  // Example (assuming you have a 'users' table with an 'email' column)
  // $db = new PDO(/* your database connection details */);
  // $stmt = $db->prepare("SELECT * FROM users WHERE email = ?");
  // $stmt->execute([$email]);
  // $user = $stmt->fetch(PDO::FETCH_OBJ);
  // return $user;

  // Mock user object for demonstration
  $user = new stdClass();
  $user->id = 123; // Example user ID
  return $user;
}


/**
 * Placeholder function to update a user's password in the database.
 * Replace with your actual database query.
 *
 * @param object $user The user object to update.
 * @param string $newPassword The new password to set.
 * @return bool True on success, false on failure.
 */
function updateUserPassword(object $user, string $newPassword) {
  // Replace this with your actual database query.
  // Example:
  // $db = new PDO(/* your database connection details */);
  // $stmt = $db->prepare("UPDATE users SET password = ? WHERE id = ?");
  // $stmt->execute([password_hash($newPassword, PASSWORD_DEFAULT), $user->id]);
  // return true;

  // Mock success for demonstration
  return true;
}

/**
 * Generates a unique reset token.
 *
 * @param int $userId The ID of the user.
 * @return string  A unique token.
 */
function generateResetToken() {
  return bin2hex(random_bytes(32)); // Use a cryptographically secure random number generator
}



// --- Example Usage ---
// You would call this function from your form submission code:

// $email = $_POST['email'];
// $newPassword = $_POST['newPassword'];

// if (isset($email) && isset($newPassword)) {
//   if (forgotPassword($email, $newPassword)) {
//     echo "Password reset email has been sent.  Check your inbox!";
//   } else {
//     echo "Error resetting password. Please try again.";
//   }
// } else {
//   echo "Please enter your email and a new password.";
// }


// --- Note:  This is a VERY simplified example. ---
// In a real application, you would:
// 1.  Validate the email and password input thoroughly.
// 2.  Use a dedicated email sending service for better reliability and security.
// 3.  Implement robust security measures to protect against attacks.
// 4.  Handle errors gracefully.


<?php

// Assuming you have a database connection established (e.g., $db connection)

/**
 * Resets a user's password.
 *
 * @param string $email The user's email address.
 * @return bool True if the password was reset successfully, false otherwise.
 */
function forgotPassword(string $email)
{
    // 1. Validate Email (Important!)
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        error_log("Invalid email format provided."); // Log the error for debugging
        return false;
    }

    // 2. Check if User Exists
    $user = getUserByEmail($email); //  Assumed function to get user by email
    if (!$user) {
        error_log("User with email '$email' not found."); //Log the error
        return false;
    }

    // 3. Generate a Unique Token (for security)
    $token = generateUniqueToken(); //  Assumed function to generate a unique token

    // 4. Store Token and User ID in a Temporary Table
    //    (This is important for security -  don't store tokens directly in the main user table)
    $query = "INSERT INTO password_reset_tokens (user_id, token, expires_at)
              VALUES ($user->id, '$token', NOW() + INTERVAL 24 HOUR)";
    mysqli_query($GLOBALS['db'], $query); // Use mysqli_query or PDO for better security.

    // 5.  Send Password Reset Email (Email Logic - Not Implemented Here)
    //   This is where you would send an email with a link containing the token.
    //   The email link should lead to a page where the user can enter a new password.
    //   Example:
    //   $subject = "Password Reset";
    //   $to = $user->email;
    //   $headers = "From: your-email@example.com";
    //   $link = "/reset-password?token=$token";
    //   mail($to, $subject, $link, $headers);
    //   echo "Password reset email sent to $email. Check your inbox.";


    return true; // Indicate successful token generation and storage.
}


/**
 * Assumed function to get user by email.  Implement this based on your database.
 * @param string $email
 * @return mysqli_result|null
 */
function getUserByEmail(string $email) {
    // Replace with your database query
    // This is just a placeholder.  Adjust to your database and setup.
    // Example using mysqli:
    // $query = "SELECT * FROM users WHERE email = '$email'";
    // $result = mysqli_query($GLOBALS['db'], $query);
    // if (mysqli_num_rows($result) > 0) {
    //   return mysqli_fetch_assoc($result);
    // } else {
    //   return null;
    // }
    //  A placeholder returning a dummy user object
    return new \stdClass(); // Returns a new empty object.
}


/**
 * Assumed function to generate a unique token.
 * @return string
 */
function generateUniqueToken()
{
    return bin2hex(random_bytes(32)); // Generates a 32-byte random hex string
}



// Example Usage (Illustrative - Not part of the forgotPassword function)
// You would typically trigger this through a form submission.
// $email = $_POST['email'];
// if (isset($email)) {
//     if (forgotPassword($email)) {
//         echo "Password reset email sent to $email.";
//     } else {
//         echo "Failed to generate password reset token.";
//     }
// }


?>


<?php

// Database credentials (Replace with your actual credentials)
$host = 'localhost';
$username = 'your_username';
$password = 'your_password';
$database = 'your_database';

// Function to reset password
function forgot_password($email) {
  // 1. Validate Email
  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    return "Invalid email format.";
  }

  // 2. Check if user exists
  $conn = new mysqli($host, $username, $password, $database);

  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  $sql = "SELECT id, username FROM users WHERE email = '$email'";
  $result = $conn->query($sql);

  if ($result->num_rows > 0) {
    $user_id = $result->fetch_assoc()['id'];
    $username = $result->fetch_assoc()['username'];

    // 3. Generate a unique token
    $token = bin2hex(random_bytes(32));

    // 4.  Store Token and User ID in a temporary table (for security)
    $conn->query("CREATE TEMPORARY TABLE IF NOT EXISTS reset_tokens (user_id INT, token VARCHAR(32))");
    $conn->query("INSERT INTO reset_tokens (user_id, token) VALUES ($user_id, '$token')");


    // 5.  Prepare the reset email
    $to = $email;
    $subject = 'Password Reset Request';
    $message = "Click this link to reset your password: " . '<a href="' . $_SERVER['PHP_SELF'] . '?reset=true&token=' . $token . '"' . ' >Reset Password</a>';
    $headers = "From: your_email@example.com"; // Replace with your email

    mail($to, $subject, $message, $headers);

    // 6. Clean up temporary table
    $conn->query("DROP TEMPORARY TABLE IF EXISTS reset_tokens");


    return "Password reset email sent to $email.";

  } else {
    return "User not found.";
  }

  $conn->close();
}

// ---  Example Usage (For testing - remove this section when integrating into your application) ---
//  This is just for demonstrating the function.  In a real application, you'd
//  likely handle this via a form submission.

if (isset($_GET['reset']) && isset($_GET['token'])) {
  $token = $_GET['token'];

  //  This is where you would check if the token exists in your database.
  //  This example doesn't include the check for simplicity, but it's crucial
  //  for security in a production environment.

  $reset_result = forgot_password($token); // Using the token as the email (for demonstration)
  echo $reset_result; // This should be the output from the forgot_password function
}

?>


<?php

// Configuration (Important - Replace with your actual values)
$db_host = 'localhost';
$db_name = 'your_database_name';
$db_user = 'your_database_username';
$db_password = 'your_database_password';

// Function to reset password
function forgot_password($email, $new_password) {
    // 1. Connect to the database
    $conn = new mysqli($db_host, $db_user, $db_password, $db_name);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // 2. Hash the new password -  IMPORTANT for security!
    $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

    // 3. Update the password in the database
    $sql = "UPDATE users SET password = '$hashed_password' WHERE email = '$email'";

    if ($conn->query($sql) === TRUE) {
        echo "New password set successfully! Please check your email for login instructions.";
    } else {
        echo "Error updating password: " . $conn->error;
    }

    $conn->close();
}


// Example usage (This is just for demonstration - you'll need a form to submit this)
// In a real application, you would handle the form submission here.
// This example shows how the function would be called.
// For a real application, you'll need to implement a form with an email field.

// Example: Let's say the user's email is 'test@example.com' and the new password is 'newPassword123'
//forgot_password('test@example.com', 'newPassword123');
?>


<?php

/**
 * Forgot Password Function
 *
 * This function handles the forgot password process, generating a temporary
 * password and sending it to the user's email.
 *
 * @param string $email The user's email address.
 * @param string $resetLinkBase URL where the reset links will be generated.  
 *                              Should be a URL that doesn't require authentication.
 * @return bool True if the reset email was sent successfully, false otherwise.
 */
function forgotPassword(string $email, string $resetLinkBase) {
  // 1. Sanitize and Validate Email
  $email = filter_var($email, FILTER_SANITIZE_EMAIL);
  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    error_log("Invalid email address provided: " . $email); // Log for debugging
    return false;
  }

  // 2. Generate a Temporary Password (e.g., random string)
  $resetPassword = generateRandomString(8); //  Adjust length as needed
  
  // 3. Store the Reset Token in the Database
  $token = hash('sha256', $resetPassword . time());  // Use a more secure hashing algorithm
  
  // IMPORTANT: In a real-world application, you would store this data in a database.
  // For demonstration purposes, we'll use a simple array.
  $resetData = [
    'email' => $email,
    'token' => $token,
    'expiry' => time() + 3600  // Expire after 1 hour
  ];
  
  // 4.  Simulate Database Storage (Replace with your database logic)
  // In a real app, you'd use something like:
  // $db->insert('forgot_passwords', $resetData); 
  
  // 5. Generate the Reset Link
  $resetLink = $resetLinkBase . '?token=' . urlencode($token);

  // 6. Send the Reset Email
  $to = $email;
  $subject = 'Password Reset';
  $message = "To reset your password, please click on the following link: " . $resetLink;
  $headers = "From: YourWebsite <noreply@yourwebsite.com>"; // Customize the sender address
  
  if (mail($to, $subject, $message, $headers)) {
    return true;
  } else {
    error_log("Failed to send reset email to: " . $email); // Log email sending failure
    return false;
  }
}

/**
 * Generates a random string of the specified length.
 *
 * @param int $length The desired length of the random string.
 * @return string The generated random string.
 */
function generateRandomString(int $length) {
  $characters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
  $randomString = '';
  $length = $length;
  for ($i = 0; $i < $length; $i++) {
    $char = $characters[rand(0, strlen($characters) - 1)];
    $randomString .= $char;
  }
  return $randomString;
}

// --- Example Usage ---
// Replace with your actual email address
$email = "testuser@example.com";
$resetLinkBase = "https://yourwebsite.com/reset-password";  // Change this to your website's base URL

if (forgotPassword($email, $resetLinkBase)) {
  echo "Reset email sent successfully! Check your inbox.";
} else {
  echo "Failed to send reset email. Please check your email settings and try again.";
}

?>


<?php

// Assuming you have a database connection established and stored in a variable like $db

/**
 * Forgets the password for a user.
 *
 * @param string $email The email address of the user.
 * @return bool True on success, false on failure.
 */
function forgot_password(string $email): bool
{
    // 1. Validate Input (Crucial for Security)
    if (empty($email)) {
        error_log("Forgot Password: Empty email provided."); // Log the error
        return false;
    }

    // 2.  Check if the user exists
    $user = get_user_by_email($email); // Implement this function (see below)
    if (!$user) {
        error_log("Forgot Password: User with email {$email} not found.");
        return false;
    }

    // 3. Generate a Unique Token (Securely)
    $token = generate_unique_token(); // Implement this function (see below)

    // 4.  Store Token in Database (Associating with User)
    //    This is where you'd update the 'token' column in your user table.
    update_user_token($user['id'], $token); // Implement this function (see below)

    // 5.  Send Password Reset Email
    //    You'll need to format and send an email with a link to the reset page
    //    The email link should include the token.
    send_password_reset_email($user['email'], $token); // Implement this function (see below)

    return true;
}



/**
 *  Helper functions (Implement these based on your database schema)
 */

/**
 *  Retrieves a user's data by email.  This is just an example.
 *  Replace with your actual database query.
 *
 *  @param string $email
 *  @return array|null An associative array containing user data if found, null otherwise.
 */
function get_user_by_email(string $email): ?array
{
    // Replace this with your database query to get user data by email
    // Example (using MySQLi) -  Adapt to your database system
    $conn = new mysqli("localhost", "username", "password", "database");

    if ($conn->connect_error) {
      die("Connection failed: " . $conn->connect_error);
    }

    $sql = "SELECT id, email FROM users WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();

    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        $stmt->close();
        return $row;
    } else {
        $stmt->close();
        return null;
    }
}



/**
 * Generates a unique token. Use a cryptographically secure random number generator.
 *
 * @return string A unique token.
 */
function generate_unique_token(): string
{
    return bin2hex(random_bytes(32));  // More secure than rand()
}



/**
 * Updates the 'token' column in the user's record with the given token.
 *
 * @param int $userId The ID of the user.
 * @param string $token The token to store.
 */
function update_user_token(int $userId, string $token): void
{
    // Replace this with your database query to update the 'token' column.
    // Example (using MySQLi)
    $conn = new mysqli("localhost", "username", "password", "database");

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $sql = "UPDATE users SET token = ? WHERE id = ?";
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("ss", $token, $userId);
        if ($stmt->execute()) {
            $stmt->close();
        } else {
            error_log("Error updating token for user ID {$userId}: " . $conn->error);
        }
    } else {
        error_log("Error preparing update statement: " . $conn->error);
    }

    $conn->close();
}



/**
 * Sends an email with a password reset link.
 *
 * @param string $email The email address of the user.
 * @param string $token The token associated with the password reset request.
 */
function send_password_reset_email(string $email, string $token): void
{
    // Implement the logic to send the email. This is a placeholder.
    // Use a reliable email sending service (e.g., SendGrid, Mailgun, AWS SES)
    // or configure your own SMTP server.

    //Example of what the email content might look like:
    $subject = 'Password Reset';
    $message = "Click on the following link to reset your password: " . "<a href='" . $_SERVER['PHP_SELF'] . "?reset_token=" . urlencode($token) . "'>Reset Password</a>";
    //  Replace this with your actual email sending function
    //  For example:
    //  mail($email, $subject, $message);

    error_log("Password reset email sent to {$email} with token {$token}");
}


// Example Usage (IMPORTANT:  Use this only in testing.  Never in production!)
//  To test, you'd call this from a form submission (e.g., using POST)
//  and the reset_token would be passed from the form to the server.
//  Uncomment the code below for testing ONLY.
/*
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST["email"];

    if (forgot_password($email)) {
        echo "Password reset email sent to {$email}.";
    } else {
        echo "Failed to initiate password reset.";
    }
}
*/

?>


<?php

// Assuming you have a database connection established
// Replace 'your_db_connection' with your actual connection details
$db = new mysqli('localhost', 'username', 'password', 'database_name');

// Check connection
if ($db->connect_error) {
    die("Connection failed: " . $db->connect_error);
}

/**
 * Forgets a user's password and sends a password reset email.
 *
 * @param string $email The user's email address.
 * @return bool True if email was sent, false otherwise.
 */
function forgot_password($email) {
  // Sanitize the email to prevent potential security issues
  $email = filter_var($email, FILTER_SANITIZE_EMAIL);

  // Validate email format
  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    return false; // Invalid email format
  }

  // Check if the user exists
  $result = $db->query("SELECT id, username FROM users WHERE email = '$email'");

  if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
    $userId = $user['id'];
    $username = $user['username'];

    // Generate a unique, random password reset token
    $reset_token = bin2hex(random_bytes(32));

    // Store the token in the database for the user
    $db->query("UPDATE users SET reset_token = '$reset_token' WHERE id = $userId");

    // Prepare a password reset email
    $to = $email;
    $subject = 'Password Reset';
    $message = "Click this link to reset your password: " . '<a href="' . $_SERVER['PHP_SELF'] . '?reset=' . urlencode($reset_token) . '"' . ' target="_blank">' . '<a href="' . $_SERVER['PHP_SELF'] . '?reset=' . urlencode($reset_token) . '"' . ' target="_blank">' . $_SERVER['PHP_SELF'] . '?reset=' . urlencode($reset_token) . '</a>';
    $headers = "From: your_email@example.com" . "\r
";
    mail($to, $message, $headers);

    return true; // Email sent successfully
  } else {
    return false; // User not found
  }
}


// Example Usage (This is for demonstration - you'll need to handle form submission and validation)
if ($_SERVER["REQUEST_METHOD"] == "GET") {
    // Get the email from the form (assuming a form submitted)
    $email = $_POST['email']; // Get the email from the form
    if (forgot_password($email)) {
        echo "Password reset email has been sent to " . $email;
    } else {
        echo "Email not found or an error occurred.";
    }
}

// Prevent direct access to this script
if ($_SERVER["REQUEST_METHOD"] != "GET") {
  header("Location: index.php"); // Redirect to a welcome page or login form.
}


?>


<?php

// Configuration (Change these to your specific setup)
$db_host = 'localhost';
$db_name = 'your_database_name';
$db_user = 'your_database_user';
$db_password = 'your_database_password';

// Function to handle password reset
function forgot_password($email) {
  // 1. Validate Email
  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    return "Invalid email address.";
  }

  // 2. Generate a unique token
  $token = bin2hex(random_bytes(32)); // Secure random token

  // 3. Prepare the query
  $mysqli = new mysqli($db_host, $db_user, $db_password, $db_name);

  if ($mysqli->connect_errno) {
    return "Failed to connect to database: " . $mysqli->connect_error;
  }

  $sql = "INSERT INTO password_resets (email, token, created_at) VALUES (?, ?, NOW())";
  $stmt = $mysqli->prepare($sql);

  if (!$stmt) {
    return "Failed to prepare statement.";
  }

  $stmt->bind_param("s", $email, $token);

  if (!$stmt->execute()) {
    return "Failed to insert data: " . $stmt->error;
  }

  $stmt->close();
  $mysqli->close();

  // 4.  Send the reset email (Implementation details depend on your email sending method)
  //     This is a placeholder.  Replace with your actual email sending code.
  send_reset_email($email, $token);  // Call the function to send the email.

  return "Password reset email sent to $email.";
}


// Placeholder function for sending reset email.  REPLACE THIS with your actual email sending code.
function send_reset_email($email, $token) {
  //  This is just a demo.  In a real application, you'd use a library like PHPMailer or
  //  a service like SendGrid or Mailgun.

  $subject = 'Password Reset - Your Account';
  $message = "Click the following link to reset your password:  http://yourwebsite.com/reset_password.php?token=" . $token;
  $headers = 'From: your_email@example.com' . "\r
";

  // In a real application, you'd use mail() or a library to send the email.
  // Example using mail():
  // mail($email, $subject, $message, $headers);

  echo "Simulating sending email to $email with token $token"; // For demonstration purposes.
}

// Example Usage (For testing)
// You'll likely want to call this from a form, not directly like this.

// Example 1: Successful reset
$result = forgot_password("testuser@example.com");
echo $result . "<br><br>";

// Example 2: Invalid email
$result = forgot_password("invalid-email");
echo $result . "<br><br>";
?>


<?php

// Include the database connection file (assuming it's named database.php)
require_once 'database.php';

/**
 * Forgets a user's password.
 *
 * @param string $email The user's email address.
 * @return bool True if a password reset email was sent, false otherwise.
 */
function forgot_password(string $email): bool
{
  // Sanitize the email address to prevent SQL injection
  $email = mysqli_real_escape_string($connection, $email);

  // Check if the email exists in the users table
  $result = mysqli_query($connection, "SELECT id, username FROM users WHERE email = '$email'");

  if (mysqli_num_rows($result) > 0) {
    // Email exists, generate a unique token and store it
    $token = bin2hex(random_bytes(32));  // Generate a random token
    $hashed_token = password_hash($token, PASSWORD_DEFAULT); // Hash the token for security

    // Store the token in the database (associated with the user)
    $sql = "UPDATE users SET reset_token = '$hashed_token' WHERE email = '$email'";

    if (mysqli_query($connection, $sql)) {
      // Send a password reset email (implementation depends on your email sending system)
      // This is just a placeholder - replace with your email sending code
      send_password_reset_email($email, $token);

      return true;
    } else {
      // Error sending the email or updating the database
      error_log("Error updating user with token: " . mysqli_error($connection));
      return false;
    }
  } else {
    // Email does not exist
    return false;
  }
}

/**
 * Placeholder function for sending the password reset email.
 *
 * @param string $email The user's email address.
 * @param string $token The generated reset token.
 */
function send_password_reset_email(string $email, string $token): void
{
  // Replace with your email sending logic here.
  // This is a placeholder - it does not actually send an email.
  error_log("Password reset email sent to: " . $email . " with token: " . $token);
  // Example using a basic email sending function (you'd need to configure this)
  /*
  $to = $email;
  $subject = 'Password Reset';
  $message = 'Click this link to reset your password: ' . 'http://yourwebsite.com/reset_password.php?token=' . $token;
  $headers = 'From: yourwebsite@example.com' . "\r
";

  mail($to, $message, $headers);
  */
}


// Example Usage (for testing - DO NOT use this directly in a deployed application)
//  This is just an example for demonstration and should not be directly used in a production environment.

/*
$user_email = "testuser@example.com"; // Replace with a valid email address
if (forgot_password($user_email)) {
    echo "Password reset email sent to $user_email";
} else {
    echo "Failed to generate password reset token.";
}
*/

?>


<?php

// Replace with your database connection details
$host = "localhost";
$username = "your_db_username";
$password = "your_db_password";
$database = "your_database_name";

// Check if the reset token is present in the query string
if (isset($_GET['token'])) {
  $token = $_GET['token'];

  // 1. Validate the token
  if (!isValidToken($token)) {
    echo "Invalid reset token.";
    exit;
  }

  // 2. Retrieve the user's email from the database based on the token
  $user = getUserByToken($token);

  if ($user) {
    // 3. Generate a new, unique reset token and send an email with a link
    $newToken = generateUniqueToken();
    $email = $user['email'];

    //  --  Email Sending Logic --
    //  Replace with your email sending library/function
    $subject = "Password Reset - Your Account";
    $message = "Click on this link to reset your password: " . base_url() . "/reset-password?token=" . $newToken;
    $headers = "From: your_email@example.com" . "\r
"; // Replace with your email address
    mail($email, $message, $headers);

    // 4.  Update the user's record with the new token (optional, but good practice)
    updateUserToken($user['id'], $newToken);

    echo "Reset link has been sent to your email.";
  } else {
    echo "User not found with that token.";
  }
} else {
  echo "Please provide a reset token.";
}


// --- Helper Functions ---

// 1. Validate the token
function isValidToken($token) {
    // Implement your token validation logic here. 
    // This could involve checking against a database table 
    // that stores used tokens and their expiration times.

    // Example: (Replace with your actual validation)
    return true; //  Placeholder -  Replace with your actual validation
}


// 2. Retrieve the user by token
function getUserByToken($token) {
  global $host, $username, $password, $database;

  // Database connection
  $conn = new mysqli($host, $username, $password, $database);

  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  // SQL Query
  $sql = "SELECT * FROM users WHERE reset_token = '$token'"; // Assuming 'reset_token' column in your users table

  $result = $conn->query($sql);

  if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
    return $user;
  } else {
    return null;
  }

  $conn->close();
}



// 3. Generate a unique token
function generateUniqueToken() {
  return bin2hex(random_bytes(32)); // Returns a 32-byte random string.
}


// 4. Update the user's token (optional, but recommended)
function updateUserToken($userId, $newToken) {
    global $host, $username, $password, $database;

    $conn = new mysqli($host, $username, $password, $database);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $sql = "UPDATE users SET reset_token = '$newToken' WHERE id = $userId";
    if ($conn->query($sql) === TRUE) {
        //echo "User token updated successfully";
    } else {
        echo "Error updating token: " . $conn->error;
    }

    $conn->close();
}


// Example base_url function (requires you to define it)
//  This assumes you are using URL rewriting.
function base_url() {
    // Adjust this based on your application setup.
    return "http://localhost/your_project_name/";
}

?>


<?php

// Assuming you have a database connection established (e.g., $db)

/**
 * Resets a user's password.
 *
 * @param string $email The user's email address.
 * @return bool True if a reset link was generated and sent, false otherwise.
 */
function forgotPassword(string $email)
{
    // 1. Check if the email exists in the database
    $user = db_get_user_by_email($email); // Replace with your DB query
    if (!$user) {
        error_log("User with email {$email} not found.");  // Log for debugging
        return false;
    }


    // 2. Generate a unique reset token
    $resetToken = generateUniqueToken();

    // 3. Store the token and user ID in the database
    $result = db_create_reset_token($user->id, $resetToken);

    if (!$result) {
        error_log("Failed to create reset token for user {$email}.");
        return false;
    }

    // 4. Generate the reset link
    $resetLink = generateResetLink($resetToken);

    // 5. Send the reset link via email
    if (!sendEmailWithResetLink($user->email, $resetLink) ) {
      //Handle email sending failure - log, display message, etc.
        error_log("Failed to send reset email to {$user->email}");
        //Optionally:  Delete the reset token from the database to prevent abuse.
        db_delete_reset_token($resetToken, $user->id);
        return false;
    }


    // 6. Return true, indicating success
    return true;
}



/**
 * Placeholder function to retrieve a user by email (replace with your DB query)
 * @param string $email
 * @return User|null  A User object or null if not found
 */
function db_get_user_by_email(string $email): ?User {
    // Example using a fictional User class
    // Replace this with your actual database query
    // This is a simplified example.  Don't use this directly in production.

    //Example using a fictional User Class
    //Replace with your database query
    //This is a simplified example.  Don't use this directly in production.

    // Assume User class:
    // class User {
    //     public $id;
    //     public $email;
    //     // ... other user attributes
    // }

    $user = new User();
    $user->email = $email;  // Simulate fetching from the database
    return $user;
}


/**
 * Placeholder function to generate a unique token.
 * In a real application, use a robust library for generating cryptographically secure tokens.
 * @return string
 */
function generateUniqueToken(): string
{
    return bin2hex(random_bytes(32));  // Generate a random 32-byte (256-bit) hex string
}


/**
 * Placeholder function to generate the reset link.
 * @param string $token
 * @return string
 */
function generateResetLink(string $token): string
{
    return "http://example.com/reset-password?token=" . urlencode($token);
}


/**
 * Placeholder function to send the email with the reset link.
 * Replace with your email sending logic.
 * @param string $email
 * @param string $resetLink
 */
function sendEmailWithResetLink(string $email, string $resetLink): bool
{
    //  Replace this with your actual email sending implementation.
    //  Use a library like PHPMailer or Swift Mailer for robust email sending.

    // Simulate sending an email (for testing)
    error_log("Simulating sending reset email to {$email} with link: {$resetLink}");
    return true;
}


/**
 * Placeholder function to delete a reset token from the database.
 * @param string $token
 * @param int $userId
 */
function db_delete_reset_token(string $token, int $userId): bool {
  // Replace with your database deletion logic
  // Example:
  // $result = db_query("DELETE FROM reset_tokens WHERE token = '$token' AND user_id = $userId");
  // return $result->rowCount > 0;

  error_log("Simulating deleting reset token for user {$userId} with token: {$token}");
  return true;
}




// Example Usage (Testing)
if (isset($_POST['email'])) {
    $email = $_POST['email'];
    if (forgotPassword($email)) {
        echo "Reset link sent to {$email}. Check your email.";
    } else {
        echo "Failed to generate reset link. Please try again.";
    }
}

?>


<?php

// Assuming you have a database connection established
// (e.g., using mysqli or PDO)
// For this example, we'll assume a simple $db connection is already set up

// Function to handle password reset requests
function forgot_password($email) {
  // 1. Validate the email
  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    return "Invalid email address.";
  }

  // 2. Generate a unique token
  $token = bin2hex(random_bytes(32)); //  More secure than older methods
  // Consider storing the token in a database table (e.g., 'reset_tokens')

  // 3.  Set up the token expiry (optional, but recommended)
  //   -  This prevents tokens from being used indefinitely.
  $expiry = time() + 3600; // Token expires in 1 hour (3600 seconds)


  // 4.  Simulate database insertion (replace with your actual database logic)
  //   This part simulates inserting the token into a database table.
  //   In a real application, this would use a database query to insert
  //   the email and token into the 'reset_tokens' table.

  $reset_token_data = [
    'email' => $email,
    'token' => $token,
    'expiry' => $expiry,
    'created_at' => time() // Record the creation timestamp
  ];

  //  Example:  Storing in an array for demonstration.
  //  In a real application, this would be a database insertion.
  //  $db->insert('reset_tokens', $reset_token_data);


  // 5.  Send the reset link (e.g., via email)
  //   -  Build the reset link URL
  $reset_link = "http://yourdomain.com/reset_password?token=" . $token . "&expiry=" . $expiry;

  //   -  Send the email. You can use a library like PHPMailer.
  //   -  Example using a simple echo to demonstrate the link.
  //   echo "<p>Please click the following link to reset your password:</p><a href='" . $reset_link . "' >Reset Password</a>";


  // 6.  Return a success message (or any relevant information)
  return "A password reset link has been sent to your email address.  Please check your inbox.";
}


// Example usage:
$email_to_reset = "testuser@example.com"; // Replace with the user's email
$reset_message = forgot_password($email_to_reset);
echo $reset_message;
?>


<?php

// Assuming you have a database connection established and available as $db

/**
 * Resets a user's password.
 *
 * @param string $email The email address of the user requesting a password reset.
 * @return bool True if a password reset email was sent, false otherwise.
 */
function forgot_password(string $email): bool
{
    // 1. Validate Email (important for security)
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        error_log("Invalid email address provided."); // Log for debugging
        return false;
    }

    // 2. Check if User Exists
    $user = getUserByEmail($email); // Assumes you have a function to fetch user by email
    if ($user === null) {
        error_log("User with email $email not found."); // Log for debugging
        return false;
    }

    // 3. Generate a Unique Token
    $reset_token = generate_unique_token();

    // 4. Store Token in Database (Temporary - Expire after some time)
    //    - Key: User ID
    //    - Value: Token
    save_reset_token($user['id'], $reset_token);

    // 5. Send Password Reset Email
    $subject = "Password Reset Request";
    $headers = "From: your_email@example.com"; // Replace with your email
    $message = "Click <a href='" . $_SERVER['PHP_SELF'] . "?reset=" . urlencode($reset_token) . "'>here</a> to reset your password.";

    if (send_email($user['email'], $subject, $headers, $message)) {
        return true;
    } else {
        error_log("Failed to send password reset email.");  // Log for debugging
        return false;
    }
}


/**
 * Dummy functions for illustration purposes.
 * Replace these with your actual implementation.
 */

function getUserByEmail(string $email): ?array
{
    // Replace this with your actual database query to fetch the user.
    // Example:
    // $query = "SELECT * FROM users WHERE email = ?";
    // $stmt = $db->prepare($query);
    // $stmt->execute([$email]);
    // $user = $stmt->fetch(PDO::FETCH_ASSOC);
    // return $user ?: null;

    // Dummy data for testing
    $dummy_users = [
        ['id' => 1, 'email' => 'test@example.com', 'password' => 'password123'],
        ['id' => 2, 'email' => 'another@example.com', 'password' => 'securepass']
    ];
    foreach($dummy_users as $user) {
        if ($user['email'] === $email) {
            return $user;
        }
    }
    return null;
}

function generate_unique_token(): string
{
    return bin2hex(random_bytes(32)); // Use a cryptographically secure random number generator
}

function save_reset_token(int $userId, string $token): void
{
    // Implement your database logic here to save the token
    // Example:
    // $query = "INSERT INTO password_resets (user_id, token, expiry_date) VALUES (?, ?, ?)";
    // $stmt = $db->prepare($query);
    // $stmt->execute([$userId, $token, time() + 3600]); // Token expires in 1 hour
}

function send_email(string $to, string $subject, string $headers, string $message): bool
{
    // Implement your email sending logic here.
    // Use a library like PHPMailer: https://github.com/PHPMailer/PHPMailer
    // For example:
    // $mail = new PHPMailer(true);
    // $mail->SetFrom('your_email@example.com', 'Your Name');
    // $mail->AddAddress($to);
    // $mail->SetSubject($subject);
    // $mail->MsgBody($message, 'html');
    // return $mail->send();

    // Dummy implementation - just return true for testing
    echo "Sending email to $to with subject $subject
";
    return true;
}



// Example Usage (for testing)
// You would typically call this function from a form submission.
// For testing, you can call it directly
// $email = "test@example.com";
// if (forgot_password($email)) {
//     echo "Password reset email sent to $email.
";
// } else {
//     echo "Failed to send password reset email.
";
// }
?>

