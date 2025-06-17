    <button type="submit">Login</button>
  </form>

  <p>Don't have an account? <a href="register.php">Register</a></p>

</body>
</html>


<?php

// Database connection details (replace with your actual details)
$db_host = "localhost";
$db_name = "your_database_name";
$db_user = "your_username";
$db_password = "your_password";

// Check if the login form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Get the username and password from the form
  $username = $_POST["username"];
  $password = $_POST["password"];

  // Validate the input (VERY IMPORTANT - prevent SQL injection)
  $username = trim($username); // Remove leading/trailing whitespace
  $password = trim($password);

  // Sanitize the input (more robust than just trim)
  $username = filter_var($username, FILTER_SANITIZE_STRING);
  $password = filter_var($password, FILTER_SANITIZE_STRING);


  // Connect to the database
  $conn = new mysqli($db_host, $db_user, $db_password, $db_name);

  // Check the connection
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  // Prepare the SQL query with prepared statements (MOST IMPORTANT - prevents SQL injection)
  $sql = "SELECT id, username, password FROM users WHERE username = ? AND password = ?";

  // Prepare the statement
  $stmt = $conn->prepare($sql);

  // Bind the parameters
  $stmt->bind_param("ss", $username, $password); // "ss" indicates two string parameters

  // Execute the statement
  if ($stmt->execute() === TRUE) {
    // Get the result
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
      // User found - set session variables
      session_start();
      $row = $result->fetch_assoc();
      $_SESSION["user_id"] = $row["id"];
      $_SESSION["username"] = $row["username"];

      // Redirect to a protected page
      header("Location: /protected_page.php"); // Replace with your protected page
      exit();

    } else {
      echo "Invalid username or password.";
    }

  } else {
    echo "Error executing query: " . $conn->error;
  }

  // Close the statement and connection
  $stmt->close();
  $conn->close();

} else {
  // If the form hasn't been submitted, display the login form
  // This is crucial for the initial page load
  ?>
