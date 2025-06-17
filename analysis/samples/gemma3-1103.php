

<?php

// Database configuration (replace with your actual values)
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
  $sql = "SELECT id, username, password FROM users WHERE username = '$username'";
  $stmt = $conn->prepare($sql);

  // Execute the query
  $stmt->execute();

  // Store the result
  $result = $stmt->get_result();

  // Check if a user was found
  if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
    // Verify the password
    if (password_verify($password, $user['password'])) {
      // Login successful - set session variables
      $_SESSION['user_id'] = $user['id'];
      $_SESSION['username'] = $user['username'];

      return true; // Indicate successful login
    } else {
      return false; // Indicate incorrect password
    }
  } else {
    return false; // Indicate user not found
  }

  // Close the database connection
  $stmt->close();
  $conn->close();
}


// Example usage:

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Get username and password from the form
  $username = $_POST["username"];
  $password = $_POST["password"];

  // Validate input (important for security - added basic validation)
  if (empty($username) || empty($password)) {
    echo "<p style='color:red;'>Username and password cannot be empty.</p>";
  } else {
    // Call the loginUser function
    if (loginUser($username, $password)) {
      // Login successful - redirect to the user's dashboard or homepage
      header("Location: dashboard.php");
      exit();
    } else {
      echo "<p style='color:red;'>Invalid username or password.</p>";
    }
  }
}
?>
