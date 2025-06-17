        Username: <input type="text" name="username" <?php if (isset($error_message) && $error_message == "Username not found.") { echo "required"; } ?>><br><br>
        Password: <input type="password" name="password"><br><br>
        <input type="submit" value="Login">
    </form>

    <p>Don't have an account? <a href="registration.php">Register</a></p>
</body>
</html>


<?php

// Database connection details (replace with your actual details)
$db_host = "localhost";
$db_name = "your_database_name";
$db_user = "your_username";
$db_pass = "your_password";

// Check if the login form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Get username and password from the form
  $username = $_POST["username"];
  $password = $_POST["password"];

  // Validate the input (very important!)
  if (empty($username) || empty($password)) {
    $error[] = "Username and Password are required.";
  }

  // Database query (use prepared statements to prevent SQL injection)
  $stmt = $conn->prepare("SELECT id, username, password FROM users WHERE username = ?");
  $stmt->bind_param("s", $username); // 's' indicates a string
  $stmt->execute();
  $result = $stmt->get_result();

  if ($result->num_rows == 0) {
    $error[] = "Invalid username or password.";
  } else {
    // Fetch the user data
    $user = $result->fetch_assoc();

    // Verify the password
    if (password_verify($password, $user["password"])) {
      // Successful login
      // You can set session variables here to store the user's ID or username.
      // For example:
      session_start();
      $_SESSION["user_id"] = $user["id"];
      $_SESSION["username"] = $username;

      // Redirect to a secure page or display a welcome message
      header("Location: secure_page.php"); // Replace 'secure_page.php' with your secure page
      exit();
    } else {
      $error[] = "Incorrect password.";
    }
  }
}

// Start the session (if not already started)
session_start();


// Display the login form
?>
