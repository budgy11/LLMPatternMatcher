

<?php

// Database configuration (replace with your actual values)
$db_host = "localhost";
$db_name = "your_database_name";
$db_user = "your_db_user";
$db_password = "your_db_password";

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Get the username and password from the form
  $username = trim($_POST["username"]);
  $password = trim($_POST["password"]);

  // Validate input (important for security)
  if (empty($username) || empty($password)) {
    $error_message = "Username and password cannot be empty.";
  } elseif (preg_match("/^[a-zA-Z0-9_]+$/", $username) == 0) {
    $error_message = "Username must contain only letters, numbers, and underscores.";
  } else {
    // Query the database
    $conn = new mysqli($db_host, $db_user, $db_password, $db_name);

    if ($conn->connect_error) {
      die("Connection failed: " . $conn->connect_error);
    }

    // Prepare the SQL query (use prepared statements for security!)
    $sql = "SELECT * FROM users WHERE username = ? AND password = ?";
    $stmt = $conn->prepare($sql);

    if ($stmt) {
      // Bind the parameters (for security)
      $stmt->bind_param("s", $username, $password);

      // Execute the query
      if ($stmt->execute()) {
        // Get the result
        $result = $stmt->get_result();

        if ($result->num_rows == 1) {
          // User found, set session variables
          session_start();
          $_SESSION["username"] = $username;
          $result->fetch_assoc(); // Get the user data
          $stmt->close();

          // Redirect to a secure page or display a success message
          header("Location: secure_page.php"); // Replace with your secure page
          exit();
        } else {
          $error_message = "Incorrect username or password.";
          $stmt->close();
        }
      } else {
        $error_message = "Query execution failed.";
        $stmt->close();
      }
    } else {
      $error_message = "Error preparing statement.";
    }
  }
}
?>
