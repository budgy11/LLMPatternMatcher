

<?php

// Database credentials (replace with your actual details)
$host = "localhost";
$username = "your_db_username";
$password = "your_db_password";
$database = "your_db_name";

// Function to handle user login
function loginUser($username, $password) {
  // Connect to the database
  $conn = new mysqli($host, $username, $password, $database);

  // Check connection
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  // SQL query to check for the username and password
  $sql = "SELECT * FROM users WHERE username = '$username' AND password = '$password'";

  $result = $conn->query($sql);

  if ($result->num_rows > 0) {
    // User found, set session variables for authentication
    $user = $result->fetch_assoc();
    $_SESSION['user_id'] = $user['id']; // Assuming you have an 'id' column in your users table
    $_SESSION['username'] = $user['username'];
    return true; // Login successful
  } else {
    return false; // Login failed
  }

  $conn->close();
}


// Example Usage (assuming you have a form to submit username and password)
//  (This section is for demonstration only.  In a real application, you would
//   process the form data and call the loginUser() function.)

//  Check if the login form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Get username and password from the form
  $username = $_POST["username"];
  $password = $_POST["password"];

  // Validate the input (important to prevent SQL injection and other issues)
  if (empty($username) || empty($password)) {
    $error = "Username and password cannot be empty.";
  } else {
    // Call the loginUser function
    $login_result = loginUser($username, $password);

    if ($login_result) {
      // Login successful, redirect to a protected page
      header("Location: /protected_page.php"); //  Replace with your protected page URL
      exit();
    } else {
      $error = "Invalid username or password.";
    }
  }
}

// Display any error messages
if (isset($error)) {
  echo "<p style='color:red;'>$error</p>";
}
?>
