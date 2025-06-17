

<?php

// Database connection details - Replace with your actual details
$servername = "localhost";
$username = "your_username";
$password = "your_password";
$dbname = "your_database_name";

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

  // Get username and password from form
  $username = trim($_POST["username"]);
  $password = trim($_POST["password"]);

  // Validate inputs (IMPORTANT - See security notes below!)
  if (empty($username) || empty($password)) {
    $errors = ["Username" => "Username is required.", "Password" => "Password is required."];
  } elseif (strlen($username) < 3) {
    $errors = ["Username" => "Username must be at least 3 characters long."];
  }

  // Attempt to connect to the database
  $conn = new mysqli($servername, $username, $password, $dbname);

  // Check connection
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  // SQL query - Prepare and execute.  This is safer than echo'ing raw user input
  $sql = "SELECT * FROM users WHERE username = ? AND password = ?"; // Using prepared statements
  $stmt = $conn->prepare($sql);

  if ($stmt) {
      $stmt->bind_param("ss", $username, $password);  // "ss" means two strings
      $stmt->execute();
      $result = $stmt->get_result();

      if ($result->num_rows == 1) {
        // User found - Session based authentication
        session_start();
        $_SESSION["username"] = $username;
        $stmt->close();
        $conn->close();

        // Redirect to a protected page (e.g., dashboard)
        header("Location: dashboard.php");
        exit();
      } else {
        $stmt->close();
        $conn->close();
        $error = "Invalid username or password.";
      }
    } else {
      $stmt = null;
      $conn->close();
      $error = "Error preparing statement.";
    }


} else {
  // If form is not submitted, display the login form
  $error = "";
}
?>
