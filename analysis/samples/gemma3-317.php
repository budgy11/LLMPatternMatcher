

<?php

// Database credentials (replace with your actual values)
$db_host = "localhost";
$db_name = "your_database_name";
$db_user = "your_username";
$db_password = "your_password";

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Get the username and password from the form
  $username = $_POST["username"];
  $password = $_POST["password"];

  // Validate the input (basic validation - add more robust validation as needed)
  if (empty($username) || empty($password)) {
    $error = "Username and password cannot be empty.";
  } elseif (!filter_var($username, FILTER_VALIDATE_EMAIL)) {
    $error = "Invalid username (must be a valid email address).";
  } else {
    // Query the database to verify the credentials
    $conn = new mysqli($db_host, $db_user, $db_password, $db_name);

    if ($conn->connect_error) {
      die("Connection failed: " . $conn->connect_error);
    }

    // Prepare the SQL query
    $sql = "SELECT * FROM users WHERE email = '$username'"; // Using email for security
    $stmt = $conn->prepare($sql);

    if ($stmt) {
      $stmt->execute();
      $result = $stmt->get_result();

      if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();
        // Verify the password
        if (password_verify($password, $row["password"])) {
          // Login successful!  You can redirect or display a welcome message
          session_start();
          $_SESSION["user_id"] = $row["id"];
          $_SESSION["username"] = $row["email"]; // Use email instead of username
          header("Location: welcome.php"); // Redirect to a welcome page
          exit();
        } else {
          $error = "Incorrect password.";
        }
      } else {
        $error = "User not found.";
      }

      $stmt->close();
    } else {
      $error = "Error preparing statement.";
    }
  }
}
?>
