

// In the user registration function (or when updating the user's password)

$hashed_password = password_hash($password, PASSWORD_DEFAULT);
$sql = "INSERT INTO users (username, password, ...) VALUES ('$username', '$hashed_password', ...)";


<?php

// Database Credentials (Replace with your actual values)
$host = "localhost";
$username = "your_db_username";
$password = "your_db_password";
$database = "your_db_name";

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Get username and password from the form
  $username = $_POST["username"];
  $password = $_POST["password"];

  // Validate Input (Important for security!)
  if (empty($username) || empty($password)) {
    $error = "Username and password cannot be empty.";
  } elseif (!filter_var($username, FILTER_SANITIZE_STRING)) { // Sanitize username
      $error = "Invalid username format.";
  } elseif (!filter_var($password, FILTER_SANITIZE_STRING)) { // Sanitize password
      $error = "Invalid password format.";
  } else {
    // SQL Query (Use prepared statements to prevent SQL injection)
    $sql = "SELECT * FROM users WHERE username = ? AND password = ?";
    $stmt = $conn->prepare($sql); // Assuming $conn is your database connection
    $stmt->bind_param("ss", $username, $password); // "ss" means two strings
    $result = $stmt->execute();

    // Check if the query was successful
    if ($result) {
      $row = $stmt->fetch_assoc();
      if ($row["id"]) { // Assuming 'id' is the primary key
        // Successful login!  Set session variables
        $_SESSION["username"] = $username;
        $_SESSION["id"] = $row["id"]; // Store the user ID
        header("Location: welcome.php"); // Redirect to a welcome page
        exit();
      } else {
        $error = "Incorrect username or password.";
      }
    } else {
      $error = "Database error.";
    }
  }
}
?>
