    Username: <input type="text" name="username" required>
    Password: <input type="password" name="password" required>
    <button type="submit">Login</button>
  </form>

</body>
</html>


<?php

// Database Credentials (Replace with your actual values!)
$db_host = "localhost";
$db_name = "your_database_name";
$db_user = "your_username";
$db_pass = "your_password";

// Function to handle login
function loginUser($username, $password) {
  // 1. Connect to the database
  $conn = new mysqli($db_host, $db_user, $db_pass, $db_name);

  // Check connection
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  // 2. Prepare the SQL query
  $sql = "SELECT * FROM users WHERE username = ? AND password = ?";

  // 3. Bind the parameters
  $stmt = $conn->prepare($sql);
  $stmt->bind_param("ss", $username, $password); // "ss" indicates two strings

  // 4. Execute the query
  if ($stmt->execute()) {
    // 5. Get the result
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
      // User found
      $row = $result->fetch_assoc();
      // 6. Set session variables
      $_SESSION['user_id'] = $row['id'];
      $_SESSION['username'] = $row['username'];

      return true; // Login successful
    } else {
      // No user found with those credentials
      return false;
    }
  } else {
    // Error executing query
    return false;
  }

  // 7. Close the statement and connection
  $stmt->close();
  $conn->close();
}


// Example Usage (Illustrative - this is for testing only)
if (isset($_POST['username']) && isset($_POST['password'])) {
  $username = $_POST['username'];
  $password = $_POST['password'];

  if (loginUser($username, $password)) {
    // Login successful - redirect or display a welcome message
    echo "Login successful!  Welcome, " . $_SESSION['username'];
    // Redirect to a secure page, or display a welcome message.
    header("Location: secure_page.php"); // Replace with your secure page
    exit();

  } else {
    // Login failed
    echo "Invalid username or password.";
  }
}

?>
