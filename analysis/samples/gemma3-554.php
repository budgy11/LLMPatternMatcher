

<?php

// Database credentials - **IMPORTANT:  NEVER HARDCODE THESE IN A PRODUCTION ENVIRONMENT**
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

  // Prepare the statement
  $stmt = $conn->prepare($sql);

  // Bind the parameters
  $stmt->bind_param("ss", $username, $password); // "ss" indicates two string parameters

  // Execute the query
  if ($stmt->execute() === TRUE) {
    // Get the result
    $result = $stmt->get_result();

    // Check if any rows were returned
    if ($result->num_rows > 0) {
      // User found -  Fetch the user data
      $user_data = $result->fetch_assoc();

      //  In a real application, you'd likely store more user data
      //  (e.g., user ID, role, etc.)

      //  For demonstration purposes, we'll return the username
      return $user_data['username'];  // Return the username

    } else {
      return null; // User not found
    }

  } else {
    // Query execution failed
    return null;
  }

  // Close the statement and connection
  $stmt->close();
  $conn->close();
}


// Example usage:
// Get the username and password from the form (or wherever they come from)
$username = $_POST['username'];
$password = $_POST['password'];

// Perform the login
$result = loginUser($username, $password);

// Check the result
if ($result) {
  // User is logged in
  echo "Login successful! Welcome, " . $result . "!";
} else {
  // Login failed
  echo "Invalid username or password.";
}

?>
