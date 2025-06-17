    Username: <input type="text" name="username" required>
    Password: <input type="password" name="password" required>
    <button type="submit">Login</button>
  </form>

  <p>Don't have an account? <a href="register.php">Register</a></p>

</body>
</html>


<?php

// Database credentials (Replace with your actual values)
$db_host = 'localhost';
$db_name = 'your_database_name';
$db_user = 'your_database_user';
$db_password = 'your_database_password';

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Get the username and password from the form
  $username = $_POST["username"];
  $password = $_POST["password"];

  // Validate the input (Important for security)
  if (empty($username) || empty($password)) {
    $error_message = "Username and password cannot be empty.";
  } else {
    // Database connection
    $conn = new mysqli($db_host, $db_user, $db_password, $db_name);

    // Check connection
    if ($conn->connect_error) {
      die("Connection failed: " . $conn->connect_error);
    }

    // Prepare the SQL query (Using prepared statements - Best Practice!)
    $query = "SELECT * FROM users WHERE username = ? AND password = ?";
    $stmt = $conn->prepare($query);

    if ($stmt) {
      // Bind the parameters
      $stmt->bind_param("ss", $username, $password);

      // Execute the query
      $stmt->execute();

      // Get the result
      $result = $stmt->get_result();

      // Check if there is a user with the given credentials
      if ($result->num_rows == 1) {
        // User found, set session variables
        session_start(); // Start session management (if not already started)
        $_SESSION["username"] = $username;
        $result->fetch_assoc(); // Fetch the user data
        $error_message = ""; // Clear any previous errors
        // Redirect to a secure page or display a success message
        header("Location: secure_page.php"); // Replace with your secure page
        exit();
      } else {
        $error_message = "Invalid username or password.";
      }
    } else {
      $error_message = "Error preparing statement.";
    }
  }
}
?>
