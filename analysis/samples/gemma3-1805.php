

<?php

// Database credentials (replace with your actual values)
$host = "localhost";
$username = "your_username";
$password = "your_password";
$database = "your_database";

// Function to reset password
function forgot_password($email) {
  // 1. Check if the email exists in the users table
  $conn = new mysqli($host, $username, $password, $database);

  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  $query = "SELECT id, email FROM users WHERE email = '$email'";
  $result = $conn->query($query);

  if ($result->num_rows > 0) {
    $user_data = $result->fetch_assoc();
    $userId = $user_data['id'];
    $userEmail = $user_data['email'];

    // 2. Generate a unique token for the password reset
    $token = bin2hex(random_bytes(32)); // Generate a strong, random token

    // 3. Store the token and user ID in the database (temporarily)
    $insert_query = "INSERT INTO password_resets (user_id, token, expires_at) VALUES ('$userId', '$token', NOW())";

    if ($conn->query($insert_query) === TRUE) {
      // 4. Send an email with a link to reset the password
      $to = $userEmail;
      $subject = "Password Reset";
      $message = "Click on the following link to reset your password: " . "<a href='" . $_SERVER['PHP_SELF'] . "?reset=$token'>" . $_SERVER['PHP_SELF'] . "?reset=$token</a>";
      $headers = "From: your_email@example.com" . "\r
";
      mail($to, $message, $headers);

      echo "Password reset email sent to $userEmail.  Check your inbox!";

    } else {
      echo "Error inserting token into password_resets table.  Please contact support.";
    }
  } else {
    echo "Email not found.";
  }

  $conn->close();
}

// Handle the password reset request (if called from a form submission)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $token = $_POST["token"];

  //  Validate the token (very important security step!)
  //  You should compare this token with the one stored in the database.
  //  This simple example doesn't validate, but you MUST do it in a production environment.
  //  To properly validate, you'll need a query like:
  //  SELECT id, email FROM password_resets WHERE token = '$token' AND expires_at > NOW()
  //  and then check if the query returns any results.  If it does, it means the token is valid and the user can reset their password.

  // Example validation -  This is HIGHLY simplified and vulnerable in production!
  // In a real application,  YOU MUST perform a proper database query to verify the token!
  if (trim($token)) {
    //  This is just for demonstration purposes and is extremely insecure.
    //  DO NOT use this in a real application.

    //  **IMPORTANT:** You MUST perform a database query to validate the token and check its expiration time.
    //  This example doesn't validate properly and is for illustration only.


    // 1. Find the user ID associated with the token
    // 2.  Check if the token is still valid (expires_at > NOW())
    // 3.  If valid, you can proceed with updating the user's password.

    echo "Token Received.  Please complete proper validation and password reset steps in a production environment.";

  } else {
    echo "Invalid token.";
  }
}


?>
