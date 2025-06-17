    <button type="submit">Login</button>
</form>

</body>
</html>


<?php

// Database credentials - **IMPORTANT:  Change these to your actual values!**
$servername = "localhost";
$username = "your_username";
$password = "your_password";
$database = "your_database";

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Get username and password from the form
  $username = $_POST["username"];
  $password = $_POST["password"];

  // Validate input (important for security!)
  if (empty($username) || empty($password)) {
    $errors = ["Username and Password fields cannot be empty."];
  } elseif (!preg_match("/^[a-zA-Z0-9._-]+$/", $username)) {
    $errors = ["Username must contain only letters, numbers, periods, underscores, and hyphens."];
  } elseif (!preg_match("/^[a-zA-Z0-9]{8,}$/", $password)) {
    $errors = ["Password must be at least 8 characters long and contain only letters and numbers."];
  } else {
    // Connect to the database
    $conn = new mysqli($servername, $username, $password, $database);

    // Check connection
    if ($conn->connect_error) {
      die("Connection failed: " . $conn->connect_error);
    }

    // Prepare the SQL query
    $sql = "SELECT * FROM users WHERE username = ? AND password = ?";

    // Prepare the statement
    $stmt = $conn->prepare($sql);

    // Bind the parameters
    $stmt->bind_param("s", $username, $password);

    // Execute the statement
    $stmt->execute();

    // Get the result
    $result = $stmt->get_result();

    // Check if a user was found
    if ($result->num_rows == 1) {
      // User found, set session variables
      session_start();
      $_SESSION["username"] = $username;  // Store username in session
      $_SESSION["user_id"] = $result->fetch_assoc()["id"]; // Assuming you have an 'id' column in your users table.  Adjust if different.

      // Redirect to a protected page
      header("Location: /protected_page.php"); // Replace with your protected page URL
      exit();
    } else {
      $error = ["Invalid username or password."];
    }
  }
}
?>
