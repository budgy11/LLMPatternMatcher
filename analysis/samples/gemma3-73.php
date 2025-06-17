

<?php

// Database credentials (replace with your actual credentials)
$username = "your_username";
$password = "your_password";
$db_host = "localhost";
$db_name = "your_database_name";

// Function to handle user login
function loginUser($username, $password) {
  // Connect to the database
  $conn = new mysqli($db_host, $username, $password, $db_name);

  // Check connection
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  // Prepare the SQL query
  $query = "SELECT id, username, password FROM users WHERE username = ? AND password = ?";
  $stmt = $conn->prepare($query);

  if ($stmt) {
    // Bind parameters
    $stmt->bind_param("ss", $username, $password); // "ss" indicates two string parameters

    // Execute the query
    if ($stmt->execute() === TRUE) {
      // Get result
      $result = $stmt->get_result();

      if ($result->num_rows == 1) {
        // User found - retrieve user data
        $row = $result->fetch_assoc();
        $_SESSION['username'] = $row['username']; // Store username in session
        $_SESSION['user_id'] = $row['id']; //  Store user ID in session (recommended)

        // Redirect to a secure page or display a success message
        header("Location: secure_page.php"); // Redirect to a secure page
        exit(); // Terminate the script execution after redirection

      } else {
        // User not found or incorrect password
        return false;
      }

      $stmt->close();
    } else {
      // Query execution failed
      return false;
    }
  } else {
    // Prepare statement failed
    return false;
  }
}

// Example usage (from a form submission)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $username = $_POST["username"];
  $password = $_POST["password"];

  if (loginUser($username, $password)) {
    // Login successful - redirect
    // You can also perform other actions here, like displaying a welcome message
  } else {
    // Login failed - display an error message
    echo "<p style='color: red;'>Invalid username or password.</p>";
  }
}

?>
