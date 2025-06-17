    Username: <input type="text" name="username" required>
    Password: <input type="password" name="password" required>
    <button type="submit">Login</button>
  </form>

  <p>Don't have an account? <a href="register.php">Register</a></p>

</body>
</html>


<?php

// Database credentials (replace with your actual credentials)
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

  // Bind parameters to prevent SQL injection
  $stmt = $conn->prepare($sql);
  $stmt->bind_param("ss", $username, $password);

  // Execute the query
  $stmt->execute();

  // Get the result
  $result = $stmt->get_result();

  // Check if any rows were returned
  if ($result->num_rows > 0) {
    // User found - fetch the data
    $user = $result->fetch_assoc();

    // Authentication successful
    // Store session information (example)
    $_SESSION['user_id'] = $user['id'];
    $_SESSION['username'] = $user['username'];

    // Redirect the user to a protected page or home page
    header("Location: home.php"); // Replace 'home.php' with your desired URL
    exit(); // Important to stop further execution

  } else {
    // Authentication failed
    return false; // Indicate failed login
  }

  // Close the statement and connection
  $stmt->close();
  $conn->close();
}

// Example Usage (inside your login form HTML)
// Assuming you have a form with input fields 'username' and 'password'
// and a submit button.
// After the form is submitted, you would call the function like this:

// if (isset($_POST['login'])) {
//   $username = $_POST['username'];
//   $password = $_POST['password'];
//   loginUser($username, $password);
// }
?>
