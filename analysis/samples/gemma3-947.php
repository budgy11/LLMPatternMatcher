    Username: <input type="text" name="username" required>
    Password: <input type="password" name="password" required>
    <button type="submit">Login</button>
  </form>

</body>
</html>


<?php

// Database connection details (Replace with your actual details)
$servername = "localhost";
$username = "your_username";
$password = "your_password";
$dbname = "your_database_name";

// Function to handle login
function login($username, $password) {
  // Create connection
  $conn = new mysqli($servername, $username, $password, $dbname);

  // Check connection
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  // SQL query to fetch user data
  $sql = "SELECT * FROM users WHERE username = '$username' AND password = '$password'";
  $result = $conn->query($sql);

  if ($result->num_rows > 0) {
    // User found, set session variables
    $row = $result->fetch_assoc();
    $_SESSION["user_id"] = $row["id"]; // Assuming you have an 'id' column in your users table
    $_SESSION["username"] = $row["username"];
    return true; // Login successful
  } else {
    return false; // Login failed
  }

  $conn->close();
}


// Example Usage:

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Get username and password from form
  $username = $_POST["username"];
  $password = $_POST["password"];

  // Validate inputs (IMPORTANT - Never trust user input directly)
  if (empty($username) || empty($password)) {
    $error = "Username and Password cannot be empty.";
  } elseif (login($username, $password) === true) {
    // Login successful, redirect to a protected page or display a welcome message
    echo "Login successful!  Redirecting...";
    header("Location: protected_page.php"); // Redirect to your protected page
    exit();
  } else {
    // Login failed, display an error message
    $error = "Invalid username or password.";
  }
}
?>
