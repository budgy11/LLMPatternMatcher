        <label for="username">Username:</label>
        <input type="text" id="username" name="username" required>

        <br><br>

        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required>

        <br><br>

        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required>

        <br><br>

        <button type="submit">Register</button>
    </form>

</body>
</html>


<?php

// Database connection details (replace with your actual details)
$db_host = "localhost";
$db_name = "your_database_name";
$db_user = "your_db_user";
$db_pass = "your_db_password";

// Function to register a new user
function registerUser($username, $password, $email) {
  // 1. Connect to the database
  $conn = new mysqli($db_host, $db_user, $db_pass, $db_name);

  // Check for connection errors
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  // 2.  Prepare the SQL query to insert the new user data
  $hashed_password = password_hash($password, PASSWORD_DEFAULT); // Hash the password for security

  $sql = "INSERT INTO users (username, password, email) VALUES (?, ?, ?)";

  // Prepare the statement
  $stmt = $conn->prepare($sql);

  // Bind the parameters
  $stmt->bind_param("ssi", $username, $hashed_password, $email);

  // Execute the query
  if ($stmt->execute()) {
    // 3.  Registration successful
    $user_id = $conn->insert_id;
    return $user_id; // Return the ID of the newly created user
  } else {
    // 4.  Registration failed
    echo "Error: " . $sql . "<br>" . $conn->error;
    return -1; // Return -1 to indicate an error
  }

  // Close the statement and connection
  $stmt->close();
  $conn->close();
}

// Example Usage (in your HTML form, after the form is submitted)
// $username = $_POST["username"];
// $password = $_POST["password"];
// $email = $_POST["email"];

// if (isset($username) && isset($password) && isset($email)) {
//   $user_id = registerUser($username, $password, $email);
//   if ($user_id > 0) {
//     echo "Registration successful! User ID: " . $user_id;
//   } else {
//     echo "Registration failed.";
//   }
// }
?>
