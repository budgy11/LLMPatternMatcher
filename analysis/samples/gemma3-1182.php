

<?php

// Database connection details (Replace with your actual credentials)
$host = "localhost";
$username = "your_username";
$password = "your_password";
$database = "your_database";

// Function to handle user login
function loginUser($username, $password) {
  // Connect to the database
  $conn = new mysqli($host, $username, $password, $database);

  // Check connection
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  // Prepare the SQL query
  $sql = "SELECT * FROM users WHERE username = ? AND password = ?";

  // Prepare statement
  $stmt = $conn->prepare($sql);

  // Bind parameters
  $stmt->bind_param("ss", $username, $password); // "ss" indicates two string parameters

  // Execute the query
  if ($stmt->execute() === TRUE) {
    // Get the result
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
      $row = $result->fetch_assoc();
      // If the user exists, you can now access their data
      // You might want to set a session or other authentication mechanism here
      session_start(); // Start the session (important for storing user information)
      $_SESSION['user_id'] = $row['id'];
      $_SESSION['username'] = $row['username'];
      // You can add more data from the row here if needed, like $row['email']
      return true; // Login successful
    } else {
      return false; // Login failed (username or password incorrect)
    }

  } else {
    // Error executing query
    return false;
  }

  // Close the statement and connection
  $stmt->close();
  $conn->close();

  return false; //Should not reach here if everything is done correctly
}

// Example usage (This is just an example - implement appropriate form handling)
if (isset($_POST['username']) && isset($_POST['password'])) {
  $username = $_POST['username'];
  $password = $_POST['password'];

  if (loginUser($username, $password)) {
    echo "Login successful!  Redirecting...";
    // Redirect to a protected page or home page
    header("Location: /protected_page.php"); // Replace with your protected page URL
    exit();
  } else {
    echo "Invalid username or password.";
  }
}
?>
