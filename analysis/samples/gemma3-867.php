    Username: <input type="text" name="username" required><br><br>
    Password: <input type="password" name="password" required><br><br>
    <input type="submit" value="Login">
</form>

</body>
</html>


<?php

// Database Credentials (Replace with your actual credentials)
$db_host = "localhost";
$db_name = "your_database_name";
$db_user = "your_username";
$db_password = "your_password";

// Function to handle user login
function loginUser($username, $password) {
  // Connect to the database
  $conn = new mysqli($db_host, $db_user, $db_password, $db_name);

  // Check connection
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  // Prepare and execute the query
  $sql = "SELECT * FROM users WHERE username = ? AND password = ?";
  $stmt = $conn->prepare($sql);

  if ($stmt) {
    $stmt->bind_param("s", $username, $password); // "s" indicates a string
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
      $row = $result->fetch_assoc();
      //  You could add more checks here, like validating user roles.

      // Set session variables for the logged-in user
      $_SESSION['user_id'] = $row['id'];  // Assuming 'id' is the primary key
      $_SESSION['username'] = $username;
      return true; // Login successful
    } else {
      return false; // Login failed (incorrect username or password)
    }
    $stmt->close();
  } else {
    return false; // Failed to prepare the statement
  }
}

// Example Usage (for testing)
// This should be called from a form submission.  Don't do this directly in the code.
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $username = $_POST["username"];
  $password = $_POST["password"];

  if (loginUser($username, $password)) {
    echo "Login successful!  Redirecting...";
    // Redirect to a protected page or display a welcome message
    header("Location: protected_page.php");
    exit();
  } else {
    echo "Invalid username or password.";
  }
}

?>
