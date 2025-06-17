

<?php

// Database credentials (replace with your actual values)
$db_host = "localhost";
$db_name = "your_database_name";
$db_user = "your_username";
$db_pass = "your_password";

// Function to handle user login
function loginUser($username, $password) {
  // Connect to the database
  $conn = new mysqli($db_host, $db_user, $db_pass, $db_name);

  // Check connection
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  // Prepare the SQL query
  $sql = "SELECT * FROM users WHERE username = ? AND password = ?";

  // Prepare statement
  $stmt = $conn->prepare($sql);

  // Bind parameters
  $stmt->bind_param("ss", $username, $password);

  // Execute the statement
  if ($stmt->execute() === TRUE) {
    // Get the result
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
      $row = $result->fetch_assoc();
      // Session variables for user data
      $_SESSION["user_id"] = $row["id"];
      $_SESSION["username"] = $row["username"];
      return true; // Login successful
    } else {
      return false; // Login failed (wrong credentials)
    }
  } else {
    return false; // Login failed (SQL error)
  }

  // Close the statement and connection
  $stmt->close();
  $conn->close();
}


// Example usage (in a form submission)

// Check if the login form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Get form data
  $username = $_POST["username"];
  $password = $_POST["password"];

  // Validate input (important for security - added below)
  if (empty($username) || empty($password)) {
      echo "Username and Password must be filled.";
  } else {
    // Call the loginUser function
    if (loginUser($username, $password)) {
      // Redirect to a secure page (e.g., the dashboard)
      header("Location: dashboard.php"); // Replace dashboard.php with your desired page
      exit();
    } else {
      echo "Invalid username or password.";
    }
  }
}

?>
