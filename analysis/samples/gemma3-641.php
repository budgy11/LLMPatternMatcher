

<?php

// Database configuration (Replace with your actual details)
$db_host = "localhost";
$db_name = "your_database_name";
$db_user = "your_db_user";
$db_pass = "your_db_password";

// Function to authenticate users
function loginUser($username, $password) {
  // Connect to the database
  $conn = new mysqli($db_host, $db_user, $db_pass, $db_name);

  // Check connection
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  // Prepare the SQL query
  $query = "SELECT id, username, password FROM users WHERE username = ?";

  // Prepare the statement
  $stmt = $conn->prepare($query);

  // Bind the parameter
  $stmt->bind_param("s", $username); 

  // Execute the query
  if ($stmt->execute()) {
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
      $row = $result->fetch_assoc();
      // Verify password (using password_verify function)
      if (password_verify($password, $row["password"])) {
        // Authentication successful
        session_start(); 

        // Store user ID and username in session
        $_SESSION['user_id'] = $row['id'];
        $_SESSION['username'] = $row['username'];

        return true; // Authentication successful
      } else {
        // Password mismatch
        return false;
      }
    } else {
      // No user found
      return false;
    }
  } else {
    // Query execution failed
    return false;
  }

  // Close the statement and connection
  $stmt->close();
  $conn->close();
}


// Example usage (for testing - don't put this in your public facing code)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $username = $_POST["username"];
  $password = $_POST["password"];

  if (loginUser($username, $password)) {
    echo "Login successful!  You are now logged in.";
    // Redirect to a protected page or do something else
    header("Location: /protected_page.php");  // Replace with your protected page URL
    exit();
  } else {
    echo "Invalid username or password.";
  }
}
?>
