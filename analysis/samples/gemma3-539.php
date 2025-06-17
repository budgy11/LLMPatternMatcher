        Username: <input type="text" name="username" />
        Password: <input type="password" name="password" />
        Confirm Password: <input type="password" name="confirm_password" />
        <button type="submit">Register</button>
    </form>

    <p>Already have an account? <a href="login.php">Login</a></p>

</body>
</html>


<?php

// Database credentials (replace with your actual credentials)
$servername = "localhost";
$username = "your_username";
$password = "your_password";
$database = "your_database";

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Get the username and password from the form
  $username = $_POST["username"];
  $password = $_POST["password"];

  // Connect to the database
  $conn = new mysqli($servername, $username, $password, $database);

  // Check connection
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  // SQL query to check username and password
  $sql = "SELECT * FROM users WHERE username = '$username' AND password = '$password'";

  $result = $conn->query($sql);

  if ($result->num_rows == 1) {
    // User found, set session variables and redirect
    session_start();
    $user_row = $result->fetch_assoc();

    // Store the username in the session
    $_SESSION["username"] = $user_row["username"];

    // You can store other user data in the session as needed
    // $_SESSION["user_id"] = $user_row["id"];

    // Redirect to a protected page or the homepage
    header("Location: /protected_page.php"); // Replace with the actual URL of the protected page
    exit();

  } else {
    // User not found
    echo "Invalid username or password.";
  }

  $conn->close();
}
?>
