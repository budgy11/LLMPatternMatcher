
</body>
</html>


<?php

// Database configuration (replace with your actual credentials)
$db_host = "localhost";
$db_name = "your_database_name";
$db_user = "your_username";
$db_pass = "your_password";

// Function to handle login
function login($username, $password) {
  // Connect to the database
  $conn = new mysqli($db_host, $db_user, $db_pass, $db_name);

  // Check connection
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  // Prepare the SQL query
  $sql = "SELECT * FROM users WHERE username = ? AND password = ?";

  // Bind the values to prevent SQL injection
  $stmt = $conn->prepare($sql);
  $stmt->bind_param("ss", $username, $password); // "ss" indicates two string parameters

  // Execute the query
  if ($stmt->execute() === TRUE) {
    // Get the result
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
      // User found, fetch the data
      $row = $result->fetch_assoc();
      // Session handling (recommended way)
      session_start();
      $_SESSION['user_id'] = $row['id']; // Store user ID in session
      $_SESSION['username'] = $row['username'];

      // Optionally, redirect to a protected page
      // header("Location: secured_page.php");
      // exit();

      return true; // Login successful
    } else {
      return false; // User not found
    }

  } else {
    return false; // Error executing query
  }

  // Close the statement and connection
  $stmt->close();
  $conn->close();
}


// Example usage (you'll need a form to submit username and password)
// You'd typically place this in your login.php file.

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $username = $_POST["username"];
  $password = $_POST["password"];

  if (login($username, $password)) {
    echo "Login successful! You are now logged in.";
    // Redirect to a protected page or display welcome message.
  } else {
    echo "Invalid username or password.";
  }
} else {
  // If not a POST request, likely a form with empty fields.
  // You can display a login form here.
}
?>
