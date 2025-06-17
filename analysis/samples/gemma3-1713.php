
<!DOCTYPE html>
<html>
<head>
    <title>Forgot Password</title>
</head>
<body>

    <h1>Forgot Password</h1>

    <form method="post" action="">
        <label for="username">Username:</label>
        <input type="text" id="username" name="username" required>
        <button type="submit">Reset Password</button>
    </form>

</body>
</html>


<?php

// This is a simplified example and should be used with caution.
// In a production environment, you MUST implement proper security measures
// like rate limiting, CAPTCHAs, and storing password reset tokens securely.

// Database connection details - Replace with your actual credentials
$db_host = 'localhost';
$db_name = 'your_database_name';
$db_user = 'your_db_user';
$db_password = 'your_db_password';

// Function to reset password
function forgotPassword($email, $token, $new_password) {
  // 1. Verify the Token (Very Important - Security!)
  //   - This is a placeholder.  You MUST replace this with a robust verification
  //     mechanism.  Storing tokens in a database and checking against them is the
//     best practice.
  //   - For this example, we're just checking for a simple 'valid' string.  **DO NOT DO THIS IN PRODUCTION!**

  if ($token !== 'valid_token') {
    return 'Invalid token.';
  }


  // 2.  Database interaction - Update the user's password
  try {
    // Connect to the database
    $pdo = new PDO("mysql:host=$db_host;dbname=$db_name;charset=utf8mb4", $db_user, $db_password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // Enable error handling

    // Prepare the SQL query
    $sql = "UPDATE users SET password = :password, password_reset_token = NULL WHERE email = :email";

    // Prepare the statement
    $stmt = $pdo->prepare($sql);

    // Bind parameters
    $stmt->bindParam(':password', $new_password);
    $stmt->bindParam(':email', $email);

    // Execute the query
    $stmt->execute();

    // Success!
    return 'Password reset successful.  Check your email to verify.';

  } catch (PDOException $e) {
    // Handle database errors
    return 'Error resetting password: ' . $e->getMessage();
  } finally {
    // Close the database connection (good practice)
    $pdo = null;
  }
}


// Example Usage: (This would be part of your form submission handling)

// 1. Get Email and New Password from the form
$email = $_POST['email'];
$new_password = $_POST['new_password'];

// 2. Generate a Token (Ideally, use a secure random string generator)
$token = bin2hex(random_bytes(32)); // Generates a 64-character hex string.  Better than a simple counter.

// 3.  Call the forgotPassword function
$result = forgotPassword($email, $token, $new_password);

// 4.  Display the result
echo "<p>" . $result . "</p>";

?>
