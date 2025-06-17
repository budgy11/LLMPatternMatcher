        Username: <input type="text" name="username" placeholder="your_email@example.com" required>
        Password: <input type="password" name="password" required>
        <button type="submit">Login</button>
    </form>

    <p>Don't have an account? <a href="register.php">Register</a></p>
</body>
</html>


<?php

// Database configuration (replace with your actual values)
$db_host = "localhost";
$db_user = "your_db_user";
$db_password = "your_db_password";
$db_name = "your_db_name";

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

  // Get username and password from the form
  $username = $_POST["username"];
  $password = $_POST["password"];

  // Database connection
  $conn = new mysqli($db_host, $db_user, $db_password, $db_name);

  // Check connection
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  // SQL query to authenticate the user
  $sql = "SELECT * FROM users WHERE username = '$username' AND password = '$password'";
  $result = $conn->query($sql);

  // Check if the query returned any results
  if ($result->num_rows > 0) {
    // User found, set session variables to keep them logged in
    session_start(); // Important: Start the session
    $_SESSION["username"] = $username;  // Store the username in the session
    // Optionally, store other user data in the session too
    // $_SESSION["user_id"] = $row["user_id"];

    // Redirect the user to a protected page
    header("Location: protected_page.php"); // Replace with your protected page URL
    exit(); // Important: Stop further script execution
  } else {
    // User not found
    echo "Invalid username or password.";
  }

  // Close the database connection
  $conn->close();
}
?>
