

<?php

// Database credentials (Replace with your actual credentials)
$db_host = 'localhost';
$db_user = 'your_db_user';
$db_password = 'your_db_password';
$db_name = 'your_db_name';

// Check if the login form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

  // Get username and password from the form
  $username = $_POST["username"];
  $password = $_POST["password"];

  // Validate input (Important for security - prevent SQL injection and other issues)
  if (empty($username) || empty($password)) {
    $error_message = "Username and password are required.";
  } else {
    // Query the database
    $conn = new mysqli($db_host, $db_user, $db_password, $db_name);

    if ($conn->connect_error) {
      die("Connection failed: " . $conn->connect_error);
    }

    // Prepare the SQL query with a prepared statement (Highly Recommended)
    $sql = "SELECT * FROM users WHERE username = ? AND password = ?";
    $stmt = $conn->prepare($sql);

    if ($stmt) {
      $stmt->bind_param("ss", $username, $password); // "ss" indicates two string parameters
      $stmt->execute();
      $result = $stmt->get_result();

      if ($result->num_rows == 1) {
        // User found
        $user = $result->fetch_assoc();
        // Set session variables to store user information
        $_SESSION["user_id"] = $user["id"];
        $_SESSION["username"] = $user["username"];
        // You can store more user data in the session if needed (e.g., role, name)
        echo "Login successful!";
        header("Location: welcome.php"); // Redirect to a welcome page (adjust path as needed)
        exit();
      } else {
        $error_message = "Invalid username or password.";
      }
    } else {
      $error_message = "Error preparing the SQL statement.";
    }

    $stmt->close();
    $conn->close();
  }
}
?>
