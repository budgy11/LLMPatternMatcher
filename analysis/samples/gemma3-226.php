    Username: <input type="text" name="username" value="<?php echo isset($_POST['username']) ? htmlspecialchars($_POST['username']) : ''; ?>">
    Password: <input type="password" name="password">
    <button type="submit">Login</button>
  </form>

  <p>Don't have an account? <a href="register.php">Register</a></p>  <!-- Replace with your registration page -->

</body>
</html>


<?php

// Database credentials (replace with your actual credentials)
$servername = "localhost";
$username = "your_username";
$password = "your_password";
$dbname = "your_database_name";

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Get the login details from the form
  $username = $_POST["username"];
  $password = $_POST["password"];

  // Connect to the database
  $conn = new mysqli($servername, $username, $password, $dbname);

  // Check connection
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  // SQL query to verify credentials
  $sql = "SELECT * FROM users WHERE username = '$username' AND password = '$password'";
  $result = $conn->query($sql);

  // Check if the user exists
  if ($result->num_rows == 1) {
    // User found, set session variables
    session_start();
    $user_row = $result->fetch_assoc();
    $_SESSION["username"] = $user_row["username"];
    $_SESSION["user_id"] = $user_row["id"]; // Assuming you have an ID column in your table

    // Redirect to a secure page or display a success message
    header("Location: secure_page.php"); // Replace with your secure page
    exit;
  } else {
    // User not found
    echo "Invalid username or password.";
  }

  $conn->close();
}
?>
