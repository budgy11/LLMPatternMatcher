

<?php

// Database credentials (replace with your actual credentials)
$servername = "localhost";
$username = "your_username";
$password = "your_password";
$dbname = "your_database";

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

  // Get username and password from the form
  $username = $_POST["username"];
  $password = $_POST["password"];

  // Validate input (IMPORTANT - Prevent SQL injection and other attacks)
  $username = trim($username); // Remove leading/trailing whitespace
  $password = trim($password);

  if (empty($username) || empty($password)) {
    $error = "Username and password must be filled.";
  } elseif (!filter_var($username, FILTER_VALIDATE_EMAIL) && !preg_match('/^[a-zA-Z0-9]+$/', $username)) {
     $error = "Invalid username.  Username must be a valid email or contain only letters and numbers.";
  } else {
    // Attempt to connect to the database
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check connection
    if ($conn->connect_error) {
      die("Connection failed: " . $conn->connect_error);
    }

    // Query to retrieve the user's data
    $sql = "SELECT id, username, password FROM users WHERE username = '$username'";
    $result = $conn->query($sql);

    if ($result->num_rows == 1) {
      // User found
      $row = $result->fetch_assoc();

      // Verify password (using password_hash/password_verify is the best practice)
      // This example uses a simple comparison, but it's not secure
      if (password_verify($password, $row["password"])) {
        // Authentication successful - Session setup
        session_start();
        $_SESSION["user_id"] = $row["id"];
        $_SESSION["username"] = $row["username"];

        // Redirect to a protected page or the homepage
        header("Location: /protected_page.php"); // Replace with your desired destination
        exit(); // Stop further execution
      } else {
        $error = "Incorrect password.";
      }
    } else {
      $error = "Invalid username.";
    }
  }
}
?>
