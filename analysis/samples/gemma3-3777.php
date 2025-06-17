    <br><br>

    <button type="submit">Register</button>
</form>

</body>
</html>


<?php

// Database credentials (replace with your actual values)
$db_host = "localhost";
$db_user = "your_db_user";
$db_password = "your_db_password";
$db_name = "your_db_name";

// Function to register a new user
function registerUser($username, $password, $email) {
  // 1. Connect to the database
  $conn = new mysqli($db_host, $db_user, $db_password, $db_name);

  // Check the connection
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  // 2. Prepare the SQL query
  $sql = "INSERT INTO users (username, password, email) VALUES (?, ?, ?)";

  // Use prepared statements to prevent SQL injection
  $stmt = $conn->prepare($sql);

  if ($stmt) {
    // Bind the parameters
    $stmt->bind_param("sss", $username, $password, $email);

    // Execute the query
    if ($stmt->execute()) {
      // Success!
      $user_id = $conn->insert_id; // Get the last inserted ID
      $stmt->close();
      $conn->close();

      // Optionally, send a confirmation email here
      // Example: sendEmail($email, "Welcome!", "...");

      return $user_id; // Return the user ID for future use
    } else {
      // Error executing query
      $error = $stmt->error;
      $stmt->close();
      $conn->close();
      return false;
    }
  } else {
    // Error preparing statement
    $stmt->close();
    $conn->close();
    return false;
  }
}


// Example Usage (Simplified -  This would typically be from a form submission)
// $username = $_POST['username'];
// $password = $_POST['password'];
// $email = $_POST['email'];

// if (isset($username) && isset($password) && isset($email)) {
//   $user_id = registerUser($username, $password, $email);

//   if ($user_id) {
//     echo "Registration successful! User ID: " . $user_id;
//   } else {
//     echo "Registration failed.";
//   }
// } else {
//   echo "Please fill in all fields.";
// }

?>
