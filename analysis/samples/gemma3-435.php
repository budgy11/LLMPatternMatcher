    <br><br>
    <button type="submit">Login</button>
  </form>

  <br><br>
  <a href="register.php">Not registered? Register here.</a> <!-- Replace with your register page -->

</body>
</html>


<?php

// Database configuration (Replace with your actual credentials)
$db_host = 'localhost';
$db_name = 'your_database_name';
$db_user = 'your_username';
$db_password = 'your_password';

// Check if the login form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

  // Get username and password from the form
  $username = $_POST["username"];
  $password = $_POST["password"];

  // Validate input (IMPORTANT: Sanitize and validate all user input)
  if (empty($username) || empty($password)) {
    $error_message = "Username and password cannot be empty.";
  } else {
    // Query the database
    $conn = new mysqli($db_host, $db_user, $db_password, $db_name);

    if ($conn->connect_error) {
      die("Connection failed: " . $conn->connect_error);
    }

    // Prepare the SQL query
    $sql = "SELECT id, username, password FROM users WHERE username = ? AND password = ?";

    // Bind parameters to prevent SQL injection
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $username, $password); // 'ss' indicates two string parameters

    // Execute the query
    if ($stmt->execute()) {
      // Get the result
      $result = $stmt->get_result();

      if ($result->num_rows == 1) {
        // User found, set session variables
        session_start();
        $user_id = $result->fetch_assoc()["id"];
        $username = $result->fetch_assoc()["username"];
        $_SESSION["user_id"] = $user_id;
        $_SESSION["username"] = $username;

        // Redirect to a protected page (e.g., homepage)
        header("Location: /protected_page.php"); // Replace with your protected page URL
        exit();
      } else {
        $error_message = "Invalid username or password.";
      }
    } else {
      $error_message = "Error executing query.";
    }
  }
}
?>
