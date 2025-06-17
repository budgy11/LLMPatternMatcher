    Username: <input type="text" name="username" required>
    Password: <input type="password" name="password" required>
    <button type="submit">Register</button>
  </form>

  <br>
  <a href="login.php">Already have an account? Log in here.</a>

</body>
</html>


<?php

// Database Configuration (Replace with your actual credentials)
$host = "localhost";
$username = "your_db_username";
$password = "your_db_password";
$database = "your_db_name";

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Get username and password from the form
  $username = $_POST["username"];
  $password = $_POST["password"];

  // Validate input (Crucial for security!)
  if (empty($username) || empty($password)) {
    $error_message = "Username and password cannot be empty.";
  } else {
    // Database connection
    $conn = new mysqli($host, $username, $password, $database);

    // Check connection
    if ($conn->connect_error) {
      die("Connection failed: " . $conn->connect_error);
    }

    // SQL Query to authenticate the user
    $sql = "SELECT * FROM users WHERE username = '$username' AND password = '$password'";
    $result = $conn->query($sql);

    if ($result->num_rows == 1) {
      // User found, set session variables
      session_start(); // Start a session (important for maintaining user info)
      $user_row = $result->fetch_assoc();
      $_SESSION["username"] = $user_row["username"];
      $_SESSION["id"] = $user_row["id"]; // Assuming you have an ID column in your users table

      // Redirect to a protected page or display a success message
      header("Location: protected_page.php"); // Replace with your protected page
      exit();

    } else {
      $error_message = "Invalid username or password.";
    }
  }
}
?>
