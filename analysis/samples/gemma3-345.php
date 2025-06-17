    <br><br>
    <button type="submit">Login</button>
  </form>

</body>
</html>


// ... (rest of the form handling code) ...

// After the user submits the form:
$username = $_POST["username"];
$password = $_POST["password"];

// Hash the password
$hashed_password = password_hash($password, PASSWORD_DEFAULT);

// SQL query to check credentials
$sql = "SELECT * FROM users WHERE username = '$username' AND password = '$hashed_password'";  // Now use the hashed password
$result = $conn->query($sql);


<?php
session_start(); // Start the session

/**
 * User Login Function
 *
 * @param string $username The username to login.
 * @param string $password The password for the user.
 * @return bool True if login is successful, false otherwise.
 */
function loginUser($username, $password) {
  // 1. Sanitize Input (Important for Security)
  $username = trim($username);
  $password = trim($password);

  // 2. Database Credentials (Replace with your actual credentials)
  $db_host = "localhost";
  $db_name = "your_database_name";
  $db_user = "your_database_user";
  $db_password = "your_database_password";

  // 3. Connect to the Database
  $conn = new mysqli($db_host, $db_user, $db_password, $db_name);

  // Check connection
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  // 4. Query the Database
  $sql = "SELECT id, username, password FROM users WHERE username = '$username'";
  $result = $conn->query($sql);

  // 5. Check for Results
  if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    // 6. Password Verification (Crucial Security Step)
    if (password_verify($password, $row['password'])) {
      // 7. Login Successful
      $_SESSION['loggedin'] = true;
      $_SESSION['id'] = $row['id'];
      $_SESSION['username'] = $username;
      return true;
    } else {
      // Password mismatch
      return false;
    }
  } else {
    // No user found
    return false;
  }

  // 8. Close the Connection
  $conn->close();
}


// --- Example Usage (Demonstration) ---

// Check if the user is already logged in
if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true) {
  echo "Welcome back, " . $_SESSION['username'] . "!";
}

// Handle Login Form Submission
if (isset($_POST['username']) && isset($_POST['password'])) {
  $username = $_POST['username'];
  $password = $_POST['password'];

  if (loginUser($username, $password)) {
    // Login Successful
    echo "Login successful!";
    // Redirect to a protected page or display a welcome message
  } else {
    // Login Failed
    echo "Incorrect username or password!";
  }
}

?>
