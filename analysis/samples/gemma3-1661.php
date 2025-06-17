
<!-- HTML Form for Requesting Password Reset -->
<form action="" method="post">
  <label for="email">Email:</label>
  <input type="email" id="email" name="email" required>
  <button type="submit">Request Password Reset</button>
</form>

<!-- Reset Password Page (reset.php -  This needs to be in a separate file) -->
<form action="reset.php" method="get">
  <label for="token">Reset Token:</label>
  <input type="text" id="token" name="token" required>
  <button type="submit">Reset Password</button>
</form>


<?php

// Database connection details (Replace with your actual credentials)
$host = "localhost";
$username = "your_username";
$password = "your_password";
$dbname = "your_database_name";

// Function to reset password
function forgotPassword($email, $host, $username, $password, $dbname) {
  try {
    // Connect to the database
    $conn = new mysqli($host, $username, $password, $dbname);

    // Check connection
    if ($conn->connect_error) {
      throw new Exception("Connection failed: " . $conn->connect_error);
    }

    // Generate a random token for verification
    $token = bin2hex(random_bytes(32));

    // Store the token and expiration date in the database
    $sql = "INSERT INTO forgot_passwords (email, token, expires) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $email, $token, time() + 3600); // Expire after 1 hour
    $stmt->execute();

    // Send password reset email (Implementation details omitted for brevity)
    $subject = "Password Reset";
    $message = "Click this link to reset your password: " . "<a href='" . $_SERVER['PHP_SELF'] . "?reset=" . $token . "'>" . $_SERVER['PHP_SELF'] . "?reset=" . $token . "</a>";
    $headers = "From: your_email@example.com";

    mail($email, $subject, $message, $headers); 

    // Success
    return true;

  } catch (Exception $e) {
    // Error handling
    error_log("Forgot Password Error: " . $e->getMessage());
    return false;
  } finally {
    // Close the connection (important!)
    if ($conn) {
      $conn->close();
    }
  }
}


// Example usage (from your form submission)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $email = $_POST["email"];

  if (!empty($email)) {
    $reset = forgotPassword($email, $host, $username, $password, $dbname);

    if ($reset) {
      echo "Password reset email sent to " . $email . ".  Check your inbox.";
    } else {
      echo "Failed to send password reset email.  Please try again.";
    }
  } else {
    echo "Please enter your email address.";
  }
}

?>
