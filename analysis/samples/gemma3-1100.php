

<?php

// Database credentials (replace with your actual credentials)
$db_host = "localhost";
$db_name = "your_database_name";
$db_user = "your_database_username";
$db_password = "your_database_password";

// Session handling
session_start();

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

  // Bind the parameters for security (important to prevent SQL injection)
  $stmt = $conn->prepare($sql);
  $stmt->bind_param("ss", $username, $password); // "ss" indicates two string parameters

  // Execute the query
  if ($stmt->execute()) {
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
      $row = $result->fetch_assoc();
      // Set session variables for the logged-in user
      $_SESSION['user_id'] = $row['id'];
      $_SESSION['username'] = $row['username'];
      return true; // Login successful
    } else {
      return false; // No user found or incorrect password
    }
  } else {
    return false; // Error executing query
  }

  // Close the statement and connection
  $stmt->close();
  $conn->close();
}


// Example Usage (Demonstration -  This would typically be within a form submission)
if (isset($_POST['username']) && isset($_POST['password'])) {
  $username = $_POST['username'];
  $password = $_POST['password'];

  if (loginUser($username, $password)) {
    echo "Login successful! You are now logged in as " . $_SESSION['username'];
    // Redirect to a protected page or display a welcome message
    header("Location: protected_page.php"); // Replace with your protected page
    exit();
  } else {
    echo "Invalid username or password.";
  }
}
?>
