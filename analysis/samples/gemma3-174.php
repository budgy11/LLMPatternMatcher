

<?php

// Database connection details (Replace with your actual credentials)
$servername = "localhost";
$username = "your_username";
$password = "your_password";
$dbname = "your_database_name";

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Get the login form data
  $username = $_POST["username"];
  $password = $_POST["password"];

  // Connect to the database
  $conn = new mysqli($servername, $username, $password, $dbname);

  // Check connection
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  // SQL query to check the username and password
  $sql = "SELECT id, username, password FROM users WHERE username = '$username'";
  $result = $conn->query($sql);

  // Check if the user exists
  if ($result->num_rows == 1) {
    // Fetch the user data
    $row = $result->fetch_assoc();

    // Verify the password
    if (password_verify($password, $row["password"])) {
      // Login successful
      // Set session variables to store the user's information
      session_start();
      $_SESSION["user_id"] = $row["id"];
      $_SESSION["username"] = $row["username"];
      echo "Login successful! <a href='welcome.php'>Welcome, " . $_SESSION["username"] . "</a>";
    } else {
      echo "Incorrect password.";
    }
  } else {
    echo "User not found.";
  }

  $conn->close();
}

// Form HTML
?>
