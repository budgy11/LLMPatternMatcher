

// Before storing the password:
$password = $_POST['password'];
$hashed_password = password_hash($password, PASSWORD_DEFAULT);

// In the database, store $hashed_password in the 'password' column.


<?php

// Database connection details (replace with your actual values)
$servername = "localhost";
$username = "your_username";
$password = "your_password";
$dbname = "your_database_name";

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Get username and password from the form
  $username = $_POST["username"];
  $password = $_POST["password"];

  // Validate input (VERY IMPORTANT - prevents SQL injection)
  $username = trim($username);  // Remove leading/trailing whitespace
  $password = trim($password);

  if (empty($username) || empty($password)) {
    $error = "Username and password cannot be empty.";
  } elseif (!filter_var($username, FILTER_VALIDATE_EMAIL) && !preg_match('/^[a-zA-Z0-9]+$/', $username)) {
    $error = "Invalid username. Username must be a valid email address or contain only letters and numbers.";
  } else {
    // Connect to the database
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check connection
    if ($conn->connect_error) {
      die("Connection failed: " . $conn->connect_error);
    }

    // Prepare and execute the query (using prepared statements - BEST PRACTICE)
    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);  // "s" indicates a string parameter
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 0) {
      $error = "Invalid username or password.";
    } else {
      // Authentication successful - set a session variable
      session_start();
      $_SESSION["username"] = $username;
      $error = "Login successful!";
      // Redirect to a protected page (optional)
      header("Location: protected_page.php"); // Replace with your protected page URL
      exit(); // Important: Stop further execution after redirect
    }
  }
}
?>
