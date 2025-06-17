    Username: <input type="text" name="username" />
    Password: <input type="password" name="password" />
    <input type="submit" value="Login" />
  </form>

</body>
</html>


   $password = $_POST["password"];
   $hashed_password = password_hash($password, PASSWORD_DEFAULT);
   $sql = "INSERT INTO users (username, password) VALUES ('$username', '$hashed_password')";
   
   Then, in the login function, you'd retrieve the hashed password from the database and use `password_verify()` to compare it with the user's input.
4. **Security Considerations (Beyond this example):**
   * **HTTPS:** Always use HTTPS to encrypt the data transmitted between the user's browser and your server.
   * **Rate Limiting:** Implement rate limiting to prevent brute-force attacks.
   * **Regular Security Audits:** Regularly review your code and server configuration for vulnerabilities.
   * **Strong Password Policies:** Enforce strong password policies.

Example with Hashing (Illustrative - Adapt to your specific hashing function):



<?php

// Database connection details (Replace with your actual credentials)
$host = "localhost";
$username = "your_db_username";
$password = "your_db_password";
$database = "your_database_name";

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Get username and password from the form
  $username = trim($_POST["username"]);
  $password = trim($_POST["password"]);

  // Validate input (Important for security)
  if (empty($username) || empty($password)) {
    $error = "Username and Password cannot be empty.";
  } elseif (!filter_var($username, FILTER_VALIDATE_EMAIL) && !preg_match('/^[a-zA-Z0-9]+$/', $username)) {
    $error = "Invalid username. Please enter a valid email or alphanumeric username.";
  } else {
     // SQL query to authenticate the user
    $sql = "SELECT * FROM users WHERE username = '$username' AND password = '$password'";
    $result = mysqli_query($host, $sql);

    if (mysqli_num_rows($result) > 0) {
      // User found, set session variables
      session_start();
      $_SESSION["username"] = $username;
      echo "Login successful! You are now logged in as " . htmlspecialchars($username);
      // Redirect to a protected page or display a welcome message
      header("Location: protected_page.php"); // Replace with your desired destination
      exit;
    } else {
      $error = "Invalid username or password.";
    }
  }
}
?>
