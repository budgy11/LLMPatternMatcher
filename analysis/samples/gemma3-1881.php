

<?php

// Replace with your database connection details
$host = "localhost";
$username = "your_db_username";
$password = "your_db_password";
$database = "your_database_name";

// Check if the reset token is present in the query string
if (isset($_GET['token'])) {
  $token = $_GET['token'];

  // 1. Validate the token
  if (!isValidToken($token)) {
    echo "Invalid reset token.";
    exit;
  }

  // 2. Retrieve the user's email from the database based on the token
  $user = getUserByToken($token);

  if ($user) {
    // 3. Generate a new, unique reset token and send an email with a link
    $newToken = generateUniqueToken();
    $email = $user['email'];

    //  --  Email Sending Logic --
    //  Replace with your email sending library/function
    $subject = "Password Reset - Your Account";
    $message = "Click on this link to reset your password: " . base_url() . "/reset-password?token=" . $newToken;
    $headers = "From: your_email@example.com" . "\r
"; // Replace with your email address
    mail($email, $message, $headers);

    // 4.  Update the user's record with the new token (optional, but good practice)
    updateUserToken($user['id'], $newToken);

    echo "Reset link has been sent to your email.";
  } else {
    echo "User not found with that token.";
  }
} else {
  echo "Please provide a reset token.";
}


// --- Helper Functions ---

// 1. Validate the token
function isValidToken($token) {
    // Implement your token validation logic here. 
    // This could involve checking against a database table 
    // that stores used tokens and their expiration times.

    // Example: (Replace with your actual validation)
    return true; //  Placeholder -  Replace with your actual validation
}


// 2. Retrieve the user by token
function getUserByToken($token) {
  global $host, $username, $password, $database;

  // Database connection
  $conn = new mysqli($host, $username, $password, $database);

  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  // SQL Query
  $sql = "SELECT * FROM users WHERE reset_token = '$token'"; // Assuming 'reset_token' column in your users table

  $result = $conn->query($sql);

  if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
    return $user;
  } else {
    return null;
  }

  $conn->close();
}



// 3. Generate a unique token
function generateUniqueToken() {
  return bin2hex(random_bytes(32)); // Returns a 32-byte random string.
}


// 4. Update the user's token (optional, but recommended)
function updateUserToken($userId, $newToken) {
    global $host, $username, $password, $database;

    $conn = new mysqli($host, $username, $password, $database);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $sql = "UPDATE users SET reset_token = '$newToken' WHERE id = $userId";
    if ($conn->query($sql) === TRUE) {
        //echo "User token updated successfully";
    } else {
        echo "Error updating token: " . $conn->error;
    }

    $conn->close();
}


// Example base_url function (requires you to define it)
//  This assumes you are using URL rewriting.
function base_url() {
    // Adjust this based on your application setup.
    return "http://localhost/your_project_name/";
}

?>
