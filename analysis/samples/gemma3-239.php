    Username: <input type="text" name="username" required><br><br>
    Password: <input type="password" name="password" required><br><br>
    <input type="submit" value="Login">
  </form>

</body>
</html>


<?php

// Database connection details (replace with your actual credentials)
$host = "localhost";
$username = "your_db_username";
$password = "your_db_password";
$database = "your_db_name";

// Function to handle user login
function loginUser($username, $password) {
  // Connect to the database
  $conn = new mysqli($host, $username, $password, $database);

  // Check connection
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  // Prepare the SQL query
  $stmt = $conn->prepare("SELECT id, username, password FROM users WHERE username = ?");

  // Bind the username parameter
  $stmt->bind_param("s", $username);

  // Execute the query
  $stmt->execute();

  // Get the result
  $result = $stmt->get_result();

  // Check if a user was found
  if ($result->num_rows == 1) {
    $row = $result->fetch_assoc();
    $hashed_password = $row['password']; // Fetch the hashed password
    // Compare the provided password with the hashed password
    if (password_verify($password, $hashed_password)) {
      // Password matches!  Set session variables.
      session_start();
      $_SESSION['user_id'] = $row['id'];
      $_SESSION['username'] = $username;
      return true; // Login successful
    } else {
      // Password does not match
      $stmt->close();
      return false;
    }
  } else {
    // No user found
    $stmt->close();
    return false;
  }
}


// Example usage (simulated form submission - replace with your actual form handling)
if (isset($_POST['username']) && isset($_POST['password'])) {
  $username = $_POST['username'];
  $password = $_POST['password'];

  if (loginUser($username, $password)) {
    // Login successful - Redirect to a secure page or display a welcome message
    header("Location: secure_page.php"); // Redirect to your secure page
    exit(); // Important to stop further execution after redirection
  } else {
    // Login failed - Display error message
    echo "<p style='color: red;'>Invalid username or password.</p>";
  }
}

?>
