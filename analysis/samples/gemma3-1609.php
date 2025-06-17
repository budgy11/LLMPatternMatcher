

<?php

// Assuming you have a database connection established and a 'users' table with an 'email' and 'password' column.
// Replace these placeholders with your actual database details.

// Database credentials
$db_host = 'localhost';
$db_name = 'your_database_name';
$db_user = 'your_database_user';
$db_pass = 'your_database_password';

// Function to handle the forgot password process
function forgotPassword($email) {
  // 1. Check if the email exists in the database
  $conn = new mysqli($db_host, $db_user, $db_pass, $db_name);
  if ($conn->connect_error) {
    // Handle connection error
    die("Connection failed: " . $conn->connect_error);
  }

  $sql = "SELECT id, password FROM users WHERE email = '$email'";
  $result = $conn->query($sql);

  if ($result->num_rows > 0) {
    $user_id = $result->fetch_assoc()['id'];
    $hashed_password = $result->fetch_assoc()['password']; // Important:  Get the *hashed* password

    // 2. Generate a temporary password and store it in the database
    $temp_password = generateTemporaryPassword();
    $conn->query("UPDATE users SET temporary_password = '$temp_password' WHERE id = '$user_id'");

    // 3. Send the temporary password to the user
    $subject = "Temporary Password for Your Account";
    $message = "Your temporary password is: $temp_password.  Please reset your password as soon as possible.";
    $headers = "From: your_email@example.com";  // Replace with your email

    mail($email, $message, $headers);

    return true;
  } else {
    return false; // Email not found
  }

  $conn->close();
}

// Helper function to generate a temporary password
function generateTemporaryPassword() {
  $length = 12;
  $characters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*';
  $password = '';
  $charactersLength = strlen($characters);
  for ($i = 0; $i < $length; $i++) {
    $random = random_int($charactersLength);
    $password .= $characters[$random];
  }
  return $password;
}


// Example Usage: (This should be handled by a form submission)
if (isset($_POST['email'])) {
  $email = $_POST['email'];

  if (isset($_POST['reset_password'])) {
    if (forgotPassword($email)) {
      echo "<p>A temporary password has been sent to your email address.</p>";
    } else {
      echo "<p>Email not found or an error occurred while sending the password.</p>";
    }
  } else {
     echo "<form method='post' action=''>";
     echo "<label for='email'>Email:</label><input type='email' id='email' name='email' required><br>";
     echo "<button type='submit' name='reset_password'>Reset Password</button>";
     echo "</form>";
  }

} else {
   echo "<form method='post' action=''>";
   echo "<label for='email'>Email:</label><input type='email' id='email' name='email' required><br>";
   echo "<button type='submit' name='reset_password'>Reset Password</button>";
   echo "</form>";
}

?>
