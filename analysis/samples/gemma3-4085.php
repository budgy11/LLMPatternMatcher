    <label for="username">Username:</label>
    <input type="text" id="username" name="username" required><br><br>

    <label for="email">Email:</label>
    <input type="email" id="email" name="email" required><br><br>

    <label for="password">Password:</label>
    <input type="password" id="password" name="password" required><br><br>

    <button type="submit">Register</button>
</form>

</body>
</html>


<?php

// Database configuration (replace with your actual database details)
$db_host = "localhost";
$db_user = "your_username";
$db_password = "your_password";
$db_name = "your_database_name";

// Function to register a new user
function registerUser($username, $password, $email) {
  // 1. Connect to the database
  $conn = new mysqli($db_host, $db_user, $db_password, $db_name);

  // Check the connection
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  // 2. Prepare the SQL statement
  $sql = "INSERT INTO users (username, password, email) VALUES (?, ?, ?)";

  // Prepare the statement
  $stmt = $conn->prepare($sql);

  // Bind the parameters
  $stmt->bind_param("ssi", $username, $password, $email); // "ssi" indicates string, string, string integer. Adjust if needed.

  // 3. Execute the statement
  if ($stmt->execute()) {
    return true; // Registration successful
  } else {
    return false; // Registration failed
  }

  // 4. Close the statement and connection
  $stmt->close();
  $conn->close();
}


// Example usage (This is for demonstration and testing - not a complete form)
//  This part is to show how to call the function

//  Simulating form submission
// $username = $_POST["username"];
// $password = $_POST["password"];
// $email = $_POST["email"];

// if (isset($username) && isset($password) && isset($email)) {
//    if (registerUser($username, $password, $email)) {
//      echo "Registration successful! Please check your email for verification.";
//      // Redirect to a success page or login form
//    } else {
//      echo "Registration failed. Please try again.";
//    }
// } else {
//   echo "Please fill out the registration form.";
// }

?>
