

<?php

// Database credentials (replace with your actual values)
$db_host = "localhost";
$db_name = "your_database_name";
$db_user = "your_db_user";
$db_password = "your_db_password";

// Session handling (to maintain login state)
session_start();

// Function to handle the login process
function loginUser($username, $password) {
  // 1. Validate Input (VERY IMPORTANT!)
  $username = trim($username); // Remove leading/trailing whitespace
  $password = trim($password);

  //  Sanitize the input - important to prevent SQL injection!
  $username = mysqli_real_escape_string($GLOBALS['db_host'], $username); // Use mysqli_real_escape_string for mysqli
  $password = mysqli_real_escape_string($GLOBALS['db_host'], $password);


  // 2. Database Query (Using mysqli - recommended)
  $conn = mysqli_connect($db_host, $db_user, $db_password, $db_name);

  if (mysqli_connect_errno()) {
    die("Connection failed: " . mysqli_connect_error());
  }

  $sql = "SELECT * FROM users WHERE username = '$username' AND password = '$password'";
  $result = mysqli_query($conn, $sql);

  if (mysqli_num_rows($result) > 0) {
    // User found - authenticate
    $row = mysqli_fetch_assoc($result);
    $_SESSION['username'] = $row['username']; // Store username in session
    $_SESSION['user_id'] = $row['id']; // Store user ID (if available)
    return true; // Login successful
  } else {
    return false; // Login failed
  }

  mysqli_close($conn);
}


// Example Usage (Illustrative - You would normally get this from a form)
if (isset($_POST['username']) && isset($_POST['password'])) {
  $username = $_POST['username'];
  $password = $_POST['password'];

  if (loginUser($username, $password)) {
    // Login successful - redirect to a protected page or display a welcome message
    header("Location: welcome.php"); // Assuming you have a welcome.php page
    exit();
  } else {
    // Login failed - display an error message
    echo "<p style='color:red;'>Invalid username or password.</p>";
  }
}

?>
