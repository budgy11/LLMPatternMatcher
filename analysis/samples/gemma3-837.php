

<?php

// Database connection details (replace with your actual values)
$servername = "localhost";
$username = "your_db_username";
$password = "your_db_password";
$dbname = "your_db_name";

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Get username and password from the form
  $username = $_POST["username"];
  $password = $_POST["password"];

  // Validate input (important for security!)
  if (empty($username) || empty($password)) {
    $error = "Username and password are required.";
  } else {
    // Connect to the database
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check connection
    if ($conn->connect_error) {
      die("Connection failed: " . $conn->connect_error);
    }

    // SQL query to authenticate the user
    $sql = "SELECT id, username, password FROM users WHERE username = '$username'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
      // User found, check the password
      $row = $result->fetch_assoc();
      if (password_verify($password, $row["password"])) {
        // Password is correct, set session variables
        session_start();
        $_SESSION["user_id"] = $row["id"];
        $_SESSION["username"] = $username;
        echo "Login successful!";
        // Redirect to a secure page or home page
        header("Location: /protected_page.php"); //  Change to your protected page's URL
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
