  Username: <input type="text" name="username" required>
  Password: <input type="password" name="password" required>
  <button type="submit">Login</button>
</form>


<?php

// Database credentials (replace with your actual details)
$db_host = "localhost";
$db_name = "your_database_name";
$db_user = "your_database_user";
$db_pass = "your_database_password";

// Function to handle user login
function loginUser($username, $password) {
  // Connect to the database
  $conn = new mysqli($db_host, $db_user, $db_pass, $db_name);

  // Check connection
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  // Prepare the SQL query
  $sql = "SELECT id, username, password FROM users WHERE username = ? AND password = ?;";

  // Use prepared statement to prevent SQL injection
  $stmt = $conn->prepare($sql);
  $stmt->bind_param("ss", $username, $password);  // "ss" indicates two string parameters

  // Execute the query
  if ($stmt->execute() === TRUE) {
    // Get the result
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
      $row = $result->fetch_assoc();
      // User found, store session variables
      $_SESSION['user_id'] = $row['id'];
      $_SESSION['username'] = $row['username'];
      return true; // Login successful
    } else {
      return false; // User not found
    }

  } else {
    // Error executing query
    return false;
  }

  // Close the statement and connection
  $stmt->close();
  $conn->close();
}


// Example Usage (for demonstration - replace with actual form handling)
if (isset($_POST['username']) && isset($_POST['password'])) {
  $username = $_POST['username'];
  $password = $_POST['password'];

  if (loginUser($username, $password)) {
    echo "Login successful!  Redirecting...";
    // Redirect to a protected page or display a welcome message
    header("Location: /protected_page.php"); // Example:  Replace with your desired URL
    exit();
  } else {
    echo "Invalid username or password.";
  }
}
?>
