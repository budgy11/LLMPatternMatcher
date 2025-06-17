

<?php

// Database connection details - REPLACE WITH YOUR ACTUAL CREDENTIALS
$host = "localhost";
$username = "your_username";
$password = "your_password";
$database = "your_database";

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Get form data
  $username = $_POST["username"];
  $password = $_POST["password"];

  // Validate the input (IMPORTANT for security)
  if (empty($username) || empty($password)) {
    $error_message = "Username and Password cannot be empty.";
  } elseif (!filter_var($username, FILTER_VALIDATE_EMAIL)) {
    $error_message = "Invalid email address.";
  } else {
    // Database query
    $conn = new mysqli($host, $username, $password, $database);

    if ($conn->connect_error) {
      die("Connection failed: " . $conn->connect_error);
    }

    // Prepare the SQL query - IMPORTANT for security
    $sql = "SELECT id, username, password FROM users WHERE username = ? AND password = ?;";
    $stmt = $conn->prepare($sql);
    if ($stmt === false) {
      die("Error preparing statement: " . $conn->error);
    }

    // Bind parameters - IMPORTANT for security
    $stmt->bind_param("ss", $username, $password);

    // Execute the query
    if (!$stmt->execute()) {
      echo "Error: " . $stmt->error;
      $stmt->close();
      $conn->close();
      exit();
    }

    // Get the result
    $result = $stmt->get_result();

    // Check if the user exists
    if ($result->num_rows == 0) {
      echo "<p style='color:red;'>Invalid username or password.</p>";
      $stmt->close();
      $conn->close();
    } else {
      // User exists, set session variables
      session_start();
      $_SESSION["user_id"] = $result->fetch_assoc()["id"];
      $_SESSION["username"] = $username;

      // Redirect to the protected page
      header("Location: protected_page.php");  // Replace with your protected page URL
      exit();
    }

  }
}
?>
