

<?php

/**
 * This function handles user login functionality.
 *
 * @param string $username The username provided by the user.
 * @param string $password The password provided by the user.
 * @return int|string Returns the user ID on successful login, or an error message on failure.
 */
function loginUser(string $username, string $password) {
  // **IMPORTANT: Replace this with your actual database connection and authentication logic.**
  // This is just a basic example for demonstration purposes.

  // Example Database Credentials - DO NOT USE THIS IN A REAL APPLICATION!
  $db_host = "localhost";
  $db_name = "your_database_name";
  $db_user = "your_db_user";
  $db_password = "your_db_password";


  // Connect to the database
  $conn = new mysqli($db_host, $db_user, $db_password, $db_name);

  // Check connection
  if ($conn->connect_error) {
    return "Error: " . $conn->connect_error;
  }

  // SQL query to check username and password
  $sql = "SELECT id, username FROM users WHERE username = '$username' AND password = '$password'";

  $result = $conn->query($sql);

  if ($result->num_rows > 0) {
    // User found, retrieve data
    $user_data = $result->fetch_assoc();
    $user_id = $user_data['id'];
    $username = $user_data['username'];

    // Session management -  This is a *basic* example.  Use a more robust solution in production.
    session_start();
    $_SESSION['user_id'] = $user_id;
    $_SESSION['username'] = $username;


    // Redirect the user (optional, you can return the user ID instead)
    // header("Location: /home.php"); // Replace /home.php with your desired landing page

    return $user_id; // Return the user ID
  } else {
    return "Invalid username or password.";
  }

  $conn->close();
}

// --- Example Usage (Demonstration - For testing only) ---
// To test this function, you'll need to call it and handle the response.
// This is just for demonstration; don't run this directly in a web page.

// Simulate a user login attempt:
// $username = "testuser";
// $password = "password123";

// $result = loginUser($username, $password);

// if (is_numeric($result)) {
//   echo "Login successful! User ID: " . $result . "<br>";
// } else {
//   echo $result . "<br>";
// }
?>
