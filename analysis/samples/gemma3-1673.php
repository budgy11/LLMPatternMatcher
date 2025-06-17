

<?php

// Assuming you have a database connection established and a 'users' table with an 'email' field.
// Replace these placeholders with your actual database credentials and table structure.

// Database connection details
$host = 'localhost';
$username = 'your_db_username';
$password = 'your_db_password';
$database = 'your_db_name';

// Function to handle the forgot password process
function forgot_password($email) {
  // 1. Validate Email (Important for security)
  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    return 'Invalid email address.';
  }

  // 2. Check if the user exists in the database
  $conn = new mysqli($host, $username, $password, $database);
  if ($conn->connect_error) {
    return 'Database connection failed: ' . $conn->connect_error;
  }

  $query = "SELECT * FROM users WHERE email = '{$email}'";
  $result = $conn->query($query);

  if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
    // 3. Generate a unique token and store it for the user
    $token = bin2hex(random_bytes(32));  // Generate a secure random token
    $new_password = generate_random_password();  // See helper function below

    // Hash the new password
    $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

    // Store the token and hashed password in the database.  Create a 'password_resets' table for this.
    $query = "INSERT INTO password_resets (user_id, token, password_hash, created_at) 
                VALUES (
                    {$user['id']}, 
                    '{$token}', 
                    '{$hashed_password}', 
                    NOW()
                )";

    if ($conn->query($query) === TRUE) {
      // 4. Send the password reset link (email) - Implement this part
      $subject = 'Password Reset Link';
      $message = "Please click the following link to reset your password: " . '<a href="' . $_SERVER['PHP_SELF'] . '?token=' . $token . '">' . $_SERVER['PHP_SELF'] . '?token=' . $token . '</a>'; // Construct the link

      //Important:  You'll need to have a sendmail setup for this part. This example assumes you have PHP configured to send email.
      // Implement your email sending code here (e.g., using mail() or a library like PHPMailer).
      //  For example (very basic, replace with your actual implementation):
      mail($email, $subject, $message);


      return 'Password reset link has been sent to your email address.';
    } else {
      return 'Error creating password reset record: ' . $conn->error;
    }
  } else {
    return 'User not found.';
  }

  $conn->close();
}

// Helper function to generate a random password
function generate_random_password($length = 12) {
    $characters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*()_-+={}[]|\:;"<>,.?/';
    $password = '';
    $character_count = strlen($characters);

    for ($i = 0; $i < $length; $i++) {
        $index = random_int(0, $character_count - 1);
        $password .= $characters[$index];
    }
    return $password;
}


// Example Usage (for testing - replace with your actual form handling)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $email = $_POST["email"];
  $result = forgot_password($email);
  echo $result;
}
?>
