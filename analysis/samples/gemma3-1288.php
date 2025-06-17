
<!DOCTYPE html>
<html>
<head>
    <title>Forgot Password</title>
</head>
<body>

    <h1>Forgot Password</h1>

    <form method="post" action="">
        <label for="email">Enter your email address:</label>
        <input type="email" id="email" name="email" required>
        <button type="submit">Reset Password</button>
    </form>

</body>
</html>


<?php

// Database credentials (replace with your actual values)
$host = 'localhost';
$username = 'your_db_username';
$password = 'your_db_password';
$database = 'your_db_name';

// Function to handle password reset requests
function forgot_password($email) {
  // 1. Validate Email
  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    return "Invalid email address.";
  }

  // 2. Check if the user exists
  $conn = new mysqli($host, $username, $password, $database);

  if ($conn->connect_error) {
    return "Connection failed: " . $conn->connect_error;
  }

  $query = "SELECT id, email FROM users WHERE email = '$email'";
  $result = $conn->query($query);

  if ($result->num_rows > 0) {
    $user_data = $result->fetch_assoc();
    $userId = $user_data['id'];
    $hashedToken = generate_token(); // Generate a unique token

    // 3. Generate a Password Reset Token (Securely!)
    //  Important:  Never store plain passwords. Use hashing.
    //  A token is a temporary, unique identifier.

    // 4.  Store the Token in the Database (for security!)
    $query = "UPDATE users SET password_reset_token = '$hashedToken' WHERE id = $userId";
    if ($conn->query($query) === TRUE) {
      // 5.  Email the user a link with the token
      $reset_link = "http://yourwebsite.com/reset_password.php?token=" . $hashedToken;
      $subject = "Password Reset Request";
      $message = "Please click the following link to reset your password: " . $reset_link;
      $headers = "From: yourwebsite@example.com"; // Replace with your email address

      mail($email, $message, $headers);

      return "Password reset email sent to $email.";

    } else {
      return "Error updating user data: " . $conn->error;
    }
  } else {
    return "User with email '$email' not found.";
  }

  $conn->close();
}

// Example Usage (This would typically be in a form submission handler)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $email = $_POST["email"];
  $resetMessage = forgot_password($email);
  echo $resetMessage;
}
?>
