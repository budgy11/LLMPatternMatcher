

<?php

// Database connection details
$servername = "localhost";
$username = "your_username";
$password = "your_password";
$dbname = "your_database_name";

// Check if the login form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

  // Get login details from the form
  $username = $_POST["username"];
  $password = $_POST["password"];

  // Create connection
  $conn = new mysqli($servername, $username, $password, $dbname);

  // Check connection
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  // SQL query to fetch the user based on username
  $sql = "SELECT id, username, password FROM users WHERE username = '$username'";
  $result = $conn->query($sql);

  // Check if a user exists
  if ($result->num_rows == 1) {
    $row = $result->fetch_assoc();

    // Verify the password
    if (password_verify($password, $row["password"])) {
      // Successful login - set session variables
      session_start();
      $_SESSION["user_id"] = $row["id"];
      $_SESSION["username"] = $row["username"];

      // Redirect to the protected page
      header("Location: /protected_page.php"); // Replace with your protected page URL
      exit(); // Important:  Stop script execution after redirect
    } else {
      // Incorrect password
      echo "Incorrect password.";
    }
  } else {
    // User does not exist
    echo "User does not exist.";
  }

  $conn->close();
}
?>
