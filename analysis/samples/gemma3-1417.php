

<?php

/**
 * Forgot Password Function
 *
 * Generates a temporary password and sends it to the user's email address.
 *
 * @param string $email The email address of the user.
 * @param string $reset_token A unique token to ensure the user is the one requesting the reset.  This is crucial!
 * @param string $from_email The email address from which the reset email is sent.
 * @param string $from_name The name of the sender.
 * @param string $base_url The base URL of your website.
 *
 * @return bool True on success, false on failure.
 */
function forgot_password(string $email, string $reset_token, string $from_email, string $from_name, string $base_url) {
  // 1. Generate a temporary password.  This should be a random, secure string.
  $password = bin2hex(random_bytes(32));  // Use bin2hex for better security.  random_bytes is more secure.
  // 2. Store the temporary password and token in the database (or storage mechanism)
  //    This is the crucial step.  Replace this with your actual database interaction.

  // Example using a hypothetical database class
  $db = new DatabaseConnection(); // Assuming you have a DatabaseConnection class

  $result = $db->insert_reset_token(
    $email,
    $password,
    $reset_token,
    time() // Expiration time
  );

  if (!$result) {
    // Handle database error - logging would be good here
    error_log("Error inserting reset token: " . $db->last_error());
    return false;
  }

  // 3.  Create the reset email message
  $subject = "Password Reset";
  $message = "To reset your password, please click on the following link:
";
  $message .= "<a href='" . $base_url . "/reset_password.php?token=" . urlencode($reset_token) . "'>Reset Password</a>
";
  $message .= "
Sincerely,
" . $from_name;

  // 4. Send the email
  if (!send_email($message, $email, $subject, $from_email, $from_name)) {
    // Handle email sending failure. Logging would be good here.
    error_log("Error sending email: " .  send_email_error()); // Assuming send_email returns an error string
    return false;
  }

  return true;
}


/**
 *  Dummy email sending function.  Replace this with your actual email sending code.
 *  This is here for demonstration purposes.
 *
 * @param string $message The email body.
 * @param string $to_email The recipient email address.
 * @param string $subject The email subject.
 * @param string $from_email The sender's email address.
 * @param string $from_name The sender's name.
 *
 * @return bool True on success, false on failure.
 */
function send_email(string $message, string $to_email, string $subject, string $from_email, string $from_name) {
  // **Replace this with your actual email sending logic (e.g., using PHPMailer, SwiftMailer, etc.)**
  // This is just a placeholder to simulate sending and return a random value.
  // In a real application, you'd use a reliable email sending library.

  // Simulate successful sending
  error_log("Simulating sending email to: " . $to_email);
  return true; //  Always return true for simulation
}

/**
 *  Dummy send_email_error function.
 *  Used for simulation.  Replace with your actual error handling.
 * @return string An error message or empty string if no error.
 */
function send_email_error() {
    return "Simulated email sending error.";
}


/**
 * Database Connection Class (Example - Replace with your actual database connection)
 */
class DatabaseConnection {
    private $host = 'localhost';
    private $username = 'your_username';
    private $password = 'your_password';
    private $dbname = 'your_database';

    public function __construct() {
        // Create connection
        $conn = new mysqli($this->host, $this->username, $this->password, $this->dbname);

        // Check connection
        if ($conn->connect_error) {
            throw new Exception("Connection failed: " . $conn->connect_error);
        }

        $this->conn = $conn;
    }

    public function last_error() {
        if ($this->conn->connect_error) {
            return $this->conn->connect_error;
        }
        return "";
    }

    public function insert_reset_token(string $email, string $password, string $reset_token, int $expiration) {
        // Example SQL insert statement - replace with your actual table and column names
        $sql = "INSERT INTO reset_tokens (email, password, token, expiration) VALUES (?, ?, ?, ?)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("sss", $email, $password, $reset_token, $expiration);
        return $stmt->execute();
    }

    //  Add other database methods as needed (e.g., select, update, delete)
}

// Example Usage (Replace with actual email and token)
// $email = 'test@example.com';
// $reset_token = 'abc123xyz456';
// $from_email = 'your_email@example.com';
// $from_name = 'Your Name';
// $base_url = 'http://localhost/your_website/';  // Replace with your base URL

// if (forgot_password($email, $reset_token, $from_email, $from_name, $base_url)) {
//   echo "Reset email sent successfully!";
// } else {
//   echo "Error sending reset email.";
// }

?>
