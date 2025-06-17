
</body>
</html>


// Example of using prepared statements (recommended)

$sql = "SELECT * FROM users WHERE email = ?"; // Use a placeholder ?
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $username); // "s" indicates a string
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 1) {
  // ...
}


<?php

// Database credentials (replace with your actual details)
$host = "localhost";
$username = "your_username";
$password = "your_password";
$database = "your_database";

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Get the username and password from the form
  $username = $_POST["username"];
  $password = $_POST["password"];

  // Validate the input (important for security)
  if (empty($username) || empty($password)) {
    $error = "Username and password cannot be empty.";
  } elseif (!filter_var($username, FILTER_VALIDATE_EMAIL)) {
    $error = "Invalid email format.";
  } else {
    // Attempt to connect to the database
    $conn = new mysqli($host, $username, $password, $database);

    // Check for connection errors
    if ($conn->connect_error) {
      $error = "Failed to connect to database: " . $conn->connect_error;
    } else {
      // Query to check for the username and password
      $sql = "SELECT * FROM users WHERE username = '$username' AND password = '$password'";
      $result = $conn->query($sql);

      if ($result->num_rows == 0) {
        $error = "Invalid username or password.";
      }

      if ($error) {
        echo "<p style='color:red;'>$error</p>";
      } else {
        // If the query is successful, redirect the user to the dashboard
        session_start();  // Start a session for user data

        $_SESSION["username"] = $username; // Store username in session

        header("Location: dashboard.php"); // Redirect to the dashboard
        exit(); // Important to stop further script execution
      }
    }
  }
}
?>
