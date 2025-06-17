

<?php

// Database configuration (replace with your actual values)
$db_host = "localhost";
$db_name = "your_database_name";
$db_user = "your_database_user";
$db_pass = "your_database_password";

// Function to validate username and password
function validate_credentials($username, $password) {
  // Sanitize inputs to prevent SQL injection
  $username = trim($username);
  $password = trim($password);

  // Check if username and password are not empty
  if (empty($username) || empty($password)) {
    return false;
  }

  //  In a real application, you would hash the password here
  //  for security reasons.  This example uses plain text
  //  for simplicity.  Never store passwords in plain text in a production environment.
  //  Consider using a password hashing library like password_hash()
  //  and password_verify().

  //  For this example, we'll compare the password directly.
  return ($password == "your_password"); // Replace "your_password" with the actual password
}


// Function to handle user login
function login($username, $password) {
  if (validate_credentials($username, $password)) {
    // User credentials are valid.  You can store a session ID here.
    session_start();
    $_SESSION['username'] = $username;
    return true;
  } else {
    return false;
  }
}

// Example usage -  This section would typically be in a separate HTML form
//  and handled by the form submission.  This is just for demonstration.

if (isset($_POST['username']) && isset($_POST['password'])) {
  $username = $_POST['username'];
  $password = $_POST['password'];

  if (login($username, $password)) {
    echo "Login successful!  You are now logged in.";
    // Redirect to a protected page or display a welcome message
    header("Location: /protected_page.php"); // Replace with your protected page URL
    exit();
  } else {
    echo "Invalid username or password.";
  }
}

?>
