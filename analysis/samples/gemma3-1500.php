

<?php

// ... (Database connection details - same as before)

// Function to reset password (using prepared statement)
function forgotPassword($email, $newPassword) {
  // 1. Connect to the database
  $conn = new mysqli($host, $username, $password, $database);

  // Check connection
  if ($conn->connect_error) {
    return "Connection failed: " . $conn->connect_error;
  }

  // 2. Prepare the SQL statement
  $sql = "UPDATE users SET password = ? WHERE email = ?";  // Use placeholders instead of direct variable substitution
  $stmt = $conn->prepare($sql);

  // 3. Bind the parameters
  $stmt->bind_param("s", $newPassword, $email);  // 's' indicates a string

  // 4. Execute the statement
  if ($stmt->execute()) {
    return "Password reset successful. Please check your email for further instructions.";
  } else {
    return "Error resetting password: " . $conn->error;
  }

  // 5. Close the connection
  $conn->close();

  // 6. Close the statement
  $stmt->close();
}


<?php

// Assuming you have a database connection established and a 'users' table with a 'email' column.
// Replace these with your actual database connection details and table name.

// Database configuration
$db_host = 'localhost';
$db_name = 'your_database_name';
$db_user = 'your_database_user';
$db_pass = 'your_database_password';

// Check if the reset_password_request parameter is set in the URL
if (isset($_GET['reset_password_request'])) {
  // 1. Validate the email
  $email = $_GET['reset_password_request'];
  if (empty($email)) {
    die("No email provided.");
  }

  // 2. Generate a unique, random token
  $token = bin2hex(random_bytes(32));

  // 3. Hash the token - important for security!
  $hashed_token = password_hash($token, PASSWORD_DEFAULT);

  // 4. Store the token in the database associated with the user's email.
  //  This is a simplified example.  In a real application, you'd likely 
  //  use a prepared statement to prevent SQL injection.
  try {
    // Using a prepared statement to prevent SQL injection.  This is crucial!
    $stmt = $db->prepare("INSERT INTO password_resets (email, token, expires_at) VALUES (?, ?, NOW())");
    $stmt->bind_param("ss", $email, $token);
    $stmt->execute();
  } catch (Exception $e) {
    // Handle database errors (e.g., email already exists)
    echo "Error inserting reset token: " . $e->getMessage();
    // Optionally, you could log this error for debugging
    exit;
  }

  // 5.  Send the reset password email (implementation omitted for brevity)
  //  This would involve using a mail function (like sendmail or a library) 
  //  to send an email with a link containing the token.
  //  Example link:  https://yourwebsite.com/reset_password.php?reset_password_request=$token

  // 6.  Success message
  echo "<p>A password reset link has been sent to your email address.</p>";
  echo "<p>You should receive an email within a few minutes.</p>";
  echo "<a href='login.php'>Back to Login</a>";

} else {
  // Handle the case where the reset_password_request parameter is not set
  echo "Invalid request.  Please try again.";
}

// Example of how to connect to your database
// (Use this or a similar method to connect to your database)
try {
    $db = new mysqli($db_host, $db_user, $db_pass, $db_name);

    if ($db->connect_error) {
        die("Database connection failed: " . $db->connect_error);
    }
} catch (Exception $e) {
    die("Database connection failed: " . $e->getMessage());
}


?>
