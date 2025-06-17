    Username: <input type="text" name="username" placeholder="Enter your username" required>
    <br><br>
    Password: <input type="password" name="password" placeholder="Enter your password" required>
    <br><br>
    <button type="submit">Login</button>
  </form>

</body>
</html>


<?php

// Database credentials (replace with your actual values)
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

  // Prepare the SQL query
  $sql = "SELECT * FROM users WHERE username = ? AND password = ?"; 

  // Use prepared statement to prevent SQL injection
  $stmt = $conn->prepare($sql);

  if ($stmt) {
    // Bind parameters
    $stmt->bind_param("ss", $username, $password); // "ss" means two strings

    // Execute the query
    if ($stmt->execute()) {
      // Get the result
      $result = $stmt->get_result();

      if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();
        // Session setup
        session_start(); 
        $_SESSION['username'] = $username;  // Store username in session
        $_SESSION['user_id'] = $row['id']; //Optionally store user ID

        // Redirect to a protected page or show a success message
        header("Location: protected_page.php"); // Replace with your protected page
        exit(); // Stop further execution
      } else {
        return "Invalid username or password.";
      }
    } else {
      return "Query error.";
    }
    $stmt->close();
  } else {
    return "Statement preparation error.";
  }
  $conn->close();
}


// Example usage (handling form submission)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $username = $_POST["username"];
  $password = $_POST["password"];

  $login_result = loginUser($username, $password);

  if ($login_result == "Invalid username or password.") {
    echo "<p style='color:red;'>$login_result</p>";
  } else if ($login_result == "Invalid username or password.") {
    echo "<p style='color:red;'>$login_result</p>";
  }
}
?>
