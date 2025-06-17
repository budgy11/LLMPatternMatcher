
<!DOCTYPE html>
<html>
<head>
  <title>Login</title>
</head>
<body>

  <h2>Login</h2>

  <form action="login.php" method="post">
    Username: <input type="text" name="username" required><br><br>
    Password: <input type="password" name="password" required><br><br>
    <input type="submit" value="Login">
  </form>

  <p>
    Don't have an account? <a href="register.php">Register</a>
  </p>

</body>
</html>


    $stmt = $conn->prepare("SELECT id, username, password FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);  // "s" indicates a string
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
      // ...
    }
    $stmt->close();
    

*   **Password Hashing (Never Store Passwords in Plain Text):**  As mentioned above, *never* store passwords in plain text. Use `password_hash()` to create a secure hash of the password before storing it in the database.

    
    $password = $_POST["password"];
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Then, when verifying the password:
    if (password_verify($password, $row["password"])) {
        // ...
    }
    

*   **Rate Limiting:** Implement rate limiting to prevent brute-force attacks. Limit the number of login attempts from a single IP address within a certain time period.

*   **Input Validation and Sanitization:**  Validate and sanitize all user input on the server-side to prevent cross-site scripting (XSS) and other vulnerabilities.

*   **HTTPS:**  Always use HTTPS to encrypt communication between the browser and the server, protecting user credentials during transmission.

*   **Error Handling:** Implement robust error handling to prevent sensitive information from being exposed to the user.  Don't display detailed error messages to the user in a production environment.  Log errors for debugging purposes.

*   **Secure Session Management:**  Use secure session cookies (set the `secure` flag to `true`) and regenerate the session ID after login to prevent session fixation attacks.

**Register.php (Example for User Registration):**



<?php

// Database configuration (Replace with your actual details)
$db_host = "localhost";
$db_name = "your_database_name";
$db_user = "your_username";
$db_pass = "your_password";

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Get the login details from the form
  $username = $_POST["username"];
  $password = $_POST["password"];

  // Validate the input (Crucial for security!)
  if (empty($username) || empty($password)) {
    $error_message = "Username and password cannot be empty.";
  } else {
    // SQL query to authenticate the user
    $sql = "SELECT * FROM users WHERE username = '$username' AND password = '$password'";
    $result = mysqli_query($db_host, $sql);

    if (mysqli_num_rows($result) == 1) {
      // User found, set session variables
      $user_info = mysqli_fetch_assoc($result);
      $_SESSION["username"] = $username;
      $_SESSION["user_id"] = $user_info["id"]; // Assuming 'id' is the primary key

      // Redirect to a secure page or the homepage
      header("Location: /home.php"); // Change to your desired URL
      exit();
    } else {
      $error_message = "Invalid username or password.";
    }
  }
}

// Start the session
session_start();

?>
