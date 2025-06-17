

<?php

// Database connection details (Replace with your actual credentials)
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

  // SQL query to authenticate the user
  $sql = "SELECT id, username, password FROM users WHERE username = ? AND password = ?";

  // Prepare the statement
  $stmt = $conn->prepare($sql);

  if ($stmt) {
    // Bind the parameters
    $stmt->bind_param("ss", $username, $password); // 's' denotes string

    // Execute the statement
    if ($stmt->execute()) {
      // Get the result
      $result = $stmt->get_result();

      if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();
        // User found, set session variables
        $_SESSION['user_id'] = $row['id'];
        $_SESSION['username'] = $row['username'];

        return true; // Login successful
      } else {
        return false; // User not found
      }
    } else {
      return false; // Execute failed
    }
  } else {
    return false; // Prepare failed
  }

  // Close the statement and connection
  $stmt->close();
  $conn->close();
  return false;
}

// Example usage:
if (isset($_POST['login_username']) && isset($_POST['login_password'])) {
  $username = $_POST['login_username'];
  $password = $_POST['login_password'];

  if (loginUser($username, $password)) {
    // Login successful, redirect to a protected page or display a welcome message
    echo "<h1>Welcome, " . $_SESSION['username'] . "</h1>";
    // Redirect if needed
    // header("Location: protected_page.php");
    // exit();
  } else {
    // Login failed, display an error message
    echo "<p style='color:red;'>Invalid username or password.</p>";
  }
}

?>
