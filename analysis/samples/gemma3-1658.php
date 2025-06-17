

<?php

// Database connection details (Replace with your actual credentials)
$db_host = "localhost";
$db_name = "your_database_name";
$db_user = "your_db_user";
$db_password = "your_db_password";

// Function to reset password
function forgotPassword($email)
{
  // 1. Validate Email (Basic)
  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    return "Invalid email address.";
  }

  // 2. Generate a Unique Token
  $token = bin2hex(random_bytes(32)); // Use a strong random token

  // 3. Hash the Token - IMPORTANT for security
  $hashed_token = password_hash($token, PASSWORD_DEFAULT); 

  // 4.  Create a Reset Token Record in the Database
  try {
    $pdo = new PDO("mysql:host=$db_host;dbname=$db_name;charset=utf8", $db_user, $db_password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $stmt = $pdo->prepare("INSERT INTO password_resets (email, token, reset_code, expires_at) VALUES (?, ?, ?, NOW())");
    $stmt->execute([$email, $hashed_token, time() + (24 * 60 * 60)]); // Token expires after 24 hours.  Adjust as needed.
    $reset_id = $pdo->lastInsertId();

    // 5.  Send the Reset Link (Email) -  This is where you'd integrate with an email library
    // For demonstration purposes, we'll just print the link.  Replace with your email sending code.
    $reset_link = "http://localhost/reset_password.php?id=$reset_id&token=$token"; // Replace with your URL
    echo "A password reset link has been sent to $email.<br>";
    echo "Click the link to reset your password.";
    echo "<br> Reset Link:  " . $reset_link;

  } catch (PDOException $e) {
    return "Error: " . $e->getMessage();
  }
}


// Example Usage (From a form submission, for instance)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST["email"];
    $reset_message = forgotPassword($email);
    echo $reset_message;
} else {
    // Display form (for demonstration)
    echo "<form method='post' action=''>";
    echo "<label for='email'>Email:</label><br>";
    echo "<input type='email' id='email' name='email'><br><br>";
    echo "<input type='submit' value='Forgot Password'>";
    echo "</form>";
}

?>
