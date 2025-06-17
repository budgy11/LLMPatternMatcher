

<?php

// Database configuration (replace with your actual credentials)
$host = "localhost";
$username = "your_username";
$password = "your_password";
$database = "your_database";

// Function to handle login
function login($username, $password) {
  // Connect to the database
  $conn = new mysqli($host, $username, $password, $database);

  // Check connection
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  // Prepare the SQL query
  $sql = "SELECT * FROM users WHERE username = ? AND password = ?";

  // Use prepared statement to prevent SQL injection
  $stmt = $conn->prepare($sql);

  if ($stmt) {
    // Bind the parameters
    $stmt->bind_param("ss", $username, $password);  // "ss" means two strings

    // Execute the query
    if ($stmt->execute()) {
      // Get the result
      $result = $stmt->get_result();

      if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        // Successful login
        session_start(); // Start the session
        $_SESSION['username'] = $username; // Store username in session
        $_SESSION['user_id'] = $row['id']; // Store user ID if available
        return true;
      } else {
        return false;  // No matching user found
      }
    } else {
      // Error executing the query
      return false;
    }

    $stmt->close();
  } else {
    // Error preparing the statement
    return false;
  }
}

// Example usage (Login Form - HTML)
//  You would have an HTML form with a username and password input fields.
//  When the form is submitted, you would call the login function.

// Example usage (Login Form - PHP processing)
/*
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $username = $_POST["username"];
  $password = $_POST["password"];

  if (login($username, $password)) {
    // Login successful
    echo "Login successful!  Redirecting...";
    header("Location: welcome.php"); // Redirect to a welcome page
    exit();
  } else {
    // Login failed
    echo "Invalid username or password.";
  }
}
*/

?>
