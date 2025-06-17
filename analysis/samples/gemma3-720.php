   

   * **`password_hash($password, PASSWORD_BCRYPT)`**:  This function generates a secure, salted hash of the user's password before storing it in the database.

4. **Register Form (register.php):** Create a `register.php` file to allow new users to create accounts.  Use `password_hash()` to hash their passwords before storing them in the database.

5. **Protected Page (protected_page.php):**  Create a `protected_page.php` (or whatever you choose) to display content only for logged-in users.  Check if `$_SESSION["username"]` is set before allowing access.

Example `register.php` (Very basic - expand for real-world use):



<?php

// Database credentials (Replace with your actual values)
$db_host = "localhost";
$db_name = "your_database_name";
$db_user = "your_username";
$db_password = "your_password";

// Function to handle user login
function loginUser($username, $password) {
  // Connect to the database
  $conn = new mysqli($db_host, $db_user, $db_password, $db_name);

  // Check connection
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  // Prepare the SQL query
  $sql = "SELECT id, username, password FROM users WHERE username = ? AND password = ?";

  // Prepare the statement
  $stmt = $conn->prepare($sql);

  // Bind the parameters
  $stmt->bind_param("ss", $username, $password); // "ss" indicates two string parameters

  // Execute the statement
  if ($stmt->execute()) {
    // Get the result
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
      // User found, extract data
      $row = $result->fetch_assoc();
      $user_id = $row['id'];
      $user_name = $row['username'];

      // Session management (important for security)
      session_start();
      $_SESSION['user_id'] = $user_id;
      $_SESSION['username'] = $user_name;

      // Redirect to a protected page or display a success message
      header("Location: protected_page.php"); // Replace with your protected page
      exit(); // Important to stop further execution

    } else {
      // User not found
      return false;
    }
  } else {
    // Error executing the query
    return false;
  }

  // Close the statement and connection
  $stmt->close();
  $conn->close();
}


// Example usage (Simplified - usually this would be from a form submission)
//  You would get the username and password from a form, then call the function
//  like this:
//  if (isset($_POST['username']) && isset($_POST['password'])) {
//    $username = $_POST['username'];
//    $password = $_POST['password'];
//    loginUser($username, $password);
//  }

?>
