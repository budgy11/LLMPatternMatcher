        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required><br><br>

        <label for="new_password">New Password:</label>
        <input type="password" id="new_password" name="new_password" required><br><br>

        <input type="submit" value="Reset Password">
    </form>

</body>
</html>


<?php

// Assuming you have a database connection established and a user table
// with an 'email' field.  This is a basic example, adjust for your setup.

// --- Configuration ---
$db_host = 'localhost';
$db_name = 'your_database_name';
$db_user = 'your_db_user';
$db_password = 'your_db_password';
$table_name = 'users';  // Replace with your user table name

// --- Function: forgot_password() ---
function forgot_password($email)
{
  // 1. Check if the email exists
  $conn = new mysqli($db_host, $db_user, $db_password, $db_name);

  if ($conn->connect_error) {
    // Handle connection error (e.g., log it, display an error message to the user)
    error_log("Database connection failed: " . $conn->connect_error);
    return "Error connecting to the database.";
  }

  $sql = "SELECT id, password, email FROM $table_name WHERE email = ?";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param("s", $email); // 's' indicates a string parameter
  $stmt->execute();

  $result = $stmt->get_result();

  if ($result->num_rows == 0) {
    $stmt->close();
    $conn->close();
    return "Email address not found.";
  } else {
    $user = $result->fetch_assoc();
    $id = $user['id'];
    $hashed_password = $user['password']; //  Store password as hashed in your database
    $new_password = generate_random_password(); // Function to generate a random password
    $conn->query("UPDATE $table_name SET password = ? WHERE id = ?", $new_password, $id);
    $stmt->close();
    $conn->close();

    // Send password reset email (implement this part)
    send_password_reset_email($user['email'], $new_password);

    return "Password reset email sent to $email";
  }
}

// --- Helper Functions (Implement these) ---

// 1. generate_random_password()
function generate_random_password() {
  // Implement a function here to generate a secure random password.
  // This is a VERY basic example - DON'T USE THIS IN PRODUCTION.
  return 'password123'; // Placeholder - REPLACE WITH A REAL RANDOM PASSWORD GENERATOR
}

// 2. send_password_reset_email($email, $new_password)
function send_password_reset_email($email, $new_password)
{
    // Implement your email sending logic here.
    // This is a placeholder - replace with your email sending functionality.
    // Use a library like PHPMailer for proper email sending.

    // Example (replace with actual email sending code):
    $to = $email;
    $subject = 'Password Reset';
    $message = "To reset your password, please click on this link: " . base64_encode("https://yourwebsite.com/reset_password.php?token=YOUR_TOKEN") . "

Regards, Your Website";
    $headers = "From: yourwebsite@example.com";

    mail($to, $message, $headers);

    echo "Password reset email sent to $email"; // For testing
}

// --- Example Usage (for testing) ---
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $email = $_POST["email"];
  $result = forgot_password($email);
  echo "<p>" . $result . "</p>";
}

?>
