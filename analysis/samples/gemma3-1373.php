

<?php

// Assuming you have a database connection established (e.g., $db)
// and a table named 'users' with columns 'id', 'email', and 'password_hash'
// This is a simplified example; production code should implement more robust security measures.

// Function to handle forgot password requests
function forgot_password($email) {
  // Validate email (Basic check - expand for more rigorous validation)
  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    return "Invalid email address.";
  }

  // Check if the email exists in the user table
  $result = mysqli_query($db, "SELECT id, password_hash FROM users WHERE email = '$email'");

  if (mysqli_num_rows($result) == 0) {
    return "User with this email not found.";
  }

  // Generate a unique, random token for password reset
  $token = bin2hex(random_bytes(32));  // Using binary data for strong randomness

  // Hash the token for security
  $hashed_token = password_hash($token, PASSWORD_DEFAULT);

  // Store the token in the database associated with the user's email
  mysqli_query($db, "UPDATE users SET reset_token = '$hashed_token' WHERE email = '$email'");

  // Prepare an email to send to the user
  $to = $email;
  $subject = "Password Reset Request";
  $message = "To reset your password, please click on the following link: <a href='" . $_SERVER['PHP_SELF'] . "?reset_token=$token'</a>";
  $headers = "From: your_website@example.com"; // Replace with your email

  // Send the email (using a mail function or similar)
  mail($to, $message, $headers);

  return "Password reset email sent to $email.";
}

// Example usage (in a form or other input):
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $email = $_POST["email"];
  $reset_token = $_POST["reset_token"]; //  This should ideally be from the link, not directly submitted.

  if (isset($email) && isset($reset_token)) {
    $result = forgot_password($email);
    echo $result; // Display the result (e.g., success or error message)
  } else {
    echo "Please enter your email address.";
  }
}
?>
