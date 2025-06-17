

<?php

// Database configuration (Replace with your actual values)
$host = "localhost";
$username = "your_username";
$password = "your_password";
$database = "your_database";

// Function to check user credentials
function loginUser($username, $password) {
  // Connect to the database
  $conn = new mysqli($host, $username, $password, $database);

  // Check connection
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  // Prepare the SQL query
  $sql = "SELECT * FROM users WHERE username = ? AND password = ?";
  $stmt = $conn->prepare($sql);

  // Bind the parameters
  $stmt->bind_param("ss", $username, $password); // "ss" indicates two strings

  // Execute the query
  if ($stmt->execute() === TRUE) {
    // Get the result
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
      // User found, retrieve user data
      $user = $result->fetch_assoc();
      // Set session variables
      $_SESSION['username'] = $user['username'];
      $_SESSION['user_id'] = $user['id']; // Assuming you have an 'id' column
      return true;
    } else {
      return false; // User not found
    }
  } else {
    // Error executing query
    return false;
  }

  // Close the statement and connection
  $stmt->close();
  $conn->close();
}


// Example Usage (for demonstration - handle this in your actual web form)
if (isset($_POST['username']) && isset($_POST['password'])) {
  $username = $_POST['username'];
  $password = $_POST['password'];

  if (loginUser($username, $password)) {
    // Login successful, redirect to a secure page or display a welcome message
    header("Location: /protected_page.php"); // Replace with your secure page URL
    exit();
  } else {
    // Login failed, display an error message
    echo "<p style='color: red;'>Invalid username or password.</p>";
  }
}

?>
