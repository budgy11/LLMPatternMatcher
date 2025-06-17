

<?php

// Database credentials (replace with your actual credentials)
$username = "your_username";
$password = "your_password";
$db_host = "localhost";
$db_name = "your_database_name";

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

  // Get the username and password from the form
  $username = $_POST["username"];
  $password = $_POST["password"];

  // Validate the input (important for security!)
  if (empty($username) || empty($password)) {
    $error_message = "Username and password cannot be empty.";
  } elseif (!filter_var($username, FILTER_VALIDATE_EMAIL)) {
    $error_message = "Invalid username. Please enter a valid email address.";
  } else {
    // Database connection
    $conn = new mysqli($db_host, $username, $password, $db_name);

    // Check connection
    if ($conn->connect_error) {
      die("Connection failed: " . $conn->connect_error);
    }

    // Prepare and execute the query
    $sql = "SELECT id, username, password FROM users WHERE username = ? AND password = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $username, $password); // "ss" for two strings
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if the user exists
    if ($result->num_rows == 0) {
      $error_message = "Invalid username or password.";
    } else {
      // User found, set session variables
      session_start();
      $_SESSION["username"] = $username;
      $user_id = $result->fetch_assoc()["id"]; //Get user id.
      $_SESSION["user_id"] = $user_id;
      $error_message = "Login successful!";
      //Redirect to a protected page
      header("Location: /protected_page.php"); //Replace with your protected page URL
      exit();
    }

    $stmt->close();
  }
}
?>
