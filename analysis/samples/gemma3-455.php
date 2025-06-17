

<?php
session_start();

// Database connection details (replace with your actual details)
$db_host = "localhost";
$db_name = "your_database_name";
$db_user = "your_username";
$db_password = "your_password";

// Form handling
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $username = $_POST["username"];
  $password = $_POST["password"];

  // Validate input (basic, for demonstration purposes)
  if (empty($username) || empty($password)) {
    $error_message = "Username and password are required.";
  } else {
    // Database query (use prepared statements for security - see important note)
    $conn = new mysqli($db_host, $db_user, $db_password, $db_name);

    if ($conn->connect_error) {
      die("Connection failed: " . $conn->connect_error);
    }

    $sql = "SELECT id, username, password FROM users WHERE username = ?;";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username); // 's' indicates a string
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 0) {
      $error_message = "Invalid username or password.";
    } else {
      $row = $result->fetch_assoc();
      // Hash the password from the database and compare
      if (password_verify($password, $row["password"])) {
        // Login successful
        $_SESSION["user_id"] = $row["id"];
        $_SESSION["username"] = $row["username"];
        header("Location: welcome.php"); // Redirect to a welcome page
        exit();
      } else {
        $error_message = "Invalid username or password.";
      }
    }
  }
}
?>
