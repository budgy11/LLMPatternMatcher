

<?php

// Database Configuration (Replace with your actual credentials)
$db_host = "localhost";
$db_name = "your_database_name";
$db_user = "your_database_user";
$db_pass = "your_database_password";

// Function to handle user login
function loginUser($username, $password) {
  // Connect to the database
  $conn = new mysqli($db_host, $db_user, $db_pass, $db_name);

  // Check connection
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  // Prepare the SQL query
  $sql = "SELECT id, username, password FROM users WHERE username = ? AND password = ?";

  // Prepare the statement
  $stmt = $conn->prepare($sql);

  // Bind the parameters
  $stmt->bind_param("ss", $username, $password); // "ss" for string

  // Execute the statement
  if ($stmt->execute()) {
    // Get the result
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
      $row = $result->fetch_assoc();
      // User found, set session variables
      $_SESSION["user_id"] = $row["id"];
      $_SESSION["username"] = $row["username"];
      return true; // Login successful
    } else {
      return false; // User not found
    }
  } else {
    return false; // Query execution error
  }

  // Close the statement and connection
  $stmt->close();
  $conn->close();
}

// Example Usage (in your login form or script)

// Handle login form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $username = $_POST["username"];
  $password = $_POST["password"];

  if (loginUser($username, $password)) {
    // Login successful - redirect or display welcome message
    header("Location: welcome.php"); // Replace with your welcome page
    exit();
  } else {
    // Login failed - display error message
    echo "<p style='color: red;'>Invalid username or password.</p>";
  }
}

?>
