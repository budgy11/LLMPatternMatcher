        Username: <input type="text" name="username" required><br><br>
        Password: <input type="password" name="password" required><br><br>
        <input type="submit" value="Register">
    </form>

    <br><br>
    <a href="login.php">Already have an account? Login here.</a>

</body>
</html>


<?php

// Database configuration (Replace with your actual values)
$host = "localhost";
$username = "your_username";
$password = "your_password";
$database = "your_database";

// Function to handle user login
function loginUser($username, $password) {
  // Connect to the database
  $conn = new mysqli($host, $username, $password, $database);

  // Check connection
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  // Prepare the SQL query
  $sql = "SELECT * FROM users WHERE username = ? AND password = ?";

  // Bind parameters to prevent SQL injection
  $stmt = $conn->prepare($sql);
  $stmt->bind_param("ss", $username, $password);

  // Execute the query
  $stmt->execute();

  // Get the result
  $result = $stmt->get_result();

  // Check if any users were found
  if ($result->num_rows > 0) {
    // Fetch the user data
    $user = $result->fetch_assoc();

    // Authentication successful - create a session
    session_start();
    $_SESSION['username'] = $user['username'];
    $_SESSION['userid'] = $user['userid']; // Add user ID for further actions

    // Redirect the user (optional - you can also use a simple success message)
    return true;
  } else {
    return false;
  }

  // Close the statement and connection
  $stmt->close();
  $conn->close();
}


// Example Usage (simulated form handling)
if (isset($_POST['username']) && isset($_POST['password'])) {
  $username = $_POST['username'];
  $password = $_POST['password'];

  if (loginUser($username, $password)) {
    // Login successful, redirect to the homepage or another protected page
    header("Location: /home.php"); // Replace with your actual homepage URL
    exit();
  } else {
    // Login failed, display an error message
    echo "<p style='color:red;'>Invalid username or password.</p>";
  }
}
?>
