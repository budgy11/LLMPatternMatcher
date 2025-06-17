

<?php
session_start();

// Database credentials (replace with your actual values)
$db_host = 'localhost';
$db_name = 'your_database_name';
$db_user = 'your_db_username';
$db_password = 'your_db_password';

// Function to handle user login
function loginUser($username, $password) {
  // Connect to the database
  $conn = new mysqli($db_host, $db_user, $db_password, $db_name);

  // Check connection
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  // Prepare and execute the query
  $sql = "SELECT id, username, password FROM users WHERE username = ? AND password = ?";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param("ss", $username, $password); // "ss" means two strings
  $stmt->execute();

  $result = $stmt->get_result();

  // Check if user found
  if ($result->num_rows == 1) {
    $user = $result->fetch_assoc();
    $_SESSION['username'] = $user['username'];
    $_SESSION['userid'] = $user['id']; // Store user ID for easier access
    return true;
  } else {
    return false;
  }

  $stmt->close();
  $conn->close();
}

// --- Example Usage (Handle Login Request) ---

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $username = $_POST["username"];
  $password = $_POST["password"];

  if (loginUser($username, $password)) {
    header("Location: /welcome.php"); // Redirect to welcome page after successful login
    exit();
  } else {
    echo "<p style='color: red;'>Invalid username or password.</p>";
  }
}
?>
