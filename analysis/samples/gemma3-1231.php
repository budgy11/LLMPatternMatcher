
    <form method="post" action="">
        Username: <input type="text" name="username" />
        Password: <input type="password" name="password" />
        <input type="submit" value="Login" />
    </form>

</body>
</html>


<?php

// Database credentials (Replace with your actual credentials)
$db_host = "localhost";
$db_name = "your_database_name";
$db_user = "your_db_user";
$db_pass = "your_db_password";

// Function to handle user login
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
      $row = $result->fetch_assoc();
      // Session management:  Store user information in session
      $_SESSION['user_id'] = $row['id'];
      $_SESSION['username'] = $row['username'];
      return true; // Login successful
    } else {
      return false; // No user found with those credentials
    }
  } else {
    // Handle the error
    echo "Error executing query: " . $conn->error;
    return false;
  }

  // Close the statement and connection
  $stmt->close();
  $conn->close();
}

// Example Usage (Handle Login Form Submission)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Get form data
  $username = $_POST["username"];
  $password = $_POST["password"];

  // Validate input (Important for security - more robust validation is recommended)
  if (empty($username) || empty($password)) {
    echo "Username and password cannot be empty.";
  } else {
    // Call the loginUser function
    if (loginUser($username, $password)) {
      // Redirect to a protected page or display a welcome message
      header("Location: /protected_page.php"); // Replace with your protected page URL
      exit();
    } else {
      echo "Invalid username or password.";
    }
  }
}

//  Display login form (only displayed on the initial page)
?>
