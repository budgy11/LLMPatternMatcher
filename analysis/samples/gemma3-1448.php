    <label for="email">Email:</label>
    <input type="email" id="email" name="email" required>
    <button type="submit">Reset Password</button>
  </form>

</body>
</html>


<?php

// Assuming you have a database connection established and a 'users' table
// with a 'password' and 'email' column.
// Adjust the database credentials and table/column names to match your setup.

// Database connection details (replace with your actual values)
$host = "localhost";
$username = "your_db_username";
$password = "your_db_password";
$database = "your_database_name";

// Function to reset password
function forgotPassword($email, $host, $username, $password, $database) {
  // 1. Connect to the database
  $conn = new mysqli($host, $username, $password, $database);

  if ($conn->connect_error) {
    // Handle the connection error (e.g., log it, display an error message)
    die("Connection failed: " . $conn->connect_error);
  }

  // 2. Check if the email exists
  $query = "SELECT id, email FROM users WHERE email = ?";
  $stmt = $conn->prepare($query);
  $stmt->bind_param("s", $email);
  $stmt->execute();
  $result = $stmt->get_result();

  if ($result->num_rows == 0) {
    $stmt->close();
    $conn->close();
    return "Email not found.";
  }

  $user_data = $result->fetch_assoc();
  $stmt->close();

  // 3. Generate a unique token (e.g., UUID)
  $token = uniqid();

  // 4. Update the user's record with the token
  $query = "UPDATE users SET password_reset_token = ? WHERE id = ?";
  $stmt = $conn->prepare($query);
  $stmt->bind_param("ss", $token, $user_data['id']);
  $stmt->execute();

  if ($stmt->affected_rows === 0) {
    $stmt->close();
    $conn->close();
    return "Failed to generate reset token.  Please try again.";
  }

  $stmt->close();

  // 5. Send an email with a reset link
  $reset_link = "http://yourdomain.com/reset_password?token=$token"; // Replace your domain
  $to = $email;
  $subject = "Password Reset";
  $message = "Click this link to reset your password: " . $reset_link;
  $headers = "From: your_email@example.com\r
";

  if (mail($to, $subject, $message, $headers)) {
    $stmt->close();
    $conn->close();
    return "Password reset email sent to $email.";
  } else {
    $stmt->close();
    $conn->close();
    return "Failed to send password reset email.";
  }
}

// Example Usage (From a form submission - handle form validation here!)
// $email = $_POST['email']; // Get email from the form
// $result = forgotPassword($email, $host, $username, $password, $database);
// echo $result; // Display the result (e.g., success or error message)

?>
