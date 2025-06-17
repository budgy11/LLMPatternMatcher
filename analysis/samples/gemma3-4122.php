
<!DOCTYPE html>
<html>
<head>
  <title>Registration Form</title>
</head>
<body>

  <h1>Register</h1>

  <form action="register.php" method="POST">
    <label for="username">Username:</label>
    <input type="text" id="username" name="username" required><br><br>

    <label for="password">Password:</label>
    <input type="password" id="password" name="password" required><br><br>

    <label for="email">Email:</label>
    <input type="email" id="email" name="email" required><br><br>

    <input type="submit" value="Register">
  </form>

</body>
</html>


<?php

// Include the database connection file (assuming you have one)
require_once 'db_config.php'; // Replace with your actual file name

/**
 * Registers a new user.
 *
 * @param string $username
 * @param string $password
 * @param string $email
 * @return bool True on success, false on failure.
 */
function registerUser($username, $password, $email) {
  // Input validation - crucial for security!
  if (empty($username) || empty($password) || empty($email)) {
    return false;
  }

  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    return false;
  }

  // Hash the password - NEVER store passwords in plain text!
  $hashed_password = password_hash($password, PASSWORD_DEFAULT);


  // Sanitize inputs to prevent SQL injection
  $username = mysqli_real_escape_string($conn, $username);
  $email = mysqli_real_escape_string($conn, $email);

  // Prepare and execute the SQL query
  $query = "INSERT INTO users (username, password, email) VALUES ('$username', '$hashed_password', '$email')";

  $result = mysqli_query($conn, $query);

  if ($result) {
    return true;
  } else {
    // Handle the error - log it, display an error message, etc.
    error_log("Error registering user: " . mysqli_error($conn));
    return false;
  }
}

// Example Usage (This would normally be part of a form submission handler)
//  This is just for demonstration
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $password = $_POST["password"];
    $email = $_POST["email"];

    if (registerUser($username, $password, $email)) {
        echo "Registration successful!  You have been redirected.";
        // Redirect to a success page or home page
        header("Location: success.php");
        exit;
    } else {
        echo "Registration failed. Please try again.";
    }
}


?>
