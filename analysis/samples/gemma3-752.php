

<?php

// Database configuration (Replace with your actual credentials)
$db_host = "localhost";
$db_name = "your_database_name";
$db_user = "your_username";
$db_password = "your_password";

// Function to handle user login
function loginUser($username, $password) {
  // Connect to the database
  $conn = new mysqli($db_host, $db_user, $db_password, $db_name);

  // Check connection
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  // Prepare the SQL query
  $query = "SELECT * FROM users WHERE username = ? AND password = ?";

  // Use prepared statement to prevent SQL injection
  $stmt = $conn->prepare($query);
  $stmt->bind_param("ss", $username, $password); // "ss" means two string parameters

  // Execute the query
  if ($stmt->execute() === TRUE) {
    $result = $stmt->get_result();
    $row = $result->fetch_assoc(); // Fetch the result as an associative array

    // Check if a user was found
    if ($row) {
      // User found, set session variables
      $_SESSION['user_id'] = $row['id'];  // Assuming you have an 'id' column in your users table
      $_SESSION['username'] = $row['username'];
      return true; // Login successful
    } else {
      return false; // User not found
    }
  } else {
    // Handle query error
    return false;
  }

  // Close the statement and connection
  $stmt->close();
  $conn->close();
}


// Example Usage (for demonstration - you'll need a form to submit these)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $username = $_POST["username"];
  $password = $_POST["password"];

  if (loginUser($username, $password)) {
    // User logged in successfully
    echo "Login successful!  Redirecting...";
    header("Location: user_dashboard.php"); // Redirect to a user dashboard
    exit();
  } else {
    echo "Invalid username or password.";
  }
}
?>
