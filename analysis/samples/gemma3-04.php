        Username: <input type="text" name="username" required><br><br>
        Password: <input type="password" name="password" required><br><br>
        <input type="submit" value="Login">
    </form>

    <p>Don't have an account? <a href="register.php">Register</a></p>  <!--  Link to registration page -->

</body>
</html>


$stmt = $conn->prepare("SELECT * FROM users WHERE username = ? AND password = ?");
$stmt->bind_param("ss", $username, $password);  // "ss" means two strings
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
  // ...
}
$stmt->close();


// Example usage (on registration or login):
$password = $_POST["password"];
$hashed_password = password_hash($password, PASSWORD_BCRYPT); // Or PASSWORD_ARGON2I

// ... store $hashed_password in your database

// To verify the password during login:
$password = $_POST["password"];
if (password_verify($password, $hashed_password)) {
  // Password is correct
} else {
  // Incorrect password
}


<?php

// Database credentials - **IMPORTANT:  Never hardcode this in a production environment!**
$host = "localhost";
$username = "your_db_username";
$password = "your_db_password";
$database = "your_db_name";

// Function to handle login
function loginUser($username, $password) {
  // Connect to the database
  $conn = new mysqli($host, $username, $password, $database);

  // Check connection
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  // SQL query to authenticate the user
  $sql = "SELECT * FROM users WHERE username = '$username' AND password = '$password'";
  $result = $conn->query($sql);

  if ($result->num_rows > 0) {
    // User found!
    $row = $result->fetch_assoc();
    // Set session variables to store user information
    $_SESSION['user_id'] = $row['id'];
    $_SESSION['username'] = $username;
    // You might want to store other user data like roles or permissions here.

    // Redirect the user to a secure page (e.g., dashboard)
    header("Location: dashboard.php");  // Replace 'dashboard.php' with your desired secure page
    exit(); // Important to stop further execution after redirect

  } else {
    // User not found
    return false;  // Indicate login failed
  }
}

// Handle login form submission (example)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $username = $_POST["username"];
  $password = $_POST["password"];

  // Validate the input (IMPORTANT for security!)
  if (empty($username) || empty($password)) {
    echo "Username and password cannot be empty.";
  } else {
    loginUser($username, $password);
  }
}
?>
