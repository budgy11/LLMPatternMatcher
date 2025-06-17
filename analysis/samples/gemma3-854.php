    Username: <input type="text" name="username" required><br><br>
    Password: <input type="password" name="password" required><br><br>
    <input type="submit" value="Login">
  </form>

  <br>
  <a href="/register.php">Don't have an account? Register here.</a>

</body>
</html>


<?php

// Database credentials (Replace with your actual credentials)
$db_host = "localhost";
$db_user = "your_username";
$db_password = "your_password";
$db_name = "your_database_name";

// Function to handle user login
function loginUser($username, $password) {
  // Connect to the database
  $conn = new mysqli($db_host, $db_user, $db_password, $db_name);

  // Check connection
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  // SQL query to retrieve the password hash
  $sql = "SELECT password_hash, salt FROM users WHERE username = ?";

  // Prepare the statement
  $stmt = $conn->prepare($sql);

  // Bind the parameter
  $stmt->bind_param("s", $username);

  // Execute the statement
  $stmt->execute();

  // Get the result
  $result = $stmt->get_result();

  // Check if a user was found
  if ($result->num_rows == 0) {
    return false; // User not found
  }

  $user = $result->fetch_assoc();

  // Verify the password
  if (password_verify($password, $user['password_hash'])) {
    // Password matches, authenticate the user
    // You can now set a session, redirect, or perform other actions
    // Here, we simply return true
    return true;
  } else {
    // Password does not match
    return false;
  }

  // Close the statement and connection
  $stmt->close();
  $conn->close();
}


// --- Example Usage (Illustrative - Not for Production) ---

// Check if the login form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Get username and password from the form
  $username = $_POST["username"];
  $password = $_POST["password"];

  // Validate input (Important - Add more robust validation here!)
  if (empty($username) || empty($password)) {
    $error = "Username and password cannot be empty.";
  } else {
    // Attempt to log in
    $login_result = loginUser($username, $password);

    if ($login_result) {
      // Login successful, redirect to a protected page or set a session
      echo "Login successful!  Redirecting...";
      // You might redirect here:
      // header("Location: /protected_page.php"); // Example
      // exit;
    } else {
      $error = "Invalid username or password.";
    }
  }
}
?>
