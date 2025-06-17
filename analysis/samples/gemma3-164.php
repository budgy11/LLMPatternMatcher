

<?php

// Database connection details (Replace with your actual details)
$servername = "localhost";
$username = "your_username";
$password = "your_password";
$dbname = "your_database";

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Get username and password from the form
  $username = $_POST["username"];
  $password = $_POST["password"];

  // Validate the input (Important for security!)
  if (empty($username) || empty($password)) {
    $error = "Username and password must be filled.";
  } else {
    // Connect to the database
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check connection
    if ($conn->connect_error) {
      die("Connection failed: " . $conn->connect_error);
    }

    // Query to check the username and password
    $sql = "SELECT id, username, password FROM users WHERE username = '$username'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
      // User found
      $row = $result->fetch_assoc();
      // Verify password (Hashing is crucial in real applications)
      if (password_verify($password, $row["password"])) {
        // Successful login
        session_start();  // Start the session
        $_SESSION["user_id"] = $row["id"];
        $_SESSION["username"] = $username;
        header("Location: welcome.php"); // Redirect to a welcome page
        exit();
      } else {
        $error = "Incorrect password.";
      }
    } else {
      $error = "User not found.";
    }
  }
}
?>
