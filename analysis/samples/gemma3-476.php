

<?php

// Database Configuration (Replace with your actual credentials)
$db_host = "localhost";
$db_name = "your_database_name";
$db_user = "your_database_user";
$db_password = "your_database_password";

// Function to handle user login
function loginUser($username, $password) {
  // Connect to the database
  $conn = new mysqli($db_host, $db_user, $db_password, $db_name);

  // Check connection
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  // Prepare the SQL query
  $sql = "SELECT * FROM users WHERE username = ? AND password = ?";

  // Prepare the statement
  $stmt = $conn->prepare($sql);

  // Bind the parameters
  $stmt->bind_param("ss", $username, $password);  // "ss" for string, string

  // Execute the statement
  if ($stmt->execute()) {
    // Get the result
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
      $row = $result->fetch_assoc();
      // Set session variables to indicate login
      $_SESSION['user_id'] = $row['id'];  // Assuming you have an 'id' column in your users table
      $_SESSION['username'] = $row['username'];
      return true;  // Login successful
    } else {
      return false; // No user found with those credentials
    }

  } else {
    // Handle SQL errors
    error_log("SQL error: " . $conn->error); // Log the error for debugging
    return false;
  }

  // Close the statement and connection
  $stmt->close();
  $conn->close();
}

// Example usage:

// Check if the login form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Get username and password from the form
  $username = $_POST["username"];
  $password = $_POST["password"];

  // Validate input (important for security)
  if (empty($username) || empty($password)) {
    echo "Username and password cannot be empty.";
  } else {
    // Call the loginUser function
    if (loginUser($username, $password)) {
      // Login successful, redirect to a protected page or display a welcome message
      header("Location: protected_page.php"); // Redirect to a secure page
      exit();
    } else {
      // Login failed, display an error message
      echo "Invalid username or password.";
    }
  }
}

?>
