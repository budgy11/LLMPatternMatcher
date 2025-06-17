

<?php

// Database credentials (replace with your actual values)
$db_host = "localhost";
$db_name = "your_database_name";
$db_user = "your_database_username";
$db_pass = "your_database_password";

// Function to handle the login process
function loginUser($username, $password) {
  // 1. Validate Input (Important for security!)
  $username = trim($username);
  $password = trim($password);

  if (empty($username) || empty($password)) {
    return false; // Or return an error message
  }

  // 2. Database Connection
  $conn = new mysqli($db_host, $db_user, $db_pass, $db_name);

  // Check connection
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  // 3. SQL Query (Important:  Use Prepared Statements!)
  $sql = "SELECT * FROM users WHERE username = ? AND password = ?"; // Use parameterized query

  // 4. Prepare Statement (Crucial for Security)
  $stmt = $conn->prepare($sql);

  if (!$stmt) {
    die("Error preparing statement: " . $conn->error);
  }

  // 5. Bind Parameters
  $stmt->bind_param("ss", $username, $password); // "ss" indicates two string parameters

  // 6. Execute Query
  if ($stmt->execute()) {
    // 7. Fetch Result
    $result = $stmt->get_result();
    if ($result->num_rows == 1) {
      $row = $result->fetch_assoc();
      // 8.  Session Management (Example)
      session_start();
      $_SESSION['user_id'] = $row['id'];
      $_SESSION['username'] = $row['username'];
      $stmt->close();
      return true;
    } else {
      $stmt->close();
      return false;
    }
  } else {
    $stmt->close();
    return false;
  }
}


// Example Usage (This is for demonstration;  You'll likely use a form)
if (isset($_POST['username']) && isset($_POST['password'])) {
  $username = $_POST['username'];
  $password = $_POST['password'];

  if (loginUser($username, $password)) {
    echo "Login successful!  You are now logged in.";
    // Redirect to a protected page or display a welcome message.
    header("Location: protected_page.php"); // Replace with your protected page
    exit();
  } else {
    echo "Invalid username or password.";
  }
}
?>
