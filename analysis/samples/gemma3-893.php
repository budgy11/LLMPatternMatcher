
    <form action="login.php" method="post">
        <label for="username">Username:</label>
        <input type="text" id="username" name="username" required>

        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required>

        <button type="submit">Login</button>
    </form>

    <p>New user? <a href="register.php">Register</a></p>

</body>
</html>


   $stmt = mysqli_prepare($db_host, "SELECT * FROM users WHERE username = ? AND password = ?");
   mysqli_stmt_bind_param($stmt, "ss", $username, $password); // "ss" means two string parameters
   mysqli_stmt_execute($stmt);
   $result = mysqli_stmt_get_result($stmt);
   mysqli_stmt_close($stmt);
   
   Prepared statements separate the SQL code from the user data, preventing attackers from injecting malicious code.

* **Password Hashing:** **Never store passwords in plain text.**  Always hash them using a strong hashing algorithm (e.g., bcrypt, Argon2) before storing them in the database.  PHP's `password_hash()` and `password_verify()` functions are designed for this.

   
   // During registration:
   $hashed_password = password_hash($password, PASSWORD_DEFAULT);
   mysqli_query($db_host, "INSERT INTO users (username, password) VALUES ('$username', '$hashed_password')");

   // During login:
   $hashed_password = mysqli_fetch_assoc($result)['password'];
   if (password_verify($password, $hashed_password)) {
       // Login successful
   }
   

* **Input Validation (Beyond Empty Checks):**  Validate that the username and password meet certain criteria (e.g., minimum length, allowed characters).

* **Session Management:**  Secure your sessions.  Use HTTPS (SSL/TLS) to encrypt communication between the browser and the server.  Consider using a more robust session management system than PHP's built-in session.

* **Error Handling and Logging:**  Implement proper error handling and logging to help you troubleshoot problems and detect potential security breaches.

* **HTTPS:**  Always use HTTPS to encrypt all communication between the browser and your server.  This protects user credentials and other sensitive data.

* **Rate Limiting:**  Implement rate limiting to prevent brute-force attacks (where an attacker tries to guess passwords repeatedly).

**`welcome.php` (Example):**



<?php

// Database credentials (Replace with your actual database details)
$host = "localhost";
$username = "your_username";
$password = "your_password";
$database = "your_database";

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

  // Get username and password from the form
  $username = $_POST["username"];
  $password = $_POST["password"];

  // Validate the input (Important for security)
  if (empty($username) || empty($password)) {
    $error = "Username and Password cannot be empty.";
  } elseif (!filter_var($username, FILTER_VALIDATE_EMAIL)) {
    $error = "Invalid email format.";
  } else {
    // SQL query to select the user
    $sql = "SELECT id, username, password FROM users WHERE username = '$username'";
    $result = mysqli_query($host, $sql);

    if ($result) {
      // Check if a user was found
      if (mysqli_num_rows($result) > 0) {
        // Fetch the user data
        $user = mysqli_fetch_assoc($result);

        // Verify the password
        if (password_verify($password, $user["password"])) {
          // Successful login - Set a session variable
          session_start();
          $_SESSION["user_id"] = $user["id"];
          $_SESSION["username"] = $user["username"];
          echo "Login successful!";
          header("Location: welcome.php"); // Redirect to a welcome page
          exit();
        } else {
          $error = "Incorrect password.";
        }
      } else {
        $error = "User not found.";
      }
    } else {
      $error = "Database error.";
    }
  }
}
?>
