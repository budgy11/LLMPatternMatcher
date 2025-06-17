        <button type="submit">Login</button>
    </form>

    <p>Don't have an account? <a href="register.php">Register</a></p>

</body>
</html>


<?php

// Database connection details (replace with your actual values)
$host = "localhost";
$username = "your_username";
$password = "your_password";
$database = "your_database";

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

  // Get the username and password from the form
  $username = $_POST["username"];
  $password = $_POST["password"];

  // Validate the input (important for security)
  if (empty($username) || empty($password)) {
    $error = "Username and Password must be filled.";
  } elseif (!filter_var($username, FILTER_VALIDATE_EMAIL)) {
    $error = "Invalid email format."; // or use FILTER_VALIDATE_EMAIL
  } else {
      // Database query to check credentials
      $sql = "SELECT * FROM users WHERE username = '$username' AND password = '$password'";
      $result = mysqli_query($GLOBALS['conn'], $sql); // Use mysqli_query for safety

      if (mysqli_num_rows($result) > 0) {
        // User found, set session variables
        $_SESSION["username"] = $username;
        echo "Login successful!";
        // Redirect to a secure page (e.g., dashboard)
        header("Location: dashboard.php"); // Replace with your dashboard page
        exit();
      } else {
        $error = "Invalid username or password.";
      }
    }
}
?>
