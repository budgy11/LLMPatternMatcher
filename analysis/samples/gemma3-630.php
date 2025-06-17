

<?php

// Database configuration (Replace with your actual values)
$db_host = "localhost";
$db_name = "your_database_name";
$db_user = "your_db_user";
$db_password = "your_db_password";

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
  $stmt->bind_param("ss", $username, $password);

  // Execute the statement
  if ($stmt->execute()) {
    // Get the result
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
      $user = $result->fetch_assoc();
      // Authentication successful
      $_SESSION['user_id'] = $user['id'];
      $_SESSION['username'] = $user['username'];
      return true; // Indicate successful login
    } else {
      // No user found
      return false;
    }
  } else {
    // Query error
    return false;
  }

  // Close the statement and connection
  $stmt->close();
  $conn->close();
}


// Example Usage (in your form or other logic)
// Assuming you have a form with input fields 'username' and 'password'

// Check if the user is already logged in
if (isset($_SESSION['user_id'])) {
  echo "User is already logged in as " . $_SESSION['username'] . "<br>";
} else {
  // Check if the login form has been submitted
  if (isset($_POST['login_username']) && isset($_POST['login_password'])) {
    $username = $_POST['login_username'];
    $password = $_POST['login_password'];

    // Call the loginUser function
    if (loginUser($username, $password)) {
      echo "Login successful!";
      // Redirect to a protected page or display a welcome message
    } else {
      echo "Invalid username or password.";
    }
  }
}

?>
