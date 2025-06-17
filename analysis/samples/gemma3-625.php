
</body>
</html>


// Don't use this directly in production.  Use a proper library like `password_hash()`

// Example (Conceptual) - DO NOT USE DIRECTLY
$hashedPassword = password_hash($password, PASSWORD_DEFAULT);
$sql = "SELECT * FROM users WHERE username = '$username' AND password = '$hashedPassword'";


<?php

// Database credentials (replace with your actual credentials)
$db_host = "localhost";
$db_name = "your_database_name";
$db_user = "your_username";
$db_pass = "your_password";

// Function to handle user login
function loginUser($username, $password) {
  // 1. Connect to the database
  $conn = new mysqli($db_host, $db_user, $db_pass, $db_name);

  // Check connection
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  // 2. Prepare and execute the SQL query
  $sql = "SELECT * FROM users WHERE username = ? AND password = ?";
  $stmt = $conn->prepare($sql);

  if ($stmt) {
    $stmt->bind_param("ss", $username, $password); // "ss" indicates two string parameters
    $stmt->execute();

    // 3. Get the result
    $result = $stmt->get_result();

    // 4. Check if the user exists and the password matches
    if ($result->num_rows == 1) {
      $row = $result->fetch_assoc();
      // Success!  You can now store the user's ID or other user data
      // in a session or other storage mechanism.
      // Example:  setSessionVariable($row['id']);

      // Return success information
      return [
        "success" => true,
        "userId" => $row['id'], // Assuming 'id' is the user ID column
        "username" => $row['username']
      ];
    } else {
      // User not found or password incorrect
      return [
        "success" => false,
        "error" => "Invalid username or password."
      ];
    }
  } else {
    // Something went wrong preparing the statement
    return [
      "success" => false,
      "error" => "Error preparing statement."
    ];
  }

  // Close the statement and connection (important for security)
  $stmt->close();
  $conn->close();
}


// Example Usage (for demonstration purposes - DON'T include this in a live website)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $username = $_POST["username"];
  $password = $_POST["password"];

  $loginResult = loginUser($username, $password);

  if ($loginResult["success"] == true) {
    // User logged in successfully
    echo "Login successful!  User ID: " . $loginResult["userId"];
    // You would typically redirect to a protected area or store the user's ID
    // in a session.  For this example, we'll just print the ID.
  } else {
    // Login failed
    echo "Login failed: " . $loginResult["error"];
  }
}
?>
