

<?php

// Database credentials (Replace with your actual values)
$db_host = 'localhost';
$db_name = 'your_database_name';
$db_user = 'your_username';
$db_pass = 'your_password';

// Function to handle login
function loginUser($username, $password) {
  // Connect to the database
  $conn = new mysqli($db_host, $db_user, $db_pass, $db_name);

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
  if ($stmt->execute() === TRUE) {
    // Get the result
    $result = $stmt->get_result();

    // Check if any rows were returned
    if ($result->num_rows > 0) {
      // Fetch the row
      $user = $result->fetch_assoc();

      // Successful login
      // You can store session information here
      // Example:
      // $_SESSION['user_id'] = $user['id'];
      // $_SESSION['username'] = $user['username'];

      // Redirect the user
      header("Location: user_dashboard.php"); // Replace with your desired redirect page
      exit();

    } else {
      // Login failed
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


// Example Usage (From a login form submission)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $username = $_POST["username"];
  $password = $_POST["password"];

  // Validate input (Crucial for security!)
  if (empty($username) || empty($password)) {
    echo "Username and password cannot be empty!";
  } else {
    // Call the login function
    if (loginUser($username, $password)) {
      // Login successful, redirect here
    } else {
      // Login failed, display an error message
      echo "Invalid username or password!";
    }
  }
}

?>
