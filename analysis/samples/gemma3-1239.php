    Username: <input type="text" name="username" placeholder="your_email@example.com">
    <br><br>
    Password: <input type="password" name="password">
    <br><br>
    <input type="submit" value="Login">
  </form>

</body>
</html>


// ... (Database connection code) ...

// Get the username and password from the form
$username = $_POST["username"];
$password = $_POST["password"];

// Hash the password using password_hash()
$hashed_password = password_hash($password, PASSWORD_DEFAULT);

// SQL query to insert the user
$sql = "INSERT INTO users (email, password) VALUES ('$username', '$hashed_password')";

// Execute the query
if ($conn->query($sql) === TRUE) {
  echo "New record created successfully";
} else {
  echo "Error: " . $conn->error;
}

// ... (Close database connection) ...


<?php

// Database credentials (Replace with your actual credentials)
$db_host = "localhost";
$db_name = "your_database_name";
$db_user = "your_db_user";
$db_password = "your_db_password";

// Function to handle login
function loginUser($username, $password) {
  // Connect to the database
  $conn = new mysqli($db_host, $db_user, $db_password, $db_name);

  // Check connection
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  // Prepare the SQL query
  $sql = "SELECT * FROM users WHERE username = ? AND password = ?";
  $stmt = $conn->prepare($sql);

  if ($stmt) {
    // Bind the parameters
    $stmt->bind_param("ss", $username, $password);

    // Execute the query
    $stmt->execute();

    // Get the result
    $result = $stmt->get_result();

    // Check if any rows were returned
    if ($result->num_rows > 0) {
      $row = $result->fetch_assoc();
      // Successful login
      session_start();
      $_SESSION['user_id'] = $row['id'];
      $_SESSION['username'] = $row['username'];
      $stmt->close();
      $conn->close();
      return true; // Indicate successful login
    } else {
      // Login failed
      $stmt->close();
      $conn->close();
      return false; // Indicate failed login
    }
  } else {
    // Prepare failed
    $stmt->close();
    $conn->close();
    return false; // Indicate prepare failed.
  }
}


// Example usage:  (This part would be in your HTML form processing)
/*
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $username = $_POST["username"];
  $password = $_POST["password"];

  if (loginUser($username, $password)) {
    // Redirect to a protected page or display a welcome message
    header("Location: protected_page.php"); // Replace with your protected page
    exit();
  } else {
    // Display an error message
    echo "<p>Invalid username or password.</p>";
  }
}
*/


?>
