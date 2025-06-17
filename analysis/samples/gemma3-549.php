

<?php

// Database credentials (replace with your actual values)
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

  // Prepare the statement
  $stmt = $conn->prepare($sql);

  // Bind the parameters
  $stmt->bind_param("ss", $username, $password); // "ss" indicates two string parameters

  // Execute the statement
  if ($stmt->execute()) {
    // Get the result
    $result = $stmt->get_result();

    // Check if any rows were returned
    if ($result->num_rows > 0) {
      $row = $result->fetch_assoc();

      // Check if the user exists and is active
      if ($row['status'] == 'active') {
        // Successful login
        $_SESSION['user_id'] = $row['id'];
        $_SESSION['username'] = $row['username'];
        return true;
      } else {
        return false; // User exists but inactive
      }
    } else {
      return false; // No user found with these credentials
    }
  } else {
    // Error executing the statement
    return false;
  }

  // Close the statement and connection
  $stmt->close();
  $conn->close();
}


// Example usage:

// Check if the login form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $username = $_POST["username"];
  $password = $_POST["password"];

  // Validate input (important for security)
  if (empty($username) || empty($password)) {
    $error = "Username and password cannot be empty.";
  } else {
    // Call the login function
    $login_result = loginUser($username, $password);

    if ($login_result) {
      // Login successful - redirect to a secure page or display a welcome message
      header("Location: /secure_page.php"); // Replace with your desired secure page URL
      exit();
    } else {
      $error = "Invalid username or password.";
    }
  }
}

?>
