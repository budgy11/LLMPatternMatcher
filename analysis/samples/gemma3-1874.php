

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
