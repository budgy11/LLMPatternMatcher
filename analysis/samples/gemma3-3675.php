
</body>
</html>


<?php

// Database configuration (Replace with your actual credentials)
$db_host = "localhost";
$db_user = "your_db_user";
$db_password = "your_db_password";
$db_name = "your_db_name";

// Function to register a user
function registerUser($username, $password, $email) {
  // 1. Connect to the database
  $conn = new mysqli($db_host, $db_user, $db_password, $db_name);

  // Check connection
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  // 2. Prepare the SQL query
  $hashed_password = password_hash($password, PASSWORD_DEFAULT); // Hash the password

  $sql = "INSERT INTO users (username, password, email) VALUES ('$username', '$hashed_password', '$email')";

  // 3. Execute the query
  if ($conn->query($sql) === TRUE) {
    return true; // Registration successful
  } else {
    return false; // Registration failed
  }

  // 4. Close the connection (Important!)
  $conn->close();
}


// --- Example Usage (Handle form submission) ---
//  This part simulates a form submission.  In a real application, 
//  this would be handled by an HTML form and PHP code.

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Get form data
  $username = $_POST["username"];
  $password = $_POST["password"];
  $email = $_POST["email"];

  // Validate the data (Important for security - do this in a real app)
  if (empty($username) || empty($password) || empty($email)) {
    echo "All fields are required.";
  } elseif (strlen($username) < 3) {
    echo "Username must be at least 3 characters long.";
  } else {
    // Register the user
    if (registerUser($username, $password, $email)) {
      echo "Registration successful! You have been redirected.";
      // Redirect to a welcome page or login page
      header("Location: welcome.php"); // Replace welcome.php with your appropriate redirect
      exit();
    } else {
      echo "Registration failed. Please try again.";
    }
  }
}


// --- HTML Form (example -  place this in a separate HTML file) ---
?>
