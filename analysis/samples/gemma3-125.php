

<?php
session_start(); // Start the session

/**
 *  Login User
 *
 *  This function handles the login process.
 *
 *  @param string $username  The username entered by the user.
 *  @param string $password The password entered by the user.
 *  @return bool True if login was successful, false otherwise.
 */
function loginUser($username, $password) {
  // *** IMPORTANT: Replace with your actual database credentials ***
  $db_host = "localhost";
  $db_name = "your_database_name";
  $db_user = "your_db_user";
  $db_password = "your_db_password";

  // Validate inputs (very basic - add more robust validation in a real application)
  if (empty($username) || empty($password)) {
    return false;
  }

  // Connect to the database
  $conn = new mysqli($db_host, $db_user, $db_password, $db_name);

  // Check connection
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  // Prepare the SQL query
  $sql = "SELECT id, username, password FROM users WHERE username = ? LIMIT 1";
  $stmt = $conn->prepare($sql);

  if ($stmt === false) {
    die("Error preparing statement: " . $conn->error);
  }

  // Bind the username to the prepared statement
  $stmt->bind_param("s", $username);

  // Execute the statement
  if (!$stmt->execute()) {
    die("Error executing statement: " . $stmt->error);
  }

  // Get the result
  $result = $stmt->get_result();

  // Check if a user was found
  if ($result->num_rows == 0) {
    $stmt->close();
    $conn->close();
    return false;
  }

  $user = $result->fetch_assoc();
  $stmt->close();
  $conn->close();

  // Verify the password (important security step)
  if (password_verify($password, $user['password'])) {
    // Login successful
    $_SESSION['user_id'] = $user['id'];
    $_SESSION['username'] = $user['username'];
    return true;
  } else {
    // Incorrect password
    return false;
  }
}


// Example usage (in your HTML form submission handler)
//  (This is just an example - integrate this into your HTML form handling)
//  Assume you have a form with fields 'username' and 'password'
//  and a button to submit.

// if (isset($_POST['username']) && isset($_POST['password'])) {
//   $username = $_POST['username'];
//   $password = $_POST['password'];

//   if (loginUser($username, $password)) {
//     // Redirect to a protected page
//     header("Location: /protected_page.php"); // Replace with your protected page URL
//     exit();
//   } else {
//     // Display an error message (e.g., "Invalid username or password")
//     echo "<p>Invalid username or password.</p>";
//   }
// }
?>
