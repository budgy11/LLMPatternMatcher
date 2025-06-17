    Username: <input type="text" name="username" required><br><br>
    Password: <input type="password" name="password" required><br><br>
    <button type="submit">Login</button>
  </form>

  <br><br>
  <a href="register.php">Don't have an account? Register here.</a>

</body>
</html>


<?php

// Database credentials (replace with your actual values)
$db_host = "localhost";
$db_name = "your_database_name";
$db_user = "your_db_user";
$db_pass = "your_db_password";

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Get the username and password from the form
  $username = $_POST["username"];
  $password = $_POST["password"];

  // Validate the input (important for security!)
  if (empty($username) || empty($password)) {
    $error = "Username and password cannot be empty.";
  } elseif (!preg_match("/^[a-zA-Z0-9._-]+$/", $username)) {
    $error = "Invalid username.  Only letters, numbers, periods, and underscores are allowed.";
  } elseif (!preg_match("/^[a-zA-Z0-9]+$/", $password)) {
    $error = "Invalid password.  Only letters and numbers are allowed.";
  }
  else {
    // Database connection
    $conn = new mysqli($db_host, $db_user, $db_pass, $db_name);

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
    if ($stmt->execute() === TRUE) {
      // Get the result
      $result = $stmt->get_result();

      if ($result->num_rows == 1) {
        // User found - now you can redirect or display the user's information
        $row = $result->fetch_assoc();
        session_start(); // Start a session to store user data

        // Store the user's data in the session
        $_SESSION["user_id"] = $row["id"];
        $_SESSION["username"] = $row["username"];
        // You might store other user data here, like roles, etc.

        // Redirect to a secure page or display a welcome message
        header("Location: welcome.php"); // Replace welcome.php with your landing page
        exit();
      } else {
        $error = "Incorrect username or password.";
      }
    } else {
      $error = "Query execution error.";
    }
  }
}
?>
